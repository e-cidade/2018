<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oGet = db_utils::postMemory($_GET);

$oDaoReconhecimentocontabil = db_utils::getDao('reconhecimentocontabil');
$oDaoReconhecimentocontabil->rotulo->label();
$oDaoReconhecimentocontabil->rotulo->tlabel();

$oRotulo = new rotulocampo();
$oRotulo->label("c111_descricao");
$oRotulo->label("z01_nome");
$oRotulo->label("c72_complem");

$aOpcoesEstorno = array("f" => "NAO", "t" => "SIM");
$aDadosReconhecimentoContabilTipo = TipoReconhecimentoContabil::buscaDadosTiposDeReconhecimento('c111_sequencial, c111_descricao');
$aReconhecimentoContabilTipo = array();

foreach ( $aDadosReconhecimentoContabilTipo as $oDadosReconhecimentoContabil ) {
  $aReconhecimentoContabilTipo[ $oDadosReconhecimentoContabil->c111_sequencial ] = $oDadosReconhecimentoContabil->c111_descricao;
}

$db_opcao = 1;

if ( !empty($oGet->lEstorno) && $oGet->lEstorno == 'true' ) {
  $db_opcao = 3;
} 
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="expires" content="0">
  <link href="estilos.css" rel="stylesheet" type="text/css" />
  <?php db_app::load('prototype.js, scripts.js, strings.js'); ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >

  <center> 

    <?php db_input('c112_sequencial', 10, $Ic112_sequencial, true, 'hidden', 3); ?>

    <fieldset class="container" style="width:500px;">

      <legend><?php echo $Lreconhecimentocontabil; ?></legend>

      <table class="form-container">

        <tr>   
          <td>
            <?php db_ancora($Lc112_numcgm, 'js_pesquisaNome(true);', $db_opcao); ?>
          </td>
          <td> 
            <?php
              db_input('c112_numcgm', 8, $Ic112_numcgm, true, 'text', $db_opcao, "onchange='js_pesquisaNome(false);'");
              db_input('z01_nome', 40, 0, true, 'text', 3);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tc112_reconhecimentocontabiltipo; ?>">
            <?php echo $Lc112_reconhecimentocontabiltipo; ?>
          </td>
          <td> 
            <?php db_select('c112_reconhecimentocontabiltipo', $aReconhecimentoContabilTipo, true, 1); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tc112_processoadm; ?>">
            <?php echo $Lc112_processoadm; ?>
          </td>
          <td> 
            <?php db_input('c112_processoadm', 20, $Ic112_processoadm, true, 'text', $db_opcao, "class='field-size-max'"); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tc112_valor; ?>">
             <?php echo $Lc112_valor; ?>
          </td>
          <td> 
            <?php db_input('c112_valor', 8, $Ic112_valor, true, 'text', $db_opcao); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tc72_complem; ?>" colspan="2">
          
            <fieldset>
              <legend><?php echo $Lc72_complem; ?></legend>
              <?php db_textarea('c72_complem', 5, 40, $Ic72_complem, true, 'text', 1); ?>
            </fieldset>

          </td>
        </tr>

      </table>

    </fieldset>

    <br />
    <input type="button" value="Confirmar" onClick="js_processar();" />

  </center>

  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

var sRPC = 'con4_reconhecimentocontabil.RPC.php';

/**
 * Objeto com parametros passados pela url 
 */
var oGet = js_urlToObject();

/**
 * Caminho das mensagens do programa 
 */
const MENSAGENS = 'financeiro.contabilidade.con4_reconhecimentocontabil001.';

/**
 * Estorno 
 * Abre tela de pesquisa dos reconhecimentos para estornar
 */
if ( oGet.lEstorno == 'true' ) {
  js_buscar();
}

/**
 * Busca reconhecimento contabil para estorno
 * - usado somente quando for passado parametro lEstorno = true
 *
 * @access public
 * @return void
 */
function js_buscar() {

  js_OpenJanelaIframe('top.corpo', 'db_iframe_reconhecimentocontabil', 
                      'func_reconhecimentocontabil.php?lEstorno=false&funcao_js=parent.js_retornoBusca|0', 'Pesquisa', 
                      true);
}

