@extends('layout')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-4 fw-bold">Input Kehadiran Pegawai</h5>
                        <form method="POST" action="{{ route('attendances.store', 0) }}" enctype="multipart/form-data"
                            id="attendanceForm">
                            @csrf
                            <div class="mb-3 position-relative">
                                <label for="employee_search" class="form-label">Nama Pegawai</label>
                                <input type="text" class="form-control" id="employee_search" autocomplete="off"
                                    placeholder="Cari nama pegawai...">
                                <input type="hidden" name="employee_id" id="employee_id">
                                <div id="employeeList" class="list-group position-absolute w-100" style="z-index:10;"></div>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status Kehadiran</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="hadir">Hadir</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="cuti">Cuti</option>
                                    <option value="tidak_hadir">Tidak Hadir</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="foto_bukti" class="form-label">Foto Bukti Kehadiran</label>
                                <input type="file" class="form-control" id="foto_bukti" name="foto_bukti"
                                    accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Simpan Kehadiran</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            let timeout = null;
            document.getElementById('employee_search').addEventListener('input', function() {
                clearTimeout(timeout);
                let query = this.value;
                if (query.length < 2) {
                    document.getElementById('employeeList').innerHTML = '';
                    return;
                }
                timeout = setTimeout(function() {
                    fetch('/api/employees/search?q=' + encodeURIComponent(query))
                        .then(res => res.json())
                        .then(data => {
                            let list = '';
                            data.forEach(emp => {
                                list +=
                                    `<button type="button" class="list-group-item list-group-item-action" data-id="${emp.id}" data-name="${emp.name}">${emp.name} (${emp.nomor_pegawai})</button>`;
                            });
                            document.getElementById('employeeList').innerHTML = list;
                        });
                }, 300);
            });

            document.getElementById('employeeList').addEventListener('click', function(e) {
                if (e.target && e.target.matches('button[data-id]')) {
                    document.getElementById('employee_search').value = e.target.getAttribute('data-name');
                    document.getElementById('employee_id').value = e.target.getAttribute('data-id');
                    this.innerHTML = '';
                }
            });

            document.getElementById('attendanceForm').addEventListener('submit', function(e) {
                if (!document.getElementById('employee_id').value) {
                    alert('Silakan pilih pegawai dari daftar.');
                    e.preventDefault();
                }
            });
        </script>
    @endpush
@endsection
