<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Service Agreement - {{ $agreement->agreement_number }}</title>
    <style>
        /* 1 Page Setup */
        @page {
            size: a4;
            margin: 35px 40px 50px 40px;
            /* Top, Right, Bottom, Left. Bottom margin for footer */
        }

        body {
            font-family: 'Times New Roman', 'DejaVu Serif', serif;
            font-size: 11px;
            /* Between 11-12px as requested */
            line-height: 1.5;
            color: #000;
            /* Optimized for B&W print */
            margin: 0;
            padding: 0;
        }

        /* Typography */
        h1,
        .document-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 0 0 10px 0;
            text-transform: uppercase;
        }

        h2,
        .section-heading {
            font-size: 13px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        p {
            text-align: justify;
            margin: 0 0 10px 0;
        }

        /* 2 Header Section */
        .header-section {
            text-align: center;
            margin-bottom: 25px;
            /* Spacing before body 20px+ */
        }

        .agreement-number {
            font-size: 12px;
            margin: 0;
            font-weight: normal;
        }

        /* Utility */
        .bold {
            font-weight: bold;
        }

        .indent {
            margin-left: 20px;
        }

        .section-block {
            margin-bottom: 15px;
        }

        /* 4 Identity Section Layout - Vertical spacing without purely tables */
        .identity-block {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        .identity-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        .identity-detail {
            margin-left: 15px;
            margin-bottom: 2px;
        }

        .identity-detail span.label {
            display: inline-block;
            width: 80px;
        }

        /* Lists for clauses */
        ul,
        ol {
            margin-top: 0;
            margin-bottom: 10px;
            padding-left: 25px;
            text-align: justify;
        }

        li {
            margin-bottom: 5px;
        }

        /* 8 Signature Section Layout */
        .signature-section {
            margin-top: 40px;
            width: 100%;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-td {
            width: 50%;
            vertical-align: top;
            text-align: center;
        }

        .signature-space {
            height: 90px;
            /* 80-100px */
            /* Background placeholder if you want to visually see it during debug */
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* 9 Footer */
        .footer {
            position: fixed;
            bottom: -30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #555;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <!-- 9 Footer (Fixed position elements must be defined first in dompdf) -->
    <div class="footer">
        Agreement Number: {{ $agreement->agreement_number }} | Generated on: {{ now()->format('Y-m-d H:i:s') }}
    </div>

    <!-- 2 Header Section -->
    <div class="header-section">
        <div class="document-title">PERJANJIAN JASA PENGEMBANGAN WEBSITE</div>
        <div class="agreement-number">Nomor: {{ $agreement->agreement_number }}</div>
    </div>

    <!-- 3 Opening Paragraph -->
    <p>Perjanjian kerja sama ini dibuat dan ditandatangani secara sah. Adapun jadwal penetapan dimulainya kesepakatan
        ini mengacu pada informasi berikut:</p>
    <p class="bold">Tanggal: {{ $agreement->agreement_date->format('d F Y') }}</p>
    <p>Oleh dan antara pihak-pihak yang bertanda tangan di bawah ini:</p>

    <!-- 4 Identity Section Layout -->
    <div class="identity-block">
        <div class="identity-title">Pihak Pertama:</div>
        <div class="identity-detail"><span class="label">Nama:</span> {{ $agreement->provider_name }}</div>
        <div class="identity-detail"><span class="label">Alamat:</span> {{ $agreement->provider_address }}</div>
        <div class="identity-detail"><span class="label">Email:</span> {{ $agreement->provider_email }}</div>
        <div style="margin-top: 5px;"><em>Selanjutnya disebut sebagai <strong>“Penyedia Jasa”</strong>.</em></div>
    </div>

    <div class="identity-block">
        <div class="identity-title">Pihak Kedua:</div>
        <div class="identity-detail"><span class="label">Nama:</span> {{ $agreement->client_name }}</div>
        <div class="identity-detail"><span class="label">Alamat:</span> {{ $agreement->client_address }}</div>
        <div class="identity-detail"><span class="label">Email:</span> {{ $agreement->client_email }}</div>
        <div style="margin-top: 5px;"><em>Selanjutnya disebut sebagai <strong>“Klien”</strong>.</em></div>
    </div>

    <p>Para pihak sepakat untuk mengikatkan diri dalam Perjanjian Jasa Pengembangan Website dengan syarat dan ketentuan
        sebagai berikut:</p>

    <!-- 5 Clauses Section -->
    <div class="section-heading">1. RUANG LINGKUP PEKERJAAN</div>
    <p>Proyek <span class="bold">{{ $agreement->project_name }}</span> mencakup pengembangan perangkat lunak dengan
        penjabaran deskripsi layanan sebagai berikut:</p>
    <ul>
        <li>{{ $agreement->service_description }}</li>
    </ul>

    <p>Rincian ruang lingkup pekerjaan (Scope of Work) yang disepakati adalah:</p>
    <div class="indent">
        {!! nl2br(e($agreement->scope_of_work)) !!}
    </div>

    <div class="section-heading">2. NILAI KONTRAK & PEMBAYARAN</div>
    <!-- 6 Financial Section Formatting -->
    <p>Kedua belah pihak menyetujui rincian biaya pengembangan dan skema pembayaran kerja sama sebagai berikut:</p>
    <ul>
        <li><span style="font-weight: 600;">Total Nilai Kontrak: Rp
                {{ number_format($agreement->total_value, 0, ',', '.') }}</span></li>
        <li>Skema Pembayaran: {{ $agreement->payment_terms }}</li>
    </ul>

    <!-- 7 Timeline Section -->
    <div class="section-heading">3. JANGKA WAKTU PELAKSANAAN</div>
    <p>Penyedia Jasa akan melaksanakan pekerjaan sesuai dengan alokasi waktu penjadwalan proyek sebagai berikut:</p>
    <ul>
        <li>Mulai: {{ $agreement->start_date->format('d F Y') }}</li>
        <li>Estimasi selesai: {{ $agreement->estimated_completion_date->format('d F Y') }}</li>
    </ul>

    <div class="section-heading">4. KETENTUAN HAK DAN KEWAJIBAN</div>
    <p>Penyedia Jasa berkewajiban menyelesaikan pekerjaan sesuai lingkup pekerjaan yang telah disepakati dan
        menyerahkannya tepat waktu. Klien berkewajiban melakukan pembayaran penuh dan menyediakan data, materi, serta
        persetujuan evaluasi (feedback) secara tepat waktu agar tidak menghambat durasi penyelesaian secara keseluruhan.
    </p>

    <div class="section-heading">5. PENYELESAIAN PERSELISIHAN</div>
    <p>Apabila terjadi perselisihan terkait pelaksanaan perjanjian ini, para pihak sepakat untuk menyelesaikan segala
        permasalahan secara musyawarah dan mufakat sebelum menempuh jalur hukum perdata yang berlaku.</p>

    <p style="margin-top: 20px;">Demikian Perjanjian ini dibuat dalam keadaan sadar dan tanpa paksaan dari pihak mana
        pun. Dokumen ini menjadi ikatan hukum yang sah sejak didokumentasikan dan ditandatangani.</p>

    <!-- 8 Signature Section Layout -->
    <!-- Avoid flexbox, use float or tables for DOMPDF -->
    <div class="signature-section" style="page-break-inside: avoid;">
        <table class="signature-table">
            <tr>
                <td class="signature-td">
                    Penyedia Jasa
                    <div class="signature-space">
                        @if(!empty($agreement->signature_provider_path))
                            <img src="{{ public_path('storage/' . $agreement->signature_provider_path) }}" height="80">
                        @endif
                    </div>
                    <div class="signature-name">{{ $agreement->provider_name }}</div>
                </td>
                <td class="signature-td">
                    Klien
                    <div class="signature-space">
                        @if(!empty($agreement->signature_client_path))
                            <img src="{{ public_path('storage/' . $agreement->signature_client_path) }}" height="80">
                        @endif
                    </div>
                    <div class="signature-name">{{ $agreement->client_name }}</div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>