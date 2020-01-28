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
$this->objpdf->text(40, $xlin -11, 'MINISTÉRIO DA FAZENDA');
$this->objpdf->Setfont('Arial', '', 8);
$this->objpdf->text(40, $xlin -8, 'SECRETARIA DA RECEITA FEDERAL');
$this->objpdf->text(130, $xlin -11, 'Comprovante de Rendimentos Pagos e de Retenção');
$this->objpdf->text(144, $xlin -8, 'de Imposto de Renda na Fonte');
$this->objpdf->Setfont('Arial', 'B', 8);
$this->objpdf->text(147, $xlin -3, 'ANO CALENDÁRIO '.$this->ano);
$this->objpdf->Setfont('Arial', '', 6);

$xlin = $xlin + 10;
/// retangulo dos dados do empenho
$this->objpdf->sety($xlin);
//$this->objpdf->rect($xcol+1, $xlin , $xcol +196, 23);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '1 - FONTE PAGADORA PESSOA JURÍDICA OU PESSOA FÍSICA',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 5);
$this->objpdf->cell(100,$alt,'RAZÃO SOCIAL','LRT',0,'L',0);
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
$this->objpdf->cell(200,$alt, '2 - PESSOA FÍSICA BENEFICIÁRIA DOS RENDIMENTOS',1,1,'L',1);
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
$this->objpdf->cell(200,5, '3 - RENDIMENTOS TRIBUTÁVEIS, DEDUÇÕES E IMPOSTO RETIDO NA FONTE ',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(160,$alt,'01 - TOTAL DOS RENDIMENTOS (INCLUSIVE FÉRIAS)',1,0,'L',0);
$this->objpdf->cell(40, $alt,db_formatar($this->w_salario,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'02 - CONTRIBUIÇÃO PREVIDÊNCIARIA OFICIAL',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_contr,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'03 - CONTRIBUIÇÃO A PREVIDÊNCIA PRIVADA',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_privad,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'04 - PENSÃO JUDICIAL (INFORME O BENEFÍCIO NO CAMPO 07)',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_pensao,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'05 - IMPOSTO SOBRE A RENDA RETIDO NA FONTE',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_irfonte,'f'),1,1,'R',0);

$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt,'4 - RENDIMENTOS ISENTOS E NÃO TRIBUTÁVEIS',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(160,$alt,'01 - PARTE DOS PROVENTOS DE APOSENTADOS (65 ANOS OU MAIS)',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_parte,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'02 - DIÁRIAS E AJUDAS DE CUSTO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_diaria,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'03 - PENSÃO, APOSENT OU REF P/MOLEST GRAVE OU INV PERMANENTE',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_aviso,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'04 - RENDIMENTO/LUCRO DISTRIBUÍDO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'05 - VALORES PAGOS AO TITULAR/SÓCIO DE MICRO/PEQUENA EMPRESA',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar(0,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'06 - INDENIZAÇÕES RESCISÃO CONTRATO, PDV E ACIDENTE TRABALHO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_vlresc_ntrib,'f'),1,1,'R',0);

$outros_especificar = '';

if($this->w_abono > 0 ) {
  $outros_especificar .= ' Abono Pecuniário ';
}
if($this->w_outros5 > 0 ) {
  $outros_especificar .= '- outros pagamentos ';
}

$this->objpdf->cell(160,$alt,'07 - OUTROS (ESPECIFICAR) :'.$outros_especificar,1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_abono+$this->w_outros5,'f'),1,1,'R',0);

$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '5 - RENDIMENTOS SUJEITOS A TRIBUTAÇÃO EXCLUSIVA (RENDIMENTO LÍQUIDO)',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(160,$alt,'01 - DÉCIMO TERCEIRO SALÁRIO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_sal13,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'02 - IMPOSTO SOBRE RENDA RETIDO NA FONTE SOBRE 13º SALÁRIO',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_irrf13,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'03 - OUTROS',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->w_outros6,'f'),1,1,'R',0);

$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt,'6 - RENDIMENTOS RECEBIDOS ACUMULADAMENTE - Art. 12-A da Lei no.7.713, de 1988 (sujeito a tributação exclusiva)',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(110,$alt,'6.1 - NÚMERO DO PROCESSO:',1,0,'L',0);
$this->objpdf->cell(30 ,$alt,'QUANT. DE MESES ',1,0,'R',0);
$this->objpdf->cell(20 ,$alt,$this->iRRAQuantidadeMeses ,1,1,'R',0);
$this->objpdf->cell(160 ,$alt,'NATUREZA DO RENDIMENTO ',1,1,'L',0);
$this->objpdf->cell(160,$alt,'01 - TOTAL DOS RENDIMENTOS TRIBUTÁVEIS (INCLUSIVE FÉRIAS E DÉCIMO TERCEIRO SALÁRIO)',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->nRRARentimentosTributaveis,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'02 - EXCLUSÃO: DESPESAS COM A AÇÃO JUDICIAL',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->nRRADespesasAcaoJudicial,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'03 - DEDUÇÃO: CONTRIBUIÇÃO PREVIDENCIÁRIA OFICIAL',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->nRRAPrevidencia,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'04 - DEDUÇÃO: PENSÃO ALIMENTÍCIA (PREENCHER TAMBEM O QUADRO 7)',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->nRRAPensao,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'05 - IMPOSTO SOBRE A RENDA RETIDO NA FONTE',1,0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->nRRAIRRF,'f'),1,1,'R',0);
$this->objpdf->cell(160,$alt,'06 - RENDIMENTOS ISENTOS DE PENSÃO, PROVENTOS DE APOSENTADORIA OU REFORMA POR MOLÉSTIA GRAVE','LRT',0,'L',0);
$this->objpdf->cell(40,$alt,'','LRT',1,'L',0);
$this->objpdf->cell(160,$alt,'OU APOSENTADORIA OU REFORMA POR ACIDENTE EM SERVIÇO','LRB',0,'L',0);
$this->objpdf->cell(40 ,$alt,db_formatar($this->nRRARendimentosIsentos,'f'),'LRB',1,'R',0);

$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '7 - INFORMAÇÕES COMPLEMENTARES',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 7);
$soma_des_med = 0;
if($this->w_dmedic > 0){
  $soma_des_med += 4;
  $this->objpdf->cell(160,$alt,'DESP MÉDICAS, PLANOS DE SAÚDE E REEMBOLSO P/ EMPREGADOR','LTB',0,'L',0);
  $this->objpdf->cell(40 ,$alt,db_formatar($this->w_dmedic,'f'),'RTB',1,'R',0);
}

if(trim($this->pensionistas) != ''){
  $this->objpdf->multicell(200,$alt,'PENSIONISTAS : '.trim($this->pensionistas),1,'L',0);
}
$this->objpdf->multicell(200,$alt,'',1,'L',0);

$this->objpdf->ln(5);
$this->objpdf->cell(50,$alt,'Matrícula  : '.$this->matricula,0,0,'L',0);
$this->objpdf->cell(30,$alt,'Lotação : '.$this->lotacao,0,1,'L',0);
$this->objpdf->ln(5);
$this->objpdf->Setfont('Arial', 'B', 7);
$this->objpdf->cell(200,$alt, '8 - RESPONSÁVEL PELAS INFORMAÇÕES',1,1,'L',1);
$this->objpdf->Setfont('Arial', '', 5);
$this->objpdf->cell(100,$alt,'Nome','LTR',0,'L',0);
$this->objpdf->cell(30 ,$alt,'Data','LTR',0,'L',0);
$this->objpdf->cell(70 ,$alt,'Assinatura','LTR',1,'L',0);
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->cell(100,$alt,$this->resp,'LBR',0,'L',0);
$this->objpdf->cell(30 ,$alt,date('d/m/Y',DB_getsession("DB_datausu")),'LBR',0,'L',0);
$this->objpdf->cell(70 ,$alt,'','LBR',1,'L',0);
