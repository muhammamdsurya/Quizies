<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Akademik & Kuiz - UNSIKA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.8)), url('https://images.unsplash.com/photo-1523050335392-9bc56753d100?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900">

    <nav class="fixed w-full z-50 glass-nav border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-blue-700 rounded-lg flex items-center justify-center text-white font-bold shadow-lg">U</div>
                    <span class="text-xl font-bold tracking-tight text-slate-800">Kuiz <span class="text-blue-600">Digital</span></span>
                </div>
                <div>
                    <a href="/admin" class="inline-flex items-center bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold text-sm hover:bg-blue-700 transition shadow-md group">
                        Masuk Portal
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-section min-h-[85vh] flex items-center justify-center text-center px-4 pt-16">
        <div class="max-w-3xl">
            <div class="inline-block px-4 py-1.5 mb-6 bg-blue-500/20 border border-blue-400/30 rounded-full">
                <span class="text-blue-300 text-xs font-bold uppercase tracking-widest">Portal Resmi Akademik</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
                Pusat Pembelajaran & <br><span class="text-blue-400 text-italic">Evaluasi Mahasiswa</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 mb-10 leading-relaxed">
                Website ini merupakan platform resmi untuk kegiatan akademik, manajemen data mahasiswa, dan pengerjaan kuiz digital Universitas XYZ.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/admin" class="bg-white text-slate-900 px-8 py-4 rounded-xl font-bold text-lg hover:bg-blue-50 transition shadow-xl border-b-4 border-slate-200">
                    <i class="fas fa-edit mr-2 text-blue-600"></i> Mulai Kuiz
                </a>
            </div>
        </div>
    </section>

    <section class="py-20 -mt-20 px-4">
        <div class="max-w-7xl mx-auto grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 transform hover:-translate-y-2 transition duration-300">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 text-2xl mb-6">
                    <i class="fas fa-university"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Sistem Akademik</h3>
                <p class="text-slate-600 leading-relaxed">Akses jadwal kuliah, perwalian, dan pantau perkembangan studi Anda secara real-time dalam satu dasbor.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 transform hover:-translate-y-2 transition duration-300">
                <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center text-yellow-600 text-2xl mb-6">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Kuiz Digital</h3>
                <p class="text-slate-600 leading-relaxed">Kerjakan tugas dan ujian online dengan sistem yang aman, transparan, dan hasil yang langsung terintegrasi.</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 transform hover:-translate-y-2 transition duration-300">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-green-600 text-2xl mb-6">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Informasi Terkini</h3>
                <p class="text-slate-600 leading-relaxed">Dapatkan pemberitahuan langsung mengenai pengumuman kampus dan batas waktu pengerjaan tugas.</p>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-12 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <div class="flex justify-center space-x-6 mb-8 text-xl">
                <a href="#" class="hover:text-blue-400 transition"><i class="fab fa-instagram"></i></a>
                <a href="#" class="hover:text-blue-400 transition"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-blue-400 transition"><i class="fab fa-twitter"></i></a>
            </div>
            <p class="text-sm">
                &copy; 2026 Universitas XYZ. <br class="md:hidden">
                <span class="hidden md:inline">|</span> Platform Digital Akademik & Evaluasi Mahasiswa.
            </p>
        </div>
    </footer>

</body>
</html>
