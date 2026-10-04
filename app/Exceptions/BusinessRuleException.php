<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use RuntimeException;

/**
 * A document action refused for a business reason. The message is user-facing (translated).
 * Livewire screens turn it into a field error via toValidation().
 */
class BusinessRuleException extends RuntimeException
{
    /**
     * @param  array<string, mixed>  $replace
     */
    public static function make(string $key, array $replace = []): self
    {
        return new self(__($key, $replace));
    }

    public function toValidation(string $field = 'document'): ValidationException
    {
        return ValidationException::withMessages([$field => $this->getMessage()]);
    }
}
