<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oGet                             = db_utils::postmemory($_GET);

$oGet->arqarretipo                = str_replace(",","','", $oGet->arqarretipo);
$oGet->aCaracteristicasPeculiares = str_replace(",","','", $oGet->aCaracteristicasPeculiares);

$iDBinstit                        = db_getsession('DB_instit');
$dtIni                            = $oGet->datai;
$dtFim                            = $oGet->dataf;
$orderPeculiar                    = "";
$aCaracteristicasPeculiares       = $oGet->aCaracteristicasPeculiares;
$iFiltroConCarPeculiar            = $oGet->iFiltroConCarPeculiar;

//se for normal... usa so a ordem
// se for renuncia ou todos e agrupa por carac.peculiar... ordena primeiro pela Carac.
if($oGet->tipoDebito != 1 and $oGet->agrupar == "CP"){
  $orderPeculiar = "c58_sequencial,";
}

switch ($oGet->selordem) {

  case "d":
    $orderby   = "order by $orderPeculiar k23_data, k00_numcgm, k00_matric, k00_inscr, arrecant.k00_numpre,
                           arrecant.k00_numpar, arrecant.k00_receit, arrecant.k00_hist ";
    $headOrdem = "Ordenado Por Data";
  break;

  case "c":
    $orderby   = "order by $orderPeculiar k00_numcgm, k00_matric, k00_inscr, k23_data, arrecant.k00_numpre,
                           arrecant.k00_numpar, arrecant.k00_receit, arrecant.k00_hist ";
    $headOrdem = "Ordenado Por CGM";
  break;

  case "m":
    $orderby   = "order by $orderPeculiar k00_matric, k00_numcgm, k00_inscr, k23_data, arrecant.k00_numpre,
                           arrecant.k00_numpar, arrecant.k00_receit, arrecant.k00_hist ";
    $headOrdem = "Ordenado Por Matrícula";
  break;

  case "i":
    $orderby   = "order by $orderPeculiar k00_inscr, k00_numcgm, k00_matric, k23_data, arrecant.k00_numpre,
                           arrecant.k00_numpar, arrecant.k00_receit, arrecant.k00_hist ";
    $headOrdem = "Ordenado Por Inscrição";
  break;
}

$where = "";

if($oGet->z01_numcgm){
  $where .= "and z01_numcgm = ".$oGet->z01_numcgm;
}
if($oGet->j01_matric){
  $where .= "and k00_matric = ".$oGet->j01_matric;
}
if($oGet->q02_inscr){
  $where .= "and k00_inscr  = ".$oGet->q02_inscr;
}
if($oGet->arqarretipo){
  $where .= "and arretipo.k00_tipo IN ('".$oGet->arqarretipo."')";
}

$campo                 = "";
$sSqlLeftCarPerculiar  = "";
$sSqlWhereCarPerculiar = "";

if($oGet->tipoDebito){

  if($oGet->tipoDebito != 3){
    $where .= " and k23_cancdebitostipo = ".$oGet->tipoDebito ;
  }

  if( $oGet->tipoDebito == 2 && !empty($aCaracteristicasPeculiares) ){

    $sSqlWhereCarPerculiarOperador = "NOT IN";
    if( $iFiltroConCarPeculiar == 1 ){
      $sSqlWhereCarPerculiarOperador = "IN";
    }

    $sSqlWhereCarPerculiar = " and c58_sequencial {$sSqlWhereCarPerculiarOperador} ( '{$aCaracteristicasPeculiares}' )";
  }

  $sSqlLeftCarPerculiar  = " left join cancdebitosprocconcarpeculiar on k74_cancdebitosproc = k23_codigo     ";
  $sSqlLeftCarPerculiar .= " left join concarpeculiar                on k74_concarpeculiar  = c58_sequencial ";
  $campo                 = " c58_sequencial, c58_descr,";
}

$sSqlCancProc  = " select  arrecant.k00_numcgm,                                                                         ";

$sSqlCancProc .= "         cgm.z01_nome,                                                                       ";
$sSqlCancProc .= "         cgm.z01_ender,                                                                      ";
$sSqlCancProc .= "         cgm.z01_numero,                                                                     ";
$sSqlCancProc .= "         cgm.z01_compl,                                                                      ";
$sSqlCancProc .= "         cgm.z01_munic,                                                                      ";
$sSqlCancProc .= "         cgm.z01_uf,                                                                         ";
$sSqlCancProc .= "         cgm.z01_telef,                                                                      ";

$sSqlCancProc .= "         k00_matric,                                                                         ";
$sSqlCancProc .= "         k00_inscr,                                                                          ";

$sSqlCancProc .= "         cadtipo.k03_tipo,                                                                   ";
$sSqlCancProc .= "         cadtipo.k03_descr,                                                                  ";
$sSqlCancProc .= "         tipoproced.v07_sequencial,                                                          ";
$sSqlCancProc .= "         tipoproced.v07_descricao,                                                           ";

$sSqlCancProc .= "         arrecant.k00_numpre,                                                                ";
$sSqlCancProc .= "         arrecant.k00_numpar,                                                                ";
$sSqlCancProc .= "         arrecant.k00_receit,                                                                ";
$sSqlCancProc .= "         arrecant.k00_hist,                                                                  ";
$sSqlCancProc .= "         arrecant.k00_tipo,                                                                  ";
$sSqlCancProc .= "         k00_valor,                                                                          ";
$sSqlCancProc .= "         k24_vlrcor,                                                                         ";
$sSqlCancProc .= "         k24_juros,                                                                          ";
$sSqlCancProc .= "         k24_multa,                                                                          ";

$sSqlCancProc .= "         case                                                                                ";
$sSqlCancProc .= "           when cadtipo.k03_tipo = 5 then v01_exerc                                          ";
$sSqlCancProc .= "           when cadtipo.k03_tipo = 3 then q05_ano                                            ";
$sSqlCancProc .= "           else extract(year from k00_dtoper)                                                ";
$sSqlCancProc .= "         end as exerc,                                                                       ";

