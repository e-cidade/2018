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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");

require_once ("dbforms/db_funcoes.php");

require_once ("classes/db_procdiver_classe.php");
require_once ("classes/db_recparprocdiver_classe.php");

db_postmemory($HTTP_POST_VARS);

$clprocdiver = new cl_procdiver;

$oPost       = db_utils::postMemory($HTTP_POST_VARS);

$db_opcao    = 1;
$db_botao    = true;

if ( isset($oPost->incluir) ) {
  
  $lSqlErro = false;
  db_inicio_transacao();
   
  $clprocdiver->dv09_instit = db_getsession('DB_instit');
  $clprocdiver->incluir(null);
  
  if( $clprocdiver->erro_status == 0 ){
    $lSqlErro = true;
  }
  
  $sErroMsg = $clprocdiver->erro_msg;
  db_fim_transacao($lSqlErro);
  
  $dv09_procdiver= $clprocdiver->dv09_procdiver;
  
  $db_opcao = 1;
  $db_botao = true; 
  
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
<body bgcolor=#CCCCCC>

  	<?
  	include("forms/db_frmprocdiver.php");
  	?>

</body>
</html>
<?
if( isset($oPost->incluir) ){
  
  if( $lSqlErro == true ){
    
    db_msgbox($sErroMsg);
    
    if ( $clprocdiver->erro_campo != "" ){
      
      echo "<script> document.form1.".$clprocdiver->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocdiver->erro_campo.".focus();</script>";
    }
    
  } else {
    
    db_msgbox($sErroMsg);
    db_redireciona("div1_procdiver005.php?liberaaba=true&chavepesquisa={$clprocdiver->dv09_procdiver}");
  }
  
}
?>