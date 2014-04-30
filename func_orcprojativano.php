<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_orcprojativ_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcprojativ = new cl_orcprojativ;
$clorcprojativ->rotulo->label("o55_anousu");
$clorcprojativ->rotulo->label("o55_projativ");
$clorcprojativ->rotulo->label("o55_descr");
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
            <td width="4%" align="right" nowrap title="<?=$To55_projativ?>">
              <?=$Lo55_projativ?>
            </td>
            <td width="96%" align="left" nowrap> 
           <input title="Projetos / Atividades do orçamento Campo:o55_projativ" 
                  name="chave_o55_projativ"  
                  type="text"     
                  id="chave_o55_projativ"  
                  value=""  
                  size="4"
                  maxlength="4" 
                  onblur="js_ValidaMaiusculo(this,'f',event);" 
                  onKeyUp="js_ValidaCampos(this,0,'Projetos / Atividades','t','f',event);"
                  onKeyDown="return js_controla_tecla_enter(this,event);"
                  autocomplete='off'>
           
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To55_descr?>">
              <?=$Lo55_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o55_descr",40,$Io55_descr,true,"text",4,"","chave_o55_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcprojativ.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
			$sWhere = '1 = 1';
      if (!isset($insti) || trim(@$insti) == ""){
           $insti = db_getsession("DB_instit");
      }

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_orcprojativ.php")==true){
             include("funcoes/db_func_orcprojativ.php");
           }else{
           $campos = "orcprojativ.*";
           }
        }
        if(isset($chave_o55_projativ) && (trim($chave_o55_projativ)!="") ){
	    $sql = $clorcprojativ->sql_query(db_getsession('DB_anousu'),$chave_o55_projativ,$campos,"o55_projativ",
			             $sWhere ." and o55_projativ=$chave_o55_projativ  and o55_anousu=".db_getsession("DB_anousu"));
        }else if(isset($chave_o55_descr) && (trim($chave_o55_descr)!="") ){
	    $sql = $clorcprojativ->sql_query(db_getsession('DB_anousu'),"",$campos,"o55_descr"
			        ," $sWhere and o55_descr like '$chave_o55_descr%' and o55_anousu=".db_getsession("DB_anousu"));
        }else if(isset($o55_tipo)){
	    if($o55_tipo==1){
	      $where=" o55_projativ > 1000 and o55_projativ < 1999 "; 
	    }else if($o55_tipo==2){
	      $where=" o55_projativ > 2000 and o55_projativ < 2999 "; 
	    }else if($o55_tipo==3){
	      $where=" o55_projativ > 3000 and o55_projativ < 3999 ";
	    }  
           $sql = $clorcprojativ->sql_query("","",$campos,"o55_anousu#o55_projativ","$sWhere and o55_anousu=".db_getsession('DB_anousu')." and ".$where);
        }else{
           $sql = $clorcprojativ->sql_query(null,"",$campos,"o55_anousu#o55_projativ",
					                                  $sWhere." and o55_anousu=".db_getsession("DB_anousu"));
        }
				//echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcprojativ->sql_record($clorcprojativ->sql_query(null,null,"*",null," $sWhere and o55_projativ=$pesquisa_chave  and o55_anousu=".db_getsession("DB_anousu")));
          if($clorcprojativ->numrows!=0){
            db_fieldsmemory($result,0);
	    if(isset($mostraprojativ)){
              echo "<script>".$funcao_js."('".db_getsession("DB_anousu")."','$pesquisa_chave');</script>";
	    }else{
              echo "<script>".$funcao_js."('$o55_descr','$o55_anousu',false);</script>";
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