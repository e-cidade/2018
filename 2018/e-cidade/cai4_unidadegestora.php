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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
  <br>
  <div id="abas"></div>
  <div id="aba_unidade_gestora" class="container">
    <form id="frmUnidadeGestora">
      <fieldset>
        <legend>Unidade Gestora</legend>
        <table>
          <tr>
            <td>
              <label for="codigo"><b>Código:</b></label>
            </td>
            <td>
              <input type="text" id="codigo" readonly class="field-size2 readonly">
            </td>
          </tr>
          <tr>
            <td>
              <label for="nome"><b>Nome:</b></label>
            </td>
            <td>
              <input type="text" id="nome" class="field-size6" maxlength="100" onkeyup="js_ValidaCampos(this, 0, 'Nome', 0, 't')">
            </td>
          </tr>
          <tr>
            <td>
              <label for="coddepto" id="lblCodDepto"><b>Departamento:</b></label>
            </td>
            <td>
              <input type="text" id="coddepto" class="field-size2" onkeyup="js_ValidaCampos(this, 1, 'Departamento', 1, 1)">          
              <input type="text" data='descrdepto' id="nome_depto" class="field-size6" readonly maxlength="100">
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" value="Salvar"    id="btnSalvarUnidade">
      <input type="button" value="Excluir"   id="btnExcluir">
      <input type="button" value="Pesquisar" id="btnPesquisar">
      <input type="reset"  value="Novo"      id="btnLimparUnidade">
    </form>
  </div>
  <div id="aba_tipo_recolhimento" style="width: 800px" class="container">
    <form id="frmTipoRecolhimento">
      <fieldset>
        <legend>Tipo de Recolhimento</legend>
        <table>
          <tr>
            <td>
              <label for="tipo_recolhimento" id="lblTipoRecolhimento"><b>Código:</b></label>
            </td>
            <td>
              <input type="text" id="tipo_recolhimento" data='k172_sequencial' class="field-size2" onkeyup="js_ValidaCampos(this, 1, 'Tipo de Recolhimento', 0, 1)">
            </td>       
            <td>
              <input type="text" id="nome_recolhimento" data='k172_nome' class="field-size10 readonly" readonly maxlength="100">
            </td>
          </tr>
          <tr>
            <td>
              <label for="codigo_receita" id="lblReceita"><b>Receita:</b></label>
            </td>
            <td>
              <input type="text" id="codigo_receita" data='k02_codigo' class="field-size2" onkeyup="js_ValidaCampos(this, 1, 'Receita', 0, 1)">
            </td>
            <td>
              <input type="text" id="nome_receita" data='k02_descr' class="field-size10 readonly" readonly maxlength="100" > 
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" value="Adicionar" id="btnAdicionarRecolhimento">
      <input type="reset"  value="Novo" id="btnLimpar">
    </form>
    <div style="width: 100%">
       <fieldset class="separator">
          <legend>Recolhimentos Incluídos</legend>
          <div id="ctnGridRecolhimentos" style="width: 100%">           
          </div>          
       </fieldset>
    </div>
  </div>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>
  
  const URL_RPC            = 'cai4_unidadegestora.RPC.php';
  var oAbas                = new DBAbas($('abas'));
  var oAbaUnidade          = oAbas.adicionarAba('Dados da Unidade', $('aba_unidade_gestora'));
  var oAbaTipoRecolhimento = oAbas.adicionarAba('Tipos de Recolhimentos', $('aba_tipo_recolhimento'));
  var oCodigoUnidade       = $('codigo');
  var oNomeUnidade         = $('nome')
  var oDepartamento        = $('coddepto');
  var oNomeDepartamento    = $('nome_depto');
  var oTipoRecolhimento    = $('tipo_recolhimento');
  var oNomeRecolhimento    = $('nome_recolhimento');

  var oCodigoReceita  = $('codigo_receita');
  var oNomeReceita    = $('nome_receita');
  var oLookupDepartamento = new DBLookUp($('lblCodDepto'), oDepartamento, oNomeDepartamento, {
    "sArquivo"      : "func_db_depart.php",
    "sObjetoLookUp" : "db_iframe_nome",
    "sLabel"        : "Pesquisar Departamento"
  });

  var oLookupTipoRecolhimento = new DBLookUp($('lblTipoRecolhimento'), oTipoRecolhimento, oNomeRecolhimento, {
    "sArquivo"      : "func_tiporecolhimento.php",
    "sObjetoLookUp" : "db_iframe_receita",
    "sLabel"        : "Pesquisar Tipo de Recolhimento"
  });
  
  var oLookupTipoRececeita = new DBLookUp($('lblReceita'), oCodigoReceita, oNomeReceita, {
    "sArquivo"      : "func_tabrec.php",
    "sObjetoLookUp" : "db_iframe_receita",
    "sLabel"        : "Pesquisar Receitas"
  });

  var oCollectionRecolhimento = new Collection().setId('codigo_recolhimento');
  var oDataGridRecolhimento   = new DatagridCollection(oCollectionRecolhimento).configure({
    'order'  : false,
    'height' : 200   
    
  });
  oDataGridRecolhimento.addColumn("codigo_recolhimento", {
    'label' : "Código",
    'width' : "10%",
    'align' : "center"
  });
  oDataGridRecolhimento.addColumn("recolhimento", {
    'label' : "Recolhimento",
    'align' : "left",
    'width' : "30%"
  });
  oDataGridRecolhimento.addColumn("descricao_receita", {
    'label' : "Receita",
    'align' : "left",
    'width' : "30%"
  });
  
  oDataGridRecolhimento.addAction("A", null, function(oEvento, oRegistro) {
    
    oCodigoReceita.value    = oRegistro.receita;
    oNomeReceita.value      = oRegistro.descricao_receita;
    oNomeRecolhimento.value = oRegistro.recolhimento;
    oTipoRecolhimento.value = oRegistro.codigo_recolhimento;
  });
  oDataGridRecolhimento.addAction("E", 'D', function(oEvento, oRegistro) {
    
    if (!confirm('Confirma a exclusão do recolhimento?')) {
       return;
    }
    
    var oParametro = {
      
      exec           : 'removerRecolhimento',
      codigo_unidade : $F('codigo'),
      recolhimento   : oRegistro.codigo_recolhimento
    }
  
    new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {
    
      alert(oResponse.sMessage.urlDecode());
      if (lErro) {
        return;
      }
      oCollectionRecolhimento.remove(oRegistro.codigo_recolhimento);
      oDataGridRecolhimento.reload();
    }).setMessage('Aguarde, processando dados...').execute();
  });
  oDataGridRecolhimento.show($('ctnGridRecolhimentos'));
  
  /**
   * Adiciona um tipo de recolhimetno a unidade gestora
   */
  function adicionarRecolhimento() {
    
    var iCodigoRecolhimento = oTipoRecolhimento.value;
    var iCodigoReceita      = oCodigoReceita.value;
    if (empty(iCodigoRecolhimento)) {
      
      alert('Informe o Tipo de Recolhimento.');
      return false;
    }
    if (empty(iCodigoReceita)) {
    
      alert('Informe a receita.');
      return false;
    }    
    var oRecolhimento = {
      
      codigo_recolhimento: iCodigoRecolhimento,
      recolhimento: oNomeRecolhimento.value,
      receita: iCodigoReceita, 
      descricao_receita: oNomeReceita.value  
    }
    var oParametro = {
    
      exec           : 'adicionarRecolhimento',
      codigo_unidade : $F('codigo'),
      recolhimento   : oRecolhimento
    }
  
    new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {
    
      alert(oResponse.sMessage.urlDecode());
      if (lErro) {
        return;
      }
      oCollectionRecolhimento.add(oRecolhimento);
      oDataGridRecolhimento.reload();
      $('frmTipoRecolhimento').reset();
    }).setMessage('Aguarde, processando dados...').execute();    
    
  } 
  

  /**
   * persiste os dados da unidade Gestora.
   * @returns {boolean}
   */
  salvarUnidade = function () {
    
    if (empty(oNomeUnidade.value.trim())) {
      
      alert('Nome da unidade deve ser informado.');
      return false;
    }
    
    if (empty(oDepartamento.value)) {
    
      alert('Departamento deve ser informado.');
      return false;
    }
  
    var oParametro = {
      
      exec   : 'salvar',
      codigo : $F('codigo'),
      nome   : oNomeUnidade.value,
      departamento: oDepartamento.value
    }
  
    new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {
    
      alert(oResponse.sMessage);
      if (lErro) {
        return;
      }  
      oCodigoUnidade.value = oResponse.codigo;
      oAbas.mostraFilho(oAbaTipoRecolhimento);
    }).setMessage('Aguarde, processando dados...').execute();
  } 
  
  
  /**
   * Lookup de pesquisa com os dados
   */
  function pesquisar () {
    
    js_OpenJanelaIframe('CurrentWindow.corpo',
      'db_iframe_unidadegestora',
      'func_unidadegestora.php?funcao_js=parent.preenchePesquisa|k171_sequencial',
      'Pesquisa de Unidades Gestoras',
      true
    );
  }

  /**
   * Preenche o formulário com os dados da unidade
   * @param codigo_unidade
   */
  function preenchePesquisa(codigo_unidade) {
    
    var oParametro = {
    
      exec        : 'pesquisarUnidade',
      codigo      : codigo_unidade      
    }
  
    new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {    
      
      if (lErro) {
        return;
      }
      oCollectionRecolhimento.clear();
      fillFormFromObject($('frmUnidadeGestora'), oResponse.unidade);
      for (oRecolhimento of oResponse.unidade.recolhimentos) {
        oCollectionRecolhimento.add(oRecolhimento);
      }
      oDataGridRecolhimento.reload();
      db_iframe_unidadegestora.hide();
    }).setMessage('Aguarde, processando dados...').execute();
  }
  
  function removerUnidade() {
    
    if (empty(oCodigoUnidade.value)) {
      
      alert('Uma Unidade Gestora deve ser selecionada!');
      return;
    }
    if (!confirm('Confirma a exclusão da Unidade Gestora?')) {
      return;
    }
    oParametro = {
      
      exec: 'removerUnidade',
      codigo_unidade: oCodigoUnidade.value
    }
    new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {
    
      alert(oResponse.sMessage);
      if (lErro) {
        return;
      }
      $('frmUnidadeGestora').reset();
      $('frmTipoRecolhimento').reset();
      oCollectionRecolhimento.clear();   
      oDataGridRecolhimento.reload();
      pesquisar();
    }).setMessage('Aguarde, processando dados...').execute();
  }
  /**
   * Adicioando eventos aos botoes
   */
  $('btnSalvarUnidade').observe('click', function () {
    salvarUnidade();
  });

  $('btnPesquisar').observe("click", function() {
    pesquisar();
  });

  $('btnAdicionarRecolhimento').observe('click', function(){
    adicionarRecolhimento();
  });
  
  $('btnExcluir').observe('click', function(){
    removerUnidade();
  });
  
  
</script>
