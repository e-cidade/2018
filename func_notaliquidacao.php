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
include("classes/db_empnota_classe.php");
include("classes/db_empempenho_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oDaoEmpNota = new cl_empnota;
$clempempenho = new cl_empempenho;
$rotulo = new rotulocampo;
$rotulo->label("e60_codemp");
$rotulo->label("e60_numemp");
$rotulo->label("e50_codord");
$rotulo->label("e50_numemp");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
       <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te60_numemp?>"><?=$Le60_codemp?> </td>
            <td width="96%" align="left" nowrap> 
             
        <input name="chave_e60_codemp" size="12" type='text'  onKeyPress="return js_mascara(event);" >
            </td>
            <td width="4%" align="right" nowrap title="<?=$Te50_numemp?>">
              <?=$Le60_numemp?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
           db_input("e50_numemp",8,$Ie50_numemp,true,"text",4,"","chave_e50_numemp");
           ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Te50_codord?>">
              <?=$Le50_codord?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
           db_input("e50_codord",6,$Ie50_codord,true,"text",4,"","chave_e50_codord");
           ?>
            </td>
          </tr>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pagordem.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere=" e60_instit = ".db_getsession("DB_instit")." and e71_anulado is false";
      $campos = "e50_codord, e69_codnota, e60_numemp, e60_emiss, e50_obs,e53_valor,e53_vlrpag, e53_vlranu" ; 
      if(!isset($pesquisa_chave)){
        
        if(isset($chave_e50_codord) && (trim($chave_e50_codord)!="") ){
           $sql = $oDaoEmpNota->sql_query_nota("",$campos,"e50_codord","$dbwhere and e50_codord = '$chave_e50_codord' ");
           //$sql = $oDaoEmpNota->sql_query("",$campos,"e50_codord","$dbwhere and e50_codord like '$chave_e50_codord%' ");
        }else if(isset($chave_e50_numemp) && (trim($chave_e50_numemp)!="") ){
           $sql = $oDaoEmpNota->sql_query_nota("",$campos,"e50_numemp","$dbwhere and e50_numemp like '$chave_e50_numemp%' ");
        }else if(isset($chave_e60_codemp) && (trim($chave_e60_codemp)!="") ){
        $arr = split("/",$chave_e60_codemp);
        if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
    $dbwhere_ano = " and e60_anousu = ".$arr[1];
              }else{
    $dbwhere_ano = "";
        }
        $sql = $oDaoEmpNota->sql_query_nota("",$campos,"e50_numemp","$dbwhere and e60_codemp =  '".$arr[0]."' $dbwhere_ano");
        }else{
    if(isset($filtroquery) || isset($pesquisar)){
             $sql = $oDaoEmpNota->sql_query_nota("",$campos,"e50_codord","$dbwhere");
    } 
        }
  if(isset($sql)){
          db_lovrot($sql,15,"()","",$funcao_js);
  }  
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $oDaoEmpNota->sql_record($oDaoEmpNota->sql_query_nota(null, $campos,null,"e50_codord = {$pesquisa_chave} and {$dbwhere}"));
          if($oDaoEmpNota->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$e60_numemp',false,{$e69_codnota});</script>";
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