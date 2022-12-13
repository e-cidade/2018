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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("arrays.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
  db_app::load("DBMensagem.js");
  db_app::load("DBTreeView.widget.js");
  db_app::load("DBHint.widget.js");
?>
</head>
<body style='margin-top: 25px; background-color: #CCCCCC;' >
  <div style='height:80%' id='panel'>
  <form method='post' action='' class='container'>
    <table>
      <tr>
        <td>
          <fieldset>
            <legend class='bold'>Plano Conta Orçamentário</legend>
            <div id='ctnArvoreOrcamentario' style="height: 95%; width: 45%;"></div>
          </fieldset>
        </td>
        <td>
          <fieldset>
            <legend class='bold'>Plano Conta PCASP</legend>
            <div id='ctnArvorePcasp' style="height: 95%; width: 45%;"></div>
          </fieldset>
        </td>
      </tr>
    </table>
    <input id='btnProcessar' name='btnProcessar' type='button' value='Processar' />
  </form>
  </div>
</body>
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script type="text/javascript">

var sRpc                = 'con4_processarvinculopcasp.RPC.php';
var sCaminhoMensagem    = 'configuracao.configuracao.con4_vinculomanualpcasp001.';
var aArvoreOrcamentaria = new Array();

$('btnProcessar').observe("click", function() {
  processar();
});
/**
 * TreeView das contas orçamentárias
 */
var oTreeViewContasOrcamentaria = new DBTreeView( 'treeViewContasOrcamentaria' );
    oTreeViewContasOrcamentaria.allowFind( true );
    oTreeViewContasOrcamentaria.setFindOptions( 'matchedonly' );
    oTreeViewContasOrcamentaria.show( $('ctnArvoreOrcamentario') );

var oNoPrincipalOrcamento = oTreeViewContasOrcamentaria.addNode('0', "Plano Orcamentário");

$('ctnArvoreOrcamentario').style.height = document.body.getHeight() - 60
$('ctnArvoreOrcamentario').style.width  = document.body.getWidth() / 2.2  ;

/**
 * TreeView das contas do PCASP
 */
var oTreeViewContasPcasp = new DBTreeView( 'treeViewContasPcasp' );
    oTreeViewContasPcasp.allowFind( true );
    oTreeViewContasPcasp.setFindOptions( 'matchedonly' );
    oTreeViewContasPcasp.show( $('ctnArvorePcasp') );


var oNoPrincipalPcasp = oTreeViewContasPcasp.addNode('0', "PCASP");

$('ctnArvorePcasp').style.height = document.body.getHeight() - 60;
$('ctnArvorePcasp').style.width  = document.body.getWidth() / 2.1;
    
/**
 * Pesquisa as contas orçamentárias a serem vinculadas
 */
function pesquisaContasOrcamentarias() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'getContasOrcamentoSemVinculo';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaContasOrcamentarias;

  js_divCarregando( _M( sCaminhoMensagem+'buscando_contas_orcamentarias' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorno das contas orçamentárias para montagem da treeView
 */
function retornoPesquisaContasOrcamentarias( oResponse ) {

  js_removeObj( "msgBox" );
  
  /**
   * Classes existentes para preenchimento do nó principal da treeView
   */
  var aClasses    = new Array();
      aClasses[1] = "Ativo";
      aClasses[2] = "Passivo";
      aClasses[3] = "Despesa";
      aClasses[4] = "Receita";
      aClasses[5] = "Resultado Diminutivo do Exercício";
      aClasses[6] = "Resultado Aumentativo do Exercício";
      aClasses[9] = "Deduções da Receita";

  var oRetorno = eval( '('+oResponse.responseText+')' );

  if ( oRetorno.iStatus != 1 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return false;
  }
  
  /**
   * 1º - Percore o primeiro array, separado por classe, montando o primeiro nó da treeView
   * 2º - Percorre os estruturais vinculados, preenchendo como nó filho da classe
   */
  for ( iClasse in oRetorno.aContasOrcamentarias ) {

    aArvoreOrcamentaria[iClasse] = oTreeViewContasOrcamentaria.addNode(
                                         iClasse,
                                         aClasses[iClasse],
                                         '0'
                                       );
    
    for ( sEstrutural in oRetorno.aContasOrcamentarias[iClasse] ) {

      var oCheckBox          = new Object();
          oCheckBox.checked  = false;
          oCheckBox.disabled = false;

      var oDadosContaOrcamentaria              = new Object();
          oDadosContaOrcamentaria.sEstrutural  = sEstrutural;
          
      var sDadosEstrutural = sEstrutural + ' - ' + oRetorno.aContasOrcamentarias[iClasse][sEstrutural].c60_descr.urlDecode();
      oTreeViewContasOrcamentaria.addNode(
                                           sEstrutural,
                                           sDadosEstrutural,
                                           iClasse,
                                           '',
                                           '',
                                           oCheckBox,
                                           null,
                                           oDadosContaOrcamentaria
                                         );
    }
  }
  oNoPrincipalOrcamento.expand();
}

/**
 * Pesquisa as contas do PCASP
 */
function pesquisaContasPcasp() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'getContasPcasp';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON( oParametro );
      oDadosRequisicao.onComplete = retornoPesquisaContasPcasp;

  js_divCarregando( _M( sCaminhoMensagem+'buscando_contas_pcasp' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorno das contas do PCASP para preenchimento da treeView
 */
function retornoPesquisaContasPcasp( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );
  
  oRetorno.aContas.each(function( oConta, iSeq ) {

    var oCheckBox        = null;
    var sDadosEstrutural = "<b>"+oConta.estrutural +" - "+ oConta.descricao.urlDecode()+"</b>";

    if ( !empty(oConta.reduzido) ) {
      
      sDadosEstrutural   = oConta.estrutural +" - "+ oConta.descricao.urlDecode();
      oCheckBox          = new Object();
      oCheckBox.checked  = false;
      oCheckBox.disabled = false;

      /**
       * No onClick em um checkbox, percorre os demais para desmarcar-los
       */
      oCheckBox.onClick  = function (oNode, event) {

        if (oNode.checkbox.checked) {

          oNode.uncheckAll(event);
          for (oNode in oTreeViewContasPcasp.aNodes ) {
            
            if (typeof(oTreeViewContasPcasp.aNodes[oNode]) == 'function') {
              continue;
            }
            oTreeViewContasPcasp.aNodes[oNode].uncheckAll(event);
          };
          oTreeViewContasPcasp.setChecked(event, event.target);
        }
      };
    }

    var oDadosContaPcasp              = new Object();
        oDadosContaPcasp.iCodigoConta = oConta.codigo_conta;
        oDadosContaPcasp.sEstrutural  = oConta.estrutural;
        oDadosContaPcasp.iAno         = oConta.ano;
        
    var oNode = oTreeViewContasPcasp.addNode(
                                             oConta.estrutural,
                                             sDadosEstrutural,
                                             oConta.conta_pai,
                                             '',
                                             '',
                                             oCheckBox,
                                             null,
                                             oDadosContaPcasp
                                           );

    if (!empty(oConta.reduzido) && oConta.aVinculos.length > 0) {
      
      oDBHint = eval("oDBHint_"+iSeq+"_1 = new DBHint('oDBHint_"+iSeq+"_1')");
      oDBHint.setText(oConta.aVinculos.implode("<br>"));
      oDBHint.setWidth(500);
      oDBHint.setUseMouse(true);
      oDBHint.setShowEvents(["onmouseover"]);
      oDBHint.setHideEvents(["onmouseout"]);
      oDBHint.setPosition('B', 'L');
      oDBHint.make(oNode.element);
    }
  });
  oNoPrincipalPcasp.expand();
}

/**
 * Realiza o vínculo das contas orçamentárias selecionadas, com a conta do PCASP
 */
function processar() {

  var lSelecionouContaOrcamentaria = false;
  var lSelecionouContaPcasp        = false;
  var aContasOrcamentarias         = new Array();
  var iContaPcasp                  = null;
  var iAno                         = null;

  /**
   * Percorre a treeView das contas orçamentárias, verificando quais nós foram selecionados, incrementando os valores
   * no array a ser enviado ao RPC
   */
  oContasOrcamento = oTreeViewContasOrcamentaria.getNodesChecked();
  oContasOrcamento.each(function (oConta, iClasse ) {

    lSelecionouContaOrcamentaria = true;
    aContasOrcamentarias.push( oConta.sEstrutural);
  });
  
  /**
   * Percorre a treeView das contas do PCASP, verificado se algum nó foi selecionado, preenchendo os campos a serem
   * enviados para o RPC
   */
  for (oNode in oTreeViewContasPcasp.aNodes ) {
    
    if ( typeof(oTreeViewContasPcasp.aNodes[oNode]) == 'function' ) {
      continue;
    }

    if ( !empty(oTreeViewContasPcasp.aNodes[oNode].checkbox) && oTreeViewContasPcasp.aNodes[oNode].checkbox.checked ) {
      
      lSelecionouContaPcasp = true;
      iContaPcasp           = parseInt( oTreeViewContasPcasp.aNodes[oNode].iCodigoConta );
      iAno                  = parseInt( oTreeViewContasPcasp.aNodes[oNode].iAno );
    }
  };
  
  if ( !lSelecionouContaOrcamentaria ) {

    alert( _M( sCaminhoMensagem+'conta_orcamentaria_nao_selecionada' ) );
    return false;
  }

  if ( !lSelecionouContaPcasp ) {

    alert( _M( sCaminhoMensagem+'conta_pcasp_nao_selecionada' ) );
    return false;
  }

  if ( !confirm( _M( sCaminhoMensagem+'confirma_vinculo' ) ) ) {
    return false;
  }
  
  var oParametro                      = new Object();
      oParametro.sExecucao            = 'vincular';
      oParametro.iContaPcasp          = iContaPcasp;
      oParametro.iAno                 = iAno;
      oParametro.aContasOrcamentarias = aContasOrcamentarias;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = retornoProcessar;

  js_divCarregando( _M( sCaminhoMensagem+'vinculando_contas' ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

function retornoProcessar( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = eval( '('+oResponse.responseText+')' );

  alert( oRetorno.sMensagem.urlDecode() );

  if ( oRetorno.iStatus == 1 ) {

    aArvoreOrcamentaria.each(function( oNodeorcamentario, iSeq ) {
      oNodeorcamentario.remove();
    });

    pesquisaContasOrcamentarias();
  }
}

pesquisaContasOrcamentarias();
pesquisaContasPcasp();
</script>