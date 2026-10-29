@extends('layouts.admin')

@section('title', 'Data Kategori')
@section('page-title', 'Data Kategori')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Data Kategori</h1>
        <p class="text-muted mb-0" style="font-size:13px">Kelola kategori layanan BSI</p>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('categories.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Kategori</th>
                        <th>Icon</th>
                        @if(auth()->user()->isAdmin())
                        <th width="160">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($categories as $item)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td class="fw-semibold">{{ $item->name }}</td>
                    <td>
                        <span style="background:#f8fafc;padding:4px 10px;border-radius:6px;font-size:13px;border:1px solid #e2e8f0">
                            {{ $item->icon }}
                        </span>
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('categories.destroy', $item->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus data?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
