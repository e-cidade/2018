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

set_time_limit(0);
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
$clrotulo        = new rotulocampo;
$clrotulo->label('v70_sequencial');
$clrotulo->label('v70_codforo');
db_app::load("prototype.js");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
  <form class="container" name="form1" method="post">
    <fieldset>
      <legend>Procedimentos - Manutenção de Custas para Processo do Foro</legend>
      <table class="form-container">
        <tr>
          <td title="<?=@$Tv70_codforo?>" >
            <?db_ancora(@$Lv70_codforo, "js_pesquisaprocessoforo(true);", 4);?>
          </td>
          <td>
            <?
              db_input("v70_sequencial",  4, $Iv70_sequencial, true, "text", 4, "onchange='js_pesquisaprocessoforo(false);'");
              db_input("v70_codforo",  40, $Iv70_codforo,  true, "text", 3, "");
            ?>
          </td>
        </tr>
      </table>
    </fieldset> 
    <input type="button" id="processar"  value="Procesar" onclick="return js_processar();" onmouseover="js_pesquisaprocessoforo(false);">
  </form>
<? 
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisaprocessoforo(mostra) {

	  if (mostra == true) {

		  var sUrl = 'func_processoforo.php?lAnuladas=false&funcao_js=parent.js_mostraprocessoforo1|v70_sequencial|v70_codforo';
	    js_OpenJanelaIframe('', 'db_iframe_processoforo', sUrl, 'Pesquisa', true);
	  } else {

	    if (document.form1.v70_sequencial.value != '') {
		    
	      var sUrl = 'func_processoforo.php?pesquisa_chave='+document.form1.v70_sequencial.value+'&funcao_js=parent.js_mostraprocessoforo'+'&lAnuladas=false'; 
	      js_OpenJanelaIframe('', 'db_iframe_processoforo', sUrl, 'Pesquisa', false);
	    }
	  }
	}

function js_mostraprocessoforo(chave,erro,chave2){

  document.form1.v70_codforo.value = chave;
  $('v70_codforo').value = chave2;
  if(erro==true){
    document.form1.v70_codforo.focus();
    document.form1.v70_codforo.value = '';
    $('v70_codforo').value = chave;
  }
  db_iframe_processoforo.hide();
}

function js_mostraprocessoforo1(chave1,chave2){
  document.form1.v70_sequencial.value = chave1;
  document.form1.v70_codforo.value = chave2;
  db_iframe_processoforo.hide();
}

function js_processar() {
	if ( document.form1.v70_sequencial.value == "" || document.form1.v70_codforo.value == "") {
		alert(_M('tributario.juridico.jur4_processoforopartilhacusta001.informe_processo'));
		return false; 
	}	

	location.href = "jur4_processoforopartilhacusta002.php?v70_sequencial="+document.form1.v70_sequencial.value+"&v70_codforo="+document.form1.v70_codforo.value;
}
</script>
<script>

$("v70_sequencial").addClassName("field-size2");
$("v70_codforo").addClassName("field-size7");

</script>