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
include("classes/db_rhpagocor_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhpagocor = new cl_rhpagocor;
$clrotulo = new rotulocampo;
$clrhpagocor->rotulo->label("rh58_codigo");
$clrhpagocor->rotulo->label("rh58_seq");
$clrotulo->label("rh57_ano");
$clrotulo->label("rh57_mes");
$clrotulo->label("rh57_regist");
$clrotulo->label("z01_nome");
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
           <td align="right" nowrap title="Digite o Ano / Mes de competência" >
             <strong>Ano / Mês :&nbsp;&nbsp;</strong>
           </td>
           <td colspan='3'>
           <?
           db_input('rh57_ano',4,$Irh57_ano,true,'text',2,"",'chave_rh57_ano');
           ?>
           &nbsp;/&nbsp;
           <?
           db_input('rh57_mes',2,$Irh57_mes,true,'text',2,"",'chave_rh57_mes');
           ?>
           </td>
         </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh57_regist?>">
              <?=$Lrh57_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh57_regist",10,$Irh57_regist,true,"text",4,"","chave_rh57_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhpagocor.hide();">
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
           if(file_exists("funcoes/db_func_rhpagocor.php")==true){
             include("funcoes/db_func_rhpagocor.php");
           }else{
           $campos = "rhpagocor.*";
           }
        }
				$dbwhere = "";
				if(isset($chave_rh57_ano) && (trim($chave_rh57_ano)!="") && isset($chave_rh57_mes) && (trim($chave_rh57_mes)!="")){
					$dbwhere = " and rh57_ano = ".$chave_rh57_ano." and rh57_mes = ".$chave_rh57_mes;
				}
        if(isset($chave_rh57_regist) && (trim($chave_rh57_regist)!="") ){
	         $sql = $clrhpagocor->sql_query_atraso(null,$campos,"rh57_regist"," rh57_regist = ".$chave_rh57_regist.$dbwhere);
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $clrhpagocor->sql_query_atraso(null,$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' ".$dbwhere);
        }else{
           $sql = $clrhpagocor->sql_query_atraso(null,$campos,"rh58_codigo"," 1=1 ".$dbwhere);
        }
        $repassa = array();
        if(isset($chave_rh58_seq)){
          $repassa = array("chave_rh58_codigo"=>$chave_rh58_codigo,"chave_rh58_seq"=>$chave_rh58_seq);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhpagocor->sql_record($clrhpagocor->sql_query_atraso($pesquisa_chave));
          if($clrhpagocor->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh58_seq',false);</script>";
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
js_tabulacaoforms("form2","chave_rh58_seq",true,1,"chave_rh58_seq",true);
</script>