$sSqlCancProc .= "         k24_desconto,                                                                       ";
$sSqlCancProc .= "         k24_vlrcor+k24_juros+k24_multa-k24_desconto as total,                               ";
$sSqlCancProc .= "         k00_descr,                                                                          ";
$sSqlCancProc .= "         k02_codigo,                                                                         ";
$sSqlCancProc .= "         k02_drecei,                                                                         ";
$sSqlCancProc .= "         k23_codigo,                                                                         ";
$sSqlCancProc .= "         login,                                                                              ";
$sSqlCancProc .= "         k23_data,                                                                           ";
$sSqlCancProc .= "         {$campo}                                                                            ";
$sSqlCancProc .= "         k23_obs,                                                                            ";
$sSqlCancProc .= "         k21_codigo                                                                          ";
$sSqlCancProc .= "    from cancdebitosproc                                                                     ";
$sSqlCancProc .= "         {$sSqlLeftCarPerculiar}                                                             ";
$sSqlCancProc .= "         inner join cancdebitosprocreg on k23_codigo                = k24_codigo             ";
$sSqlCancProc .= "         inner join cancdebitosreg     on k24_cancdebitosreg        = k21_sequencia          ";
$sSqlCancProc .= "         inner join arrecant           on k21_numpre                = arrecant.k00_numpre    ";
$sSqlCancProc .= "                                      and k21_numpar                = arrecant.k00_numpar    ";
$sSqlCancProc .= "                                      and ( case                                             ";
$sSqlCancProc .= "                                              when k21_receit <> 0 then                      ";
$sSqlCancProc .= "                                                k21_receit = k00_receit                      ";
$sSqlCancProc .= "                                              else                                           ";
$sSqlCancProc .= "                                                true                                         ";
$sSqlCancProc .= "                                            end )                                            ";
$sSqlCancProc .= "         inner join arreinstit a       on a.k00_numpre              = arrecant.k00_numpre    ";
$sSqlCancProc .= "                                      and a.k00_instit              = {$iDBinstit}           ";
$sSqlCancProc .= "         inner join arretipo           on arretipo.k00_tipo         = arrecant.k00_tipo      ";
$sSqlCancProc .= "         inner join cadtipo            on arretipo.k03_tipo         = cadtipo.k03_tipo       ";
$sSqlCancProc .= "         inner join cgm                on z01_numcgm                = arrecant.k00_numcgm    ";

$sSqlCancProc .= "         left  join arrenumcgm         on arrenumcgm.k00_numpre     = arrecant.k00_numpre    ";
$sSqlCancProc .= "                                      and arrenumcgm.k00_numcgm     = arrecant.k00_numcgm    ";

$sSqlCancProc .= "         left  join arrematric         on arrematric.k00_numpre     = arrecant.k00_numpre    ";
$sSqlCancProc .= "         left  join arreinscr          on arreinscr.k00_numpre      = arrecant.k00_numpre    ";
$sSqlCancProc .= "         inner join tabrec             on k02_codigo                = arrecant.k00_receit    ";
$sSqlCancProc .= "         inner join db_usuarios        on id_usuario                = k23_usuario            ";
$sSqlCancProc .= "         left join divida              on divida.v01_numpre         = arrecant.k00_numpre    ";
$sSqlCancProc .= "                                      and divida.v01_numpar         = arrecant.k00_numpar    ";
$sSqlCancProc .= "         left join proced              on proced.v03_codigo         = divida.v01_proced      ";
$sSqlCancProc .= "         left join tipoproced          on tipoproced.v07_sequencial = proced.v03_tributaria  ";
$sSqlCancProc .= "         left join issvar              on issvar.q05_numpre         = arrecant.k00_numpre    ";
$sSqlCancProc .= "                                      and issvar.q05_numpar         = arrecant.k00_numpar    ";

$sSqlCancProc .= "   where k23_data between '{$dtIni}' and '{$dtFim}'                                          ";
$sSqlCancProc .= "     and k00_valor <> 0                                                                      ";
$sSqlCancProc .= "  {$sSqlWhereCarPerculiar}                                                                   ";
$sSqlCancProc .= "  {$where}                                                                                   ";
$sSqlCancProc .= "  {$orderby}                                                                                 ";
//dieSql($sSqlCancProc);
$rsCancProc  = db_query($sSqlCancProc);
$iCancProc   = pg_num_rows($rsCancProc);

if($iCancProc == 0){

  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  exit;
}

$head2 = "Relatório de Débitos Cancelados";
$head3 = "De ".db_formatar($oGet->datai,"d")." á ".db_formatar($oGet->dataf,"d");
$head4 = $headOrdem;

/**
 * Tipo de seleção do relatorio
 */
switch ($oGet->seltipo) {

  case "r":
    $sShowTipoSelecao = "Resumido por tipo";
  break;

  case "rc":
    $sShowTipoSelecao = "Resumido por contribuinte";
  break;

  default:
    $sShowTipoSelecao = "Completo";
  break;
}
$head5 = "Tipo: " . $sShowTipoSelecao;

/**
 * Tipo de Cancelamento selecionado
 */
switch ($oGet->tipoDebito) {
  case 1:
    $sShowTipoCancelamento = "Normal";
  break;

  case 2:
    $sShowTipoCancelamento = "Renúncia";
  break;

  default:
    $sShowTipoCancelamento = "Todos";
  break;
}
$head6 = "Tipo de cancelamento: " . $sShowTipoCancelamento;

