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
include("classes/db_procfaixaetaria_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprocfaixaetaria = new cl_procfaixaetaria;
$db_botao = true;
$db_opcao = 1;
if(isset($incluir)){
  db_inicio_transacao();
  $clprocfaixaetaria->incluir($sd16_i_codigo);
  db_fim_transacao();
}
if(isset($alterar)){
 db_inicio_transacao();
 $clprocfaixaetaria->alterar($sd16_i_codigo);
 db_fim_transacao();
}
if(isset($excluir)){
 db_inicio_transacao();
 $clprocfaixaetaria->excluir($sd16_i_codigo);
 db_fim_transacao();
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
        include("forms/db_frmprocfaixaetaria.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd16_i_procedimento",true,1,"sd16_i_procedimento",true);
</script>
<?
if(isset($incluir)){
  if($clprocfaixaetaria->erro_status=="0"){
    $clprocfaixaetaria->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprocfaixaetaria->erro_campo!=""){
      echo "<script> document.form1.".$clprocfaixaetaria->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocfaixaetaria->erro_campo.".focus();</script>";
    }
  }else{
    $clprocfaixaetaria->pagina_retorno = "sau1_procfaixaetaria001.php?sd16_i_procedimento=".$sd16_i_procedimento."'";
    $clprocfaixaetaria->erro(true,true);
  }
}
if(isset($alterar)){
 if($clprocfaixaetaria->erro_status=="0"){
  $clprocfaixaetaria->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprocfaixaetaria->erro_campo!=""){
   echo "<script> document.form1.".$clprocfaixaetaria->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprocfaixaetaria->erro_campo.".focus();</script>";
  };
 }else{
  $clprocfaixaetaria->pagina_retorno = "sau1_procfaixaetaria001.php?sd16_i_procedimento=".$sd16_i_procedimento."'";
  $clprocfaixaetaria->erro(true,true);
 };
}
if(isset($excluir)){
 if($clprocfaixaetaria->erro_status=="0"){
  $clprocfaixaetaria->erro(true,false);
 }else{
  $clprocfaixaetaria->pagina_retorno = "sau1_procfaixaetaria001.php?sd16_i_procedimento=".$sd16_i_procedimento."'";
  $clprocfaixaetaria->erro(true,true);
 };
}
?>