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
include("classes/db_conplanoreduz_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconplanoreduz = new cl_conplanoreduz;
$clconplanoreduz->rotulo->label("c61_codcon");
$clconplanoreduz->rotulo->label("c61_reduz");
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
            <td width="4%" align="right" nowrap title="<?=$Tc61_codcon?>"> <?=$Lc61_codcon?></td>
            <td width="96%" align="left" nowrap> 
              <? db_input("c61_codcon",6,$Ic61_codcon,true,"text",4,"","chave_c61_codcon"); ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc61_reduz?>"><?=$Lc61_reduz?></td>
            <td width="96%" align="left" nowrap> 
              <? db_input("c61_reduz",6,$Ic61_reduz,true,"text",4,"","chave_c61_reduz"); ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplanoreduz.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $campos = "c61_codcon,c61_reduz,c60_estrut,c60_descr";
      if(!isset($pesquisa_chave)){
             if(isset($chave_c61_codcon) && (trim($chave_c61_codcon)!="") ){
  	          // $sql = $clconplanoreduz->sql_query($chave_c61_codcon,$campos,"c61_codcon");
		  $txt_where = "and c60_codcon=$chave_c61_codcon";
             }else if(isset($chave_c61_reduz) && (trim($chave_c61_reduz)!="") ){
  	          //  $sql = $clconplanoreduz->sql_query("",$campos,"c61_reduz"," c61_reduz like '$chave_c61_reduz%' ");
	 	  $txt_where = "and c61_reduz like '$chave_c61_reduz%'";
             }else{
                  $txt_where="";            
	     }
             $sql= "select $campos 
                      from conplanoreduz
 		                 inner join conplano on c60_codcon = c61_codcon and c61_anousu=c60_anousu
		                 left outer join conplanoexe on c62_reduz=conplanoreduz.c61_reduz and c62_anousu=".db_getsession("DB_anousu")."
                      where conplanoexe.c62_reduz is null  and c61_anousu=".db_getsession("DB_anousu")."   
 		                 $txt_where
         	          order by c60_codcon,c61_reduz ";	     
             db_lovrot($sql,15,"()","",$funcao_js);  
      }else{
           if($pesquisa_chave!=null && $pesquisa_chave!=""){
              $result = $clconplanoreduz->sql_record($clconplanoreduz->sql_query($pesquisa_chave,db_getsession("DB_anousu")));
                 if($clconplanoreduz->numrows!=0){
                     db_fieldsmemory($result,0);
                     echo "<script>".$funcao_js."('$c61_reduz',false);</script>";
                 }else{
	             echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
                 };
           }else{
    	        echo "<script>".$funcao_js."('',false);</script>";
           };
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?  if(!isset($pesquisa_chave)){        ?>
        <script>
        </script>
<?  }       ?>