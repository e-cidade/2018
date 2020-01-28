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
include("classes/db_veicmotoristascentral_classe.php");

db_postmemory($HTTP_POST_VARS);

$clveicmotoristascentral  = new cl_veicmotoristascentral;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

if (!isset($ve41_veiccadcentral)){
  exit;
}

$db_opcao = 1;
$db_botao = true;

if (isset($novo)){
  unset($sequencial);
  unset($ve41_veicmotoristas);
  unset($ve41_dtini);
  unset($ve41_dtini_dia);
  unset($ve41_dtini_mes);
  unset($ve41_dtini_ano);
  unset($ve41_dtfim);
  unset($ve41_dtfim_dia);
  unset($ve41_dtfim_mes);
  unset($ve41_dtfim_ano);
  unset($z01_nome);
}


if (isset($opcao)){

  $dbwhere = "";
  
  if (isset($ve41_sequencial) && trim(@$ve41_sequencial) != ""){
    $dbwhere = "and ve41_sequencial = $ve41_sequencial";
  }

  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query(null,"ve41_sequencial,ve41_veiccadcentral,ve41_veicmotoristas,ve41_dtini,ve41_dtfim,z01_nome",null,"ve41_veiccadcentral = $ve41_veiccadcentral $dbwhere"));
  if ($clveicmotoristascentral->numrows > 0){
    db_fieldsmemory($res_veicmotoristascentral,0);
  }
}

if (isset($opcao) && $opcao == "alterar"){
  $sequencial = $ve41_sequencial;
  $db_opcao   = 2;

  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query($sequencial,"ve41_veicmotoristas,ve41_dtini,ve41_dtfim,z01_nome"));
  if ($clveicmotoristascentral->numrows > 0){
    db_fieldsmemory($res_veicmotoristascentral,0);
  }
}


if (isset($opcao) && $opcao == "excluir"){
  $sequencial = $ve41_sequencial;
  $db_opcao = 3;
  
  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query($sequencial,"ve41_veicmotoristas,ve41_dtini,ve41_dtfim,z01_nome"));
  if ($clveicmotoristascentral->numrows > 0){
    db_fieldsmemory($res_veicmotoristascentral,0);
  }
}

if(isset($incluir)){
  $erro_msg = "";
  $sqlerro  = false;

  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query(null,"ve41_veicmotoristas",null,"ve41_veiccadcentral = $ve41_veiccadcentral and ve41_veicmotoristas = $ve41_veicmotoristas"));
  if ($clveicmotoristascentral->numrows > 0){
    $erro_msg                            = "Motorista já cadastrado. Verifique.";
    $clveicmotoristascentral->erro_campo = "ve41_veicmotoristas";
    $sqlerro = true;
  }

  db_inicio_transacao();

  if ($sqlerro == false){
    $clveicmotoristascentral->ve41_veicmotoristas = $ve41_veicmotoristas;
    $clveicmotoristascentral->ve41_veiccadcentral = $ve41_veiccadcentral;
    $clveicmotoristascentral->ve41_dtini          = $ve41_dtini_ano."-".$ve41_dtini_mes."-".$ve41_dtini_dia;
    if (isset($ve41_dtfim_dia) && trim($ve41_dtfim_dia) != ""){
      $clveicmotoristascentral->ve41_dtfim = $ve41_dtfim_ano."-".$ve41_dtfim_mes."-".$ve41_dtfim_dia;
    } else {
      $clveicmotoristascentral->ve41_dtfim = null;
    }

    $clveicmotoristascentral->incluir(null);
    $erro_msg = $clveicmotoristascentral->erro_msg;
    if ($clveicmotoristascentral->erro_status == 0){
      $sqlerro = true;
      if (trim($clveicmotoristascentral->erro_campo) == ""){
        $clveicmotoristascentral->erro_campo = "ve41_veicmotoristas";
      } else if ($clveicmotoristascentral->erro_campo == "ve41_dtini_dia"){
        $clveicmotoristascentral->erro_campo = "ve41_dtini";
      }
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    unset($ve41_sequencial);
    unset($sequencial);
    unset($ve41_veicmotoristas);
    unset($ve41_dtini);
    unset($ve41_dtini_dia);
    unset($ve41_dtini_mes);
    unset($ve41_dtini_ano);
    unset($ve41_dtfim);
    unset($ve41_dtfim_dia);
    unset($ve41_dtfim_mes);
    unset($ve41_dtfim_ano);
    unset($z01_nome);
  }
}

