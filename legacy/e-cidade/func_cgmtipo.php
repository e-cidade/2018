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
/*
  Func de pesquisa que filtra por tipo CGM (Fisico/Juridico)
  @param string tipo fisico|juridico
 */
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_cgm_classe.php"));

db_postmemory($HTTP_POST_VARS);

if (!isset($pesquisar)) {
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
}

$oGet = db_utils::postMemory($_GET);

if (!isset($oGet->tipo)) {
  $oGet->tipo = '';
}

$clcgm = new cl_cgm;
$clrotulo = new rotulocampo;

$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");
$clcgm->rotulo->label("z01_cgccpf");

$clrotulo->label("DBtxt30");
$clrotulo->label("DBtxt31");

if(isset($script) && !empty($script)) { ?>
  <script>
  <?php
    $vals = "";
    $vir  = "";
    $camp = split(",",$valores);

    for ($f = 0; $f < count($camp); $f++) {
      $vals .= $vir . "'" . $camp[$f] . "'";
      $vir   = ",";
    }
    echo $script."(".$vals.")";
  ?>
  </script>
<?php
exit;
}

if (isset($testanome) && !isset($pesquisa_chave)) {

  $funmat = split("\|",$funcao_js);
  $func_antes = $funmat[0];
  $valores = "";
  $camp = "";
  $vir = "";

  for ($i = 1; $i < count($funmat); $i++) {
    if($funmat[$i] == "0") {
      $funmat[$i] = "z01_numcgm";
    }

    if($funmat[$i] == "1") {
      $funmat[$i] = "z01_nome";
    }

    $valores .= "|" . $funmat[$i];
    $camp  .= $vir . $funmat[$i];
    $vir .= ",";
  }

  $funmat[0] = "js_testanome";
  $funcao_js = $funmat[0] . "|z01_numcgm|z01_ender|z01_cgccpf" . $valores;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_submit_numcgm_buscanome(numcgm){
    document.form_busca_dados.numcgm_busca_dados.value = numcgm;
    document.form_busca_dados.submit();
  }

  function js_limpa(){
    for (var i = 0; i < document.form2.elements.length; i++) {
      if (document.form2.elements[i].type == 'text') {
        document.form2.elements[i].value = "";
      }
    }
  }

  <?php if(isset($testanome) && !isset($pesquisa_chave)): ?>

    function js_testanome(z01_numcgm, ender, cgccpf, <?= $camp; ?>) {

      alerta = "";
      if (ender == "") {
        alerta += "Endereço\n";
      }
      if (cgccpf == "") {
        alerta += "CPF/CNPJ\n";
      }
      if (alerta != "") {
        alert("O Contribuinte não possui o CGM atualizado");
        <?php echo "location.href = 'prot1_cadcgm002.php?chavepesquisa=' + z01_numcgm + '&testanome={$func_antes}&valores={$valores}&funcao_js={$func_antes}{$valores}';"; ?>
      } else {
        <?= $func_antes . "(" . $camp . ")"; ?>;
      }
    }
  <?php endif; ?>
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form name="form2" class="container" method="post" action="" >
    <fieldset>
      <legend>Pesquisa CGM</legend>
      <table border="0" class="form-container">
        <tr>
          <td>
            <label for="">
              <?= $Lz01_numcgm; ?>
            </label>
          </td>
          <td>
            <?php db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',4,"","numcgmDigitadoParaPesquisa"); ?>
          </td>
        </tr>
        <?php if ($oGet->tipo == '' || $oGet->tipo == 'fisico') { ?>
        <tr>
          <td>
            <label for="">
              <?=$DBtxt30; ?>:
            </label>
          </td>
          <td>
            <?php

              $GLOBALS['Sz01_cgccpf'] = 'CPF';
              $GLOBALS['Mz01_cgccpf'] = 11;
              db_input('z01_cgccpf',20,1,true,'text',1,"",'cpf');
            ?>
          </td>
        </tr>
        <?php }
           if ($oGet->tipo == '' || $oGet->tipo == 'juridico') { ?>
        <tr>
          <td>
            <label for="">
              <?= $DBtxt31; ?>:
            </label>
          </td>
          <td>
            <?php db_input('z01_cgccpf',20,$Iz01_cgccpf,true,'text',1,"",'cnpj'); ?>
          </td>
        </tr>
        <?php } ?>
        <tr>
          <td>
            <label for="">
               <?php if ($oGet->tipo == 'juridico') {
                 echo 'Razão Social:';
               } else if ($oGet->tipo == 'fisico') {
                 echo 'Nome:';
               } else {
                 echo $Lz01_nome;
               } ?>
            </label>
          </td>
          <td>
            <?php db_input('z01_nome',40,$Iz01_nome,true,'text',4,"",'nomeDigitadoParaPesquisa'); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <table style="margin: 0 auto;">
      <tr>
        <td>
          <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
          <input name="limpar" type="button" id="naoencontrado2" value="Limpar" onClick="js_limpa()">
          <input name="Fechar" type="button" id="limpar" value="Fechar" onClick="parent.func_nome.hide();">
          <?php if (isset($testanome)) { ?>
            <input name="Incluir" type="button" value="Incluir Novo CGM" onClick="location.href = 'prot1_cadcgm001.php?testanome=<?=$func_antes?>&valores=<?=$valores?>&funcao_js=<?=$func_antes.$valores?>'">
            <script>
              var permissao_parcelamento = <?= db_permissaomenu(db_getsession("DB_anousu"), 604, 1305); ?>;
              if (permissao_parcelamento == false) {
                document.form2.Incluir.disabled = true;
              }
            </script>
          <?php } ?>
        </td>
      </tr>
    </table>
  </form>

  <table class="container">
    <tr>
      <td>
        <?php
          if (!isset($pesquisa_chave)) {
        ?>
          <script>
            js_limpa();
            document.form2.nomeDigitadoParaPesquisa.focus();
          </script>
        <?php

            $aWhere = array();

            if (!isset($campos)) {

              $campos  = "cgm.z01_numcgm,";
              $campos .= " z01_nome,trim(z01_cgccpf) as z01_cgccpf,";
              $campos .= " case when length(trim(z01_cgccpf)) = 14 then 'JURIDICA' else 'FÍSICA' end as tipo,";
              $campos .= " trim(z01_ender) as z01_ender,";
              $campos .= " z01_munic, z01_uf,";
              $campos .= " z01_cep,";
              $campos .= " z01_email,";
              $campos .= " z01_login,";
              $campos .= " z01_mae,";
              $campos .= " z01_pai";
            }

            /* Monta joins */
            if (isset($oGet->tipo) && $oGet->tipo == 'fisico') {
              $sJoin = "inner join cgmfisico on (cgm.z01_numcgm = cgmfisico.z04_numcgm)";
            } else if ($oGet->tipo == 'juridico') {
              $sJoin = "inner join cgmjuridico on (cgm.z01_numcgm = cgmfisico.z08_numcgm)";
            } else {
              $sJoin  = "left join cgmfisico on (cgm.z01_numcgm = cgmfisico.z04_numcgm)";
              $sJoin .= "left join cgmjuridico on (cgm.z01_numcgm = cgmfisico.z08_numcgm)";
            }

            $sSql  = "SELECT ";
            $sSql .= $campos;
            $sSql .= " FROM cgm ";
            $sSql .= $sJoin;

            /* Monta array de condicoes */
            if (isset($numcgmDigitadoParaPesquisa) && !empty($numcgmDigitadoParaPesquisa)) {
              $aWhere['z01_numcgm'] = $numcgmDigitadoParaPesquisa;
            }

            if (isset($cpf) && !empty($cpf)) {
              $aWhere['z01_cgccpf'] = "'$cpf'";
            } else if (isset($cnpj) && !empty($cnpj)) {
              $aWhere['z01_cgccpf'] = "'$cnpj'";
            }

            if (isset($nomeDigitadoParaPesquisa) && !empty($nomeDigitadoParaPesquisa)) {
              $aWhere['z01_nome'] = strtoupper($nomeDigitadoParaPesquisa);
            }

            $oCgm = new cl_cgm();
            $sSql = $oCgm->sql_query_cgmtipo($aWhere, $campos, $oGet->tipo);

            db_lovrot($sSql, 14, "()", "", $funcao_js);
          } else {

            if ($pesquisa_chave != null && $pesquisa_chave != "") {

              $oCgm = new cl_cgm();
              $sSql = $oCgm->sql_query_cgmtipo(array("z01_numcgm" => $pesquisa_chave), "z01_nome, z01_mae, z01_pai", $oGet->tipo);

              $result = $oCgm->sql_record($sSql);

              if ($oCgm->numrows != 0) {

                db_fieldsmemory($result, 0);
                echo "<script>".$funcao_js."(false, '$z01_nome', '$z01_mae', '$z01_pai');</script>";

              } else {
                echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
              }

            } else {
              echo "<script>".$funcao_js."(true, null, null, null);</script>";
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
