<?php

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
        Schema::create('loan_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->decimal('amount', 8, 2);
            $table->smallInteger('terms');
            $table->decimal('bal_amount', 8, 2)->default(0);
            $table->string('purpose')->nullable()->comment('reason behind loan');
            $table->smallInteger('status')->default(1)->comment('1=PENDING,2=APPROVED,3=PAID');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->dateTime('approved_on')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
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
        Schema::dropIfExists('loan_accounts');
    }
};
