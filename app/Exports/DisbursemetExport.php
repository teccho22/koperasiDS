<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use DB;

class DisbursemetExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
        $sql = DB::table('ms_loans')
        ->join('ms_outgoings','ms_loans.loan_id', '=', 'ms_outgoings.loan_id')
        ->join('customers','ms_loans.customer_id', '=', 'customers.customer_id');

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

        // DB::connection()->enableQueryLog();

        $disbursement = $sql
                    ->where('customers.is_active', 1)
                    ->where('ms_loans.is_active', 1)
                    ->where('ms_outgoings.is_active', 1)
                    ->select( DB::raw('DATE_FORMAT(ms_outgoings.outgoing_date, "%d-%b-%Y") as transaction_date')
                            , 'ms_loans.loan_number'
                            , 'ms_loans.customer_id'
                            , 'customers.customer_name'
                            , 'customers.customer_proffesion'
                            , 'customers.customer_address'
                            , DB::raw('(CASE WHEN ms_loans.collateral_description IS NULL THEN IFNULL(ms_loans.collateral_category, "") ELSE ms_loans.collateral_description END) AS collateral')
                            , 'ms_loans.loan_amount'
                            , 'ms_loans.installment_amount'
                            , 'ms_loans.tenor'
                    )
                    ->get();
        // dd(sizeof($disbursement));
        // dd(sizeof($disbursement->items));
        $this->totalRow = sizeof($disbursement);
        
        // $queries = DB::getQueryLog();

        return collect($disbursement);
    }

    public function headings(): array
    {
        return ["Transaction Date", "Loan Number", "Customer Id", "Customer Name", "Job", "Address", "Collateral", "Loan Amount", "Installment Amount", "Tenor"];
    }

    public function registerEvents(): array
    {
        // $alphabetRange = range('A', 'Z');
        // $alphabet = $alphabetRange[$this->totalValue+6]; // returns Alphabet

        // $totalRow       = (count($this->attributeSets) * 3) + count($this->allItems)+1;
        // $cellRange1      = 'A1:'.$alphabet.$totalRow;

        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:J1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12)->setBold(true);

                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '#000000'],
                        ]
                    ]
                ];

                $cellRange = 'A1:J' .(string)($this->totalRow +1); // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
}
