<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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
namespace ECidade\Saude\Agendamento\Exame\Cota\Mensal;

use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Prestador\Individual as PrestadorIndividual;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Prestador\Grupo as PrestadorGrupo;

use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Municipio\Individual as MunicipioIndividual;
use ECidade\Saude\Agendamento\Exame\Cota\Mensal\Municipio\Grupo as MunicipioGrupo;

class Factory
{
  /**
   * Funcão que retorna a instância do objeto da cota mensal de acordo com o tipo
   *
   * @param  integer $iTipo [description]
   *
   * @return \Mensal
   */
  public function getCotaMensal( $iTipo, \StdClass $oDados)
  {
    $oCotaMensal = null;

    if ( $iTipo == PrestadorIndividual::TIPO_COTA ) {
      $oCotaMensal = new PrestadorIndividual();
    }

    if ( $iTipo == PrestadorGrupo::TIPO_COTA ) {
      $oCotaMensal = new PrestadorGrupo();
    }

    if ( $iTipo == MunicipioIndividual::TIPO_COTA ) {
      $oCotaMensal = new MunicipioIndividual();
    }

    if ( $iTipo == MunicipioGrupo::TIPO_COTA ) {
      $oCotaMensal = new MunicipioGrupo();
    }

    if ( is_null($oCotaMensal) ) {
      throw new \BusinessException("Tipo de cota mensal inválida.");
    }

    $oCotaMensal->setQuantidade($oDados->iQuantidade);
    $oCotaMensal->setMes($oDados->iMes);
    $oCotaMensal->setAno($oDados->iAno);
    $oCotaMensal->setNome($oDados->sNome);

    return $oCotaMensal;
  }
}