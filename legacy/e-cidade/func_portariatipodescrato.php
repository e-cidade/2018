<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_portariatipo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clportariatipo = new cl_portariatipo;
$cltipoasse     = new cl_tipoasse;
$clportariatipo->rotulo->label();
$cltipoasse->rotulo->label();
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
            <td width="4%" align="right" nowrap title="<?=$Th30_sequencial?>">
              <?=$Lh30_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h30_sequencial",10,$Ih30_sequencial,true,"text",4,"","chave_h30_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th12_assent?>">
              <?=$Lh12_assent?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h12_assent",10,$Ih12_assent,true,"text",4,"","chave_h12_assent");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_portariatipo.hide();">
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
      	
        $campos  = "portariatipo.h30_sequencial,      ";
        $campos .= "tipoasse.h12_assent,              ";
        $campos .= "tipoasse.h12_descr,               ";
        $campos .= "portariatipo.h30_portariaenvolv,  ";
        $campos .= "portariatipo.h30_portariatipoato, ";
        $campos .= "portariatipo.h30_portariaproced,  ";
        $campos .= "portariatipo.h30_amparolegal,     ";
        $campos .= "h41_descr,                        ";
        $campos .= "h12_natureza,                     ";
        $campos .= "h12_codigo                      ";

        if(isset($chave_h30_sequencial) && (trim($chave_h30_sequencial)!="") ){
	         $sql = $clportariatipo->sql_query_func($chave_h30_sequencial,$campos,"h30_sequencial",'');
        }else if(isset($chave_h30_sequencial) && (trim($chave_h30_sequencial)!="") ){
	         $sql = $clportariatipo->sql_query_func("",$campos,"h30_sequencial"," h30_sequencial like '$chave_h30_sequencial%' ");
        }else if(isset($chave_h12_assent) && (trim($chave_h12_assent)!="") ){
           $sql = $clportariatipo->sql_query_func("",$campos,"h12_assent"," h12_assent like '$chave_h12_assent%' ");
        }else{
           $sql = $clportariatipo->sql_query_func("",$campos,"h30_sequencial","");
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe");
        
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clportariatipo->sql_record($clportariatipo->sql_query_func($pesquisa_chave));
          if($clportariatipo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h30_sequencial',false,'$h12_descr','$h30_amparolegal','$h41_descr','$h12_natureza','$h12_codigo');</script>";
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
js_tabulacaoforms("form2","chave_h30_sequencial",true,1,"chave_h30_sequencial",true);
</script>