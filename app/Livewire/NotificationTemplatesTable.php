<?php

namespace App\Livewire;

use App\Models\NotificationTemplate;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;

class NotificationTemplatesTable extends DataTableComponent
{
    protected $model = NotificationTemplate::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setPageName('templates')
            ->setAdditionalSelects(['slug'])
            ->setTableAttributes([
                'class' => 'premium-table w-full',
            ])
            ->setThAttributes(function(Column $column) {
                return ['class' => 'bg-surface-50 dark:bg-surface-800/50 text-surface-600 dark:text-surface-400 font-bold uppercase text-xs tracking-wider px-6 py-4 border-b border-surface-100 dark:border-surface-700'];
            })
            ->setTrAttributes(function($row, $index) {
                return ['class' => 'hover:bg-surface-50/50 dark:hover:bg-surface-800/30 transition-colors border-b border-surface-100 dark:border-surface-700'];
            });
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()
                ->collapseOnMobile(),

            Column::make("اسم القالب", "name")
                ->sortable()
                ->searchable()
                ->format(function($value, $row) {
                    return '<div>
                        <div class="font-bold text-surface-900 dark:text-surface-100">' . $value . '</div>
                        <div class="text-[10px] text-surface-500 font-mono">' . $row->slug . '</div>
                    </div>';
                })
                ->html(),

            ComponentColumn::make('النظام', 'is_system_active')
                ->component('table-toggle')
                ->attributes(fn($value, $row, Column $column) => [
                    'active' => $value,
                    'action' => 'toggleStatus(' . $row->id . ', "is_system_active")',
                ]),

            ComponentColumn::make('البريد', 'is_email_active')
                ->component('table-toggle')
                ->attributes(fn($value, $row, Column $column) => [
                    'active' => $value,
                    'action' => 'toggleStatus(' . $row->id . ', "is_email_active")',
                ]),

            ComponentColumn::make('واتساب', 'is_whatsapp_active')
                ->component('table-toggle')
                ->attributes(fn($value, $row, Column $column) => [
                    'active' => $value,
                    'action' => 'toggleStatus(' . $row->id . ', "is_whatsapp_active")',
                ]),

            ComponentColumn::make('الحالة العامة', 'is_active')
                ->component('table-toggle')
                ->attributes(fn($value, $row, Column $column) => [
                    'active' => $value,
                    'action' => 'toggleStatus(' . $row->id . ', "is_active")',
                    'isGlobal' => true,
                ]),

            Column::make("آخر تحديث", "updated_at")
                ->sortable()
                ->format(fn($value) => $value->locale('ar')->diffForHumans())
                ->collapseOnMobile(),
        ];
    }

    public function toggleStatus($id, $field)
    {
        $template = NotificationTemplate::findOrFail($id);
        $template->$field = !$template->$field;
        $template->save();

        $status = $template->$field ? 'تفعيل' : 'إلغاء تفعيل';
        $label = $this->getFieldLabel($field);

        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم التحديث',
            'message' => "تم {$status} إشعارات {$label} لقالب {$template->name}",
        ]);
    }

    private function getFieldLabel($field)
    {
        return match($field) {
            'is_system_active' => 'النظام',
            'is_email_active' => 'البريد',
            'is_whatsapp_active' => 'الواتساب',
            'is_active' => 'القالب ككل',
            default => 'الإشعار'
        };
    }
}
