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
