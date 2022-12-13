<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Factory;

use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\AdicionalNoturno;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Extra100Diurna;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Extra100Noturna;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Extra50Diurna;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Extra50Noturna;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Extra75Diurna;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Extra75Noturna;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\ExtraEvento;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Falta;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Trabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\HoraExtraCalculo;

/**
 * Instância o tipo de hora a ser calculado
 * Class TipoHora
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Factory
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
abstract class TipoHora {

  public static function getHora(DiaTrabalho $oDiaTrabalho, $iTipoHora) {

    $oTipoHora = null;

    switch($iTipoHora) {

      case BaseHora::HORAS_ADICIONAL_NOTURNO:

        $oTipoHora = new AdicionalNoturno($oDiaTrabalho);
        break;

      case BaseHora::HORAS_EXTRA50:

        $oTipoHora = new Extra50Diurna($oDiaTrabalho);
        break;

      case BaseHora::HORAS_EXTRA75:

        $oTipoHora = new Extra75Diurna($oDiaTrabalho);
        break;

      case BaseHora::HORAS_EXTRA100:

        $oTipoHora = new Extra100Diurna($oDiaTrabalho);
        break;

      case BaseHora::HORAS_FALTA:

        $oTipoHora = new Falta($oDiaTrabalho);
        break;

      case BaseHora::HORAS_EXTRA50_NOTURNA:

        $oTipoHora = new Extra50Noturna($oDiaTrabalho);
        break;

      case BaseHora::HORAS_EXTRA75_NOTURNA:

        $oTipoHora = new Extra75Noturna($oDiaTrabalho);
        break;

      case BaseHora::HORAS_EXTRA100_NOTURNA:

        $oTipoHora = new Extra100Noturna($oDiaTrabalho);
        break;
      
      case BaseHora::HORAS_EXTRAS_EVENTO:
        $oTipoHora = new ExtraEvento($oDiaTrabalho);
        break;

      case BaseHora::HORAS_EXTRA_CALCULO:
        $oTipoHora = new HoraExtraCalculo($oDiaTrabalho);
        break;
      default:
        $oTipoHora = new Trabalho($oDiaTrabalho);
    }

    return $oTipoHora;
  }
}
