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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("dbforms/db_funcoes.php"));

$db_opcao = 1;
$clcargorhrubricas = new cl_cargorhrubricas();
$clfuncaorhrubricas = new cl_funcaorhrubricas();
$clrotulo = new rotulocampo;

$clrotulo->label('rh04_descr');
$clrotulo->label('rh37_descr');

$clcargorhrubricas->rotulo->label();

/*
 
    cargorhrubricas
      rh176_sequencial
      rh176_cargo
      rh176_rubrica
      rh176_instit
      rh176_quantidade
      rh176_valor

    funcaorhrubricas
      rh177_sequencial
      rh177_funcao
      rh177_rubrica
      rh177_instit
      rh177_quantidade
      rh177_valor

*/
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>    
  </head>
  <body class="body-default">

    <div class="container">
      
      <fieldset>

        <legend>Rubricas por Função:</legend>

        <table border="0">

          <tr>
              <td nowrap title="<?php echo $Trh176_cargo; ?>">
                <label for="rh176_cargo">
                  <a href id="labelFuncao" class="DBAncora">Função:</a>
                </label>
              </td>
              <td>
                <?php
                  db_input('rh176_cargo', 10, $Irh176_cargo, true, 'text', ($db_opcao != 1 ? 3 : 1), 'data="rh04_codigo"');
                  db_input('rh04_descr', 39, 0, true, 'text', 3);
                ?>
              </td>

              <td>
                <input type="button" value="Adicionar" id="btnAdicionarFuncao" disabled />
              </td>

            </tr>

            <tr>
              <td nowrap colspan="3">
                <div id="gridFuncoes"></div>
              </td>
            </tr>

        </table>

      </fieldset>

      <fieldset>

        <legend>Rubricas por Cargo:</legend>

        <table border="0">

          <tr>

              <td nowrap title="<?php echo $Trh176_funcao; ?>">
                <label for="rh176_funcao">
                  <a href id="labelCargo" class="DBAncora">Cargo:</a>
                </label>
              </td>

              <td>
                <?php
                  db_input('rh176_funcao', 10, $Irh176_cargo, true, 'text', ($db_opcao != 1 ? 3 : 1), 'data="rh37_funcao"');
                  db_input('rh37_descr', 39, 0, true, 'text', 3);
                ?>
              </td>

              <td>
                <input type="button" value="Adicionar" id="btnAdicionarCargo" disabled />
              </td>

            </tr>

            <tr>
              <td nowrap colspan="3">
                <div id="gridCargos"></div>
              </td>
            </tr>

        </table>

      </fieldset>

      <center><input type="button" value="Salvar" id="salvar" /></center>

    </div>

    <?php db_menu(); ?>
  </body>
