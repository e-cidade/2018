<?php
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_utils.php"));
include(modification("classes/db_tabrec_classe.php"));

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));

$cltabrec = new cl_tabrec;
$cltabrec->rotulo->label("k02_codigo");

$clrotulo        = new rotulocampo;
$clrotulo->label('v70_sequencial');
$clrotulo->label('v70_codforo');

if(isset($Parcelamento) && $Parcelamento != ""){
  $sql=" select v07_numpre from termo where v07_parcel = $Parcelamento";
  $result=db_query($sql);
  $numrows=pg_num_rows($result);
  if ($numrows>0){
      db_fieldsmemory($result,0);
      $numpre = $v07_numpre;
  }
}

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php 
  db_app::load("scripts.js, strings.js, numbers.js, prototype.js, AjaxRequest.js, datagrid.widget.js");
  db_app::load("widgets/Collection.widget.js, widgets/DatagridCollection.widget.js, widgets/DBDownload.widget.js");
?>
</head>
<script>
   function js_imprime() {
      jandb = window.open('cai3_gerfinanc014.php?<?
        if(isset($matric)){
           echo "matric=$matric";
        }else if(isset($inscr)){
           echo "inscr=$inscr";
        }else if(isset($numcgm)){
           echo "numcgm=$numcgm";
        }else {
           echo "numpre=$numpre";
        }
        if (isset($y50_codauto) && $y50_codauto != '') {                     
            echo "&y50_codauto=$y50_codauto";                                  
        } 

        if(!empty($HTTP_POST_VARS["datainicial_dia"])) {
           echo "&datainicial=".$datainicial = $HTTP_POST_VARS['datainicial_ano']."-".$HTTP_POST_VARS['datainicial_mes']."-".$HTTP_POST_VARS['datainicial_dia'];
           echo "&datafinal=".$datafinal = $HTTP_POST_VARS['datafinal_ano']."-".$HTTP_POST_VARS['datafinal_mes']."-".$HTTP_POST_VARS['datafinal_dia'];
        }
        if(!empty($HTTP_POST_VARS['k02_codigo'])) {
            echo "&k02_codigo=".$HTTP_POST_VARS['k02_codigo'];
        }
        if(!empty($HTTP_POST_VARS['conta'])) {
           echo "&conta=".$HTTP_POST_VARS['conta'];
        }

        if(!empty($HTTP_POST_VARS['v70_sequencial'])) {
           echo "&v70_sequencial=".$HTTP_POST_VARS['v70_sequencial'];
        }
      ?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jandb.moveTo(0,0);
   }
</script>


<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<?
if(isset($tipo_cert) && !isset($HTTP_POST_VARS["procurar"])) {
?>
<br><br>
<form name="form1" method="post" >
  <table width="420" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td width="20%" class="tabs" nowrap><strong>Data Inicial:</strong></td>
     <td width="80%" class="tabs">
      <? db_inputdata("datainicial",'','','',true,'text',2); ?>
     </td>
    </tr>
    <tr>
      <td class="tabs" nowrap><strong>Data Final:</strong></td>
      <td class="tabs">
       <? db_inputdata("datafinal",date('d',db_getsession("DB_datausu")),date('m',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu")),true,'text',2); ?>
      </td>
    </tr>
    <tr>
      <td class="tabs">
        <? db_ancora("<font color=blue><b>Receita:</b></font>", "js_pesquisareceita(true);", 1); ?>
      </td>
      <td class="tabs" colspan=2>
        <? db_input('k02_codigo', 10, $Ik02_codigo, true, 'text', 1); ?>
      </td>
    </tr>
    <tr>
      <td class="tabs" nowrap><strong>Conta:</strong></td>
      <td class="tabs"><input type="text" name="conta" size=10></td>
    </tr>
    <tr>
      <td align="left" nowrap title="<?=@$Tv70_codforo?>" >
        <?db_ancora(@$Lv70_codforo, "js_pesquisaprocessoforo(true);", 4);?>
      </td>
      <td align="left">
        <?
          db_input("v70_sequencial",  10, $Iv70_sequencial, true, "text", 4, "onchange='js_pesquisaprocessoforo(false);'");
          db_input("v70_codforo",    25, $Iv70_codforo,  true, "text", 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td class="tabs" nowrap>&nbsp;</td>
      <td height="30" class="tabs"><input name="procurar" type="submit" id="procurar" value="Procurar"></td>
    </tr>
  </table>
</form>

<script>

document.form1.datainicial_dia.focus();

function js_validar() {

  var F = document.form1;

  if(F.k02_codigo.value == "" && F.conta.value == "" && (F.datainicial_dia.value == "" || F.datainicial_mes.value == "" || F.datainicial_ano.value == "" || F.datafinal_dia.value == "" || F.datafinal_mes.value == "" || F.datafinal_ano.value == "")) {
    alert("Informe algum campo");
    F.datainicial_dia.select();
    return false;
  }

}

</script>
<?
} else {

  $aWherePagamento    = array();
  $sWhereNumpreNormal = "";
  $sWhereNumprePgto   = "";


  if (isset($numcgm)) {

    $sInnerPagamento   = " inner join arrenumcgm on arrenumcgm.k00_numpre = arrepaga.k00_numpre";
    if(isset($y50_codauto) && $y50_codauto != ''){
      $sInnerPagamento .= " inner join arreauto on arrenumcgm.k00_numpre = arreauto.k00_numpre and arreauto.k00_auto =".$y50_codauto;
    }
    $aWherePagamento[] = " arrenumcgm.k00_numcgm = ".$numcgm;

  } else if(isset($matric)) {

    $sInnerPagamento   = " inner join arrematric on arrematric.k00_numpre = arrepaga.k00_numpre";
    if(isset($y50_codauto) && $y50_codauto != ''){
      $sInnerPagamento .= " inner join arreauto on arrematric.k00_numpre = arreauto.k00_numpre and arreauto.k00_auto =".$y50_codauto;
    }
    $aWherePagamento[] = " arrematric.k00_matric = ".$matric;

  } else if(isset($inscr)) {

    $sInnerPagamento   = " inner join arreinscr on arreinscr.k00_numpre   = arrepaga.k00_numpre";
    if(isset($y50_codauto) && $y50_codauto != ''){
      $sInnerPagamento .= " inner join arreauto on arreinscr.k00_numpre = arreauto.k00_numpre and arreauto.k00_auto =".$y50_codauto;
    }
    $aWherePagamento[] = " arreinscr.k00_inscr   = ".$inscr;

  } else if (isset($numpre)) {

    $sInnerPagamento    = "";
    $sWhereNumpreNormal = " and arrepaga.k00_numpre   = ".$numpre;

    $sWhereNumprePgto   = " and (  arrepaga.k00_numpre = {$numpre} ";
    $sWhereNumprePgto  .= "     or arreckey.k00_numpre = {$numpre} ";
    $sWhereNumprePgto  .= "     )                                  ";

  }

  if (isset($idret)) {

    $sWhereNumpreNormal = " and ( select count(*) from arreidret arreidret2 where arreidret2.idret = $idret and arreidret2.k00_numpre = arrepaga.k00_numpre and arreidret2.k00_numpar = arrepaga.k00_numpar ) > 0 ";

    $sWhereNumprePgto   = " and ( ( select count(*) from arreidret arreidret2 where arreidret2.idret = $idret and arreidret2.k00_numpre = arrepaga.k00_numpre and arreidret2.k00_numpar = arrepaga.k00_numpar )  > 0";
    $sWhereNumprePgto  .= "     or ( select count(*) from arreidret arreidret2 where arreidret2.idret = $idret and arreidret2.k00_numpre = arreckey.k00_numpre and arreidret2.k00_numpar = arreckey.k00_numpar ) > 0 ";
    $sWhereNumprePgto  .= "     ) ";

  }


  if (!empty($HTTP_POST_VARS["datainicial_dia"])) {

    $datainicial       = $HTTP_POST_VARS["datainicial_ano"]."-".$HTTP_POST_VARS["datainicial_mes"]."-".$HTTP_POST_VARS["datainicial_dia"];
    $datafinal         = $HTTP_POST_VARS["datafinal_ano"]  ."-".$HTTP_POST_VARS["datafinal_mes"]  ."-".$HTTP_POST_VARS["datafinal_dia"];
    $aWherePagamento[] = " arrepaga.k00_dtpaga between '$datainicial' and '$datafinal' ";
  }

  if (!empty($HTTP_POST_VARS["k02_codigo"])) {
    $aWherePagamento[] = " arrepaga.k00_receit = ".$HTTP_POST_VARS["k02_codigo"];
  }

  if (!empty($HTTP_POST_VARS["conta"])) {
    $aWherePagamento[] = " arrepaga.k00_conta = ".$HTTP_POST_VARS["conta"];
  }

  if (!empty($HTTP_POST_VARS["v70_sequencial"])) {

    $sInnerPagamento .= " inner join ( select distinct";
    $sInnerPagamento .= "                     case ";
    $sInnerPagamento .= "                       when termo.v07_numpre is null ";
    $sInnerPagamento .= "                         then inicialnumpre.v59_numpre";
    $sInnerPagamento .= "                       else termo.v07_numpre";
    $sInnerPagamento .= "                     end as numpre ";
    $sInnerPagamento .= "                from processoforoinicial ";
    $sInnerPagamento .= "                left join termoini      on inicial          = v71_inicial ";
    $sInnerPagamento .= "                left join termo         on termo.v07_parcel = termoini.parcel ";
    $sInnerPagamento .= "                left join inicialnumpre on inicialnumpre.v59_inicial = v71_inicial ";
    $sInnerPagamento .= "               where processoforoinicial.v71_processoforo = {$HTTP_POST_VARS["v70_sequencial"]}";
    $sInnerPagamento .= "            ) as processoforo on processoforo.numpre = arrepaga.k00_numpre ";

    //$aWherePagamento[] = " processoforoinicial.v71_processoforo = ".$HTTP_POST_VARS["v70_sequencial"];
  }

  $sWherePagamento = implode(" and ", $aWherePagamento);

  if (trim($sWherePagamento) != '') {
    $sWherePagamento = " and ".$sWherePagamento;
  }

  $sSqlPagamentos  = " select distinct
                              arrepaga.k00_numpre,
                              arrepaga.k00_numpar,
                              arrepaga.k00_numtot,
                              case when arrecant.k00_dtvenc is null then arrepaga.k00_dtvenc else arrecant.k00_dtvenc end as k00_dtvenc,
                              case when arrecant.k00_dtoper is null then arrepaga.k00_dtoper else arrecant.k00_dtoper end as k00_dtoper,
                              arrepaga.k00_receit,
                              k02_drecei,
                              arrepaga.k00_hist,
                              k01_descr,
                              arrepaga.k00_valor,
                              arrepaga.k00_conta,
                              arrepaga.k00_dtpaga,
                              arrecant.k00_tipo,
                              coalesce(disbanco.dtpago,k00_dtpaga) as efetpagto,
                              'NORMAL'                             as tipopagamento,
                              0                                    as abatimento,
                              0                                    as numpreabatimento,
                              case 
                                arrepaga.k00_hist when 505 then 0
                                else coalesce(disbanco.idret, 
                                              (select 1 
                                                 from cornump
                                                where cornump.k12_numpre = arrepaga.k00_numpre
                                                  and cornump.k12_numpar = arrepaga.k00_numpar
                                                  and cornump.k12_receit = arrepaga.k00_receit), 
                                              0) 
                              end as boleto
                         from arrepaga
                              {$sInnerPagamento}
                              left  join arrecant      on arrecant.k00_numpre   = arrepaga.k00_numpre
                                                      and arrecant.k00_numpar   = arrepaga.k00_numpar
                                                      and arrecant.k00_receit   = arrepaga.k00_receit
                                                      and arrecant.k00_hist    <> 918
                              inner join arreinstit    on arreinstit.k00_numpre = arrepaga.k00_numpre
                                                      and arreinstit.k00_instit = ".db_getsession('DB_instit')."
                              inner join tabrec        on tabrec.k02_codigo     = arrepaga.k00_receit
                              inner join tabrecjm      on tabrecjm.k02_codjm    = tabrec.k02_codjm
                              inner join histcalc      on histcalc.k01_codigo   = arrepaga.k00_hist
                              left  join arreidret     on arreidret.k00_numpre  = arrepaga.k00_numpre
                                                      and arreidret.k00_numpar  = arrepaga.k00_numpar
                              left  join disbanco      on disbanco.idret        = arreidret.idret
                        where not exists ( select 1
                                             from abatimentorecibo
                                                  inner join abatimento on abatimento.k125_sequencial = abatimentorecibo.k127_abatimento
                                            where abatimentorecibo.k127_numprerecibo = arrepaga.k00_numpre
                                              and abatimento.k125_tipoabatimento     in (1, 4)
                                             limit 1 )
                                and not exists(
                                  select 1 from abatimentoutilizacaodestino
                                    inner join abatimentoutilizacao on k157_sequencial = k170_utilizacao
                                    inner join abatimento on abatimento.k125_sequencial = k157_abatimento
                                    inner join abatimentorecibo on k127_abatimento = k125_sequencial
                                  where
                                    k170_numpre = arrepaga.k00_numpre and
                                    k170_numpar = arrepaga.k00_numpar and
                                    k157_tipoutilizacao = '2'         and
                                    k125_tipoabatimento = 3
                                  limit 1
                                )
                                and not exists(
                                  select 1 from abatimentorecibo
                                    inner join abatimento on abatimento.k125_sequencial = k127_abatimento
                                    left  join termo on termo.v07_numpre = abatimentorecibo.k127_numpreoriginal
                                                    and termo.v07_situacao = 2
                                  where
                                    abatimentorecibo.k127_numpreoriginal = arrepaga.k00_numpre and
                                    k125_tipoabatimento = 4
                                    and termo is null
                                  limit 1
                                )
                              {$sWhereNumpreNormal}
                              {$sWherePagamento}
                     group by arrepaga.k00_numpre,
                              arrepaga.k00_numpar,
                              arrepaga.k00_numtot,
                              arrepaga.k00_hist,
                              arrepaga.k00_receit,
                              k02_drecei,
                              k01_descr,
                              arrepaga.k00_conta,
                              arrepaga.k00_dtpaga,
                              arrecant.k00_tipo,
                              arrepaga.k00_dtoper,
                              arrecant.k00_dtoper,
                              disbanco.dtpago,
                              k00_dtpaga,
                              arrepaga.k00_dtvenc,
                              arrecant.k00_dtvenc,
                              arrepaga.k00_valor,
                              boleto

                     union all

                       select distinct
                              arreckey.k00_numpre,
                              arreckey.k00_numpar,
                              case
                                when arrecad.k00_numtot is not null then arrecad.k00_numtot
                                when arrecant.k00_numtot is not null then arrecant.k00_numtot
                                else arrepaga.k00_numtot
                              end as k00_numtot,
                              case
                                when arrecad.k00_dtvenc is not null then arrecad.k00_dtvenc
                                when arrecant.k00_dtvenc is not null then arrecant.k00_dtvenc
                                else arrepaga.k00_dtvenc
                              end as k00_dtvenc,
                              case
                                when arrecad.k00_dtoper is not null then arrecad.k00_dtoper
                                when arrecant.k00_dtoper is not null then arrecant.k00_dtoper
                                else arrepaga.k00_dtoper
                              end as k00_dtoper,
                              arreckey.k00_receit,
                              tabrec.k02_drecei,
                              arreckey.k00_hist,
                              histcalc.k01_descr,
                              ( abatimentoarreckey.k128_valorabatido +
                                abatimentoarreckey.k128_correcao     +
                                abatimentoarreckey.k128_juros        +
                                abatimentoarreckey.k128_multa  ) as valorabatido,
                              arrepaga.k00_conta,
                              arrepaga.k00_dtpaga,
                              case when arrecad.k00_tipo is not null then arrecad.k00_tipo else arrecant.k00_tipo end as k00_tipo,
                              coalesce(disbanco.dtpago,k00_dtpaga) as efetpagto,
                              'PARCIAL'                            as tipopagamento,
                              abatimento.k125_sequencial           as abatimento,
                              arrepaga.k00_numpre                  as numpreabatimento,
                              0                                    as boleto
                         from abatimentorecibo
                              inner join abatimento         on abatimento.k125_sequencial         = abatimentorecibo.k127_abatimento
                              left join abatimentodisbanco  on abatimentodisbanco.k132_abatimento = abatimento.k125_sequencial
                              left join disbanco            on disbanco.idret                     = abatimentodisbanco.k132_idret
                              inner join abatimentoarreckey on abatimentoarreckey.k128_abatimento = abatimento.k125_sequencial
                              inner join arreckey           on arreckey.k00_sequencial            = abatimentoarreckey.k128_arreckey
                              inner join tabrec             on tabrec.k02_codigo                  = arreckey.k00_receit
                              inner join histcalc           on histcalc.k01_codigo                = arreckey.k00_hist
                              left  join arrepaga           on arrepaga.k00_numpre                = abatimentorecibo.k127_numprerecibo
                              inner join arreinstit         on arreinstit.k00_numpre              = arrepaga.k00_numpre
                                                           and arreinstit.k00_instit              = ".db_getsession('DB_instit')."
                              left  join arrecant           on arrecant.k00_numpre                = arreckey.k00_numpre
                                                           and arrecant.k00_numpar                = arreckey.k00_numpar
                                                           and arrecant.k00_receit                = arreckey.k00_receit
                              left  join arrecad            on arrecad.k00_numpre                 = arreckey.k00_numpre
                                                           and arrecad.k00_numpar                 = arreckey.k00_numpar
                                                           and arrecad.k00_receit                 = arreckey.k00_receit
                              {$sInnerPagamento}
                        where abatimento.k125_tipoabatimento in (1, 4)
                              {$sWhereNumprePgto}
                              {$sWherePagamento}
                     order by efetpagto,
                              k00_numpre,
                              k00_numpar    ";
//    echo $sSqlPagamentos; exit;
  $rsPagamentos    = db_query($sSqlPagamentos);
  $iRowsPagamentos = pg_num_rows($rsPagamentos);

  $ConfCor1   = "#EFE029";
  $ConfCor2   = "#E4F471";
  $numpre_cor = "";
  $numpre_par = "";
  $qcor       = $ConfCor1;

  if ($iRowsPagamentos > 0) {

  ?>
  <div id="divDebitoTabela">
    <table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr bgcolor="#ffcc66">
        <th width="2%"  nowrap>MI</th>
        <th width="3%"  nowrap>Boleto</th>
        <th width="5%"  nowrap>Tipo</th>
        <th width="7%"  nowrap>Numpre</th>
        <th width="5%"  nowrap>Operacao</th>
        <th width="2%"  nowrap>Par</th>
        <th width="2%"  nowrap>Tot</th>
        <th width="7%"  nowrap>Venc</th>
        <th width="3%"  nowrap>Hist</th>
        <th width="17%" nowrap>Descri&ccedil;&atilde;o</th>
        <th width="3%"  nowrap>Rec.</th>
        <th width="20%" nowrap>Descri&ccedil;&atilde;o</th>
        <th width="7%"  nowrap>Valor</th>
        <th width="3%"  nowrap>Cont</th>
        <th width="7%"  nowrap>Dtpagto</th>
        <th width="7%"  nowrap>Efetpagto</th>
      </tr>
    <?
      $totalpago = 0;

      for ($iInd=0; $iInd < $iRowsPagamentos; $iInd++) {

        $oPagamento = db_utils::fieldsMemory($rsPagamentos,$iInd);


         if ( trim($oPagamento->k00_tipo) == '' ) {

          $oPagamento->k00_tipo = 0;

          $sSqlTipo = "select k00_tipo
                         from recibo
                        where k00_numpre = $oPagamento->k00_numpre
                        limit 1";

          $rsTipo  = db_query($sSqlTipo);

          if (pg_numrows($rsTipo) > 0) {
            $oPagamento->k00_tipo = db_utils::fieldsMemory($rsTipo,0)->k00_tipo;
          }
         }

        if($numpre_cor==""){
           $numpre_cor = $oPagamento->k00_numpre;
          $numpre_par = $oPagamento->k00_numpar;
        }

        if($numpre_cor != $oPagamento->k00_numpre || $numpre_par != $oPagamento->k00_numpar ){

          $numpre_cor = $oPagamento->k00_numpre;
          $numpre_par = $oPagamento->k00_numpar;

          if($qcor == $ConfCor1) {
            $qcor = $ConfCor2;
          } else {
            $qcor = $ConfCor1;
          }
        }

        $aHistorico    = array();
        $sSqlHistorico = " select k00_dtoper as dtlhist,
                                  k00_hora,
                                  login,
                                  k00_histtxt as k00_histtxt
                             from arrehist
                                  left outer join db_usuarios on id_usuario = k00_id_usuario
                            where k00_numpre = $oPagamento->k00_numpre
                              and (    k00_numpar = $oPagamento->k00_numpar
                                    or k00_numpar = 0
                                  )
                         order by k00_dtoper,
                                  k00_hora desc
                                limit 1 ";

        $rsHistorico    = db_query($sSqlHistorico);
        $iRowsHistorico = pg_num_rows($rsHistorico);

        if ($iRowsHistorico > 0){

          for($iIndHist=0; $iIndHist< $iRowsHistorico; $iIndHist++){

            $oHistorico   = db_utils::fieldsMemory($rsHistorico,$iIndHist);
            $aHistorico[] = $oHistorico->dtlhist ." "
                           .$oHistorico->k00_hora." "
                           .$oHistorico->login." "
                           .$oHistorico->k00_histtxt;

          }
        }

        $sHistorico = implode("\n",$aHistorico);
        $sHistorico = ($sHistorico!=""?str_replace("\n",'',$sHistorico):"");
        $sHistorico = ($sHistorico!=""?str_replace("\r",'',$sHistorico):"");

      ?>

      <tr bgcolor="<?=$qcor?>">
        <td align="center" nowrap >
        <a href="#" onClick="parent.js_mostradetalhes('cai3_gerfinanc025.php?<?=base64_encode($oPagamento->k00_tipo."#".$oPagamento->k00_numpre."#".$oPagamento->k00_numpar."#".$oPagamento->numpreabatimento)?>','','width=600,height=500,scrollbars=1')">
        MI
        </a>
        </td>
        <td align="center" nowrap >
          <?php if ($oPagamento->boleto) { ?>
            <a style="cursor:pointer;"><img style="width:13px;height:15px;margin-top:3px;" src="imagens/boleto.png" onclick="consultaBoleto(<?php echo ($oPagamento->k00_numpre.", ".$oPagamento->k00_numpar.", ".$oPagamento->k00_receit); ?>);"></a>
          <?php } ?>
        </td>
        <td align="center" nowrap >
          <?php
            if ( trim($oPagamento->abatimento) != 0 ) {
              db_ancora('PARCIAL',"js_consultaOrigemAbatimento($oPagamento->abatimento)",1,'');
            } else {
              echo "NORMAL";
            }
          ?>
        </td>
        <td align="right"  nowrap >
          <a OnMouseOut="parent.js_label('false','');"
             OnMouseOver="parent.js_label('true','<?=$sHistorico?>');"
             href="javascript:parent.document.getElementById('processando').style.visibility = 'visible';history.back()">
               <?=$oPagamento->k00_numpre?>
          </a>
        </td>
        <td align="center" nowrap ><?=db_formatar($oPagamento->k00_dtoper,"d")?>    </td>
        <td align="right"  nowrap ><?=$oPagamento->k00_numpar?>                     </td>
        <td align="right"  nowrap ><?=$oPagamento->k00_numtot?>                     </td>
        <td align="center" nowrap ><?=db_formatar($oPagamento->k00_dtvenc,"d")?>    </td>
        <td align="right"  nowrap ><?=$oPagamento->k00_hist?>                       </td>
        <td align="left"   nowrap ><?=str_pad($oPagamento->k01_descr,20)?>          </td>
        <td align="center" nowrap ><?=$oPagamento->k00_receit?>                     </td>
        <td align="left"   nowrap ><?=str_pad($oPagamento->k02_drecei,40)?>         </td>
        <td align="right"  nowrap ><?=db_formatar(($oPagamento->k00_valor*-1),"f")?></td>
        <td align="center" nowrap ><?=$oPagamento->k00_conta?>                      </td>
        <td align="center" nowrap ><?=db_formatar($oPagamento->k00_dtpaga,"d")?>    </td>
        <td align="center" nowrap ><?=db_formatar($oPagamento->efetpagto,'d')?>     </td>
      </tr>
      <?

        $totalpago += $oPagamento->k00_valor;
      }

      ?>

      <tr bgcolor="#ffcc66">
        <th align="center" colspan="12" nowrap>Total Pago</th>
        <th nowrap><?=db_formatar(($totalpago*-1),'f')?></th>
        <th nowrap></th>
        <th nowrap></th>
        <th nowrap></th>
      </tr>
      <tr>
        <td  colspan="11" align="center" class="tabs"><input type="button" name="imprimir" value="Imprimir" onclick="js_imprime()"></td>
      </tr>
  </table>
  </div>
  <div class="subcontainer" id="divDebitoRecibo" style="display: none;">
    <div id="gridRecibo" style="width: 1200px;"></div>
    <div style="padding-top: 15px;">
      <input name="voltar" type="button" id="voltar" value="Voltar" onclick="reciboPagoCodigoArrecadacaoVoltar();"/>
    </div>
  </div>
 <?
  } else {
    $DB_ERRO = "Não existe pagamentos efetuados para este numpre.";
  }
}
?>
</center>
</body>
</html>
<?

if(isset($DB_ERRO)) {
  ?>
  <script>
    alert('<?=$DB_ERRO?>');
    parent.document.getElementById('processando').style.visibility = 'visible';
  history.back();
  </script>
<!--

//-->
</script>
  <?
}
?>
  <script>
      function js_pesquisareceita(lMostra){

        var sQuery = '?funcao_js=parent.debitos.js_mostrareceitas|k02_codigo';
<?
          if(isset($matric)){
             echo "sQuery += '&matric={$matric}';";
          }else if(isset($inscr)){
             echo "sQuery += '&inscr={$inscr}';";
          }else if(isset($numcgm)){
             echo "sQuery += '&numcgm={$numcgm}';";
          }else {
             echo "sQuery += '&numpre={$numpre}';";
          }
?>
        if (lMostra) {
          var sUrl = 'func_tabrecpagamentosefetivados.php'+sQuery;
          js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_pagamentosefetivados',sUrl,'Pesquisa',true);

        }
      }


    function js_mostrareceitas(chave){
      document.form1.k02_codigo.value = chave;
      (window.CurrentWindow || parent.CurrentWindow).corpo.db_iframe_pagamentosefetivados.hide();
    }


    function js_consultaOrigemAbatimento(iAbatimento) {

      var sUrl = 'func_origemabatimentoparcial.php?iAbatimento='+iAbatimento+'&sOrigem=recibo';

      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_abatimento',sUrl,'Origem Pagamento Parcial',true);
    }

    function js_pesquisaprocessoforo(mostra) {

      var sQuery = "";
      <?
         if (isset($matric)) {
           echo "sQuery += '&matric={$matric}';";
         }else if(isset($inscr)){
            echo "sQuery += '&inscr={$inscr}';";
         }else if(isset($numcgm)){
            echo "sQuery += '&numcgm={$numcgm}';";
         }else {
            echo "sQuery += '&numpre={$numpre}';";
         }
      ?>

      if (mostra == true) {

        var sUrl = 'func_processoforo.php?lAnuladas=false&funcao_js=parent.js_mostraprocessoforo1|v70_sequencial|v70_codforo'+sQuery;
        js_OpenJanelaIframe('', 'db_iframe_processoforo', sUrl, 'Pesquisa', true);
      } else {

        if (document.form1.v70_sequencial.value != '') {

          var sUrl = 'func_processoforo.php?pesquisa_chave='+document.form1.v70_sequencial.value+'&funcao_js=parent.js_mostraprocessoforo&lAnuladas=false'+sQuery;
          js_OpenJanelaIframe('', 'db_iframe_processoforo', sUrl, 'Pesquisa', false);
        }
      }
    }

  function js_mostraprocessoforo(chave,erro,chave2){

    document.form1.v70_codforo.value = chave;
    if(erro==true){
      document.form1.v70_codforo.focus();
      document.form1.v70_codforo.value = '';
    }
    db_iframe_processoforo.hide();
  }

  function js_mostraprocessoforo1(chave1,chave2){
    document.form1.v70_sequencial.value = chave1;
    document.form1.v70_codforo.value = chave2;
    db_iframe_processoforo.hide();
  }

var oCollection = new Collection().setId("id");

var oGridRecibo = DatagridCollection.create(oCollection).configure({
  order  : false,
  align  : "center",
  height : "auto"
});

oGridRecibo.addColumn("numpre",              {label: "Numpre",         align: "center", width: "10%"});
oGridRecibo.addColumn("parcela",             {label: "Parcela",        align: "center", width: "4%"});
oGridRecibo.addColumn("total",               {label: "Total",          align: "center", width: "3%"});
oGridRecibo.addColumn("tipo",                {label: "Tipo",           align: "center", width: "5%"});
oGridRecibo.addColumn("tipodebitodescricao", {label: "Tipo de D\E9bito", align: "left",   width: "18%"});
oGridRecibo.addColumn("receita",             {label: "Receita",        align: "center", width: "4%"});
oGridRecibo.addColumn("receitadescricao",    {label: "Descri\E7\E3o",      align: "left",   width: "24%"});
oGridRecibo.addColumn("datavencimento",      {label: "Vencimento",     align: "center", width: "9%"});
oGridRecibo.addColumn("datapagamento",       {label: "Processamento",  align: "center", width: "9%"});
oGridRecibo.addColumn("dataefetivacao",      {label: "Pagamento",      align: "center", width: "9%"});
oGridRecibo.addColumn("valor",               {label: "Valor",          align: "right",  width: "5%"});

var oDivDebitoTabela = $('divDebitoTabela');
var oDivDebitoRecibo = $('divDebitoRecibo');

function consultaBoleto(iNumpre, iNumpar, iReceit) {

  var aParametros = {
    "sExecucao": "getDadosBoleto",
    "iNumpre": iNumpre,
    "iNumpar": iNumpar,
    "iReceit": iReceit
  }

  var ajaxCallBack = function(oRetorno, lErro) {

    oDivDebitoRecibo.hide();

    if (lErro) {

      alert(oRetorno.sMessage.urlDecode());
      return false;
    }

    if (oRetorno.oDados.aLinhas.length > 0) {

      oDivDebitoTabela.hide();
      oDivDebitoRecibo.show();

      oCollection.clear();

      oRetorno.oDados.aLinhas.each(function (oLinha, iLinha) {

        oLinha.datavencimento = js_formatar(oLinha.datavencimento, 'd');
        oLinha.datapagamento  = js_formatar(oLinha.datapagamento, 'd');
        oLinha.dataefetivacao = js_formatar(oLinha.dataefetivacao, 'd');
        oLinha.valor = js_formatar(oLinha.valor, 'f');
        
        oLinha.id = iLinha;

        oCollection.add(oLinha);
      });

      oGridRecibo.grid.aHeaders = new Array();
      oGridRecibo.reload();
      oGridRecibo.show($("gridRecibo"));

    } else {

      alert("Nenhum registro encontrado para o(s) filtro(s) selecionado(s)");
      return false;
    }
  }

  var oAjaxRequest = new AjaxRequest("cai3_gerfinanc.RPC.php", aParametros, ajaxCallBack);

  oAjaxRequest.setMessage("Aguarde...").execute();
}

function reciboPagoCodigoArrecadacaoVoltar() {

  oDivDebitoTabela.show();
  oDivDebitoRecibo.hide();
}
</script>
