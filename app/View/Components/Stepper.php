<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Stepper extends Component
{
    // Properti untuk kelas CSS (tetap sama)
    public string $step1Class = '';
    public string $step2Class = '';
    public string $step3Class = '';
    public string $step4Class = '';

    /**
     * Create a new component instance.
     * @param int $currentStep Langkah aktif saat ini
     * @param string $step1Text Teks untuk langkah 1
     * @param string $step2Text Teks untuk langkah 2
     * @param string $step3Text Teks untuk langkah 3
     * @param string $step4Text Teks untuk langkah 4
     */
    public function __construct(
        public int $currentStep = 1,
        public string $step1Text = 'Langkah 1',
        public string $step2Text = 'Langkah 2',
        public string $step3Text = 'Langkah 3',
        public string $step4Text = 'Langkah 4'
    ) {
        // Logika untuk menentukan kelas 'completed' atau 'active' tetap sama
        $steps = [1, 2, 3, 4];
        foreach ($steps as $step) {
            $property = 'step' . $step . 'Class';
            if ($step < $this->currentStep) {
                $this->{$property} = 'completed';
            } elseif ($step == $this->currentStep) {
                $this->{$property} = 'active';
            }
        }
    }

    public function render(): View
    {
        return view('components.stepper');
    }
}