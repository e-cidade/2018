<?php
$sSqlContrato = "
  select
    aguacontrato.*,
    cgm.z01_nome,
    cgm.z01_numcgm,
    aguacategoriaconsumo.*
  from
    aguacontrato
  inner join aguacalc             on x22_aguacontrato = x54_sequencial
  inner join arrecad              on x22_numpre = k00_numpre
  inner join cgm                  on z01_numcgm = x54_cgm
  left  join aguacategoriaconsumo on x54_aguacategoriaconsumo = x13_sequencial and (x54_condominio is false or x54_condominio is null)
  where
    x22_numpre = {$numpre}
  limit 1
";

$oContrato = null;
$rsContrato = db_query($sSqlContrato);
if ($rsContrato && pg_num_rows($rsContrato) > 0) {
  $oContrato = pg_fetch_object($rsContrato, 0);
}
?>

<?php if ($oContrato) : ?>
  <fieldset style="width: 98%;">

    <legend>Dados do Contrato</legend>

    <table style="width: 35%; float: left;" class="linhaZebrada">

      <tr>
        <td class="bold">Contrato:</td>
        <td><?php echo $oContrato->x54_sequencial ?></td>
      </tr>

      <?php if ($oContrato->x54_condominio != 't') : ?>
      <tr>
        <td class="bold">Categoria de Consumo:</td>
        <td><?php echo $oContrato->x13_sequencial . ' - ' . $oContrato->x13_descricao ?></td>
      </tr>
      <?php endif ?>

      <tr>
        <td class="bold">Nome/Razão Social:</td>
        <td><?php echo $oContrato->z01_numcgm . ' - ' . $oContrato->z01_nome ?></td>
      </tr>

      <tr>
        <td class="bold">Matrícula:</td>
        <td><?php echo $oContrato->x54_aguabase ?></td>
      </tr>

      <tr>
        <td class="bold">Condomínio:</td>
        <td><?php echo ($oContrato->x54_condominio == 't') ? 'Sim' : 'Não' ?></td>
      </tr>

      <tr>
        <td class="bold">Data Inicial:</td>
        <td><?php echo (string) DBDate::create($oContrato->x54_datainicial) ?></td>
      </tr>

      <?php if ($oContrato->x54_datafinal) : ?>
      <tr>
        <td class="bold">Data Final:</td>
        <td><?php echo (string) DBDate::create($oContrato->x54_datafinal) ?></td>
      </tr>
      <?php endif ?>

    </table>

  </fieldset>
<?php endif ?>