if($oGet->tipoDebito != 1){
  $head7 = "Agrupado por:".($oGet->agrupar=="N"?" Nenhum":" Característica Peculiar");
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->addpage("L");

$alt   = 5;
$fonte = 7;
$iList = 1;

$lPreecher           = false;
$lImprimeCab         = false;
$lImprimeSubTotal    = false;

$aDadosCancProc      = array();
$aDados              = array();
$aCabecalho          = array();
$aTotalMatric        = array();
$aTotalInscr         = array();
$aTotalCgm           = array();
$aDadosResTipoDeb    = array();
$aDadosResTipoProced = array();
$aDadosResTipoReceit = array();
$aCanceladoTotalGeral =array();

$sPeculiar           = "";

$codProc             = null;
$AuxCaracPeculiar    = null;

$iNroMatric          = 0;
$iNroInscr           = 0;
$iNroCgm             = 0;
$TotalVlrHist        = 0;
$TotalVlrCorr        = 0;
$TotalVlrMulta       = 0;
$TotalVlrJuros       = 0;
$Total               = 0;
$GeralVlrHist        = 0;
$GeralVlrCorr        = 0;
$GeralVlrMulta       = 0;
$GeralVlrJuros       = 0;
$GeralTotal          = 0;
$TotalVlrHistCP      = 0;
$TotalVlrCorrCP      = 0;
$TotalVlrMultaCP     = 0;
$TotalVlrJurosCP     = 0;
$TotalCP             = 0;

for( $iInd = 0; $iInd < $iCancProc; $iInd++ ) {

  $oCancProc = db_utils::fieldsMemory($rsCancProc,$iInd);

  $oDadosPrincipal               = new stdClass();
  $oDadosPrincipal->iNumCgm      = $oCancProc->k00_numcgm;
  $oDadosPrincipal->sNomeRazao   = $oCancProc->z01_nome;

  $oDadosPrincipal->sEndereco    = $oCancProc->z01_ender;
  $oDadosPrincipal->iNumero      = $oCancProc->z01_numero;
  $oDadosPrincipal->sComplemento = $oCancProc->z01_compl;
  $oDadosPrincipal->sMunicipio   = $oCancProc->z01_munic;
  $oDadosPrincipal->sUf          = $oCancProc->z01_uf;
  $oDadosPrincipal->iFone        = $oCancProc->z01_telef;

  $oDadosPrincipal->iMatric      = $oCancProc->k00_matric;
  $oDadosPrincipal->iInscr       = $oCancProc->k00_inscr;

  $oDadosItens                   = new stdClass();
  $oDadosItens->iSeq             = $oCancProc->c58_sequencial;
  $oDadosItens->dtData           = $oCancProc->k23_data;
  $oDadosItens->Valor            = $oCancProc->k00_valor;
  $oDadosItens->VlrCor           = $oCancProc->k24_vlrcor;
  $oDadosItens->VlrMulta         = $oCancProc->k24_multa;
  $oDadosItens->VlrJuro          = $oCancProc->k24_juros;
  $oDadosItens->VlrTotal         = $oCancProc->total;
  $oDadosItens->iCod             = $oCancProc->k23_codigo;
  $oDadosItens->iNumPre          = $oCancProc->k00_numpre;
  $oDadosItens->iNumPar          = $oCancProc->k00_numpar;
  $oDadosItens->iExerc           = $oCancProc->exerc;
  $oDadosItens->iReceit          = $oCancProc->k02_codigo;
  $oDadosItens->sDescrRecei      = $oCancProc->k02_drecei;
  $oDadosItens->sDescr           = $oCancProc->k00_descr;
  $oDadosItens->iCodCancelado    = $oCancProc->k21_codigo;

  if($oCancProc->c58_sequencial != ""){
    $sPeculiar = $oCancProc->c58_sequencial."-".$oCancProc->c58_descr;
  }

  if($codProc != $oCancProc->k23_codigo){
    $obsCancProc  = $oCancProc->k23_obs;
    $codProc      = $oCancProc->k23_codigo;
  }

  $oDadosItens->sPeculiar        = $sPeculiar;
  $oDadosItens->sLogin           = $oCancProc->login;
  $oDadosItens->sObsCancProc     = $obsCancProc;

  $aIndiceCancProc = array();

  $aIndiceCancProc[] = $oCancProc->k00_numcgm;
  if ( isset( $oCancProc->k00_matric ) && !empty( $oCancProc->k00_matric ) ) {
    $aIndiceCancProc[] = $oCancProc->k00_matric;
  }

  if ( isset( $oCancProc->k00_inscr ) && !empty( $oCancProc->k00_inscr ) ) {
    $aIndiceCancProc[] = $oCancProc->k00_inscr;
  }

  $sIndiceCancProc = implode( "_", $aIndiceCancProc );

  if ( !isset($aDadosCancProc[$sIndiceCancProc]) ) {
    $aDadosCancProc[$sIndiceCancProc]['oDadosCancProc'] = $oDadosPrincipal;
    $aDadosCancProc[$sIndiceCancProc]['aListaItens'][]  = $oDadosItens;
  } else {
    $aDadosCancProc[$sIndiceCancProc]['aListaItens'][]  = $oDadosItens;
  }

  if(isset($aDadosResTipoDeb[$oCancProc->k03_tipo])){

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){
      $aDadosResTipoDeb[$oCancProc->k03_tipo]['Vlr' ]   += $oCancProc->total;
    }
  }else{

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

      $aDadosResTipoDeb[$oCancProc->k03_tipo]['Vlr' ]    = $oCancProc->total;
      $aDadosResTipoDeb[$oCancProc->k03_tipo]['sDescr' ] = $oCancProc->k03_descr;
    }
  }

  if ( trim($oCancProc->v07_sequencial) != "") {

    if(isset($aDadosResTipoProced[$oCancProc->k03_descr][$oCancProc->v07_sequencial])){

      if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){
        $aDadosResTipoProced[$oCancProc->k03_descr][$oCancProc->v07_sequencial]['Vlr' ]   += $oCancProc->total;
      }
    } else {

      if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

        $aDadosResTipoProced[$oCancProc->k03_descr][$oCancProc->v07_sequencial]['Vlr' ]    = $oCancProc->total;
        $aDadosResTipoProced[$oCancProc->k03_descr][$oCancProc->v07_sequencial]['sDescr' ] = $oCancProc->k03_descr
                                                                                            ." "
                                                                                            .$oCancProc->v07_descricao;
      }
    }
  } else {

      if(isset($aDadosResTipoProced["SEM PROCEDENCIA"])){

        if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){
          $aDadosResTipoProced["SEM PROCEDENCIA"][0]['Vlr' ]   += $oCancProc->total;
        }
      } else {

        if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

          $aDadosResTipoProced["SEM PROCEDENCIA"][0]['Vlr' ]    = $oCancProc->total;
          $aDadosResTipoProced["SEM PROCEDENCIA"][0]['sDescr' ] = "DÉBITOS SEM PROCEDÊNCIA";
        }
      }
  }

  if(isset($aDadosResTipoReceit[$oCancProc->k02_codigo])){

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){
      $aDadosResTipoReceit[$oCancProc->k02_codigo]['Vlr' ]   += $oCancProc->total;
    }
  }else{

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

      $aDadosResTipoReceit[$oCancProc->k02_codigo]['Vlr' ]    = $oCancProc->total;
      $aDadosResTipoReceit[$oCancProc->k02_codigo]['sDescr' ] = $oCancProc->k02_drecei;
    }
  }

  // totais da segunda folha
  if(isset($aAgrupaCarPec[$oCancProc->c58_sequencial])){

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['valor' ] += $oCancProc->k00_valor;
      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['vlrcor'] += $oCancProc->k24_vlrcor;
      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['multa' ] += $oCancProc->k24_multa;
      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['juros' ] += $oCancProc->k24_juros;
      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['total' ] += $oCancProc->total;
    }
  }else{

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['valor' ] = $oCancProc->k00_valor;
      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['vlrcor'] = $oCancProc->k24_vlrcor;
      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['multa' ] = $oCancProc->k24_multa;
      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['juros' ] = $oCancProc->k24_juros;
      $aAgrupaCarPec[$oCancProc->c58_sequencial][$oCancProc->c58_descr]['total' ] = $oCancProc->total;
    }
  }

  if(isset($aAgrupaRec[$oCancProc->k02_codigo])){

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['valor' ] += $oCancProc->k00_valor;
      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['vlrcor'] += $oCancProc->k24_vlrcor;
      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['multa' ] += $oCancProc->k24_multa;
      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['juros' ] += $oCancProc->k24_juros;
      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['total' ] += $oCancProc->total;
    }
  }else{

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['valor' ]  = $oCancProc->k00_valor;
      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['vlrcor']  = $oCancProc->k24_vlrcor;
      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['multa' ]  = $oCancProc->k24_multa;
      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['juros' ]  = $oCancProc->k24_juros;
      $aAgrupaRec[$oCancProc->k02_codigo][$oCancProc->k02_drecei]['total' ]  = $oCancProc->total;
    }
  }

  if(isset($aAgrupaTipo[$oCancProc->k00_descr])){

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

      $aAgrupaTipo[$oCancProc->k00_descr]['valor' ] += $oCancProc->k00_valor;
      $aAgrupaTipo[$oCancProc->k00_descr]['vlrcor'] += $oCancProc->k24_vlrcor;
      $aAgrupaTipo[$oCancProc->k00_descr]['multa' ] += $oCancProc->k24_multa;
      $aAgrupaTipo[$oCancProc->k00_descr]['juros' ] += $oCancProc->k24_juros;
      $aAgrupaTipo[$oCancProc->k00_descr]['total' ] += $oCancProc->total;
    }
  }else{

    if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

      $aAgrupaTipo[$oCancProc->k00_descr]['valor' ]  = $oCancProc->k00_valor;
      $aAgrupaTipo[$oCancProc->k00_descr]['vlrcor']  = $oCancProc->k24_vlrcor;
      $aAgrupaTipo[$oCancProc->k00_descr]['multa' ]  = $oCancProc->k24_multa;
      $aAgrupaTipo[$oCancProc->k00_descr]['juros' ]  = $oCancProc->k24_juros;
      $aAgrupaTipo[$oCancProc->k00_descr]['total' ]  = $oCancProc->total;
    }
  }

  if( !in_array($oCancProc->k00_matric, $aTotalMatric) && $oCancProc->k00_matric != ""){

    $aTotalMatric[$iNroMatric] = $oCancProc->k00_matric;
    $iNroMatric++;
  }
  if( !in_array($oCancProc->k00_inscr, $aTotalInscr) && $oCancProc->k00_inscr != ""){

    $aTotalInscr[$iNroInscr] = $oCancProc->k00_inscr;
    $iNroInscr++;
  }
  if( !in_array($oCancProc->k00_numcgm, $aTotalCgm) && $oCancProc->k00_matric == "" && $oCancProc->k00_inscr == ""){

    $aTotalCgm[$iNroCgm] = $oCancProc->k00_numcgm;
    $iNroCgm++;
  }

  $aCanceladoTotalGeral[] = array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit);
}

