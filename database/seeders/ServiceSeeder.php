<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        // First, clear existing services to prevent collisions or duplicates
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Service::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $services = [
            // ==========================================
            // 1. STANDARDIZED TESTS (TUITIONS)
            // ==========================================
            [
                'title' => 'IELTS Prep Course (Academic/General)',
                'slug' => 'ielts-preparation',
                'category' => 'standardized_test',
                'subtitle' => 'Expert training to score 8.0+ in your IELTS exams',
                'description' => '<p>Get professional, comprehensive coaching for both Academic and General Training modules of the IELTS exam.</p><p>Our structured curriculum covers all four essential sections: Listening, Reading, Writing, and Speaking. Instructors provide personalized correction, strategies to bypass common traps, and mock practice tests modeled on the real British Council / IDP assessments.</p>',
                'features' => [
                    'Personalized 1-on-1 speaking evaluations',
                    '20+ realistic mock assessments',
                    'Comprehensive study guides and course material',
                    'Certified British Council registration affiliate assistance'
                ],
                'why_choose_program' => [
                    'Proven 95% pass rate with average bands score of 7.5+',
                    'Flexible morning, evening, and weekend slots',
                    'Exclusive access to premium learning materials'
                ],
                'pricing_type' => 'tiered',
                'price' => 250.00, // GHS 3,000 equivalent
                'processing_fee' => 50.00, // GHS 600 registration support
                'currency' => 'USD',
                'duration' => '8 Weeks',
                'class_size' => 'Max 12 Students',
                'icon' => 'bi-book',
                'color' => '#2E5BBA',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'TOEFL Preparation Course',
                'slug' => 'toefl-preparation',
                'category' => 'standardized_test',
                'subtitle' => 'Ace the TOEFL iBT test with expert guidance',
                'description' => '<p>Master the TOEFL internet-based test (iBT) with our specialized coaching program.</p><p>We focus heavily on academic vocabulary, fast reading pacing, speaking under time limits, and writing responses that conform to ETS grading standards. We prepare students using official software simulations to reduce anxiety on exam day.</p>',
                'features' => [
                    'Computer-based testing software simulation',
                    'Academic listening and integrated writing workshops',
                    'Expert speech analysis with timing drills',
                    'Complimentary registration support'
                ],
                'why_choose_program' => [
                    'Instructors scoring 115+ in their personal tests',
                    'Robust vocabulary modules tailored for university placement',
                    'State-of-the-art computer testing lab access'
                ],
                'pricing_type' => 'fixed',
                'price' => 200.00,
                'processing_fee' => 40.00,
                'currency' => 'USD',
                'duration' => '6 Weeks',
                'class_size' => 'Max 10 Students',
                'icon' => 'bi-laptop',
                'color' => '#0D6EFD',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'GRE Prep Intensive',
                'slug' => 'gre-intensive',
                'category' => 'standardized_test',
                'subtitle' => 'High-score strategy coaching for graduate program entries',
                'description' => '<p>Aiming for top-tier graduate schools in the US, Europe, or Canada? Our GRE Prep Intensive is designed for you.</p><p>We cover Quantitative Reasoning (advanced algebra, geometry, word problems), Verbal Reasoning (text completion, sentence equivalence, reading comprehension), and Analytical Writing (issue and argument tasks) with emphasis on shortcuts and pacing.</p>',
                'features' => [
                    'Advanced quant shortcuts and formulas toolkit',
                    'Vocabulary database containing 1,500+ high-frequency GRE words',
                    'Mock analytical writing review and grading feedback',
                    'Adaptive computer diagnostic mock exams'
                ],
                'why_choose_program' => [
                    'Averages scores boost of 15+ points post-course',
                    'Curriculum structured by mathematical PhD scholars',
                    'Lifetime study-group networking access'
                ],
                'pricing_type' => 'fixed',
                'price' => 300.00,
                'processing_fee' => 60.00,
                'currency' => 'USD',
                'duration' => '10 Weeks',
                'class_size' => 'Max 8 Students',
                'icon' => 'bi-calculator',
                'color' => '#198754',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'SAT College Prep',
                'slug' => 'sat-college-prep',
                'category' => 'standardized_test',
                'subtitle' => 'Path to Ivy League and premium scholarships in the US',
                'description' => '<p>Equip high school students to conquer the Digital SAT.</p><p>This course reviews all standard reading, writing, and math topics tested on the SAT. We emphasize time-saving techniques, logic reasoning, and regular mock exams on the official Bluebook app to secure high scores necessary for merit scholarships.</p>',
                'features' => [
                    'Digital SAT format practice modules',
                    'Integrated math tricks and calculator shortcuts',
                    'Weekly full-length diagnostic testing sessions',
                    'Direct scholarship linking advisory'
                ],
                'why_choose_program' => [
                    'Averages score of 1450+ achieved by previous cohorts',
                    'Engaging, student-friendly pedagogy',
                    'Support with early action/decision application timings'
                ],
                'pricing_type' => 'fixed',
                'price' => 180.00,
                'processing_fee' => 35.00,
                'currency' => 'USD',
                'duration' => '8 Weeks',
                'class_size' => 'Max 15 Students',
                'icon' => 'bi-mortarboard',
                'color' => '#6F42C1',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'OET Prep for Medical Professionals',
                'slug' => 'oet-medical-prep',
                'category' => 'standardized_test',
                'subtitle' => 'Fulfill healthcare registration requirements with ease',
                'description' => '<p>The Occupational English Test (OET) is highly specific to healthcare professionals.</p><p>We offer customized tutoring for Doctors, Nurses, and Pharmacists wishing to work in the UK, Australia, or Ireland. We practice medical communication scenarios, patient histories, discharge summaries, and medical texts analysis.</p>',
                'features' => [
                    'Profession-specific communication roleplays',
                    'Clinical letters assessment and detailed corrections',
                    'Authentic listening recordings based on hospital consultations',
                    'Direct links with hospital hiring agents'
                ],
                'why_choose_program' => [
                    'Get your required Grade B or above on the first attempt',
                    'Taught by certified healthcare communication trainers',
                    'Custom course materials matching the clinical environment'
                ],
                'pricing_type' => 'fixed',
                'price' => 280.00,
                'processing_fee' => 50.00,
                'currency' => 'USD',
                'duration' => '6 Weeks',
                'class_size' => 'Max 8 Professionals',
                'icon' => 'bi-heart-pulse',
                'color' => '#DC3545',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'G-MAT Prep Course',
                'slug' => 'gmat-preparation',
                'category' => 'standardized_test',
                'subtitle' => 'Master the GMAT Focus Edition for business school admission',
                'description' => '<p>Achieve a competitive GMAT score with our focused preparation course.</p><p>We cover Quantitative Reasoning, Verbal Reasoning, and Data Insights sections. Instructors provide individual assessment, shortcut algorithms, and practice testing on the official software simulation platforms.</p>',
                'features' => [
                    'Focus on Quantitative, Verbal, and Data Insights sections',
                    'Official GMAC practice software simulations',
                    'Advanced adaptive diagnostic mock tests',
                    'Comprehensive verbal reasoning modules'
                ],
                'why_choose_program' => [
                    'Coaches scoring in the 98th percentile of their tests',
                    'Individual MBA pathway planning and strategy sessions',
                    'Proven average score boost of 80+ points post-coaching'
                ],
                'pricing_type' => 'fixed',
                'price' => 250.00,
                'processing_fee' => 50.00,
                'currency' => 'USD',
                'duration' => '8 Weeks',
                'class_size' => 'Max 10 Students',
                'icon' => 'bi-bar-chart-fill',
                'color' => '#FFC107',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'TESOL Certification Course',
                'slug' => 'tesol-certification',
                'category' => 'standardized_test',
                'subtitle' => 'Earn your internationally accredited teaching certificate',
                'description' => '<p>Get certified to teach English to speakers of other languages globally or online.</p><p>Our 120-hour TESOL/TEFL curriculum covers language acquisition theories, grammar instruction strategies, lesson planning, and classroom management, including real peer-teaching practices.</p>',
                'features' => [
                    'Accredited 120-hour curriculum accepted worldwide',
                    'Hands-on teaching practice with immediate evaluations',
                    'Global job placement database and CV builder',
                    'Comprehensive study modules and reference books'
                ],
                'why_choose_program' => [
                    'Accredited trainers with overseas English teaching experience',
                    'Flexible schedule options for professional working candidates',
                    'Lifetime reference letters and job application support'
                ],
                'pricing_type' => 'fixed',
                'price' => 167.00,
                'processing_fee' => 40.00,
                'currency' => 'USD',
                'duration' => '10 Weeks',
                'class_size' => 'Max 15 Students',
                'icon' => 'bi-translate',
                'color' => '#20C997',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'NCLEX RN/PN Review Program',
                'slug' => 'nclex-review',
                'category' => 'standardized_test',
                'subtitle' => 'Pass the US & Canadian nursing licensing exams on your first attempt',
                'description' => '<p>Pass the Next Generation NCLEX (NGN) exam with our specialized nursing review course.</p><p>We provide deep dive reviews into physiological adaptation, safety, pharmacology, and clinical judgment case studies, utilizing computerized adaptive testing (CAT) engine simulations.</p>',
                'features' => [
                    'Next Generation NCLEX (NGN) case study practice',
                    'Computerized adaptive testing (CAT) engine simulations',
                    'Detailed rationales for 3,000+ NCLEX-style questions',
                    'Nursing board registration support and credential assistance'
                ],
                'why_choose_program' => [
                    'Outstanding 98% pass rate achieved by international nurses',
                    'Mentorship by licensed educators in US and Canada boards',
                    'Diagnostic reports highlighting weak clinical areas'
                ],
                'pricing_type' => 'fixed',
                'price' => 167.00,
                'processing_fee' => 40.00,
                'currency' => 'USD',
                'duration' => '12 Weeks',
                'class_size' => 'Max 12 Nurses',
                'icon' => 'bi-activity',
                'color' => '#17A2B8',
                'order' => 8,
                'is_active' => true,
            ],

            // ==========================================
            // 2. SCHOOL APPLICATIONS
            // ==========================================
            [
                'title' => 'Undergraduate Study Admissions Support',
                'slug' => 'undergraduate-placement',
                'category' => 'school_application',
                'subtitle' => 'Start your higher education journey in world-class institutions',
                'description' => '<p>Complete, expert guidance through university admissions for high school graduates.</p><p>We assist with identifying matching universities based on budget, drafting stellar personal statements, building CVs, obtaining academic letters of recommendation, and submitting applications to target countries like the US, Canada, UK, and Germany.</p>',
                'features' => [
                    'Personal Statement review and drafting support',
                    'Custom list of 5-8 matching university options',
                    'Submission of up to 4 university applications included',
                    'Comprehensive student visa coaching'
                ],
                'why_choose_program' => [
                    '99% admissions offer success rate',
                    'Access to application fee waiver programs (saving you cash)',
                    'Pre-departure orientation and local flight support'
                ],
                'pricing_type' => 'fixed',
                'price' => 125.00,
                'processing_fee' => 350.00,
                'currency' => 'USD',
                'duration' => 'Admission Cycle',
                'class_size' => '1-on-1 Advisory',
                'icon' => 'bi-university',
                'color' => '#17A2B8',
                'order' => 9,
                'is_active' => true,
            ],
            [
                'title' => 'Postgraduate & PhD Placement Consulting',
                'slug' => 'postgraduate-phd-consulting',
                'category' => 'school_application',
                'subtitle' => 'Secure fully funded assistantships and Master programs',
                'description' => '<p>Professional consulting designed for prospective Master and PhD students.</p><p>We specialize in structuring research proposals, identifying potential academic supervisors, writing cold contact emails to professors, and preparing for research interviews. We focus heavily on securing fully funded Teaching (TA) or Research Assistantships (RA).</p>',
                'features' => [
                    'Research proposal structure and review sessions',
                    'Cold-email drafts to supervisors',
                    'CV editing highlighting research publications/projects',
                    'Complete graduate assistantship scholarship coaching'
                ],
                'why_choose_program' => [
                    'Over $500,000 in merit funding secured for clients annually',
                    'Managed by advisors with post-graduate degrees abroad',
                    'Highly strategic timeline and supervisor outreach management'
                ],
                'pricing_type' => 'fixed',
                'price' => 125.00,
                'processing_fee' => 500.00,
                'currency' => 'USD',
                'duration' => 'Admission Cycle',
                'class_size' => '1-on-1 Advisory',
                'icon' => 'bi-award',
                'color' => '#FD7E14',
                'order' => 10,
                'is_active' => true,
            ],

            // ==========================================
            // 3. JOB ABROAD PLACEMENTS
            // Pricing structure per payment document (GHS values, 1 USD = 12 GHS)
            // Cat 1: UK, Canada, Australia, NZ (Single) — GHS 200,000 total
            // Cat 2: UK (Family of 4)                   — GHS 300,000 total
            // Cat 3: Denmark, Norway, Ireland            — GHS 150,000 total
            // Cat 4: Poland, Serbia, Montenegro          — GHS 70,000 total
            // Cat 5: Canada, Australia, NZ (Family of 4) — GHS 320,000 total
            // Cat 6: Canada, Australia, NZ (Family of 5) — GHS 350,000 total
            // ==========================================

            // ---- CATEGORY 1: UK, Canada, Australia, New Zealand (Single Individual) ----
            [
                'title' => 'UK Job Placement – Individual Package',
                'slug' => 'job-placement-uk-single',
                'category' => 'job_abroad',
                'country' => 'United Kingdom',
                'subtitle' => 'Sponsored work relocation to the UK — Single applicant package',
                'description' => '<p>Secured, legally sponsored job placements across the United Kingdom in healthcare, hospitality, logistics, and skilled trades.</p><p>We handle your job matching, employer interviews, visa sponsorship document assembly, and pre-departure support from start to finish. This individual package includes all necessary steps from commitment to final visa clearance.</p>',
                'features' => [
                    'End-to-end employer job matching and interview preparation',
                    '40% commitment fee to begin processing — refundable if interview fails',
                    'Complete UK visa sponsorship document assembly',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee — GHS 37,120 refundable if unsuccessful',
                    '100% legal sponsorship with licensed UK employers',
                    'Family relocation (dependent visa) support available',
                ],
                'pricing_type' => 'fixed',
                'price' => round(200000 / 12), // GHS 200,000 → ~$16,667 USD
                'processing_fee' => round(40000 / 12), // 40% commitment fee → ~$3,333 USD
                'currency' => 'USD',
                'duration' => '4-6 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-briefcase-fill',
                'color' => '#0D6EFD',
                'order' => 11,
                'is_active' => true,
            ],
            [
                'title' => 'Canada Job Placement – Individual Package',
                'slug' => 'job-placement-canada-single',
                'category' => 'job_abroad',
                'country' => 'Canada',
                'subtitle' => 'Sponsored work relocation to Canada — Single applicant package',
                'description' => '<p>Direct hiring slots across Canada in technology, healthcare, hospitality, construction, and skilled trades.</p><p>We link you with LMIA-certified Canadian employers, manage your work permit application, and guide you through the full relocation process. This individual package includes all steps from registration to final visa clearance.</p>',
                'features' => [
                    'LMIA-certified employer job matching and interview prep',
                    '40% commitment fee to start — refundable if interview fails',
                    'Work permit and PR pathway support (Express Entry)',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee — refundable portion if unsuccessful',
                    'Fast-track PR pathway via Express Entry or PNP',
                    'Canadian-format CV and interview coaching included',
                ],
                'pricing_type' => 'fixed',
                'price' => round(200000 / 12),
                'processing_fee' => round(40000 / 12),
                'currency' => 'USD',
                'duration' => '4-6 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-map-fill',
                'color' => '#DC3545',
                'order' => 12,
                'is_active' => true,
            ],
            [
                'title' => 'Australia Job Placement – Individual Package',
                'slug' => 'job-placement-australia-single',
                'category' => 'job_abroad',
                'country' => 'Australia',
                'subtitle' => 'Sponsored work relocation to Australia — Single applicant package',
                'description' => '<p>Sponsored employment opportunities across Australia in healthcare, engineering, trades, and hospitality sectors.</p><p>We secure eligible employer sponsorships (TSS visa subclass 482), manage your Skills Assessment where required, and handle all immigration documentation through to visa grant.</p>',
                'features' => [
                    'Employer TSS 482 sponsorship matching and coordination',
                    '40% commitment fee to start — refundable if interview fails',
                    'Skills Assessment pathway advisory where applicable',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee with partial refund protection',
                    'Pathway to Permanent Residency (subclass 186/189)',
                    'High-demand sectors with excellent salary packages in AUD',
                ],
                'pricing_type' => 'fixed',
                'price' => round(200000 / 12),
                'processing_fee' => round(40000 / 12),
                'currency' => 'USD',
                'duration' => '4-6 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-sun-fill',
                'color' => '#FD7E14',
                'order' => 13,
                'is_active' => true,
            ],
            [
                'title' => 'New Zealand Job Placement – Individual Package',
                'slug' => 'job-placement-new-zealand-single',
                'category' => 'job_abroad',
                'country' => 'New Zealand',
                'subtitle' => 'Sponsored work relocation to New Zealand — Single applicant package',
                'description' => '<p>Accredited employer-sponsored job placements across New Zealand in nursing, agriculture, hospitality, and construction.</p><p>We work with Accredited New Zealand Employers (AEWV holders) to secure your work visa, match you with relevant roles, and support your settlement on arrival.</p>',
                'features' => [
                    'Accredited Employer Work Visa (AEWV) job matching',
                    '40% commitment fee to start — refundable if interview fails',
                    'NZ employer direct interview preparation',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee with partial refund protection',
                    'Pathway to NZ Skilled Migrant Residence visa',
                    'Exceptional quality of life and work-life balance',
                ],
                'pricing_type' => 'fixed',
                'price' => round(200000 / 12),
                'processing_fee' => round(40000 / 12),
                'currency' => 'USD',
                'duration' => '4-6 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-tree-fill',
                'color' => '#198754',
                'order' => 14,
                'is_active' => true,
            ],

            // ---- CATEGORY 3: Denmark, Norway, Ireland ----
            [
                'title' => 'Denmark Job Placement',
                'slug' => 'job-placement-denmark',
                'category' => 'job_abroad',
                'country' => 'Denmark',
                'subtitle' => 'Sponsored work relocation to Denmark with full visa support',
                'description' => '<p>Sponsored job placements across Denmark in healthcare, engineering, IT, logistics, and skilled trades.</p><p>Under the Danish Positive List and Pay Limit schemes, we secure your employer letter, handle your residence and work permit, and support your integration into Danish workplace culture.</p>',
                'features' => [
                    'Danish Positive List / Pay Limit employer matching',
                    '40% commitment fee to start — refundable if interview fails',
                    'Work and residence permit documentation assembly',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee — GHS 27,840 refundable if unsuccessful',
                    'Among the highest salaries in Europe for skilled workers',
                    'Excellent social security, healthcare, and work-life balance',
                ],
                'pricing_type' => 'fixed',
                'price' => round(150000 / 12), // GHS 150,000 → ~$12,500 USD
                'processing_fee' => round(30000 / 12), // 40% commitment → ~$2,500 USD
                'currency' => 'USD',
                'duration' => '4-6 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-buildings-fill',
                'color' => '#C0392B',
                'order' => 15,
                'is_active' => true,
            ],
            [
                'title' => 'Norway Job Placement',
                'slug' => 'job-placement-norway',
                'category' => 'job_abroad',
                'country' => 'Norway',
                'subtitle' => 'Sponsored work relocation to Norway with full visa support',
                'description' => '<p>Sponsored employment across Norway in oil & gas, maritime, healthcare, construction, and IT sectors.</p><p>We secure skilled worker visas under the Norwegian Immigration Directorate (UDI) framework, coordinate your employer job offer, and handle all documentation for a seamless relocation.</p>',
                'features' => [
                    'UDI-compliant skilled worker visa coordination',
                    '40% commitment fee to start — refundable if interview fails',
                    'Employer NOK salary verification and contract support',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee with partial refund protection',
                    'Top-tier salaries in NOK with strong workers rights',
                    'Access to universal healthcare and social security from day one',
                ],
                'pricing_type' => 'fixed',
                'price' => round(150000 / 12),
                'processing_fee' => round(30000 / 12),
                'currency' => 'USD',
                'duration' => '4-6 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-snow2',
                'color' => '#2980B9',
                'order' => 16,
                'is_active' => true,
            ],
            [
                'title' => 'Ireland Job Placement',
                'slug' => 'job-placement-ireland',
                'category' => 'job_abroad',
                'country' => 'Ireland',
                'subtitle' => 'Sponsored work relocation to Ireland with full visa support',
                'description' => '<p>Sponsored job placements across Ireland in healthcare, hospitality, logistics, construction, and tech sectors.</p><p>We secure Critical Skills or General Employment Permit holders, coordinate employer job offers, and manage your Irish Residence Permit (IRP) application for a stress-free relocation.</p>',
                'features' => [
                    'Critical Skills / General Employment Permit employer matching',
                    '40% commitment fee to start — refundable if interview fails',
                    'Irish PPS number and IRP registration assistance',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee with partial refund protection',
                    'English-speaking EU hub with excellent career growth',
                    'Clear pathway to Irish Long-Term Residency',
                ],
                'pricing_type' => 'fixed',
                'price' => round(150000 / 12),
                'processing_fee' => round(30000 / 12),
                'currency' => 'USD',
                'duration' => '4-6 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-box-seam-fill',
                'color' => '#27AE60',
                'order' => 17,
                'is_active' => true,
            ],

            // ---- CATEGORY 4: Poland, Serbia, Montenegro ----
            [
                'title' => 'Poland Job Placement',
                'slug' => 'job-placement-poland',
                'category' => 'job_abroad',
                'country' => 'Poland',
                'subtitle' => 'Affordable sponsored work relocation to Poland',
                'description' => '<p>Entry-friendly job placements across Poland in manufacturing, construction, warehousing, agriculture, and services.</p><p>We coordinate your employer work permit (zezwolenie na pracę), assist with temporary residence applications, and provide airport reception and accommodation support on arrival.</p>',
                'features' => [
                    'Polish employer work permit (zezwolenie) coordination',
                    '40% commitment fee to start — refundable if interview fails',
                    'Temporary residence permit application support',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee — GHS 12,992 refundable if unsuccessful',
                    'Most affordable European relocation option with strong demand',
                    'Fast processing and growing job market across sectors',
                ],
                'pricing_type' => 'fixed',
                'price' => round(70000 / 12), // GHS 70,000 → ~$5,833 USD
                'processing_fee' => round(14000 / 12), // 40% commitment → ~$1,167 USD
                'currency' => 'USD',
                'duration' => '3-4 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-hammer',
                'color' => '#8E44AD',
                'order' => 18,
                'is_active' => true,
            ],
            [
                'title' => 'Serbia Job Placement',
                'slug' => 'job-placement-serbia',
                'category' => 'job_abroad',
                'country' => 'Serbia',
                'subtitle' => 'Affordable sponsored work relocation to Serbia',
                'description' => '<p>Job placements across Serbia in manufacturing, IT, hospitality, construction, and agriculture.</p><p>We secure your Serbian employer work permit, handle temporary residence and registration requirements, and provide full pre-departure advisory to make your relocation smooth and straightforward.</p>',
                'features' => [
                    'Serbian employer work permit coordination',
                    '40% commitment fee to start — refundable if interview fails',
                    'Temporary residence and local registration support',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee with partial refund protection',
                    'Fast-growing economy with increasing employer demand',
                    'Low cost of living with attractive Euro-adjacent salaries',
                ],
                'pricing_type' => 'fixed',
                'price' => round(70000 / 12),
                'processing_fee' => round(14000 / 12),
                'currency' => 'USD',
                'duration' => '3-4 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-gear-wide-connected',
                'color' => '#7F8C8D',
                'order' => 19,
                'is_active' => true,
            ],
            [
                'title' => 'Montenegro Job Placement',
                'slug' => 'job-placement-montenegro',
                'category' => 'job_abroad',
                'country' => 'Montenegro',
                'subtitle' => 'Affordable sponsored work relocation to Montenegro',
                'description' => '<p>Job placements across Montenegro in tourism, hospitality, construction, and services — a fast-growing Adriatic economy.</p><p>We secure employer work authorization, manage your temporary residence permit, and provide end-to-end pre-departure support to get you started in this beautiful European destination.</p>',
                'features' => [
                    'Montenegro employer work authorization coordination',
                    '40% commitment fee to start — refundable if interview fails',
                    'Temporary residence permit application support',
                    'Progress fee payable only upon confirmed offer letter',
                ],
                'why_choose_program' => [
                    'Risk-free interview guarantee with partial refund protection',
                    'Booming tourism sector with seasonal and year-round roles',
                    'Affordable living with EU accession trajectory',
                ],
                'pricing_type' => 'fixed',
                'price' => round(70000 / 12),
                'processing_fee' => round(14000 / 12),
                'currency' => 'USD',
                'duration' => '3-4 Months Process',
                'class_size' => 'Individual Slot',
                'icon' => 'bi-water',
                'color' => '#16A085',
                'order' => 20,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }
    }
}
