<?php

namespace App\Livewire\Dashboard\Academic\Instructors;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Reactive;

class InstructorsTable extends DataTableComponent
{
    protected $model = Instructor::class;
    
    #[Reactive]
    public $activeYearId;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('id', 'desc')
             ->setEmptyMessage('لا يوجد مدربين مسجلين في هذا العام.')
             ->setSearchPlaceholder('ابحث باسم المدرب أو التخصص...')
             ->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
                return [
                    'class' => 'text-right p-4 font-medium text-surface-700 w-auto',
                ];
             })
             ->setThAttributes(function(Column $column) {
                return [
                    'class' => 'text-right p-4 text-xs font-black tracking-wider text-surface-500 uppercase bg-surface-50 whitespace-nowrap arabic-font',
                ];
             });
    }

    public function builder(): Builder
    {
        $query = Instructor::query()->with(['user']);
        
        if ($this->activeYearId) {
            $query->join('academic_year_instructor', 'instructors.id', '=', 'academic_year_instructor.instructor_id')
                  ->where('academic_year_instructor.academic_year_id', $this->activeYearId)
                  ->select('instructors.*', 'academic_year_instructor.subject_ids', 'academic_year_instructor.id as pivot_id');
        }

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make("المدرب", "user.name")
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    $avatar = $row->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($value).'&background=random';
                    return '<div class="flex items-center gap-3">
                                <img src="'.$avatar.'" class="w-8 h-8 rounded-full border border-surface-200 shadow-sm" />
                                <div>
                                    <p class="text-sm font-bold text-surface-900 arabic-font">'.$value.'</p>
                                    <p class="text-[10px] font-mono text-surface-500">'.$row->instructor_code.'</p>
                                </div>
                            </div>';
                })->html(),

            Column::make("التخصص", "specialization")
                ->sortable()
                ->searchable()
                ->format(fn($value) => '<span class="text-xs font-bold text-surface-600 bg-surface-100 px-2 py-1 rounded-lg arabic-font">'.($value ?? 'غير محدد').'</span>')
                ->html(),

            Column::make("المواد المسندة", "id")
                ->format(function($value, $row) {
                    if (!$this->activeYearId) return '<span class="text-xs text-surface-400">اختر عاماً للعرض</span>';
                    
                    $subjectIds = is_string($row->subject_ids) ? json_decode($row->subject_ids, true) : ($row->subject_ids ?? []);
                    if (empty($subjectIds)) return '<span class="text-xs text-surface-400 arabic-font">لا يوجد مواد</span>';
                    
                    $subjects = \App\Models\Subject::whereIn('id', $subjectIds)->pluck('name')->toArray();
                    $display = count($subjects) > 2 ? implode('، ', array_slice($subjects, 0, 2)) . '...' : implode('، ', $subjects);
                    
                    return '<div class="flex flex-col gap-1">
                                <span class="text-[11px] font-bold text-primary-600 arabic-font bg-primary-50 px-2 py-0.5 rounded-md self-start">'.count($subjects).' مواد</span>
                                <p class="text-[10px] text-surface-500 arabic-font truncate max-w-[150px]">'.$display.'</p>
                            </div>';
                })->html(),

            Column::make("الإجراءات", "id")
                ->format(function($value, $row) {
                    return '<div class="flex items-center justify-center gap-2">
                        <button wire:click="confirmRemove('.$row->id.')" wire:loading.attr="disabled" class="p-2 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors group" title="إزالة من العام">
                            <svg wire:loading.remove wire:target="confirmRemove('.$row->id.')" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <svg wire:loading wire:target="confirmRemove('.$row->id.')" class="w-4 h-4 animate-spin text-red-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>';
                })->html()
        ];
    }

    public function confirmRemove($instructorId)
    {
        $instructor = Instructor::with('user')->find($instructorId);
        if (!$instructor) return;

        $this->dispatch('confirm-delete', [
            'id' => $instructorId,
            'title' => 'تأكيد الحذف',
            'message' => "هل أنت متأكد من إزالة المدرب '{$instructor->user->name}' من هذا العام الأكاديمي؟",
            'component' => $this->getId(),
            'action' => 'removeInstructor'
        ]);
    }

    public function removeInstructor($id)
    {
        DB::table('academic_year_instructor')
            ->where('academic_year_id', $this->activeYearId)
            ->where('instructor_id', $id)
            ->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تمت الإزالة',
            'message' => 'تم إزالة ربط المدرب بالعام الحالي بنجاح.'
        ]);
    }
}
