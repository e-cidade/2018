<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao\Formatter;

use ECidade\RecursosHumanos\ESocial\Integracao\Formatter\Formatter;

/**
 * Formata os dados do Empregador
 *
 * @package ECidade\RecursosHumanos\ESocial\Integracao\Formatter
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class EmpregadorFormatter extends Formatter
{
    /**
     * Realiza a formatação dos dados para envio da API
     *
     * @param array $dados
     * @return array
     */
    public function formatar($dados)
    {
        $dadosFormatado = parent::formatar($dados);
        return $this->posProcessamento($dadosFormatado);
    }

    /**
     * Realiza uma consistencia nos dados enviados
     *
     * @param array  $dadosFormatado
     * @return array
     */
    private function posProcessamento($dadosFormatado)
    {
        foreach ($dadosFormatado as $dadoEmpregador) {
            if (isset($dadoEmpregador->infoCadastro) && $dadoEmpregador->infoCadastro->indEtt == 'N') {
                unset($dadoEmpregador->infoCadastro->nrRegEtt);
            }

            if (isset($dadoEmpregador->infoEFR) && $dadoEmpregador->infoEFR->ideEFR == 'S') {
                unset($dadoEmpregador->infoEFR->cnpjEFR);
            }

            // grupo dadosIsencao é opcional
            if (isset($dadoEmpregador->dadosIsencao)) {
                if (!$this->validaSeGrupoFoiPreenchido(get_object_vars($dadoEmpregador->dadosIsencao))) {
                    unset($dadoEmpregador->dadosIsencao);
                }
            }

            // grupo infoOP é opcional
            if (isset($dadoEmpregador->infoOP)) {
                if (!$this->validaSeGrupoFoiPreenchido(get_object_vars($dadoEmpregador->infoOP))) {
                    unset($dadoEmpregador->infoOP);
                }
            }

            unset($dadoEmpregador->infoOrgInternacional);
        }

        return $dadosFormatado;
    }

    /**
     * Valida se uma propriedade do grupo foi preechida
     *
     * @param array $propriedades
     * @return boolean
     */
    private function validaSeGrupoFoiPreenchido($propriedades)
    {
        $preenchido = false;
        foreach ($propriedades as $propriedade) {
            if (!empty($propriedade)) {
                $preenchido = true;
            }
        }
        return $preenchido;
    }
}