</html>
<script type="text/javascript">
(function(exports) {

  var btnSalvar = document.getElementById('salvar');

  var btnAdicionarFuncao = document.getElementById('btnAdicionarFuncao');
  var inputCodigoFuncao = document.getElementById('rh176_cargo');
  var inputDescricaoFuncao = document.getElementById('rh04_descr');

  var btnAdicionarCargo = document.getElementById('btnAdicionarCargo');
  var inputCodigoCargo = document.getElementById('rh176_funcao');
  var inputDescricaoCargo = document.getElementById('rh37_descr');

  var sBotaoRubricas = '<input type="button" value="Adicionar" onclick="adicionarRubricas(\'$codigo\', \'$type\');" />';
  var sBotaoRemover = '<input type="button" value="Remover" onclick="remover(\'$codigo\', \'$type\');" />';

  // Instituicao da sessao 
  var iInstituicao = '<?php echo db_getsession('DB_instit'); ?>';

  var sRPC = 'pes1_rubricasfuncaocargo.RPC.php';

  var oJanelaAtual = null;

  var oDados = {
    funcao : {},
    cargo : {},
  };

  var oGrid = {
    funcao : criarGrid('Funcoes', document.getElementById('gridFuncoes')),
    cargo : criarGrid('Cargos', document.getElementById('gridCargos')),
  };

  buscar();

  var messages = {
    cargo: {
      erro_codigo : "Cargo não informado.",
      erro_codigo_cadastrado : "Cargo já cadastrado.",
      confirmacao_remover : "Todas as configurações para o cargo serão removidas. Confirma a ação?",
    },
    funcao: {
      erro_codigo : "Função não informada.",
      erro_codigo_cadastrado : "Função já cadastrada.",
      confirmacao_remover : "Todas as configurações para a função serão removidas. Confirma a ação?",
    },
    rubrica : {
      erro_codigo : "Rubrica não informada.",
      erro_codigo_cadastrado : "Rubrica já cadastrada.",
      confirmacao_remover : "Remover rubrica. Confirma a ação?",
    },
  };

  btnAdicionarFuncao.addEventListener('click', function() {
    adicionar('funcao', inputCodigoFuncao, inputDescricaoFuncao, btnAdicionarFuncao);
  });

  btnAdicionarCargo.addEventListener('click', function() {
    adicionar('cargo', inputCodigoCargo, inputDescricaoCargo, btnAdicionarCargo);
  });

  inputCodigoFuncao.addEventListener('change', function() {
    btnAdicionarFuncao.disabled = true;
  });

  inputCodigoCargo.addEventListener('change', function() {
    btnAdicionarCargo.disabled = true;
  });

  btnSalvar.addEventListener('click', salvar);

  function buscar() {

    var fnCallback = function(oRetorno, lErro) {

      if (lErro || oRetorno.erro) {
        return alert(oRetorno.mensagem);
      }

      oDados = oRetorno.oDados;

      if (Object.keys(oDados.cargo) == 0) {
        oDados.cargo = {};
      }
      if (Object.keys(oDados.funcao) == 0) {
        oDados.funcao = {};
      }
      atualizarGrid('funcao');
      atualizarGrid('cargo'); 

      console.log('oDados', oDados);
    }
    var oAjaxRequest = new AjaxRequest(sRPC, {exec: 'getDados'}, fnCallback);
    oAjaxRequest.sanitizeTransport(true);
    oAjaxRequest.execute();
  }

  /**
   * Ancora para funcao
   * @type {DBLookUp}
   */
  var oLookUpFuncao = exports.oLookUpFuncao = new DBLookUp($('labelFuncao'), $('rh176_cargo'), $('rh04_descr'), {
    sArquivo : 'func_rhcargo.php',
    sObjetoLookUp : 'db_iframe_rhcargo',
    sLabel : 'Pesquisar Função',
    fCallBack : function() {
      btnAdicionarFuncao.disabled = false;
    }
  });

  /**
   * Ancora para cargo
   * @type {DBLookUp}
   */
  var oLookUpCargo = exports.oLookUpCargo = new DBLookUp($('labelCargo'), $('rh176_funcao'), $('rh37_descr'), {
    sArquivo : 'func_rhfuncao.php',
    sObjetoLookUp : 'db_iframe_rhfuncao',
    sLabel : 'Pesquisar Cargo',
    fCallBack : function() {
      btnAdicionarCargo.disabled = false;
    }
  });

  exports.adicionarRubricas = function adicionarRubricas(codigo, type) {

    var _oDados = oDados[type][codigo];

    if (!_oDados) {
      return console.error('Erro ao criar janela de rubricas.');
    }

    var oTitulo = {
      funcao : 'Rubricas por Função',
      cargo : 'Rubricas por Cargo'
    }

    var oParametros = {
      id : type,
      windowTitle: oTitulo[type],
      onClose : function() {
        _oDados.rubricas = this._oLancador.getRegistros(); 
      }
    };

    var oJanelaRubricas = criarJanelaRubricas(oParametros, oDados[type][codigo]);
    oJanelaRubricas.show();
  }

  exports.remover = function remover(codigo, type) {

    if (!confirm(messages[type].confirmacao_remover)) {
      return false;
    }

    delete oDados[type][codigo];
    atualizarGrid(type);
  }

  function adicionar(type, inputCodigo, inputDescricao, button) {

    var codigo = inputCodigo.value;
    var descricao = inputDescricao.value; 

    button.disabled = true;
    inputCodigo.value = '';
    inputDescricao.value = '';

    if (empty(codigo)) {
      return alert(messages[type].erro_codigo);
    }

    if (oDados[type] && oDados[type][codigo]) {
      return alert(messages[type].erro_codigo_cadastrado);
    }

    oDados[type][codigo] = {
      codigo : codigo,
      descricao : descricao,
      rubricas : {},
    };
      
    atualizarGrid(type);
  }

  function salvar() {

    // atualiza dados
    if (oJanelaAtual) {
      oJanelaAtual._shutdown();
    }

    console.log('salvar.janela_atual', oJanelaAtual);
    console.log('salvar.dados_funcao', oDados.funcao);
    console.log('salvar.dados_cargo', oDados.cargo);

    var fnCallback = function(oRetorno, lErro) {
      return alert(oRetorno.mensagem);
    };

    console.log('salvar.dados', oDados);

    var oAjaxRequest = new AjaxRequest(sRPC, {exec: 'salvarDados', oDados : oDados}, fnCallback);
    oAjaxRequest.sanitizeTransport(true);
    oAjaxRequest.execute();
  }

  function atualizarGrid(type) {

    oGrid[type].clearAll(true); 

    for (var codigo in oDados[type]) {

      var _oDados = oDados[type][codigo];
      oGrid[type].addRow([
        _oDados.codigo, 
        _oDados.descricao,
        sBotaoRubricas.replace('$codigo', _oDados.codigo).replace('$type', type),
        sBotaoRemover.replace('$codigo', _oDados.codigo).replace('$type', type)
      ]);
    }
    
    oGrid[type].renderRows();
  }

  function criarGrid(sName, oContainer) {

    var oGrid = exports['oGrid' + sName] = new DBGrid('grid' + sName);
    oGrid.nameInstance = "oGrid" + sName;
    oGrid.setCellWidth(['15%', '45%', '20%', '20%']);
    oGrid.setCellAlign(["center", "center", "center", "center"]);
    oGrid.setHeader(["Código", "Descrição", "Rubricas", "Ação"]);
    oGrid.show(oContainer);
    oGrid.clearAll(true); 

    return oGrid;
  }

  function criarJanelaRubricas(options, owner) {

    if (oJanelaAtual) {
      oJanelaAtual._shutdown();
    }

    var oWindowAux = new windowAux("window" + options.id, options.windowTitle, 700, 300);
    oWindowAux.setContent("<div id='gridRubricas"+options.id+"' style='margin-top:10px; width: 100%;'>");

    oJanelaAtual = oWindowAux;

    oWindowAux._shutdown = function() {
      this.destroy();
      if (options.onClose) {
        options.onClose.call(this);
      }
      oJanelaAtual = null;
    }

    oWindowAux.setShutDownFunction(oWindowAux._shutdown.bind(oWindowAux));

    oWindowAux.setIndex(1);
    oWindowAux.show();

    var oLancador = criarLancadorRubricas(options.id, document.getElementById('gridRubricas' + options.id), owner);
    oWindowAux._oLancador = oLancador;

    for (var codigo in owner.rubricas) {

      var rubrica = owner.rubricas[codigo];
      oLancador.adicionarRegistro(rubrica);
    }

    atualizarGridLancador(oLancador);

    oWindowAux.hide();

    return oWindowAux;
  }

  function LancadorRubricas(owner) {
  
    var id = Object.keys(LancadorRubricas.instances).length;

    return LancadorRubricas.instances[id] = {

      id : String(id),

      owner : owner,

      exists : function(codigo) {
        return owner.rubricas[codigo];
      },

      adicionarRegistro : function(rubrica) {
        owner.rubricas[rubrica.codigo] = rubrica;
      },

      getRegistros : function() {
        return owner.rubricas;
      }

    };
  }

  LancadorRubricas.instances = {};
  LancadorRubricas.getInstance = function(id) {
    return LancadorRubricas.instances[id];
  };

  exports.atualizarInputRubrica = function atualizarInputRubrica(input, id) {

    var lancador = LancadorRubricas.getInstance(id);
    var codigo = input.getAttribute('data-codigo');
    var type = input.getAttribute('data-type');
    lancador.owner.rubricas[codigo][type] = input.value; 
  }

  exports.removerRubrica = function removerRubrica(codigo, id) {

    if (!confirm(messages.rubrica.confirmacao_remover)) {
      return false;
    }

    var lancador = LancadorRubricas.getInstance(id);
    delete lancador.owner.rubricas[codigo];
    atualizarGridLancador(lancador);
  }

  function criarInputRubrica(type, codigo, lancador) {

    var mensagemValidacao = {
      valor : 'O campo Valor',
      quantidade : 'O campo Quantidade'
    }

    var input = document.createElement('input');
    input.setAttribute('data-type', type);
    input.setAttribute('data-codigo', codigo);
    input.setAttribute('value', '0');
    input.setAttribute('size', '15');
    input.setAttribute('maxlength', '15');
    input.setAttribute('oninput', "js_ValidaCampos(this,4,'"+ mensagemValidacao[type] +"','f','f',event);");
    input.setAttribute('onkeydown', 'return js_controla_tecla_enter(this,event);');
    input.setAttribute('autocomplete', 'off');
    input.setAttribute('style', 'width:100%;');
    input.setAttribute('onchange', 'atualizarInputRubrica(this, "'+lancador.id+'");');

    return input;
  }

  function atualizarGridLancador(oLancador) {

    var oGrid = oLancador.oGrid;
    oGrid.clearAll(true);

    var oRubricas = oLancador.getRegistros();

    for (var codigo in oRubricas) {

      var oRubrica = oRubricas[codigo];
      var inputQuantidade = criarInputRubrica('quantidade', codigo, oLancador);
      var inputValor = criarInputRubrica('valor', codigo, oLancador);

      inputQuantidade.setAttribute('value', oRubrica.quantidade);
      inputValor.setAttribute('value', oRubrica.valor);

      var inputRemover = document.createElement('input');
      inputRemover.setAttribute('value', 'Remover');
      inputRemover.setAttribute('type', 'button');
      inputRemover.setAttribute('onclick', 'removerRubrica("'+ codigo +'", "'+ oLancador.id +'");');

      var row = [
        oRubrica.codigo,
        oRubrica.descricao,
        inputQuantidade.outerHTML,
        inputValor.outerHTML,
        inputRemover.outerHTML
      ];

      oGrid.addRow(row);
    }

    oGrid.renderRows(); 
  }
  
  function criarLancadorRubricas(id, container, owner) {

    var oLancador = new LancadorRubricas(owner);
    var sContainer = '';
    sContainer += '<fieldset>';
    sContainer += '  <legend>Rubricas:</legend>';
    sContainer += '  <table border="0" style="width:100%">';
    sContainer += '    <tr>';
    sContainer += '      <td nowrap id="grid-'+id+'-rubricas-ancora"></td>';
    sContainer += '      <td nowrap id="grid-'+id+'-rubricas-inputs"></td>';
    sContainer += '      <td nowrap id="grid-'+id+'-rubricas-button"></td>';
    sContainer += '    </tr>';
    sContainer += '    <tr>';
    sContainer += '      <td colspan="3" nowrap id="grid-'+id+'-rubricas-grid"></td>';
    sContainer += '    </tr>';
    sContainer += '  </table>';
    sContainer += '</fieldset>';

    container.innerHTML = sContainer;

    var oLabel = document.createElement("label");
    var oAncoraPesquisa = document.createElement("a"); 
    var oInputCodigo = document.createElement("input");
    var oInputDescricao = document.createElement("input");
    var oInputAdicionar = document.createElement("input"); 

    var oContainerAncora = document.getElementById('grid-'+id+'-rubricas-ancora');
    var oContainerInputs = document.getElementById('grid-'+id+'-rubricas-inputs');
    var oContainerButton = document.getElementById('grid-'+id+'-rubricas-button');
    var oContainerGrid = document.getElementById('grid-'+id+'-rubricas-grid');

    oInputCodigo.setAttribute("lang", "rh27_rubric");
    oInputCodigo.setAttribute("name", "rh27_rubric");

    oInputCodigo.addEventListener('change', function() {
      oInputAdicionar.disabled = true;
    });

    oInputDescricao.setAttribute("lang", "rh27_descr");
    oInputDescricao.setAttribute("name", "rh27_descr");
    oInputDescricao.setAttribute("style", "width:440px;");

    oInputAdicionar.setAttribute('type', 'button');
    oInputAdicionar.setAttribute('value', 'Adicionar');
    oInputAdicionar.disabled = true;

    oAncoraPesquisa.innerHTML = "Rubrica:";

    oLabel.appendChild(oAncoraPesquisa);
    oContainerAncora.appendChild(oLabel);

    oContainerInputs.appendChild(oInputCodigo);
    oContainerInputs.appendChild(document.createTextNode(' '));
    oContainerInputs.appendChild(oInputDescricao);

    oContainerButton.appendChild(oInputAdicionar);

    var oLookUp = new DBLookUp(oAncoraPesquisa, oInputCodigo, oInputDescricao, {
      sArquivo      : 'func_rhrubricas.php',
      sQueryString  : '&instit=' + iInstituicao,
      sObjetoLookUp : 'db_iframe_rhfuncao',
      fCallBack : function() {
        oInputAdicionar.disabled = false;
      }
    });

    var oGrid = exports['oGridRubrica' + id] = new DBGrid('gridRubrica' + id);
    oGrid.nameInstance = 'oGridRubrica' + id;
    oGrid.setCellWidth(['11%', '40%', '18%', '18%', '13%']);
    oGrid.setCellAlign(["center", "center", "center", "center", "center"]);
    oGrid.setHeader(["Código", "Descrição", "Quantidade", "Valor", "Ação"]);
    oGrid.show(oContainerGrid);
    oGrid.clearAll(true); 

    oLancador.oGrid = oGrid;

    oInputAdicionar.addEventListener('click', function() {

      var codigo = oInputCodigo.value;
      var descricao = oInputDescricao.value;

      oInputCodigo.value = '';
      oInputDescricao.value = '';

      if (empty(codigo)) {
        return alert(messages.rubrica.erro_codigo);
      }

      if (oLancador.exists(codigo)) {
        return alert(messages.rubrica.erro_codigo_cadastrado);
      }

      oLancador.adicionarRegistro({codigo: codigo, descricao: descricao, valor : 0, quantidade: 0});

      atualizarGridLancador(oLancador);
    });

    return oLancador;
  }

})(this);
</script>
