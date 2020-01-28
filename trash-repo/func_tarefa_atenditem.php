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
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatenditem = new cl_atenditem;
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
            <td width="4%" align="right" nowrap title="<?=$Tat05_seq?>">
              <?=$Lat05_seq?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at05_seq",4,$Iat05_seq,true,"text",4,"","chave_at05_seq");
		       ?>
            </td>
          </tr>
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
            <td width="4%" align="right" nowrap title="<?=$Tat05_solicitado?>">
              <?=$Lat05_solicitado?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("chave_at05_solicitado",40,"",true,"text",4);
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
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_atenditem.php")==true){
             include("funcoes/db_func_atenditem.php");
           }else{
           $campos = "atenditem.*";
           }
        }
        if(isset($chave_at05_seq) && (trim($chave_at05_seq)!="") ){
	         $sql = $clatenditem->sql_query_tarefa(@$chave_at05_seq,@$chave_at05_codatend,$campos,"at05_seq","at05_perc < 100 and at05_seq=$chave_at05_seq and at44_atenditem is null and at02_codtipo >= 100");
        }else if(isset($chave_at05_codatend) && (trim($chave_at05_codatend)!="") ){
	         $sql = $clatenditem->sql_query_tarefa(@$chave_at05_seq,@$chave_at05_codatend,$campos,"at05_codatend","at05_perc < 100 and at05_codatend=$chave_at05_codatend and at44_atenditem is null and at02_codtipo >= 100");
        }else if(isset($chave_at05_solicitado) && (trim($chave_at05_solicitado)!="") ){
	         $sql = $clatenditem->sql_query_tarefa("","",$campos,"at05_solicitado","at05_perc < 100 and at05_solicitado like '$chave_at05_solicitado%' and at44_atenditem is null and at02_codtipo >= 100");
        }else{
           $sql = $clatenditem->sql_query_tarefa("","",$campos,"at05_seq desc#at05_codatend","at05_perc < 100 and at44_atenditem is null and at02_codtipo >= 100");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clatenditem->sql_record($clatenditem->sql_query_tarefa(null,null,"*",null,"at05_perc < 100 and at05_seq = $pesquisa_chave and at44_atenditem is null and at02_codtipo >= 100"));
          if($clatenditem->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$at05_solicitado',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>