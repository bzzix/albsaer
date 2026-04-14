<?php

namespace App\Livewire\Dashboard\Academic\Groups;

use App\Models\Group;
use App\Models\Project;
use App\Services\Academic\GroupService;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class GroupTable extends DataTableComponent
{
    protected $model = Group::class;

    protected GroupService $groupService;
    
    public ?int $projectId = null;

    public function booted(): void
    {
        $this->groupService = app(GroupService::class);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('created_at', 'desc')
             ->setEmptyMessage('لا يوجد مجموعات لعرضها')
             ->setSearchPlaceholder('البحث في المجموعات...')
             ->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
                return [
                    'class' => 'text-right p-4 font-medium text-surface-700 w-auto arabic-font',
                ];
             })
             ->setThAttributes(function(Column $column) {
                return [
                    'class' => 'text-right p-4 text-xs tracking-wider text-surface-500 uppercase bg-surface-50 whitespace-nowrap arabic-font',
                ];
             });
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")->hideIf(true),

            Column::make("المجموعة", "name")
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return "<div>
                        <div class='font-bold text-primary-600'>{$value}</div>
                        <div class='text-xs text-surface-400'>CODE: {$row->code}</div>
                    </div>";
                })->html(),

            Column::make("المشروع", "project.name")
                ->sortable()
                ->searchable(),

            Column::make("المدرب", "trainer.user.name")
                ->sortable()
                ->format(fn($value) => $value ?? 'لم يعين بعد'),

            Column::make("السعة", "max_students")
                ->sortable()
                ->format(fn($value, $row) => "{$row->students_count} / {$value}"),

            BooleanColumn::make("نشطة", "is_active")
                ->sortable(),

            Column::make("الإجراءات", "id")
                ->label(fn($row) => view('livewire.dashboard.academic.groups.columns.actions', ['group' => $row]))
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('المشروع', 'project_id')
                ->options(
                    Project::query()
                        ->active()
                        ->pluck('name', 'id')
                        ->toArray()
                )
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('project_id', $value);
                }),
        ];
    }

    public function builder(): Builder
    {
        $query = Group::query()->with(['project', 'trainer.user'])->withCount('students');

        if ($this->projectId) {
            $query->where('project_id', $this->projectId);
        }

        return $query;
    }

    public function editGroup($groupId)
    {
        $this->dispatch('edit-group', $groupId);
    }

    public function confirmDelete($id)
    {
        $group = Group::find($id);
        if (!$group) return;

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد حذف المجموعة',
            'message' => "هل أنت متأكد أنك تريد حذف المجموعة '{$group->name}'؟",
            'component' => $this->getId(),
            'action' => 'deleteGroup'
        ]);
    }

    public function deleteGroup($groupId)
    {
        try {
            $this->groupService->deleteGroup($groupId);
            
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم الحذف',
                'message' => 'تم حذف المجموعة بنجاح',
            ]);
            
            $this->dispatch('refreshDatatable');
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'فشل',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
