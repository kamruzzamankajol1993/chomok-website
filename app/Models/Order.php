<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id', 'client_id', 'created_by', 'order_number', 'invoice_sequence', 'source', 'order_type',
        'status', 'payment_type', 'payment_reference', 'split_cash', 'split_mfs', 'split_bank',
        'split_mfs_reference', 'split_bank_reference', 'customer_name', 'customer_phone',
        'customer_email', 'customer_address', 'delivery_address', 'subtotal', 'discount_type',
        'discount_value', 'discount', 'service_charge_rate', 'service_charge_amount', 'tax_label',
        'tax_rate', 'tax_amount', 'delivery_charge', 'grand_total', 'paid_amount', 'due_amount',
        'note', 'confirmed_at', 'notification_seen_at', 'notification_dismissed_at',
    ];

    protected function casts(): array
    {
        return [
            'invoice_sequence' => 'integer', 'subtotal' => 'decimal:2', 'discount_value' => 'decimal:2',
            'discount' => 'decimal:2', 'service_charge_rate' => 'decimal:2', 'service_charge_amount' => 'decimal:2',
            'tax_rate' => 'decimal:2', 'tax_amount' => 'decimal:2', 'delivery_charge' => 'decimal:2',
            'grand_total' => 'decimal:2', 'paid_amount' => 'decimal:2', 'due_amount' => 'decimal:2',
            'split_cash' => 'decimal:2', 'split_mfs' => 'decimal:2', 'split_bank' => 'decimal:2',
            'confirmed_at' => 'datetime', 'notification_seen_at' => 'datetime', 'notification_dismissed_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function client(): BelongsTo { return $this->belongsTo(Client::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}
