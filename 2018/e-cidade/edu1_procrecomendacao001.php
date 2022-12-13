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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_procrecomendacao_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clprocrecomendacao = new cl_procrecomendacao;
$db_opcao = 1;
$db_botao = true;
$result1 = $clprocrecomendacao->sql_record($clprocrecomendacao->sql_query("","ed51_i_recomendacao as recjacad",""," ed51_i_procedimento = $ed51_i_procedimento"));
if($clprocrecomendacao->numrows>0){
 $sep = "";
 $rec_cad = "";
 for($c=0;$c<$clprocrecomendacao->numrows;$c++){
  db_fieldsmemory($result1,$c);
  $rec_cad .= $sep.$recjacad;
  $sep = ",";
 }
}else{
 $rec_cad = 0;
}
if(isset($incluir)){
 db_inicio_transacao();
 $clprocrecomendacao->incluir($ed51_i_codigo);
 db_fim_transacao();
}
if(isset($alterar)){
  db_inicio_transacao();
  $db_opcao = 2;
  $clprocrecomendacao->alterar($ed51_i_codigo);
  db_fim_transacao();
}
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clprocrecomendacao->excluir($ed51_i_codigo);
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:90%"><legend><b>Recomendações do  Procedimento de Avaliação <?=$ed40_c_descr?></b></legend>
    <?include("forms/db_frmprocrecomendacao.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
 if($clprocrecomendacao->erro_status=="0"){
  $clprocrecomendacao->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprocrecomendacao->erro_campo!=""){
   echo "<script> document.form1.".$clprocrecomendacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clprocrecomendacao->erro_campo.".focus();</script>";
  };
 }else{
  $clprocrecomendacao->erro(true,true);
 };
};
if(isset($alterar)){
  if($clprocrecomendacao->erro_status=="0"){
    $clprocrecomendacao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprocrecomendacao->erro_campo!=""){
      echo "<script> document.form1.".$clprocrecomendacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocrecomendacao->erro_campo.".focus();</script>";
    };
  }else{
    $clprocrecomendacao->erro(true,true);
  };
};
if(isset($excluir)){
  if($clprocrecomendacao->erro_status=="0"){
    $clprocrecomendacao->erro(true,false);
  }else{
    $clprocrecomendacao->erro(true,true);
  };
};
if(isset($cancelar)){
 echo "<script>location.href='".$clprocrecomendacao->pagina_retorno."'</script>";
}
?>