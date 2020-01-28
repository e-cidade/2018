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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_sau_distritosanitario_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoSauDistritoSanitario = new cl_sau_distritosanitario;
$oDaoSauDistritoSanitario->rotulo->label("s153_c_codigo");
$oDaoSauDistritoSanitario->rotulo->label("s153_c_descr");
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
       <form name="form2" method="post" action='' >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ts153_c_codigo?>">
              <?=$Ls153_c_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
              db_input("s153_c_codigo",10,$Is153_c_codigo,true,"text",4,'',"chave_s153_c_codigo");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ts153_c_descr?>">
              <?=$Ls153_c_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
              db_input("s153_c_descr",50,$Is153_c_descr,true,"text",4,'',"chave_s153_c_descr");
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_distritosanitario.hide();">
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

           if (file_exists("funcoes/db_func_sau_distritosanitario.php")==true) {
             require_once("funcoes/db_func_sau_distritosanitario.php");
           } else {
             $campos = "sau_distritosanitario.*";
           }

        }

        if (isset($chave_s153_c_codigo) && (trim($chave_s153_c_codigo) != '') ) {

          $sSql = $oDaoSauDistritoSanitario->sql_query(null, $campos, 's153_c_codigo', 
                                                       "s153_c_codigo = '$chave_s153_c_codigo'"
                                                      );

        } elseif (isset($chave_s153_c_descr) && (trim($chave_s153_c_descr) != '') ) {

          $sSql = $oDaoSauDistritoSanitario->sql_query(null , $campos, 's153_c_descr', 
                                                       " s153_c_descr like '$chave_s153_c_descr%' "
                                                      );

        } else {
          $sSql = $oDaoSauDistritoSanitario->sql_query(null, $campos, 's153_c_codigo', '');
        }

        if(isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $oDaoSauDistritoSanitario->sql_record($sSql);
           if($oDaoSauDistritoSanitario->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_s153_c_codigo.") não Encontrada');</script>");
           } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for($iCont = 1; $iCont < count($aFuncao); $iCont++) {
               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep = ', ';

             }
             $sFuncao = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');

          }
        }

        $aRepassa = array();
        if (isset($chave_s153_c_codigo)) {
          $aRepassa = array("chave_s153_c_codigo"=>$chave_s153_c_codigo,"chave_s153_c_codigo"=>$chave_s153_c_codigo);
        }

        db_lovrot($sSql,15,"()",'',$funcao_js,'',"NoMe",$aRepassa);

      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != '') {

          $result = $oDaoSauDistritoSanitario->sql_record($oDaoSauDistritoSanitario->sql_query($pesquisa_chave));
          if ($oDaoSauDistritoSanitario->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$s153_c_codigo',false);</script>";

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
js_tabulacaoforms("form2","chave_s153_c_codigo",true,1,"chave_s153_c_codigo",true);
</script>