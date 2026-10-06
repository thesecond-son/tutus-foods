<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartVariantTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper: create a product that has a required "Flavour" variant
     * with two selectable values.
     */
    protected function makeProductWithVariant(): Product
    {
        return Product::factory()->create([
            'category_id' => Category::factory()->create()->id,
            'base_price' => 12.00,
            'inventory' => 10,
            'active' => true,
            'product_options' => [
                [
                    'name' => 'Flavour',
                    'values' => [
                        ['value' => 'Vanilla'],
                        ['value' => 'Chocolate'],
                    ],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_rejects_adding_a_variant_product_with_no_option_selected()
    {
        $product = $this->makeProductWithVariant();

        $response = $this->postJson("/cart/add/{$product->id}", [
            'quantity' => 1,
            // no "options" sent at all — this is exactly the bug we hit
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $response->assertJsonFragment(['missing_options' => ['Flavour']]);

        // Nothing should have been persisted to the cart
        $this->assertEmpty(session('cart', []));
    }

    /** @test */
    public function it_rejects_adding_a_variant_product_with_an_empty_option_value()
    {
        $product = $this->makeProductWithVariant();

        $response = $this->postJson("/cart/add/{$product->id}", [
            'quantity' => 1,
            'options' => ['Flavour' => ''], // sent, but blank — same bug, different shape
        ]);

        $response->assertStatus(422);
        $this->assertEmpty(session('cart', []));
    }

    /** @test */
    public function it_accepts_adding_a_variant_product_once_an_option_is_selected()
    {
        $product = $this->makeProductWithVariant();

        $response = $this->postJson("/cart/add/{$product->id}", [
            'quantity' => 2,
            'options' => ['Flavour' => 'Vanilla'],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $cart = session('cart');
        $this->assertNotEmpty($cart);

        $item = collect($cart)->first();
        $this->assertEquals($product->id, $item['id']);
        $this->assertEquals(2, $item['quantity']);
        $this->assertEquals(['Flavour' => 'Vanilla'], $item['options']);
    }

    /** @test */
    public function it_adds_a_product_with_no_variants_normally()
    {
        $product = Product::factory()->create([
            'category_id' => Category::factory()->create()->id,
            'base_price' => 8.00,
            'inventory' => 10,
            'active' => true,
            'product_options' => [], // no variants configured at all
        ]);

        $response = $this->postJson("/cart/add/{$product->id}", [
            'quantity' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertNotEmpty(session('cart'));
    }

    /** @test */
    public function a_non_ajax_request_missing_a_variant_redirects_back_with_an_error_instead_of_a_false_success()
    {
        $product = $this->makeProductWithVariant();

        // Plain form POST — no JSON headers — mirrors the directAddForm path
        $response = $this->post("/cart/add/{$product->id}", [
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('options');
        $this->assertEmpty(session('cart', []));
    }

    /** @test */
    public function two_different_variant_selections_are_stored_as_separate_cart_lines()
    {
        $product = $this->makeProductWithVariant();

        $this->postJson("/cart/add/{$product->id}", [
            'quantity' => 1,
            'options' => ['Flavour' => 'Vanilla'],
        ]);

        $this->postJson("/cart/add/{$product->id}", [
            'quantity' => 1,
            'options' => ['Flavour' => 'Chocolate'],
        ]);

        $cart = session('cart');
        $this->assertCount(2, $cart);
    }
}