<?php
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel {
    
    public function getDataExcel() {
        $reader=IOFactory::createReader("Xlsx");
        $spreadsheet=$reader->load('../publics/xlsx/matrice.xlsx');
        $writer=IOFactory::createWriter($spreadsheet, "Html");
        return $writer->save("php://output");
    }

    public function addDataExcel() {
        $reader=IOFactory::createReader("Xlsx");
        $spreadsheet=$reader->load('../publics/xlsx/matrice.xlsx');
        $ajouter=$spreadsheet->getActiveSheet();
        
        $ajouter->setCellValue('A2', 'Lahatra');
        $ajouter->setCellValue('B2', 'ESTI');
        $writer=IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save('../publics/xlsx/matrice.xlsx');
        echo 1;
    }
}
