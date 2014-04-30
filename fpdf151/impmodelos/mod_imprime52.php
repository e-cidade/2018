<?
##Modelo de boletim de estagio probatorio; 
$this->objpdf->AliasNbPages();
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$this->objpdf->setfillcolor(255, 255, 255);
$this->objpdf->Image('imagens/files/'.$this->logo,140,8,15);
$this->objpdf->Setfont('Arial', 'B', 12);
$this->objpdf->setY(30);
$this->objpdf->cell(0,5, $this->prefeitura,0,1,"C");
$this->objpdf->Setfont('Arial', 'b', 10);
$this->objpdf->cell(0,5,"GABINETE DO EXECUTIVO MUNICIPAL",0,1,"C");
$this->objpdf->Setfont('Arial', 'b', 9);
$this->objpdf->setY(40);
$this->objpdf->cell(0,5,'A N E X O   III',0,1,"C");
$this->objpdf->cell(0,5,'COMISSÃO ESPECIAL DE AVALIAÇÃO DE DESEMPENHO NO ESTÁGIO PROBÁTORIO',0,1,"C");
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->line(10,$this->objpdf->gety(),270,$this->objpdf->gety());
$this->objpdf->ln();
$this->objpdf->cell(35,5,'Nome do Funcionário:',0,0,"L");
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->cell(70,5,$this->dadosAvaliacao->z01_nome,0,1,"L");
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->cell(15,5,'Matrícula:',0,0,"L");
$this->objpdf->Setfont('Arial', 'b', 9);
$this->objpdf->cell(20,5,$this->dadosAvaliacao->rh01_regist,0,0,"L");
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->cell(30,5,'Data da Nomeação:',0,0);
$this->objpdf->Setfont('Arial', 'b', 9);
$this->objpdf->cell(40,5,db_formatar($this->dadosAvaliacao->rh01_admiss,"d"),0,0);
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->cell(45,5,'Data do Início do Exercício:',0,0);
$this->objpdf->Setfont('Arial', 'b', 9);
$this->objpdf->cell(25,5,db_formatar($this->dadosAvaliacao->rh01_admiss,"d"),0,1);
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->cell(35,5,'Cargo:',0,0);
$this->objpdf->Setfont('Arial', 'b', 7);
$this->objpdf->cell(70,5,$this->dadosAvaliacao->rh37_descr,0,1);
$this->objpdf->ln();
$this->objpdf->setfillcolor(245);
$this->objpdf->cell(20,5,"AVALIAÇÃO",1,0,"C",1);
$this->objpdf->cell(20,5,"DATA",1,0,"C",1);
foreach ($this->aQuesitos as $chave => $valor){

  $this->objpdf->cell(35,5,$valor,1,0,"C",1);
  $nTotalQuesito[$chave] = 0;
}
$this->objpdf->cell(20,5,'TOTAL',1,0,"C",1);
$this->objpdf->ln();
$dataInicial = db_formatar($this->dadosAvaliacao->rh01_admiss,"d");
$linha = 1;
$this->objpdf->Setfont('Arial', '', 9);
(float)$nTotalAval    = 0;
(float)$nTotal        = 0;
foreach($this->aDadosAvaliacao as $chave => $valor){

  $nTotalAval = 0;
  $dataFinal  = db_formatar($valor["data"],'d'); 
  $sequenciaaval = $valor["seqaval"]; 
  $texto      = "{$dataInicial} a {$dataFinal}";
  $this->objpdf->cell(20,5,$sequenciaaval,"TBR",0,"C");
  $this->objpdf->cell(20,5,$dataFinal,"TBR",0,"C");
  foreach ($this->aQuesitos as $quesito => $descr){

     if (isset($valor["quesito"][$quesito])){
        $this->objpdf->cell(35,5,$valor["quesito"][$quesito],1,0,"C");
        $nTotalAval              += $valor["quesito"][$quesito];
        $nTotalQuesito[$quesito] += $valor["quesito"][$quesito];

     }else{
        $this->objpdf->cell(35,5,'',1,0,"C");
     }
  }
  $nTotal += $nTotalAval;
  $this->objpdf->cell(20,5,$nTotalAval,"TBL",0,"C");
  $this->objpdf->ln();
  $dataInicial = $dataFinal;
}
$this->objpdf->cell(40,5,"Total",1,0,"C",1);
foreach ($this->aQuesitos as $chave => $valor){
  $this->objpdf->cell(35,5,$nTotalQuesito[$chave],1,0,"C",1);
}
$this->objpdf->cell(20,5,$nTotal,1,0,"C",1);
$this->objpdf->Setfont('Arial', '', 10);
$this->objpdf->setY($this->objpdf->getY()+10);
//testamos se existe resultado para essa avaliacao;
if ($this->dadosAvaliacao->h65_sequencial != null){
   
   $this->objpdf->cell(10,4,"Resultado do exame:",0,1);
   if ($this->dadosAvaliacao->h65_resultado == "A"){
      $sResultado = "Aprovado(a).";
   }else{
      $sResultado = "Reprovado(a).";
   }
   $sTexto   = "Foi {$sResultado}, com o total de {$nTotal} pontos, sendo assim, confirmado no cargo acima disposto.\n";
   $sTexto  .= "{$this->dadosAvaliacao->h65_observacao}"; 
   $this->objpdf->multicell(180,4,$sTexto,0,"J"); 
   $dataaux  = explode("-",$this->dadosAvaliacao->h65_data);
   $sMes     = ucfirst(db_mes($dataaux[1]));
   $sData    = "{$this->munic}, {$dataaux[2]} de {$sMes}, de {$dataaux[0]}";
}else{

   $sTexto   = "Boletim parcial."; 
   $this->objpdf->multicell(180,4,$sTexto,0,"J"); 
   $dataaux  = explode("-",date("Y-m-d",db_getsession("DB_datausu")));
   $sMes     = ucfirst(db_mes($dataaux[1]));
   $sData    = "{$this->munic}, {$dataaux[2]} de {$sMes}, de {$dataaux[0]}";
}
$this->objpdf->text(220,$this->objpdf->getY()+10,$sData);
