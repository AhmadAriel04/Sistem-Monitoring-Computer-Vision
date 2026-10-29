@extends('layouts.admin')

@section('title', 'Edit Kategori')
@section('page-title', 'Kategori')

@section('content')

<div class="mb-4 d-flex align-items-center gap-3">
    <a href="{{ route('categories.index') }}" class="btn btn-sm" style="background:#f1f5f9;color:#475569;border-radius:8px">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <h1 class="fs-4 fw-bold mb-0">Edit Kategori</h1>
</div>

<div class="card" style="max-width:480px">
    <div class="card-body">
        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Nama Kategori</label>
                <input type="text" name="name" value="{{ $category->name }}" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
                @error('name') <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Icon Bootstrap</label>
                <input type="text" name="icon" value="{{ $category->icon }}" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
                @error('icon') <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Update
                </button>
                <a href="{{ route('categories.index') }}" class="btn" style="background:#f1f5f9;color:#475569">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
