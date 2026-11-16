<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /* ------------------------------------------------------------------
     | Home page
     * -----------------------------------------------------------------*/
    public function home()
    {
        return view('admin.settings.home');
    }

    public function updateHome(Request $request)
    {
        $request->validate([
            'hero_slides' => 'required|array|min:1|max:6',
            'hero_slides.*.title' => 'nullable|string|max:120',
            'hero_slides.*.highlight' => 'nullable|string|max:120',
            'hero_slides.*.subtitle' => 'nullable|string|max:300',
            'hero_slides.*.image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
            'home_about_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'home_about_image2_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'why_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'cta_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
        ]);

        $slides = $this->repeaterWithImages($request, 'hero_slides', ['title', 'highlight', 'subtitle'], 'settings');

        $data = $request->only([
            'hero_badge', 'marquee_text', 'home_about_tag', 'home_about_title', 'home_about_text',
            'experience_years', 'services_title', 'why_title', 'why_text', 'cta_title', 'cta_text',
        ]);
        $data['hero_slides'] = $slides;
        $data['home_about_points'] = array_values(array_filter(array_map('trim', explode("\n", (string) $request->home_about_points))));
        $data['stats'] = $this->repeater($request->input('stats', []), ['number', 'suffix', 'label']);
        $data['services'] = $this->repeater($request->input('services', []), ['icon', 'title', 'text']);
        $data['why_points'] = $this->repeater($request->input('why_points', []), ['title', 'text']);
        $data['process'] = $this->repeater($request->input('process', []), ['title', 'text']);
        $data['testimonials'] = $this->repeater($request->input('testimonials', []), ['name', 'role', 'text']);

        foreach (['home_about_image', 'home_about_image2', 'why_image', 'cta_image'] as $img) {
            $this->singleImage($request, $img, $data);
        }

        Setting::putMany($data);

        return back()->with('success', 'Home page settings saved.');
    }

    /* ------------------------------------------------------------------
     | About us
     * -----------------------------------------------------------------*/
    public function about()
    {
        return view('admin.settings.about');
    }

    public function updateAbout(Request $request)
    {
        $request->validate([
            'about_title' => 'required|string|max:190',
            'about_banner_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6144',
            'about_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'about_image2_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'founder_image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $data = $request->only([
            'about_tag', 'about_title', 'about_content', 'mission', 'vision',
            'founder_name', 'founder_role', 'founder_message',
        ]);
        $data['about_values'] = $this->repeater($request->input('about_values', []), ['icon', 'title', 'text']);

        foreach (['about_banner', 'about_image', 'about_image2', 'founder_image'] as $img) {
            $this->singleImage($request, $img, $data);
        }

        Setting::putMany($data);

        return back()->with('success', 'About Us page saved.');
    }

    /* ------------------------------------------------------------------
     | Footer & contact details
     * -----------------------------------------------------------------*/
    public function footer()
    {
        return view('admin.settings.footer');
    }

    public function updateFooter(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'map_embed' => 'nullable|string|max:3000',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'site_name', 'tagline', 'address', 'phone', 'phone2', 'email', 'gstin', 'working_hours',
            'whatsapp', 'footer_about', 'facebook', 'instagram', 'youtube', 'linkedin', 'copyright_text',
        ]);

        // Accept either a full <iframe> embed code or just the src URL
        $map = (string) $request->map_embed;
        if (preg_match('/src=["\']([^"\']+)["\']/i', $map, $m)) {
            $map = html_entity_decode($m[1]);
        }
        $data['map_embed'] = trim($map);

        Setting::putMany($data);

        return back()->with('success', 'Footer & contact details saved.');
    }

    /* ------------------------------------------------------------------
     | Helpers
     * -----------------------------------------------------------------*/
    private function repeater(array $rows, array $fields): array
    {
        $out = [];
        foreach ($rows as $row) {
            $item = [];
            foreach ($fields as $f) {
                $item[$f] = trim((string) ($row[$f] ?? ''));
            }
            if (implode('', $item) !== '') {
                $out[] = $item;
            }
        }
        return $out;
    }

    private function repeaterWithImages(Request $request, string $key, array $fields, string $folder): array
    {
        $out = [];
        foreach ($request->input($key, []) as $i => $row) {
            $item = [];
            foreach ($fields as $f) {
                $item[$f] = trim((string) ($row[$f] ?? ''));
            }
            $item['image'] = $row['image'] ?? '';
            if ($request->hasFile("$key.$i.image_file")) {
                delete_upload($item['image']);
                $item['image'] = upload_image($request->file("$key.$i.image_file"), $folder);
            }
            if (implode('', $item) !== '') {
                $out[] = $item;
            }
        }
        return $out;
    }

    private function singleImage(Request $request, string $key, array &$data): void
    {
        if ($request->hasFile($key . '_file')) {
            delete_upload(Setting::get($key));
            $data[$key] = upload_image($request->file($key . '_file'), 'settings');
        } elseif ($request->boolean($key . '_remove')) {
            delete_upload(Setting::get($key));
            $data[$key] = '';
        }
    }
}
