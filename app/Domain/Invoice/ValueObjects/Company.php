<?php

namespace App\Domain\Invoice\ValueObjects;

class Company
{
    public function __construct(
        public string $name,
        public string $street,
        public string $city,
        public string $zip,
        public string $phone,
        public ?string $email = null
    ) {}
}
