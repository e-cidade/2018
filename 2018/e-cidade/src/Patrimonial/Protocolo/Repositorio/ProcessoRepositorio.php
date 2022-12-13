<?php

namespace ECidade\Patrimonial\Protocolo\Repositorio;

require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));

use db_utils;
use DBException;
use ECidade\Patrimonial\Protocolo\Modelo\Processo;

/**
 * Class ProcessoRepositorio
 * @package ECidade\Patrimonial\Protocolo\Repositorio
 */
class ProcessoRepositorio
{
    /**
     * @param $codigo
     * @return Processo
     * @throws DBException
     */
    public static function encontrar($codigo)
    {
        $query = "SELECT * FROM ultimas_movimentacoes_processos_vencidos WHERE codigo_processo = {$codigo}";

        $resultado = db_query($query);

        if (!$resultado) {
            throw new DBException('Não foi possível buscar o processo ' . $codigo);
        }

        $resultado = db_utils::fieldsMemory($resultado, 0);

        return new Processo(
            $resultado->codigo_processo,
            $resultado->data_criacao,
            $resultado->ultima_data,
            $resultado->ultima_hora,
            $resultado->descricao_departamento,
            $resultado->nome,
            $resultado->login,
            $resultado->assunto,
            $resultado->codigo_departamento,
            $resultado->codigo_usuario,
            $resultado->numero_processo,
            $resultado->ano_processo
        );
    }

    /**
     * @return array
     * @throws DBException
     */
    public static function vencidos()
    {
        $query = "
            SELECT * 
            FROM ultimas_movimentacoes_processos_vencidos
            WHERE TO_CHAR(NOW(), 'YYYY-MM-DD') = TO_CHAR(ultima_data + (
                SELECT p101_diasprazo 
                FROM mensageriaprocesso 
                LIMIT 1
            ), 'YYYY-MM-DD')
        ";

        $resultado = db_query($query);

        if (!$resultado) {
            throw new DBException('Não foi possível buscar os processos vencidos.');
        }

        return db_utils::makeCollectionFromRecord($resultado, function ($processoRaw) {
            return new Processo(
                $processoRaw->codigo_processo,
                $processoRaw->data_criacao,
                $processoRaw->ultima_data,
                $processoRaw->ultima_hora,
                $processoRaw->descricao_departamento,
                $processoRaw->nome,
                $processoRaw->login,
                $processoRaw->assunto,
                $processoRaw->codigo_departamento,
                $processoRaw->codigo_usuario,
                $processoRaw->numero_processo,
                $processoRaw->ano_processo
            );
        });
    }

    public static function chunkByDepartamento(
        $sTabela,
        $aDepartamentos,
        \Closure $fRetorno,
        $aCampos = array('*'),
        $aFiltros = array(),
        $aOrdem = array()
    ) {
        $sCampos = implode(', ', $aCampos);
        $sDepartamentos = implode(', ', $aDepartamentos);
        $sQuery = "SELECT {$sCampos} FROM {$sTabela} ";
        $aDepartamentosSelecionados = array();

        if ($aDepartamentos || $aFiltros) {
            $sQuery .= ' WHERE ';
        }

        self::construirFiltro($aFiltros, $sQuery);

        if ($aDepartamentos) {
            $sQuery .= " codigo_departamento IN ({$sDepartamentos})";
        }

        if ($aOrdem) {
            $sQuery .= ' ORDER BY ' . implode(', ', $aOrdem);
        }

        $rsProcessos = db_query($sQuery);

        if (!$rsProcessos) {
            throw new DBException('Não foi possível buscar os processos vencidos.');
        }

        db_utils::makeCollectionFromRecord($rsProcessos, function ($oProcesso) use (&$aDepartamentosSelecionados) {
            $aDepartamentosSelecionados[$oProcesso->codigo_departamento][] = $oProcesso;
        });

        $aDepartamentosNaoEncontrados = array_diff($aDepartamentos, array_keys($aDepartamentosSelecionados));

        foreach ($aDepartamentosNaoEncontrados as $iDepartamentoNaoEncontrado) {
            $aDepartamentosSelecionados[$iDepartamentoNaoEncontrado] = array();
        }

        foreach ($aDepartamentosSelecionados as $iDepartamento => $aProcessos) {
            $oDepartamento = new \DBDepartamento($iDepartamento);
            $fRetorno($aProcessos, $oDepartamento);
            unset($aProcessos);
        }

        return true;
    }

    public static function totalVencidosPorDepartamento($aDepartamentos, $aFiltros)
    {
        $sDepartamentos = implode(', ', $aDepartamentos);

        $sQuery = '';

        self::construirFiltro($aFiltros, $sQuery);

        $sQuery = "
            SELECT COUNT(codigo_processo) AS total, 
                   codigo_departamento, 
                   descricao_departamento,
                   (SELECT ARRAY_TO_JSON(ARRAY_AGG(DISTINCT db_usuarios.nome)) 
                    FROM gestaodepartamentoprocesso
                    LEFT JOIN db_usuarios ON id_usuario = p103_db_usuarios 
                    WHERE p103_db_depart = codigo_departamento) AS responsaveis
            FROM ultimas_movimentacoes_processos_vencidos
            WHERE {$sQuery} codigo_departamento IN ({$sDepartamentos})
            GROUP BY codigo_departamento, descricao_departamento
            ORDER BY descricao_departamento
        ";

        $rsVencidos = db_query($sQuery);

        if (!$rsVencidos) {
            throw new DBException('Erro ao buscar os processos vencidos do(s) departamento(s).');
        }

        return db_utils::getCollectionByRecord($rsVencidos);
    }


    private static function construirFiltro($aFiltros, &$sQuery)
    {
        $iContadorFiltros = 1;
        $iQuantidadeFiltros = count($aFiltros);

        foreach ($aFiltros as $sCampo => $sValor) {
            if (is_array($sValor)) {
                $sCampoTabela = $sValor[0];
                $sOperador = $sValor[1];
                $sValorCampo = $sValor[2];
            } else {
                $sCampoTabela = $sCampo;
                $sOperador = '=';
                $sValorCampo = $sValor;
            }

            $sQuery .= " {$sCampoTabela} {$sOperador} {$sValorCampo}";

            if ($iContadorFiltros <= $iQuantidadeFiltros) {
                $sQuery .= ' AND';
            }

            $iContadorFiltros++;
        }
    }
}
