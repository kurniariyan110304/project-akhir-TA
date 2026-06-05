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

        html {
            scroll-behavior: smooth;
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

        .mini-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            max-width: 650px;
        }

        .mini-stat {
            padding: 18px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid rgba(148, 163, 184, 0.22);
            backdrop-filter: blur(14px);
        }

        .mini-stat strong {
            display: block;
            font-size: 26px;
            color: #0f172a;
            margin-bottom: 4px;
        }

        .mini-stat span {
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
            flex-shrink: 0;
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
            flex-shrink: 0;
        }

        .dashboard-section {
            padding: 10px 8% 70px;
        }

        .section-heading {
            text-align: center;
            max-width: 760px;
            margin: 0 auto 34px;
        }

        .section-heading span {
            display: inline-flex;
            padding: 8px 14px;
            border-radius: 999px;
            background: #ffedd5;
            color: #c2410c;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 14px;
        }

        .section-heading h2 {
            font-size: clamp(30px, 4vw, 46px);
            letter-spacing: -1.4px;
            color: #111827;
            margin-bottom: 12px;
        }

        .section-heading p {
            color: #64748b;
            font-size: 16px;
            line-height: 1.7;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .dashboard-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 22px;
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(148, 163, 184, 0.22);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.06);
            backdrop-filter: blur(14px);
            transition: 0.2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 24px 55px rgba(15, 23, 42, 0.1);
            border-color: rgba(249, 115, 22, 0.35);
        }

        .dashboard-icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: #fff7ed;
            font-size: 25px;
            flex-shrink: 0;
        }

        .dashboard-card strong {
            display: block;
            font-size: 30px;
            line-height: 1;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .dashboard-card span {
            display: block;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
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
            position: relative;
            margin-top: 30px;
            padding: 0 8% 34px;
            color: #475569;
        }

        .footer::before {
            content: "";
            position: absolute;
            left: 8%;
            right: 8%;
            top: -18px;
            height: 1px;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(249, 115, 22, 0.45),
                rgba(59, 130, 246, 0.25),
                transparent
            );
        }

        .footer-container {
            position: relative;
            display: grid;
            grid-template-columns: 1fr 1.25fr;
            gap: 42px;
            padding: 38px;
            border-radius: 36px;
            overflow: hidden;
            background:
                radial-gradient(circle at 0% 0%, rgba(249, 115, 22, 0.22), transparent 34%),
                radial-gradient(circle at 100% 100%, rgba(59, 130, 246, 0.14), transparent 32%),
                linear-gradient(135deg, rgba(255, 255, 255, 0.92), rgba(255, 247, 237, 0.72));
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow:
                0 28px 75px rgba(15, 23, 42, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(18px);
        }

        .footer-container::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -80px;
            top: -90px;
            border-radius: 999px;
            background: rgba(249, 115, 22, 0.10);
            filter: blur(2px);
        }

        .footer-brand {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: flex-start;
            gap: 18px;
        }

        .footer-logo {
            width: 62px;
            height: 62px;
            border-radius: 22px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #ea580c, #fb923c);
            color: #ffffff;
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 0.5px;
            box-shadow:
                0 18px 38px rgba(234, 88, 12, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.35);
            flex-shrink: 0;
        }

        .footer-brand h3 {
            font-size: 25px;
            color: #0f172a;
            margin-bottom: 10px;
            letter-spacing: -0.7px;
        }

        .footer-brand p {
            max-width: 620px;
            color: #64748b;
            line-height: 1.85;
            font-size: 15px;
        }

        .footer-info {
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .footer-info > div {
            padding: 20px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.58);
            border: 1px solid rgba(226, 232, 240, 0.85);
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.04);
            transition: 0.2s ease;
        }

        .footer-info > div:hover {
            transform: translateY(-3px);
            border-color: rgba(249, 115, 22, 0.38);
            box-shadow: 0 22px 46px rgba(15, 23, 42, 0.08);
        }

        .footer-info h4 {
            position: relative;
            font-size: 15px;
            color: #0f172a;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .footer-info h4::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 34px;
            height: 3px;
            border-radius: 999px;
            background: linear-gradient(90deg, #ea580c, #fb923c);
        }

        .footer-info p {
            position: relative;
            color: #64748b;
            font-size: 14px;
            margin-bottom: 11px;
            line-height: 1.5;
            padding-left: 18px;
        }

        .footer-info p::before {
            content: "";
            position: absolute;
            left: 0;
            top: 8px;
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: #fb923c;
            box-shadow: 0 0 0 4px rgba(251, 146, 60, 0.14);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            padding: 18px 6px 0;
            color: #64748b;
            font-size: 14px;
        }

        .footer-bottom span:first-child {
            font-weight: 700;
            color: #475569;
        }

        @media (max-width: 980px) {
            .hero {
                grid-template-columns: 1fr;
                padding-top: 20px;
            }

            .dashboard-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .features {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .footer-container {
                grid-template-columns: 1fr;
            }

            .footer-info {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
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

            .mini-stats {
                grid-template-columns: 1fr;
            }

            .dashboard-section {
                padding: 0 20px 50px;
            }

            .dashboard-grid {
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

            .role-link {
                align-items: flex-start;
            }

            .footer {
                padding: 0 20px 26px;
            }

            .footer::before {
                left: 20px;
                right: 20px;
            }

            .footer-container {
                padding: 24px;
                border-radius: 28px;
            }

            .footer-brand {
                flex-direction: column;
            }

            .footer-logo {
                width: 56px;
                height: 56px;
                border-radius: 20px;
            }

            .footer-brand h3 {
                font-size: 22px;
            }

            .footer-info {
                grid-template-columns: 1fr;
            }

            .footer-info > div {
                padding: 18px;
                border-radius: 22px;
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

                    <a href="#dashboard" class="btn btn-secondary">
                        Lihat Dashboard
                    </a>
                </div>

                <div class="mini-stats">
                    <div class="mini-stat">
                        <strong>4</strong>
                        <span>Role pengguna</span>
                    </div>
                    <div class="mini-stat">
                        <strong>{{ $stats['kelas'] ?? 0 }}</strong>
                        <span>Total kelas</span>
                    </div>
                    <div class="mini-stat">
                        <strong>{{ $stats['project_mahasiswa'] ?? 0 }}</strong>
                        <span>Total project</span>
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

        <section class="dashboard-section" id="dashboard">
            <div class="section-heading">
                <span>Dashboard Sistem</span>
                <h2>Ringkasan Data Keseluruhan</h2>
                <p>
                    Informasi jumlah data yang tersedia pada Sistem Informasi Penilaian Proyek Akhir Mata Kuliah.
                </p>
            </div>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="dashboard-icon">👨‍🏫</div>
                    <div>
                        <strong>{{ $stats['dosen'] ?? 0 }}</strong>
                        <span>Total Dosen</span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-icon">🎓</div>
                    <div>
                        <strong>{{ $stats['mahasiswa'] ?? 0 }}</strong>
                        <span>Total Mahasiswa</span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-icon">🏫</div>
                    <div>
                        <strong>{{ $stats['kelas'] ?? 0 }}</strong>
                        <span>Total Kelas</span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-icon">📚</div>
                    <div>
                        <strong>{{ $stats['matakuliah'] ?? 0 }}</strong>
                        <span>Total Mata Kuliah</span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-icon">📝</div>
                    <div>
                        <strong>{{ $stats['tugas'] ?? 0 }}</strong>
                        <span>Total Tugas Project</span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-icon">🚀</div>
                    <div>
                        <strong>{{ $stats['project_mahasiswa'] ?? 0 }}</strong>
                        <span>Total Project Mahasiswa</span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-icon">👥</div>
                    <div>
                        <strong>{{ $stats['kelompok_project'] ?? 0 }}</strong>
                        <span>Total Kelompok Project</span>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="dashboard-icon">🤝</div>
                    <div>
                        <strong>{{ $stats['asdos'] ?? 0 }}</strong>
                        <span>Total Asdos</span>
                    </div>
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
            <div class="footer-container">
                <div class="footer-brand">
                    <div class="footer-logo">SIP</div>

                    <div>
                        <h3>SIP Proyek Akhir MK</h3>
                        <p>
                            Sistem Informasi Penilaian Proyek Akhir Mata Kuliah dirancang untuk membantu
                            proses pengelolaan tugas proyek, pembentukan kelompok, monitoring pengerjaan,
                            serta penilaian akhir secara lebih efektif dan terstruktur.
                        </p>
                    </div>
                </div>

                <div class="footer-info">
                    <div>
                        <h4>Fokus Sistem</h4>
                        <p>Manajemen kelas</p>
                        <p>Pengelolaan tugas project</p>
                        <p>Kelompok mahasiswa</p>
                        <p>Penilaian proyek akhir</p>
                    </div>

                    <div>
                        <h4>Pengguna Sistem</h4>
                        <p>Admin akademik</p>
                        <p>Dosen pengampu</p>
                        <p>Mahasiswa</p>
                        <p>Asisten dosen</p>
                    </div>

                    <div>
                        <h4>Keunggulan</h4>
                        <p>Data terpusat</p>
                        <p>Akses sesuai role</p>
                        <p>Monitoring lebih mudah</p>
                        <p>Penilaian lebih rapi</p>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>© {{ date('Y') }} SIP Proyek Akhir MK.</span>
                <span>Sistem Informasi Penilaian Proyek Akhir Mata Kuliah.</span>
            </div>
        </footer>
    </main>
</body>

</html>