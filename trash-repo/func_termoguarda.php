<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_bensguarda_classe.php");

$oGet = db_utils::postMemory($_GET);

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbensguarda = new cl_bensguarda;
$clbensguarda->rotulo->label("t21_codigo");
$clbensguarda->rotulo->label("t21_codigo");
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
            <td width="4%" align="right" nowrap title="<?=$Tt21_codigo?>">
              <?=$Lt21_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("t21_codigo",8,$It21_codigo,true,"text",4,"","chave_t21_codigo");
		       ?>
            </td>
          </tr>          
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bensguarda.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $aWhere   = array();
      $aWhere[] = "t21_instit = ".db_getsession("DB_instit");
      
      if (isset($oGet->devolucao) && $oGet->devolucao == "true") {
        $aWhere[] = " t23_guardaitem is not null";
      }
      
      $sWhereGuarda = implode(" and", $aWhere);
      
      $sCampos      = " distinct bensguarda.t21_codigo, bensguarda.t21_numcgm, cgm.z01_nome, bensguarda.t21_tipoguarda, ";
      $sCampos     .= " bensguarda.t21_data, bensguarda.t21_obs";
      
      if (!isset($pesquisa_chave)) {

        if(isset($chave_t21_codigo) && (trim($chave_t21_codigo)!="") ){
          $sql = $clbensguarda->sql_query_dev(null, $sCampos, "t21_codigo","t21_codigo = {$chave_t21_codigo} and {$sWhereGuarda}");
        }else{
          $sql = $clbensguarda->sql_query_dev("", $sCampos, "t21_codigo", $sWhereGuarda);
        }
        $repassa = array();
        
        if (isset($chave_t21_codigo)) {
          $repassa = array("chave_t21_codigo"=>$chave_t21_codigo);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $sWhere = "t21_codigo = {$pesquisa_chave} and {$sWhereGuarda} ";
          $sSql   = $clbensguarda->sql_query_dev(null, $sCampos, " t21_codigo ", $sWhere);
          $result = $clbensguarda->sql_record($sSql);
          
          if ($clbensguarda->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."(false, '$t21_codigo', '$t21_numcgm', '$z01_nome');</script>";
          }else{
	         echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
          }
        }else{
	       echo "<script>".$funcao_js."(false, '');</script>";
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
js_tabulacaoforms("form2","chave_t21_codigo",true,1,"chave_t21_codigo",true);
</script>