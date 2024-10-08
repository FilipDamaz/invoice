<?php

// app/Domain/Invoice/Entities/Invoice.php
namespace App\Domain\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    // Define the table if it's not the plural of the model name
    protected $table = 'invoices';

    // Optionally, define fillable attributes
    protected $fillable = ['id', 'number', 'date', 'due_date', 'status'];

    protected $keyType = 'string'; // Ensure the key type is string
    public $incrementing = false; // Disable auto-incrementing IDs

    // Specify which attributes should be cast to Carbon instances
    protected $casts = [
        'date' => 'datetime',
        'due_date' => 'datetime',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid(); // Automatically generate a UUID
        });
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id'); // Make sure to use the correct foreign key
    }

    public function billedCompany()
    {
        return $this->belongsTo(Company::class, 'billed_company_id'); // Ensure this foreign key exists in the invoices table
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_product_lines')
            ->withPivot('quantity'); // Add this to access pivot data
    }
}
