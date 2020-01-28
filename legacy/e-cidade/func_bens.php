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
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_bens_classe.php"));
require_once(modification("classes/db_cfpatri_classe.php"));
require_once(modification("classes/db_clabens_classe.php"));
require_once(modification("classes/db_db_depusu_classe.php"));
require_once(modification("classes/db_db_depart_classe.php"));
$cldb_depart = new cl_db_depart;
$clbens      = new cl_bens;
$clcfpatri   = new cl_cfpatri;
$clclabens   = new cl_clabens;
$cldb_depusu = new cl_db_depusu;
$cldb_estrut = new cl_db_estrut;

$clbens->rotulo->label("t52_bem");
$clbens->rotulo->label("t52_ident");
$clbens->rotulo->label("t52_descr");
$clclabens->rotulo->label("t64_class");
$cldb_depart->rotulo->label("descrdepto");

db_postmemory($_POST);
db_postmemory($_GET);
$oGet = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$lConsultaBens = !empty($oGet->lConsultaBens) ? true : false;
$sStyleDisplay = $lConsultaBens ? "" : "display:none;";


parse_str($_SERVER["QUERY_STRING"]);

$result_t06_codcla = $clcfpatri->sql_record($clcfpatri->sql_query_file(null,"t06_codcla"));
if($clcfpatri->numrows>0){
  db_fieldsmemory($result_t06_codcla,0);
}

define("SITUACAO_TODOS", "1");
define("SITUACAO_ATIVO", "2");
define("SITUACAO_BAIXADO", "3");

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<div class="container">
  <fieldset style="width: 500px;">
    <legend class="bold">Filtros</legend>
    <form name="form2" method="post" action="">
      <table width="35%" border="0" align="center" cellspacing="0">
        <tr>
          <td width="4%" align="left" nowrap title="<?=$Tt52_ident?>">
            <?=$Lt52_ident?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
            db_input("t52_ident",20,$It52_ident,true,"text",4,"","chave_t52_ident");
            ?>
          </td>
        </tr>
        <tr>
          <td width="4%" align="left" nowrap title="<?=$Tt52_bem?>">
            <?=$Lt52_bem?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
            db_input("t52_bem",8,$It52_bem,true,"text",4,"","chave_t52_bem");
            ?>
          </td>
        </tr>
        <?
        $cldb_estrut->autocompletar = true;
        $cldb_estrut->funcao_onchange = 'js_troca(this.value)';
        $cldb_estrut->nomeform = 'form2';
        $cldb_estrut->mascara = false;
        $cldb_estrut->reload  = false;
        $cldb_estrut->input   = false;
        $cldb_estrut->size    = 10;
        $cldb_estrut->nome    = "t64_class";
        $cldb_estrut->db_opcao= 1;
        $cldb_estrut->db_mascara(@$t06_codcla);
        ?>
        <tr>
          <td width="4%" align="left" nowrap title="<?=$Tt52_descr?>">
            <?=$Lt52_descr?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
            db_input("t52_descr",40,$It52_descr,true,"text",4,"","chave_t52_descr");
            ?>&nbsp;&nbsp;
          </td>
        </tr>
        <tr>
          <td width="4%" align="left" nowrap title="<?=$Tdescrdepto?>">
            <?=$Ldescrdepto?>
          </td>
          <td width="96%" align="left" nowrap>
            <?
            db_input("descrdepto",40,$Idescrdepto,true,"text",4,"");
            ?>
          </td>
        </tr>
        <tr style="<?php echo $sStyleDisplay; ?>">
          <td>
            <b>Situação:</b>
          </td>
          <td>
            <?php
            $aSituacao = array(SITUACAO_TODOS => 'Todos', SITUACAO_ATIVO => 'Ativos', SITUACAO_BAIXADO => 'Baixados');
            db_select('situacao', $aSituacao, 1, 1);
            ?>
          </td>
        </tr>
      </table>
  </fieldset>
  <p>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar"    type="button"  id="limpar"     value="Limpar" onClick="js_limpar();">
    <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_bens.hide();">
  </p>
  </form>
