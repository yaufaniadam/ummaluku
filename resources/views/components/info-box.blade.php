{{-- <div class="small-box bg-{{ $color }}">
    <div class="inner">
        <h3>{{ $value }}</h3>
        <p>{{ $title }}</p>
    </div>
    <div class="icon">
        <i class="{{ $icon }}"></i>
    </div>
    @if ($url)
        <a href="{{ $url }}" class="small-box-footer">
            Lihat Detail <i class="fas fa-arrow-circle-right"></i>
        </a>
    {{-- @else
        <a href="#" class="small-box-footer">&nbsp;</a> --}
    @endif
</div> --}}


<div class="info-box">
    <span class="info-box-icon bg-{{ $color }} elevation-1"><i class="{{ $icon }}"></i></span>
    <div class="info-box-content">
        <span class="info-box-text">{{ $title }} <a href="{{ $url }}" title="Selengkapnya" class="small-box-footer">
         <i class="fas fa-long-arrow-alt-right"></i></a></span>
        <span class="info-box-number">
            {{ $value }}
        </span>
    </div>
    <!-- /.info-box-content -->
</div>
