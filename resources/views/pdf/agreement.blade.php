<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agreement->agreement_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid #222;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header table {
            width: 100%;
        }

        .header h1 {
            margin: 0;
            text-transform: uppercase;
            font-size: 24px;
        }

        .header p {
            margin: 4px 0;
            color: #555;
            font-size: 14px;
        }

        .title {
            text-transform: uppercase;
            font-size: 28px;
            color: #777;
            text-align: right;
            margin: 0;
        }

        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }

        .info-section td {
            vertical-align: top;
            width: 50%;
        }

        .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #888;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .value {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 15px 0;
        }

        .body-content {
            margin-top: 30px;
            font-family: 'Times New Roman', serif;
            font-size: 15px;
            text-align: justify;
        }

        .footer-signatures {
            margin-top: 50px;
            width: 100%;
        }

        .footer-signatures td {
            width: 50%;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #111;
            width: 80%;
            margin: 60px auto 10px auto;
        }

        .signature-img {
            max-height: 80px;
            margin-top: 10px;
            margin-bottom: -40px;
        }
    </style>
</head>

<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <h1>JASA DIGITAL UMKM</h1>
                    <p>Jl. Contoh Bisnis No. 123, Tabanan, Bali</p>
                    <p>jasadigitalumkm@gmail.com</p>
                </td>
                <td style="text-align: right;">
                    <h2 class="title">Agreement</h2>
                    <p><strong>Ref:</strong> {{ $agreement->agreement_number }}</p>
                    <p><strong>Date:</strong> {{ $agreement->start_date->format('d M Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-section">
        <tr>
            <td style="padding-right: 20px;">
                <div class="label">Client Details</div>
                <p class="value">{{ $agreement->client_name }}</p>
                <p style="margin:0; font-size:14px; color:#555;">{{ $agreement->company_name }}</p>
                <p style="margin:0; font-size:14px; color:#555;">{{ $agreement->client_email }}</p>
            </td>
            <td style="background-color: #f9f9f9; padding: 15px;">
                <div class="label">Contract Totals</div>
                <p class="value" style="font-size:22px;">Rp {{ number_format($agreement->price, 0, ',', '.') }}</p>
                <p style="margin:5px 0 0 0; font-size:13px;"><strong>Term:</strong>
                    {{ $agreement->start_date->format('d M Y') }} - {{ $agreement->end_date->format('d M Y') }}</p>
                <p style="margin:5px 0 0 0; font-size:13px;"><strong>Status:</strong>
                    {{ strtoupper($agreement->status) }}</p>
            </td>
        </tr>
    </table>

    <div class="body-content">
        {!! $agreement->scope_of_work !!}
    </div>

    <table class="footer-signatures">
        <tr>
            <td>
                <div class="label">Authorized by Provider:</div>
                <div class="signature-line"></div>
                <strong>JASA DIGITAL UMKM</strong>
            </td>
            <td>
                <div class="label">Agreed by Client:</div>

                @if($agreement->status === 'signed' && $agreement->signature_path)
                    @php
                        // To display image in dompdf, need absolute local path
                        $path = storage_path('app/public/' . $agreement->signature_path);
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        if (file_exists($path)) {
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            echo '<img src="' . $base64 . '" class="signature-img">';
                        }
                    @endphp
                @endif

                <div class="signature-line"
                    style="margin-top: {{ ($agreement->status === 'signed' && $agreement->signature_path) ? '50px' : '80px' }}">
                </div>
                <strong>{{ $agreement->client_name }}</strong>
                @if($agreement->signed_at)
                    <p style="font-size: 11px; color:#666; margin-top:5px;">Signed on:
                        {{ $agreement->signed_at->format('d M Y, H:i') }}</p>
                @endif
            </td>
        </tr>
    </table>

</body>

</html>