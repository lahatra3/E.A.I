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

    public function addDataExcel(string $nomEtudiant, string $esEtudiant, string $promotionsEtudiant,
     string $foyerEtudiant, string $nomFichier, string $nombrePage, string $nombreExemplaire, 
     string $qualiteDocument, string $reliure, string $descriptions) {
        $reader=IOFactory::createReader("Xlsx");
        $spreadsheet=$reader->load('../publics/xlsx/matrice.xlsx');
        $ajouter=$spreadsheet->getActiveSheet();
        
        $ajouter->setCellValue('A2', $nomEtudiant);
        $ajouter->setCellValue('B2', $esEtudiant);
        $ajouter->setCellValue('C2', $promotionsEtudiant);
        $ajouter->setCellValue('D2', $foyerEtudiant);
        $ajouter->setCellValue('E2', $nomFichier);
        $ajouter->setCellValue('F2', $nombrePage);
        $ajouter->setCellValue('G2', $nombreExemplaire);
        $ajouter->setCellValue('H2', $qualiteDocument);
        $ajouter->setCellValue('I2', $reliure);
        $ajouter->setCellValue('J2', $descriptions);

        $writer=IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save('../publics/xlsx/matrice.xlsx');
        echo 1;
    }
}
