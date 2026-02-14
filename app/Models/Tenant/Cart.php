<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function getItemCountAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Get or create a cart for the current user/session.
     */
    public static function getCart(?int $userId = null, ?string $sessionId = null): self
    {
        if ($userId) {
            return self::firstOrCreate(['user_id' => $userId]);
        }

        return self::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Merge a guest cart into a user cart upon login.
     */
    public function mergeGuestCart(string $sessionId): void
    {
        $guestCart = self::where('session_id', $sessionId)->first();

        if (!$guestCart) {
            return;
        }

        foreach ($guestCart->items as $guestItem) {
            $existingItem = $this->items()
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity,
                ]);
            } else {
                $this->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                ]);
            }
        }

        $guestCart->items()->delete();
        $guestCart->delete();
    }
}
