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
include("classes/db_veiccadcentral_classe.php");
include("classes/db_veiccadcentraldepart_classe.php");
include("classes/db_veicmotoristascentral_classe.php");
include("classes/db_veiccentral_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveiccadcentral        = new cl_veiccadcentral;
$clveiccadcentraldepart  = new cl_veiccadcentraldepart;
$clveicmotoristascentral = new cl_veicmotoristascentral;
$clveiccentral           = new cl_veiccentral;

$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  $erro_msg = "";
  $sqlerro  = false;

  db_inicio_transacao();


$res_verifica=$clveiccadcentral->sql_record($clveiccadcentral->sql_query_central("$ve36_sequencial","*",null,""));
  if ($clveiccadcentral->numrows>0){
    for ($i=0;$i<$clveiccadcentral->numrows;$i++){
         db_fieldsmemory($res_verifica,$i);
         if ($ve60_codigo!=null or $ve73_codigo!=null){
          $sqlerro  = true;
          $erro_msg = "já existe uma retirada ou abastecimento de um veículo desta central.";
          db_msgbox($erro_msg);
          break;
         }
     }
  }


  $res_veiccadcentraldepart = $clveiccadcentraldepart->sql_record($clveiccadcentraldepart->sql_query(null,"*",null,"ve37_veiccadcentral = $ve36_sequencial"));
 if ($clveiccadcentraldepart->numrows > 0){
    $clveiccadcentraldepart->excluir(null,"ve37_veiccadcentral = $ve36_sequencial");
    if ($clveiccadcentraldepart->erro_status == 0){
      $sqlerro  = true;
      $erro_msg = $clveiccadcentraldepart->erro_msg;
    }
  }

  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query(null,"*",null,"ve41_veiccadcentral = $ve36_sequencial"));
  if ($clveicmotoristascentral->numrows > 0){
    $clveicmotoristascentral->excluir(null,"ve41_veiccadcentral = $ve36_sequencial");
    if ($clveicmotoristascentral->erro_status == 0){
      $sqlerro  = true;
      $erro_msg = $clveicmotoristascentral->erro_msg;
    }
  }

 $res_veiccentral = $clveiccentral->sql_record($clveiccentral->sql_query(null,"*",null,"ve40_veiccadcentral = $ve36_sequencial"));

if ($clveiccentral->numrows>0){

        $clveiccentral->excluir(null,"ve40_veiccadcentral= $ve36_sequencial");
       if ($clveiccentral->erro_status == 0){
        $sqlerro  = true;
        $erro_msg = $clveiccentral->erro_msg;
       }
}
  
  if ($sqlerro == false){
    $clveiccadcentral->excluir($ve36_sequencial);
    $erro_msg = $clveiccadcentral->erro_msg;
    if ($clveiccadcentral->erro_status == 0){
      $sqlerro  = true;
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $db_opcao = 3;
  }
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $clveiccadcentral->sql_record($clveiccadcentral->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
	include("forms/db_frmveiccadcentral.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($excluir)){
  if ($sqlerro == true){
    $clveiccadcentral->erro(true,false);
  }else{
    $clveiccadcentral->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>