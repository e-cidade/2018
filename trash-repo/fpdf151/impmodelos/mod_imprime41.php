<?
$this->objpdf->AliasNbPages();
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->AddPage();

/**
 * Configura a vari�vel NUMERO + ANO
 */
$sNumeroProtocolo = $this->p58_numero."/".$this->p58_ano;

$this->objpdf->sety(20);
$this->objpdf->setx(80);
$this->objpdf->Setfont("Times", "", 10);
$this->objpdf->cell(40, 5, "TELEFONE: ".$this->telefinstit, 0, 0, 'R');

$this->objpdf->SetLeftMargin(15);
$this->objpdf->sety(60);
$this->objpdf->roundedrect(10, 58, 190, 50, 2, 'df', 1234);
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->cell(50, 5, "PROTOCOLO N�: ", 0, 0, 'L');
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->cell(30, 5, $sNumeroProtocolo, 0, 0, 'L');
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->cell(40, 5, "N� CONTROLE: ", 0, 0, 'L');
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->cell(20, 5, $this->p58_codproc, 0, 0, 'L');
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->cell(20, 5, "CGM: ", 0, 0, 'L');
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->cell(75, 5, $this->p58_numcgm, 0, 1, 'L');
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->cell(50, 5, "TITULAR: ", 0, 0, 'L');
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->cell(75, 5, $this->z01_nome, 0, 1, 'L');
if ($this->z01_cgccpf != "") {
	$this->objpdf->Setfont("Times", "B", 14);
	$this->objpdf->cell(50, 5, (strlen($this->z01_cgccpf) == 11 ? "CPF: " : "CNPJ: "), 0, 0, 'L');
	$this->objpdf->Setfont("Times", "", 12);
	$this->objpdf->cell(40, 5, $this->z01_cgccpf, 0, ($this->z01_telef != "" ? 0 : 1), 'L');
}
if ($this->z01_telef != "") {
	$this->objpdf->Setfont("Times", "B", 14);
	$this->objpdf->cell(40, 5, "TELEFONE: ", 0, 0, 'L');
	$this->objpdf->Setfont("Times", "", 12);
	$this->objpdf->cell(30, 5, $this->z01_telef, 0, 1, 'L');
}
if (trim($this->p58_requer) != trim($this->z01_nome)) {
	$this->objpdf->Setfont("Times", "B", 14);
	$this->objpdf->cell(50, 5, "REQUERENTE: ", 0, 0, 'L');
	$this->objpdf->Setfont("Times", "", 12);
	$this->objpdf->cell(75, 5, $this->p58_requer ,0, 1, 'L');
}
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->Cell(50, 5, "ASSUNTO", 0, 0, "L");
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->multicell(100, 5, $this->p51_descr, 0, 1, 'L');
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->cell(50, 5, "LOGRADOURO: ", 0, 0, 'L');
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->cell(75, 5, $this->z01_ender. ($this->z01_numero != "" ? ", " : "").$this->z01_numero . ($this->z01_compl  != "" ? " - " : "").$this->z01_compl, 0, 1, 'L');
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->cell(50, 5, "BAIRRO: ", 0, 0, 'L');
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->cell(75, 5, $this->z01_bairro, 0, 1, 'L');
$this->objpdf->Setfont("Times", "B", 14);
$this->objpdf->cell(50, 5, "MUNIC�PIO: ", 0, 0, 'L');
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->cell(75, 5, $this->z01_munic, 0, 1, 'L');
if ($this->p58_dtproc != "") {
	$this->objpdf->Setfont("Times", "B", 14);
	$this->objpdf->cell(50, 5, ("DATA: "), 0, 0, 'L');
	$this->objpdf->Setfont("Times", "", 12);
	$this->objpdf->cell(75, 5, db_formatar($this->p58_dtproc, 'd'), 0, 1, 'L');
}
$result_impusu = pg_exec("select p90_impusuproc from protparam where p90_instit=".db_getsession("DB_instit"));
if (pg_numrows($result_impusu) > 0) {
	$p90_impusuproc = pg_result($result_impusu,0,0);
	if ($p90_impusuproc == 't') {
		if ($this->nome  != "") {
			$this->objpdf->Setfont("Times", "B", 14);
			$this->objpdf->cell(90, 5, ("USU�RIO QUE CRIOU O PROCESSO: "), 0, 0, 'L');
			$this->objpdf->Setfont("Times", "", 12);
			$this->objpdf->cell(75, 5, $this->nome , 0, 1, 'L');
		}
	}
}
$sqlproc = "select coddepto,descrdepto,p51_descr 
	   		from andpadrao 
				inner join db_depart on coddepto = p53_coddepto 
				inner join tipoproc on p51_codigo = p53_codigo where p53_codigo = ".$this->p58_codigo ."";
