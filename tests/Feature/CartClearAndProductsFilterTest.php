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
