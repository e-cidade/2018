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
include("classes/db_ossoario_classe.php");
include("classes/db_ossoariopart_classe.php");
include("classes/db_gavetas_classe.php");
include("classes/db_restos_classe.php");
include("classes/db_retiradas_classe.php");
include("classes/db_sepulturas_classe.php");
include("classes/db_sepultamentos_classe.php");
include("classes/db_sepulthist_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clossoario = new cl_ossoario;
$clossoariopart = new cl_ossoariopart;
$clsepulturas = new cl_sepulturas;
$clgavetas = new cl_gavetas;
$clrestos = new cl_restos;
$clretiradas = new cl_retiradas;
$clsepultamento = new cl_sepultamentos;
$clsepulthist = new cl_sepulthist;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  if(isset($antigo)){
   //sepulturas
   if($antigo == "sepultura"){
    $clsepulturas->excluir($cod);

   //ossoarios
   }elseif($antigo == "ossoario"){
    $clossoario->excluir($cod);

   //gavetas/jazigos
   }elseif($antigo == "resto"){
    $clrestos->excluir($cod);

   //retiradas
   }elseif($antigo == "retirada"){
    $clretiradas->excluir($cod);
   }
  }
  $clgavetas->incluir(null);
  if(isset($antigo)){
   $clsepulthist->cm21_i_sepultamento = $sepultamento;
   $clsepulthist->cm21_i_usuario      = db_getsession("DB_id_usuario");
   $clsepulthist->cm21_d_data         = date("Y/m/d",db_getsession("DB_datausu"));
   $clsepulthist->cm21_c_localnovo    = "Cod: $clgavetas->cm13_i_codigo Descr: Jazigo/Gavetas";
   $clsepulthist->cm21_c_localant     = "Cod: $cod Descr: $antigo";
   $clsepulthist->incluir($cm05_i_codigo);
  }
  db_fim_transacao();
}
 $result = $clsepultamento->sql_record($clsepultamento->sql_query($sepultamento,"cm01_i_codigo as cm13_i_sepultamento, cgm.z01_nome as nome_sepultamento"));
 db_fieldsmemory($result,0);
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmgavetas.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($clgavetas->erro_status=="0"){
    db_msgbox($clgavetas->erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clgavetas->erro_campo!=""){
      echo "<script> document.form1.".$clgavetas->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clgavetas->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($clgavetas->erro_msg);
   echo "<script>";
   if(!isset($antigo)){
    echo " top.corpo.iframe_a1.location.href='cem1_sepultamentos001.php';";
   }else{
    echo " top.corpo.iframe_a1.location.href='cem1_transacao001.php';";
    echo " parent.document.formaba.a4.disabled=true;";
   }
   echo " parent.mo_camada('a1'); ";
   echo "</script>";
  }
 }
?>