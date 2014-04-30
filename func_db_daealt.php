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
include("classes/db_db_dae_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_dae = new cl_db_dae;
$cldb_dae->rotulo->label("w04_codigo");
$cldb_dae->rotulo->label("w04_inscr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
	     <form name="form2" method="post" action="" >
        </form>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = "";
      if(isset($z01_numcgm) && $z01_numcgm != "" ){
        $where = " z01_numcgm = $z01_numcgm";
      }
      if(isset($q02_inscr) && $q02_inscr != ""){
        if($where != ""){
          $where.= " and ";
        }
          $where.= " q02_inscr = $q02_inscr";
      }
      if(isset($dia) && $dia != ""){
        if($where != ""){
          $where .= " and ";
        }
        $where .= "  w04_data = '".$ano."-".$mes."-".$dia."'";
      }
      $sql = "select w04_codigo,w04_inscr,w04_enviado,w04_ano,w04_data from db_dae inner join issbase on w04_inscr = q02_inscr inner join cgm on z01_numcgm = q02_numcgm ".($where != ""?' where '.$where:"")." order by w04_inscr";
      db_lovrot($sql,15,"()","",$funcao_js);
      ?>
     </td>
   </tr>
</table>
</body>
</html>