<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserForm extends Component
{
    public $userId;
    public $name;
    public $email;
    public $password;
    public $phone;
    public $whatsapp_number;
    public $national_id;
    public $is_active = true;
    public $role = '';
    
    public $isEditMode = false;
    
    public function getAvailableRolesProperty()
    {
        return Role::all();
    }

    #[On('edit-user')]
    public function editUser($id)
    {
        $this->resetValidation();
        $this->isEditMode = true;
        
        $user = User::findOrFail($id);
        
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->whatsapp_number = $user->whatsapp_number;
        $this->national_id = $user->national_id;
        $this->is_active = $user->is_active;
        
        // Assume single role per user for the form simplicity
        $this->role = $user->roles->first()?->name ?? '';
        
        // Reset passwords
        $this->password = '';
        
        $this->dispatch('open-modal', 'user-form');
    }

    public function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'phone', 'whatsapp_number', 'national_id', 'role']);
        $this->is_active = true;
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId),
            ],
            'national_id' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore($this->userId),
            ],
            'phone' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'role' => 'required|string|exists:roles,name',
        ];

        if (!$this->isEditMode || !empty($this->password)) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp_number' => $this->whatsapp_number,
            'national_id' => $this->national_id,
            'is_active' => $this->is_active,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditMode) {
            $user = User::findOrFail($this->userId);
            $user->update($data);
            $message = 'تم تحديث بيانات المستخدم بنجاح';
        } else {
            $user = User::create($data);
            $user->markEmailAsVerified(); // Auto verify manually created users
            $message = 'تم إضافة المستخدم بنجاح';
        }

        // Sync Role
        if ($this->role) {
            $user->syncRoles([$this->role]);
        }

        $this->dispatch('close-modal', 'user-form');
        $this->dispatch('refreshDatatable'); // Trigger refresh for Rappasoft datatable
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحفظ',
            'message' => $message,
        ]);
        
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.users.user-form');
    }
}
