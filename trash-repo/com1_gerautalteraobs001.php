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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_carrega('<?=$campo?>');">
<form name="form1" method="post">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <table border="0" align="center">
        <tr height="60">
          <td nowrap><b>Material:</b></td>
          <td nowrap>
          <?
            db_input("codmater", 10, 0, true, "text", 3);
            db_input("descrmater", 60, 0, true, "text", 3);
          ?>
          </td>
          </tr>
          <tr><td nowrap colspan="2">&nbsp;</td></tr>
          <tr>
            <td nowrap><b>Observacao:</b></td>
            <td nowrap>
            <?
              db_textarea($campo,10,80,0,true,"text",2);
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap colspan="2" align="center"><input type="button" value="Alterar" onClick="return js_atualizar('<?=$campo?>');"></td>
          </tr>
      </table></td>
    </center>
</table>
</form>
<script>
  function js_carrega(campo){
    var obj         = eval("parent.iframe_solicitem.document.form1."+campo);
    var resumo_novo = eval("document.form1."+campo);
   
    resumo_novo.value = obj.value;
  }

  function js_atualizar(campo){
    var obj         = eval("parent.iframe_solicitem.document.form1."+campo);
    var resumo_novo = eval("document.form1."+campo);

    if (resumo_novo.value == ""){
         alert("Resumo deve ser preenchido. Verifique");
         return false;
    }

    obj.value = resumo_novo.value;

    parent.db_iframe_geraut.hide();
  }
</script>
</body>
</html>