<?php

//// RECIBO
$this->objpdf->AliasNbPages();
$this->objpdf->AddPage();
$this->objpdf->setAutoPageBreak(1,1);
$this->objpdf->settopmargin(1);
$this->objpdf->line(2,148.5,208,148.5);
$xlin = 20;
$xcol = 4;
for ($i = 0;$i < 2;$i++){

  $this->totaldesc   = 0;
  $this->totalrec    = 0;
  $this->totalacres  = 0;
  $this->objpdf->setfillcolor(245);
  $this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
  $this->objpdf->setfillcolor(255,255,255);
  //    $this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
  $this->objpdf->Setfont('Arial','B',11);
  $this->objpdf->text(150,$xlin-13,'RECIBO VÁLIDO ATÉ: ');
  $this->objpdf->text(159,$xlin-8,$this->dtvenc);
  //Via
  if( $i == 0 ){
    $str_via = 'Contribuinte';
  }else{
    $str_via = 'Prefeitura';
  }
  $this->objpdf->Setfont('Arial','B',8);
  $this->objpdf->text(178,$xlin-1,($i+1).'ª Via '.$str_via );

  $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
  $this->objpdf->Setfont('Arial','B',9);
  $this->objpdf->text(40,$xlin-15,$this->prefeitura);
  $this->objpdf->Setfont('Arial','',9);
  $this->objpdf->text(40,$xlin-11,$this->enderpref);
  $this->objpdf->text(40,$xlin-8,$this->municpref);
  $this->objpdf->text(40,$xlin-5,$this->telefpref);
  $this->objpdf->text($xcol+60,$xlin-5,"CNPJ: ");
  $this->objpdf->text($xcol+70,$xlin-5,db_formatar($this->cgcpref,'cnpj'));
  $this->objpdf->text(40,$xlin-2,$this->emailpref);
  //    $this->objpdf->setfillcolor(245);

  $this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+119,20,2,'DF','1234');
  $this->objpdf->Setfont('Arial','',6);
  $this->objpdf->text($xcol+2,$xlin+4,'Identificação:');
  $this->objpdf->Setfont('Arial','',8);
  $this->objpdf->text($xcol+2,$xlin+7,'Nome :');
  $this->objpdf->text($xcol+17,$xlin+7,$this->nome);
  $this->objpdf->text($xcol+2,$xlin+11,'Endereço :');
  $this->objpdf->text($xcol+17,$xlin+11,$this->ender);

  $this->objpdf->text($xcol+2,  $xlin+15, 'Bairro :');
  $this->objpdf->text($xcol+17, $xlin+15, $this->bairrocontri);

  $this->objpdf->text($xcol+2,$xlin+19,'Município :');
  $this->objpdf->text($xcol+17,$xlin+19,"{$this->munic}");
  $this->objpdf->text($xcol+75,$xlin+15,'CEP :');
  $this->objpdf->text($xcol+82,$xlin+15,$this->cep);

  $this->objpdf->text($xcol+128,  $xlin, 'Data :'. date("d-m-Y",db_getsession("DB_datausu")). 'Hora: '.date("H:i:s"));

  $this->objpdf->text($xcol+75,$xlin+19,'CNPJ/CPF:');
  $this->objpdf->text($xcol+90,$xlin+19,db_formatar($this->cgccpf,(strlen($this->cgccpf)<12?'cpf':'cnpj')));

  $this->objpdf->Setfont('Arial','',6);

  $this->objpdf->Roundedrect($xcol+126,$xlin+2,76,20,2,'DF','1234');

  $this->objpdf->text($xcol+128,$xlin+4,$this->identifica_dados);

  $this->objpdf->text($xcol+128,$xlin+7,$this->tipoinscr);
  $this->objpdf->text($xcol+145,$xlin+7,$this->nrinscr);

  $this->objpdf->text($xcol+128,$xlin+11,$this->tipolograd);
  $this->objpdf->text($xcol+145,$xlin+11,$this->nomepri);
  $this->objpdf->text($xcol+128,$xlin+15,$this->tipocompl);
  $this->objpdf->text($xcol+145,$xlin+15,$this->nrpri."      ".$this->complpri);
  $this->objpdf->text($xcol+128,$xlin+19,$this->tipobairro);
  $this->objpdf->text($xcol+145,$xlin+19,$this->bairropri);

  $this->objpdf->Roundedrect($xcol,$xlin+24,202,45,2,'DF','1234');
  $this->objpdf->sety($xlin+24);
  $maiscol = 0;
  $yy = $this->objpdf->gety();

  for($ii = 0;$ii < $this->linhasdadospagto ;$ii++) {

    $this->obsdescr  = null;
    if ($ii == 14 ){
      $maiscol = 100;
      $this->objpdf->sety($yy);
    }
    if($ii==0 || $ii == 14){

      $this->objpdf->setx($xcol+3+$maiscol);
      $this->objpdf->cell(5,3,"Rec",0,0,"L",0);
      $this->objpdf->cell(7,3,"Reduz",0,0,"L",0);
      $this->objpdf->cell(63,3,"Descrição",0,0,"L",0);
      $this->objpdf->cell(15,3,"Valor",0,1,"R",0);

    }
    if (pg_result($this->recorddadospagto,$ii,"k00_hist") == 918){

        $this->obsdescr = "(desconto)";
    }
    $codtipo = pg_result($this->recorddadospagto,$ii,"codtipo");
    $valor   = pg_result($this->recorddadospagto,$ii,$this->valor);
    $this->objpdf->setx($xcol+3+$maiscol);
    $this->objpdf->cell(5,3,trim(pg_result($this->recorddadospagto,$ii,$this->receita)),0,0,"R",0);
    $this->objpdf->cell(7,3,"(".trim(pg_result($this->recorddadospagto,$ii,$this->receitared)).")",0,0,"R",0);
    if ( trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita) ) == ''){
      $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->dreceita)." ".$this->obsdescr ),0,0,"L",0);
    }else{
      $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita)." ".$this->obsdescr),0,0,"L",0);
    }

    $this->objpdf->cell(15,3,db_formatar(pg_result($this->recorddadospagto,$ii,$this->valor),'f'),0,1,"R",0);
    if ($valor < 0){
         $this->totaldesc += pg_result($this->recorddadospagto,$ii,$this->valor);
    }else if ($codtipo == 't' and $valor > 0){
         $this->totalacres  += pg_result($this->recorddadospagto,$ii,$this->valor);
    }else{
       $this->totalrec  += pg_result($this->recorddadospagto,$ii,$this->valor);
    }
  }
  $this->objpdf->Roundedrect($xcol,$xlin+71,176,30,2,'DF','1234');
  $this->objpdf->SetY($xlin+72);
  $this->objpdf->SetX($xcol+3);


  /**
   * aqui iremos criar a query para buscar a observacao da VISTORIA
   *
   */

  $sSqlReciboPaga = "       select *
                     from recibopaga
                    where k00_numnov = ".$this->numnov_recibo;

  $rsReciboPaga = db_query($sSqlReciboPaga);
  $iNumpre      = db_utils::fieldsMemory($rsReciboPaga, 0)->k00_numpre;

  $sSqlVistoria = "
                        select *
                          from vistorias
                    inner join vistorianumpre on y69_codvist = y70_codvist
                         where y69_numpre = {$iNumpre}
                 ";
  $rsVistorias = db_query($sSqlVistoria);


  $sComplementoHistoricoVistoria = "";
  if ( pg_numrows($rsVistorias) > 0 ) {
    $sComplementoHistoricoVistoria = "\n" .  db_utils::fieldsMemory($rsVistorias, 0)->y70_obs;
  }

  $this->objpdf->Setfont('Arial','',7);
  $this->objpdf->multicell(170,4,'HISTÓRICO : '.$this->historico);
  $this->objpdf->SetX($xcol+3);
  //dados do desconto

  $this->objpdf->Roundedrect(181,$xlin+71,25,9,2,'DF','1234');
  $this->objpdf->Roundedrect(181,$xlin+81.5,25,9,2,'DF','1234');
  $this->objpdf->Roundedrect(181,$xlin+92,25,9,2,'DF','1234');

  $this->objpdf->Setfont('Arial','',6);
  $this->objpdf->text(182,$xlin+73,'( = ) Valor Documento');
  $this->objpdf->text(182,$xlin+83.5,'( - ) Desconto ');
  $this->objpdf->text(182,$xlin+94,'( + ) Mora / Multa');

  if(isset($this->lEmiteVal)){
    if( $this->lEmiteVal == false){
      $totalrec   = "";
      $totaldesc  = "";
      $totalacres = "";
      $valtotal   = "";
    }else{
      $totalrec   = $this->totalrec;
      $totaldesc  = abs($this->totaldesc);
      $totalacres = $this->totalacres;
      $valtotal   = $this->valtotal;
    }
  }else{
    $totalrec   = db_formatar($this->totalrec,'f');
    $totaldesc  = db_formatar(abs($this->totaldesc),'f');
    $totalacres = db_formatar($this->totalacres,'f');
    $valtotal   = $this->valtotal;
  }

  $this->objpdf->setfont('Arial','',10);
  $this->objpdf->setxy(181,$xlin+71);
  $this->objpdf->cell(25,9,$totalrec,0,0,"R");
  $this->objpdf->setxy(181,$xlin+81.5);
  $this->objpdf->cell(25,9,$totaldesc,0,0,"R");
  $this->objpdf->setxy(181,$xlin+92);
  $this->objpdf->cell(25,9,$totalacres,0,0,"R");


  $this->objpdf->setx(15);

   ///Totais
  $this->objpdf->Setfont('Arial','',6);
  $this->objpdf->Roundedrect(125,$xlin+103,32,9,2,'DF','1234');
  $this->objpdf->Roundedrect(158,$xlin+103,22,9,2,'DF','1234');
  $this->objpdf->Roundedrect(181,$xlin+103,25,9,2,'DF','1234');
  $this->objpdf->text(129,$xlin+105,'Código de Arrecadação');
  $this->objpdf->text(160,$xlin+105,'Vencimento');
  $this->objpdf->text(183,$xlin+105,'( = ) Valor Cobrado R$');
  $this->objpdf->setfont('Arial','',10);
  $this->objpdf->text(160,$xlin+109,$this->dtvenc);
  $this->objpdf->text(126,$xlin+109,$this->numpre);
  $this->objpdf->setfont('Arial','b',10);
  $this->objpdf->setxy(181,($xlin+103));
  $this->objpdf->cell(25,9,$valtotal,0,0,"R");

  $this->objpdf->SetFont('Arial','B',5);
  $this->objpdf->text(140,$xlin+116,"A   U   T   E   N   T   I   C   A   Ç   Ã   O      M   E   C   Â   N   I   C   A");

  if (isset($this->k12_codautent)){
     $this->objpdf->SetFont('Arial','',8);
     $this->objpdf->text(138,$xlin+122,$this->k12_codautent);
  }

  $this->objpdf->setfillcolor(0,0,0);
  $this->objpdf->SetFont('Arial','',4);
  $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
  $this->objpdf->setfont('Arial','',11);
  $this->objpdf->text(10,$xlin+108,@$this->linhadigitavel);

  if( $i == 1 ){
    $this->objpdf->int25(10,$xlin+110,$this->codigobarras,15,0.341);
  }
  $xlin = 169;

}



