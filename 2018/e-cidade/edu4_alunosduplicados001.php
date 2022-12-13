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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, strings.js, prototype.js, dbcomboBox.widget.js, datagrid.widget.js");
    db_app::load('estilos.css, grid.style.css')
  ?>
  <style>
  #ctnGridOpcoesPesquisa td {
    padding: 3px;
  }
  
  .camposGrid {
    display: block;
  }
  
  </style>
</head>
<body bgcolor="#CCCCCC" style="margin: 25px auto 10px auto;">
  <center>
	  <div style="display: table;">
		  <form name='form1'>
		    <fieldset>
		      <legend><b>Filtros</b></legend>
		      <table>
		        <tr>
		          <td><b>Nome do Aluno: </b></td>
		          <td>
		            <?
		              db_input('nomeAluno', 50, 'nomeAluno', true, 'text', 1);
		            ?>
		          </td>
		        </tr>
		        <tr>
		          <td><b>Nome da Mãe: </b></td>
		          <td>
		            <?
		              db_input('nomeMae', 50, 'nomeMae', true, 'text', 1);
		            ?>
		          </td>
		        </tr>
		        <tr>
		          <td><b>Data de Nascimento: </b></td>
		          <td>
		            <?
		              db_inputdata('dataNascimento', '', '', '', true, 'text', 1);
		            ?>
		          </td>
		        </tr>
		      </table>
		    </fieldset>
		    <div style="text-align: center;">
		      <input type='button' id='btnPesquisar' name='btnPesquisar' value='Pesquisar' onclick='js_buscarAlunos()' />
		      <input type='button' id='btnLimparCampos' name=''btnLimparCampos'' value='Limpar Campos' onclick='js_limparCampos()' />
		    </div>
		    <div style='width: 1200px'>
			    <fieldset>
			      <legend><b>Alunos Retornados</b></legend>
				    <div id='ctnAlunosRetornados'></div>
			    </fieldset>
			    <div style="text-align: center;">
			      <input type='button' 
			             id='btnProcessar' 
			             name='btnProcessar' 
			             value='Processar' 
			             onclick='js_incluirInconsistencia()' 
			             disabled='disabled' />
			    </div>
		    </div>
		  </form>
	  </div>
  </center>
</body>
</html>
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
<script>

var sRPCAluno          = 'edu4_aluno.RPC.php';
var sRPCInconsistencia = 'con4_registrosinconsistentes.RPC.php';
		            
/**
 * Monta a grid dos alunos
 */
function js_montaGrid() {

  var aCabecalho   = new Array( 'Correto', 'Errado', 'Código', 'Nome do Aluno', 'Nome da Mãe', 'Data de Nascimento' );
  var aAlinhamento = new Array( 'center', 'center', 'right', 'left', 'left', 'left' );
  var aLargura     = new Array( '5%', '5%', '5%', '40%', '40%', '10%' );
  
  oGridAlunosRetornados              = new DBGrid('gridAlunosRetornados');
  oGridAlunosRetornados.nameInstance = 'oGridAlunosRetornados';
  oGridAlunosRetornados.setHeader(aCabecalho);
  oGridAlunosRetornados.setCellAlign(aAlinhamento);
  oGridAlunosRetornados.setCellWidth(aLargura);
  oGridAlunosRetornados.show($('ctnAlunosRetornados'));

  js_limparCampos();
}

/**
 * Busca os alunos de acordo com o nome informado, e caso tenha sido informado o nome da mae e data de nascimento, 
 * utiliza estes campos nas busca
 */
function js_buscarAlunos() {

  if ( $F('nomeAluno') == '' ) {

    alert('Deve ser informado o nome do aluno.');
    return false;
  }
  
  var oParametro              = new Object();
      oParametro.exec         = 'buscaAlunos';
      oParametro.sNomeAluno   = encodeURIComponent(tagString($F('nomeAluno')));
      oParametro.sNomeMae     = encodeURIComponent(tagString($F('nomeMae')));
      oParametro.dtNascimento = $F('dataNascimento');

  var oDadosRequest            = new Object();
      oDadosRequest.methot     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = js_retornoBuscaAlunos;

  js_divCarregando("Aguarde, buscando os alunos de acordo com os campos informados.", "msgBox");
  new Ajax.Request( sRPCAluno, oDadosRequest );
}

/**
 * Retorno da busca dos alunos
 */
