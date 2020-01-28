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
include("classes/db_atenditem_classe.php");
include("classes/db_db_ordematend_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatenditem = new cl_atenditem;
$cldb_ordematend = new cl_db_ordematend;
$clatenditem->rotulo->label("at05_seq");
$clatenditem->rotulo->label("at05_codatend");
$clatenditem->rotulo->label("at05_solicitado");
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
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tat05_codatend?>">
              <?=$Lat05_codatend?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at05_codatend",6,$Iat05_codatend,true,"text",4,"","chave_at05_codatend");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        $campos = "at01_nomecli,at02_dataini,at02_horaini,at02_horafim,atenditem.*";
	$result = $cldb_ordematend->sql_record($cldb_ordematend->sql_query_file("","","","*"));
        $where = "";
	if($cldb_ordematend->numrows > 0){
	  $numrows = $cldb_ordematend->numrows;
	  for($i=0;$i<$numrows;$i++){
	    db_fieldsmemory($result,$i);
	    if($i == 0){
	      $where .= " at05_seq <> $or10_seq and at05_tipo = 1";
	    }else{
	      $where .= " and at05_seq <> $or10_seq";
	    }
	  }
	}
	if(isset($chave_at05_codatend) && (trim($chave_at05_codatend)!="") ){
	  $sql = $clatenditem->sql_query("","",$campos,"at05_seq"," $where ".($where == ""?" at05_codatend = $chave_at05_codatend":" and at05_tipo = 1 and at05_codatend = $chave_at05_codatend")."");
        }else{
          $sql = $clatenditem->sql_query("","",$campos,"at05_seq desc","$where");
        }
	db_lovrot($sql,15,"()","",$funcao_js);
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>