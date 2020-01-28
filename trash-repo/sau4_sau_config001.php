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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');
require_once('libs/db_app.utils.php');


db_postmemory( $HTTP_POST_VARS );

$oSauConfig = db_utils::getdao('sau_config_ext');
$db_opcao   = 1;

if (isset($alterar)) {

  db_inicio_transacao();
  $db_opcao = 2;
  $oSauConfig->alterar("1' or '1");
  db_fim_transacao();
  
} elseif (isset($incluir)) {
  
  db_inicio_transacao ();
  $oSauConfig->incluir ();
  db_fim_transacao ();
    db_msgbox($oSauConfig->erro_msg);

}

$sSql = $oSauConfig->sql_query_ext(null, '*', null, null);
$rs   = $oSauConfig->sql_record($sSql);

if ($oSauConfig->numrows > 0) {
  
  $db_opcao = 2;
  db_fieldsmemory($rs, 0);
  
}

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title> 
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load(" prototype.js, strings.js, webseller.js, scripts.js ");
    db_app::load(" estilos.css ");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=4">
    <center>
      <?
	  require_once("forms/db_frmsau_config.php");
	  ?>
    </center>
  </body>
</html>

<script>
//js_desabinc();
//js_desabexc();
</script>

<?

if ($db_opcao == 2) {
		
  for ($iI = 2; $iI <= 5; $iI++) {
    echo "<script> parent.document.getElementById('a$iI').rows[0].cells[0].childElements()[0].disabled = false; </script>  "; 
  }
  
}

if (isset($incluir) || isset($alterar)) {
  
  if ($oSauConfig->erro_status == "0") {
    db_msgbox($oSauConfig->erro_msg);
  }

}

?>