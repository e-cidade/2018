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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oRotulo = new rotulocampo;
$oRotulo->label("v50_inicial");
$oRotulo->label("v70_sequencial");
$oRotulo->label("v70_codforo");

/**
 * 
 * Esse fonte recebe o parâmetro iTipoArquivo adionado no nome do arquivo no item de menu
 * 
 * iTipoArquivo = 1 - incluir = prepara o arquivo para inclusão de movimentações
 * iTipoArquivo = 2 - alterar = prepara o arquivo para alteração de movimentações
 * iTipoArquivo = 3 - excluir = prepara o arquivo para exclusão de movimentações
 * 
 * */

?>
<html>
<head>
  <?php
    db_app::load('scripts.js, prototype.js, datagrid.widget.js, strings.js');
    db_app::load('estilos.css, grid.style.css');
  ?>
</head>
</head>
<body bgcolor="#CCCCCC">
<form class="container" name="form1" id="form1">
  <fieldset>
    <legend>Procedimentos - Pesquisa Inicial ou Processo de Inicial</legend>
  	<table class="form-container">
  		<tr>
  			<td nowrap title="<?=@$Tv50_inicial?>">
  			<?
  			  db_ancora(@$Lv50_inicial,"js_pesquisaInicial(true);", 1);
  			?>
  			</td>
  			<td>
  			<?
  			  db_input('v50_inicial', 20, $Iv50_inicial, true, 'text', 1, " onchange='js_pesquisaInicial(false);'");
  			?>
  			</td>
  		</tr>
  		<tr>
  			<td nowrap title="<?=@$Tv70_codforo?>">
    			<?
    			  db_ancora('Processo do Foro', "js_pesquisaProcessoForo(true);", 1);
    			?>
  			</td>
  			<td>
  			<?
  			  db_input('v70_sequencial', 10, $Iv70_sequencial, true, 'hidden', 1);
  			  db_input('v70_codforo'   , 20, $Iv70_codforo   , true, 'text'  , 1, " onchange='js_pesquisaProcessoForo(false);'")
  			?>
  			</td>
  		</tr>
  	</table>
  </fieldset>
  
    <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar" onclick="js_pesquisa()"/>
  <?
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</form>

<script>

function js_pesquisaInicial(lMostra){
  
  if (lMostra==true) {
    
		js_OpenJanelaIframe('top.corpo','db_iframe_inicial','func_inicial.php?funcao_js=parent.js_mostraInicial|0','Pesquisa',true);
		
  }else{
    
		js_OpenJanelaIframe('top.corpo','db_iframe_inicial','func_inicial.php?pesquisa_chave='+document.form1.v50_inicial.value+'&funcao_js=parent.js_mostraInicialHide','Pesquisa',false);
		
  }
}

function js_mostraInicialHide(iCodigoInicial, lErro){
  
  if(lErro==true){
    
    alert(_M('tributario.juridico.jur4_inicialmov001.inicial_invalida'));
    document.form1.v50_inicial.focus();
     
  }else{
    
    document.form1.v50_inicial.value = iCodigoInicial;    
    
  }
	
}

function js_mostraInicial(iCodigoInicial){
  
  document.form1.v50_inicial.value = iCodigoInicial ;
  
  db_iframe_inicial.hide();
  
}

function js_pesquisaProcessoForo(lMostra){
  
  if (lMostra==true) {
    
		js_OpenJanelaIframe('top.corpo','db_iframe_processoforo','func_processoforo.php?lPossuiIniciais=true&funcao_js=parent.js_enviaConsulta|v70_sequencial','Pesquisa',true);
		
  }else{
    
		js_OpenJanelaIframe('top.corpo','db_iframe_processoforo','func_processoforo.php?lPossuiIniciais=true&pesquisa_chave='+document.form1.v70_codforo.value+'&funcao_js=parent.js_mostraProcessoForoHide','Pesquisa', false);

	}
	
}

function js_mostraProcessoForoHide(iCodigoProcessoForo, lErro){
  
  if(lErro == true){
    
    document.form1.v70_codforo.focus();
     
  }else{
    
    document.form1.v70_codforo.value = iCodigoProcessoForo;
        
  }
	
}

function js_pesquisa() {


  var oGet                = js_urlToObject();
  var iCodigoInicial      = $F('v50_inicial');
  var iCodigoProcessoForo = $F('v70_sequencial');
  var iNumeroProcessoForo = $F('v70_codforo');
  var sQueryString        = 'func_processoforo.php';
  
  if (iCodigoInicial != '') {
    
    window.location.href    = "jur4_inicialmov002.php?iTipoAcao=" + oGet.iTipoAcao + "&iCodigoInicial=" + iCodigoInicial + "&iCodigoProcessoForo=" + iCodigoProcessoForo;
    
  } else  {

    sQueryString += '?lPossuiIniciais=true&chave_v70_codforo=' + iNumeroProcessoForo + '&funcao_js=parent.js_enviaConsulta|v70_sequencial';
    
    js_OpenJanelaIframe('top.corpo','db_iframe_processoforo', sQueryString,'Processo Foro', true);

  }
}

function js_enviaConsulta(iCodigoProcessoForo) {
  var oGet                = js_urlToObject();
  
  window.location.href    = "jur4_inicialmov002.php?iTipoAcao=" + oGet.iTipoAcao + "&iCodigoProcessoForo=" + iCodigoProcessoForo;
}
</script>
</body>
</html>