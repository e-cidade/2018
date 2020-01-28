<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBselller Servicos de Informatica
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

use ECidade\Financeiro\Tesouraria\InfracaoTransito\ReceitaInfracao as ReceitaInfracaoModel;

/**
 * Class ReceitaInfracao
 * Classe que representa o repository do model ReceitaInfracao
 * @package ECidade\Financeiro\Tesouraria\InfracaoTransito\Repository
 * @author Alysson Zanette <alysson.zanette@dbseller.com.br>
 */
class ReceitaInfracao extends \BaseClassRepository
{

    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     * @var ReceitaInfracao
     */
    protected static $oInstance;

    /**
     * Retorna uma instancia da classe
     *
     * @param \stdClass $dados stdClass com os dados do registro a ser construido
     * @return ReceitaInfracaoModel
     */
    protected function make($dados)
    {
        if (empty($dados)) {
            return NULL;
        }

        $oReceitaInfracao = new ReceitaInfracaoModel();

        $oReceitaInfracao->setId($dados->i06_sequencial);
        $oReceitaInfracao->setNivel($dados->i06_nivel);
        $oReceitaInfracao->setReceitaPrincipal($dados->i06_receitaprincipal);
        $oReceitaInfracao->setReceitaDuplicidade($dados->i06_receitaduplicidade);
        $oReceitaInfracao->setExercicio($dados->i06_anousu);
        $oReceitaInfracao->setConta($dados->i06_conta);

        return $oReceitaInfracao;
    }

    /**
     * Retorna as configuracoes das Receitas de Infração do codigo informado
     * @param $codigo
     * @return ReceitaInfracaoModel
     * @throws \DBException
     */
    public function getByCodigo($codigo)
    {
        $oDao = new \cl_receitainfracao();
        $oDados = $oDao->findBydId($codigo);

        if (empty($oDados)) {
            throw new \DBException("Houve uma falha ao buscar a configuração com o código {$codigo}.");
        }
        return $this->make($oDados);

    }

    /**
     * Retorna as configuracoes das Receitas de Infracao do ano informado
     * @param $ano
     * @return ReceitaInfracaoModel[]
     * @throws \DBException
     */
    public function getByAno($ano)
    {
        $oDaoReceitaInfracao = new \cl_receitainfracao;
        $sWhere = "i06_anousu = {$ano}";

        $sSqlReceitas = $oDaoReceitaInfracao->sql_query_file(null, "*", "i06_nivel", $sWhere);
        $rsReceitas = db_query($sSqlReceitas);

        if (!$rsReceitas) {
            throw new \DBException("Não foi possível pesquisar as receitas do ano {$ano}.\nTente novamente.");
        }

        return $this->makeCollection($rsReceitas);
    }

    /**
     * Retorna as configuracoes das Receitas de Infração do ano e nivel informado
     * @param $ano
     * @param $nivel
     * @return ReceitaInfracaoModel
     * @throws \DBException
     */
    public function getByAnoNivel($ano, $nivel)
    {
        $oDaoReceitaInfracao = new \cl_receitainfracao;
        $aWhere = array();
        $aWhere[] = "i06_anousu = {$ano}";
        $aWhere[] = "i06_nivel  = {$nivel}";
        $sWhere = implode(" AND ", $aWhere);

        $sSqlReceitas = $oDaoReceitaInfracao->sql_query_file(null, "*", "i06_nivel", $sWhere);
        $rsReceitas = db_query($sSqlReceitas);

        if (!$rsReceitas) {
            throw new \DBException("Não foi possível pesquisar as receitas do ano {$ano} e nível {$nivel}.\nTente novamente.");
        }

        $oDados = pg_fetch_object($rsReceitas);

        return $this->make($oDados);
    }

    /**
     * Persiste o objeto da classe ReceitaInfracao no banco de dados.
     * @param ReceitaInfracaoModel $dadoReceitaInfracao
     * @return bool
     * @throws \Exception
     */
    public function salvar(ReceitaInfracaoModel $dadoReceitaInfracao)
    {
        if (empty($dadoReceitaInfracao)) {
            throw new \Exception("Ocorreu um erro ao incluir, o objeto está vazio.");
        }

        $dadoReceitaInfracao->validaReceitas();

        $oReceitasInfracao = $this->getByAnoNivel($dadoReceitaInfracao->getExercicio(), $dadoReceitaInfracao->getNivel());

        $oDao = new \cl_receitainfracao();
        $oDao->i06_receitaprincipal = $dadoReceitaInfracao->getReceitaPrincipal();
        $oDao->i06_receitaduplicidade = $dadoReceitaInfracao->getReceitaDuplicidade();
        $oDao->i06_nivel = $dadoReceitaInfracao->getNivel();
        $oDao->i06_conta = $dadoReceitaInfracao->getConta();

        if (empty($oReceitasInfracao)) {

            $oDao->i06_anousu = $dadoReceitaInfracao->getExercicio();

            if (!$oDao->incluir()) {
                throw new \Exception("Ocorreu um erro ao incluir a configuração." . $oDao->erro_msg);
            }
        } else {

            $oDao->i06_sequencial = $oReceitasInfracao->getId();
            $oDao->i06_anousu = $oReceitasInfracao->getExercicio();

            if (!$oDao->alterar()) {
                throw new \Exception("Ocorreu um erro ao alterar a configuração." . $oDao->erro_msg);
            }
        }
        $oDao->atualizarContas($dadoReceitaInfracao->getConta(), $dadoReceitaInfracao->getExercicio());
        return true;
    }

    /**
     * Retorna os niveis que não estão configurados
     * @param $ano
     * @return \stdClass
     * @throws \DBException
     */
    public function verificaFaltantes($ano)
    {
        $oDaoReceitaInfracao = new \cl_receitainfracao;
        $aWhere = array();
        $aWhere[] = "i06_anousu = {$ano}";
        $sWhere = implode(" AND ", $aWhere);
        $oRetorno = new \stdClass;
        $oRetorno->Nivel = array();

        $sSqlNivel = $oDaoReceitaInfracao->sql_query_file(null, "i06_nivel, i06_conta", "i06_nivel", $sWhere);
        $rsNivel = db_query($sSqlNivel);

        if (!$rsNivel) {
            throw new \DBException("Não foi possível pesquisar as receitas do ano {$ano}.\nTente novamente.");
        }

        $aNivel = array(1 => 1, 2 => 2, 3 => 3, 4 => 4);
        $aNivelConfig = \db_utils::getCollectionByRecord($rsNivel);

        foreach ($aNivelConfig as $key => $value) {

            if ($aNivel[$value->i06_nivel]) {
                unset($aNivel[$value->i06_nivel]);
            }
            $oRetorno->Conta = $value->i06_conta;
        }

        foreach ($aNivel as $iNivel) {
            $oRetorno->Nivel[] = $iNivel;
        }

        return $oRetorno;
    }

    /**
     * @param $rsResult
     * @return array
     */
    private function makeCollection($rsResult)
    {
        $aReceitasCollection = array();
        $aReceitas = pg_fetch_all($rsResult);

        if (empty($aReceitas)) {
            return NULL;
        }

        foreach ($aReceitas as $aReceita) {
            $aReceitasCollection[] = $this->make((object)$aReceita);
        }

        return $aReceitasCollection;
    }
}
