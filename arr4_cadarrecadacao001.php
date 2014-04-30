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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cadarrecadacao_classe.php");
include("classes/db_db_config_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");

$oPost = db_utils::postMemory($_POST);

$clcadarrecadacao = new cl_cadarrecadacao();
$cldb_config	  = new cl_db_config(); 

$db_botao = true;
$db_opcao = 1;
$lSqlErro = false;

if (isset($oPost->incluir)) {
  
  db_inicio_transacao();

  $clcadarrecadacao->incluir(null);
	
  if ($clcadarrecadacao->erro_status == 0) {
    $lSqlErro = true;
    $sMsgErro = $clcadarrecadacao->erro_msg;
  }
    
  db_fim_transacao($lSqlErro);
  
} else if (isset($oPost->alterar)) {
	  
  db_inicio_transacao();

  $clcadarrecadacao->alterar($oPost->ar16_sequencial);
  
  if ($clcadarrecadacao->erro_status == 0) {
    $lSqlErro = true;
    $sMsgErro = $clcadarrecadacao->erro_msg;
  }
  	
  db_fim_transacao($lSqlErro);
	
} else {
  
  $rsConfig    = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'),"nomeinst"));
  $oConfig     = db_utils::fieldsMemory($rsConfig,0);	
  $ar16_instit = db_getsession('DB_instit');
  $nomeinst    = $oConfig->nomeinst;
  
  $rsCadArrecadacao = $clcadarrecadacao->sql_record($clcadarrecadacao->sql_query_file(null,"*",null," ar16_instit = ".db_getsession('DB_instit')));
     
  if ( $clcadarrecadacao->numrows > 0 ) {
  	
  	$oCadArrecadacao = db_utils::fieldsMemory($rsCadArrecadacao,0);
  	
    $ar16_sequencial  = $oCadArrecadacao->ar16_sequencial;
 	$ar16_convenio    = $oCadArrecadacao->ar16_convenio;
 	$ar16_segmento    = $oCadArrecadacao->ar16_segmento;
 	$ar16_formatovenc = $oCadArrecadacao->ar16_formatovenc;
 	
	$db_opcao = 2;

  }
  
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
<body bgcolor=#CCCCCC onLoad="a=1" >

	    <?
	 	  include("forms/db_frmcadarrecadacao.php");
		?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($oPost->alterar) || isset($oPost->incluir)){
  
  if($lSqlErro){
    $clcadarrecadacao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcadarrecadacao->erro_campo!=""){
      echo "<script> document.form1.".$clcadarrecadacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcadarrecadacao->erro_campo.".focus();</script>";
    }
  }else{
    $clcadarrecadacao->erro(true,true);
  }
  
}

?>