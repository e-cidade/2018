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
include("classes/db_inicialcodforo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clinicialcodforo = new cl_inicialcodforo;
$clinicialcodforo->rotulo->label("v55_inicial");
$clinicialcodforo->rotulo->label("v55_codforo");
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
            <td width="4%" align="right" nowrap title="<?=$Tv55_inicial?>">
              <?=$Lv55_inicial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v55_inicial",8,$Iv55_inicial,true,"text",4,"","chave_v55_inicial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tv55_codforo?>">
              <?=$Lv55_codforo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v55_codforo",30,$Iv55_codforo,true,"text",4,"","chave_v55_codforo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_inicialcodforo.hide();">
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
           if(file_exists("funcoes/db_func_inicialcodforo.php")==true){
             include("funcoes/db_func_inicialcodforo.php");
           }else{
           $campos = "inicialcodforo.*";
           }
        }
        if(isset($chave_v55_inicial) && (trim($chave_v55_inicial)!="") ){
	         $sql = $clinicialcodforo->sql_query($chave_v55_inicial,$campos,"v55_inicial","v55_inicial = $chave_v55_inicial and v50_situacao = 1 and inicial.v50_instit = ".db_getsession('DB_instit') );
        }else if(isset($chave_v55_codforo) && (trim($chave_v55_codforo)!="") ){
	         $sql = $clinicialcodforo->sql_query("",$campos,"v55_codforo"," trim(inicialcodforo.v55_codforo) = '$chave_v55_codforo' and v50_situacao = 1 and inicial.v50_instit = ".db_getsession('DB_instit') );
        }else{
           $sql = $clinicialcodforo->sql_query("",$campos,"v55_inicial"," v50_situacao = 1 and inicial.v50_instit = ".db_getsession('DB_instit'));
        }

        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){          
          $result = $clinicialcodforo->sql_record($clinicialcodforo->sql_query(null,"*",null,"trim(v55_codforo) = '$pesquisa_chave' and inicial.v50_instit = ".db_getsession('DB_instit') ));
          if($clinicialcodforo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v55_codforo',false,$v55_inicial);</script>";
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