$resproc = pg_query($sqlproc);
if (pg_num_rows($resproc)) {
	$coddepto   = pg_result($resproc,0,0);
	$descrdepto = pg_result($resproc,0,1);
	$p51_descr = pg_result($resproc,0,2);
	$this->objpdf->setfillcolor(235);
	$this->objpdf->Setfont("Times", "B", 8);
  $sqldepto = "select p90_impdepto from protparam where p90_instit = ".db_getsession('DB_instit');
	$resultdepto = pg_exec($sqldepto);
	$impdepto = pg_result($resultdepto,0,0);
	if ($impdepto == 't') {
		$this->objpdf->cell(180, 8, "DEPARTAMENTO PADR�O: $coddepto - $descrdepto", 0, 1, 'L', 1);
	}
}
$this->objpdf->Setfont("Times", "", 12);
//alterado o tamanho do campo outros dados
$this->objpdf->roundedrect(10, 110, 190, 35, 2, 'df', 1234);
//
$this->objpdf->setfillcolor(234);
$this->objpdf->roundedrect(10, 110, 190, 5, 2, 'DF', 12);
$this->objpdf->sety(110);
$this->objpdf->cell(180, 5, 'OUTROS DADOS', 0, 1, "C");


$this->objpdf->Setfont("Times", "", 10);


$texto = str_replace("\n",", ",$this->p58_obs);
//echo $texto;

$str = "";
if(strlen($texto) > 430){
	$str = " ...";
}

$this->objpdf->multicell(185, 5,substr($texto,0,430).$str,0,1,"L"); 

// Variaveis
if ($this->result_vars != ""){
     $numrows     = pg_numrows($this->result_vars);
     $result_vars = $this->result_vars;
     $imprime_str = "";
     $separador   = " - ";
     for ($i = 0; $i < $numrows; $i++){
     $rotulo   = pg_result($result_vars,$i,0);
     $conteudo = pg_result($result_vars,$i,1);
     if (($i+1) == $numrows){
          $separador = "";
     }
           $imprime_str .= ucfirst($rotulo).": ".$conteudo.$separador; 
     }

     $this->objpdf->multicell(185, 5, $imprime_str,0,1,"L"); 
}
$this->objpdf->Setfont("Times", "", 12);
//////////////////////////////////////////////////////////////////////////////////////////////////

//-----------------------------------DOCUMENTOS---------------------------------------------------
//alterado para colocar mais para cima.
$this->objpdf->roundedrect(10, 147, 190, 31, 2, 'df', 1234);
$this->objpdf->roundedrect(10, 147, 190, 5, 2, 'DF', 12);
$this->objpdf->sety(147);
//
$this->objpdf->cell(177, 5, 'DOCUMENTOS', 0, 1, "C");
$sql_doc = "select p81_doc,p56_descr from procprocessodoc 
               inner join procdoc on p81_coddoc = p56_coddoc
	        where p81_codproc=".$this->p58_codproc ;
