@extends('layouts.admin')

@section('title', 'Data Pegawai')
@section('page-title', 'Data Pegawai')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Data Pegawai</h1>
        <p class="text-muted mb-0" style="font-size:13px">Kelola daftar pegawai BSI</p>
    </div>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('employees.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Tambah Pegawai
    </a>
    @endif
</div>

@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ session('error') }}
    </div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Kode Loket</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        @if(auth()->user()->isAdmin())
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($employees as $employee)
                <tr>
                    <td>
                        @if($employee->photo)
                            <img
                                src="{{ asset('storage/'.$employee->photo) }}"
                                width="44" height="44"
                                class="rounded-circle"
                                style="object-fit:cover;border:2px solid #e2e8f0"
                            >
                        @else
                            <div style="width:44px;height:44px;border-radius:50%;background:#eff6ff;display:flex;align-items:center;justify-content:center;font-weight:700;color:#2563eb;font-size:16px">
                                {{ strtoupper(substr($employee->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="fw-semibold" style="font-size:13.5px">{{ $employee->name }}</td>
                    <td style="color:#64748b;font-size:13px">{{ $employee->position }}</td>
                    <td>
                        @if($employee->counter_code)
                            <span class="badge" style="background:#eff6ff;color:#2563eb;font-family:monospace">
                                {{ $employee->counter_code }}
                            </span>
                        @else
                            <span class="badge" style="background:#fef3c7;color:#b45309" title="Hasil Computer Vision tidak bisa dicocokkan tanpa kode loket">
                                Belum diatur
                            </span>
                        @endif
                    </td>
                    <td>
                        <span style="background:#f1f5f9;color:#475569;padding:3px 8px;border-radius:6px;font-size:12px;font-weight:500">
                            {{ $employee->category?->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @if($employee->status)
                            <span class="badge" style="background:#dcfce7;color:#15803d">Aktif</span>
                        @else
                            <span class="badge" style="background:#fef2f2;color:#b91c1c">Nonaktif</span>
                        @endif
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pegawai ini?')">
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
