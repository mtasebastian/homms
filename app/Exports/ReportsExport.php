<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    protected $reportsService;
    protected $filters;

    public function __construct(array $filters = [], $reportsService)
    {
        $this->reportsService = $reportsService;
        $this->filters = $filters;
    }

    public function collection()
    {
        $report = $this->reportsService->filter($this->filters);
        $reporttype = $this->filters['reporttype'];
        return $report->map(function($data) use($reporttype){
            switch($reporttype){
                case "Financials":
                    return [
                        $data->id,
                        $data->resident->fullname,
                        $data->bill_year,
                        $data->monthname,
                        number_format($data->bill_amount, 2, '.', ','),
                        number_format($data->payments()->sum('payment'), 2, '.', ','),
                        number_format($data->balances()->sum('balance'), 2, '.', ','),
                        date("m/d/y", strtotime($data->created_at))
                    ];
                break;
                case "Requests":
                    return [
                        $data->id,
                        $data->request_type,
                        $data->reqBy->fullname,
                        $data->type,
                        $data->valid_from != '' ? date("m/d/Y", strtotime($data->valid_from)) . ' to ' . date("m/d/Y", strtotime($data->valid_to)) : '',
                        $data->appBy ? $data->appBy->name : '',
                        $data->chkBy ? $data->chkBy->name : '',
                        $data->request_status,
                        date("m/d/y", strtotime($data->created_at))
                    ];
                break;
                case "Residents":
                    return [
                        $data->id,
                        $data->fullname,
                        'Brgy. ' . ucwords(strtolower($data->barangay->name)) . " " . $data->hoaaddress . ", " . ucwords(strtolower($data->city->name)) . (str_contains(strtolower($data->city->name), 'city') ? ', ' : ' City, ') . ucwords(strtolower($data->province->name)),
                        $data->email_address,
                        $data->mobile_number,
                        $data->status(),
                        date("m/d/y", strtotime($data->created_at))
                    ];
                break;
                case "Complaints":
                    return [
                        $data->id,
                        $data->complaint_type,
                        $data->resident->fullname,
                        $data->complaint_to ? $data->defendant->fullname : '',
                        $data->details,
                        $data->reported_to->name,
                        $data->status,
                        date("m/d/y", strtotime($data->created_at))
                    ];
                break;
                case "Visitors":
                    return [
                        $data->id,
                        $data->visitor_type,
                        $data->name,
                        $data->purpose,
                        date("m/d/Y h:i A", strtotime($data->time_in)),
                        $data->time_out != '' ? date("m/d/Y h:i A", strtotime($data->time_out)) : '',
                        date("m/d/y", strtotime($data->created_at))
                    ];
                break;
            }
        });
    }

    public function headings(): array
    {
        $headings = [];
        switch($this->filters["reporttype"]){
            case "Financials":
                $headings = ['ID', 'Resident', 'Year', 'Month', 'Amount', 'Payment', 'Balance', 'Created At'];
            break;
            case "Requests":
                $headings = ['ID', 'Request Type', 'Requested By', 'Type', 'Validity', 'Approved By', 'Checked By', 'Status', 'Created At'];
            break;
            case "Residents":
                $headings = ['ID', 'Full Name', 'Address', 'Email Address', 'Mobile Number', 'Status', 'Created At'];
            break;
            case "Complaints":
                $headings = ['ID', 'Complaint Type', 'Complainant', 'Defendant', 'Details', 'Reported To', 'Status', 'Created At'];
            break;
            case "Visitors":
                $headings = ['ID', 'Visitor Type', 'Visitor Name', 'Purpose', 'Time In', 'Time Out', 'Created At'];
            break;
        }
        return $headings;
    }
}
