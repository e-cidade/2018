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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$claguabase = new cl_aguabase;
$claguabase->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');

$aAnos = array(
  db_getsession('DB_anousu') => db_getsession('DB_anousu')
);

if (!isset($mesini)) {
  $mesini = db_subdata(db_getsession("DB_datausu"), "m", "t");
}

if (!isset($mesfim)) {
  $mesfim = db_subdata(db_getsession("DB_datausu"), "m", "t");
}

$aTiposEmissao = array(
  "pdf" => 'PDF',
  "txt" => "TXT"
);

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" onLoad="document.form1.x01_matric.focus();" >
  <div class="container">
    <form name="form1" action="" method="post" onSubmit="return js_verificaMatricula();">
        <fieldset>
          <legend>Emissão Parcial de Carnês</legend>
          <table>
            <tr>
              <td title="<?php echo $Tz01_numcgm?>">
                <?php db_ancora('<strong>Matricula:</strong>', 'js_mostranomes(true);', 4); ?>
              </td>
              <td>
                <?php db_input("x01_matric", 10, $Ix01_matric, true, 'text', 4, " onchange='js_mostranomes(false);' "); ?>
              </td>
            </tr>

            <tr>
              <td><?php db_ancora('<strong>Nome:</strong>','js_mostranomes(true);', 4); ?></td>
              <td><?php db_input("z01_nome", 40, $Iz01_nome, true, 'text', 3); ?></td>
            </tr>

            <tr>
              <td><label for="anousu" class="bold">Ano:</label></td>
              <td><?php db_select("anousu", $aAnos, true, 1); ?></td>
            </tr>

            <tr>
              <td><label for="mesini" class="bold">Mês Inicial:</label></td>
              <td><?php db_select("mesini", DBDate::getMesesExtenso(), true, 1); ?></td>
            </tr>

            <tr>
              <td><label for="mesfim" class="bold">Mês Final:</label></td>
                <td><?php db_select("mesfim", DBDate::getMesesExtenso(), true, 1); ?></td>
            </tr>

            <tr>
              <td><label for="matriculas_sem_contrato" class="bold">Somente parcelamentos:</label></td>
              <?php
              $aOpcoesFiltro = array(
                '0' => 'Não',
                '1' => 'Sim',
              );
              ?>
              <td><?php db_select('matriculas_sem_contrato', $aOpcoesFiltro, true, 1); ?></td>
            </tr>
          </table>
        </fieldset>

        <input name="processar" type="submit" id="processar" value="Processar">

    </form>
  </div>

  <?php db_menu(); ?>

  <script type="text/javascript">
    function js_mostranomes(mostra) {

      if (mostra == true) {

       js_OpenJanelaIframe(
         'CurrentWindow.corpo','db_iframe_aguabase',
         'func_aguabase.php?funcao_js=parent.js_mostra1|x01_matric|z01_nome',
         'Pesquisa', true, 20
       );
      } else {

        if (document.form1.x01_matric.value != '') {

          js_OpenJanelaIframe(
            'CurrentWindow.corpo','db_iframe_aguabase',
            'func_aguabase.php?pesquisa_chave=' + document.form1.x01_matric.value +
            '&funcao_js=parent.js_mostra', 'Pesquisa', false, 0
          );
        } else {
          document.form1.z01_nome.value = '';
        }
      }
    }

    function js_mostra(chave, erro) {

      document.form1.z01_nome.value = chave;
      if (erro == true) {

        document.form1.x01_matric.focus();
        document.form1.x01_matric.value = '';
      }
    }

    function js_mostra1(chave1, chave2) {

      document.form1.x01_matric.value = chave1;
      document.form1.z01_nome.value   = chave2;
      db_iframe_aguabase.hide();
    }
  </script>

  <?php if (isset($processar)) { ?>
    <script type="text/javascript">
      js_OpenJanelaIframe(
        'CurrentWindow.corpo','db_iframe',
        'agu4_emissaoparcial002.php?' +
        'matricula=<?php echo $x01_matric;?>' +
        '&exercicio=<?php echo $anousu;?>&' +
        'parcela_ini=<?php echo $mesini?>&' +
        'parcela_fim=<?php echo $mesfim?>&' +
        'tipo_emissao=txt&' +
        'matriculas_sem_contrato=<?php echo $matriculas_sem_contrato ?>',
        'Emissao de Carnes', true, 20);
    </script>
  <?php } ?>

  <script type="text/javascript">
    function js_verificaMatricula() {

      if (document.form1.x01_matric.value == "") {

        alert('Informe uma Matrícula.');
        return false;
      }
    }
  </script>
</body>
</html>
