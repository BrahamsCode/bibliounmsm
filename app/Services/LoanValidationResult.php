<?php
namespace App\Services;
use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;

/**
 * Class LoanValidationResult
 * Represents the result of a loan validation process.
 */

class LoanValidationResult
{
    private bool $isValid;
    private ?string $errorMessage;

    private function __construct(bool $isValid, ?string $errorMessage = null)
    {
        $this->isValid = $isValid;
        $this->errorMessage = $errorMessage;
    }

    public static function valid(): self
    {
        return new self(true);
    }

    public static function invalid(string $errorMessage): self
    {
        return new self(false, $errorMessage);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }
}
