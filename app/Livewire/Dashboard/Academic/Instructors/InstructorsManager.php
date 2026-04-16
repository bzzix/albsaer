<?php

namespace App\Livewire\Dashboard\Academic\Instructors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Instructor;
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class InstructorsManager extends Component
{
    public $activeYearId;

    public $showCreateModal = false;
    public $showAssignModal = false;

    // Create Modal Modes
    public $creationMode = 'select'; // 'select' or 'new'

    // Form fields
    public $selectedUserId;
    public $name = '';
    public $email = '';
    public $password = '';
    public $phone = '';
    
    // Instructor Details
    public $specialization = '';
    public $bio = '';

    // Assignment Details
    public $assignedSubjects = [];
    
    protected function rules()
    {
        $rules = [
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'assignedSubjects' => 'array',
        ];

        if ($this->creationMode === 'new') {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = 'required|min:6';
        } else {
            $rules['selectedUserId'] = 'required|exists:users,id';
        }

        return $rules;
    }

    public function mount()
    {
        $this->activeYearId = AcademicYear::where('is_active', true)->value('id');
    }

    public function updatedActiveYearId()
    {
        // Handled by Reactive Table
    }

    public function openCreateModal($mode = 'select')
    {
        $this->resetForm();
        $this->creationMode = $mode;
        $this->showCreateModal = true;
    }

    public function saveInstructor()
    {
        if (!$this->activeYearId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ',
                'message' => 'لا يوجد عام دراسي نشط كخلفية للعمل.'
            ]);
            return;
        }

        $this->validate();

        DB::beginTransaction();
        try {
            if ($this->creationMode === 'new') {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);
                $user->assignRole('Trainer');
                $userId = $user->id;
            } else {
                $userId = $this->selectedUserId;
                $user = User::findOrFail($userId);
                if (!$user->hasRole('Trainer')) {
                    $user->assignRole('Trainer');
                }
            }

            // Create or update Instructor
            $instructor = Instructor::updateOrCreate(
                ['user_id' => $userId],
                [
                    'instructor_code' => 'INST-' . time(),
                    'specialization' => $this->specialization,
                    'bio' => $this->bio,
                    'hire_date' => now(),
                    'status' => 'active'
                ]
            );

            // Link to Academic Year
            DB::table('academic_year_instructor')->updateOrInsert(
                [
                    'academic_year_id' => $this->activeYearId,
                    'instructor_id' => $instructor->id
                ],
                [
                    'subject_ids' => json_encode($this->assignedSubjects),
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم الحفظ',
                'message' => 'تم إضافة المدرب وربطه بالعام الأكاديمي بنجاح.'
            ]);
            $this->showCreateModal = false;
            $this->resetForm();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ في الحفظ',
                'message' => 'حدث خطأ أثناء حفظ البيانات: ' . $e->getMessage()
            ]);
        }
    }

    public function removeInstructorFromYear($instructorId)
    {
        // This is now handled in InstructorsTable.php
    }

    private function resetForm()
    {
        $this->selectedUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->phone = '';
        $this->specialization = '';
        $this->bio = '';
        $this->assignedSubjects = [];
    }

    public function render()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $subjects = Subject::where('is_active', true)->get();
        
        // Find users that are NOT currently instructors for the active year
        $existingInstructorIds = [];
        if ($this->activeYearId) {
            $existingInstructorIds = DB::table('academic_year_instructor')
                                       ->where('academic_year_id', $this->activeYearId)
                                       ->pluck('instructor_id')->toArray();
        }
        
        $availableUsers = User::whereNotIn('id', function($query) use ($existingInstructorIds) {
            $query->select('user_id')->from('instructors')->whereIn('id', $existingInstructorIds);
        })->get();

        return view('livewire.dashboard.academic.instructors.instructors-manager', [
            'academicYears' => $academicYears,
            'subjects' => $subjects,
            'availableUsers' => $availableUsers
        ])->layout('dashboard.layouts.master');
    }
}
