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
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhestagioperiodomes_classe.php");
include("classes/db_rhestagioperiodo_classe.php");
include("dbforms/db_funcoes.php");

$oPost                 = db_utils::postMemory($_POST);
$oGet                  = db_utils::postMemory($_GET);
$clrhestagioperiodomes = new cl_rhestagioperiodomes;
$clrhestagioperiodo    = new cl_rhestagioperiodo;
$db_opcao = 22;
$db_botao = false;
$h66_rhestagioperiodo = $oGet->h55_rhestagioperiodo;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$clrhestagioperiodomes->h66_sequencial = $h66_sequencial;
$clrhestagioperiodomes->h66_rhestagioperiodo = $h66_rhestagioperiodo;
$clrhestagioperiodomes->h66_mes = $h66_mes;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $sSQL      = "select coalesce(count(rhestagioperiodomes.*),0) as total,";
    $sSQL     .= "       h55_nroaval       ";
    $sSQL     .= "  from rhestagioperiodo  ";
    $sSQL     .= "       left join rhestagioperiodomes on h66_rhestagioperiodo = h55_sequencial ";
    $sSQL     .= " where h55_sequencial = {$oPost->h66_rhestagioperiodo} ";
    $sSQL     .= " group by h55_nroaval";
    $rsPeriodo = $clrhestagioperiodomes->sql_record($sSQL);
    $oPeriodo  = db_utils::fieldsMemory($rsPeriodo,0);
    if ($oPeriodo->total < $oPeriodo->h55_nroaval){
      $clrhestagioperiodomes->incluir($h66_sequencial);
      $erro_msg = $clrhestagioperiodomes->erro_msg;
      if($clrhestagioperiodomes->erro_status==0){
         $sqlerro=true;
      }
    }else{
      $sqlerro  = true;
      $erro_msg = "Número de meses maior que o número informado no periodo."; 
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clrhestagioperiodomes->alterar($h66_sequencial);
    $erro_msg = $clrhestagioperiodomes->erro_msg;
    if($clrhestagioperiodomes->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $clrhestagioperiodomes->excluir($h66_sequencial);
    $erro_msg = $clrhestagioperiodomes->erro_msg;
    if($clrhestagioperiodomes->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clrhestagioperiodomes->sql_record($clrhestagioperiodomes->sql_query($h66_sequencial));
   if($result!=false && $clrhestagioperiodomes->numrows>0){
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
    <center>
	<?
	include("forms/db_frmrhestagioperiodomes.php");
	?>
    </center>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($clrhestagioperiodomes->erro_campo!=""){
        echo "<script> document.form1.".$clrhestagioperiodomes->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clrhestagioperiodomes->erro_campo.".focus();</script>";
    }
}
?>