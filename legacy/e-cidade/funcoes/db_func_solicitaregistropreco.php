<?php
$campos = "
solicitaregistropreco.pc54_sequencial,
solicitaregistropreco.pc54_solicita,
solicitaregistropreco.pc54_datainicio,
solicitaregistropreco.pc54_datatermino,
solicitaregistropreco.pc54_liberado,
solicitaregistropreco.pc54_formacontrole,
case when solicitaregistropreco.pc54_formacontrole = 1
  then 'Por Quantidade'::varchar
    else 'Por Valor'::varchar
  end as dl_Forma_de_Controle ";
?>
