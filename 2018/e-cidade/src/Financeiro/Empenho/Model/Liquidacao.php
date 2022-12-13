<?php

namespace ECidade\Financeiro\Empenho\Model;

/**
 * Representa��o do documento de hist�rico da Liquida��o
 *
 * @package ECidade\Financeiro\Empenho\Model
 * @author Stephano Ramos <stephano.ramos@dbseller.com.br>
 * @author Leonardo Oliveira <leonardo.malia@dbseller.com.br>
 */
class Liquidacao
{
    /**
     * @param integer $codigoContrato
     * @param string $competenciaLiquidacao
     * @param string $historico
     * @return string
     */
    public function obterComplemento($codigoContrato, $competenciaLiquidacao, $historico = null)
    {
        $acordo = \AcordoRepository::getByCodigo($codigoContrato);

        if (empty($competenciaLiquidacao) || empty($acordo)) {
            return $historico;
        }

        $complemento = 'Reconhecimento de regime da compet�ncia ' . $competenciaLiquidacao . ' do acordo ';
        $complemento .= $acordo->getNumero() . '/' . $acordo->getAno();
        $complemento .= $historico ? (', ' . $historico) : '';

        return $complemento;
    }
}
