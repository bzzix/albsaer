<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;

class GroupsTable extends DataTableComponent
{
    protected $model = Group::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('created_at', 'desc')
             ->setEmptyMessage('لا يوجد مجموعات لعرضها')
             ->setSearchPlaceholder('البحث في المجموعات...')
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
        return Group::query()
            ->with(['project', 'course', 'trainer', 'supervisor']);
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->searchable()
                ->excludeFromColumnSelect()
                ->hideIf(true),

            Column::make("الكود", "code")
                ->sortable()
                ->searchable(),

            Column::make("اسم المجموعة", "name")
                ->sortable()
                ->searchable(),

            Column::make("المشروع", "project_id")
                ->label(fn($row) => $row->project?->name ?? '-')
                ->sortable(),

            Column::make("الدورة", "course_id")
                ->label(fn($row) => $row->course?->name ?? '-')
                ->sortable(),

            Column::make("المدرب", "trainer_id")
                ->label(fn($row) => $row->trainer?->user?->name ?? '-')
                ->sortable(),

            Column::make("المشرف", "supervisor_id")
                ->label(fn($row) => $row->supervisor?->name ?? '-')
                ->sortable(),

            Column::make("عدد الطلاب", "max_students")
                ->sortable(),

            Column::make("الحالة", "status")
                ->sortable()
                ->format(function ($value, $row, $column) {
                    return view('livewire.groups.columns.status', ['group' => $row]);
                })->html(),

            Column::make("الحالة", "is_active")
                ->sortable()
                ->format(function ($value, $row, $column) {
                    return view('livewire.groups.columns.active-status', ['group' => $row]);
                })->html(),

            Column::make("الإجراءات", "id")
                ->label(fn($row) => view('livewire.groups.columns.actions', ['group' => $row]))
                ->html(),
        ];
    }
    
    public function toggleStatus(Group $group)
    {
        $group->is_active = !$group->is_active;
        $group->save();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم التحديث',
            'message' => 'تم تحديث حالة المجموعة بنجاح',
        ]);
    }

    public function confirmDelete($id)
    {
        $group = Group::find($id);
        if (!$group) return;

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد حذف المجموعة',
            'message' => "هل أنت متأكد أنك تريد حذف المجموعة '{$group->name}'؟ لا يمكن التراجع عن هذا الإجراء.",
            'component' => $this->getId(),
            'action' => 'deleteGroup'
        ]);
    }

    public function deleteGroup($groupId)
    {
        $group = Group::find($groupId);
        
        if (!$group) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ',
                'message' => 'المجموعة غير موجودة',
            ]);
            return;
        }

        $group->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحذف',
            'message' => 'تم حذف المجموعة بنجاح',
        ]);
    }
}
