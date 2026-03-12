<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class NotificationSettings extends Component
{
    // Email Settings
    public $email_driver;
    public $email_host;
    public $email_port;
    public $email_encryption;
    public $email_username;
    public $email_password;
    public $email_from_address;
    public $email_from_name;

    // WhatsApp Settings
    public $whatsapp_enabled;
    public $whatsapp_api_url;
    public $whatsapp_api_key;
    public $whatsapp_channel_id;

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Email
        $this->email_driver = get_setting('email_driver', 'smtp');
        $this->email_host = get_setting('email_host', 'smtp.gmail.com');
        $this->email_port = get_setting('email_port', 587);
        $this->email_encryption = get_setting('email_encryption', 'tls');
        $this->email_username = get_setting('email_username', '');
        $this->email_password = get_setting('email_password', '');
        $this->email_from_address = get_setting('email_from_address', 'info@albsaer.com');
        $this->email_from_name = get_setting('email_from_name', 'معهد البصائر');

        // WhatsApp
        $this->whatsapp_enabled = (bool) get_setting('whatsapp_enabled', false);
        $this->whatsapp_api_url = get_setting('whatsapp_api_url', 'https://api.respond.io');
        $this->whatsapp_api_key = get_setting('whatsapp_api_key', '');
        $this->whatsapp_channel_id = get_setting('whatsapp_channel_id', '');
    }

    public function save()
    {
        $this->validate([
            'email_host' => 'required',
            'email_port' => 'required|numeric',
            'email_from_address' => 'required|email',
        ]);

        // Save Email Settings
        set_setting('email_driver', $this->email_driver, 'string', 'email');
        set_setting('email_host', $this->email_host, 'string', 'email');
        set_setting('email_port', $this->email_port, 'int', 'email');
        set_setting('email_encryption', $this->email_encryption, 'string', 'email');
        set_setting('email_username', $this->email_username, 'string', 'email');
        set_setting('email_password', $this->email_password, 'string', 'email');
        set_setting('email_from_address', $this->email_from_address, 'string', 'email');
        set_setting('email_from_name', $this->email_from_name, 'string', 'email');

        // Save WhatsApp Settings
        set_setting('whatsapp_enabled', $this->whatsapp_enabled, 'bool', 'whatsapp');
        set_setting('whatsapp_api_url', $this->whatsapp_api_url, 'string', 'whatsapp');
        set_setting('whatsapp_api_key', $this->whatsapp_api_key, 'string', 'whatsapp');
        set_setting('whatsapp_channel_id', $this->whatsapp_channel_id, 'string', 'whatsapp');

        $this->dispatch('notify', [
            'type' => 'success',
            'title' => 'تم الحفظ',
            'message' => 'تم تحديث إعدادات الإشعارات بنجاح.',
        ]);
    }

    public function render()
    {
        return view('livewire.settings.notification-settings')
            ->layout('dashboard.layouts.master');
    }
}
