<?php

namespace App\Domain\Invoice\Entities;

use App\Domain\Enums\StatusEnum;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class InvoiceModel extends Model
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
        'due_date' => 'datetime'
    ];
    protected static function boot()
    {
        parent::boot();

    }

    // Getter for ID
    public function getId(): string
    {
        return $this->id; // Return the invoice ID
    }
    public function company()
    {
        return $this->belongsTo(CompanyModel::class, 'company_id'); // Make sure to use the correct foreign key
    }

    // Getter for Status
    public function getStatus(): StatusEnum
    {
        return $this->status;
    }
    public function getNumber(): string
    {
        return $this->number;
    }
    public function getDate(): string
    {
        return $this->date;
    }
    public function getDueDate(): string
    {
        return $this->due_date; // Return the invoice status
    }

    public function getBilledCompany(): CompanyModel
    {
        return $this->billedCompany;
    }

    public function getCompany(): CompanyModel
    {
        return $this->company;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function billedCompany()
    {
        return $this->belongsTo(CompanyModel::class, 'billed_company_id'); // Ensure this foreign key exists in the invoices table
    }

    public function products()
    {
        return $this->belongsToMany(ProductModel::class, 'invoice_product_lines', 'invoice_id', 'product_id')
            ->withPivot('quantity'); // Access pivot data
    }
}
