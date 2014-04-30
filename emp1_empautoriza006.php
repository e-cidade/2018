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
include("classes/db_empautoriza_classe.php");
include("classes/db_orcreserva_classe.php");
include("classes/db_orcreservaaut_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempautoriza = new cl_empautoriza;
$clorcreserva = new cl_orcreserva;
$clorcreservaaut = new cl_orcreservaaut;

if(isset($anular)){
  $sqlerro=false;
  db_inicio_transacao();
  //rotina que atualiza empautoriza
  $clempautoriza->e54_autori = $e54_autori;
  $clempautoriza->alterar($e54_autori);
  if($clempautoriza->erro_status==0){
    $sqlerro=true;
    $erro_msg=$clempautoriza->erro_msg;
  }else{
    $ok_msg=$clempautoriza->erro_msg;
  }
  /*rotina que exclui orcreserva e  aut */
  if($sqlerro==false ){
      $result=$clorcreservaaut->sql_record($clorcreservaaut->sql_query(null,"o83_codres","","o83_autori=$e54_autori")); 
      $num=$clorcreservaaut->numrows;
      if($num>0){
        db_fieldsmemory($result,0); 
	$clorcreservaaut->o83_codres=$o83_codres; 
	$clorcreservaaut->excluir($o83_codres);
	$erro_msg=$clorcreservaaut->erro_msg;
	if($clorcreservaaut->erro_status==0){
	  $sqlerro=true;
	}
      }	
  } 
  if($sqlerro==false){
    if($num>0){
      $clorcreserva->o80_codres=$o83_codres; 
      $clorcreserva->excluir($o83_codres);
      $erro_msg=$clorcreserva->erro_msg;
      if($clorcreserva->erro_status==0){
	$sqlerro=true;
      }
    }	
  }  
  /*final rotina que exclui do orcreserva e aut*/
  db_fim_transacao($sqlerro);
  if($sqlerro==true){
    $db_opcao = 1;
  }else{
    $db_opcao = 3;
  }
}else if(isset($reativar)){
  $sqlerro=false;
  db_inicio_transacao();
  
  //rotina que atualiza empautoriza
  $clempautoriza->e54_autori = $e54_autori;
  $clempautoriza->alterar($e54_autori);
  if($clempautoriza->erro_status==0){
    $sqlerro=true;
    $erro_msg=$clempautoriza->erro_msg;
  }else{
    $ok_msg=$clempautoriza->erro_msg;
  }

  db_fim_transacao($sqlerro);
  if($sqlerro==true){
    $db_opcao = 3;
  }else{
    $db_opcao = 1;
  }
}else if(isset($e54_autori)){
   $result = $clempautoriza->sql_record($clempautoriza->sql_query_file($e54_autori,"e54_anulad")); 
   db_fieldsmemory($result,0);
   if($e54_anulad!=0){  
     $db_opcao = 3;
   }else{
     $db_opcao = 1;
   }  
}
     $db_botao = true;
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmempautoriza_anulacao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($anular) || isset($reativar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    $db_botao=true;
  }else{
    db_msgbox($ok_msg);
    if(isset($reativar)){
      db_msgbox("Será necessário gerar reserva novamente!");
    }
    echo "
         <script>
      top.corpo.iframe_empautoriza.location.href='emp1_empautoriza005.php?chavepesquisa=$e54_autori';\n
            
         </script>
	 
    
    ";
  }
}
?>