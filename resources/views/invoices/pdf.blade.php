<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            size: a4;
            margin: 35px 40px 50px 40px;
        }

        body {
            font-family: 'Times New Roman', 'DejaVu Serif', serif;
            font-size: 11px;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

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

        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
        }

        .company-detail {
            font-size: 10px;
            color: #444;
        }

        .invoice-title {
            font-size: 26px;
            font-weight: 300;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        .invoice-number {
            font-size: 13px;
            font-weight: bold;
        }

        .agreement-ref {
            font-size: 10px;
            color: #666;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 2px solid #000;
            margin: 8px 0 16px 0;
        }

        .divider-light {
            border: none;
            border-top: 1px solid #ddd;
            margin: 8px 0;
        }

        /* Bill To / Dates */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .meta-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
        }

        .meta-value {
            font-size: 11px;
            font-weight: bold;
        }

        .due-date {
            color: #c00;
            font-weight: bold;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .items-table th {
            background: #f5f5f5;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }

        .items-table th.right {
            text-align: right;
        }

        .items-table td {
            padding: 8px;
            font-size: 11px;
            border-bottom: 1px solid #eee;
        }

        .items-table td.right {
            text-align: right;
        }

        /* Totals */
        .totals-table {
            width: 40%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .totals-table td {
            padding: 4px 8px;
            font-size: 11px;
        }

        .totals-table .grand-row td {
            font-size: 13px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 8px;
        }

        .amount-right {
            text-align: right;
        }

        /* Signature */
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
            padding: 0 20px;
        }

        .signature-space {
            height: 80px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="footer">
        Invoice: {{ $invoice->invoice_number }}
        @if($invoice->agreement) | Agreement: {{ $invoice->agreement->agreement_number }} @endif
        | Generated: {{ now()->format('Y-m-d H:i') }}
    </div>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td>
                <div class="company-name">Jasa Digital UMKM</div>
                <div class="company-detail">Jl. Contoh Bisnis No. 123, Tabanan, Bali</div>
                <div class="company-detail">jasadigitalumkm@gmail.com</div>
            </td>
            <td style="text-align: right; vertical-align: top;">
                <div class="invoice-title">Invoice</div>
                <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                @if($invoice->agreement)
                    <div class="agreement-ref">Ref: {{ $invoice->agreement->agreement_number }}</div>
                @endif
            </td>
        </tr>
    </table>

    <hr class="divider">

    <!-- Bill To & Dates -->
    <table class="meta-table">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="meta-label">Bill To</div>
                @if($invoice->agreement)
                    <div class="meta-value">{{ $invoice->agreement->client_name }}</div>
                    <div>{{ $invoice->agreement->client_email }}</div>
                    <div>{{ $invoice->agreement->client_address }}</div>
                @endif
                @if($invoice->payment_reference)
                    <div style="margin-top: 8px;">
                        <div class="meta-label">Payment Reference</div>
                        <div class="meta-value">{{ $invoice->payment_reference }}</div>
                    </div>
                @endif
            </td>
            <td style="width: 25%; vertical-align: top;">
                <div class="meta-label">Date Issued</div>
                <div>{{ $invoice->invoice_date->format('d F Y') }}</div>
            </td>
            <td style="width: 25%; vertical-align: top;">
                <div class="meta-label">Due Date</div>
                <div class="due-date">{{ $invoice->due_date->format('d F Y') }}</div>
            </td>
        </tr>
    </table>

    <hr class="divider-light">

    <!-- Line Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right" style="width: 8%">Qty</th>
                <th class="right" style="width: 20%">Unit Price</th>
                <th class="right" style="width: 20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <table class="totals-table">
        <tr>
            <td>Subtotal</td>
            <td class="amount-right">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($invoice->tax_amount > 0)
            <tr>
                <td>Tax ({{ $invoice->tax_rate }}%)</td>
                <td class="amount-right">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
            </tr>
        @endif
        @if($invoice->discount_amount > 0)
            <tr>
                <td>Discount</td>
                <td class="amount-right">- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
            </tr>
        @endif
        <tr class="grand-row">
            <td>Grand Total</td>
            <td class="amount-right">Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
        </tr>
        @php $totalPaid = $invoice->payments->sum('amount'); @endphp
        @if($totalPaid > 0)
            <tr>
                <td style="font-size:10px; color:#555;">Amount Paid</td>
                <td class="amount-right" style="font-size:10px; color:#555;">Rp {{ number_format($totalPaid, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td
                    style="font-size:10px; font-weight:bold; {{ ($invoice->grand_total - $totalPaid) > 0 ? 'color:#c00;' : 'color:#060;' }}">
                    Balance Due</td>
                <td class="amount-right"
                    style="font-size:10px; font-weight:bold; {{ ($invoice->grand_total - $totalPaid) > 0 ? 'color:#c00;' : 'color:#060;' }}">
                    Rp {{ number_format($invoice->grand_total - $totalPaid, 0, ',', '.') }}
                </td>
            </tr>
        @endif
    </table>

    @if($invoice->notes)
        <p style="margin-top: 12px; font-style: italic; font-size: 10px; color: #555;">
            <strong>Notes:</strong> {{ $invoice->notes }}
        </p>
    @endif

    <!-- Signature Section -->
    <div class="signature-section" style="page-break-inside: avoid;">
        <table class="signature-table">
            <tr>
                <td class="signature-td">
                    Penyedia Jasa
                    <div class="signature-space"></div>
                    <div class="signature-name">Jasa Digital UMKM</div>
                </td>
                <td class="signature-td">
                    Klien / Client
                    <div class="signature-space"></div>
                    @if($invoice->agreement)
                        <div class="signature-name">{{ $invoice->agreement->client_name }}</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <p style="text-align: center; margin-top: 20px; font-size: 10px; color: #888;">Terima kasih atas kepercayaan Anda. ·
        Thank you for your business!</p>

</body>

</html>