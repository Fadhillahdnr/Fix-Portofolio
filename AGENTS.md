# AGENTS.md

> Project-wide instructions for AI coding agents.
>
> This file is the primary development guideline for every AI agent working in this repository.
> The agent must read and follow these rules before creating, modifying, refactoring, or reviewing code.

---

# 1. Purpose

The purpose of this document is to ensure that every generated change is:

- Correct
- Secure
- Readable
- Maintainable
- Consistent
- Easy to debug
- Easy to review
- Easy to extend
- Appropriate for a professional Laravel codebase

The source code in this repository is written for humans first.

Do **not** optimize source files for minimum line count.

---

# 2. Non-Negotiable Rule: Never Minify Source Code

The AI agent MUST NOT generate minified or compressed source code.

Never collapse:

- Multiple HTML elements into one line
- Multiple Blade directives into one line
- Blade directives and HTML into the same unreadable line
- Multiple PHP statements into one line
- Complex conditional statements into one line
- Large JavaScript/TypeScript expressions into unreadable one-liners
- CSS blocks into minified syntax

## Forbidden

```blade
@section('content')<article><header class="page-hero"><h1>{{ $project->title }}</h1></header></article>@endsection
```

```blade
@if($project->demo_url)<a href="{{ $project->demo_url }}">Lihat demo</a>@endif
```

```blade
@foreach($projects as $project)<div>{{ $project->title }}</div>@endforeach
```

## Required

```blade
@section('content')
    <article>
        <header class="page-hero">
            <h1>
                {{ $project->title }}
            </h1>
        </header>
    </article>
@endsection
```

```blade
@if ($project->demo_url)
    <a
        href="{{ $project->demo_url }}"
        target="_blank"
        rel="noopener noreferrer"
    >
        Lihat demo
    </a>
@endif
```

```blade
@foreach ($projects as $project)
    <div>
        {{ $project->title }}
    </div>
@endforeach
```

---

# 3. Decision Priority

When implementation choices conflict, use this priority:

1. Correctness
2. Security
3. Readability
4. Maintainability
5. Consistency
6. Testability
7. Performance
8. Compactness

Never sacrifice readability only to reduce the number of lines.

---

# 4. General Formatting Rules

The AI agent must:

- Use consistent indentation.
- Use blank lines between logical blocks.
- Keep one logical responsibility per block.
- Avoid excessively long lines.
- Avoid dense nested logic.
- Prefer explicit code over clever code.
- Preserve existing project conventions when they are already readable.
- Keep formatting stable when modifying existing code.
- Avoid reformatting unrelated areas.

The code should remain easy to visually scan six months later.

---

# 5. Blade Formatting

## 5.1 Blade Directives Must Be Readable

Use a space after Blade control keywords.

### Correct

```blade
@if ($condition)
@endif

@foreach ($items as $item)
@endforeach

@while ($condition)
@endwhile

@switch ($status)
@endswitch
```

### Incorrect

```blade
@if($condition)
@foreach($items as $item)
```

---

## 5.2 Blade Directives Must Be on Their Own Lines

### Correct

```blade
@if ($project->repository_url)
    <a
        href="{{ $project->repository_url }}"
        target="_blank"
        rel="noopener noreferrer"
        class="btn ghost"
    >
        Repository ↗
    </a>
@endif
```

### Incorrect

```blade
@if($project->repository_url)<a href="{{ $project->repository_url }}">Repository</a>@endif
```

---

# 6. HTML Formatting

## 6.1 Multi-Line Attributes

If an HTML element has several attributes or becomes difficult to read, place each attribute on its own line.

### Correct

```blade
<input
    id="title"
    name="title"
    type="text"
    value="{{ old('title', $project->title ?? '') }}"
    class="w-full rounded-lg border border-slate-700 bg-slate-900 px-4 py-3"
>
```

### Avoid

```blade
<input id="title" name="title" type="text" value="{{ old('title', $project->title ?? '') }}" class="w-full rounded-lg border border-slate-700 bg-slate-900 px-4 py-3">
```

A simple element may remain on one line when it is still clearly readable.

Example:

```html
<span class="text-sm">Draft</span>
```

