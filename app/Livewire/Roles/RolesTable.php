<?php

namespace App\Livewire\Roles;

use Spatie\Permission\Models\Role;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;

class RolesTable extends DataTableComponent
{
    protected $model = Role::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('created_at', 'desc')
             ->setEmptyMessage('لا يوجد أدوار لعرضهم')
             ->setSearchPlaceholder('البحث في الأدوار...')
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
        return Role::query()
            ->select(['id', 'name', 'display_name', 'color', 'created_at', 'updated_at'])
            ->with(['permissions']); // Eager load permissions
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->searchable()
                ->excludeFromColumnSelect()
                ->hideIf(true),

            Column::make("اسم الدور", "display_name")
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, $column) {
                    $color = $row->color ?? '#64748b';
                    $name = $row->name;
                    $displayName = $value ?? $name;
                    
                    return '
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full border border-white/20 shadow-sm" style="background-color: ' . $color . '"></div>
                            <div class="flex flex-col">
                                <span class="font-bold text-surface-900 leading-tight">' . $displayName . '</span>
                                <span class="text-[10px] text-surface-400 font-mono tracking-tighter leading-none">' . $name . '</span>
                            </div>
                        </div>
                    ';
                })->html(),

            Column::make("عدد الصلاحيات", "id")
                ->label(fn($row) => '<span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border bg-surface-100 text-surface-700 border-surface-200">' . $row->permissions->count() . ' صلاحية</span>')
                ->html(),

            // Column::make("المستخدمين", "id")
            //     ->label(fn($row) => '<span class="px-2.5 py-1 text-[11px] font-bold rounded-lg border bg-blue-50 text-blue-700 border-blue-200">' . $row->users()->count() . ' مستخدم</span>')
            //     ->html(),

            Column::make("الإجراءات", "id")
                ->label(fn($row) => view('livewire.roles.columns.actions', ['role' => $row]))
                ->html(),
        ];
    }

    public function confirmDelete($id)
    {
        $role = Role::find($id);
        if (!$role) return;

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد حذف الدور',
            'message' => "هل أنت متأكد أنك تريد حذف دور '{$role->name}'؟",
            'component' => $this->getId(),
            'action' => 'deleteRole'
        ]);
    }

    public function deleteRole($roleId)
    {
        $role = Role::find($roleId);
        
        if (!$role) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ',
                'message' => 'الدور غير موجود',
            ]);
            return;
        }
        
        if (in_array($role->name, ['super-admin', 'admin', 'teacher', 'student', 'parent'])) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'فشل',
                'message' => 'لا يمكن حذف الأدوار الأساسية في النظام',
            ]);
            return;
        }

        if ($role->users()->count() > 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'فشل',
                'message' => 'لا يمكن حذف دور مرتبط بمستخدمين حاليين',
            ]);
            return;
        }

        $role->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحذف',
            'message' => 'تم حذف الدور بنجاح',
        ]);
    }
}
