{{-- Ini adalah contoh sederhana, Anda bisa membuatnya jauh lebih bagus --}}

@if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
@endif

<h1>Penerimaan Mahasiswa Baru</h1>
<p>Silakan pilih jalur pendaftaran yang Anda inginkan:</p>

@foreach ($categories as $category)
    <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 15px;">
        <h2>{{ $category->name }}</h2>
        <p>{{ $category->description }}</p>
        <p>Biaya: Rp {{ number_format($category->price, 0, ',', '.') }}</p>

        {{-- Tombol ini akan mengarahkan ke form pendaftaran dengan parameter yang benar --}}
        <a href="{{ route('pendaftaran.form', ['type' => $category->slug, 'batch' => 1]) }}">
            Daftar via {{ $category->name }}
        </a>
    </div>
@endforeach