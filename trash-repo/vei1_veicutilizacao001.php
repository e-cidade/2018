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
include("classes/db_veicutilizacao_classe.php");
include("classes/db_veicutilizacaobem_classe.php");
include("classes/db_veicutilizacaoconvenio_classe.php");

db_postmemory($HTTP_POST_VARS);

$clveicutilizacao         = new cl_veicutilizacao;
$clveicutilizacaobem      = new cl_veicutilizacaobem;
$clveicutilizacaoconvenio = new cl_veicutilizacaoconvenio;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

if (!isset($ve15_veiculos)){
  exit;
}

$db_opcao = 1;
$db_botao = true;

if (isset($novo)){
  unset($sequencial);
  unset($ve15_veiccadutilizacao);
  unset($ve14_descr);
}

if (isset($opcao)){
  $dbwhere = "";
  
  if (isset($ve15_sequencial) && trim(@$ve15_sequencial) != ""){
    $dbwhere = "and ve15_sequencial = $ve15_sequencial";
  }

  $res_veicutilizacao = $clveicutilizacao->sql_record($clveicutilizacao->sql_query_uso(null,"ve15_sequencial,ve15_veiculos,ve14_descr",null,"ve15_veiculos = $ve15_veiculos $dbwhere"));

  if ($clveicutilizacao->numrows > 0){
    db_fieldsmemory($res_veicutilizacao,0);
  }
}




if (isset($opcao) && $opcao == "alterar"){
  $sequencial = $ve15_sequencial;
  $db_opcao   = 2;

  $res_veicutilizacao = $clveicutilizacao->sql_record($clveicutilizacao->sql_query($sequencial,"ve15_veiccadutilizacao"));
  if ($clveicutilizacao->numrows > 0){
    db_fieldsmemory($res_veicutilizacao,0);
  }

  $res_veicutilizacaobem = $clveicutilizacaobem->sql_record($clveicutilizacaobem->sql_query(null,"ve16_bens,t52_descr",null,"ve16_veicutilizacao = $sequencial"));
  if ($clveicutilizacaobem->numrows > 0){
    db_fieldsmemory($res_veicutilizacaobem,0);
  }
  
  $res_veicutilizacaoconvenio = $clveicutilizacaoconvenio->sql_record($clveicutilizacaoconvenio->sql_query(null,"ve19_veiccadconvenio,ve17_descr",null,"ve19_veicutilizacao = $sequencial"));
  if ($clveicutilizacaoconvenio->numrows > 0){
    db_fieldsmemory($res_veicutilizacaoconvenio,0);
  }
}

if (isset($opcao) && $opcao == "excluir"){
  $sequencial = $ve15_sequencial;
  $db_opcao   = 3;
  
  $res_veicutilizacao = $clveicutilizacao->sql_record($clveicutilizacao->sql_query($sequencial,"ve15_veiccadutilizacao"));
  if ($clveicutilizacao->numrows > 0){
    db_fieldsmemory($res_veicutilizacao,0);
  }
  
  $res_veicutilizacaobem = $clveicutilizacaobem->sql_record($clveicutilizacaobem->sql_query(null,"ve16_bens,t52_descr",null,"ve16_veicutilizacao = $sequencial"));
  if ($clveicutilizacaobem->numrows > 0){
    db_fieldsmemory($res_veicutilizacaobem,0);
  }
  
  $res_veicutilizacaoconvenio = $clveicutilizacaoconvenio->sql_record($clveicutilizacaoconvenio->sql_query(null,"ve19_veiccadconvenio,ve17_descr",null,"ve19_veicutilizacao = $sequencial"));
  if ($clveicutilizacaoconvenio->numrows > 0){
    db_fieldsmemory($res_veicutilizacaoconvenio,0);
  }
}

