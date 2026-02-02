<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Poll;

class CreatePoll extends Component
{
    public $title;
    public $options = ['First'];

    protected $rules = [
        'title'     => 'required|min:3|max:255',
        'options'   => 'required|array|min:1|max:10',
        'options.*' => 'required|min:1|max:10',
    ];

    protected $messages = [
        'options.*' => 'The option can\'t be empty or be longer than 10 chars.'
    ];

    public function render()
    {
        return view('livewire.create-poll');
    }

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function createPoll()
    {
        $this->validate();

        $poll = Poll::create([
            'title' => $this->title,
        ])
            ->options()
            ->createMany(
                collect($this->options)
                            ->map(fn($option) => ['name' => $option])
                            ->all()
            );

        $this->reset(['title', 'options']);
        $this->emit('pollCreated');
    }
}
