<?

$this->objpdf->line(2, 148.5, 208, 148.5);
$xlin = 25;
$xcol = 4;
$iNumRows = pg_num_rows($this->dados);
for ($j = 0; $j < $iNumRows; $j++) {

  if ($j > 0) {

    $this->objpdf->addPage();
    $xlin = 25;
    $xcol = 4;
  }
  for ($i = 0; $i < 2; $i ++) {

  $this->objpdf->setfillcolor(245);
  $this->objpdf->roundedrect($xcol -2, $xlin -18, 206, 139.5, 2, 'DF', '1234');
  $this->objpdf->setfillcolor(255, 255, 255);
  $this->objpdf->Setfont('Arial', 'B', 11);
  $this->objpdf->Image('imagens/files/'.$this->logo, 15, $xlin -14, 14);
  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->text(40, $xlin -11, $this->nomeinst);
  $this->objpdf->Setfont('Arial', '', 9);
  $this->objpdf->text(40, $xlin -7, $this->ender);
  $this->objpdf->text(40, $xlin -4, $this->munic);
  $this->objpdf->text(40, $xlin -1, $this->telef);
  $this->objpdf->text(40, $xlin +2, $this->email);
  $this->objpdf->settextcolor(190);
  $this->objpdf->Setfont('Arial', 'B', 30);
  $this->objpdf->text(175.5, $xlin -7.5, 'SLIP');
  $this->objpdf->settextcolor(0, 0, 0);
  $this->objpdf->Setfont('Arial', 'B', 30);
  $this->objpdf->text(175, $xlin -7, 'SLIP');
  $this->objpdf->Setfont('Arial', 'B', 12);
  $this->objpdf->Setfont('Arial', '', 9);
  $xlin += 10;
  $this->objpdf->Roundedrect($xcol +142, $xlin -12, 60, 12, 2, 'DF', '1234');
  $this->objpdf->text($xcol +144, $xlin -8, 'Slip N'.chr(176).' '.db_formatar(pg_result($this->dados, $j, "k17_codigo"), 's', '0', 6, 'e'));
  $this->objpdf->text($xcol +144, $xlin -4, 'Emissao : '.db_formatar(pg_result($this->dados, $j, "k17_data"), 'd'));
  $this->objpdf->Roundedrect($xcol, $xlin +2, 202, 15, 2, 'DF', '1234');
  $this->objpdf->Roundedrect($xcol, $xlin +20, 202, 15, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol +2, $xlin +6, 'Conta Débito (Recebe):');
  $this->objpdf->Setfont('Arial', 'B', 10);
  if (pg_result($this->dados, 0, "k17_debito") != 0) {
    $this->objpdf->text($xcol +10, $xlin +12, pg_result($this->dados, $j, "k17_debito").'   -   '.pg_result($this->dados, $j, "descr_debito"));
  } else {
    $this->objpdf->text($xcol +10, $xlin +12, '______________________________');
  }
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol +2, $xlin +24, 'Conta Crédito (Paga):');
  $this->objpdf->Setfont('Arial', 'B', 10);

  if (pg_result($this->dados, 0, "k17_credito") != 0) {
    $this->objpdf->text($xcol +10, $xlin +30, pg_result($this->dados, $j, "k17_credito").'   -   '.pg_result($this->dados, $j, "descr_credito"));
  } else {
    $this->objpdf->text($xcol +10, $xlin +30, '______________________________');
  }
  $this->objpdf->sety($xlin +28);
  $maiscol = 0;
  $this->objpdf->Roundedrect($xcol, $xlin +37, 202, 45, 2, 'DF', '1234');
  $this->objpdf->SetY($xlin +42);
  $this->objpdf->multicell(0, 5, 'Favorecido    :   '.pg_result($this->dados, $j, "z01_numcgm") .' - '.pg_result($this->dados, $j, "z01_nome"));

  if (USE_PCASP) {
    $this->objpdf->multicell(0, 5, 'Evento           :   '.strtoupper($this->sEvento));
  }

  $this->objpdf->multicell(0, 5, 'Histórico        :   '.pg_result($this->dados, $j, "k17_hist").'  -  '.pg_result($this->dados, $j, "descr_hist"));
  $this->objpdf->cell(20, 5, 'Observações :   ', 0, 1, "L");
  $this->objpdf->Setfont('Arial', '', 8);
  $sTextoObservacao = pg_result($this->dados, $j, "k17_texto");
  $sTextoObservacao = str_replace('\n', "\n", $sTextoObservacao);
  if (strlen($sTextoObservacao) > 700) {
    $sTextoObservacao = substr(str_repeat("1", 900), 0, 700)." ...";
  }
  $this->objpdf->multicell(0, 4, $sTextoObservacao);
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

  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->Roundedrect($xcol, $xlin +84, 202, 20, 2, 'DF', '1234');
  $this->objpdf->text($xcol +2, $xlin +87, 'Valor');
  $this->objpdf->Setfont('Arial', 'B', 10);
  $this->objpdf->SetY($xlin +89);
  $extenso = db_extenso(pg_result($this->dados, $j, "k17_valor"));
  $this->objpdf->multicell(0, 6, 'R$ '.db_formatar(pg_result($this->dados, $j, "k17_valor"), 'f').'('.$extenso.')');

  //Alterado dia 12/01/2006
  //O emissor aparece ao lado da folha como foi solicitado.
  $this->objpdf->setfillcolor(0, 0, 0);
  $this->objpdf->SetFont('Arial', '', 4);
  $this->objpdf->TextWithDirection(1.5, 80, db_getsession('DB_login').' - '.date("d/m/Y", db_getsession("DB_datausu")).' - '.date('H:m').' - '.db_getsession('DB_anousu').' - '.db_getsession('DB_base'), 'U');
  $this->objpdf->TextWithDirection(1.5, 225, db_getsession('DB_login').' -'.date("d/m/Y", db_getsession("DB_datausu")).' - '.date('H:m').' - '.db_getsession('DB_anousu').' - '.db_getsession('DB_base'), 'U');

  $xlin = 169;
  }
}

$this->lUtilizaModeloDefault = false;

?>
