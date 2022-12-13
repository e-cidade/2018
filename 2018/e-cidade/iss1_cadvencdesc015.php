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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clcadvencdesc = new cl_cadvencdesc;
$clcadvencdescban = new cl_cadvencdescban;
$db_opcao = 22;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clcadvencdesc->alterar($q92_codigo);
  if($clcadvencdescban->erro_status=='0'){
      $sqlerro=true;
  }
  if($k15_codigo>0){
    $result01 = $clcadvencdescban->sql_record($clcadvencdescban->sql_query_file($q92_codigo)); 
    if($clcadvencdescban->numrows>0){
      $clcadvencdescban->q93_codigo=$q92_codigo;
      $clcadvencdescban->q93_cadban=$k15_codigo;
      $clcadvencdescban->alterar($q92_codigo);
      if($clcadvencdescban->erro_status=='0'){
          $sqlerro=true;
      }
    }else{
      $clcadvencdescban->q93_codigo=$q92_codigo;
      $clcadvencdescban->q93_cadban=$k15_codigo;
      $clcadvencdescban->incluir($q92_codigo);
      //$clcadvencdescban->erro(true,false);
      if($clcadvencdescban->erro_status=='0'){
          $sqlerro=true;
      }
    }  
  }else{
   $result01 = $clcadvencdescban->sql_record($clcadvencdescban->sql_query_file($q92_codigo)); 
   if($clcadvencdescban->numrows>0){
     $clcadvencdescban->q93_codigo=$q92_codigo;
     $clcadvencdescban->excluir($q92_codigo);
     if($clcadvencdescban->erro_status=='0'){
        $sqlerro=true;
     }
   }
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $result = $clcadvencdesc->sql_record($clcadvencdesc->sql_query($chavepesquisa,"*")); 
   db_fieldsmemory($result,0);
   $result01 = $clcadvencdescban->sql_record($clcadvencdescban->sql_query($q92_codigo,"q93_cadban as k15_codigo,nomebco")); 
   if($clcadvencdescban->numrows>0){
     db_fieldsmemory($result01,0);
   }  
}
if((isset($tavainlcu) && $tavainclu==true) || isset($chavepesquisa) || isset($alterar)){
  $db_opcao=2;
  $db_botao = true;
}else{
  $db_opcao = 22;
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
            		top.corpo.iframe_cadvenc.location.href='iss1_cadvenc004.php?q82_codigo=$chavepesquisa';\n
                //parent.mo_camada('cadvenc');
              }
              js_xy();
           </script>
         ";
}
if(isset($alterar)){
  if($clcadvencdesc->erro_status=="0"){
    $clcadvencdesc->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcadvencdesc->erro_campo!=""){
      echo "<script> document.form1.".$clcadvencdesc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcadvencdesc->erro_campo.".focus();</script>";
    }
  }else{
    $clcadvencdesc->erro(true,false);
  }
}  

if($db_opcao==22){
   echo "<script>document.form1.pesquisar.click();</script>";
 }
?>