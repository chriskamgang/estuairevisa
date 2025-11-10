<?php

namespace Database\Seeders;

use App\Models\FrontendImage;
use Illuminate\Database\Seeder;

class FrontendImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $images = [
            // About Section
            [
                'key' => 'about_main_image',
                'section' => 'about',
                'label' => 'About Us - Main Image',
                'image' => 'about.png',
                'description' => 'Main image for About Us section',
                'order' => 1
            ],

            // Banner Section
            [
                'key' => 'banner_background',
                'section' => 'banner',
                'label' => 'Banner - Background Image',
                'image' => 'bg/banner-2.jpg',
                'description' => 'Background image for banner section',
                'order' => 1
            ],

            // Choose Section
            [
                'key' => 'choose_icon_1',
                'section' => 'choose',
                'label' => 'Why Choose Us - Icon 1 (Fast & Hassle-Free)',
                'image' => 'choose/1.png',
                'description' => 'Icon for Fast & Hassle-Free feature',
                'order' => 1
            ],
            [
                'key' => 'choose_icon_2',
                'section' => 'choose',
                'label' => 'Why Choose Us - Icon 2 (Expert Support)',
                'image' => 'choose/2.png',
                'description' => 'Icon for Expert Support Team feature',
                'order' => 2
            ],
            [
                'key' => 'choose_icon_3',
                'section' => 'choose',
                'label' => 'Why Choose Us - Icon 3 (Secure & Confidential)',
                'image' => 'choose/3.png',
                'description' => 'Icon for Secure & Confidential feature',
                'order' => 3
            ],
            [
                'key' => 'choose_icon_4',
                'section' => 'choose',
                'label' => 'Why Choose Us - Icon 4 (High Approval Rate)',
                'image' => 'choose/4.png',
                'description' => 'Icon for High Approval Rate feature',
                'order' => 4
            ],
            [
                'key' => 'choose_main_image',
                'section' => 'choose',
                'label' => 'Why Choose Us - Main Image',
                'image' => 'why-choose.png',
                'description' => 'Main image for Why Choose Us section',
                'order' => 5
            ],

            // Destination Section
            [
                'key' => 'destination_map_image',
                'section' => 'destination',
                'label' => 'Destination - Map Image',
                'image' => 'saudi-arab-map.png',
                'description' => 'Map image for destination section',
                'order' => 1
            ],

            // Authorized Section
            [
                'key' => 'authorized_image_1',
                'section' => 'authorized',
                'label' => 'Authorized By - Image 1',
                'image' => 'authorized/1.webp',
                'description' => 'First authorized organization logo',
                'order' => 1
            ],
            [
                'key' => 'authorized_image_2',
                'section' => 'authorized',
                'label' => 'Authorized By - Image 2',
                'image' => 'authorized/2.webp',
                'description' => 'Second authorized organization logo',
                'order' => 2
            ],
            [
                'key' => 'authorized_image_3',
                'section' => 'authorized',
                'label' => 'Authorized By - Image 3',
                'image' => 'authorized/3.webp',
                'description' => 'Third authorized organization logo',
                'order' => 3
            ],

            // Track Section
            [
                'key' => 'tracking_image',
                'section' => 'track',
                'label' => 'Track Visa - Main Image',
                'image' => 'tracking.png',
                'description' => 'Main image for tracking section',
                'order' => 1
            ],

            // CTA Section
            [
                'key' => 'cta_pattern',
                'section' => 'cta',
                'label' => 'CTA - Pattern Image',
                'image' => 'elements/pattern-1.png',
                'description' => 'Pattern background for CTA section',
                'order' => 1
            ],
            [
                'key' => 'cta_passport',
                'section' => 'cta',
                'label' => 'CTA - Passport Element',
                'image' => 'elements/passport-el.png',
                'description' => 'Passport element for CTA section',
                'order' => 2
            ],

            // Decorative Elements
            [
                'key' => 'element_plane_3',
                'section' => 'elements',
                'label' => 'Decorative - Plane 3',
                'image' => 'elements/plane-3.png',
                'description' => 'Decorative plane element',
                'order' => 1
            ],
            [
                'key' => 'element_plane_4',
                'section' => 'elements',
                'label' => 'Decorative - Plane 4',
                'image' => 'elements/plane-4.png',
                'description' => 'Decorative plane element',
                'order' => 2
            ],
            [
                'key' => 'element_2',
                'section' => 'elements',
                'label' => 'Decorative - Element 2',
                'image' => 'elements/2.png',
                'description' => 'Decorative element 2',
                'order' => 3
            ]
        ];

        foreach ($images as $imageData) {
            FrontendImage::updateOrCreate(
                ['key' => $imageData['key']],
                $imageData
            );
        }
    }
}
