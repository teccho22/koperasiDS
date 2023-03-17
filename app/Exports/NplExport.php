<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class NplExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $agent;
    protected $dateFrom;
    protected $dateTo;
    protected $totalRow;

    function __construct($agent, $dateFrom, $dateTo) {
            $this->agent = $agent;
            $this->dateFrom = $dateFrom;
            $this->dateTo = $dateTo;
    }

    public function collection()
    {
        //
        $sql = DB::table('ms_loans')
                ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id')
                ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id');

        if ($this->agent)
        {
            $sql->where("customers.customer_agent","=",$this->agent);
        }
        if ($this->dateFrom)
        {
            $sql->whereRaw("DATE_FORMAT(ms_loans.create_at, '%Y-%m') >= ?", date($this->dateFrom));
        }
        if ($this->dateTo)
        {
            $sql->whereRaw("DATE_FORMAT(ms_loans.create_at, '%Y-%m') <= ?", date($this->dateTo));
        }
        if (!$this->dateFrom && !$this->dateTo)
        {
            $sql->whereMonth('ms_loans.create_at', '=', date('m'))
            ->whereYear('ms_loans.create_at', '=', date('Y'));
        }

        $npl = $sql
            ->where('customers.is_active', 1)
            ->where('ms_loans.is_active', 1)
            ->where('ms_loans.loan_collect', '>=', 3)
            ->where(DB::raw('(select count(1) from ms_incomings where loan_status = "Overdue" and ms_incomings.loan_id = ms_loans.loan_id) > 3'))
            ->select('ms_loans.customer_id',
                    'customers.customer_name',
                    'ms_loans.loan_number',
                    'ms_loans.loan_amount',
                    'ms_loans.installment_amount',
                    DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%d-%b-%Y") as disbursement_date'),
                    'ms_loans.tenor',
                    DB::raw('IFNULL(ms_loans.loan_amount - (
                        SELECT SUM(incoming_amount)
                        FROM ms_incomings
                        WHERE loan_status in ("Paid","Not Fully Paid")
                        and ms_incomings.loan_id = ms_loans.loan_id
                    ), ms_loans.loan_amount) AS outstanding'),
                    DB::raw('IFNULL(ms_loans.loan_amount - (
                        SELECT SUM(incoming_amount)
                        FROM ms_incomings
                        WHERE loan_status in ("Paid","Not Fully Paid")
                        and ms_incomings.loan_id = ms_loans.loan_id
                    ), ms_loans.loan_amount) AS npl_at')
            )->get();

        $this->totalRow = sizeof($npl);

        return collect($npl);
    }

    public function headings(): array
    {
        return ["Customer Id", "Customer Name", "Loan Id", "Loan Amount", "Installment Amount", "Outgoing Date", "Tenor", "Outsanding", "NPL at"];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:I1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12)->setBold(true);

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '#000000'],
                        ]
                    ]
                ];

                $cellRange = 'A1:I' .(string)($this->totalRow +1); // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
