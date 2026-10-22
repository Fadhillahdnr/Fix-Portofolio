<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Services\CloudinaryMediaService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        return view('admin.projects.index', ['projects' => Project::with('categories')->latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => new Project, 'categories' => Category::orderBy('name')->get(), 'skills' => Skill::orderBy('name')->get()]);
    }

    public function store(ProjectRequest $r, CloudinaryMediaService $media)
    {
        $p = DB::transaction(function () use ($r, $media) {
            $d = $r->safe()->except(['cover', 'categories', 'skills']);
            $d['created_by'] = $d['updated_by'] = $r->user()->id;
            if ($r->hasFile('cover')) {
                $u = $media->upload($r->file('cover'), 'covers');
                $d['cover_path'] = $u['path'];
                $d['cover_public_id'] = $u['public_id'];
            }$p = Project::create($d);
            $p->categories()->sync($r->input('categories', []));
            $p->skills()->sync($r->input('skills', []));

            return $p;
        });
        Cache::flush();

        return redirect()->route('admin.projects.edit', $p)->with('success', 'Proyek berhasil dibuat.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', compact('project') + ['categories' => Category::orderBy('name')->get(), 'skills' => Skill::orderBy('name')->get()]);
    }

    public function update(ProjectRequest $r, Project $project, CloudinaryMediaService $media)
    {
        DB::transaction(function () use ($r, $project, $media) {
            $d = $r->safe()->except(['cover', 'categories', 'skills']);
            $d['updated_by'] = $r->user()->id;
            if ($r->hasFile('cover')) {
                $u = $media->upload($r->file('cover'), 'covers');
                $media->destroy($project->cover_public_id);
                $d['cover_path'] = $u['path'];
                $d['cover_public_id'] = $u['public_id'];
            }$project->update($d);
            $project->categories()->sync($r->input('categories', []));
            $project->skills()->sync($r->input('skills', []));
        });
        Cache::flush();

        return back()->with('success', 'Perubahan disimpan.');
    }

    public function destroy(Project $project, CloudinaryMediaService $media)
    {
        $media->destroy($project->cover_public_id);
        foreach ($project->media as $item) {
            $media->destroy($item->public_id, $item->media_type);
        }$project->delete();
        Cache::flush();

        return redirect()->route('admin.projects.index')->with('success','Proyek dihapus.');
    }
}
