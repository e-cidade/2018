<?php
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_conrelinfo_classe.php"));
require_once(modification("classes/db_conrelvalor_classe.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));
require_once(modification("classes/db_orcparamseq_classe.php"));
require_once(modification("classes/db_orcparamelemento_classe.php"));
require_once(modification("classes/db_orcparamrecurso_classe.php"));
require_once(modification("classes/db_orcparamsubfunc_classe.php"));
require_once(modification("classes/db_orcparamnivel_classe.php"));
require_once(modification("classes/db_orcparamfunc_classe.php"));
require_once(modification("model/linhaRelatorioContabil.model.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if (!isset($iCodigoPeriodo)) {
  $iCodigoPeriodo = null;
}

$clconrelinfo       = new cl_conrelinfo;
$clconrelvalor      = new cl_conrelvalor;
$clorcparamrel      = new cl_orcparamrel;
$clorcparamseq      = new cl_orcparamseq;
$clorcparamelemento = new cl_orcparamelemento;
$clorcparamrecurso  = new cl_orcparamrecurso;
$clorcparamsubfunc  = new cl_orcparamsubfunc;
$clorcparamfunc     = new cl_orcparamfunc;

$oGet               = db_utils::postMemory($_GET);
$iCodigoRelatorio   = $oGet->c83_codrel;

$clrotulo = new rotulocampo;
$clrotulo->label('c83_codrel');
$clrotulo->label('o42_descrrel');

$db_opcao = 1;
$db_botao = true;
$iInstit  = db_getsession('DB_instit');

if (!isset($filtrar_seq)) {
  $filtrar_seq = "C";
}

$res = $clorcparamrel->sql_record($clorcparamrel->sql_query($c83_codrel));
if ($clorcparamrel->numrows > 0) {
  db_fieldsmemory($res, 0);
}
function atualiza_nivel($rel,$linha,$valor){

  $msg = "0| Registro Atualizado !";
  $clorcparamnivel = new cl_orcparamnivel;

  $sSql = $clorcparamnivel->sql_query_file(db_getsession("DB_anousu"),$rel,$linha);
  $res  = $clorcparamnivel->sql_record($sSql);

  $clorcparamnivel->o44_codparrel     = $rel;
  $clorcparamnivel->o44_sequencia     = $linha;
  $clorcparamnivel->o44_anousu        = db_getsession("DB_anousu");
  $clorcparamnivel->o44_nivelexclusao = '0';

  $clorcparamnivel->o44_nivel         = $valor;
  if ($clorcparamnivel->numrows > 0) {

    $clorcparamnivel->o44_nivelexclusao = '0';
    $clorcparamnivel->alterar(db_getsession("DB_anousu"),$rel,$linha);
  } else {

    $clorcparamnivel->o44_nivelexclusao = '0';
    $clorcparamnivel->incluir(db_getsession("DB_anousu"),$rel,$linha);
  }

  $erro = $clorcparamnivel->erro_msg;
  if ($clorcparamnivel->erro_status == 0) {
    $msg= "1| Falha ao atualizar Nivel".$erro;
  }

  return $msg;
}
function atualiza_nivel_exclusao($rel, $linha, $valor) {

  $msg = "0| Registro Atualizado !";
  $clorcparamnivel = new cl_orcparamnivel;

  $res = $clorcparamnivel->sql_record($clorcparamnivel->sql_query_file(db_getsession("DB_anousu"),$rel,$linha));

  $clorcparamnivel->o44_codparrel     = $rel;
  $clorcparamnivel->o44_sequencia     = $linha;
  $clorcparamnivel->o44_anousu        = db_getsession("DB_anousu");
  $clorcparamnivel->o44_nivelexclusao = $valor;

  if ($clorcparamnivel->numrows > 0) {

    $clorcparamnivel->o44_nivel = "" ;
    $clorcparamnivel->alterar(db_getsession("DB_anousu"),$rel,$linha);
  } else {

    $clorcparamnivel->o44_nivel = '0' ;
    $clorcparamnivel->incluir(db_getsession("DB_anousu"),$rel,$linha);
  }

  $erro = $clorcparamnivel->erro_msg;
  if ($clorcparamnivel->erro_status == 0) {
    $msg = "1| Falha ao atualizar Nivel" . $erro;
  }

  return $msg;
}

require_once(modification("dbforms/Sajax.php"));  // inclusão da biblioteda ajax
sajax_init();// Inicializar o sajax
$sajax_debug_mode = 0;// para Debugar o sajax = 0 desligado 1 = ligado
sajax_export("atualiza_nivel");// função exportada !
sajax_export("atualiza_nivel_exclusao");// função exportada !
sajax_handle_client_request();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/filtroorcamento.widget.js");
db_app::load("widgets/messageboard.widget.js");
db_app::load("estilos.css,grid.style.css");
?>
<script>
function js_filtrarSeq() {
  document.form1.submit();
}

</script>
<style>
tr.linhas:HOVER {background-color: #999999}
</style>
</head>
<body style="background-color: #CCCCCC" >

<form name="form1" method="post" action="" >
<?php
$dbwhere   = "o69_codparamrel = $c83_codrel";

/**
 * Se relatório do anexo VIII então esconde a linha 19
 * Se relatório do anexo VI então esconde a linha 17
 */
switch ($c83_codrel) {
  case 165:
    $dbwhere .= " AND o69_codseq <> 19";
    break;

  case 146:
    $dbwhere .= " AND o69_codseq <> 17";
    break;
}

$lista_seq = "";
$virgula   = "";
?>
<table  border=0  width="100%">
<tr>
  <td>
    <fieldset>
      <table border = 0>
        <tr>
          <td align = "left">
            <?php db_ancora(@trim($Lc83_codrel), "js_pesquisac60_codcla(true);" ,3);?>
          </td>
          <td>
            <?php db_input('c83_codrel',    5,   $Ic83_codrel, true, 'text', 3, "")?>
            <?php db_input('o42_descrrel', 60, $Io42_descrrel, true, 'text', 3, "")?>
          </td>
      </tr>
    </table>
  </fieldset>
 </td>
</tr>
<tr>
  <td colspan=8 height=20px>
   <fieldset>
   <table cellpadding="0" cellspacing="0" width="98%" style='border:2px inset white'>
   <tr>
     <td width="8%" class="table_header"><b>Linha</b></td>
     <td width=65% class="table_header"><b>Descrição</b></td>
     <td width=5% class="table_header"><b>Configuração Padrão</b></td>
     <td width=10% class="table_header"><b>Customizar Configuração</b></td>
     <td width=10% class="table_header"><b>Edição</b></td>
     <td style='width:3%' class="table_header">&nbsp;</td>
   </tr>
   <tbody style='height:500;overflow: scroll;overflow-x:hidden;background-color: white;'>

 <?php
$sSqlLinhas =  $clorcparamseq->sql_query_nivel($c83_codrel,
                                         null,
                                          "distinct o69_codparamrel,
                                          o69_codseq,
                                          o69_grupo,
                                          o69_grupoexclusao,
                                          o69_descr,
                                          o69_librec,
                                          o69_libsubfunc,
                                          o69_libfunc,
                                          o69_manual,
                                          o69_ordem,
                                          o69_totalizador,
                                          o69_labelrel,
                                          o69_nivellinha,
                                          o44_nivel as o69_nivel,
                                          o44_nivelexclusao as o69_nivelexclusao,
                                          o69_libnivel",
                                          "o69_ordem",
                                          "$dbwhere"
                                         );

$record = $clorcparamseq->sql_record($sSqlLinhas);
if ($clorcparamseq->numrows > 0 )
  for ($x = 0; $x < pg_numrows($record); $x++) {

    db_fieldsmemory($record,$x);
    // Permissao de menu para alterar parametro de relatorio, modulo 209 (Contabilidade)
    $flag_permissao = db_permissaomenu(db_getsession("DB_anousu"),209,228050);

    if ($flag_permissao == "true"){
      $lb_texto = "Editar";
    } else {
      $lb_texto = "Visualizar";
    }
    $sStyleLinha = "padding-left:".($o69_nivellinha*10)."px;";
    if ($o69_totalizador == 't') {
      $sStyleLinha .= 'font-weight:bold;';
    }
     ?>
      <tr style='height:1em;<?=$sStyleLinha?>' class='linhas'>

        <td class='linhagrid'style='text-align:right'>
        <?=$o69_ordem?></td>
        <td class='linhagrid' style='text-align:left;<?=$sStyleLinha?>' id='descr<?=$o69_codseq."_".$c83_codrel?>'>
        <?=$o69_labelrel?></td>
        <td valign=top class='linhagrid' style='text-align:center'>
        <?

         $sSql  = "select * from orcparamseqfiltropadrao";
         $sSql .= " where o132_orcparamseq = {$o69_codseq}";
         $sSql .= "   and o132_orcparamrel = {$c83_codrel}";
         $sSql .= "   and o132_anousu      = ".db_getsession("DB_anousu");
         $rsFiltroPadrao = db_query($sSql);
         if (pg_num_rows($rsFiltroPadrao) > 0) {
           echo "<a href='#' onclick='js_mostrapadrao({$o69_codseq},{$c83_codrel});return false'>Ver</a>";
         }else {
           echo "&nbsp;";
         }
         $oLinhaRelatorio = new linhaRelatorioContabil($c83_codrel,$o69_codseq);
         ?>
        </td>
        <td valign=top class='linhagrid' style='text-align:center'>
        <?
         if ($o69_totalizador == 'f' && $o69_grupoexclusao == 0) {

           echo "<a href='#' onclick='js_mostrafiltrousuario({$o69_codseq},{$c83_codrel});return false'>Editar</a>";
           $sSql  = "select * from orcparamseqfiltroorcamento";
           $sSql .= " where o133_orcparamseq = {$o69_codseq}";
           $sSql .= "   and o133_orcparamrel = {$c83_codrel}";
           $sSql .= "   and o133_anousu      = ".db_getsession("DB_anousu");
           $rsFiltroPadrao = db_query($sSql);
           if (pg_num_rows($rsFiltroPadrao) > 0) {
             echo "<img src='imagens/action_ok.png' align='topmargin' border='0'>";
           } else {
             echo "<img src='imagens/fundo_transparente_12x12.png' align='topmargin' border='0'>";
           }
         } else {
           echo '&nbsp;';
         }
         ?>
      	</td>
        <td class='linhagrid' style='text-align:center' valign="top">
	      <?
	       if ($o69_manual == "t") {

    	     $aColunas = $oLinhaRelatorio->getCols();
    	     $avalores  = array();
    	     if (count($aColunas) > 0) {

    	       db_ancora("Edição Manual","js_editar_colunas($c83_codrel,$o69_codseq);return false;",1);


  	           $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);
  	           $avalores  = $oLinhaRelatorio->getValoresColunas(null, null, null, db_getsession("DB_anousu"));
      	       if (count($avalores) > 0) {
                echo "<img src='imagens/action_ok.png' align='topmargin' border='0'>";
      	       } else {
                 echo "<img src='imagens/fundo_transparente_12x12.png' align='topmargin' border='0'>";
               }
    	     } else {
    	       echo "&nbsp;";
    	     }
  	     } else {
  	       echo "&nbsp;";
  	     }
 	    ?>
       </td>
       <td>&nbsp;</td>
      </tr>
     <?php
 }
?>
<tr style="height: auto;"><td colspan="6">&nbsp;</td></tr>
</tbody>
</table>
</div>
</td>
</tr>
</table>

</form>
</body>
<html>
<script>
var iPeriodo = '<?=$iCodigoPeriodo?>';
var sURlRPC = "con4_configuracaorelatorioRPC.php";
function js_editar_elemento(codrel,linha,grupo_de_contas ) {
    js_OpenJanelaIframe('','wndContas',
                       'func_selecionaparametrocontas.php?o69_codparamrel='+codrel+
                       '&o69_codseq='+linha+
                       '&grupo='+grupo_de_contas+'&flag_permissao=<?=$flag_permissao?>',
                       'Escolha as Contas',
                       true
     );
}
function js_editar_exclusao_elemento(codrel,linha,grupo_de_contas){
    parent.iframe_parametro.location.href = 'func_seleciona_exclusao_plano.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&grupo='+grupo_de_contas+'&flag_permissao=<?=$flag_permissao?>';
}
function js_editar_recurso(codrel,linha){
    parent.iframe_parametro.location.href = 'func_seleciona_recursos.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&flag_permissao=<?=$flag_permissao?>';
}
function js_editar_subfunc(codrel,linha){
    parent.iframe_parametro.location.href = 'func_seleciona_subfunc.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&flag_permissao=<?=$flag_permissao?>';
}
function js_editar_func(codrel,linha){
    parent.iframe_parametro.location.href = 'func_seleciona_func.php?o69_codparamrel='+codrel+'&o69_codseq='+linha+'&flag_permissao=<?=$flag_permissao?>';
}
function js_refresh(){
   atualiza_hp();
}
function atualiza_hp(){
   document.form1.submit();
}
<? sajax_show_javascript();   /* imprime a função do sajax */ ?>
function js_updateNivel(relatorio,linha,valor){
   x_atualiza_nivel(relatorio,linha,valor,mensagem);
}
function js_updateNivelExclusao(relatorio,linha,valor){
   x_atualiza_nivel_exclusao(relatorio,linha,valor,mensagem);
}
function mensagem(retorno){
  if (retorno.substr(0,1)!='0') {
    alert(retorno);
   }
}
function js_editar_colunas(iCodRel, iLinha){

	  iAnoPesquisa = <?=db_getsession("DB_anousu")?>;
    var iPeriodo = (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_relatorio.document.getElementById('o116_periodo').value;
    if (iPeriodo == '0') {

      alert('Para configurar manualmente os valores das colunas é necessário selecionar um Período.');
      return false;
    }

    var sUrl='con4_orcrelatorioscolunas.php?iCodRel='+iCodRel+'&iLinha='+iLinha+'&iPeriodo='+iPeriodo+'&iAnoPesquisa='+iAnoPesquisa;
    var iWidth  = document.body.getWidth() - 10;
    var iHeight = document.body.scrollHeight - 100;

	  if ($('windowColunas')) {
	    oWindowColunas.destroy();
	  }

    oWindowColunas = new windowAux("windowColunas", "Tela de Edição Manual",iWidth, iHeight);
    var sConteudo = "<iframe width='100%' style='height:99%' frameborder=0 src='"+sUrl+"'>";
    oWindowColunas.setContent(sConteudo);
     var iCenter = (iWidth)/3
    $('windowwindowColunas_btnclose').onclick=function() {

     oWindowColunas.destroy();
     location.href = location.href;
    };

    oWindowColunas.allowCloseWithEsc(false);
    oWindowColunas.allowDrag(false);
    oWindowColunas.show(3, 2);
}

 function js_criafiltro(iLinha, iRelatorio) {
   js_getDadosLinhas(iLinha, iRelatorio)
 }


 function js_saveParametro(iRelatorio, iLinha) {

   var oParam = new Object();
   oParam.iRelatorio = iRelatorio;
   oParam.iLinha     = iLinha;
   oParam.exec       = "salvarParametrosUsuario";
   oParam.filters = new Object();
   oParam.filters.orgao     = filtro.getOrgaos();
   oParam.filters.unidade   = filtro.getUnidades();
   oParam.filters.funcao    = filtro.getFuncoes();
   oParam.filters.subfuncao = filtro.getSubFuncoes();
   oParam.filters.programa  = filtro.getProgramas();
   oParam.filters.projativ  = filtro.getProjAtivs();
   oParam.filters.recurso   = filtro.getRecursos();
   js_divCarregando('Aguarde, Salvando Dados',"msgBox");
   $('btnSalvar').disabled = true;
   var oAjax = new Ajax.Request(sURlRPC,
                                {method:"post",
                                 parameters:"json="+Object.toJSON(oParam),
                                 onComplete:js_retornosaveParametro
                                }
                               );

 }

 function js_retornosaveParametro(oAjax) {

   $('btnSalvar').disabled = false;
   js_removeObj("msgBox");
   var oRetorno = eval("("+oAjax.responseText+")");
   if (oRetorno.status == 1) {

     alert('Dados Salvos com sucesso.');
     filtro.window.destroy();
     location.href = location.href;
   } else {
     alert(oRetorno.message.urlDecode());
   }
 }

 function js_getDadosLinhas(iLinha, iRelatorio) {

   var oParam = new Object();
   oParam.iRelatorio = iRelatorio;
   oParam.iLinha     = iLinha;
   oParam.exec       = "getParametrosUsuario";

   var oAjax = new Ajax.Request(sURlRPC,
                                {method:"post",
                                 parameters:"json="+Object.toJSON(oParam),
                                 onComplete:js_retornoGetDadosLinha
                                }
                               );
 }

 function js_retornoGetDadosLinha(oAjax) {

   var oRetorno = eval("("+oAjax.responseText+")");
   if (oRetorno.status == 1) {

      filtro = new filtroOrcamento(oRetorno.iLinha+"_"+oRetorno.iRelatorio);
      filtro.showSaveButton(true);
      filtro.setCallBackSave(function (){js_saveParametro(oRetorno.iRelatorio, oRetorno.iLinha)});
      filtro.setData(oRetorno.filter);
      var oMessageBoard = new messageBoard("msg0",
                                           "Filtro para linha "+
                                           $('descr'+oRetorno.iLinha+"_"+oRetorno.iRelatorio).innerHTML,
                                           "Marque os itens que deseja vincular a linha. "+
                                           "Para salvar a informação, clique em salvar.",
                                          $("windowwindowFiltros"+oRetorno.iLinha+"_"+oRetorno.iRelatorio+"_content")
                                          );
      oMessageBoard.show();
      filtro.show();
   }
 }

 function js_mostrafiltrousuario(iLinha, iRelatorio) {

   if ($('windowFiltroUsuario')) {
     owindowFiltroUsuario.destroy();
   }

   oWindowFiltroUsuario = new windowAux("windowFiltroUsuario", "Tela de Configuração do Usuário", 1000, 600);
   var sUrl = 'orc4_orcparamseqfiltropadrao.php?o116_codseq='+iLinha+'&o116_codparamrel='+iRelatorio+'&usuario=true';
   var sConteudo = "<iframe width='100%' style='height:99%' frameborder=0 src='"+sUrl+"'>";
      sConteudo += "</iframe>";

   oWindowFiltroUsuario.setContent(sConteudo);
   oWindowFiltroUsuario.allowDrag(false);
   var oMessageBoardUsuario = new messageBoard("msg1",
                                       "Configurações para a linha "+
                                       $('descr'+iLinha+"_"+iRelatorio).innerHTML+".",
                                       'Informe as contas para a linha e o vínculo com o orçamento.',
                                       $("windowwindowFiltroUsuario_content")
                                      );
   oMessageBoardUsuario.show();
   var iCenter = (800)/3
   oWindowFiltroUsuario.setShutDownFunction(function () {

       oWindowFiltroUsuario.destroy();
       location.href = location.href;
   });
   oWindowFiltroUsuario.allowCloseWithEsc(false);
   oWindowFiltroUsuario.show(0, iCenter);
 }

  function js_mostrapadrao(iLinha, iRelatorio) {

   if ($('windowFiltrosPadrao')) {
     owindowFiltrosPadrao.destroy();
   }

   oWindowFiltroPadrao  = new windowAux("windowFiltrosPadrao", "Tela de Configuração Padrão", 1000, 600);
   var sUrl = 'orc4_orcparamseqfiltropadrao.php?o116_codseq='+iLinha+'&o116_codparamrel='+iRelatorio+'&readonly=true';
   var sConteudo = "<iframe width='100%' style='height:99%' frameborder=0 src='"+sUrl+"'>";
      sConteudo += "</iframe>";

  oWindowFiltroPadrao.setContent(sConteudo);
  oWindowFiltroPadrao.allowDrag(false);
  var oMessageBoard2 = new messageBoard("msg2",
                                       "Configurações padrões para linha "+
                                       $('descr'+iLinha+"_"+iRelatorio).innerHTML+".",
                                       '',
                                       $("windowwindowFiltrosPadrao_content")
                                      );
  oMessageBoard2.show();
  var iCenter = (800)/3
  $('windowwindowFiltrosPadrao_btnclose').onclick=function() {
     oWindowFiltroPadrao.destroy();
  };

  oWindowFiltroPadrao.allowCloseWithEsc(false);
  oWindowFiltroPadrao.show(0, iCenter);
 }

 var oCboPeriodo = (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_relatorio.document.getElementById('o116_periodo');
 if (oCboPeriodo) {
   oCboPeriodo.onchange = js_recarregaTela;
 }

 function js_recarregaTela() {

   var oCboPeriodo = (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_relatorio.document.getElementById('o116_periodo');
   var sUrl = 'con4_parametrosrelatorioslegais001.php?c83_codrel=<?=$iCodigoRelatorio?>&iCodigoPeriodo='+oCboPeriodo.value;
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_parametro.location.href = sUrl;
 }
 if (iPeriodo != oCboPeriodo.value ) {
   js_recarregaTela();
 }

/**
 * Cria windowAux com formulario
 */
function js_editarVinculo(iLinha, iRelatorio) {

  var iWidth     = document.width-10;
  var iHeight    = document.body.scrollHeight-100;
  var sConteudo  = " <form method='post' id='form1'>";
			sConteudo += "   <center>";
			sConteudo += "     <table>";
			sConteudo += "       <tr>";
			sConteudo += "         <td>";
			sConteudo += "           <fieldset>";
			sConteudo += "             <legend><b>Vinculos SIGAP</b>";
			sConteudo += "             <table>";
			sConteudo += "               <tr>";
			sConteudo += "                 <td nowrap title='Código Sequencial Campo:o141_sequencial'>";
			sConteudo += "                   <strong>Código Sequencial:</strong>";
			sConteudo += "                 </td>";
			sConteudo += "                 <td>";
			sConteudo += "                   <input title='Código Sequencial Campo:o141_sequencial'";
			sConteudo += "                          name='o141_sequencial' type='text'";
			sConteudo += "                          id='o141_sequencial' value='' size='10' maxlength='10' readonly";
			sConteudo += "                          style='background-color:#DEB887;' autocomplete='off'>";
			sConteudo += "                 </td>";
			sConteudo += "               </tr>";
			sConteudo += "               <tr>";
			sConteudo += "                 <td nowrap title='Conta SIGAP Campo:o141_contasigap'>";
			sConteudo += "                   <strong>Conta SIGAP:</strong>";
			sConteudo += "                 </td>";
			sConteudo += "                 <td>";
			sConteudo += "                   <input title='Conta SIGAP Campo:o141_contasigap'";
			sConteudo += "                          name='o141_contasigap' type='text'";
			sConteudo += "                          id='o141_contasigap' value='' size='100' maxlength='100'";
			sConteudo += "                          autocomplete='off'>";
			sConteudo += "                 </td>";
			sConteudo += "               </tr>";
			sConteudo += "               <tr>";
			sConteudo += "                 <td nowrap title='Descrição Campo:o141_descricao '>";
			sConteudo += "                   <strong>Descrição:</strong>";
			sConteudo += "                 </td>";
			sConteudo += "                 <td>";
			sConteudo += "                   <input title='Descrição Campo:o141_descricao' name='o141_descricao'";
			sConteudo += "                          type='text' id='o141_descricao' value='' size='100' maxlength='100'";
			sConteudo += "                          autocomplete='off'>";
			sConteudo += "                   </td>";
			sConteudo += "               </tr>";
			sConteudo += "               <tr>";
			sConteudo += "                 <td nowrap title='Estrutural Campo:o141_estrutural '>";
			sConteudo += "                   <strong>Estrutural:</strong>";
			sConteudo += "                 </td>";
			sConteudo += "                 <td>";
			sConteudo += "                   <input title='Estrutural Campo:o141_estrutural' name='o141_estrutural'";
			sConteudo += "                          type='text' id='o141_estrutural' value='' size='20' maxlength='20'";
			sConteudo += "                          autocomplete='off'>";
			sConteudo += "                 </td>";
			sConteudo += "               </tr>";
			sConteudo += "             </table>";
			sConteudo += "           </fieldset>";
			sConteudo += "         </td>";
			sConteudo += "       </tr>";
			sConteudo += "       <tr>";
			sConteudo += "         <td align='center'>";
			sConteudo += "           <input type='button' id='btnSalvarVinculo' value='Salvar'";
			sConteudo += "                  onclick='return js_salvarVinculo("+iLinha+","+iRelatorio+");'>";
			sConteudo += "           <input type='button' id='btnExcluirVinculo' value='Excluir'";
			sConteudo += "                  onclick='return js_excluirVinculo("+iLinha+","+iRelatorio+");'";
			sConteudo += "                  style='display: ;'>";
			sConteudo += "         </td>";
			sConteudo += "       </tr>";
			sConteudo += "     </table>";
			sConteudo += "   </center>";
			sConteudo += " </form>";

  if ($('windowVinculosSigap')) {
    oWindowVinculosSigap.destroy();
  }

  oWindowVinculosSigap          = new windowAux("windowVinculosSigap", "Vinculos SIGAP", iWidth, iHeight);
  oWindowVinculosSigap.setContent(sConteudo);
  oWindowVinculosSigap.allowDrag(false);

  var oMessageBoardVinculoSigap = new messageBoard( "msg3",
                                                    "Manutenção do Vinculo SIGAP "+
                                                    $('descr'+iLinha+"_"+iRelatorio).innerHTML,
                                                    'Informe o vínculo com o SIGAP.',
                                                    $("windowwindowVinculosSigap_content")
                                                  );
  oMessageBoardVinculoSigap.show();

  $('windowwindowVinculosSigap_btnclose').onclick=function() {

     oWindowVinculosSigap.destroy();
     location.href = location.href;
  };

  oWindowVinculosSigap.allowCloseWithEsc(false);
  oWindowVinculosSigap.show(3, 2);

  js_getDadosVinculo(iLinha, iRelatorio);
}

/**
 * Busca dados vunculo sigap
 */
function js_getDadosVinculo(iLinha, iRelatorio) {

    $('o141_sequencial').value = '';
    $('o141_contasigap').value = '';
    $('o141_descricao').value  = $('descr'+iLinha+"_"+iRelatorio).innerHTML.trim();
    $('o141_estrutural').value = '';

    js_divCarregando('Aguarde, pesquisando vinculo SIGAP...', "msgBoxVinculoSigap");

    if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

      $('btnSalvarVinculo').disabled  = true;
      $('btnExcluirVinculo').disabled = true;
    }

    var oParam       = new Object();
    oParam.exec      = 'getVinculoSigap';
    oParam.linha     = iLinha;
    oParam.relatorio = iRelatorio;

    var oAjax   = new Ajax.Request ('con4_configuracaorelatorioRPC.php',
                                    {
                                       method: 'post',
                                       parameters:'json='+Object.toJSON(oParam),
                                       onComplete: function (oAjax) {

                                         js_removeObj("msgBoxVinculoSigap");

                                         if ($('btnSalvarVinculo')) {
                                           $('btnSalvarVinculo').disabled  = false;
                                         }

                                        /*
                                         * Trata o retorno da function js_getDadosVinculo()
                                         */
                                         var oRetorno = eval("("+oAjax.responseText+")");
                                         if (oRetorno.status == 1) {

                                           if (oRetorno.filter != false) {

		                                         if ($('btnExcluirVinculo')) {
		                                           $('btnExcluirVinculo').disabled = false;
		                                         }

                                             $('o141_sequencial').value = oRetorno.filter.sequencial;
                                             $('o141_contasigap').value = oRetorno.filter.contasigap;
                                             $('o141_descricao').value  = oRetorno.filter.descricao;
                                             $('o141_estrutural').value = oRetorno.filter.estrutural;
                                           }
                                           return true;
                                         } else {

                                           alert(oRetorno.message.urlDecode());
                                           return false;
                                         }
                                       }
                                    });
}

/**
 * Salvar vinculo sigap
 */
function js_salvarVinculo(iLinha, iRelatorio) {

  if ($('o141_contasigap').value == '') {

     alert('Informe a conta SIGAP!');
     return false;
  }

  if ($('o141_descricao').value == '') {

     alert('Informe uma descrição!');
     return false;
  }

  if ($('o141_estrutural').value == '') {

     alert('Informe o estrutural!');
     return false;
  }

  js_divCarregando('Aguarde, salvando vinculo SIGAP...', "msgBoxVinculoSigap");

  if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

    $('btnSalvarVinculo').disabled  = true;
    $('btnExcluirVinculo').disabled = true;
  }

  var oParam                = new Object();
  oParam.exec               = 'salvarVinculoSigap';
  oParam.linha              = iLinha;
  oParam.relatorio          = iRelatorio;

  oParam.filters            = new Object();
  oParam.filters.contasigap = encodeURIComponent(tagString($('o141_contasigap').value));
  oParam.filters.descricao  = encodeURIComponent(tagString($('o141_descricao').value));
  oParam.filters.estrutural = encodeURIComponent(tagString($('o141_estrutural').value));

  var oAjax   = new Ajax.Request ('con4_configuracaorelatorioRPC.php',
                                  {
                                     method: 'post',
                                     parameters:'json='+Object.toJSON(oParam),
                                     onComplete: function (oAjax) {

                                       js_removeObj("msgBoxVinculoSigap");

                                       if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

                                         $('btnSalvarVinculo').disabled  = false;
                                         $('btnExcluirVinculo').disabled = false;
                                       }

                                      /*
                                       * Trata o retorno da function js_salvarVinculo()
                                       */
                                       var oRetorno = eval("("+oAjax.responseText+")");
                                       if (oRetorno.status == 1) {

                                         js_getDadosVinculo(oRetorno.iLinha, oRetorno.iRelatorio);
                                         return true;
                                       } else {

                                         alert(oRetorno.message.urlDecode());
                                         return false;
                                       }
                                     }
                                  });
}

function js_excluirVinculo(iLinha, iRelatorio) {

  if (!confirm('Confirma exclusão do vinculo SIGAP?')) {
    return false;
  }

  js_divCarregando('Aguarde, excluindo vinculo SIGAP...', "msgBoxVinculoSigap");

  if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

    $('btnSalvarVinculo').disabled  = true;
    $('btnExcluirVinculo').disabled = true;
  }

  var oParam                = new Object();
  oParam.exec               = 'excluirVinculoSigap';
  oParam.linha              = iLinha;
  oParam.relatorio          = iRelatorio;

  var oAjax   = new Ajax.Request ('con4_configuracaorelatorioRPC.php',
                                  {
                                     method: 'post',
                                     parameters:'json='+Object.toJSON(oParam),
                                     onComplete: function (oAjax) {

                                       js_removeObj("msgBoxVinculoSigap");

                                       if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

                                         $('btnSalvarVinculo').disabled  = false;
                                         $('btnExcluirVinculo').disabled = false;
                                       }

                                      /*
                                       * Trata o retorno da function js_salvarVinculo()
                                       */
                                       var oRetorno = eval("("+oAjax.responseText+")");
                                       if (oRetorno.status == 1) {

                                         alert('Vinculo excluido com sucesso.');
                                         js_getDadosVinculo(oRetorno.iLinha, oRetorno.iRelatorio);
                                         return true;
                                       } else {

                                         alert(oRetorno.message.urlDecode());
                                         return false;
                                       }
                                     }
                                  });
}
</script>