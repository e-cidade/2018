<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Agua\Leitura;

use ECidade\Tributario\Agua\Entity\Leitura\Situacao;
use ECidade\Tributario\Agua\Leitura\Regra\RegraInterface;
use ParameterException;

class RegraFactory {

  /**
   * @param ResumoMensal[] $aResumosMensais
   * @return RegraInterface
   * @throws ParameterException
   */
  public function create(array $aResumosMensais) {

    if (!count($aResumosMensais)) {
      throw new ParameterException('Nenhuma Leitura informada.');
    }

    foreach ($aResumosMensais as $oResumoMensal) {
      if (!$oResumoMensal instanceof ResumoMensal) {
        throw new ParameterException('Lista de Leituras é inválida.');
      }
    }

    /**
     * Regra de Média
     */
    $oUltimaLeitura = array_shift($aResumosMensais);
    if ($oUltimaLeitura->getRegra() == Situacao::REGRA_SEM_LEITURA_SEM_SALDO) {
      return new Regra\Media(array_slice($aResumosMensais, 0, 6));
    }

    if ($oUltimaLeitura->getRegra() != Situacao::REGRA_NORMAL) {
      return null;
    }

    /**
     * Regra de Penalidade
     */
    $aLeiturasCalculadas = array();
    foreach ($aResumosMensais as $oResumoMensal) {

      $lLeituraMediaCalculada = ($oResumoMensal->getRegra() == Situacao::REGRA_MEDIA_ULTIMOS_MESES);

      if ($lLeituraMediaCalculada) {

        $aLeiturasCalculadas[] = $oResumoMensal;
        continue;
      }

      if (count($aLeiturasCalculadas) && !$lLeituraMediaCalculada) {
        $aLeiturasCalculadas[] = $oResumoMensal;
      }

      break;
    }

    if (count($aLeiturasCalculadas)) {

      array_unshift($aLeiturasCalculadas, $oUltimaLeitura);
      return new Regra\Penalidade($aLeiturasCalculadas);
    }

    return null;
  }
}
