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
require_once("classes/db_obrassituacao_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($HTTP_POST_VARS);
$oGet  = db_utils::postMemory($HTTP_GET_VARS);

$clobrassituacao = new cl_obrassituacao;
$db_botao        = false;
$db_opcao        = 33;

if (isset($oGet->chavepesquisa)) {

   $db_opcao          = 3;
   $db_botao          = true;
   $sSqlObrasSituacao = $clobrassituacao->sql_query($oGet->chavepesquisa);
   $rsObrasSituacao   = $clobrassituacao->sql_record($sSqlObrasSituacao); 
   
   if( $clobrassituacao->erro_status != '0' ) {     
     
     $oObrasSituacao  = db_utils::fieldsMemory($rsObrasSituacao, 0);
     $ob28_sequencial = $oObrasSituacao->ob28_sequencial;
     $ob28_descricao  = $oObrasSituacao->ob28_descricao;
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
<body bgcolor=#CCCCCC>
    <?
    include("forms/db_frmobrassituacao.php");
    ?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($oPost->excluir)) {
  
  $db_opcao = 3;
  
  try {
    
    db_inicio_transacao();    
    
    $clobrassituacao->excluir($oPost->ob28_sequencial);    
    
    if( $clobrassituacao->erro_status == '0' ){
      throw new Exception($clobrassituacao->erro_msg);
    }
    
    db_fim_transacao(false);    
    db_msgbox($clobrassituacao->erro_msg);
    db_redireciona($clobrassituacao->pagina_retorno);
    
  } catch (Exception $eErro) {
    
    db_fim_transacao(true);
    db_msgbox( $eErro->getMessage() );    
  } 

  if ($clobrassituacao->erro_status == "0") {
    $clobrassituacao->erro(true, false);
  } else {
    $clobrassituacao->erro(true, true);
  }
}

if ($db_opcao == 33 ) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
  js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>