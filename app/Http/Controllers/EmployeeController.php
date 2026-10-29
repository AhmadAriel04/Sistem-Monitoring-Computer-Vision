<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('category')
            ->latest()
            ->get();

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('employees.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateEmployee($request);

        $photo = null;

        if ($request->hasFile('photo')) {

            $photo = $request
                ->file('photo')
                ->store('employees', 'public');
        }

        Employee::create([
            'category_id'  => $validated['category_id'],
            'name'         => $validated['name'],
            'position'     => $validated['position'],
            'counter_code' => $validated['counter_code'] ?? null,
            'photo'        => $photo,
            'status'       => $request->boolean('status'),
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Pegawai berhasil ditambahkan');
    }

    public function edit(Employee $employee)
    {
        $categories = Category::orderBy('name')->get();

        return view(
            'employees.edit',
            compact('employee', 'categories')
        );
    }

    public function update(Request $request, Employee $employee)
    {
        /*
         * Validasi harus dijalankan sebelum file lama dihapus. Pada versi
         * sebelumnya foto lama sudah terhapus lebih dulu, sehingga kalau
         * validasi gagal pegawai jadi kehilangan fotonya tanpa alasan.
         */
        $validated = $this->validateEmployee($request, $employee);

        $photo = $employee->photo;

        if ($request->hasFile('photo')) {

            if ($employee->photo) {

                Storage::disk('public')
                    ->delete($employee->photo);
            }

            $photo = $request
                ->file('photo')
                ->store('employees', 'public');
        }

        $employee->update([
            'category_id'  => $validated['category_id'],
            'name'         => $validated['name'],
            'position'     => $validated['position'],
            'counter_code' => $validated['counter_code'] ?? null,
            'photo'        => $photo,
            'status'       => $request->boolean('status'),
        ]);

        return redirect()
            ->route('employees.index')
            ->with('success', 'Pegawai berhasil diupdate');
    }

    public function destroy(Employee $employee)
    {
        /*
         * cv_sessions memakai restrictOnDelete, jadi penghapusan pegawai
         * yang sudah punya sesi CV akan dilempar sebagai QueryException.
         * Ditangkap di sini supaya pengguna dapat pesan yang jelas.
         */
        if ($employee->cvSessions()->exists()) {

            return redirect()
                ->route('employees.index')
                ->with('error', 'Pegawai tidak bisa dihapus karena sudah memiliki sesi Computer Vision. Nonaktifkan saja statusnya.');
        }

        if ($employee->photo) {

            Storage::disk('public')
                ->delete($employee->photo);
        }

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Pegawai berhasil dihapus');
    }

    /**
     * Aturan validasi yang sama untuk store dan update.
     *
     * counter_code wajib unik karena jadi satu-satunya penghubung antara
     * kamera Computer Vision dan pegawai. Kalau dua pegawai memakai kode
     * yang sama, hasil CV akan menempel ke pegawai yang salah.
     */
    private function validateEmployee(
        Request $request,
        ?Employee $employee = null
    ): array {

        return $request->validate([

            'category_id' => ['required', 'exists:categories,id'],

            'name' => ['required', 'string', 'max:100'],

            'position' => ['required', 'string', 'max:100'],

            'counter_code' => [
                'nullable',
                'string',
                'max:32',
                Rule::unique('employees', 'counter_code')
                    ->ignore($employee?->id),
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],

            'status' => ['nullable', 'boolean'],
        ], [
            'counter_code.unique' => 'Kode loket ini sudah dipakai pegawai lain.',
        ]);
    }
}
