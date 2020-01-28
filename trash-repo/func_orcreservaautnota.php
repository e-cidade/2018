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
include("classes/db_orcreservaaut_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcreservaaut = new cl_orcreservaaut;
$clorcreservaaut->rotulo->label("o83_codres");

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("pc11_numero");
$clrotulo->label("o58_coddot");
$clrotulo->label("e54_autori");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload=''>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>"><?=$Lz01_numcgm?></td>
            <td width="96%" align="left" nowrap><? db_input("z01_numcgm",8,$Iz01_numcgm,true,"text",4,"","chave_z01_numcgm");?>&nbsp;&nbsp;</td>
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>"><?=$Lz01_nome?></td>
            <td width="96%" align="left" nowrap><? db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");?></td>
         </tr>
         <tr> 
           <td width="4%" align="right" nowrap title="<?=$Te54_autori?>"><?=$Le54_autori?></td>
           <td width="96%" align="left" nowrap><? db_input("e54_autori",6,$Ie54_autori,true,"text",4,"","chave_e54_autori"); ?></td>
         </tr>
         <tr> 
           <td width="4%" align="right" nowrap title="<?=$Tpc11_numero?>"><?=$Lpc11_numero?></td>
           <td width="96%" align="left" nowrap><? db_input("pc11_numero",8,$Ipc11_numero,true,"text",4,"","chave_pc11_numero"); ?></td>
           <td width="4%" align="right" nowrap title="<?=$To58_coddot?>"><?=$Lo58_coddot?></td>
           <td width="96%" align="left" nowrap><? db_input("o58_coddot",6,$Io58_coddot,true,"text",4,"","chave_o58_coddot"); ?></td>
         </tr>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcreservaaut.hide();">
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
           if(file_exists("funcoes/db_func_orcreservaaut.php")==true){
             include("funcoes/db_func_orcreservaaut.php");
           }else{
           $campos = "orcreservaaut.*";
           }
        }
        $campos   = "distinct ".$campos;
        $campos  .= ",fc_solaut(e54_autori::integer) as pc11_numero,o58_coddot, e55_codele ";
        $dbwhere  = " e54_instit = ".db_getsession("DB_instit")."  and e54_anousu =".db_getsession("DB_anousu");
        $dbwhere2 = " and empempaut.e61_autori is null "; 
        if (isset($chave_e54_autori) && (trim($chave_e54_autori)!="") && 1==1){
	         $sql = $clorcreservaaut->sql_query("",$campos,"e54_autori","$dbwhere $dbwhere2 and e54_autori = $chave_e54_autori");
        } else if (isset($chave_z01_numcgm) && (trim($chave_z01_numcgm)!="") ){
	         $sql = $clorcreservaaut->sql_query("",$campos,"e54_autori","$dbwhere $dbwhere2 and z01_numcgm = $chave_z01_numcgm");
        } else if (isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $clorcreservaaut->sql_query("",$campos,"e54_autori","$dbwhere $dbwhere2 and z01_nome like '$chave_z01_nome%' ");
        } else if (isset($chave_pc11_numero)&&trim($chave_pc11_numero)!=""){
           $sql = $clorcreservaaut->sql_query("",$campos,"o83_codres","$dbwhere $dbwhere2 and pc11_numero = $chave_pc11_numero");
        } else if (isset($chave_o58_coddot)&&trim($chave_o58_coddot)!=""){
           $sql = $clorcreservaaut->sql_query("",$campos,"o83_codres","$dbwhere $dbwhere2 and o58_coddot  = $chave_o58_coddot");
        } else {
           $sql = $clorcreservaaut->sql_query("",$campos,"o83_codres","$dbwhere $dbwhere2");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcreservaaut->sql_record($clorcreservaaut->sql_query($pesquisa_chave, "", " e54_autori desc", "$dbwhere $dbwhere2"));
          if($clorcreservaaut->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e54_autori',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."($o58_codele,false);</script>";
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