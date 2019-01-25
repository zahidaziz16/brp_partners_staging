<?php

// Include the main TCPDF library (search for installation path).
require_once('tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    // Page footer
    public function my_Footer($input) {
        // Position at 15 mm from bottom
        $this->SetY(-30);
		$this->SetFont('calibri', 'R', 9);
        $this->Cell(0, 0, 'If you have any questions about this price quote, please contact:', 0, 'C', 'C', 0, '', 0, true, 0, false, true, 10, 'M');
		$this->Ln(5);
		$this->SetFont('calibri', 'B', 9);
		$this->Cell(0, 0, $input, 0, 'C', 'C', 0, '', 0, true, 0, false, true, 10, 'M');
	}
}

?>