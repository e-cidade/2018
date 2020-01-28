<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
$oDaoRhpreponto = new cl_rhpreponto();
$oDaoRhpreponto->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("nomeinst");
$oRotulo->label("rh142_sequencial");
$oRotulo->label("rh01_numcgm");
$oRotulo->label("z01_nome");
$oRotulo->label("rh01_regist");
$oRotulo->label("r14_quant");
$oRotulo->label("r14_valor");
$db_opcao = 1;
?>
<style>
  #registrosLotebody{
    table-layout: auto !important;
  }

  #registrosLotebody .nome-lote{
    background-color: #F2F2F2;
  }

  #registrosLotebody .nome-lote > td{ 
    text-align: left; 
    padding-left: 5px;
  }

  #gridRegistros table > tbody > tr > td:nth-child(7), #gridRegistros table > tbody > tr > td:nth-child(8){
    display: none !important;
  }
</style>

<div class="container">
  <form name="form1" method="post" action="">

    <fieldset>
      <legend>Rubrica </legend>
      <table>
        <tr>
          <td>
            <a id="procurarRubrica"><?php echo $Srh149_rubric; ?>:</a>
          </td>
          <td>
            <?php db_input('rh27_rubric', 20, $Irh149_rubric, true, 'text', $db_opcao, "" , "", "", "margin-left:20px;"); ?>
          </td>
          <td>
            <input type="text" id="rh27_descr" name="rh27_descr" />
          </td>
          <td>
            <label class="bold" for="descricaorubrica" id="lbl_descricaorubrica">Ano/Mês</label>
          </td>
          <td>
            <input type="hidden" id="rh27_presta" name="rh27_presta" />
            <input type="text" id="rh27_limdat" name="rh27_limdat" class="field-size2 readOnly" readonly="" size="7" maxlength="7" />
            <input type="hidden" id="rh27_valorlimite" class="field-size2 readonly" readonly="" size="8" maxlength="8" />
            <input type="hidden" id="rh27_quantidadelimite" class="field-size2 readonly" readonly="" size="8" maxlength="8" />
            <input type="hidden" id="rh27_tipobloqueio" class="field-size2 readonly" readonly="" size="8" maxlength="8" />
          </td>
        </tr>
      </table>
      <div id="notificacao" style="display:none;">
        <div colspan='5' class="caixaalta" style="display:block; text-align: left; background-color: #fcf8e3; border: 1px solid #fcc888; padding: 5px; width: calc(100% - 10px); "></div>
      </div>
    </fieldset>

    <fieldset>
      <legend>Servidor </legend>
      <table>
        <tr  title="<?php echo $Trh01_regist; ?>">
          <td nowrap title="<?php echo $Trh01_regist; ?>" >
            <a id="procurarMatricula"><?php echo $Srh01_regist; ?>:</a>
          </td>
          <td>
            <?php db_input('rh01_regist', 20, $Irh01_regist, true, 'text', 1); ?>
          </td>
          <td colspan="2">
            <?php db_input('z01_nome', 20, $Iz01_nome, true, 'text', 5, 'class="field-size9 readOnly"'); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label class="bold" for="" id="lbl_lotacao">Lotação:</label>
          </td>
          <td>
            <?php db_input('rh02_lota',   8, $Iz01_nome, true, 'text',5, 'class="field-size2 readOnly"','codLotacao'); ?>
          </td>
          <td colspan="2">
            <?php db_input('descrLotacao',8, $Iz01_nome, true, 'text',5, 'class="field-size8 readOnly"'); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label class="bold" for="" id="lbl_iQuantidade">Quantidade:</label>
          </td>
          <td>
            <?php $GLOBALS['iQuantidade'] = 0; ?>
            <?php db_input('r14_quant', 8, 4, true, 'text',1, 'class="field-size2"', 'iQuantidade' ); ?>
          </td>
          <td colspan="2">
            <label class="bold" for="" id="lbl_nValor">Valor:</label>
            <?php $GLOBALS['nValor'] = 0; ?>
            <?php db_input('r14_valor', 8, 4, true, 'text',1, 'class="field-size2"', 'nValor'); ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <input type="button" id="incluirRegistro" value="Incluir" /> 
    <input type="button" id="LimparDados"     value="Limpar" /> 

    <fieldset>
      <legend>Registros do Ponto no Lote </legend>
      <div width='800px' id="gridRegistros"></div>
    </fieldset>
    <center><input type="button" id="alterarRegistro" value="Alterar" /> </center>
  </form>
</div>