The goal is readability, not blindly placing every attribute on a new line.

---

# 7. Nested HTML Structure

Nested HTML must use clear indentation.

### Correct

```blade
<section class="section">
    <div class="container">
        <header class="section-header">
            <p class="eyebrow">
                Projects
            </p>

            <h2>
                Featured Projects
            </h2>
        </header>

        <div class="project-grid">
            @foreach ($projects as $project)
                <article class="project-card">
                    ...
                </article>
            @endforeach
        </div>
    </div>
</section>
```

Do not collapse nested structures into one line.

---

# 8. Blank Lines

Use blank lines to separate logical groups.

### Correct

```blade
<div>
    <label for="title">
        Judul
    </label>

    <input
        id="title"
        name="title"
        type="text"
    >

    @error('title')
        <p class="text-red-400">
            {{ $message }}
        </p>
    @enderror
</div>
```

Blank lines are encouraged when they improve visual structure.

---

# 9. Blade Expression Rules

Avoid complex business logic inside Blade.

Blade should focus primarily on presentation.

## Avoid

```blade
@if (
    $project->status === 'published'
    && $project->published_at !== null
    && $project->skills->count() >= 3
    && auth()->user()?->can('view', $project)
)
```

For non-trivial logic, prefer moving the decision into:

- Controller
- Service
- Action
- ViewModel
- Presenter
- Policy
- Model accessor
- Dedicated helper when truly appropriate

---

# 10. Blade Loop Rules

Loops must remain visually clear.

### Correct

```blade
@foreach ($project->media as $media)
    <figure>
        @if ($media->media_type === 'video')
            <video
                controls
                preload="metadata"
                aria-label="{{ $media->alt_text }}"
            >
                <source src="{{ $media->path }}">
            </video>
        @else
            <img
                src="{{ $media->path }}"
                alt="{{ $media->alt_text }}"
                width="{{ $media->width ?: 1200 }}"
                height="{{ $media->height ?: 800 }}"
                loading="lazy"
            >
        @endif

        @if ($media->caption)
            <figcaption>
                {{ $media->caption }}
            </figcaption>
        @endif
    </figure>
@endforeach
```

---

# 11. Blade Forms

Forms should be structured and easy to maintain.

### Preferred

```blade
<div>
    <label
        for="title"
        class="mb-2 block"
    >
        Judul
    </label>

    <input
        id="title"
        name="title"
        type="text"
        value="{{ old('title', $project->title ?? '') }}"
        class="w-full rounded-lg border border-slate-700 bg-slate-900 px-4 py-3"
    >

    @error('title')
        <p class="mt-1 text-red-400">
            {{ $message }}
        </p>
    @enderror
</div>
```

---

# 12. Conditional Attributes

Use Laravel Blade helpers when appropriate.

### Correct

```blade
<input
    name="is_featured"
    type="checkbox"
    value="1"
    @checked(old('is_featured', $project->is_featured ?? false))
>
```

Prefer:

- `@checked`
- `@selected`
- `@disabled`
- `@readonly`

when they improve readability.

---

# 13. PHP Formatting

All PHP code should follow PSR-12 unless the project defines a stricter convention.

## Correct

```php
public function store(StoreProjectRequest $request)
{
    $project = Project::create([
        'title' => $request->title,
        'slug' => $request->slug,
        'summary' => $request->summary,
    ]);

    return redirect()
        ->route('projects.index')
        ->with('success', 'Project berhasil dibuat.');
}
```

Avoid compressed methods.

---

# 14. PHP Arrays

Use multi-line formatting for arrays with multiple entries.

```php
$data = [
    'title' => $request->title,
    'slug' => $request->slug,
    'summary' => $request->summary,
    'status' => $request->status,
    'is_featured' => $request->boolean('is_featured'),
];
```

Do not write large arrays on one line.

---

# 15. Long Method Calls

Break fluent calls into readable lines.

### Correct

```php
return redirect()
    ->route('projects.index')
    ->with('success', 'Project berhasil dibuat.');
```

### Avoid

```php
return redirect()->route('projects.index')->with('success', 'Project berhasil dibuat.');
```

