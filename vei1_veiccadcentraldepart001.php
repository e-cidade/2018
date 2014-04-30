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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_veiccadcentraldepart_classe.php");
include("classes/db_veiccadcentral_classe.php");

db_postmemory($HTTP_POST_VARS);

$clveiccadcentraldepart = new cl_veiccadcentraldepart;
$clveiccadcentral       = new cl_veiccadcentral;
$cliframe_seleciona     = new cl_iframe_seleciona;

if (!isset($ve37_veiccadcentral)){
  exit;
}

$sqlerro  = false;
$erro_msg = "";

if (isset($cod_depto) && trim($cod_depto)!=""){
  db_inicio_transacao();

  $clveiccadcentraldepart->ve37_veiccadcentral = $ve37_veiccadcentral;

  $clveiccadcentraldepart->excluir(null,"ve37_veiccadcentral = $ve37_veiccadcentral and ve37_coddepto <> $ve36_coddepto");
  $erro_msg = $clveiccadcentraldepart->erro_msg;
  if($clveiccadcentraldepart->erro_status == "0"){
    $sqlerro = true;
  }

  $cod_deptos = split(",",$cod_depto);
  for($i = 0; $i < count($cod_deptos); $i++){
    $clveiccadcentraldepart->ve37_coddepto       = trim($cod_deptos[$i]);
    $clveiccadcentraldepart->ve37_veiccadcentral = $ve37_veiccadcentral;

    $clveiccadcentraldepart->incluir(null);
    if ($clveiccadcentraldepart->erro_status == "0"){
      $sqlerro = true;
      break;
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $erro_msg = "Departamentos incluidos. Inclusão feita com sucesso.";
  }
}

if (isset($ve37_veiccadcentral) && trim($ve37_veiccadcentral) != "" && $sqlerro == false){
  $res_veiccadcentral = $clveiccadcentral->sql_record($clveiccadcentral->sql_query($ve37_veiccadcentral,"ve36_coddepto,descrdepto"));
  if ($clveiccadcentral->numrows > 0){
    db_fieldsmemory($res_veiccadcentral,0);
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <center>
	<?
	include("forms/db_frmveiccadcentraldepart.php");
	?>
    </center>
</table>
</body>
</html>
<?
  if (trim($erro_msg)!=""){
    db_msgbox($erro_msg);
  }
?>