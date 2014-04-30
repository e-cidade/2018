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
include("classes/db_procandam_classe.php");
include("classes/db_proctransfer_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_proctransand_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_mostra_andam(processo){ 
   window.db_iframe.jan.location.href='pro3_mosprocandam.php?codproc='+processo;
   db_iframe.mostraMsg();
   db_iframe.show();
   db_iframe.focus();
 }
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="40">&nbsp;</td>
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

 $sql = "select p58_codproc, 
                z01_nome,
                p51_descr,
                p58_obs 
         from   protprocesso inner join cgm on p58_numcgm = z01_numcgm
                inner join tipoproc on p58_codigo = p51_codigo
        ";
 $where = "";
 if (@$p58_codproc != ""){
    $where .= " p58_codproc = ".@ $p58_codproc ;     
 }                
 

 if (@$p58_requer != ""){
    if ($where != ""){
      $where .=" and ";
    }
    $where .= " p58_requer = '".@ $p58_requer."'";
 }
 
 if (@$p58_numcgm != ""){
    if ($where != ""){
      $where .=" and ";
    }
    $where .= " p58_numcgm = ". @$p58_numcgm;
 }

/* if (@$p58_id_usuario != ""){
    if ($where != ""){
      $where .=" and ";
    }
    $where .= " p61_id_usuario = ". @ $p58_id_usuario;
 }
 if (@$p58_coddepto != ""){
    if ($where != ""){
      $where .=" and ";
    }
    $where .= " p61_coddepto = ".@ $p58_coddepto;
 }*/
 if ($where == ""){
    $where = " ";
 }else{
   $where = "where ".$where;
}
$sql = $sql.$where;
db_lovrot($sql,10,"()","","js_mostra_andam|p58_codproc");
 ?>
</center>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=750;
$func_iframe->altura=400;
$func_iframe->titulo='Andamento do processo';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
</body>
</html>