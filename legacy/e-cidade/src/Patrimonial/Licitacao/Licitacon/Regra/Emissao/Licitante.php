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

namespace ECidade\Patrimonial\Licitacao\Licitacon\Regra\Emissao;

use TratamentoDiferenciado;
use FornecedorBase;

/**
 * Class Licitante
 */
class Licitante extends BaseAbstract
{

    /**
     * Código do leiaute da versão 1.2
     * @var integer
     */
    const CODIGO_LAYOUT_V12 = 236;

    /**
     * @return int
     */
    public function getCodigoLayout()
    {
        return self::CODIGO_LAYOUT_V12;
    }

    /**
     * Retorna o valor para o campo BL_BENEFICIO_MICRO_EPP do licitante para uso no arquivo LICITANTE.TXT
     * @param \licitacao $oLicitacao
     * @param           $iTipoEmpresa
     *
     * @return string
     */
    public function getBeneficioMicroEpp(\licitacao $oLicitacao, $iTipoEmpresa)
    {
        $oTratamentoDiferenciado = new TratamentoDiferenciado($oLicitacao);
        if ($oTratamentoDiferenciado->temBeneficio()) {
            if (self::licitantePossuiBeneficioMicroEPP($iTipoEmpresa)) {
                return 'S';
            }
        }
        return 'N';
    }

    /**
     * Verifica se o licitante é do tipo MicroEPP.
     * @param $iTipoEmpresa
     *
     * @return bool
     */
    private function licitantePossuiBeneficioMicroEPP($iTipoEmpresa)
    {
        return FornecedorBase::isTipoMicroEpp($iTipoEmpresa);
    }

    /**
     * Retorna a versão do leiaute
     * @return string
     */

    public function getVersao()
    {
        return $this->oConfiguracao->getVersao();
    }
}
