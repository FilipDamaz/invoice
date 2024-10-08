<?php

namespace App\Domain\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'company_id');
    }
}
