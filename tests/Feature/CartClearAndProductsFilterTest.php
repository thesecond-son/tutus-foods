<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartClearAndProductsFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function clear_cart_form_redirects_back_to_the_cart_page()
    {
        $this->withSession(['cart' => ['1' => ['id' => 1, 'quantity' => 1, 'price' => 5]]])
            ->post('/cart/clear')
            ->assertRedirect(route('cart.index'));

        $this->assertNull(session('cart'));
    }

    /** @test */
    public function clear_cart_still_returns_json_for_the_sidebar_fetch()
    {
        $this->withSession(['cart' => ['1' => ['id' => 1, 'quantity' => 1, 'price' => 5]]])
            ->postJson('/cart/clear')
            ->assertOk()
            ->assertJson(['success' => true]);
    }

    /**
     * Regression: an unclosed @section made Blade print the page content
     * before <!DOCTYPE html>/<head>, causing a flash of unstyled content.
     */
    public static function layoutPages(): array
    {
        return [
            'all products' => ['/products', null],
            'cupcakes' => ['/products/category/cupcakes', 'cupcakes'],
            'checkout' => ['/checkout', null],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('layoutPages')]
    public function test_pages_start_with_the_doctype_and_render_content_inside_main(string $url, ?string $categorySlug)
    {
        if ($categorySlug) {
            \App\Models\Category::create(['name' => ucfirst($categorySlug), 'slug' => $categorySlug]);
        }

        $html = $this->get($url)->assertOk()->getContent();

        $this->assertStringStartsWith('<!DOCTYPE html>', ltrim($html));
        $this->assertSame(1, substr_count(strtolower($html), '<!doctype html>'));
        $this->assertGreaterThan(strpos($html, '<main>'), strpos($html, 'id="productModalWrapper"') ?: strpos($html, 'id="checkoutForm"'));
    }

    /** @test */
    public function products_page_renders_the_filter_toggle_before_the_sidebar_with_its_script()
    {
        Product::factory()->create();

        $html = $this->get('/products')->assertOk()->getContent();

        $buttonPos = strpos($html, 'id="toggleSidebar"');
        $sidebarPos = strpos($html, 'id="filterSidebar"');

        $this->assertNotFalse($buttonPos);
        $this->assertNotFalse($sidebarPos);
        $this->assertLessThan($sidebarPos, $buttonPos, 'Toggle button should come before the sidebar so it opens below it.');
        $this->assertStringContainsString('aria-controls="filterSidebar"', $html);
        $this->assertStringContainsString('function initFilterToggle()', $html);
        $this->assertSame(1, substr_count($html, 'function initFilterToggle()'));
    }
}
