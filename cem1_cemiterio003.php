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
include("classes/db_cemiterio_classe.php");
include("classes/db_cemiteriocgm_classe.php");
include("classes/db_cemiteriorural_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clcemiterio = new cl_cemiterio;
$clcemiteriocgm   = new cl_cemiteriocgm;
$clcemiteriorural = new cl_cemiteriorural;

$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  if($tp == 1){
   $clcemiteriocgm->excluir($cm15_i_cemiterio);
   $clcemiterio->excluir($cm15_i_cemiterio);
  }else if($tp == 2){
   $clcemiteriorural->excluir($cm16_i_cemiterio);
   $clcemiterio->excluir($cm16_i_cemiterio);
  }
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   if($tp == 2){
    $result = $clcemiteriorural->sql_record($clcemiteriorural->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
   }else{
    $result = $clcemiteriocgm->sql_record($clcemiteriocgm->sql_query($chavepesquisa));
    db_fieldsmemory($result,0);
   }
   $db_botao = true;
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
<table  width="790" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC">
    <form>
    <table>
     <tr><td align="center">Tipo de Cemitério</td></tr>
     <tr>
      <td>
       <?
         $x = array('0'=>'Selecione','1'=>'Urbano','2'=>'Rural');
         db_select('tp',$x,true,1,"onchange='submit()'");
       ?>
      </td>
     </tr>
     </form>
    </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC" >
     <?
      if(@$tp == 1){
       include("forms/db_frmcemiteriocgm.php");
      }else if(@$tp == 2){
       include("forms/db_frmcemiteriorural.php");
      }
     ?>
     </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  if($clcemiterio->erro_status=="0"){
    $clcemiterio->erro(true,false);
  }else{
    $clcemiterio->erro(true,true);
  };
};
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>