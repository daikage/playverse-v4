<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProcessComicUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Project $project, public string $sourcePathOnDisk, public string $disk = 'public')
    {
    }

    public function handle(): void
    {
        // Determine format by extension
        $ext = strtolower(pathinfo($this->sourcePathOnDisk, PATHINFO_EXTENSION));
        $pagesDir = "comics/{$this->project->id}/pages";
        $pageIndex = 1;
        $pagePaths = [];

        if (in_array($ext, ['cbz', 'zip'])) {
            $pagePaths = $this->processCbz($pagesDir, $pageIndex);
        } elseif (in_array($ext, ['cbr', 'rar'])) {
            // NOTE: Requires rar extension or external tooling; stubbed fallback
            // In production, use unrar or a service to extract.
            // For now, we skip processing and keep original.
            $pagePaths = []; // TODO
        } elseif ($ext === 'pdf') {
            $pagePaths = $this->processPdf($pagesDir, $pageIndex);
        } else {
            // Assume raw image stack folder prefix stored in sourcePathOnDisk
            $pagePaths = $this->processImageStack($pagesDir, $pageIndex);
        }

        // Save manifest
        $this->project->pages = $pagePaths;
        $this->project->save();
    }

    protected function processCbz(string $pagesDir, int $pageIndex): array
    {
        $zip = new ZipArchive();
        $tmpPath = Storage::disk($this->disk)->path($this->sourcePathOnDisk);

        $extracted = [];
        if ($zip->open($tmpPath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (! preg_match('/\.(jpe?g|png|webp)$/i', $name)) {
                    continue;
                }
                $stream = $zip->getStream($name);
                if (! $stream) {
                    continue;
                }
                $contents = stream_get_contents($stream);
                fclose($stream);

                $path = sprintf('%s/%04d.webp', $pagesDir, $pageIndex++);
                $webp = $this->toWebp($contents);
                Storage::disk($this->disk)->put($path, $webp, ['visibility' => 'public']);
                $extracted[] = $path;
            }
            $zip->close();
        }

        return $extracted;
    }

    protected function processPdf(string $pagesDir, int $pageIndex): array
    {
        // Requires Imagick + Ghostscript for robust conversion
        $pdfPath = Storage::disk($this->disk)->path($this->sourcePathOnDisk);
        $paths = [];

        if (class_exists(\Imagick::class)) {
            $imagick = new \Imagick();
            $imagick->setResolution(180, 180);
            $imagick->readImage($pdfPath);
            $imagick->setImageFormat('webp');
            foreach ($imagick as $i => $image) {
                /** @var \Imagick $image */
                $image->setImageFormat('webp');
                $image->setImageCompressionQuality(82);
                $blob = $image->getImageBlob();
                $path = sprintf('%s/%04d.webp', $pagesDir, $pageIndex++);
                Storage::disk($this->disk)->put($path, $blob, ['visibility' => 'public']);
                $paths[] = $path;
            }
            $imagick->clear();
            $imagick->destroy();
        }

        return $paths;
        // For environments without Imagick, consider using a queue worker
        // with the proper extensions or an external microservice to convert PDFs.
    }

    protected function processImageStack(string $pagesDir, int $pageIndex): array
    {
        // Treat sourcePathOnDisk as a folder prefix; list objects under it
        $paths = [];
        $all = Storage::disk($this->disk)->files($this->sourcePathOnDisk);

        sort($all, SORT_NATURAL);

        foreach ($all as $file) {
            if (! preg_match('/\.(jpe?g|png|webp)$/i', $file)) {
                continue;
            }
            $contents = Storage::disk($this->disk)->get($file);
            $path = sprintf('%s/%04d.webp', $pagesDir, $pageIndex++);
            $webp = $this->toWebp($contents);
            Storage::disk($this->disk)->put($path, $webp, ['visibility' => 'public']);
            $paths[] = $path;
        }

        return $paths;
    }

    protected function toWebp(string $binary): string
    {
        if (class_exists(\Imagick::class)) {
            $img = new \Imagick();
            $img->readImageBlob($binary);
            $img->setImageFormat('webp');
            $img->setImageCompressionQuality(82);
            $out = $img->getImageBlob();
            $img->clear();
            $img->destroy();
            return $out;
        }

        // Fallback: return original image bytes (non-webp)
        // Consider adding intervention/image for broader support.
        return $binary;
    }
}
