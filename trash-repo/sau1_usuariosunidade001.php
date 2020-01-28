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
include("classes/db_usuariosunidade_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_unidades_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clusuariosunidade = new cl_usuariosunidade;
$clunidade = new cl_unidades;
$clusuario = new cl_db_usuarios;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$db_opcao = 22;
$db_botao = false;

if(isset($incluir)){
  $sqlerro = false;
  db_inicio_transacao();

  //verifica se o Usuario ja está cadastrado em alguma unidade
  $result = $clusuariosunidade->sql_record($clusuariosunidade->sql_query($sd25_i_usuario));
  if($clusuariosunidade->numrows != 0){
  $m = 1;
    db_msgbox("Usuário já cadastrado para outra Unidade");
  }else{
  $clusuariosunidade->incluir($sd25_i_usuario,$sd25_i_unidade);
  $erro_msg = $clusuariosunidade->erro_msg;
  if($clusuariosunidade->erro_status==0){ $sqlerro=true; }
  }
  db_fim_transacao();
}elseif(isset($alterar)){
  $sqlerro = false;
  db_inicio_transacao();
   //especialidade
    $clusuariosunidade->sd25_i_usuario=$sd25_i_usuario;

    $clusuariosunidade->alterar($sd25_i_usuario,$sd25_i_unidade,'sd25_i_unidade = $sd25_i_unidade');
     $erro_msg = $clusuariosunidade->erro_msg;
     if($clusuariosunidade->erro_status=="0"){
       $sqlerro=true;
     }
 db_fim_transacao($sqlerro);
}elseif(isset($excluir)){
    $sqlerro=false;
    db_inicio_transacao();
    $clusuariosunidade->excluir($sd25_i_usuario,$sd25_i_unidade);
    $erro_msg = $clusuariosunidade->erro_msg;
    if($clusuariosunidade->erro_status=="0"){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
}
elseif(isset($chavepesquisa)){
   $db_opcao = 1;
   $sd25_i_unidade = $chavepesquisa;
   @$result = $clunidade->sql_record($clunidade->sql_query($chavepesquisa));
   @db_fieldsmemory($result,0);
      $db_botao = true;
}elseif(isset($opcao) && empty($consultando)){
 @$result = $clusuariosunidade->sql_record($clusuariosunidade->sql_query($sd25_i_unidade));
 @db_fieldsmemory($result,0);
}
if(isset($sd25_i_usuario))
 {
  @$result1 = $clusuario->sql_record($clusuario->sql_query($sd25_i_usuario));
  @db_fieldsmemory($result1,0);
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmusuariosunidade.php");
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
if( (isset($incluir))||(isset($alterar))||(isset($excluir)) ){
  if($clusuariosunidade->erro_status=="0"){
    $clusuariosunidade->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clusuariosunidade->erro_campo!=""){
      echo "<script> document.form1.".$clusuariosunidade->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clusuariosunidade->erro_campo.".focus();</script>";
    }
  }else{
   if($m != 1){
    db_msgbox($erro_msg);
   }
  }
}
if($db_opcao==22){
  echo "<script>js_pesquisa();</script>";
}
?>