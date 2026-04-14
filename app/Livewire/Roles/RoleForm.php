<?php

namespace App\Livewire\Roles;

use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleForm extends Component
{
    public $roleId;
    public $name;
    public $display_name;
    public $display_name_en;
    public $color = '#4f46e5';
    public $selectedPermissions = [];
    public $isEditMode = false;
    
    public function getAvailablePermissionsProperty()
    {
        return Permission::all();
    }

    #[On('edit-role')]
    public function editRole($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        
        $role = Role::findOrFail($id);
        
        $this->roleId         = $role->id;
        $this->name           = $role->name;
        $this->display_name   = $role->display_name;
        $this->display_name_en = $role->display_name_en;
        $this->color          = $role->color ?? '#4f46e5';
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        
        $this->dispatch('open-modal', 'role-form');
    }

    #[On('open-modal')]
    public function handleOpenModal($modalName)
    {
        if ($modalName === 'role-form' && !$this->isEditMode && !$this->roleId) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset(['roleId', 'name', 'display_name', 'display_name_en', 'color', 'selectedPermissions']);
        $this->color = '#4f46e5';
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function save()
    {
        $rules = [
            'name'           => ['required', 'string', 'max:100', 'regex:/^[a-z0-9_-]+$/', 'unique:roles,name,' . $this->roleId],
            'display_name'   => 'required|string|max:255',
            'display_name_en'=> 'required|string|max:255',
            'color'          => 'nullable|string|max:20',
            'selectedPermissions' => 'array',
        ];

        $messages = [
            'name.regex' => 'الاسم الفريد يجب أن يحتوي فقط على أحرف إنجليزية صغيرة وأرقام والشرطة السفلية (_) أو علامة الطرح (-).',
            'name.unique' => 'هذا الاسم الفريد مستخدم بالفعل.',
        ];

        $this->validate($rules, $messages);

        if ($this->isEditMode) {
            $role = Role::findOrFail($this->roleId);
            
            $data = [
                'display_name'    => $this->display_name,
                'display_name_en' => $this->display_name_en,
                'color'           => $this->color,
            ];

            // Only update name if not a core role
            if (!in_array($role->name, ['super-admin', 'admin', 'instructor', 'student', 'parent'])) {
                $data['name'] = $this->name;
            }

            $role->update($data);
            $message = 'تم تحديث الدور بنجاح';
        } else {
            $role = Role::create([
                'name'            => $this->name, 
                'display_name'    => $this->display_name,
                'display_name_en' => $this->display_name_en,
                'color'           => $this->color,
                'guard_name'      => 'web', // Must be 'web' since all permissions use web guard
            ]);
            $message = 'تم إضافة الدور بنجاح';
        }

        // Sync Permissions - explicitly load with 'web' guard to avoid sanctum guard mismatch
        $permissions = Permission::where('guard_name', 'web')
            ->whereIn('name', $this->selectedPermissions)
            ->get();
        $role->syncPermissions($permissions);

        $this->dispatch('close-modal', 'role-form');
        $this->dispatch('refreshDatatable'); 
        $this->dispatch('notify', [
            'type'    => 'success',
            'title'   => 'تم الحفظ',
            'message' => $message,
        ]);
        
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.roles.role-form');
    }
}
