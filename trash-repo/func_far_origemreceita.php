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
require_once("classes/db_far_origemreceita_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaofar_origemreceita = new cl_far_origemreceita;
$oDaofar_origemreceita->rotulo->label("fa40_i_codigo");
$oDaofar_origemreceita->rotulo->label("fa40_c_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tfa40_i_codigo?>">
              <?=$Lfa40_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	  	        db_input("fa40_i_codigo",10,$Ifa40_i_codigo,true,"text",4,"","chave_fa40_i_codigo");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tfa40_c_descr?>">
              <?=$Lfa40_c_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input('fa40_c_descr', 50, $Ifa40_c_descr, true, 'text', 4, '', 'chave_fa40_c_descr');
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_far_origemreceita.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sSepVal = '';
      $sValidade = '';
      if(isset($chave_validade)) {
  
        $dDataAtual = date('Y-m-d', db_getsession('DB_datausu'));
        $sValidade = " (fa40_d_validade is null or fa40_d_validade >= '$dDataAtual')";
        $sSepVal = ' and ';

      }

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_far_origemreceita.php")==true){
             require_once("funcoes/db_func_far_origemreceita.php");
           }else{
           $campos = "far_origemreceita.*";
           }
        }
        if(isset($chave_fa40_i_codigo) && (trim($chave_fa40_i_codigo) != '')) {

	         $sql = $oDaofar_origemreceita->sql_query(null, $campos, 'fa40_i_codigo', 
                                                  "fa40_i_codigo = $chave_fa40_i_codigo $sSepVal $sValidade");

        } else if(isset($chave_fa40_c_descr) && (trim($chave_fa40_c_descr) != '') ) {

	         $sql = $oDaofar_origemreceita->sql_query(null, $campos, 'fa40_c_descr', " fa40_c_descr like".
                                                  " '$chave_fa40_c_descr%' $sSepVal $sValidade ");

        } else {

           $sql = $oDaofar_origemreceita->sql_query("",$campos,"fa40_i_codigo", $sValidade);

        }
        $repassa = array();
        if(isset($chave_fa40_i_codigo)){
          $repassa = array("chave_fa40_i_codigo"=>$chave_fa40_i_codigo,"chave_fa40_i_codigo"=>$chave_fa40_i_codigo);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {

        if($pesquisa_chave != null && $pesquisa_chave != '') {

          $sSql = $oDaofar_origemreceita->sql_query(null, '*', null, 
                                                    " fa40_i_codigo = $pesquisa_chave $sSepVal $sValidade");
          $result = $oDaofar_origemreceita->sql_record($sSql);
          if($oDaofar_origemreceita->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$fa40_c_descr',false);</script>";

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
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_fa40_i_codigo",true,1,"chave_fa40_i_codigo",true);
</script>