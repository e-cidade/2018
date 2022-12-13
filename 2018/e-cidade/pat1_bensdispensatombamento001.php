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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_bensdispensatombamento_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clbensdispensatombamento = new cl_bensdispensatombamento;
$clbensdispensatombamento->rotulo->label();
$clbensdispensatombamento->rotulo->tlabel();

$clrotulo = new rotulocampo;
$clrotulo->label("pc01_descrmater");
$clrotulo->label("m71_codmatestoque");

$iOpcaoAncora = 1;
$lEstorno     = false;
$sLabelBotao  = "Processar";
$oGet         = db_utils::postMemory($_GET);

/**
 * Estorno true/false
 */
if ( !empty($oGet->lEstorno) && $oGet->lEstorno == 'true' ) {

  $lEstorno    = true;
  $sLabelBotao = "Estornar";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?php db_app::load('prototype.js, scripts.js, strings.js, DBToogle.widget.js, dbmessageBoard.widget.js'); ?>
<?php db_app::load("estilos.css, grid.style.css, classes/DBViewNotasPendentes.classe.js, widgets/windowAux.widget.js, datagrid.widget.js"); ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<center>

  <form name="form1" method="post" action="">

    <?php db_input('iNumeroEmpenho',      10, 0, true, 'hidden', 3); ?>
    <?php db_input('nValorNota',          10, 0, true, 'hidden', 3); ?>
    <?php db_input('iCodigoNota',         10, 0, true, 'hidden', 3); ?>

    <fieldset class='container' style="width:500px;">

      <legend>Dispensa de Tombamento</legend>

      <table border="0" class='form-container'>

        <tr>
          <td nowrap title="<?php echo $Te139_empnotaitem; ?>" width="50">
             <?php db_ancora($Le139_empnotaitem, "js_buscarDadosItem();", 1); ?>
          </td>
          <td>
            <?php
              db_input('e139_empnotaitem', 10, 0, true, 'text', 3);
              db_input('pc01_descrmater', 50, 0, true, 'text', 3, '')
             ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Te139_justificativa; ?>" colspan="2">
            <fieldset style="margin-top:10px;">
              <legend>Justificativa</legend>
              <?php db_textarea('e139_justificativa', 0, 0, $Ie139_justificativa, true, 'text', 1); ?>
            </fieldset>
          </td>
        </tr>

      </table>

    </fieldset>

    <br />

    <input type="button" value="<?php echo $sLabelBotao; ?>" onclick="js_processar();" />

  </form>
</center>
<script type="text/javascript">

var sRPC = 'pat1_bensdispensatombamento.RPC.php';

/**
 * Estorno true | false
 * @var boolean
 */
var lEstorno = <?php echo $lEstorno ? 'true' : 'false'; ?>;

/**
 * Caminho das mensagens do programa
 */
const MENSAGENS = 'patrimonial.patrimonio.pat1_bensdispensatombamento001.';

/**
 * - Cria janelas para selecionar itens da nota para dispensa de tombamento
 * - Abre tela de pesquisa dos itens para estornar dispensa de tombamento
 */
(function() {

  /**
    * Abre tela de pesquisa dos itens para estornar dispensa de tombamento
   */
  if ( lEstorno ) {
    return js_pesquisa();
  }

  /**
   * Cria janelas para selecionar itens da nota para dispensa de tombamento
   */
  return js_criarJanelaNotasPendentes();

})();

/**
 * Callback para preencher dados da nota pendente escolhida
 *
 * @param Object oDadosLinha
 * @access public
 * @return void
 */
function js_preencheDadosNotaPendente(oDadosLinha) {

  $('e139_empnotaitem').value = oDadosLinha.iCodigoEmpNotaItem;
  $('pc01_descrmater').value  = oDadosLinha.sDescricaoItem;
  $('iNumeroEmpenho').value   = oDadosLinha.iNumeroEmpenho;
  $('nValorNota').value       = oDadosLinha.nValorNota;
  $('iCodigoNota').value      = oDadosLinha.iCodigoNota;

  /**
   * Esconde janela do WindowAux
   */
  oDBViewNotasPendentes.getWindowAux().hide();
}

/**
 * Mostra janela com itens da nota pra dispensa de tombamento
 *
 * @access public
 * @return void
 */
function js_buscarDadosItem() {

  /**
   * Estorno
   * - Pesquisa itens para estorno
   */
  if ( lEstorno ) {
    return js_pesquisa();
  }

  /**
   * Exibe window com itens para dispensa de tombamento
   */
  oDBViewNotasPendentes.getWindowAux().show();
}

/**
 * Cria janela para usuario esolher item para dispensa de tombamento
 *
 * @access public
 * @return void
 */
function js_criarJanelaNotasPendentes() {

  oDBViewNotasPendentes = new DBViewNotasPendentes('oDBViewNotasPendentes', <?php echo USE_PCASP ? 'true' : 'false';?>);
  oDBViewNotasPendentes.setTextoRodape("<b> * Dois cliques sob a linha para definir o item para dispensa de tombamento.</b>");
  oDBViewNotasPendentes.setCallBackDoubleClick(js_preencheDadosNotaPendente);
  oDBViewNotasPendentes.show();
}

/**
 * Processar
 * INCLUI ou ESTORNA item como dispensa de tombamento
 *
 * @access public
 * @return boolean
 */
function js_processar() {

  /**
   * Item da nota
   */
  if ( empty($('e139_empnotaitem').value) ) {

    alert(_M(MENSAGENS + 'item_nota_nao_informada'));
    return false;
  }

  /**
   * Justifica tiva de tombamento
   */
  if ( empty($('e139_justificativa').value) ) {

    alert(_M(MENSAGENS + 'justificativa_nao_informada'));
    return false;
  }

  js_divCarregando(_M(MENSAGENS + 'processando'), 'msgBox');

  var oParametros = new Object();

  oParametros.sExecucao = 'processar';

  if ( lEstorno ) {
    oParametros.sExecucao = 'estornar';
  }

  oParametros.iCodigoEmpNotaItem = $F('e139_empnotaitem');
  oParametros.iNumeroEmpenho     = $F('iNumeroEmpenho');
  oParametros.nValorNota         = js_formatar($F('nValorNota'), 'f');
  oParametros.iCodigoNota        = $F('iCodigoNota');
  oParametros.sJustificativa     = encodeURIComponent(tagString($F('e139_justificativa')));

  var oAjax = new Ajax.Request(sRPC, {
                               method     : "post",
                               parameters : 'json='+Object.toJSON(oParametros),
                               onComplete : js_retornoProcessar
                              });

  return true;
}

/**
 * Retorno do processar
 *
 * @param oAjax $oAjax
 * @access public
 * @return void
 */
function js_retornoProcessar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();
  var sEstorno  = "true";

  /**
   * Erro no RPC
   */
  if ( oRetorno.iStatus > 1 ) {
    return alert(sMensagem);
  }

  alert(sMensagem);

  if (!lEstorno) {
    sEstorno = "false";
  }

  document.location.href = 'pat1_bensdispensatombamento001.php?lEstorno='+lEstorno;
}

