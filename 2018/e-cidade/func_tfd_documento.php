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
require_once("classes/db_tfd_documento_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oDaotfd_documento = new cl_tfd_documento;
$oDaotfd_documento->rotulo->label("tf07_i_codigo");
$oDaotfd_documento->rotulo->label("tf07_c_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Ttf07_i_codigo?>">
              <?=$Ltf07_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("tf07_i_codigo",10,$Itf07_i_codigo,true,"text",4,"","chave_tf07_i_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ttf07_c_descr?>">
              <?=$Ltf07_c_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("tf07_c_descr",40,$Itf07_c_descr,true,"text",4,"","chave_tf07_c_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tfd_documento.hide();">
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
        $sValidade = " (tf07_d_validade is null or tf07_d_validade >= '$dDataAtual')";
        $sSepVal = ' and ';

      }

      if(!isset($pesquisa_chave)) {

        if(isset($campos)==false) {

           if(file_exists("funcoes/db_func_tfd_documento.php")==true) {
             require_once("funcoes/db_func_tfd_documento.php");
           } else {
             $campos = "tfd_documento.*";
           }

        }

        if(isset($chave_tf07_i_codigo) && (trim($chave_tf07_i_codigo) != '')) {

	         $sSql = $oDaotfd_documento->sql_query(null, $campos, 'tf07_i_codigo', "tf07_i_codigo = ".
                                               "$chave_tf07_i_codigo $sSepVal $sValidade");

        } else if(isset($chave_tf07_c_descr) && (trim($chave_tf07_c_descr) != '')) {

	         $sSql = $oDaotfd_documento->sql_query(null, $campos, 'tf07_c_descr', " tf07_c_descr like '$chave_tf07_c_descr%'".
                                               "$sSepVal $sValidade");

        } else {

           $sSql = $oDaotfd_documento->sql_query(null, $campos, 'tf07_i_codigo', $sValidade);

        }

        $repassa = array();
        if(isset($chave_tf07_i_codigo)) {
          $repassa = array("chave_tf07_i_codigo"=>$chave_tf07_i_codigo,"chave_tf07_c_descr"=>$chave_tf07_c_descr);
        }
        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);

      } else {

        if($pesquisa_chave != null && $pesquisa_chave != '') {

          $sSql = $oDaotfd_documento->sql_query(null, '*', null, " tf07_i_codigo = $pesquisa_chave $sSepVal $sValidade");
          $result = $oDaotfd_documento->sql_record($sSql);
          if($oDaotfd_documento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tf07_c_descr',false);</script>";
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
js_tabulacaoforms("form2","chave_tf07_i_codigo",true,1,"chave_tf07_i_codigo",true);
</script>