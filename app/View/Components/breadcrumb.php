<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;

class Breadcrumb extends Component
{
    public string $current;

    /**
     * Create a new component instance.
     *
     * @param string $current Halaman aktif saat ini.
     * @param string $home Teks untuk halaman utama.
     * @return void
     */
    public function __construct(string $current)
    {       
        $this->current = $current;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('components.breadcrumb');
    }
}