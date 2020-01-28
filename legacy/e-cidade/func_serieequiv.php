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
include("classes/db_serie_classe.php");
include("classes/db_serieequiv_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clserie = new cl_serie;
$clserieequiv = new cl_serieequiv;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 11;
 font-weight: bold;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="1"  align="center" cellspacing="0" cellpading="2" bgcolor="#CCCCCC">
 <?
 $result = $clserie->sql_record($clserie->sql_query_equiv("","*","ed10_c_abrev,ed11_i_sequencia",""));
 $primeiro = "";
 for($x=0;$x<$clserie->numrows;$x++){
  db_fieldsmemory($result,$x);
  if($primeiro!=$ed11_i_ensino){
   ?>
   <tr class="titulo">
    <td colspan="2"><?=$ed10_c_descr?></td>
   </tr>
   <tr class="cabec1">
    <td width="25%"><b>Etapa</b></td>
    <td><b>Etapas Equivalentes</b></td>
   </tr>
   <?
   $primeiro = $ed11_i_ensino;
  }
  ?>
  <tr bgcolor="#f3f3f3">
   <td class="aluno" width="25%">&nbsp;&nbsp;<a href="javascript:parent.db_iframe_geral.hide();parent.location.href='edu1_serieequiv001.php?ensino=<?=$ed11_i_ensino?>&serie=<?=$ed11_i_codigo?>'"><?=$ed11_c_descr?></a></td>
   <td>
    <?
    $result1 = $clserieequiv->sql_record($clserieequiv->sql_query("","serie1.ed11_c_descr as seriedescr,ensino1.ed10_c_descr as ensinodescr","ensino1.ed10_c_abrev,serie1.ed11_i_sequencia"," ed234_i_serie = $ed11_i_codigo"));
    if($clserieequiv->numrows==0){
     echo "Nenhum registro.";
    }else{
     for($y=0;$y<$clserieequiv->numrows;$y++){
      db_fieldsmemory($result1,$y);
      ?>
      <table width="60%" border="0" cellspacing="0" cellpading="2">
       <tr>
        <td class="aluno1"width="30%"><b>-> <?=$seriedescr?></b></td>
        <td class="aluno1"><?=$ensinodescr?></td>
       </tr>
      </table>
      <?
     }
    }
    ?>
   </td>
  </tr>
  <?
 }
 ?>
</table>
</body>
</html>