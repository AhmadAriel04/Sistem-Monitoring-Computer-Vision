@extends('layouts.admin')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Kelola User</h1>
        <p class="text-muted mb-0" style="font-size:13px">Kelola akun login & hak akses (Admin, BOSM, Manager)</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Tambah User
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif

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
                        <th width="60">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $item)
                <tr>
                    <td class="text-muted">{{ $loop->iteration }}</td>
                    <td class="fw-semibold">
                        {{ $item->name }}
                        @if($item->id === auth()->id())
                            <span class="badge" style="background:#eff6ff;color:#2563eb;font-weight:500">Anda</span>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:13px">{{ $item->email }}</td>
                    <td>
                        @php
                            $roleClasses = [
                                'admin' => 'text-primary bg-primary-subtle',
                                'bosm' => 'text-success bg-success-subtle',
                                'manager' => 'text-warning bg-warning-subtle',
                            ];
                            $roleClass = $roleClasses[$item->role] ?? 'text-secondary bg-light';
                        @endphp
                        <span class="badge {{ $roleClass }}" style="font-weight:600">
                            {{ $item->roleLabel() }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('users.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            @if($item->id !== auth()->id())
                                <form action="{{ route('users.destroy', $item->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus akun ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3" style="font-size:12.5px;color:#94a3b8">
    <i class="bi bi-info-circle me-1"></i>
    <strong>Admin</strong>: akses penuh (tambah/edit/hapus data & user). &nbsp;
    <strong>BOSM</strong> &amp; <strong>Manager</strong>: hanya bisa melihat data (view-only).
</div>

@endsection
