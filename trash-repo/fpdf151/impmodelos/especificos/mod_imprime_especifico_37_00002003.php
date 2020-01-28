<?
$classinatura = new cl_assinatura;

$xlin = 20;
$xcol = 4;

$this->objpdf->setfillcolor(245);
$this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
$this->objpdf->setfillcolor(255, 255, 255);
$this->objpdf->Setfont('Arial', 'B', 10);
$this->objpdf->Image('imagens/files/'.$this->logo, 15, $xlin -17, 12);
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text(40, $xlin -14, $this->nomeinst);

$this->objpdf->Setfont('Arial', 'B', 6);
$this->objpdf->text(40, $xlin -10, $this->ender);
$this->objpdf->text(40, $xlin -7, $this->munic);
$this->objpdf->text(40, $xlin -4, $this->telef);
$this->objpdf->text(40, $xlin -1, $this->email);
$this->objpdf->Setfont('Arial', 'B', 16);
$this->objpdf->text(165, $xlin -8, 'SLIP: ' .  db_formatar(pg_result($this->dados, 0, "k17_codigo"), 's', '0', 6, 'e'));

if (USE_PCASP) {
  
  $this->objpdf->Setfont('Arial', '', 9);
  
  $y   = $this->objpdf->getY();
  $this->objpdf->setY($y + 5);
  $this->objpdf->cell(190,  4, substr("Evento: " . $this->sEvento, 0, 55),      "",  1, "R", 0);
  $this->objpdf->setY($y);
}

/// retângulo dos dados da transferência

$this->objpdf->rect($xcol, $xlin +2, $xcol +198, 60, 10, 'DF', '1234');
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +7, 'DATA');
$this->objpdf->text($xcol +6, $xlin +11,  db_formatar(pg_result($this->dados, 0, "k17_data"), 'd'));
$this->objpdf->text($xcol +164, $xlin +7, 'VALOR');
$this->objpdf->Setfont('Arial', 'B', 11);
$this->objpdf->text($xcol +168, $xlin +11, 'R$');
$this->objpdf->text($xcol +172, $xlin +11, db_formatar(pg_result($this->dados, 0, "k17_valor"), 'f'));
$this->objpdf->Setfont('Arial', 'B', 9);

$this->objpdf->text($xcol +2, $xlin + 18, 'CGM');
$this->objpdf->text($xcol +6, $xlin + 22,  pg_result($this->dados, 0, "z01_numcgm"). ' - '. pg_result($this->dados, 0, "z01_nome"));

$this->objpdf->text($xcol + 2, $xlin + 43, 'DÉBITO');
$this->objpdf->text($xcol + 6, $xlin + 47, pg_result($this->dados, 0, "k17_debito").'   -   '.pg_result($this->dados, 0, "descr_debito"));
$this->objpdf->text($xcol + 2, $xlin + 56, 'CRÉDITO');
$this->objpdf->text($xcol + 6, $xlin + 60, pg_result($this->dados, 0, "k17_credito").'   -   '.pg_result($this->dados, 0, "descr_credito"));

/// retângulo do histórico

$this->objpdf->rect($xcol, $xlin +80, $xcol +198, 60, 10, 'DF', '1234');
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +85, 'HISTÓRICO');
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->text($xcol +6, $xlin +95, pg_result($this->dados, 0, "k17_hist").'  -  '.pg_result($this->dados,0, "descr_hist"));

$this->objpdf->setxy($xcol +6, $xlin +103);
$this->objpdf->multicell(190, 3, pg_result($this->dados, 0, "k17_texto"), 0, "L");
$this->objpdf->ln(2);
if(pg_result($this->dados,0,"k17_situacao") == 3){
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->multicell(190, 3,"Estornado em ". db_formatar(pg_result($this->dados, 0, "k17_dtestorno"),'d'), 0, "L");
  $this->objpdf->Setfont('Arial', '', 8);
  $motivo = substr((pg_result($this->dados, 0, "k17_motivoestorno")),0,900);
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->multicell(190, 3,"Motivo : ".$motivo, 0, "L"); 
}else if(pg_result($this->dados,0,"k17_situacao") == 4){
  $this->objpdf->Setfont('Arial', 'b', 8);
  $this->objpdf->multicell(190, 3,"Anulado em ".db_formatar(pg_result($this->dados, $j, "k17_dtanu"), 'd'), 0, "L");
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->multicell(190, 3,"Motivo : ".substr(pg_result($this->dados, $j, "k18_motivo"),0,900), 0, "L");
}

$ass_pref     = str_replace( "_", "" ,$classinatura->assinatura(1000,"","0") );
$ass_prefFunc = str_replace( "_", "", $classinatura->assinatura(1000,"","1"));
$ass_sec      = str_replace( "_", "", $classinatura->assinatura(1002,"","0"));
$ass_secFunc  = str_replace( "_", "", $classinatura->assinatura(1002,"","1"));
$ass_tes      = str_replace( "_", "", $classinatura->assinatura(1004,"","0"));
$ass_tesFunc  = str_replace( "_", "", $classinatura->assinatura(1004,"","1"));
$ass_cont     = str_replace( "_", "", $classinatura->assinatura(1005,"","0"));
$ass_contFunc = str_replace( "_", "", $classinatura->assinatura(1005,"","1"));

// retorna a assinatura e o modelo de recibo conforme a configuração do cliente
require_once("libs/db_utils.php");
require_once("libs/db_libdocumento.php");
$oAssinaturas = new libdocumento(1705);
$aParagrafo = $oAssinaturas->getDocParagrafos();

foreach ($aParagrafo as $oParag) {
  if ($oParag->oParag->db02_tipo == 3) {
    $texto = $oParag->oParag->db02_texto;
    eval($texto);
  }    
}

$this->lUtilizaModeloDefault = false;

?>
