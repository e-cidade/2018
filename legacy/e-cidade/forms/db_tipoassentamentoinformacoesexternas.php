<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, prototype.js, estilos.css, widgets/DBLookUp.widget.js");
    ?>
  </head>
  <body>
    <form action="" method="" name="form1" class="container">
      <?php db_input('rh167_sequencial', 20, 1, true, 'hidden', 3); ?>
      <?php db_input('rh167_instit', 4, 1, true, 'hidden', 3); ?>
      <?php db_input('rh167_anousu', 4, 1, true, 'hidden', 3); ?>
      <?php db_input('rh167_mesusu', 4, 1, true, 'hidden', 3); ?>
      <fieldset style="width: 590px; margin: 5px auto">
        <legend>Manutenção de Vinculações do Tipo de Assentamento</legend>
        <table class="form-container">
          <tr>
            <td width="155">
              <label><a href="" id="labelTipoAssentamento"><?php echo $Lrh167_tipoasse ?></a></label>
            </td>
            <td>
              <?php db_input('rh167_tipoasse', 10, 0, true, 'text', $db_opcao, 'lang="h12_assent"'); ?>
              <?php db_input('h12_descr', 40, 1, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td width="155">
              <label><a href="" id="labelSituacaoAfastamento"><?php echo $Lrh167_situacaoafastamento ?></a></label>
            </td>
            <td>
              <?php db_input('rh167_situacaoafastamento', 4, 1, true, 'text', $db_opcao, 'lang="rh166_sequencial"'); ?>
              <?php db_input('rh166_descricao', 50, 1, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td width="155">
              <label><a href="" id="labelCodigoSefip"><?php echo $Lrh167_codmovsefip ?></a></label>
            </td>
            <td>
              <?php db_input('rh167_codmovsefip', 4, 0, true, 'text', $db_opcao, 'lang="r66_codigo"'); ?>
              <?php db_input('r66_descr', 50, 1, true, 'text', 3); ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <?php

        $sLabel = "Incluir";

        if ($db_opcao == 2) {
          $sLabel = "Alterar";
        }

        if ($db_opcao == 3) {
          $sLabel = "Excluir";  
        }
      ?>

      <input type="submit" value="<?php echo $sLabel ?>" name="<?php echo strtolower($sLabel) ?>" onClick="return validaCampos()" />
      <input type="button" id="novo" value="Novo" onClick="window.location.href = window.location.protocol +'//'+ window.location.host + window.location.pathname;" />

      <div class="container">
        <?php
          $iAnoCompetencia = DBPessoal::getAnoFolha();
          $iMesCompetencia = DBPessoal::getMesFolha();
          $iInstituicao    = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
          $aChavePri       = array("rh167_sequencial" => @$rh167_sequencial);

          $sCampos  = " rh167_sequencial,";
          $sCampos .= " rh167_anousu,";
          $sCampos .= " rh167_mesusu,";
          $sCampos .= " rh167_codmovsefip,";
          $sCampos .= " h12_descr as rh167_tipoasse,";
          $sCampos .= " rh166_descricao as rh167_situacaoafastamento,";
          $sCampos .= " rh167_instit";

          $sWhere   = "     rh167_anousu = {$iAnoCompetencia}";
          $sWhere  .= " and rh167_mesusu = {$iMesCompetencia}";
          $sWhere  .= " and rh167_instit = {$iInstituicao}";

          $sSqlTipoasseexterno   = $cltipoasseexterno->sql_query(null, $sCampos, null, $sWhere);
          $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
          $cliframe_alterar_excluir->chavepri= $aChavePri;
          $cliframe_alterar_excluir->sql     = $sSqlTipoasseexterno;
          $cliframe_alterar_excluir->campos  = $sCampos;
          $cliframe_alterar_excluir->campos  = "rh167_sequencial, rh167_anousu, rh167_mesusu, rh167_codmovsefip, rh167_tipoasse, rh167_situacaoafastamento, rh167_instit";
          $cliframe_alterar_excluir->legenda = "Tipos de Assentamentos Vinculados";
          $cliframe_alterar_excluir->iframe_height = "160";
          $cliframe_alterar_excluir->iframe_width  = "850";
          $cliframe_alterar_excluir->iframe_alterar_excluir(1);
        ?>
      </div>
    </form>

    <script type="text/javascript">

      (function() {

        /**
         * Criação da Ancora para tipo de assentamento.
         * @type  {DBLookUp}
         */
        var oTipoAssentamento = new DBLookUp($('labelTipoAssentamento'), $('rh167_tipoasse'), $('h12_descr'), {
          'sArquivo'              : 'func_tipoasse.php',
          'sObjetoLookUp'         : 'db_iframe_tipoasse',
          'sLabel'                : 'Pesquisar Tipo Assentamento',
          'aParametrosAdicionais' : ['sAcao=vincularTipoAssentamentoDadosExternos']
        });

        /**
         * Sobrescrevendo método para pegar retornos da pesquisa
         */
        oTipoAssentamento.callBackChange = function(){

          $('rh167_tipoasse').value = arguments[0];
          $('h12_descr').value      = arguments[1];

        };

        var oSituacaoAfastamento = new DBLookUp( $('labelSituacaoAfastamento'), $('rh167_situacaoafastamento'), $('rh166_descricao'), {
          'sArquivo'      : 'func_situacaoafastamento.php',
          'sObjetoLookUp' : 'db_iframe_situacaoafastamento',
          'sLabel'        : 'Pesquisar Situação do Afastamento'
        });

        var oCodigoSefip = new DBLookUp( $('labelCodigoSefip'), $('rh167_codmovsefip'), $('r66_descr'), {
          'sArquivo'      : 'func_codmovsefip.php',
          'sObjetoLookUp' : 'db_iframe_codmovsefip',
          'sQueryString'  : '&lAtivos=true',
          'sLabel'        : 'Pesquisar Código da Sefip',
          'sLabel'        : 'Pesquisar Código da Sefip'
        });

        MENSAGEM = "recursoshumanos.pessoal.db_tipoassentamentoinformacoesexternas.";

      })();

      function validaCampos() {

        if ($F('rh167_tipoasse') == '') {
          alert(_M(MENSAGEM +"vazio_campo_obrigatorio_tipoasse"));
          return false;
        }

        if ($F('rh167_situacaoafastamento') == '') {
          alert(_M(MENSAGEM +"vazio_campo_obrigatorio_situacaoafastamento"));
          return false;
        }

        if ($F('rh167_codmovsefip') == '') {
          alert(_M(MENSAGEM +"vazio_campo_obrigatorio_codmovsefip"));
          return false;
        }

        return true;
      }

    </script>

    <?php
      if(isset($sMsgSucesso)) {
        db_msgbox($sMsgSucesso);

        echo '<script type="text/javascript">(function() {form1.novo.click();})(); </script>';
      }
    ?>

    <?php
      db_menu();
    ?>

  </body>
</html>