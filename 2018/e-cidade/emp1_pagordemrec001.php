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
include("classes/db_pagordemrec_classe.php");
include("classes/db_pagordemele_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clpagordemrec = new cl_pagordemrec;
$clpagordemele = new cl_pagordemele;
    //rotina que traz o valor da ordem 
     $tot_vlrpag  = '0.00';
     $tot_vlranu = '0.00';
     $tot_valor  = '0.00';

     $result  = $clpagordemele->sql_record($clpagordemele->sql_query_file($e52_codord)); 
     $numrows = $clpagordemele->numrows;
     for($i=0; $i<$numrows; $i++){
       db_fieldsmemory($result,$i);
       $tot_valor  += $e53_valor ;
       $tot_vlrpag += $e53_vlrpag;
       $tot_vlranu += $e53_vlranu;
     }
     $vlrdis=($tot_valor - $tot_vlrpag - $tot_vlranu );  ///quando estiver alterando o disponivel será o disponiv

if(isset($alterar) || isset($excluir) || isset($incluir)){
  if($e52_valor>$vlrdis){
    $sqlerro=true;
    $erro_msg = "Valor digitado é maior que o saldo disponivel da ordem!";
  }else{
    $sqlerro=false;
  }
  $clpagordemrec->e52_codord = $e52_codord ;
  $clpagordemrec->e52_receit = $e52_receit ;
  $clpagordemrec->e52_valor  = $e52_valor  ;
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clpagordemrec->incluir($e52_codord,$e52_receit);
    $erro_msg= $clpagordemrec->erro_msg;
    if($clpagordemrec->erro_status==0){
     $sqlerro=false;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clpagordemrec->alterar($e52_codord,$e52_receit);
    $erro_msg= $clpagordemrec->erro_msg;
    if($clpagordemrec->erro_status==0){
     $sqlerro=false;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clpagordemrec->excluir($e52_codord,$e52_receit);
    $erro_msg= $clpagordemrec->erro_msg;
    if($clpagordemrec->erro_status==0){
     $sqlerro=false;
    }
    db_fim_transacao($sqlerro);
  }  
}else if(isset($opcao)){
   $result = $clpagordemrec->sql_record($clpagordemrec->sql_query($e52_codord,$e52_receit)); 
   db_fieldsmemory($result,0);
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
	include("forms/db_frmpagordemrec.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
     db_msgbox($erro_msg); 
    if($clpagordemrec->erro_campo!=""){
      echo "<script> document.form1.".$clpagordemrec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpagordemrec->erro_campo.".focus();</script>";
    }
}
?>