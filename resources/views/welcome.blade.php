@php
    $menu = ['tentang' => 'Tentang', 'fitur' => 'Fitur', 'cara-pakai' => 'Cara Pakai', 'faq' => 'FAQ'];

    $fitur = [
        ['ikon' => 'fa-layer-group', 'judul' => 'Bank Soal Terpusat', 'teks' => 'Dosen menyusun paket soal pilihan ganda atau esai per mata kuliah, lengkap dengan kunci jawaban dan urutan soal yang bisa diatur.'],
        ['ikon' => 'fa-calendar-check', 'judul' => 'Ujian Terjadwal', 'teks' => 'Ujian hanya bisa dikerjakan dalam jadwal yang ditentukan dosen, dan hanya oleh mahasiswa yang mengambil mata kuliahnya.'],
        ['ikon' => 'fa-stopwatch', 'judul' => 'Timer Anti-Curang', 'teks' => 'Sisa waktu dihitung di server. Me-refresh halaman tidak mengulang waktu, dan ujian terkumpul otomatis saat waktu habis.'],
        ['ikon' => 'fa-floppy-disk', 'judul' => 'Tersimpan Otomatis', 'teks' => 'Setiap jawaban langsung tersimpan. Jika tab tertutup atau koneksi terputus, ujian bisa dilanjutkan selama waktu masih ada.'],
        ['ikon' => 'fa-check-double', 'judul' => 'Penilaian Cepat', 'teks' => 'Pilihan ganda dinilai otomatis saat ujian dikumpulkan. Jawaban esai dinilai dosen lengkap dengan catatan.'],
        ['ikon' => 'fa-mobile-screen-button', 'judul' => 'Nyaman di Semua Perangkat', 'teks' => 'Tampilan menyesuaikan layar HP, tablet, maupun laptop sehingga ujian bisa dikerjakan di mana saja.'],
    ];

    $peran = [
        ['ikon' => 'fa-user-tie', 'judul' => 'Kaprodi', 'poin' => [
            'Mengelola data program studi, mata kuliah, dosen, dan mahasiswa',
            'Mengatur tahun akademik serta jenis & tipe soal yang diizinkan',
            'Memantau seluruh bank soal dan ujian',
        ]],
        ['ikon' => 'fa-chalkboard-user', 'judul' => 'Dosen', 'poin' => [
            'Menyusun bank soal untuk mata kuliah yang diampu',
            'Menjadwalkan ujian dan menentukan durasinya',
            'Melihat hasil mahasiswa dan menilai jawaban esai',
        ]],
        ['ikon' => 'fa-user-graduate', 'judul' => 'Mahasiswa', 'poin' => [
            'Melihat ujian yang sedang dibuka untuk mata kuliahnya',
            'Mengerjakan ujian dengan timer dan navigasi nomor soal',
            'Melihat nilai, jawaban, dan kunci di riwayat ujian',
        ]],
    ];

    $langkah = [
        ['peran' => 'Mahasiswa', 'ikon' => 'fa-user-graduate', 'daftar' => [
            ['Masuk ke portal', 'Klik "Masuk Portal", lalu login dengan email dan kata sandi yang diberikan Kaprodi.'],
            ['Buka "Ujian Tersedia"', 'Ujian yang sedang berlangsung untuk mata kuliah Anda tampil di sini, dikelompokkan per mata kuliah.'],
            ['Kerjakan sebelum waktu habis', 'Klik "Mulai Ujian", jawab soal, pindah soal lewat panel nomor, lalu klik "Kumpulkan".'],
            ['Lihat hasil di "Riwayat Ujian"', 'Nilai pilihan ganda langsung tampil. Kunci jawaban terbuka setelah jadwal ujian berakhir.'],
        ]],
        ['peran' => 'Dosen', 'ikon' => 'fa-chalkboard-user', 'daftar' => [
            ['Buat bank soal', 'Di menu "Bank Soal", pilih mata kuliah dan tipe soal, lalu tambahkan butir soal beserta kuncinya.'],
            ['Jadwalkan ujian', 'Di menu "Buat Ujian", pilih paket soal, atur waktu mulai, waktu selesai, dan durasi pengerjaan.'],
            ['Pantau peserta', 'Buka detail ujian untuk melihat siapa saja yang sudah mengerjakan beserta nilainya.'],
            ['Nilai jawaban esai', 'Klik "Nilai Esai", isi nilai 0–100 dan catatan. Nilai akhir mahasiswa dihitung otomatis.'],
        ]],
    ];

    $faq = [
        ['Bagaimana cara mendapatkan akun?', 'Akun dibuat oleh Kaprodi, tidak ada pendaftaran mandiri. Hubungi Kaprodi program studi Anda jika belum memiliki akun.'],
        ['Saya lupa kata sandi, apa yang harus dilakukan?', 'Klik "Lupa kata sandi?" di halaman masuk, lalu ikuti tautan reset yang dikirim ke email Anda.'],
        ['Kenapa ujian saya tidak muncul di "Ujian Tersedia"?', 'Ujian hanya tampil selama jadwalnya berlangsung dan hanya untuk mahasiswa yang terdaftar di mata kuliah tersebut. Jika seharusnya muncul, hubungi dosen pengampu.'],
        ['Apa yang terjadi jika browser tertutup atau koneksi terputus?', 'Jawaban yang sudah dipilih tetap tersimpan. Buka kembali "Ujian Tersedia" dan klik "Lanjutkan". Ingat, waktu tetap berjalan selama Anda keluar.'],
        ['Apakah waktu ujian bisa diulang dengan me-refresh halaman?', 'Tidak. Sisa waktu dihitung sejak Anda pertama kali mulai, dan tidak akan melewati jadwal selesai ujian.'],
        ['Bolehkah mengerjakan ujian lebih dari sekali?', 'Tidak. Setiap ujian hanya bisa dikumpulkan satu kali, dan jawaban tidak bisa diubah setelah dikumpulkan.'],
        ['Kapan nilai saya keluar?', 'Soal pilihan ganda dinilai otomatis saat ujian dikumpulkan. Jika ada soal esai, nilai akhir muncul setelah dosen selesai menilai.'],
        ['Kapan kunci jawaban bisa dilihat?', 'Kunci jawaban tampil di "Riwayat Ujian" setelah jadwal ujian berakhir, agar tidak tersebar ke mahasiswa yang belum mengerjakan.'],
        ['Apakah bisa mengerjakan ujian lewat HP?', 'Bisa. Tampilan ujian sudah disesuaikan untuk HP, tablet, dan laptop. Pastikan koneksi internet Anda stabil.'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Akademik & Kuiz Digital - UNSADA</title>
    <meta name="description" content="Kuiz Digital adalah platform bank soal dan ujian online Universitas Darma Persada untuk Kaprodi, Dosen, dan Mahasiswa.">

    <link rel="icon" href="/favicon.ico?v=2" sizes="any">
    <link rel="icon" href="/favicon.png?v=2" type="image/png">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png?v=2">
    <link rel="stylesheet" href="{{ asset('fonts/filament/filament/inter/index.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        :root {
            --unsada-navy: #002d57;
            --unsada-gold: #ffcc00;
        }

        /* Perbaikan Koneksi Gambar Hero */
        .hero-section {
            background: linear-gradient(rgba(0, 45, 87, 0.85), rgba(0, 45, 87, 0.85)),
                        url("{{ asset('images/jumbo.jpg') }}");
            background-size: cover;
            background-position: center;
        }

        /* Efek parallax hanya di desktop (background fixed bermasalah di iOS/Android) */
        @media (min-width: 1024px) and (hover: hover) {
            .hero-section { background-attachment: fixed; }
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
        }

        .text-gradient {
            background: linear-gradient(to right, #ffffff, var(--unsada-gold));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Hover Effect untuk Cards */
        .feature-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
            background-color: var(--unsada-gold);
            color: var(--unsada-navy);
        }

        summary::-webkit-details-marker { display: none; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900 leading-normal antialiased">

    {{-- ================= NAVIGASI ================= --}}
    <nav class="fixed w-full z-50 glass-nav border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 sm:h-20 items-center gap-4">

                <a href="#beranda" class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UNSADA" class="h-10 sm:h-12 w-auto drop-shadow-sm">
                    <div class="hidden sm:block border-l-2 border-slate-300 pl-4">
                        <p class="text-lg font-bold text-[#002d57] leading-none tracking-tight">KUIZ DIGITAL</p>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-[0.2em]">Univ. Darma Persada</p>
                    </div>
                </a>

                <div class="hidden lg:flex items-center gap-8">
                    @foreach ($menu as $id => $label)
                        <a href="#{{ $id }}" class="text-sm font-semibold text-slate-600 hover:text-[#002d57] transition-colors">{{ $label }}</a>
                    @endforeach
                </div>

                <div class="flex items-center gap-2">
                    <a href="/admin" class="inline-flex items-center bg-[#002d57] text-white px-4 sm:px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-900 transition-all duration-300 shadow-lg hover:shadow-blue-900/20 active:scale-95 group">
                        <span>@auth Ke Dashboard @else Masuk Portal @endauth</span>
                        <i class="fas fa-arrow-right-to-bracket ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>

                    {{-- Menu ponsel tanpa JavaScript --}}
                    <details class="relative lg:hidden">
                        <summary class="flex size-10 cursor-pointer list-none items-center justify-center rounded-xl text-[#002d57] hover:bg-slate-100" aria-label="Buka menu">
                            <i class="fas fa-bars text-lg"></i>
                        </summary>
                        <div class="absolute right-0 mt-3 w-52 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl">
                            @foreach ($menu as $id => $label)
                                <a href="#{{ $id }}" onclick="this.closest('details').removeAttribute('open')"
                                   class="block rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#002d57]">{{ $label }}</a>
                            @endforeach
                        </div>
                    </details>
                </div>

            </div>
        </div>
    </nav>

    {{-- ================= HERO ================= --}}
    <section id="beranda" class="hero-section min-h-screen flex items-center justify-center text-center px-4 relative pt-16 pb-48 md:pb-40">
        <div class="max-w-4xl pt-10 sm:pt-20">

            <h1 class="text-4xl sm:text-5xl md:text-7xl font-black text-white mb-6 sm:mb-8 leading-[1.1]">
                Elevasi Pendidikan <br>
                <span class="text-gradient">Melalui Inovasi Digital</span>
            </h1>

            <p class="text-base sm:text-lg md:text-xl text-slate-200 mb-10 sm:mb-12 max-w-2xl mx-auto font-light leading-relaxed">
                Platform evaluasi terpadu untuk mendukung mahasiswa <span class="font-bold border-b-2 border-yellow-400">UNSADA</span> dalam meraih capaian akademik terbaik di era teknologi.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center items-center">
                <a href="/admin" class="w-full sm:w-auto bg-[#ffcc00] text-[#002d57] px-10 sm:px-12 py-4 rounded-2xl font-black text-lg hover:scale-105 transition-all shadow-xl hover:shadow-yellow-500/30">
                    <i class="fas fa-rocket mr-2"></i> MULAI SEKARANG
                </a>
                <a href="#cara-pakai" class="w-full sm:w-auto bg-white/10 text-white border border-white/30 px-10 sm:px-12 py-4 rounded-2xl font-bold text-lg hover:bg-white/20 backdrop-blur-md transition-all">
                    Cara Menggunakan
                </a>
            </div>
        </div>
    </section>

    {{-- ================= STATISTIK (menumpuk di atas hero) ================= --}}
    <section class="px-4 relative z-10">
        <div class="max-w-5xl mx-auto -mt-32 md:-mt-24 grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($statistik as $s)
                <div class="bg-white rounded-3xl p-5 sm:p-7 shadow-xl border border-slate-100 text-center">
                    <div class="mx-auto mb-3 flex size-12 items-center justify-center rounded-2xl bg-blue-50 text-xl text-[#002d57]">
                        <i class="fas {{ $s['ikon'] }}"></i>
                    </div>
                    <p class="text-3xl sm:text-4xl font-black text-[#002d57] tabular-nums">{{ number_format($s['nilai'], 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs sm:text-sm font-semibold uppercase tracking-wider text-slate-500">{{ $s['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================= TENTANG ================= --}}
    <section id="tentang" class="scroll-mt-20 py-20 sm:py-28 px-4 overflow-x-clip">
        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-yellow-600">Tentang Platform</p>
                <h2 class="mt-3 text-3xl sm:text-4xl font-black text-[#002d57] leading-tight">Apa itu Kuiz Digital?</h2>
                <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed">
                    Kuiz Digital adalah sistem <strong class="text-slate-800">bank soal dan ujian online</strong> Universitas Darma Persada.
                    Dosen menyusun soal dan menjadwalkan ujian, mahasiswa mengerjakannya langsung dari browser,
                    dan nilai tersaji secara cepat dan transparan, tanpa kertas.
                </p>
                <ul class="mt-8 space-y-4">
                    @foreach (['Satu portal untuk Kaprodi, Dosen, dan Mahasiswa', 'Soal pilihan ganda dan esai dalam satu sistem', 'Hasil ujian dan riwayat nilai tersimpan rapi'] as $poin)
                        <li class="flex items-start gap-3">
                            <span class="mt-0.5 flex size-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs text-emerald-600"><i class="fas fa-check"></i></span>
                            <span class="text-slate-700 font-medium">{{ $poin }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Pratinjau tampilan halaman ujian --}}
            <div class="relative mx-auto w-full max-w-md" aria-hidden="true">
                <div class="absolute -inset-6 rounded-[3rem] bg-gradient-to-br from-[#002d57] to-blue-500 opacity-15 blur-2xl"></div>
                <div class="relative rounded-3xl border border-slate-200 bg-white p-5 sm:p-6 shadow-2xl">
                    <div class="flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600">Pemrograman Web</p>
                            <p class="truncate font-bold text-slate-900">Kuis Mingguan</p>
                        </div>
                        <div class="flex items-center gap-2 rounded-xl bg-slate-900 px-3 py-2 text-white">
                            <i class="fas fa-clock text-sm"></i>
                            <span class="font-mono font-bold tabular-nums">24:59</span>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-200"><div class="h-full w-2/5 rounded-full bg-emerald-500"></div></div>
                        <span class="text-[11px] text-slate-500">2/5 terjawab</span>
                    </div>
                    <span class="mt-5 inline-block rounded-full bg-blue-600 px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-white">Soal 3 dari 5</span>
                    <p class="mt-3 font-medium text-slate-800">Manakah yang termasuk bahasa pemrograman sisi server?</p>
                    <div class="mt-4 space-y-2.5">
                        @foreach (['A' => 'HTML', 'B' => 'CSS', 'C' => 'PHP', 'D' => 'Figma'] as $huruf => $opsi)
                            <div @class([
                                'flex items-center gap-3 rounded-xl border-2 p-2.5 text-sm',
                                'border-blue-600 bg-blue-50 text-blue-900 font-semibold' => $huruf === 'C',
                                'border-slate-200 text-slate-600' => $huruf !== 'C',
                            ])>
                                <span @class([
                                    'flex size-7 items-center justify-center rounded-lg text-xs font-bold',
                                    'bg-blue-600 text-white' => $huruf === 'C',
                                    'bg-slate-100 text-slate-500' => $huruf !== 'C',
                                ])>{{ $huruf }}</span>
                                {{ $opsi }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FITUR ================= --}}
    <section id="fitur" class="scroll-mt-20 py-20 sm:py-28 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="max-w-2xl mx-auto text-center">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-yellow-600">Fitur Unggulan</p>
                <h2 class="mt-3 text-3xl sm:text-4xl font-black text-[#002d57]">Semua yang dibutuhkan untuk ujian online</h2>
                <p class="mt-4 text-slate-500">Dirancang agar ujian berjalan adil, aman, dan mudah bagi dosen maupun mahasiswa.</p>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($fitur as $f)
                    <div class="feature-card bg-slate-50 p-7 sm:p-8 rounded-[2rem] border border-slate-100 hover:bg-white hover:shadow-xl transition-all duration-300 group">
                        <div class="icon-box w-14 h-14 bg-blue-100 text-[#002d57] rounded-2xl flex items-center justify-center text-2xl mb-6 transition-all duration-500">
                            <i class="fas {{ $f['ikon'] }}"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-[#002d57]">{{ $f['judul'] }}</h3>
                        <p class="text-slate-500 leading-relaxed">{{ $f['teks'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= PERAN PENGGUNA ================= --}}
    <section class="py-20 sm:py-28 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="max-w-2xl mx-auto text-center">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-yellow-600">Untuk Siapa?</p>
                <h2 class="mt-3 text-3xl sm:text-4xl font-black text-[#002d57]">Satu portal, tiga peran</h2>
                <p class="mt-4 text-slate-500">Menu yang tampil menyesuaikan peran akun Anda, sehingga setiap pengguna hanya melihat yang ia butuhkan.</p>
            </div>

            <div class="mt-14 grid md:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($peran as $p)
                    <div class="relative overflow-hidden rounded-[2rem] bg-[#002d57] p-7 sm:p-8 text-white shadow-xl">
                        <i class="fas {{ $p['ikon'] }} absolute -right-4 -top-4 text-8xl text-white/5"></i>
                        <div class="flex size-14 items-center justify-center rounded-2xl bg-[#ffcc00] text-2xl text-[#002d57]">
                            <i class="fas {{ $p['ikon'] }}"></i>
                        </div>
                        <h3 class="mt-6 text-2xl font-bold">{{ $p['judul'] }}</h3>
                        <ul class="mt-5 space-y-3">
                            @foreach ($p['poin'] as $poin)
                                <li class="flex items-start gap-3 text-slate-200">
                                    <i class="fas fa-circle-check mt-1 text-[#ffcc00]"></i>
                                    <span>{{ $poin }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= CARA PAKAI ================= --}}
    <section id="cara-pakai" class="scroll-mt-20 py-20 sm:py-28 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="max-w-2xl mx-auto text-center">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-yellow-600">Cara Pakai</p>
                <h2 class="mt-3 text-3xl sm:text-4xl font-black text-[#002d57]">Mulai dalam beberapa langkah</h2>
                <p class="mt-4 text-slate-500">Panduan singkat untuk mahasiswa dan dosen.</p>
            </div>

            <div class="mt-14 grid lg:grid-cols-2 gap-6 lg:gap-8">
                @foreach ($langkah as $l)
                    <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6 sm:p-8">
                        <div class="flex items-center gap-3">
                            <span class="flex size-11 items-center justify-center rounded-xl bg-[#002d57] text-white"><i class="fas {{ $l['ikon'] }}"></i></span>
                            <h3 class="text-xl font-bold text-[#002d57]">Untuk {{ $l['peran'] }}</h3>
                        </div>

                        <ol class="mt-8">
                            @foreach ($l['daftar'] as $i => [$judul, $teks])
                                <li class="relative flex gap-4 pb-8 last:pb-0">
                                    @unless ($loop->last)
                                        <span class="absolute left-5 top-11 bottom-1 w-0.5 bg-slate-200" aria-hidden="true"></span>
                                    @endunless
                                    <span class="relative flex size-10 shrink-0 items-center justify-center rounded-full bg-[#ffcc00] font-black text-[#002d57]">{{ $i + 1 }}</span>
                                    <div class="pt-1.5">
                                        <p class="font-bold text-slate-900">{{ $judul }}</p>
                                        <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $teks }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= FAQ ================= --}}
    <section id="faq" class="scroll-mt-20 py-20 sm:py-28 px-4">
        <div class="max-w-3xl mx-auto">
            <div class="text-center">
                <p class="text-sm font-bold uppercase tracking-[0.2em] text-yellow-600">FAQ</p>
                <h2 class="mt-3 text-3xl sm:text-4xl font-black text-[#002d57]">Pertanyaan yang sering diajukan</h2>
            </div>

            <div class="mt-12 space-y-3">
                @foreach ($faq as [$tanya, $jawab])
                    <details class="group rounded-2xl border border-slate-200 bg-white shadow-sm open:shadow-md transition-shadow" @if ($loop->first) open @endif>
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 p-5 font-semibold text-slate-800 hover:text-[#002d57]">
                            <span>{{ $tanya }}</span>
                            <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs text-slate-500 transition-transform group-open:rotate-180 group-open:bg-[#002d57] group-open:text-white">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </summary>
                        <p class="px-5 pb-5 -mt-1 leading-relaxed text-slate-600">{{ $jawab }}</p>
                    </details>
                @endforeach
            </div>

            <p class="mt-8 text-center text-sm text-slate-500">
                Masih ada pertanyaan? Hubungi Kaprodi atau dosen pengampu mata kuliah Anda.
            </p>
        </div>
    </section>

    {{-- ================= AJAKAN (CTA) ================= --}}
    <section class="px-4 pb-20 sm:pb-28">
        <div class="relative max-w-5xl mx-auto overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-[#002d57] to-blue-800 px-6 py-14 sm:px-12 text-center shadow-2xl">
            <i class="fas fa-graduation-cap absolute -left-6 -bottom-8 text-[10rem] text-white/5" aria-hidden="true"></i>
            <h2 class="relative text-3xl sm:text-4xl font-black text-white">Siap mengikuti ujian?</h2>
            <p class="relative mt-4 text-slate-200 max-w-xl mx-auto">Masuk ke portal dengan akun yang diberikan Kaprodi untuk melihat ujian yang sedang tersedia.</p>
            <a href="/admin" class="relative mt-8 inline-flex items-center gap-2 bg-[#ffcc00] text-[#002d57] px-10 py-4 rounded-2xl font-black text-lg hover:scale-105 transition-all shadow-xl">
                <i class="fas fa-arrow-right-to-bracket"></i> @auth Ke Dashboard @else Masuk Portal @endauth
            </a>
        </div>
    </section>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-[#002d57] text-white pt-16 sm:pt-20 pb-10 px-4">
        <div class="max-w-7xl mx-auto grid sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 border-b border-white/10 pb-12 mb-8">
            <div class="lg:col-span-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo UNSADA" class="h-16 mb-6 rounded-xl bg-white p-1">
                <p class="text-slate-300 text-base sm:text-lg mb-6 leading-relaxed max-w-md">Mencetak SDM unggul yang menguasai ilmu pengetahuan dan teknologi berdasarkan nilai-nilai luhur.</p>
                <a href="https://www.unsada.ac.id/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-5 py-2.5 font-semibold hover:bg-[#ffcc00] hover:text-[#002d57] transition-all">
                    <i class="fas fa-globe"></i> unsada.ac.id
                </a>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-5">Tautan</h4>
                <ul class="space-y-3 text-slate-300">
                    @foreach ($menu as $id => $label)
                        <li><a href="#{{ $id }}" class="hover:text-[#ffcc00] transition-colors">{{ $label }}</a></li>
                    @endforeach
                    <li><a href="/admin" class="hover:text-[#ffcc00] transition-colors">Masuk Portal</a></li>
                    <li><a href="https://pmb.unsada.ac.id/" target="_blank" rel="noopener noreferrer" class="hover:text-[#ffcc00] transition-colors">Informasi PMB</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-5">Lokasi Kampus</h4>
                <p class="text-slate-300">Jl. Taman Malaka Selatan, Pondok Kelapa,<br>Duren Sawit, Jakarta Timur 13450.</p>
                <p class="mt-4 text-[#ffcc00] font-bold"><i class="fas fa-phone mr-2"></i> (021) 8649051</p>
            </div>
        </div>
        <p class="text-center text-slate-400 text-sm italic">&copy; {{ date('Y') }} Universitas Darma Persada. Designed for Academic Excellence.</p>
    </footer>

</body>
</html>
