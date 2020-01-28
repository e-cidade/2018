<?
$this->objpdf->AliasNbPages();
$this->objpdf->setAutoPageBreak(1,1);
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$this->objpdf->line(2,148.5,208,148.5);

$xlin = 24;
$xcol = 6;

$this->objpdf->setfillcolor(245);
$this->objpdf->roundedrect($xcol-2, $xlin-25,206,185.5,2,'DF','1234');
$this->objpdf->setfillcolor(255,255,255);
$this->objpdf->Setfont('Arial','',11);
$this->objpdf->text(160, $xlin-13,'RECIBO DO SACADO ');
// die($this->dtparapag);
// 2007-01-01
if (substr($this->dtparapag,4,1)=='-' || substr($this->dtparapag,7,1)=='/') {
	$this->dtparapag =  db_formatar($this->dtparapag,'d');	
}
//die($this->dtparapag);
$this->objpdf->Setfont('Arial','',9);
$this->objpdf->text(142, $xlin-8,"DOCUMENTO VÁLIDO ATÉ: ".$this->dtparapag); //$this->descr14); //  $this->datacalc);  COMENTEI ESSA LINHA E ADD A DEBAIXO
//$this->objpdf->text(159, $xlin-8,  $this->descr14); //$this->descr14); //  $this->datacalc);

$str_via = 'Contribuinte';
$this->objpdf->Setfont('Arial','',8);

$this->objpdf->Image('imagens/files/'.$this->logo,15,@$xlin-17,12);
$this->objpdf->Setfont('Arial','',9);
$this->objpdf->text(40, $xlin-15, $this->prefeitura);
$this->objpdf->Setfont('Arial','',9);

$this->objpdf->text(40, $xlin-11,$this->enderpref);
$this->objpdf->text(40, $xlin-8, $this->municpref);
$this->objpdf->text(40, $xlin-5, $this->telefpref);
$this->objpdf->text($xcol+60,$xlin-8,"CNPJ: ");
$this->objpdf->text($xcol+70,$xlin-8,db_formatar($this->cgcpref,'cnpj')); 
$this->objpdf->text(40, $xlin-2, $this->emailpref);

$this->objpdf->Roundedrect(@$xcol+2,@$xlin+2,@$xcol+115,20,2,'DF','1234');

$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text(@$xcol+4,@$xlin+4,'Identificação:');
$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+4,  $xlin+7,  'Nome : ');
$this->objpdf->text($xcol+19, $xlin+7,  $this->descr11_1); //  $this->nome);
$this->objpdf->text($xcol+4,  $xlin+11, 'Endereço : ');
$this->objpdf->text($xcol+19, $xlin+11, $this->descr11_2); //  $this->ender);

$this->objpdf->text($xcol+4,  $xlin+15, 'Bairro : ');
$this->objpdf->text($xcol+19, $xlin+15, $this->bairrocontri);


$this->objpdf->text($xcol+4,  $xlin+19, 'Município : ');
$this->objpdf->text($xcol+19, $xlin+19, $this->munic);
$this->objpdf->text($xcol+75, $xlin+15, 'CEP : ');
$this->objpdf->text($xcol+83, $xlin+15, $this->cep);



$this->objpdf->text($xcol+75, $xlin+19, 'CNPJ/CPF:');
$this->objpdf->text($xcol+90, $xlin+19, db_formatar(@$this->cgccpf,(strlen(@$this->cgccpf)<12?'cpf':'cnpj')));
$this->objpdf->Setfont('Arial','',6);

$this->objpdf->Roundedrect(@$xcol+126,@$xlin+2,70,20,2,'DF','1234');

$this->objpdf->text($xcol+128,  $xlin, 'Data: '. date("d-m-Y",db_getsession("DB_datausu")). ' - Hora: '.date("H:i:s"));


