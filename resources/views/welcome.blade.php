<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In - CV Rangga Soly</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-family: 'Inter', sans-serif;
            --brand-primary: #0d6efd;
            --brand-light: #f1f5f9;
            --text-primary: #1e293b;
            --text-muted: #64748b;
        }

        body,
        html {
            height: 100%;
            margin: 0;
            font-family: var(--font-family);
            background-color: var(--brand-light);
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        /* * =================================
        * Sisi Kiri (Branding) - DIPERBARUI
        * =================================
        */
        .login-branding {
            flex: 1;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            text-align: center;

            /* [BARU] Menggunakan Gambar Background */
            background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* [BARU] Overlay transparan di atas gambar */
        .login-branding::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.85), rgba(15, 23, 42, 0.9));
            z-index: 1;
        }

        /* [BARU] Konten branding harus di atas overlay */
        .login-branding .branding-content {
            position: relative;
            z-index: 2;
        }

        .login-branding .logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .login-branding .subtitle {
            font-size: 1.15rem;
            font-weight: 400;
            opacity: 0.9;
        }

        /* * =================================
        * Sisi Kanan (Form) - DIPERBARUI
        * =================================
        */
        .login-form-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            background-color: #ffffff;
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .login-form-container h2 {
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }

        .login-form-container .text-muted {
            margin-bottom: 2.5rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control-icon {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            z-index: 3;
        }

        /* [BARU] Ikon untuk show/hide password */
        .form-control-icon-right {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            z-index: 3;
            cursor: pointer;
        }

        .form-control {
            /* [DIPERBARUI] Padding di kanan untuk ikon show/hide */
            padding: 0.9rem 3rem 0.9rem 3rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            background-color: var(--brand-light);
            font-size: 0.95rem;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        .btn-primary {
            background-color: var(--brand-primary);
            border: none;
            padding: 0.9rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        @media (max-width: 992px) {
            .login-branding {
                display: none;
            }

            .login-form-wrapper {
                background-color: var(--brand-light);
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-branding">
            <div class="branding-content">
                <div class="logo">
                    <i class="bi bi-building-fill"></i>
                    <span>CV Rangga Soly</span>
                </div>
                <p class="subtitle">Sistem Informasi Penggajian Karyawan</p>
            </div>
        </div>

        <div class="login-form-wrapper">
            <div class="login-form-container">
                <h2>Selamat Datang</h2>
                <p class="text-muted">Silakan masuk untuk melanjutkan ke dashboard Anda.</p>

                <form method="POST" action="/">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger" style="font-size: 0.9rem;">
                            {{ $errors->first('email') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <i class="bi bi-envelope-fill form-control-icon"></i>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Email address" required autofocus value="{{ old('email') }}">
                    </div>

                    <div class="form-group">
                        <i class="bi bi-lock-fill form-control-icon"></i>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password" required>
                        <i class="bi bi-eye-fill form-control-icon-right" id="togglePassword"></i>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember" style="font-size: 0.9rem;">Ingat Saya</label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            togglePassword.addEventListener('click', function(e) {
                // Toggle tipe input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle ikon
                this.classList.toggle('bi-eye-fill');
                this.classList.toggle('bi-eye-slash-fill');
            });
        });
    </script>

</body>

</html>
