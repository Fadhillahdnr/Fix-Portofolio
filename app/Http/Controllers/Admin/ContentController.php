<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\Skill;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    public function index()
    {
        return view('admin.content.index', ['profile' => Profile::first(), 'experiences' => Experience::orderBy('sort_order')->get(), 'skills' => Skill::orderBy('sort_order')->get(), 'categories' => Category::orderBy('name')->get(), 'services' => Service::orderBy('sort_order')->get(), 'socials' => SocialLink::orderBy('sort_order')->get(), 'messages' => ContactMessage::latest()->paginate(15), 'settings' => SiteSetting::orderBy('group')->get()]);
    }

    public function profile(Request $r)
    {
        $d = $r->validate(['headline' => 'required|string|max:180', 'short_bio' => 'required|string|max:600', 'full_bio' => 'nullable|string|max:10000', 'location' => 'nullable|string|max:120', 'availability' => 'nullable|string|max:120']);
        Profile::updateOrCreate(['id' => Profile::value('id')], $d + ['user_id' => $r->user()->id]);

        return $this->done('Profil diperbarui.');
    }

    public function category(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:100', 'slug' => 'nullable|alpha_dash|max:120']);
        Category::create(['name' => $d['name'], 'slug' => $d['slug'] ?: Str::slug($d['name'])]);

        return $this->done('Kategori ditambahkan.');
    }

    public function skill(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:100', 'category' => 'nullable|string|max:100', 'proficiency' => 'nullable|integer|min:0|max:100', 'sort_order' => 'nullable|integer|min:0']);
        Skill::create($d + ['slug' => Str::slug($d['name']), 'is_featured' => $r->boolean('is_featured')]);

        return $this->done('Skill ditambahkan.');
    }

    public function service(Request $r)
    {
        $d = $r->validate(['title' => 'required|string|max:140', 'description' => 'required|string|max:1200', 'sort_order' => 'nullable|integer|min:0']);
        Service::create($d + ['slug' => Str::slug($d['title']), 'is_active' => true]);

        return $this->done('Layanan ditambahkan.');
    }

    public function experience(Request $r)
    {
        $d = $r->validate(['company' => 'required|string|max:140', 'position' => 'required|string|max:140', 'description' => 'required|string|max:2000', 'start_date' => 'required|date', 'end_date' => 'nullable|date|after_or_equal:start_date', 'sort_order' => 'nullable|integer|min:0']);
        Experience::create($d + ['is_current' => $r->boolean('is_current')]);

        return $this->done('Pengalaman ditambahkan.');
    }

    public function social(Request $r)
    {
        $d = $r->validate(['platform' => 'required|string|max:80', 'label' => 'required|string|max:100', 'url' => 'required|url|max:2048', 'sort_order' => 'nullable|integer|min:0']);
        SocialLink::create($d + ['is_active' => true]);

        return $this->done('Tautan sosial ditambahkan.');
    }

    public function setting(Request $r)
    {
        $d = $r->validate(['key' => 'required|alpha_dash|max:100', 'value' => 'nullable|string|max:3000', 'group' => 'required|string|max:80']);
        SiteSetting::updateOrCreate(['key' => $d['key']], ['value' => ['text' => $d['value']], 'group' => $d['group'], 'is_public' => $r->boolean('is_public')]);

        return $this->done('Pengaturan disimpan.');
    }

    public function message(Request $r, ContactMessage $message)
    {
        $d = $r->validate(['status' => ['required', Rule::in(['new', 'read', 'archived'])]]);
        $message->update($d);

        return $this->done('Status pesan diperbarui.');
    }

    public function destroy(string $type, int $id)
    {
        $map = ['categories' => Category::class, 'skills' => Skill::class, 'services' => Service::class, 'experiences' => Experience::class, 'socials' => SocialLink::class, 'settings' => SiteSetting::class];
        abort_unless(isset($map[$type]), 404);
        $map[$type]::findOrFail($id)->delete();

        return $this->done('Data dihapus.');
    }

    private function done(string $message)
    {
        Cache::flush();

        return back()->with('success', $message);
    }
}
