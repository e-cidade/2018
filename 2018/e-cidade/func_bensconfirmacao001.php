<?php
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));
include(modification("classes/db_bens_classe.php"));
include(modification("classes/db_clabens_classe.php"));
include(modification("classes/db_db_depusu_classe.php"));
include(modification("classes/db_db_depart_classe.php"));
include(modification("classes/db_cfpatri_classe.php"));
$cldb_depart = new cl_db_depart;
$clbens      = new cl_bens;
$clclabens   = new cl_clabens;
$cldb_depusu = new cl_db_depusu;
$cldb_estrut = new cl_db_estrut;
$clcfpatri   = new cl_cfpatri;

$clbens->rotulo->label("t52_bem");
$clbens->rotulo->label("t52_descr");
$clbens->rotulo->label("t52_ident");
$clclabens->rotulo->label("t64_class");
$cldb_depart->rotulo->label("descrdepto");

$where_depart = "";
$where_g      = "";
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$result = $clcfpatri->sql_record($clcfpatri->sql_query_file());
db_fieldsmemory($result,0);
?>
  <html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table height="100%" width="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
          <form name="form2" method="post" action="" >
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tt52_bem?>">
                <label class="bold" id="lbl_chave_t52_bem" for="chave_t52_bem"><?=$Lt52_bem?></label>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("t52_bem",10,$It52_bem,true,"text",4,"","chave_t52_bem");
                ?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tt52_ident?>">
                <label class="bold" id="lbl_chave_t52_placa" for="chave_t52_placa"><?=$Lt52_ident?></label>
              </td>
              <td width="96%" align="left" nowrap>
                <?php
                $Nt52_ident = null;
                db_input("t52_ident", 10, $It52_ident, true, "text", 4, "", "chave_t52_placa");
                ?>
              </td>
            </tr>
            <tr>
              <?php
              $cldb_estrut->autocompletar = true;
              $cldb_estrut->funcao_onchange = 'js_troca(this.value)';
              $cldb_estrut->nomeform        = 'form2';
              $cldb_estrut->mascara         = false;
              $cldb_estrut->reload          = false;
              $cldb_estrut->input   = false;
              $cldb_estrut->size            = 10;
              $cldb_estrut->nome            = "t64_class";
              $cldb_estrut->db_opcao        = 1;
              $cldb_estrut->db_mascara($t06_codcla);
              ?>
            </tr>
            <tr>
              <td width="4%" align="left" nowrap title="<?=$Tt52_descr?>">
                <label class="bold" id="lbl_chave_t52_descr" for="chave_t52_descr"><?=$Lt52_descr?></label>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("t52_descr",40,$It52_descr,true,"text",4,"","chave_t52_descr");
                ?>&nbsp;&nbsp;
              </td>
              <td width="4%" align="left" nowrap title="<?=$Tdescrdepto?>">
                <label class="bold" id="lbl_descrdepto" for="descrdepto"><?=$Ldescrdepto?></label>
              </td>
              <td width="96%" align="left" nowrap>
                <?
                db_input("descrdepto",40,$Idescrdepto,true,"text",4,"");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                <input name="limpar" type="reset" id="limpar" value="Limpar" >
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bensconfirmacao.hide();">
              </td>
            </tr>
          </form>
        </table>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?php
        $aWhereGeral   = array('1 = 1');
        $where_instit  = " and db_depart.instit = ".db_getsession("DB_instit");
        $where2_instit = " and t52_instit = ".db_getsession("DB_instit");
        $where3_instit = " t52_instit = ".db_getsession("DB_instit");

        /*
         * Não trazer os bens baixados
         */
        $where_baixado = " and t55_codbem is null ";

        if(isset($campos)==false){
          if(file_exists("funcoes/db_func_bens.php")==true){
            include(modification("funcoes/db_func_bensconfirmacao.php"));
          }else{
            $campos = "bens.*";
          }
        }

        $campos = "distinct $campos";

        //rotina q busca o código do departamento quando o usuário informar o campo
        //descrição do departamento
        if (isset($descrdepto) && trim($descrdepto) != "") {
          $result_descrdepto = $cldb_depart->sql_record($cldb_depart->sql_query_file(null,"coddepto",null," descrdepto like '$descrdepto%' $where_instit"));
          $where = "";
          $or="";

          if($cldb_depart->numrows>0) {

            for($i=0;$i<$cldb_depart->numrows;$i++) {
              db_fieldsmemory($result_descrdepto,$i);
              if (!isset($chave_t93_depart) || $chave_t93_depart == $coddepto) {
                $where .= $or." t52_depart = $coddepto " ;
                $or = " or ";
              } else {
                $where = 'NDA';
              }
            }
          } else {
            $where = 'NDA';
          }
        }

        if (isset($chave_t93_depart) && $chave_t93_depart != "") {

          $where         = " t52_depart = {$chave_t93_depart} " ;
          $aWhereGeral[] = $where;
        }
        //fim

        //rotina q retira os pontos do estrutural da classe e busca,na clabens, o código do estrutural
        //informado pelo usuário na tabela clabens
        if (isset($t64_class) && trim($t64_class) != "") {
          $t64_class = str_replace(".","",$t64_class);
          $result_t64_codcla = $clclabens->sql_record($clclabens->sql_query_file(null,
                                                                                 "t64_codcla as chave_t64_codcla",
                                                                                 null,
                                                                                 " t64_class = '$t64_class' "));
          if ($clclabens->numrows>0) {
            db_fieldsmemory($result_t64_codcla,0);
          } else {
            $chave_t64_codcla = 'NDA';
          }
        }
        //fim

        $sWhereGeral = ' and ' . implode(' and ', $aWhereGeral);
        /**
         * Pesquisa para quando abre a lookup
         */
        if(!isset($pesquisa_chave) && !isset($pesquisa_chave_placa)) {

          /**
           * Filtro pelo código do bem
           */
          if (isset($chave_t52_bem) && (trim($chave_t52_bem)!="") ) {
            $sql = $clbens->sql_query_benstransf("",
                                                 $campos,
                                                 "t52_bem",
                                                 "t52_bem = {$chave_t52_bem} {$where2_instit} {$where_baixado} {$sWhereGeral} ",
                                                 "tudo");

            /**
             * Filtro por placa
             */
          } else if (isset($chave_t52_placa) && trim($chave_t52_placa) != "") {

            $sWhere = " t52_ident = '{$chave_t52_placa}' ";
            $sql    = $clbens->sql_query_benstransf("",
                                                    $campos,
                                                    "t52_bem",
                                                    " {$sWhere} {$where_depart} {$where_g} {$where2_instit} {$where_baixado} {$sWhereGeral} "
            );

            /**
             * Filtro por Classificação
             */
          } else if(isset($chave_t64_codcla) && (trim($chave_t64_codcla)!="") ) {

            if ($chave_t64_codcla == 'NDA') {
              $sql = $clbens->sql_query_benstransf("",$campos,"","t52_codcla = -1 $where2_instit $where_baixado {$sWhereGeral} ","tudo");
            } else {
              $sql = $clbens->sql_query_benstransf("",$campos,"","t52_codcla = $chave_t64_codcla $where2_instit $where_baixado {$sWhereGeral} ","tudo");
            }

            /**
             * Filtro por descrição do bem.
             */
          } else if (isset($chave_t52_descr) && (trim($chave_t52_descr) != "")) {
            $sql = $clbens->sql_query_benstransf("",
                                                 $campos,
                                                 "t52_descr",
                                                 "t52_descr like '$chave_t52_descr%' $where2_instit $where_baixado {$sWhereGeral} ",
                                                 "tudo");

            /**
             * Filtro por departamento.
             */
          } else if(isset($where) && (trim($where)!="") ) {

            /**
             * Não encontrou o departamento pela descrição.
             */
            if($where == 'NDA'){
              $sql = $clbens->sql_query_benstransf("",
                                                   $campos,
                                                   "",
                                                   " t52_depart = -1 $where2_instit $where_baixado {$sWhereGeral} ",
                                                   "",
                                                   "tudo");

              /**
               * Encontrou o departamento pela descrição.
               */
            } else {
              $sql = $clbens->sql_query_benstransf("",$campos,"","$where $where2_instit $where_baixado {$sWhereGeral} ","tudo");
            }

            /**
             * Sem filtro. Busca todos bens.
             */
          } else {
            $sql = $clbens->sql_query_benstransf("",$campos,"t52_bem","$where3_instit $where_baixado {$sWhereGeral} ","tudo");

          }

          db_lovrot($sql,15,"()","",$funcao_js);

          /**
           * Pesquisa sem abrir a lookup
           */
        } else {

          if (!empty($pesquisa_chave) || !empty($pesquisa_chave_placa)) {

            $sWhere = " ";
            if (!empty($pesquisa_chave)) {
              $sWhere = " t52_bem = $pesquisa_chave ";
            }

            if (!empty($pesquisa_chave_placa)) {
              $sWhere = " t52_ident = '{$pesquisa_chave_placa}' ";
            }

            if (isset($chave_id_usuario) && (trim($chave_id_usuario)!="") ) {
              $sql = $clbens->sql_query_benstransf("",
                                                   $campos,
                                                   "",
                                                   " {$sWhere} {$where2_instit} {$where_baixado} {$sWhereGeral} ",
                                                   "tudo");
              $result = $clbens->sql_record($sql);

            } else if(isset($chave_coddepto) && (trim($chave_descrdepto)!="")) {
              $sql = $clbens->sql_query_benstransf("",
                                                   $campos,
                                                   "",
                                                   " t52_depart = {$pesquisa_chave} {$where2_instit} {$where_baixado} {$sWhereGeral} ",
                                                   "tudo");
              $result = $clbens->sql_record($sql);

            } else {
              $result = $clbens->sql_record($clbens->sql_query(null,
                                                               "*",
                                                               null,
                                                               " {$sWhere} {$where2_instit} {$where_baixado} {$sWhereGeral} ",
                                                               "tudo"));
            }

            if($clbens->numrows!=0) {
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$t52_bem', '$t52_descr', '$t52_ident', false);</script>";

            } else {
              echo "<script>".$funcao_js."('', 'Chave(".$pesquisa_chave.") não Encontrado', '', true);</script>";
            }

          }else{
            echo "<script>".$funcao_js."('', '', '', false);</script>";
          }
        }
        ?>
      </td>
    </tr>
  </table>
  </body>
  </html>
  <script>
    function js_troca(obj){
      js_mascara02_t64_class();
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
