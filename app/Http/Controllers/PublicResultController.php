<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentResult;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicResultController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'exam_year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'exam_name' => ['nullable', 'string', 'max:100'],
        ]);

        $query = StudentResult::query()
            ->with(['schoolClass', 'section'])
            ->orderByDesc('exam_year')
            ->orderBy('student_name');

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function ($builder) use ($search): void {
                $builder
                    ->where('student_name', 'like', "%{$search}%")
                    ->orWhere('roll_no', 'like', "%{$search}%")
                    ->orWhere('registration_no', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['class_id'])) {
            $query->where('class_id', $filters['class_id']);
        }

        if (! empty($filters['section_id'])) {
            $query->where('section_id', $filters['section_id']);
        }

        if (! empty($filters['exam_year'])) {
            $query->where('exam_year', $filters['exam_year']);
        }

        if (! empty($filters['exam_name'])) {
            $query->where('exam_name', $filters['exam_name']);
        }

        $results = $query->paginate(20)->withQueryString();

        $summary = StudentResult::query()
            ->selectRaw('class_id, section_id, exam_year, COUNT(*) as total_students, AVG(gpa) as avg_gpa')
            ->when(! empty($filters['class_id']), fn ($builder) => $builder->where('class_id', $filters['class_id']))
            ->when(! empty($filters['section_id']), fn ($builder) => $builder->where('section_id', $filters['section_id']))
            ->when(! empty($filters['exam_year']), fn ($builder) => $builder->where('exam_year', $filters['exam_year']))
            ->groupBy('class_id', 'section_id', 'exam_year')
            ->with(['schoolClass', 'section'])
            ->orderByDesc('exam_year')
            ->get();

        return view('public.results', [
            'results' => $results,
            'summary' => $summary,
            'classes' => SchoolClass::orderBy('class_name')->get(),
            'sections' => Section::with('schoolClass')->orderBy('section_name')->get(),
            'examNames' => StudentResult::query()->select('exam_name')->distinct()->orderBy('exam_name')->pluck('exam_name'),
            'filters' => $filters,
        ]);
    }
}
