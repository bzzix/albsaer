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
        
        // Prevent editing core roles (unless we want to allow editing display_name/color for them)
        // Let's allow editing display_name and color for core roles but keep name readonly in blade
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->display_name = $role->display_name;
        $this->color = $role->color ?? '#4f46e5';
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
        $this->reset(['roleId', 'name', 'display_name', 'color', 'selectedPermissions']);
        $this->color = '#4f46e5';
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:roles,name,' . $this->roleId,
            'display_name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
            'selectedPermissions' => 'array',
        ];

        $this->validate($rules);

        if ($this->isEditMode) {
            $role = Role::findOrFail($this->roleId);
            
            $data = [
                'display_name' => $this->display_name,
                'color' => $this->color,
            ];

            // Only update name if not core role
            if (!in_array($role->name, ['super-admin', 'admin', 'teacher', 'student', 'parent'])) {
                $data['name'] = $this->name;
            }

            $role->update($data);
            $message = 'تم تحديث الدور بنجاح';
        } else {
            $role = Role::create([
                'name' => $this->name, 
                'display_name' => $this->display_name,
                'color' => $this->color,
                'guard_name' => 'web'
            ]);
            $message = 'تم إضافة الدور بنجاح';
        }

        // Sync Permissions
        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('close-modal', 'role-form');
        $this->dispatch('refreshDatatable'); 
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحفظ',
            'message' => $message,
        ]);
        
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.roles.role-form');
    }
}
