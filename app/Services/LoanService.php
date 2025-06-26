<?php

namespace App\Services;

use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Carbon\Carbon;

class LoanService
{
    private const MAX_ACTIVE_LOANS = 3;
    private const LOAN_DURATION_DAYS = 14;

    public function validateLoanRequest(User $user, Book $book): LoanValidationResult
    {
        if (!$user) {
            return LoanValidationResult::invalid('Debes iniciar sesión para solicitar un préstamo.');
        }

        if (!$this->isEligibleStudent($user)) {
            return LoanValidationResult::invalid('Solo los estudiantes pueden solicitar préstamos.');
        }

        if (!$book->isAvailable()) {
            return LoanValidationResult::invalid('Este libro no está disponible para préstamo.');
        }

        if ($this->userHasActiveLoanForBook($user, $book)) {
            return LoanValidationResult::invalid('Ya tienes este libro en préstamo.');
        }

        if ($this->hasReachedLoanLimit($user)) {
            return LoanValidationResult::invalid('Has alcanzado el límite máximo de 3 préstamos activos.');
        }

        return LoanValidationResult::valid();
    }

    public function createLoan(User $user, Book $book, string $notes = ''): Loan
    {
        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => Carbon::now(),
            'due_date' => $this->calculateDueDate(),
            'status' => 'active',
            'notes' => $notes,
        ]);

        $this->decreaseBookAvailability($book);

        return $loan;
    }

    public function userHasActiveLoanForBook(?User $user, Book $book): bool
    {
        if (!$user) {
            return false;
        }

        return $book->activeLoans()
            ->where('user_id', $user->id)
            ->exists();
    }

    private function isEligibleStudent(User $user): bool
    {
        return $user->isStudent();
    }

    private function hasReachedLoanLimit(User $user): bool
    {
        return $user->activeLoans()->count() >= self::MAX_ACTIVE_LOANS;
    }

    private function calculateDueDate(): Carbon
    {
        return Carbon::now()->addDays(self::LOAN_DURATION_DAYS);
    }

    private function decreaseBookAvailability(Book $book): void
    {
        $book->decrement('available_quantity');
    }
}
