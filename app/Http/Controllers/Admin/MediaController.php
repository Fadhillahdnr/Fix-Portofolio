<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Services\CloudinaryMediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function store(Request $r, Project $project, CloudinaryMediaService $cloud)
    {
        $d = $r->validate(['file' => ['required', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/avif,video/mp4,video/webm', 'max:51200'], 'alt_text' => ['required', 'string', 'max:180'], 'caption' => ['nullable', 'string', 'max:300']]);
        $u = $cloud->upload($r->file('file'), 'projects/'.$project->id);
        $project->media()->create($u + ['alt_text' => $d['alt_text'], 'caption' => $d['caption'] ?? null]);

        return back()->with('success', 'Media berhasil diunggah ke Cloudinary.');
    }

    public function destroy(Project $project, ProjectMedia $medium, CloudinaryMediaService $cloud)
    {
        abort_unless($medium->project_id === $project->id, 404);
        $cloud->destroy($medium->public_id, $medium->media_type);
        $medium->delete();

        return back()->with('success', 'Media dihapus.');
    }
}
