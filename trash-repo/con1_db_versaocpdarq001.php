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
include("classes/db_db_versaocpdarq_classe.php");
include("dbforms/db_funcoes.php");
//db_postmemory($HTTP_SERVER_VARS,2);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_versaocpdarq = new cl_db_versaocpdarq;
//$db_opcao = 1;
$db_botao = true;
/*
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Incluir"){
  db_inicio_transacao();
  $cldb_versaocpdarq->incluir($db34_codarq);
  db_fim_transacao();
  unset($db34_descr);
  unset($db34_obs);
  unset($db34_arq);
  echo "<script>
       
        parent.document.form1.selid.options[parent.document.form1.selid.options.length] = new Option('".$cldb_versaocpdarq->db34_codarq."','".$cldb_versaocpdarq->db34_codarq."') ;
	
	</script>";
}
*/
$sqlerro = false;
if($db_opcao =='Incluir'){
  if($sqlerro==false){
    $cldb_versaocpdarq->db34_codarq = $db34_codarq;
    db_inicio_transacao();
    $cldb_versaocpdarq->incluir($db34_codarq);
    $erro_msg = $cldb_versaocpdarq->erro_msg;
    if($cldb_versaocpdarq->erro_status==0){
      $sqlerro=true;
    }else{
      unset($db34_descr);
      unset($db34_obs);
      unset($db34_arq);
      echo "<script>
       
            parent.document.form1.selid.options[parent.document.form1.selid.options.length] = new Option('".$cldb_versaocpdarq->db34_descr."','".$cldb_versaocpdarq->db34_codarq."') ;
	
  	    </script>";
    }
    
    db_fim_transacao($sqlerro);
  }
}else if($db_opcao == 'Alterar'){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_versaocpdarq->alterar($db34_codarq);
    $erro_msg = $cldb_versaocpdarq->erro_msg;
    if($cldb_versaocpdarq->erro_status==0){
      $sqlerro=true;
    }else{
      echo "<script>
	    for(x=0;x<parent.document.form1.selid.options.length;x++){
	      var codarq = ".$db34_codarq.";
	      var descrarq = '".$db34_descr."';
	      
	      if(parent.document.form1.selid.options[x].value == codarq){
                 parent.document.form1.selid.options[x].text = descrarq ;
		 break;
              }
            }
  	    </script>";
      unset($db34_descr);
      unset($db34_obs);
      unset($db34_arq);
    }
    db_fim_transacao($sqlerro);
  }
}else if($db_opcao == 'Excluir'){
  if($sqlerro==false){
    db_inicio_transacao();
    $cldb_versaocpdarq->excluir($db34_codarq);
    $erro_msg = $cldb_versaocpdarq->erro_msg;
    if($cldb_versaocpdarq->erro_status==0){
      $sqlerro=true;
    }else{
      echo "<script>
	    for(x=0;x<parent.document.form1.selid.options.length;x++){
	      codarq = ".$db34_codarq.";
	      descrarq = ".$db34_descr.";
	      if(parent.document.form1.selid.options[x].value == codarq){
                 parent.document.form1.selid.options[x] = null ;
		 break;
              }
            }
  	    </script>";
    }
      unset($db34_descr);
      unset($db34_obs);
      unset($db34_arq);
  
    db_fim_transacao($sqlerro);
  }
}elseif(isset($opcao)){
   $result = $cldb_versaocpdarq->sql_record($cldb_versaocpdarq->sql_query($db34_codarq));
   if($result!=false && $cldb_versaocpdarq->numrows>0){
     db_fieldsmemory($result,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_versaocpdarq.php");
	?>
    </center>
   </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Incluir"){
  if($cldb_versaocpdarq->erro_status=="0"){
    $cldb_versaocpdarq->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cldb_versaocpdarq->erro_campo!=""){
      echo "<script> document.form1.".$cldb_versaocpdarq->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_versaocpdarq->erro_campo.".focus();</script>";
    };
  }else{
    $cldb_versaocpdarq->erro(true,false);
  };
};
?>