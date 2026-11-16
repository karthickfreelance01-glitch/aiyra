<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::withCount('images')
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('title', 'like', "%$s%")->orWhere('location', 'like', "%$s%")))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->ordered()
            ->paginate(12)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => new Project(['status' => 'completed', 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['slug'] = Project::uniqueSlug($data['title']);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = upload_image($request->file('cover_image'), 'projects');
        }

        $project = Project::create($data);
        $this->saveGallery($request, $project);

        return redirect()->route('admin.projects.index')->with('success', 'Project "' . $project->title . '" uploaded successfully.');
    }

    public function edit(Project $project)
    {
        $project->load('images');
        return view('admin.projects.form', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validated($request, false);
        if ($data['title'] !== $project->title) {
            $data['slug'] = Project::uniqueSlug($data['title'], $project->id);
        }

        if ($request->hasFile('cover_image')) {
            delete_upload($project->cover_image);
            $data['cover_image'] = upload_image($request->file('cover_image'), 'projects');
        }

        $project->update($data);
        $this->saveGallery($request, $project);

        return redirect()->route('admin.projects.edit', $project)->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        delete_upload($project->cover_image);
        foreach ($project->images as $img) {
            delete_upload($img->image);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }

    public function destroyImage(Project $project, ProjectImage $image)
    {
        abort_unless($image->project_id === $project->id, 404);
        delete_upload($image->image);
        $image->delete();

        return request()->expectsJson()
            ? response()->json(['ok' => true])
            : back()->with('success', 'Image removed.');
    }

    public function toggle(Project $project)
    {
        $field = request('field') === 'is_featured' ? 'is_featured' : 'is_active';
        $project->update([$field => !$project->$field]);

        return response()->json(['ok' => true, 'value' => $project->$field]);
    }

    private function validated(Request $request, bool $creating): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:190',
            'category' => 'required|string|max:60',
            'status' => 'required|in:' . implode(',', array_keys(Project::STATUSES)),
            'location' => 'nullable|string|max:190',
            'client' => 'nullable|string|max:190',
            'area' => 'nullable|string|max:60',
            'year' => 'nullable|string|max:10',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'cover_image' => ($creating ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:5120',
            'gallery' => 'nullable|array|max:30',
            'gallery.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'cover_image.required' => 'Please upload a cover image for the project.',
            'gallery.*.max' => 'Each gallery image must be under 5 MB.',
        ]);

        unset($data['gallery'], $data['cover_image']);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }

    private function saveGallery(Request $request, Project $project): void
    {
        if (!$request->hasFile('gallery')) {
            return;
        }
        $order = (int) $project->images()->max('sort_order');
        foreach ($request->file('gallery') as $file) {
            $project->images()->create([
                'image' => upload_image($file, 'projects'),
                'sort_order' => ++$order,
            ]);
        }
    }
}
