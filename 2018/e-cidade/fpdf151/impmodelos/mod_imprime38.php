<?

$xlin = 20;
$xcol = 4;

$this->objpdf->setfillcolor(245);
$this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
$this->objpdf->setfillcolor(255, 255, 255);
$this->objpdf->Setfont('Arial', 'B', 10);
$this->objpdf->Image('imagens/files/'.$this->logo, 15, $xlin -17, 12); 
$this->objpdf->Setfont('Arial', 'B', 9);
//$this->objpdf->text(40, $xlin -15, $this->prefeitura);
$this->objpdf->text(40, $xlin -14, $this->nomeinst);

$this->objpdf->Setfont('Arial', 'B', 6);
$this->objpdf->text(40, $xlin -10, $this->ender);
$this->objpdf->text(40, $xlin -7, $this->munic);
$this->objpdf->text(40, $xlin -4, $this->telef);
$this->objpdf->text(40, $xlin -1, $this->email);
$this->objpdf->Setfont('Arial', 'B', 16);
$this->objpdf->text(165, $xlin -8, 'SLIP: ' .  db_formatar(pg_result($this->dados, 0, "k17_codigo"), 's', '0', 6, 'e'));

/// retângulo dos dados da transferência

$this->objpdf->rect($xcol, $xlin +2, $xcol +198, 60, 10, 'DF', '1234');
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +7, 'DATA');
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->text($xcol +6, $xlin +11,  db_formatar(pg_result($this->dados, 0, "k17_data"), 'd'));
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +164, $xlin +7, 'VALOR');
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->text($xcol +178, $xlin +7, 'R$');
//$extenso = db_extenso(pg_result($this->dados, 0, "k17_valor"));
$this->objpdf->text($xcol +169, $xlin +11, db_formatar(pg_result($this->dados, 0, "k17_valor"), 'f'));
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +20, 'CGM');
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->text($xcol +6, $xlin +24,  pg_result($this->dados, 0, "z01_numcgm"). '-'. pg_result($this->dados, 0, "z01_nome"));
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +33, 'DÉBITO');
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->text($xcol +6, $xlin +37, pg_result($this->dados, 0, "k17_debito").'   -   '.pg_result($this->dados, 0, "descr_debito"));
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +46, 'CRÉDITO');
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->text($xcol +6, $xlin +50, pg_result($this->dados, 0, "k17_credito").'   -   '.pg_result($this->dados, 0, "descr_credito"));

/// retângulo do histórico

$this->objpdf->rect($xcol, $xlin +80, $xcol +198, 60, 10, 'DF', '1234');
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +85, 'HISTÓRICO');
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->text($xcol +6, $xlin +95, pg_result($this->dados, 0, "k17_hist").'  -  '.pg_result($this->dados,0, "descr_hist"));
$this->objpdf->text($xcol +6, $xlin +103, pg_result($this->dados, 0, "k17_texto"));

/// retângulo dos dados da contabilidade 

$this->objpdf->rect($xcol, $xlin +160, $xcol +198, 40, 10, 'DF', '1234');
$this->objpdf->Setfont('Arial', 'B', 6);

$this->objpdf->text($xcol +12, $xlin +165, 'CONTADORIA GERAL');
//$this->objpdf->text($xcol +16, $xlin +168, 'Empenhado');

$this->objpdf->text($xcol +2, $xlin +180, '_______________________________________');
$this->objpdf->text($xcol +18, $xlin +183, 'Conferido');

$this->objpdf->text($xcol +2, $xlin +195, '_______________________________________');
$this->objpdf->text($xcol +18, $xlin +198, 'Contador');

$this->objpdf->line($xcol +50, $xlin +200, $xcol +50, $xlin +160);

$this->objpdf->text($xcol +69, $xlin +165, 'PAGUE-SE');
$this->objpdf->text($xcol +72, $xlin +168, 'Data');

$this->objpdf->text($xcol +58, $xlin +175, '________ /________ /__________');

$this->objpdf->text($xcol +52, $xlin +183, '_______________________________________');
$this->objpdf->text($xcol +63, $xlin +186, 'Secretário da Fazenda');

$this->objpdf->text($xcol +52, $xlin +195, '_______________________________________');
$this->objpdf->text($xcol +65, $xlin +198, 'Prefeito Municipal');
$this->objpdf->line($xcol +100, $xlin +200, $xcol +100, $xlin +160);

$this->objpdf->text($xcol +150, $xlin +165, 'TESOURARIA');
$this->objpdf->text($xcol +101, $xlin +170, 'Banco');
$this->objpdf->text($xcol +110, $xlin +173, '_______________________________________________________________________');

$this->objpdf->text($xcol +101, $xlin +182, 'Cheque');
$this->objpdf->text($xcol +110, $xlin +185, '_______________________________________________________________________');

$this->objpdf->text($xcol +101, $xlin +193, 'Data');
$this->objpdf->text($xcol +110, $xlin +195, '________ /________ /__________');

$this->objpdf->text($xcol +148, $xlin +195, '_______________________________________');
$this->objpdf->text($xcol +168, $xlin +198, 'Tesoureiro');

///recibo

$this->objpdf->rect($xcol, $xlin +200, $xcol +198, 55, 10, 'DF', '1234');

$this->objpdf->SetFont('Arial', '', 7);
$this->objpdf->text($xcol +90, $xlin +205, 'R E C I B O');
$this->objpdf->text($xcol +45, $xlin +215, 'RECEBI(EMOS) DA '.$this->nomeinst.', A IMPORTÂNCIA ACIMA ESPECIFICADA.');
$this->objpdf->text($xcol +20, $xlin +225, '(     ) PARTE DO VALOR EMPENHADO');
$this->objpdf->text($xcol +130, $xlin +225, '(     ) SALDO/TOTAL EMPENHADO');
$this->objpdf->text($xcol +4, $xlin +235, 'R$_________________________');
$this->objpdf->text($xcol +110, $xlin +235, 'R$_________________________');
$this->objpdf->text($xcol +45, $xlin +235, 'EM ________/________/________', 0, 0, 'C', 0);
$this->objpdf->text($xcol+2,$xlin+275,'Nome: _____________________________________________________',0,0,'C',0);
$this->objpdf->text($xcol +150, $xlin +235, 'EM ________/________/________', 0, 0, 'C', 0);

$this->objpdf->text($xcol +10, $xlin +245, '_________________________________________', 0, 1, 'C', 0);
$this->objpdf->SetFont('Arial', '', 6);
$this->objpdf->text($xcol+4,$xlin+260,'',0,0,'C',0);
$this->objpdf->text($xcol +30, $xlin +250, 'CREDOR', 0, 1, 'C', 0);
$this->objpdf->text($xcol +135, $xlin +245, '_________________________________________', 0, 1, 'C', 0);
$this->objpdf->SetFont('Arial', '', 6);
$this->objpdf->text($xcol+62,$xlin+260,'',0,0,'C',0);
$this->objpdf->text($xcol +155, $xlin +250, 'CREDOR', 0, 1, 'C', 0);

?>