$this->objpdf->text($xcol+128,$xlin+4, $this->identifica_dados);
$this->objpdf->text($xcol+128,$xlin+7, $this->tipoinscr);
$this->objpdf->text($xcol+145,$xlin+7, $this->nrinscr);
$this->objpdf->text($xcol+128,$xlin+11,"Rua : ");
$this->objpdf->text($xcol+145,$xlin+11,$this->nomepriimo);
$this->objpdf->text($xcol+128,$xlin+15,$this->tipocompl);
$this->objpdf->text($xcol+145,$xlin+15,$this->nrpri.(isset($this->complpri)&&$this->complpri!=""?" / ".$this->complpri:"") );
$this->objpdf->text($xcol+128,$xlin+19,"Bairro : ");
$this->objpdf->text($xcol+145,$xlin+19,$this->bairropri);

$this->objpdf->Roundedrect($xcol+2,$xlin+24,194,60,2,'DF','1234'); // quadro das receitas

$this->objpdf->sety($xlin+24);
$maiscol = 0;
$yy = $this->objpdf->gety();
$intnumrows = count($this->arraycodreceitas);

$this->objpdf->setx($xcol+5+$maiscol);
$this->objpdf->cell(6,3,"Rec",0,0,"L",0);
$this->objpdf->cell(7,3,"Reduz",0,0,"L",0);
$this->objpdf->cell(64,3,"Descrição",0,0,"L",0);
$this->objpdf->cell(18,3,"Valor",0,1,"R",0);

$reccol           = $xcol+5;
$reccol2          = $xcol+5;

