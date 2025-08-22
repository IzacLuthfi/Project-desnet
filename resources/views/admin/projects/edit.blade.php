@extends('layouts.app')

@section('content')
{{-- ======== Layer Belakang ======== --}}
<div class="full-width-content">
    @include('admin.projects.partials.layar-belakang')
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ======== Modal Edit (Layer Depan) ======== --}}
<div class="modal-overlay d-flex justify-content-center align-items-center">
    <div class="card shadow-lg modal-card">
        <div class="card-body p-5">
            <h4 class="fw-bold mb-4">Edit Dokumen</h4>

            <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Proyek --}}
                <h6 class="fw-bold mb-3">Proyek</h6>
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Proyek</label>
                    <input type="text" name="judul" id="judul" class="form-control"
                        value="{{ old('judul', $project->judul) }}">
                </div>
                <div class="mb-4">
                    <label for="nilai" class="form-label">Nilai Proyek</label>
                    <input type="number" name="nilai" id="nilai" class="form-control"
                        value="{{ old('nilai', $project->nilai) }}">
                </div>

                {{-- Personel --}}
                <div class="mb-3">
                    <h6 class="fw-semibold">Personel</h6>

                    {{-- Project Manager --}}
                    <div class="mb-3">
                        <label class="form-label">Project Manager</label>
                        <select name="pm_id" id="edit_pm_id" class="form-select" required>
                            <option value="">-- Pilih Project Manager --</option>
                            @foreach ($projectManagers as $pm)
                                <option value="{{ $pm->id }}"
                                    {{ $pm->id == old('pm_id', $project->pm_id) ? 'selected' : '' }}>
                                    {{ $pm->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Personel list --}}
                    <div id="editPersonelContainer">
                        @php
                            $personels = $project->projectPersonel;
                            $maxPersonel = max(3, count($personels)); // minimal 3 slot
                        @endphp

                        @for ($i = 0; $i < $maxPersonel; $i++)
                            @php
                                $selectedUser = $personels[$i]->user_id ?? '';
                                $selectedRole = $personels[$i]->role ?? '';
                            @endphp
                            <div class="row g-2 mb-3 personel-row">
                                <div class="col-md-6">
                                    <label class="form-label">Personel {{ $i + 1 }}</label>
                                    <select name="personel[{{ $i }}][user_id]" class="form-select">
                                        <option value="">-- Pilih Personel --</option>
                                        @foreach ($staffs as $staff)
                                            <option value="{{ $staff->id }}"
                                                {{ $staff->id == old("personel.$i.user_id", $selectedUser) ? 'selected' : '' }}>
                                                {{ $staff->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sebagai:</label>
                                    <select name="personel[{{ $i }}][role]" class="form-select">
                                        <option value="">-- Pilih Peran --</option>
                                        @foreach (['Analis', 'Programer web', 'Programer mobile', 'Tester', 'Desainer', 'Front-end'] as $role)
                                            <option value="{{ $role }}"
                                                {{ $role == old("personel.$i.role", $selectedRole) ? 'selected' : '' }}>
                                                {{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- Tombol Tambah Personel --}}
                    <button type="button" 
                            class="btn btn-sm btn-primary mt-2 rounded-pill px-3" 
                            id="editAddPersonelBtn">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Personel
                    </button>
                </div>

                {{-- Footer --}}
                <div class="modal-footer border-top-0">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4" id="btnUpdateProject">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



{{-- ======== CSS Modal Overlay & Scroll ======== --}}
<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.4);
    z-index: 1050;
    padding: 20px; /* biar ada ruang di atas bawah saat scroll */
    overflow-y: auto; /* biar overlay bisa di-scroll */
}

.modal-card {
    max-width: 700px;
    width: 100%;
    border-radius: 12px;
    max-height: 90vh; /* tinggi maksimal modal */
    overflow-y: auto; /* scroll di dalam modal */
}

.full-width-content {
    margin-left: -240px; /* menghilangkan offset sidebar */
    width: calc(100% + 240px);
    padding: 0; /* hilangkan padding bawaan */
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ Script loaded");

    const addBtn = document.getElementById("editAddPersonelBtn");
    const container = document.getElementById("editPersonelContainer");

    if (addBtn) {
        addBtn.addEventListener("click", function () {
            const index = container.querySelectorAll(".personel-row").length;

            const newRow = document.createElement("div");
            newRow.classList.add("row", "g-2", "mb-3", "personel-row");
            newRow.innerHTML = `
                <div class="col-md-6">
                    <label class="form-label">Personel ${index + 1}</label>
                    <select name="personel[${index}][user_id]" class="form-select">
                        <option value="">-- Pilih Personel --</option>
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sebagai:</label>
                    <select name="personel[${index}][role]" class="form-select">
                        <option value="">-- Pilih Peran --</option>
                        @foreach (['Analis','Programer web','Programer mobile','Tester','Desainer','Front-end'] as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
            `;
            container.appendChild(newRow);
        });
    }
});
</script>

{{-- SweetAlert Success --}}
@if(session('success'))
<script>
    document.addEventListener("DOMContentLoaded", function () {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            // setelah alert selesai -> redirect ke dashboard
            window.location.href = "{{ route('admin.dashboard') }}";
        });
    });
</script>
@endif

@endpush
