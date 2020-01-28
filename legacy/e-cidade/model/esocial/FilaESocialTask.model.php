<?php

use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use \ECidade\V3\Extension\Registry;

require_once modification("interfaces/iTarefa.interface.php");

class FilaESocialTask extends Task implements iTarefa
{
    public function iniciar()
    {
        parent::iniciar();

        if (!isset($_SESSION)) {
            $_SESSION = array();
        }

        $_SESSION['DB_desativar_account'] = true;

        require_once modification("libs/db_conn.php");
        require_once modification("libs/db_stdlib.php");
        require_once modification("libs/db_utils.php");
        require_once modification("dbforms/db_funcoes.php");

        try {

            /**
             * Conecta no banco com variaveis definidas no 'libs/db_conn.php'
             */
            if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
                throw new Exception('Erro ao conectar ao banco.');
            }

            $parametros = $this->oTarefa->getParametros();

            $dao = new \cl_esocialenvio();
            $sql = $dao->sql_query_file($parametros['id_fila']);
            $rs  = \db_query($sql);

            if (!$rs && pg_num_rows($rs) == 0) {
                throw new \Exception('Agendamento nao encontrado.');
            }
            $dadosEnvio = \db_utils::fieldsMemory($rs, 0);
            $dados = array(json_decode($dadosEnvio->rh213_dados));

            $sRecurso = Recurso::getRecursoByEvento($dadosEnvio->rh213_evento);
            $exportar = new ESocial(Registry::get('app.config'), $sRecurso);
            $exportar->setDados($dados);
            $retorno = $exportar->request();

            $dao->rh213_situacao = 2;
            $dao->rh213_sequencial = $parametros['id_fila'];
            $dao->alterar($parametros['id_fila']);

            if ($dao->erro_status == 0) {
                throw new \Exception("Não foi possível alterar situação da fila.");
            }
        } catch (\Exception $e) {
            $this->log("Erro na execução:\n{$e->getMessage()} - ");
        }
        parent::terminar();
    }

    public function cancelar()
    {
    }

    public function abortar()
    {
    }
}
