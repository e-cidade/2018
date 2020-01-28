<?
set_time_limit(0);
if(!defined('DB_BIBLIOT')){

  session_cache_limiter('none');
  if ( !isset($_SESSION) ) {
    session_start();
  }
  require_once modification("libs/db_stdlib.php");
  require_once modification("libs/db_conecta.php");
  include_once modification("libs/db_sessoes.php");

  db_postmemory($_POST);
  db_postmemory($_SERVER);

  define('FPDF_FONTPATH','fpdf151/font/');
  require_once modification("fpdf151/fpdf.php");
  
}

 /**
  * PDFNovo
  *
  * Esta classe é uma extensão da classe |fpdf| e difere da mesma pelo fato de que nesta  classe 
  * foram alterados os métodos |header| (cabeçalho da página) de  |footer|  (rodapé)  para   que  
  * atendessem as nossas necessidades, da seguinte maneira:
  * |header|     :    - O logotipo da prefeitura ficou alinhado a esquerda;
  *                   - Os dados da prefeitura tais como: nome,  enderço,  município,  telefone,
  *                     email, e site ficaram alinhados a  esquerda,  ao  lado  do  logotipo  da 
  *                     prefeitura;
  *                Contem ainda variáveis livres para o desenvolvedor as quais  serão  impressas
  *                na parte superior direita da tela, são elas:
  *                   - head1, head2, head3, head4, head5, head6, head7, head8, head9
  * 
  * |footer|     :    - contem dados como:
  *                       - programa que gerou o relatório;
  *                       - emissor;
  *                       - exercício;
  *                       - data e hora da emissão;
  *           - número da página.
  */
class PDFNovo extends FPDF {

  private $aTableHeaders = array();

  private $oTableTitleHeaders = null;

  private $aHeaders = array();

  private $oQuebra = null;

  private $iHeaderMargin = 0;

  public function setQuebra($sQuebra = '', $iAltura = 0, $sAlinhamento = 'L', $lPreenche = false) {
    $this->oQuebra = new StdClass();

    $this->oQuebra->sQuebra      = $sQuebra;
    $this->oQuebra->iAltura      = $iAltura;
    $this->oQuebra->sAlinhamento = $sAlinhamento;
    $this->oQuebra->lPreenche    = $lPreenche;
  }

  public function removeQuebra() {
    $this->oQuebra = null;
  }

  public function addTableHeader($sTitulo = '', $iTamanho = 0, $iAltura = 0, $sAlinhamento = 'C', $lPreenche = false) {
    $oTableHeader = new StdClass();

    $oTableHeader->sTitulo      = $sTitulo;
    $oTableHeader->iTamanho     = $iTamanho;
    $oTableHeader->iAltura      = $iAltura;
    $oTableHeader->sAlinhamento = $sAlinhamento;
    $oTableHeader->lPreenche    = $lPreenche;

    $this->aTableHeaders[] = $oTableHeader;
  }

  public function addHeader($sHeader = '') {
    $this->aHeaders[] = $sHeader;
  }

  public function setHeaderMargin($iMargin) {
    $this->iHeaderMargin = $iMargin;
  }

