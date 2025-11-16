@extends('layout')

@section('title', 'Penggajian Karyawan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">@yield('title')</h4>
        <a href="{{ route('salary.edit') }}" class="btn btn-primary">
            <i class="bi bi-sliders"></i> Set Tarif Gaji
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('salary.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="month" class="form-label">Pilih Bulan</label>
                    <select name="month" id="month" class="form-select">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Pilih Tahun</label>
                    <input type="number" id="year" name="year" value="{{ $year }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-filter"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Sakit</th>
                            <th class="text-center">Tdk Hadir</th>
                            <th class="text-center">Kosong</th>
                            <th class="text-center">Total Gaji</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $data)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($data['employee']->name) }}&background=0f172a&color=cbd5e1&size=36"
                                            alt="Avatar" class="rounded-circle">
                                        <div>
                                            <div class="fw-bold">{{ $data['employee']->name }}</div>
                                            <div class="small text-muted">{{ $data['employee']->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $data['hadir'] }}</td>
                                <td class="text-center">{{ $data['sakit'] }}</td>
                                <td class="text-center">{{ $data['tidak_hadir'] }}</td>
                                <td class="text-center">{{ $data['kosong'] }}</td>
                                <td class="text-center fw-bold">Rp {{ number_format($data['totalSalary'], 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('salary.print', ['employee' => $data['employee']->id, 'month' => $month, 'year' => $year]) }}"
                                            class="btn btn-sm btn-info" target="_blank" title="Cetak (Print)">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        <a href="{{ route('salary.download', ['employee' => $data['employee']->id, 'month' => $month, 'year' => $year]) }}"
                                            class="btn btn-sm btn-danger" title="Download PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted p-4">Belum ada data pegawai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        {{-- <tr>
                            <td colspan="5" class="text-center">Total Penggajian Bulan Ini</td>
                            <td class="text-center">Rp {{ number_format($salarycost, 0, ',', '.') }}</td>
                            <td></td>
                        </tr> --}}
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
