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
    <form action="" method="POST" name="form1" class="container">
      <?php db_input('rh165_sequencial', 40, 1, true, 'hidden', 3); ?>
      <fieldset style="width: 565px; margin: 0 auto">
        <legend>Manutenção Fórmulas Tipo Assentamento</legend>
        <table class="form-container">
          <tr>
            <td>
              <label><a href="" id="labelTipoAssentamento"><?php echo $Lrh165_tipoasse; ?></a></label>
            </td>
            <td>
              <?php db_input('rh165_tipoasse', 4, 0, true, 'text', $db_opcao, 'lang="h12_codigo"'); ?>
              <?php db_input('h12_descr', 40, 1, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td>
              <label><a href="" id="labelRubrica"><?php echo $Lrh165_rubric?></a></label>
            </td>
            <td>
              <?php db_input('rh165_rubric', 4, 0, true, 'text', $db_opcao, 'lang="rh27_rubric"'); ?>
              <?php db_input('rh27_descr', 40, 1, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td>
              <label><a href="" id="labelFormula"><?php echo $Lrh165_db_formulas?></a></label>
            </td>
            <td>
              <?php db_input('rh165_db_formulas', 4, 0, true, 'text', $db_opcao, 'lang="db148_sequencial"'); ?>
              <?php db_input('db148_nome', 40, 1, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td>
              <label><?php echo $Lrh165_tipolancamento?></label>
            </td>
            <td>
              <?php
                $aOpcoes = array("1" => "Valor", "2" => "Quantidade");
                db_select('rh165_tipolancamento', $aOpcoes, $Irh165_tipolancamento, $db_opcao);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?php echo $Trh165_datainicio; ?>">
              <label id="lbl_rh165_datainicio" for="rh165_datainicio"><?php echo $Lrh165_datainicio; ?></label>
            </td>
            <td>
              <?php 
                db_inputdata('rh165_datainicio',@$rh165_datainicio_dia,@$rh165_datainicio_mes,@$rh165_datainicio_ano,true,'text',$db_opcao,"");
              ?>
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
      <input type="button" value="Novo" onClick="window.location.href = window.location.href;" <?php echo $db_opcao == 1 ? "disabled" : "" ?> />

      <div class="container">
        <?php

          $iInstituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
          $aChavePri    = array("rh165_sequencial" => @$rh165_sequencial);

          $sCampos  = "rh165_sequencial,";
          $sCampos .= "h12_descr as rh165_tipoasse,     ";
          $sCampos .= "rh165_rubric || ' - ' || rh27_descr as rh165_rubric,      ";
          $sCampos .= "db148_nome as rh165_db_formulas, ";
          $sCampos .= "case when rh165_tipolancamento = 1 then 'Valor' else 'Quantidade' end as rh165_tipolancamento,";
          $sCampos .= "rh165_datainicio ";


          $sWhere  = "     rh165_instit = {$iInstituicao}";
          $sWhere .= " and rh165_anousu = ".DBPessoal::getAnoFolha();
          $sWhere .= " and rh165_mesusu = ".DBPessoal::getMesFolha();

          if (isset($rh165_tipoasse) && !empty($rh165_tipoasse)) {
            $sWhere .= " and rh165_tipoasse <> $rh165_tipoasse ";
          } 

          $sSqlTipoasseFinanceiro   = $cltipoassefinanceiro->sql_query(null,$sCampos,null,$sWhere);
          $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
          $cliframe_alterar_excluir->chavepri= $aChavePri;
          $cliframe_alterar_excluir->sql     = $sSqlTipoasseFinanceiro;
          $cliframe_alterar_excluir->campos  = "rh165_sequencial, rh165_tipoasse, rh165_rubric, rh165_db_formulas, rh165_tipolancamento, rh165_datainicio";
          $cliframe_alterar_excluir->legenda = "Assentamentos Cadastrados";
          $cliframe_alterar_excluir->iframe_height = "160";
          $cliframe_alterar_excluir->iframe_width  = "850";
          $cliframe_alterar_excluir->iframe_alterar_excluir(1);
        ?>
      </div>
    </form>

    <script>

      const MENSAGEM = "recursoshumanos.pessoal.db_lancamentotipoassentamento.";

      (function() {

        /**
         * Criação da Ancora para tipo de assentamento.
         * @type  {DBLookUp}
         */
        var oTipoAssentamento = new DBLookUp($('labelTipoAssentamento'), $('rh165_tipoasse'), $('h12_descr'), {
            'sArquivo'             : 'func_tipoasse.php',
            'sObjetoLookUp'        : 'db_iframe_tipoasse',
            'sLabel'               : 'Pesquisar Tipo Assentamento',
            'aParametrosAdicionais': ['lPesquisaNatureza=true']
        });

        oTipoAssentamento.setCamposAdicionais(['h12_natureza']);

        oTipoAssentamento.callBackClick = function() {

          var iNaturezaAssentamento = arguments[2];
          var iTipoAssentamento = arguments[0];
          var sTipoAssentamento = arguments[1];

          if (iNaturezaAssentamento == 3) {
            location.href = 'pes4_configuracaofinanceirarra001.php?rh165_tipoasse='+iTipoAssentamento+'&h12_descr='+sTipoAssentamento;
          }

          $('rh165_tipoasse').value = iTipoAssentamento;
          $('h12_descr').value      = sTipoAssentamento;
          var oObjetoLookUp         = eval(this.oParametros.sObjetoLookUp);
          oObjetoLookUp.hide();
        }

        oTipoAssentamento.callBackChange = function() {
          
          console.log(arguments);

          var iNaturezaAssentamento = arguments[3];
          var sDescricao            = arguments[2];


          if (arguments[1]) {

            sDescricao = arguments[0];
            $('rh165_tipoasse').value = '';
          }

          $('h12_descr').value = sDescricao;

          if (iNaturezaAssentamento == 3) {
            location.href = 'pes4_configuracaofinanceirarra001.php?rh165_tipoasse=' + $('rh165_tipoasse').value + '&h12_descr='+sDescricao;
          }

        }


        /**
         * Criação da Ancora para a rubrica.
         * @type  {DBLookUp}
         */
        var oRubrica = new DBLookUp($('labelRubrica'), $('rh165_rubric'), $('rh27_descr'), {
            'sArquivo'     : 'func_rhrubricas.php',
            'sObjetoLookUp': 'db_iframe_rhrubricas',
            'sLabel'       : 'Pesquisar Rubricas'
        });

        /**
         * Criação da Ancora para a formula.
         * @type  {DBLookUp}
         */
        var oRubrica = new DBLookUp($('labelFormula'), $('rh165_db_formulas'), $('db148_nome'), {
            'sArquivo'     : 'func_db_formulas.php',
            'sObjetoLookUp': 'db_iframe_formulas',
            'sLabel'       : 'Pesquisar Formulas'
        });
      })();

      /**
       * Efetua a validação dos campos obrigatórios.
       *
       * @return  {boolean}
       */
      function validaCampos() {

        if ($F(rh165_tipoasse) == "") {

          alert(_M(MENSAGEM+"campo_assentamento_obrigadorio"));
          return false;
        } 

        if ($F(rh165_rubric) == "") {
          
          alert(_M(MENSAGEM+"campo_rubrica_obrigadorio"));
          return false;
        } 

        if ($F(rh165_db_formulas) == "") {
          
          alert(_M(MENSAGEM+"campo_formula_obrigadorio"));
          return false;
        } 

        return true;
      }
    </script>

    <?php
      db_menu();
    ?>
  </body>
</html>