/**
 * Pesquisar itens com dispensa de tombamento
 * - usado somente pela rotina de estorno
 *
 * @access public
 * @return void
 */
function js_pesquisa() {

  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_bensdispensatombamento',
                      'func_bensdispensatombamento.php?funcao_js=parent.js_preenchePesquisa|e139_empnotaitem|pc01_descrmater|e69_numemp|e72_valor|e72_codnota',
                      'Pesquisa',
                      true);
}

/**
 * Redireciona tela para passar como parametro o codigo da dispensa de tombamento
 *
 * @param integer iCodigoEmpNotaItem
 * @param string  sDescricaoItem
 * @param integer iNumeroEmpenho
 * @param Numeric nValorItemNota
 * @access public
 * @return void
 */
function js_preenchePesquisa(iCodigoEmpNotaItem, sDescricaoItem, iNumeroEmpenho, nValorItemNota, iCodigoNota) {

  $('iNumeroEmpenho').value      = iNumeroEmpenho;
  $('e139_empnotaitem').value    = iCodigoEmpNotaItem;
  $('pc01_descrmater').value     = sDescricaoItem;
  $('nValorNota').value          = nValorItemNota;
  $('iCodigoNota').value         = iCodigoNota;
  db_iframe_bensdispensatombamento.hide();
}

js_tabulacaoforms("form1","e139_empnotaitem",true,1,"e139_empnotaitem",true);
</script>

<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>