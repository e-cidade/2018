<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_notasiss_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clnotasiss = new cl_notasiss;
$clnotasiss->rotulo->label("q09_nota");
$clnotasiss->rotulo->label("q09_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tq09_nota?>">
              <?=$Lq09_nota?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("q09_nota",5,$Iq09_nota,true,"text",4,"","chave_q09_nota");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tq09_descr?>">
              <?=$Lq09_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("q09_descr",40,$Iq09_descr,true,"text",4,"","chave_q09_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar"    type="button" onclick="this.form.reset()"  id="limpar"     value="Limpar" >
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
           $campos = "notasiss.*, gruponotaiss.q139_descricao";
        }
        if(isset($chave_q09_nota) && (trim($chave_q09_nota)!="") ){
	         $sql = $clnotasiss->sql_query($chave_q09_nota,$campos,"q09_nota");
        }else if(isset($chave_q09_descr) && (trim($chave_q09_descr)!="") ){
	         $sql = $clnotasiss->sql_query("",$campos,"q09_descr"," q09_descr like '$chave_q09_descr%' ");
        }else{
           $sql = $clnotasiss->sql_query("",$campos,"q09_nota","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        $result = $clnotasiss->sql_record($clnotasiss->sql_query($pesquisa_chave));
        if($clnotasiss->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$q09_descr',false);</script>";
        }else{
	       echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
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
document.form2.chave_q09_nota.focus();
document.form2.chave_q09_nota.select();
  </script>
  <?
}
?>