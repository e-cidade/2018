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
$oPost      = db_utils::postMemory($_POST);
$claguabase = new cl_aguabase;
$lErro      = false;
$aAnos      = array(
  db_getsession('DB_anousu') => db_getsession('DB_anousu')
);

if (!empty($oPost->calcular)) {
  try {

    db_inicio_transacao();

    if (empty($oPost->x01_matric)) {

      $claguabase->erro_msg    = 'Matricula não informada.';
      $claguabase->erro_status = '0';

      throw new Exception("Matrícula não informada.");
    }

    if ($oPost->parc_fim < $oPost->parc_ini) {
      throw new Exception("Parcela Final não pode ser menor que a Parcela Inicial.");
    }

    for ($iMes = $oPost->parc_ini; $iMes <= $oPost->parc_fim; $iMes++) {

      $sSql = $claguabase->sql_query_calculo_taxa_parcial($oPost->anousu, $iMes, $oPost->x01_matric, 1, true, true);
      $rsCalculo = $claguabase->sql_record($sSql);

      if (!$rsCalculo) {
        throw new DBException($claguabase->erro_msg);
      }

      if (pg_numrows($rsCalculo) > 0) {
        $sRetornoCalculo = pg_result($rsCalculo, 0, 0);

        if (substr($sRetornoCalculo, 0, 1) != 1) {

          $claguabase->erro_msg    = "Erro: {$sRetornoCalculo}";
          $claguabase->erro_status = '0';
          throw new DBException($sRetornoCalculo);
        }
      }
    }

    db_fim_transacao();

  } catch (Exception $exception) {

    $lErro = true;
    $claguabase->erro_msg = $exception->getMessage();
    db_fim_transacao($lErro);
  }
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
  <form name="form1" action="" method="post" onSubmit="return js_verificacalculo();">
    <fieldset>
      <legend>Cálculo Parcial</legend>
      <table>
        <tr>
          <td><?php db_ancora('Matrícula:', 'js_mostranomes(true);', 4, 'autofocus'); ?></td>
          <td><?php db_input("x01_matric", 8, 0, true, 'text', 4, " onchange='js_mostranomes(false);' "); ?></td>
        </tr>

        <tr>
          <td><?php db_ancora('Nome:', 'js_mostranomes(true);', 4); ?></td>
          <td><?php db_input("z01_nome", 40, 3, true, 'text', 3); ?></td>
        </tr>

        <tr>
          <td><label class="bold" for="anousu">Ano:</label></td>
          <td><?php db_select('anousu', $aAnos, true, 1); ?></td>
        </tr>

        <tr>
          <td><label for="parc_ini" class="bold">Parcela Inicial:</label></td>
          <td><?php db_select("parc_ini", DBDate::getMesesExtenso(), true, 1); ?></td>
        </tr>

        <tr>
          <td><label for="parc_fim" class="bold">Parcela Final:</label></td>
          <td><?php db_select("parc_fim", DBDate::getMesesExtenso(), true, 1); ?></td>
        </tr>
      </table>
    </fieldset>

    <input name="calcular"  type="submit" id="calcular" value="Calcular">

    <?php if (!empty($oPost->calcular) && !$lErro) { ?>
      <input name="Limpar"  type="button" id="limpr" value="Limpar" onClick="document.form1.x01_matric.value='';document.form1.z01_nome.value=''">
      <input name="ultimo"  type="button" id="ultimo" value="Último Calculo" onClick="func_nome.show();  func_nome.focus();">
    <?php } ?>
  </form>
</div>

<?php db_menu(); ?>

<script type="text/javascript">

  /**
   * Verifica Campos Para Calculo
   * @returns {boolean}
   */
  function js_verificacalculo() {

    if (document.form1.x01_matric.value == "") {

      alert('Informe uma Matrícula.');
      return false;
    }

    var oImplantacaoTarifa = new Date(2017, 6);
    var iMesInicial, iMesFinal, iAno;

    iMesInicial = $F('parc_ini');
    iMesFinal   = $F('parc_fim');
    iAno        = $F('anousu');

    if (Number(iMesFinal) < Number(iMesInicial)) {
      alert('Parcela Final não pode ser menor que a Parcela Inicial.');
      return false;
    }

    var oPeriodoInicial = new Date(iAno, iMesInicial - 1);
    var oPeriodoFinal = new Date(iAno, iMesFinal - 1);
    if (oPeriodoInicial >= oImplantacaoTarifa || oPeriodoFinal >= oImplantacaoTarifa) {

      var mensagemAviso = "Não é possível executar o cálculo de taxas nesta rotina a partir do período de Julho/2017. \n\n";
      mensagemAviso    += "Para executar o cálculo de tarifas utilize a rotina:\nProcedimentos > Cálculo de Tarifas > Cálculo Parcial";
      alert(mensagemAviso);
      return false;
    }
    return true;
  }

  /**
   * Monstra Nomes
   * @param mostra
   */
  function js_mostranomes(mostra) {

    if (mostra == true) {

      func_nome.jan.location.href = 'func_aguabase.php?funcao_js=parent.js_preenche|0|1';
      func_nome.mostraMsg();
      func_nome.show();
      func_nome.focus();
    } else {
      func_nome.jan.location.href = 'func_aguabase.php?pesquisa_chave='+document.form1.x01_matric.value+'&funcao_js=parent.js_preenche1';
    }
  }

  /**
   * Preenche Campos
   * @param chave
   * @param chave1
   */
  function js_preenche(chave, chave1) {

    document.form1.x01_matric.value = chave;
    document.form1.z01_nome.value = chave1;
    func_nome.hide();
  }

  /**
   * Preenche Campos
   * @param chave
   * @param chave1
   */
  function js_preenche1(chave, chave1) {

    document.form1.z01_nome.value = chave;
    if(chave1 == false) {

      document.form1.x01_matric.select();
      document.form1.x01_matric.focus();
    }

    func_nome.hide();
  }

</script>
<?php

if (!empty($oPost->calcular)) {
  if ($lErro == false) {
    db_msgbox("Calculo efetuado com sucesso");
  }else{
    db_msgbox("{$claguabase->erro_msg}");
  }
}

$func_nome = new janela('func_nome', '');
$func_nome->posX =1;
$func_nome->posY = 20;
$func_nome->largura = 700;
$func_nome->altura = 430;
$func_nome->titulo = "Pesquisa";
$func_nome->iniciarVisivel = false;
$func_nome->mostrar();

if (!empty($oPost->calcular) && $lErro == false) { ?>

  <script type="text/javascript">
    func_nome.jan.location.href = "agu3_conscadastro_002_detalhes.php?solicitacao=Calculo&parametro=<?= $oPost->x01_matric ?>";
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  </script>

<?php } ?>
</body>
</html>