$aCanceladoTotalGeral = array();

if($oGet->seltipo == "c" || $oGet->seltipo == "rc"){

  $lImprimeCab      = true;
  $lImprimeSubTotal = true;
  $lPreecher        = false;
}

foreach ( $aDadosCancProc as $iInd => $aDados ) {

    if ($lPreecher == true) {

      $iList     = 1;
      $lPreecher = false;
    } else {

      $iList     = 0;
      $lPreecher = true;
    }

  if($lImprimeCab == true){

    $pdf->ln();
    $pdf->setfont('arial','b',$fonte);
    $pdf->cell(17,$alt,"CGM"                  ,1,0,"C",1);
    $pdf->cell(90,$alt,"Nome/ Razão Social"   ,1,0,"C",1);

    if (isset($oGet->mostender) && $oGet->mostender == "S") {

      $pdf->cell(75,$alt,"Endereço"           ,1,0,"L",1);
      $pdf->cell(35,$alt,"Município"          ,1,0,"L",1);
      $pdf->cell(10,$alt,"UF"                 ,1,0,"C",1);
      $pdf->cell(19,$alt,"Fone"               ,1,0,"C",1);
    }

    $pdf->cell(16,$alt,"Matrícula"            ,1,0,"C",1);
    $pdf->cell(15,$alt,"Inscrição"            ,1,1,"C",1);

    $pdf->setfont('arial','',$fonte);
    $pdf->cell(16,$alt,$aDados['oDadosCancProc']->iNumCgm              ,0,0,"C",0);
    $pdf->cell(90,$alt,$aDados['oDadosCancProc']->sNomeRazao           ,0,0,"L",0);

    if (isset($oGet->mostender) && $oGet->mostender == "S") {

      $sEndereco = $aDados['oDadosCancProc']->sEndereco." ".
                   $aDados['oDadosCancProc']->iNumero." ".
                   $aDados['oDadosCancProc']->sComplemento;

      $pdf->cell(75,$alt,substr($sEndereco,0,50)                                      ,0,0,"L",0);
      $pdf->cell(35,$alt,substr($aDados['oDadosCancProc']->sMunicipio,0,50)           ,0,0,"L",0);
      $pdf->cell(10,$alt,$aDados['oDadosCancProc']->sUf                               ,0,0,"C",0);
      $pdf->cell(19,$alt,$aDados['oDadosCancProc']->iFone                             ,0,0,"C",0);
    }

    $pdf->cell(16,$alt,$aDados['oDadosCancProc']->iMatric              ,0,0,"C",0);
    $pdf->cell(18,$alt,$aDados['oDadosCancProc']->iInscr               ,0,1,"C",0);
    if ($oGet->seltipo != 'rc'){

      $pdf->setfont('arial','b',$fonte);
      $pdf->cell(12,$alt,"Canc"   ,1,0,"C",1);
      $pdf->cell(15,$alt,"Dt Canc"   ,1,0,"C",1);
      $pdf->cell(15,$alt,"Vlr Hist"  ,1,0,"C",1);
      $pdf->cell(15,$alt,"Vlr Corr"  ,1,0,"C",1);
      $pdf->cell(15,$alt,"Multa"     ,1,0,"C",1);
      $pdf->cell(15,$alt,"Juros"     ,1,0,"C",1);
      $pdf->cell(15,$alt,"Total"     ,1,0,"C",1);
      $pdf->cell(15,$alt,"Cod Proc"  ,1,0,"C",1);
      $pdf->cell(16,$alt,"Numpre"    ,1,0,"C",1);
      $pdf->cell(10,$alt,"Parc"      ,1,0,"C",1);
      $pdf->cell(8,$alt,"Exerc"     ,1,0,"C",1);
      $pdf->cell(14,$alt,"Cod Rec"   ,1,0,"C",1);

      if($oGet->tipoDebito== 1 or  $oGet->agrupar=="CP"){
        $pdf->cell(71,$alt,"Receitass"   ,1,0,"C",1);
      } else {
        $pdf->cell(48,$alt,"Receita"   ,1,0,"C",1);
      }
      $pdf->cell(25,$alt,"Tipo"      ,1,0,"C",1);

      if($oGet->tipoDebito!= 1 and $oGet->agrupar=="N" ){
        $pdf->cell(26,$alt,"Carac. Peculiar",1,0,"C",1);
      }

      $pdf->cell(16,$alt,"Login"     ,1,1,"C",1);
    }
      $lPreecher = false;

  }

      foreach ( $aDados['aListaItens'] as $iInd => $oDadosItens ) {

        if ($oGet->seltipo != 'rc'){

          if($lImprimeCab == true){

            if ($pdf->gety() > $pdf->h - 30 ){

              $pdf->addpage("L");
              $lPreecher = false;

              $pdf->setfont('arial','b',$fonte);
              $pdf->cell(12,$alt,"Canc"      ,1,0,"C",1);
              $pdf->cell(15,$alt,"Dt Canc"   ,1,0,"C",1);
              $pdf->cell(15,$alt,"Vlr Hist"  ,1,0,"C",1);
              $pdf->cell(15,$alt,"Vlr Corr"  ,1,0,"C",1);
              $pdf->cell(15,$alt,"Multa"     ,1,0,"C",1);
              $pdf->cell(15,$alt,"Juros"     ,1,0,"C",1);
              $pdf->cell(15,$alt,"Total"     ,1,0,"C",1);
              $pdf->cell(15,$alt,"Cod Proc"  ,1,0,"C",1);
              $pdf->cell(16,$alt,"Numpre"    ,1,0,"C",1);
              $pdf->cell(10,$alt,"Parc"      ,1,0,"C",1);
              $pdf->cell(8,$alt,"Exerc"      ,1,0,"C",1);
              $pdf->cell(14,$alt,"Cod Rec"   ,1,0,"C",1);

              if($oGet->tipoDebito== 1 or  $oGet->agrupar=="CP"){
                $pdf->cell(71,$alt,"Receita"   ,1,0,"C",1);
              } else {
                $pdf->cell(48,$alt,"Receita"   ,1,0,"C",1);
              }

              $pdf->cell(25,$alt,"Tipo"      ,1,0,"C",1);

              if($oGet->tipoDebito!= 1 and $oGet->agrupar=="N" ){
                $pdf->cell(26,$alt,"Carac. Peculiar",1,0,"C",1);
              }

              $pdf->cell(16,$alt,"Login"     ,1,1,"C",1);

            }
          }

          if ($lPreecher == true) {

            $iList     = 1;
            $lPreecher = false;
          } else {

            $iList     = 0;
            $lPreecher = true;
          }

          if($lImprimeCab == true){

            $pdf->setfont('arial','',$fonte);
            $pdf->cell(12,$alt,$oDadosItens->iCodCancelado                ,0,0,"C",$iList);
            $pdf->cell(15,$alt,db_formatar($oDadosItens->dtData,"d")      ,0,0,"C",$iList);
            $pdf->cell(15,$alt,db_formatar($oDadosItens->Valor,"f")       ,0,0,"R",$iList);
            $pdf->cell(15,$alt,db_formatar($oDadosItens->VlrCor,"f")      ,0,0,"R",$iList);
            $pdf->cell(15,$alt,db_formatar($oDadosItens->VlrMulta,"f")    ,0,0,"R",$iList);
            $pdf->cell(15,$alt,db_formatar($oDadosItens->VlrJuro,"f")     ,0,0,"R",$iList);
            $pdf->cell(15,$alt,db_formatar($oDadosItens->VlrTotal,"f")    ,0,0,"R",$iList);
            $pdf->cell(15,$alt,$oDadosItens->iCod                         ,0,0,"C",$iList);
            $pdf->cell(16,$alt,$oDadosItens->iNumPre                      ,0,0,"C",$iList);
            $pdf->cell(10,$alt,$oDadosItens->iNumPar                      ,0,0,"C",$iList);
            $pdf->cell( 8,$alt,$oDadosItens->iExerc                       ,0,0,"C",$iList);
            $pdf->cell(14,$alt,$oDadosItens->iReceit                      ,0,0,"C",$iList);

            if($oGet->tipoDebito== 1 or  $oGet->agrupar=="CP"){
              $pdf->cell(71,$alt,$oDadosItens->sDescrRecei               ,0,0,"L",$iList);
            }else{
              $pdf->cell(48,$alt,substr($oDadosItens->sDescrRecei,0,31)  ,0,0,"L",$iList);
            }

            $pdf->cell(25,$alt,substr($oDadosItens->sDescr, 0, 15)                     ,0,0,"L",$iList);

            if($oGet->tipoDebito!= 1 and  $oGet->agrupar=="N"){
              $pdf->cell(26,$alt,substr($oDadosItens->sPeculiar,0,17)    ,0,0,"L",$iList);
            }

            $pdf->cell(16,$alt,$oDadosItens->sLogin                      ,0,1,"C",$iList);

            // imprime a caracteristica peculiar quando agrupado
            if($oGet->tipoDebito != 1 and $oGet->agrupar == "CP"){

              if($AuxCaracPeculiar != $oDadosItens->iSeq){

                // agrupa por Caracterista peculiar
                if($sPeculiar == ""){
                  $sPeculiar = "SEM CARACTERISTICA PECULIAR";
                }

                if($oGet->quebrar=="S" and $i!=0){
                  $pdf->addPage("L");
                }

                $pdf->ln(3);
                $pdf->cell(277,$alt,$sPeculiar                            ,"B",1,"L",1);
                $AuxCaracPeculiar = $oDadosItens->iSeq;
              }
            }

            $obsCancProc       = $oDadosItens->sObsCancProc;

          }

        }

        $TotalVlrHist     += $oDadosItens->Valor;
        $TotalVlrCorr     += $oDadosItens->VlrCor;
        $TotalVlrMulta    += $oDadosItens->VlrMulta;
        $TotalVlrJuros    += $oDadosItens->VlrJuro;
        $Total            += $oDadosItens->VlrTotal;

        if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

          $GeralVlrHist     += $oDadosItens->Valor;
          $GeralVlrCorr     += $oDadosItens->VlrCor;
          $GeralVlrMulta    += $oDadosItens->VlrMulta;
          $GeralVlrJuros    += $oDadosItens->VlrJuro;
          $GeralTotal       += $oDadosItens->VlrTotal;
        }

        // total do agrupamento por Carac. peculiar
        if( $AuxCaracPeculiar ==  $oDadosItens->iSeq ){

          if(!in_array(array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit), $aCanceladoTotalGeral)){

            $TotalVlrHistCP   += $oDadosItens->Valor;
            $TotalVlrCorrCP   += $oDadosItens->VlrCor;
            $TotalVlrMultaCP  += $oDadosItens->VlrMulta;
            $TotalVlrJurosCP  += $oDadosItens->VlrJuro;
            $TotalCP          += $oDadosItens->VlrTotal;
          }
        }
        $aCanceladoTotalGeral[] = array($oDadosItens->iCodCancelado, $oDadosItens->iNumPre, $oDadosItens->iNumPar, $oDadosItens->iReceit);
      }

      if($lImprimeCab == true){

        if( $oGet->selhist == "s" && $oGet->seltipo != "rc" ){

          if ($lPreecher == true) {

            $iList     = 1;
            $lPreecher = false;
          } else {

            $iList     = 0;
            $lPreecher = true;
          }

          $iTam = strlen($obsCancProc);
          if ($iTam > 185) {
            $obsCancProc = substr($obsCancProc,0,181)."...";
          }

          $pdf->setfont('arial','i',7);
          $pdf->cell(277,$alt,"Histórico : ".$obsCancProc ,0,1,"L",$iList);
          $pdf->setfont('arial','',$fonte);
        }

        if( $lImprimeSubTotal == true ){

          // imprime o TOTAL por CGM
          $pdf->ln(2);
          $pdf->setfont('arial','b',$fonte);

          if ($oGet->seltipo == "rc") {

            $pdf->cell(204,$alt,"",0,0,"R",0);

            $pdf->cell(15,$alt,"Vlr Hist",1,0,"C",1);
            $pdf->cell(15,$alt,"Vlr Corr",1,0,"C",1);
            $pdf->cell(15,$alt,"Multa",1,0,"C",1);
            $pdf->cell(15,$alt,"Juros",1,0,"C",1);
            $pdf->cell(15,$alt,"Total",1,1,"C",1);

            $pdf->cell(204,$alt,"",0,0,"R",0);

          }else{
            $pdf->cell(16,$alt,"TOTAL : ",0,0,"R",0);
          }

          $pdf->cell(15,$alt,db_formatar( $TotalVlrHist  ,"f"),0,0,"R",0);
          $pdf->cell(15,$alt,db_formatar( $TotalVlrCorr  ,"f"),0,0,"R",0);
          $pdf->cell(15,$alt,db_formatar( $TotalVlrMulta ,"f"),0,0,"R",0);
          $pdf->cell(15,$alt,db_formatar( $TotalVlrJuros ,"f"),0,0,"R",0);
          $pdf->cell(15,$alt,db_formatar( $Total         ,"f"),0,0,"R",0);
          $pdf->setfont('arial','',$fonte);
          $pdf->ln();

          $TotalVlrHist   = 0;
          $TotalVlrCorr   = 0;
          $TotalVlrMulta  = 0;
          $TotalVlrJuros  = 0;
          $Total          = 0;
        }

        // imprime o TOTAL por caracteristica peculiar
        if($oGet->tipoDebito != 1 and $oGet->agrupar == "CP"){

            $pdf->ln(1);
            $pdf->setfont('arial','B',$fonte+1);
            $pdf->cell(16,$alt,"TOTAL : ","T",0,"R",0);
            $pdf->cell(15,$alt,db_formatar( $TotalVlrHistCP  ,"f"),"T",0,"R",0);
            $pdf->cell(15,$alt,db_formatar( $TotalVlrCorrCP  ,"f"),"T",0,"R",0);
            $pdf->cell(15,$alt,db_formatar( $TotalVlrMultaCP ,"f"),"T",0,"R",0);
            $pdf->cell(15,$alt,db_formatar( $TotalVlrJurosCP ,"f"),"T",0,"R",0);
            $pdf->cell(15,$alt,db_formatar( $TotalCP         ,"f"),"T",0,"R",0);
            $pdf->cell(175,$alt,"","T",1,"R",0);
            $pdf->setfont('arial','',$fonte);

            $TotalVlrHistCP   = 0;
            $TotalVlrCorrCP   = 0;
            $TotalVlrMultaCP  = 0;
            $TotalVlrJurosCP  = 0;
            $TotalCP          = 0;

        }
      }
}

