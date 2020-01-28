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
include("classes/db_histocorrenciacgm_classe.php");
include("classes/db_histocorrenciamatric_classe.php");
include("classes/db_histocorrenciainscr_classe.php");
include("dbforms/db_funcoes.php");
require("libs/db_utils.php");

db_postmemory($_POST);
db_postmemory($_GET);

$mostra_input = false;

$ar23_data_dia = date("d");
$ar23_data_mes = date("m");
$ar23_data_ano = date("Y");

$ar23_hora = date("H:i");


$clhistocorrencia = new cl_histocorrencia;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  
  $clhistocorrencia->ar23_id_usuario   = db_getsession("DB_id_usuario");
  $clhistocorrencia->ar23_instit       = db_getsession("DB_instit");
  $clhistocorrencia->ar23_modulo       = db_getsession("DB_modulo");
  $clhistocorrencia->ar23_id_itensmenu = db_getsession("DB_itemmenu_acessado");
  
  db_inicio_transacao();
  $clhistocorrencia->incluir($ar23_sequencial);
  db_fim_transacao();
  
  db_putsession("z01_nome", $z01_nome);
  if(isset($z01_numcgm) and ($z01_numcgm != '')) {
    $clhistocorrenciacgm = new cl_histocorrenciacgm;  
    $clhistocorrenciacgm->ar24_numcgm         = $z01_numcgm;
    $clhistocorrenciacgm->ar24_histocorrencia = $clhistocorrencia->ar23_sequencial;
    db_inicio_transacao();
    $clhistocorrenciacgm->incluir($ar24_sequencial);
    db_fim_transacao();
    db_putsession("z01_numcgm", $z01_numcgm);
    
  }elseif(isset($j01_matric) and ($j01_matric != '')) {
    $clhistocorrenciamatric = new cl_histocorrenciamatric;
    $clhistocorrenciamatric->ar25_matric         = $j01_matric;
    $clhistocorrenciamatric->ar25_histocorrencia = $clhistocorrencia->ar23_sequencial;
    db_inicio_transacao();
    $clhistocorrenciamatric->incluir($ar25_sequencial);
    db_fim_transacao();
    db_putsession("j01_matric", $j01_matric);
    
  }elseif(isset($q02_inscr) and ($q02_inscr != '')) {
    $clhistocorrenciainscr = new cl_histocorrenciainscr;
    $clhistocorrenciainscr->ar26_inscr          = $q02_inscr;
    $clhistocorrenciainscr->ar26_histocorrencia = $clhistocorrencia->ar23_sequencial;
    db_inicio_transacao();
    $clhistocorrenciainscr->incluir($ar26_sequencial);
    db_fim_transacao();
    db_putsession("q02_inscr", $q02_inscr);
    
  }
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
<script>
js_tabulacaoforms("form1","ar23_id_usuario",true,1,"ar23_id_usuario",true);
</script>
<?
if(isset($incluir)){
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
}else {
  db_destroysession("j01_matric");
  db_destroysession("z01_numcgm");
  db_destroysession("q02_inscr");
  db_destroysession("z01_nome");
  /*$j01_matric = "";
  $z01_numcgm = "";
  $q02_inscr  = "";
  $z01_nome   = "";*/
}
?>