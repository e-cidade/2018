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
require_once("classes/db_cadconvenio_classe.php");
require_once("classes/db_cadtipoconvenio_classe.php");
require_once("classes/db_conveniocobranca_classe.php");
require_once("classes/db_convenioarrecadacao_classe.php");
require_once("classes/db_cadarrecadacao_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("classes/db_cadconveniogrupotaxa_classe.php");

$oPost = db_utils::postMemory($_POST);

$cl_db_config		   = new cl_db_config();
$clcadconvenio 	       = new cl_cadconvenio();
$clcadarrecadacao	   = new cl_cadarrecadacao();
$clcadtipoconvenio     = new cl_cadtipoconvenio();
$clconveniocobranca    = new cl_conveniocobranca();
$clconvenioarrecadacao = new cl_convenioarrecadacao();
$clcadconveniogrupotaxa = new cl_cadconveniogrupotaxa;

$db_opcao = 3;
$db_botao = true;
$lSqlErro = false;
$sMsgErro = "";

if (isset($oPost->excluir)) {
	
  db_inicio_transacao();
  
  $rsConsultaModalidadeAtual = $clcadconvenio->sql_record($clcadconvenio->sql_query($oPost->ar11_sequencial,"ar15_sequencial"));
  $oModalidadeAtual	    = db_utils::fieldsMemory($rsConsultaModalidadeAtual,0);
  
  if ($oModalidadeAtual->ar15_sequencial == "1") {
	$clconveniocobranca->excluir(null," ar13_cadconvenio = ".$oPost->ar11_sequencial);  	  	
  	if ($clconveniocobranca->erro_status == 0) {
	  $sMsgErro = $clconveniocobranca->erro_msg;
	  $lSqlErro = true;  	   	
    }  	
  } else if ($oModalidadeAtual->ar15_sequencial == "2") {
    $clconvenioarrecadacao->excluir(null," ar14_cadconvenio = ".$oPost->ar11_sequencial);
  	if ($clconvenioarrecadacao->erro_status == 0) {
	  $sMsgErro = $clconvenioarrecadacao->erro_msg;
	  $lSqlErro = true;  	   	
  	}  	
  }
  
  $clcadconvenio->excluir($oPost->ar11_sequencial);

  if ( $clcadconvenio->erro_status == 0 ) {
  	$sMsgErro = $clcadconvenio->erro_msg;
  	$lSqlErro = true;
  }
  
  $clcadconveniogrupotaxa->excluir(null, "ar39_cadconvenio = {$oPost->ar11_sequencial}");
  if($clcadconveniogrupotaxa->erro_status == "0") {
    
    $sMsgErro = $clcadconveniogrupotaxa->erro_msg;
    $lSqlErro = true;   
  } 
  
  
  db_fim_transacao($lSqlErro);
  
} else if (isset($chavepesquisa)) {
	
	
	$rsConsultaConvenio = $clcadconvenio->sql_record($clcadconvenio->sql_query_arrecad_cobranc($chavepesquisa));
	db_fieldsmemory($rsConsultaConvenio,0);
	$agencia13 = $db89_codagencia;
	$agencia14 = $db89_codagencia;
	
	$rsConsultaConfig = $cl_db_config->sql_record($cl_db_config->sql_query_file(db_getsession('DB_instit'),"codigo,nomeinst"));
	$oConfig	        = db_utils::fieldsMemory($rsConsultaConfig,0);
	$nomeinst	 	      = $oConfig->nomeinst;
 	$ar11_instit 	    = $oConfig->codigo;
	
  if ( $ar11_cadtipoconvenio == 5 ) {
    $ar13_carteira_selsicob = $ar13_carteira;   
  } else if ( $ar11_cadtipoconvenio == 6 ) {
    $ar13_carteira_selsigcb = $ar13_carteira;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="<?=(isset($chavepesquisa)?"js_validaTipo('{$ar11_cadtipoconvenio}')":"")?>" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table style="padding-top:15px;" align="center">
  <tr> 
    <td> 
    <center>
	<?
	include("forms/db_frmcadconvenios.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
  if (isset($oPost->excluir)) {
     
  	if ($lSqlErro) {
  	  db_msgbox($sMsgErro);
  	  echo "<script>location.href = '';</script>";	
  	} else {
	  $clcadconvenio->erro(true,true);  		
  	}

  } else if (!isset($chavepesquisa)) {
    echo "<script>document.form1.pesquisar.click();</script>";	
  }
?>