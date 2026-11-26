<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoMetaTest extends TestCase
{
    use RefreshDatabase;

    public function test_pages_have_seo_tags_without_double_escaping(): void
    {
        \App\Models\Category::create(['name' => 'Cupcakes', 'slug' => 'cupcakes']);

        foreach (['/products', '/products/category/cupcakes', '/cart', '/checkout'] as $path) {
            $html = $this->get($path)->assertOk()->getContent();
            $this->assertStringStartsWith('<!DOCTYPE html>', ltrim($html), $path);
            $this->assertStringContainsString('<meta property="og:title"', $html, $path);
            $this->assertStringContainsString('<meta name="twitter:card"', $html, $path);
            $this->assertStringContainsString('application/ld+json', $html, $path);
            $this->assertStringNotContainsString('&amp;#039;', $html, $path);
            $this->assertStringNotContainsString('googlebot" content="noindex', $html, $path);
        }

        $this->assertStringContainsString('noindex', $this->get('/cart')->getContent());
    }

    public function test_sitemap_and_robots(): void
    {
        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('products.index'), false);
        $this->get('/robots.txt')->assertOk();
    }
}