A short method chain may remain on one line if it is clearly readable.

---

# 16. Laravel Architecture

Prefer clear separation of responsibilities.

```text
Route
  ↓
Controller
  ↓
Form Request
  ↓
Service / Action
  ↓
Model / Repository
  ↓
Database
```

Not every CRUD operation requires a service or repository.

Do not over-engineer simple functionality.

Introduce an abstraction only when it provides a clear benefit.

---

# 17. Controllers

Controllers should coordinate HTTP/application flow.

They should not contain large amounts of domain or business logic.

### Preferred

```php
public function store(
    StoreProjectRequest $request,
    ProjectService $projectService
) {
    $project = $projectService->create(
        $request->validated()
    );

    return redirect()
        ->route('admin.projects.show', $project)
        ->with('success', 'Project created successfully.');
}
```

A small, simple CRUD operation may remain directly in the controller when introducing a service would add unnecessary complexity.

---

# 18. Validation

Prefer Form Requests for non-trivial request validation.

Example:

```php
final class StoreProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'summary' => [
                'required',
                'string',
                'max:500',
            ],
            'demo_url' => [
                'nullable',
                'url',
            ],
        ];
    }
}
```

Validation must be server-side even if client-side validation also exists.

---

# 19. Authorization

Always consider whether an action requires authorization.

Use:

- Policies
- Gates
- Middleware
- Role/permission checks

Prefer centralized authorization over duplicated checks.

Never rely only on hiding UI buttons.

---

# 20. Eloquent Queries

Write expressive, readable Eloquent queries.

### Correct

```php
$projects = Project::query()
    ->with([
        'skills',
        'media',
    ])
    ->where('status', 'published')
    ->latest('published_at')
    ->paginate(12);
```

Avoid large query chains compressed into one line.

---

# 21. N+1 Queries

Always consider N+1 query problems.

Prefer eager loading when relationships will be used.

```php
$project->load([
    'skills',
    'media',
]);
```

Do not add eager loading automatically when it is unnecessary.

---

# 22. Database Transactions

Use database transactions when multiple dependent writes must succeed or fail together.

Example:

```php
DB::transaction(function () use ($data): void {
    // Related writes that must be atomic.
});
```

Do not wrap simple independent reads in unnecessary transactions.

---

# 23. Models

Models should define:

- Relationships
- Casts
- Fillable/guarded strategy
- Useful accessors/mutators
- Query scopes when they improve reuse

Avoid turning Eloquent models into giant business-service classes.

---

# 24. Mass Assignment

Always handle mass assignment intentionally.

Never blindly use:

```php
Model::create($request->all());
```

Prefer:

```php
Project::create($request->validated());
```

or explicitly mapped data.

---

# 25. Routes

Keep routes readable.

### Correct

```php
Route::get('/projects', [ProjectController::class, 'index'])
    ->name('projects.index');

Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])
    ->name('projects.show');
```

Use route model binding when appropriate.

---

# 26. Tailwind CSS

Tailwind class lists can be long.

Do not collapse the complete HTML element just because the `class` attribute is long.

### Preferred

```blade
<button
    type="submit"
    class="inline-flex items-center rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700"
>
    Simpan
</button>
```

Do not extract utilities into custom CSS solely to shorten markup unless doing so improves reuse or consistency.

---

# 27. CSS

Do not minify development source CSS.

### Correct

```css
.project-card {
    display: grid;
    gap: 1rem;
}

.project-card__title {
    font-size: 1.25rem;
    font-weight: 600;
}
```

### Incorrect

```css
.project-card{display:grid;gap:1rem}.project-card__title{font-size:1.25rem;font-weight:600}
```

Use existing naming conventions in the project.

---

# 28. JavaScript

Readable concise code is allowed.

### Good

```javascript
const project = projects.find((item) => item.slug === currentSlug);
```

When logic becomes complex, expand it.

```javascript
const project = projects.find((item) => {
    return (
        item.slug === currentSlug
        && item.status === 'published'
    );
});
```

Avoid clever one-liners that obscure intent.

---

# 29. TypeScript

Use meaningful types.

