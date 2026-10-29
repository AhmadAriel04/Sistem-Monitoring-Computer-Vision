@extends('layouts.admin')

@section('title', 'Edit Pegawai')
@section('page-title', 'Pegawai')

@section('content')

<div class="mb-4 d-flex align-items-center gap-3">
    <a href="{{ route('employees.index') }}" class="btn btn-sm" style="background:#f1f5f9;color:#475569;border-radius:8px">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <div>
        <h1 class="fs-4 fw-bold mb-0">Edit Pegawai</h1>
    </div>
</div>

<div class="card" style="max-width:600px">
    <div class="card-body">

        @if($employee->photo)
        <div class="mb-4 text-center">
            <img src="{{ asset('storage/'.$employee->photo) }}" width="80" height="80"
                 class="rounded-circle" style="object-fit:cover;border:3px solid #e2e8f0">
            <p style="font-size:12px;color:#64748b;margin-top:8px">Foto saat ini</p>
        </div>
        @endif

        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Kategori</label>
                <select name="category_id" class="form-select" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
                    @foreach($categories as $item)
                        <option value="{{ $item->id }}" {{ $employee->category_id == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $employee->name }}" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Jabatan</label>
                <input type="text" name="position" value="{{ $employee->position }}" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Kode Loket (Counter Code)</label>
                <input type="text" name="counter_code" value="{{ old('counter_code', $employee->counter_code) }}" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px" placeholder="Contoh: CS-01">
                <div class="text-muted" style="font-size:11.5px;margin-top:4px">
                    Wajib diisi agar hasil Computer Vision bisa dicocokkan ke pegawai ini. Harus unik.
                </div>
                @error('counter_code') <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Status</label>
                <select name="status" class="form-select" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
                    <option value="1" {{ old('status', $employee->status) ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !old('status', $employee->status) ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <div class="text-muted" style="font-size:11.5px;margin-top:4px">
                    Computer Vision hanya mengirim data untuk pegawai berstatus Aktif.
                </div>
                @error('status') <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Ganti Foto <span style="font-weight:400;color:#94a3b8">(opsional)</span></label>
                <input type="file" name="photo" accept="image/*" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Update
                </button>
                <a href="{{ route('employees.index') }}" class="btn" style="background:#f1f5f9;color:#475569">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
