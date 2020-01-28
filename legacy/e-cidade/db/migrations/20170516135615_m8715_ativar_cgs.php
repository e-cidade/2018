<?php

use Classes\PostgresMigration;

class M8715AtivarCgs extends PostgresMigration
{
  public function up() {

    $sSqlCreateTableFalecidos  = "CREATE TEMPORARY TABLE w_cgs_falecidos_8715 as ";
    $sSqlCreateTableFalecidos .= "      select z01_i_cgsund ";
    $sSqlCreateTableFalecidos .= "        from cgs_und_ext ";
    $sSqlCreateTableFalecidos .= "       where z01_b_faleceu is true ";
    $sSqlCreateTableFalecidos .= "         and z01_d_falecimento is null";

    $sSqlUpdateFalecidos  = "update cgs_und_ext ";
    $sSqlUpdateFalecidos .= "   set z01_b_faleceu = 'f' ";
    $sSqlUpdateFalecidos .= " where z01_b_faleceu is true ";
    $sSqlUpdateFalecidos .= "    and z01_d_falecimento is null";

    $sSqlCreateTableMaeDesconhecida  = "CREATE TEMPORARY TABLE w_cgs_mae_desconhecida_8715 as ";
    $sSqlCreateTableMaeDesconhecida .= "      select cgs_und_ext.z01_i_cgsund ";
    $sSqlCreateTableMaeDesconhecida .= "        from cgs_und ";
    $sSqlCreateTableMaeDesconhecida .= "             inner join cgs_und_ext on cgs_und_ext.z01_i_cgsund = cgs_und.z01_i_cgsund ";
    $sSqlCreateTableMaeDesconhecida .= "       where z01_b_descnomemae is true ";
    $sSqlCreateTableMaeDesconhecida .= "    and trim(z01_v_mae) <> '' ";
    $sSqlCreateTableMaeDesconhecida .= "    and trim(z01_v_mae) not ilike 'SEM INFORM%' ";
    $sSqlCreateTableMaeDesconhecida .= "    and trim(z01_v_mae) not ilike '%INFORMADO'";

    $sSqlUpdateMaeDesconhecida  = "update cgs_und_ext ";
    $sSqlUpdateMaeDesconhecida .= "   set z01_b_descnomemae = 'f' ";
    $sSqlUpdateMaeDesconhecida .= "  from cgs_und ";
    $sSqlUpdateMaeDesconhecida .= " where cgs_und_ext.z01_i_cgsund = cgs_und.z01_i_cgsund ";
    $sSqlUpdateMaeDesconhecida .= "    and z01_b_descnomemae is true ";
    $sSqlUpdateMaeDesconhecida .= "    and trim(z01_v_mae) <> '' ";
    $sSqlUpdateMaeDesconhecida .= "    and trim(z01_v_mae) not ilike 'SEM INFORM%' ";
    $sSqlUpdateMaeDesconhecida .= "    and trim(z01_v_mae) not ilike '%INFORMADO'";

    $this->execute($sSqlCreateTableFalecidos);
    $this->execute($sSqlUpdateFalecidos);
    $this->execute($sSqlCreateTableMaeDesconhecida);
    $this->execute($sSqlUpdateMaeDesconhecida);
  }

  public function down(){}
}