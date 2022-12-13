<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\Efetividade\Repository;

use ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaTrabalho as EscalaTrabalhoModel;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\Jornada;

/**
 * Classe responsável pelas buscas e ações referentes a escala de trabalho
 * Class EscalaTrabalho
 * @package ECidade\RecursosHumanos\RH\Efetividade\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class EscalaTrabalho
{
    private static $aColecao;

    /**
     * Retorna uma instância de EscalaTrabalho
     * @param int $iGradeHorario
     * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaTrabalho
     * @throws \BusinessException
     * @throws \DBException
     */
    public static function getInstanciaPorCodigo($iGradeHorario)
    {

        if (!empty(self::$aColecao["{$iGradeHorario}"])) {
            return self::$aColecao["{$iGradeHorario}"];
        }

        $oDaoEscalaServidor     = new \cl_escalaservidor();
        $sCamposEscalaServidor  = "gradeshorarios.*";
        $aWhereEscalaServidor   = array();
        $aWhereEscalaServidor[] = "rh192_gradeshorarios = {$iGradeHorario}";
        $sSqlEscalaServidor     = $oDaoEscalaServidor->sqlEscalaTrabalhoJornada($sCamposEscalaServidor, null, $aWhereEscalaServidor);
        $rsEscalaServidor       = db_query($sSqlEscalaServidor);

        if (!$rsEscalaServidor) {
            throw new \DBException("Erro ao buscar a escala de trabalho do servidor");
        }

        if (pg_num_rows($rsEscalaServidor) == 0) {
            throw new \BusinessException("Escala de trabalho do servidor não encontrada.");
        }

        $oDadosEscala    = \db_utils::fieldsMemory($rsEscalaServidor, 0);
        $oEscalaTrabalho = new EscalaTrabalhoModel();

        $oEscalaTrabalho->setCodigo($oDadosEscala->rh190_sequencial);
        $oEscalaTrabalho->setDescricao($oDadosEscala->rh190_descricao);
        $oEscalaTrabalho->setDataBase(new \DBDate($oDadosEscala->rh190_database));
        $oEscalaTrabalho->setRevezamento($oDadosEscala->rh190_revezamento);

        self::getJornadas($oEscalaTrabalho);

        self::$aColecao["{$iGradeHorario}"] = $oEscalaTrabalho;
        return $oEscalaTrabalho;
    }

    /**
     * Preenche as jornadas de trabalho vinculadas a uma escala
     * @param EscalaTrabalhoModel $oEscalaTrabalho
     * @throws \DBException
     */
    private static function getJornadas(EscalaTrabalhoModel $oEscalaTrabalho)
    {

        $oDaoEscalaServidor     = new \cl_escalaservidor();
        $sCamposEscalaServidor  = "gradeshorariosjornada.*";
        $aWhereEscalaServidor   = array();
        $aWhereEscalaServidor[] = "rh191_gradehorarios = {$oEscalaTrabalho->getCodigo()}";
        $sSqlEscalaServidor     = $oDaoEscalaServidor->sqlEscalaTrabalhoJornada($sCamposEscalaServidor, null, $aWhereEscalaServidor);
        $rsEscalaServidor       = db_query($sSqlEscalaServidor);

        if (!$rsEscalaServidor) {
            throw new \DBException("Erro ao buscar a escala de trabalho do servidor");
        }

        $iLinhasEscalaServidor = pg_num_rows($rsEscalaServidor);

        for ($iContador = 0; $iContador < $iLinhasEscalaServidor; $iContador++) {
            $oDadosJornada = \db_utils::fieldsMemory($rsEscalaServidor, $iContador);

            $oEscalaTrabalho->addJornada(
                Jornada::getInstanciaByCodigo($oDadosJornada->rh191_jornada),
                $oDadosJornada->rh191_ordemhorario
            );
        }
    }
}
