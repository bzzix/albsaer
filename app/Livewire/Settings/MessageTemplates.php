<?php

namespace App\Livewire\Settings;

use App\Models\NotificationTemplate;
use Livewire\Component;

class MessageTemplates extends Component
{
    public $templates = [];
    public $editingTemplateId = null;
    
    // Form fields
    public $name;
    public $type;
    public $subject;
    public $content;
    public $variables;
    public $schedule_time;
    public $is_active;

    protected $rules = [
        'name' => 'required|string|max:255',
        'type' => 'required|in:email,whatsapp',
        'subject' => 'nullable|required_if:type,email|string|max:255',
        'content' => 'required|string',
        'schedule_time' => 'nullable|string|max:10',
    ];

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $this->templates = NotificationTemplate::all();
    }

    public function editTemplate($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        $this->editingTemplateId = $id;
        $this->name = $template->name;
        $this->type = $template->type;
        $this->subject = $template->subject;
        $this->content = $template->content;
        $this->variables = is_array($template->variables) ? implode(', ', $template->variables) : $template->variables;
        $this->schedule_time = $template->schedule_time;
        $this->is_active = $template->is_active;
    }

    public function cancelEdit()
    {
        $this->editingTemplateId = null;
        $this->reset(['name', 'type', 'subject', 'content', 'variables', 'schedule_time', 'is_active']);
    }

    public function save()
    {
        $this->validate();

        $variablesArray = array_map('trim', explode(',', $this->variables));

        if ($this->editingTemplateId) {
            $template = NotificationTemplate::findOrFail($this->editingTemplateId);
            $template->update([
                'name' => $this->name,
                'type' => $this->type,
                'subject' => $this->subject,
                'content' => $this->content,
                'variables' => $variablesArray,
                'schedule_time' => $this->schedule_time,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم التحديث',
                'message' => 'تم تحديث القالب بنجاح.',
            ]);
        } else {
            NotificationTemplate::create([
                'name' => $this->name,
                'type' => $this->type,
                'subject' => $this->subject,
                'content' => $this->content,
                'variables' => $variablesArray,
                'schedule_time' => $this->schedule_time,
                'is_active' => $this->is_active,
            ]);
            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم الإنشاء',
                'message' => 'تم إنشاء القالب بنجاح.',
            ]);
        }

        $this->cancelEdit();
        $this->loadTemplates();
    }

    public function deleteTemplate($id)
    {
        NotificationTemplate::findOrFail($id)->delete();
        $this->loadTemplates();
        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحذف',
            'message' => 'تم حذف القالب بنجاح.',
        ]);
    }

    public function toggleStatus($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        $template->update(['is_active' => !$template->is_active]);
        $this->loadTemplates();
    }

    public function render()
    {
        return view('livewire.settings.message-templates')
            ->layout('dashboard.layouts.master');
    }
}
