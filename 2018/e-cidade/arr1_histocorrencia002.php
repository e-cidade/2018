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
include("classes/db_histocorrencia_classe.php");
include("classes/db_histocorrenciamatric_classe.php");
include("classes/db_histocorrenciacgm_classe.php");
include("classes/db_histocorrenciainscr_classe.php");
include("dbforms/db_funcoes.php");




parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
db_postmemory($_GET);

$clhistocorrencia = new cl_histocorrencia;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  
  $clhistocorrencia->ar23_id_usuario   = db_getsession("DB_id_usuario");
  $clhistocorrencia->ar23_instit       = db_getsession("DB_instit");
  $clhistocorrencia->ar23_modulo       = db_getsession("DB_modulo");
  $clhistocorrencia->ar23_id_itensmenu = db_getsession("DB_itemmenu_acessado");
  db_inicio_transacao();
  $db_opcao = 2;
  $clhistocorrencia->alterar($ar23_sequencial);
  db_fim_transacao();
}else if((isset($chavepesquisa)) and (isset($tipoPesquisa)) and (isset($idchave))){
  
  $campos = "ar23_sequencial		, ";
  $campos .= "ar23_id_usuario		, ";
  $campos .= "ar23_instit				, ";
  $campos .= "ar23_modulo				, ";
  $campos .= "ar23_id_itensmenu	, ";
  $campos .= "ar23_data					, ";
  $campos .= "ar23_hora					, ";
  $campos .= "ar23_tipo					, ";
  $campos .= "ar23_descricao		, ";
  $campos .= "ar23_ocorrencia		";
  $result = $clhistocorrencia->sql_record($clhistocorrencia->sql_query($chavepesquisa, $campos));
  db_fieldsmemory($result, 0);
  
  if($tipoPesquisa == 'cgm'){
    $campos  = "ar24_numcgm, ";
    $campos .= "z01_nome";
    
    $clhistocorrenciacgm = new cl_histocorrenciacgm;
    $result = $clhistocorrenciacgm->sql_record($clhistocorrenciacgm->sql_query("", $campos, "", "ar24_histocorrencia = $ar23_sequencial"));
    db_fieldsmemory($result, 0);
    
    $z01_numcgm = $ar24_numcgm;

  }elseif($tipoPesquisa == 'matric'){
    $campos  = "ar25_matric, ";
    $campos .= "z01_nome";
        
    $clhistocorrenciamatric = new cl_histocorrenciamatric;
    $result = $clhistocorrenciamatric->sql_record($clhistocorrenciamatric->sql_query("", $campos, "", "ar25_histocorrencia = $ar23_sequencial"));
    db_fieldsmemory($result, 0);
    
    $j01_matric = $ar25_matric;
    
  }elseif($tipoPesquisa == 'inscr') {
    $campos  = "ar26_inscr, ";
    $campos .= "z01_nome";
        
    $clhistocorrenciainscr = new cl_histocorrenciainscr;
    $result = $clhistocorrenciainscr->sql_record($clhistocorrenciainscr->sql_query("", $campos, "", "ar26_histocorrencia = $ar23_sequencial"));
    db_fieldsmemory($result, 0);
    
    $q02_inscr = $ar26_inscr;
  }
  
  if (($ar23_id_usuario == db_getsession("DB_id_usuario") || db_getsession('DB_administrador') == '1' )) {
    $db_botao = true;
  } else {
    $db_botao = false;
  }
  
  $db_opcao = 2;

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmhistocorrencia.php");
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
if(isset($alterar)){
  if($clhistocorrencia->erro_status=="0"){
    $clhistocorrencia->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clhistocorrencia->erro_campo!=""){
      echo "<script> document.form1.".$clhistocorrencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clhistocorrencia->erro_campo.".focus();</script>";
    }
  }else{
    $clhistocorrencia->erro(true,true);
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","ar23_id_usuario",true,1,"ar23_id_usuario",true);


</script>