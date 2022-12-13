<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_codmovsefip_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcodmovsefip = new cl_codmovsefip;
$clcodmovsefip->rotulo->label("r66_anousu");
$clcodmovsefip->rotulo->label("r66_mesusu");
$clcodmovsefip->rotulo->label("r66_codigo");
$clcodmovsefip->rotulo->label("r66_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tr66_codigo?>">
              <?=$Lr66_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <? 
           db_input("r66_codigo",2,$Ir66_codigo,true,"text",4,"","chave_r66_codigo");
           ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr66_descr?>">
              <?=$Lr66_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
           db_input("r66_descr",40,$Ir66_descr,true,"text",4,"","chave_r66_descr");
           ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_codmovsefip.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $sWhere = "";

      if (isset($lAtivos)) {

        $sWhere  = "     r66_anousu = " . DBPessoal::getAnoFolha();
        $sWhere .= " and r66_mesusu = " . DBPessoal::getMesFolha();
        $sWhere .= " and r66_tipo   = 'A'";

        if(isset($chave_r66_codigo) && (trim($chave_r66_codigo)!="") ){
          $sWhere .= " and r66_codigo  = '{$chave_r66_codigo}'";
        }

        if(isset($pesquisa_chave) && $pesquisa_chave!=null && $pesquisa_chave!="") {
          $sWhere .= " and r66_codigo  = '{$pesquisa_chave}'";
        }

        if(isset($chave_r66_descr) && (trim($chave_r66_descr)!="") ){
          $sWhere .= " and r66_descr like '$chave_r66_descr%'";
        }
      }

      if(!isset($pesquisa_chave)){

        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_codmovsefip.php")==true){
             include("funcoes/db_func_codmovsefip.php");
           }else{
             $campos = "codmovsefip.*";
           }
        }

        if(isset($chave_r66_codigo) && (trim($chave_r66_codigo)!="") ){
           $sql = $clcodmovsefip->sql_query(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(),$chave_r66_codigo,$campos,"r66_mesusu desc", $sWhere);
        }else if(isset($chave_r66_descr) && (trim($chave_r66_descr)!="") ){
           $sql = $clcodmovsefip->sql_query(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(),"",$campos,"r66_descr"," r66_descr like '$chave_r66_descr%' ", $sWhere);
        }else{
           $sql = $clcodmovsefip->sql_query(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(),"",$campos,"r66_anousu desc#r66_mesusu desc#r66_codigo",$sWhere);
        }
        $repassa = array();
        if(isset($chave_r66_descr)){
          $repassa = array("chave_r66_anousu"=>db_getsession('DB_anousu'),"chave_r66_descr"=>$chave_r66_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!="") {

          $result = $clcodmovsefip->sql_record($clcodmovsefip->sql_query(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), $pesquisa_chave, $campos, "", $sWhere));

          if($clcodmovsefip->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r66_descr',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_r66_descr",true,1,"chave_r66_descr",true);
</script>