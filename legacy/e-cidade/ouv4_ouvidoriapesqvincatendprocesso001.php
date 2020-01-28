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
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_processoouvidoria_classe.php");
include("classes/db_ouvidoriaatendimento_classe.php");
include("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$oPost        = db_utils::postMemory($_POST);
$oGet         = db_utils::postMemory($_GET);

require_once("model/processoOuvidoria.model.php");
$oProcessoOuvidoria = new processoOuvidoria();

$iP58_CodProc    = $oPost->p58_codproc;
$db_opcao        = 1;
$lSemAtendimento = false; 
$lSqlErro        = false;

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir();
$clprotprocesso           = new cl_protprocesso();
$clouvidoriaatendimento   = new cl_ouvidoriaatendimento();
$clproceouvidoria         = new cl_processoouvidoria();

if (isset($oPost->opcao) && $oPost->opcao == "excluir") {

  db_inicio_transacao();
  
  $clproceouvidoria->excluir(null,"ov09_sequencial = {$oPost->ov09_sequencial} ");
  
  if ( $clproceouvidoria->erro_status == 0 ) {
    $lSqlErro = true;
    $sMsgErro = $clproceouvidoria->erro_msg; 
  }
    
  db_fim_transacao($lSqlErro);   
  
  $sWherenroAtend    = "ov09_protprocesso = {$oPost->p58_codproc}";
  $sSqlNroAtend      = $clproceouvidoria->sql_query_file(null,"ov09_sequencial",null,$sWherenroAtend);
  $rsNroAtendimentos = $clproceouvidoria->sql_record($sSqlNroAtend);
  
  if ( $clproceouvidoria->numrows == 0 ) {
  	$lSemAtendimento = true;
  }
  
  
} else if (isset($oPost->incluir) && $oPost->incluir == "Incluir") {
  
	if (isset($oPost->p58_codproc) && isset($oPost->ov01_sequencial)) {
  	$iProtProcesso = $oPost->p58_codproc;
  	$iNumero       = $oPost->ov01_sequencial;
  } else {
  	$lSqlErro = true;
  }

  if ( !$lSqlErro ) {
	  
  	db_inicio_transacao();
	  
	  $clproceouvidoria->ov09_ouvidoriaatendimento = $iNumero;
	  $clproceouvidoria->ov09_protprocesso         = $iProtProcesso;
	  $clproceouvidoria->ov09_principal            = 'false';
	  $clproceouvidoria->incluir($clproceouvidoria->ov09_sequencial);
	
	  if ( $clproceouvidoria->erro_status == 0 ) {
	    $lSqlErro = true;
	    $sMsgErro = $clproceouvidoria->erro_msg; 
	  }
	  
	  if ( !$lSqlErro ) {
		  
	  	$sSqlAtendimento = $clouvidoriaatendimento->sql_query_file($iNumero,"ov01_solicitacao");
		  $rsAtendimento   = $clouvidoriaatendimento->sql_record($sSqlAtendimento);
		  $oAtendimento    = db_utils::fieldsMemory($rsAtendimento,0);
		  
		  try {
		    $oProcessoOuvidoria->incluirDespachoInterno($iProtProcesso,$oAtendimento->ov01_solicitacao);  	  
		  } catch (Exception $eException) {
		  	$lSqlErro = true;
		  	$sMsgErro = $eException->getMessage();
		  }
		  
	  }
	   
	  db_fim_transacao($lSqlErro);   
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="center"> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
		include("forms/db_frmvincatendprocpesquisa.php");
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
 if (isset($oPost->incluir) && $oPost->incluir == "Incluir") {
 	echo "<script>
            document.getElementById('ov01_sequencial').value = '';
            document.getElementById('ov01_requerente').value = '';
 	      </script>";
 	
    if ( isset($sMsgErro) && $lSqlErro === true) {
      db_msgbox($sMsgErro);
    } else {
      db_msgbox("Administrador: \\n - Inclusão Efetuada com Sucesso!");
    }
 }
 
 if ( isset($oPost->opcao) && $oPost->opcao == "excluir" ){  
   if ( isset($sMsgErro) && $lSqlErro === true) {
     db_msgbox($sMsgErro);
   } else {
  	 db_msgbox("Administrador: \\n - Exclusão Efetuada com Sucesso!");
  	 if ( $lSemAtendimento ) {
  	 	 echo "<script>
  	 	         if ( confirm('Deseja arquivar o processo?')) {
							   js_arquivarProcesso({$oPost->p58_codproc});
  	 	         }
  	 	       </script>";
  	 }
   }   
 } 
?>