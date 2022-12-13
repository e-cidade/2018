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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if(!isset($abas)){
  echo "<script>location.href='itb1_itbi005.php?db_opcao=2'</script>";
  exit;
}
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_itbi_classe.php");
include("classes/db_itburbano_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_itbirural_classe.php");
include("classes/db_itbiruralcaract_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clitbi = new cl_itbi;
$clitburbano = new cl_itburbano;
$clitbirural = new cl_itbirural;
$clitbiruralcaract = new cl_itbiruralcaract;
$db_opcao = 22;
$db_botao = false;
global $tipo;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $clitbi->alterar($it01_guia);
  if($tipo == "urbano"){
    $clitburbano->it05_guia = $it01_guia;
    $clitburbano->alterar($it01_guia);
  }elseif($tipo == "rural"){
    $clitbirural->it18_guia = $it01_guia;
    $clitbirural->alterar($it01_guia);
    if(isset($codigo) && isset($valor) && $codigo != "" && $valor != ""){
      $cod = split(",",$codigo);
      $val = split(",",$valor);
      for($i=0;$i<sizeof($cod);$i++){
        $clitbiruralcaract->it19_guia = $clitbi->it01_guia;
        $clitbiruralcaract->it19_codigo = $cod[$i];
        $clitbiruralcaract->it19_valor = $val[$i];
		$clitbiruralcaract->alterar($clitbi->it01_guia,$cod[$i]);
      }
    }
  }
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clitbi->sql_record($clitbi->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $result = $clitburbano->sql_record($clitburbano->sql_query($chavepesquisa)); 
   if($clitburbano->numrows > 0){
     db_fieldsmemory($result,0);
     $tipo = "urbano";
   }
   $result = $clitbirural->sql_record($clitbirural->sql_query($chavepesquisa)); 
   if($clitbirural->numrows > 0){
     db_fieldsmemory($result,0);
     $tipo = "rural";
     $result = $clitbiruralcaract->sql_record($clitbiruralcaract->sql_query($chavepesquisa,"","it19_codigo,it19_valor","j31_codigo")); 
     $codigo = "";
     $valor = "";
     $vir = "";
     if($clitbiruralcaract->numrows > 0){
       for($x=0;$x < $clitbiruralcaract->numrows;$x++){
         db_fieldsmemory($result,$x);
	 $codigo .= $vir.$it19_codigo;
	 $valor .= $vir.$it19_valor;
	 $vir = ",";
       }
     }
   }
   $db_botao = true;
    echo "<script>
           // parent.iframe_comp.location.href = 'itb1_itbicgm001.php?it02_guia=".$chavepesquisa."'; 
           // parent.document.formaba.comp.disabled = false; 

            parent.iframe_dados.location.href = 'itb1_itbidadosimovel001.php?it22_itbi=".$chavepesquisa."'; 
            parent.document.formaba.dados.disabled = false; 

            parent.iframe_transm.location.href = 'itb1_itbinome001.php?tiponome=t&it03_guia=".$chavepesquisa."'; 
            parent.document.formaba.transm.disabled = false; 
	    
            parent.iframe_compnome.location.href = 'itb1_itbinomecomp001.php?tiponome=c&it03_guia=".$chavepesquisa."'; 
            parent.document.formaba.compnome.disabled = false; 
			
            parent.iframe_constr.location.href = 'itb1_itbiconstr001.php?it08_guia=".$chavepesquisa."'; 
            parent.document.formaba.constr.disabled = false; 
			
            parent.iframe_old.location.href = 'itb1_itbipropriold001.php?it20_guia=".$chavepesquisa."'; 
            parent.document.formaba.old.disabled = false; 
          </script>";
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
	include("forms/db_frmitbi.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clitbi->erro_status=="0"){
    $clitbi->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clitbi->erro_campo!=""){
      echo "<script> document.form1.".$clitbi->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clitbi->erro_campo.".focus();</script>";
    };
  }else{
    $clitbi->erro(true,false);
    echo "<script>
            parent.iframe_itbi.location.href = 'itb1_itbi002.php?chavepesquisa=".$it01_guia."&abas=1'; 
            parent.document.formaba.comp.disabled = false; 
            parent.document.formaba.compnome.disabled = false; 
            parent.document.formaba.old.disabled = false; 
            parent.mo_camada('comp'); 
          </script>";
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>