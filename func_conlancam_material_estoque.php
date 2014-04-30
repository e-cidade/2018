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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_conlancam_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconlancam = new cl_conlancam;
$clconlancam->rotulo->label("c70_codlan");
$clconlancam->rotulo->label("c70_anousu");

$oRotuloRequiItem = new rotulo('matrequiitem');
$oRotuloRequiItem->label('m41_codmatrequi');

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
            <td width="4%" align="right" nowrap title="<?=$Tc70_codlan?>">
              <?=$Lc70_codlan?>
            </td>
            <td width="96%" align="left" nowrap>
              <?  db_input("c70_codlan",10,$Ic70_codlan,true,"text",4,"","chave_c70_codlan");  ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tc70_anousu?>">
              <?=$Lc70_anousu?>
            </td>
            <td width="96%" align="left" nowrap>
              <?  db_input("c70_anousu",10,$Ic70_anousu,true,"text",4,"","chave_c70_anousu");  ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tc70_anousu?>">
              <b>Requisição:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?  db_input("m41_codmatrequi",10,$Im41_codmatrequi,true,"text",4);  ?>
            </td>
          </tr>



          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframeconlancam.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $aWherePadrao   = array();
      $aWherePadrao[] = "c70_data >= '2013-01-01'";
      $aWherePadrao[] = "c71_coddoc = 400";

      $campos = "distinct matrequi.*, descrdepto";
      if(!isset($pesquisa_chave)){



        if(isset($chave_c70_codlan) && (trim($chave_c70_codlan)!="") ){

	         $sql = $clconlancam->sql_query($chave_c70_codlan,$campos,"c70_codlan");

        }else if(isset($chave_c70_anousu) && (trim($chave_c70_anousu)!="") ){

          $aWherePadrao[] = "c70_anousu = {$chave_c70_anousu}";
	        $sql = $clconlancam->sql_query_lancamento_requisicao_material(null, $campos,"m40_codigo desc", implode(" and ", $aWherePadrao));

        } else if(isset($m41_codmatrequi) && (trim($m41_codmatrequi)!="") ){

          $aWherePadrao[] = "m41_codmatrequi = {$m41_codmatrequi}";
	        $sql = $clconlancam->sql_query_lancamento_requisicao_material(null, $campos,"m40_codigo desc", implode(" and ", $aWherePadrao));
        } else {

           $sql = $clconlancam->sql_query_lancamento_requisicao_material("",$campos, "m40_codigo desc",implode(" and ", $aWherePadrao));
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      }else{


        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $aWherePadrao[] = "m40_codigo = {$pesquisa_chave}";
          $sSql   = $clconlancam->sql_query_lancamento_requisicao_material($pesquisa_chave,"matrequi.*, db_depart.descrdepto","m40_codigo desc", implode(" and ", $aWherePadrao));
          $result = $clconlancam->sql_record($sSql);
          if($clconlancam->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$descrdepto',false);</script>";
          }else{
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