if ($lImprimeCab == true) {

  // imprime o TOTAL geral
  $pdf->ln();
  $pdf->setfont('arial','b',8);
  $pdf->cell(16,$alt,"GERAL : ",0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar( $GeralVlrHist  ,"f"),0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar( $GeralVlrCorr  ,"f"),0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar( $GeralVlrMulta ,"f"),0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar( $GeralVlrJuros ,"f"),0,0,"R",1);
  $pdf->cell(15,$alt,db_formatar( $GeralTotal    ,"f"),0,0,"R",1);
  $pdf->setfont('arial','',$fonte);
  $pdf->ln();
}

$iNroReg    = $iCancProc;
$ValorRec   = 0;
$VlrCorRec  = 0;
$MultaRec   = 0;
$JurosRec   = 0;
$TotalRec   = 0;

$ValorTipo  = 0;
$VlrCorTipo = 0;
$MultaTipo  = 0;
$JurosTipo  = 0;
$TotalTipo  = 0;

$ValorCarac  = 0;
$VlrCorCarac = 0;
$MultaCarac  = 0;
$JurosCarac  = 0;
$TotalCarac  = 0;

if($oGet->seltipo == "c" || $oGet->seltipo == "rc"){
  $pdf->addpage("L");
}

$pdf->sety(35);
$pdf->setfont('arial','b',$fonte);
$pdf->cell(30,$alt,"TOTAL DE REGISTROS  : ",0,1,"L",0);
$pdf->cell(30,$alt,"TOTAL DE MATRÍCULAS : ",0,1,"L",0);
$pdf->cell(30,$alt,"TOTAL DE INSCRIÇÕES : ",0,1,"L",0);
$pdf->cell(30,$alt,"TOTAL SOMENTE CGM   : ",0,1,"L",0);
$pdf->ln();

