<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Shuchkin\SimpleXLSX;
use Illuminate\Support\Arr;
use Shuchkin\SimpleXLSXGen;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Session\Session;

class ImportController extends Controller
{
    public function import(Request $request)
    {
        // $this->exportStatement();

        $request->validate([
            'files.*' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        $data = [];
        $file = $request->file('files');
        if ($xlsx = SimpleXLSX::parse($file->getRealPath())) {
            $rows = $xlsx->rows();
            $data[] = [
                'filename' => $file->getClientOriginalName(),
                'rows' => $rows
            ];
            $rows = array_values(Arr::except($rows, 0));

            $excelData = [];

            foreach ($rows as $row) {
                $excelData[$row[1]][] = [
                    'customer_name' => $row[2],
                    'assignment' => $row[3],
                    'posting_date' => $row[4],
                    'due_date' => $row[5],
                    'amount' => $row[6],
                    'email' => $row[7]
                ];
            }

            session()->put('excelData', $excelData);

            $generatedFiles = [];  // Store paths of generated files
            foreach ($excelData as $group => $rows) {
                $pdf = Pdf::loadView('pdf-format', compact('rows', 'group'));

                $fileName = $group . '.pdf';  // Customize as needed
                $filePath = storage_path('app/public/pdf/' . $fileName);  // Ensure this path exists
                $pdf->save($filePath);  // Save instead of download
                $generatedFiles[] = $filePath;
            }

            return view('excel.result', compact('data', 'generatedFiles'));
        } else {
            return back()->withErrors(['files' => 'Failed to parse file: ' . $file->getClientOriginalName()]);
        }
    }

    public function exportStatement()
    {
        $payload = session()->get('excelData');

        $header = $payload['header_and_footer'];
        $body = $payload['body'];
        $excelHeader = [
            [],
            ['Company:', 'CJ PHILIPPINES, INC.', '', '', '', '', '', 'Date:', '<u>     ' . date('F d, Y') . '     </u>'],
            ['Plant:', 'BUKIDNON'],
            ['Customer:', $header['customer']],
            [],
            ['<style bgcolor="#FFFF00" color="#000"><center>STATEMENT OF ACCOUNT</center></style>'],
            [],
            ['Invoice Date', 'Due Date', 'Invoice', 'Invoice Amount', 'Less: Wtax', 'Payment History', '', '', 'Balance', 'Remarks'],
            ['', '', '', '', '', 'Date', 'Ref Number', 'Amount', '', ''],
        ];
        $excelBody = [];


        foreach ($body as $item) {
            $excelBody[] = [
                Carbon::parse($item['invoice_date'])->format('m/d/Y'),  // Format invoice_date
                Carbon::parse($item['due_date'])->format('m/d/Y'),      // Format due_date
                $item['invoice'],
                number_format($item['invoice_amount'], 2),
                '',
                '',
                '',
                '',
                number_format($item['invoice_amount'], 2),
                ''
            ];
        }

        $excelFooter = [
            [],
            ['Total', '', '', number_format($header['total']), '', '', '', '', number_format($header['total']), ''],
            [],
            ['Prepared By:', '', '', '', '', '', '', '', 'Confirmed By:'],
            ['JOAN ILLISCUPIDEZ', 'CJ PHILIPPINES, INC.', '', '', '', '', '', 'Printed Name over Signature:', ''],
            ['', '', '', '', '', '', '', 'Position / Relation to Dealer:', ''],
            ['', '', '', '', '', '', '', 'Date Confirmed:', ''],
        ];

        $excelData = array_merge($excelHeader, $excelBody, $excelFooter);

        SimpleXLSXGen::fromArray($excelData)
            ->mergeCells('A6:J6')
            ->downloadAs(uniqid() . '.xlsx');

        return view('excel.result', compact('data'));
    }
}
