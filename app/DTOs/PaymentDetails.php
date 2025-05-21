<?php

namespace App\DTOs;

class PaymentDetails
{
    public function __construct(
        public string $method,
        public string $reference,
        public ?string $cvc = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['method'],
            $data['reference'],
            $data['cvc'] ?? null
        );
    }
}
