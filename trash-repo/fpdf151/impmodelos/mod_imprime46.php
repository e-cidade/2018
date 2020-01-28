<?php
// comprovante de rendimentos  
/*
$ano  = $data[0];
$mes  = db_mes($mes);
$data = " $dia de $mes de $ano ";
*/

$this->objpdf->AliasNbPages();
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$this->objpdf->setfillcolor(225);
$this->objpdf->setleftmargin(5);
$pagina = 1;
$xlin = 20;
$xcol = 4;
$alt  = 4;

//Inserindo usuario e data no rodape
$this->objpdf->Setfont('Arial', 'I', 5);
$this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")."  Data: ".date("d/m/Y", db_getsession("DB_datausu"))."");
$this->objpdf->text($xcol +170, $xlin +276, "Comprovante: ".$this->num_comprovante);

$this->objpdf->setfillcolor(255,255,255);
$this->objpdf->roundedrect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
$this->objpdf->setfillcolor(235);
$this->objpdf->Setfont('Arial', 'B', 10);

//$this->objpdf->Image('imagens/brasao_armas1.png', 15, $xlin -17, 15); //.$this->logo
$this->objpdf->Setfont('Arial', 'B', 8);
$this->objpdf->text(40, $xlin -11, 'MINIST�RIO DA FAZENDA');
$this->objpdf->Setfont('Arial', '', 8);
$this->objpdf->text(40, $xlin -8, 'SECRETARIA DA RECEITA FEDERAL');
$this->objpdf->text(130, $xlin -11, 'Comprovante de rendimentos pagos e de reten��o');
$this->objpdf->text(144, $xlin -8, 'de imposto de renda na fonte.');
$this->objpdf->Setfont('Arial', 'B', 8);
$this->objpdf->text(147, $xlin -3, 'ANO CALEND�RIO '.$this->ano);
$this->objpdf->Setfont('Arial', '', 6);

$xlin = $xlin + 10;
/// retangulo dos dados do empenho
$this->objpdf->sety($xlin);
//$this->objpdf->rect($xcol+1, $xlin , $xcol +196, 23);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '1 - FONTE PAGADORA PESSOA JUR�DICA OU PESSOA F�SICA',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 5);
$this->objpdf->cell(100,$alt,'RAZ�O SOCIAL','LRT',0,'L',0);
$this->objpdf->cell(100,$alt,'CNPJ/CPF','LRT',1,'L',0);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(100,$alt,$this->prefeitura,'LBR',0,'L',0);
$this->objpdf->cell(100,$alt,db_formatar($this->cgcpref, 'cnpj'),'LBR',1,'L',0);
$this->objpdf->Setfont('Arial', '', 5);
$this->objpdf->cell(200,$alt, 'NATUREZA DO RENDIMENTO','LRT',1,'L',0);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(200,$alt, 'RENDIMENTOS DE TRABALHO ASSALARIADO','LRB',1,'L',0);


$this->objpdf->ln(5);
//$this->objpdf->rect($xcol+1, $xlin+30 , $xcol +196, 18,1);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '2 - PESSOA F�SICA BENEFICI�RIA DOS RENDIMENTOS',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 5);
$this->objpdf->cell(100,$alt,'CPF','LRT',0,'L',0);
$this->objpdf->cell(100,$alt,'NOME COMPLETO','LRT',1,'L',0);
$this->objpdf->Setfont('Arial', '', 7);
$sFormatar = "cpf";
if (strlen($this->cpf) == 14) {
  $sFormatar = "cnpj";
}
$this->objpdf->cell(100,$alt,db_formatar($this->cpf, $sFormatar),'BLR',0,'L',0);
$this->objpdf->cell(100,$alt,$this->nome,'BLR',1,'L',0);

