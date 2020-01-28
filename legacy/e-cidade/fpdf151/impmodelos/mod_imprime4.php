<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

$this->objpdf->AliasNbPages();
$this->objpdf->setAutoPageBreak(1,1);
$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$this->objpdf->line(2,148.5,208,148.5);

$xlin = 20;
$xcol = 4;

$this->objpdf->setfillcolor(245);
$this->objpdf->roundedrect($xcol-2, $xlin-18,206,185.5,2,'DF','1234');
$this->objpdf->setfillcolor(255,255,255);
$this->objpdf->Setfont('Arial','B',11);
$this->objpdf->text(160, $xlin-13,'RECIBO DO SACADO ');

if (substr($this->dtparapag,4,1)=='-' || substr($this->dtparapag,7,1)=='/') {
	$this->dtparapag =  db_formatar($this->dtparapag,'d');
}

$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->text(142, $xlin-8,"DOCUMENTO VÁLIDO ATÉ: ".$this->dtparapag); //$this->descr14); //  $this->datacalc);

$str_via = 'Contribuinte';
$this->objpdf->Setfont('Arial','B',8);

$this->objpdf->Image('imagens/files/'.$this->logo,15,@$xlin-17,12);
$this->objpdf->Setfont('Arial','B',9);
$this->objpdf->text(40, $xlin-15, $this->prefeitura);
$this->objpdf->Setfont('Arial','',9);

$this->objpdf->text(40, $xlin-11,$this->enderpref);
$this->objpdf->text(40, $xlin-8, $this->municpref);
$this->objpdf->text(40, $xlin-5, $this->telefpref);
$this->objpdf->text($xcol+60,$xlin-5,"CNPJ: ");
$this->objpdf->text($xcol+70,$xlin-5,db_formatar($this->cgcpref,'cnpj'));
$this->objpdf->text(40, $xlin-2, $this->emailpref);

$this->objpdf->Roundedrect(@$xcol,@$xlin+2,@$xcol+119,20,2,'DF','1234');

$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text(@$xcol+2,@$xlin+4,'Identificação:');
$this->objpdf->Setfont('Arial','',8);
$this->objpdf->text($xcol+2,  $xlin+7,  'Nome : ');
$this->objpdf->text($xcol+17, $xlin+7,  $this->descr11_1); //  $this->nome);
$this->objpdf->text($xcol+2,  $xlin+11, 'Endereço : ');
$this->objpdf->text($xcol+17, $xlin+11, $this->descr11_2); //  $this->ender);

$this->objpdf->text($xcol+2,  $xlin+15, 'Bairro : ');
$this->objpdf->text($xcol+17, $xlin+15, $this->bairrocontri);

$this->objpdf->text($xcol+2,  $xlin+19, 'Município : ');
$this->objpdf->text($xcol+17, $xlin+19, $this->munic);
$this->objpdf->text($xcol+75, $xlin+15, 'CEP : ');
$this->objpdf->text($xcol+83, $xlin+15, $this->cep);
$this->objpdf->text($xcol+75, $xlin+19, 'CNPJ/CPF:');
$this->objpdf->text($xcol+90, $xlin+19, db_formatar(@$this->cgccpf,(strlen(@$this->cgccpf)<12?'cpf':'cnpj')));
$this->objpdf->Setfont('Arial','',6);

$this->objpdf->Roundedrect(@$xcol+126,@$xlin+2,76,20,2,'DF','1234');

$this->objpdf->text($xcol+128,  $xlin, 'Data :'. date("d-m-Y",db_getsession("DB_datausu")). ' - Hora : '.date("H:i:s"));

$this->objpdf->text($xcol+128,$xlin+4, $this->identifica_dados);
$this->objpdf->text($xcol+128,$xlin+7, $this->tipoinscr);
$this->objpdf->text($xcol+145,$xlin+7, $this->nrinscr);
$this->objpdf->text($xcol+128,$xlin+11,"Rua : ");
$this->objpdf->text($xcol+145,$xlin+11,$this->nomepriimo);
$this->objpdf->text($xcol+128,$xlin+15,$this->tipocompl);
$this->objpdf->text($xcol+145,$xlin+15,$this->nrpri.(isset($this->complpri)&&$this->complpri!=""?" / ".$this->complpri:"") );
$this->objpdf->text($xcol+128,$xlin+19,"Bairro : ");
$this->objpdf->text($xcol+145,$xlin+19,$this->bairropri);

$this->objpdf->Roundedrect($xcol,$xlin+24,202,60,2,'DF','1234'); //Quadro das receitas

$this->objpdf->sety($xlin+24);
$maiscol = 0;
$yy = $this->objpdf->gety();
$intnumrows = count($this->arraycodreceitas);