```typescript
interface Project {
    id: number;
    title: string;
    slug: string;
    summary: string;
    isFeatured: boolean;
}
```

Avoid `any` unless there is a clear reason.

Prefer:

- Interfaces
- Type aliases
- Union types
- Generic types

when they improve correctness and developer experience.

---

# 30. Naming

Use descriptive and natural names.

### Good

```text
projectService
featuredProjects
publishedProjects
projectMedia
projectRepository
uploadResult
```

### Avoid

```text
x
obj
thing
tmp
foo
aaa
data2
resultFinalFinal
```

Generic names like `data` are acceptable only in very small, obvious scopes.

---

# 31. Function and Method Size

Prefer small, focused functions.

A function should ideally have one main responsibility.

When a method becomes difficult to understand:

- Extract meaningful operations
- Reduce nesting
- Use early returns
- Move domain logic into appropriate services/actions

Do not split code into tiny functions merely to reduce line count.

---

# 32. Early Returns

Use early returns when they reduce nesting.

### Preferred

```php
if (! $project->isPublished()) {
    abort(404);
}

return view('projects.show', [
    'project' => $project,
]);
```

Avoid unnecessary deeply nested `if` structures.

---

# 33. Comments

Comments should explain **why**, not repeat what the code already says.

### Good

```php
// Load relationships here to prevent N+1 queries in the project gallery.
$project->load([
    'skills',
    'media',
]);
```

### Poor

```php
// Get the project.
$project = Project::find($id);
```

Prefer self-explanatory code over excessive comments.

---

# 34. Error Handling

Errors must be handled intentionally.

Prefer:

- Specific validation messages
- Appropriate exceptions
- User-friendly UI messages
- Appropriate logs
- Correct HTTP status codes

Do not silently swallow exceptions.

### Forbidden

```php
try {
    // ...
} catch (Throwable $exception) {
}
```

If an error is intentionally ignored, explain why.

---

# 35. Logging

Logs should provide useful diagnostic context.

Do not log:

- Passwords
- Access tokens
- API keys
- Sensitive personal information
- Secrets

Avoid noisy logs inside high-frequency loops unless required for debugging.

---

# 36. Security Rules

Security takes priority over convenience.

Always consider:

- Authentication
- Authorization
- CSRF
- XSS
- SQL injection
- Mass assignment
- File upload security
- URL validation
- Secret management
- Access control
- Session security

Never hardcode:

- Passwords
- API keys
- Tokens
- Database credentials
- Production secrets

Use environment variables/configuration.

---

# 37. File Upload Security

For uploads:

- Validate MIME/type
- Validate extension
- Validate size
- Generate safe filenames
- Avoid trusting original filenames
- Store through Laravel Storage
- Restrict executable file types
- Protect private files appropriately

Example:

```php
'image' => [
    'nullable',
    'image',
    'mimes:jpg,jpeg,png,webp',
    'max:5120',
],
```

---

# 38. API Design

For REST APIs, prefer consistent resource semantics.

```text
GET     /api/projects
GET     /api/projects/{project}
POST    /api/projects
PUT     /api/projects/{project}
PATCH   /api/projects/{project}
DELETE  /api/projects/{project}
```

Use meaningful HTTP status codes.

```text
200 OK
201 Created
204 No Content
400 Bad Request
401 Unauthorized
403 Forbidden
404 Not Found
409 Conflict
422 Unprocessable Entity
500 Internal Server Error
```

---

# 39. API Responses

API responses should be consistent.

Do not expose internal implementation details unnecessarily.

For larger APIs, prefer Laravel API Resources when they improve response consistency.

---

# 40. Components and Reusability

Extract reusable components when:

- Markup is repeated
- A block has a clear responsibility
- A component improves maintainability

Do not create components for every trivial HTML element.

Avoid:

- Giant Blade templates
- Giant Vue components
- Giant Angular components
- Duplicate markup
- Components with unrelated responsibilities

---

# 41. Accessibility

Use semantic HTML.

Always consider:

- Labels
- Alt text
- Keyboard access
- Focus state
- Button/link semantics
- Form validation feedback
- Heading hierarchy

### Correct

