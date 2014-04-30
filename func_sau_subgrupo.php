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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_sau_subgrupo_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoSauSubGrupo = new cl_sau_subgrupo;
$oDaoSauSubGrupo->rotulo->label("sd61_i_codigo");
$oDaoSauSubGrupo->rotulo->label("sd61_c_nome");
$oDaoSauSubGrupo->rotulo->label("sd61_c_subgrupo");
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
            <td width="4%" align="right" nowrap title="<?=$Tsd61_i_codigo?>">
              <?=$Lsd61_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                 db_input("sd61_i_codigo",5,$Isd61_i_codigo,true,"text",4,"","chave_sd61_i_codigo");
                 ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tsd61_c_subgrupo?>">
              <?=$Lsd61_c_subgrupo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("sd61_c_subgrupo", 2, $Isd61_c_subgrupo, true, "text", 4, "", "chave_sd61_c_subgrupo");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd61_c_nome?>">
              <b>Nome:</b>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
                 db_input("sd61_c_nome",50,$Isd61_c_nome,true,"text",4,"","chave_sd61_c_nome");
                 ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_subgrupo.hide();">
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
           if (file_exists("funcoes/db_func_sau_subgrupo.php")==true) {
             require_once("funcoes/db_func_sau_subgrupo.php");
           } else {
           $campos = "sau_subgrupo.*";
           }
        }
        
        if (!isset($sOrderBy)) {
          $sOrderBy = 'sd61_i_codigo';
        } else {
          $sOrderBy = str_replace("|", " ", $sOrderBy);
        } 
        if (isset($lDistinct)) {
          $campos = " distinct on (sd61_c_subgrupo) ".$campos; 
        }    
        $sWhere = '';
        if (isset($chave_grupo) && !empty($chave_grupo)) {
          $sWhere .= "and sd60_c_grupo = '$chave_grupo'";
        }
        if (isset($chave_sd61_i_codigo) && (trim($chave_sd61_i_codigo) != '')) {

          $sSql = $oDaoSauSubGrupo->sql_query(null, $campos, 'sd61_i_codigo desc', 
                                              "sd61_i_codigo = $chave_sd61_i_codigo $sWhere"
                                             );

        } elseif (isset($chave_sd61_c_subgrupo) && (trim($chave_sd61_c_subgrupo) != "")) {

          $sSql = $oDaoSauSubGrupo->sql_query(null, $campos, 'sd61_i_codigo desc', 
                                              "sd61_c_subgrupo = '$chave_sd61_c_subgrupo' $sWhere"
                                             );

        } else if (isset($chave_sd61_c_nome) && (trim($chave_sd61_c_nome) != '') ) {

          $sSql = $oDaoSauSubGrupo->sql_query('', $campos, 'sd61_c_nome', 
                                              " sd61_c_nome like '$chave_sd61_c_nome%' $sWhere"
                                             );

        } else {
          $sSql = $oDaoSauSubGrupo->sql_query('', $campos, $sOrderBy, substr($sWhere, 4));
        }

        if (isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $oDaoSauSubGrupo->sql_record($sSql);
           if ($oDaoSauSubGrupo->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd61_c_subgrupo.") não Encontrado');</script>");
           } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for ($iCont = 1; $iCont < count($aFuncao); $iCont++) {

               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep     = ', ';

             }
             $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');

          }

        }

        $repassa = array();
        if (isset($chave_sd61_c_nome)) {
          $repassa = array("chave_sd61_i_codigo"=>$chave_sd61_i_codigo,"chave_sd61_c_nome"=>$chave_sd61_c_nome);
        }
        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          $result = $oDaoSauSubGrupo->sql_record($oDaoSauSubGrupo->sql_query($pesquisa_chave));
          if ($oDaoSauSubGrupo->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd61_c_nome',false);</script>";
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
</body>
</html>
<?
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>
function js_limpar() {
document.form2.chave_sd61_i_codigo.value="";
document.form2.chave_sd61_c_nome.value="";	
}
js_tabulacaoforms("form2","chave_sd61_c_nome",true,1,"chave_sd61_c_nome",true);
</script>