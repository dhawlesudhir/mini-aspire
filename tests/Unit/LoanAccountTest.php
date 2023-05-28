<?php

namespace Tests\Unit;

use App\Http\Controllers\LoanAccountController;
use App\Models\LoanAccount;
use PHPUnit\Framework\TestCase;

class LoanAccountTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_save_loan()
    {
        $form = [
            'amount' => 'required|numeric',
            'terms' => 'required|numeric',
            'purpose' => 'string|null',
        ];

        $obj = new LoanAccountController();
        $response = $obj->saveLoan($form);
        $response->assertStatus(201);
    }
}
