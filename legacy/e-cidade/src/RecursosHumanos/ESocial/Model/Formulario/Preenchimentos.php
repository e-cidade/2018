<?php
namespace ECidade\RecursosHumanos\ESocial\Model\Formulario;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\DadosResposta;

/**
 * Classe responsável por buscar os dados de preenchimento dos formulários
 * @package ECidade\RecursosHumanos\ESocial\Model\Formulario
 */
class Preenchimentos
{
    /**
     * Responsável pelo preenchimento do formulário
     *
     * @var mixed
     */
    private $responsavelPreenchimento;

    /**
     * Informa o responsável pelo preenchimento. Se não indormado, busca de todos
     *
     * @param mixed $responsavel
     */
    public function setReponsavelPeloPreenchimento($responsavel)
    {
        $this->responsavelPreenchimento = $responsavel;
    }

    /**
     * Busca os preenchimentos dos empregadores
     *
     * @param integer $codigoFormulario
     * @return stdClass[]
     */
    public function buscarUltimoPreenchimentoEmpregador($codigoFormulario)
    {
        $where = array(" db101_sequencial = {$codigoFormulario} ");
        if (!empty($this->responsavelPreenchimento)) {
            $where[] = "eso03_cgm = {$this->responsavelPreenchimento}";
        }

        $where = implode(' and ', $where);

        $group = " group by eso03_cgm";
        $campos = 'eso03_cgm as cgm, max(db107_sequencial) as preenchimento, ';
        $campos .= '(select z01_cgccpf from cgm where z01_numcgm = eso03_cgm) as inscricao_empregador ';
        $dao = new \cl_avaliacaogruporespostacgm;
        $sql = $dao->sql_avaliacao_preenchida(null, $campos, null, $where . $group);
        $rs = \db_query($sql);

        if (!$rs) {
            throw new \Exception("Erro ao buscar os preenchimentos dos formulários dos empregadores.");
        }

        return \db_utils::getCollectionByRecord($rs);
    }

    /**
     * Busca os preenchimentos dos servidores
     *
     * @param integer $codigoFormulario
     * @return stdClass[]
     */
    public function buscarUltimoPreenchimentoServidor($codigoFormulario)
    {
        $where = " db101_sequencial = {$codigoFormulario} ";
        $group = " group by eso02_rhpessoal";
        $campos = 'eso02_rhpessoal as matricula, max(db107_sequencial) as preenchimento';
        $dao = new \cl_avaliacaogruporespostarhpessoal;
        $sql = $dao->sql_avaliacao_preenchida(null, $campos, null, $where . $group);
        $rs = \db_query($sql);

        if (!$rs) {
            throw new \Exception("Erro ao buscar os preenchimentos dos formulários dos servidores.");
        }

        /**
         * Para pegar o empregador, vai ter que ver a lotação do servidor na competência.
         */
        return \db_utils::getCollectionByRecord($rs);
    }

    /**
     * Busca o preenchimento dos formulários genéricos.
     * Aqueles que possuem uma carga de dados e um campo pk (Uma chave única )
     *
     * @param integer $codigoFormulario
     * @return stdClass[]
     */
    public function buscarUltimoPreenchimento($codigoFormulario)
    {
        $where = " db101_sequencial = {$codigoFormulario} ";
        $campos = 'distinct db107_sequencial as preenchimento, ';
        $campos .= '(select db106_resposta';
        $campos .= '   from avaliacaoresposta as ar ';
        $campos .= '   join avaliacaogrupoperguntaresposta as preenchimento on preenchimento.db108_avaliacaoresposta = ar.db106_sequencial ';
        $campos .= '   join avaliacaoperguntaopcao as apo on apo.db104_sequencial = ar.db106_avaliacaoperguntaopcao ';
        $campos .= '   join avaliacaopergunta as ap on ap.db103_sequencial = apo.db104_avaliacaopergunta ';
        $campos .= '  where ap.db103_perguntaidentificadora is true ';
        $campos .= '    and preenchimento.db108_avaliacaogruporesposta = db107_sequencial ';
        $campos .= ') as pk ';
        $dao = new \cl_avaliacaogruporesposta;
        $sql = $dao->sql_avaliacao_preenchida(null, $campos, null, $where);

        $rs = \db_query($sql);

        if (!$rs) {
            throw new \Exception("Erro ao buscar os preenchimentos dos formulários das rubricas.");
        }

        return \db_utils::getCollectionByRecord($rs);
    }

    /**
     * Busca o preenchimento dos formulários genéricos.
     * Aqueles que possuem uma carga de dados e um campo pk (Uma chave única )
     *
     * @param integer $codigoFormulario
     * @return stdClass[]
     */
    public function buscarUltimoPreenchimentoRubrica($codigoFormulario)
    {
        $where = " db101_sequencial = {$codigoFormulario} ";
        $campos = 'distinct db107_sequencial as preenchimento, ';
        $campos .= '(select db106_resposta';
        $campos .= '   from avaliacaoresposta as ar ';
        $campos .= '   join avaliacaogrupoperguntaresposta as preenchimento on preenchimento.db108_avaliacaoresposta = ar.db106_sequencial ';
        $campos .= '   join avaliacaoperguntaopcao as apo on apo.db104_sequencial = ar.db106_avaliacaoperguntaopcao ';
        $campos .= '   join avaliacaopergunta as ap on ap.db103_sequencial = apo.db104_avaliacaopergunta ';
        $campos .= '  where ap.db103_perguntaidentificadora is true ';
        $campos .= '    and preenchimento.db108_avaliacaogruporesposta = db107_sequencial ';
        $campos .= ') as pk ';
        $dao = new \cl_avaliacaogruporesposta;
        $sql = $dao->sql_avaliacao_preenchida(null, $campos, null, $where);

        $rs = \db_query($sql);

        if (!$rs) {
            throw new \Exception("Erro ao buscar os preenchimentos dos formulários das rubricas.");
        }

        $rubricas = \db_utils::getCollectionByRecord($rs);

        /**
         * @todo busca os empregadores da instituição e adicona para cada rubriuca
         */
        return \db_utils::getCollectionByRecord($rs);
    }


    /**
     * Buscas as respostas de um preenchimento
     *
     * @param integer $preenchimentoId
     * @return DadosResposta[]
     */
    public static function buscaRespostas($preenchimentoId)
    {
        $dao = new \cl_avaliacaogruporesposta;
        $campos = array(
            "db102_identificadorcampo as grupo",
            "db103_identificadorcampo as pergunta",
            "db103_sequencial as idpergunta",
            "db104_valorresposta as valorresposta",
            "db106_resposta as resposta",
            "db103_avaliacaotiporesposta as tipopergunta",
            "db103_obrigatoria as obrigatoria"
        );

        $campos = implode(', ', $campos);
        $sql = $dao->busca_resposta_preenchimento($preenchimentoId, $campos);
        $rs = \db_query($sql);

        return \db_utils::makeCollectionFromRecord($rs, function ($dado) {

            $dadoResposta = new DadosResposta();
            $dadoResposta->grupo = $dado->grupo;
            $dadoResposta->pergunta = $dado->pergunta;
            $dadoResposta->idPergunta = $dado->idpergunta;
            $dadoResposta->valorResposta = $dado->valorresposta;
            $dadoResposta->resposta = $dado->resposta;
            $dadoResposta->tipoPergunta = $dado->tipopergunta;
            $dadoResposta->obrigatoria = $dado->obrigatoria == 't';

            return $dadoResposta;
        });
    }
}
