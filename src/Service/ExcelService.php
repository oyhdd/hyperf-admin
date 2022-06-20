<?php

declare (strict_types=1);
namespace Oyhdd\Admin\Service;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ExcelService
{
    private $sheet;
    private $spreadsheet;
    private $row;

    public function __construct()
    {
        // Create new Spreadsheet object
        $this->spreadsheet = new Spreadsheet();
        // Set document properties
        $this->spreadsheet->getProperties()->setCreator('Maarten Balliauw')
            ->setLastModifiedBy('Maarten Balliauw')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('Test result file');
        // Add some data
        $this->spreadsheet->setActiveSheetIndex(0);
        $this->sheet = $this->spreadsheet->getActiveSheet();
        // Rename worksheet
        $this->spreadsheet->getActiveSheet()->setTitle('Sheet1');
    }

    // Set Header
    public function setHeader(array $header)
    {
        foreach ($header as $key => $item) {
            $this->sheet->setCellValue(chr($key + 65) . '1', $item);
        }
        $this->row = 2;
        return $this;
    }
 
    // Add Content
    public function addData(array $data)
    {
        foreach ($data as $item) {
            $dataCol = 'A';
            foreach ($item as $value) {
                $this->sheet->setCellValue($dataCol . $this->row, $value);
                $dataCol++;
            }
            $this->row++;
        }
        return $this;
    }

    public function saveToLocal(string $fileName)
    {
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->spreadsheet->setActiveSheetIndex(0);
 
        $fileName = $fileName . '.xlsx';
        $url = '/storage/' . $fileName;
        $outFilename = BASE_PATH . $url;
        $writer = IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->save($outFilename);
        $this->spreadsheet->disconnectWorksheets();
        unset($this->spreadsheet);
        return ['path' => $outFilename, 'filename' => $fileName];
    }

    public function saveToBrowserByTmp(string $fileName)
    {
        $fileName = $fileName . '.xlsx';
        $writer = IOFactory::createWriter($this->spreadsheet, "Xlsx");
        $tmpPath = './' . $fileName;
        $writer->save($tmpPath);
 
        $content = file_get_contents($tmpPath);
        unlink($tmpPath);
 
        $response = new Response();
        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
 
        return $response->withHeader('content-description', 'File Transfer')
            ->withHeader('content-type', $contentType)
            ->withHeader('content-disposition', "attachment; filename={$fileName}")
            ->withHeader('content-transfer-encoding', 'binary')
            ->withHeader('pragma', 'public')
            ->withBody(new SwooleStream((string)$content));
    }
}
