<?php
/**
 * MODULO: recursoshumanos
 */

$oDaoAgendaassentamento->rotulo->label();
$oRotulo = new rotulocampo;

if ($db_opcao == 1) {
  $sNameBotaoProcessar = "incluir";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $sNameBotaoProcessar = "alterar";
} else {
  $sNameBotaoProcessar = "excluir";
}

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css, widgets/DBLookUp.widget.js");
    ?>
  </head>

  <body style="background-color: #ccc; margin-top: 30px">

    <form name="form1" id="form1" class="container">
      <fieldset style="width: 565px; margin: 5px auto">
        <legend>Configuração de Dados da Agenda</legend>
        <table class="form-container">
          <tr>
            <td><a href="" id="labelTipoAssentamento"><?php echo $Lh82_tipoassentamento ?></a></td>
            <td>
              <?php db_input('h82_sequencial', 4, 0, true, 'hidden', $db_opcao); ?>
              <?php db_input('h82_tipoassentamento', 4, 0, true, 'text', $db_opcao, 'lang="h12_assent"'); ?>
              <?php db_input('h12_descr', 40, 1, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td><a href="" id="labelSelecao"><?php echo $Lh82_selecao ?></a></td>
            <td>
              <?php db_input('h82_selecao', 4, 0, true, 'text', $db_opcao, 'lang="r44_selec"'); ?>
              <?php db_input('r44_descr', 40, 1, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td><a href="" id="labelFormulaCondicao"><?php echo $Lh82_formulacondicao ?></a></td>
            <td>
              <?php db_input('h82_formulacondicao', 4, 0, true, 'text', $db_opcao, 'lang="db148_sequencial"'); ?>
              <?php db_input('db148_nome', 40, 1, true, 'text', 3); ?>
            </td>
          </tr>
          <tr>
            <td><a href="" id="labelFormulaInicio"><?php echo $Lh82_formulainicio ?></a></td>
            <td>
              <?php db_input('h82_formulainicio', 4, 0, true, 'text', $db_opcao, 'lang="db148_sequencial"'); ?>
              <?php db_input('db148_nome_inicio', 40, 1, true, 'text', 3, 'lang="db148_nome"'); ?>
            </td>
          </tr>

          <tr>
            <td><a href="" id="labelFormulaFim"><?php echo $Lh82_formulafim ?></a></td>
            <td>
              <?php db_input('h82_formulafim', 4, 0, true, 'text', $db_opcao, 'lang="db148_sequencial"'); ?>
              <?php db_input('db148_nome_fim', 40, 1, true, 'text', 3, 'lang="db148_nome"'); ?>
            </td>
          </tr>

          <tr>
            <td><a href="" id="labelFormulaFaltasPeriodo"><?php echo $Lh82_formulafaltasperiodo ?></a></td>
            <td>
              <?php db_input('h82_formulafaltasperiodo', 4, 0, true, 'text', $db_opcao, 'lang="db148_sequencial"'); ?>
              <?php db_input('db148_nome_faltasperiodo', 40, 1, true, 'text', 3, 'lang="db148_nome"'); ?>
            </td>
          </tr>

          <tr>
            <td><a href="" id="labelFormulaProrrogaFim"><?php echo $Lh82_formulaprorrogafim ?></a></td>
            <td>
              <?php db_input('h82_formulaprorrogafim', 4, 0, true, 'text', $db_opcao, 'lang="db148_sequencial"'); ?>
              <?php db_input('db148_nome_prorrogafim', 40, 1, true, 'text', 3, 'lang="db148_nome"'); ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <input type="submit" value="<?=strtoupper(substr($sNameBotaoProcessar, 0, 1)).substr($sNameBotaoProcessar, 1)?>" name="<?=$sNameBotaoProcessar?>" onClick="return validaCampos()" />
      <input type="button" value="Novo" name="novo" id="novo" onclick="location.href='rec1_agendaassentamento001.php'" />

      <div class="container">

        <?php

          $iInstituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
          $aChavePri    = array("chavepesquisa" => @$chavepesquisa);

          $sCampos  = "h82_sequencial as chavepesquisa,                  ";
          $sCampos .= "h12_descr as h82_tipoassentamento,                 ";
          $sCampos .= "formulacondicao.db148_nome as h82_formulacondicao, ";
          $sCampos .= "formulainicio.db148_nome as h82_formulainicio,     ";
          $sCampos .= "formulafim.db148_nome as h82_formulafim,     ";
          $sCampos .= "formulafaltasperiodo.db148_nome as h82_formulafaltasperiodo,";
          $sCampos .= "r44_descr  as h82_selecao                          ";
          
          $sWhere  = "     h82_instit = {$iInstituicao}";

          if (isset($chavepesquisa) && !empty($chavepesquisa)) {
            $sWhere .= " and h82_sequencial <> $chavepesquisa ";
          } 

          $sSqlAgendaAssentamento   = $oDaoAgendaassentamento->sql_query(null,$sCampos,null,$sWhere);

          $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
          $cliframe_alterar_excluir->chavepri= $aChavePri;
          $cliframe_alterar_excluir->sql     = $sSqlAgendaAssentamento;
          $cliframe_alterar_excluir->campos  = "h82_tipoassentamento, h82_formulainicio, h82_formulafim, h82_formulafaltasperiodo, h82_formulacondicao, h82_selecao";
          $cliframe_alterar_excluir->legenda = "Assentamentos Configurados";
          $cliframe_alterar_excluir->iframe_height = "160";
          $cliframe_alterar_excluir->iframe_width  = "850";
          $cliframe_alterar_excluir->iframe_alterar_excluir(1);
        ?>

      </div>

    </form>

    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>

    <script type="text/javascript">
      <?php
        if(isset($sPosScripts)) {
          echo $sPosScripts;
        }
      ?>
    </script>

    <script>
    
    const MENSAGEM = "recursoshumanos.rh.rec1_agendaassentamento.";

    (function(){
      
      /**
       * Criação da Ancora para tipo de assentamento.
       * @type  {DBLookUp}
       */
      var oTipoAssentamento = new DBLookUp($('labelTipoAssentamento'), $('h82_tipoassentamento'), $('h12_descr'), {
          'sArquivo'              : 'func_tipoasse.php',
          'sObjetoLookUp'         : 'db_iframe_tipoasse',
          'sLabel'                : 'Pesquisar Tipo Assentamento',
          'aParametrosAdicionais' : ['sAcao=vincularTipoAssentamentoDadosExternos']
      });

      /**
       * Criação da Ancora para a seleção.
       * @type  {DBLookUp}
       */
      var oSelecao = new DBLookUp($('labelSelecao'), $('h82_selecao'), $('r44_descr'), {
          'sArquivo'     : 'func_selecao.php',
          'sObjetoLookUp': 'db_iframe_selecao',
          'sLabel'       : 'Pesquisar Seleção'
      });

      /**
       * Criação da Ancora para a formula de condição
       * @type  {DBLookUp}
       */
      var oFormulaCondicao = new DBLookUp($('labelFormulaCondicao'), $('h82_formulacondicao'), $('db148_nome'), {
          'sArquivo'     : 'func_db_formulas.php',
          'sObjetoLookUp': 'db_iframe_db_formulas',
          'sLabel'       : 'Pesquisar Fórmula'
      });

      /**
       * Criação da Ancora para a formula de inicio
       * @type  {DBLookUp}
       */
      var oFormulaInicio = new DBLookUp($('labelFormulaInicio'), $('h82_formulainicio'), $('db148_nome_inicio'), {
          'sArquivo'     : 'func_db_formulas.php',
          'sObjetoLookUp': 'db_iframe_db_formulas', //@todo verificar conflito
          'sLabel'       : 'Pesquisar Fórmula'
      });

      /**
       * Criação da Ancora para a formula de fim
       * @type  {DBLookUp}
       */
      var oFormulaFim    = new DBLookUp($('labelFormulaFim'), $('h82_formulafim'), $('db148_nome_fim'), {
          'sArquivo'     : 'func_db_formulas.php',
          'sObjetoLookUp': 'db_iframe_db_formulas',
          'sLabel'       : 'Pesquisar Fórmula'
      });

      /**
       * Criação da Ancora para a formula de faltas periodo
       * @type  {DBLookUp}
       */
      var oFormulaFaltasPeriodo = new DBLookUp($('labelFormulaFaltasPeriodo'), $('h82_formulafaltasperiodo'), $('db148_nome_faltasperiodo'), {
          'sArquivo'            : 'func_db_formulas.php',
          'sObjetoLookUp'       : 'db_iframe_db_formulas',
          'sLabel'              : 'Pesquisar Fórmula'
      });

      /**
       * Criação da Ancora para a formula de faltas periodo
       * @type  {DBLookUp}
       */
      var oFormulaProrrogaFim = new DBLookUp($('labelFormulaProrrogaFim'), $('h82_formulaprorrogafim'), $('db148_nome_prorrogafim'), {
        'sArquivo'            : 'func_db_formulas.php',
        'sObjetoLookUp'       : 'db_iframe_db_formulas',
        'sLabel'              : 'Pesquisar Fórmula'
      });
      /**
       * Sobrescrevendo método para pegar retornos da pesquisa
       */
      oTipoAssentamento.callBackChange = function(){

        // $('rh167_tipoasse').value = arguments[0];
        $('h12_descr').value      = arguments[1];

      };

      /**
       * Sobrescrevendo método para pegar retornos da pesquisa
       */
      oFormulaCondicao.callBackChange = function(){
        $('db148_nome').value         = arguments[2];
      };

      oFormulaInicio.callBackChange = function(){
        $('db148_nome_inicio').value  = arguments[2];
      };

      oFormulaFim.callBackChange = function(){
        $('db148_nome_fim').value  = arguments[2];
      };

      oFormulaFaltasPeriodo.callBackChange = function(){
        $('db148_nome_faltasperiodo').value  = arguments[2];
      };

      oFormulaProrrogaFim.callBackChange = function(){
        $('db148_nome_prorrogafim').value  = arguments[2];
      };

    })()

    function validaCampos(){

      if ($F("h82_tipoassentamento") == "") {

        alert(_M(MENSAGEM+"campo_assentamento_obrigadorio"));
        return false;
      }

      if ($F("h82_selecao") == "") {
        
        alert(_M(MENSAGEM+"campo_selecao_obrigadorio"));
        return false;
      }

      if ($F("h82_formulacondicao") == "") {
        
        alert(_M(MENSAGEM+"campo_formulacondicao_obrigadorio"));
        return false;
      }

      if ($F("h82_formulainicio") == "") {
        
        alert(_M(MENSAGEM+"campo_formulainicio_obrigadorio"));
        return false;
      }

      return true;
    }

    </script>

  </body>
</html>
