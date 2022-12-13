<?php
//function imprime(){
$lin = 2;
$col = 2;
$alt = 7;

$arr_rubproventos = array();
$arr_desproventos = array();
$arr_qtdproventos = array();
$arr_valproventos = array();

$arr_rubdescontos = array();
$arr_desdescontos = array();
$arr_qtddescontos = array();
$arr_valdescontos = array();

$linhasproventos = $this->linhasproventos;
$linhasdescontos = $this->linhasdescontos;

$total_qtdproventos = 0;
$total_valproventos = 0;

$total_qtddescontos = 0;
$total_valdescontos = 0;

for($i=0; $i<$linhasproventos; $i++){
  $arr_rubproventos[$i] = pg_result($this->resultproventos,$i,"rh27_rubric");
  $arr_desproventos[$i] = pg_result($this->resultproventos,$i,"rh27_descr");
  $arr_qtdproventos[$i] = pg_result($this->resultproventos,$i,"r20_quant");
  $arr_valproventos[$i] = pg_result($this->resultproventos,$i,"r20_valor");

  $total_qtdproventos += $arr_qtdproventos[$i];
  $total_valproventos += $arr_valproventos[$i];
}
for($i=0; $i<$linhasdescontos; $i++){
  $arr_rubdescontos[$i] = pg_result($this->resultdescontos,$i,"rh27_rubric");
  $arr_desdescontos[$i] = pg_result($this->resultdescontos,$i,"rh27_descr");
  $arr_qtddescontos[$i] = pg_result($this->resultdescontos,$i,"r20_quant");
  $arr_valdescontos[$i] = pg_result($this->resultdescontos,$i,"r20_valor");

  $total_qtddescontos += $arr_qtddescontos[$i];
  $total_valdescontos += $arr_valdescontos[$i];
}

//db_rescisao($this);
$totalpaginas = (int)(max($linhasproventos, $linhasdescontos) / 20);
if($totalpaginas < 1){
  $totalpaginas = 1;
}else{
  $totalpaginas ++;
}


$iTotalRegistros = max($linhasproventos, $linhasdescontos);

