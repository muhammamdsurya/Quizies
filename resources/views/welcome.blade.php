<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Akademik & Kuiz Digital - UNSADA</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
            background-attachment: fixed; /* Membuat efek parallax modern */
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
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900 leading-normal">

    <nav class="fixed w-full z-50 glass-nav border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">

                <div class="flex items-center space-x-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UNSADA" class="h-12 w-auto drop-shadow-sm">
                    <div class="hidden sm:block border-l-2 border-slate-300 pl-4">
                        <h1 class="text-lg font-bold text-[#002d57] leading-none tracking-tight">KUIZ DIGITAL</h1>
                        <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-[0.2em]">Univ. Darma Persada</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="/admin" class="inline-flex items-center bg-[#002d57] text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-blue-900 transition-all duration-300 shadow-lg hover:shadow-blue-900/20 active:scale-95 group">
                        <span>Masuk Portal</span>
                        <i class="fas fa-arrow-right-to-bracket ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <section class="hero-section min-h-screen flex items-center justify-center text-center px-4 relative">
        <div class="max-w-4xl pt-20">
            <div class="inline-flex items-center px-4 py-2 mb-8 bg-white/10 border border-white/20 rounded-full backdrop-blur-md">
                <span class="relative flex h-3 w-3 mr-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                </span>
                <span class="text-white text-[10px] font-bold uppercase tracking-[0.3em]">Official Academic Platform</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-black text-white mb-8 leading-[1.1]">
                Elevasi Pendidikan <br>
                <span class="text-gradient">Melalui Inovasi Digital</span>
            </h1>

            <p class="text-lg md:text-xl text-slate-200 mb-12 max-w-2xl mx-auto font-light leading-relaxed">
                Platform evaluasi terpadu untuk mendukung mahasiswa <span class="font-bold border-b-2 border-yellow-400">UNSADA</span> dalam meraih capaian akademik terbaik di era teknologi.
            </p>

            <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
                <a href="/admin" class="w-full sm:w-auto bg-[#ffcc00] text-[#002d57] px-12 py-4 rounded-2xl font-black text-lg hover:scale-105 transition-all shadow-xl hover:shadow-yellow-500/30">
                    <i class="fas fa-rocket mr-2"></i> MULAI SEKARANG
                </a>
                <a href="https://pmb.unsada.ac.id/" target="_blank" class="w-full sm:w-auto bg-white/10 text-white border border-white/30 px-12 py-4 rounded-2xl font-bold text-lg hover:bg-white/20 backdrop-blur-md transition-all">
                    Informasi PMB
                </a>
            </div>
        </div>
    </section>

    <section class="py-24 px-4 bg-slate-50 relative z-10">
        <div class="max-w-7xl mx-auto -mt-40 grid md:grid-cols-3 gap-8">

            <div class="feature-card bg-white p-10 rounded-[2rem] shadow-xl border border-slate-100 transition-all duration-300 group">
                <div class="icon-box w-16 h-16 bg-blue-50 text-[#002d57] rounded-2xl flex items-center justify-center text-3xl mb-8 transition-all duration-500">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-[#002d57]">Materi Digital</h3>
                <p class="text-slate-500 leading-relaxed">Akses modul perkuliahan dan bank soal dengan sistem manajemen konten yang rapi.</p>
            </div>

            <div class="feature-card bg-white p-10 rounded-[2rem] shadow-xl border border-slate-100 transition-all duration-300 group">
                <div class="icon-box w-16 h-16 bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center text-3xl mb-8 transition-all duration-500">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-[#002d57]">Ujian Terjadwal</h3>
                <p class="text-slate-500 leading-relaxed">Sistem pengerjaan kuiz dengan timer presisi dan pengawasan integritas yang ketat.</p>
            </div>

            <div class="feature-card bg-white p-10 rounded-[2rem] shadow-xl border border-slate-100 transition-all duration-300 group">
                <div class="icon-box w-16 h-16 bg-blue-50 text-[#002d57] rounded-2xl flex items-center justify-center text-3xl mb-8 transition-all duration-500">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 text-[#002d57]">Hasil Instan</h3>
                <p class="text-slate-500 leading-relaxed">Lihat perkembangan skor Anda segera setelah menyelesaikan ujian secara transparan.</p>
            </div>

        </div>
    </section>

    <footer class="bg-[#002d57] text-white pt-20 pb-10 px-4">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 border-b border-white/10 pb-12 mb-8">
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 mb-6 brightness-200">
                <p class="text-slate-300 text-lg mb-6 leading-relaxed">Mencetak SDM unggul yang menguasai ilmu pengetahuan dan teknologi berdasarkan nilai-nilai luhur.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#ffcc00] hover:text-[#002d57] transition-all"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#ffcc00] hover:text-[#002d57] transition-all"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#ffcc00] hover:text-[#002d57] transition-all"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="md:text-right">
                <h4 class="text-xl font-bold mb-6">Lokasi Kampus</h4>
                <p class="text-slate-300">Jl. Taman Malaka Selatan, Pondok Kelapa,<br>Duren Sawit, Jakarta Timur 13450.</p>
                <p class="mt-4 text-[#ffcc00] font-bold"><i class="fas fa-phone mr-2"></i> (021) 8649051</p>
            </div>
        </div>
        <p class="text-center text-slate-400 text-sm italic">&copy; 2026 Universitas Darma Persada. Designed for Academic Excellence.</p>
    </footer>

</body>
</html>
