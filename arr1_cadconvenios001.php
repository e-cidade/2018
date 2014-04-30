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

$cl_db_config		        = new cl_db_config();
$clcadconvenio 	        = new cl_cadconvenio();
$clcadarrecadacao	      = new cl_cadarrecadacao();
$clcadtipoconvenio      = new cl_cadtipoconvenio();
$clconveniocobranca     = new cl_conveniocobranca();
$clconvenioarrecadacao  = new cl_convenioarrecadacao();
$clcadconveniogrupotaxa = new cl_cadconveniogrupotaxa;

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;
$sMsgErro = "";

if (isset($oPost->incluir)) {
	
  db_inicio_transacao();
  
  if ($oPost->ar11_cadtipoconvenio == 3 || $oPost->ar11_cadtipoconvenio == 4 ) {
  	
  	if ( $oPost->ar11_cadtipoconvenio == 3 ) {
  	  $sWhere   = "     ar11_instit 		     = ".db_getsession('DB_instit');
  	  $sWhere  .= " and ar11_cadtipoconvenio = 3 ";
	    $sMsgTipo = "ARRECADAÇÃO";  	  
  	} else {
  	  $sWhere   = "     ar11_instit 		     = ".db_getsession('DB_instit');
  	  $sWhere  .= " and ar11_cadtipoconvenio = 4 ";
  	  $sMsgTipo = "CAIXA PADRÃO";
  	}
  	 
  	$rsConsultaConvenios = $clcadconvenio->sql_record($clcadconvenio->sql_query(null,"*",null,$sWhere));	
  	
  	if ( $clcadconvenio->numrows > 0) {
  	  $lSqlErro = true;	 
  	  $sMsgErro = " Já possui convenio do tipo {$sMsgTipo} cadastrado !";
  	}
  }
  
  if ( !$lSqlErro ) {
  	
  	$clcadconvenio->ar11_cadtipoconvenio = $oPost->ar11_cadtipoconvenio;
  	$clcadconvenio->ar11_instit			     = db_getsession('DB_instit');
  	$clcadconvenio->ar11_nome			       = $oPost->ar11_nome;
	  $clcadconvenio->incluir($oPost->ar11_sequencial);
	
	 	if ( $clcadconvenio->erro_status == 0 ) {
		  $sMsgErro = $clcadconvenio->erro_msg;
		  $lSqlErro = true;
		}
		  
		$iConvenio = $clcadconvenio->ar11_sequencial;
		
		$rsConsultaModalidade = $clcadtipoconvenio->sql_record($clcadtipoconvenio->sql_query($oPost->ar11_cadtipoconvenio,"ar12_cadconveniomodalidade"));
		$oModalidade = db_utils::fieldsMemory($rsConsultaModalidade,0);    
		
		  
		if (!$lSqlErro) {
		    
	  	// Inlclui Cobrança
		  if ($oModalidade->ar12_cadconveniomodalidade == "1") {
		
		  	$clconveniocobranca->ar13_cadconvenio = $clcadconvenio->ar11_sequencial;
		  	$clconveniocobranca->incluir(null);
			
		  	if ($clconveniocobranca->erro_status == 0) {
				  $sMsgErro = $clconveniocobranca->erro_msg;
				  $lSqlErro = true;  	   	
		    }
		
		  	  
		  // Inlcui Arrecadação
		  } else if ($oModalidade->ar12_cadconveniomodalidade == "2") {
				
		  		
		  	$rsCadArrecacadao = $clcadarrecadacao->sql_record($clcadarrecadacao->sql_query_file(null,"ar16_sequencial",null," ar16_instit = ".db_getsession('DB_instit')));	
		      
		  	if ( $clcadarrecadacao->numrows > 0 ) {
		  	  $oCadArrecadacao  = db_utils::fieldsMemory($rsCadArrecacadao,0);
		    } else {
		      $lSqlErro = true;
		      $sMsgErro = "Configurar convênio arrecadação!";	
		    }
		  	  
		    if (!$lSqlErro) {
		      	
		      $clconvenioarrecadacao->ar14_cadarrecadacao = $oCadArrecadacao->ar16_sequencial;
		  	  $clconvenioarrecadacao->ar14_cadconvenio    = $clcadconvenio->ar11_sequencial;
		  	  $clconvenioarrecadacao->incluir(null);
		  	  
		  	  if ($clconvenioarrecadacao->erro_status == 0) {
	  		    $sMsgErro = $clconvenioarrecadacao->erro_msg;
		        $lSqlErro = true;  	   	
	  	    }
	      }	
		  }
	  }
  }  

  if (!empty($_POST['ar37_sequencial'])) {
    $clcadconveniogrupotaxa->ar39_grupotaxa   = $_POST['ar37_sequencial'];
    $clcadconveniogrupotaxa->ar39_cadconvenio = $iConvenio;
    $clcadconveniogrupotaxa->incluir(null);
    if($clcadconveniogrupotaxa->erro_status == "0") {
      
      $sMsgErro = $clcadconveniogrupotaxa->erro_msg;
      $lSqlErro = true;  	
    }
  }
  
  db_fim_transacao($lSqlErro);
  
} else {
	
	$rsConsultaConfig = $cl_db_config->sql_record($cl_db_config->sql_query_file(db_getsession('DB_instit'),"codigo,nomeinst"));
	$oConfig	        = db_utils::fieldsMemory($rsConsultaConfig,0);
	$nomeinst	    	  = $oConfig->nomeinst;
 	$ar11_instit 	    = $oConfig->codigo;
	
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
<body bgcolor=#CCCCCC >

			<?
			  include("forms/db_frmcadconvenios.php");
			?>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
  if (isset($oPost->incluir)) {
     
  	if ($lSqlErro) {
  	  db_msgbox($sMsgErro);
  	} else {
  	  $clcadconvenio->erro(true,true);  		
  	}

  }
?>