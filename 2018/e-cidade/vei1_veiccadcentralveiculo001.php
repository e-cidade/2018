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
include("classes/db_veiccentral_classe.php");
include("classes/db_veiccadcentral_classe.php");
include("classes/db_veicmotoristascentral_classe.php");
include("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$clveiccentral            = new cl_veiccentral;
$clveiccadcentral         = new cl_veiccadcentral;
$clveicmotoristascentral  = new cl_veicmotoristascentral;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$db_opcao = 1;
$db_botao = true;

if (isset($novo)){
    unset($sequencial);
}

if (isset($opcao)){
  
  $dbwhere = "";
  if (isset($ve41_veiccadcentral) && trim(@$ve41_veiccadcentral) != ""){
    $dbwhere = "and  ve40_sequencial=$ve40_sequencial";
  }
  $res_veiccentral = $clveiccentral->sql_record($clveiccentral->sql_query(null,"ve40_sequencial,ve01_codigo,ve40_veiccadcentral,ve40_veiculos,ve01_placa,ve22_descr",null,"ve40_veiccadcentral = $ve41_veiccadcentral $dbwhere"));


if ($clveiccentral->numrows > 0){
    db_fieldsmemory($res_veiccentral,0);
  }
}

if (isset($opcao) && $opcao == "excluir"){
  $sequencial = $ve40_sequencial; 
  $db_opcao = 3;
  $res_veiccentral = $clveiccentral->sql_record($clveiccentral->sql_query($sequencial,"ve40_sequencial"));
  if ($clveiccentral->numrows > 0){
     db_fieldsmemory($res_veiccentral,0);
  }

}


if(isset($incluir)){
  $erro_msg = "";
  $sqlerro  = false;
  $res_veiccentral = $clveiccentral->sql_record($clveiccentral->sql_query(null,"ve40_veiccadcentral",null,"ve40_veiculos = $ve01_codigo and ve40_veiccadcentral=$ve41_veiccadcentral "));
  if ($clveiccentral->numrows > 0){
    $erro_msg                     = "Item já cadastrado. Verifique.";
    $clveiccentral->erro_campo = "ve40_veiccadcentral";
    $sqlerro = true;
  }

  db_inicio_transacao();

  if ($sqlerro == false){
    $clveiccentral->ve40_veiccadcentral = $ve41_veiccadcentral;
    $clveiccentral->ve40_veiculos       = $ve01_codigo;
    $clveiccentral->incluir(null);
    $erro_msg = $clveiccentral->erro_msg;
    if ($clveiccentral->erro_status == 0){
      $sqlerro = true;
      $clveiccentral->erro_campo = "ve40_veiccadcentral";
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    unset($sequencial);
    unset($ve01_codigo);
    unset($ve01_placa);
    unset($ve22_descr);
  }
}


if (isset($excluir)){
  $erro_msg = "";
  $sqlerro  = false;
  db_inicio_transacao();
  $ve40_sequencial=$sequencial;
  $clveiccentral->excluir($ve40_sequencial);
  $erro_msg = $clveiccentral->erro_msg;
  if ($clveiccentral->erro_status == 0){
    $sqlerro = true;
    $clclveiccentral->erro_campo = "ve40_sequencial";
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $db_opcao = 1;
   unset($sequencial);
   unset($ve01_codigo);
   unset($ve01_placa);
   unset($ve22_descr);

  
  
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
	include("forms/db_frmveiccadcentralveiculo.php");
	?>

 
    </center>
</table>
</body>
</html>
<?
if(isset($incluir)||isset($excluir)){
  if($sqlerro == true){
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($clveiccentral->erro_campo!=""){
      echo "<script> document.form1.".$clveiccentral->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveiccentral->erro_campo.".focus();</script>";
    }
  }

  if (trim($erro_msg) != ""){
    db_msgbox($erro_msg);
  }
}
?>