<?PHP
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("classes/db_veiculos_classe.php");
include("classes/db_veiccadcentraldepart_classe.php");
include("classes/db_veiccadcentral_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveiculos             = new cl_veiculos;
$clveiccadcentraldepart = new cl_veiccadcentraldepart;
$clveiccadcentral         = new cl_veiccadcentral;
$clveiculos->rotulo->label("ve01_codigo");
$clveiculos->rotulo->label("ve01_placa");
$clveiccadcentraldepart->rotulo->label("ve37_veiccadcentral");

$iInstituicao = db_getsession("DB_instit");

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
	     <form name="form1" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tve01_codigo?>">
              <?=$Lve01_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ve01_codigo",10,$Ive01_codigo,true,"text",4,"","chave_ve01_codigo");
		       ?>
            </td>
          </tr>          
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tve01_placa?>">
              <?=$Lve01_placa?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
           db_input("ve01_placa",10,$Ive01_placa,true,"text",4,"","chave_ve01_placa");
           ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="<?=$Tve37_veiccadcentral?>"><?=$Lve37_veiccadcentral?></td>
            <td nowrap align="left">
            <?
               $res_veiccadcentral = $clveiccadcentral->sql_record($clveiccadcentral->sql_query(null,"ve36_sequencial,descrdepto",null,""));
               db_selectrecord("veiccadcentral",$res_veiccadcentral,true,4,"","chave_veiccadcentral","","0");
            ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_veiculos.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = "";
      if (isset($baixa)&&$baixa!=""){
      	$where = " ve01_ativo = '$baixa' and ";
      } else {
      	$where = " ve01_ativo = '1' ";
			}


      if (isset($chave_veiccadcentral) && trim($chave_veiccadcentral) != "" && $chave_veiccadcentral != "0"){
        $where .= "and  ve37_veiccadcentral = $chave_veiccadcentral "; 
      } else {
        $where .="";
      }

      if(!isset($pesquisa_chave)){
      	
        if(isset($campos)==false){
        	
           $campos  = "distinct ve01_codigo,ve01_placa,ve20_descr,ve21_descr,";
           $campos .= "ve22_descr,ve23_descr,ve01_chassi,ve01_certif,ve01_anofab,ve01_anomod";           
        }
        
        if (isset($chave_ve01_codigo) && (trim($chave_ve01_codigo)!="") ) {
	         $sql = $clveiculos->sql_query($chave_ve01_codigo,$campos,"ve01_codigo","ve01_codigo=$chave_ve01_codigo and instit = {$iInstituicao} ");
        } else if( isset($chave_ve01_placa) && (trim($chave_ve01_placa)!="") ) {
           $sql = $clveiculos->sql_query("",$campos,"ve01_placa"," trim(ve01_placa) like '$chave_ve01_placa%' and instit = {$iInstituicao} ");
        } else {
           $sql = $clveiculos->sql_query("",$campos,"ve01_codigo","$where and instit = {$iInstituicao} ");
        }

        $repassa = array();
        if(isset($chave_ve01_codigo)){
          $repassa = array("chave_ve01_codigo" => $chave_ve01_codigo, "chave_ve01_codigo"=>$chave_ve01_codigo);
        }
				
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa,false);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          if ($where!=""){
           	$where = " and ".$where;
          }	
          $result = $clveiculos->sql_record($clveiculos->sql_query($pesquisa_chave,"*",null,"ve01_codigo=$pesquisa_chave $where and instit = {$iInstituicao} "));
          if($clveiculos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ve01_placa',false);</script>";
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
js_tabulacaoforms("form2","chave_ve01_codigo",true,1,"chave_ve01_codigo",true);
</script>