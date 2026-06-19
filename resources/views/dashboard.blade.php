<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SouthMrt</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --primary-accent: #6366f1;
            --secondary-accent: #a855f7;
            --text-light: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-light);
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 2rem;
        }

        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(45deg, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.03em;
        }

        .dashboard-container {
            margin-top: 4rem;
            margin-bottom: 4rem;
            flex-grow: 1;
            display: flex;
            align-items: center;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 30px 60px rgba(99, 102, 241, 0.15);
        }

        .user-avatar {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--primary-accent) 0%, var(--secondary-accent) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            font-weight: 700;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            margin: 0 auto 1.5rem auto;
        }

        .welcome-title {
            font-weight: 800;
            font-size: 2.2rem;
            line-height: 1.2;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #ffffff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .role-badge {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-pill {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.2rem;
            transition: all 0.3s ease;
        }

        .info-pill:hover {
            background: rgba(255, 255, 255, 0.06);
            transform: translateY(-2px);
        }

        .info-title {
            font-size: 0.85rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.3rem;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-light);
        }

        .btn-logout {
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            padding: 0.8rem 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
        }

        .btn-logout:hover {
            background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
        }

        .footer {
            background: rgba(15, 23, 42, 0.8);
            border-top: 1px solid var(--glass-border);
            padding: 1.5rem 0;
            font-size: 0.9rem;
            color: var(--text-muted);
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container-fluid">
            <span class="navbar-brand navbar-brand-custom">
                <i class="bi bi-shield-check me-2"></i>SouthMrt
            </span>
            <div class="ms-auto d-flex align-items-center gap-2"> {{-- DISESUAIKAN: Menambahkan d-flex dan gap agar tombol POS dan Keluar berjejer rapi --}}
                
                {{-- TOMBOL BARU: Akses cepat menuju halaman Dashboard Utama --}}
                <a href="{{ route('tebet.pos.index') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="bi bi-cart-fill me-1"></i> Buka POS Kasir Tebet
                    </a>

                <a href="{{ route('logout') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container dashboard-container">
        <div class="row justify-content-center w-100 g-0">
            <div class="col-md-8 col-lg-6">
                
                <div class="glass-card text-center">
                    
                    <!-- Avatar with initials -->
                    <div class="user-avatar">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>

                    <!-- Welcome details -->
                    <h1 class="welcome-title">Selamat Datang Kembali</h1>
                    <h3 class="fs-4 text-light-50 mb-3">{{ $user->name }}</h3>
                    
                    <div class="mb-4">
                        <span class="role-badge">
                            <i class="bi bi-person-badge me-1"></i> {{ $user->role }}
                        </span>
                    </div>

                    <p class="text-muted mb-4">
                        Anda telah berhasil masuk ke dalam sistem portal terpusat **SouthMrt**. Berikut adalah detail akun Anda yang terdaftar pada sistem kami.
                    </p>

                    <!-- Account Details Grid -->
                    <div class="row g-3 text-start mb-4">
                        <div class="col-12">
                            <div class="info-pill d-flex align-items-center gap-3">
                                <div class="fs-3 text-primary"><i class="bi bi-envelope-at"></i></div>
                                <div>
                                    <div class="info-title">Email Pengguna</div>
                                    <div class="info-value">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-pill d-flex align-items-center gap-3">
                                <div class="fs-3 text-success"><i class="bi bi-clock-history"></i></div>
                                <div>
                                    <div class="info-title">Terdaftar Pada</div>
                                    <div class="info-value">{{ $user->created_at ? $user->created_at->format('d M Y') : 'Baru' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-pill d-flex align-items-center gap-3">
                                <div class="fs-3 text-warning"><i class="bi bi-hdd-network"></i></div>
                                <div>
                                    <div class="info-title">Database</div>
                                    <div class="info-value">southmrt_mysql</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-logout w-100">
                            <i class="bi bi-box-arrow-right me-2"></i> Log Out dari Sistem
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <span>&copy; 2026 SouthMrt. Hak Cipta Dilindungi.</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
