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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_obrassituacao_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost           = db_utils::postmemory($HTTP_POST_VARS);
$clobrassituacao = new cl_obrassituacao;
$db_opcao        = 1;
$db_botao        = true;
$lErro           = false;
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
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ob28_descricao",true,1,"ob28_descricao",true);
</script>
<?
if ( isset($oPost->incluir) ) {
  
    try {
  
      db_inicio_transacao();
  
      $clobrassituacao->ob28_descricao = $oPost->ob28_descricao;
      $clobrassituacao->incluir($oPost->ob28_sequencial);
  
      if ((int)$clobrassituacao->erro_status == 0) {
        throw new Exception($clobrassituacao->erro_msg);
      }
  
      db_fim_transacao(false);
  
    } catch (Exception $eErro) {
      db_msgbox( $eErro->getMessage() );
      db_fim_transacao(true);
    }
  
  if ($clobrassituacao->erro_status == "0") {
    
    $clobrassituacao->erro(true,false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled = false;</script>  ";

    if ($clobrassituacao->erro_campo!="") {

      echo "<script> document.form1.".$clobrassituacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clobrassituacao->erro_campo.".focus();</script>";
    }

  }else{
    $clobrassituacao->erro(true,true);
  }
}
?>