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
include("classes/db_veiculoscomb_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveiculoscomb = new cl_veiculoscomb;

$clveiculoscomb->rotulo->label("ve06_sequencial");
$clveiculoscomb->rotulo->label("ve06_veiccadcomb");
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
            <td width="4%" align="right" nowrap title="<?=$Tve06_sequencial?>">
              <?=$Lve06_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ve06_sequencial",10,$Ive06_sequencial,true,"text",4,"","chave_ve06_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tve06_veiccadcomb?>">
              <?=$Lve06_veiccadcomb?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ve06_veiccadcomb",10,$Ive06_veiccadcomb,true,"text",4,"","chave_ve06_veiccadcomb");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_veiculoscomb.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
        $dbwhere = " ";
        if (isset($filtrar_veiculo) && trim($filtrar_veiculo) != ""){
          $dbwhere = "ve06_veiculos = $filtrar_veiculo ";
        }

      if (isset($filtrar_veiculocomb) && trim($filtrar_veiculocomb) != ""){
            $dbwhere = "ve06_veiculos = $cod_veiculo and ve06_veiccadcomb=$filtrar_veiculocomb";
         }


      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_veiculoscomb.php")==true){
             include("funcoes/db_func_veiculoscomb.php");
           }else{
           $campos = "veiculoscomb.*";
           }
        }

        if(isset($chave_ve06_sequencial) && (trim($chave_ve06_sequencial)!="") ){
           if (trim($dbwhere) != ""){
             $dbwhere = " and ".$dbwhere;
           }
	         $sql = $clveiculoscomb->sql_query(null,$campos,"ve06_sequencial","ve06_sequencial = $chave_ve06_sequencial $dbwhere");
        }else if(isset($chave_ve06_veiccadcomb) && (trim($chave_ve06_veiccadcomb)!="") ){
           if (trim($dbwhere) != ""){
             $dbwhere = " and ".$dbwhere;
           }

	         $sql = $clveiculoscomb->sql_query("",$campos,"ve06_veiccadcomb"," ve06_veiccadcomb = $chave_ve06_veiccadcomb $dbwhere");
        }else{
           $sql = $clveiculoscomb->sql_query("","distinct $campos","ve06_sequencial","$dbwhere");

        }
        $repassa = array();
        if(isset($chave_ve06_sequencial)||isset($chave_ve06_veiccadcomb)){
          $repassa = array("chave_ve06_sequencial"=>$chave_ve06_sequencial,"chave_ve06_veiccadcomb"=>$chave_ve06_veiccadcomb);

        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $clveiculoscomb->sql_record($clveiculoscomb->sql_query(null,"*",null," $dbwhere"));
          if($clveiculoscomb->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ve06_sequencial',false,'$ve26_descr','$ve06_veiccadcomb');</script>";
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
js_tabulacaoforms("form2","chave_ve06_sequencial",true,1,"chave_ve06_sequencial",true);
</script>