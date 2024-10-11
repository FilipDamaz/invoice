<?php

namespace App\Domain\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    // Define the table if it's not the plural of the model name
    protected $table = 'companies';
    protected $keyType = 'string'; // Specify that the ID is a string
    public $incrementing = false; // Disable auto-incrementing IDs

    protected $fillable = [
        'id',
        'name',
        'street',
        'city',
        'zip',
        'phone',
        'email',
    ];

    // Getter methods
    public function getId(): string
    {
        return $this->attributes['id'];
    }

    public function getName(): string
    {
        return $this->attributes['name'];
    }

    public function getStreet(): string
    {
        return $this->attributes['street'];
    }

    public function getCity(): string
    {
        return $this->attributes['city'];
    }

    public function getZip(): string
    {
        return $this->attributes['zip'];
    }

    public function getPhone(): string
    {
        return $this->attributes['phone'];
    }

    public function getEmail(): string
    {
        return $this->attributes['email'];
    }

    public function invoices()
    {
        return $this->hasMany(InvoiceModel::class, 'company_id');
    }
}
