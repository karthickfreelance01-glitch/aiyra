<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Project;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function home()
    {
        $featured = Project::active()->where('is_featured', true)->ordered()->take(6)->get();
        if ($featured->count() < 3) {
            $featured = Project::active()->ordered()->take(6)->get();
        }
        return view('pages.home', compact('featured'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.services');
    }

    public function projects(Request $request)
    {
        $projects = Project::active()->ordered()->get();
        $categories = $projects->pluck('category')->unique()->values();
        return view('pages.projects', compact('projects', 'categories'));
    }

    public function project(Project $project)
    {
        abort_unless($project->is_active, 404);
        $project->load('images');
        $related = Project::active()->where('id', '!=', $project->id)
            ->where('category', $project->category)->ordered()->take(3)->get();
        if ($related->count() < 3) {
            $related = Project::active()->where('id', '!=', $project->id)->ordered()->take(3)->get();
        }
        $next = Project::active()->where('id', '!=', $project->id)->ordered()->first();
        return view('pages.project-show', compact('project', 'related', 'next'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function enquiry(Request $request)
    {
        // simple honeypot
        if ($request->filled('website')) {
            return $request->expectsJson()
                ? response()->json(['ok' => true, 'message' => 'Thank you!'])
                : back()->with('success', 'Thank you!');
        }

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s()]{8,20}$/'],
            'email' => 'nullable|email|max:150',
            'service' => 'nullable|string|max:120',
            'location' => 'nullable|string|max:150',
            'message' => 'nullable|string|max:3000',
            'source' => 'nullable|string|max:20',
            'project_id' => 'nullable|integer|exists:projects,id',
        ], [
            'phone.regex' => 'Please enter a valid phone number.',
        ]);

        $data['source'] = $data['source'] ?? 'contact';
        $data['ip'] = $request->ip();
        Enquiry::create($data);

        $msg = 'Thank you ' . e($data['name']) . '! Our team will call you shortly.';

        return $request->expectsJson()
            ? response()->json(['ok' => true, 'message' => $msg])
            : back()->with('success', $msg);
    }
}
