<?php 
require_once 'classes/Funcionario.php';
require_once 'classes/Funcoes.php';
require ('fpdf/fpdf.php');
include_once 'connection.php';

$objFcn = new Funcionario();
$objFcs = new Funcoes();


class PDF extends FPDF
{
// Page header
function Header()
{
$objFcn = new Funcionario();
$objFcs = new Funcoes();
	// Logo
	$this->Image('fpdf/img/logo-.png',10,15,30);

	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Title
	$this->Cell(190,10, $objFcs->tratarCaracter('Apoio às Residências em Saúde', 1),0,0,'C');
	$this ->Ln();
	$this->Cell(190,7,'Lista de Inscritos - 2016',0,0,'C');
	// Line break
	$this->Ln(20);

	// Titulo Tabela
	$this->SetFillColor(220,220,220);
	$this->SetDrawColor(50,50,100);
		
	$this->Ln();
	// Tabela
	 $this->SetFont('Arial', '', 9);
	 $this->Cell(20, 7, $objFcs->tratarCaracter('Inscrição', 1), 1, 0, 'C',true);
	 $this->Cell(100, 7, 'Nome', 1, 0, 'L',true);
	 $this->Cell(30, 7, 'CPF', 1, 0, 'C',true);
	 $this->Cell(40, 7, $objFcs->tratarCaracter('Situação',1), 1, 0, 'L',true);
	 $this->Ln();

}

// Page footer-
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Pag '.$this->PageNo().'/{nb}',0,0,'C');
	// Date
	setlocale(LC_ALL,"pt_BR","pt_BR.utf-8","portuguese");
	date_default_timezone_set('America/sao_paulo');
	$this->Cell(0,0,strftime("%d de %B de %Y, %H:%M"),0,0,'R');
	

}
}
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);


try
{
	$database = new Connection();
    $db = $database->Conectar();
}
catch (PDOException $e)
{
    echo "There is some problem in connection: " . $e->getMessage();
}
	$sql = "SELECT * FROM candidat_caua";
	$i = 0;
	$in = 0;
	$pi = 0;
	//Dados Tabela 
 foreach ($db->query($sql) as $row) {
 	
 	if($row['status'] == "1"){
		$pdf->Cell(20, 7, $row['id'], 1, 0, 'C');
		$pdf->Cell(100, 7, $objFcs->tratarCaracter($row['nome'], 1), 1, 0, 'L');
		$pdf->Cell(30, 7, $row['cpf'], 1, 0, 'C');
		$pdf->Cell(40, 7, "Isento", 1, 0, 'L');
		 $i++;
		$pdf->Ln();
			
	}else if($row['status'] == "3"){
		$pdf->Cell(20, 7, $row['id'], 1, 0, 'C');
		$pdf->Cell(100, 7, $objFcs->tratarCaracter($row['nome'], 1), 1, 0, 'L');
		$pdf->Cell(30, 7, $row['cpf'], 1, 0, 'C');
		$pdf->Cell(40, 7, $objFcs->tratarCaracter('Inscrição Não Paga', 1), 1, 0, 'L');
		 $in++;
		$pdf->Ln();
	
	}else if($row['status'] == "4"){
		$pdf->Cell(20, 7,$row['id'],1,0,'C');
		$pdf->Cell(100, 7, $objFcs->tratarCaracter($row['nome'], 1), 1,0, 'L');
		$pdf->Cell(30, 7,$row['cpf'],1,0,'C');
		$pdf->Cell(40, 7, "Ou Pago ou Isento", 1,0, 'L');
		 $pi++;

		$pdf->Ln();
	}
 }
 	//Tabela Resumo
$pdf->Ln();
	//Titulo tabela Resumo
	$pdf->SetFillColor(220,220,220);
	$pdf->SetDrawColor(50,50,100);
	$pdf->Cell(190,7,'Resumo',1,0,'C',true);
$pdf->Ln();
	//Colunas tabela Resumo
	$pdf->Cell(47.5,7,'Isento',1,0,'C');
	$pdf->Cell(47.5,7,'Pagos',1,0,'C');
	$pdf->Cell(47.5,7,'Inscritos',1,0,'C');
	$pdf->Cell(47.5,7,$objFcs->tratarCaracter('Não Pagos', 1),1,0,'C');
$pdf->Ln();
	//Dados tabela Resumo
	$pdf->Cell(47.5,7,$i,1,0,'C');
	$pdf->Cell(47.5,7,$pi,1,0,'C');
	$pdf->Cell(47.5,7,$pi+$i,1,0,'C');
	$pdf->Cell(47.5,7,$in,1,0,'C');
$pdf->Ln();
$pdf->Output();


 ?>