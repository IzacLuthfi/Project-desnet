@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-bold mb-4">Edit Proyek</h4>

    <form action="{{ route('project.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" name="judul" value="{{ $project->judul }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="nilai" class="form-label">Nilai</label>
            <input type="number" name="nilai" value="{{ $project->nilai }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="pm" class="form-label">Project Manager</label>
            <input type="text" name="pm" value="{{ $project->pm }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <input type="text" name="status" value="{{ $project->status }}" class="form-control">
        </div>

        <h5 class="mt-4">Personel</h5>
        @foreach($project->personel as $index => $person)
            <div class="row g-2 mb-2">
                <div class="col-md-6">
                    <input type="text" name="personel[{{ $index }}][nama]" value="{{ $person->nama }}" class="form-control" placeholder="Nama">
                </div>
                <div class="col-md-6">
                    <select name="personel[{{ $index }}][role]" class="form-select">
                        <option>Analis</option>
                        <option>Programer web</option>
                        <option>Programer mobile</option>
                        <option>Tester</option>
                        <option>Desainer</option>
                        <option>Front-end</option>
                    </select>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('project.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection