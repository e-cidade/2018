<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_debcontapedido_classe.php"));
require_once(modification("libs/db_utils.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cldebcontapedido = new cl_debcontapedido;
$cldebcontapedido->rotulo->label("d63_codigo");
$cldebcontapedido->rotulo->label("d63_instit");
$cldebcontapedido->rotulo->label("d63_idempresa");

$oGet             = new _db_fields();
$oGet             = db_utils::postMemory($HTTP_GET_VARS);
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
            <td width="4%" align="right" nowrap title="<?=$Td63_codigo?>">
              <?=$Ld63_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("d63_codigo",10,$Id63_codigo,true,"text",4,"","chave_d63_codigo");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Td63_idempresa?>">
              <?=$Ld63_idempresa?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
           db_input('d63_idempresa',25,$Id63_idempresa,true,"text",4,"","chave_d63_idempresa")
		       ?>
            </td>
          </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_debcontapedido.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhere = " d63_instit = ".db_getsession("DB_instit");
      $sAnd   = " and ";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_debcontapedido.php")==true){
             include(modification("funcoes/db_func_debcontapedido.php"));
           }else{
             $campos = "debcontapedido.*";
           }
           if( isset($oGet->sTipo) ){
             if ( $oGet->sTipo == 'CGM' ) {
             	 $campos .= ',debcontapedidocgm.d70_numcgm';
             	 $sWhere .= ' and debcontapedidocgm.d70_numcgm is not null ';
             } else if ( $oGet->sTipo == 'MATRIC' ) {
             	 $campos .= ',debcontapedidomatric.d68_matric';
             	 $sWhere .= ' and debcontapedidomatric.d68_matric is not null ';
             } else if ( $oGet->sTipo == 'INSCR' ) {
             	 $campos .= ',debcontapedidoinscr.d69_inscr';
             	 $sWhere .= ' and debcontapedidoinscr.d69_inscr is not null ';
             } else if ( $oGet->sTipo == 'AGUA' ) {
               $campos .= ',d81_contrato, d82_economia';
               $sWhere .= ' and (d81_codigo is not null or d82_codigo is not null) ';
             }
           }
        }
        if(isset($chave_d63_codigo) && (trim($chave_d63_codigo)!="") ){
	         $sql = $cldebcontapedido->sql_query_info(null,$campos,"d63_codigo",null,"d63_codigo = $chave_d63_codigo $sAnd $sWhere" );
        }else if(isset($chave_d63_idempresa) && (trim($chave_d63_idempresa)!="") ){
	         $sql = $cldebcontapedido->sql_query_info("",$campos,"d63_idempresa"," d63_idempresa like '$chave_d63_idempresa%' $sAnd $sWhere");
        }else{
           $sql = $cldebcontapedido->sql_query_info("",$campos,"d63_codigo","$sWhere");
        }

        db_lovrot($sql,15,"()","",$funcao_js, "", "NoMe", array(), false);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldebcontapedido->sql_record($cldebcontapedido->sql_query(null,"*",null,"db63_codigo = $pesquisa_chave $sAnd $sWhere"));
          if($cldebcontapedido->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$d63_instit',false);</script>";
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

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
