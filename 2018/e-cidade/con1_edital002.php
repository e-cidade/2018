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
include("classes/db_edital_classe.php");
include("classes/db_editalrua_classe.php");
include("classes/db_editalproj_classe.php");
include("classes/db_editaldoc_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cledital = new cl_edital;
$cleditalrua = new cl_editalrua;
$cleditalproj = new cl_editalproj;
$cleditaldoc  = new cl_editaldoc;
$db_opcao = 22;
$db_botao = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  $sqlerro=false;
  db_inicio_transacao();
  $db_opcao = 2;
  $cledital->d01_idlog=db_getsession("DB_id_usuario");
  $cledital->alterar($d01_codedi);
  if($cledital->erro_status=='0'){
     $sqlerro=true;
  }
  
  $codedi=$d01_codedi;
  $cleditalproj->d10_codedi=$codedi;
  $cleditalproj->excluir($codedi);
  if($cleditalproj->erro_status=='0'){
     $sqlerro=true;
  }
  if(!$sqlerro){
    $dados=split("XX",$codigo);
    for($r=0; $r<sizeof($dados); $r++){
       if($dados[$r]!=""){
        $cleditalproj->d10_codedi=$codedi;
        $cleditalproj->d10_codigo=$dados[$r];
        $cleditalproj->incluir($codedi,$dados[$r]);
        if($cleditalproj->erro_status=='0'){
          $sqlerro=true;
          break;
        }
      }   
    }
  }
  
  if($sqlerro==false){
  	
    $cleditaldoc->excluir(null," d13_edital = $d01_codedi ");
    if ( $cleditaldoc->erro_status == '0' ) {
      $sqlerro = true;
      break;
    }
    
    if( isset( $d13_db_documento ) && $d13_db_documento != "" ) {    
      $cleditaldoc->d13_db_documento = $d13_db_documento;
      $cleditaldoc->d13_edital       = $cledital->d01_codedi;
      $cleditaldoc->incluir(null);
      if ( $cleditaldoc->erro_status == '0' ) {
        $sqlerro = true;
        break;
      }
      
    }
    
  }
  
   
  if($sqlerro==true){
    $db_opcao=22;
     $db_botao = true;
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $rsEditalDoc = $cleditaldoc->sql_record($cleditaldoc->sql_query(null,"*",null," d13_edital = {$chavepesquisa} ")); 
   if ($cleditaldoc->numrows > 0) {
   	db_fieldsmemory($rsEditalDoc,0); 
   }
  
   $result = $cledital->sql_record($cledital->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result05 = $cleditalrua->sql_record($cleditalrua->sql_query("","d02_contri,j14_nome as xj14_nome","","d02_codedi=$chavepesquisa")); 
   $numrows05=$cleditalrua->numrows;
   if($numrows05>0){
     $db_opcao = 22;
     $db_botao = true;
     $vir="";
     $contris="";
     for($f=0;$f<$numrows05;$f++){
       db_fieldsmemory($result05,$f);
       $contris.=$vir.$d02_contri."-".$xj14_nome;
       $vir=", ";
     }
   }else{
     $db_opcao = 2;
     $db_botao = true;
   }
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmeditalalt.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($cledital->erro_status=="0"){
  $cledital->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cledital->erro_campo!=""){
    echo "<script> document.form1.".$cledital->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cledital->erro_campo.".focus();</script>";
  };
}else{
  $cledital->erro(true,true);
};
if(isset($contris) && $contris!=""){
  if($numrows05>1){
    db_msgbox("Edital está sendo utilizado nas contribuições $contris");
  }else{
    db_msgbox("Edital está sendo utilizado na contribuição $contris");
  }   
}  
?>