/**
 * Retorno da busca dos reconhecimentos para estorno
 *
 * @param integer $iReconhecimentoContabil - sequencial do reconhecimento
 * @access public
 * @return void
 */
function js_retornoBusca(iReconhecimentoContabil) {

  js_buscarDados(iReconhecimentoContabil);
  db_iframe_reconhecimentocontabil.hide();
}

/**
 * Busca dados do reconhecimento contabil
 * - Usado somente quando for passado parametro lEstorno = true
 *
 * @param integer $iReconhecimentoContabil - sequencial do reconhecimento
 * @access public
 * @return void
 */
function js_buscarDados(iReconhecimentoContabil) {

  js_divCarregando('Processando...', 'msgBox');

  var oParametros = new Object();

  oParametros.sExecucao = 'buscarDados';
  oParametros.iReconhecimentoContabil = iReconhecimentoContabil;

  var oAjax = new Ajax.Request(sRPC, {
                               method     : "post",
                               parameters : 'json='+Object.toJSON(oParametros),
                               onComplete : js_retornoBuscarDados
                              });  

}

/**
 * Retorno da busca do reconhecimento
 * - Carrega o valor dos campos na tela
 *
 * @param oAjax $oAjax
 * @access public
 * @return void
 */
function js_retornoBuscarDados(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode(); 

  /**
   * Erro no RPC 
   */
  if ( oRetorno.iStatus > 1 ) {
    return alert(sMensagem);
  }

  var oDados = oRetorno.oDados;

  $('c112_sequencial').value = oDados.reconhecimento_contabil;
  $('c112_numcgm').value = oDados.numcgm;
  $('z01_nome').value = oDados.nome;

  $('c112_reconhecimentocontabiltipo').value = oDados.tipo;
  $('c112_reconhecimentocontabiltipo').disabled = true;
  $('c112_reconhecimentocontabiltipo').style.background = '#DEB887';
  $('c112_reconhecimentocontabiltipo').style.color = '#444';

  $('c112_processoadm').value = oDados.processo;
  $('c112_valor').value = oDados.valor;
}

/**
 * Processar reconhecimento contabil
 * - lancamento e estorno
 * - inclusao: js_incluirReconhecimentoContabil() 
 * - estorno : js_estornarReconhecimentoContabil() 
 *
 * @access public
 * @return boolean
 */
function js_processar() {

  /**
   * Estorno 
   */
  if ( oGet.lEstorno == 'true' ) {

    if ( $('c112_sequencial').value == '' ) {

      alert(_M(MENSAGENS + 'codigo_reconhecimento_nao_informado'));
      js_recarregarTela();
      return false;
    }

    /**
     * Texto complementar nao informado 
     */
    if ( $('c72_complem').value == '' ) {

      alert(_M(MENSAGENS + 'texto_complementar_nao_informado'));
      return false;
    } 

    /**
     * Estorna lancamento
     */
     js_estornarReconhecimentoContabil();
     return true;
  }

  /**
   * CGM nao inforamdo 
   */
  if ( $('c112_numcgm').value == '' ) {
    
    alert(_M(MENSAGENS + 'cgm_nao_informado'));
    return false;
  } 

  /**
   * Processo nao inforamdo 
   */
  if ( $('c112_valor').value == '' ) {

    alert(_M(MENSAGENS + 'valor_nao_informado'));
    return false;
  } 

  /**
   * Texto complementar nao informado 
   */
  if ( $('c72_complem').value == '' ) {

    alert(_M(MENSAGENS + 'texto_complementar_nao_informado'));
    return false;
  } 

  /**
   * Incluir reconhecimento contabil
   */
  js_incluirReconhecimentoContabil();
}

/**
 * Inclui lancamento de reconhecimento contabil
 *
 * @access public
 * @return void
 */
