<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_tabrec_classe.php");
include("classes/db_tabrecregrasjm_classe.php");
include("dbforms/db_funcoes.php");
$cltabrec = new cl_tabrec;
$cltabrecregrasjm = new cl_tabrecregrasjm;
$db_opcao = 1;
$db_botao = true;
$anousu =  db_getsession("DB_anousu");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$codjmOK = true;
if(isset($incluir)){
  db_inicio_transacao();

  if(trim($k04_dtini_dia) != "" && trim($k04_dtini_mes) != "" && trim($k04_dtini_ano) != ""){
    $k04_dtini = $k04_dtini_ano. '-' .$k04_dtini_mes. '-' .$k04_dtini_dia;
  }

  if(trim($k04_dtfim_dia) != "" && trim($k04_dtfim_mes) != "" && trim($k04_dtfim_ano) != ""){
    $k04_dtfim = $k04_dtfim_ano. '-' .$k04_dtfim_mes. '-' .$k04_dtfim_dia;
  }

  $result_faixa_existente = $cltabrecregrasjm->sql_record($cltabrecregrasjm->sql_query_file(null," k04_receit ",""," k04_receit = ".$k04_receit." and ('".$k04_dtini."' between k04_dtini and k04_dtfim or '".$k04_dtfim."' between k04_dtini and k04_dtfim or ('".$k04_dtini."' < k04_dtini and '".$k04_dtfim."' > k04_dtfim))"));
  if($cltabrecregrasjm->numrows > 0){
    $cltabrecregrasjm->erro_status = 0;
    $cltabrecregrasjm->erro_msg = "Inclusão não efetuada. \\n\\nJá existe uma regra com (ou entre) estas datas. Verifique!";
    $cltabrecregrasjm->erro_campo = "k04_dtini_dia";
  }else{
    $cltabrecregrasjm->incluir(null);
  }
  db_fim_transacao();
}else if(isset($alterar)){
  db_inicio_transacao();

  if(trim($k04_dtini_dia) != "" && trim($k04_dtini_mes) != "" && trim($k04_dtini_ano) != ""){
    $k04_dtini = $k04_dtini_ano. '-' .$k04_dtini_mes. '-' .$k04_dtini_dia;
  }

  if(trim($k04_dtfim_dia) != "" && trim($k04_dtfim_mes) != "" && trim($k04_dtfim_ano) != ""){
    $k04_dtfim = $k04_dtfim_ano. '-' .$k04_dtfim_mes. '-' .$k04_dtfim_dia;
  }

  $result_faixa_existente = $cltabrecregrasjm->sql_record($cltabrecregrasjm->sql_query_file(null," k04_receit ",""," k04_sequencial <> ".$k04_sequencial." and k04_receit = ".$k04_receit." and ('".$k04_dtini."' between k04_dtini and k04_dtfim or '".$k04_dtfim."' between k04_dtini and k04_dtfim or ('".$k04_dtini."' < k04_dtini and '".$k04_dtfim."' > k04_dtfim))"));
  if($cltabrecregrasjm->numrows > 0){
    $cltabrecregrasjm->erro_status = 0;
    $cltabrecregrasjm->erro_msg = "Alteração não efetuada. \\n\\nJá existe uma regra com (ou entre) estas datas. Verifique!";
    $cltabrecregrasjm->erro_campo = "k04_dtini_dia";
    $db_opcao = 2;
  }else{
    $cltabrecregrasjm->alterar($k04_sequencial);
    if($cltabrecregrasjm->erro_status=="0"){
      $db_opcao = 2;
    }
  }
  db_fim_transacao();
}else if(isset($excluir)){
  db_inicio_transacao();
  $cltabrecregrasjm->excluir($k04_sequencial);
  if($cltabrecregrasjm->erro_status=="0"){
    $db_opcao = 3;
  }
  db_fim_transacao();
}else if(isset($k04_sequencial) && trim($k04_sequencial) != ""){
  $result_tabrecregrasjm = $cltabrecregrasjm->sql_record($cltabrecregrasjm->sql_query_tabrec($k04_sequencial," *, k04_codjm as testajuroemulta "));
  if($cltabrecregrasjm->numrows > 0){
    db_fieldsmemory($result_tabrecregrasjm, 0);
  }

  if($opcao == "alterar"){
    $db_opcao = 2; 
  }else{
    $db_opcao = 3; 
  }
}else if(!empty($k04_receit)){
  $result = $cltabrec->sql_record($cltabrec->sql_query($k04_receit));
  db_fieldsmemory($result,0);

  $sSqlRegras = $cltabrecregrasjm->sql_query_file(null,"k04_codjm as testajuroemulta","","k04_receit = ".$k04_receit." and '".date("Y-m-d",db_getsession("DB_datausu"))."' between k04_dtini and k04_dtfim");
  $result_tabrecregrasjm = $cltabrecregrasjm->sql_record($sSqlRegras);
  if($cltabrecregrasjm->numrows > 0){
    db_fieldsmemory($result_tabrecregrasjm, 0);
    if($testajuroemulta != $k02_codjm){
      $codjmOK = false;
    }
  }
  $k04_codjm = $k02_codjm;
}
if((isset($incluir) || isset($alterar) || isset($excluir)) && $cltabrecregrasjm->erro_status != "0"){
  unset($k04_sequencial, $k04_dtini, $k04_dtini_dia, $k04_dtini_mes, $k04_dtini_ano, $k04_dtfim, $k04_dtfim_dia, $k04_dtfim_mes, $k04_dtfim_ano);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <?
      include("forms/db_frmreceitasregrasjm.php");
      ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($cltabrecregrasjm->erro_status=="0"){
    $cltabrecregrasjm->erro(true,false);
    if($cltabrecregrasjm->erro_campo!=""){
      echo "<script> document.form1.".$cltabrecregrasjm->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltabrecregrasjm->erro_campo.".focus();</script>";
    };
  }else{
    $cltabrecregrasjm->erro(true,false);
  };
}
if($codjmOK == false){
  db_msgbox("ALERTA: Código de juro e multa do período atual diferente da receita.\\nVerifique.");
}
?>