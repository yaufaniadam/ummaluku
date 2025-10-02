<div class="small-box bg-{{ $color }}">
    <div class="inner">
        <h3>{{ $value }}</h3>
        <p>{{ $title }}</p>
    </div>
    <div class="icon">
        <i class="{{ $icon }}"></i>
    </div>
    @if($url)
        <a href="{{ $url }}" class="small-box-footer">
            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
        </a>
    {{-- @else
        <a href="#" class="small-box-footer">&nbsp;</a> --}}
    @endif
</div>