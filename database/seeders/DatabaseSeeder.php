<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@aiyra.com'],
            ['name' => 'Aiyra Admin', 'password' => Hash::make('Admin@123')]
        );

        $u = 'https://images.unsplash.com/';

        $settings = [
            // ---------- General / Footer ----------
            'site_name' => 'Aiyra Constructions',
            'tagline' => 'Building Dreams | Creating Reality',
            'address' => '100/3, KS Building, Kovai Road, Pollachi - 642002.',
            'phone' => '+91 9047046011',
            'phone2' => '',
            'email' => 'info@aiyraconstructions.in',
            'gstin' => '33BHEPA8556K1Z9',
            'working_hours' => 'Mon - Sat : 9:00 AM - 7:00 PM',
            'whatsapp' => '9047046011',
            'footer_about' => 'Aiyra Constructions delivers building construction, residential projects, architectural planning and turnkey solutions across Pollachi and Coimbatore — built with honesty, quality and on-time delivery.',
            'facebook' => '#',
            'instagram' => '#',
            'youtube' => '#',
            'linkedin' => '',
            'map_embed' => 'https://www.google.com/maps?q=Kovai+Road+Pollachi+642002&output=embed',
            'copyright_text' => '© ' . date('Y') . ' Aiyra Constructions. All rights reserved.',

            // ---------- Home ----------
            'hero_slides' => [
                ['title' => 'We Build', 'highlight' => 'Your Dream Home', 'subtitle' => 'Quality residential construction from foundation to finishing, delivered on time and on budget.', 'image' => $u . 'photo-1600585154340-be6161a56a0c?w=1920&q=80'],
                ['title' => 'Planning That', 'highlight' => 'Shapes Reality', 'subtitle' => 'Architectural planning, 2D/3D design and approvals handled by our in-house experts.', 'image' => $u . 'photo-1503387762-592deb58ef4e?w=1920&q=80'],
                ['title' => 'Complete', 'highlight' => 'Turnkey Solutions', 'subtitle' => 'One team, one contract — design, construction, interiors and handover of keys.', 'image' => $u . 'photo-1541888946425-d81bb19240f5?w=1920&q=80'],
            ],
            'hero_badge' => 'Trusted Builders in Pollachi',
            'marquee_text' => 'Building Construction • Residential Projects • Architectural Planning • Turnkey Solutions • Interiors • Renovation',
            'home_about_tag' => 'About Aiyra',
            'home_about_title' => 'Building Dreams With Strength, Style & Trust',
            'home_about_text' => 'At Aiyra Constructions we turn your ideas into lasting structures. From the first sketch to the final coat of paint, our engineers, architects and site teams work with complete transparency so you always know where your project stands.',
            'home_about_points' => ['Transparent pricing & BOQ', 'Premium quality materials', 'Experienced engineers on site', 'On-time project delivery'],
            'home_about_image' => $u . 'photo-1504307651254-35680f356dfd?w=1000&q=80',
            'home_about_image2' => $u . 'photo-1541976590-713941681591?w=800&q=80',
            'experience_years' => '10',
            'stats' => [
                ['number' => '150', 'suffix' => '+', 'label' => 'Projects Completed'],
                ['number' => '10', 'suffix' => '+', 'label' => 'Years Experience'],
                ['number' => '120', 'suffix' => '+', 'label' => 'Happy Families'],
                ['number' => '25', 'suffix' => '+', 'label' => 'Expert Team'],
            ],
            'services_title' => 'What We Do Best',
            'services' => [
                ['icon' => 'bi-buildings', 'title' => 'Building Construction', 'text' => 'Structurally sound residential and commercial buildings built to code with premium materials.'],
                ['icon' => 'bi-house-heart', 'title' => 'Residential Projects', 'text' => 'Independent houses, villas and apartments designed around the way your family lives.'],
                ['icon' => 'bi-rulers', 'title' => 'Architectural Planning', 'text' => '2D plans, 3D elevations, structural design and approval drawings by qualified professionals.'],
                ['icon' => 'bi-key', 'title' => 'Turnkey Solutions', 'text' => 'Design, construction, interiors and handover — a single accountable team for everything.'],
            ],
            'why_title' => 'Why Families Choose Aiyra',
            'why_text' => 'We combine engineering discipline with honest communication, so building your home feels exciting — not stressful.',
            'why_image' => $u . 'photo-1429497419816-9ca5cfb4571a?w=1000&q=80',
            'why_points' => [
                ['title' => 'Fixed, Transparent Quotes', 'text' => 'Detailed BOQ with no hidden charges.'],
                ['title' => 'Quality Checked Materials', 'text' => 'Branded cement, steel and fittings only.'],
                ['title' => 'Stage-wise Updates', 'text' => 'Photo & video progress reports every week.'],
                ['title' => 'Warranty & Support', 'text' => 'Post-handover structural warranty.'],
            ],
            'process' => [
                ['title' => 'Consultation', 'text' => 'Understand your needs, site and budget.'],
                ['title' => 'Planning & Design', 'text' => '2D plans, 3D elevation and estimate.'],
                ['title' => 'Construction', 'text' => 'Execution with quality checks at every stage.'],
                ['title' => 'Handover', 'text' => 'Final inspection and keys to your dream.'],
            ],
            'testimonials' => [
                ['name' => 'Ramesh K', 'role' => 'Home Owner, Pollachi', 'text' => 'The team handled everything from approval to handover. Our house was completed on time and the quality is excellent.'],
                ['name' => 'Priya S', 'role' => 'Villa Owner, Coimbatore', 'text' => 'Very transparent with costs and gave weekly updates. Highly recommend Aiyra Constructions.'],
                ['name' => 'Senthil M', 'role' => 'Commercial Client', 'text' => 'Professional engineers and a disciplined site team. Our commercial building came out exactly as planned.'],
            ],
            'cta_title' => 'Ready to Build Your Dream Project?',
            'cta_text' => 'Get a free consultation and detailed estimate from our experts today.',
            'cta_image' => $u . 'photo-1487958449943-2429e8be8625?w=1920&q=80',

            // ---------- About ----------
            'about_banner' => $u . 'photo-1531834685032-c34bf0d84c77?w=1920&q=80',
            'about_tag' => 'Who We Are',
            'about_title' => 'Crafting Spaces That Last For Generations',
            'about_content' => "Aiyra Constructions is a Pollachi-based construction company specialising in building construction, residential projects, architectural planning and turnkey solutions.\n\nWe believe every structure tells the story of the family or business behind it. That is why we focus on careful planning, honest pricing, quality materials and disciplined execution — so the building you dreamt of is the building you receive.",
            'about_image' => $u . 'photo-1503387762-592deb58ef4e?w=1000&q=80',
            'about_image2' => $u . 'photo-1621905251189-08b45d6a269e?w=800&q=80',
            'mission' => 'To deliver safe, durable and beautiful buildings with complete transparency, on time and within budget.',
            'vision' => 'To be the most trusted construction partner in Western Tamil Nadu, known for quality and integrity.',
            'about_values' => [
                ['icon' => 'bi-shield-check', 'title' => 'Integrity', 'text' => 'Honest pricing and clear communication.'],
                ['icon' => 'bi-gem', 'title' => 'Quality', 'text' => 'No compromise on materials or workmanship.'],
                ['icon' => 'bi-clock-history', 'title' => 'Timeliness', 'text' => 'Planned schedules that we keep.'],
                ['icon' => 'bi-people', 'title' => 'Customer First', 'text' => 'Your dream guides every decision.'],
            ],
            'founder_name' => 'Founder',
            'founder_role' => 'Managing Director, Aiyra Constructions',
            'founder_message' => 'Every home we build carries our name. That responsibility pushes us to plan better, build stronger and serve every client like family.',
            'founder_image' => '',
        ];

        foreach ($settings as $key => $value) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::put($key, $value);
            }
        }

        if (Project::count() === 0) {
            $demo = [
                ['Modern Villa Residence', 'Villa', 'completed', 'Pollachi', '3,200 sq.ft', 'photo-1600596542815-ffad4c1539a9'],
                ['Green Valley Apartments', 'Apartment', 'ongoing', 'Coimbatore', '18,000 sq.ft', 'photo-1545324418-cc1a3fa10c00'],
                ['Contemporary Duplex Home', 'Residential', 'completed', 'Udumalpet', '2,450 sq.ft', 'photo-1600607687939-ce8a6c25118c'],
                ['Commercial Complex', 'Commercial', 'ongoing', 'Kovai Road, Pollachi', '12,500 sq.ft', 'photo-1486406146926-c627a92ad1ab'],
                ['Luxury Interior Fit-out', 'Interior', 'completed', 'Pollachi', '1,800 sq.ft', 'photo-1600210492486-724fe5c67fb0'],
                ['Farmhouse Retreat', 'Villa', 'upcoming', 'Anaimalai', '4,000 sq.ft', 'photo-1600047509807-ba8f99d2cdde'],
            ];
            foreach ($demo as $i => [$title, $cat, $status, $loc, $area, $img]) {
                Project::create([
                    'title' => $title,
                    'slug' => Project::uniqueSlug($title),
                    'category' => $cat,
                    'status' => $status,
                    'location' => $loc,
                    'area' => $area,
                    'year' => (string) (date('Y') - ($i % 3)),
                    'short_description' => 'A thoughtfully planned ' . strtolower($cat) . ' project in ' . $loc . ', built with premium materials and precise engineering.',
                    'description' => "This " . strtolower($cat) . " project showcases Aiyra's approach to planning and execution.\n\nFrom soil testing and structural design to finishing and handover, every stage was carried out with strict quality checks and regular client updates.",
                    'cover_image' => $u . $img . '?w=1200&q=80',
                    'is_featured' => $i < 4,
                    'sort_order' => $i,
                ]);
            }
        }
    }
}
