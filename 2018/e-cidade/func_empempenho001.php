<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/Dotacao.model.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_empelemento_classe.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_empempaut_classe.php"));
require_once(modification("classes/db_empemphist_classe.php"));
require_once(modification("classes/db_emphist_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_empagemov_classe.php"));
require_once(modification("classes/db_empautitem_classe.php"));
require_once(modification("classes/db_empempitem_classe.php"));
require_once(modification("classes/db_empempenhonl_classe.php"));
require_once(modification('classes/db_empresto_classe.php'));
require_once(modification("dbforms/verticalTab.widget.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clempempenho   = new cl_empempenho;
$clempempenhonl = new cl_empempenhonl;
$clempelemento  = new cl_empelemento;
$clorcdotacao   = new cl_orcdotacao;
$clempempaut    = new cl_empempaut;
$clempemphist   = new cl_empemphist;
$clemphist      = new cl_emphist;
$clorctiporec   = new cl_orctiporec;
$clempagemov    = new cl_empagemov;
$clempautitem   = new cl_empautitem;
$clempempitem   = new cl_empempitem;
$clempresto     = new cl_empresto;

$clClassificacaoCredorEmpenho = new cl_classificacaocredoresempenho();
$clClassificacaoCredorEmpenho->rotulo->label("cc31_justificativa");
$clClassificacaoCredorEmpenho->rotulo->label("cc31_classificacaocredores");

$clempempenho->rotulo->label();
$clempempaut->rotulo->label();
$clempemphist->rotulo->label();
$clemphist->rotulo->label();
$clorctiporec->rotulo->label();
$clempagemov->rotulo->label();
$iAnousu             = db_getsession("DB_anousu");
$lNotaLiquidacao     = false;
$lPermissaoImpressao = db_permissaomenu(db_getsession("DB_anousu"),398,4754);
$e61_autori          = '';
if (isset($e60_numemp) and $e60_numemp != "") {


  $sCampos = "*, (select rh76_rhempenhofolha from rhempenhofolhaempenho where rh76_numemp = {$e60_numemp}) as empenho_folha";
  $sSqlBuscaEmpenho = $clempempenho->sql_query($e60_numemp, $sCampos);
  $res = $clempempenho->sql_record($sSqlBuscaEmpenho);
  if ($clempempenho->numrows > 0 ) {

    db_fieldsmemory($res,0,true);
    $rsNotLiq = $clempempenhonl->sql_record($clempempenhonl->sql_query_file(null,"*",null,"e68_numemp={$e60_numemp}"));
    if ($clempempenhonl->numrows > 0) {
      $lNotaLiquidacao = true;
    }
    //-----
    $ra=$clempempaut->sql_record($clempempaut->sql_query_file($e60_numemp));
    if ($clempempaut->numrows > 0) {
      db_fieldsmemory($ra,0,true);
    }

    /**
     * Busca o processo
     */
    $oDaoEmpAutorizaProcesso  = db_utils::getDao("empautorizaprocesso");
    $sWhereBuscaProcessoAdmin = " e150_empautoriza = {$e61_autori}";
    $sSqlBuscaProcessoAdmin   = $oDaoEmpAutorizaProcesso->sql_query_file(null, "e150_numeroprocesso", null, $sWhereBuscaProcessoAdmin);
    $rsBuscaProcessoAdmin     = $oDaoEmpAutorizaProcesso->sql_record($sSqlBuscaProcessoAdmin);
    $sProcessoAdministrativo  = "";

    if ($rsBuscaProcessoAdmin && $oDaoEmpAutorizaProcesso->numrows > 0) {
      $sProcessoAdministrativo = db_utils::fieldsMemory($rsBuscaProcessoAdmin, 0)->e150_numeroprocesso;
    }

    //------
    $rhist=$clempemphist->sql_record($clempemphist->sql_query($e60_numemp));
    if ($clempemphist->numrows > 0) {
      db_fieldsmemory($rhist,0,true);
    }
  }else{

    echo '<html>';
    echo '<head>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
    echo '<link href="estilos.css" rel="stylesheet" type="text/css">';
    echo '<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>';
    echo '</head>';
    echo '<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">';
    echo '<script>';
    echo 'alert("Empenho não encontrado.")';
    echo '</script>';
    echo '<body>';
    echo '</html>';
    exit;
  }
}
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/infoLancamentoContabil.classe.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script>
    function js_gerar_relatorio() {

      var permissao_gerar = <?=db_permissaomenu(db_getsession("DB_anousu"),398,4754)?>;

      if (permissao_gerar == true) {
        tabDetalhes.location.href='emp2_consultas001.php';
        //jan = window.open('emp2_consultas.php?e60_numemp=<?=@$e60_numemp?>&permissao='+permissao_gerar,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      } else {
        alert('Você não tem permissão para imprimir!');
      }

    }
    function pesquisa_cgm(){
      js_JanelaAutomatica('cgm','<?=@$e60_numcgm ?>');
    }
    function pesquisa_dot(){
      js_JanelaAutomatica('orcdotacao','<?=@$e60_coddot ?>','<?=@$e60_anousu ?>');
    }
    function pesquisa_autori(){
      js_JanelaAutomatica('empautoriza','<?=@$e61_autori ?>');
    }

  </script>
  <style>
    .valores {background-color:#FFFFFF}
  </style>
</head>
<body bgcolor="#CCCCCC">
<form name='form1'>
  <fieldset><legend><B>Dados do Empenho</b></legend>
    <table  border="0"  cellspacing="1">
      <tr>
        <td nowrap="nowrap"  align="left"  title="<?=$Te60_numemp?>">
          <?=$Le60_numemp?>
        </td>
        <td nowrap="nowrap"  align="left" class='valores' style="width:80px">
          <?=$e60_numemp; ?>
        </td>
        <td nowrap="nowrap" align="left" title="<?=$Te60_codemp ?>" width="45px" >
          <?=$Le60_codemp ?>
        </td>
        <?
        /*
         * Consulta na tabela empresto, para ver se possui registro do numero de empenho no ano corrente
         * se tiver, exibe a iformação de restos a pagar
         */
        $sSqlEmpresto = $clempresto->sql_query("", "", "*", "",
                                               "e91_numemp = {$e60_numemp} AND e91_anousu = {$iAnousu}");
        $rsEmpresto   = $clempresto->sql_record($sSqlEmpresto);
        if ($clempresto->numrows > 0) {
          db_fieldsmemory($rsEmpresto,0);
          $sRestos = " - <font color='red'><b>RESTOS À PAGAR</b></font>";
          $sRestosPagar = $e91_codtipo ." - ". $e90_descr;
        } else {
          $sRestos = "";
          $sRestosPagar = "";
        }
        ?>

        <td nowrap="nowrap" class='valores'>
          <?
          echo "{$e60_codemp}/{$e60_anousu}";
          if ($e60_anousu != db_getsession("DB_anousu")) {
            echo $sRestos;
          }
          ?>
        </td>

        <?  //-----------  dotacão
        if (isset($e60_coddot) and ($e60_coddot !="")) {

          $sql = $clorcdotacao->sql_query($e60_anousu,
                                          $e60_coddot,
                                          "o56_elemento,o56_descr,
                                           fc_estruturaldotacao(o58_anousu,o58_coddot) as o58_estrutdespesa, o15_descr"
          );
          $res = $clorcdotacao->sql_record($sql);
          if ($clorcdotacao->numrows >0 ) {
            db_fieldsmemory($res,0,true);
          }
        }
        ?>
        <td nowrap="nowrap" align="left" title="<?=$Te60_coddot ?>">
          <?
          db_ancora($Le60_coddot,"pesquisa_dot();",1); ?>
        </td>
        <td nowrap="nowrap" colspan='1' align="right" width='15' class='valores'>
          <?
          echo $e60_coddot;
          ?>
        </td>
        <td class='valores' colspan='2' nowrap>
          <?=$o40_descr ?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" width="160" align="left" nowrap title="Tipo de Restos a Pagar">
          <strong>Tipo de Restos a Pagar:</strong>
        </td>
        <td nowrap="nowrap" class='valores' colspan="3">
          <?php echo $sRestosPagar;?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap" width="160" align="left" nowrap title="Número do processo administrativo (P.A.)">
          <strong>Proc. Adminstravivo (P.A):</strong>
        </td>
        <td nowrap="nowrap" class='valores' colspan="3">
          <?php echo $sProcessoAdministrativo;?>
        </td>
        <td nowrap="nowrap" align="left" nowrap title="<?=$To15_descr?>">
          <b>Recurso:</b>
        </td>
        <td nowrap="nowrap" colspan='1' align="right" width='15' class='valores'>
          <?
          echo $o15_codigo;
          ?>
        </td>
        <td nowrap="nowrap" colspan=2 class='valores'  align="left" >
          <?
          echo $o15_descr;
          ?>
        </td>
        <?
        $oDaoEmpautidot = db_utils::getDao("empautidot");
        $rsEmpautidot   = $oDaoEmpautidot->sql_record($oDaoEmpautidot->sql_query_dotacao($e61_autori));
        if ($oDaoEmpautidot->numrows > 0) {


          $oContrapartida = db_utils::fieldsMemory($rsEmpautidot, 0);
          if ($oContrapartida->e56_orctiporec != '') {

            echo "<tr> ";
            echo "<td colspan='2'>&nbsp;</td>";
            echo " <td  nowrap ";
            echo "<b>Contrapartida:</b></td>";
            echo "<td colspan='1' align='left' width='15' class='valores'>";
            echo $oContrapartida->e56_orctiporec;
            echo "</td>";
            echo "<td class='valores' colspan='2' nowrap>";
            echo $oContrapartida->o15_descr;
            echo "</td>";
            echo "</tr>";
          }

        }
        ?>
      </tr>
      <tr>
        <td   align="left" nowrap title="<?=$Te60_emiss?>"><?=$Le60_emiss?></td>
        <td   align="left" nowrap class='valores' colspan="3">
          <?  if(isset($e60_emiss) and ($e60_emiss != "")){
            list($e60_emiss_dia,$e60_emiss_mes,$e60_emiss_ano)= split('[/.-]',$e60_emiss);
          }
          echo "{$e60_emiss_dia}/{$e60_emiss_mes}/{$e60_emiss_ano}";
          ?>
        </td>
        <td>
          <b>Desdobramento:</b>
        </td>
        <td width="15" class='valores' style='text-align:right'>
          <?
          $e64_codele = ' ';
          $o56_descr  = ' ';
          $result     = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp, null, "e64_codele, o56_elemento,
                                                                           o56_descr","e64_codele"));
          if ($clempelemento->numrows > 0) {
            db_fieldsmemory($result,0);
          }
          echo $e64_codele;
          ?>
        </td>
        <td class='valores'> <?=$o56_elemento?></td>
        <td  class='valores'>
          <?
          echo $o56_descr;
          ?>
        </td>
      </tr>
      <tr>
        <td  align="left" nowrap title="<?=$Te60_vencim ?>">
          <?=$Le60_vencim ?>
        </td>
        <td class='valores' colspan="3">
          <? if (isset($e60_vencim) and ($e60_vencim != "")) {
            list($e60_vencim_dia,$e60_vencim_mes,$e60_vencim_ano) = split('[/.-]',$e60_vencim);
          }
          echo "{$e60_vencim_dia}/{$e60_vencim_mes}/{$e60_vencim_ano}";
          ?>
        </td>
        <td  align="left" nowrap>
          <? db_ancora("<b>Credor:</b>","pesquisa_cgm();",1);?></b></td>
        <td  colspan=3 class='valores'  align="left" nowrap title="<?=$z01_nome ?>">
          <?
          echo $z01_nome;
          ?>
        </td>
      </tr>
      <tr>
        <td  align="left" nowrap title="<?=$Te60_destin ?>">
          <?=$Le60_destin ?>
        </td>
        <td class='valores' colspan="3">
          <?=$e60_destin==''?'&nbsp;':$e60_destin;?>
        </td>
        <td>
          <?=@$Le63_codhist ?>
        </td>
        <td colspan='3' class='valores'>
          <?
          echo @$e40_descr;
          ?>
        </td>
      </tr>
      <tr>
        <td  align="left" nowrap title="<?=$Te61_autori?>">
          <? db_ancora($Le61_autori,"pesquisa_autori();",1);    ?></td>
        <td  align="left" nowrap class='valores' colspan="3">
          <?
          echo $e61_autori;
          ?>
        </td>
        <td  align="left" nowrap title="<?=$Te60_codtipo ?>">
          <?
          echo $Le60_codtipo;
          ?>
        </td>
        <td class='valores' colspan='3'>
          <?
          echo $e41_descr;
          ?>
        </td>
      </tr>
      <tr>
        <?
        $result_licita = $clempautitem->sql_record($clempautitem->sql_query_lic(
          null,
          null,"distinct l20_codigo,l20_dtpublic,l20_numero,l03_codcom,l03_descr",
          null,
          "e55_autori = ". @$e61_autori));
        if ($clempautitem->numrows > 0) {

        db_fieldsmemory($result_licita,0);
        $arr_data  = split("-",$l20_dtpublic);
        $ano_lic   = $arr_data[0];
        $numerolic = $l20_numero."/".$ano_lic;
        db_input("l20_codigo",10,"",true,"hidden",3);
        ?>
        <td  align="left" nowrap><b>
            <?=@$Le60_codcom?></b>
        </td>
        <td  align="left" class='valores' colspan="3">
          <?echo $l03_descr; ?>resumo
        </td>
        <td  align="left" nowrap>
          <b>
            <?
            db_ancora($Le60_numerol,"pesquisa_lic();",1);
            ?>
          </b>
        </td>
        <td colspan='3' class='valores'>
          <?
          echo $numerolic;
          ?>
        </td>
      </tr>
      <?
      } else {
   ?>
      <td  align="left" nowrap>
        <b><?=$Le60_codcom?> </b>
      </td>
      <td  align="left" nowrap class='valores' colspan="3">
       <?=$pc50_descr; ?>
      </td>
       <td  align="left" nowrap>
         <b><?=$Le60_numerol?>
       </td>
       <td colspan='3' class='valores'>
         <?
          echo $e60_numerol;
         ?>
       </td>
    </tr>
  <?
  }

      $sListaCredor   = " Empenho não classificado";
      $sJustificativa = null;

      $sCamposListaCredor = " cc30_codigo, cc30_descricao, cc31_justificativa ";
      $sWhereListaCredor  = " cc31_empempenho = {$e60_numemp} ";

      $oDaoListaCredor = new cl_classificacaocredoresempenho();
      $sSqlListaCredor = $oDaoListaCredor->sql_query(null, $sCamposListaCredor, null, $sWhereListaCredor);
      $rsListaCredor   = $oDaoListaCredor->sql_record($sSqlListaCredor);

      if ($rsListaCredor != false && $oDaoListaCredor->numrows > 0) {

        $oListaCredor   =  db_utils::fieldsMemory($rsListaCredor, 0);
        $sListaCredor   = "{$oListaCredor->cc30_codigo} - {$oListaCredor->cc30_descricao}";
        $sJustificativa = $oListaCredor->cc31_justificativa;
      }

      ?>
      <tr>
        <td  align="left" nowrap title="<?= $Tcc31_classificacaocredores ?>">
          <b><?= $Lcc31_classificacaocredores ?></b>
        </td>
        <td  align="left" nowrap class='valores' colspan="3">
          <?= $sListaCredor  ?>
        </td>
        <td  align="left" nowrap>
        </td>
        <td colspan='3'>
        </td>
      </tr>

      <?php
      if (!empty($sJustificativa)) {
        ?>
        <tr>
          <td align="left" nowrap title="<?= $Tcc31_justificativa ?>">
            <b><?= $Lcc31_justificativa ?></b>
          </td>
          <td colspan='8' width='100%' class='valores'>
            <?= nl2br($sJustificativa) ?>
        </tr>
        <?php
      }
      ?>

      <tr>
        <td align="left" nowrap title="<?=$Te60_resumo ?>">
          <?=$Le60_resumo ?>
        </td>
        <td colspan='8' width='100%' class='valores'>
          <?
          echo nl2br($e60_resumo);
          ?>
      </tr>

      <?php

      if (!empty($empenho_folha)) {

        ?>
        <tr>
          <td align="left" nowrap>
            <b>Origem:</b>
          </td>
          <td colspan='8' width='100%' class='valores'>
            Folha de Pagamento
        </tr>
        <?

      }

      ?>

    </table>
  </fieldset>
  <fieldset style='padding-left:0px'><legend><b>Detalhamento</b></legend>
    <?
    $oTabDetalhes = new verticalTab("detalhesemp",300);
    $oTabDetalhes->add("resmovim", "Resumo da Movimentação","func_empempenho002.php?e60_numemp={$e60_numemp}");

    $oTabDetalhes->add("itensempNovo", "Itens do Empenho",
                       "emp2_consultaitensempenho001.php?e60_numemp={$e60_numemp}&e55_autori={$e61_autori}");


    $oTabDetalhes->add("lancamemp", "Lançamentos Contábeis","func_conlancam002.php?chavepesquisa={$e60_numemp}");
    $oTabDetalhes->add("notasemp", "Notas de Liquidação","func_empnota001.php?e60_numemp={$e60_numemp}");
    $oTabDetalhes->add("opsemp", "Pagamentos","func_pagordem002.php?e60_numemp={$e60_numemp}");
    $oTabDetalhes->add("ordensemp", "Ordens de Compra",
                       "func_consultamatordememp001.php?m52_numemp={$e60_numemp}&funcao_js=js_mostraordem|m51_codordem");
    $lDetalhesAut  = true;
    if ($e61_autori == '') {
      $lDetalhesAut = false;
    }
    $oTabDetalhes->add("solicitaemp", "Solicitações de Compra","func_solicita001.php?e55_autori={$e61_autori}", $lDetalhesAut);
    $oTabDetalhes->add("pcemp", "Processo de Compras","func_pcproc001.php?e55_autori={$e61_autori}",$lDetalhesAut);
    $oTabDetalhes->add("agendaemp", "Agenda de Pagamentos","func_empempage001.php?e60_numemp={$e60_numemp}");
    //$oTabDetalhes->add("prestaconta", "Prestação de Contas","", false);
    $oTabDetalhes->add("contratos", "Contratos","emp2_listacontratosempenho001.php?e60_numemp={$e60_numemp}");
    $oTabDetalhes->add("impressao", "Imprimir Consulta","emp2_consultas001.php?e60_numemp={$e60_numemp}", $lPermissaoImpressao);
    $oTabDetalhes->show();
    ?>
  </fieldset>
</form>
</body>
</html>
<script>
  function js_infoLancamento(iLancamento) {
    var oLancamentoInfo = new infoLancamentoContabil(iLancamento);
  }
  function js_exibeContrato(iNumero){

    var sQuery = "";
    var ac16_sequencial = iNumero;
    sQuery = "ac16_sequencial="+ac16_sequencial;
    var iLargura = document.width - 10;
    var iAltura  = getDocHeight() - 50;
    js_OpenJanelaIframe('','db_iframe_consultaabertura',
      'con4_consacordos003.php?'+sQuery,
      'Detalhes',true, 0, 0, iLargura);
  }
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
