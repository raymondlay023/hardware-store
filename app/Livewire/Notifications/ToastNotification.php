<?php

namespace App\Livewire\Notifications;

use Livewire\Attributes\On;
use Livewire\Component;

class ToastNotification extends Component
{
    public $notifications = [];

    #[On('notification')]
    public function addNotification($message, $type = 'success')
    {
        $id = uniqid();
        $this->notifications[$id] = [
            'id' => $id,
            'message' => $message,
            'type' => $type,
        ];

        // Schedule removal using JavaScript
        $this->dispatch('schedule-remove', id: $id, delay: 5000);
    }

    #[On('remove-notification')]
    public function removeNotification($id)
    {
        unset($this->notifications[$id]);
    }

    public function render()
    {
        return view('livewire.notifications.toast-notification');
    }
}
