<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('triagens', function (Blueprint $table) {
            $table->timestamp('ultima_reavaliacao')->nullable()->after('data_reavaliacao');
            $table->unsignedInteger('reavaliacoes_count')->default(0)->after('ultima_reavaliacao');
        });
    }

    public function down(): void
    {
        Schema::table('triagens', function (Blueprint $table) {
            $table->dropColumn(['ultima_reavaliacao', 'reavaliacoes_count']);
        });
    }
};


