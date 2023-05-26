<?php

use App\Models\LoanAccount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayment_schedulers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(LoanAccount::class);
            $table->smallInteger('term')->comment('0=extra payment/non term payment');
            $table->decimal('amount', 8, 2);
            $table->decimal('amount_paid', 8, 2)->default(0);
            $table->dateTime('due_date');
            $table->string('nurration')->nullable();
            $table->smallInteger('status')->default(1)->comment('1=PENDING,2=PAID,3=OTHER');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repayment_schedulers');
    }
};
