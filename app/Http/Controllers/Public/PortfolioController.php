<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function home()
    {
        $projects = Project::published()
            ->with(['categories', 'skills'])
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        return view('pages.home', [
            'profile' => Profile::first(),
            'projects' => $projects,
            'skills' => Skill::query()
                ->where('is_featured', true)
                ->orderBy('sort_order')
                ->get(),
            'services' => Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'metrics' => [
                ['value' => Project::published()->count(), 'label' => 'Project records'],
                ['value' => Skill::count(), 'label' => 'Technology nodes'],
                ['value' => Category::count(), 'label' => 'Engineering domains'],
            ],
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'profile' => Profile::first(),
            'experiences' => Experience::query()
                ->orderByDesc('start_date')
                ->get(),
            'skills' => Skill::query()
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function projects(Request $request)
    {
        $projects = Project::published()->with(['categories', 'skills']);

        if ($request->filled('q')) {
            $search = $request->string('q');

            $projects->where(function ($query) use ($search): void {
                $query
                    ->where('title', 'like', '%'.$search.'%')
                    ->orWhere('summary', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('category')) {
            $projects->whereHas('categories', function ($query) use ($request): void {
                $query->where('slug', $request->string('category'));
            });
        }

        return view('pages.projects.index', [
            'projects' => $projects
                ->orderBy('sort_order')
                ->latest('published_at')
                ->paginate(9)
                ->withQueryString(),
            'categories' => Category::query()
                ->whereHas('projects', fn ($query) => $query->published())
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function show(Project $project)
    {
        abort_unless(
            $project->status === 'published'
                && (! $project->published_at || $project->published_at->isPast()),
            404,
        );

        $project->load(['categories', 'skills', 'media']);

        $nextProject = Project::published()
            ->with(['categories', 'skills'])
            ->whereKeyNot($project)
            ->orderBy('sort_order')
            ->first();

        return view('pages.projects.show', [
            'project' => $project,
            'nextProject' => $nextProject,
        ]);
    }

    public function lab()
    {
        $labCategories = [
            'ai',
            'ai-ml',
            'data',
            'embedded',
            'iot',
            'machine-learning',
        ];

        return view('pages.lab', [
            'projects' => Project::published()
                ->with(['categories', 'skills'])
                ->whereHas('categories', function ($query) use ($labCategories): void {
                    $query->whereIn('slug', $labCategories);
                })
                ->orderBy('sort_order')
                ->get(),
            'skillSectors' => Skill::query()
                ->orderBy('sort_order')
                ->get()
                ->groupBy(fn (Skill $skill) => $skill->category ?: 'Engineering'),
        ]);
    }

    public function services()
    {
        return view('pages.services', [
            'services' => Service::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'profile' => Profile::first(),
            'socials' => SocialLink::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
