@extends('layout')

@section('title', 'Data Karyawan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">@yield('title')</h4>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Tambah Karyawan
        </a>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>NIK</th>
                            <th>Penempatan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($emp->name) }}&background=0f172a&color=cbd5e1&size=36"
                                            alt="Avatar" class="rounded-circle">
                                        <div>
                                            <div class="fw-bold">{{ $emp->name }}</div>
                                            <div class="small text-muted">{{ $emp->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $emp->nomor_pegawai }}</td>
                                <td>{{ $emp->penempatan }}</td>
                                <td class="text-center">
                                    @php
                                        $today = now()->toDateString();
                                        $isOnLeave = $emp->leaves
                                            ->where('tanggal_mulai', '<=', $today)
                                            ->where('tanggal_selesai', '>=', $today)
                                            ->isNotEmpty();
                                    @endphp

                                    @if ($isOnLeave)
                                        <span class="badge bg-warning me-1">Cuti</span>
                                    @else
                                        <span class="badge bg-success me-1">Aktif</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('employees.show', $emp->id) }}" class="btn btn-sm btn-outline-info"
                                        title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('employees.edit', $emp->id) }}" class="btn btn-sm btn-outline-warning"
                                        title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $emp->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data karyawan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted p-4">Belum ada data karyawan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