$this->objpdf->setx($xcol+3+$maiscol);
$this->objpdf->cell( 6, 3, "Rec"      , 0, 0, "L", 0);
$this->objpdf->cell( 7, 3, "Reduz"    , 0, 0, "L", 0);
$this->objpdf->cell(64, 3, "Descrição", 0, 0, "L", 0);
$this->objpdf->cell(18, 3, "Valor"    , 0, 1, "R", 0);

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
  $this->objpdf->Text($reccol + 12, $bklin,$this->arraydescrreceitas[$x].$this->obsdescr);

  if($x == 0){
    $this->objpdf->SetY($xlin+28);
  }
  $this->objpdf->cell($reccol + 82, 2,db_formatar($this->arrayvalreceitas[$x],'f'),0,1,"R",0);

  $iFormaCorrecao = pg_result(db_query("select k03_separajurmulparc
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
    $this->objpdf->SetY($xlin+28);
  }

  $bklin += 2;
}

$xlin+= 15;

$this->objpdf->Roundedrect($xcol,$xlin+72,160,60,2,'DF','1234'); // historico
$this->objpdf->SetY($xlin+72);
$this->objpdf->SetX($xcol+3);
$this->objpdf->multicell(155,4,'HISTÓRICO :   '.$this->descr12_1);

$this->objpdf->SetX($xcol+3);
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->setx(15);

//Dados do desconto
$this->objpdf->Roundedrect($xcol+161,$xlin+72,40,60,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+163,$xlin+75,36,9,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+163,$xlin+85.5,36,9,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+163,$xlin+97,36,9,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+163,$xlin+109,36,9,2,'DF','1234');
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->text($xcol+165,$xlin+77,'( = ) Valor Devido');
$this->objpdf->text($xcol+165,$xlin+87.5,'( - ) Desconto');
$this->objpdf->text($xcol+165,$xlin+99,'( + ) Mora / Multa');
$this->objpdf->text($xcol+165,$xlin+111,'( = ) Valor Documento');
$this->objpdf->Setfont('Arial','',10);
$this->objpdf->setxy($xcol+163,$xlin+75);

/**
 * valtotal -> valor historico
 * totalrec -> valor corrigido
 */
$this->objpdf->cell(36,9,db_formatar($this->totalrec,"f"),0,0,"R");
$this->objpdf->setxy($xcol+163,$xlin+85.5);
$this->objpdf->cell(36,9,db_formatar(abs($this->totaldesc),"f"),0,0,"R");
$this->objpdf->setxy($xcol+163,$xlin+97);
$this->objpdf->cell(36,9,db_formatar($this->totalacres,"f"),0,0,"R");
$this->objpdf->Setfont('Arial','b',10);
$this->objpdf->setxy($xcol+163,$xlin+109);
$this->objpdf->cell(36,9,$this->valtotal,0,0,"R");

$xlin+= 35;
$this->objpdf->Setfont('Arial','',6);
$this->objpdf->Roundedrect( 98,$xlin+103,44,9,2,'DF','1234');
$this->objpdf->Roundedrect(143,$xlin+103,21,9,2,'DF','1234');
$this->objpdf->Roundedrect($xcol+161,$xlin+103,40,9,2,'DF','1234');

$this->objpdf->text(112,$xlin+105,'Nosso Número');
$this->objpdf->text(147,$xlin+105,'Vencimento');
$this->objpdf->text(168,$xlin+105,'Nro. Documento/Cód. Arrecadação');
$this->objpdf->setfont('Arial','',10);
$this->valor_cobrado       = $this->valtotal;

$this->desconto_abatimento = db_formatar(abs($this->totaldesc),'f');
$this->mora_multa          = db_formatar(($this->totalacres),'f');
$this->valtotal            = db_formatar(($this->totalrec),'f');

if (isset($this->linhadigitavel)){
  $this->objpdf->text(10,$xlin+102,@$this->linhadigitavel);
}

$this->objpdf->text(101,$xlin+110,$this->nosso_numero);
$this->objpdf->text(145,$xlin+110,$this->dtparapag); //$this->dtvenc);
$this->objpdf->text(175,$xlin+110,$this->descr9);

$this->objpdf->setfillcolor(0);
$this->objpdf->Setfont('Arial','',4);

$sBase  = db_getsession('DB_base');
$sHora  = db_hora();
$sUser  = db_getsession('DB_login');
$sData  = date('d/m/Y',db_getsession('DB_datausu'));
$sTexto = " Usuário: {$sUser}         Base: {$sBase}         Data: {$sData}         Hora: {$sHora}";

$this->objpdf->TextWithDirection(3.6,$xlin+95,$sTexto,'U');

/**
 * Inclui a Ficha de Compensação
 */
include("fpdf151/impmodelos/mod_imprime48.php");

