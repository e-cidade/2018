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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$clArquivoAuxiliar = new cl_arquivo_auxiliar();

$clArquivoAuxiliar->lFuncaoPersonalizada    = true;
$clArquivoAuxiliar->db_opcao                = 1;
$clArquivoAuxiliar->tipo                    = 2;
$clArquivoAuxiliar->linhas                  = 3;
$clArquivoAuxiliar->vwidth                  = 350;
$clArquivoAuxiliar->Labelancora             = 'Código';
$clArquivoAuxiliar->obrigarselecao          = false;

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
    <legend>Relatório de Alvarás Vencidos</legend>
  	<table class="form-container">
  		<tr>
  			<td title="Data de Vencimento">Data de Liberação:</td>
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
  			<td title="Data de lançamento da situação da obra">Habite-se:</td>
  			<td>
  				<?php
  					$aHabite = array('' => 'Todos...', '1' => 'Com Habite-se', '2' => 'Sem Habite-se');
  					db_select('lHabite', $aHabite, true, 1);
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td colspan="2">
  			  <?php
  				  $clArquivoAuxiliar->cabecalho               = '<strong>Logradouro</strong>';
  				  $clArquivoAuxiliar->codigo                  = 'j14_codigo';
  				  $clArquivoAuxiliar->descr                   = 'j14_nome';
  				  $clArquivoAuxiliar->nomeobjeto              = 'oLogradouros';
  				  $clArquivoAuxiliar->funcao_js               = 'js_mostraRua';
  				  $clArquivoAuxiliar->funcao_js_hide          = 'js_mostraRuaHide';
  				  $clArquivoAuxiliar->func_arquivo            = 'func_ruas_arquivoauxiliar.php';
  				  $clArquivoAuxiliar->nomeiframe              = 'db_iframe_logradouros';
  				  $clArquivoAuxiliar->nome_botao              = 'btnLogradouro';
  				  	
  			  	$clArquivoAuxiliar->funcao_gera_formulario();
  				?>
  			</td>
  		</tr>
  		<tr>
  			<td colspan="2">
  			  <?php
  				  $clArquivoAuxiliar->cabecalho            = '<strong>Bairro</strong>';
  				  $clArquivoAuxiliar->codigo               = 'j13_codi';
  				  $clArquivoAuxiliar->descr                = 'j13_descr';
  				  $clArquivoAuxiliar->nomeobjeto           = 'oBairros';
  				  $clArquivoAuxiliar->funcao_js            = 'js_mostraBairro';
  				  $clArquivoAuxiliar->funcao_js_hide       = 'js_mostraBairroHide';
  				  $clArquivoAuxiliar->func_arquivo         = 'func_bairro.php';
  				  $clArquivoAuxiliar->nomeiframe           = 'db_iframe_bairros';
  				  $clArquivoAuxiliar->nome_botao           = 'btnBairro';
  			  
  			  	$clArquivoAuxiliar->funcao_gera_formulario();
  				?>
  			</td>
  		</tr>
  	</table>
  </fieldset>
  <input type="button" name="processar" id="processar" Value="Processar" onclick="js_processar()"/>
</form>

<?php
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">
function js_processar() {

	var dDataInicial = document.form1.data_inicial.value;

	var dDataFinal   = document.form1.data_final.value;

	var lComHabite   = document.form1.lHabite.value;

	var sBairros     = js_campo_recebe_valores_oBairros();

	var sLogradouros = js_campo_recebe_valores_oLogradouros();

	jan = window.open('pro2_alvarasvencidos002.php?data_inicial='+dDataInicial+'&data_final='+dDataFinal+'&habite='+lComHabite+'&bairros='+sBairros+'&logradouros='+sLogradouros, 
            '', 
            'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0 ');
	
}
</script>

</body>
</html>

<script>

$("data_inicial").addClassName("field-size2");
$("data_final").addClassName("field-size2");
$("fieldset_oLogradouros").addClassName("separator");
$("fieldset_oBairros").addClassName("separator");
$("j14_codigo").addClassName("field-size2");
$("j14_nome").addClassName("field-size7");
$("oLogradouros").style.width = "100%";
$("j13_codi").addClassName("field-size2");
$("j13_descr").addClassName("field-size7");
$("oBairros").style.width = "100%";

</script>