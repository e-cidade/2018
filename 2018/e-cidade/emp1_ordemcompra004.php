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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

include("classes/db_cgm_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemanu_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_matestoqueitemoc_classe.php");

$clmatordem         = new cl_matordem;
$clmatordemanu      = new cl_matordemanu;
$clmatordemitem     = new cl_matordemitem;
$clempempenho       = new cl_empempenho;
$clcgm              = new cl_cgm;
$cldbdepart         = new cl_db_depart;
$clmatestoqueitemoc = new cl_matestoqueitemoc;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

if (isset($anula)){
  db_inicio_transacao();
  $sqlerro = false;
  $m53_data="$m53_data_ano-$m53_data_mes-$m53_data_dia";

  $clmatordemanu->m53_data = $m53_data; 
  $clmatordemanu->m53_obs = $m53_obs;
  $clmatordemanu->incluir($m51_codordem);
  if($clmatordemanu->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg = $clmatordemanu->erro_msg;
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
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"><center>
    <?
      include("forms/db_frmmatordem.php");

if (isset($m51_codordem)&&trim($m51_codordem)!=""){
     $resultado = $clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query(null,null,
             "m73_codmatordemitem",null,"m51_codordem = $m51_codordem and m73_cancelado is false"));     
     
     if ($clmatestoqueitemoc->numrows > 0){
          db_msgbox("Ordem nao pode ser anulada itens ja em estoque!");
          echo"<script>top.corpo.location.href='emp1_ordemcompra003.php';</script>";
     }
}
    ?></center></td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<?
if (isset($anula)){
    db_msgbox($erro_msg);
    if($clmatordemanu->erro_campo!=""){
      echo "<script> document.form1.".$clmatordemanu->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatordemanu->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='emp1_ordemcompra003.php';</script>";
    }
}
?>
</body>
</html>