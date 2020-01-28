<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

/**
 * Mapeia os recursos (rotas) disponiveis da API do e-cidade para tratar os dados do eSocial
 * @package ECidade\RecursosHumanos\ESocial\Integracao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class Recurso
{
    const CADASTRO_EMPREGADOR = '/empregador';
    const EVENTO_EMPREGADOR = '/evento/empregador';
    const EVENTO_ESTABELECIMENTO = '/evento/estabelecimento';
    const EVENTO_RUBRICA = '/evento/rubrica';
    const EVENTO_LOTACAO_TRIBUTARIA = '/evento/lotacao_tributaria';
    const EVENTO_EMPREGO_PUBLICO = '/evento/emprego_publico';
    const EVENTO_CARREIRA_PUBLICA = '/evento/carreira_publica';
    const EVENTO_FUNCAO = '/evento/funcao';
    const EVENTO_HORARIOS = '/evento/horarios';
    const EVENTO_AMBIENTE_TRABALHO = '/evento/ambiente_trabalho';
    const EVENTO_PROCESSO_ADMINISTRATIVO = '/evento/processo_administrativo';
    const EVENTO_OPERACAO_PORTUARIA = '/evento/operacao_portuaria';

    /**
     * Retorna a rota de acordo com o código do evento
     *
     * @param integer $codigoEvento
     * @throws \Exception
     * @return string
     */
    public static function getRecursoByEvento($codigoEvento)
    {
        switch ($codigoEvento) {
            case Tipo::S1000:
                return self::EVENTO_EMPREGADOR;
                break;
            case Tipo::S1005:
                return self::EVENTO_ESTABELECIMENTO;
                break;
            case Tipo::S1010:
                return self::EVENTO_RUBRICA;
                break;
            case Tipo::S1020:
                return self::EVENTO_LOTACAO_TRIBUTARIA;
                break;
            case Tipo::S1030:
                return self::EVENTO_EMPREGO_PUBLICO;
                break;
            case Tipo::S1035:
                return self::EVENTO_CARREIRA_PUBLICA;
                break;
            case Tipo::S1040:
                return self::EVENTO_FUNCAO;
                break;
            case Tipo::S1050:
                return self::EVENTO_HORARIOS;
                break;
            case Tipo::S1060:
                return self::EVENTO_AMBIENTE_TRABALHO;
                break;
            case Tipo::S1070:
                return self::EVENTO_PROCESSO_ADMINISTRATIVO;
                break;
            case Tipo::S1080:
                return self::EVENTO_OPERACAO_PORTUARIA;
                break;
            default:
                throw new \Exception("Evento não mapeado.");
                break;
        }
    }
}
