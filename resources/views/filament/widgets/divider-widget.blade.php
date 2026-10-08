{{-- Pemisah antara info akun dan info akademik. Style inline karena CSS Filament sudah terkompilasi. --}}
<div>
    <style>
        .kd-divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: .25rem 0;
            color: var(--gray-500);
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .kd-divider::before,
        .kd-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--gray-200);
        }

        .dark .kd-divider { color: var(--gray-400); }

        .dark .kd-divider::before,
        .dark .kd-divider::after { background: rgb(255 255 255 / .1); }
    </style>

    <div class="kd-divider" role="separator" aria-label="Informasi Akademik">
        <span>Informasi Akademik</span>
    </div>
</div>
