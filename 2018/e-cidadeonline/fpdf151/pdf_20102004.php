<?
set_time_limit(0);
if(!defined('DB_BIBLIOT')){

  session_cache_limiter('none');
  session_start();
  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  db_postmemory($HTTP_POST_VARS);
  db_postmemory($HTTP_SERVER_VARS);

  define('FPDF_FONTPATH','font/');
  require('fpdf.php');
}

class PDF extends FPDF {
//|00|//pdf
//|10|//Esta classe � uma extens�o da classe |fpdf| e difere da mesma pelo fato de que nesta  classe 
//|10|//foram alterados os m�todos |header| (cabe�alho da p�gina) de  |footer|  (rodap�)  para   que  
//|10|//atendessem as nossas necessidades, da seguinte maneira:
//|10|//|header|     :    - O logotipo da prefeitura ficou alinhado a esquerda;
//|10|//                  - Os dados da prefeitura tais como: nome,  ender�o,  munic�pio,  telefone,
//|10|//                    email, e site ficaram alinhados a  esquerda,  ao  lado  do  logotipo  da 
//|10|//                    prefeitura;
//|10|//               Contem ainda vari�veis livres para o desenvolvedor as quais  ser�o  impressas
//|10|//               na parte superior direita da tela, s�o elas:
//|10|//                  - head1, head2, head3, head4, head5, head6, head7, head8, head9
//|10|//
//|10|//|footer|     :    - contem dados como:
//|10|//                      - programa que gerou o relat�rio;
//|10|//                      - emissor;
//|10|//                      - exerc�cio;
//|10|//                      - data e hora da emiss�o;
//|10|//		      - n�mero da p�gina.

//Page header
  function Header() {
//#00#//header
//#10#//Este m�todo � usado gerar o cabe�alho da p�gina. � chamado automaticamente por |addPage| e n�o
//#10#//deve ser chamado diretamente pela aplica��o. A implementa��o em FPDF est�  vazia,  ent�o  voc�
//#10#//precisa criar uma subclasse dele para  sobrepor o  m�todo  se  voc�  quiser  um  processamento
//#10#//espec�fico para o cabe�alho.
//#15#//header()	
//#99#//Exemplo:
//#99#//class PDF extends FPDF
//#99#//{
//#99#//  function Header()
//#99#//  {
//#99#//    Seleciona fonte Arial bold 15
//#99#//      $this->SetFont('Arial','B',15);
//#99#//    Move para a direita
//#99#//      $this->Cell(80);
//#99#//    Titulo dentro de uma caixa
//#99#//      $this->Cell(30,10,'Title',1,0,'C');
//#99#//    Quebra de linha
//#99#//      $this->Ln(20);
//#99#//  }
//#99#//}

    global $conn;
	global $result;
	global $url;
	//Dados da institui��o
    $dados = @pg_exec("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
	$url = @pg_result($dados,0,"url");
	$this->SetXY(1,1);
    $this->Image('imagens/files/'.pg_result($dados,0,"logo"),7,3,20);
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
    $Espaco = $this->w - 70 ;
    $this->SetFont('Arial','',8);	
    $this->Text($Espaco,6,@$GLOBALS["head1"]);
    $this->Text($Espaco,9,@$GLOBALS["head2"]);	
    $this->Text($Espaco,12,@$GLOBALS["head3"]);	
    $this->Text($Espaco,15,@$GLOBALS["head4"]);	
    $this->Text($Espaco,18,@$GLOBALS["head5"]);	
    $this->Text($Espaco,21,@$GLOBALS["head6"]);	
    $this->Text($Espaco,24,@$GLOBALS["head7"]);	
    $this->Text($Espaco,27,@$GLOBALS["head8"]);	
    $this->Text($Espaco,30,@$GLOBALS["head9"]);	
    $this->SetY(35);
  }

//Page footer
  function Footer() {
//#00#//footer
//#10#//Este m�todo � usado para criar o rodap� da p�gina. Ele � automaticamente chamado por |addPage|
//#10#//e |close| e n�o deve ser chamado diretamente pela aplica��o. A  implementa��o  em  FPDF  est�
//#10#//vazia, ent�o voc�  deve  criar  uma  subclasse  e  sobrepor  o  m�todo  se  voc�  quiser   um
//#10#//processamento espec�fico.
//#15#//footer()
//#99#//Exemplo:
//#99#//class PDF extends FPDF
//#99#//{
//#99#//  function Footer()
//#99#//  {
//#99#//    Vai para 1.5 cm da borda inferior
//#99#//      $this->SetY(-15);
//#99#//    Seleciona Arial it�lico 8
//#99#//      $this->SetFont('Arial','I',8);
//#99#//    Imprime o n�mero da p�gina centralizado
//#99#//      $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
//#99#//  }
//#99#//}
    
  global $url;
    //Position at 1.5 cm from bottom
    $this->SetFont('Arial','',5);
    $this->text(10,$this->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
    $this->SetFont('Arial','I',8);
    $this->SetY(-10);
    $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
    $nome = substr($nome,strrpos($nome,"/")+1);
    $this->Cell(0,10,$nome.'     Emissor: '.@$GLOBALS["DB_login"].'     Exerc�cio: '.db_getsession("DB_anousu").'    Data: '.date("d-m-Y - H:i:s"),"T",0,'C');
    $this->Cell(0,10,'P�gina '.$this->PageNo().' de {nb}',0,1,'R');

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

//|XX|//
?>
