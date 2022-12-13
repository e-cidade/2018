<?
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$oDaoAtoLegal  = db_utils::getdao("atolegal");
$oDaoAtoEscola = db_utils::getdao("atoescola");
$db_opcao      = 1;
$db_botao      = true;

if (isset($incluir)) {
	
  db_inicio_transacao();
  $oDaoAtoLegal->incluir($ed05_i_codigo);
  $oDaoAtoEscola->ed19_i_ato    = $oDaoAtoLegal->ed05_i_codigo;
  $oDaoAtoEscola->ed19_i_escola = db_getsession("DB_coddepto");
  $oDaoAtoEscola->incluir(null);
  db_fim_transacao();

  $db_botao = false;
   
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
     <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Inclusão de Ato Legal</b></legend>
       <?include("forms/db_frmatolegal.php");?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<?
if (isset($incluir)) {
	
  if ($oDaoAtoLegal->erro_status == "0") {
  	
    $oDaoAtoLegal->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($oDaoAtoLegal->erro_campo != "") {
    	
      echo "<script> document.form1.".$oDaoAtoLegal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAtoLegal->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $oDaoAtoLegal->erro(true,false); 
    ?>
    <script>
     parent.document.formaba.a2.disabled = false;
     location.href='edu1_atolegal002.php?chavepesquisa=<?=$oDaoAtoLegal->ed05_i_codigo?>';
    </script>
   <?
   
  }
  
}
?>