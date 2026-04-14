<?php

namespace App\Livewire\Dashboard\Academic\AcademicYears;

use App\Models\AcademicYear;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;

class AcademicYearTable extends DataTableComponent
{
    protected $model = AcademicYear::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('start_date', 'desc')
             ->setEmptyMessage('لا يوجد أعوام أكاديمية לעرضها')
             ->setSearchPlaceholder('البحث في الأعوام...')
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
        return AcademicYear::query();
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->searchable()
                ->excludeFromColumnSelect()
                ->hideIf(true),

            Column::make("اسم العام الأكاديمي", "name")
                ->sortable()
                ->searchable(),

            Column::make("كود العام", "code")
                ->sortable()
                ->searchable(),

            Column::make("تاريخ البداية", "start_date")
                ->sortable()
                ->format(fn($value) => $value->format('Y/m/d')),

            Column::make("تاريخ النهاية", "end_date")
                ->sortable()
                ->format(fn($value) => $value->format('Y/m/d')),

            Column::make("حالة العام", "is_active")
                ->sortable()
                ->format(function ($value, $row, $column) {
                    return view('livewire.dashboard.academic.academic-years.columns.status', ['year' => $row]);
                })->html(),

            Column::make("الإجراءات", "id")
                ->label(fn($row) => view('livewire.dashboard.academic.academic-years.columns.actions', ['year' => $row]))
                ->html(),
        ];
    }

    public function toggleStatus(AcademicYear $year)
    {
        $year->is_active = !$year->is_active;
        $year->save();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم التحديث',
            'message' => 'تم تحديث حالة العام الأكاديمي بنجاح',
        ]);
    }

    public function confirmDelete($id)
    {
        $year = AcademicYear::find($id);
        if (!$year) return;

        // Check if there are projects related
        if ($year->projects()->count() > 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'منع الحذف',
                'message' => 'لا يمكن حذف هذا العام لوجود مشاريع مرتبطة به.',
            ]);
            return;
        }

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد حذف العام الأكاديمي',
            'message' => "هل أنت متأكد أنك تريد حذف العام الأكاديمي '{$year->name}'؟",
            'component' => $this->getId(),
            'action' => 'deleteAcademicYear'
        ]);
    }

    public function deleteAcademicYear($id)
    {
        $year = AcademicYear::find($id);
        
        if (!$year) return;

        if ($year->projects()->count() > 0) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'فشل الحذف',
                'message' => 'لا يمكن حذف هذا العام لارتباطه بمشاريع.',
            ]);
            return;
        }

        $year->delete();
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحذف',
            'message' => 'تم حذف العام الأكاديمي بنجاح',
        ]);
    }
}
