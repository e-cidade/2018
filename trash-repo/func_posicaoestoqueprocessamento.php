<?php
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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_posicaoestoqueprocessamento_classe.php");

$iInstituicao = db_getsession("DB_instit");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST);

$clposicaoestoqueprocessamento = new cl_posicaoestoqueprocessamento;
$clposicaoestoqueprocessamento->rotulo->label();

$iDiaProcessamento = null; 
$iMesProcessamento = null; 
$iAnoProcessamento = null; 

if ( !empty($oPost->m05_data) ) {
  list($iDiaProcessamento, $iMesProcessamento, $iAnoProcessamento) = explode("/", $oPost->m05_data);
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#cccccc leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">

	     <form name="form2" method="post" action="" >

          <tr> 
            <td nowrap title="<?php echo $Tm05_data; ?>">
              <?php echo $Lm05_data; ?>
            </td>
            <td nowrap> 
              <?php db_inputdata("m05_data", $iDiaProcessamento, $iMesProcessamento, $iAnoProcessamento, true, "text", 4); ?>
            </td>
          </tr>

          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" /> 
              <input name="limpar" type="reset" id="limpar" onclick="js_limpar();" value="Limpar" />
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_posicaoestoqueprocessamento.hide();" />
             </td>
          </tr>

        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?php
      $sWhere = 'm05_instit = ' . $iInstituicao;

      if( !isset($pesquisa_chave) ) {

        if( !isset($campos) ){

          if( file_exists("funcoes/db_func_posicaoestoqueprocessamento.php") ) {
            include("funcoes/db_func_posicaoestoqueprocessamento.php");
          } else {
            $campos = "posicaoestoqueprocessamento.oid, posicaoestoqueprocessamento.*";
          }
        }

        if ( !empty($oPost->m05_data) ) {
          $sWhere = "m05_data = '{$oPost->m05_data}'";
        }

        $sql = $clposicaoestoqueprocessamento->sql_query(null, $campos, 'm05_data', $sWhere);
        $repassa = array();
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $clposicaoestoqueprocessamento->sql_record($clposicaoestoqueprocessamento->sql_query($pesquisa_chave));

          if($clposicaoestoqueprocessamento->numrows!=0){

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$oid',false);</script>";

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
<?php if(!isset($pesquisa_chave)) : ?>

  <script>
    function js_limpar() {

      document.getElementById('m05_data').setAttribute('value', '');
      document.getElementById('m05_data').value = '';
    }
  </script>

<?php endif; ?>