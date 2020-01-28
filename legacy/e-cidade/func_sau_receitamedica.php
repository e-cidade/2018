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
require_once("classes/db_sau_receitamedica_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoSauReceitaMedica = new cl_sau_receitamedica;
$oDaoSauReceitaMedica->rotulo->label("s158_i_codigo");
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
            <td width="4%" align="right" nowrap title="<?=$Ts158_i_codigo?>">
              <?=$Ls158_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input('s158_i_codigo', 10, $Is158_i_codigo, true, 'text', 4, '', 'chave_s158_i_codigo');
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_sau_receitamedica.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
      $sWhere = '';
      $sSep   = '';
      if (isset($lFiltrarAnuladas)) {

        $sWhere .= $sSep.' s158_i_situacao != 3 '; // Filtro as anuladas
        $sSep    = ' and ';

      }

      if (isset($lFiltrarAtendidas)) {

        $sWhere .= $sSep.' s158_i_situacao != 2 '; // Filtro as que já foram atendidas
        $sSep    = ' and ';

      }

      if (isset($lFiltrarVencidas)) {

        $dAtual  = date('Y-m-d', db_getsession('DB_datausu'));
        $sWhere .= $sSep." s158_d_validade >= '$dAtual' "; // Filtra as receitas que ultrapassaram a data de validade
        $sSep    = ' and ';

      }

      if (isset($iChaveCgs) && !empty($iChaveCgs)) {

        $sWhere .= $sSep.' z01_i_cgsund = '.$iChaveCgs; // Filtro pelo CGS de um paciente
        $sSep    = ' and ';

      }

      if (!isset($pesquisa_chave)) {

        if (isset($sCampos) == false) {

          if (file_exists("funcoes/db_func_sau_receitamedica.php")==true) {
            require_once("funcoes/db_func_sau_receitamedica.php");
          } else {
            $sCampos = "sau_receitamedica.*";
          }

        }
        if (isset($chave_s158_i_codigo) && (trim($chave_s158_i_codigo) != '')) {

	         $sSql = $oDaoSauReceitaMedica->sql_query_prontuario(null, $sCampos, 's158_i_codigo desc ', 
                                                               "s158_i_codigo = $chave_s158_i_codigo".
                                                               $sSep.$sWhere
                                                              );

        } else {

           $sSql = $oDaoSauReceitaMedica->sql_query_prontuario('', $sCampos, 's158_i_codigo desc ', $sWhere);

        }

        if (isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $oDaoSauReceitaMedica->sql_record($sSql);
          if ($oDaoSauReceitaMedica->numrows == 0) {
	          die('<script>'.$aFuncao[0]."('','Chave(".$chave_s158_i_codigo.") não Encontrado');</script>");
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
        if (isset($chave_s158_i_codigo)) {
          $repassa = array("chave_s158_i_codigo"=>$chave_s158_i_codigo,"chave_s158_i_codigo"=>$chave_s158_i_codigo);
        }
        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          $result = $oDaoSauReceitaMedica->sql_record($oDaoSauReceitaMedica->sql_query_prontuario($pesquisa_chave));
          if ($oDaoSauReceitaMedica->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$s158_i_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_s158_i_codigo",true,1,"chave_s158_i_codigo",true);
</script>