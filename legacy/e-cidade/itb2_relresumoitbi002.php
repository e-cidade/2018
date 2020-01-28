<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification('fpdf151/pdf.php'));
require_once(modification('libs/db_sql.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('classes/db_matmater_classe.php'));

$oGet = db_utils::postMemory($_GET);

$clrotulo   = new rotulocampo;
$clmatmater = new cl_matmater;

$clrotulo->label('m60_codmater');
$clrotulo->label('m60_descr');
$clrotulo->label('m60_quantent');

$sOrdem           = "";
$sWhere           = "";
$sAnd             = "";
$sWhereLograd     = "";

$sHeaderGuia      = "";
$sHeaderPeriodo   = "";
$sHeaderSituacao  = "";
$sHeaderTipo      = "";
$sHeaderOrdem     = "";
$sHeaderLiberadas = "";

if ( isset($oGet->guiaini) && isset($oGet->guiafim) ) {

	if ( (trim($oGet->guiaini) != "") && (trim($oGet->guiafim) != "") ) {

    $sHeaderGuia = "Código da Guia: ".$oGet->guiaini." á ".$oGet->guiafim;
	  $sWhere    .= "{$sAnd} it01_guia between '{$oGet->guiaini}' and '{$oGet->guiafim}' ";
	  $sAnd       = " and ";
	} else if ( trim($oGet->guiaini) != "" ) {

	  $sHeaderGuia = "Código da Guia: ".$oGet->guiaini;
	  $sWhere .= "{$sAnd} ( it01_guia >= '{$oGet->guiaini}' ) ";
	  $sAnd    = " and ";
	} else if ( trim($oGet->guiafim) != "" ) {

	  $sHeaderGuia = "Código da Guia: ".$oGet->guiafim;
	  $sWhere .= "{$sAnd} ( it01_guia <= '{$oGet->guiafim}' ) ";
	  $sAnd    = " and ";
	}
}

if ( isset($oGet->dtfim) && isset($oGet->dtini) ) {

	$dtIni = implode("-",array_reverse(explode("/",$oGet->dtini)));
  $dtFim = implode("-",array_reverse(explode("/",$oGet->dtfim)));

  if ( !empty($dtIni) && !empty($dtFim) ) {

	  $sHeaderPeriodo = "PERIODO : DE ".$oGet->dtini." A ".$oGet->dtfim;
	  $sWhere        .= " {$sAnd} it01_data between '{$dtIni}' and '{$dtFim}'";
	  $sAnd           = " and ";
	} else if ( !empty($dtIni) ) {

	  $sHeaderPeriodo = "PERIODO : DATA MAIOR OU IGUAL A ".$oGet->dtini;
	  $sWhere        .= " {$sAnd} it01_data >= '{$dtIni}' ";
	  $sAnd           = " and ";
	} else if ( !empty($dtFim) ) {

	  $sHeaderPeriodo = "PERIODO : DATA MENOR OU IGUAL A ".$oGet->dtfim;
	  $sWhere        .= " {$sAnd} it01_data <= '{$dtFim}' ";
	  $sAnd           = " and ";
	}
}

if ( isset($oGet->sLogradouro) ) {
  if ( !empty($oGet->sLogradouro) ) {
    $sWhereLograd  = " where logradouro = '{$oGet->sLogradouro}' ";
  }
}


if ( isset($oGet->sSetor) && $oGet->sSetor != "") {
  $sWhere  .= " {$sAnd} j34_setor = '" . str_pad($oGet->sSetor,4,"0",STR_PAD_LEFT)."'";
  $sAnd     = " and ";
}

if ( isset($oGet->sQuadra) && $oGet->sQuadra != "" ) {
  $sWhere  .= " {$sAnd} j34_quadra = '" . str_pad($oGet->sQuadra,4,"0",STR_PAD_LEFT)."'";
  $sAnd     = " and ";
}

if ( isset($oGet->sLote) && $oGet->sLote != "" ) {
  $sWhere  .= " {$sAnd} j34_lote = '" . str_pad($oGet->sLote,4,"0",STR_PAD_LEFT)."'";
  $sAnd     = " and ";
}

if ( $oGet->situacao == "2" ) {

  $sHeaderSituacao = "SITUAÇÃO : ABERTO";
  $sWhere         .= " {$sAnd} not exists(select arrepaga.k00_numpre";
  $sWhere         .= "                     from arrepaga ";
  $sWhere         .= "                          inner join itbinumpre on itbinumpre.it15_numpre = arrepaga.k00_numpre";
  $sWhere         .= "                     where itbinumpre.it15_guia = itbi.it01_guia)";
  $sWhere         .= " and it16_guia is null";
  $sAnd            = " and ";
} else if ( $oGet->situacao == "3" ) {

  $sHeaderSituacao = "SITUAÇÃO : PAGO";
  $sWhere         .= " {$sAnd} exists(select arrepaga.k00_numpre";
  $sWhere         .= "                     from arrepaga ";
  $sWhere         .= "                          inner join itbinumpre on itbinumpre.it15_numpre = arrepaga.k00_numpre";
  $sWhere         .= "                     where itbinumpre.it15_guia = itbi.it01_guia)";
  $sAnd            = " and ";
} else if ( $oGet->situacao == "4" ) {

  $sHeaderSituacao = "SITUAÇÃO : CANCELADO";
  $sWhere         .= " {$sAnd} it16_guia is not null";
  $sAnd            = " and ";
} else {
  $sHeaderSituacao = "SITUAÇÃO : TODOS";
}

if ( $oGet->tipo == "u"  ) {

  $sHeaderTipo = "TIPO : URBANO";
  $sWhere     .= " {$sAnd} it05_guia is not null ";
  $sAnd        = " and ";
} else if ( $oGet->tipo == "r"  ) {

  $sHeaderTipo = "TIPO : RURAL";
  $sWhere     .= " {$sAnd} it18_guia is not null ";
  $sAnd        = " and ";
} else {
  $sHeaderTipo = "TIPO : TODOS";
}

if ( $oGet->liberadas == "s" ) {

  $sHeaderLiberadas = "SOMENTE LIBERADAS";
  $sWhere          .= " {$sAnd}  it14_guia is not null ";
  $sAnd             = " and ";
} else if ( $oGet->liberadas == "n" ) {

  $sHeaderLiberadas = "SOMENTE NÃO LIBERADAS";
  $sWhere          .= " {$sAnd}  it14_guia is null ";
  $sAnd             = " and ";
}

if ( isset($oGet->modoimp) ) {

	if ($oGet->modoimp == 'anal') {
		$sHeaderModoImp = "ANÁLITICO";
	} else if ($oGet->modoimp == 'sint') {
		$sHeaderModoImp = "SINTÉTICO";
	}
}

if( $oGet->ordem == 'n' ) {

  $sHeaderOrdem = "ORDEM : NOME ";
  $sOrdem       = " itbinome.it03_nome ";
} else if ( $oGet->ordem == 'v' ) {

  $sHeaderOrdem = "ORDEM : VALOR ";
  $sOrdem       = " itbi.it01_valortransacao ";
} else if ( $oGet->ordem == 'g' ) {

  $sHeaderOrdem = "ORDEM : GUIA";
  $sOrdem       = " itbi.it01_guia ";
}

//echo $sOrdem;
//die();

if ( isset($sWhere) && !empty($sWhere) ) {
	$sWhere = " where {$sWhere}";
}

$head2 = "RESUMO DE ITBI ";
$head4 = $sHeaderGuia;
$head3 = $sHeaderPeriodo;
$head4 = $sHeaderSituacao;
$head5 = $sHeaderTipo;
$head6 = $sHeaderOrdem;
$head7 = $sHeaderLiberadas;

$sSql  = "    select *                                                                                                ";
$sSql .= "      from (                                                                                                ";
$sSql .= "             select it01_guia,  																	          ";
$sSql .= " 	                  it04_descr, 																	          ";
$sSql .= "	                  recibo.k00_numpre, 															          ";
$sSql .= "	                  arrepaga.k00_numpre as arrepaga, 	  											          ";
$sSql .= "	                  it06_matric as matric, 														          ";
$sSql .= "		                case  																			      ";
$sSql .= "		  	              when it21_numcgm is not null then it21_numcgm 								      ";
$sSql .= "		  	              else it02_numcgm 															          ";
$sSql .= "		                end as cgm, 																	      ";
$sSql .= "		                case  																			      ";
$sSql .= "		  	              when it14_guia is not null then 'Sim'		 								          ";
$sSql .= "		  	              else 'Não' 																	      ";
$sSql .= "		                end as itbiliberada, 															      ";
$sSql .= "		                case  																			      ";
$sSql .= "		  	              when it05_guia is not null then 'Urbano'	 								          ";
$sSql .= "		  	              else 'Rural'	 															          ";
$sSql .= "		                end as urbanorural, 															      ";
$sSql .= "                    case                                                                                    ";
$sSql .= "                      when itbidadosimovel.it22_itbi is not null then it22_descrlograd                      ";
$sSql .= "                      else itbirural.it18_nomelograd                                                        ";
$sSql .= "                    end as logradouro,                                                                      ";
$sSql .= "		                coalesce(recibo.k00_valor,0) as k00_valor,       	                                  ";
$sSql .= "		                recibo.k00_dtvenc, 															          ";
$sSql .= "		                it03_nome as z01_nome, 														          ";
$sSql .= "                    coalesce(itbi.it01_valorconstr          ,0) as it01_valorconstr,                        ";
$sSql .= "                    coalesce(itbi.it01_valorterreno         ,0) as it01_valorterreno,                       ";
$sSql .= "                    coalesce(itbi.it01_valortransacao       ,0) as it01_valortransacao,                     ";
$sSql .= "                    coalesce(itbiavalia.it14_valoravalconstr,0) as it14_valoravalconstr,                    ";
$sSql .= "                    coalesce(itbiavalia.it14_valoravalter   ,0) as it14_valoravalter,                       ";
$sSql .= "                    coalesce(itbiavalia.it14_valoraval      ,0) as it14_valoraval,                          ";
$sSql .= "                    itbinome.it03_tipo,                                                                     ";
$sSql .= "		                it16_guia, 																		      ";
$sSql .= "                    case when arrepaga.k00_numpre is not null                                               ";
$sSql .= "                      then 'PAGO'                                                                           ";
$sSql .= "                        else                                                                        		  ";
$sSql .= "											  case when recibo.k00_valor > 0                                  ";
$sSql .= "											    then 'EM_ABERTO'                                              ";
$sSql .= "											    else 'NAO_INCIDE'                                             ";
$sSql .= "											  end                                                             ";
$sSql .= "										end as valida_situacao                                                ";
$sSql .= "	             from itbi  																			      ";
$sSql .= "                    inner join itbitransacao     on it04_codigo                = it01_tipotransacao         ";
$sSql .= "	 	                inner join itbinome          on itbinome.it03_guia 	       = itbi.it01_guia 		  ";

if ( $oGet->modoimp == 'sint' ) {
  $sSql .= "				                                      and itbinome.it03_tipo 	       = 'C' 	  		  ";
}

$sSql .= "		                left  join itburbano         on itburbano.it05_guia         = itbi.it01_guia 		  ";
$sSql .= "		                left  join itbirural         on itbirural.it18_guia         = itbi.it01_guia 		  ";
$sSql .= "		                left  join itbicancela       on itbicancela.it16_guia       = itbi.it01_guia 		  ";
$sSql .= "		                left  join itbiavalia        on itbiavalia.it14_guia        = itbi.it01_guia 		  ";
$sSql .= "		                left  join itbinomecgm       on itbinomecgm.it21_itbinome   = itbi.it01_guia 		  ";
$sSql .= "		                left  join itbicgm           on itbicgm.it02_guia           = itbi.it01_guia 		  ";
$sSql .= "                    left  join itbidadosimovel   on itbidadosimovel.it22_itbi   = itbi.it01_guia            ";
$sSql .= "		                left  join itbimatric        on itbi.it01_guia              = itbimatric.it06_guia      ";
$sSql .= "                    left  join iptubase          on it06_matric                 = j01_matric                ";
$sSql .= "                    left  join lote              on j34_idbql                   = j01_idbql                 ";
$sSql .= "		                left  join cgm               on z01_numcgm                  = it21_numcgm 			        ";
$sSql .= "		                left  join itbinumpre        on itbinumpre.it15_guia	      = itbi.it01_guia		        ";
$sSql .= "		                left  join recibo            on recibo.k00_numpre  	        = it15_numpre 			        ";
$sSql .= "		                left  join arrepaga	         on arrepaga.k00_numpre         = itbinumpre.it15_numpre		";
$sSql .= "          {$sWhere}                                                                                         ";
$sSql .= "           order by {$sOrdem} {$oGet->modo}  ,recibo.k00_numpre {$oGet->modo}                               ";
$sSql .= "           ) as x                                                                                           ";
$sSql .= "           {$sWhereLograd}                                                                                  ";
$rsSql     = db_query($sSql);
$iNumRows  = pg_num_rows($rsSql);

$aRetorno  = db_utils::getCollectionByRecord($rsSql);

/**
 * Cria vetor para comparação
 */
$aCompara  = array();
foreach ($aRetorno as $oDadosFiltro){
  $aCompara[$oDadosFiltro->it01_guia][$oDadosFiltro->it03_tipo][] = $oDadosFiltro;
}
$oGuiaPaga          = null;
$oGuiaAberto        = null;
$oGuiaSemIncidencia = null;
$aDados             = array();
/**
 * Filtra os dados de guias repetidas
 */
foreach ($aCompara as $aGuias){

  foreach ($aGuias as $aTipoRegistro) {

    $oGuiaPaga          = array();
    $oGuiaAberto        = array();
    $oGuiaSemIncidencia = array();

    foreach ($aTipoRegistro as $oRegistroValido) {

      if ($oRegistroValido->valida_situacao =='PAGO'){
        $oGuiaPaga          = $oRegistroValido;
      } elseif ($oRegistroValido->valida_situacao == 'EM_ABERTO'){
        $oGuiaAberto        = $oRegistroValido;
      } else {
        $oGuiaSemIncidencia = $oRegistroValido;
      }

    }

    if (count($oGuiaPaga) > 0) {
      $aDados[] = $oGuiaPaga;
    } else {

      if (count($oGuiaAberto) > 0) {
        $aDados[] = $oGuiaAberto;
      } else {
        $aDados[] = $oGuiaSemIncidencia;
      }
    }
  }
}

$aRetorno = $aDados;
if ($iNumRows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=O filtro selecionado não retornou nenhum registro.');
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetFont('arial','b',8);
$pdf->SetFillColor(235);



if ( $oGet->modoimp == 'sint' ) {

	$iTotal      = 0;
	$iTotalGuia  = 0;
	$iTroca      = 1;
	$iAlt        = 4;
	$aTipo       = array();
	$aTipoValor  = array();
	$aDadosGuias = array();

	foreach ($aRetorno as $i => $oRetorno) {


	  if ($pdf->gety() > $pdf->h - 30 || $iTroca != 0 ){

	    $pdf->addpage("L");
	    $pdf->SetFont('arial','b',8);
	    $pdf->SetFillColor(215);
	    $pdf->cell(15,$iAlt,"Guia"                                                                            ,1,0,"C",1);
	    $pdf->cell(55,$iAlt,"Nome Adquirente"                                                                 ,1,0,"C",1);
	    $pdf->cell(15,$iAlt,"Numpre"                                                                          ,1,0,"C",1);
	    $pdf->cell(15,$iAlt,"Matrícula"                                                                       ,1,0,"C",1);
	    $pdf->cell(15,$iAlt,"Cgm"                                                                             ,1,0,"C",1);
	    $pdf->cell(20,$iAlt,"Valor"                                                                           ,1,0,"C",1);
	    $pdf->cell(20,$iAlt,"Situação"                                                                        ,1,0,"C",1);
	    $pdf->cell(20,$iAlt,"Liberada"                                                                        ,1,0,"C",1);
	    $pdf->cell(20,$iAlt,"Dtvenc"                                                                          ,1,0,"C",1);
	    $pdf->cell(50,$iAlt,"Transação"                                                                       ,1,0,"C",1);
	    $pdf->cell(30,$iAlt,"Urbano/Rural"                                                                    ,1,1,"C",1);
	    $pdf->cell(200,1,""                                                                                   ,0,1,"L",0);

	    $iTroca = 0;

	  }

	  if($i % 2 == 0){
	    $corfundo = 236;
	  }else{
	    $corfundo = 255;
	  }
	  $pdf->SetFillColor($corfundo);

	  $pdf->SetFont('arial','',6);
	  $pdf->cell(15,$iAlt,$oRetorno->it01_guia                                                                ,0,0,"C",1);
	  $pdf->cell(55,$iAlt,$oRetorno->z01_nome                                                                 ,0,0,"L",1);
	  $pdf->cell(15,$iAlt,$oRetorno->k00_numpre                                                               ,0,0,"C",1);
	  $pdf->cell(15,$iAlt,$oRetorno->matric                                                                   ,0,0,"C",1);
	  $pdf->cell(15,$iAlt,$oRetorno->cgm                                                                      ,0,0,"C",1);
	  $pdf->cell(20,$iAlt,db_formatar("$oRetorno->k00_valor",'f')                                             ,0,0,"R",1);

	  if (isset($oRetorno->it16_guia) and $oRetorno->it16_guia != "") {
	    $sTipo = "Cancelado";
	  } else if (isset($oRetorno->arrepaga)&& trim($oRetorno->arrepaga) != "") {
	    $sTipo = "Pago";
	  } else if ( $oRetorno->itbiliberada == "Sim" ) {
	    $sTipo = "Aberto";
	  } else {
	    $sTipo = "";
	  }

	  $pdf->cell(20,$iAlt,$sTipo                                                                              ,0,0,"C",1);

	  if (!isset($aTipo[$sTipo])) {
	    $aTipo[$sTipo] = 1;
	    $aTipoValor[$sTipo] = round($oRetorno->k00_valor,2);
	  } else {
	    $aTipo[$sTipo]++;
	    $aTipoValor[$sTipo] += round($oRetorno->k00_valor,2);
	  }

	  $pdf->cell(20,$iAlt,$oRetorno->itbiliberada                                                             ,0,0,"C",1);
	  $pdf->cell(20,$iAlt,db_formatar("$oRetorno->k00_dtvenc",'d')                                            ,0,0,"C",1);
	  $pdf->cell(50,$iAlt,substr($oRetorno->it04_descr,0,30)                                                  ,0,0,"L",1);
	  $pdf->cell(30,$iAlt,$oRetorno->urbanorural                                                              ,0,1,"L",1);

	  $oDadosGuias = new stdClass();
	  $oDadosGuias->CodGuia = $oRetorno->it01_guia;

	  if ( !isset($aDadosGuias[$oRetorno->it01_guia]) ) {

	  	$aDadosGuias[$oRetorno->it01_guia]['oCodGuia'] = $oDadosGuias;
	    $iTotalGuia++;
	  }

	  $iTotal++;
	}

	$pdf->SetFont('arial','b',8);
	$pdf->cell(0,$iAlt,"TOTAL DE REGISTROS  :  $iTotal"                                                     ,'T',0,"L",0);
	$pdf->ln(10);

	$pdf->SetFont('arial','b',8);
	$pdf->cell(0,$iAlt,"TOTAL DE GUIAS  :  $iTotalGuia"                                                     ,'T',0,"L",0);
	$pdf->ln(10);

	$pdf->cell(0,$iAlt,"TOTAIS POR TIPO"                                                                    ,'T',1,"L",0);

	foreach ($aTipo as $k => $v) {
	  $pdf->cell(55,$iAlt,$k,0,0,"L",1);
	  $pdf->cell(55,$iAlt,$v,0,0,"L",1);
	  $pdf->cell(55,$iAlt,db_formatar($aTipoValor[$k],'f'),0,1,"L",1);
	}
	$pdf->ln();
} else {

  $iAlt          = 4;
  $iTotal        = 0;
  $iTotalGuia    = 0;

  $lImprime      = true;
  $iPreencher    = true;
  $aTipo         = array();
  $aTipoValor    = array();
  $aDados        = array();
  $aDadosItbi    = array();

	foreach ($aRetorno as $i => $oRetorno) {

   // $oRetorno = db_utils::fieldsMemory($rsSql,$i);

    $oDadosItbi = new stdClass();
    $oDadosItbi->iGuia             = $oRetorno->it01_guia;
    $oDadosItbi->iNumpre           = $oRetorno->k00_numpre;
    $oDadosItbi->iMatric           = $oRetorno->matric;
    $oDadosItbi->nValor            = $oRetorno->k00_valor;
    $oDadosItbi->nValor            = $oRetorno->k00_valor;

    if (isset($oRetorno->it16_guia) and $oRetorno->it16_guia != "") {
      $oDadosItbi->sSituacao       = "Cancelado";
    } else if (isset($oRetorno->arrepaga)&& trim($oRetorno->arrepaga) != "") {
      $oDadosItbi->sSituacao       = "Pago";
    } else if ( $oRetorno->itbiliberada == "Sim" ) {
      $oDadosItbi->sSituacao       = "Aberto";
    } else {
      $oDadosItbi->sSituacao       = "";
    }

    $oDadosItbi->sLiberada         = $oRetorno->itbiliberada;
    $oDadosItbi->dtVenc            = $oRetorno->k00_dtvenc;
    $oDadosItbi->sTranzacao        = substr($oRetorno->it04_descr,0,30);
    $oDadosItbi->sTranzacao        = substr($oRetorno->it04_descr,0,30);
    $oDadosItbi->sUrbRural         = $oRetorno->urbanorural;

    $oDadosItbi->nValorDeclConstr  = $oRetorno->it01_valorconstr;
    $oDadosItbi->nValorDeclTerreno = $oRetorno->it01_valorterreno;
    $oDadosItbi->nValTotalDecl     = $oRetorno->it01_valortransacao;

    $oDadosItbi->nValorAvalConstr  = $oRetorno->it14_valoravalconstr;
    $oDadosItbi->nValorAvalTerreno = $oRetorno->it14_valoravalter;
    $oDadosItbi->nValTotalAval     = $oRetorno->it14_valoraval;

    $aDadosItbi[$oRetorno->it01_guia]['oDadosGuias'] = $oDadosItbi;

    if (  $oRetorno->it03_tipo == 'C' ) {
      $aDadosItbi[$oRetorno->it01_guia]['aAdquirentes'][]   = $oRetorno->z01_nome;
    } else {
	    $aDadosItbi[$oRetorno->it01_guia]['aTransmitentes'][] = $oRetorno->z01_nome;
    }

    if ( isset($oRetorno->it01_guia) ) {
      $iTotalGuia++;
    }

  }

  foreach ( $aDadosItbi as $iCodItbi => $aDados ) {

    imprimeCabecalho($pdf,$iAlt,$lImprime);
		$lImprime = false;

    if ($iPreencher == true) {
      $iPreencher = false;
    	$iCorFundo = 1;
    } else {
    	$iPreencher = true;
      $iCorFundo  = 0;
    }

    $pdf->cell(25,$iAlt,$aDados['oDadosGuias']->iGuia                                              ,0,0,"C",$iCorFundo);
    $pdf->cell(25,$iAlt,$aDados['oDadosGuias']->iNumpre                                            ,0,0,"C",$iCorFundo);
    $pdf->cell(25,$iAlt,$aDados['oDadosGuias']->iMatric                                            ,0,0,"C",$iCorFundo);
    $pdf->cell(30,$iAlt,db_formatar($aDados['oDadosGuias']->nValor,'f')                            ,0,0,"C",$iCorFundo);
    $pdf->cell(30,$iAlt,$aDados['oDadosGuias']->sSituacao                                          ,0,0,"C",$iCorFundo);
    $pdf->cell(30,$iAlt,$aDados['oDadosGuias']->sLiberada                                          ,0,0,"C",$iCorFundo);
    $pdf->cell(30,$iAlt,db_formatar($aDados['oDadosGuias']->dtVenc,'d')                            ,0,0,"C",$iCorFundo);
    $pdf->cell(52,$iAlt,$aDados['oDadosGuias']->sTranzacao                                         ,0,0,"C",$iCorFundo);
    $pdf->cell(32,$iAlt,$aDados['oDadosGuias']->sUrbRural                                          ,0,1,"C",$iCorFundo);

    $pdf->cell(75,$iAlt,""                                                                         ,0,0,"C",$iCorFundo);
    $pdf->cell(34,$iAlt,db_formatar($aDados['oDadosGuias']->nValorDeclConstr,'f')                  ,0,0,"C",$iCorFundo);
    $pdf->cell(34,$iAlt,db_formatar($aDados['oDadosGuias']->nValorDeclTerreno,'f')                 ,0,0,"C",$iCorFundo);
    $pdf->cell(34,$iAlt,db_formatar($aDados['oDadosGuias']->nValTotalDecl,'f')                     ,0,0,"C",$iCorFundo);
    $pdf->cell(34,$iAlt,db_formatar($aDados['oDadosGuias']->nValorAvalConstr,'f')                  ,0,0,"C",$iCorFundo);
    $pdf->cell(34,$iAlt,db_formatar($aDados['oDadosGuias']->nValorAvalTerreno,'f')                 ,0,0,"C",$iCorFundo);
    $pdf->cell(34,$iAlt,db_formatar($aDados['oDadosGuias']->nValTotalAval,'f')                     ,0,1,"C",$iCorFundo);

    if (!isset($aTipo[$aDados['oDadosGuias']->sSituacao])) {
      $aTipo[$aDados['oDadosGuias']->sSituacao] = 1;
      $aTipoValor[$aDados['oDadosGuias']->sSituacao] = round($aDados['oDadosGuias']->nValor,2);
    } else {
      $aTipo[$aDados['oDadosGuias']->sSituacao]++;
      $aTipoValor[$aDados['oDadosGuias']->sSituacao] += round($aDados['oDadosGuias']->nValor,2);
    }

    $iYPosIni = $pdf->GetY();

    if (isset($aDados['aAdquirentes'])) {

	    foreach ( $aDados['aAdquirentes'] as $iInd => $aDadosAdquir ) {

	      imprimeCabecalho($pdf,$iAlt);
	      $pdf->cell(139.5,$iAlt,$aDadosAdquir                                                                ,0,1,"C",$iCorFundo);
	    }
    }

    $iYAdquirentes = $pdf->GetY();

    if ( $pdf->GetY() > $iYPosIni ) {
      $pdf->SetY($iYPosIni);
    }

    if (isset($aDados['aTransmitentes'])) {

	    foreach ( $aDados['aTransmitentes'] as $iInd => $aDadosTrans ) {

	      imprimeCabecalho($pdf,$iAlt);
	    	$pdf->SetX(149.5);
	      $pdf->cell(139.5,$iAlt,$aDadosTrans                                                                 ,0,1,"C",$iCorFundo);
	    }
    }

    if ( $iYAdquirentes > $pdf->GetY() ) {
    	$pdf->SetY($iYAdquirentes);
    }

    $iTotal++;

  }

  $pdf->cell(280,$iAlt,""                                                                                   ,0,1,"L",0);

  $pdf->SetFont('arial','b',8);
  $pdf->cell(0,$iAlt,"TOTAL DE REGISTROS  :  $iTotalGuia"                                                     ,'T',0,"L",0);
  $pdf->ln(10);

  $pdf->SetFont('arial','b',8);
  $pdf->cell(0,$iAlt,"TOTAL DE GUIAS  :  $iTotal"                                                     ,'T',0,"L",0);
  $pdf->ln(10);

  $pdf->cell(0,$iAlt,"TOTAIS POR TIPO"                                                                    ,'T',1,"L",0);

  foreach ($aTipo as $xInd => $oValor) {

  	if ( !empty($xInd) ) {
  		$pdf->cell(55,$iAlt,$xInd,0,0,"L",0);
  	} else {
  	  $pdf->cell(55,$iAlt,"Outros",0,0,"L",0);
  	}

    $pdf->cell(55,$iAlt,$oValor,0,0,"L",0);
    $pdf->cell(55,$iAlt,db_formatar($aTipoValor[$xInd],'f'),0,1,"L",0);

  }

  $pdf->ln();
}

$pdf->output();

function imprimeCabecalho($pdf,$iAlt,$lImprime=false) {

  if ( $pdf->gety() > $pdf->h - 30 || $lImprime ) {

    $pdf->addpage("L");
    $pdf->SetFont('arial','b',8);

    $pdf->cell(25,$iAlt,"Guia"                                                                              ,1,0,"C",1);
    $pdf->cell(25,$iAlt,"Numpre"                                                                            ,1,0,"C",1);
    $pdf->cell(25,$iAlt,"Matrícula"                                                                         ,1,0,"C",1);
    $pdf->cell(30,$iAlt,"Valor"                                                                             ,1,0,"C",1);
    $pdf->cell(30,$iAlt,"Situação"                                                                          ,1,0,"C",1);
    $pdf->cell(30,$iAlt,"Liberada"                                                                          ,1,0,"C",1);
    $pdf->cell(30,$iAlt,"Dtvenc"                                                                            ,1,0,"C",1);
    $pdf->cell(52,$iAlt,"Transação"                                                                         ,1,0,"C",1);
    $pdf->cell(32,$iAlt,"Urbano/Rural"                                                                      ,1,1,"C",1);

    $pdf->cell(75,$iAlt,""                                                                                  ,1,0,"C",1);
    $pdf->cell(102,$iAlt,"Valor Declarado"                                                                  ,1,0,"C",1);
    $pdf->cell(102,$iAlt,"Valor Avaliado"                                                                   ,1,1,"C",1);

    $pdf->cell(75,$iAlt,""                                                                                  ,1,0,"C",1);
    $pdf->cell(34,$iAlt,"Construção"                                                                        ,1,0,"C",1);
    $pdf->cell(34,$iAlt,"Terreno"                                                                           ,1,0,"C",1);
    $pdf->cell(34,$iAlt,"Total"                                                                             ,1,0,"C",1);
    $pdf->cell(34,$iAlt,"Construção"                                                                        ,1,0,"C",1);
    $pdf->cell(34,$iAlt,"Terreno"                                                                           ,1,0,"C",1);
    $pdf->cell(34,$iAlt,"Total"                                                                             ,1,1,"C",1);

    $pdf->cell(139.5,$iAlt,"Nome Adquirente"                                                                ,1,0,"C",1);
    $pdf->cell(139.5,$iAlt,"Nome Transmitente"                                                              ,1,1,"C",1);

    $pdf->cell(279,1,""                                                                                     ,0,1,"L",0);
    $pdf->SetFont('arial','',8);

  }
}
?>
