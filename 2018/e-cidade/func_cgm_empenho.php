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
include("classes/db_cgm_classe.php");
include("classes/db_empempenho_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clcgm = new cl_cgm;
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");
$clcgm->rotulo->label("z01_cgccpf");
$clempempenho = new cl_empempenho;

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
            <td width="4%" nowrap title="<?=$Tz01_numcgm?>" align=left>
            <?=$Lz01_numcgm?>
            </td>
            <td width="96%" align="left" nowrap colspan=3> 
            <? db_input("z01_numcgm",6,$Iz01_numcgm,true,"text",4,"","chave_z01_numcgm"); ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="left" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
            <? db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");   ?>
            </td>
          <td width="4%" align="right" nowrap title="<?=$Tz01_cgccpf?>"><?=$Lz01_cgccpf?></td>
          <td width="21%" align="left" nowrap> 
            <? db_input("z01_cgccpf",16,"",true,"text",4,"","chave_z01_cgccpf"); ?>
          </td>
 
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cgm.hide();">
             </td>
          </tr>
        </table>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $campos = "distinct e60_numcgm , z01_nome, z01_cgccpf";
      if(!isset($pesquisa_chave)){
        if(isset($chave_z01_numcgm) && (trim($chave_z01_numcgm)!="") ){
             $sql = $clempempenho->sql_query(null,$campos,"e60_numcgm","e60_numcgm = $chave_z01_numcgm"); 
	     
	     db_lovrot($sql,15,"()","",$funcao_js);	 
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
             $sql = $clempempenho->sql_query(null,$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' " ); 
             db_lovrot($sql,15,"()","",$funcao_js);
        }else if(isset($chave_z01_cgccpf) && (trim($chave_z01_cgccpf)!="") ){
             $sql = $clempempenho->sql_query(null,$campos,"z01_cgccpf"," z01_cgccpf like '$chave_z01_cgccpf%' " ); 
             db_lovrot($sql,15,"()","",$funcao_js);
 
        }else{
	    //  $sql = $clempempenho->sql_query(null,$campos,"e60_numcgm"); 
        } 
       //  db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          // $result = $clcgm->sql_record($clcgm->sql_query($pesquisa_chave));
	  $result = $clempempenho->sql_record($clempempenho->sql_query(null,$campos,"e60_numcgm","e60_numcgm = $pesquisa_chave")); 

          if($clempempenho->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
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
 </form>
</td>
</tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.chave_z01_nome.focus();
document.form2.chave_z01_nome.select();
  </script>
  <?
}
?>