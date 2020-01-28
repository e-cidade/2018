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
require_once("classes/db_tfd_tipotratamentoproced_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrotulo = new rotulocampo;
$clrotulo->label("sd63_c_procedimento");
$clrotulo->label("sd63_c_nome");
$oDaoTfdTipoTratamentoProced = new cl_tfd_tipotratamentoproced;
$oDaoTfdTipoTratamentoProced->rotulo->label("tf05_i_codigo");
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
            <td width="4%" align="right" nowrap title="<?=$Ttf05_i_codigo?>">
              <?=$Ltf05_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("tf05_i_codigo",10,$Itf05_i_codigo,true,"text",4,'',"chave_tf05_i_codigo");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd63_c_procedimento?>">
              <?=$Lsd63_c_procedimento?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("sd63_c_procedimento",10,$Isd63_c_procedimento,true,"text",4,'',"chave_sd63_c_procedimento");
		          ?>
            </td>
          </tr>
          <tr> 
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd63_c_nome?>">
              <?=$Lsd63_c_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input("sd63_c_nome",50,$Isd63_c_nome,true,"text",4,'',"chave_sd63_c_nome");
		          ?>
            </td>
          </tr>
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tfd_tipotratamentoproced.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere     = '';
      $sSep       = '';
      $sTipoQuery = 'sql_query2';
      if (isset($chave_rh70_sequencial)) {

        $sWhere     = "sd96_i_cbo = ".(int)$chave_rh70_sequencial;
        $sWhere    .=' and tf05_i_tipotratamento = '.(int)$chave_tf05_i_tipotratamento;
        $sSep       = 'and';
        $sTipoQuery = 'sql_query_especialidade';

      } elseif (isset($chave_tf05_i_tipotratamento)) {

        $sWhere =' tf05_i_tipotratamento = '.(int)$chave_tf05_i_tipotratamento;
        $sSep   = 'and';

      }

      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

           if (file_exists("funcoes/db_func_tfd_tipotratamentoproced.php") == true) {
             require_once("funcoes/db_func_tfd_tipotratamentoproced.php");
           } else {
             $campos = "tfd_tipotratamentoproced.*";
           }

        }
        if (isset($chave_tf05_i_codigo) && (trim($chave_tf05_i_codigo) != '') ) {

	        $sSql = $oDaoTfdTipoTratamentoProced->{$sTipoQuery}(null, $campos, "tf05_i_codigo", 
                                                              "tf05_i_codigo = $chave_tf05_i_codigo $sSep $sWhere"
                                                             );

        } elseif (isset($chave_sd63_c_procedimento) && (trim($chave_sd63_c_procedimento) != '')) {

	        $sSql = $oDaoTfdTipoTratamentoProced->{$sTipoQuery}('', $campos, "sd63_c_nome",
                                                              " sd63_c_procedimento like '$chave_sd63_c_procedimento'".
                                                              " $sSep $sWhere"
                                                             );

        } elseif (isset($chave_sd63_c_nome) && (trim($chave_sd63_c_nome) != '')) {

	        $sSql = $oDaoTfdTipoTratamentoProced->{$sTipoQuery}('', $campos, "sd63_c_nome",
                                                              " sd63_c_nome like '$chave_sd63_c_nome%'".
                                                              " $sSep $sWhere "
                                                             );

        } else {
          $sSql = $oDaoTfdTipoTratamentoProced->{$sTipoQuery}('', $campos, "tf05_i_codigo", $sWhere);
        }
        $repassa = array();
        if (isset($chave_tf05_i_codigo)) {
          $repassa = array("chave_tf05_i_codigo"=>$chave_tf05_i_codigo,"chave_tf05_i_codigo"=>$chave_tf05_i_codigo);
        }

        if (isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $oDaoTfdTipoTratamentoProced->sql_record($sSql);
           if ($oDaoTfdTipoTratamentoProced->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd63_c_procedimento.") não Encontrado');</script>");
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

        db_lovrot($sSql,15,"()",'',$funcao_js,'',"NoMe",$repassa);
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave != '') {
          
          $sSql   = $oDaoTfdTipoTratamentoProced->sql_query($pesquisa_chave);
          $result = $oDaoTfdTipoTratamentoProced->sql_record($sSql);
          if ($oDaoTfdTipoTratamentoProced->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tf05_i_codigo',false);</script>";

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
js_tabulacaoforms("form2","chave_tf05_i_codigo",true,1,"chave_tf05_i_codigo",true);
</script>