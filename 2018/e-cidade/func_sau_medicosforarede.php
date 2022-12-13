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
require_once("classes/db_sau_medicosforarede_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoSauMedicosForaRede = new cl_sau_medicosforarede;
$oDaoSauMedicosForaRede->rotulo->label('s154_i_medico');
$oDaoSauMedicosForaRede->rotulo->label('s154_c_nome');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="" >
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
        <tr> 
          <td width="4%" align="right" nowrap title="<?=$Ts154_i_medico?>">
            <?=$Ls154_i_medico?>
          </td>
          <td width="96%" align="left" nowrap> 
            <?
		        db_input("s154_i_medico", 10, $Is154_i_medico, true, "text", 4, "", "chave_s154_i_medico");
		        ?>
          </td>
        </tr>
        <tr> 
          <td width="4%" align="right" nowrap title="<?=$Ts154_c_nome?>">
            <?=$Ls154_c_nome?>
          </td>
          <td width="96%" align="left" nowrap> 
            <?
		        db_input("s154_c_nome", 50, $Is154_c_nome, true, "text", 4, "", "chave_s154_c_nome");
		        ?>
          </td>
        </tr>
        <tr> 
          <td colspan="2" align="center"> 
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
            <input name="limpar" type="reset" id="limpar" value="Limpar" >
            <input name="Fechar" type="button" id="fechar" value="Fechar"
              onClick="parent.db_iframe_sau_medicosforarede.hide();">
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top">
      <?
      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

           if (file_exists("funcoes/db_func_sau_medicosforarede.php") == true) {
             require_once("funcoes/db_func_sau_medicosforarede.php");
           } else {
             $campos = "sau_medicosforarede.*";
           }

        }
        if (isset($chave_s154_i_medico) && (trim($chave_s154_i_medico) != '') ) {

	        $sSql = $oDaoSauMedicosForaRede->sql_query(null, $campos, "s154_i_medico",
                                                     " s154_i_medico = $chave_s154_i_medico"
                                                    );

        } elseif (isset($chave_s154_c_nome) && (trim($chave_s154_c_nome) != '') ) {

	        $sSql = $oDaoSauMedicosForaRede->sql_query(null, $campos, 's154_c_nome', 
                                                     " s154_c_nome like '$chave_s154_c_nome%' "
                                                    );

        } else {
          $sSql = $oDaoSauMedicosForaRede->sql_query(null, $campos, "s154_i_medico", '');
        }

        $repassa = array();
        if (isset($chave_s154_i_medico)) {
          $repassa = array("chave_s154_i_medico" => $chave_s154_i_medico);
        }
        db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);

      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != '') {
          
          $sSql = $oDaoSauMedicosForaRede->sql_query($pesquisa_chave);
          $rs   = $oDaoSauMedicosForaRede->sql_record($sSql);
          if ($oDaoSauMedicosForaRede->numrows!=0) {

            db_fieldsmemory($rs, 0);
            echo "<script>".$funcao_js."('$s154_i_codigo', false);</script>";

          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
          }

        } else {
	        echo "<script>".$funcao_js."('', false);</script>";
        }

      }
      ?>
     </td>
   </tr>
</table>
</form>
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
js_tabulacaoforms("form2", "chave_s154_i_medico", true, 1, "chave_s154_i_medico", true);
</script>