```blade
<label for="title">
    Judul
</label>

<input
    id="title"
    name="title"
    type="text"
>
```

Do not use clickable `<div>` elements when `<button>` or `<a>` is semantically correct.

---

# 42. Images and Media

For informative images:

- Provide meaningful alt text
- Provide width/height when known
- Use lazy loading when appropriate
- Optimize file size
- Prefer modern formats when appropriate

Example:

```blade
<img
    src="{{ $media->path }}"
    alt="{{ $media->alt_text }}"
    width="{{ $media->width ?: 1200 }}"
    height="{{ $media->height ?: 800 }}"
    loading="lazy"
>
```

Decorative images may use an empty alt attribute.

---

# 43. Responsive Design

Every public page must consider:

- Mobile
- Tablet
- Desktop
- Large desktop

Do not implement layouts that only work at one viewport size.

Check for:

- Overflow
- Touch targets
- Text readability
- Responsive images
- Navigation
- Grid collapse
- Modal behavior

---

# 44. SEO for Portfolio Pages

Public portfolio pages should consider:

- Page title
- Meta description
- Semantic headings
- Canonical URL
- Open Graph metadata
- Social preview image
- Descriptive alt text
- Clean URLs
- Crawlability
- Structured content

Avoid keyword stuffing.

---

# 45. Portfolio-Specific Content Structure

Project detail pages should be easy to scan.

Recommended structure:

```text
Project Hero
Overview
Role
Timeline
Tech Stack
Problem
Approach / Solution
Key Features
Architecture
Challenges
Technical Decisions
Result / Impact
Gallery
Demo
Repository
Lessons Learned
Future Improvements
```

Not every project must include every section.

Use only sections that add meaningful information.

---

# 46. Project Data Structure

Prefer structured project fields instead of storing all content in a single unstructured description.

Potential fields:

```text
title
slug
summary
description
problem
solution
result
role
timeline
status
is_featured
published_at
demo_url
repository_url
cover_path
```

Related data may include:

```text
skills
media
categories
project_links
```

---

# 47. Performance

Consider performance during implementation.

Check for:

- N+1 queries
- Repeated database queries
- Large unpaginated result sets
- Oversized images
- Duplicate API calls
- Expensive loops
- Unnecessary frontend re-renders
- Missing pagination
- Missing indexes for relevant database workloads

Do not prematurely optimize without evidence.

Readability remains important.

---

# 48. Caching

Use caching only when there is a clear benefit.

Potential candidates:

- Public project lists
- Skill lists
- Site settings
- Expensive aggregate queries

Ensure cache invalidation is considered.

Do not cache everything by default.

---

# 49. Database Indexing

When creating or modifying schema, consider indexes for:

- Foreign keys
- Frequently filtered fields
- Frequently sorted fields
- Unique identifiers
- Slugs
- Composite query patterns

Do not add indexes blindly.

---

# 50. Migrations

Migrations must be:

- Clear
- Reversible where practical
- Safe for existing data
- Properly indexed
- Explicit about nullable/default behavior

Do not edit an old migration that has already been used in shared environments unless the project workflow explicitly allows it.

Prefer a new migration for subsequent schema changes.

---

# 51. Seeders and Factories

Use factories/seeders for:

- Development data
- Testing
- Demo portfolio content

Avoid placing production secrets or private information inside seeders.

---

# 52. Testing

When changing behavior, consider relevant tests.

Preferred test categories:

- Feature tests
- Unit tests
- HTTP tests
- Validation tests
- Authorization tests
- Database tests

When fixing a bug, add a regression test when practical.

Do not remove tests merely to make a failing build pass.

---

# 53. Test Readability

Tests must also remain readable.

Prefer descriptive names.

```php
public function test_admin_can_publish_a_project(): void
{
    // Arrange

    // Act

    // Assert
}
```

Use Arrange / Act / Assert conceptually when useful.

Comments are optional if the test is already clear.

---

# 54. Git Changes

Keep diffs focused.

When implementing a task:

- Change only necessary files
- Avoid unrelated refactors
- Avoid mass formatting unrelated code
- Avoid adding unnecessary dependencies
- Preserve existing behavior outside the requested scope

