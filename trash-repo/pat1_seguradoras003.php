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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_seguradoras_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clseguradoras = new cl_seguradoras;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  if($sqlerro == false){
    $clseguradoras->excluir($t80_segura);
    if($clseguradoras->erro_status == 0){
      $sqlerro = true;
    }
    $erro_msg = $clseguradoras->erro_msg;
    db_fim_transacao($sqlerro);
    $t80_segura ="";
    $t80_numcgm ="";
    $z01_nome = "";
    $t80_contato = "";
  }
}else if(isset($chavepesquisa)){
  $result = $clseguradoras->sql_record($clseguradoras->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
  $db_opcao = 3;
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
	include("forms/db_frmseguradoras.php");
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
    if($clseguradoras->erro_campo!=""){
      echo "<script> document.form1.".$clseguradoras->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clseguradoras->erro_campo.".focus();</script>";
    };
  };
};

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>