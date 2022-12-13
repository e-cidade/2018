<?
##Modelo de boletim de estagio probatorio; 
$this->objpdf->AliasNbPages();
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$this->objpdf->setfillcolor(255, 255, 255);
$this->objpdf->Image('imagens/files/'.$this->logo,95,8,15);
$this->objpdf->Setfont('Arial', 'B', 10);
$this->objpdf->setY(30);
$this->objpdf->cell(0,5, $this->prefeitura,0,1,"C");
$this->objpdf->Setfont('Arial', 'b', +9);
$this->objpdf->cell(0,5,"GABINETE DO EXECUTIVO MUNICIPAL",0,1,"C");
$this->objpdf->Setfont('Arial', 'b', 9);
$this->objpdf->setY(40);
$this->objpdf->cell(0,5,'A N E X O  I',0,1,"C");
$this->objpdf->cell(0,5,'AVALIAÇÃO DE DESEMPENHO NO ESTÁGIO PROBÁTORIO',0,1,"C");
$this->objpdf->Setfont('Arial', '', 9);
$this->objpdf->line(10,$this->objpdf->gety(),200,$this->objpdf->gety());
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
$this->objpdf->Setfont('Arial', 'b', 8);
$this->objpdf->cell(70,5,$this->dadosAvaliacao->rh37_descr,0,1);
$this->objpdf->ln();
$this->objpdf->line(10,$this->objpdf->gety(),200,$this->objpdf->gety());
$this->objpdf->cell(0,5,"RESULTADO GERAL DA AVALIAÇÃO",0,1,"C");
$this->objpdf->Setfont('Arial', '', 8);
$this->objpdf->cell(75,5,"Pontos atribuídos aos quesitos constantes do presente Processo avaliatório a saber: ",0,1);
$nTotalPontos = 0;
foreach ($this->aQuesitos as $chave => $valor){
   $this->objpdf->Setfont('Arial', '', 7);
   $this->objpdf->setX(20);
   $this->objpdf->cell(50,5,"{$valor["descricao"]}:",0,0,"R");
   $this->objpdf->Setfont('Arial', 'b', 8);
   $this->objpdf->cell(10,5,$valor["pontos"],0,1,"R");
   $nTotalPontos += $valor["pontos"];
}
$this->objpdf->Setfont('Arial', '', 7);
$this->objpdf->setX(20);
$this->objpdf->cell(50,5,"TOTAL DE PONTOS:","T",0,"R");
$this->objpdf->Setfont('Arial', 'b', 8);
$this->objpdf->cell(10,5,$nTotalPontos,"T",1,"R");
$this->objpdf->line(10,$this->objpdf->gety(),200,$this->objpdf->gety());
$objDoc = new libdocumento("1504");
$objDoc->getParagrafos();
$this->objpdf->ln();
//variaveis para o documento 1504:
$objDoc->db_hora  = date("h"); //hora
$objDoc->db_min   = date("i"); //mes
$h65_data = explode("-",$this->dadosAvaliacao->h65_data);//data do resultado final;
$objDoc->dia_res  = $h65_data[2];//
$objDoc->mes_res  = $h65_data[1];
$objDoc->ano_res  = $h65_data[0];
$objDoc->h65_resultado = null;
switch ($this->dadosAvaliacao->h65_resultado){

   case "A":
     $objDoc->h65_resultado = "Aprovado";
     break;
   case "R":
     $objDoc->h65_resultado = "Reprovado";
     break;
}
$objDoc->h50_lei    = $this->dadosAvaliacao->h50_lei; 
$paragrafos = $objDoc->aParagrafos;
$this->objpdf->Setfont('Arial', '', 9);
foreach ($paragrafos as $objParag){

   $texto =  $objDoc->replaceText($objParag->db02_texto);
   $this->objpdf->multicell(190,4,"        ".$texto,0,"J","15");
}
$this->objpdf->ln();
$this->munic = ucfirst(strtolower($this->munic));
$this->objpdf->Setfont('Arial', '', 10);
$this->objpdf->cell(0,5,"{$this->munic}, {$objDoc->dia_res} de ".ucfirst(db_mes($objDoc->mes_res))." de {$objDoc->ano_res}",0,1,"C");
//assinaturas
$this->objpdf->sety($this->objpdf->h-50);
$this->objpdf->multicell(70,3,str_repeat("_",34)."\nAvaliador",0,"C");
$this->objpdf->ln();
$this->objpdf->multicell(70,3,str_repeat("_",34)."\nAvaliador",0,"C");
$this->objpdf->setxy(120,$this->objpdf->h-50);
$this->objpdf->multicell(70,3,str_repeat("_",34)."\nMembro da Comissão",0,"C");
$this->objpdf->ln();
$this->objpdf->setx(120);
$this->objpdf->multicell(70,3,str_repeat("_",34)."\nMembro da Comissão",0,"C");
$this->objpdf->ln();
$this->objpdf->setx(60);
$this->objpdf->multicell(80,4,str_repeat("_",38)."\n{$this->dadosAvaliacao->z01_nome}",0,"C");




