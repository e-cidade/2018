<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("classes/db_pctipocompra_classe.php"));

if (empty($chave_pc50_descr)) {
  $chave_pc50_descr = null;
}

if (empty($chave_pc50_codcom)) {
  $chave_pc50_codcom = null;
}
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clpctipocompra = new cl_pctipocompra;
$clpctipocompra->rotulo->label("pc50_codcom");
$clpctipocompra->rotulo->label("pc50_descr");

$sDescricao = isset($chave_pc50_descr) ? $chave_pc50_descr : null;
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
            <td width="4%" align="right" nowrap title="<?=$Tpc50_codcom?>">
              <?=$Lpc50_codcom?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("pc50_codcom",4,$Ipc50_codcom,true,"text",4,"","chave_pc50_codcom");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc50_descr?>">
              <?=$Lpc50_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              $chave_pc50_descr = htmlentities(stripslashes($sDescricao), ENT_QUOTES, 'ISO-8859-1');
              db_input("pc50_descr",50,$Ipc50_descr,true,"text",4,"","chave_pc50_descr");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pctipocompra.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      $chave_pc50_descr = pg_escape_string(stripcslashes($sDescricao));
      if(!isset($pesquisa_chave)){

        $campos = "pctipocompra.*";
        if(isset($chave_pc50_codcom) && (trim($chave_pc50_codcom)!="") ){
          $sql = $clpctipocompra->sql_query($chave_pc50_codcom,$campos,"pc50_codcom");
        }else if(isset($chave_pc50_descr) && (trim($chave_pc50_descr)!="") ){
          $sql = $clpctipocompra->sql_query("",$campos,"pc50_descr"," pc50_descr like '$chave_pc50_descr%' ");
        }else{
          $sql = $clpctipocompra->sql_query("",$campos,"pc50_codcom","");
        }
        $repassa = array();
        if(isset($chave_pc50_descr)){
          $repassa = array("chave_pc50_codcom"=>$chave_pc50_codcom,"chave_pc50_descr"=>$chave_pc50_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpctipocompra->sql_record($clpctipocompra->sql_query($pesquisa_chave));
          if($clpctipocompra->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc50_descr',false);</script>";
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
  js_tabulacaoforms("form2","chave_pc50_descr",true,1,"chave_pc50_descr",true);
</script>
<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