function js_incluirReconhecimentoContabil() {

  js_divCarregando('Processando...', 'msgBox');

  var oParametros = new Object();

  oParametros.sExecucao          = 'incluir';
  oParametros.iNumcgm            = $('c112_numcgm').value;
  oParametros.iTipo              = $('c112_reconhecimentocontabiltipo').value;
  oParametros.sProcesso          = $('c112_processoadm').value.urlDecode();
  oParametros.nValor             = $('c112_valor').value;
  oParametros.sTextoComplementar = $('c72_complem').value.urlDecode();

  var oAjax = new Ajax.Request(sRPC, {
                               method     : "post",
                               parameters : 'json='+Object.toJSON(oParametros),
                               onComplete : js_retornoIncluirReconhencimentoContabil
                              });  

}

/**
 * Retorno da inclusao do lancamento
 * - Recarrega tela caso RPC nao retornar erro
 *
 * @param oAjax $oAjax
 * @access public
 * @return void
 */
function js_retornoIncluirReconhencimentoContabil(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode(); 

  /**
   * Erro no RPC 
   * Nao recarrega tela
   */
  if ( oRetorno.iStatus > 1 ) {    
    return alert(sMensagem);
  }

  alert(sMensagem);   
  js_recarregarTela();
}

/**
 * Estorna lancamento
 *
 * @access public
 * @return void
 */
function js_estornarReconhecimentoContabil() {

  js_divCarregando('Processando...', 'msgBox');

  var oParametros = new Object();

  oParametros.sExecucao = 'estornar';
  oParametros.sTextoComplementar      = $('c72_complem').value.urlDecode();
  oParametros.iReconhecimentoContabil = $('c112_sequencial').value;

  var oAjax = new Ajax.Request(sRPC, {
                               method     : "post",
                               parameters : 'json='+Object.toJSON(oParametros),
                               onComplete : js_retornoEstornarReconhencimentoContabil
                              });  

}

/**
 * Retorno do estorno
 * - Recarrega tela caso RPC nao retornar erro
 *
 * @param oAjax $oAjax
 * @access public
 * @return void
 */
function js_retornoEstornarReconhencimentoContabil(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode(); 

  /**
   * Erro no RPC 
   */
  if ( oRetorno.iStatus > 1 ) {
    alert(sMensagem);
  }

  alert(sMensagem); 
  js_recarregarTela();
}

/**
 * Recarrega tela
 * - Redirecina para o mesmo arquivo, mantendo parametro lEstorno
 *
 * @access public
 * @return void
 */
function js_recarregarTela() {
  location.href = 'con4_reconhecimentocontabil001.php?lEstorno=' + oGet.lEstorno;
}

/**
 * Pesquisa nome do CGM
 *
 * @param boolean $lMostrar - exibir janela de pesquisa
 * @access public
 * @return boolean
 */
function js_pesquisaNome(lMostrar) {

  if (lMostrar) {

    js_OpenJanelaIframe('top.corpo','db_iframe2','func_nome.php?funcao_js=parent.js_retornoPesquisaNomeAncora|0|1','Pesquisa',true);
    return true;
  }

  var iNumCgm = $('c112_numcgm').value;
  js_OpenJanelaIframe('top.corpo','db_iframe2','func_nome.php?pesquisa_chave=' + iNumCgm + '&funcao_js=parent.js_retornoPesquisaNomeInput','Pesquisa',false);

  return true;
}

/**
 * Retorno da pesquisa de cgm pelo ancora 
 *
 * @param iNumcgm $iNumcgm
 * @param sNome $sNome
 * @access public
 * @return void
 */
function js_retornoPesquisaNomeAncora(iNumcgm, sNome) {

  $('c112_numcgm').value    = iNumcgm;
  $('z01_nome').value = sNome;
  db_iframe2.hide();
}

/**
 * Retorno da pesquisa do nome do cmg pela pesquisa do input, onchange
 *
 * @param lErro $lErro
 * @param sNome $sNome
 * @access public
 * @return void
 */
function js_retornoPesquisaNomeInput(lErro, sNome) {

  $('z01_nome').value = sNome; 

  if (lErro) { 

    $('c112_numcgm').focus(); 
    $('c112_numcgm').value = ''; 
  }
}
</script>