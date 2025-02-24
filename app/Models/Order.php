<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';

    // Desabilita o auto increment, pois estamos usando UUID
    public $incrementing = false;

    // Define o tipo da chave primária como string
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'client_name',
        'order_date',
        'delivery_date',
        'status'
    ];

    // Gera automaticamente um UUID na criação do registro
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model): void {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
