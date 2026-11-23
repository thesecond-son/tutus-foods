<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutPickupOnlyTest extends TestCase
{
    use RefreshDatabase;

    protected function cartFor(Product $product, bool $pickupOnlySnapshot): array
    {
        return [
            (string) $product->id => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'quantity' => 1,
                'price' => $product->base_price,
                'image' => $product->image_path,
                'options' => [],
                'pickup_only' => $pickupOnlySnapshot,
                'pre_order' => false,
                'pre_order_expectation' => null,
                'weight' => 0,
                'seasonal' => false,
                'season_start' => null,
                'season_end' => null,
            ],
        ];
    }

    /** @test */
    public function checkout_uses_current_pickup_only_setting_not_the_stale_cart_snapshot()
    {
        $product = Product::factory()->create(['pickup_only' => false]);

        $response = $this->withSession(['cart' => $this->cartFor($product, true)])
            ->get('/checkout');

        $response->assertOk();
        $response->assertDontSee('Some products are pickup-only');
        $this->assertFalse(session('cart')[(string) $product->id]['pickup_only']);
    }

    /** @test */
    public function checkout_shows_pickup_only_when_the_product_is_switched_on_after_adding()
    {
        $product = Product::factory()->create(['pickup_only' => true]);

        $this->withSession(['cart' => $this->cartFor($product, false)])
            ->get('/checkout')
            ->assertOk()
            ->assertSee('Some products are pickup-only');
    }

    /** @test */
    public function seasonal_products_offer_delivery_when_pickup_only_is_off()
    {
        $product = Product::factory()->create([
            'pickup_only' => false,
            'seasonal' => true,
            'season_start' => now()->addDays(10),
            'season_end' => now()->addDays(40),
        ]);

        $this->withSession(['cart' => $this->cartFor($product, false)])
            ->get('/checkout')
            ->assertOk()
            ->assertSee('<option value="delivery">Delivery</option>', false);
    }

    /** @test */
    public function seasonal_pickup_only_products_still_offer_pickup_only()
    {
        $product = Product::factory()->create([
            'pickup_only' => true,
            'seasonal' => true,
        ]);

        $this->withSession(['cart' => $this->cartFor($product, false)])
            ->get('/checkout')
            ->assertOk()
            ->assertSee('Some products are pickup-only')
            ->assertDontSee('<option value="delivery">Delivery</option>', false);
    }

    /** @test */
    public function seasonal_notice_uses_the_default_wording_with_the_season_date()
    {
        $product = Product::factory()->create([
            'seasonal' => true,
            'season_start' => '2026-12-01',
            'pre_order_expectation' => null,
        ]);

        $this->withSession(['cart' => $this->cartFor($product, false)])
            ->get('/checkout')
            ->assertOk()
            ->assertSee('This product will be available from 1 December 2026.')
            ->assertDontSee('Pickup for this product');
    }

    /** @test */
    public function seasonal_notice_uses_the_wording_from_checkout_page_settings()
    {
        \App\Models\CheckoutPageSetting::instance()->update([
            'seasonal_notice' => 'Christmas orders ship or collect from {date}!',
        ]);

        $product = Product::factory()->create([
            'seasonal' => true,
            'season_start' => '2026-12-01',
            'pre_order_expectation' => null,
        ]);

        $this->withSession(['cart' => $this->cartFor($product, false)])
            ->get('/checkout')
            ->assertOk()
            ->assertSee('Christmas orders ship or collect from 1 December 2026!');
    }

    /** @test */
    public function product_pre_order_expectation_overrides_the_seasonal_notice()
    {
        $product = Product::factory()->create([
            'seasonal' => true,
            'season_start' => '2026-12-01',
            'pre_order_expectation' => 'Ready from the 18th of December',
        ]);

        $this->withSession(['cart' => $this->cartFor($product, false)])
            ->get('/checkout')
            ->assertOk()
            ->assertSee('Ready from the 18th of December')
            ->assertDontSee('This product will be available from');
    }

    /** @test */
    public function delivery_is_rejected_server_side_for_pickup_only_products()
    {
        $product = Product::factory()->create(['pickup_only' => true]);

        $this->withSession(['cart' => $this->cartFor($product, false)])
            ->postJson('/checkout/square/create', [
                'delivery_type' => 'delivery',
                'customer_email' => 'test@example.com',
            ])
            ->assertStatus(422);
    }

    /** @test */
    public function cart_price_is_refreshed_from_the_product()
    {
        $product = Product::factory()->create(['pickup_only' => false]);
        $cart = $this->cartFor($product, false);
        $cart[(string) $product->id]['price'] = 0.01;

        $this->withSession(['cart' => $cart])->get('/checkout')->assertOk();

        $this->assertEquals($product->base_price, session('cart')[(string) $product->id]['price']);
    }
}
