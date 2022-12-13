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
include("classes/db_sau_grupo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsau_grupo = new cl_sau_grupo;
$clsau_grupo->rotulo->label("sd60_i_codigo");
$clsau_grupo->rotulo->label("sd60_c_nome");
$clsau_grupo->rotulo->label("sd60_c_grupo");
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
            <td width="4%" align="right" nowrap title="<?=$Tsd60_i_codigo?>">
              <?=$Lsd60_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                 db_input("sd60_i_codigo",5,$Isd60_i_codigo,true,"text",4,"","chave_sd60_i_codigo");
                 ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tsd60_c_grupo?>">
              <?=$Lsd60_c_grupo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("sd60_c_grupo",2,$Isd60_c_grupo,true,"text",4,"","chave_sd60_c_grupo");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tsd60_c_nome?>">
              <?=$Lsd60_c_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                 db_input("sd60_c_nome",60,$Isd60_c_nome,true,"text",4,"","chave_sd60_c_nome");
                 ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_grupo.hide();">
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
        
        if (!isset($sOrderBy)) {
          $sOrderBy = 'sd60_i_codigo';
        } else {
          $sOrderBy = str_replace("|", " ", $sOrderBy);
        }
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_sau_grupo.php")==true){
             include("funcoes/db_func_sau_grupo.php");
           }else{
           $campos = "sau_grupo.*";
           }
        }
        if(isset($chave_sd60_i_codigo) && (trim($chave_sd60_i_codigo)!="") ){
              $sql = $clsau_grupo->sql_query($chave_sd60_i_codigo,$campos,"sd60_i_codigo");
        } elseif (isset($chave_sd60_c_grupo) && (trim($chave_sd60_c_grupo)!="") ) {
          $sql = $clsau_grupo->sql_query(null, $campos, 'sd60_i_codigo desc', "sd60_c_grupo = '$chave_sd60_c_grupo'");
        } else if(isset($chave_sd60_c_nome) && (trim($chave_sd60_c_nome)!="") ){
              $sql = $clsau_grupo->sql_query("",$campos,"sd60_c_nome"," sd60_c_nome like '$chave_sd60_c_nome%' ");
        }else{
           $sql = $clsau_grupo->sql_query("",$campos, $sOrderBy, "");
        }

        if (isset($nao_mostra)) {

          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $clsau_grupo->sql_record($sql);
           if($clsau_grupo->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd60_c_grupo.") não Encontrado');</script>");
           } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for($iCont = 1; $iCont < count($aFuncao); $iCont++) {

               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep     = ', ';

             }
             $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');

          }

        }

        $repassa = array();
        if(isset($chave_sd60_c_nome)){
          $repassa = array("chave_sd60_i_codigo"=>$chave_sd60_i_codigo,"chave_sd60_c_nome"=>$chave_sd60_c_nome);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsau_grupo->sql_record($clsau_grupo->sql_query($pesquisa_chave));
          if($clsau_grupo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd60_c_nome',false);</script>";
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
function js_limpar(){
document.form2.chave_sd60_i_codigo.value="";
document.form2.chave_sd60_c_nome.value="";	
}
js_tabulacaoforms("form2","chave_sd60_c_nome",true,1,"chave_sd60_c_nome",true);
</script>