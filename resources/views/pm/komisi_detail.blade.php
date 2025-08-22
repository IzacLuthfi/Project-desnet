@extends('layouts.pm')

@section('content')
<div class="container">
    <h2>Detail Komisi - {{ $project->nama_project }}</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Personel</th>
                <th>Margin</th>
                <th>Persentase (%)</th>
                <th>Nilai Komisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->komisi as $komisi)
                <tr>
                    <td>{{ $komisi->user->name ?? $komisi->projectPersonel->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($komisi->margin, 2, ',', '.') }}</td>
                    <td>{{ $komisi->persentase }}%</td>
                    <td>Rp {{ number_format($komisi->nilai_komisi, 2, ',', '.') }}</td>
                    <td>
                        <form action="{{ route('komisi.destroy', $komisi->id) }}" method="POST" class="form-hapus d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-konfirmasi-hapus">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('pm.komisi') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection

@push('scripts')
<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-konfirmasi-hapus').forEach(button => {
        button.addEventListener('click', function () {
            let form = this.closest('form');

            Swal.fire({
                title: 'Yakin hapus komisi ini?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush
