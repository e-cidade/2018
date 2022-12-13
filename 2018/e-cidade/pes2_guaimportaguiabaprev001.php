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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>


function js_erro(msg){
  top.corpo.db_iframe_bbconverte.hide();
  alert(msg);
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" enctype="multipart/form-data">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
    <td align="left" nowrap title="Indique o Arquivo a Importar Guaibaprev/C�mara Vereadores" >
      <strong>Indique o Arquivo a Importar Guaibaprev/C�mara Vereadores &nbsp;&nbsp;</strong> 
    </td>
    <td >&nbsp;</td>
      </tr>
      <tr>

	<td nowrap align='left'>
  <?
  db_input('AArquivo',46,"",true,'file',1,"");
  ?>
	</td>
         <td >&nbsp;</td>
      </tr>
	
      <tr>
       	<td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="submit" value="Gera">
        </td>
      </tr>
<?
// testa se esta setado o bota de carregamento e se o input nao esta vazio
if(isset($gera) && $AArquivo != ""){
  
  // Nome do arquivo tempor�rio gerado no /tmp
  $nomearquivo =  $_FILES["AArquivo"]["name"];
  // Nome do arquivo tempor�rio gerado no /tmp
  $nometmp     = $_FILES["AArquivo"]["tmp_name"];
  // Faz um upload do arquivo para o local especificado
  move_uploaded_file($nometmp,$nomearquivo) or $erro_msg = "ERRO: Contate o suporte.";
  echo "<script> js_OpenJanelaIframe('top.corpo','db_iframe_bbconverte','pes2_guaimportaguiabaprev002.php?AArquivo=$nomearquivo','Gerando Importa��o',true);</script>";
}
?>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>