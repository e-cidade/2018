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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_processoforo_classe.php");

$clprocessoforo = new cl_processoforo;
$clprocessoforo->rotulo->label();

$clRotulo = new rotulocampo();
$clRotulo->label("v71_inicial");
$clRotulo->label("v58_numcgm ");
$clRotulo->label("z01_nome");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC >

<form class="container" name="form1" id="form1" onsubmit="js_pesquisa()">
  <fieldset>
  	<legend>Consultas - Processo do Foro</legend>
  	<table class="form-container">
      <tr> 
        <td nowrap title="<?=$Tv70_sequencial?>">
          <?=$Lv70_sequencial?>
      	</td>
      	<td nowrap> 
      	  <?
            db_input("v70_sequencial",10,$Iv70_sequencial,true,"text",4,"","chave_v70_sequencial");
          ?>
        </td>
      </tr>
    	<tr> 
      	<td nowrap title="<?=$Tv70_codforo?>">
          <?=$Lv70_codforo?>
    		</td>
    		<td nowrap> 
          <?
            db_input("v70_codforo",30,$Iv70_codforo,true,"text",4,"","chave_v70_codforo");
          ?>
    		</td>
    	</tr>
    	<tr> 
      	<td nowrap title="<?=$Tv71_inicial?>">
          <?=$Lv71_inicial?>
    		</td>
    		<td nowrap> 
          <?
            db_input("v71_inicial",10,$Iv71_inicial,true,"text",4,"","chave_v71_inicial");
          ?>
      	</td>
    	</tr>          
    	<tr>
      	<td nowrap title="<?=@$Tv58_numcgm?>">
          <?
            db_ancora(@$Lv58_numcgm," js_pesquisaCgm(true); ",1);
          ?>
    		</td>
    		<td> 
      	  <?
            db_input('v58_numcgm',10,$Iv58_numcgm,true,'text',4," onchange='js_pesquisaCgm(false);'","chave_v58_numcgm");
            db_input('z01_nome',40,$Iz01_nome,true,'text',3);
          ?>
      	</td>
    	</tr>
      <tr>
    		<td nowrap title="<?=@$Tv58_numcgm?>">
    			Situação:
      	</td>
    		<td> 
      	  <?
            $aSituacao = array('T'  => 'Todos',
                               'AT' => 'Ativo',
                               'AN' => 'Anulado');
            db_select('v70_anulado', $aSituacao, true, 1, "style='width:92px;'");
          ?>
    		</td>
    	</tr>
    	<tr>
    		<td title="<?=$Tv70_data?>">
    			Período:
    		</td>
    		<td>
          <?php 
            db_inputdata('v70_datainicial', @$v70_datainicial_dia, @$v70_datainicial_mes, @$v70_datainicial_ano, true, 'text', 1);
          ?>
    		  <b>a</b>
          <?php 
            db_inputdata('v70_datafinal', @$v70_datafinal_dia, @$v70_datafinal_mes, @$v70_datafinal_ano, true, 'text', 1);
          ?>
    		</td>
    	</tr>
  	</table>
  </fieldset>
  <input name="pesquisa" type="submit" value="Pesquisa">
</form>
  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>

function js_pesquisa() {
  //cria um iframe
  js_OpenJanelaIframe('top.corpo','db_iframe_processoforo','','Processo Foro', true);
  $('form1').method  = 'post';
  $('form1').action  = 'func_processoforo.php';
  $('form1').action += '?&lSituacao=true&funcao_js=parent.js_detalhesProcesso|v70_sequencial';
  $('form1').target  = "IFdb_iframe_processoforo";
  
}

function js_detalhesProcesso(iCodigoProcessoForo) {

  js_OpenJanelaIframe('top.corpo',
      							  'db_iframe_processoforo', 
      								'jur1_consultaprocessoforo002.php?v70_sequencial='+iCodigoProcessoForo,
      								'Consulta',
      								true);

  
}

function js_pesquisaCgm(mostra) {

  var cgm = document.form1.chave_v58_numcgm.value;
  if (mostra == true) {
  
    var sUrl = 'func_nome.php?funcao_js=parent.js_mostracgm|0|1';
    js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', true);
  } else {
  
    if (cgm != "") {
    
      var sUrl = 'func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1';
      js_OpenJanelaIframe('', 'db_iframe_numcgm', sUrl, 'Pesquisa', false);
    } else {
    
      document.form1.chave_v58_numcgm.value = '';
      document.form1.z01_nome.value         = '';
    }  
  }
}

function js_mostracgm(chave1, chave2) {

  document.form1.chave_v58_numcgm.value  = chave1;
  document.form1.z01_nome.value          = chave2;
  db_iframe_numcgm.hide();
}

function js_mostracgm1(erro,chave) {

  document.form1.z01_nome.value = chave; 
  if (erro == true) { 
  
    document.form1.chave_v58_numcgm.focus(); 
    document.form1.chave_v58_numcgm.value = '';
  }
}

</script>