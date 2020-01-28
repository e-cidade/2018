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

/**
 * Class RegraClassificacaoCredorElementos
 * Padrão Chain of Responsability
 * Regra referente aos elementos de despesa vinculados a Lista de Classificação de Credores.
 */
class RegraClassificacaoCredorElementosExclusao extends RegraClassificacaoCredor {

  private function comparaContaOrcamento(ContaOrcamento $oContaComparacao) {

    foreach ($this->oListaClassificacaoCredor->getContas() as $oConta) {

      if (!$oConta->contaExclusao()) {
        continue;
      }

      $sEstruturalContaListaComMascara = $oConta->getContaOrcamento()->getEstruturalComMascara();
      $iNivelBusca = $oConta->getContaOrcamento()->getNivelEstrutura($sEstruturalContaListaComMascara);

      $sEstruturalContaEmpenho = $oContaComparacao->getEstruturaAteNivel($oContaComparacao->getEstruturalComMascara(), $iNivelBusca);
      $sEstruturalContaLista   = $oConta->getContaOrcamento()
                                        ->getEstruturaAteNivel($sEstruturalContaListaComMascara, $iNivelBusca);

      if ($sEstruturalContaEmpenho == $sEstruturalContaLista) {
        return false;
      }
    }

    return true;
  }

  public function regra() {

    $oContaEmpenho = $this->oAtributosEmpenho->getContaOrcamento();
    $oContaElemento = new ContaOrcamento($this->oAtributosEmpenho->getElemento(), $oContaEmpenho->getAno());

    if (!$this->comparaContaOrcamento($oContaElemento)) {
      return false;
    }

    if (!$this->comparaContaOrcamento($oContaEmpenho)) {
      return false;
    }

    return true;
  }
}
