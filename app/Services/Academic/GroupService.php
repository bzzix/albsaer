<?php

namespace App\Services\Academic;

use App\Models\Group;
use App\Models\Project;
use App\Models\GroupEnrollment;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class GroupService extends BaseService
{
    /**
     * الحصول على قائمة المجموعات مع عد الطلاب
     */
    public function getGroupsList($projectId = null, $search = null)
    {
        $query = Group::withCount('students')->with('project');

        if ($projectId) {
            $query->where('project_id', $projectId);
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->latest()->paginate(10);
    }

    /**
     * إنشاء مجموعة جديدة
     */
    public function createGroup(array $data)
    {
        return Group::create([
            'project_id'   => $data['project_id'] ?? null,
            'course_id'    => $data['course_id'] ?? null,
            'name'         => $data['name'],
            'code'         => $data['code'],
            'trainer_id'   => $data['trainer_id'] ?? null,
            'supervisor_id'=> $data['supervisor_id'] ?? null,
            'start_date'   => $data['start_date'] ?? now(),
            'end_date'     => $data['end_date'] ?? now()->addMonths(3),
            'max_students' => $data['max_students'] ?? 30,
            'status'       => $data['status'] ?? 'active',
            'is_active'    => $data['is_active'] ?? true,
        ]);
    }

    /**
     * تحديث مجموعة موجودة
     */
    public function updateGroup($id, array $data)
    {
        $group = Group::findOrFail($id);
        $group->update([
            'project_id'   => $data['project_id'] ?? $group->project_id,
            'course_id'    => $data['course_id'] ?? $group->course_id,
            'name'         => $data['name'] ?? $group->name,
            'code'         => $data['code'] ?? $group->code,
            'trainer_id'   => $data['trainer_id'] ?? $group->trainer_id,
            'supervisor_id'=> $data['supervisor_id'] ?? $group->supervisor_id,
            'start_date'   => $data['start_date'] ?? $group->start_date,
            'end_date'     => $data['end_date'] ?? $group->end_date,
            'max_students' => $data['max_students'] ?? $group->max_students,
            'status'       => $data['status'] ?? $group->status,
            'is_active'    => $data['is_active'] ?? $group->is_active,
        ]);
        return $group;
    }

    /**
     * حذف مجموعة
     */
    public function deleteGroup($id)
    {
        $group = Group::findOrFail($id);
        
        // التحقق من وجود طلاب مسجلين
        if ($group->students()->count() > 0) {
            throw new \Exception('لا يمكن حذف المجموعة لوجود طلاب مسجلين بها. يرجى إلغاء تسجيل الطلاب أولاً.');
        }

        return $group->delete();
    }

    /**
     * تسجيل طالب في مجموعة
     */
    public function enrollStudent($groupId, $studentId)
    {
        return GroupEnrollment::updateOrCreate(
            ['group_id' => $groupId, 'student_id' => $studentId],
            ['joined_at' => now(), 'is_active' => true]
        );
    }

    /**
     * إلغاء تسجيل طالب من مجموعة
     */
    public function unenrollStudent($groupId, $studentId)
    {
        return GroupEnrollment::where('group_id', $groupId)
            ->where('student_id', $studentId)
            ->delete();
    }
}
