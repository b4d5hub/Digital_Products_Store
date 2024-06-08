<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id','number','total_price','status','shipping_price','notes'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($order) {
    //         $receiver = auth()->user();
    //         Notification::make()
    //             ->title('Order Number #' .Order::get()->number. 'Completed')
    //             ->sendToDatabase($receiver);
    //     });
    // }

    protected static function boot()
    {
        parent::boot();
    
        static::created(function ($order) {
            $receiver = auth()->user();

            if (!$receiver) {
                $receiver = User::first(); // Retrieve the first user from the users table
            }
            $orderNumber = $order->number;
            $title = "Order Number #$orderNumber Completed";

            Notification::make()
                ->title($title)
                ->sendToDatabase($receiver);
        });
    }
    

}