$pdf->sety(35);
$pdf->setfont('arial','',$fonte);
$pdf->cell(45,$alt,$iNroReg    ,0,1,"R",0);
$pdf->cell(45,$alt,$iNroMatric ,0,1,"R",0);
$pdf->cell(45,$alt,$iNroInscr  ,0,1,"R",0);
$pdf->cell(45,$alt,$iNroCgm    ,0,1,"R",0);
$pdf->ln(2);

$pdf->setfont('arial','b',$fonte);
$pdf->cell(60,$alt,"","T",0,"C",0);
$pdf->cell(140,$alt,"TOTAL DE DÉBITOS CANCELADOS","T",0,"C",0);
$pdf->cell(0,$alt,"","T",1,"C",0);
$pdf->cell(120,$alt,""         ,"T",0,"C",1);
$pdf->cell(22,$alt,"Vlr Hist"  ,"T",0,"C",1);
$pdf->cell(22,$alt,"Vlr Corr"  ,"T",0,"C",1);
$pdf->cell(22,$alt,"Multa"     ,"T",0,"C",1);
$pdf->cell(22,$alt,"Juros"     ,"T",0,"C",1);
$pdf->cell(22,$alt,"Total"     ,"T",0,"C",1);
$pdf->cell(0,$alt,""           ,"T",1,"C",1);
$pdf->ln(2);
$pdf->setx(70);
$pdf->cell(60,$alt,"TOTAL GERAL : ",0,0,"C",0);
$pdf->cell(22,$alt,db_formatar( $GeralVlrHist  ,"f"),0,0,"R",0);
$pdf->cell(22,$alt,db_formatar( $GeralVlrCorr  ,"f"),0,0,"R",0);
$pdf->cell(22,$alt,db_formatar( $GeralVlrMulta ,"f"),0,0,"R",0);
$pdf->cell(22,$alt,db_formatar( $GeralVlrJuros ,"f"),0,0,"R",0);
$pdf->cell(22,$alt,db_formatar( $GeralTotal    ,"f"),0,1,"R",0);
$pdf->ln();

