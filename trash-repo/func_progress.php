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
include("classes/db_progress_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprogress = new cl_progress;
$clprogress->rotulo->label("r24_regime");
$clprogress->rotulo->label("r24_padrao");
$clprogress->rotulo->label("r24_descr");
if(!isset($chave_r24_anousu) || (isset($chave_r24_anousu) && trim($chave_r24_anousu) == "")){
  $chave_r24_anousu = db_anofolha();
}
if(!isset($chave_r24_mesusu) || (isset($chave_r24_mesusu) && trim($chave_r24_mesusu) == "")){
  $chave_r24_mesusu = db_mesfolha();
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
            <td width="4%" align="right" nowrap title="<?=$Tr24_regime?>">
              <?=$Lr24_regime?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r24_regime",10,$Ir24_regime,true,"text",4,"","chave_r24_regime");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr24_padrao?>">
              <?=$Lr24_padrao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r24_padrao",10,$Ir24_padrao,true,"text",4,"","chave_r24_padrao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr24_descr?>">
              <?=$Lr24_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r24_descr",40,$Ir24_descr,true,"text",4,"","chave_r24_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_progress.hide();">
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
           if(file_exists("funcoes/db_func_progress.php")==true){
             include("funcoes/db_func_progress.php");
           }else{
           $campos = "progress.*";
           }
        }
        if(isset($chave_r24_padrao) && (trim($chave_r24_padrao)!="") ){
	         $sql = $clprogress->sql_query_padrao($chave_r24_anousu,$chave_r24_mesusu,null,$chave_r24_padrao,null,$campos,"r24_padrao","r24_anousu=$chave_r24_anousu and r24_mesusu=$chave_r24_mesusu r24_padrao='$chave_r24_padrao' and r24_instit =".db_getsession("DB_instit"));
        }else if(isset($chave_r24_regime) && (trim($chave_r24_regime)!="") ){
	         $sql = $clprogress->sql_query_padrao($chave_r24_anousu,$chave_r24_mesusu,$chave_r24_regime,null,null,$campos,"r24_regime","r24_anousu=$chave_r24_anousu and r24_mesusu=$chave_r24_mesusu and r24_regime=$chave_r24_regime and r24_instit =".db_getsession("DB_instit"));
        }else if(isset($chave_r24_descr) && (trim($chave_r24_descr)!="") ){
	         $sql = $clprogress->sql_query_padrao(db_getsession('DB_anousu'),"","","","",$campos,"r24_descr"," r24_anousu=$chave_r24_anousu and r24_mesusu=$chave_r24_mesusu and r24_descr like '$chave_r24_descr%' and  r24_instit =".db_getsession("DB_instit"));
        }else{
             $sql = $clprogress->sql_query_padrao($chave_r24_anousu,$chave_r24_mesusu,null,null,null,$campos,"r24_anousu#r24_mesusu#r24_regime#r24_padrao#r24_meses","r24_anousu=$chave_r24_anousu and r24_mesusu=$chave_r24_mesusu and r24_instit =".db_getsession("DB_instit"));
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clprogress->sql_record($clprogress->sql_query_padrao($chave_r24_anousu,$chave_r24_mesusu,null,$pesquisa_chave,null,"*",null,"r24_anousu=$chave_r24_anousu and r24_mesusu=$chave_r24_mesusu r24_padrao=$pesquisa_chave and r24_instit =".db_getsession("DB_instit")));
          if($clprogress->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r24_regime',false);</script>";
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