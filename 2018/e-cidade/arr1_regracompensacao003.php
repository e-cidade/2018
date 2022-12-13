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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_regracompensacao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clregracompensacao = new cl_regracompensacao;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
  $clregracompensacao->k155_instit = db_getsession('DB_instit');
  $clregracompensacao->excluir($k155_sequencial);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   
   $sCampos  = "regracompensacao.k155_sequencial                 , ";             
   $sCampos .= "regracompensacao.k155_tiporegracompensacao       , ";
   $sCampos .= "regracompensacao.k155_descricao                  , ";
   $sCampos .= "regracompensacao.k155_arretipoorigem             , ";
   $sCampos .= "regracompensacao.k155_arretipodestino            , ";
   $sCampos .= "regracompensacao.k155_percmaxuso                 , ";
   $sCampos .= "regracompensacao.k155_tempovalidade              , ";
   $sCampos .= "regracompensacao.k155_automatica                 , ";
   $sCampos .= "regracompensacao.k155_permitetransferencia       , ";
   $sCampos .= "regracompensacao.k155_instit                     , ";
   $sCampos .= "arretipoorigem.k00_descr  as k00_descricaoorigem , ";
   $sCampos .= "arretipodestino.k00_descr as k00_descricaodestino, ";
   $sCampos .= "tiporegracompensacao.k154_descricao                ";
   
   $result = $clregracompensacao->sql_record($clregracompensacao->sql_query($chavepesquisa, $sCampos)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>
<table width="790" align="center"  style="margin:30px auto">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
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
<?
if(isset($excluir)){
  if($clregracompensacao->erro_status=="0"){
    $clregracompensacao->erro(true,false);
  }else{
    $clregracompensacao->erro(true,true);
  }
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>