$bklin            = $xlin+30; //17
$bklin2           = $xlin+30;
$this->totalrec   = 0;
$this->totaldesc  = 0;
$this->totalacres = 0;
for($x=0;$x<$intnumrows;$x++){
  $this->obsdescr = null;
   if($x==50){
     db_redireciona('db_erros.php?fechar=true&db_erro=O numero de receitas ultrapassou o espaço limite do carne.  Contate o suporte!');
     break;
   }
   $this->objpdf->Text($reccol,    $bklin,$this->arraycodreceitas[$x]);
   $this->objpdf->Text($reccol+6,  $bklin,"(".$this->arrayreduzreceitas[$x].")");
   if (@$this->arraycodhist[$x] == 918){
     $this->obsdescr = " (desconto)";
   }
   $this->objpdf->Text($reccol+12, $bklin,$this->arraydescrreceitas[$x].$this->obsdescr);
   //$this->objpdf->Text($reccol+82, $bklin,db_formatar($this->arrayvalreceitas[$x],'f'));
   $this->objpdf->cell($reccol + 85,3,db_formatar($this->arrayvalreceitas[$x],'f'),0,1,"R",0);

   $iFormaCorrecao = pg_result(pg_query("select k03_separajurmulparc
                                           from numpref
                                          where k03_instit = ".db_getsession("DB_instit")."
                                            and k03_anousu = ".db_getsession("DB_anousu")),0,0); 
   if ($iFormaCorrecao == 1) {
     /*
      * Controle da composição 
      * utilizado em Canela
      */
   	  if (@$this->arraycodhist[$x] == 918) {
         $this->totaldesc += $this->arrayvalreceitas[$x]; 
      } else if (@$this->arraycodtipo[$x] == 't' and $this->arrayvalreceitas[$x] > 0 and @$this->arraycodhist[$x] != 918) {
         $this->totalacres += $this->arrayvalreceitas[$x];
      } else {
        $this->totalrec += $this->arrayvalreceitas[$x];
      }
   } else {	
     if ($this->arraycodtipo[$x] == 't' and $this->arrayvalreceitas[$x] < 0){
        $this->totaldesc += $this->arrayvalreceitas[$x]; 
     }else if (@$this->arraycodtipo[$x] == 't' and $this->arrayvalreceitas[$x] > 0){
        $this->totalacres += $this->arrayvalreceitas[$x];
     }else{
        $this->totalrec += $this->arrayvalreceitas[$x];
     }
   }
   
   if($x==25){
     $bklin  = $bklin2-2;
     $reccol += 98;
   }
   $bklin += 2;

}

$xlin+= 15;

$this->objpdf->Roundedrect($xcol+2,$xlin+62,153,60,2,'DF','1234'); // historico
$this->objpdf->SetY($xlin+64);
$this->objpdf->SetX($xcol+3);
$this->objpdf->multicell(145,4,'HISTÓRICO :   '.$this->descr12_1);
$this->objpdf->SetX($xcol+3);
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->setx(15);
//dados do desconto
$this->objpdf->Roundedrect($xcol+156,$xlin+62,40,60,2,'DF','1234');

$this->objpdf->Roundedrect($xcol+159,$xlin+69,34,9,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+159,$xlin+79.5,34,9,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+159,$xlin+91,34,9,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+159,$xlin+103,34,9,2,'DF','1234');
  //		$this->objpdf->multicell(0,4,$this->histparcel);
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text($xcol+161,$xlin+71,'( = ) Valor Devido');
$this->objpdf->text($xcol+161,$xlin+81.5,'( - ) Desconto');
$this->objpdf->text($xcol+161,$xlin+93,'( + ) Mora / Multa');
$this->objpdf->text($xcol+161,$xlin+105,'( = ) Valor Documento');
$this->objpdf->Setfont('Arial','',10);
$this->objpdf->setxy($xcol+156,$xlin+70);
$this->objpdf->cell(36,9,db_formatar($this->totalrec,"f"),0,0,"R");
$this->objpdf->setxy($xcol+156,$xlin+80.5);
$this->objpdf->cell(36,9,db_formatar(abs($this->totaldesc),"f"),0,0,"R");
$this->objpdf->setxy($xcol+156,$xlin+92);
$this->objpdf->cell(36,9,db_formatar($this->totalacres,"f"),0,0,"R");
$this->objpdf->Setfont('Arial','b',10);
$this->objpdf->setxy($xcol+156,$xlin+104);
$this->objpdf->cell(36,9,$this->valtotal,0,0,"R");

$xlin+= 35;
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->Roundedrect(102,$xlin+95,40,9,2,'DF','1234');
$this->objpdf->Roundedrect(144,$xlin+95,21,9,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+161,$xlin+95,35,9,2,'DF','1234');

$this->objpdf->text(110,$xlin+97,'Nosso Número');
$this->objpdf->text(147,$xlin+97,'Vencimento');
$this->objpdf->text(168,$xlin+97,'Nro. Documento/Cód. Arrecadação');
$this->objpdf->setfont('Arial','',10);
$this->valor_cobrado       = $this->valtotal;

$this->desconto_abatimento = db_formatar(abs($this->totaldesc),'f');
$this->mora_multa          = db_formatar(($this->totalacres),'f');
/*
valtotal  == valor historico
totalrec  == valor corrigido

*/
//die("total : {$this->valtotal} -- totalrec : {$this->totalrec}");

$this->valtotal            = db_formatar(($this->totalrec),'f');

if (isset($this->linhadigitavel)){
    $this->objpdf->text(10,$xlin+92,@$this->linhadigitavel);
}
//$this->objpdf->text(127,$xlin+110,$this->descr14);//   $this->dtvenc);
//$this->objpdf->text(104,$xlin+110,str_pad($this->nosso_numero,17,"0",STR_PAD_LEFT));//   $this->dtvenc);
$this->objpdf->text(104,$xlin+102,$this->nosso_numero);//   $this->dtvenc);
$this->objpdf->text(145,$xlin+102,$this->dtparapag);//   $this->dtvenc);
$this->objpdf->text(175,$xlin+102,$this->descr9);


$this->objpdf->setfillcolor(0);
$this->objpdf->Setfont('Arial','',4);

$sBase  = db_getsession('DB_base');
$sHora  = db_hora();
$sUser  = db_getsession('DB_login');
$sData  = date('d/m/Y',db_getsession('DB_datausu'));
$sTexto = " Usuário: {$sUser}         Base: {$sBase}         Data: {$sData}         Hora: {$sHora}"; 

 
$this->objpdf->TextWithDirection(135,$xlin+107,$sTexto,'F');
 
/*********************************************************************************************************************************************************/
// incluir a ficha de compensação
include("fpdf151/impmodelos/mod_imprime48.php"); 
 
?>