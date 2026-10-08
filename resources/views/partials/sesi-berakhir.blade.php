{{--
    Pengganti dialog bawaan Livewire "This page has expired" (window.confirm) saat sesi login habis (HTTP 419).
    Dipakai di halaman ujian dan panel Filament, jadi style ditulis mandiri (tidak bergantung Tailwind build).
--}}
<div x-data="{ open: false }" x-cloak x-show="open" x-transition.opacity
     @sesi-berakhir.window="open = true"
     class="kd-sesi" role="alertdialog" aria-modal="true" aria-labelledby="kd-sesi-judul">
    <div class="kd-sesi__panel" x-trap="open">
        <div class="kd-sesi__ikon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
            </svg>
        </div>
        <h2 id="kd-sesi-judul">Sesi Anda telah berakhir</h2>
        <p>Demi keamanan, sesi login berakhir karena tidak aktif cukup lama. Muat ulang halaman lalu masuk kembali untuk melanjutkan.</p>
        <button type="button" onclick="window.location.reload()">Muat ulang halaman</button>
    </div>
</div>

<style>
    .kd-sesi {
        position: fixed;
        inset: 0;
        z-index: 60;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgb(15 23 42 / .65);
        backdrop-filter: blur(4px);
    }

    .kd-sesi__panel {
        width: 100%;
        max-width: 24rem;
        border-radius: 1.5rem;
        background: #fff;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 25px 50px -12px rgb(0 0 0 / .35);
        font-family: inherit;
    }

    .kd-sesi__ikon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 4rem;
        height: 4rem;
        margin: 0 auto;
        border-radius: 9999px;
        background: #dbeafe;
        color: #2563eb;
        box-shadow: 0 0 0 8px #eff6ff;
    }

    .kd-sesi__ikon svg { width: 2rem; height: 2rem; }

    .kd-sesi h2 {
        margin: 1.25rem 0 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
    }

    .kd-sesi p {
        margin: .5rem 0 0;
        font-size: .875rem;
        line-height: 1.6;
        color: #64748b;
    }

    .kd-sesi button {
        width: 100%;
        margin-top: 1.5rem;
        padding: .75rem 1rem;
        border: 0;
        border-radius: .75rem;
        background: #2563eb;
        color: #fff;
        font: inherit;
        font-size: .875rem;
        font-weight: 600;
        cursor: pointer;
    }

    .kd-sesi button:hover { background: #1d4ed8; }
    .kd-sesi button:focus-visible { outline: 3px solid #93c5fd; outline-offset: 2px; }

    .dark .kd-sesi__panel { background: #18181b; }
    .dark .kd-sesi h2 { color: #fff; }
    .dark .kd-sesi p { color: #a1a1aa; }
    .dark .kd-sesi__ikon { background: rgb(37 99 235 / .2); color: #60a5fa; box-shadow: 0 0 0 8px rgb(37 99 235 / .08); }
</style>

<script>
    (() => {
        const pasang = () => Livewire.hook('request', ({ fail }) => {
            fail(({ status, preventDefault }) => {
                if (status === 419) {
                    preventDefault(); // cegah window.confirm bawaan Livewire
                    window.dispatchEvent(new CustomEvent('sesi-berakhir'));
                }
            });
        });

        // Bisa dimuat sebelum atau sesudah script Livewire
        window.Livewire ? pasang() : document.addEventListener('livewire:init', pasang);
    })();
</script>
