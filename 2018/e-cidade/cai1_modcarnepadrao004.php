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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_modcarnepadrao_classe.php");
require_once("classes/db_modcarnepadraotipo_classe.php");
require_once("classes/db_modcarnepadraolayouttxt_classe.php");
require_once("classes/db_modcarnepadraocadmodcarne_classe.php");

$cldb_config				         = new cl_db_config;
$clmodcarnepadrao 			     = new cl_modcarnepadrao;
$clmodcarnepadraolayouttxt   = new cl_modcarnepadraolayouttxt;
$clmodcarnepadraocadmodcarne = new cl_modcarnepadraocadmodcarne;

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;

if(isset($incluir)){
	
  $sqlerro = false;
  db_inicio_transacao();
  
  
//   $sWhereValidaRegra  = " 	  k48_instit	 	 = ".db_getsession('DB_instit');
//   $sWhereValidaRegra .= " and k48_cadtipomod 	 = {$k48_cadtipomod} 		  ";
//   $sWhereValidaRegra .= " and k49_modcarnepadrao is null			 		  ";
//   $sWhereValidaRegra .= " and k36_modcarnepadrao is null 			 		  ";

//   $rsValidaRegra = $clmodcarnepadrao->sql_record($clmodcarnepadrao->sql_query(null,"k48_sequencial",null,$sWhereValidaRegra));
  
//  if ( $clmodcarnepadrao->numrows == 0 ) {
  
    
    $clmodcarnepadrao->k48_parcini     = $_POST['k48_parcini'];
    $clmodcarnepadrao->k48_parcfim     = $_POST['k48_parcfim'];
    $clmodcarnepadrao->k48_utilizataxa = $_POST['k48_utilizataxa'];
    $clmodcarnepadrao->k48_cadtipomod   = $_POST['k48_cadtipomod'];
    $clmodcarnepadrao->incluir("");
  	if($clmodcarnepadrao->erro_status==0){
	  $sqlerro=true;
  	}
  	
    $erro_msg = $clmodcarnepadrao->erro_msg;
  
    if ($selPdfTxt == "pdf") {
  	
  	  $clmodcarnepadraocadmodcarne->m01_cadmodcarne	   = $m01_cadmodcarne;
  	  $clmodcarnepadraocadmodcarne->m01_modcarnepadrao = $clmodcarnepadrao->k48_sequencial;
  	  $clmodcarnepadraocadmodcarne->incluir(null);

  	  if ($clmodcarnepadraocadmodcarne->erro_status == 0 ) {
  	    $sqlerro  = true;
  	    $erro_msg = $clmodcarnepadraocadmodcarne->erro_msg;
      }
  	
    } else if($selPdfTxt == "txt") {
  	
  	  $clmodcarnepadraolayouttxt->m02_db_layouttxt   = $m02_db_layouttxt;  
  	  $clmodcarnepadraolayouttxt->m02_modcarnepadrao = $clmodcarnepadrao->k48_sequencial;
  	  $clmodcarnepadraolayouttxt->incluir(null); 

  	  if ($clmodcarnepadraolayouttxt->erro_status == 0 ) {
  	    $sqlerro  = true;
  	    $erro_msg = $clmodcarnepadraolayouttxt->erro_msg;
  	  }
    }
//   } else {
//   	$oValidaRegra = db_utils::fieldsMemory($rsValidaRegra,0);
//  	$sqlerro  = true;
//  	$erro_msg = " Já existe regra nº{$oValidaRegra->k48_sequencial} com os mesmo parâmetros configurados! Verifique."; 	
//   }
  
  db_fim_transacao($sqlerro);
  
  $k48_sequencial = $clmodcarnepadrao->k48_sequencial;
  $db_opcao 	  = 1;
  $db_botao 	  = true;
  
} else {
	
  $rsConfig   = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'),'codigo,nomeinst')); 	
  $oConfig    = db_utils::fieldsMemory($rsConfig,0);
  $k48_instit = $oConfig->codigo;
  $nomeinst   = $oConfig->nomeinst;
	
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmodcarnepadrao.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clmodcarnepadrao->erro_campo!=""){
      echo "<script> document.form1.".$clmodcarnepadrao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmodcarnepadrao->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("cai1_modcarnepadrao005.php?liberaaba=true&chavepesquisa={$k48_sequencial}");
  }
}
?>