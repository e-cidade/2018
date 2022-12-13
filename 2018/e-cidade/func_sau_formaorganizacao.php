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
require_once("classes/db_sau_formaorganizacao_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoSauFormaOrganizacao = new cl_sau_formaorganizacao;
$oDaoSauFormaOrganizacao->rotulo->label("sd62_i_codigo");
$oDaoSauFormaOrganizacao->rotulo->label("sd62_c_nome");
$oDaoSauFormaOrganizacao->rotulo->label("sd62_c_formaorganizacao");
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
            <td width="4%" align="right" nowrap title="<?=$Tsd62_i_codigo?>">
              <?=$Lsd62_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("sd62_i_codigo", 5, $Isd62_i_codigo, true, "text", 4, "", "chave_sd62_i_codigo");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tsd62_c_formaorganizacao?>">
              <?=$Lsd62_c_formaorganizacao?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("sd62_c_formaorganizacao", 5, $Isd62_c_formaorganizacao, 
                       true, "text", 4, "", "chave_sd62_c_formaorganizacao"
                      );
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tsd62_c_nome?>">
              <?=$Lsd62_c_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
                 db_input("sd62_c_nome",60,$Isd62_c_nome,true,"text",4,"","chave_sd62_c_nome");
                 ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_formaorganizacao.hide();">
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
           if (file_exists("funcoes/db_func_sau_formaorganizacao.php")==true) {
             require_once("funcoes/db_func_sau_formaorganizacao.php");
           } else {
           $campos = "sau_formaorganizacao.*";
           }
        }
        if (!isset($sOrderBy)) {
          $sOrderBy = 'sd62_i_codigo';
        } else {
          $sOrderBy = str_replace("|", " ", $sOrderBy);
        }
        if (isset($lDistinct)) {
          $campos = " distinct on (sd62_c_formaorganizacao) ".$campos; 
        }    
        $sWhere = '';
        if (isset($chave_grupo) && !empty($chave_grupo)) {
          $sWhere .= "and sd60_c_grupo = '$chave_grupo' ";
        }
        if (isset($chave_subgrupo) && !empty($chave_subgrupo)) {
          $sWhere .= "and sd61_c_subgrupo = '$chave_subgrupo' ";
        }

        if (isset($chave_sd62_i_codigo) && (trim($chave_sd62_i_codigo) != '')) {

          $sSql = $oDaoSauFormaOrganizacao->sql_query2(null, $campos, 'sd62_i_codigo desc', 
                                                       "sd62_i_codigo = $chave_sd62_i_codigo $sWhere"
                                                      );

        } elseif (isset($chave_sd62_c_formaorganizacao) && (trim($chave_sd62_c_formaorganizacao) != '')) {

          $sSql = $oDaoSauFormaOrganizacao->sql_query2(null, $campos, 'sd62_i_codigo desc', 
                                                       "sd62_c_formaorganizacao = '$chave_sd62_c_formaorganizacao' $sWhere"
                                                      );

        } elseif (isset($chave_sd62_c_nome) && (trim($chave_sd62_c_nome) != '')) {

          $sSql = $oDaoSauFormaOrganizacao->sql_query2('', $campos, 'sd62_c_nome', 
                                                       " sd62_c_nome like '$chave_sd62_c_nome%' $sWhere"
                                                      );

        } else {
          $sSql = $oDaoSauFormaOrganizacao->sql_query2('', $campos, $sOrderBy, substr($sWhere, 4));
        }

        if (isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $oDaoSauFormaOrganizacao->sql_record($sSql);
           if ($oDaoSauFormaOrganizacao->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd62_c_formaorganizacao.") não Encontrado');</script>");
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
        if (isset($chave_sd62_c_nome)) {
          $repassa = array("chave_sd62_i_codigo"=>$chave_sd62_i_codigo,"chave_sd62_c_nome"=>$chave_sd62_c_nome);
        }
        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          $result = $oDaoSauFormaOrganizacao->sql_record($oDaoSauFormaOrganizacao->sql_query($pesquisa_chave));
          if ($oDaoSauFormaOrganizacao->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd62_c_nome',false);</script>";
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
document.form2.chave_sd62_i_codigo.value="";
document.form2.chave_sd62_c_nome.value="";	
}
js_tabulacaoforms("form2","chave_sd62_c_nome",true,1,"chave_sd62_c_nome",true);
</script>