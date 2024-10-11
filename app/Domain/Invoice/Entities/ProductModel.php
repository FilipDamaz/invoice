<?php

// app/Domain/InvoiceModel/Entities/ProductModel.php
namespace App\Domain\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $keyType = 'string'; // Specify that the ID is a string
    public $incrementing = false; // Disable auto-incrementing IDs

    protected $fillable = [
        'id',
        'name',
        'price',
        'currency',
    ];

    public function invoices()
    {
        return $this->belongsToMany(InvoiceModel::class, 'invoice_product_lines', 'product_id', 'invoice_id')
            ->withPivot('quantity'); // Access pivot data
    }


}
