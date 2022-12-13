<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_contcearquivo_classe.php");
require_once ("classes/db_contcearquivoresp_classe.php");
require_once ("classes/db_contcearquivorespcgm_classe.php");
require_once ("classes/db_concadtce_classe.php");
require_once ('libs/db_utils.php');

$clconcadtce            = new cl_concadtce();
$clcontcearquivo        = new cl_contcearquivo();
$clcontcearquivoresp    = new cl_contcearquivoresp();
$clcontcearquivorespcgm = new cl_contcearquivorespcgm();

$oInstit = db_utils::getDao('db_config');

db_postmemory($_POST);
db_postmemory($_GET);

$db_opcao = 1;
$db_botao = true;

try {

if (isset($incluir)) {
  $sqlerro = false;
  db_inicio_transacao();
  $clconcadtce->sql_record($clconcadtce->sql_query_file($c11_concadtce));
  if($clconcadtce->numrows == 0){
    throw new Exception("Código do Tribunal de Contas não encontrado. Inclusão abortada.");
   }
  $clcontcearquivo->incluir($c11_sequencial);
  if ($clcontcearquivo->erro_status == 0) {
    $sqlerro = true;
  }
  $erro_msg = $clcontcearquivo->erro_msg;
  db_fim_transacao($sqlerro);
  $c11_sequencial = $clcontcearquivo->c11_sequencial;
  $db_opcao = 1;
  $db_botao = true;
}else if (isset($importar)) {
  $sqlerro = false;
  db_inicio_transacao();
  /**
   * Importando os registros de uma geracao anterior
   */
  $rsContcearquivo = $clcontcearquivo->sql_record($clcontcearquivo->sql_query_file($icodigonovo, "*", null, null));
  $oContcearquivo  = db_utils::fieldsMemory($rsContcearquivo, 0);
  $clcontcearquivo->c11_codigoremessa = $oContcearquivo->c11_codigoremessa;
  $clcontcearquivo->c11_concadtce     = $oContcearquivo->c11_concadtce;
  $clcontcearquivo->c11_dataini       = $oContcearquivo->c11_dataini;
  $clcontcearquivo->c11_datafim       = $oContcearquivo->c11_datafim;
  $clcontcearquivo->c11_datageracao   = $oContcearquivo->c11_datageracao;
  $clcontcearquivo->c11_diapagtofolha = $oContcearquivo->c11_diapagtofolha;
  $clcontcearquivo->c11_infleiame     = $oContcearquivo->c11_infleiame;
  $clcontcearquivo->c11_instit        = $oContcearquivo->c11_instit;
  $clcontcearquivo->incluir(null);
  $erro_msg = $clcontcearquivo->erro_msg;
  if ($clcontcearquivo->erro_status == "0") {
    $sqlerro  = true;
    $erro_msg = $clcontcearquivo->erro_msg;
  }
  /**
   * Importando os responsaveis anteriores
   */
  
  $rsResponsaveis   = $clcontcearquivoresp->sql_record($clcontcearquivoresp->sql_query_file(null,"*",null,"c12_contcearquivo = {$icodigonovo}"));
  if ($rsResponsaveis) {
	  $iNumResponsaveis = pg_num_rows($rsResponsaveis); 
	  for ($i = 0 ; $i < $iNumResponsaveis; $i++) {
	  	
	  	$oResponsaveis = db_utils::fieldsMemory($rsResponsaveis,$i);
	  	$clcontcearquivoresp->c12_cargo         = $oResponsaveis->c12_cargo;
	  	$clcontcearquivoresp->c12_contcearquivo = $clcontcearquivo->c11_sequencial;
	  	$clcontcearquivoresp->c12_nome          = $oResponsaveis->c12_nome;
	  	$clcontcearquivoresp->c12_nrodoc        = $oResponsaveis->c12_nrodoc;
	  	$clcontcearquivoresp->c12_tipo          = $oResponsaveis->c12_tipo;
	  	$clcontcearquivoresp->c12_tipodoc       = $oResponsaveis->c12_tipodoc;
	  	$clcontcearquivoresp->incluir(null); 
	    if ($clcontcearquivoresp->erro_status == "0") {
	      $sqlerro  = true;
	      $erro_msg = $clcontcearquivoresp->erro_msg;
	    }
	  	
	  }
  }
  db_fim_transacao($sqlerro);
  
  $c11_sequencial = $clcontcearquivo->c11_sequencial;
  $incluir = true;   

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
include ("forms/db_frmcontcearquivo.php");
?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($incluir)) {
  if ($sqlerro == true) {
    db_msgbox($erro_msg);
    if ($clcontcearquivo->erro_campo != "") {
      echo "<script> document.form1." . $clcontcearquivo->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clcontcearquivo->erro_campo . ".focus();</script>";
    }
    ;
  } else {
    db_msgbox($erro_msg);
    db_redireciona("con1_contcearquivo005.php?liberaaba=true&chavepesquisa=$c11_sequencial");
  }
}

}catch(Exception $oErro) {

  db_msgbox($oErro->getMessage());
  db_redireciona('con1_contcearquivo004.php');
  db_fim_transacao(true);

}
?>