$pdf->setfont('arial','b',$fonte);
$pdf->cell(60,$alt,"","T",0,"C",0);
$pdf->cell(140,$alt,"TOTAL DE DÉBITOS CANCELADOS DETALHADO POR RECEITA","T",0,"C",0);
$pdf->cell(0,$alt,""           ,"T",1,"C",0);
$pdf->cell(45,$alt,""          ,"T",0,"C",1);
$pdf->cell(15,$alt,"Cod Rec"   ,"T",0,"C",1);
$pdf->cell(60,$alt,"Receita"   ,"T",0,"C",1);
$pdf->cell(22,$alt,"Vlr Hist"  ,"T",0,"C",1);
$pdf->cell(22,$alt,"Vlr Corr"  ,"T",0,"C",1);
$pdf->cell(22,$alt,"Multa"     ,"T",0,"C",1);
$pdf->cell(22,$alt,"Juros"     ,"T",0,"C",1);
$pdf->cell(22,$alt,"Total"     ,"T",0,"C",1);
$pdf->cell(0,$alt,""           ,"T",1,"C",1);
$pdf->ln(2);

foreach($aAgrupaRec as $Cod => $aRec ){

  $pdf->setfont('arial','',$fonte);
  $pdf->setx(45);
  $pdf->cell(15,$alt,$Cod,0,0,"C",0);

  foreach($aRec as $Rec => $valorRec ){

    $pdf->cell(70,$alt,$Rec,0,0,"L",0);
    $pdf->cell(22,$alt,db_formatar($valorRec['valor' ],"f"),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($valorRec['vlrcor'],"f"),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($valorRec['multa' ],"f"),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($valorRec['juros' ],"f"),0,0,"R",0);
    $pdf->cell(22,$alt,db_formatar($valorRec['total' ],"f"),0,1,"R",0);

    $ValorRec  += $valorRec['valor' ];
    $VlrCorRec += $valorRec['vlrcor'];
    $MultaRec  += $valorRec['multa' ];
    $JurosRec  += $valorRec['juros' ];
    $TotalRec  += $valorRec['total' ];
  }

}

$pdf->setfont('arial','b',$fonte);
$pdf->setx(45);
$pdf->cell(15,"",0,1,"R",0);
$pdf->cell(70,"",0,1,"R",0);
$pdf->cell(22,$alt,db_formatar($ValorRec ,"f"),"T",0,"R",0);
$pdf->cell(22,$alt,db_formatar($VlrCorRec,"f"),"T",0,"R",0);
$pdf->cell(22,$alt,db_formatar($MultaRec ,"f"),"T",0,"R",0);
$pdf->cell(22,$alt,db_formatar($JurosRec ,"f"),"T",0,"R",0);
$pdf->cell(22,$alt,db_formatar($TotalRec ,"f"),"T",1,"R",0);
$pdf->ln();

$pdf->setfont('arial','b',$fonte);
$pdf->cell(60,$alt,"","T",0,"C",0);
$pdf->cell(140,$alt,"TOTAL DE DÉBITOS CANCELADOS DETALHADO POR TIPO DE DÉBITO","T",0,"C",0);
$pdf->cell(0,$alt,""           ,"T",1,"C",0);
$pdf->cell(120,$alt,""         ,"T",0,"C",1);
$pdf->cell(22,$alt,"Vlr Hist"  ,"T",0,"C",1);
$pdf->cell(22,$alt,"Vlr Corr"  ,"T",0,"C",1);
$pdf->cell(22,$alt,"Multa"     ,"T",0,"C",1);
$pdf->cell(22,$alt,"Juros"     ,"T",0,"C",1);
$pdf->cell(22,$alt,"Total"     ,"T",0,"C",1);
$pdf->cell(0,$alt,""           ,"T",1,"C",1);
$pdf->ln(2);

foreach($aAgrupaTipo as $Tipo => $valorTipo ){

  $pdf->setfont('arial','',$fonte);
  $pdf->setx(60);
  $pdf->cell(70,$alt,$Tipo,0,0,"L",0);
  $pdf->cell(22,$alt,db_formatar($valorTipo['valor' ],"f"),0,0,"R",0);
  $pdf->cell(22,$alt,db_formatar($valorTipo['vlrcor'],"f"),0,0,"R",0);
  $pdf->cell(22,$alt,db_formatar($valorTipo['multa' ],"f"),0,0,"R",0);
  $pdf->cell(22,$alt,db_formatar($valorTipo['juros' ],"f"),0,0,"R",0);
  $pdf->cell(22,$alt,db_formatar($valorTipo['total' ],"f"),0,1,"R",0);
  $ValorTipo  += $valorTipo['valor' ];
  $VlrCorTipo += $valorTipo['vlrcor'];
  $MultaTipo  += $valorTipo['multa' ];
  $JurosTipo  += $valorTipo['juros' ];
  $TotalTipo  += $valorTipo['total' ];
}
$pdf->setfont('arial','b',$fonte);
$pdf->setx(70);
$pdf->cell(60,"",0,1,"R",0);
$pdf->cell(22,$alt,db_formatar($ValorTipo ,"f"),"T",0,"R",0);
$pdf->cell(22,$alt,db_formatar($VlrCorTipo,"f"),"T",0,"R",0);
$pdf->cell(22,$alt,db_formatar($MultaTipo ,"f"),"T",0,"R",0);
$pdf->cell(22,$alt,db_formatar($JurosTipo ,"f"),"T",0,"R",0);
$pdf->cell(22,$alt,db_formatar($TotalTipo,"f"),"T",1,"R",0);

if($oGet->tipoDebito != 1){

  // por peculiar
  $pdf->ln(5);
  $pdf->setfont('arial','b',$fonte);
  $pdf->cell(60,$alt,"","T",0,"C",0);
  $pdf->cell(140,$alt,"TOTAL DE DÉBITOS CANCELADOS DETALHADO POR CARACTERÍSTICA PECULIAR","T",0,"C",0);
  $pdf->cell(0,$alt,""                          ,"T",1,"C",0);
  $pdf->cell(45,$alt,""                         ,"T",0,"C",1);
  $pdf->cell(15,$alt,"Cod Carac."               ,"T",0,"C",1);
  $pdf->cell(60,$alt,"Característica Peculiar"  ,"T",0,"C",1);
  $pdf->cell(22,$alt,"Vlr Hist"                 ,"T",0,"C",1);
  $pdf->cell(22,$alt,"Vlr Corr"                 ,"T",0,"C",1);
  $pdf->cell(22,$alt,"Multa"                    ,"T",0,"C",1);
  $pdf->cell(22,$alt,"Juros"                    ,"T",0,"C",1);
  $pdf->cell(22,$alt,"Total"                    ,"T",0,"C",1);
  $pdf->cell(0,$alt,""                          ,"T",1,"C",1);
  $pdf->ln(2);


  foreach($aAgrupaCarPec as $CodPec => $aCarac ){

    $pdf->setfont('arial','',$fonte);
    $pdf->setx(45);
    $pdf->cell(15,$alt,$CodPec,0,0,"C",0);
    foreach($aCarac as $Carac => $valorCarac ){

      if($Carac ==""){
         $Carac = "SEM CARACTERÍSTICA PECULIAR";
      }
      $pdf->cell(70,$alt,$Carac,0,0,"L",0);
      $pdf->cell(22,$alt,db_formatar($valorCarac['valor' ],"f"),0,0,"R",0);
      $pdf->cell(22,$alt,db_formatar($valorCarac['vlrcor'],"f"),0,0,"R",0);
      $pdf->cell(22,$alt,db_formatar($valorCarac['multa' ],"f"),0,0,"R",0);
      $pdf->cell(22,$alt,db_formatar($valorCarac['juros' ],"f"),0,0,"R",0);
      $pdf->cell(22,$alt,db_formatar($valorCarac['total' ],"f"),0,1,"R",0);
      $ValorCarac  += $valorCarac['valor' ];
      $VlrCorCarac += $valorCarac['vlrcor'];
      $MultaCarac  += $valorCarac['multa' ];
      $JurosCarac  += $valorCarac['juros' ];
      $TotalCarac  += $valorCarac['total' ];
    }
  }
  $pdf->setfont('arial','b',$fonte);
  $pdf->setx(45);
  $pdf->cell(15,"",0,1,"R",0);
  $pdf->cell(70,"",0,1,"R",0);
  $pdf->cell(22,$alt,db_formatar($ValorCarac ,"f"),"T",0,"R",0);
  $pdf->cell(22,$alt,db_formatar($VlrCorCarac,"f"),"T",0,"R",0);
  $pdf->cell(22,$alt,db_formatar($MultaCarac ,"f"),"T",0,"R",0);
  $pdf->cell(22,$alt,db_formatar($JurosCarac ,"f"),"T",0,"R",0);
  $pdf->cell(22,$alt,db_formatar($TotalCarac ,"f"),"T",1,"R",0);
  $pdf->ln();
}

