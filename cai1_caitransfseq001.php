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
include("classes/db_caitransfseq_classe.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$clcaitransfseq = new cl_caitransfseq;

$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $clcaitransfseq->incluir($k94_seqtransf);
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
<style type='TEXT/CSS'>
.btn {
  height:20px;
  width:220px;
  border:1px solid;
}  
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name=form1 action="">
<table height="100%" width="100%" border="1" cellspacing="0" cellpadding="0">
 <tr> 
   <td height="10%" align="left" valign="top" bgcolor="#CCCCCC">   
   <table width=100%  height=100% border=0>
     <tr>
       <td colspan=4 align=left><h3>Controle de Transferências </h3></td>
     </tr>     
     <tr>
       <td width=20%><input class=btn type=submit name='efetuada' value='Solicitações Efetuadas' title='Solicitações que eu efetuei'></td>
       <td width=20%><input class=btn type=submit name='novo' value='Nova Solicitação de Transferência'  title='Novo Lançamento' > </td>   
       <td> &nbsp;</td>
       <td> &nbsp;</td>

     </tr>  
   </table> 
   </form>
   </td>
 </tr>
 <tr> 
 <td height="90%" valign=top>
   <?
    $tipo='';
    if (isset($notif)){
       $tipo='notif';
    } elseif (isset($efetuada)){
       $tipo='efetuada';
       ?><iframe name=iframe_transf src="cai1_caitransf_iframe.php?consulta=<?=$tipo?>" width="100%" height="400" border=2 ></iframe><?
    } elseif (isset($liberada)){
       $tipo='liberada';
    }  elseif (isset($novo)){
       ?><iframe name=iframe_transf src="cai1_caitransfseq004.php" width="100%" height="400" border=2 ></iframe><?

    }  
   ?>

 </td>
</tr> 
</table>



<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_lanca_transf(){
   js_OpenJanelaIframe('top.corpo','db_iframe_transfseq','cai1_caitransfseq004.php','Pesquisa',true,'30');

}

</script>
</html>
<?
if(isset($incluir)){
  if($clcaitransfseq->erro_status=="0"){
    $clcaitransfseq->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcaitransfseq->erro_campo!=""){
      echo "<script> document.form1.".$clcaitransfseq->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcaitransfseq->erro_campo.".focus();</script>";
    };
  }else{
    $clcaitransfseq->erro(true,true);
  };
};
?>