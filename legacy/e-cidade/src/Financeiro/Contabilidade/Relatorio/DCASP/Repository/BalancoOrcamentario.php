<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBSelller Servicos de Informatica
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


namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Repository;

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\BalancoOrcamentarioDCASP2017;
use \BalancoOrcamentarioDCASP2015;

/**
 * Classe que representa o repository das models de BalancoOrcamentarioDCASP2015 e BalancoOrcamentarioDCASP2017
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Repository
 * @author Alysson Zanette <alysson.zanette@dbseller.com.br>
 */
class BalancoOrcamentario extends \BaseClassRepository
{

    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     * @var BalancoOrcamentario
     */
    protected static $oInstance;

    /**
     * Retorna uma instância das model BalancoOrcamentario
     *
     * @param \stdClass $dados stdClass com os dados do registro a ser construido
     * @return BalancoOrcamentarioDCASP2017|BalancoOrcamentarioDCASP2015|null
     */
    protected function make($dados)
    {
        $oBalancoOrcamentario = null;

        if (!empty($dados)) {
            if ($dados->iAnoUsu < 2017) {
                $oBalancoOrcamentario = new BalancoOrcamentarioDCASP2015($dados->iAnoUsu, $dados->iCodigoRelatorio, $dados->iCodigoPeriodo);
            } else {
                $oBalancoOrcamentario = new BalancoOrcamentarioDCASP2017($dados->iAnoUsu, $dados->iCodigoRelatorio, $dados->iCodigoPeriodo);
            }
        }

        return $oBalancoOrcamentario;
    }

    /**
     * Retorna uma model BalancoOrcamentario de acordo com o ano da sessão
     *
     * @param int $anoUsu
     * @param int $codigoRelatorio
     * @param int $codigoRelatorio
     * @return BalancoOrcamentarioDCASP2017|BalancoOrcamentarioDCASP2015|null
     */
    public function getBalancoOrcamentario($anoUsu, $codigoRelatorio, $codigoPeriodo)
    {
        $dados = new \stdClass;
        $dados->iAnoUsu          = $anoUsu;
        $dados->iCodigoRelatorio = $codigoRelatorio;
        $dados->iCodigoPeriodo   = $codigoPeriodo;

        $oBalancoOrcamentario = $this->make($dados);

        return $oBalancoOrcamentario;
    }

    /**
    * Retorna o código do relatório de acodro com o ano
    * @param int $anoUsuu
    * @return integer
    */
    public function getCodigoRelatorioByAno($anoUsu)
    {
        return ($anoUsu < 2017 ? BalancoOrcamentarioDCASP2015::CODIGO_RELATORIO : BalancoOrcamentarioDCASP2017::CODIGO_RELATORIO);
    }
}
