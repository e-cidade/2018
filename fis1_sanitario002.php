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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_sanitario_classe.php");
include("classes/db_sanitarioinscr_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsanitario = new cl_sanitario;
$clsanitarioinscr = new cl_sanitarioinscr;

$db_opcao = 22;
$db_botao = true;
if(!isset($y80_codsani) && !isset($chavepesquisa)){
  db_redireciona("fis1_sanitario001.php?db_opcao=2&entrar=1");
  exit;
}
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $clsanitario->alterar($y80_codsani);
  
  if($q02_inscr != ""){
	$clsanitarioinscr->sql_record($clsanitarioinscr->sql_query_file($y80_codsani)); 
	$clsanitarioinscr->y18_codsani = $y80_codsani;
    $clsanitarioinscr->y18_inscr = $q02_inscr;
	if($clsanitarioinscr->numrows > 0){
 	 $clsanitarioinscr->alterar($y80_codsani);
	}else{
     $clsanitarioinscr->incluir($y80_codsani,$q02_inscr);
	}
	if($clsanitarioinscr->erro_status==0){
	   $sqlerro=true;
	   $msg = "Não foi possível realizar a Alteração!\\n A Inscrição Municipal informada pode estar ligada a outro Alvará Sanitário!\\n\\n";
	   $clsanitario->erro_msg = $msg;
	}
  }
  
  $clsanitario->erro(true,false);
   ?>
    <script>
     function js_src(){
      parent.iframe_sanitario.location.href='fis1_sanitario002.php?chavepesquisa=<?=$clsanitario->y80_codsani?>&y83_codsani=x';
      parent.iframe_observacoes.location.href='fis1_saniatividade006.php?y83_codsani=<?=$clsanitario->y80_codsani?>';
      parent.iframe_saniatividade.location.href='fis1_saniatividade001.php?y83_codsani=<?=$clsanitario->y80_codsani?>';
      parent.iframe_resptecnico.location.href='fis1_resptecnico001.php?y22_codsani=<?=$clsanitario->y80_codsani?>';
      parent.iframe_calculo.location.href='fis1_sanicalc001.php?y80_codsani=<?=$clsanitario->y80_codsani?>';
     }
     js_src();
    </script>
   <?
  db_fim_transacao();
}else if(isset($chavepesquisa)){
  echo "
       <script>
       function js_src(){
         parent.iframe_observacoes.location.href='fis1_sanitario006.php?y80_codsani=$chavepesquisa';\n
         parent.iframe_saniatividade.location.href='fis1_saniatividade001.php?y83_codsani=$chavepesquisa';\n
         parent.iframe_resptecnico.location.href='fis1_resptecnico001.php?y22_codsani=".$chavepesquisa."';\n
         parent.iframe_calculo.location.href='fis1_sanicalc001.php?y80_codsani=".$chavepesquisa."';\n
       }
       js_src();
       </script>
   ";
   $db_opcao = 2;
   $result  = $clsanitario->sql_record($clsanitario->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   global $db_botao;
   if($y80_dtbaixa != ""){
     $db_opcao = 22;
     $db_botao = false;
   }else{
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td height="430" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmsanitario.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
echo "<script>
        if(document.form1.y80_codsani.value!=\"\"){
          parent.document.formaba.observacoes.disabled = false;
          parent.document.formaba.saniatividade.disabled = false;
          parent.document.formaba.resptecnico.disabled = false;
          parent.document.formaba.calculo.disabled = false;
        }else{
          parent.document.formaba.observacoes.disabled = true;
          parent.document.formaba.saniatividade.disabled = true;
          parent.document.formaba.resptecnico.disabled = true;
          parent.document.formaba.calculo.disabled = true;
	  }
          
      </script>  ";
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  if($clsanitario->erro_status=="0"){
    $clsanitario->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clsanitario->erro_campo!=""){
      echo "<script> document.form1.".$clsanitario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clsanitario->erro_campo.".focus();</script>";
    };
  }else{
    $clsanitario->erro(true,false);
  };
};
if(@$y80_codsani == ""){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>