---

# 55. Git Commit Messages

Prefer Conventional Commit-style messages when the repository follows that convention.

Examples:

```text
feat: add project media gallery
fix: prevent duplicate project slugs
refactor: extract project publishing action
docs: update portfolio project documentation
style: format project detail blade
test: add project publishing feature test
```

Avoid vague messages:

```text
update
fix
test
done
final
aaa
changes
```

---

# 56. Dependency Management

Before adding a dependency:

1. Check whether the project already has equivalent functionality.
2. Consider maintenance status.
3. Consider bundle/runtime impact.
4. Consider security.
5. Prefer framework/native features when sufficient.

Do not add packages for trivial tasks.

---

# 57. Existing Code Preservation

When modifying existing code:

1. Inspect the surrounding implementation first.
2. Follow established naming conventions.
3. Preserve behavior not related to the task.
4. Avoid unnecessary file rewrites.
5. Do not change public APIs unless required.
6. Do not rename unrelated variables.
7. Do not reorder unrelated imports merely for aesthetics unless the formatter requires it.

---

# 58. Refactoring Rules

Refactor when it clearly improves:

- Correctness
- Readability
- Maintainability
- Reuse
- Testability
- Security
- Performance

Do not refactor solely because another style is possible.

Large refactors should not be mixed with unrelated feature changes unless required.

---

# 59. Do Not Over-Engineer

The AI agent must avoid unnecessary abstraction.

Do not automatically introduce:

- Repository pattern
- Service pattern
- DTO
- Interface
- Event
- Listener
- Command
- Factory abstraction

unless the complexity or project architecture justifies it.

Use the simplest clean architecture that remains maintainable.

---

# 60. Do Not Under-Engineer

At the same time, do not place everything inside:

- One controller
- One Blade file
- One model
- One JavaScript file

Extract responsibilities when complexity is real.

---

# 61. AI Agent Workflow

Before writing code, the agent MUST:

1. Understand the requested change.
2. Inspect relevant files.
3. Inspect related routes/controllers/models/views.
4. Check existing project conventions.
5. Identify security implications.
6. Identify database implications.
7. Identify possible regression risks.
8. Reuse existing implementations where appropriate.

---

# 62. AI Agent Implementation Behavior

During implementation, the agent SHOULD:

1. Make the smallest clean change that fully solves the requirement.
2. Preserve architectural consistency.
3. Reuse existing components and helpers.
4. Avoid duplicate logic.
5. Avoid unnecessary dependencies.
6. Keep code human-readable.
7. Keep business logic out of Blade when appropriate.
8. Validate all untrusted input.
9. Consider authorization.
10. Consider test coverage.

---

# 63. AI Agent Post-Implementation Review

Before declaring the task complete, review:

- Syntax
- Formatting
- Imports
- Route names
- Validation
- Authorization
- Null handling
- Edge cases
- Database queries
- N+1 risk
- Security
- Accessibility
- Responsive layout
- Existing behavior
- Relevant tests

---

# 64. AI Agent Must Not Hide Problems

If the agent discovers:

- A bug
- Security vulnerability
- Data-loss risk
- Breaking change
- Missing migration
- Missing validation
- Performance issue
- Architectural conflict

the agent must clearly report it.

Do not silently work around serious problems.

---

# 65. Preserve User Code Style

If the existing repository already follows a coherent style, preserve it unless:

- It violates an explicit rule in this file
- It is clearly harmful
- The requested task requires a change

Do not impose personal stylistic preferences unnecessarily.

---

# 66. Formatter Rules

Automatic formatters may be used, but the resulting source code must remain human-readable.

For Laravel/PHP, use the project's configured formatter when available.

Possible tools include:

```text
Laravel Pint
PHP CS Fixer
Prettier
ESLint
Blade formatter
```

Do not introduce a formatter solely for one small change unless requested.

---

# 67. Blade Formatting Example

This is the style expected for complex project views.