/**
 *   Resumo por Tipo de Débito
 */
$pdf->addpage("L");
$pdf->ln();
$pdf->setfont('arial','b',$fonte);
$pdf->cell(90,$alt,""                            ,0,0,"C",0);
$pdf->cell(100,$alt,"Resumo por Tipo de Débito"  ,1,0,"C",0);
$pdf->cell(90,$alt,""                            ,0,1,"C",0);

$pdf->cell(90,$alt,""                            ,0,0,"C",0);
$pdf->cell(50,$alt,"Tipo de Débito"              ,1,0,"C",0);
$pdf->cell(50,$alt,"Valor"                       ,1,0,"C",0);
$pdf->cell(90,$alt,""                            ,0,1,"C",0);

$nTotalResumTipoDeb = 0;

foreach ( $aDadosResTipoDeb as $iInd => $aDados ) {
  $pdf->setfont('arial','',$fonte);
  $pdf->cell(90,$alt,""                                 ,0,0,"C",0);
  $pdf->cell(50,$alt,$aDados['sDescr']                  ,1,0,"L",0);
  $pdf->cell(50,$alt,db_formatar($aDados['Vlr'],"f")    ,1,0,"R",0);
  $pdf->cell(90,$alt,""                                 ,0,1,"C",0);

  $nTotalResumTipoDeb += $aDados['Vlr'];
}

$pdf->setfont('arial','b',$fonte);
$pdf->cell(90,$alt,""                                   ,0,0,"C",0);
$pdf->cell(50,$alt,"Total:"                             ,1,0,"L",0);
$pdf->cell(50,$alt,db_formatar($nTotalResumTipoDeb,"f") ,1,0,"R",0);
$pdf->cell(90,$alt,""                                   ,0,1,"C",0);

/**
 *   Resumo por Tipo de Procedência
 */
$pdf->addpage("L");
$pdf->ln();
$pdf->setfont('arial','b',$fonte);
$pdf->cell(85,$alt,""                                        ,0,0,"C",0);
$pdf->cell(130,$alt,"Resumo por Tipo de Procedência"         ,1,0,"C",0);
$pdf->cell(85,$alt,""                                        ,0,1,"C",0);

$pdf->cell(85,$alt,""                                        ,0,0,"C",0);
$pdf->cell(65,$alt,"Procedência"                             ,1,0,"C",0);
$pdf->cell(65,$alt,"Valor"                                   ,1,0,"C",0);
$pdf->cell(85,$alt,""                                        ,0,1,"C",0);

$nTotalResumTipoProced = 0;

foreach ( $aDadosResTipoProced as $iTotProced => $aQtdTotProced ) {
  foreach ( $aQtdTotProced as $iQtdTotProced => $aDados ) {
    $pdf->setfont('arial','',$fonte);
    $pdf->cell(85,$alt,""                                    ,0,0,"C",0);
    $pdf->cell(65,$alt,$aDados['sDescr']                     ,1,0,"L",0);
    $pdf->cell(65,$alt,db_formatar($aDados['Vlr'],"f")       ,1,0,"R",0);
    $pdf->cell(85,$alt,""                                    ,0,1,"C",0);

    $nTotalResumTipoProced += $aDados['Vlr'];
  }
}

$pdf->setfont('arial','b',$fonte);
$pdf->cell(85,$alt,""                                        ,0,0,"C",0);
$pdf->cell(65,$alt,"Total:"                                  ,1,0,"L",0);
$pdf->cell(65,$alt,db_formatar($nTotalResumTipoProced,"f")   ,1,0,"R",0);
$pdf->cell(85,$alt,""                                        ,0,1,"C",0);

/**
 *   Resumo por Receita
 */
$pdf->addpage("L");
$pdf->ln();
$pdf->setfont('arial','b',$fonte);
$pdf->cell(85,$alt,""                                       ,0,0,"C",0);
$pdf->cell(130,$alt,"Resumo por Receita"                    ,1,0,"C",0);
$pdf->cell(85,$alt,""                                       ,0,1,"C",0);

$pdf->cell(85,$alt,""                                       ,0,0,"C",0);
$pdf->cell(65,$alt,"Receita"                                ,1,0,"C",0);
$pdf->cell(65,$alt,"Valor"                                  ,1,0,"C",0);
$pdf->cell(85,$alt,""                                       ,0,1,"C",0);

$nTotalResumTipoReceit = 0;

foreach ( $aDadosResTipoReceit as $iInd => $aDados ) {

  $pdf->setfont('arial','',$fonte);
  $pdf->cell(85,$alt,""                                    ,0,0,"C",0);
  $pdf->cell(65,$alt,$aDados['sDescr']                     ,1,0,"L",0);
  $pdf->cell(65,$alt,db_formatar($aDados['Vlr'],"f")       ,1,0,"R",0);
  $pdf->cell(85,$alt,""                                    ,0,1,"C",0);

  $nTotalResumTipoReceit += $aDados['Vlr'];
}

$pdf->setfont('arial','b',$fonte);
$pdf->cell(85,$alt,""                                      ,0,0,"C",0);
$pdf->cell(65,$alt,"Total:"                                ,1,0,"L",0);
$pdf->cell(65,$alt,db_formatar($nTotalResumTipoReceit,"f") ,1,0,"R",0);
$pdf->cell(85,$alt,""                                      ,0,1,"C",0);

$pdf->Output();