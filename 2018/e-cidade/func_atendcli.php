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
include("classes/db_atendimento_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clatendimento = new cl_atendimento;
$clatendimento->rotulo->label("at02_codatend");
$clatendimento->rotulo->label("at02_codcli");
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
            <td width="4%" align="right" nowrap title="<?=$Tat02_codatend?>">
              <?=$Lat02_codatend?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at02_codatend",4,$Iat02_codatend,true,"text",4,"","chave_at02_codatend");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tat02_codcli?>">
              <?=$Lat02_codcli?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("at02_codcli",0,$Iat02_codcli,true,"text",4,"","chave_at02_codcli");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
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
           $campos = "distinct(at02_codatend),at06_datalanc as dl_data,at06_horalanc as dl_hora,at01_codcli,at01_nomecli as dl_cliente,at10_nome as dl_solicitante,at04_descr as dl_Contato_por,nome as dl_Tecnico";
                      
        }
        if(isset($chave_at02_codatend) && (trim($chave_at02_codatend)!="") ){
        	 $sql = $clatendimento->sql_query_inc($chave_at02_codatend,$campos,"at02_codatend desc","at02_codatend = $chave_at02_codatend and atenditem.at05_codatend is null");
	        // $sql = $clatendimento->sql_query($chave_at02_codatend,$campos,"at02_codatend");
        }else if(isset($chave_at02_codcli) && (trim($chave_at02_codcli)!="") ){
	         $sql = $clatendimento->sql_query_inc("",$campos,"at02_codatend desc"," at02_codcli like '$chave_at02_codcli%' and atenditem.at05_codatend is null");
	         //$sql = $clatendimento->sql_query("",$campos,"at02_codcli"," at02_codcli like '$chave_at02_codcli%' ");
        }else{
        	$sql = $clatendimento->sql_query_inc("",$campos,"at02_codatend desc","atenditem.at05_codatend is null");
            //$sql = $clatendimento->sql_query("",$campos,"at02_codatend desc","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{die("xxxxxxxxxxx");
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clatendimento->sql_record($clatendimento->sql_query_inc($pesquisa_chave));
          if($clatendimento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$at02_codcli',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      //echo"<br>sql = $sql";
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
document.form2.chave_at02_codatend.focus();
document.form2.chave_at02_codatend.select();
  </script>
  <?
}
?>