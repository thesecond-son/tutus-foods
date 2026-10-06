<?php

namespace Tests\Feature;

use App\Http\Controllers\Web\CheckoutController;
use App\Models\DeliveryOption;
use App\Models\DeliveryWeightRange; // ⚠️ adjust namespace/model name if yours differs
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use ReflectionClass;
use Tests\TestCase;

class DeliveryFeeCalculationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Calls the private calculateDeliveryFee($cart, $request) method directly.
     *
     * This is the single method both createSquarePayment() and
     * createPayPalOrder() rely on for the actual charge — testing it here
     * once covers both gateways, since there's no gateway-specific fee
     * logic left after the fix.
     */
    protected function callCalculateDeliveryFee(array $cart, Request $request): float
    {
        $controller = new CheckoutController();
        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('calculateDeliveryFee');
        $method->setAccessible(true);

        return $method->invoke($controller, $cart, $request);
    }

    protected function cartWithWeight(float $totalWeight): array
    {
        // One item whose weight * quantity equals $totalWeight
        return [
            'item1' => [
                'id' => 1,
                'price' => 10.00,
                'quantity' => 1,
                'weight' => $totalWeight,
            ],
        ];
    }

    protected function deliveryRequest(int $optionId): Request
    {
        return Request::create('/checkout', 'POST', [
            'delivery_type' => 'delivery',
            'delivery_option_id' => $optionId,
        ]);
    }

    /** @test */
    public function flat_rate_delivery_always_charges_the_configured_rate()
    {
        $option = DeliveryOption::create([
            'name' => 'Standard Delivery',
            'calculated_by' => 'flat_rate',
            'rate' => 4.99,
        ]);

        $fee = $this->callCalculateDeliveryFee(
            $this->cartWithWeight(2.5),
            $this->deliveryRequest($option->id)
        );

        $this->assertEquals(4.99, $fee);
    }

    /** @test */
    public function weight_based_delivery_charges_the_matching_range_rate()
    {
        $option = DeliveryOption::create([
            'name' => 'Weight Based',
            'calculated_by' => 'weight_based',
            'rate' => 8.50, // default/fallback rate
        ]);

        $option->weightRanges()->create(['min_weight' => 0, 'max_weight' => 2, 'rate' => 3.50]);
        $option->weightRanges()->create(['min_weight' => 2.01, 'max_weight' => 5, 'rate' => 6.00]);

        $fee = $this->callCalculateDeliveryFee(
            $this->cartWithWeight(1.5), // falls in the 0–2 range
            $this->deliveryRequest($option->id)
        );

        $this->assertEquals(3.50, $fee);
    }

    /**
     * @test
     * This is the exact bug from the puff puff order: a weight that falls
     * in the gap between configured ranges (here, between 2 and 2.01) must
     * fall back to the delivery option's own default rate — not silently 0.
     */
    public function weight_based_delivery_falls_back_to_default_rate_when_no_range_matches()
    {
        $option = DeliveryOption::create([
            'name' => 'Weight Based',
            'calculated_by' => 'weight_based',
            'rate' => 8.50, // the documented "default charge if no range matches"
        ]);

        $option->weightRanges()->create(['min_weight' => 0, 'max_weight' => 2, 'rate' => 3.50]);
        $option->weightRanges()->create(['min_weight' => 5, 'max_weight' => 10, 'rate' => 12.00]);

        // 2.5kg falls in the gap between the two configured ranges
        $fee = $this->callCalculateDeliveryFee(
            $this->cartWithWeight(2.5),
            $this->deliveryRequest($option->id)
        );

        $this->assertEquals(8.50, $fee);
        $this->assertNotEquals(0, $fee); // guards against the regression directly
    }

    /** @test */
    public function weight_based_delivery_falls_back_to_default_rate_when_weight_exceeds_every_range()
    {
        $option = DeliveryOption::create([
            'name' => 'Weight Based',
            'calculated_by' => 'weight_based',
            'rate' => 15.00,
        ]);

        $option->weightRanges()->create(['min_weight' => 0, 'max_weight' => 5, 'rate' => 5.00]);

        // Way above the only configured range
        $fee = $this->callCalculateDeliveryFee(
            $this->cartWithWeight(50),
            $this->deliveryRequest($option->id)
        );

        $this->assertEquals(15.00, $fee);
    }

    /** @test */
    public function four_packs_of_a_heavy_item_can_reproduce_the_reported_incident()
    {
        // Mirrors the real scenario: 4 packs of puff puff, each 0.6kg,
        // total 2.4kg — which must land in the gap between ranges.
        $option = DeliveryOption::create([
            'name' => 'Weight Based',
            'calculated_by' => 'weight_based',
            'rate' => 8.50,
        ]);

        $option->weightRanges()->create(['min_weight' => 0, 'max_weight' => 2, 'rate' => 3.50]);
        $option->weightRanges()->create(['min_weight' => 3, 'max_weight' => 6, 'rate' => 6.00]);

        $cart = [
            'puffpuff' => ['id' => 9, 'price' => 5.00, 'quantity' => 4, 'weight' => 0.6],
        ];

        $fee = $this->callCalculateDeliveryFee($cart, $this->deliveryRequest($option->id));

        $this->assertEquals(8.50, $fee);
    }

    /** @test */
    public function pickup_orders_are_never_charged_a_delivery_fee()
    {
        $option = DeliveryOption::create([
            'name' => 'Weight Based',
            'calculated_by' => 'weight_based',
            'rate' => 8.50,
        ]);
        $option->weightRanges()->create(['min_weight' => 0, 'max_weight' => 5, 'rate' => 5.00]);

        $request = Request::create('/checkout', 'POST', [
            'delivery_type' => 'pickup',
            'delivery_option_id' => $option->id, // present but irrelevant for pickup
        ]);

        $fee = $this->callCalculateDeliveryFee($this->cartWithWeight(2), $request);

        $this->assertEquals(0, $fee);
    }

    /** @test */
    public function a_tampered_client_delivery_fee_is_ignored_entirely()
    {
        $option = DeliveryOption::create([
            'name' => 'Weight Based',
            'calculated_by' => 'weight_based',
            'rate' => 8.50,
        ]);
        $option->weightRanges()->create(['min_weight' => 0, 'max_weight' => 2, 'rate' => 3.50]);

        // Client claims delivery_fee = 0.01, trying to undercut the real charge
        $request = Request::create('/checkout', 'POST', [
            'delivery_type' => 'delivery',
            'delivery_option_id' => $option->id,
            'delivery_fee' => 0.01,
        ]);

        $fee = $this->callCalculateDeliveryFee($this->cartWithWeight(1), $request);

        $this->assertEquals(3.50, $fee);
        $this->assertNotEquals(0.01, $fee);
    }
}