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
include("classes/db_sepultamentos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsepultamentos = new cl_sepultamentos;
$clrotulo = new rotulocampo;
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("z01_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
             <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tcm01_i_codigo?>">
              <?=$Lcm01_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                db_input("cm01_i_codigo",10,$Icm01_i_codigo,true,"text",4,"","chave_cm01_i_codigo");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tcm01_i_codigo?>">
             <b>Nome</b>
            </td>
            <td width="96%" align="left" nowrap title="<?=$Tz01_nome?>">
              <?
                db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sepultamentos.hide();">
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
           if(file_exists("funcoes/db_func_sepultamentos.php")==true){
             include("funcoes/db_func_sepultamentos.php");
           }else{
           $campos = " cm01_i_codigo,
                       cgm.z01_nome as z01_nome,
                       cm01_d_falecimento,
                       cm01_i_declarante, 
		       cgm3.z01_nome as declarante,
                       cm01_i_hospital,   
		       cgm1.z01_nome as hospital,
                       cm01_i_funeraria,  
		       cgm2.z01_nome as funeraria";
           }
        }
        if(isset($chave_cm01_i_codigo) && (trim($chave_cm01_i_codigo)!="") ){
                 $sql = $clsepultamentos->sql_query($chave_cm01_i_codigo,$campos,"cm01_i_codigo");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
          $chave_z01_nome = strtoupper($chave_z01_nome);
          $sql = $clsepultamentos->sql_query("",$campos,"cm01_i_codigo"," cgm.z01_nome like '$chave_z01_nome%' ");
        }else{
         $sql = $clsepultamentos->sql_query("",$campos,"cm01_i_codigo");
        }
        
        db_lovrot(@$sql,15,"()","",$funcao_js);
      } else {
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $campos = "*, cgm3.z01_nome as cm01_c_declarante ";
          $result = $clsepultamentos->sql_record($clsepultamentos->sql_query($pesquisa_chave,$campos));
          //echo($clsepultamentos->sql_query($pesquisa_chave,$campos));
          if($clsepultamentos->numrows!=0){
            db_fieldsmemory($result,0);
            if (isset($dtfalecimento)) {
            	echo "<script>".$funcao_js."('$z01_nome',$cm01_i_declarante,'$cm01_c_declarante','$cm01_d_falecimento',false);</script>";
            } else {
            	echo "<script>".$funcao_js."('$z01_nome',$cm01_i_declarante,'$cm01_c_declarante',false);</script>";
            }
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