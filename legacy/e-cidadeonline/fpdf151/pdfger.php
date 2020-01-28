<?
if(!defined('DB_BIBLIOT')){

  session_cache_limiter('none');
  session_start();
  require("../libs/db_stdlib.php");
  require("../libs/db_conecta.php");
  include("../libs/db_sessoes.php");
  include("../libs/db_usuariosonline.php");
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_SERVER_VARS);

  define('FPDF_FONTPATH','font/');
  require('fpdf.php');
}

/*
parse_str(@$HTTP_SERVER_VARS["QUERY_STRING"]);
if(!isset($DB_login)) {
  parse_str(base64_decode(@$HTTP_SERVER_VARS["QUERY_STRING"]));
}
if(isset($DB_NBASE)){
  $DB_BASE = $DB_NBASE;
}
if(!($conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
  echo "Erro(10) ao tentar conectar no servidor.";
  exit;
}
*/
class PDF extends FPDF {
//Page header
  function Header() {
    global $conn;
	global $result;
	global $url;
	//Dados da instituição
    $dados = @db_query("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".@$GLOBALS["DB_instit"]);
	$url = @pg_result($dados,0,"url");
	$this->SetXY(1,1);
    $this->Image('../imagens/files/'.pg_result($dados,0,"logo"),7,3,20);
	//$this->Cell(100,32,"",1);
	$nome = pg_result($dados,0,"nomeinst");
	global $nomeinst;
        $nomeinst = pg_result($dados,0,"nomeinst");
	if(strlen($nome) > 42)
	  $TamFonteNome = 8;
	else
	  $TamFonteNome = 9;	
    $this->SetFont('Arial','BI',$TamFonteNome);
    $this->Text(33,9,$nome);
    $this->SetFont('Arial','I',8);
    $this->Text(33,14,trim(pg_result($dados,0,"ender")));
    $this->Text(33,18,trim(pg_result($dados,0,"munic"))." - ".pg_result($dados,0,"uf"));	
    $this->Text(33,22,trim(pg_result($dados,0,"telef")));
    $this->Text(33,26,trim(pg_result($dados,0,"email")));
    $this->Text(33,30,$url);	
	//parametros
//	$this->SetXY(108,3);
//	$this->SetFillColor(235);
//	$this->Cell(98,29,"",1,0,0,1);
//    $this->Line(178,3,178,32);
//    $this->Line(178,13,206,13);
//    $this->Line(178,22,206,22);
//    $this->Text(180,10,date("d-m-Y"));
//    $this->Text(180,20,date("H:i:s"));
//    $this->Text(180,30,@$GLOBALS["DB_login"]);
//    $this->SetFont('Arial','',6);
//    $this->Text(179,5,"Data:");
//    $this->Text(179,15,"Hora:");
//    $this->Text(179,24,"Login:");
        $Espaco = $this->w - 70 ;
        $this->SetFont('Arial','',8);	
//	$this->Text(140,6,@$GLOBALS["head1"]);
	$this->Text($Espaco,6,@$GLOBALS["head1"]);
	$this->Text($Espaco,9,@$GLOBALS["head2"]);	
	$this->Text($Espaco,12,@$GLOBALS["head3"]);	
	$this->Text($Espaco,15,@$GLOBALS["head4"]);	
	$this->Text($Espaco,18,@$GLOBALS["head5"]);	
	$this->Text($Espaco,21,@$GLOBALS["head6"]);	
	$this->Text($Espaco,24,@$GLOBALS["head7"]);	
	$this->Text($Espaco,27,@$GLOBALS["head8"]);	
	$this->Text($Espaco,30,@$GLOBALS["head9"]);	
	//$this->Ln(25);
	$this->SetY(35);
  }

//Page footer
  function Footer() {
  global $url;
    //Position at 1.5 cm from bottom
    $this->SetFont('Arial','I',8);
    $this->SetY(-10);
//    $this->Text(14,293,$url);	
    $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
    $nome = substr($nome,strrpos($nome,"/")+1);
    $this->Cell(0,10,$nome.'     Emissor: '.@$GLOBALS["DB_login"].'     Exercício: '.db_getsession("DB_anousu").'    Data: '.date("d-m-Y - H:i:s"),"T",0,'C');
    $this->Cell(0,10,'Página '.$this->PageNo().' de {nb}',0,1,'R');

  }

// mudar o angulo do texto
function TextWithDirection($x,$y,$txt,$direction='R')
{
    $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));
    if ($direction=='R')
        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$txt);
    elseif ($direction=='L')
        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$txt);
    elseif ($direction=='U')
        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$txt);
    elseif ($direction=='D')
        $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$txt);
    else
        $s=sprintf('BT %.2f %.2f Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$txt);
    $this->_out($s);
}

// rotacionar o texto
//
//$pdf->SetFont('Arial','',40);
//$pdf->TextWithRotation(50,65,'E Ai Dr.?',45,-45);
//$pdf->SetFontSize(30);
//$pdf->TextWithDirection(110,50,'Belezinha','L');




function TextWithRotation($x,$y,$txt,$txt_angle,$font_angle=0)
{
    $txt=str_replace(')','\\)',str_replace('(','\\(',str_replace('\\','\\\\',$txt)));

    $font_angle+=90+$txt_angle;
    $txt_angle*=M_PI/180;
    $font_angle*=M_PI/180;

    $txt_dx=cos($txt_angle);
    $txt_dy=sin($txt_angle);
    $font_dx=cos($font_angle);
    $font_dy=sin($font_angle);

    $s=sprintf('BT %.2f %.2f %.2f %.2f %.2f %.2f Tm (%s) Tj ET',
             $txt_dx,$txt_dy,$font_dx,$font_dy,
             $x*$this->k,($this->h-$y)*$this->k,$txt);
    $this->_out($s);


}




}

?>
