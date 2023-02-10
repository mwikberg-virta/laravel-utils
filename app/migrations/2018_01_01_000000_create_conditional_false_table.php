<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use MLL\LaravelUtils\Database\ConditionalMigration;

return new class() extends Migration implements ConditionalMigration {
    public function up(): void
    {
        Schema::create('conditional_false', function (Blueprint $table): void {
            $table->id();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conditional_false');
    }

    public function shouldRun(): bool
    {
        return false;
    }
};
