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
include("classes/db_cadvencdesc_classe.php");
include("classes/db_cadvencdescban_classe.php");
include("classes/db_cadvenc_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clcadvencdesc = new cl_cadvencdesc;
$clcadvencdescban = new cl_cadvencdescban;
$clcadvenc = new cl_cadvenc;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $db_opcao = 3;
  $clcadvencdesc->excluir($q92_codigo);
  if($clcadvencdesc->erro_status==0){
    $sqlerro=true; 
  }
  if(!$sqlerro){
    $result01 = $clcadvencdescban->sql_record($clcadvencdescban->sql_query_file($q92_codigo)); 
    if($clcadvencdescban->numrows>0){
      $clcadvencdescban->q93_codigo=$q92_codigo;
      $clcadvencdescban->excluir($q92_codigo);
      if($clcadvencdescban->erro_status=='0'){
         $sqlerro=true;
      }
    }
  } 
  $clcadvenc->q82_codigo=$q92_codigo;
  $clcadvenc->excluir($q92_codigo);
  if($clcadvenc->erro_status==0){
    $sqlerro=true; 
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clcadvencdesc->sql_record($clcadvencdesc->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result01 = $clcadvencdescban->sql_record($clcadvencdescban->sql_query($q92_codigo,"q93_cadban as k15_codigo,nomebco")); 
   if($clcadvencdescban->numrows>0){
     db_fieldsmemory($result01,0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcadvencdescalt.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($chavepesquisa)){
     echo "
           <script>
              function js_xy(){
                parent.document.formaba.cadvenc.disabled=false;\n
		top.corpo.iframe_cadvenc.location.href='iss1_cadvenc004.php?db_opcaoal=3&q82_codigo=$chavepesquisa';\n
                //parent.mo_camada('cadvenc');
              }
              js_xy();
           </script>
         ";
}


if(isset($excluir)){
  if($clcadvencdesc->erro_status=="0"){
    $clcadvencdesc->erro(true,false);
  }else{
      $clcadvencdesc->erro(true,false);
           echo "<script>
             parent.location.href='iss1_cadvencdesc006.php';
          </script>";
  } 
}  
  if($db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>