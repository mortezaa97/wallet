<?php

use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', [Status::PENDING->value, Status::REJECTED->value, Status::IN_PROGRESS->value, Status::DONE->value])->default(Status::PENDING->value);
            $table->unsignedBigInteger('wallet_id');
            $table->decimal('amount',20,0);
            $table->decimal('balance_after', 19, 0);
            $table->date('date')->nullable();
            $table->text('desc')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('bank_id');
            $table->index('user_id');
            $table->index('wallet_id');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraws');
    }
};

