<div class="stepper-wrapper mb-5">
    <div class="step-item {{ $step1Class }}">
        <div class="step-counter d-flex justify-content-center align-items-center">1</div>
        {{-- Menggunakan variabel dinamis --}}
        <div class="step-name small">{{ $step1Text }}</div>
    </div>
    <div class="step-item {{ $step2Class }}">
        <div class="step-counter d-flex justify-content-center align-items-center">2</div>
        <div class="step-name small">{{ $step2Text }}</div>
    </div>
    <div class="step-item {{ $step3Class }}">
        <div class="step-counter d-flex justify-content-center align-items-center">3</div>
        <div class="step-name small">{{ $step3Text }}</div>
    </div>
    <div class="step-item {{ $step4Class }}">
        <div class="step-counter d-flex justify-content-center align-items-center">4</div>
        <div class="step-name small">{{ $step4Text }}</div>
    </div>
</div>