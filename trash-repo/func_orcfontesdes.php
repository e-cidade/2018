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
include("classes/db_orcfontesdes_classe.php");
require("libs/db_liborcamento.php");
include("classes/db_orcparametro_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcfontesdes = new cl_orcfontesdes;
$clorcparametro = new cl_orcparametro;
$clestrutura = new cl_estrutura;
$clorcfontesdes->rotulo->label("o60_anousu");
$clorcfontesdes->rotulo->label("o60_codfon");
$clorcfontesdes->rotulo->label("o60_perc");
if(isset($o50_estrutreceita)){
 $chave_o57_fonte= str_replace(".","",$o50_estrutreceita);
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
            <td width="4%" align="right" nowrap title="<?=$To60_codfon?>">
              <?=$Lo60_codfon?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o60_codfon",6,$Io60_codfon,true,"text",4,"","chave_o60_codfon");
		       ?>
            </td>
          </tr>
<?
         $clestrutura->mascara =false;
         $clestrutura->input   =false;
         $clestrutura->nomeform="form2";//o nome do campo é DB_txtdotacao
	 $clestrutura->estrutura('o50_estrutreceita');
?> 
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To60_perc?>">
              <?=$Lo60_perc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o60_perc",15,$Io60_perc,true,"text",4,"","chave_o60_perc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcfontesdes.hide();">
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
           if(file_exists("funcoes/db_func_orcfontesdes.php")==true){
             include("funcoes/db_func_orcfontesdes.php");
           }else{
           $campos = "orcfontesdes.*";
           }
        }
        if(isset($chave_o60_codfon) && (trim($chave_o60_codfon)!="") ){
	         $sql = $clorcfontesdes->sql_query(db_getsession('DB_anousu'),$chave_o60_codfon,$campos,"o60_codfon");
        }else if(isset($chave_o60_perc) && (trim($chave_o60_perc)!="") ){
	         $sql = $clorcfontesdes->sql_query(db_getsession('DB_anousu'),"",$campos,"o60_perc"," o60_perc like '$chave_o60_perc%' and o60_anousu=".db_getsession("DB_anousu"));
	  }else if(isset($chave_o57_fonte) && (trim($chave_o57_fonte)!="") ){
	         $sql = $clorcfontesdes->sql_query(db_getsession('DB_anousu'),"",$campos,"o60_perc"," o57_fonte like '$chave_o57_fonte%' and o60_anousu=".db_getsession("DB_anousu"));
        }else{
           $sql = $clorcfontesdes->sql_query(db_getsession('DB_anousu'),"",$campos,"o60_anousu#o60_codfon","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcfontesdes->sql_record($clorcfontesdes->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clorcfontesdes->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o60_perc',false);</script>";
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