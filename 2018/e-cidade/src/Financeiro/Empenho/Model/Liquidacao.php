<?php

namespace ECidade\Financeiro\Empenho\Model;

/**
 * Representação do documento de histórico da Liquidação
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

        $complemento = 'Reconhecimento de regime da competência ' . $competenciaLiquidacao . ' do acordo ';
        $complemento .= $acordo->getNumero() . '/' . $acordo->getAno();
        $complemento .= $historico ? (', ' . $historico) : '';

        return $complemento;
    }
}
