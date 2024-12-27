<?php

// app/Services/PdfService.php
namespace App\Services;

use Barryvdh\DomPDF\Facade as PDF;

class PdfService
{
    public function generatePdf($data, $view, $filename)
    {
        $pdf = PDF::loadView($view, $data);
        return $pdf->download($filename);
    }
}