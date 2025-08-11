@extends('layouts.app') 
@section('content')
<div class="content">
    <div class="mb-3">
        <button class="btn btn-primary btn-filter" id="btnFilterBulan">Perbulan</button>
        <button class="btn btn-outline-primary btn-filter" id="btnFilterTahun">Pertahun</button>
    </div>

    <h3 id="komisiJudul">Komisi Bulanan</h3>

    <table class="table table-hover align-middle table-bordered bg-white" id="tabelKomisi">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Judul Proyek</th>
                <th>Personel</th>
                <th>Nilai Proyek</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $projects = $projects ?? collect(); @endphp
            @forelse ($projects as $project)
                <tr 
                    data-bulan="{{ \Carbon\Carbon::parse($project->created_at)->format('m') }}"
                    data-tahun="{{ \Carbon\Carbon::parse($project->created_at)->format('Y') }}"
                >
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $project->judul }}</td>
                    <td>
                        {{ $project->projectPersonel->map(function($p) {
                            return $p->user ? $p->user->name : '(User tidak ditemukan)';
                        })->join(', ') ?: '-' }}
                    </td>
                    <td>{{ number_format($project->nilai ?? 0, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-detail">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data proyek</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const currentDate = new Date();
    const currentMonth = String(currentDate.getMonth() + 1).padStart(2, '0');
    const currentYear = currentDate.getFullYear().toString();

    const rows = document.querySelectorAll('#tabelKomisi tbody tr');
    const judul = document.getElementById('komisiJudul');

    const btnBulan = document.getElementById('btnFilterBulan');
    const btnTahun = document.getElementById('btnFilterTahun');

    function filterData(mode) {
        let showCount = 0;
        rows.forEach(row => {
            const bulan = row.dataset.bulan;
            const tahun = row.dataset.tahun;

            const isBulanIni = bulan === currentMonth && tahun === currentYear;
            const isTahunIni = tahun === currentYear;

            if ((mode === 'bulan' && isBulanIni) || (mode === 'tahun' && isTahunIni)) {
                row.style.display = '';
                showCount++;
            } else {
                row.style.display = 'none';
            }
        });

        judul.innerText = mode === 'bulan' ? 'Komisi Bulanan' : 'Komisi Tahunan';

        if (showCount === 0) {
            const tbody = document.querySelector('#tabelKomisi tbody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data proyek</td>
                </tr>
            `;
        }

        // Toggle warna tombol
        if (mode === 'bulan') {
            btnBulan.classList.remove('btn-outline-primary');
            btnBulan.classList.add('btn-primary');

            btnTahun.classList.remove('btn-primary');
            btnTahun.classList.add('btn-outline-primary');
        } else {
            btnTahun.classList.remove('btn-outline-primary');
            btnTahun.classList.add('btn-primary');

            btnBulan.classList.remove('btn-primary');
            btnBulan.classList.add('btn-outline-primary');
        }
    }

    // Default: tampilkan komisi bulanan
    filterData('bulan');

    btnBulan.addEventListener('click', () => filterData('bulan'));
    btnTahun.addEventListener('click', () => filterData('tahun'));
});
</script>
@endpush
