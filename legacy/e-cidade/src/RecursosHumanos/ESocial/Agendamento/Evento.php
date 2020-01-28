<?php

namespace ECidade\RecursosHumanos\ESocial\Agendamento;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

class Evento
{

    /**
     * Código do Evento do eSocial
     *
     * @var integer
     */
    private $tipoEvento;

    /**
     * Código do empregador
     *
     * @var integer
     */
    private $empregador;

    /**
     * Código do responsavel pelo evento
     * @var mixed
     */
    private $responsavelPreenchimento;

    /**
     * Dados do Evento
     *
     * @var \stdClass
     */
    private $dado;

    /**
     * md5 do objeto salvo
     *
     * @var string
     */
    private $md5;

    /**
     * Undocumented function
     *
     * @param integer $tipoEvento
     * @param integer $empregador
     * @param string $responsavelPreenchimento
     * @param \stdClass $dados
     */
    public function __construct($tipoEvento, $empregador, $responsavelPreenchimento, $dado)
    {
        /**
         * @todo pesquisar exite na fila um evento do tipo: $tipoEvento para o : $responsavelPreenchimento
         * @todo Não existido, cria uma agenda e inclui na tabela
         * @todo se houver e os $dados forem iguais ( usar md5 ), desconsidera
         * @todo se houver e os $dados forem diferentes ( usar md5 ), altera / inclui novo registro e reagenda
         *
         */
        $this->tipoEvento = $tipoEvento;
        $this->empregador = $empregador;
        $this->responsavelPreenchimento = $responsavelPreenchimento;
        $this->dado = $dado;

        $dado = json_encode(\DBString::utf8_encode_all($this->dado));
        if (is_null($dado)) {
            throw new \Exception("Erro ao codificar dados para envio.");
        }
        $this->md5 = md5($dado);
    }

    public function adicionarFila()
    {
        $where = array(
            "rh213_evento = {$this->tipoEvento}",
            "rh213_empregador = {$this->empregador}",
            "rh213_responsavelpreenchimento = '{$this->responsavelPreenchimento}'"
        );

        $where = implode(" and ", $where);
        $dao = new \cl_esocialenvio();
        $sql = $dao->sql_query_file(null, "*", null, $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new \Exception("Erro ao buscar registros.");
        }

        if (pg_num_rows($rs) == 1) {
            $md5Evento = \db_utils::fieldsMemory($rs, 0)->rh213_md5;
            if ($md5Evento == $this->md5) {
                return false;
            }
        }
        $codigoFila = pg_num_rows($rs) == 0 ? null : \db_utils::fieldsMemory($rs, 0)->rh213_sequencial;
        $this->adicionarEvento($codigoFila);

        return true;
    }

    /**
     *
     *
     * @param integer $codigo
     */
    private function adicionarEvento($codigo = null)
    {
        $daoFilaEsocial = new \cl_esocialenvio();
        $daoFilaEsocial->rh213_sequencial = $codigo;
        $daoFilaEsocial->rh213_evento = $this->tipoEvento;
        $daoFilaEsocial->rh213_empregador = $this->empregador;
        $daoFilaEsocial->rh213_responsavelpreenchimento = $this->responsavelPreenchimento;
        $daoFilaEsocial->rh213_dados = pg_escape_string(json_encode(\DBString::utf8_encode_all($this->dado)));
        $daoFilaEsocial->rh213_md5 = $this->md5;
        $daoFilaEsocial->rh213_situacao = 1;

        if (empty($codigo)) {
            $daoFilaEsocial->incluir(null);
        } else {
            $daoFilaEsocial->alterar($codigo);
        }

        if ($daoFilaEsocial->erro_status == 0) {
            throw new \Exception("Não foi possível adicionar na fila.");
        }

        $this->adicionarTarefa($daoFilaEsocial->rh213_sequencial);
    }

    /**
     * Cria o job
     *
     * @param integer $idFila
     */
    private function adicionarTarefa($idFila)
    {
        $job = new \Job();
        $job->setNome("eSocial_Evento_" . $this->tipoEvento . "_$idFila");
        $job->setCodigoUsuario(1);
        $time = new \DateTime();
        $job->setMomentoCricao($time->modify('+ 1 minute')->getTimestamp());
        $job->setDescricao('Evento eSocial ' . $this->tipoEvento);
        $job->setNomeClasse('FilaESocialTask');
        $job->setTipoPeriodicidade(\Agenda::PERIODICIDADE_UNICA);
        $job->adicionarParametro("id_fila", $idFila);
        $job->setCaminhoPrograma('model/esocial/FilaESocialTask.model.php');
        $job->salvar();
    }
}
