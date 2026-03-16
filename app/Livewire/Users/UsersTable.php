<?php

namespace App\Livewire\Users;

use App\Models\User;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Illuminate\Database\Eloquent\Builder;

class UsersTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('created_at', 'desc')
             ->setEmptyMessage('لا يوجد مستخدمين لعرضهم')
             ->setSearchPlaceholder('البحث في المستخدمين...')
             ->setSearchDebounce(500)
             ->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
                return [
                    'class' => 'text-right p-4 font-medium text-surface-700 w-auto',
                ];
             })
             ->setThAttributes(function(Column $column) {
                return [
                    'class' => 'text-right p-4 text-xs tracking-wider text-surface-500 uppercase bg-surface-50 whitespace-nowrap',
                ];
             });
    }

    public function builder(): Builder
    {
        return User::query()
            ->with('roles'); // Eager load Spatie roles
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->searchable()
                ->excludeFromColumnSelect()
                ->hideIf(true),

            Column::make("المستخدم", "name")
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, $column) {
                    return view('livewire.users.columns.user', ['user' => $row]);
                }),

            Column::make("الصلاحية", "id")
                ->label(fn($row) => view('livewire.users.columns.role', ['roles' => $row->roles]))
                ->html(),

            Column::make("حالة الإيميل", "email_verified_at")
                ->sortable()
                ->format(function ($value, $row, $column) {
                    return view('livewire.users.columns.email-status', ['user' => $row]);
                })->html(),

            Column::make("حالة الحساب", "is_active")
                ->sortable()
                ->format(function ($value, $row, $column) {
                    return view('livewire.users.columns.account-status', ['user' => $row]);
                })->html(),

            Column::make("البريد الإلكتروني", "email")
                ->sortable()
                ->searchable(),

            Column::make("رقم الجوال", "phone")
                ->sortable()
                ->searchable(),

            Column::make("الهاتف (واتساب)", "whatsapp_number")
                ->sortable()
                ->searchable(),
                
            Column::make("تاريخ التسجيل", "created_at")
                ->sortable()
                ->format(fn($value) => $value->format('Y/m/d H:i')),

            Column::make("رقم الهوية", "national_id")
                ->sortable()
                ->searchable()
                ->deselected(),

            Column::make("الإجراءات", "id")
                ->label(fn($row) => view('livewire.users.columns.actions', ['user' => $row]))
                ->html(),
        ];
    }
    
    public function toggleEmailStatus(User $user)
    {
        if ($user->email_verified_at) {
            $user->email_verified_at = null;
        } else {
            $user->email_verified_at = now();
        }
        $user->save();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم التحديث',
            'message' => 'تم تحديث حالة تفعيل البريد الإلكتروني بنجاح',
        ]);
    }

    public function toggleAccountStatus(User $user)
    {
        // Don't allow deactivating ID 1
        if ($user->id === 1) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'فشل',
                'message' => 'لا يمكن تعطيل حساب مدير النظام الأساسي',
            ]);
            return;
        }
        
        $user->is_active = !$user->is_active;
        $user->save();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم التحديث',
            'message' => 'تم تحديث حالة حساب المستخدم بنجاح',
        ]);
    }

    public function confirmDelete($id)
    {
        $user = User::find($id);
        if (!$user) return;

        if ($user->id === 1) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'منع الحذف',
                'message' => 'لا يمكن حذف حساب مدير النظام الأساسي',
            ]);
            return;
        }

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد حذف المستخدم',
            'message' => "هل أنت متأكد أنك تريد حذف المستخدم '{$user->name}'؟ لا يمكن التراجع عن هذا الإجراء.",
            'component' => $this->getId(),
            'action' => 'deleteUser'
        ]);
    }

    public function deleteUser($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ',
                'message' => 'المستخدم غير موجود',
            ]);
            return;
        }
        
        if ($user->id === 1) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'فشل',
                'message' => 'لا يمكن حذف حساب مدير النظام الأساسي',
            ]);
            return;
        }

        $user->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحذف',
            'message' => 'تم حذف المستخدم بنجاح',
        ]);
    }
}
