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
include("dbforms/db_classesgenericas.php");
$clcriaabas     = new cl_criaabas;
$arquivo2 = "sau1_procservicos001.php";
if($db_opcao==1){
 $arquivo1 = "sau1_procedimentos001.php";
}elseif($db_opcao==22){
 $arquivo1 = "sau1_procedimentos002.php";
}elseif($db_opcao==33){
 $arquivo1 = "sau1_procedimentos003.php";
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
     <?
      $clcriaabas->identifica = array("a1"=>"Cadastro","a2"=>"Serviços","a3"=>"Tipo de Atendimento","a4"=>"Grupo de Atendimento","a5"=>"Faixa Etária","a6"=>"Especialidade");
      $clcriaabas->src = array("a1"=>$arquivo1,"a2"=>$arquivo2,"a3"=>"sau1_proctipoatend001.php","a4"=>"sau1_procgrupoatend001.php","a5"=>"sau1_procfaixaetaria001.php","a6"=>"sau1_procespecialidades001.php");
      $clcriaabas->disabled   =  array("a2"=>"true","a3"=>"true","a4"=>"true","a5"=>"true","a6"=>"true");
      $clcriaabas->sizecampo  = array("a1"=>27,"a2"=>15,"a3"=>16,"a4"=>17,"a5"=>15,"a6"=>15);
      $clcriaabas->scrolling = "no";
      $clcriaabas->iframe_width = 770;
      $clcriaabas->cria_abas();
     ?>
     </td>
    </tr>
  </table>
  <form name="form1">
  </form>
  <?
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
  </html>