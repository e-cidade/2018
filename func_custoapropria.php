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
include("classes/db_custoapropria_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcustoapropria = new cl_custoapropria;
$oRotulo = new rotulocampo();
$clcustoapropria->rotulo->label("cc12_sequencial");
$oRotulo->label("m60_descr");
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
       <fieldset><legend><b>Pesquisar por </legend>
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td  align="left" nowrap title="<?=$Tcc12_sequencial?>">
              <b>Material</b>
            </td>
            <td  align="left" nowrap> 
              <?
		       db_input("m60_codmater",10,$Icc12_sequencial,true,"text",4,"","chave_m60_codmater");
		       ?>
            </td>
            <td align="left" nowrap title="<?=$Tm60_descr?>">
              <?=$Lm60_descr?>
            </td>
            <td  align="left" nowrap> 
              <?
		       db_input("m60_descr",30,$Im60_descr,true,"text",4,"","chave_m60_descr");
		       ?>
            </td>
          </tr>
           <tr>
            <td nowrap>
              <b>Data Inicial:</b>
            </td>
            <td nowrap>
              <?
               
               db_inputdata("datainicial",null,null,null,true,"text", 1);
              ?>
              </td>
              <td>
              <b>Data Final:</b>
              </td>
              <td nowrap align="">
              <?
               db_inputdata("datafinal",null,null,null,true,"text", 1);
              ?>
            </td>
           </tr>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_custoapropria.hide();">
             </td>
          </tr>
        </form>
        </table>
        </fieldset>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = " m80_coddepto = ".db_getsession("DB_coddepto");
      $sOrder = " m80_codigo,m80_data";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_custoapropria.php")==true){
             include("funcoes/db_func_custoapropria.php");
           }else{
           $campos = "custoapropria.*";
           }
        }
        if (isset($chave_m60_codmater) && (trim($chave_m60_codmater)!="") ){
	       $sWhere .= " and m60_codmater = {$chave_m60_codmater}";
	                                                         
        }
        
        if (isset($chave_m60_descr) && (trim($chave_m60_descr)!="") ){
	      $sWhere .= " and m60_descr like '{$chave_m60_descr}%'"; 
        }
        if (isset($datainicial) && $datainicial != "" and (isset($datafinal) && $datafinal == "")){

          $datainicial  = implode(array_reverse(explode("/",$datainicial)));
          $sWhere      .= " and m80_data = '{$datainicial}'";
          
        }
        if (isset($datainicial) && $datainicial != "" and (isset($datafinal) && $datafinal != "")){

          $datainicial  = implode("-",array_reverse(explode("/",$datainicial)));
          $datafinal    = implode("-",array_reverse(explode("/",$datafinal)));
          $sWhere      .= " and m80_data between'{$datainicial}' and '{$datafinal}'";
          
        }
        $repassa = array();
        if(isset($chave_cc12_sequencial)){
          $repassa = array("chave_cc12_sequencial"=>$chave_cc12_sequencial,"chave_cc12_sequencial"=>$chave_cc12_sequencial);
        }
        $sql = $clcustoapropria->sql_query_custoapropria(null,
	                                                         $campos,
	                                                         $sOrder,
	                                                         "{$sWhere}");
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcustoapropria->sql_record($clcustoapropria->sql_query_custoapropria($pesquisa_chave));
          if($clcustoapropria->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$cc12_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_cc12_sequencial",true,1,"chave_cc12_sequencial",true);
</script>