<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository;

use ECidade\Financeiro\Tesouraria\InfracaoTransito\InfracaoTransito as InfracaoTransitoModel;

/**
 * Class InfracaoTransito
 * Classe que representa o repository do model InfracaoTransito
 * @package ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository
 * @author Davi Busanello <davi@dbseller.com.br>
 */
class InfracaoTransito extends \BaseClassRepository
{
    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     * @var ReceitaInfracao
     */
    protected static $oInstance;

    /**
     * Retorna a infracao pelo codigo
     * @param string $codigoinfracao
     * @return InfracaoTransitoModel|null|
     * @throws \BusinessException
     * @throws \DBException
     */
    public function getByCodigoInfracao($codigoinfracao)
    {
        $oDaoInfracaoTransito = new \cl_infracaotransito();
        $sWhere = "i05_codigo = '{$codigoinfracao}'";
        $sql = $oDaoInfracaoTransito->sql_query_file(null, '*', null, $sWhere);

        $rsResult = db_query($sql);

        if (!$rsResult) {
            throw new \DBException("Ocorreu um erro ao buscar Infração.");
        }

        if (pg_num_rows($rsResult) == 0) {
            throw new \BusinessException("Infração {$codigoinfracao} não encontrada.");
        }

        $oInfracaoTransito = \db_utils::fieldsMemory($rsResult, 0);

        return $this->make($oInfracaoTransito);
    }

    /**
     * @param $dados
     * @return InfracaoTransitoModel|null
     */
    protected function make($dados)
    {

        if (empty($dados)) {
            return null;
        }

        $oInfracaoTransito = new InfracaoTransitoModel();

        $oInfracaoTransito->setId($dados->i05_sequencial);
        $oInfracaoTransito->setCodigoInfracao($dados->i05_codigo);
        $oInfracaoTransito->setNivel($dados->i05_nivel);
        $oInfracaoTransito->setDescricao($dados->i05_descricao);

        return $oInfracaoTransito;
    }
}
