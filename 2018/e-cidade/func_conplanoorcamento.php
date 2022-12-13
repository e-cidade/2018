<?php
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

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clconplanoorcamento = new cl_conplanoorcamento;
$clconplanoorcamento->rotulo->label("c60_codcon");
$clconplanoorcamento->rotulo->label("c60_anousu");
$clconplanoorcamento->rotulo->label("c60_descr");

$oGet  = db_utils::postMemory($_GET);
$oPOST = db_utils::postMemory($_POST);
$sDescricaoConta  = isset($chave_c60_descr)  ? $chave_c60_descr : null;
$chave_c60_codcon = isset($chave_c60_codcon) ? $chave_c60_codcon : null;
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
            <td width="4%" align="right" nowrap title="<?php echo $Tc60_codcon?>">
              <?php echo $Lc60_codcon?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_input("c60_codcon",6,$Ic60_codcon,true,"text",4,"","chave_c60_codcon");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Tc60_descr?>">
              <?php echo $Lc60_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              $chave_c60_descr = htmlentities(stripslashes($sDescricaoConta), ENT_QUOTES, 'ISO-8859-1');
              db_input("c60_descr",50,$Ic60_descr,true,"text",4,"","chave_c60_descr");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Código Reduzido">
              <b>Reduzido:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              $SiReduzido = "Reduzido";
              db_input("iReduzido",50, 1,true,"text",4,"");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="Estrutural">
              <b>Estrutural:</b>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              $SsEstrutural = "Estrutural";
              db_input("sEstrutural",50, 1,true,"text",4,"");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplanoorcamento.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

      $aWherePadrao = array();
      $aWherePadrao[] = "c60_anousu = ".db_getsession("DB_anousu");
      $chave_c60_descr = pg_escape_string(stripcslashes($sDescricaoConta));

      if (!empty($oGet->sSomenteEstrutural)) {
        $aWherePadrao[] = "c60_estrut ilike '{$oGet->sSomenteEstrutural}%'";
      }

      if(!isset($pesquisa_chave)){

        $campos = "conplanoorcamento.*";

        $sWherePadrao[] = " c60_anousu = ".db_getsession("DB_anousu");
        if (!empty($oGet->sSomenteEstrutural)) {
          $aWherePadrao[] = "c60_estrut ilike '{$oGet->sSomenteEstrutural}%'";
        }
        if(isset($chave_c60_codcon) && (trim($chave_c60_codcon)!="") ){

          $aWherePadrao[] = "c60_codcon = {$chave_c60_codcon}";
          $sql = $clconplanoorcamento->sql_query_geral(null, null, $campos, "c60_estrut", implode(' and ', $aWherePadrao));

        }else if(isset($chave_c60_descr) && (trim($chave_c60_descr)!="") ){

          $sql = $clconplanoorcamento->sql_query_geral("","",$campos,"c60_estrut"," c60_descr like '$chave_c60_descr%' and ".implode(' and ', $aWherePadrao));

        } else if (isset($iReduzido) && trim($iReduzido) != "") {

          $aWherePadrao[] = "c61_reduz = {$iReduzido}";
          $sql = $clconplanoorcamento->sql_query_geral(null, null, $campos, "c60_estrut", implode(' and ', $aWherePadrao));
        } else if (isset($sEstrutural) && trim($sEstrutural) != "") {

          $aWherePadrao[] = "c60_estrut like '{$sEstrutural}%'";
          $sql = $clconplanoorcamento->sql_query_geral(null, null, $campos, "c60_estrut", implode(' and ', $aWherePadrao));
        } else{
          $sql = $clconplanoorcamento->sql_query_geral("","",$campos, "c60_estrut", implode(' and ', $aWherePadrao));
        }
        $repassa = array();
        if(isset($chave_c60_descr)){
          $repassa = array("chave_c60_codcon"=>$chave_c60_codcon,"chave_c60_descr"=>$chave_c60_descr);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clconplanoorcamento->sql_record($clconplanoorcamento->sql_query_geral($pesquisa_chave, db_getsession('DB_anousu')));
          if($clconplanoorcamento->numrows!=0){
            db_fieldsmemory($result,0);

            $sDescricao = $c60_descr;
            if (!empty($lEstrutural)) {
              $sDescricao = "{$c60_estrut} - {$c60_descr}";
            }

            echo "<script>" . $funcao_js . "('$sDescricao',false);</script>";
          }else{
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não encontrada.',true);</script>";
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
<?php
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?php
}
?>
<script>
  js_tabulacaoforms("form2","chave_c60_descr",true,1,"chave_c60_descr",true);
</script>
<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
