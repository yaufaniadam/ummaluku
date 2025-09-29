<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatBox extends Component
{
    public string $title;
    public string $value;
    public string $icon;
    public string $color;
    public ?string $url;

    public function __construct(string $title, string $value, string $icon, string $color, string $url = null)
    {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->color = $color;
        $this->url = $url;
    }

    public function render()
    {
        return view('components.stat-box');
    }
}