<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
  
  db_postmemory($HTTP_POST_VARS);
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  
  $oDaoRegraArredondamento = db_utils::getDao("regraarredondamento");
  $oDaoRegraArredondamento->rotulo->label("ed316_sequencial");
  $oDaoRegraArredondamento->rotulo->label("ed316_descricao");
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
                <td width="4%" align="right" nowrap title="<?=$Ted316_sequencial?>">
                  <?=$Led316_sequencial?>
                </td>
                <td width="96%" align="left" nowrap> 
                  <?
    		            db_input("ed316_sequencial",10,$Ied316_sequencial,true,"text",4,"","chave_ed316_sequencial");
    		          ?>
                </td>
              </tr>
              <tr> 
                <td width="4%" align="right" nowrap title="<?=$Ted316_descricao?>">
                  <?=$Led316_descricao?>
                </td>
                <td width="96%" align="left" nowrap> 
                  <?
    		            db_input("ed316_descricao",100,$Ied316_descricao,true,"text",4,"","chave_ed316_descricao");
    		          ?>
                </td>
              </tr>
              <tr> 
                <td colspan="2" align="center"> 
                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
                  <input name="limpar" type="reset" id="limpar" value="Limpar" >
                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_regraarredondamento.hide();">
                 </td>
              </tr>
            </form>
          </table>
        </td>
      </tr>
      <tr> 
        <td align="center" valign="top"> 
          <?
            $sWhere = " ed316_ativo <> 'f'";
            if (!isset($pesquisa_chave)) {
              
              if (isset($campos) == false) {
                if (file_exists("funcoes/db_func_regraarredondamento.php") == true) {
                  require_once("funcoes/db_func_regraarredondamento.php");
                } else {
                  $campos = "regraarredondamento.*";
                }
              }
              if (isset($chave_ed316_sequencial) && (trim($chave_ed316_sequencial) != "")) {
                
                if (isset($pesquisa)  && $pesquisa == 'E') {
                  $sql = $oDaoRegraArredondamento->sql_query($chave_ed316_sequencial,$campos,"ed316_sequencial", $sWhere);
                } else {
      	          $sql = $oDaoRegraArredondamento->sql_query($chave_ed316_sequencial,$campos,"ed316_sequencial");
                }
              }
              if (isset($chave_ed316_descricao) && (trim($chave_ed316_descricao) !="" )) {

                if (isset($pesquisa)  && $pesquisa == 'E') {
                  $sql = $oDaoRegraArredondamento->sql_query("",$campos,"ed316_descricao"," ed316_descricao like '$chave_ed316_descricao%' and {$sWhere}");
                } else {
      	          $sql = $oDaoRegraArredondamento->sql_query("",$campos,"ed316_descricao"," ed316_descricao like '$chave_ed316_descricao%' ");
                }
              } else {
                
                if (isset($pesquisa)  && $pesquisa == 'E') {
                  $sql = $oDaoRegraArredondamento->sql_query("",$campos,"ed316_sequencial",$sWhere);
                } else {
                  $sql = $oDaoRegraArredondamento->sql_query("",$campos,"ed316_sequencial","");
                }
              }
              $repassa = array();
              if (isset($chave_ed316_descricao)) {
                $repassa = array("chave_ed316_sequencial"=>$chave_ed316_sequencial,"chave_ed316_descricao"=>$chave_ed316_descricao);
              }
              db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
            } else {
              if ($pesquisa_chave != null && $pesquisa_chave != "") {
                $result = $oDaoRegraArredondamento->sql_record($oDaoRegraArredondamento->sql_query($pesquisa_chave));
                if ($oDaoRegraArredondamento->numrows != 0) {
                  db_fieldsmemory($result,0);
                  echo "<script>".$funcao_js."('$ed316_descricao',false);</script>";
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
  js_tabulacaoforms("form2","chave_ed316_descricao",true,1,"chave_ed316_descricao",true);
</script>