if ($this->loteamento == true) {


  $sqlrecibo =  "
  select
  a.k99_numpre,
  a.k99_desconto,
  a.k00_ano,
  arrematric.k00_matric,
  ruas.j14_nome,
  lote.j34_setor,
  lote.j34_quadra,
  lote.j34_lote,
  a.k99_desconto,
  tipoparc.descmul,
  tipoparc.descjur,
  tipoparc.descvlr,
  vlrhis,
  vlrcor,
  vlrjuros,
  vlrmulta,
  vlrdesconto,
  descontovlr,
  descontojur,
  descontomul
  from (
  select
  z.k99_numpre,
  z.k99_desconto,
  z.k00_ano,
  sum(round(vlrhis,2)) as vlrhis,
  sum(round(vlrcor,2)) as vlrcor,
  sum(round(vlrjuros,2)) as vlrjuros,
  sum(round(vlrmulta,2)) as vlrmulta,
  sum(round(vlrdesconto,2)) as vlrdesconto,
  sum(round(total,2)) as total,
  sum(round(round(vlrcor,2) * descvlr / 100,2)) as descontovlr,
  sum(round(round(vlrjuros,2) * descjur / 100,2)) as descontojur,
  sum(round(round(vlrmulta,2) * descmul / 100,2)) as descontomul
  from
  (
  select
  y.k99_numpre,
  y.k99_numpar,
  y.k00_receit,
  y.k99_desconto,
  y.k00_ano,
  substr(fc_calcula,2,13)::float8 as vlrhis,
  substr(fc_calcula,15,13)::float8 as vlrcor,
  substr(fc_calcula,28,13)::float8 as vlrjuros,
  substr(fc_calcula,41,13)::float8 as vlrmulta,
  substr(fc_calcula,54,13)::float8 as vlrdesconto,
  (substr(fc_calcula,15,13)::float8+
  substr(fc_calcula,28,13)::float8+
  substr(fc_calcula,41,13)::float8-
  substr(fc_calcula,54,13)::float8) as total
  from (
  select
  x.k99_numpre,
  x.k99_numpar,
  x.k00_receit,
  x.k99_desconto,
  x.k00_ano,
  fc_calcula(x.k99_numpre,x.k99_numpar,x.k00_receit,'" . date("Y-m-d",db_getsession("DB_datausu")) . "', '" . date("Y-m-d",db_getsession("DB_datausu")) . "', " . db_getsession("DB_anousu") . ")

  from (
  select
  distinct
  k99_numpre,
  k99_numpar,
  k00_receit,
  k99_desconto,
  extract (year from arrecad.k00_dtoper) as k00_ano
  from db_reciboweb
  inner join arrecad on db_reciboweb.k99_numpre = arrecad.k00_numpre and db_reciboweb.k99_numpar = arrecad.k00_numpar
  where db_reciboweb.k99_numpre_n = " . substr($this->numpre,0,8) . "
  ) as x
  ) as y
  ) as z
  inner join cadtipoparc   on cadtipoparc.k40_codigo = z.k99_desconto
  left  join tipoparc      on tipoparc.cadtipoparc = cadtipoparc.k40_codigo
                                          and tipoparc.maxparc     = 1
  group by
  z.k99_numpre,
  z.k99_desconto,
  z.k00_ano,
  tipoparc.descvlr,
  tipoparc.descmul,
  tipoparc.descjur
  ) as a
  inner join arrematric   on a.k99_numpre = arrematric.k00_numpre
  inner join iptubase      on iptubase.j01_matric = arrematric.k00_matric
  inner join lote          on lote.j34_idbql = iptubase.j01_idbql
  left  join testpri       on testpri.j49_idbql = lote.j34_idbql
  left  join ruas          on ruas.j14_codigo = testpri.j49_codigo
  inner join cadtipoparc   on cadtipoparc.k40_codigo = a.k99_desconto
  left  join tipoparc      on tipoparc.cadtipoparc = cadtipoparc.k40_codigo
                          and tipoparc.maxparc     = 1;
  ";
  $resultrecibo = db_query($sqlrecibo) or die($sqlrecibo);

  global $k00_matric, $j14_nome, $j34_setor, $j34_quadra, $j34_lote, $k99_desconto, $descmul, $descjur, $descvlr, $vlrcor, $vlrjuros, $vlrmulta, $descontovlr, $descontojur, $descontomul, $k00_ano, $fc_calcula;

  $totvlrcor      = 0;
  $totvlrmul      = 0;
  $totvlrjur      = 0;
  $totvlrdesconto = 0;
  $totapagar      = 0;

  for ($reg=0; $reg < pg_numrows($resultrecibo); $reg++) {
    db_fieldsmemory($resultrecibo, $reg);

    if(($this->objpdf->gety() > $this->objpdf->h-40) or $reg == 0) {

      $this->objpdf->AddPage();

      $this->objpdf->SetXY(1,1);
      $this->objpdf->Image('imagens/files/'.$this->logo,7,3,20);

      $nome = $this->prefeitura;

      if(strlen($nome) > 42) {
        $TamFonteNome = 8;
      } else {
        $TamFonteNome = 9;
      }

      $alt = 5;

      $this->objpdf->SetFont('Arial','BI',$TamFonteNome);
      $this->objpdf->Text(33,9,$nome);
      $this->objpdf->SetFont('Arial','I',8);
      $this->objpdf->Text(33,14,$this->enderpref);
      $this->objpdf->Text(33,18,$this->municpref);
      $this->objpdf->Text(33,22,$this->telefpref);
      $this->objpdf->Text(33,26,$this->emailpref);
      $comprim = ($this->objpdf->w - $this->objpdf->rMargin - $this->objpdf->lMargin);
      $Espaco = $this->objpdf->w - 80;
      $this->objpdf->SetFont('Arial','',7);
      $margemesquerda = $this->objpdf->lMargin;
      $this->objpdf->setleftmargin($Espaco);
      $this->objpdf->sety(6);
      $this->objpdf->setfillcolor(235);
      $this->objpdf->roundedrect($Espaco - 3,5,75,28,2,'DF','123');
      $this->objpdf->line(10,33,$comprim,33);
      $this->objpdf->setfillcolor(255);
      $this->objpdf->multicell(0,3,"DETALHAMENTO DO RECIBO DE PAGAMENTO",0,1,"J",0);
      $this->objpdf->multicell(0,3,"CODIGO DE ARRECADACAO: " . $this->numpre,0,1,"J",0);
      $this->objpdf->multicell(0,3,"LOTEAMENTO: " . $this->descr11_1,0,1,"J",0);
      $this->objpdf->multicell(0,3,"DESCONTO NO VALOR: " . $descvlr . "%",0,1,"J",0);
      $this->objpdf->multicell(0,3,"DESCONTO NOS JUROS: " . $descjur . "%",0,1,"J",0);
      $this->objpdf->multicell(0,3,"DESCONTO NA MULTA: " . $descmul . "%",0,1,"J",0);
      $this->objpdf->multicell(0,3,"DATA: " . date("d-m-Y",db_getsession("DB_datausu")) . " - HORA: " . date("H:i:s") ,0,1,"J",0);

      $this->objpdf->setleftmargin($margemesquerda);
      $this->objpdf->SetY(35);

      $this->objpdf->cell(10,$alt,"MATRIC",0,0,"L",0);
      $this->objpdf->cell(10,$alt,"ANO",0,0,"L",0);
      $this->objpdf->cell(50,$alt,"LOGRADOURO",0,0,"L",0);
      $this->objpdf->cell(18,$alt,"SET/QUA/LOT",0,0,"L",0);
      $this->objpdf->cell(22,$alt,"VLR LANCADO",0,0,"R",0);
      $this->objpdf->cell(18,$alt,"VLR MULTA",0,0,"R",0);
      $this->objpdf->cell(18,$alt,"VLR JUROS",0,0,"R",0);
      $this->objpdf->cell(22,$alt,"VLR DESCONTO",0,0,"R",0);
      $this->objpdf->cell(22,$alt,"VLR A PAGAR",0,0,"R",0);
      $this->objpdf->Ln();

      $this->objpdf->cell(0,$alt,'',"T",1,"C",0);
      $this->objpdf->setfont('arial','',7);

    }

    $vlrtotal    = $vlrcor + $vlrjuros + $vlrmulta;
    $vlrdesconto = $descontovlr + $descontojur + $descontomul;

    $this->objpdf->cell(10, 5, $k00_matric , 0, 0, 'L');
    $this->objpdf->cell(10, 5, $k00_ano , 0, 0, 'L');
    $this->objpdf->cell(50, 5, $j14_nome   , 0, 0, 'L');
    $this->objpdf->cell(18, 5, $j34_setor . "/" . $j34_quadra . "/" . $j34_lote , 0, 0, 'L');
    $this->objpdf->cell(22, 5, db_formatar($vlrcor, "f", ' ', 20) , 0, 0, 'R');
    $this->objpdf->cell(18, 5, db_formatar($vlrmulta, "f", ' ', 20) , 0, 0, 'R');
    $this->objpdf->cell(18, 5, db_formatar($vlrjuros, "f", ' ', 20) , 0, 0, 'R');
    $this->objpdf->cell(22, 5, db_formatar($vlrdesconto, "f", ' ', 20) , 0, 0, 'R');
    $this->objpdf->cell(22, 5, db_formatar($vlrtotal - $vlrdesconto, "f", ' ', 20) , 0, 0, 'R');

    $totvlrcor      += $vlrcor;
    $totvlrmul      += $vlrmulta;
    $totvlrjur      += $vlrjuros;
    $totvlrdesconto += $vlrdesconto;
    $totapagar      += ($vlrtotal - $vlrdesconto);

    $this->objpdf->ln();

  }

  $this->objpdf->cell(88, 5, "TOTAL DE MATRICULAS: " . pg_numrows($resultrecibo) , 0, 0, 'L');
  $this->objpdf->cell(22, 5, db_formatar($totvlrcor, "f", ' ', 20) , 0, 0, 'R');
  $this->objpdf->cell(18, 5, db_formatar($totvlrmul, "f", ' ', 20) , 0, 0, 'R');
  $this->objpdf->cell(18, 5, db_formatar($totvlrjur, "f", ' ', 20) , 0, 0, 'R');
  $this->objpdf->cell(22, 5, db_formatar($totvlrdesconto, "f", ' ', 20) , 0, 0, 'R');
  $this->objpdf->cell(22, 5, db_formatar($totapagar, "f", ' ', 20) , 0, 0, 'R');

}

$this->lUtilizaModeloDefault = false;