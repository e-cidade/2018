<?php
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
require_once("classes/db_orcobjetivo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcobjetivo = new cl_orcobjetivo;
$clorcobjetivo->rotulo->label("o143_sequencial");
$clorcobjetivo->rotulo->label("o143_descricao");

$iAnoUsu = db_getsession("DB_anousu");
$sWhere  = " o143_orcorgaoanousu = {$iAnoUsu}";

if (!empty($lVinculado) && $lVinculado === "false") {
  $sWhere .= " and o143_sequencial not in (select o144_orcobjetivo from orcprogramavinculoobjetivo where o144_orcobjetivo = orcobjetivo.o143_sequencial)";
}

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
            <td width="4%" align="right" nowrap title="<?=$To143_sequencial?>">
              <?=$Lo143_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o143_sequencial",10,$Io143_sequencial,true,"text",4,"","chave_o143_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To143_descricao?>">
              <?=$Lo143_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o143_descricao",50,$Io143_descricao,true,"text",4,"","chave_o143_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcobjetivo.hide();">
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
           $campos = "orcobjetivo.*,  orcorgao.o40_anousu, orcorgao.o40_orgao";
        }
        
        if(isset($chave_o143_sequencial) && (trim($chave_o143_sequencial)!="") ){
	         $sql = $clorcobjetivo->sql_query($chave_o143_sequencial,$campos,"o143_sequencial", $sWhere);
        }else if(isset($chave_o143_descricao) && (trim($chave_o143_descricao)!="") ){
	         $sql = $clorcobjetivo->sql_query("",$campos,"o143_descricao"," {$sWhere} and o143_descricao like '$chave_o143_descricao%' ");
        }else{
           $sql = $clorcobjetivo->sql_query("",$campos,"o143_sequencial", $sWhere);
        }
        $repassa = array();
        if(isset($chave_o143_descricao)){
          $repassa = array("chave_o143_sequencial"=>$chave_o143_sequencial,"chave_o143_descricao"=>$chave_o143_descricao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $sWhere .= " and o143_sequencial = {$pesquisa_chave} ";
          $sSql    = $clorcobjetivo->sql_query(null, "*", null, $sWhere);
          $result  = $clorcobjetivo->sql_record($sSql);
          if($clorcobjetivo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o143_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_o143_descricao",true,1,"chave_o143_descricao",true);
</script>