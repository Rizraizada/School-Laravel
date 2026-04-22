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
        if (! Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table): void {
                $table->id();
                $table->string('subject_name');
                $table->string('subject_code')->nullable()->unique();
                $table->string('subject_type')->default('compulsory');
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['subject_name', 'subject_type'], 'subjects_name_type_unique');
            });
        }

        if (Schema::hasTable('subject_config')) {
            Schema::table('subject_config', function (Blueprint $table): void {
                if (! Schema::hasColumn('subject_config', 'subject_id')) {
                    $table->foreignId('subject_id')->nullable()->after('group_name')->constrained('subjects')->nullOnDelete();
                }
                if (! Schema::hasColumn('subject_config', 'include_in_gpa')) {
                    $table->boolean('include_in_gpa')->default(true)->after('is_optional');
                }
                if (! Schema::hasColumn('subject_config', 'include_in_total_score')) {
                    $table->boolean('include_in_total_score')->default(true)->after('include_in_gpa');
                }
            });
        }

        if (! Schema::hasTable('exams')) {
            Schema::create('exams', function (Blueprint $table): void {
                $table->id();
                $table->string('exam_name');
                $table->unsignedSmallInteger('exam_year');
                $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
                $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->text('notes')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['exam_year', 'exam_name']);
            });
        }

        if (! Schema::hasTable('exam_subjects')) {
            Schema::create('exam_subjects', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
                $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
                $table->foreignId('subject_config_id')->nullable()->constrained('subject_config')->nullOnDelete();
                $table->string('subject_name');
                $table->string('subject_code')->nullable();
                $table->unsignedSmallInteger('full_mark')->default(100);
                $table->unsignedSmallInteger('pass_mark')->default(33);
                $table->boolean('is_optional')->default(false);
                $table->boolean('include_in_gpa')->default(true);
                $table->boolean('include_in_total_score')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['exam_id', 'subject_name'], 'exam_subject_unique_name');
            });
        }

        if (Schema::hasTable('student_results')) {
            Schema::table('student_results', function (Blueprint $table): void {
                if (! Schema::hasColumn('student_results', 'exam_id')) {
                    $table->foreignId('exam_id')->nullable()->after('section_id')->constrained('exams')->nullOnDelete();
                }
            });
        }

        if (! Schema::hasTable('student_result_items')) {
            Schema::create('student_result_items', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('student_result_id')->constrained('student_results')->cascadeOnDelete();
                $table->foreignId('exam_subject_id')->nullable()->constrained('exam_subjects')->nullOnDelete();
                $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
                $table->string('subject_name');
                $table->string('subject_code')->nullable();
                $table->decimal('obtained_mark', 6, 2);
                $table->unsignedSmallInteger('full_mark')->default(100);
                $table->unsignedSmallInteger('pass_mark')->default(33);
                $table->boolean('is_optional')->default(false);
                $table->boolean('include_in_gpa')->default(true);
                $table->boolean('include_in_total_score')->default(true);
                $table->decimal('gpa_point', 3, 2)->default(0);
                $table->string('grade_letter', 10)->nullable();
                $table->boolean('is_passed')->default(false);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index('subject_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_result_items');

        if (Schema::hasTable('student_results') && Schema::hasColumn('student_results', 'exam_id')) {
            Schema::table('student_results', function (Blueprint $table): void {
                $table->dropConstrainedForeignId('exam_id');
            });
        }

        Schema::dropIfExists('exam_subjects');
        Schema::dropIfExists('exams');

        if (Schema::hasTable('subject_config')) {
            Schema::table('subject_config', function (Blueprint $table): void {
                if (Schema::hasColumn('subject_config', 'include_in_total_score')) {
                    $table->dropColumn('include_in_total_score');
                }
                if (Schema::hasColumn('subject_config', 'include_in_gpa')) {
                    $table->dropColumn('include_in_gpa');
                }
                if (Schema::hasColumn('subject_config', 'subject_id')) {
                    $table->dropConstrainedForeignId('subject_id');
                }
            });
        }

        Schema::dropIfExists('subjects');
    }
};
