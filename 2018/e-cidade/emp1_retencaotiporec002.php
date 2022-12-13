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
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_retencaotiporec_classe.php");
include("classes/db_retencaotiporeccgm_classe.php");
include("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clretencaotiporec    = new cl_retencaotiporec();
$clretencaotiporeccgm = new cl_retencaotiporeccgm();

$db_opcao = 22;
$db_botao = false;

if(isset($alterar)){
	
  db_inicio_transacao();
  $db_opcao = 2;
  $lSqlErro = false;
  $clretencaotiporec->alterar($e21_sequencial);
  if ($clretencaotiporec->erro_status == 0) {
  	 $lSqlErro = true;
  } else {
  	
  	//deletamos da tabela retencaonaturezatiporec
  	$oDaoRetencaoNaturezaTipoRec = db_utils::getDao("retencaonaturezatiporec");
  	$oDaoRetencaoNaturezaTipoRec->excluir(null,"e31_retencaotiporec  = {$e21_sequencial}");
  	if ($e31_retencaonatureza != '') {

  	  $oDaoRetencaoNaturezaTipoRec = db_utils::getDao("retencaonaturezatiporec");
  	  $oDaoRetencaoNaturezaTipoRec->e31_retencaonatureza = $e31_retencaonatureza;
  	  $oDaoRetencaoNaturezaTipoRec->e31_retencaotiporec  = $e21_sequencial;
  	  $oDaoRetencaoNaturezaTipoRec->incluir(null);
  	  if ($oDaoRetencaoNaturezaTipoRec->erro_status == 0 ) {
  	  	
  	    $lSqlErro                       = true;
  	    $clretencaotiporec->erro_msg    = $oDaoRetencaoNaturezaTipoRec->erro_msg;
  	    $clretencaotiporec->erro_status = 0;
  	     	
  	  }
   	}
   	
   	$clretencaotiporeccgm->excluir(null," e48_retencaotiporec = {$e21_sequencial} ");
    if ( isset($e48_cgm) && trim($e48_cgm) != '' ) {
      $clretencaotiporeccgm->e48_cgm             = $e48_cgm;
      $clretencaotiporeccgm->e48_retencaotiporec = $clretencaotiporec->e21_sequencial;
      $clretencaotiporeccgm->incluir(null);
      if ( $clretencaotiporeccgm->erro_status == '0') {
        $lSqlErro = true;
        $clretencaotiporec->erro_msg    = $clretencaotiporeccgm->erro_msg;
        $clretencaotiporec->erro_status = 0;
      }
    }   	
  }
  db_fim_transacao($lSqlErro);
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $sWhere   = "e21_sequencial = {$chavepesquisa} and e21_instit = ".db_getsession("DB_instit");
   $result   = $clretencaotiporec->sql_record($clretencaotiporec->sql_query_irrf(null,"*",null,$sWhere)); 
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
	<?
	include("forms/db_frmretencaotiporec.php");
	?>
    </center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($alterar)){
  if($clretencaotiporec->erro_status=="0"){
    $clretencaotiporec->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clretencaotiporec->erro_campo!=""){
      echo "<script> document.form1.".$clretencaotiporec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clretencaotiporec->erro_campo.".focus();</script>";
    }
  }else{
    $clretencaotiporec->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","e21_retencaotipocalc",true,1,"e21_retencaotipocalc",true);
</script>