$result_doc = pg_query($sql_doc);
$numrows_doc = pg_numrows($result_doc);
if ($numrows_doc > 0) {
	$m = 0;
	$this->objpdf->cell(180, 2, '', 0, 1, "C");
	for ($y = 0; $y < $numrows_doc; $y ++) {
		$x = " ";
		$this->objpdf->Setfont("Times", "", 10);
		$p81_doc=pg_result($result_doc,$y,0);
		$p56_descr=pg_result($result_doc,$y,1);
		if ($p81_doc == 't') {
			$x = "X";
		}
		$this->objpdf->cell(90, 4, "($x)".substr($p56_descr, 0, 35), 0, $m, "L");
		if ($m == 0) {
			$m = 1;
} else {
			$m = 0;
		}
	}
}
//-------------------------------------------------------------------------------------------------
$this->objpdf->Setfont("Times", "", 12);
$this->objpdf->roundedrect(10, 180, 93, 55, 2, 'df', 1234);
$this->objpdf->roundedrect(10, 180, 93, 7, 2, 'df', 12);
$this->objpdf->roundedrect(107, 180, 93, 55, 2, 'df', 1234);
$this->objpdf->roundedrect(107, 180, 93, 7, 2, 'df', 12);
// comprovante de retirada
$this->objpdf->roundedrect(10, 242, 190, 44, 2, 'df', 1234);
$this->objpdf->roundedrect(10, 242, 190, 5, 2, 'DF', 12);
$this->objpdf->Text(25, 185, "ASSINATURAS");
$this->objpdf->Text(112, 185, "ASSINATURA RETIRADA DE DOCUMENTOS");
$this->objpdf->Text(50, 246, "COMPROVANTE DE ENTRADA DE PROCESSO");
$this->objpdf->Text(13, 192, 'TIT./REQ.:', 0, 35);
$this->objpdf->Text(13, 200, substr($this->p58_requer, 0, 35));
$usuario = pg_exec("select nome from db_usuarios where id_usuario =".db_getsession("DB_id_usuario"));
$nome=pg_result($usuario,0,0);
$this->objpdf->Text(13, 220, 'EMISSOR:', 0, 35);
$this->objpdf->Text(13, 228, $nome, 0, 35);
$this->objpdf->Text(150, 195, "DATA: ____/____/_______");
$this->objpdf->Text(112, 207, "NOME:");
$this->objpdf->Text(112, 220, "CPF/CI:");
// Comprovante de retirada
$this->objpdf->Text(15, 252, "CGM:".$this->p58_numcgm);
$this->objpdf->Text(45, 252, "NOME:".substr($this->z01_nome, 0, 35));
$this->objpdf->Text(150, 252, "CNPJ/CPF:".$this->z01_cgccpf);
$this->objpdf->Text(15, 256, "PROTOCOLO N�/ASSUNTO:".$this->p58_codproc."/".$this->p51_descr);
$this->objpdf->Text(150, 260, "DATA:".$this->dtproc);
$this->objpdf->Text(15, 260, "OUTROS DADOS:");
$this->objpdf->sety(260);


$this->objpdf->Setfont("Times", "", 10);

$imprime_str = "";
$tamstr = 290;

$texto = db_formatatexto(4, 95, $this->p58_obs, "t");
$this->objpdf->multicell(185, 4,$texto,0,1,"L"); 
//$this->objpdf->multicell(185, 4, $imprime_str." ...",0,1,"L"); 

// Variaveis
if ($this->result_vars != ""){
     $numrows     = pg_numrows($this->result_vars);
     $result_vars = $this->result_vars;
     $imprime_str = "";
     $separador   = " - ";
     for ($i = 0; $i < $numrows; $i++){
     $rotulo   = pg_result($result_vars,$i,0);
     $conteudo = pg_result($result_vars,$i,1);
     if (($i+1) == $numrows){
          $separador = "";
     }
           $imprime_str .= ucfirst($rotulo).": ".$conteudo.$separador; 
     }

     $linha = split("-",$imprime_str);
     $this->objpdf->multicell(185, 4, $imprime_str,0,1,"L"); 
} 

$this->objpdf->Setfont("Times", "", 12);

$this->objpdf->Text(15, 285, "ASSINATURA:");
//$this->objpdf->Text(155, 285, "TELEFONE:".$this->telefinstit);
?>