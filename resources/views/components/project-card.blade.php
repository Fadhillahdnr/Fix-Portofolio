<article
    class="archive-row"
    data-project-row
    data-category="{{ $project->categories->pluck('slug')->join(' ') }}"
>
    <a href="{{ route('projects.show', $project) }}">
        <div class="archive-row__index">
            <span>{{ str_pad($project->sort_order + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <small>PRJ</small>
        </div>
        <div class="archive-row__identity">
            <p>{{ $project->categories->pluck('name')->join(' / ') ?: 'Engineering' }}</p>
            <h3>{{ $project->title }}</h3>
            <span>{{ $project->summary }}</span>
        </div>
        <dl class="archive-row__meta">
            <div>
                <dt>Role</dt>
                <dd>{{ $project->role ?: 'Developer' }}</dd>
            </div>
            <div>
                <dt>Stack</dt>
                <dd>{{ $project->skills->take(3)->pluck('name')->join(' / ') ?: 'Web system' }}</dd>
            </div>
            <div>
                <dt>Entry</dt>
                <dd>{{ $project->published_at?->format('Y') ?: 'Archive' }}</dd>
            </div>
        </dl>
        <div class="archive-row__preview">
            @if ($project->cover_path)
                <img
                    src="{{ $project->cover_path }}"
                    alt=""
                    width="480"
                    height="320"
                    loading="lazy"
                >
            @else
                <span aria-hidden="true">{{ strtoupper(substr($project->slug, 0, 3)) }}</span>
            @endif
        </div>
        <svg class="archive-row__arrow" aria-hidden="true" viewBox="0 0 24 24">
            <path d="M5 12h14M14 7l5 5-5 5" />
        </svg>
    </a>
</article>
