<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class DesignSettings extends Component
{
    public $primary_color;
    public $secondary_color;
    public $font_family;
    public $border_radius;
    public $glass_intensity;
    public $dark_mode_enabled;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->primary_color = get_setting('primary_color', '#3b82f6');
        $this->secondary_color = get_setting('secondary_color', '#6366f1');
        $this->font_family = get_setting('font_family', 'Cairo');
        $this->border_radius = get_setting('border_radius', 'xl');
        $this->glass_intensity = get_setting('glass_intensity', 'middle');
        $this->dark_mode_enabled = (bool) get_setting('dark_mode_enabled', false);
    }

    public function save()
    {
        try {
            $this->validate([
                'primary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
                'secondary_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            ]);

            set_setting('primary_color', $this->primary_color, 'string', 'design');
            set_setting('secondary_color', $this->secondary_color, 'string', 'design');
            set_setting('font_family', $this->font_family, 'string', 'design');
            set_setting('border_radius', $this->border_radius, 'string', 'design');
            set_setting('glass_intensity', $this->glass_intensity, 'string', 'design');
            set_setting('dark_mode_enabled', $this->dark_mode_enabled, 'bool', 'design');

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم الحفظ',
                'message' => 'تم تحديث إعدادات التصميم بنجاح.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ في النظام',
                'message' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage(),
            ]);
        }
    }

    public function resetDefaults()
    {
        $this->primary_color = '#3b82f6';
        $this->secondary_color = '#6366f1';
        $this->font_family = 'Cairo';
        $this->border_radius = 'xl';
        $this->glass_intensity = 'middle';
        $this->dark_mode_enabled = false;

        $this->save();

        $this->dispatch('notify', [
            'type' => 'info',
            'title' => 'تم استعادة الافتراضات',
            'message' => 'تم إعادة كافة إعدادات التصميم لوضعها الأصلي.',
        ]);
    }

    public function render()
    {
        return view('livewire.settings.design-settings')
            ->layout('dashboard.layouts.master');
    }
}
