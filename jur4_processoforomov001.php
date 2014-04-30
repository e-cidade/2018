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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_db_usuarios_classe.php");
require_once("classes/db_processoforo_classe.php");
require_once("classes/db_processoforomov_classe.php");
require_once("classes/db_processoforomovsituacao_classe.php");


$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cldbusuarios              = new cl_db_usuarios;
$clprocessoforo            = new cl_processoforo;
$clprocessoforomov         = new cl_processoforomov;
$clprocessoforomovsituacao = new cl_processoforomovsituacao;

$db_opcao                  = 1;
$db_botao                  = true;
$lSqlErro                  = false;
if (isset($oPost->incluir)) {
  
  if (!$lSqlErro) {
    
    db_inicio_transacao();
   
    $clprocessoforomov->v73_processoforo            = $oPost->v70_sequencial;
    $clprocessoforomov->v73_processoforomovsituacao = $oPost->v74_sequencial;
    $clprocessoforomov->v73_id_usuario              = db_getsession('DB_id_usuario');
    $clprocessoforomov->v73_data                    = implode("-", array_reverse(explode("/",$oPost->v73_data)));
    $clprocessoforomov->v73_hora                    = $oPost->v73_hora;
    $clprocessoforomov->v73_obs                     = $oPost->v73_obs;
    $clprocessoforomov->incluir(null);
    $sMsgErro   = $clprocessoforomov->erro_msg;
    if ($clprocessoforomov->erro_status == 0) {
      $lSqlErro = true;
    }
    
    if (!$lSqlErro) {
    
      $clprocessoforo->v70_processoforomov = $clprocessoforomov->v73_sequencial;
      $clprocessoforo->alterar($oPost->v70_sequencial);
	    if ($clprocessoforo->erro_status == 0) {
	      
	    	$sMsgErro = $clprocessoforo->erro_msg;
	      $lSqlErro = true;
	    }
    }
    
    $v70_sequencial = '';
    $v74_sequencial = '';
    $v74_descricao  = '';
    $v73_obs        = '';
    
    db_fim_transacao($lSqlErro);
  }
}


$v73_data     = date('d/m/Y', db_getsession('DB_datausu'));
$v73_hora     = db_hora();
$sSqlUsuario  = $cldbusuarios->sql_query_file(null, "nome", null, "id_usuario = ".db_getsession('DB_id_usuario'));
$rsUsuario    = $cldbusuarios->sql_record($sSqlUsuario);
if ($cldbusuarios->numrows > 0) {
		
	$oDbUsuario = db_utils::fieldsMemory($rsUsuario, 0);
  $nome       = $oDbUsuario->nome;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC>
		  <?
		    include("forms/db_frmprocessoforomov.php");
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
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clprocessoforomov->erro_campo != "") {
      
      echo "<script> document.form1.".$clprocessoforomov->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocessoforomov->erro_campo.".focus();</script>";
    }
  } else {
    db_msgbox($sMsgErro);
  }
}
?>