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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tfd_ajudacusto_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oIframeAE = new cl_iframe_alterar_excluir();

db_postmemory($HTTP_POST_VARS);
$cltfd_ajudacusto = new cl_tfd_ajudacusto;
$db_opcao = 1;
$db_botao = true;

$sMsgErro = "";
$lErro    = false;

if(isset($opcao)) {

  if($opcao == 'alterar') {
    $db_opcao = 2;
  } else {
    $db_opcao = 3;
  }
}

if(isset($incluir)){
  db_inicio_transacao();
  $cltfd_ajudacusto->incluir($tf12_i_codigo);
  db_fim_transacao($cltfd_ajudacusto->erro_status == '0' ? true : false);
}

if(isset($alterar)) {
 
  $db_opcao = 2;
  $opcao = 'alterar';
  db_inicio_transacao();
  $cltfd_ajudacusto->alterar($tf12_i_codigo);
  db_fim_transacao($cltfd_ajudacusto->erro_status == '0' ? true : false);
}

if (isset($excluir)) {

  $opcao    = 'excluir';
  $db_opcao = 3;
  $oDaoBeneficio = new cl_tfd_beneficiadosajudacusto();
  
  $rs = $oDaoBeneficio->sql_record($oDaoBeneficio->sql_query_file(null, "1", null, "tf15_i_ajudacusto = {$tf12_i_codigo}"));
  if ($oDaoBeneficio->numrows > 0) {
  	
    $sMsgErro = "Esta ajuda de custo não pode ser excluída pois esta vinculada com um pedido TFD.";
    $lErro    = true;
  }
  
  if (!$lErro) {
  	
    $opcao = 'excluir';
    db_inicio_transacao();
    $cltfd_ajudacusto->excluir($tf12_i_codigo);
    db_fim_transacao($cltfd_ajudacusto->erro_status == '0' ? true : false);
  }
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body bgcolor=#CCCCCC >
<div class="container">
<?
	require_once("forms/db_frmtfd_ajudacusto.php");
?>
</div>
<?php 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($incluir) || isset($alterar) || isset($excluir)) {
  if($cltfd_ajudacusto->erro_status=="0"){
    $cltfd_ajudacusto->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltfd_ajudacusto->erro_campo!=""){
      echo "<script> document.form1.".$cltfd_ajudacusto->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltfd_ajudacusto->erro_campo.".focus();</script>";
    }
  }else{
    $cltfd_ajudacusto->erro(true,true);
  }
}

if ($lErro) {
	db_msgbox($sMsgErro);
}
?>