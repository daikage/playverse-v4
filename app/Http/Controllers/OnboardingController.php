<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OnboardingController
{
    public function createAccountForm()
    {
        return view('onboarding.register');
    }

    public function createAccount(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:191', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8', 'max:191'],
            'studio_name' => ['required', 'string', 'max:120'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'author',
        ]);

        $slug = Str::slug($data['studio_name']);
        $slug = $this->uniqueAuthorSlug($slug);

        $author = Author::create([
            'name' => $data['studio_name'],
            'slug' => $slug,
            'studio_uuid' => Str::uuid(),
            'verification_status' => 'pending', // Stage 3 gate
            'owner_user_id' => $user->id,
        ]);

        // Track onboarding session
        session(['onboarding_author_id' => $author->id]);

        // Go to Stage 2: Studio Identity
        return redirect()->route('onboarding.studio.form');
    }

    public function studioProfileForm(Request $request)
    {
        $author = $this->getOnboardingAuthorOrAbort();

        return view('onboarding.studio', compact('author'));
    }

    public function studioProfile(Request $request)
    {
        $author = $this->getOnboardingAuthorOrAbort();

        $data = $request->validate([
            'mission_statement' => ['nullable', 'string', 'max:2000'],
            'links.github' => ['nullable', 'url'],
            'links.artstation' => ['nullable', 'url'],
            'links.itch' => ['nullable', 'url'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $author->logo_path = $path;
        }

        $author->mission_statement = $data['mission_statement'] ?? null;
        $author->links = $data['links'] ?? null;
        $author->save();

        // Stage 3: Pending Review
        return redirect()->route('onboarding.pending');
    }

    public function pending()
    {
        $author = $this->getOnboardingAuthorOrAbort();

        return view('onboarding.pending', compact('author'));
    }

    protected function getOnboardingAuthorOrAbort(): Author
    {
        $id = session('onboarding_author_id');
        abort_unless($id, 404);

        return Author::findOrFail($id);
    }

    protected function uniqueAuthorSlug(string $base): string
    {
        $slug = $base;
        $i = 1;
        while (Author::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
