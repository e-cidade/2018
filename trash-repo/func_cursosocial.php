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
include("classes/db_cursosocial_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcursosocial = new cl_cursosocial;
$clcursosocial->rotulo->label("as19_sequencial");
$clcursosocial->rotulo->label("as19_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Tas19_sequencial?>">
              <?=$Las19_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("as19_sequencial",10,$Ias19_sequencial,true,"text",4,"","chave_as19_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tas19_nome?>">
              <?=$Las19_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("as19_nome",70,$Ias19_nome,true,"text",4,"","chave_as19_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cursosocial.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere     = '';
      $sSeparador = '';
      $dtAtual    = date("Y-m-d");
      
      if (isset($lAtivos)) {

        $sSeparador  = ' and ';
        $sWhere     .= " '{$dtAtual}' between as19_inicio and as19_fim ";
      }
      
      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

           if (file_exists("funcoes/db_func_cursosocial.php") == true) {
             include("funcoes/db_func_cursosocial.php");
           } else {
             $campos = "cursosocial.*";
           }
        }
        
        if (isset($chave_as19_sequencial) && (trim($chave_as19_sequencial) != "") ) {
	         $sql = $clcursosocial->sql_query($chave_as19_sequencial, $campos, "as19_sequencial", $sWhere);
        } else if (isset($chave_as19_nome) && (trim($chave_as19_nome) != "") ) {

           $sWhere .= "{$sSeparador} as19_nome like '$chave_as19_nome%' ";
	         $sql     = $clcursosocial->sql_query_completo("", $campos, "as19_nome", $sWhere);
        } else {
           $sql = $clcursosocial->sql_query_completo("", $campos, "as19_sequencial", $sWhere);
        }
        
        $repassa = array();
        if (isset($chave_as19_nome)) {
          $repassa = array("chave_as19_sequencial"=>$chave_as19_sequencial, "chave_as19_nome"=>$chave_as19_nome);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sWhere .= " {$sSeparador} as19_sequencial = {$pesquisa_chave}";
          $result = $clcursosocial->sql_record($clcursosocial->sql_query(null, "*", null, $sWhere));
          if ($clcursosocial->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$as19_nome',false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
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
<script>
js_tabulacaoforms("form2","chave_as19_nome",true,1,"chave_as19_nome",true);
</script>