  //Page header
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
  function Header() {

    global $conn;
    global $result;
    global $url;
    global $db21_compl;

    //Dados da instituição
    $dados = db_query($conn,"select nomeinst,
                                   db21_compl,
                                   trim(ender)||',
                                   '||trim(cast(numero as text)) as ender,
                                   trim(ender) as rua,
                                   munic,
                                   numero,
                                   uf,
                                   cgc,
                                   telef,
                                   email,
                                   url,
                                   logo 
                            from db_config where codigo = ".db_getsession("DB_instit"));
    $url = @pg_result($dados,0,"url");
    $this->SetXY(1,1);
    $this->Image('imagens/files/'.pg_result($dados,0,'logo'),7,3,20);

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
    $sComplento = substr(trim(pg_result($dados,0,"db21_compl") ),0,20 );
    if ($sComplento != '' || $sComplento != null ) {
      $sComplento = ", ".substr(trim(pg_result($dados,0,"db21_compl") ),0,20 );
    }
    $this->Text(33,14,trim(pg_result($dados,0,"rua")).", ".trim(pg_result($dados,0,"numero")).$sComplento );
    $this->Text(33,18,trim(pg_result($dados,0,"munic"))." - ".pg_result($dados,0,"uf"));  
    $this->Text(33,22,trim(pg_result($dados,0,"telef"))."   -    CNPJ : ".db_formatar(pg_result($dados,0,"cgc"),"cnpj"));
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

    /**
     * Adiciona os headers principais no cabeçalho do relatório
     */
    if (!empty($this->aHeaders)) {
      foreach ($this->aHeaders as $sHeader) {
        $this->multicell(0, 3, $sHeader, 0, 1, "J", 0);
      }
    }    

    $this->setfillcolor(235);

    $this->setleftmargin($margemesquerda);
    $this->SetY(35);

    $this->renderTableHeaders();
  }

  public function removeTableHeaders() { 
    $this->aTableHeaders = array();
  }

  public function addTableTitle($sTitulo = '', $iTamanho = 0, $iAltura = 0, $sAlinhamento = 'C', $lPreenche = false) {

    $oTableTitleHeader = new StdClass();
    $oTableTitleHeader->sTitulo      = $sTitulo;
    $oTableTitleHeader->iTamanho     = $iTamanho;
    $oTableTitleHeader->iAltura      = $iAltura;
    $oTableTitleHeader->sAlinhamento = $sAlinhamento;
    $oTableTitleHeader->lPreenche    = $lPreenche;

    $this->oTableTitleHeaders = $oTableTitleHeader;
  }

  public function renderTableHeaders() {

    $this->setFont('Arial', 'b');

    if (!empty($this->oQuebra)) {

      $iLarguraCelula = 0;

      if (!empty($this->aTableHeaders)) {
        foreach ($this->aTableHeaders as $aColuna) {
          $iLarguraCelula += $aColuna->iTamanho;
        }
      }

      $this->cell( $iLarguraCelula, 
                   $this->oQuebra->iAltura,
                   $this->oQuebra->sQuebra,
                   true,
                   true, 
                   $this->oQuebra->sAlinhamento,
                   $this->oQuebra->lPreenche );
    }
    
    /**
     * Adiciona os headers da tabela ao final do header do cabeçalho
     */
    if (!empty($this->aTableHeaders)) {

      if(!empty($this->oTableTitleHeaders)) {

        $this->cell( $this->oTableTitleHeaders->iTamanho, 
                     $this->oTableTitleHeaders->iAltura, 
                     $this->oTableTitleHeaders->sTitulo, 
                     false, 
                     1,
                     $this->oTableTitleHeaders->sAlinhamento,
                     $this->oTableTitleHeaders->lPreenche );
      }

      $this->setFont('', 'b');

      foreach ($this->aTableHeaders as $iIndice => $aColuna) {
        $this->cell( $aColuna->iTamanho, 
                     $aColuna->iAltura, 
                     $aColuna->sTitulo, 
                     true, 
                     (($iIndice == count($this->aTableHeaders)-1) ? 1 : 0),
                     $aColuna->sAlinhamento,
                     $aColuna->lPreenche );
      }

      $this->setFont('', '');
    }

    /**
     * Adiciona um espaçamento após o header
     */
    if ($this->iHeaderMargin) {
      $this->SetY($this->GetY() + $this->iHeaderMargin);
    }
  }

  //Page footer
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
  function Footer() {

    global $conn;
    global $result; 
    global $url;

    if($this->imprime_rodape == true) {
      
    /*
     * Modificação para exibir o caminho do menu 
     * na base do relatório
     */         
      //$sSqlMenuAcess = "SELECT fc_montamenu(funcao) as menu from db_itensmenu where id_item =".db_getsession("DB_itemmenu_acessado");
      $sSqlMenuAcess = " select trim(modulo.descricao)||'>'||trim(menu.descricao)||'>'||trim(item.descricao) as menu
                           from db_menu 
                          inner join db_itensmenu as modulo on modulo.id_item = db_menu.modulo 
                          inner join db_itensmenu as menu on menu.id_item = db_menu.id_item 
                          inner join db_itensmenu as item on item.id_item = db_menu.id_item_filho 
                          where id_item_filho = ".db_getsession("DB_itemmenu_acessado")." 
                            and modulo = ".db_getsession("DB_modulo");
          
      $rsMenuAcess   = db_query($conn,$sSqlMenuAcess);
      $sMenuAcess    = substr(pg_result($rsMenuAcess, 0, "menu"), 0, 50);
       
      //Position at 1.5 cm from bottom
      $this->SetFont('Arial','',5);
      $this->text(10,$this->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
      $this->SetFont('Arial','I',6);
      $this->SetY(-10);
      $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
      $nome = substr($nome,strrpos($nome,"/")+1);
      $result_nomeusu = db_query($conn, "select nome as nomeusu from db_usuarios where id_usuario =".db_getsession("DB_id_usuario"));
      if (pg_numrows($result_nomeusu)>0){
        $nomeusu = pg_result($result_nomeusu,0,0);
      }
      if (isset($nomeusu)&&$nomeusu!=""){
        $emissor = $nomeusu;
      }else{
        $emissor = @$GLOBALS["DB_login"];
      }
      $this->Cell(0,10,$sMenuAcess. "  ". $nome.'   Emissor: '.substr(ucwords(strtolower($emissor)),0,30).'  Exerc: '.db_getsession("DB_anousu").
                                                '   Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"),"T",0,'L');
      
      $this->Cell(0,10,'Pág '.$this->PageNo().'/{nb}',0,1,'R');
    }
  }

  function TextWithDirection($x, $y, $txt, $direction = 'R') {
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

  function TextWithRotation($x,$y,$txt,$txt_angle,$font_angle=0) {
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
