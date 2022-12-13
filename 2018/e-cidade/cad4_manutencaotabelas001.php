<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require_once("classes/db_iptutabelas_classe.php");
require_once("classes/db_iptutabelasconfig_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cliptutabelas        = new cl_iptutabelas;
$cliptutabelasconfig  = new cl_iptutabelasconfig;
$clrotulo             = new rotulocampo();

$cliptutabelas->rotulo->label();
$cliptutabelasconfig->rotulo->label();
$clrotulo->label('nomearq');

$db_opcao             = 1;
$db_botao             = true;
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
<style>
td {
  white-space: nowrap
}

fieldset table td:first-child {
              width: 90px;
              white-space: nowrap
}

#nomearq {
  width: 100%;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
			<form name="form1" method="post" action="cad4_manutencaotabelas002.php">
			  <fieldset>
			    <legend><b>Manutenção de Tabelas do Cálculo</b></legend>
			    <table border="0" align="center" width="400px">
			      <tr>
			        <td nowrap title="<?=@$Tj121_sequencial?>">
			          <?
			            db_ancora("<b>Tabela Cálculo:</b>", "js_pesquisaj121_sequencial(true);", $db_opcao);
			          ?>
			        </td>
			        <td> 
			          <?
			            db_input('j121_sequencial', 10, $Ij121_sequencial, true, 
			                     'text', $db_opcao, " onchange='js_pesquisaj121_sequencial(false);'");
			          ?>
			        </td>
			        <td> 
			          <?
			            db_input('nomearq', 40, $Inomearq, true, 'text', 3);
			            db_input('j121_codarq', 10, $Ij121_codarq, true, 'hidden', 3);
			          ?>
			        </td>
			      </tr>
			    </table>
			  </fieldset>
			  <table align="center">
			    <tr>
			      <td>&nbsp;</td>
			    </tr>
			    <tr>      
			      <td>
			        <input name="processar" type="submit" id="processar" value="Processar" onclick="return js_validar();">
			      </td> 
			    </tr>
			  </table>
			</form>
    </td>
  </tr>
</table>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_validar() {

  var j121_sequencial = $('j121_sequencial').value;
  if (j121_sequencial == '') {
    alert('Informe o código da tabela!');
    return false;
  } 
}

function js_pesquisaj121_sequencial(mostra) {

  if (mostra == true) {
  
    var sUrl = 'func_iptutabelas.php?funcao_js=parent.js_mostraiptutabelas1|j121_sequencial|j121_codarq|nomearq';
    js_OpenJanelaIframe('', 'db_iframe_iptutabelas', sUrl, 'Pesquisa', true);
  } else {
  
    if ($('j121_sequencial').value != '') {
    
      var sUrl = 'func_iptutabelas.php?pesquisa_chave='+$('j121_sequencial').value
                +'&funcao_js=parent.js_mostraiptutabelas'; 
      js_OpenJanelaIframe('', 'db_iframe_iptutabelas', sUrl, 'Pesquisa', false);
    } else {
    
      $('j121_sequencial').value = ''; 
      $('nomearq').value        = '';
      $('j121_codarq').value     = '';
    }
  }
}

function js_mostraiptutabelas(chave1, chave2, chave3, erro) {

  $('j121_sequencial').value = chave1;
  $('j121_codarq').value     = chave2; 
  $('nomearq').value         = chave3;
  if (erro == true) {
   
    $('j121_sequencial').value = ''; 
    $('j121_codarq').value     = ''; 
    $('nomearq').value         = chave1;
    $('j121_sequencial').focus(); 
  }
}

function js_mostraiptutabelas1(chave1, chave2, chave3) {

  $('j121_sequencial').value = chave1;
  $('j121_codarq').value     = chave2;
  $('nomearq').value         = chave3;
  db_iframe_iptutabelas.hide();
}
</script>
</html>