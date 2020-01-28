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

$oDaoRegistrosInconsistentes = new cl_db_registrosinconsistentes();
$oDaoRegistrosInconsistentes->rotulo->label();
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
		      <legend><b>Aluno Correto</b></legend>
		      <table>
		        <tr style='display: none;'>
					    <td nowrap title="<?=@$Tdb136_sequencial?>">
					       <?=@$Ldb136_sequencial?>
					    </td>
					    <td> 
								<?
									db_input('db136_sequencial', 10, $Idb136_sequencial, true, 'text', 3, "");
								?>
					    </td>
					  </tr>
		        <tr>
		          <td><b>Nome do Aluno: </b></td>
		          <td>
		            <?
		              db_input('nomeAluno', 50, 'nomeAluno', true, 'text', 3);
		            ?>
		          </td>
		        </tr>
		        <tr>
		          <td><b>Nome da Mãe: </b></td>
		          <td>
		            <?
		              db_input('nomeMae', 50, 'nomeMae', true, 'text', 3);
		            ?>
		          </td>
		        </tr>
		        <tr>
		          <td><b>Data de Nascimento: </b></td>
		          <td>
		            <?
		              db_inputdata('dataNascimento', '', '', '', true, 'text', 3);
		            ?>
		          </td>
		        </tr>
		      </table>
		    </fieldset>
		    <div style="text-align: center;">
		      <input type='button' id='btnPesquisar' name='btnPesquisar' value='Pesquisar' onclick='js_buscarAlunos()' />
		    </div>
		    <div style='width: 1200px'>
			    <fieldset>
			      <legend><b>Alunos Errados</b></legend>
				    <div id='ctnAlunosErrados'></div>
			    </fieldset>
			    <div style="text-align: center;">
			      <input type='button' 
			             id='btnExcluir' 
			             name='btnExcluir' 
			             value='Excluir' 
			             onclick='js_excluir()' 
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

var sRPCInconsistencia = 'con4_registrosinconsistentes.RPC.php';

/**
 * Monta a grid dos alunos
 */
function js_montaGrid() {

  var aCabecalho   = new Array( 'Código', 'Nome do Aluno', 'Nome da Mãe', 'Data de Nascimento' );
  var aAlinhamento = new Array( 'right', 'left', 'left', 'left' );
  var aLargura     = new Array( '5%', '45%', '40%', '10%' );
  
  oGridAlunosErrados              = new DBGrid('gridAlunosErrados');
  oGridAlunosErrados.nameInstance = 'oGridAlunosErrados';
  oGridAlunosErrados.setHeader(aCabecalho);
  oGridAlunosErrados.setCellAlign(aAlinhamento);
  oGridAlunosErrados.setCellWidth(aLargura);
  oGridAlunosErrados.show($('ctnAlunosErrados'));

  js_limparCampos();
}

/**
 * Limpamos os campos na tela
 */
function js_limparCampos() {

  $('db136_sequencial').value = '';
  $('nomeAluno').value        = '';
  $('nomeMae').value          = '';
  $('dataNascimento').value   = '';
  oGridAlunosErrados.clearAll(true);
}

/**
 * Buscamos os alunos que tiveram registros cadastrados como inconsistentes e que ainda nao foram processados
 */
function js_buscarAlunos() {

  var sParametros = 'funcao_js=parent.js_buscaDadosAluno|db136_sequencial&lTabelaAlunos';
  js_OpenJanelaIframe(
                       'top.corpo',
                       'db_iframe_db_registrosinconsistentes',
                       'func_db_registrosinconsistentes.php?'+sParametros,
                       'Pesquisa',
                       true
                     );
}

/**
 * Buscamos os dados da inconsistencia de acordo com o sequencial retornado da lookup
 */
function js_buscaDadosAluno( iSequencial ) {

  db_iframe_db_registrosinconsistentes.hide();
  $('db136_sequencial').value = iSequencial;
  
  var oParametro              = new Object();  
      oParametro.sExec            = "consultaInconsistencia";
      oParametro.iInconsistencia  = iSequencial;
      oParametro.iCodigoTabela    = 1010051;
      oParametro.sNomeTabela      = 'aluno';

  var oDadosRequest            = new Object();
      oDadosRequest.method     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = js_retornaRegistrosInconsistencia;
      
  js_divCarregando("Consultando registros do sistema", "msgBox");
  new Ajax.Request (sRPCInconsistencia, oDadosRequest);
  	
}

/**
 * Retorno dos dados da inconsistencia.
 * Caso tenha sido encontrado algum registro, comparamos o codigo do aluno com o codigo iCorreto, preenchendo os dados 
 * deste nos inputs. Os registros incorretos sao preenchidos na Grid
 */
function js_retornaRegistrosInconsistencia ( oResponse ) {
  	
  js_removeObj("msgBox");

  var oRetorno = eval("("+oResponse.responseText+")");

  if (oRetorno.aDadosInconsistentes.length == 0) {
  	
  	alert('Nenhum Registro Encontrado');
  	return false;
  }
  
  oGridAlunosErrados.clearAll(true);
  oRetorno.aDadosInconsistentes.each(function( oAluno, iAluno ) {

    if ( oAluno.ed47_i_codigo == oRetorno.iCorreto ) {

      $('nomeAluno').value      = oAluno.ed47_v_nome.urlDecode();
      $('nomeMae').value        = oAluno.ed47_v_mae.urlDecode();
      $('dataNascimento').value = js_formatar(oAluno.ed47_d_nasc, 'd');
    } else {
      
	    var aLinha    = new Array();
	        aLinha[0] = oAluno.ed47_i_codigo;
	        aLinha[1] = oAluno.ed47_v_nome.urlDecode();
	        aLinha[2] = oAluno.ed47_v_mae.urlDecode();
	        aLinha[3] = js_formatar(oAluno.ed47_d_nasc, 'd');
	
	    oGridAlunosErrados.addRow(aLinha);
    }
  });	  
  oGridAlunosErrados.renderRows();
  $('btnExcluir').disabled = false;
}

/**
 * Excluimos o registro inconsistente selecionado
 */
function js_excluir() {

  if ($F('db136_sequencial') == '') {
    
		alert('Não há registros para excluir');
		return false;
	}

  var sMensagemExclusao  = 'Confirma a exclusão do registro inconsistente para o(a) aluno(a) '+$F('nomeAluno');
      sMensagemExclusao += ' (lembrando a correção do mesmo não será processada) ?';

  if ( confirm(sMensagemExclusao) ) {
    
		var oParametro                       = new Object(); 
		    oParametro.sExec                 = "excluir";
		    oParametro.iCodigoInconsistencia = $F('db136_sequencial');
		
		var oDadosRequest            = new Object();
	      oDadosRequest.method     = 'post';
	      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
	      oDadosRequest.onComplete = js_retornaExcluir;
	  
		js_divCarregando("Excluindo Inconsistências", "msgBox");
		new Ajax.Request (sRPCInconsistencia, oDadosRequest);
  }
}

/**
 * Retorno da exclusao do registro inconsistente
 */
function js_retornaExcluir( oResponse ) {

	js_removeObj("msgBox");
	var oRetorno = eval("("+oResponse.responseText+")");

	var sMensagem = oRetorno.sMessage.urlDecode().replace(/\\n/g,"\n");

  alert(sMensagem);
	if (oRetorno.iStatus > 1) {
		return false;
	}
	
	js_limparCampos();
	js_buscarAlunos();
}
 
js_buscarAlunos();
js_montaGrid();
</script>