</div>

<div class="container">
  <?
  $where_instit  = " and t52_instit = ".db_getsession("DB_instit");
  $where_baixado = " and not exists ( select 1 from bensbaix where bensbaix.t55_codbem = t52_bem )";

  if ( !empty($opcao) ){

    $where_baixado = "";
    if ($opcao == "baixados") {
      $where_baixado = " and exists ( select 1 from bensbaix where bensbaix.t55_codbem = t52_bem )";
    }
  }

  if ($lConsultaBens && !empty($oPost->situacao)) {

    $where_baixado = "";
    switch ($oPost->situacao) {

      case SITUACAO_BAIXADO:
        $where_baixado = " and exists ( select 1 from bensbaix where bensbaix.t55_codbem = t52_bem ) ";
        break;

      case SITUACAO_ATIVO:
        $where_baixado = " and not exists ( select 1 from bensbaix where bensbaix.t55_codbem = t52_bem ) ";
        break;
    }
  }

  if (isset($campos)==false) {
    if (file_exists("funcoes/db_func_bens.php")==true) {
      include(modification("funcoes/db_func_bens.php"));
    } else{
      $campos = "bens.*";
    }
  }

  $campos = "distinct $campos";
  if (!isset($pesquisa_chave)) {

    if (isset($t64_class) && trim($t64_class) != ""){
      //rotina q retira os pontos do estrutural da classe e busca o código do estrutural na tabela clabens
      $t64_class = str_replace(".","",$t64_class);
      $result_t64_codcla = $clclabens->sql_record($clclabens->sql_query_file(null,"t64_codcla as chave_t64_codcla",null," t64_class = '$t64_class' "));
      if ($clclabens->numrows>0){
        db_fieldsmemory($result_t64_codcla,0);
      } else {
        $chave_t64_codcla = 'NDA';
      }
    }

    if (isset($descrdepto) && trim($descrdepto) != "") {

      //rotina q busca o código do departamento
      $result_descrdepto = $cldb_depart->sql_record($cldb_depart->sql_query_div(null,"coddepto",null," descrdepto like '$descrdepto%' and db_depart.instit = ".db_getsession("DB_instit")));
      $where = "";

      if ($cldb_depart->numrows>0) {

        for ($i=0;$i<$cldb_depart->numrows;$i++) {
          db_fieldsmemory($result_descrdepto,$i);
          $chave_coddepto[$i] = $coddepto;
        }
        $or="";

        for ($i=0;$i<sizeof($chave_coddepto);$i++) {
          $where .= $or." t52_depart =$chave_coddepto[$i] " ;
          $or = " or ";
        }

      } else {
        $where = 'NDA';
      }
    }

    $sql = "";
    $sMetodoQuery = "sql_query";

    if ( isset($oGet->lObrigaConta) && $oGet->lObrigaConta == 'false') {
      $sMetodoQuery = "sql_query_func_pesquisa";
    }

    if (isset($chave_t52_bem) && trim($chave_t52_bem) != "") {
      $sql = $clbens->{$sMetodoQuery}(null,$campos,"t52_bem","t52_bem = $chave_t52_bem $where_instit $where_baixado");
    } else if (isset($chave_t52_ident) && (trim($chave_t52_ident)!="") ) {
      $sql = $clbens->{$sMetodoQuery}(null,$campos,"t52_ident","t52_ident like '$chave_t52_ident' $where_instit $where_baixado");
    } else if (isset($chave_t64_codcla) && (trim($chave_t64_codcla)!="") ) {
      if ($chave_t64_codcla == 'NDA') {
        $sql = $clbens->{$sMetodoQuery}("",$campos,""," t52_codcla = -1 $where_instit $where_baixado");
      } else {
        $sql = $clbens->{$sMetodoQuery}("",$campos,""," t52_codcla = $chave_t64_codcla $where_instit $where_baixado");
      }
    } else if (isset($chave_t52_descr) && (trim($chave_t52_descr)!="") ) {
      $sql = $clbens->{$sMetodoQuery}("",$campos,"t52_descr"," t52_descr like '$chave_t52_descr%' $where_instit $where_baixado");
    } else if (isset($chave_depto) ) {

      if(trim($chave_depto) != ""){
        $dbwhere = "t52_depart = $chave_depto";
      }

      if (isset($departamentos) && trim($departamentos)!= "") {
        $dbwhere = "t52_depart in $departamentos";
      }

      if (isset($chave_div) && trim($chave_div) != ""){
        $dbwhere .= " and t33_divisao = $chave_div";
      }

      if (isset($divisoes) && trim($divisoes)!= "") {
        $dbwhere = "t33_divisao in $divisoes";
      }

      $sql = $clbens->{$sMetodoQuery}("",$campos,"t52_bem#t52_descr","$dbwhere $where_instit $where_baixado");

    } else if(isset($where) && (trim($where)!="") ) {
      if ($where == 'NDA') {
        $sql = $clbens->{$sMetodoQuery}("",$campos,""," t52_depart = -1 $where_instit $where_baixado");
      } else {
        $sql = $clbens->{$sMetodoQuery}("",$campos,"","$where $where_instit $where_baixado");
      }
    } else {
      if (isset($pesquisar) && trim($pesquisar) != "") {
        $sql = $clbens->{$sMetodoQuery}("",$campos,"t52_bem","t52_instit = ".db_getsession("DB_instit")." ".$where_baixado);
      }
    }

    db_lovrot($sql,15,"()","",$funcao_js);

  } else {
    if ($pesquisa_chave!=null && $pesquisa_chave!="") {

      if (isset($chave_coddepto) && (trim($chave_descrdepto)!="")) {

        $sql    = $clbens->{$sMetodoQuery}("",$campos,"","t52_depart = $pesquisa_chave $where_instit $where_baixado");
        $result = $clbens->sql_record($sql);

      } else if (isset($chave_deptos) && (trim($chave_deptos)!="")){

        $where_baixado = " and not exists ( select 1 from bensbaix where bensbaix.t55_codbem = t52_bem )";

        if (trim($chave_deptos)!= "") {
          $dbwhere = "t52_depart in ($chave_deptos)";
        }

        if (isset($chave_divs) && trim($chave_divs) != ""){
          $dbwhere .= " and t33_divisao in ($chave_divs)";
        }

        $sql    = $clbens->{$sMetodoQuery}("",$campos,"", "t52_bem = $pesquisa_chave and ".$dbwhere." and t52_instit = ".db_getsession("DB_instit")." ".$where_baixado);
        $result = $clbens->sql_record($sql);
        //echo "<script> alert($sql)</script>";
      } else {

        if (!isset($lRetornoPlaca)) {

          $sql    = $clbens->{$sMetodoQuery}(null,$campos,"","t52_bem = $pesquisa_chave and t52_instit = ".db_getsession("DB_instit")." ".$where_baixado);
          $result = $clbens->sql_record($sql);
        } else {

          $sql    = $clbens->{$sMetodoQuery}(null,$campos,"","t52_ident = '{$pesquisa_chave}' and t52_instit = ".db_getsession("DB_instit"));
          $result = $clbens->sql_record($sql);
        }
      }

      if (!isset($lRetornoPlaca)) {

        if($clbens->numrows != 0) {

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$t52_descr',false);</script>";
        } else {
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      } else {

        if ($clbens->numrows != 0) {

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$t52_ident',false);</script>";
        } else {
          echo "<script>".$funcao_js."('', true);</script>";
        }
      }


    } else {
      echo "<script>".$funcao_js."('',false);</script>";
    }
  }
  ?>
</div>
</body>
</html>
<script>
  function js_troca(obj){
    js_mascara02_t64_class();
  }
  function js_limpar(){
    document.form2.t64_class.value       = "";
    document.form2.chave_t52_bem.value   = "";
    document.form2.chave_t52_descr.value = "";
    document.form2.descrdepto.value      = "";
  }
</script>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
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
