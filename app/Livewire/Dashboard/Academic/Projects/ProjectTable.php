<?php

namespace App\Livewire\Dashboard\Academic\Projects;

use App\Models\Project;
use App\Services\Academic\ProjectService;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Illuminate\Database\Eloquent\Builder;

class ProjectTable extends DataTableComponent
{
    protected $model = Project::class;

    protected ProjectService $projectService;

    public function booted(): void
    {
        $this->projectService = app(ProjectService::class);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('created_at', 'desc')
             ->setEmptyMessage('لا يوجد مشاريع لعرضها')
             ->setSearchPlaceholder('البحث في المشاريع...')
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

            Column::make("المشروع", "name")
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return "<div>
                        <div class='font-bold text-primary-600'>{$value}</div>
                        <div class='text-xs text-surface-400'>CODE: {$row->code}</div>
                    </div>";
                })->html(),

            Column::make("تاريخ البدء", "start_date")
                ->sortable()
                ->format(fn($value) => $value ? $value->format('Y/m/d') : '-'),

            Column::make("تاريخ الانتهاء", "end_date")
                ->sortable()
                ->format(fn($value) => $value ? $value->format('Y/m/d') : '-'),

            BooleanColumn::make("نشط", "is_active")
                ->sortable(),

            Column::make("المجموعات", "id")
                ->label(fn($row) => $row->groups_count)
                ->sortable(),

            Column::make("الإجراءات", "id")
                ->label(fn($row) => view('livewire.dashboard.academic.projects.columns.actions', ['project' => $row]))
                ->html(),
        ];
    }

    public function builder(): Builder
    {
        return Project::query()->withCount('groups');
    }

    public function editProject($projectId)
    {
        $this->dispatch('edit-project', $projectId);
    }

    public function confirmDelete($id)
    {
        $project = Project::find($id);
        if (!$project) return;

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد حذف المشروع',
            'message' => "هل أنت متأكد أنك تريد حذف المشروع '{$project->name}'؟ سيؤدي هذا إلى حذف المجموعات المرتبطة به أيضاً.",
            'component' => $this->getId(),
            'action' => 'deleteProject'
        ]);
    }

    public function deleteProject($projectId)
    {
        try {
            $this->projectService->deleteProject($projectId);
            
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم الحذف',
                'message' => 'تم حذف المشروع بنجاح',
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
