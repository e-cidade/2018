<?php

// Para consumos anteriores deve passar um recordset

// Deixar comentado e nao excluir - TESTE
/*
$this->mes              = db_mes(2, 1);
$this->ano              = 2006;
$this->logo             = "imagens/files/logo_boleto.jpg";
$this->logo2            = "imagens/files/logo_boleto.jpg";
$this->nome_orgao       = "Prefeitura";
$this->cnpj             = "90940172/0001-38";
$this->endereco         = "Teste Endereço";
$this->cep              = "96400-400";
$this->municipio        = "Municipio Porto Alegre";
$this->estado           = "RS";
$this->telefone         = "051 - 3028-9170";
$this->email            = "suporte@dbseller.com.br";
$this->nome_usuario     = "Testando usuario";
$this->endereco_usuario = "Endereco usuario";
$this->bairro_usuario   = "Bairro usuario";
$this->codlograd        = "Rua";
$this->numpre        = "1000";
$this->zona             = "Baixa";
$this->natureza         = "Despesa";
$this->quarteirao       = "Teste quarteirao";
$this->categoria        = "Categoria";
$this->economias        = "Pobreza";
$this->area             = "Nenhuma";
$this->hidrometro       = "99999";
$this->leitura_atual    = "5555";
$this->leitura_ant      = "4444";
$this->consumo          = "3333";
$this->num_dias         = "15";
$this->media_dia        = "1111";
$this->abreviatura      = "Daeb";
$this->inscricao        = "123456";
$this->vencimento       = "17/03/2006";
$this->valor_total      = "250";
$this->acrescimo        = "10";
$this->desconto         = "0";
$this->valor_pago       = "250";
$this->numpre    = "0001";
$this->descr1           = "Descricao 1";
$this->valor1           = "150";
$this->descr2           = "Descricao 2";
$this->valor2           = "180";
$this->descr3           = "Descricao 3";
$this->valor3           = "190";
$this->descr4           = "Descricao 4";
$this->valor4           = "210";
$this->descr5           = "Descricao 5";
$this->valor5           = "120";
$this->obs              = "Isto eh um texto muito, muito, muito, muito, longo, longo, longo";

$this->linha_digitavel = "82620000000-6    47780038070-1    30600000000-1    03000583000-2";
$this->codigo_barras   = "82620000000477800380703060000000003000583000";
*/

$this->objpdf->AliasNbPages();
$this->objpdf->AddPage();

//$this->objpdf->Rotate(90);

//$this->objpdf->settopmargin(1);
//$this->objpdf->setleftmargin(4);
//$pagina =  1;
//$xlin   = 58;
//$xcol   = 25;

$this->objpdf->settopmargin(0);
$this->objpdf->setleftmargin(0);
$pagina =  1;
$xlin   = 10;
$xcol   = 0;

// Cabecalho	
$this->objpdf->Setfont('Arial','B',10);
$this->objpdf->text($xcol+112,$xlin+20, db_mes($this->mes,2)."/".db_formatar($this->ano,'s','0',4,'e'));
$this->objpdf->Setfont('Arial','',8);
//$this->objpdf->text(125,$xlin+15,"/".db_formatar($this->ano,'s','0',4,'e'));

//$this->objpdf->Setfont('Arial','B',8);
//$this->objpdf->text(65,$xlin+3,$this->nome_orgao);
//$this->objpdf->Setfont('Arial','',8);
//$this->objpdf->text(65,$xlin+7,$this->cnpj);
//$this->objpdf->text(65,$xlin+11,$this->endereco);
//$this->objpdf->text(65,$xlin+15,$this->cep." - ".$this->municipio." - ".$this->estado);
//$this->objpdf->text(65,$xlin+19,$this->telefone);
//$this->objpdf->text(65,$xlin+23,$this->email);
//////////////////////

// Dados do usuario
$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+2,   $xlin+35, $this->nome_usuario);

$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+2,   $xlin+38, $this->endereco_entrega);
$this->objpdf->text($xcol+2,   $xlin+41, $this->zona_entrega);
$this->objpdf->text($xcol+96,  $xlin+32, "Inscrição:");
$this->objpdf->text($xcol+110, $xlin+32, $this->inscricao);
$this->objpdf->Setfont('Arial','',8);

if(!empty($this->endereco_imovel)) {
	$this->objpdf->text($xcol+2,  $xlin+44, "End. Imovel: ".$this->endereco_imovel);
}


$impcontador  = "L" . str_pad($this->contador_logradouro, 5, "0", STR_PAD_LEFT);
$impcontador .= "/";
$impcontador .= "G" . str_pad($this->contador, 5, "0", STR_PAD_LEFT);

$this->objpdf->Setfont('Arial','B',8);
$this->objpdf->text($xcol+120, $xlin+41, $impcontador);
$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+111, $xlin+44, "Emitido em ".$this->data_emissao);

//////////////////////

// Dados da Unidade
$xb2 = 3; // Eixo X do Bloco 2 do carne

$this->objpdf->Setfont('Arial','',8);