function js_retornoBuscaAlunos( oResponse ) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if ( oRetorno.aAlunos.length > 0 ) {

    var iContador = 0;
    
    oGridAlunosRetornados.clearAll(true);
    oRetorno.aAlunos.each(function( oAluno, iAluno ) {

      var aLinha    = new Array();
          aLinha[0] = "<input class='camposGrid' type='radio' id='correto_"+iContador+"' name='correto' onclick='js_verificaMarcado();' />";
          aLinha[1] = "<input class='camposGrid' type='checkbox' id='errado_"+iContador+"' name='errado' />";
          aLinha[2] = oAluno.ed47_i_codigo;
          aLinha[3] = "<a href='#' onClick='js_consultaAluno("+oAluno.ed47_i_codigo+")'>"+oAluno.ed47_v_nome.urlDecode()+"</a>";
          aLinha[4] = oAluno.ed47_v_mae.urlDecode();
          aLinha[5] = js_formatar(oAluno.ed47_d_nasc, 'd');

      iContador++;
      oGridAlunosRetornados.addRow(aLinha);
    });
    oGridAlunosRetornados.renderRows();
  } else {

    alert('Não foram encontrados alunos utilizando as informações preenchidas nos campos.');
    return false;
  }
}

/**
 * Verificamos qual item foi marcado como correto e desabilitamos o checkbox do errado 
 */
function js_verificaMarcado() {

  var aErrados  = document.getElementsByName('errado');
  var aCorretos = document.getElementsByName('correto');

  for(var iIndiceErrados = 0; iIndiceErrados < aErrados.length; iIndiceErrados++) {
    $('errado_'+iIndiceErrados).disabled = false;          
  } 

  for(var iIndiceCorretos = 0; iIndiceCorretos < aErrados.length; iIndiceCorretos++) {

    if ( $('correto_'+iIndiceCorretos).checked ) {
            
      $('errado_'+iIndiceCorretos).disabled = true;    
      $('errado_'+iIndiceCorretos).checked = false;      
    }
  }
  $('btnProcessar').disabled = false; 
}

/**
 * Chama a consulta do aluno clicado
 */
function js_consultaAluno( iCodigoAluno ) {
  
  js_OpenJanelaIframe(
                       'top.corpo',
                       'db_iframe_aluno',
                       'edu3_alunos001.php?chavepesquisa='+iCodigoAluno+'&lAlunosDuplos',
                       'Consulta do Aluno',
                       true
                     );
}

/**
 * Incluimos as inconsistencias selecionadas
 */
function js_incluirInconsistencia() {

  var oParametro               = new Object();
      oParametro.sExec         = "incluirInconsistencia";
      oParametro.iCorreto      = 0;
      oParametro.iCodigoTabela = 1010051;
      oParametro.aCampos       = new Array();

  $$('.camposGrid').each(function(oElemento) {
    
    if (!oElemento.checked) {
      return;
    }

    var aIdLinhaCampo    = oElemento.id.split('_');
    var iSequencialCampo = $('gridAlunosRetornadosrow'+aIdLinhaCampo[1]+'cell2').innerHTML;
    
    var oCampo                  = new Object();
        oCampo.iSequencialCampo = iSequencialCampo;
        oCampo.lExcluir         = true;

    if (oElemento.type == 'radio') {

      oParametro.iCorreto = iSequencialCampo;
      return; 
    }

    oParametro.aCampos.push(oCampo);
  });

  if (oParametro.iCorreto == 0 || oParametro.aCampos.length == 0) {

    alert('Marque um item correto e no mínimo um errado para continuar');
    return false;
  }

  js_divCarregando("Processando os dados do aluno...", "msgBox");

  var oDadosRequest            = new Object();
		  oDadosRequest.method     = 'post';
		  oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
		  oDadosRequest.onComplete = js_retornoIncluirInconsistencia;
  
  new Ajax.Request( sRPCInconsistencia, oDadosRequest );
}

/**
 * Retorno da inclusao de inconsistencias
 */
function js_retornoIncluirInconsistencia( oResponse ) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.sMessage.urlDecode());

  if ( oRetorno.iStatus == 2 ) {
    return false;
  } else {
    js_limparCampos();
  }
}

/**
 * Limpamos os campos na tela
 */
function js_limparCampos() {

  $('nomeAluno').value      = '';
  $('nomeMae').value        = '';
  $('dataNascimento').value = '';
  oGridAlunosRetornados.clearAll(true);
}

js_montaGrid();
</script>