```blade
@section('content')
    <article>
        <header class="page-hero container project-hero">
            <div>
                <p class="eyebrow">
                    Case study / {{ $project->published_at?->format('Y') }}
                </p>

                <h1>
                    {{ $project->title }}
                </h1>

                <p class="lead">
                    {{ $project->summary }}
                </p>

                <div class="actions">
                    @if ($project->demo_url)
                        <a
                            class="btn primary"
                            target="_blank"
                            rel="noopener noreferrer"
                            href="{{ $project->demo_url }}"
                        >
                            Lihat demo ↗
                        </a>
                    @endif

                    @if ($project->repository_url)
                        <a
                            class="btn ghost"
                            target="_blank"
                            rel="noopener noreferrer"
                            href="{{ $project->repository_url }}"
                        >
                            Repository ↗
                        </a>
                    @endif
                </div>
            </div>

            <div class="project-meta">
                <div>
                    <span>
                        Peran
                    </span>

                    <strong>
                        {{ $project->role ?: 'Designer & Developer' }}
                    </strong>
                </div>

                <div>
                    <span>
                        Teknologi
                    </span>

                    <strong>
                        {{ $project->skills->pluck('name')->join(', ') ?: 'Web technology' }}
                    </strong>
                </div>
            </div>
        </header>
    </article>
@endsection
```

---

# 68. Forbidden Blade Style

The following style must never be generated:

```blade
@section('content')<article><header class="page-hero container project-hero"><div><p class="eyebrow">Case study / {{ $project->published_at?->format('Y') }}</p><h1>{{ $project->title }}</h1><p class="lead">{{ $project->summary }}</p></div></header></article>@endsection
```

Even if this syntax technically works, it is unacceptable because it harms:

- readability
- debugging
- code review
- maintenance
- merge conflict resolution

---

# 69. Source Code vs Production Minification

This rule applies to **source files**.

Production build tools may minify generated assets such as:

- CSS bundles
- JavaScript bundles

That is acceptable when performed automatically during the production build.

Never manually minify source files stored in the repository.

---

# 70. Documentation

Important architecture decisions should be documented when appropriate.

Documentation should explain:

- What was changed
- Why it was changed
- Important tradeoffs
- Setup requirements
- Environment requirements

Do not document obvious implementation details unnecessarily.

---

# 71. Environment Configuration

Never commit real secrets.

Use:

```text
.env
.env.example
config/*
```

`.env.example` should document required environment variable names without real production credentials.

---

# 72. Public Portfolio Safety

Before exposing project information publicly, avoid publishing:

- Private company source code
- Private repository URLs
- Credentials
- Internal API URLs
- Customer data
- Confidential business information
- Internal infrastructure details that should remain private

Portfolio case studies should describe work without leaking confidential data.

---

# 73. Final Checklist

Before completing any coding task, verify:

- [ ] No source-code minification
- [ ] Blade directives are readable
- [ ] Blade directives use proper spacing
- [ ] HTML nesting is clearly indented
- [ ] Long attributes are split when needed
- [ ] Logical blocks use sensible blank lines
- [ ] PHP follows PSR-12
- [ ] Business logic is not unnecessarily placed in Blade
- [ ] Validation is considered
- [ ] Authorization is considered
- [ ] Security is considered
- [ ] File uploads are validated if relevant
- [ ] Database queries are reasonably efficient
- [ ] N+1 queries are considered
- [ ] Responsive behavior is considered
- [ ] Accessibility is considered
- [ ] SEO is considered for public pages
- [ ] Existing project conventions are preserved
- [ ] No unrelated files were modified
- [ ] No unnecessary dependency was added
- [ ] Relevant tests were considered or executed
- [ ] Code remains readable and maintainable

---

# 74. Core Principle

When in doubt, choose the implementation that is:

> Clearer, safer, easier to understand, easier to maintain, and consistent with the existing codebase.

Do not optimize for the fewest lines.

Optimize for long-term maintainability.

---

# 75. Mandatory AI Instruction

Every AI coding agent working in this repository must treat this file as a project-level instruction.

If a generated solution conflicts with this file, the agent must revise the implementation before considering the task complete.

The following rule is especially strict:

> **NEVER generate minified, compressed, or one-line Blade/HTML source code for complex markup.**

Readable source code is part of the implementation quality, not an optional formatting preference.