$this->objpdf->text($xcol+28,  $xlin+$xb2+52, $this->codlograd);
$this->objpdf->text($xcol+103, $xlin+$xb2+52, $this->numpre);
$this->objpdf->text($xcol+16,  $xlin+$xb2+55, $this->zona);
$this->objpdf->text($xcol+94,  $xlin+$xb2+55, $this->natureza);
$this->objpdf->text($xcol+20,  $xlin+$xb2+58, $this->quarteirao);
$this->objpdf->text($xcol+95,  $xlin+$xb2+58, $this->categoria);
$this->objpdf->text($xcol+21,  $xlin+$xb2+61, $this->economias);
$this->objpdf->text($xcol+104, $xlin+$xb2+61, $this->area);
//////////////////////

// Consumos
$xb3 = 2; // Eixo X do Bloco 3 do carne

$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+99,  $xlin+$xb3+71, $this->hidrometro);
$this->objpdf->text($xcol+100, $xlin+$xb3+74, $this->leitura_atual);
$this->objpdf->text($xcol+104, $xlin+$xb3+78, $this->leitura_ant);
$this->objpdf->text($xcol+96,  $xlin+$xb3+82, $this->consumo);
$this->objpdf->text($xcol+97,  $xlin+$xb3+85, $this->num_dias);
$this->objpdf->text($xcol+100, $xlin+$xb3+89, $this->media_dia);


$this->objpdf->Setfont('Arial','',6);
$inc_linha = 76;
for($i = 0;$i < pg_numrows($this->resultLeitura);$i++){

	$exerc = pg_result($this->resultLeitura, $i, $this->campo_ano);
	$parc  = pg_result($this->resultLeitura, $i, $this->campo_mes);

	if($exerc == $this->ano && $parc == $this->mes) {
		$this->objpdf->Setfont('Arial','B',6);
	} else {
		$this->objpdf->Setfont('Arial','',6);
	}

	$this->objpdf->text($xcol+1,  $xlin+$inc_linha, substr(db_mes($parc,2),0,3));
	$this->objpdf->text($xcol+7,  $xlin+$inc_linha, substr(pg_result($this->resultLeitura,$i,$this->campo_situacao),0,19));
	$this->objpdf->text($xcol+29, $xlin+$inc_linha, pg_result($this->resultLeitura,$i,$this->campo_leitura));
	$this->objpdf->text($xcol+43, $xlin+$inc_linha, pg_result($this->resultLeitura,$i,$this->campo_consumo));
	$this->objpdf->text($xcol+59, $xlin+$inc_linha, pg_result($this->resultLeitura,$i,$this->campo_excesso));
	$this->objpdf->text($xcol+72, $xlin+$inc_linha, pg_result($this->resultLeitura,$i,$this->campo_dias));
	$inc_linha += 3;
}
$this->objpdf->Setfont('Arial','',8);

//////////////////////

// Descricoes e valores
$xb4 = 5; // Eixo X do Bloco 4 do carne

//$this->objpdf->Setfont('Arial','B',8);
//$this->objpdf->text($xcol+2,$xlin+$xb4+99,"Rec");
//$this->objpdf->text($xcol+10,$xlin+$xb4+99,"Descricao");
//$this->objpdf->text($xcol+38,$xlin+$xb4+99,"Parcela");
//$this->objpdf->text($xcol+50,$xlin+$xb4+99,"       Valor");
//$this->objpdf->text($xcol+65,$xlin+$xb4+99,"Numpre");
//$this->objpdf->Setfont('Arial','',8);

$inc_linha = 107;
$xcol2 = 0;
$this->valor_total = 0;
for($i = 0;$i < pg_numrows($this->resultArrecad);$i++){

	if($inc_linha == 107) {
		$this->objpdf->Setfont('Arial','B',7);
		$this->objpdf->text($xcol2+$xcol+2,$xlin+$xb4+99,"Rec");
		$this->objpdf->text($xcol2+$xcol+9,$xlin+$xb4+99,"Descricao");
		$this->objpdf->text($xcol2+$xcol+33,$xlin+$xb4+99,"Parcela");
		$this->objpdf->text($xcol2+$xcol+45,$xlin+$xb4+99,"       Valor");
		$this->objpdf->text($xcol2+$xcol+59,$xlin+$xb4+99,"Numpre");
		$this->objpdf->Setfont('Arial','',7);
	}
	
	$this->objpdf->text($xcol2+$xcol+2,$xlin+$inc_linha,  pg_result($this->resultArrecad,$i,$this->campo_receit)  );
	$this->objpdf->text($xcol2+$xcol+9,$xlin+$inc_linha, pg_result($this->resultArrecad,$i,$this->campo_recdescr) );

	$parcela = str_pad(pg_result($this->resultArrecad,$i,$this->campo_numpar),3,"0",STR_PAD_LEFT) . "/" .
	           str_pad(pg_result($this->resultArrecad,$i,$this->campo_numtot),3,"0",STR_PAD_LEFT) ;

	$this->objpdf->text($xcol2+$xcol+33,$xlin+$inc_linha,$parcela);

	$valor = str_pad(trim(db_formatar(pg_result($this->resultArrecad,$i,$this->campo_valor),"f")), 10, "*", STR_PAD_LEFT);
	
	$this->objpdf->text($xcol2+$xcol+45,$xlin+$inc_linha, $valor);

	$this->valor_total += pg_result($this->resultArrecad,$i,$this->campo_valor);

	$numpre = str_pad(pg_result($this->resultArrecad,$i,$this->campo_numpre), 8, "0", STR_PAD_LEFT);
	$this->objpdf->text($xcol2+$xcol+59,$xlin+$inc_linha, $numpre);
	
	$inc_linha += 3;

	if($inc_linha > 119) {
		$xcol2 = 73;
		$inc_linha = 107;
	}
}

