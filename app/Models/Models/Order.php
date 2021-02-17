<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'comment',
        'amount',
        'status',
    ];
    
    public const STATUSES = [
        0 => 'Новый',
        1 => 'Обработан',
        2 => 'Оплачен',
        3 => 'Доставлен',
        4 => 'Завершен',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
    * @param $value
    * @return \Carbon\Carbon|false
    */
   public function getCreatedAtAttribute($value)
   {
       return Carbon::createFromFormat('Y-m-d H:i:s', $value)->timezone('Europe/Kiev');
   }

    /**
     * @param $value
     * @return \Carbon\Carbon|false
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->timezone('Europe/Kiev');
    }
}
