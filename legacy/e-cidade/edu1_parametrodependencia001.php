<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_libdicionario.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulo('parametroprogressaoparcial');
$oRotulo->label('ed112_habilitado');
$oRotulo->label('ed112_formacontrole');
$oRotulo->label('ed112_quantidadedisciplinas');
$oRotulo->label('ed112_controlefrequencia');
$oRotulo->label('ed112_disciplinaeliminadependencia');
$oRotulo->label('ed112_justificativa');

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
                  dbmessageBoard.widget.js,dbcomboBox.widget.js,datagrid.widget.js, prototype.maskedinput.js,
                  DBTreeView.widget.js,arrays.js, webseller.js");
    db_app::load("estilos.css, grid.style.css");
  ?>
  <style type="text/css">
    .tabelas {
      width: 100%;
    }
    .tabelas .primeiraColuna {
/* ATENCAO: PLUGIN ParametroProgressaoParcial - Não apagar o conteudo primeiraColuna - INICIO - NAO REMOVER */
      width: 250px;
/* ATENCAO: PLUGIN ParametroProgressaoParcial - Não apagar o conteudoprimeiraColuna - FIM - NAO REMOVER */
    }
    .fieldhr {
      border: none;
      border-top: groove 2px white;
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?php
  if (db_getsession('DB_modulo') != 7159) {
    MsgAviso(db_getsession("DB_coddepto"),"escola");
  }
?>
<center>
  <div style="display:table;margin-top: 25px;">
    <form action="">
      <fieldset >
        <legend><b>Parâmetros Progressão Parcial/Dependência</b></legend>
        <fieldset class='fieldhr' id='FieldSetCampos'>
          <legend ><b>Configuração</b></legend>
          <table class='tabelas'>
            <tr>
              <td class="primeiraColuna" nowrap="nowrap" title = "<?php echo $Ted112_habilitado;?>">
                <?php echo $Led112_habilitado;?>
              </td>
              <td>
                <?php
                  $aHabilita = array('false' => 'Não', 'true' => 'Sim');
                  db_select('ed112_habilitado', $aHabilita, true, 1, 'onchange = "js_liberaCampos();"');
                ?>
              </td>
            </tr>
          </table>
          <table class='tabelas' id='progressaoAtiva'>
            <tr >
              <td class="primeiraColuna" nowrap="nowrap" title = "<?php echo $Ted112_quantidadedisciplinas;?>">
                <?php echo $Led112_quantidadedisciplinas;?>
              </td>
              <td>
                <?php
                  db_input('ed112_quantidadedisciplinas', 10, $Ied112_quantidadedisciplinas, true, 'text', 1, '', '', '', '',1);
                ?>
              </td>
            </tr>
            <tr >
              <td class="primeiraColuna" nowrap="nowrap" title = "<?php echo $Ted112_formacontrole;?>">
                <? echo $Led112_formacontrole?>
              </td>
              <td>
                <?php
                  db_select('ed112_formacontrole', getValoresPadroesCampo('ed112_formacontrole'), true, 1);
                ?>
              </td>
            </tr>
            <tr >
              <td class="primeiraColuna" nowrap="nowrap" title = "<?php echo $Ted112_controlefrequencia;?>">
                <? echo $Led112_controlefrequencia?>
              </td>
              <td>
                <?php
                  $aControlaFrequencia = array('false' => 'Não', 'true' => 'Sim');
                  db_select('ed112_controlefrequencia', $aControlaFrequencia, true, 1);
                ?>
              </td>
            </tr>
            <tr >
              <td class="primeiraColuna" nowrap="nowrap" title = "<?php echo $Ted112_disciplinaeliminadependencia;?>">
                <? echo $Led112_disciplinaeliminadependencia?>
              </td>
              <td>
                <?php
                  $aEliminaDependencia = array('false' => 'Não', 'true' => 'Sim');
                  db_select('ed112_disciplinaeliminadependencia', $aEliminaDependencia, true, 1,
                            'onchange = "js_liberaJustificativa();"');
                ?>
              </td>
            </tr>
            <tr id = 'ctnJustificativa' style="display:none;">
              <td class="primeiraColuna" nowrap="nowrap" title = "<?php echo $Ted112_justificativa;?>">
                <? echo $Led112_justificativa?>
              </td>
              <td>
                <?php
                  db_input('ed112_justificativa', 40, $Ied112_justificativa, true, 'text', 1);
                ?>
              </td>
            </tr>
            <!-- ATENCAO: PLUGIN ParametroProgressaoParcial - Linha Formulario - INSTALADO AQUI - NAO REMOVER -->
          </table>
        </fieldset>
        <fieldset class='fieldhr' id='etapas'>
          <legend><b>Etapas Com Progressão Parcial</b></legend>
          <div id='ctnTreeView' style='height:300px;'></div>
        </fieldset>
      </fieldset>
    </form>
    <center>
      <input type="button" id="btnIncluir" name="btnIncluir" value="Salvar" onclick="js_salvar()" />
    </center>
  </div>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

var sRPC            = 'edu4_parametrodependencia.RPC.php';
var oTreeViewEtapas = new DBTreeView('treeViewGrupo');
oTreeViewEtapas.show($('ctnTreeView'));
oNoPrincipal  = oTreeViewEtapas.addNode("0", "Lista de Cursos");
oTreeViewEtapas.allowFind(true);
oTreeViewEtapas.setFindOptions('matchedonly');


/**
 * Busca os Dados do formulario
 */
function js_getDados() {

  var oObject  = new Object;
  oObject.exec = 'getDados';

  js_divCarregando('Buscando Dados ...','msgBox');
  var objAjax   = new Ajax.Request (sRPC,{
                                           method:'post',
                                           parameters:'json='+Object.toJSON(oObject),
                                           asynchronous:false,
                                           onComplete:js_retornoDados
                                          }
                                   );
}

/**
 * Trata o retorno dos dados
 */
function js_retornoDados(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  $('ed112_habilitado').value                   = oRetorno.dados.lHabilitado;
  $('ed112_formacontrole').value                = oRetorno.dados.iFormaControle;
  $('ed112_quantidadedisciplinas').value        = oRetorno.dados.iNumeroDisciplina;
  $('ed112_controlefrequencia').value           = oRetorno.dados.lControlaFrequencia
  $('ed112_disciplinaeliminadependencia').value = oRetorno.dados.lDisciplinaEliminaDependencia;
  $('ed112_justificativa').value                = oRetorno.dados.sJustificativa.urlDecode();

  js_preencheTreeView(oRetorno.dados.aCursos);

  if (oRetorno.dados.lHabilitadoSecretaria && !oRetorno.dados.lModuloAcessoSecretaria) {

    alert("Esta rotina está habilitada para manutenção somente para o Módulo Secretaria de Educação.");
    js_liberaCampoBloqueado();
  } else if (!oRetorno.dados.lHabilitadoSecretaria && !oRetorno.dados.lModuloAcessoSecretaria) {

    js_liberaCampoDesbloqueado();
  } else if (!oRetorno.dados.lHabilitadoSecretaria && oRetorno.dados.lModuloAcessoSecretaria) {

    alert("Esta rotina está habilitada para manutenção somente para o Módulo Escola.");
    js_liberaCampoBloqueado();
  } else if (oRetorno.dados.lHabilitadoSecretaria && oRetorno.dados.lModuloAcessoSecretaria) {

    js_liberaCampoDesbloqueado();
  }
  /* ATENCAO: PLUGIN ParametroProgressaoParcial - lDependenciaMesmaDisciplina - INSTALADO AQUI - NAO REMOVER */
}

/**
 * Preenche os Cursos e as Etapas na TreeView
 */
function js_preencheTreeView(aCursos) {

  /**
   *Percorremos os Cursos retornados
   */
  aCursos.each(function (oCurso, ind) {

    var sIdentificadorPai = oCurso.iCodigoCurso + "#";
    var lAbrirEtapas      = false;
    var oNodeEtapas       = oTreeViewEtapas.addNode(sIdentificadorPai,
                                              oCurso.sDescricaoCurso.urlDecode(),
                                              0,
                                              null,
                                              null,
                                              null
                                             );
    /**
     * Percorremos as etapas deste curso
     */
    oCurso.aEtapas.each(function (oEtapa, iIndiceEtapa) {

      oCheck = function (oNode, event) {

        if (oNode.checkbox.checked) {
          oNode.checkAll(event);
        } else {
          oNode.uncheckAll(event);
        }
      }

      if (oEtapa.lConfigurada) {
        lAbrirEtapas = true;
      }
      oTreeViewEtapas.addNode(oEtapa.iCodigoEtapa,
                              oEtapa.sDescricaoEtapa.urlDecode(),
                              sIdentificadorPai,
                              null,
                              null,
                              {checked:oEtapa.lConfigurada,
                               onClick:oCheck,
                               disabled:oEtapa.lBloqueiaTreeView
                              }
                             );
    }); // fim  oCurso.aEtapas
    if (lAbrirEtapas) {
      oNodeEtapas.expand();
    }
  }); // aCursos.each
  oNoPrincipal.expand();
}


/**
 * Liberamos a visualizacao dos campos do formulario bloqueados para edicao
 */
function js_liberaCampoBloqueado() {

  js_liberaCampos();
  js_liberaJustificativa();

  $('ed112_habilitado').setAttribute('disabled', 'disabled');
  $('ed112_formacontrole').setAttribute('disabled', 'disabled');
  $('ed112_quantidadedisciplinas').setAttribute('disabled', 'disabled');
  $('ed112_controlefrequencia').setAttribute('disabled', 'disabled');
  $('ed112_disciplinaeliminadependencia').setAttribute('disabled', 'disabled');
  $('ed112_justificativa').setAttribute('disabled', 'disabled');
  $('btnIncluir').setAttribute('disabled', 'disabled');
}

/**
* Liberamos a visualizacao dos campos do formulario desbloqueados para edicao
*/
function js_liberaCampoDesbloqueado() {

  js_liberaCampos();
  js_liberaJustificativa();

  $('ed112_habilitado').removeAttribute('disabled');
  $('ed112_formacontrole').removeAttribute('disabled');
  $('ed112_quantidadedisciplinas').removeAttribute('disabled');
  $('ed112_controlefrequencia').removeAttribute('disabled');
  $('ed112_disciplinaeliminadependencia').removeAttribute('disabled');
  $('ed112_justificativa').removeAttribute('disabled');
  $('btnIncluir').removeAttribute('disabled');

}

/**
 * Validamos se a o campo Disciplina Aprovada Elimina Dependencia esta setado como sim.
 * Se sim devemos abrim um campo de justificativa para o usuario
 */
function js_liberaJustificativa() {

  if ($F('ed112_disciplinaeliminadependencia') == "true") {
    $('ctnJustificativa').style.display = 'table-row';
  } else {

    $('ed112_justificativa').value      = '';
    $('ctnJustificativa').style.display = 'none';
  }
}

/**
 * Caso o parametro de depencendia/progressao parcial esteja ativado
 * devemos liberar os campos do formulario
 */
function js_liberaCampos() {

  if ($F('ed112_habilitado') == "true") {

    $('progressaoAtiva').style.display = 'table';
    $('progressaoAtiva').style.width   = '100%';
    $('etapas').style.display     = '';
  } else {

    $('progressaoAtiva').style.display = 'none';
    $('etapas').style.display     = 'none';
  }
  js_liberaJustificativa();
}

/**
 * Trata o como os campos serão tratado no inicio do formulario
 */
function js_init() {

  $('progressaoAtiva').style.display = 'none';
  $('etapas').style.display     = 'none';

  $('btnIncluir').setAttribute('disabled', 'disabled');
  $('ed112_habilitado').setAttribute('disabled', 'disabled');
  $('ed112_quantidadedisciplinas').setAttribute('maxlength', '1');

  $('ed112_habilitado').style.width                   = '100%';
  $('ed112_formacontrole').style.width                = '100%';
  $('ed112_quantidadedisciplinas').style.width        = '100%';
  $('ed112_controlefrequencia').style.width           = '100%';
  $('ed112_disciplinaeliminadependencia').style.width = '100%';
  $('ed112_justificativa').style.width                = '100%';
}

js_init();
js_getDados();


/**
 * Persiste os dados
 */
function js_salvar() {

  var oParametro                           = new Object;
  oParametro.exec                          = 'salvar';
  oParametro.lHabilitado                   = $F('ed112_habilitado') == 'true' ? true : false;
  oParametro.iNumeroDisciplina             = $F('ed112_quantidadedisciplinas');
  oParametro.iFormaControle                = $F('ed112_formacontrole');
  oParametro.lControlaFrequencia           = $F('ed112_controlefrequencia') == 'true' ? true : false;
  oParametro.lDisciplinaEliminaDependencia = $F('ed112_disciplinaeliminadependencia')  == 'true' ? true : false;
  /* ATENCAO: PLUGIN ParametroProgressaoParcial - dependenciamesmadisciplina - INSTALADO AQUI - NAO REMOVER */
  oParametro.aEtapas                       = new Array();
  oParametro.sJustificativa                = encodeURIComponent(tagString($F('ed112_justificativa')));
  var aEtapasMarcadas                   = oTreeViewEtapas.getNodesChecked();
  aEtapasMarcadas.each(function(oNode) {
    oParametro.aEtapas.push(oNode.value);
  });

  if (oParametro.lHabilitado) {

    if (oParametro.iNumeroDisciplina == '' || oParametro.iNumeroDisciplina < 0) {

      alert('Para habilitar a Progressão parcial é necessário informar a Quantidade de Disciplinas Dependentes.');
      $('ed112_quantidadedisciplinas').focus();
      return false;
    }
    if (oParametro.aEtapas.length == 0) {

      alert('Nenhuma Etapa informada.');
      return false;
    }
  }
  js_divCarregando('Aguarde, processando dados...','msgBox');
  var objAjax   = new Ajax.Request (sRPC,{
                                           method:'post',
                                           parameters:'json='+Object.toJSON(oParametro),
                                           asynchronous:false,
                                           onComplete:js_retornoSalvar
                                          }
                                   );
}

/**
 *
 */
function js_retornoSalvar(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  alert(oRetorno.message.urlDecode());

  if ( oRetorno.status == 1 ) {
    location.href = 'edu1_parametrodependencia001.php';
  }
}
var iTamanhoFieldSetCampos    = $('FieldSetCampos').scrollHeight;
var ItamanhoDisponivel        = (document.body.clientHeight - iTamanhoFieldSetCampos) - 300;

/* ATENCAO: PLUGIN ParametroProgressaoParcial - js_liberaDependenciaMesmaDisciplina - INSTALADO AQUI - NAO REMOVER */

</script>