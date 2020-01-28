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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_levanta_classe.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cllevanta = new cl_levanta;
$clrotulo  = new rotulocampo();
$cllevanta->rotulo->label("y60_codlev");
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body>
<table height="100%" border="0"align="center">
  <tr>
    <td height="63" align="center" valign="top">
      <table width="80%" border="0" align="center">
       <form name="form1" method="post" action="" >
          <tr>
            <td width="30%" align="right" nowrap title="<?php echo $Ty60_codlev; ?>">
              <?php echo $Ly60_codlev; ?>
            </td>
            <td align="left" nowrap>
              <?php db_input("y60_codlev", 10, $Iy60_codlev, true, "text", 4, "", "chave_y60_codlev"); ?>
            </td>
          </tr>
          <tr>
            <td title="<?php echo $Tq02_inscr; ?>" align="right">
              <?php db_ancora($Lq02_inscr, 'js_inscr(true);', 1); ?>
            </td>
            <td nowrap>
              <?php
                db_input('q02_inscr', 5, $Iq02_inscr, true, 'text', 1, "onchange='js_inscr(false)'");
                db_input('z01_nome', 30, 0, true, 'text', 3, "", "z01_nomeinscr");
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?php echo $Tz01_numcgm; ?>" nowrap align="right">
              <?php db_ancora($Lz01_nome, 'js_cgm(true);', 1); ?>
            </td>
            <td nowrap>
              <?php
               db_input('z01_numcgm', 5, $Iz01_numcgm, true, 'text', 1, "onchange='js_cgm(false)'");
               db_input('z01_nome', 30, 0, true, 'text', 3, "", "z01_nomecgm");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" onClick="js_limparFormulario();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();"/>
             </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

        if(!isset($pesquisa_chave)) {

          if(isset($campos) == false) {

            if(file_exists("funcoes/db_func_levanta.php") == true) {
              include(modification("funcoes/db_func_levanta.php"));
            } else {
              $campos = "levanta.*";
            }
          }

          if(isset($chave_y60_codlev) && (trim($chave_y60_codlev) != "")) {

            $sql = $cllevanta->sql_query_pesquisa(null, "*", "y60_codlev", "y60_codlev = $chave_y60_codlev");

          } else if(isset($q02_inscr) && (trim($q02_inscr) != "")) {

            $sql = $cllevanta->sql_query_pesquisa(null, "*", "y60_codlev", "x.y62_inscr = $q02_inscr");

          } else if(isset($z01_numcgm) && (trim($z01_numcgm) != "")) {

            $sql = $cllevanta->sql_query_pesquisa(null, "*", "y60_codlev", "x.y93_numcgm = $z01_numcgm");

          }else{
            $sql = $cllevanta->sql_query_pesquisa(null, "*", "y60_codlev");
          }

          db_lovrot($sql, 15, "()", "", $funcao_js);
        }else{

          if($pesquisa_chave != null && $pesquisa_chave != "") {

            $sWhere = "";

            if(isset($q02_inscr) && (trim($q02_inscr) != "")) {
              $sWhere .= " x.y62_inscr = $q02_inscr";
            }

            if(isset($z01_numcgm) && (trim($z01_numcgm) != "")) {

              $sWhere .= (empty($sWhere)) ? "" : " and ";
              $sWhere .= " x.y93_numcgm = $z01_numcgm ";
            }

            $sWhere .= (empty($sWhere)) ? "" : " and ";
            $sWhere .= " y60_codlev = $pesquisa_chave ";

            $result = $cllevanta->sql_record($cllevanta->sql_query_pesquisa(null, "*", null, $sWhere));

            if($cllevanta->numrows != 0) {

              db_fieldsmemory($result, 0);
              echo "<script>".$funcao_js."('$dbtxtnome_origem', false);</script>";
            }else{
              echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
            }
          }else{
            echo "<script>".$funcao_js."('', false);</script>";
          }
        }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script type="text/javascript">

function js_limparFormulario() {
  document.getElementById('limpar').click();
}

function js_inscr(mostra){

  var inscr = document.form1.q02_inscr.value;

  if(mostra == true) {
    js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true,0,0,780,430);
  } else {

    if(inscr != "") {
      js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    }else{
      document.form1.z01_nomeinscr.value = "";
    }
  }
}

function js_mostrainscr(chave1, chave2) {
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe_issbase.hide();
}

function js_mostrainscr1(chave, erro) {

  document.form1.z01_nomeinscr.value = chave;

  if(erro == true) {
    document.form1.q02_inscr.focus();
    document.form1.q02_inscr.value = '';
  }
}

function js_cgm(mostra) {

  var cgm = document.form1.z01_numcgm.value;

  if(mostra == true) {
    js_OpenJanelaIframe('','db_iframe_numcgm','func_nome.php?funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome','Pesquisa',true,0,0,780,430);
  } else {

    if(cgm != "") {
      js_OpenJanelaIframe('','db_iframe_numcgm','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
    } else {
      document.form1.z01_nomecgm.value = '';
    }
  }
}

function js_mostracgm(chave1, chave2){

  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe_numcgm.hide();
}

function js_mostracgm1(erro, chave){

  document.form1.z01_nomecgm.value = chave;

  if(erro == true) {
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}

</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
