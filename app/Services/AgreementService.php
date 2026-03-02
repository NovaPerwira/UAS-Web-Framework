<?php

namespace App\Services;

use App\Models\Agreement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AgreementService
{
    /**
     * Create a new Agreement in DRAFT status.
     *
     * Auto-generates agreement_number if not provided.
     * Saves as draft — content is NOT frozen yet.
     */
    public function create(array $data): Agreement
    {
        if (empty($data['agreement_number'])) {
            $data['agreement_number'] = $this->generateAgreementNumber();
        }

        $data['status'] = Agreement::DRAFT;

        return DB::transaction(function () use ($data) {
            return Agreement::create($data);
        });
    }

    /**
     * Update an existing Agreement (only allowed in DRAFT status).
     *
     * Throws a ValidationException if the agreement is not editable.
     */
    public function update(Agreement $agreement, array $data): Agreement
    {
        if (!$agreement->canEdit()) {
            throw ValidationException::withMessages([
                'status' => 'Agreement cannot be edited after it has been issued.',
            ]);
        }

        return DB::transaction(function () use ($agreement, $data) {
            $agreement->update($data);
            return $agreement->fresh();
        });
    }

    /**
     * Transition an Agreement to a new status.
     *
     * Enforces the allowed transition map and business rules:
     * - draft → issued: renders and freezes content snapshot
     * - issued → signed: permanently locks editing
     * - draft/issued → cancelled: blocks invoice creation
     *
     * @throws ValidationException on invalid transition
     */
    public function transition(Agreement $agreement, string $newStatus): Agreement
    {
        if (!$agreement->canTransitionTo($newStatus)) {
            throw ValidationException::withMessages([
                'status' => "Cannot transition from '{$agreement->status}' to '{$newStatus}'.",
            ]);
        }

        return DB::transaction(function () use ($agreement, $newStatus) {
            if ($newStatus === Agreement::ISSUED) {
                // Render and freeze the legal content snapshot
                $rendered = $this->renderContent($agreement);
                $agreement->update([
                    'status' => Agreement::ISSUED,
                    'rendered_content' => $rendered,
                ]);
            } else {
                $agreement->update(['status' => $newStatus]);
            }

            return $agreement->fresh();
        });
    }

    /**
     * Delete an agreement — blocked if it has linked invoices.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete(Agreement $agreement): void
    {
        if ($agreement->invoices()->exists()) {
            throw ValidationException::withMessages([
                'agreement' => 'Cannot delete an agreement that has linked invoices.',
            ]);
        }

        $agreement->delete();
    }

    /**
     * Render the agreement body as an immutable text snapshot.
     * Called when transitioning to ISSUED to permanently freeze the document.
     */
    private function renderContent(Agreement $agreement): string
    {
        return view('agreements.pdf', compact('agreement'))->render();
    }

    /**
     * Auto-generate a unique agreement number: AGR-YYYY-XXXX
     */
    private function generateAgreementNumber(): string
    {
        $year = now()->format('Y');
        $lastAgreement = Agreement::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->lockForUpdate()
            ->first();

        $sequence = 1;
        if ($lastAgreement && preg_match('/(\d+)$/', $lastAgreement->agreement_number, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }

        return 'AGR-' . $year . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
