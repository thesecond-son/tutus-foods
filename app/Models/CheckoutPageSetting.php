<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutPageSetting extends Model
{
    /**
     * Default notice for seasonal (e.g. Shop Christmas) products.
     * {date} is replaced with the product's season start date.
     */
    public const DEFAULT_SEASONAL_NOTICE = 'This product will be available from {date}.';

    protected $fillable = [
        'shipping_notice',
        'seasonal_notice',
    ];

    /**
     * Always return the single settings row, creating it if it doesn't exist.
     */
    public static function instance(): static
    {
        return static::firstOrCreate([], [
            'shipping_notice' => 'We post orders from Monday to Thursday only. Orders received after 10am on Thursday will be posted on Monday: Order by Thursday before 10am.',
            'seasonal_notice' => self::DEFAULT_SEASONAL_NOTICE,
        ]);
    }

    /**
     * Build the checkout notice for seasonal products, filling in {date}.
     * Returns null when the text needs a date but the product has none.
     */
    public function seasonalNoticeFor(?string $formattedDate): ?string
    {
        $template = trim((string) $this->seasonal_notice) ?: self::DEFAULT_SEASONAL_NOTICE;

        if (str_contains($template, '{date}')) {
            return $formattedDate ? str_replace('{date}', $formattedDate, $template) : null;
        }

        return $template;
    }
}
