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
include("classes/db_vacinasaplicadas_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clvacinasaplicadas = new cl_vacinasaplicadas;
$clvacinasaplicadas->rotulo->label("sd08_c_vacina");
$clvacinasaplicadas->rotulo->label("sd08_i_unidade");
$clvacinasaplicadas->rotulo->label("sd08_i_cgm");
$clvacinasaplicadas->rotulo->label("sd08_c_vacina");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd08_c_vacina?>">
              <?=$Lsd08_c_vacina?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("sd08_c_vacina",10,$Isd08_c_vacina,true,"text",4,"","chave_sd08_c_vacina");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd08_i_unidade?>">
              <?=$Lsd08_i_unidade?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("sd08_i_unidade",10,$Isd08_i_unidade,true,"text",4,"","chave_sd08_i_unidade");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd08_i_cgm?>">
              <?=$Lsd08_i_cgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("sd08_i_cgm",10,$Isd08_i_cgm,true,"text",4,"","chave_sd08_i_cgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd08_c_vacina?>">
              <?=$Lsd08_c_vacina?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("sd08_c_vacina",10,$Isd08_c_vacina,true,"text",4,"","chave_sd08_c_vacina");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_vacinasaplicadas.hide();">
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
           $campos = "sd08_c_vacina, sd07_c_nome, sd08_i_unidade, sd02_c_razao, sd08_i_cgm, z01_nome, sd08_d_data ";
        }
        if(isset($chave_sd08_c_vacina) && (trim($chave_sd08_c_vacina)!="") ){
	         $sql = $clvacinasaplicadas->sql_query($chave_sd08_c_vacina,$chave_sd08_i_unidade,$chave_sd08_i_cgm,$campos,"sd08_c_vacina");
        }else if(isset($chave_sd08_c_vacina) && (trim($chave_sd08_c_vacina)!="") ){
	         $sql = $clvacinasaplicadas->sql_query("","","",$campos,"sd08_c_vacina"," sd08_c_vacina like '$chave_sd08_c_vacina%' ");
        }else{
           $sql = $clvacinasaplicadas->sql_query("","","",$campos,"sd08_c_vacina#sd08_i_unidade#sd08_i_cgm","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clvacinasaplicadas->sql_record($clvacinasaplicadas->sql_query($pesquisa_chave));
          if($clvacinasaplicadas->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd08_c_vacina',false);</script>";
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