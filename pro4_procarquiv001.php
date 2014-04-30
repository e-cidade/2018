<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_procandam_classe.php");
require_once("classes/db_proctransfer_classe.php");
require_once("classes/db_proctransferproc_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_proctransand_classe.php");
require_once("classes/db_procarquiv_classe.php");
require_once("classes/db_arqproc_classe.php");
require_once("classes/db_arqandam_classe.php");
require_once("classes/db_ouvidoriaatendimento_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($_POST);
db_postmemory($_GET);

$clprocarquiv           = new cl_procarquiv;
$clprocandam            = new cl_procandam;
$clarqproc              = new cl_arqproc;
$clarqandam             = new cl_arqandam;
$clproctransfer         = new cl_proctransfer;
$clproctransand         = new cl_proctransand;
$clprotprocesso         = new cl_protprocesso;
$clOuvidoriaAtendimento = new cl_ouvidoriaatendimento();

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;

$hoje = date('d/m/y');
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
	
  db_inicio_transacao();
  $p67_codproc = $p58_codproc;
  $p67_dtarq = implode("-", array_reverse(explode("/", $p67_dtarq)));
  $clprocarquiv->p67_id_usuario = db_getsession("DB_id_usuario");
  $clprocarquiv->p67_coddepto = db_getsession("DB_coddepto");
  $clprocarquiv->p67_codproc = $p67_codproc;
  $clprocarquiv->incluir(null);
  $clarqproc->incluir($clprocarquiv->p67_codarquiv, $p67_codproc); 
  if ($clarqproc->erro_status == 0) {
  	
		$sqlerro  = true;
		$erro_msg = $clarqproc->erro_msg;
	} else {
		
		$clproctransfer->p62_coddepto    = db_getsession("DB_coddepto");
		$clproctransfer->p62_dttran      = $p67_dtarq;
		$clproctransfer->p62_coddeptorec = db_getsession("DB_coddepto");
		$clproctransfer->p62_id_usorec   = db_getsession("DB_id_usuario");
		$clproctransfer->p62_id_usuario  = db_getsession("DB_id_usuario");
		$clproctransfer->p62_hora        = db_hora();
		$clproctransfer->incluir(null);
		if ($clproctransfer->erro_status == 0) {
			
			$sqlerro  = true;
			$erro_msg = $clproctransfer->erro_msg;
		} else {
			
			$cod  = $clproctransfer->p62_codtran;
			
			$sqli = "insert into proctransferproc values($cod,$p67_codproc)";
			$rsi  =  db_query($sqli) or die($sqli);
			
			if ($clproctransfer->erro_status == "1" or !$rsi ){
				 $erro = 0;
			} else {
				
				$clproctransfer->erro(true,false);
				$sqlerro = true;	
			}
			
			//inclusão do andamento
			$clprocandam->p61_despacho   = $clprocarquiv->p67_historico;
			$clprocandam->p61_dtandam    = $p67_dtarq;
			$clprocandam->p61_hora       = db_hora();
			$clprocandam->p61_codproc    = $p67_codproc;
			$clprocandam->p61_id_usuario = db_getsession("DB_id_usuario");
			$clprocandam->p61_coddepto   = db_getsession("DB_coddepto");
			$clprocandam->p61_publico    = 'true';
			$clprocandam->incluir(null);
			$erro_msg = $clprocandam->erro_msg;
			if ($clprocandam->erro_status==0){
				 $sqlerro=true;
			}
		//  $clprocandam->erro(true,false);

			//inclui  a transferencia. e o andamento do processo na tabela proctransand;
			$clproctransand->p64_codtran  = $clproctransfer->p62_codtran;
			$clproctransand->p64_codandam = $clprocandam->p61_codandam;
			$clproctransand->incluir();

			if ($clproctransand->erro_status == "1"){
				 $erro = 0;
			}else{
				 $clproctransand->erro(true,false);
				 $sqlerro = true;
			}

			if ($sqlerro == false) {
				$clarqandam->p69_codarquiv = $clprocarquiv->p67_codarquiv;
				$clarqandam->p69_codandam  = $clprocandam->p61_codandam;
				$clarqandam->p69_arquivado = 'true';
				$clarqandam->incluir();
				$erro_msg = $clarqandam->erro_msg;
				if ($clarqandam->erro_status==0){
					 $sqlerro=true;
				} else {
					if ( $grupo == 2 ) {
					  $sWhereAtendimento = " ov09_protprocesso = {$p67_codproc} ";
				    $sSqlAtendimento   = $clOuvidoriaAtendimento->sql_query_proc(null,"distinct ov01_sequencial",null,$sWhereAtendimento);
				    $rsAtendimento     = $clOuvidoriaAtendimento->sql_record($sSqlAtendimento);
				    $iNroAtendimento   = $clOuvidoriaAtendimento->numrows;
				    
				    if ( $iNroAtendimento > 0 ) {
				      for ( $iInd=0; $iInd < $iNroAtendimento; $iInd++ ) {
				        $oAtendimento = db_utils::fieldsMemory($rsAtendimento,$iInd);
				        $clOuvidoriaAtendimento->ov01_sequencial = $oAtendimento->ov01_sequencial;
				        $clOuvidoriaAtendimento->ov01_situacaoouvidoriaatendimento = 3;
				        $clOuvidoriaAtendimento->alterar($oAtendimento->ov01_sequencial);
				        if ( $clOuvidoriaAtendimento->erro_status == 0 ) {
				          $sqlerro = true;
				          break;
				        }
				      }
				    }				
					}	
				}
			}
		}
	}

  db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top:25px;">
	<?
	include("forms/db_frmprocarquiv.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($clprocarquiv->erro_status=="0"){
  $clprocarquiv->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprocarquiv->erro_campo!=""){
    echo "<script> document.form1.".$clprocarquiv->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clprocarquiv->erro_campo.".focus();</script>";
  }
}else{
	$clprocarquiv->pagina_retorno = "pro4_procarquiv001.php?grupo=$grupo";
  $clprocarquiv->erro(true,true);
}
?>