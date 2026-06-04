<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIP Proyek Akhir MK</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(251, 146, 60, 0.18), transparent 35%),
                radial-gradient(circle at top right, rgba(59, 130, 246, 0.16), transparent 32%),
                linear-gradient(135deg, #fff7ed 0%, #ffffff 45%, #f8fafc 100%);
            color: #0f172a;
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            width: 100%;
            padding: 24px 8%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 20px;
            color: #0f172a;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #f97316, #fb923c);
            color: #fff;
            font-weight: 900;
            box-shadow: 0 12px 30px rgba(249, 115, 22, 0.28);
        }

        .nav-badge {
            padding: 10px 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(148, 163, 184, 0.25);
            font-size: 14px;
            color: #475569;
            backdrop-filter: blur(14px);
        }

        .hero {
            flex: 1;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            gap: 54px;
            align-items: center;
            padding: 40px 8% 70px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            background: #ffedd5;
            color: #c2410c;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-size: clamp(42px, 5vw, 72px);
            line-height: 1.02;
            letter-spacing: -2.5px;
            color: #111827;
            margin-bottom: 24px;
        }

        .hero h1 span {
            color: #ea580c;
        }

        .hero p {
            max-width: 680px;
            font-size: 18px;
            line-height: 1.8;
            color: #475569;
            margin-bottom: 32px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 34px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 20px;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 800;
            transition: 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: #ea580c;
            color: #fff;
            box-shadow: 0 18px 35px rgba(234, 88, 12, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            background: #c2410c;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.78);
            color: #0f172a;
            border-color: rgba(148, 163, 184, 0.28);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            border-color: #fb923c;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            max-width: 650px;
        }

        .stat {
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(148, 163, 184, 0.22);
            backdrop-filter: blur(14px);
        }

        .stat strong {
            display: block;
            font-size: 26px;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .stat span {
            font-size: 13px;
            color: #64748b;
        }

        .login-card {
            position: relative;
            padding: 30px;
            border-radius: 34px;
            background: rgba(255, 255, 255, 0.82);
            border: 1px solid rgba(148, 163, 184, 0.24);
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(18px);
        }

        .login-card::before {
            content: "";
            position: absolute;
            inset: -1px;
            border-radius: 34px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.4), rgba(59, 130, 246, 0.28));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .login-card-header {
            margin-bottom: 24px;
        }

        .login-card-header small {
            display: inline-block;
            color: #ea580c;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .login-card-header h2 {
            font-size: 30px;
            margin-bottom: 10px;
            letter-spacing: -0.8px;
        }

        .login-card-header p {
            color: #64748b;
            line-height: 1.6;
        }

        .login-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .role-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 18px;
            border-radius: 22px;
            text-decoration: none;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            transition: 0.2s ease;
        }

        .role-link:hover {
            transform: translateY(-2px);
            border-color: #fb923c;
            box-shadow: 0 16px 35px rgba(15, 23, 42, 0.08);
        }

        .role-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            font-size: 23px;
            background: #fff7ed;
        }

        .role-text strong {
            display: block;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .role-text span {
            font-size: 13px;
            color: #64748b;
        }

        .arrow {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: #f8fafc;
            color: #ea580c;
            font-weight: 900;
        }

        .features {
            padding: 0 8% 70px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .feature {
            padding: 22px;
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .feature h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .feature p {
            font-size: 14px;
            line-height: 1.6;
            color: #64748b;
        }

        .footer {
            padding: 22px 8%;
            text-align: center;
            color: #64748b;
            font-size: 14px;
            border-top: 1px solid rgba(148, 163, 184, 0.18);
        }

        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
                padding-top: 20px;
            }

            .features {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .navbar {
                padding: 20px;
            }

            .nav-badge {
                display: none;
            }

            .hero {
                padding: 24px 20px 50px;
                gap: 34px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .features {
                grid-template-columns: 1fr;
                padding: 0 20px 50px;
            }

            .login-card {
                padding: 22px;
                border-radius: 26px;
            }

            .hero h1 {
                letter-spacing: -1.4px;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <nav class="navbar">
            <div class="brand">
                <div class="brand-logo">SIP</div>
                <div>SIP Proyek Akhir MK</div>
            </div>

            <div class="nav-badge">
                Sistem Informasi Penilaian Proyek Akhir Mata Kuliah
            </div>
        </nav>

        <section class="hero">
            <div>
                <div class="eyebrow">
                    📌 Platform Akademik Terintegrasi
                </div>

                <h1>
                    Kelola proyek akhir mata kuliah dengan <span>lebih rapi</span> dan terukur.
                </h1>

                <p>
                    Sistem ini membantu admin, dosen, mahasiswa, dan asdos dalam mengelola kelas,
                    mata kuliah, tugas project, kelompok, anggota, pengumpulan link, serta penilaian
                    akhir proyek mata kuliah.
                </p>

                <div class="hero-actions">
                    <a href="{{ url('/mahasiswa/login') }}" class="btn btn-primary">
                        Login Mahasiswa
                    </a>

                    <a href="#pilih-login" class="btn btn-secondary">
                        Pilih Role Login
                    </a>
                </div>

                <div class="stats">
                    <div class="stat">
                        <strong>4</strong>
                        <span>Role pengguna</span>
                    </div>
                    <div class="stat">
                        <strong>360°</strong>
                        <span>Monitoring project</span>
                    </div>
                    <div class="stat">
                        <strong>Real-time</strong>
                        <span>Data kelas & nilai</span>
                    </div>
                </div>
            </div>

            <div class="login-card" id="pilih-login">
                <div class="login-card-header">
                    <small>Masuk ke Sistem</small>
                    <h2>Pilih jenis akun</h2>
                    <p>
                        Silakan masuk sesuai hak akses masing-masing pengguna.
                    </p>
                </div>

                <div class="login-grid">
                    <a href="{{ url('/admin/login') }}" class="role-link">
                        <div class="role-left">
                            <div class="icon">🛡️</div>
                            <div class="role-text">
                                <strong>Login Admin</strong>
                                <span>Kelola data master, user, kelas, dosen, mahasiswa, dan tugas.</span>
                            </div>
                        </div>
                        <div class="arrow">→</div>
                    </a>

                    <a href="{{ url('/dosen/login') }}" class="role-link">
                        <div class="role-left">
                            <div class="icon">👨‍🏫</div>
                            <div class="role-text">
                                <strong>Login Dosen</strong>
                                <span>Kelola kelas, mata kuliah, tugas project, dan penilaian.</span>
                            </div>
                        </div>
                        <div class="arrow">→</div>
                    </a>

                    <a href="{{ url('/mahasiswa/login') }}" class="role-link">
                        <div class="role-left">
                            <div class="icon">🎓</div>
                            <div class="role-text">
                                <strong>Login Mahasiswa</strong>
                                <span>Lihat kelas, tugas, kelompok, project, dan nilai akhir.</span>
                            </div>
                        </div>
                        <div class="arrow">→</div>
                    </a>

                    <a href="{{ url('/asdos/login') }}" class="role-link">
                        <div class="role-left">
                            <div class="icon">🤝</div>
                            <div class="role-text">
                                <strong>Login Asdos</strong>
                                <span>Bantu monitoring project, kelompok, dan proses penilaian.</span>
                            </div>
                        </div>
                        <div class="arrow">→</div>
                    </a>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="feature">
                <h3>Manajemen Kelas</h3>
                <p>Dosen dan mahasiswa dapat melihat kelas sesuai data yang terhubung di sistem.</p>
            </div>

            <div class="feature">
                <h3>Tugas Project</h3>
                <p>Setiap mata kuliah dapat memiliki tugas project individu maupun kelompok.</p>
            </div>

            <div class="feature">
                <h3>Kelompok Mahasiswa</h3>
                <p>Mahasiswa dapat membentuk kelompok dan melihat anggota yang tergabung.</p>
            </div>

            <div class="feature">
                <h3>Penilaian Akhir</h3>
                <p>Nilai project dapat dikelola dan ditampilkan sesuai hak akses pengguna.</p>
            </div>
        </section>

        <footer class="footer">
            © {{ date('Y') }} SIP Proyek Akhir MK. Sistem Informasi Penilaian Proyek Akhir Mata Kuliah.
        </footer>
    </main>
</body>
</html>