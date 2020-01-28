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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_regracompensacao_classe.php");
require_once ("classes/db_arretipo_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$clregracompensacao = new cl_regracompensacao;
$clarretipo         = new cl_arretipo();
$db_opcao           = 1;
$db_botao           = true;
$lErro              = false;

if (isset($k155_arretipoorigem) && $k155_arretipoorigem != '') {
  
  $sSqlArretipo = $clarretipo->sql_query_file($k155_arretipoorigem, 'k00_receitacredito, k00_tipo, k00_descr');
  
  $rsArretipo   = $clarretipo->sql_record($sSqlArretipo);
   
  $oArretipo    = db_utils::fieldsMemory($rsArretipo, 0);
  
  if($oArretipo->k00_receitacredito == '') {
    
    $clregracompensacao->erro_status = "0";
    
    $clregracompensacao->erro_msg    = "Receita de crédito não configurada no tipo de débito de origem: \\n\\n {$oArretipo->k00_tipo} - $oArretipo->k00_descr.\\n\\n";
    
    $clregracompensacao->erro_msg   .= "Verifique o cadastro";
    
    $clregracompensacao->erro_campo  = "k155_arretipoorigem";
    
    $lErro                           = true;
    
  }
  
}

if(isset($incluir) and !$lErro){
  
  db_inicio_transacao();
  
  $clregracompensacao->k155_instit = db_getsession('DB_instit');
  $clregracompensacao->incluir($k155_sequencial);

  db_fim_transacao();
  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - Pk00_receitacredito&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
<table width="790" style="margin:30px auto">
  <tr> 
    <td> 
    <center>
  	<?
  	  include("forms/db_frmregracompensacao.php");
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
<script>
js_tabulacaoforms("form1","k155_tiporegracompensacao",true,1,"k155_tiporegracompensacao",true);
</script>
<?
if(isset($incluir)){
  if($clregracompensacao->erro_status=="0"){
    $clregracompensacao->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clregracompensacao->erro_campo!=""){
      echo "<script> document.form1.".$clregracompensacao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clregracompensacao->erro_campo.".focus();</script>";
    }
  }else{
    $clregracompensacao->erro(true,true);
  }
}
?>