$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
//$this->objpdf->text($xcol +2, $xlin +60, '3 - RENDIMENTOS TRIBUTAVEIS, DEDUCOES E IMPOSTO RETIDO NA FONTE ');
$this->objpdf->cell(200,5, '3 - RENDIMENTOS TRIBUT�VEIS, DEDU��ES E IMPOSTO RETIDO NA FONTE ',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(160,$alt,'01 - TOTAL DOS RENDIMENTOS (INCLUSIVE F�RIAS)',1,0,'L',0);
$this->objpdf->cell(40, $alt,db_formatar($this->w_salario,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'02 - CONTRIBUI��O PREVID�NCIARIA OFICIAL',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_contr,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'03 - CONTRIBUI��O A PREVID�NCIA PRIVADA',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_privad,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'04 - PENS�O JUDICIAL (INFORME O BENEF�CIO NO CAMPO 06)',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_pensao,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'05 - IMPOSTO RETIDO NA FONTE',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_irfonte,'f'),1,1,'R',0);

$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt,'4 - RENDIMENTOS ISENTOS E N�O TRIBUT�VEIS',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(160,$alt,'01 - PARTE DOS PROVENTOS DE APOSENTADOS (65 ANOS OU MAIS)',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_parte,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'02 - DI�RIAS E AJUDA DE CUSTO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_diaria,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'03 - PENS�O, APOSENT OU REF P/MOLEST GRAVE OU INV PERMANENTE',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_aviso,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'04 - RENDIMENTO/LUCRO DISTRIBU�DO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'05 - VALORES PAGOS AO TITULAR/SOCIO DE MICRO/PEQUENA EMPRESA',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'06 - INDENIZA��ES RESCIS�O CONTRATO, PDV E ACIDENTE TRABALHO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_vlresc_ntrib,'f'),1,1,'R',0);

$outros_especificar = '';

if($this->w_abono > 0 ) {
  $outros_especificar .= ' Abono Pecuni�rio ';
}
if($this->w_outros5 > 0 ) {
  $outros_especificar .= '- outros pagamentos ';
}

$this->objpdf->cell(160,$alt,'07 - OUTROS (ESPECIFICAR) :'.$outros_especificar,1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_abono+$this->w_outros5,'f'),1,1,'R',0);


$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '5 - RENDIMENTOS SUJEITOS A TRIBUTA��O EXCLUSIVA (RENDIMENTO L�QUIDO)',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(160,$alt,'01 - D�CIMO TERCEIRO SAL�RIO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_sal13,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'02 - OUTROS (ESPECIFICAR)',1,0,'L',0);                                                      
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_outros6,'f'),1,1,'R',0);


$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt,'6 - RENDIMENTOS RECEBIDOS ACUMULADAMENTO - Art. 12-A da Lei no.7.713, de 1988 (sujeito a tributa�ao exclusiva)',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(110,$alt,'6.1 - NUMERO DO PROCESSO:',1,0,'L',0);
$this->objpdf->cell(30 ,$alt,'QUANT. DE MESES ',1,0,'R',0);
$this->objpdf->cell(20 ,$alt,db_formatar(0 ,'f') ,1,1,'R',0);
$this->objpdf->cell(160 ,$alt,'NATUREZA DO RENDIMENTO ',1,1,'L',0);
$this->objpdf->cell(160,$alt,'01 - TOTAL DOS RENDIMENTOS TRIBUTAVEIS (INCLUSIVE E FERIAS E DECIMO TERCEIRO SALARIO)',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'02 - EXCLUSAO: DESPESAS COM A ACAO JUDICIAL',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'03 - DEDUCAO: CONTRIBUICAO PREVIDENCIARIA OFICIAL',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'04 - DEDUCAO: PENSAO ALIMENTICIA(PREENCHER TAMBEM O QUADRO 7)',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'05 - IMPOSTO SOBRES A RENDA RETIDO NA FONTE',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'06 - RENDIMENTOS ISENTOS DE PENSAO, PROVENTOS DE APOSENTADORIA OU REFORMA POR MOLESTIA GRAVE','LRT',0,'L',0);
$this->objpdf->cell(40,$alt,'','LRT',1,'L',0);
$this->objpdf->cell(160,$alt,'OU APOSENTADORIA OU REFORMA POR ACIDENTE EM SERVICO','LRB',0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),'LRB',1,'R',0);



$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '7 - INFORMA��ES COMPLEMENTARES)',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$soma_des_med = 0;
if($this->w_dmedic > 0){
  $soma_des_med += 4;
  $this->objpdf->cell(160,$alt,'DESP M�DICAS, PLANOS DE SA�DE E REEMBOLSO P/ EMPREGADOR','LTB',0,'L',0);
  $this->objpdf->cell(40 ,$alt,db_formatar($this->w_dmedic,'f'),'RTB',1,'R',0);
}

if(trim($this->pensionistas) != ''){
  $this->objpdf->multicell(200,$alt,'PENSIONISTAS : '.trim($this->pensionistas),1,'L',0);
}
$this->objpdf->multicell(200,$alt,'',1,'L',0);

$this->objpdf->ln(5);
$this->objpdf->cell(50,$alt,'Matricula  : '.$this->matricula,0,0,'L',0);                                                      
$this->objpdf->cell(30,$alt,'     Lota��o : '.$this->lotacao,0,1,'L',0);                                                      
$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '7 - RESPONS�VEL PELAS INFORMA��ES',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 5);
$this->objpdf->cell(100,$alt,'Nome','LTR',0,'L',0);                                                      
$this->objpdf->cell(30 ,$alt,'Data','LTR',0,'L',0);                                                      
$this->objpdf->cell(70 ,$alt,'Assinatura','LTR',1,'L',0);                                                      
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(100,$alt,$this->resp,'LBR',0,'L',0);                                                      
$this->objpdf->cell(30 ,$alt,date('d/m/Y',DB_getsession("DB_datausu")),'LBR',0,'L',0);                                                      
$this->objpdf->cell(70 ,$alt,'','LBR',1,'L',0);                                                      


?>