for ($i=0, $a=1; $i <= $iTotalRegistros; $i++) {



  if($i==0 || $this->objpdf->GetY() > 184){
    $this->objpdf->AliasNbPages();
    $this->objpdf->settopmargin(1);
    $this->objpdf->setfillcolor(245);
    $this->objpdf->AddPage();

    $this->objpdf->rect($col,$lin,206,10);
    $this->objpdf->Setfont('Arial','B',12);
    $this->objpdf->text(70,8,'T E R M O  D E  R E S C I S Ã O');

    $this->objpdf->rect($col,$lin+12,10,27);
    $this->objpdf->rect($col+10,$lin+12,45,9);
    $this->objpdf->rect($col+55,$lin+12,151,9);
    $this->objpdf->rect($col+10,$lin+21,111,9);
    $this->objpdf->rect($col+121,$lin+21,85,9);
    $this->objpdf->rect($col+10,$lin+30,80,9);
    $this->objpdf->rect($col+90,$lin+30,15,9);
    $this->objpdf->rect($col+105,$lin+30,20,9);
    $this->objpdf->rect($col+125,$lin+30,20,9);
    $this->objpdf->rect($col+145,$lin+30,61,9);

    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($col+12,$lin+14,'01 - CNPJ/CEI');
    $this->objpdf->text($col+57,$lin+14,'02 - NOME/RAZÃO SOCIAL');
    $this->objpdf->text($col+12,$lin+23,'03 - Endereço(logradouro, n°, andar, apartamento)');
    $this->objpdf->text($col+123,$lin+23,'04 - Bairro');
    $this->objpdf->text($col+12,$lin+32,'05 - Município');
    $this->objpdf->text($col+92,$lin+32,'06 - UF');
    $this->objpdf->text($col+107,$lin+32,'07 - CEP');
    $this->objpdf->text($col+127,$lin+32,'08 - CNAE');
    $this->objpdf->text($col+147,$lin+32,'09 - CNPJ/CEI Tomador/Obra');

    $this->objpdf->Setfont('Arial','',9);
    $this->objpdf->text($col+12,$lin+18,db_formatar($this->cgcpref,'cnpj'));
    $this->objpdf->text($col+57,$lin+18,$this->prefeitura);
    $this->objpdf->text($col+12,$lin+27,$this->enderpref);
    $this->objpdf->text($col+123,$lin+27,$this->bairropref);
    $this->objpdf->text($col+12,$lin+36,$this->municpref);
    $this->objpdf->text($col+92,$lin+36,$this->ufpref);
    $this->objpdf->text($col+107,$lin+36,db_formatar($this->ceppref,'cep'));
    $this->objpdf->text($col+127,$lin+36,substr(db_formatar($this->cnae,"s","0",6,"e",0),0,5).'-'.substr(db_formatar($this->cnae,"s","0",6,"e",0),5,1));
    $this->objpdf->text($col+147,$lin+36,'');



    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->rect($col,$lin+41,10,36);
    $this->objpdf->rect($col+10,$lin+41,45,9);
    $this->objpdf->rect($col+55,$lin+41,151,9);
    $this->objpdf->rect($col+10,$lin+50,111,9);
    $this->objpdf->rect($col+121,$lin+50,85,9);
    $this->objpdf->rect($col+10,$lin+59,80,9);
    $this->objpdf->rect($col+90,$lin+59,15,9);
    $this->objpdf->rect($col+105,$lin+59,30,9);
    $this->objpdf->rect($col+135,$lin+59,71,9);
    $this->objpdf->rect($col+10,$lin+68,45,9);
    $this->objpdf->rect($col+55,$lin+68,35,9);
    $this->objpdf->rect($col+90,$lin+68,116,9);

    $this->objpdf->text($col+12,$lin+43,'10 - PIS / PASEP');
    $this->objpdf->text($col+57,$lin+43,'11 - Nome');
    $this->objpdf->text($col+12,$lin+52,'12 - Endereço(logradouro, n°, andar, apartamento)');
    $this->objpdf->text($col+123,$lin+52,'13 - Bairro');
    $this->objpdf->text($col+12,$lin+61,'14 - Município');
    $this->objpdf->text($col+92,$lin+61,'15 - UF');
    $this->objpdf->text($col+107,$lin+61,'16 - CEP');
    $this->objpdf->text($col+137,$lin+61,'17 - Carteira de Trabalho(n°,série,UF');
    $this->objpdf->text($col+12,$lin+70,'18 - CPF');
    $this->objpdf->text($col+57,$lin+70,'19 - Data de Nascimento');
    $this->objpdf->text($col+92,$lin+70,'20 - Nome da Mãe');

    $this->objpdf->Setfont('Arial','',9);
    $this->objpdf->text($col+12,$lin+47,$this->pis);
    $this->objpdf->text($col+57,$lin+47,$this->nome);
    $this->objpdf->text($col+12,$lin+56,$this->endereco);
    $this->objpdf->text($col+123,$lin+56,$this->bairro);
    $this->objpdf->text($col+12,$lin+65,$this->munic);
    $this->objpdf->text($col+92,$lin+65,$this->uf);
    $this->objpdf->text($col+107,$lin+65,db_formatar($this->cep,'cep'));
    $this->objpdf->text($col+137,$lin+65,$this->ctps);
    $this->objpdf->text($col+12,$lin+74, db_formatar($this->cpf,'cpf'));
    $this->objpdf->text($col+57,$lin+74, db_formatar($this->nasc,'d'));
    $this->objpdf->text($col+92,$lin+74,$this->mae);



    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->rect($col,$lin+79,10,18);
    $this->objpdf->rect($col+10,$lin+79,40,9);
    $this->objpdf->rect($col+50,$lin+79,60,9);
    $this->objpdf->rect($col+110,$lin+79,45,9);
    $this->objpdf->rect($col+155,$lin+79,51,9);

    $this->objpdf->rect($col+10,$lin+88,100,9);
    $this->objpdf->rect($col+110,$lin+88,30,9);
    $this->objpdf->rect($col+140,$lin+88,30,9);
    $this->objpdf->rect($col+170,$lin+88,36,9);

    $this->objpdf->text($col+12, $lin+81,'21 - Remuneração p/ fins rescisão');
    $this->objpdf->text($col+52, $lin+81,'22 - Data de Admissão');
    $this->objpdf->text($col+112,$lin+81,'23 - Data do Aviso Prévio');
    $this->objpdf->text($col+157,$lin+81,'24 - Data de Afastamento');
    $this->objpdf->text($col+12, $lin+90,'25 - Causa do Afastamento');
    $this->objpdf->text($col+112,$lin+90,'26 - Cód. Afastamento');
    $this->objpdf->text($col+142,$lin+90,'27 - Pensão Alimentícia');
    $this->objpdf->text($col+172,$lin+90,'28 - Categoria do Trabalhador');

    $this->objpdf->Setfont('Arial','',9);
    $this->objpdf->text($col+12, $lin+85,db_formatar($this->mremun,'f'));
    $this->objpdf->text($col+52, $lin+85,db_formatar($this->admiss,'d'));
    $this->objpdf->text($col+112,$lin+85,db_formatar($this->aviso,'d'));
    $this->objpdf->text($col+157,$lin+85,db_formatar($this->recis,'d'));
    $this->objpdf->text($col+12, $lin+94,$this->causa);
    $this->objpdf->text($col+112,$lin+94,$this->cod_afas);
    $this->objpdf->text($col+142,$lin+94,db_formatar($this->pensao,'f'));
    $this->objpdf->text($col+172,$lin+94,$this->categoria);



    $this->objpdf->Setfont('Arial','',10);
    $this->objpdf->rect($col,$lin+99,10,99);
    $this->objpdf->rect($col+10,$lin+99,196,99);
    $this->objpdf->text($col+40,$lin+104,'PROVENTOS');
    $this->objpdf->text($col+150,$lin+104,'DESCONTOS');



    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->rect($col,$lin+200,10,86);
    $this->objpdf->rect($col+10,$lin+200,98,9);
    $this->objpdf->text($col+12,$lin+202,'56 - Local e Data do Recolhimento');
    $this->objpdf->rect($col+108,$lin+200,98,9);
    $this->objpdf->text($col+110,$lin+202,'57 - Carimbo e Assinatura do Empregador ou Proposto');
    $this->objpdf->rect($col+10,$lin+209,98,9);
    $this->objpdf->text($col+12,$lin+211,'58 - Assinatura do Trabalhador');
    $this->objpdf->rect($col+108,$lin+209,98,9);
    $this->objpdf->text($col+110,$lin+211,'59 - Assinatura do Responsável Legal do Trabalhador');
    $this->objpdf->rect($col+10,$lin+218,98,45);
    $this->objpdf->text($col+12,$lin+220,'60 - Homologação');

    $this->objpdf->Setfont('Arial','',8);
    $this->objpdf->rect($col+108,$lin+218,49,45);
    if($this->regime != 1){
      $this->objpdf->text($col+12,$lin+226,'Foi prestado, gratualmente, assistência ao trabalhador, nos termos do ');
      $this->objpdf->text($col+12,$lin+229,'art. 447 parag. 1° da Consolidação das Leis do Trabalho - CLT, sendo ');
      $this->objpdf->text($col+12,$lin+232,'comprovado, neste ato, efetivo pagamento das verbas rescisórias acima ');
      $this->objpdf->text($col+12,$lin+235,'especificadas.');
    }
    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($col+12,$lin+247,'__________________________________________________');
    $this->objpdf->text($col+12,$lin+250,'Local e data');
    $this->objpdf->text($col+12,$lin+259,'__________________________________________________');
    $this->objpdf->text($col+12,$lin+262,'Carimbo e assinatura do assistente');

    $this->objpdf->Setfont('Arial','',6);
    $this->objpdf->text($col+110,$lin+220,'61 - Digital do Trabalhador');
    $this->objpdf->rect($col+157,$lin+218,49,45);
    $this->objpdf->text($col+159,$lin+220,'62 - Digital do Responsável Legal');
    $this->objpdf->rect($col+10,$lin+263,98,23);
    $this->objpdf->text($col+12,$lin+265,'63 - Identificação do Órgao Homologador');
    $this->objpdf->rect($col+108,$lin+263,98,23);
    $this->objpdf->text($col+110,$lin+265,'64 - Recepção Pelo Banco (Data e Carimbo');


    $this->objpdf->text($col,$lin+288,'Órgao   : '.$this->orgao.'-'.$this->descr_orgao);
    $this->objpdf->text($col+100,$lin+288,'Unidade : '.$this->unidade.'-'.$this->descr_unidade);
    $this->objpdf->text($col,$lin+291,'Proj/Ativ. : '.$this->projativ.'-'.$this->descr_projativ);
    $this->objpdf->text($col+100,$lin+291,'Recurso  : '.$this->recurso.'-'.$this->descr_recurso);
    $this->objpdf->text($col,$lin+294,'Competencia : '.$this->mes.'/'.$this->ano);

    if($i>0){
      $this->objpdf->text($col + 18,190,"FOLHA ".$a." DE ".$totalpaginas);
      $a ++;
    }

    $this->objpdf->SetX($col + 25);
    $this->objpdf->SetY($lin + 106);
    $this->objpdf->Setfont('Arial','',5);
    $this->objpdf->SetAligns(array('C','R','L','R','C','R','L','R'));
    $this->objpdf->SetWidths(array(10,15,58,16,10,15,58,16));

  }

  if(!isset($arr_rubproventos[$i])){
    $arr_rubproventos[$i] = "";
    $arr_desproventos[$i] = "";
    $arr_qtdproventos[$i] = "";
    $arr_valproventos[$i] = "";
  }
  if(!isset($arr_rubdescontos[$i])){
    $arr_rubdescontos[$i] = "";
    $arr_desdescontos[$i] = "";
    $arr_qtddescontos[$i] = "";
    $arr_valdescontos[$i] = "";
  }

  $this->objpdf->Row(array(
                           $arr_rubproventos[$i],($arr_rubproventos[$i]!=""?db_formatar($arr_qtdproventos[$i],"f"):""),$arr_desproventos[$i],($arr_rubproventos[$i]!=""?db_formatar($arr_valproventos[$i],"f"):""),
                           $arr_rubdescontos[$i],($arr_rubdescontos[$i]!=""?db_formatar($arr_qtddescontos[$i],"f"):""),$arr_desdescontos[$i],($arr_rubdescontos[$i]!=""?db_formatar($arr_valdescontos[$i],"f"):"")
                          ),3,false,4);

}

if($a > 1){
  $this->objpdf->text($col + 18,190,"FOLHA ".$a." DE ".$totalpaginas);
}

$this->objpdf->SetY(192);
$this->objpdf->SetAligns(array('C','R','R','R','C','R','R','R'));
$this->objpdf->Row(
  array(
    "",
    "",
    "SOMA DOS PROVENTOS",
    db_formatar($total_valproventos,"f"),
    "",
    "",
    "SOMA DOS DESCONTOS",
    db_formatar($total_valdescontos,"f")
  ),
  3,
  false,
  4);
$this->objpdf->Row(array(
                         "", "", "", "",
                         "", "", "TOTAL LÍQUIDO", db_formatar((round($total_valproventos, 2) - round($total_valdescontos, 2)),"f")
                        ),3,false,4);

?>
