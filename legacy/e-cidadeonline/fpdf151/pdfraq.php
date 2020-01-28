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
//|10|//Esta classe é uma extensão da classe |fpdf| e difere da mesma pelo fato de que nesta  classe 
//|10|//foram alterados os métodos |header| (cabeçalho da página) de  |footer|  (rodapé)  para   que  
//|10|//atendessem as nossas necessidades, da seguinte maneira:
//|10|//|header|     :    - O logotipo da prefeitura ficou alinhado a esquerda;
//|10|//                  - Os dados da prefeitura tais como: nome,  enderço,  município,  telefone,
//|10|//                    email, e site ficaram alinhados a  esquerda,  ao  lado  do  logotipo  da 
//|10|//                    prefeitura;
//|10|//               Contem ainda variáveis livres para o desenvolvedor as quais  serão  impressas
//|10|//               na parte superior direita da tela, são elas:
//|10|//                  - head1, head2, head3, head4, head5, head6, head7, head8, head9
//|10|//
//|10|//|footer|     :    - contem dados como:
//|10|//                      - programa que gerou o relatório;
//|10|//                      - emissor;
//|10|//                      - exercício;
//|10|//                      - data e hora da emissão;
//|10|//		      - número da página.

//Page header
  function Header() {
//#00#//header
//#10#//Este método é usado gerar o cabeçalho da página. É chamado automaticamente por |addPage| e não
//#10#//deve ser chamado diretamente pela aplicação. A implementação em FPDF está  vazia,  então  você
//#10#//precisa criar uma subclasse dele para  sobrepor o  método  se  você  quiser  um  processamento
//#10#//específico para o cabeçalho.
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
	//Dados da instituição
   
//   echo ("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
//   $dados = db_query("select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));

    $dados = db_query($conn,"select nomeinst,ender,munic,uf,telef,email,url,logo from db_config where codigo = ".db_getsession("DB_instit"));
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
    $comprim = ($this->w - $this->rMargin - $this->lMargin);
    $this->Text(33,30,$url);	
    $Espaco = $this->w - 80 ;
    $this->SetFont('Arial','',7);
    $margemesquerda = $this->lMargin; 
    $this->setleftmargin($Espaco);
    $this->sety(6);
    $this->setfillcolor(235);
    $this->roundedrect($Espaco - 3,5,75,28,2,'DF','123');
    $this->line(10,33,$comprim,33);
    $this->setfillcolor(255);
    $this->multicell(0,3,@$GLOBALS["head1"],0,1,"J",0);
    $this->multicell(0,3,@$GLOBALS["head2"],0,1,"J",0);	
    $this->multicell(0,3,@$GLOBALS["head3"],0,1,"J",0);	
    $this->multicell(0,3,@$GLOBALS["head4"],0,1,"J",0);	
    $this->multicell(0,3,@$GLOBALS["head5"],0,1,"J",0);	
    $this->multicell(0,3,@$GLOBALS["head6"],0,1,"J",0);	
    $this->multicell(0,3,@$GLOBALS["head7"],0,1,"J",0);	
    $this->multicell(0,3,@$GLOBALS["head8"],0,1,"J",0);	
    $this->multicell(0,3,@$GLOBALS["head9"],0,1,"J",0);	
    $this->setleftmargin($margemesquerda);
    $this->SetY(35);
  }

//Page footer
  function Footer() {
//#00#//footer
//#10#//Este método é usado para criar o rodapé da página. Ele é automaticamente chamado por |addPage|
//#10#//e |close| e não deve ser chamado diretamente pela aplicação. A  implementação  em  FPDF  está
//#10#//vazia, então você  deve  criar  uma  subclasse  e  sobrepor  o  método  se  você  quiser   um
//#10#//processamento específico.
//#15#//footer()
//#99#//Exemplo:
//#99#//class PDF extends FPDF
//#99#//{
//#99#//  function Footer()
//#99#//  {
//#99#//    Vai para 1.5 cm da borda inferior
//#99#//      $this->SetY(-15);
//#99#//    Seleciona Arial itálico 8
//#99#//      $this->SetFont('Arial','I',8);
//#99#//    Imprime o número da página centralizado
//#99#//      $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
//#99#//  }
//#99#//}
    
  global $url;
    //Position at 1.5 cm from bottom
/*    $this->SetFont('Arial','',5);
    $this->text(10,$this->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
    $this->SetFont('Arial','I',8);
    $this->SetY(-10);
    $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
    $nome = substr($nome,strrpos($nome,"/")+1);
    $this->Cell(0,10,$nome.'     Emissor: '.@$GLOBALS["DB_login"].'     Exercício: '.db_getsession("DB_anousu").'    Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"),"T",0,'C');
    $this->Cell(0,10,'Página '.$this->PageNo().' de {nb}',0,1,'R');
*/
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
