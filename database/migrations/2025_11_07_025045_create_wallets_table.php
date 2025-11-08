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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->uuid('code')->unique();
            $table->unsignedBigInteger('user_id')->unique(); // 1:1 per user
            $table->decimal('balance', 19, 0)->default(0);
            $table->string('currency', 8)->default('IRT'); // Currency field
            $table->enum(column: 'status', allowed: [Status::ACTIVE->value, Status::INACTIVE->value, Status::SUSPENDED->value])->default(Status::ACTIVE->value);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['user_id', 'currency']); // One wallet per user per currency
            $table->index('user_id');
            $table->index('currency');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};

