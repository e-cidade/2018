<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_matordem_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatordem = new cl_matordem;
$clmatordem->rotulo->label("m51_codordem");
$clmatordem->rotulo->label("m51_data");
$clmatordem->rotulo->label("m51_numcgm");
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("e60_codemp");
$clrotulo->label("m52_numemp");


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
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_matordemempcgm.php")==true){
             include("funcoes/db_func_matordemempcgm.php");
           }else{
           $campos = "matordem.*";
           }
        }
        if(isset($m51_codordem) && (trim($m51_codordem)!="") ){
	        $sql = $clmatordem->sql_query_anu($m51_codordem,$campos,"m51_codordem","");
        }else if(isset($m51_data) && (trim($m51_data)!="") ){
	        $sql = $clmatordem->sql_query_anu("",$campos,"m51_data"," m51_data like '$m51_data%' ");
        }else if(isset($m51_numcgm)&&(trim($m51_numcgm))){
				  $sql = $clmatordem->sql_query_anu("",$campos,"m51_numcgm","m51_numcgm = $m51_numcgm ");
	      }else if(isset($z01_nome) && (trim($z01_nome))){
	        $sql = $clmatordem->sql_query_anu("",$campos,"z01_nome","z01_nome like '$_z01_nome%' ");
	      }else if(isset($e60_codemp)&&(trim($e60_codemp))){
	        $sql = $clmatordem->sql_query_anu("",$campos,"e60_codemp","e60_codemp = $e60_codemp");
	      }else if(isset($m52_numemp)&&(trim($m52_numemp))){
	        $sql = $clmatordem->sql_query_anu("",$campos,"m52_numemp","m52_numemp = $m52_numemp");
	      }else{
          $sql = $clmatordem->sql_query_anu("",$campos,"m51_codordem","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      } else {

        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmatordem->sql_record($clmatordem->sql_query_anu($pesquisa_chave));
          if($clmatordem->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m51_data',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
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
 function js_mostraordem(codordem) {
   js_OpenJanelaIframe('parent','db_iframe_matordem2','emp3_ordemcompra002.php?m51_codordem='+codordem,'Ordem de Compra n� '+codordem,true)
 }
</script>