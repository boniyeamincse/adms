<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with(['schoolClass', 'section']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%");
        }

        // Filter by class
        if ($request->has('class_id') && !empty($request->class_id)) {
            $query->where('class_id', $request->class_id);
        }

        // Filter by section
        if ($request->has('section_id') && !empty($request->section_id)) {
            $query->where('section_id', $request->section_id);
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $students = $query->paginate(15);

        $classes = SchoolClass::all();
        $sections = SectionModel::with('schoolClass')->get();

        return view('students.index', compact('students', 'classes', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = SchoolClass::all();
        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roll_no' => 'nullable|string|max:50|unique:students',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'dob' => 'nullable|date|before:today',
            'status' => 'required|in:active,inactive,transferred',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students', 'public');
        }

        Student::create([
            'name' => $request->name,
            'roll_no' => $request->roll_no,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'dob' => $request->dob,
            'status' => $request->status,
            'photo' => $photoPath,
        ]);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['schoolClass', 'section', 'fees', 'payments']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::all();
        $sections = SectionModel::where('class_id', $student->class_id)->get();
        return view('students.edit', compact('student', 'classes', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'roll_no' => 'nullable|string|max:50|unique:students,roll_no,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'dob' => 'nullable|date|before:today',
            'status' => 'required|in:active,inactive,transferred',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $photoPath = $request->file('photo')->store('students', 'public');
            $request->merge(['photo' => $photoPath]);
        } elseif ($request->has('remove_photo')) {
            // Remove photo
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $request->merge(['photo' => null]);
        }

        $student->update($request->only([
            'name', 'roll_no', 'class_id', 'section_id', 'dob', 'status', 'photo'
        ]));

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        // Delete photo if exists
        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Get sections for a class via AJAX
     */
    public function getSections($classId)
    {
        $sections = SectionModel::where('class_id', $classId)->get();
        return response()->json($sections);
    }

    /**
     * Export students to Excel
     */
    public function export()
    {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }

    /**
     * Import students from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx|max:2048'
        ]);

        Excel::import(new StudentsImport, $request->file('file'));

        return redirect()->route('students.index')
            ->with('success', 'Students imported successfully.');
    }
}
