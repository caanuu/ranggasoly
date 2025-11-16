@extends('layout')

@section('title', 'Pengaturan Akun')

@push('styles')
    <style>
        /* [BARU] Style untuk tab navigasi agar lebih modern */
        .profile-nav .nav-link {
            color: var(--text-muted);
            font-weight: 500;
        }

        .profile-nav .nav-link.active {
            color: var(--brand-primary);
            background-color: var(--brand-light);
            border-bottom: 2px solid var(--brand-primary);
        }

        .profile-nav .nav-link:hover {
            color: var(--text-primary);
        }

        /* [BARU] Style untuk avatar uploader */
        .avatar-uploader {
            position: relative;
            display: inline-block;
        }

        .avatar-uploader .avatar-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid var(--border-color);
        }

        .avatar-uploader .avatar-label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--brand-primary);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
            transition: all 0.2s;
        }

        .avatar-uploader .avatar-label:hover {
            transform: scale(1.1);
        }

        .avatar-uploader input[type="file"] {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-white border-0 pb-0 pt-3 profile-nav">
                    <ul class="nav nav-tabs" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#tab-profile" type="button" role="tab" aria-controls="tab-profile"
                                aria-selected="true">
                                <i class="bi bi-person-fill me-1"></i> Informasi Profil
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#tab-password"
                                type="button" role="tab" aria-controls="tab-password" aria-selected="false">
                                <i class="bi bi-key-fill me-1"></i> Ubah Password
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4 p-md-5">
                    <div class="tab-content" id="profileTabContent">

                        <div class="tab-pane fade show active" id="tab-profile" role="tabpanel"
                            aria-labelledby="profile-tab">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-lg-4 text-center">
                                        <div class="avatar-uploader mb-4">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0f172a&color=cbd5e1' }}"
                                                alt="Avatar" class="avatar-image">
                                            <label for="avatar" class="avatar-label" title="Ganti Foto">
                                                <i class="bi bi-camera-fill"></i>
                                            </label>
                                            <input type="file" id="avatar" name="avatar" accept="image/*"
                                                onchange="previewAvatar(event)">
                                        </div>
                                        <small class="text-muted d-block">Maks. 2MB (JPG, PNG)</small>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name', $user->name) }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ old('email', $user->email) }}" required>
                                        </div>
                                        <div class="mt-4">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="bi bi-save-fill me-1"></i> Simpan Perubahan Profil
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="tab-password" role="tabpanel" aria-labelledby="password-tab">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <h5 class="fw-bold mb-3 text-center">Ubah Password</h5>
                                    <form action="{{ route('profile.password.update') }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Password Saat Ini</LabeL>
                                            <input type="password" class="form-control" id="current_password"
                                                name="current_password" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password Baru</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Password
                                                Baru</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" required>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100 mt-3">
                                            <i class="bi bi-shield-lock-fill me-1"></i> Ganti Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // [BARU] Script untuk preview avatar
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.querySelector('.avatar-image');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // [BARU] Script untuk mengingat tab yang aktif
        document.addEventListener('DOMContentLoaded', function() {
            var triggerTabList = [].slice.call(document.querySelectorAll('#profileTab button'));
            triggerTabList.forEach(function(triggerEl) {
                var tabTrigger = new bootstrap.Tab(triggerEl);

                triggerEl.addEventListener('click', function(event) {
                    event.preventDefault();
                    tabTrigger.show();
                    localStorage.setItem('activeProfileTab', triggerEl.getAttribute(
                        'data-bs-target'));
                });
            });

            var activeTab = localStorage.getItem('activeProfileTab');
            if (activeTab) {
                var activeTabEl = document.querySelector('button[data-bs-target="' + activeTab + '"]');
                if (activeTabEl) {
                    new bootstrap.Tab(activeTabEl).show();
                }
            }
        });
    </script>
@endpush
