<?php

namespace App\Http\Livewire;

use LivewireUI\Modal\ModalComponent;

class ConfirmModal extends ModalComponent
{
    public $route;
    public $model_id;
    public $model_name;
    public $model_action;
    public $message;

    public function mount($route, $model_id, $model_name, $model_action)
    {
        $this->route = $route;
        $this->model_id = $model_id;
        $this->model_name = $model_name;
        $this->model_action = $model_action;
        $this->message = 'Are you sure you want to ' . $model_action . ' this ' . $model_name . '?';
    }

    public function render()
    {
        return view('livewire.confirm-modal');
    }
}
