<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report - {{ $labRequest->patient->user->FullName() }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.4; margin: 0; padding: 20px; }
        .report-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .report-header h1 { margin: 0; color: #000; text-transform: uppercase; letter-spacing: 2px; }
        .report-header p { margin: 5px 0 0; color: #666; }
        
        .info-section { display: flex; justify-content: space-between; margin-bottom: 30px; font-size: 14px; }
        .info-box { width: 48%; }
        .info-row { display: flex; margin-bottom: 5px; }
        .info-label { width: 120px; font-weight: bold; color: #555; }
        .info-value { flex: 1; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background-color: #f8f9fa; border-bottom: 2px solid #dee2e6; padding: 12px 10px; text-align: left; font-size: 13px; text-transform: uppercase; color: #555; }
        td { border-bottom: 1px solid #eee; padding: 12px 10px; font-size: 14px; }
        
        .result-value { font-weight: bold; }
        .remark-high { color: #d32f2f; font-weight: bold; }
        .remark-low { color: #1976d2; font-weight: bold; }
        .remark-normal { color: #388e3c; }
        .comments-section { margin-top: 40px; padding: 15px; background-color: #fcfcfc; border: 1px solid #f0f0f0; border-radius: 4px; }
        .comments-label { font-weight: bold; font-size: 13px; color: #1a237e; text-transform: uppercase; margin-bottom: 10px; display: block; }
        .comments-text { white-space: pre-wrap; font-style: italic; color: #444; }

        .footer { margin-top: 60px; display: flex; justify-content: space-between; border-top: 1px solid #eee; pt: 20px; }
        .signature-box { text-align: center; width: 250px; }
        .signature-line { border-top: 1px solid #000; margin-top: 50px; padding-top: 5px; }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .btn-print { display: none; }
        }

        .btn-print {
            position: fixed; top: 20px; right: 20px;
            padding: 10px 20px; background: #007bff; color: #fff;
            border: none; border-radius: 4px; cursor: pointer; text-decoration: none;
            font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <a href="javascript:window.print()" class="btn-print no-print">Print Report</a>

    <div class="report-header">
        @if($settings->system_logo)
            <img src="{{ asset($settings->system_logo) }}" alt="Logo" style="max-height: 80px; margin-bottom: 10px;">
        @endif
        <h1>Laboratory Investigation Report</h1>
        <p>{{ $settings->system_name }}</p>
        <p style="font-size: 12px; margin-top: 2px;">{{ $settings->system_address }}</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <div class="info-row"><div class="info-label">Patient Name:</div><div class="info-value">{{ $labRequest->patient->user->FullName() }}</div></div>
            <div class="info-row"><div class="info-label">Hospital No:</div><div class="info-value">{{ $settings->hospital_number_prefix }}-{{ $labRequest->patient->hospital_no }}</div></div>
            <div class="info-row"><div class="info-label">Age / Gender:</div><div class="info-value">{{ $labRequest->patient->getAge() }} / {{ ucfirst($labRequest->patient->gender) }}</div></div>
        </div>
        <div class="info-box" style="text-align: right;">
            <div class="info-row"><div class="info-label" style="text-align: right; width: 100%;"><strong>Ref No:</strong> {{ $labRequest->request_ref }}</div></div>
            <div class="info-row"><div class="info-label" style="text-align: right; width: 100%;"><strong>Requested:</strong> {{ $labRequest->created_at->format('d M, Y H:i') }}</div></div>
            <div class="info-row"><div class="info-label" style="text-align: right; width: 100%;"><strong>Reported:</strong> {{ $result->created_at->format('d M, Y H:i') }}</div></div>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 style="margin: 0; color: #1a237e; border-left: 4px solid #1a237e; padding-left: 10px;">{{ strtoupper($labRequest->test->name) }}</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 35%">Parameter</th>
                <th style="width: 20%">Result</th>
                <th style="width: 25%">Reference Range</th>
                <th style="width: 20%">Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result->items as $item)
            <tr>
                <td>{{ $item->templateItem->parameter->name }}</td>
                <td class="result-value">{{ $item->value }} {{ $item->templateItem->unit }}</td>
                <td style="color: #666; font-size: 13px;">{{ $item->templateItem->reference }} {{ $item->templateItem->unit }}</td>
                <td>
                    @php $remark = $item->remark; @endphp
                    @if($remark)
                        <span class="remark-{{ strtolower($remark) }}">{{ $remark }}</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($result->pathologist_comments)
    <div class="comments-section">
        <span class="comments-label">Clinical Interpretation / Comments:</span>
        <div class="comments-text">{{ $result->pathologist_comments }}</div>
    </div>
    @endif

    <div class="footer">
        <div class="signature-box" style="text-align: left; width: 60%;">
            <p style="font-size: 11px; color: #555; margin-bottom: 5px;">
                <strong>Reported By:</strong> {{ $result->user->FullName() }} | {{ $result->created_at->format('d M, Y H:i') }}
                @if($result->is_approved)
                    <span class="mx-2">|</span>
                    <strong>Approved By:</strong> {{ $result->approvedBy->FullName() }} | {{ $result->approved_at->format('d M, Y H:i') }}
                @endif
            </p>
            <p style="font-size: 10px; color: #888; margin-top: 5px;">
                Report printed on {{ now()->format('d M, Y H:i') }} by {{ auth()->user()->FullName() }}
            </p>
        </div>
        <div class="signature-box" style="text-align: right; width: 60%;">
            <div style="font-size: 10px; color: #1a237e; font-weight: bold; border-top: 2px solid #edeff2; padding-top: 10px;">
                <span>{{ $settings->system_address }}</span>
                @if($settings->system_phone)
                    <span class="mx-2">|</span>
                    <span><i class="ti tabler-phone me-1"></i> {{ $settings->system_phone }}</span>
                @endif
                @if($settings->system_email)
                    <span class="mx-2">|</span>
                    <span><i class="ti tabler-mail me-1"></i> {{ $settings->system_email }}</span>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
