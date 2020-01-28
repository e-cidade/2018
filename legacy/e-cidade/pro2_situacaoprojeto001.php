<?
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$clArquivoAuxiliar = new cl_arquivo_auxiliar();

$clArquivoAuxiliar->cabecalho      = '<strong>Situação do Projeto</strong>';
$clArquivoAuxiliar->codigo         = 'ob28_sequencial';
$clArquivoAuxiliar->descr          = 'ob28_descricao';
$clArquivoAuxiliar->nomeobjeto     = 'oSituacaoProjeto';
$clArquivoAuxiliar->funcao_js      = 'js_mostraSituacao';
$clArquivoAuxiliar->funcao_js_hide = 'js_mostraSituacaoHide';
$clArquivoAuxiliar->func_arquivo   = 'func_obrassituacao.php';
$clArquivoAuxiliar->nomeiframe     = 'db_iframe_situacao';
$clArquivoAuxiliar->db_opcao       = 2;
$clArquivoAuxiliar->tipo           = 2;
$clArquivoAuxiliar->linhas         = 3;
$clArquivoAuxiliar->vwidth         = 350;
$clArquivoAuxiliar->Labelancora    = 'Código';
$clArquivoAuxiliar->obrigarselecao = false;
	
?>

<html>
<head>
<?php 
	db_app::load('scripts.js, prototype.js, estilos.css');
?>
</head>

<body bgcolor="#CCCCCC">
<form class="container" name="form1" id="form1">
  <fieldset>
  	<legend>Relatório de Situação dos Projetos</legend>
  	<table class="form-container">
  		<tr>
  			<td title="Data de lançamento da situação da obra">
  				Data de Lançamento:
  			</td>
  			<td>
  				<?php
  					db_inputdata('data_inicial', null, null, null, true, 'text', 1)
  				?>
  				<b>até</b>
  				<?php
  					db_inputdata('data_final', null, null, null, true, 'text', 1)
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td colspan="2">
  			  <?php
  			  	$clArquivoAuxiliar->funcao_gera_formulario();
  				?>
  			</td>
  		</tr>
  	</table>
  </fieldset>
  <input type="button" name="processar" id="processar" Value="Processar" onclick="js_processar()"/>
  
  <?php
  	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  
  <script type="text/javascript">
  
  function js_processar() {
  	
  	var sCodigoSituacao = js_campo_recebe_valores();
  
  	var dDataInicial    = document.form1.data_inicial.value;
  
  	var dDataFinal      = document.form1.data_final.value;
  
  	jan = window.open('pro2_situacaoprojeto002.php?situacao='+sCodigoSituacao+'&data_inicial='+dDataInicial+'&data_final='+dDataFinal, 
  			              '', 
  			              'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 ');
  	
  }
  </script>
</form>
</body>
</html>

<script>
$("data_inicial").addClassName("field-size2");
$("data_final").addClassName("field-size2");
$("fieldset_oSituacaoProjeto").addClassName("separator");
$("ob28_sequencial").addClassName("field-size2");
$("ob28_descricao").addClassName("field-size7");
$("oSituacaoProjeto").style.width = "100%";
</script>