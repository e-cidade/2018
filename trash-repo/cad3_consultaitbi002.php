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
include("classes/db_db_itbi_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_itbi = new cl_db_itbi;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$campos = " j01_matric,z01_numcgm,j34_setor,j34_quadra,z01_nome,j14_nome";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<?
if(isset($j34_setor) && (trim($j34_setor)!="") ){
  $where = " where ";
  if($j34_quadra != ""){
    $where .= " j34_quadra = '$j34_quadra'  and j34_setor = '$j34_setor'";
  }elseif($j14_codigo != ""){  
    $where .= " j14_codigo = '$j14_codigo'  and j34_setor = '$j34_setor'";
  }else{
    $where .= " j34_setor = '$j34_setor'";
  }
  $sql ="select $campos from iptubase 
		inner join lote on lote.j34_idbql = iptubase.j01_idbql
		inner join cgm on cgm.z01_numcgm = iptubase.j01_numcgm
		inner join bairro on bairro.j13_codi = lote.j34_bairro
		inner join setor on setor.j30_codi = lote.j34_setor 
		left outer join iptuant on iptubase.j01_matric = iptuant.j40_matric
		inner join db_itbi on j01_matric = matricula
		inner join testpri on j49_idbql = j01_idbql
		inner join ruas on j49_codigo = j14_codigo
		$where";
  $result = $cldb_itbi->sql_record($sql);
  if($cldb_itbi->numrows == 1){
    db_fieldsmemory($result,0);
    $funcao = split("\\|",$funcao_js);
    echo "<script>".$funcao[0]."('$j01_matric');</script>";
    echo "<script>parent.db_iframe_consultaitbi.hide();</script>";
    exit;
  }
}
?>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align="center" valign="top">
      <? 
        db_lovrot($sql,15,"()","",$funcao_js);
      ?>
     </td>
   </tr>
  <tr> 
    <td align="center" valign="top">
      <input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultaitbi.hide();" > 
    </td>
  </tr>
</table>
</body>
</html>