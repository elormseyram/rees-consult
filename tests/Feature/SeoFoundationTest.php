<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_seo_endpoints_are_available(): void
    {
        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: https://www.reesconsult.com/sitemap.xml');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<urlset', false);
    }

    public function test_homepage_exposes_primary_search_intent(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('IELTS, <span class="highlight">Study Abroad</span>', false)
            ->assertSee('Work Abroad Experts in Ghana', false);
    }
}