<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once('fpdf151/pdf.php');
require_once('libs/db_sql.php');
require_once('libs/db_utils.php');
require_once('classes/db_matmater_classe.php');

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
$nValorTotalAval  = 0;


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

if ( isset($oGet->sSetorLoc) && $oGet->sSetorLoc != "") {
  $sWhere  .= " {$sAnd} j05_codigoproprio = '" . $oGet->sSetorLoc . "'";
  $sAnd     = " and ";
}

if ( isset($oGet->sQuadraLoc) && $oGet->sQuadraLoc != "" ) {
  $sWhere  .= " {$sAnd} j06_quadraloc = '" . str_pad($oGet->sQuadraLoc,4,"0",STR_PAD_LEFT)."'";
  $sAnd     = " and ";
}

if ( isset($oGet->sLoteLoc) && $oGet->sLoteLoc != "" ) {
  $sWhere  .= " {$sAnd} j06_lote = '" . str_pad($oGet->sLoteLoc,4,"0",STR_PAD_LEFT)."'";
  $sAnd     = " and ";
}


if ( $oGet->situacao == "2" ) {
  
  $sHeaderSituacao = "SITUAÇÃO : ABERTO"; 
//   $sWhere         .= " {$sAnd} arrepaga.k00_numpre is null";
     $sWhere         .= " {$sAnd} it16_guia is null";
     $sAnd            = " and ";
} else if ( $oGet->situacao == "3" ) {
  
  $sHeaderSituacao = "SITUAÇÃO : PAGO";
//  $sWhere         .= " {$sAnd} arrepaga.k00_numpre is not null";
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


if( $oGet->ordem == 'log' ) {
  
  $sHeaderOrdem = "ORDEM : LOGRADOURO ";
  $sOrdem       = " logradouro ";
} else if ( $oGet->ordem == 's' ) {
  
  $sHeaderOrdem = "ORDEM : SETOR ";
  $sOrdem       = " j34_setor ";
} else if ( $oGet->ordem == 'q' ) {
  
  $sHeaderOrdem = "ORDEM : QUADRA ";
  $sOrdem       = " j34_quadra ";
} else if ( $oGet->ordem == 'lot' ) {
  
  $sHeaderOrdem = "ORDEM : LOTE ";
  $sOrdem       = " j34_lote ";
} else if ( $oGet->ordem == 'g' ) {
  
  $sHeaderOrdem = "ORDEM : GUIA";
  $sOrdem       = " itbi.it01_guia ";
}




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
$sSql  = "    select *                                                                                              \n";
$sSql .= "      from (                                                                                              \n";
$sSql .= "             select it01_guia,                                                                            \n";
$sSql .= "                    it04_descr,                                                                           \n";
$sSql .= "                    recibo.k00_numpre,                                                                    \n";
$sSql .= "                    arrepaga.k00_numpre as arrepaga,                                                      \n";
$sSql .= "                    it06_matric as matric,                                                                \n";
$sSql .= "                    case                                                                                  \n";
$sSql .= "                      when it21_numcgm is not null then it21_numcgm                                       \n"; 
$sSql .= "                      else it02_numcgm                                                                    \n"; 
$sSql .= "                    end as cgm,                                                                           \n";
$sSql .= "                    case                                                                                  \n";
$sSql .= "                      when it14_guia is not null then 'Sim'                                               \n"; 
$sSql .= "                      else 'Não'                                                                          \n"; 
$sSql .= "                    end as itbiliberada,                                                                  \n";
$sSql .= "                    case                                                                                  \n";
$sSql .= "                      when it05_guia is not null then 'Urbano'                                            \n"; 
$sSql .= "                      else 'Rural'                                                                        \n"; 
$sSql .= "                    end as urbanorural,                                                                   \n";
$sSql .= "                    case                                                                                  \n";
$sSql .= "                      when itbidadosimovel.it22_itbi is not null then it22_descrlograd                    \n";
$sSql .= "                      else itbirural.it18_nomelograd                                                      \n";
$sSql .= "                    end as logradouro,                                                                    \n";
$sSql .= "                    recibo.k00_valor,                                                                     \n";
$sSql .= "                    recibo.k00_dtvenc,                                                                    \n";
$sSql .= "                    it03_nome as z01_nome,                                                                \n";
$sSql .= "                    coalesce(itbi.it01_valorconstr          ,0) as it01_valorconstr,                      \n";
$sSql .= "                    coalesce(itbi.it01_valorterreno         ,0) as it01_valorterreno,                     \n";
$sSql .= "                    coalesce(itbi.it01_valortransacao       ,0) as it01_valortransacao,                   \n";
$sSql .= "                    coalesce(itbiavalia.it14_valoravalconstr,0) as it14_valoravalconstr,                  \n";
$sSql .= "                    coalesce(itbiavalia.it14_valoravalter   ,0) as it14_valoravalter,                     \n";
$sSql .= "                    coalesce(itbiavalia.it14_valoraval      ,0) as it14_valoraval,                        \n";
$sSql .= "                    itbinome.it03_tipo,                                                                   \n";
$sSql .= "                    it16_guia, it22_descrlograd, it22_numero, it22_compl ,j34_setor,j34_quadra,j34_lote,  \n";
$sSql .= "                    case when arrepaga.k00_numpre is not null                                             \n";
$sSql .= "                      then 'PAGO'                                                                         \n";
$sSql .= "                        else                                                                        			\n";
$sSql .= "											  case when recibo.k00_valor > 0                                                    \n";
$sSql .= "											    then 'EM_ABERTO'                                                                \n";
$sSql .= "											    else 'NAO_INCIDE'                                                               \n";
$sSql .= "											  end                                                                               \n";
$sSql .= "										end as valida_situacao                                                               \n";
$sSql .= "               from itbi                                                                                  \n";
$sSql .= "                    inner join itbitransacao     on it04_codigo                = it01_tipotransacao       \n";
$sSql .= "                    inner join itbinome          on itbinome.it03_guia         = itbi.it01_guia           \n";
$sSql .= "                    left  join itburbano         on itburbano.it05_guia        = itbi.it01_guia           \n";
$sSql .= "                    left  join itbirural         on itbirural.it18_guia        = itbi.it01_guia           \n";
$sSql .= "                    left  join itbicancela       on itbicancela.it16_guia      = itbi.it01_guia           \n";
$sSql .= "                    left  join itbiavalia        on itbiavalia.it14_guia       = itbi.it01_guia           \n";
$sSql .= "                    left  join itbinomecgm       on itbinomecgm.it21_itbinome  = itbi.it01_guia           \n";
$sSql .= "                    left  join itbicgm           on itbicgm.it02_guia          = itbi.it01_guia           \n";
$sSql .= "                    left  join itbidadosimovel   on itbidadosimovel.it22_itbi  = itbi.it01_guia           \n";
$sSql .= "                    left  join itbimatric        on itbi.it01_guia             = itbimatric.it06_guia     \n";
$sSql .= "                    left  join iptubase          on it06_matric                = j01_matric               \n";
$sSql .= "                    left  join lote              on j34_idbql                  = j01_idbql                \n";
$sSql .= "                    left  join loteloc           on j06_idbql                  = j01_idbql                \n";
$sSql .= "                    left  join setorloc          on j05_codigo                 = j06_setorloc             \n";
$sSql .= "                    left  join cgm               on z01_numcgm                 = it21_numcgm              \n";
$sSql .= "                    left  join itbinumpre        on itbinumpre.it15_guia       = itbi.it01_guia           \n";
// $sSql .= "                                                and itbinumpre.it15_ultimaguia is true                 \n ";
$sSql .= "                    left  join recibo            on recibo.k00_numpre          = it15_numpre              \n";
$sSql .= "                    left  join arrepaga          on arrepaga.k00_numpre        = itbinumpre.it15_numpre   \n";
$sSql .= "          {$sWhere}                                                                                       \n"; 
$sSql .= "           order by {$sOrdem}                                                                             \n";
$sSql .= "           ) as x                                                                                         \n";
$sSql .= "           {$sWhereLograd}                                                                                \n"; 

$rsSql     = pg_query($sSql);
$iNumRows  = pg_num_rows($rsSql);


$aRetorno  = db_utils::getColectionByRecord($rsSql);

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
 * Filtra os dados de guias repetidas validando a situação de cada uma 
 * 
 */
foreach ($aCompara as $aGuias){

  foreach ($aGuias as $aTipoRegistro) {

    foreach ($aTipoRegistro as $oRegistroValido){

      if ($oRegistroValido->valida_situacao =='PAGO'){
        $oGuiaPaga         = $oRegistroValido;
      } elseif ($oRegistroValido->valida_situacao == 'EM_ABERTO'){
        $oGuiaAberto        = $oRegistroValido;
      } else {
        $oGuiaSemIncidencia = $oRegistroValido;
      }
    }
    
    /**
     * Valida qual variável foi setada para ser adicionada ao ARRAY que será
     * impresso no relatório.
     */
    if (!is_null($oGuiaPaga)) {
      $aDados[] = $oGuiaPaga;
    } else {

      if (!is_null($oGuiaAberto)) {
        $aDados[] = $oGuiaAberto;
      } else {
        $aDados[] = $oGuiaSemIncidencia;
      }
    }
    /**
     * Limpa as variáveis para não armazenar valores repetidos nas linhas do array que serão impressos.
     */
    $oGuiaPaga          = null;
    $oGuiaAberto        = null;
    $oGuiaSemIncidencia = null;
  }
}
$aRetorno = $aDados;
$aFiltro  = array();
/**
 * Filtra conforme seleção no formulário
 */
foreach ($aRetorno as $oFiltro) {
  
  if($oGet->situacao == "2" && $oFiltro->valida_situacao == "EM_ABERTO"){
    $aFiltro[] = $oFiltro;
  } elseif ($oGet->situacao == "3" && $oFiltro->valida_situacao == "PAGO"){
    $aFiltro[] = $oFiltro;
  } elseif($oGet->situacao == "1"){
    $aFiltro[] = $oFiltro;
  }
}
$aRetorno = $aFiltro;
// echo "<PRE>";
// echo $oGet->situacao;
// print_r($aFiltro);
// exit;
if ($iNumRows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=O filtro selecionado não retornou nenhum registro.');
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetFont('arial','b',8);
$pdf->SetFillColor(235);

$iTotal = 0;
$iTroca = 1;
$iAlt   = 4;
$aGuias = array();  
foreach ($aRetorno as $i => $oRetorno) {
    
    if ($pdf->gety() > $pdf->h - 30 || $iTroca != 0 ){
      
      $pdf->addpage("L");
      $pdf->SetFont('arial','b',8);
      $pdf->SetFillColor(215);
      $pdf->cell(15,$iAlt,"Guia"                                                                            ,1,0,"C",1); 
      $pdf->cell(15,$iAlt,"Matrícula"                                                                       ,1,0,"C",1);
      $pdf->cell(65,$iAlt,"Nome"                                                                            ,1,0,"C",1);
      $pdf->cell(65,$iAlt,"Logradouro"                                                                      ,1,0,"C",1);
      $pdf->cell(15,$iAlt,"Número"                                                                          ,1,0,"C",1);
      $pdf->cell(35,$iAlt,"Comp"                                                                            ,1,0,"C",1); 
      $pdf->cell(35,$iAlt,"Setor / Quadra / Lote"                                                           ,1,0,"C",1); 
      $pdf->cell(35,$iAlt,"Valor Total Avaliado"                                                            ,1,1,"C",1); 
      $pdf->Ln(2);
      
      $iTroca = 0;
      
    }
    
    if($i % 2 == 0){
      $corfundo = 236;
    } else {
      $corfundo = 255;
    }
    
    $pdf->SetFillColor($corfundo);
    
    $pdf->SetFont('arial','',6);
    $pdf->cell(15,$iAlt,$oRetorno->it01_guia                                                                ,0,0,"C",1);
    $pdf->cell(15,$iAlt,$oRetorno->matric                                                                   ,0,0,"C",1);
    $pdf->cell(65,$iAlt,$oRetorno->z01_nome                                                                 ,0,0,"L",1); 
    $pdf->cell(65,$iAlt,$oRetorno->it22_descrlograd                                                         ,0,0,"L",1); 
    $pdf->cell(15,$iAlt,$oRetorno->it22_numero                                                              ,0,0,"C",1); 
    $pdf->cell(35,$iAlt,$oRetorno->it22_compl                                                               ,0,0,"L",1); 
    $pdf->cell(35,$iAlt,$oRetorno->j34_setor." / ".$oRetorno->j34_quadra." / ".$oRetorno->j34_lote          ,0,0,"C",1); 
    $pdf->cell(35,$iAlt,db_formatar( $oRetorno->it14_valoraval , "f")                                       ,0,1,"R",1);  
    
    if (!in_array($oRetorno->it01_guia, $aGuias)) {
    	$aGuias[] = $oRetorno->it01_guia;
      $nValorTotalAval +=$oRetorno->it14_valoraval;
    } 
    
    $iTotal++;
    
}
  
$nValorTotalAval = db_formatar( $nValorTotalAval , "f");
  
$pdf->SetFont('arial','b',8);
$pdf->cell(0,$iAlt,"TOTAL DE REGISTROS  :  $iTotal"                                                     ,'T',0,"L",0);
$pdf->ln(10);
  
$pdf->SetFont('arial','b',8);
$pdf->cell(0,$iAlt,"VALOR TOTAL DAS AVALIAÇÕES :  $nValorTotalAval"                                     ,'T',0,"L",0);
$pdf->ln(10);

$pdf->output();

?>