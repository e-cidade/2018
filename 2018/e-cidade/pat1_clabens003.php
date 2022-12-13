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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require_once("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_clabens_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_cfpatri_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clclabens            = new cl_clabens;
$cldb_estrut          = new cl_db_estrut;
$clcfpatri            = new cl_cfpatri;
$oDaoClabensconplano  = db_utils::getDao("clabensconplano");

if(isset($t64_codcla)){
    $db_opcao = 3;
    $db_botao = true;
}else{
    $db_opcao = 33;
    $db_botao = false; 
}
if(isset($excluir)){
    $sqlerro=false;
    db_inicio_transacao();					
    //rotina para verificar a estrutura
    //$cldb_estrut->db_estrut_exclusao($t64_class,$mascara,"clabens","t64_class");
    //if($cldb_estrut->erro_status==0){
    //  $erro_msg = $cldb_estrut->erro_msg;
    //  $sqlerro=true;
    //}
    if($sqlerro==false){
      
      
      $oDaoClabensconplano->excluir(null,"t86_clabens = {$t64_codcla}");
      $clclabens->t64_codcla = $t64_codcla;
      $clclabens->excluir($t64_codcla);
      if($clclabens->erro_status==0){
        $sqlerro=true;
      }
      $erro_msg = $clclabens->erro_msg;
    }
    db_fim_transacao($sqlerro);
    if($sqlerro==false){
      $t64_clas="";
      $t64_descr="";
      $t64_obs="";
      $c60_descr="";
    }
    }else if(isset($chavepesquisa)){
      
      $sCampos  = " t64_codcla, t64_class, t64_descr, t64_obs, t64_analitica,";
      $sCampos .= " t64_bemtipos, t64_benstipodepreciacao, t64_vidautil, ";
      $sCampos .= " t46_descricao,";
      $sCampos .= " t86_conplano, t86_conplanodepreciacao,";
      $sCampos .= " contadepreciacao.c60_descr as descricaocontadepreciacao, conta.c60_descr as descricaoconta";
      $result = $clclabens->sql_record($clclabens->sql_query_contas($chavepesquisa,$sCampos));
      db_fieldsmemory($result,0);
      $db_opcao = 3;
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
	include("forms/db_frmclabens.php");
	?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($excluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clclabens->erro_campo!=""){
      echo "<script> document.form1.".$clclabens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clclabens->erro_campo.".focus();</script>";
    };
  }else{
    db_redireciona("pat1_clabens003.php");
  };
};

if($db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>