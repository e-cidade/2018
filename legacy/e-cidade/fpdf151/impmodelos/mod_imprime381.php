<?
$classinatura = new cl_assinatura;
$xlin = 20;
$xcol = 4;

/*
echo "impcarne <br>";
echo $this->recursos;
print_r($this->recursos);
exit;
*/

$this->objpdf->setfillcolor(245);
$this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
$this->objpdf->setfillcolor(255, 255, 255);
$this->objpdf->Setfont('Arial', 'B', 10);
$this->objpdf->Image('imagens/files/logo_boleto.png', 15, $xlin -17, 12); //.$this->logo
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text(40, $xlin -14, $this->nomeinst);



$this->objpdf->Setfont('Arial', 'B', 6);
$this->objpdf->text(40, $xlin -10, $this->ender);
$this->objpdf->text(40, $xlin -7, $this->munic);
$this->objpdf->text(40, $xlin -4, $this->telef);
$this->objpdf->text(40, $xlin -1, $this->email);
$this->objpdf->Setfont('Arial', 'B', 16);
$this->objpdf->text(165, $xlin -8, 'SLIP: ' .  db_formatar(pg_result($this->dados, 0, "k17_codigo"), 's', '0', 6, 'e'));


/// retângulo dos dados da transferência
$this->objpdf->rect($xcol, $xlin +2, $xcol +198, 55, 10, 'DF', '1234'); //alt 55
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +7, 'DATA');
$this->objpdf->text($xcol +6, $xlin +11,  db_formatar(pg_result($this->dados, 0, "k17_data"), 'd'));
$this->objpdf->text($xcol +164, $xlin +7, 'VALOR');
$this->objpdf->text($xcol +168, $xlin +11, 'R$');
$this->objpdf->text($xcol +172, $xlin +11, db_formatar(pg_result($this->dados, 0, "k17_valor"), 'f'));
$this->objpdf->text($xcol +2, $xlin +20, 'CGM');
$this->objpdf->text($xcol +6, $xlin +24,  pg_result($this->dados, 0, "z01_numcgm"). '-'. pg_result($this->dados, 0, "z01_nome"));
$this->objpdf->text($xcol +2, $xlin +33, 'DÉBITO');
$this->objpdf->text($xcol +6, $xlin +37, pg_result($this->dados, 0, "k17_debito").'   -   '.pg_result($this->dados, 0, "descr_debito"));
$this->objpdf->text($xcol +2, $xlin +46, 'CRÉDITO');
$this->objpdf->text($xcol +6, $xlin +50, pg_result($this->dados, 0, "k17_credito").'   -   '.pg_result($this->dados, 0, "descr_credito"));


/// retângulo do histórico
// x - y - lartura - altura
$this->objpdf->rect($xcol, $xlin +60, $xcol +198, 25, 10, 'DF', '1234');
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +65, 'HISTÓRICO'); // altura correta
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->text($xcol +6, $xlin +70, pg_result($this->dados, 0, "k17_hist").'  -  '.pg_result($this->dados,0, "descr_hist"));
$this->objpdf->setxy($xcol +6, $xlin +75);
$this->objpdf->multicell(190, 3, pg_result($this->dados, 0, "k17_texto"), 0, "L");


// retangulo dos recursos
$this->objpdf->rect($xcol, $xlin +90, $xcol +198, 65, 10, 'DF', '1234');
$this->objpdf->Setfont('Arial', 'B', 9);
$this->objpdf->text($xcol +2, $xlin +95, 'RECURSOS');

  // escreve os recursos na tela
  $altura_topo = $xlin + 95;
  $listagem = $this->recursos;
  foreach($listagem as  $chave=>$recurso){   
       $altura_topo += 5;
       $sp = split('#',$recurso);
       $this->objpdf->Setfont('Arial','', 9);     
       $this->objpdf->setXY($xcol + 6,$altura_topo);
       
       $this->objpdf->Cell(10,4,$sp[0],'0');        
       $this->objpdf->Cell(100,4,$sp[1],'0');        
       $this->objpdf->Cell(10,4,"R$",'0'); 
       $this->objpdf->Cell(20, 4, db_formatar($sp[2],'f'),'0','0','R'); 

  }

$ass_pref     = $classinatura->assinatura(1000,"","0");
$ass_prefFunc = $classinatura->assinatura(1000,"","1");
$ass_sec      = $classinatura->assinatura(1002,"","0");
$ass_secFunc  = $classinatura->assinatura(1002,"","1");
$ass_tes      = $classinatura->assinatura(1004,"","0");
$ass_tesFunc  = $classinatura->assinatura(1004,"","1");
$ass_cont     = $classinatura->assinatura(1005,"","0");
$ass_contFunc = $classinatura->assinatura(1005,"","1");

