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
include("dbforms/db_funcoes.php");
include("classes/db_atendusucliproced_classe.php");
include("classes/db_db_modulos_classe.php");
include("classes/db_db_menu_classe.php");
include("classes/db_db_itensmenu_classe.php");
$clatendusucliproced = new cl_atendusucliproced;
$cldb_modulos = new cl_db_modulos;
$cldb_menu = new cl_db_menu;
$cldb_itensmenu = new cl_db_itensmenu;
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
if(isset($incluir)){
  db_inicio_transacao();
  $clatendusucliproced->excluir(null,"at82_usucliitem = ".$at82_usucliitem);
  $erro_msg = $clatendusucliproced->erro_msg;
  if($clatendusucliproced->erro_status != "0"){
    $sqlerro = false;
    if(isset($CHECK)){
      for($i=0; $i<count($CHECK); $i++) {
        $aux = explode("#",$CHECK[$i]);
        $clatendusucliproced->at82_usucliitem = $at82_usucliitem;
        $clatendusucliproced->at82_id_item = $aux[0];
        $clatendusucliproced->at82_id_item_filho = $aux[1];
        $clatendusucliproced->at82_modulo = $aux[2];
        $clatendusucliproced->incluir(null);
        $erro_msg = $clatendusucliproced->erro_msg;
        if($clatendusucliproced->erro_status == "0"){
          $sqlerro = true;
          break;
        }
      }
    }
  }else{
    $sqlerro = true;
  }
  db_fim_transacao();
}

$result_modulo = $clatendusucliproced->sql_record($clatendusucliproced->sql_query_file(null, " at82_modulo ",""," at82_usucliitem = $at82_usucliitem "));
if($clatendusucliproced->numrows > 0){
  db_fieldsmemory($result_modulo, 0);
  $result = $cldb_modulos->sql_record($cldb_modulos->sql_query_file(null,"id_item||'--'||descr_modulo||'||'||nome_modulo as mod",""," id_item = ".$at82_modulo));
  if($cldb_modulos->numrows > 0){
    db_fieldsmemory($result, 0);
    $modulos[0] = $mod;
    $selecionar = "selecionar";
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <?include("forms/db_frmatendusucliproced.php")?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro == true){
    if($clatendusucliproced->erro_campo!=""){
      echo "<script> document.form1.".$clatendusucliproced->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clatendusucliproced->erro_campo.".focus();</script>";
    }
  }else if(isset($CHECK)){
    echo "<script>parent.db_iframe_menus.hide();</script>";
  }
}
?>