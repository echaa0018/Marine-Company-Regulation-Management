<?php

namespace App\Services;

use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Storage;

class PdfWatermarkService
{
    private $watermarkText;
    
    public function __construct()
    {
        $this->watermarkText = 'Ahoyyy~';
    }

    /**
     * Add watermark to PDF content
     */
    public function addWatermark(string $pdfContent): string
    {
        try {
            // Validate that this is actually a PDF file
            if (!$this->isPdfContent($pdfContent)) {
                logger('PDF Watermark Warning: Content does not appear to be a valid PDF');
                return $pdfContent;
            }

            // Create temporary file for input PDF
            $tempInputPath = tempnam(sys_get_temp_dir(), 'pdf_input_');
            file_put_contents($tempInputPath, $pdfContent);

            // Create new FPDI instance
            $pdf = new Fpdi();
            
            // Get the page count
            $pageCount = $pdf->setSourceFile($tempInputPath);
            
            // Loop through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // Import a page
                $templateId = $pdf->importPage($pageNo);
                
                // Get the size of the template
                $size = $pdf->getTemplateSize($templateId);
                
                // Create a page with the same size
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                
                // Use the imported page as template
                $pdf->useTemplate($templateId);
                
                // Add watermark
                $this->addWatermarkToPage($pdf, $size);
            }
            
            // Clean up temporary file
            unlink($tempInputPath);
            
            // Return the watermarked PDF content
            return $pdf->Output('', 'S');
            
        } catch (\Exception $e) {
            // If watermarking fails, return original content
            logger('PDF Watermark Error: ' . $e->getMessage());
            return $pdfContent;
        }
    }

    /**
     * Check if content is a valid PDF
     */
    private function isPdfContent(string $content): bool
    {
        // Check for PDF header
        return strpos($content, '%PDF-') === 0;
    }

    /**
     * Add watermark to a single page
     */
    private function addWatermarkToPage(Fpdi $pdf, array $size): void
    {
        // Save the current graphics state
        $pdf->StartTransform();
        
        // Set transparency (more visible than before)
        $pdf->SetAlpha(0.3);
        
        // Calculate position for center placement
        $centerX = $size['width'] / 2;
        $centerY = $size['height'] / 2;
        
        // Set font for watermark - make it much larger and bold
        $fontSize = min($size['width'], $size['height']) * 0.25; // Increased from 0.15 to 0.25 for bigger text
        $pdf->SetFont('helvetica', 'B', $fontSize);
        
        // Set text color (darker gray for better visibility)
        $pdf->SetTextColor(120, 120, 120);
        
        // Rotate the text (45 degrees clockwise for diagonal effect)
        $pdf->Rotate(45, $centerX, $centerY);
        
        // Calculate text width to center it properly
        $textWidth = $pdf->GetStringWidth($this->watermarkText);
        $textX = $centerX - ($textWidth / 2);
        
        // Add single centered watermark text
        $pdf->Text($textX, $centerY, $this->watermarkText);
        
        // Restore the graphics state
        $pdf->StopTransform();
    }

    /**
     * Create a watermarked temporary file and return the path
     */
    public function createWatermarkedTempFile(string $pdfContent): string
    {
        $watermarkedContent = $this->addWatermark($pdfContent);
        
        // Create temporary file for watermarked PDF
        $tempPath = tempnam(sys_get_temp_dir(), 'watermarked_pdf_');
        file_put_contents($tempPath, $watermarkedContent);
        
        return $tempPath;
    }

    /**
     * Clean up temporary file
     */
    public function cleanupTempFile(string $filePath): void
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
