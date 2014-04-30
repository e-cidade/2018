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
include("classes/db_cgmalt_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcgmalt = new cl_cgmalt;
$clcgmalt->rotulo->label("z05_sequencia");
$clcgmalt->rotulo->label("z05_sequencia");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
		<td>&nbsp;</td>
	</tr> 
  <tr> 
		<td align="center" valign="top">
			<table width="35%" border="0" align="center" cellspacing="0">
				<form name="form2" method="post" action="" >
          <tr> 
            <td colspan="2" align="center"> 
              <input name="Fechar"		type="button" id="fechar"			value="Fechar" onClick="parent.db_iframe_cgmaltres.hide();">
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
					 	$campos  = " z05_sequencia,								";
						$campos .= " z05_numcgm,                  ";
						$campos .= " case when z05_tipo_alt = 'A' ";
						$campos .= "  	then 'Alteração'					";
						$campos .= "  	else 'Exclusão'						";
						$campos .= " end as z05_tipo_alt,					";
						$campos .= " login as z05_login_alt,			";
						$campos .= " z05_data_alt,								";
						$campos .= " z05_hora_alt									";
        }
        
				if(isset($chave_z05_sequencia) && (trim($chave_z05_sequencia)!="") ){
					 $sql = $clcgmalt->sql_query($chave_z05_sequencia,$campos,"z05_sequencia");
				}else if(isset($chave_z05_sequencia) && (trim($chave_z05_sequencia)!="") ){
					 $sql = $clcgmalt->sql_query("",$campos,"z05_sequencia"," z05_sequencia like '$chave_z05_sequencia%' ");
				}else if(isset($pesquisa_nomealt) && trim($pesquisa_nomealt)!="") {
					 $sql = $clcgmalt->sql_query("",$campos,"z05_sequencia","z05_nome like '%{$pesquisa_nomealt}%'");
				}else if(isset($pesquisa_numcgm) && trim($pesquisa_numcgm)!="") {
					 $sql = $clcgmalt->sql_query("",$campos,"z05_sequencia","z05_numcgm = {$pesquisa_numcgm}");
				}else{
					 $sql = $clcgmalt->sql_query("",$campos,"z05_sequencia","");
				}
			
				db_lovrot($sql,15,"()","",$funcao_js);
			
			}else{
        
				if($pesquisa_chave!=null && $pesquisa_chave!=""){
          
					$result = $clcgmalt->sql_record($clcgmalt->sql_query($pesquisa_chave));
          
					if($clcgmalt->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z05_sequencia',false);</script>";
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
//js_tabulacaoforms("form2","chave_z05_sequencia",true,1,"chave_z05_sequencia",true);
</script>