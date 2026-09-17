<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Rosemary Gyau',
                'role' => 'Student, Ghana',
                'message' => 'I am grateful to Ree\'s Consult. I passed my IELTS exam and they helped me throughout my school application process. I just got an admission with scholarship to Cambridge University. I highly recommend Ree\'s Consult!',
                'rating' => 5,
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name' => 'James Addae',
                'role' => 'Student, University of Ghana',
                'message' => 'Ree\'s Consult provided exceptional support during my study abroad application. Their team was professional, knowledgeable, and always available to answer my questions. Thanks to them, I\'m now studying at my dream university!',
                'rating' => 5,
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name' => 'Paddy Awuvre Kanwugu',
                'role' => 'Professional, Ghana',
                'message' => 'I highly recommend Rees Consult for anyone preparing for their IELTS exams. Their expert guidance, personalized coaching, and effective study materials were instrumental in helping me achieve my desired score. If you\'re looking for a supportive and knowledgeable team to help you succeed, Rees Consult is the way to go!',
                'rating' => 5,
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name' => 'Emmanuella Beke',
                'role' => 'Student, Accra',
                'message' => 'The visa guidance service was excellent. They helped me prepare all my documents correctly and I got my visa approved on the first try. Very professional and reliable service!',
                'rating' => 5,
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name'], 'role' => $testimonial['role']],
                $testimonial
            );
        }
    }
}
