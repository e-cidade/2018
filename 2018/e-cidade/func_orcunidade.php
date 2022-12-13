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
require_once(modification("classes/db_orcunidade_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$chave_o41_descr = isset($chave_o41_descr) ? stripslashes($chave_o41_descr) : '';

$clorcunidade = new cl_orcunidade;
$clorcunidade->rotulo->label("o41_anousu");
$clorcunidade->rotulo->label("o41_orgao");
$clorcunidade->rotulo->label("o41_unidade");
$clorcunidade->rotulo->label("o41_descr");

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
            <td width="4%" align="right" nowrap title="<?=$To41_orgao?>">
              <?=$Lo41_orgao?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              $db_opcao        = 4;
              if(isset($orgao)){
                $db_opcao        = 3;
                $chave_o41_orgao = $orgao;
              }
              db_input("o41_orgao",2,$Io41_orgao,true,"text",$db_opcao,"","chave_o41_orgao");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$To41_unidade?>">
              <?=$Lo41_unidade?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("o41_unidade",2,$Io41_unidade,true,"text",4,"","chave_o41_unidade");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$To41_descr?>">
              <?=$Lo41_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("o41_descr",50,$Io41_descr,true,"text",4,"","chave_o41_descr");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcunidade.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $chave_o41_descr = addslashes($chave_o41_descr);

      $wh1 = '';
      $wh  = '';
      if (isset($orgao)){

        $wh1 = " o41_orgao = {$orgao} and ";
        $wh  = " o41_orgao = {$orgao} and ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
          if(file_exists("funcoes/db_func_orcunidade.php")==true){
            include(modification("funcoes/db_func_orcunidade.php"));
          }else{
            $campos = "orcunidade.*,o58_instit as db_o58_instit,nomeinst as db_nomeinst";
          }
        }
        if( isset($chave_o41_unidade) ){
          if (  !DBNumber::isInteger($chave_o41_unidade) ) {
            $chave_o41_unidade = '';
          }
        }
        $campos = "orcunidade.*,o40_instit as db_o58_instit,db_config.nomeinst as db_nomeinst";
        if (!empty($chave_o41_orgao)) {
          $wh1 = " o41_orgao = $chave_o41_orgao and ";
        }
        if(isset($chave_o41_orgao) && (trim($chave_o41_orgao)!="") ){
          if(isset($orgaos) && $orgaos != ""){
            $wh1 = " o41_orgao in  ($orgaos) and ";
          }
          $sql = $clorcunidade->sql_query(null, " {$chave_o41_orgao} ",$chave_o41_unidade,$campos,"o41_orgao",$wh1." o41_anousu=".db_getsession("DB_anousu"));
        }
        if(isset($chave_o41_unidade) && trim($chave_o41_unidade) != ""){
          $sql = $clorcunidade->sql_query(null,"","",$campos,"o41_descr"," $wh o41_unidade = $chave_o41_unidade and o41_anousu=".db_getsession("DB_anousu"));
        }else if(isset($chave_o41_descr) && (trim($chave_o41_descr)!="") ){
          $sql = $clorcunidade->sql_query(null,"","",$campos,"o41_descr"," $wh o41_descr like '$chave_o41_descr%' and o41_anousu=".db_getsession("DB_anousu"));
        }else{

          if(isset($orgao) && $orgao != ""){
            $wh1 = " o41_orgao in  ($orgao) and ";
          }
          $sql = $clorcunidade->sql_query(null,"","",$campos,"o41_anousu#o41_orgao#o41_unidade",$wh1." o41_anousu=".db_getsession("DB_anousu"));
        }

        if( isset($chave_o41_descr) ){
          $chave_o41_descr = str_replace("\\", "", $chave_o41_descr);
        }

        db_lovrot($sql,15,"()","",$funcao_js);

      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $campos = "orcunidade.o41_orgao,o40_descr,orcunidade.o41_unidade,orcunidade.o41_codtri,orcunidade.o41_descr,
					           orcunidade.o41_indent,orcunidade.o41_cnpj,
										 orcunidade.o41_ident,orcunidade.o41_anousu as db_o41_anousu,o41_instit,db_config.nomeinst as db_nomeinst";
          if(isset($orgaos) && $orgaos != ""){
            $wh1 = " o41_orgao in  ($orgaos) and ";
          }


          $result = $clorcunidade->sql_record($clorcunidade->sql_query(null,'',null,$campos,'',$wh1." o41_anousu=".db_getsession("DB_anousu")."
					                             and o41_unidade = $pesquisa_chave" ));

          if($clorcunidade->numrows > 0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o41_descr',false,'$db_nomeinst','$o41_instit','$o41_orgao', '$db_o41_anousu');</script>";
          }else{
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,'');</script>";
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
    (function(){

      if( document.getElementById('chave_o41_unidade').value != '') {
        var oRegex  = /^[0-9]+$/;
        if ( !oRegex.test( document.getElementById('chave_o41_unidade').value ) ) {
          alert('Unidade deve ser preenchido somente com números!');
          document.getElementById('chave_o41_unidade').value = '';
          return false;
        }
      }

    })();
  </script>
  <?
}
?>
<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
