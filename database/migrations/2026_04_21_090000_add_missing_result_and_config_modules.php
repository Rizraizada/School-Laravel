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
        if (! Schema::hasTable('student_results')) {
            Schema::create('student_results', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
                $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
                $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
                $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('student_name');
                $table->string('roll_no')->nullable();
                $table->string('registration_no')->nullable();
                $table->string('father_name')->nullable();
                $table->string('mother_name')->nullable();
                $table->string('group_name')->nullable();
                $table->string('class_level')->nullable();
                $table->string('section_name')->nullable();
                $table->string('exam_name')->default('Final');
                $table->unsignedSmallInteger('exam_year');
                $table->decimal('bangla', 5, 2)->nullable();
                $table->decimal('english', 5, 2)->nullable();
                $table->decimal('mathematics', 5, 2)->nullable();
                $table->decimal('science', 5, 2)->nullable();
                $table->decimal('religion', 5, 2)->nullable();
                $table->decimal('ict', 5, 2)->nullable();
                $table->decimal('social_science', 5, 2)->nullable();
                $table->decimal('agriculture', 5, 2)->nullable();
                $table->decimal('higher_math', 5, 2)->nullable();
                $table->decimal('biology', 5, 2)->nullable();
                $table->decimal('chemistry', 5, 2)->nullable();
                $table->decimal('physics', 5, 2)->nullable();
                $table->decimal('total_marks', 7, 2)->nullable();
                $table->decimal('gpa', 3, 2)->nullable();
                $table->string('grade', 10)->nullable();
                $table->string('result_status', 25)->nullable();
                $table->unsignedInteger('merit_position')->nullable();
                $table->json('raw_marks')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->index(['exam_year', 'exam_name']);
                $table->index(['class_id', 'section_id']);
                $table->index('roll_no');
            });
        }

        if (! Schema::hasTable('subject_config')) {
            Schema::create('subject_config', function (Blueprint $table) {
                $table->id();
                $table->foreignId('class_id')->nullable()->constrained('classes')->nullOnDelete();
                $table->string('class_level');
                $table->string('group_name')->nullable();
                $table->string('subject_code')->nullable();
                $table->string('subject_name');
                $table->string('subject_type')->default('compulsory');
                $table->unsignedSmallInteger('full_mark')->default(100);
                $table->unsignedSmallInteger('pass_mark')->default(33);
                $table->unsignedSmallInteger('subjective_mark')->nullable();
                $table->unsignedSmallInteger('mcq_mark')->nullable();
                $table->unsignedSmallInteger('practical_mark')->nullable();
                $table->boolean('is_optional')->default(false);
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['class_level', 'group_name', 'subject_name'], 'subject_config_unique_subject');
            });
        }

        if (! Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->string('image')->nullable();
                $table->string('title');
                $table->longText('description')->nullable();
                $table->date('date')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('branch')) {
            Schema::create('branch', function (Blueprint $table) {
                $table->id();
                $table->string('image_url')->nullable();
                $table->string('branch_name');
                $table->string('branch_address')->nullable();
                $table->string('branch_email')->nullable();
                $table->string('branch_incharge')->nullable();
                $table->string('branch_phone')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('sections')) {
            Schema::table('sections', function (Blueprint $table): void {
                if (! Schema::hasColumn('sections', 'total_male')) {
                    $table->unsignedInteger('total_male')->default(0);
                }
                if (! Schema::hasColumn('sections', 'total_female')) {
                    $table->unsignedInteger('total_female')->default(0);
                }
                if (! Schema::hasColumn('sections', 'total_students')) {
                    $table->unsignedInteger('total_students')->default(0);
                }
            });
        }

        if (Schema::hasTable('quick_attendances')) {
            Schema::table('quick_attendances', function (Blueprint $table): void {
                if (! Schema::hasColumn('quick_attendances', 'total_male')) {
                    $table->unsignedInteger('total_male')->default(0);
                }
                if (! Schema::hasColumn('quick_attendances', 'total_female')) {
                    $table->unsignedInteger('total_female')->default(0);
                }
                if (! Schema::hasColumn('quick_attendances', 'total_students')) {
                    $table->unsignedInteger('total_students')->default(0);
                }
                if (! Schema::hasColumn('quick_attendances', 'absent_student_ids')) {
                    $table->json('absent_student_ids')->nullable();
                }
            });
        }

        if (Schema::hasTable('board_directors') && ! Schema::hasColumn('board_directors', 'description')) {
            Schema::table('board_directors', function (Blueprint $table): void {
                $table->longText('description')->nullable();
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'plain_password')) {
                    $table->string('plain_password')->nullable()->after('password');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('board_directors') && Schema::hasColumn('board_directors', 'description')) {
            Schema::table('board_directors', function (Blueprint $table): void {
                $table->dropColumn('description');
            });
        }

        if (Schema::hasTable('quick_attendances')) {
            Schema::table('quick_attendances', function (Blueprint $table): void {
                $dropColumns = [];
                foreach (['total_male', 'total_female', 'total_students', 'absent_student_ids'] as $column) {
                    if (Schema::hasColumn('quick_attendances', $column)) {
                        $dropColumns[] = $column;
                    }
                }
                if ($dropColumns !== []) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        if (Schema::hasTable('sections')) {
            Schema::table('sections', function (Blueprint $table): void {
                $dropColumns = [];
                foreach (['total_male', 'total_female', 'total_students'] as $column) {
                    if (Schema::hasColumn('sections', $column)) {
                        $dropColumns[] = $column;
                    }
                }
                if ($dropColumns !== []) {
                    $table->dropColumn($dropColumns);
                }
            });
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'plain_password')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('plain_password');
            });
        }

        Schema::dropIfExists('branch');
        Schema::dropIfExists('news');
        Schema::dropIfExists('subject_config');
        Schema::dropIfExists('student_results');
    }
};
