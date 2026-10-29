@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Kelola User')

@section('content')

<div class="mb-4 d-flex align-items-center gap-3">
    <a href="{{ route('users.index') }}" class="btn btn-sm" style="background:#f1f5f9;color:#475569;border-radius:8px">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
    <div>
        <h1 class="fs-4 fw-bold mb-0">Edit User</h1>
    </div>
</div>

<div class="card" style="max-width:600px">
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
                @error('name') <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px">
                @error('email') <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Role</label>
                <select name="role" class="form-select" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $user->role) === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }} {{ $role === 'admin' ? '(akses penuh)' : '(hanya lihat)' }}
                        </option>
                    @endforeach
                </select>
                @if($user->id === auth()->id())
                    <input type="hidden" name="role" value="{{ $user->role }}">
                    <div style="font-size:12px;color:#94a3b8;margin-top:4px">Anda tidak bisa mengubah role akun sendiri.</div>
                @endif
                @error('role') <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Password Baru</label>
                <input type="password" name="password" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px" placeholder="Kosongkan jika tidak diubah">
                @error('password') <div style="font-size:12px;color:#ef4444;margin-top:4px">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:13px">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="form-control" style="border-radius:8px;border-color:#e2e8f0;font-size:13.5px" placeholder="Ulangi password baru">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
                <a href="{{ route('users.index') }}" class="btn" style="background:#f1f5f9;color:#475569">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
