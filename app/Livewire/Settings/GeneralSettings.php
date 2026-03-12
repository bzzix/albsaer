<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class GeneralSettings extends Component
{
    use WithFileUploads;

    public $site_name;
    public $site_name_en;
    public $site_description;
    public $site_email;
    public $site_phone;
    public $site_address;
    public $site_logo;
    public $site_favicon;
    public $timezone;
    public $locale;
    public $meta_keywords;
    public $site_display_mode;

    public $new_logo;
    public $new_favicon;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->site_name = get_setting('site_name');
        $this->site_name_en = get_setting('site_name_en');
        $this->site_description = get_setting('site_description');
        $this->site_email = get_setting('site_email');
        $this->site_phone = get_setting('site_phone');
        $this->site_address = get_setting('site_address');
        $this->site_logo = get_setting('site_logo');
        $this->site_favicon = get_setting('site_favicon');
        $this->timezone = get_setting('timezone', 'Asia/Riyadh');
        $this->locale = get_setting('locale', 'ar');
        $this->site_display_mode = get_setting('site_display_mode', 'both');
        
        $metaTags = get_setting('meta_tags', []);
        $this->meta_keywords = $metaTags['keywords'] ?? '';
    }

    public function save()
    {
        $this->dispatch('notify', [
            'type' => 'info',
            'title' => 'جاري الحفظ...',
            'message' => 'بدأ معالجة طلب الحفظ، يرجى الانتظار.',
        ]);

        try {
            $this->validate([
                'site_name' => 'required|string|max:255',
                'site_email' => 'required|email',
                'new_logo' => 'nullable|image|max:1024',
                'new_favicon' => 'nullable|image|max:512',
            ]);

            if ($this->new_logo) {
                $path = $this->new_logo->store('settings', 'public');
                $this->site_logo = Storage::url($path);
            }

            if ($this->new_favicon) {
                $path = $this->new_favicon->store('settings', 'public');
                $this->site_favicon = Storage::url($path);
            }

            set_setting('site_name', $this->site_name, 'string', 'general');
            set_setting('site_name_en', $this->site_name_en, 'string', 'general');
            set_setting('site_description', $this->site_description, 'string', 'general');
            set_setting('site_email', $this->site_email, 'string', 'general');
            set_setting('site_phone', $this->site_phone, 'string', 'general');
            set_setting('site_address', $this->site_address, 'string', 'general');
            set_setting('site_logo', $this->site_logo, 'string', 'general');
            set_setting('site_favicon', $this->site_favicon, 'string', 'general');
            set_setting('timezone', $this->timezone, 'string', 'general');
            set_setting('locale', $this->locale, 'string', 'general');
            set_setting('site_display_mode', $this->site_display_mode, 'string', 'general');
            
            set_setting('meta_tags', [
                'description' => $this->site_description,
                'keywords' => $this->meta_keywords
            ], 'array', 'general');

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'تم الحفظ',
                'message' => 'تم تحديث الإعدادات العامة بنجاح.',
            ]);
            
            // Re-load to ensure UI reflects saved state
            $this->loadSettings();

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'خطأ في الحفظ',
                'message' => 'حدث خطأ غير متوقع: ' . $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.settings.general-settings')
            ->layout('dashboard.layouts.master');
    }
}
