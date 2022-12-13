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
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_ppaestimativareceita_classe.php");
include("classes/db_ppaversao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clppaestimativareceita = new cl_ppaestimativareceita;
$clppaestimativareceita->rotulo->label("o06_sequencial");
$clppaestimativareceita->rotulo->label("o06_codrec");
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
            <td width="4%" align="right" nowrap title="<?=$To06_sequencial?>">
              <?=$Lo06_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o06_sequencial",10,$Io06_sequencial,true,"text",4,"","chave_o06_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To06_codrec?>">
              <?=$Lo06_codrec?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o06_codrec",10,$Io06_codrec,true,"text",4,"","chave_o06_codrec");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap>
              <b>Perspectiva:</b>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
               $oDaoPPaVersao    = new cl_ppaversao;
               $sSqlPerspectivas = $oDaoPPaVersao->sql_query_integracao(null,
                                                                        "ppaversao.*,
                                                                        o01_sequencial",
                                                                        "o01_sequencial,o119_versao",
                                                                        "((o123_sequencial is null or o123_situacao = 2)
                                                                          )
                                                                         ");

               $rsPerspectivas   = $oDaoPPaVersao->sql_record($sSqlPerspectivas);
               if (@pg_numrows($rsPerspectivas) == 0) {
                  echo "<strong> Nenhum Perspectiva válida encontrada <strong>";
                  exit;
               }
               $aPerspectivas    = array();
               for ($i = 0; $i < $oDaoPPaVersao->numrows; $i++ ) {
                 
                 $oPerspectiva  = db_utils::fieldsMemory($rsPerspectivas, $i);
                 $aPerspectivas[$oPerspectiva->o119_sequencial] = "P{$oPerspectiva->o119_versao} - {$oPerspectiva->o01_sequencial}";
                    
               }
               if (!isset($o119_versao)) { 
                 $o119_versao = $oPerspectiva->o119_sequencial;
               }
               db_select("o119_versao", $aPerspectivas, true, 1,"style='width:95px'");
             ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_ppaestimativareceita.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = "c61_instit = ".db_getsession("DB_instit");
      if (isset($lEstimativa)) {
        $sWhere .= " and  o05_base is false";
      }
      $sWhere .= " and o06_ppaversao = {$o119_versao}";
      $sWhere .= " and o06_anousu    = ".(db_getsession("DB_anousu")+1);
      $sWhere .= " and o70_codrec is null ";
      $iAnoRec = db_getsession("DB_anousu");
      if(!isset($pesquisa_chave)){
      
        $campos  = "distinct o06_sequencial,o57_fonte,";
        $campos .= "         o05_anoreferencia,"; 
        $campos .= "      o05_valor, ";
        $campos .= "     cast('P'||o119_versao as varchar) as o119_versao";
        $sOrder = "3";
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_ppaestimativareceita.php")==true){
             include("funcoes/db_func_ppaestimativareceita.php");
           }else{
           $campos = "ppaestimativareceita.*";
           }
        }
        if(isset($chave_o06_sequencial) && (trim($chave_o06_sequencial)!="") ) {
	         $sql = $clppaestimativareceita->sql_query_receita_integrada(null,
	                                                             $campos,
	                                                             "o06_sequencial",
	                                                             "o06_sequencial={$chave_o06_sequencial} and $sWhere",
	                                                             $iAnoRec);
        }else if(isset($chave_o06_codrec) && (trim($chave_o06_codrec)!="") ){
	         $sql = $clppaestimativareceita->sql_query_receita_integrada("",$campos,"o06_ppaestimativa",
	                                                                     " o06_codrec = $chave_o06_codrec and $sWhere",
	                                                                     $iAnoRec);
        }else{
           $sql = $clppaestimativareceita->sql_query_receita_integrada("",$campos,"o57_fonte","$sWhere", $iAnoRec);
        }
        $repassa = array();
        if(isset($chave_o06_codrec)){
          $repassa = array("chave_o06_sequencial"=>$chave_o06_sequencial,"chave_o06_ppacodrec"=>$chave_o06_codrec);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          
          $result = $clppaestimativareceita->sql_record($clppaestimativareceita->sql_query_receita_integrada($pesquisa_chave));
          if($clppaestimativareceita->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o06_ppaestimativa',false);</script>";
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
js_tabulacaoforms("form2","chave_o06_ppaestimativa",true,1,"chave_o06_ppaestimativa",true);
</script>