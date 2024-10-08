<?php

// app/Domain/Invoice/Entities/Product.php
namespace App\Domain\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $keyType = 'string'; // Specify that the ID is a string
    public $incrementing = false; // Disable auto-incrementing IDs

    protected $fillable = [
        'id',
        'name',
        'price',
        'currency',
    ];

    public function invoiceProductLines()
    {
        return $this->hasMany(InvoiceProductLine::class);
    }
}
