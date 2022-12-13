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
include("classes/db_procedimentos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clprocedimentos = new cl_procedimentos;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clprocedimentos->alterar($sd09_i_codigo);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clprocedimentos->sql_record($clprocedimentos->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
   echo "<script>
          parent.mo_camada('a2');
          parent.document.formaba.a2.disabled = false;
          parent.iframe_a2.document.location.href='sau1_procservicos001.php?sd19_i_procedimento=$chavepesquisa';
          parent.document.formaba.a3.disabled = false;
          parent.iframe_a3.document.location.href='sau1_proctipoatend001.php?sd20_i_procedimento=$chavepesquisa';
          parent.document.formaba.a4.disabled = false;
          parent.iframe_a4.document.location.href='sau1_procgrupoatend001.php?sd17_i_procedimento=$chavepesquisa';
          parent.document.formaba.a5.disabled = false;
          parent.iframe_a5.document.location.href='sau1_procfaixaetaria001.php?sd16_i_procedimento=$chavepesquisa';
          parent.document.formaba.a6.disabled = false;
          parent.iframe_a6.document.location.href='sau1_procespecialidades001.php?sd18_i_procedimento=$chavepesquisa';
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
        <?
        include("forms/db_frmprocedimentos.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($clprocedimentos->erro_status=="0"){
    $clprocedimentos->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprocedimentos->erro_campo!=""){
      echo "<script> document.form1.".$clprocedimentos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocedimentos->erro_campo.".focus();</script>";
    }
  }else{
    $clprocedimentos->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","sd09_c_grupoproc",true,1,"sd09_c_grupoproc",true);
</script>