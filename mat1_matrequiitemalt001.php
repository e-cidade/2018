<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require("std/db_stdClass.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matrequiitem_classe.php");
include("classes/db_matrequi_classe.php");
include("classes/db_db_almox_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clmatrequiitem = new cl_matrequiitem;
$clmatrequi     = new cl_matrequi;
$cldb_almox     = new cl_db_almox;
$oDaoMatRequiCriterio = db_utils::getDao("matrequiitemcriteriocustorateio");
$aParamKeys          = array( db_getsession("DB_anousu") );
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
$iTipoControleCustos = 0; 

if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}

if (isset($m40_codigo)&&trim($m40_codigo)!=""){

  $res_matrequi = $clmatrequi->sql_record($clmatrequi->sql_query_file($m40_codigo,"m40_auto"));

  if ($clmatrequi->numrows > 0){

    db_fieldsmemory($res_matrequi,0);

    if ($m40_auto == "t"){
      $db_opcao = 3;
    }
  }
}

$db_botao = true;

if ( isset($incluir) ) {

   $sqlerro=false;
   db_inicio_transacao();

   $result_mat = $clmatrequiitem->sql_record($clmatrequiitem->sql_query(null,'*',null,"m41_codmatrequi = $m40_codigo and m41_codmatmater = $m41_codmatmater "));
   if ( $clmatrequiitem->numrows > 0 ) {

     $erro_msg        = "Material ja incluido nesta requisicao!!";
     $m41_codmatmater = "";
     $m60_descr       = "";
     $sqlerro         = true;
  }

	 if ($sqlerro==false){

		 $clmatrequiitem->m41_codunid=$codunid;
		 $clmatrequiitem->m41_codmatrequi=$m40_codigo;
		 $clmatrequiitem->incluir(null);
		 $erro_msg=$clmatrequiitem->erro_msg;
		 if ($clmatrequiitem->erro_status==0){
			 $sqlerro=true;
		 }
	 }

	 if (!$sqlerro) {

		 if (isset($cc08_sequencial) && $cc08_sequencial != "") {

			 $oDaoMatRequiCriterio = db_utils::getDao("matrequiitemcriteriocustorateio");
			 $oDaoMatRequiCriterio->cc13_custocriteriorateio = $cc08_sequencial;
			 $oDaoMatRequiCriterio->cc13_matrequiitem        = $clmatrequiitem->m41_codigo;
			 $oDaoMatRequiCriterio->incluir(null);
			 if ($oDaoMatRequiCriterio->erro_status == 0) {

				 $sqlerro  = true;
				 $erro_msg = $oDaoMatRequiCriterio->erro_msg;  

			 }
		 }
	 }

  db_fim_transacao($sqlerro);

} elseif ( isset($alterar) ) {
  
  $sqlerro=false;
  db_inicio_transacao();
  $clmatrequiitem->m41_codunid=$codunid;
  $clmatrequiitem->alterar($m41_codigo);
  $erro_msg=$clmatrequiitem->erro_msg;
  if ($clmatrequiitem->erro_status==0){
    $sqlerro=true;
  }
  if (!$sqlerro) {
     $oDaoMatRequiCriterio->excluir(null, "cc13_matrequiitem={$m41_codigo}"); 
    if (isset($cc08_sequencial) && $cc08_sequencial != "") {
      
      $oDaoMatRequiCriterio = db_utils::getDao("matrequiitemcriteriocustorateio");
      $oDaoMatRequiCriterio->cc13_custocriteriorateio = $cc08_sequencial;
      $oDaoMatRequiCriterio->cc13_matrequiitem        = $m41_codigo;
      $oDaoMatRequiCriterio->incluir(null);
      if ($oDaoMatRequiCriterio->erro_status == 0) {
        
        $sqlerro  = true;
        $erro_msg = $oDaoMatRequiCriterio->erro_msg;  
        
      }
    }
  }
  if ($sqlerro==false){
    $m41_codmatmater="";
    $m41_obs="";
    $m41_quant="";
    $m60_descr="";
  }
  db_fim_transacao($sqlerro);

} elseif ( isset($excluir) ) {

  $sqlerro = false;

  db_inicio_transacao();

  $oDaoMatRequiCriterio->excluir(null, "cc13_matrequiitem={$m41_codigo}");
  $clmatrequiitem->excluir($m41_codigo);
  $erro_msg=$clmatrequiitem->erro_msg;

  if ($clmatrequiitem->erro_status==0){
    $sqlerro=true;
  }
  if ( $sqlerro == false ) { 

    $m41_codmatmater="";
    $m41_obs="";
    $m41_quant="";
    $m60_descr="";
  }
  db_fim_transacao($sqlerro);
}

if ( !empty($m41_codigo) ) {
  
  $sSql       = $oDaoMatRequiCriterio->sql_query(null,"cc08_descricao, cc08_sequencial",null,"cc13_matrequiitem= $m41_codigo");
  $rsCriterio = $oDaoMatRequiCriterio->sql_record($sSql);

	if ($oDaoMatRequiCriterio->numrows > 0) {
		db_fieldsmemory($rsCriterio, 0);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
			<?php include("forms/db_frmmatrequiitemalt.php"); ?>
    </center>
  </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($sqlerro==true){
//    $clmatrequiitem->erro(true,false);
      db_msgbox($erro_msg);
    if($clmatrequiitem->erro_campo!=""){
      echo "<script> parent.document.form1.".$clmatrequiitem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> parent.document.form1.".$clmatrequiitem->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    echo "<script>
               parent.iframe_matrequiitem.location.href='mat1_matrequiitemalt001.php?m40_codigo=".@$m40_codigo."&m40_almox=".@$m40_almox."';\n
   </script>";

  }
}  
?>