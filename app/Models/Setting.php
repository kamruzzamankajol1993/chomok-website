<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'restaurant_name', 'website_url', 'admin_panel_url', 'address', 'phone', 'logo', 'icon',
        'opening_time', 'closing_time', 'header_hours_text', 'tax_rate', 'tax_label',
        'tax_registration_number', 'service_charge', 'tax_included', 'delivery_charge_enabled',
        'invoice_prefix', 'invoice_starting_number', 'invoice_footer_note',
        'print_paper_size', 'show_logo_invoice',
        'delivery_within_1km_charge', 'delivery_outside_base_charge', 'delivery_per_km_charge',
    ];

    protected function casts(): array
    {
        return [
            'tax_rate' => 'decimal:2',
            'service_charge' => 'decimal:2',
            'tax_included' => 'boolean',
            'delivery_charge_enabled' => 'boolean',
            'show_logo_invoice' => 'boolean',
            'delivery_within_1km_charge' => 'decimal:2',
            'delivery_outside_base_charge' => 'decimal:2',
            'delivery_per_km_charge' => 'decimal:2',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate([], [
            'restaurant_name' => 'Chomok',
            'header_hours_text' => 'Opening & Closing time (Saturday to Thursday) : 12PM to 10:30PM (Friday 2PM to 10:30PM)',
            'tax_label' => 'VAT',
            'delivery_charge_enabled' => true,
            'invoice_prefix' => 'INV-',
            'invoice_starting_number' => 1,
            'print_paper_size' => '80mm',
        ]);
    }
}