$this->objpdf->Setfont('Arial','',8);
$this->valor_total = str_pad(trim(db_formatar($this->valor_total,"f")), 10, "*", STR_PAD_LEFT);
$this->acrescimo   = str_pad(trim(db_formatar($this->acrescimo,"f")), 10, "*", STR_PAD_LEFT);
$this->desconto    = str_pad(trim(db_formatar($this->desconto,"f")), 10, "*", STR_PAD_LEFT);

/*
if(strlen($this->descr1) > 0) {
	$this->objpdf->text($xcol+7,$xlin+$xb4+99,$this->descr1);
	$this->objpdf->text($xcol+(strlen($this->descr1))+8,$xlin+$xb4+99,"           R$ ".db_formatar($this->valor1,"f"));
}
if(strlen($this->descr2) > 0) {
	$this->objpdf->text($xcol+78,$xlin+$xb4+99,$this->descr2);
	$this->objpdf->text($xcol+(strlen($this->descr2))+80,$xlin+$xb4+99,"           R$ ".db_formatar($this->valor2,"f"));
}
if(strlen($this->descr3) > 0) {
	$this->objpdf->text($xcol+7,$xlin+$xb4+103,$this->descr3);
	$this->objpdf->text($xcol+(strlen($this->descr3))+8,$xlin+$xb4+103,"           R$ ".db_formatar($this->valor3,"f"));
}
if(strlen($this->descr4) > 0) {
	$this->objpdf->text($xcol+78,$xlin+$xb4+103,$this->descr4);
	$this->objpdf->text($xcol+(strlen($this->descr4))+80,$xlin+$xb4+103,"           R$ ".db_formatar($this->valor4,"f"));
}
if(strlen($this->descr5) > 0) {
	$this->objpdf->text($xcol+7,$xlin+$xb4+107,$this->descr5);
	$this->objpdf->text($xcol+(strlen($this->descr5))+8,$xlin+$xb4+107,"           R$ ".db_formatar($this->valor5,"f"));
}
//////////////////////
*/

// Dados de valores
$xb5 = 6; // Eixo X do Bloco 5 do carne

$this->objpdf->Setfont('Arial','B',10);
$this->objpdf->text($xcol+36,  $xlin+$xb5+124, $this->vencimento);
$this->objpdf->text($xcol+64,  $xlin+$xb5+124, $this->acrescimo);
$this->objpdf->text($xcol+90,  $xlin+$xb5+124, $this->desconto);
$this->objpdf->text($xcol+120, $xlin+$xb5+124, $this->valor_total);
$this->objpdf->Setfont('Arial','',8);

//////////////////////

// Observacao
$this->objpdf->text($xcol+5,$xlin+136, $this->msg1);
$this->objpdf->text($xcol+5,$xlin+139, $this->msg2);
$this->objpdf->text($xcol+5,$xlin+142, $this->msg3);


// Linha digitavel na via do contribuinte
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->Text($xcol,$xlin+152,$this->linha_digitavel);
$this->objpdf->Setfont('Arial','',8);
//////////////////////

// Rodape
$xb6 = 8; // Eixo X do Bloco 6 do carne (rodape)
$this->objpdf->text($xcol+104, $xlin+$xb6+157, $this->numpre);

$this->objpdf->Setfont('Arial','B',10);
$this->objpdf->text($xcol+28,  $xlin+$xb6+162, db_mes($this->mes,2)."/".db_formatar($this->ano,'s','0',4,'e'));
$this->objpdf->text($xcol+62,  $xlin+$xb6+162, $this->inscricao);

// Dados de valores
$this->objpdf->text($xcol+07, $xlin+$xb6+172, $this->vencimento);
$this->objpdf->text($xcol+35, $xlin+$xb6+172, $this->acrescimo);
$this->objpdf->text($xcol+61, $xlin+$xb6+172, $this->desconto);
$this->objpdf->text($xcol+90, $xlin+$xb6+172, $this->valor_total);

// Codigo de Barras

if(empty($this->msg_debconta01)) {
	$this->objpdf->Setfont('Arial','',10);
	$this->objpdf->Text($xcol+10,$xlin+$xb6+179,$this->linha_digitavel);
	$this->objpdf->int25($xcol+10,$xlin+$xb6+181,$this->codigo_barras,15,0.341);
} else {
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->Text($xcol+10,$xlin+$xb6+181,$this->msg_debconta01);
	$this->objpdf->Text($xcol+10,$xlin+$xb6+186,$this->msg_debconta02);
}
		


//////////////////////

?>
