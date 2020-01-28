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
include("classes/db_leis_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clleis = new cl_leis;
$clleis->rotulo->label("h08_codlei");
$clleis->rotulo->label("h08_numero");
$clleis->rotulo->label("h08_tipo");
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
            <td width="4%" align="right" nowrap title="<?=$Th08_codlei?>">
              <?=$Lh08_codlei?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h08_codlei",6,$Ih08_codlei,true,"text",4,"","chave_h08_codlei");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th08_numero?>">
              <?=$Lh08_numero?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h08_numero",6,$Ih08_numero,true,"text",4,"","chave_h08_numero");
		       ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Th08_tipo?>">
              <?=@$Lh08_tipo?>
            </td>
            <td> 
              <?
	      $db_opcao = 1;
	      if(isset($chave_tipo)){
		$chave_h08_tipo = $chave_tipo;
		$db_opcao = 3;
              }
              $arr_tipo = Array("T"=>"Todos", "A"=>"Avanço", "G"=>"Gratificação", "C"=>"Cargos", "O"=>"Outros");
              db_select("chave_h08_tipo", $arr_tipo, true, $db_opcao);
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_leis.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = "";
      if(isset($chave_h08_tipo) && trim($chave_h08_tipo) != "T"){
	$dbwhere = " and h08_tipo = '$chave_tipo'";
      }
      if(!isset($pesquisa_chave) && !isset($pesquisa_chave_numero)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_leis.php")==true){
             include("funcoes/db_func_leis.php");
           }else{
           $campos = "leis.*";
           }
        }
        if(isset($chave_h08_codlei) && (trim($chave_h08_codlei)!="") ){
	         $sql = $clleis->sql_query_file(null,$campos,"h08_codlei","h08_codlei = ".$chave_h08_codlei.$dbwhere);
        }else if(isset($chave_h08_numero) && (trim($chave_h08_numero)!="") ){
	         $sql = $clleis->sql_query_file(null,$campos,"h08_numero"," h08_numero like '".$chave_h08_numero."%' ".$dbwhere);
        }else{
           $sql = $clleis->sql_query_file(null,$campos,"h08_codlei",str_replace("and","",$dbwhere));
        }
//	echo $sql;
        $repassa = array();
        if(isset($chave_h08_numero)){
          $repassa = array("chave_h08_codlei"=>$chave_h08_codlei,"chave_h08_numero"=>$chave_h08_numero);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if(isset($pesquisa_chave) && $pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clleis->sql_record($clleis->sql_query_file(null,"*","","h08_codlei = ".$pesquisa_chave.$dbwhere));
          if($clleis->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h08_numero','$h08_dtlanc',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
          }
	}else if(isset($pesquisa_chave_numero) && $pesquisa_chave_numero!=null && $pesquisa_chave_numero!=""){
          $result = $clleis->sql_record($clleis->sql_query_file(null,"*","","h08_numero='".$pesquisa_chave_numero."' ".$dbwhere));
          if($clleis->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h08_codlei','$h08_dtlanc',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave_numero.") não Encontrado','',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('','',false);</script>";
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
js_tabulacaoforms("form2","chave_h08_numero",true,1,"chave_h08_numero",true);
</script>