if ($this->loteamento == true) {

  $sqlrecibo =	"select a.k99_numpre,
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
                   from ( select z.k99_numpre,
                                 z.k99_desconto,
                                 z.k00_ano,
                                 sum(round(vlrhis,2))                            as vlrhis,
                                 sum(round(vlrcor,2))                            as vlrcor,
                                 sum(round(vlrjuros,2))                          as vlrjuros,
                                 sum(round(vlrmulta,2))                          as vlrmulta,
                                 sum(round(vlrdesconto,2))                       as vlrdesconto,
                                 sum(round(total,2))                             as total,
                                 sum(round(round(vlrcor,2) * descvlr / 100,2))   as descontovlr,
                                 sum(round(round(vlrjuros,2) * descjur / 100,2)) as descontojur,
                                 sum(round(round(vlrmulta,2) * descmul / 100,2)) as descontomul
                            from ( select y.k99_numpre,
                                          y.k99_numpar,
                                          y.k00_receit,
                                          y.k99_desconto,
                                          y.k00_ano,
                                          substr(fc_calcula,2,13)::float8    as vlrhis,
                                          substr(fc_calcula,15,13)::float8   as vlrcor,
                                          substr(fc_calcula,28,13)::float8   as vlrjuros,
                                          substr(fc_calcula,41,13)::float8   as vlrmulta,
                                          substr(fc_calcula,54,13)::float8   as vlrdesconto,
                                          (substr(fc_calcula,15,13)::float8 + substr(fc_calcula,28,13)::float8+
                                           substr(fc_calcula,41,13)::float8 - substr(fc_calcula,54,13)::float8) as total

                                     from ( select x.k99_numpre,
                                                   x.k99_numpar,
                                                   x.k00_receit,
                                                   x.k99_desconto,
                                                   x.k00_ano,
                                                   fc_calcula(x.k99_numpre,x.k99_numpar,x.k00_receit,'" . date("Y-m-d",db_getsession("DB_datausu")) . "', '" . date("Y-m-d",db_getsession("DB_datausu")) . "', " . db_getsession("DB_anousu") . ")
                                              from ( select distinct k99_numpre,
                                                            k99_numpar,
                                                            k00_receit,
                                                            k99_desconto,
                                                            extract (year from arrecad.k00_dtoper) as k00_ano
                                                       from db_reciboweb
                                                            inner join arrecad on db_reciboweb.k99_numpre = arrecad.k00_numpre
                                                                              and db_reciboweb.k99_numpar = arrecad.k00_numpar
                                                      where db_reciboweb.k99_numpre_n = " . substr($this->numpre,0,8) . "
                                                   ) as x
                                          ) as y
                                 ) as z
                                 inner join cadtipoparc on cadtipoparc.k40_codigo = z.k99_desconto
                                 left  join tipoparc    on tipoparc.cadtipoparc   = cadtipoparc.k40_codigo
                                                       and tipoparc.maxparc     = 1
                        group by z.k99_numpre,
                                 z.k99_desconto,
                                 z.k00_ano,
                                 tipoparc.descvlr,
                                 tipoparc.descmul,
                                 tipoparc.descjur
                        ) as a
                        inner join arrematric	 on a.k99_numpre           = arrematric.k00_numpre
                        inner join iptubase    on iptubase.j01_matric    = arrematric.k00_matric
                        inner join lote        on lote.j34_idbql         = iptubase.j01_idbql
                        left  join testpri     on testpri.j49_idbql      = lote.j34_idbql
                        left  join ruas        on ruas.j14_codigo        = testpri.j49_codigo
                        inner join cadtipoparc on cadtipoparc.k40_codigo = a.k99_desconto
                        left  join tipoparc    on tipoparc.cadtipoparc   = cadtipoparc.k40_codigo
                                              and tipoparc.maxparc       = 1";

  $resultrecibo = db_query($sqlrecibo) or die($sqlrecibo);

  global $k00_matric, $j14_nome, $j34_setor, $j34_quadra, $j34_lote, $k99_desconto, $descmul, $descjur, $descvlr, $vlrcor, $vlrjuros, $vlrmulta, $descontovlr, $descontojur, $descontomul, $k00_ano, $fc_calcula;

  $totvlrcor			= 0;
  $totvlrmul			= 0;
  $totvlrjur			= 0;
  $totvlrdesconto = 0;
  $totapagar			= 0;

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

    $totvlrcor			+= $vlrcor;
    $totvlrmul			+= $vlrmulta;
    $totvlrjur			+= $vlrjuros;
    $totvlrdesconto += $vlrdesconto;
    $totapagar			+= ($vlrtotal - $vlrdesconto);

    $this->objpdf->ln();

  }

  $this->objpdf->cell(88, 5, "TOTAL DE MATRICULAS: " . pg_numrows($resultrecibo) , 0, 0, 'L');
  $this->objpdf->cell(22, 5, db_formatar($totvlrcor, "f", ' ', 20) , 0, 0, 'R');
  $this->objpdf->cell(18, 5, db_formatar($totvlrmul, "f", ' ', 20) , 0, 0, 'R');
  $this->objpdf->cell(18, 5, db_formatar($totvlrjur, "f", ' ', 20) , 0, 0, 'R');
  $this->objpdf->cell(22, 5, db_formatar($totvlrdesconto, "f", ' ', 20) , 0, 0, 'R');
  $this->objpdf->cell(22, 5, db_formatar($totapagar, "f", ' ', 20) , 0, 0, 'R');

}