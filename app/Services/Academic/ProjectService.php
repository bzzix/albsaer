<?php

namespace App\Services\Academic;

use App\Models\Project;
use App\Services\BaseService;

class ProjectService extends BaseService
{
    /**
     * الحصول على قائمة المشاريع مع عد المجموعات والطلاب
     */
    public function getProjectsList($search = null, $status = null)
    {
        $query = Project::withCount(['groups', 'students']);

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
        }

        if ($status !== null) {
            $query->where('is_active', $status);
        }

        return $query->latest()->paginate(10);
    }

    /**
     * إنشاء مشروع جديد
     */
    public function createProject(array $data)
    {
        $userId = auth()->id();
        if (!$userId) {
            throw new \Exception('يجب تسجيل الدخول لإنشاء مشروع.');
        }

        $project = Project::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'academic_year_id' => $data['academic_year_id'] ?? null,
            'description' => $data['description'] ?? null,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'] ?? 'active',
            'is_active' => (bool) ($data['is_active'] ?? true),
            'created_by' => $userId,
        ]);

        if (isset($data['subjects'])) {
            $project->subjects()->sync($data['subjects']);
        }
        
        if (isset($data['courses'])) {
            $project->courses()->sync($data['courses']);
        }

        return $project;
    }

    /**
     * تحديث مشروع
     */
    public function updateProject($id, array $data)
    {
        $project = Project::findOrFail($id);
        // حماية الحقول الحساسة
        unset($data['created_by']);
        
        $subjects = $data['subjects'] ?? [];
        $courses = $data['courses'] ?? [];
        unset($data['subjects'], $data['courses']);

        $project->update($data);

        $project->subjects()->sync($subjects);
        $project->courses()->sync($courses);

        return $project;
    }

    /**
     * حذف مشروع (مع التحقق من وجود مجموعات مرتبطة)
     */
    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        
        if ($project->groups()->count() > 0) {
            throw new \Exception('لا يمكن حذف المشروع لوجود مجموعات مرتبطة به.');
        }

        return $project->delete();
    }
}
