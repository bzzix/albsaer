<?php

namespace App\Livewire\Dashboard\Academic\Schedules;

use App\Models\StudyPeriod;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Illuminate\Database\Eloquent\Builder;

class StudyPeriodTable extends DataTableComponent
{
    protected $model = StudyPeriod::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('created_at', 'desc')
             ->setEmptyMessage('لا يوجد فترات دراسة لعرضها')
             ->setSearchPlaceholder('البحث في الفترات...')
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

            Column::make("اسم الفترة", "name")
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return "<div>
                        <div class='font-bold text-primary-600'>{$value}</div>
                        <div class='text-[10px] text-surface-400'>{$row->academicYear?->name}</div>
                    </div>";
                })->html(),

            Column::make("التوقيت", "start_time")
                ->format(function($value, $row) {
                    return "<div class='flex flex-col gap-1'>
                        <div class='text-xs font-bold'>{$row->start_time} - {$row->end_time}</div>
                        <div class='text-[10px] text-surface-400'>{$row->sessions_count} حصص / {$row->session_duration}د</div>
                    </div>";
                })->html(),

            Column::make("الاستراحة", "break_duration")
                ->format(fn($value) => "{$value} دقيقة"),

            Column::make("أيام الدراسة", "active_days")
                ->format(function($value) {
                    $days = [
                        0 => 'أحد', 1 => 'اثنين', 2 => 'ثلاثاء', 
                        3 => 'أربعاء', 4 => 'خميس', 5 => 'جمعة', 6 => 'سبت'
                    ];
                    $selected = array_map(fn($v) => $days[$v] ?? '', $value);
                    return "<div class='flex flex-wrap gap-1'>" . 
                        implode('', array_map(fn($d) => "<span class='px-1.5 py-0.5 bg-surface-100 rounded text-[9px]'>$d</span>", $selected)) . 
                        "</div>";
                })->html(),

            BooleanColumn::make("نشطة", "is_active")
                ->sortable(),

            Column::make("الإجراءات", "id")
                ->label(fn($row) => view('livewire.dashboard.academic.schedules.columns.actions', ['period' => $row]))
                ->html(),
        ];
    }

    public function builder(): Builder
    {
        return StudyPeriod::query()->with('academicYear');
    }

    public function editPeriod($id)
    {
        $this->dispatch('edit-period', $id)->to(StudyPeriodsManager::class);
    }

    public function confirmDelete($id)
    {
        $period = StudyPeriod::find($id);
        if (!$period) return;

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد حذف الفترة',
            'message' => "هل أنت متأكد أنك تريد حذف فترة '{$period->name}'؟",
            'component' => $this->getId(),
            'action' => 'deletePeriod'
        ]);
    }

    public function deletePeriod($id)
    {
        StudyPeriod::find($id)?->delete();
        $this->dispatch('notify', ['type' => 'success', 'message' => 'تم حذف الفترة بنجاح']);
    }
}
