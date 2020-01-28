<?php

require_once modification("model/configuracao/Task.model.php");
require_once modification("interfaces/iTarefa.interface.php");

use ECidade\Tributario\Integracao\Civitas\Service;
use ECidade\Tributario\Integracao\Civitas\Repository\Importador as ImportadorRepository;
use ECidade\Tributario\Integracao\Civitas\Model\Importador as ImportadorModel;

/**
 * Class CivitasTask
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class CivitasTask extends Task implements iTarefa
{
    /**
     * Inicia Execucao da Tarefa
     *
     * @return void
     */
    public function iniciar()
    {
        parent::iniciar();

        $parametros = $this->oTarefa->getParametros();

        try {

            /**
             * Variaveis necessarias para usar as bibliotecas padroes
             */
            global $HTTP_SERVER_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS, $_SESSION, $conn;
            $HTTP_SERVER_VARS = $_SESSION;
            $HTTP_POST_VARS = $_POST;
            $HTTP_GET_VARS = $_GET;

            require_once modification("libs/db_conn.php");
            require_once modification("libs/db_stdlib.php");
            require_once modification("libs/db_utils.php");
            require_once "libs/db_autoload.php";
            require_once modification("dbforms/db_funcoes.php");

            /**
             * Conecta no banco com variaveis definidas no 'libs/db_conn.php'
             */
            if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
                throw new Exception('Erro ao conectar ao banco.');
            }

            /**
             * Desativa log de alteracoes nas classes de dao
             */
            db_putsession('DB_desativar_account', true);
            db_putsession('DB_datausu', date('Y-m-d'));
            db_putsession('DB_acessado', "1325613");
            db_putsession('DB_anousu', date("Y"));
            db_putsession('DB_id_usuario', '1');

            db_inicio_transacao();

            $arquivos = array();
            $arquivos[] = array(
              "Nome" => $parametros['arquivoLotes'],
              "TipoArquivo" => ImportadorModel::ARQUIVO_LOTES,
              "Data" => $parametros['data'],
              "Caminho" => Service::FILE_PATH
            );

            $arquivos[] = array(
              "Nome" => $parametros['arquivoEdificacoes'],
              "TipoArquivo" => ImportadorModel::ARQUIVO_EDIFICACOES,
              "Data" => $parametros['data'],
              "Caminho" => Service::FILE_PATH
            );

            $arquivos[] = array(
              "Nome" => $parametros['arquivoTestadas'],
              "TipoArquivo" => ImportadorModel::ARQUIVO_TESTADAS,
              "Data" => $parametros['data'],
              "Caminho" => Service::FILE_PATH
            );

            $oImportador = ImportadorRepository::getImportador($arquivos);
            $oImportador->processar();

            $situacao = ImportadorRepository::CODIGO_SUCESSO;

            db_fim_transacao();

        } catch (Exception $oErro) {

            db_fim_transacao(true);
            $situacao = ImportadorRepository::CODIGO_ERRO;
            $this->log("Erro na execução:\n{$oErro->getMessage()}");
        }

        try{

            db_inicio_transacao();
            ImportadorRepository::atualizarSituacao($situacao, $parametros['sequencialRequisicao']);
            db_fim_transacao();
        } catch (Exception $oErro) {

            db_fim_transacao(true);
            $this->log("Erro na execução:\n{$oErro->getMessage()}");
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
