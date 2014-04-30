<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_proctransfer_classe.php");
include("classes/db_proctransferproc_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$clproctransfer = new cl_proctransfer;
$clproctransferproc = new cl_proctransferproc;

if(isset($cancel)){
  db_inicio_transacao();
    $sqlerro=false;
    $result_cont=$clproctransferproc->sql_record($clproctransferproc->sql_query_file($codtransfer));
    if ($clproctransferproc->numrows==$contador){
      $clproctransferproc->excluir(null,null,"p63_codtran=$codtransfer");  
      $erro = $clproctransferproc->erro_msg;
      if ($clproctransferproc->erro_status==0){
	$sqlerro=true;
      }
      if ($sqlerro==false){
	$clproctransfer->excluir($codtransfer);  
	$erro = $clproctransfer->erro_msg;
	if ($clproctransfer->erro_status==0){
	  $sqlerro=true;
	}
      }
    }else{
      $clproctransferproc->excluir(null,null,"p63_codtran=$codtransfer and p63_codproc in ($listaproc)");  
      $erro = $clproctransferproc->erro_msg;
      if ($clproctransferproc->erro_status==0){
	$sqlerro=true;
      }
    }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script>
</script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" style="margin-top: 25px" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
        <?
        include("forms/db_frmcanceltranspar.php");
        ?>
    </center>
<?
if (isset($cancel)){
    db_msgbox($erro);
    if($sqlerro==true){
      echo "<script> document.form1.".$clproctransferproc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clproctransferproc->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='pro4_canceltranspar001.php';</script>";
    }
}
?>

</body>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>