if (isset($alterar)){
  $ve41_sequencial = $sequencial;
  $erro_msg        = "";
  $sqlerro         = false;

  db_inicio_transacao();

  $res_veicmotoristascentral = $clveicmotoristascentral->sql_record($clveicmotoristascentral->sql_query(null,"ve41_veicmotoristas",null,"ve41_veiccadcentral = $ve41_veiccadcentral and ve41_veicmotoristas = $ve41_veicmotoristas and ve41_dtini = '$ve41_dtini_ano-$ve41_dtini_mes-$ve41_dtini_dia'"));

/*
  if ($clveicmotoristascentral->numrows > 0){
    $erro_msg                            = "Motorista já cadastrado. Verifique.";
    $clveicmotoristascentral->erro_campo = "ve41_veicmotoristas";
    $sqlerro = true;
  }
*/
  if ($sqlerro == false){
    $clveicmotoristascentral->ve41_sequencial     = $ve41_sequencial;
    $clveicmotoristascentral->ve41_veiccadcentral = $ve41_veiccadcentral;
    $clveicmotoristascentral->ve41_veicmotoristas = $ve41_veicmotoristas;

    if (isset($ve41_dtini_dia) && trim($ve41_dtini_dia) == ""){
      $clveicmotoristascentral->ve41_dtini = null;
    } else{
      $clveicmotoristascentral->ve41_dtini  = $ve41_dtini_ano."-".$ve41_dtini_mes."-".$ve41_dtini_dia;
    }

    if (isset($ve41_dtfim_dia) && trim($ve41_dtfim_dia) != ""){
      $clveicmotoristascentral->ve41_dtfim = $ve41_dtfim_ano."-".$ve41_dtfim_mes."-".$ve41_dtfim_dia;
    } else {
      $clveicmotoristascentral->ve41_dtfim = null;
    }
  
    $clveicmotoristascentral->alterar($ve41_sequencial);
    $erro_msg = $clveicmotoristascentral->erro_msg;
    if ($clveicmotoristascentral->erro_status == 0){
      $sqlerro = true;
      if (trim($clveicmotoristascentral->erro_campo) == ""){
        $clveicmotoristascentral->erro_campo = "ve41_veicmotoristas";
      } else if ($clveicmotoristascentral->erro_campo == "ve41_dtini_dia"){
        $clveicmotoristascentral->erro_campo = "ve41_dtini";
      }
    }
  }

  db_fim_transacao($sqlerro);
  $db_opcao = 2;
}

if (isset($excluir)){
  $ve41_sequencial = $sequencial;
  $erro_msg = "";
  $sqlerro  = false;
  
  db_inicio_transacao();

  $clveicmotoristascentral->excluir($ve41_sequencial);
  $erro_msg = $clveicmotoristascentral->erro_msg;
  if ($clveicmotoristascentral->erro_status == 0){
    $sqlerro = true;
    if (trim($clveicmotoristascentral->erro_campo) == ""){
      $clveicmotoristascentral->erro_campo = "ve41_veicmotoristas";
    } else if ($clveicmotoristascentral->erro_campo == "ve41_dtini_dia"){
      $clveicmotoristascentral->erro_campo = "ve41_dtini";
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $db_opcao = 1;

    unset($ve41_sequencial);
    unset($ve41_veicmotoristas);
    unset($ve41_dtini);
    unset($ve41_dtini_dia);
    unset($ve41_dtini_mes);
    unset($ve41_dtini_ano);
    unset($ve41_dtfim);
    unset($ve41_dtfim_dia);
    unset($ve41_dtfim_mes);
    unset($ve41_dtfim_ano);
    unset($sequencial);
    unset($z01_nome);
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
	include("forms/db_frmveicmotoristascentral.php");
	?>
    </center>
</table>
</body>
</html>
<?
if(isset($incluir)||isset($alterar)||isset($excluir)){
  if($sqlerro == true){
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($clveicmotoristascentral->erro_campo!=""){
      echo "<script> document.form1.".$clveicmotoristascentral->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicmotoristascentral->erro_campo.".focus();</script>";
    }
  }

  if (trim($erro_msg) != ""){
    db_msgbox($erro_msg);
  }
}
?>