if(isset($incluir)){
  $erro_msg = "";
  $sqlerro  = false;
  
  $res_veicutilizacao = $clveicutilizacao->sql_record($clveicutilizacao->sql_query_file(null,"ve15_veiccadutilizacao",null,"ve15_veiculos = $ve15_veiculos"));
  if ($clveicutilizacao->numrows > 0){
    $erro_msg                     = "já existe utilização cadastrada. Verifique.";
    $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
    $sqlerro = true;
  }

  db_inicio_transacao();

  if ($sqlerro == false){
    $clveicutilizacao->ve15_veiccadutilizacao = $ve15_veiccadutilizacao;
    $clveicutilziacao->ve15_veiculos          = $ve15_veiculos;

    $clveicutilizacao->incluir(null);
    $erro_msg = $clveicutilizacao->erro_msg;
    if ($clveicutilizacao->erro_status == 0){
      $sqlerro = true;
      $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
    }

    $ve15_sequencial = $clveicutilizacao->ve15_sequencial;
  }
  
  if ($sqlerro == false){
    if (isset($ve16_bens) && trim($ve16_bens) != ""){
      $clveicutilizacaobem->ve16_bens           = $ve16_bens;
      $clveicutilizacaobem->ve16_veicutilizacao = $ve15_sequencial;

      $clveicutilizacaobem->incluir(null);
      if ($clveicutilizacaobem->erro_status == "0"){
        $sqlerro  = true;
        $erro_msg = $clveicutilizacaobem->erro_msg;
        $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
      }
    }
    
    if (isset($ve19_veiccadconvenio) && trim($ve19_veiccadconvenio) != ""){
      $clveicutilizacaoconvenio->ve19_veiccadconvenio = $ve19_veiccadconvenio;
      $clveicutilizacaoconvenio->ve19_veicutilizacao  = $ve15_sequencial;

      $clveicutilizacaoconvenio->incluir(null);
      if ($clveicutilizacaoconvenio->erro_status == "0"){
        $sqlerro  = true;
        $erro_msg = $clveicutilizacaoconvenio->erro_msg;
        $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
      }
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    unset($ve15_sequencial);
    unset($sequencial);
    unset($ve15_veiccadutilizacao);
    unset($ve14_descr);
  }
}

if (isset($alterar)){
  $ve15_sequencial = $sequencial;
  $erro_msg        = "";
  $sqlerro         = false;

  db_inicio_transacao();

  $clveicutilizacao->ve15_sequencial        = $ve15_sequencial;
  $clveicutilizacao->ve15_veiccadutilizacao = $ve15_veiccadutilizacao;
  $clveicutilizacao->ve15_veiculos          = $ve15_veiculos;
  
  $clveicutilizacao->alterar($ve15_sequencial);
  $erro_msg = $clveicutilizacao->erro_msg;
  if ($clveicutilizacao->erro_status == 0){
    $sqlerro = true;
    $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
  }

  $res_veicutilizacaobem = $clveicutilizacaobem->sql_record($clveicutilizacaobem->sql_query(null,"ve16_sequencial as seq_bem",null,"ve16_veicutilizacao = $ve15_sequencial"));
  if ($clveicutilizacaobem->numrows > 0){
    // Trocou de Proprio -> Convenio
    if (isset($ve19_veiccadconvenio) && trim($ve19_veiccadconvenio) != ""){
      $clveicutilizacaoconvenio->ve19_veiccadconvenio = $ve19_veiccadconvenio;
      $clveicutilizacaoconvenio->ve19_veicutilizacao  = $ve15_sequencial;

      $clveicutilizacaoconvenio->incluir(null);
      if ($clveicutilizacaoconvenio->erro_status == "0"){
        $sqlerro  = true;
        $erro_msg = $clveicutilizacaoconvenio->erro_msg;
        $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
      }

      if ($sqlerro == false){
        db_fieldsmemory($res_veicutilizacaobem,0);
        $clveicutilizacaobem->ve16_sequencial = $seq_bem;
    
        $clveicutilizacaobem->excluir($seq_bem);
        if ($clveicutilizacaobem->erro_status == "0"){
          $sqlerro  = true;
          $erro_msg = $clveicutilizacaobem->erro_msg;
          $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
        }
      }
    }
  } else {
    $res_veicutilizacaoconvenio = $clveicutilizacaoconvenio->sql_record($clveicutilizacaoconvenio->sql_query(null,"ve19_sequencial as seq_conv",null,"ve19_veicutilizacao = $ve15_sequencial"));
    if ($clveicutilizacaoconvenio->numrows > 0){
      // Trocou de Convenio -> Proprio
      if (isset($ve16_bens) && trim($ve16_bens) != ""){
        $clveicutilizacaobem->ve16_bens           = $ve16_bens;
        $clveicutilizacaobem->ve16_veicutilizacao = $ve15_sequencial;

        $clveicutilizacaobem->incluir(null);
        if ($clveicutilizacaobem->erro_status == "0"){
          $sqlerro  = true;
          $erro_msg = $clveicutilizacaobem->erro_msg;
          $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
        }
      }

      if ($sqlerro == false){
        db_fieldsmemory($res_veicutilizacaoconvenio, 0);
        $clveicutilizacaoconvenio->ve19_sequencial = $seq_conv;
    
        $clveicutilizacaoconvenio->excluir($seq_conv);
        if ($clveicutilizacaoconvenio->erro_status == "0"){
          $sqlerro  = true;
          $erro_msg = $clveicutilizacaoconvenio->erro_msg;
          $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
        }
      }
    } else {
      // Trocou para Paricular -> Convenio ou Proprio
      if (isset($ve16_bens) && trim($ve16_bens) != ""){
        $clveicutilizacaobem->ve16_bens           = $ve16_bens;
        $clveicutilizacaobem->ve16_veicutilizacao = $ve15_sequencial;

        $clveicutilizacaobem->incluir(null);
        if ($clveicutilizacaobem->erro_status == "0"){
          $sqlerro  = true;
          $erro_msg = $clveicutilizacaobem->erro_msg;
          $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
        }
      }
    
      if (isset($ve19_veiccadconvenio) && trim($ve19_veiccadconvenio) != ""){
        $clveicutilizacaoconvenio->ve19_veiccadconvenio = $ve19_veiccadconvenio;
        $clveicutilizacaoconvenio->ve19_veicutilizacao  = $ve15_sequencial;

        $clveicutilizacaoconvenio->incluir(null);
        if ($clveicutilizacaoconvenio->erro_status == "0"){
          $sqlerro  = true;
          $erro_msg = $clveicutilizacaoconvenio->erro_msg;
          $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
        }
      }
    }
  }

  db_fim_transacao($sqlerro);
  $db_opcao = 2;
}

if (isset($excluir)){
  $ve15_sequencial = $sequencial;
  $erro_msg        = "";
  $sqlerro         = false;
  
  db_inicio_transacao();

  $result = $clveicutilizacaobem->sql_record($clveicutilizacaobem->sql_query(null,"ve16_sequencial",null,"ve16_veicutilizacao = $ve15_sequencial"));
  if ($clveicutilizacaobem->numrows > 0){
    db_fieldsmemory($result,0);
    $clveicutilizacaobem->ve16_sequencial = $ve16_sequencial;
    
    $clveicutilizacaobem->excluir($ve16_sequencial);
    if ($clveicutilizacaobem->erro_status == "0"){
      $sqlerro  = true;
      $erro_msg = $clveicutilizacaobem->erro_msg;
      $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
    }
  }

  $result = $clveicutilizacaoconvenio->sql_record($clveicutilizacaoconvenio->sql_query(null,"ve19_sequencial",null,"ve19_veicutilizacao = $ve15_sequencial"));
  if ($clveicutilizacaoconvenio->numrows > 0){
    db_fieldsmemory($result,0);
    $clveicutilizacaoconvenio->ve19_sequencial = $ve19_sequencial;
    
    $clveicutilizacaoconvenio->excluir($ve19_sequencial);
    if ($clveicutilizacaoconvenio->erro_status == "0"){
      $sqlerro  = true;
      $erro_msg = $clveicutilizacaoconvenio->erro_msg;
      $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
    }
  }

  if ($sqlerro == false){  
    $clveicutilizacao->ve15_sequencial = $ve15_sequencial;

    $clveicutilizacao->excluir($ve15_sequencial);
    $erro_msg = $clveicutilizacao->erro_msg;
    if ($clveicutilizacao->erro_status == 0){
      $sqlerro = true;
      $clveicutilizacao->erro_campo = "ve15_veiccadutilizacao";
    }
  }

  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $db_opcao = 1;

    unset($ve15_sequencial);
    unset($ve15_veiccadutilizacao);
    unset($sequencial);
    unset($ve14_descr);
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
	include("forms/db_frmveicutilizacao.php");
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

    if($clveicutilizacao->erro_campo!=""){
      echo "<script> document.form1.".$clveicutilizacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicutilizacao->erro_campo.".focus();</script>";
    }
  }

  if (trim($erro_msg) != ""){
    db_msgbox($erro_msg);
  }
}
?>