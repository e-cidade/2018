<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$oRotulo  = new rotulocampo;
$oRotulo->label('ob16_codobrasenvio');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js"); 
    ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <BR>
    <BR>
    <BR>
    <BR>
    <center>
      <form name="form1" id="form1">
      
        <fieldset style="width: 500px;">
          <legend><strong>Exportação de Logs de Acesso: </strong></legend>
	        <BR>
		      <table border="0" cellspacing="0" cellpadding="0">
          
		        <tr> 
		          <td><b>Período:</b></td>
		          <td> 
		            <?
		             db_inputdata('data_inicial', "", "", "", true, 'text', 1);
		             echo "Á";
		             db_inputdata('data_final'  , "", "", "", true, 'text', 1);
		            ?>
		          </td>
		        </tr>
            
		      </table>
          
	        <BR>
        </fieldset>
      </form>
      <br>
      <input name="reemissao"   type="button"  value="Processsar"  onclick="js_emiteTXT();">      
    </center>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>

<script>

function js_emiteTXT() {

  
  if ($F('data_inicial') == "" && $F('data_final') == "") {
    alert("O preenchimento das datas é obrigatório.");
    return;
  } 

  var sQueryString = "?dtInicial=" + js_formatar($F('data_inicial'), "d");
      sQueryString+= "&dtFinal="   + js_formatar($F('data_final')  , "d");


  js_OpenJanelaIframe("","windowRelatorio","con4_exportalogacesso002.php" + sQueryString,"Pesquisa",true);
  
  //oJanela = window.open("con4_exportalogacesso002.php" + sQueryString,
  //                      "windowRelatorio",
  //                     "width="+(screen.availWidth-5)+",height="+(screen.availHeight-40)+",scrollbars=1,location=0");
  //oJanela.moveTo(0,0);
}
function js_retorno(sArquivo) {
  
 windowRelatorio.hide();
 js_montarlista(sArquivo  + "# Download do Arquivo - " + sArquivo, 'form1')
}
</script>