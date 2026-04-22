<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\SubjectConfig;
use Illuminate\Database\Seeder;

class BangladeshCurriculumSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['subject_name' => 'Bangla', 'subject_code' => '101', 'subject_type' => 'compulsory', 'sort_order' => 1],
            ['subject_name' => 'English', 'subject_code' => '107', 'subject_type' => 'compulsory', 'sort_order' => 2],
            ['subject_name' => 'Mathematics', 'subject_code' => '109', 'subject_type' => 'compulsory', 'sort_order' => 3],
            ['subject_name' => 'General Science', 'subject_code' => '127', 'subject_type' => 'compulsory', 'sort_order' => 4],
            ['subject_name' => 'Bangladesh and Global Studies', 'subject_code' => '150', 'subject_type' => 'compulsory', 'sort_order' => 5],
            ['subject_name' => 'Religion and Moral Education', 'subject_code' => '111', 'subject_type' => 'compulsory', 'sort_order' => 6],
            ['subject_name' => 'Information and Communication Technology', 'subject_code' => '154', 'subject_type' => 'compulsory', 'sort_order' => 7],
            ['subject_name' => 'Agriculture Studies', 'subject_code' => '134', 'subject_type' => 'compulsory', 'sort_order' => 8],
            ['subject_name' => 'Home Science', 'subject_code' => '151', 'subject_type' => 'optional', 'sort_order' => 9],
            ['subject_name' => 'Higher Mathematics', 'subject_code' => '126', 'subject_type' => 'optional', 'sort_order' => 10],
            ['subject_name' => 'Biology', 'subject_code' => '138', 'subject_type' => 'compulsory', 'sort_order' => 11],
            ['subject_name' => 'Chemistry', 'subject_code' => '137', 'subject_type' => 'compulsory', 'sort_order' => 12],
            ['subject_name' => 'Physics', 'subject_code' => '136', 'subject_type' => 'compulsory', 'sort_order' => 13],
            ['subject_name' => 'Finance and Banking', 'subject_code' => '152', 'subject_type' => 'compulsory', 'sort_order' => 14],
            ['subject_name' => 'Business Entrepreneurship', 'subject_code' => '143', 'subject_type' => 'compulsory', 'sort_order' => 15],
            ['subject_name' => 'Accounting', 'subject_code' => '146', 'subject_type' => 'compulsory', 'sort_order' => 16],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['subject_code' => $subject['subject_code']],
                [
                    'subject_name' => $subject['subject_name'],
                    'subject_type' => $subject['subject_type'],
                    'is_active' => true,
                    'sort_order' => $subject['sort_order'],
                ]
            );
        }

        $classSubjectMap = [
            'Class 6' => [
                ['code' => '101', 'type' => 'compulsory'],
                ['code' => '107', 'type' => 'compulsory'],
                ['code' => '109', 'type' => 'compulsory'],
                ['code' => '127', 'type' => 'compulsory'],
                ['code' => '150', 'type' => 'compulsory'],
                ['code' => '111', 'type' => 'compulsory'],
                ['code' => '154', 'type' => 'compulsory'],
                ['code' => '134', 'type' => 'compulsory'],
                ['code' => '151', 'type' => 'optional'],
            ],
            'Class 7' => [
                ['code' => '101', 'type' => 'compulsory'],
                ['code' => '107', 'type' => 'compulsory'],
                ['code' => '109', 'type' => 'compulsory'],
                ['code' => '127', 'type' => 'compulsory'],
                ['code' => '150', 'type' => 'compulsory'],
                ['code' => '111', 'type' => 'compulsory'],
                ['code' => '154', 'type' => 'compulsory'],
                ['code' => '134', 'type' => 'compulsory'],
                ['code' => '126', 'type' => 'optional'],
            ],
            'Class 8' => [
                ['code' => '101', 'type' => 'compulsory'],
                ['code' => '107', 'type' => 'compulsory'],
                ['code' => '109', 'type' => 'compulsory'],
                ['code' => '127', 'type' => 'compulsory'],
                ['code' => '150', 'type' => 'compulsory'],
                ['code' => '111', 'type' => 'compulsory'],
                ['code' => '154', 'type' => 'compulsory'],
                ['code' => '134', 'type' => 'compulsory'],
                ['code' => '126', 'type' => 'optional'],
            ],
            'Class 9' => [
                ['code' => '101', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '107', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '109', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '111', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '154', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '150', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '126', 'type' => 'optional', 'group' => 'General'],
                ['code' => '136', 'type' => 'compulsory', 'group' => 'Science'],
                ['code' => '137', 'type' => 'compulsory', 'group' => 'Science'],
                ['code' => '138', 'type' => 'compulsory', 'group' => 'Science'],
                ['code' => '126', 'type' => 'optional', 'group' => 'Science'],
                ['code' => '146', 'type' => 'compulsory', 'group' => 'Commerce'],
                ['code' => '143', 'type' => 'compulsory', 'group' => 'Commerce'],
                ['code' => '152', 'type' => 'compulsory', 'group' => 'Commerce'],
                ['code' => '126', 'type' => 'optional', 'group' => 'Commerce'],
            ],
            'Class 10' => [
                ['code' => '101', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '107', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '109', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '111', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '154', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '150', 'type' => 'compulsory', 'group' => 'General'],
                ['code' => '126', 'type' => 'optional', 'group' => 'General'],
                ['code' => '136', 'type' => 'compulsory', 'group' => 'Science'],
                ['code' => '137', 'type' => 'compulsory', 'group' => 'Science'],
                ['code' => '138', 'type' => 'compulsory', 'group' => 'Science'],
                ['code' => '126', 'type' => 'optional', 'group' => 'Science'],
                ['code' => '146', 'type' => 'compulsory', 'group' => 'Commerce'],
                ['code' => '143', 'type' => 'compulsory', 'group' => 'Commerce'],
                ['code' => '152', 'type' => 'compulsory', 'group' => 'Commerce'],
                ['code' => '126', 'type' => 'optional', 'group' => 'Commerce'],
            ],
        ];

        foreach ($classSubjectMap as $className => $entries) {
            $schoolClass = SchoolClass::firstOrCreate(['class_name' => $className]);
            $sortOrder = 1;

            foreach ($entries as $entry) {
                $subject = Subject::where('subject_code', $entry['code'])->first();
                if (! $subject) {
                    continue;
                }

                $isOptional = $entry['type'] === 'optional';
                $groupName = $entry['group'] ?? null;

                SubjectConfig::updateOrCreate(
                    [
                        'class_level' => $className,
                        'group_name' => $groupName,
                        'subject_name' => $subject->subject_name,
                    ],
                    [
                        'class_id' => $schoolClass->id,
                        'subject_id' => $subject->id,
                        'subject_code' => $subject->subject_code,
                        'subject_type' => $isOptional ? 'optional' : 'compulsory',
                        'full_mark' => 100,
                        'pass_mark' => 33,
                        'is_optional' => $isOptional,
                        'include_in_gpa' => true,
                        'include_in_total_score' => true,
                        'is_active' => true,
                        'sort_order' => $sortOrder++,
                    ]
                );
            }
        }
    }
}
