<?php

namespace App\Livewire\Dashboard\Academic\Subjects;

use App\Models\Subject;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Reactive;

class SubjectsTable extends DataTableComponent
{
    protected $model = Subject::class;
    
    // We bind to the activeYearId from the parent component
    #[Reactive]
    public $activeYearId;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
             ->setDefaultSort('id', 'desc')
             ->setEmptyMessage('لا يوجد مواد مسجلة في هذا العام.')
             ->setSearchPlaceholder('ابحث باسم المادة أو الكود...')
             ->setSearchDebounce(500)
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
        $query = Subject::query();
        if ($this->activeYearId) {
            $query->where('academic_year_id', $this->activeYearId)
                  ->orWhereNull('academic_year_id');
        }
        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make("الكود", "code")
                ->sortable()
                ->searchable()
                ->format(fn($value) => '<span class="text-xs font-bold font-mono text-surface-500 bg-surface-100 px-2 py-1 rounded-md">'.($value ?? '-').'</span>')
                ->html(),

            Column::make("اسم المادة", "name")
                ->sortable()
                ->searchable()
                ->format(function ($value, $row) {
                    $desc = $row->description ? '<p class="text-xs text-surface-500 arabic-font mt-1 max-w-xs truncate">'.$row->description.'</p>' : '';
                    return '<p class="text-sm font-bold text-surface-900 arabic-font">'.$value.'</p>' . $desc;
                })->html(),

            Column::make("الحالة", "is_active")
                ->sortable()
                ->format(function ($value, $row) {
                    $color = $value ? 'bg-primary-500' : 'bg-surface-300';
                    $translate = $value ? '-translate-x-6' : '-translate-x-1';
                    
                    return '<button wire:click="toggleStatus(' . $row->id . ')" wire:loading.attr="disabled" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors ' . $color . '">
                                <span wire:loading.remove wire:target="toggleStatus(' . $row->id . ')" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform ' . $translate . '"></span>
                                <span wire:loading wire:target="toggleStatus(' . $row->id . ')" class="inline-block h-3 w-3 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto"></span>
                            </button>';
                })->html(),

            Column::make("الإجراءات", "id")
                ->format(function($value, $row) {
                    return '<div class="flex items-center justify-center gap-2">
                        <button wire:click="$parent.edit(' . $value . ')" wire:loading.attr="disabled" class="p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors group">
                            <svg wire:loading.remove wire:target="$parent.edit(' . $value . ')" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            <svg wire:loading wire:target="$parent.edit(' . $value . ')" class="w-4 h-4 animate-spin text-primary-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                        <button wire:click="confirmDelete(' . $value . ')" wire:loading.attr="disabled" class="p-2 text-surface-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg wire:loading.remove wire:target="confirmDelete(' . $value . ')" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <svg wire:loading wire:target="confirmDelete(' . $value . ')" class="w-4 h-4 animate-spin text-red-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>';
                })->html()
        ];
    }

    public function toggleStatus($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->is_active = !$subject->is_active;
        $subject->save();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم التحديث',
            'message' => 'تم تغيير حالة المادة بنجاح.'
        ]);
    }

    public function confirmDelete($id)
    {
        $subject = Subject::find($id);
        if (!$subject) return;

        $this->dispatch('confirm-delete', [
            'id' => $id,
            'title' => 'تأكيد الحذف',
            'message' => "هل أنت متأكد من حذف المادة '{$subject->name}'؟ لا يمكن التراجع عن هذا الإجراء.",
            'component' => $this->getId(),
            'action' => 'deleteSubject'
        ]);
    }

    public function deleteSubject($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $subject->delete();
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم الحذف',
                'message' => 'تم حذف المادة بنجاح.'
            ]);
        }
    }
}
