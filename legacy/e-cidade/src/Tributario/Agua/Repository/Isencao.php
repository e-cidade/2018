<?php

namespace ECidade\Tributario\Agua\Repository;

use AguaIsencaoCgm;
use DBException;

class Isencao {

  /**
   * @param integer $iCodigoCgm
   *
   * @return AguaIsencaoCgm|null
   * @throws DBException
   */
  public function getIsencaoValida($iCodigoCgm) {

    $sData = date('Y-m-d', db_getsession('DB_datausu'));
    $sSql = "
      select
        x56_sequencial
      from aguaisencaocgm
      where x56_datainicial <= '{$sData}'
        and (x56_datafinal >= '{$sData}' or x56_datafinal is null)
        and x56_cgm = {$iCodigoCgm}
      limit 1
    ";
    $rsIsencaoValida = db_query($sSql);

    if (!$rsIsencaoValida) {
      throw new DBException('Ocorreu um erro ao procurar isenção para o CGM informado.');
    }

    if (!pg_num_rows($rsIsencaoValida)) {
      return null;
    }

    $oIsencao = pg_fetch_object($rsIsencaoValida);
    return new AguaIsencaoCgm($oIsencao->x56_sequencial);
  }

}
