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
include("classes/db_veiculos_classe.php");
include("classes/db_veiccadcentraldepart_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clveiculos             = new cl_veiculos;
$clveiccadcentraldepart = new cl_veiccadcentraldepart;

$clveiculos->rotulo->label("ve01_codigo");
$clveiccadcentraldepart->rotulo->label("ve37_veiccadcentral");

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
        <table width="23%" border="0" align="center" cellspacing="0">
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
      	$where = " ve01_ativo = '$baixa' ";
      } else {
      	$where = " ve01_ativo = '1' ";
			}

      if (trim($where)!=""){
        $where .= "and";
      }

      if (isset($chave_ve37_sequencial) && trim($chave_ve37_sequencial) != "" && $chave_ve37_sequencial != "0"){
        $where .= " ve37_sequencial = $chave_ve37_sequencial "; 
      } else {
       $where .= "  ve36_coddepto = ".db_getsession("DB_coddepto")." ";
      }

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "distinct ve01_codigo,ve01_placa,ve20_descr,ve21_descr,ve22_descr,ve23_descr,ve01_chassi,ve01_certif,ve01_anofab,ve01_anomod";           
        }
        if(isset($chave_ve01_codigo) && (trim($chave_ve01_codigo)!="") ){
        	 if ($where!=""){
           		$where = " and ".$where;
           	 }
	         $sql = $clveiculos->sql_query_central($chave_ve01_codigo,$campos,"ve01_codigo","ve01_codigo=$chave_ve01_codigo $where");
        }else{
           $sql = $clveiculos->sql_query_central("",$campos,"ve01_codigo",$where);
        }

        $repassa = array();
        if(isset($chave_ve01_codigo)){
          $repassa = array("chave_ve01_codigo"=>$chave_ve01_codigo,"chave_ve01_codigo"=>$chave_ve01_codigo);
        }
				//die($sql);
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa,false);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          if ($where!=""){
           	$where = " and ".$where;
          }	
          $result = $clveiculos->sql_record($clveiculos->sql_query_central($pesquisa_chave,"*",null,"ve01_codigo=$pesquisa_chave $where"));
          if($clveiculos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ve01_placa',false,'$ve20_descr');</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,'');</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false,'');</script>";
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