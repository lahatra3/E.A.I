<?php
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel {
    
    public function getDataExcel() {
        $reader=IOFactory::createReader("Xlsx");
        $spreadsheet=$reader->load('../publics/xlsx/matrice.xlsx');
        $writer=IOFactory::createWriter($spreadsheet, "Html");
        print_r($writer->save("php://output"));
    }
}
