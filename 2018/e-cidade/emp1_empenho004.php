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
include("classes/db_empautoriza_classe.php");
include("classes/db_empauthist_classe.php");
include("classes/db_emphist_classe.php");
include("classes/db_emptipo_classe.php");
include("classes/db_cflicita_classe.php");
include("classes/db_pctipocompra_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clempautoriza = new cl_empautoriza;
$clempauthist = new cl_empauthist;
$clemphist = new cl_emphist;
$clemptipo = new cl_emptipo;
$clcflicita = new cl_cflicita;
$clpctipocompra = new cl_pctipocompra;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $clempautoriza->e54_autori = $e54_autori;
  $clempautoriza->e54_numcgm = $e54_numcgm;
  $clempautoriza->e54_login  = db_getsession("DB_id_usuario") ;
  $clempautoriza->e54_resumo = $e54_resumo;
  $clempautoriza->e54_anousu = db_getsession("DB_anousu");;
  $clempautoriza->e54_codcom = $e54_codcom;
  $clempautoriza->e54_destin = $e54_destin;
  $clempautoriza->e54_tipol  = $e54_tipol ;
  $clempautoriza->e54_numerl = $e54_numerl;
  $clempautoriza->e54_emiss  = date("Y-m-d",db_getsession("DB_datausu"));
  $clempautoriza->e54_codtipo = $e54_codtipo;
  $clempautoriza->e54_instit = db_getsession("DB_instit");
  
  $clempautoriza->e54_valor  = '';
  $clempautoriza->e54_praent = '';
  $clempautoriza->e54_entpar = '';
  $clempautoriza->e54_conpag = '';
  $clempautoriza->e54_codout = '';
  $clempautoriza->e54_contat = '';
  $clempautoriza->e54_telef  = '';
  $clempautoriza->e54_numsol = '';
  $clempautoriza->e54_anulad = null;
  $clempautoriza->incluir(null);
  if($clempautoriza->erro_status==0){
    $sqlerro=true;
  }
  $e54_autori = $clempautoriza->e54_autori;
  if($sqlerro==false && $e57_codhist!="Nenhum"){
    $clempauthist->e57_autori=$e54_autori;
    $clempauthist->e57_codhist=$e57_codhist;
    $clempauthist->incluir($e54_autori);
    if($clempauthist->erro_status==0){
      $sqlerro=true;
    }
  }
  db_fim_transacao($sqlerro);
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
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmempenho.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clempautoriza->erro_status=="0"){
    $clempautoriza->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clempautoriza->erro_campo!=""){
      echo "<script> document.form1.".$clempautoriza->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clempautoriza->erro_campo.".focus();</script>";
    }
  }else{
    $clempautoriza->erro(true,false);
    echo "
           <script>
                parent.document.formaba.empautitem.disabled=false;\n
                parent.document.formaba.empautidot.disabled=false;\n
                parent.document.formaba.prazos.disabled=false;\n
		top.corpo.iframe_empautitem.location.href='emp1_empautitem001.php?e55_autori=$e54_autori';\n
		top.corpo.iframe_empautidot.location.href='emp1_empautidot001.php?e56_autori=$e54_autori';\n
		top.corpo.iframe_prazos.location.href='emp1_empautoriza007.php?e54_autori=$e54_autori';\n
                parent.mo_camada('empautitem');
	   </script>
         ";  
    db_redireciona("emp1_empautoriza005.php?outro=true&chavepesquisa=$e54_autori");
  }
}
?>