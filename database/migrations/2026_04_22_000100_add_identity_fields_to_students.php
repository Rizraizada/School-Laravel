<?php

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
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table): void {
                if (! Schema::hasColumn('students', 'roll_no')) {
                    $table->string('roll_no')->nullable()->after('section_id');
                }
                if (! Schema::hasColumn('students', 'registration_no')) {
                    $table->string('registration_no')->nullable()->after('roll_no');
                }
                if (! Schema::hasColumn('students', 'father_name')) {
                    $table->string('father_name')->nullable()->after('registration_no');
                }
                if (! Schema::hasColumn('students', 'mother_name')) {
                    $table->string('mother_name')->nullable()->after('father_name');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table): void {
                $columns = [];
                foreach (['roll_no', 'registration_no', 'father_name', 'mother_name'] as $column) {
                    if (Schema::hasColumn('students', $column)) {
                        $columns[] = $column;
                    }
                }

                if ($columns !== []) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
