<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;

class ProjectsTable extends DataTableComponent
{
    protected $model = Project::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('created_at', 'desc')
             ->setEmptyMessage('لا يوجد مشاريع لعرضها')
             ->setSearchPlaceholder('البحث في المشاريع...')
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
        return Project::query()
            ->with(['academicYear', 'creator']);
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

            Column::make("اسم المشروع", "name")
                ->sortable()
                ->searchable(),

            Column::make("السنة الأكاديمية", "academic_year_id")
                ->label(fn($row) => $row->academicYear?->name ?? '-')
                ->sortable(),

            Column::make("التاريخ", "start_date")
                ->sortable()
                ->format(fn($value, $row) => $row->start_date?->format('Y/m/d') . ' إلى ' . $row->end_date?->format('Y/m/d') ?? '-'),

            Column::make("الحالة", "status")
                ->sortable()
                ->format(function ($value, $row, $column) {
                    return view('livewire.projects.columns.status', ['project' => $row]);
                })->html(),

            Column::make("الحالة", "is_active")
                ->sortable()
                ->format(function ($value, $row, $column) {
                    return view('livewire.projects.columns.active-status', ['project' => $row]);
                })->html(),

            Column::make("الإجراءات", "id")
                ->label(fn($row) => view('livewire.projects.columns.actions', ['project' => $row]))
                ->html(),
        ];
    }
    
    public function toggleStatus(Project $project)
    {
        $project->is_active = !$project->is_active;
        $project->save();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم التحديث',
            'message' => 'تم تحديث حالة المشروع بنجاح',
        ]);
    }

    public function confirmDelete($id)
    {
        $project = Project::find($id);
        if (!$project) return;

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد حذف المشروع',
            'message' => "هل أنت متأكد أنك تريد حذف المشروع '{$project->name}'؟ لا يمكن التراجع عن هذا الإجراء.",
            'component' => $this->getId(),
            'action' => 'deleteProject'
        ]);
    }

    public function deleteProject($projectId)
    {
        $project = Project::find($projectId);
        
        if (!$project) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ',
                'message' => 'المشروع غير موجود',
            ]);
            return;
        }

        $project->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحذف',
            'message' => 'تم حذف المشروع بنجاح',
        ]);
    }
}
