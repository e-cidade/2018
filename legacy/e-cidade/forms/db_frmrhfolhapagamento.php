<?php

$oDaoRhfolhapagamento = new cl_rhfolhapagamento;
$oDaoRhfolhapagamento->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("rh141_codigo");
?>
<table class="form-container">
  <?php  
  if ( $db_opcao <> 1 && !isset($lManutenaoSalario) ) {
  ?>
  <tr>
    <td width="165" title="<?= $Trh141_codigo ?>" >
      <label for="rh141_codigo">
        <?= $Lrh141_codigo ?>
      </label>
    </td>
    <td>
      <?php 
      db_input('rh141_codigo', 4, $Irh141_codigo, true, 'text', 3); 
      ?>
    </td>
  </tr>
  <?php } ?>
  <tr>
    <td title="<?= $Trh141_anoref ?>" >
      <label class="bold" for="rh141_anoref" id="lbl_rh141_anoref">
        Competência de Referência:
      </label>
      <?php
      db_input('rh141_anoref', 4, $Irh141_anoref, true, 'hidden', 3);
      db_input('rh141_mesref', 4, $Irh141_mesref, true, 'hidden', 3);
      ?>
    </td>
    <td id="containeirCompetencia"></td>
  </tr>
  <tr>
    <td colspan="2">
      <fieldset>
        <legend><?php echo $Srh141_descricao; ?></legend>
        <?php db_textarea('rh141_descricao', 0, 0, $Irh141_descricao, true, 'text',$db_opcao); ?>
      </fieldset>
    </td>
  </tr>
</table>
<script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>

<script>
    var oCompetencia = new DBViewFormularioFolha.CompetenciaFolha(false);
    oCompetencia.renderizaFormulario($("containeirCompetencia"));
    
    oCompetencia.oAno.setValue($F('rh141_anoref'));
    oCompetencia.oMes.setValue($F('rh141_mesref'));

    oCompetencia.oAno.getElement().addEventListener("change", function() {
      $('rh141_anoref').setValue(oCompetencia.oAno.getValue());
    });
    oCompetencia.oMes.getElement().addEventListener("change", function() {
      $('rh141_mesref').setValue(oCompetencia.oMes.getValue());
    });

    if ( $('rh141_descricao').readOnly ) {

      oCompetencia.oAno.setReadOnly(true);
      oCompetencia.oMes.setReadOnly(true);
    }
</script>
</html>