/// retângulo dos dados da contabilidade 

$this->objpdf->rect($xcol, $xlin +160, $xcol +198, 40, 10, 'DF', '1234');
$this->objpdf->Setfont('Arial', 'B', 6);

$this->objpdf->text($xcol +12, $xlin +165, 'CONTADORIA GERAL');
//$this->objpdf->text($xcol +16, $xlin +168, 'Empenhado');

$this->objpdf->text($xcol +2, $xlin +180, '_______________________________________');
$this->objpdf->text($xcol +18, $xlin +183, 'Conferido');

$this->objpdf->text( $xcol +2, $xlin +192, '_______________________________________');
$this->objpdf->setXY($xcol +2, $xlin +193);
$this->objpdf->cell(45,3,$ass_cont    ,0,0,"C",0);
$this->objpdf->setXY($xcol +2, $xlin +196);
$this->objpdf->cell(45,3,$ass_contFunc,0,0,"C",0);

//$this->objpdf->text($xcol +18, $xlin +198, 'Contador');

$this->objpdf->line($xcol +50, $xlin +200, $xcol +50, $xlin +160);

$this->objpdf->text($xcol +69, $xlin +165, 'PAGUE-SE');
$this->objpdf->text($xcol +72, $xlin +168, 'Data');

$this->objpdf->text($xcol +58, $xlin +172, '________ /________ /__________');

$this->objpdf->text( $xcol +52, $xlin +180, '_______________________________________');
$this->objpdf->setXY($xcol +52, $xlin +181);
$this->objpdf->cell(45,3,$ass_sec    ,0,0,"C",0);
$this->objpdf->setXY($xcol +52, $xlin +184);
$this->objpdf->cell(45,3,$ass_secFunc,0,0,"C",0);

//$this->objpdf->text($xcol +63, $xlin +186, 'Secretário da Fazenda');

$this->objpdf->text( $xcol +52, $xlin +192, '_______________________________________');
$this->objpdf->setXY($xcol +52, $xlin +193);
$this->objpdf->cell(45,3,$ass_pref    ,0,0,"C",0);
$this->objpdf->setXY($xcol +52, $xlin +196);
$this->objpdf->cell(45,3,$ass_prefFunc,0,0,"C",0);

//$this->objpdf->text($xcol +63, $xlin +198, 'Prefeito Municipal');

$this->objpdf->line($xcol +100, $xlin +200, $xcol +100, $xlin +160);

$this->objpdf->text($xcol +150, $xlin +165, 'TESOURARIA');
$this->objpdf->text($xcol +101, $xlin +170, 'Banco');
$this->objpdf->text($xcol +110, $xlin +173, '_______________________________________________________________________');

$this->objpdf->text($xcol +101, $xlin +180, 'Cheque');
$this->objpdf->text($xcol +110, $xlin +183, '_______________________________________________________________________');

$this->objpdf->text($xcol +101, $xlin +193, 'Data');
$this->objpdf->text($xcol +110, $xlin +195, '________ /________ /__________');

$this->objpdf->text( $xcol +148, $xlin +192, '_______________________________________');
$this->objpdf->setXY($xcol +148, $xlin +193);
$this->objpdf->cell(45,3,$ass_tes    ,0,0,"C",0);
$this->objpdf->setXY($xcol +148, $xlin +196);
$this->objpdf->cell(45,3,$ass_tesFunc,0,0,"C",0);

//$this->objpdf->text($xcol +168, $xlin +198, 'Tesoureiro');

///recibo

$this->objpdf->rect($xcol, $xlin +200, $xcol +198, 55, 10, 'DF', '1234');

$this->objpdf->SetFont('Arial', '', 7);
$this->objpdf->text($xcol +90, $xlin +205, 'R E C I B O');
$this->objpdf->text($xcol +45, $xlin +215, 'RECEBI(EMOS) DA '.$this->nomeinst.', A IMPORTÂNCIA ACIMA ESPECIFICADA.');

$this->objpdf->text($xcol +4, $xlin +235, 'R$_________________________');
$this->objpdf->text($xcol +110, $xlin +235, 'R$_________________________');
$this->objpdf->text($xcol +45, $xlin +235, 'EM ________/________/________', 0, 0, 'C', 0);
$this->objpdf->text($xcol +150, $xlin +235, 'EM ________/________/________', 0, 0, 'C', 0);

$this->objpdf->text($xcol +75, $xlin +245, '_________________________________________', 0, 1, 'C', 0);
$this->objpdf->SetFont('Arial', '', 6);
$this->objpdf->text($xcol +98, $xlin +250, 'CREDOR', 0, 1, 'C', 0);

?>
