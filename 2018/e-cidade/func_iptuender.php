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
include("classes/db_iptuender_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cliptuender = new cl_iptuender;
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
          <td colspan="2" align="center"> 
					  <?
			        db_ancora("Matrícula do ímovel","js_pesquisaj43_matric(true);",1);
							db_input('j43_matric',10,'',true,'text',"1");
					  ?>
          </td>
        </tr>
				<tr>
				  <td>
				  	<center>		
					    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onclick="js_pesquisa()"/> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" />
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_iptuender.hide();" />
						</center>	
			    </td>
        </tr>
      </form>
    </table>
		
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($pesquisa_chave)) {
        if (isset($campos)==false) {
           if (file_exists("funcoes/db_func_iptuender.php")==true){

						//existe
	           include("funcoes/db_func_iptuender.php");
           } else {
             $campos = "iptuender.oid,iptuender.*";
           }
        }
				
        if ( isset($j43_matric) && trim($j43_matric) != "" ) {
          $sql = $cliptuender->sql_query_file($j43_matric); 
				} else {	
          $sql = $cliptuender->sql_query_file(null); 
			  }
				
        $repassa = array();
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
				
      } else {

	      if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          $result = $cliptuender->sql_record($cliptuender->sql_query($pesquisa_chave));
          if ($cliptuender->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$oid',false);</script>";
          } else {
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>

<script>
	
function js_pesquisaj43_matric(mostra) {
  js_OpenJanelaIframe('','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|j01_matric','Pesquisa',true);
}

function js_mostraiptubase1(chave1){
  document.form2.j43_matric.value = chave1;
  db_iframe_iptubase.hide();
}

</script>

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