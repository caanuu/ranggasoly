@extends('layout')

@section('title', 'Riwayat Aktivitas')

@push('styles')
<style>
    /* Ukuran avatar di tabel */
    .avatar-sm {
        width: 36px;
        height: 36px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">@yield('title')</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($log->employee->name ?? 'S') }}&background=0f172a&color=cbd5e1&size=36"
                                             alt="Avatar" class="rounded-circle avatar-sm">
                                        <div class="fw-bold">{{ $log->employee->name ?? 'Sistem' }}</div>
                                    </div>
                                </td>
                                <td>{{ $log->activity }}</td>
                                <td>{{ $log->created_at->translatedFormat('d F Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted p-4">
                                    Belum ada riwayat aktivitas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
