@extends('tablar::page') 

@section('content')
<div class="page-body">
    <div class="container-tight py-4">
        <div class="empty">
            <div class="empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path><path d="M9 12l2 2l4 -4"></path></svg>
            </div>
            <p class="empty-title h2">Pendaftaran Berhasil!</p>
            <p class="empty-subtitle text-muted">
                Terima kasih telah melakukan pendaftaran di Universitas Muhammadiyah Maluku. <br>
                Kami telah mengirimkan email ke alamat Anda untuk informasi dan langkah selanjutnya.
            </p>
            <div class="empty-action">
                <a href="/" class="btn btn-primary">
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>
</div>
@endsection