<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

db_app::import('configuracao.inconsistencia.InconsistenciaDados');
db_app::import('configuracao.DBLog');

/**
 * Classe para processar dados inconsistentes
 *
 * @require db_utils
 * @require db_app
 * @require InconsistenciaDados
 *
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 * @version $Revision: 1.16 $
 */
class ProcessamentoInconsistencia {

  /**
   * Array com as inconsistencias para processar
   */
  public $aInconsistencias;

  /**
   * Array com todas as tabelas que possuem exceções para processamento das dependências
   * @var array
   */
  private $aExcecoesDependencias = array();

  /**
   * Instancia da classe de log
   *
   * @var mixed
   * @access private
   */
  private $oDBLog;

  /**
   * Nome do arquivo de log
   * @var string
   */
  protected $sArquivoLog = "";

  /**
   * Construtor da classe
   *
   * @access public
   * @return void
   */
  public function __construct() {

    /**
     * cria arquivo de log em txt
     */
    $this->sArquivoLog = 'tmp/erros_processar_registros_inconsistentes_' . date('d-m-Y');
    $this->oDBLog      = new DBLog('TXT', $this->sArquivoLog );
  }

  /**
   * Processa inconsistencias
   *
   * @access public
   * @return void
   */
  public function processar() {

    $lErro = false;
    $this->oDBLog->escreverLog( str_pad("INICIANDO PROCESSAMENTO",130,"-", STR_PAD_BOTH) );
    $this->oDBLog->escreverLog( "" );
    $this->oDBLog->escreverLog( "" );
    $this->oDBLog->escreverLog( "" );
    $this->oDBLog->escreverLog( "" );
    /**
     * Verifica se existe transacao com banco
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception('Nenhuma transação com o banco de dados definida.');
    }

    /**
     * Apenas da include na DAO
     */
    db_utils::getDao('db_registrosinconsistentes', false);

    /**
     * Percorre todas as inconsistencias nao processadas
     */
    foreach( $this->getInconsistencias() as $oInconsistenciaDados ) {

      $this->oDBLog->escreverLog( str_pad("PROCESSANDO INCONSISTENCIA: {$oInconsistenciaDados->getCodigo()}.",130,"-", STR_PAD_BOTH) );
      /**
       * Nome do savepoint
       */
      $sSavePoint  = "savepoint_registros_inconsistentes_" . $oInconsistenciaDados->getCodigo();

      /**
       * cria savepoint
       */
      @db_query("SAVEPOINT {$sSavePoint}_geral;");

      /**
       * Instancia dao dos registros inconsistentes e busca os seus dados
       */
      $oDaoDb_registrosinconsistentes = new cl_db_registrosinconsistentes();
      $sSqlIncosistencias             = $oDaoDb_registrosinconsistentes->sql_query_inconsistencias($oInconsistenciaDados->getCodigo());
      $rsInconsistencias              = @db_query($sSqlIncosistencias);
      $oInconsistencia                = db_utils::fieldsMemory($rsInconsistencias, 0);

      /**
       * Instancia model de inconsistencias de dados
       * busca os registros inconsistentes e o registro correto
       */
      $aDadosInconsistentes = $oInconsistenciaDados->getDadosInconsistentes();
      $iRegistroCorreto     = $oInconsistenciaDados->getRegistroCorreto();

      /**
       * Variavel para contar quantos registros foram processados sem erro
       */
      $iTotalProcessados = 0;

      /**
       * Total de registros inconsistentes para processar
       */
      $iTotalInconsistencias = count($aDadosInconsistentes);

      /**
       * Array com os registros processados
       */
      $aRegistrosProcessados = array();

      /**
       * cria header da inconsistencia no arquivo de log
       */
      $this->oDBLog->escreverLog("");
      $this->oDBLog->escreverLog("Registros dependentes da tabela '{$oInconsistencia->tabela_inconsistente}' para processar: {$iTotalInconsistencias}");
      $this->oDBLog->escreverLog("");

      /**
       * Percorre todos os registros inconsitentes e atualiza as dependencias para o registro correto
       * e apos remove os registros incorretos
       *
       * @todo usar DAO das classes para ter account
       */
      $lErroInterno = false;

      foreach ( $aDadosInconsistentes as $oDependencia ) {

        @db_query("SAVEPOINT {$sSavePoint}_{$oDependencia->tabela};");

        $oProcessaDuplo = new ProcessaDuploPadrao($oDependencia->tabela, $oDependencia->campo);

        /**
         * Validamos se a tabela inconsistente, possui alguma Excessao
         * e Se a excessao foi a tabela ($oDependencia->tabela) atual do laço.
         */
        $mNomeClasseExcecao = $this->getExcecoes($oInconsistencia->tabela_inconsistente, $oDependencia->tabela);
        if ($mNomeClasseExcecao) {

          $oProcessaDuplo = new $mNomeClasseExcecao;
        }

        $lProcessou = $oProcessaDuplo->processar($iRegistroCorreto, $oDependencia->chave);

        if (!$lProcessou) {

          $this->log($oDependencia->tabela, $oProcessaDuplo->getMensagemErro());
          @db_query("ROLLBACK TO SAVEPOINT {$sSavePoint}_{$oDependencia->tabela};");
          $lErro        = true;
          $lErroInterno = true;
          continue;
        }

        /**
         * Dependencias ja corrigidas para depois deletadas
         */
        $aRegistrosProcessados[$oDependencia->chave] = $oDependencia;
      }

      if ( $lErroInterno ) {

        @db_query( "ROLLBACK TO SAVEPOINT {$sSavePoint}_geral" );
        continue;//return false; // Processou ?
      }

      /**
       * Deleta os registros inconsistentes apos ter alterado suas dependencias
       */
      foreach ( $aRegistrosProcessados as $oRegistroProcessado ) {

        /**
         * Deleta os registros incorretos
         */
        if ($oRegistroProcessado->excluir == 't') {

          $sSqlRegistrosIncorretos  = "delete from {$oInconsistencia->tabela_inconsistente}                   ";
          $sSqlRegistrosIncorretos .= " where {$oInconsistencia->campo_inconsitencia} = $oRegistroProcessado->chave  ";
          $rsRegistrosIncorretos    = @db_query($sSqlRegistrosIncorretos);
        }
        /**
         * Erro na query que remove as inconsitencias, grava log, retorna para o savepoint, e continua o foreach
         */
        if ( !$rsRegistrosIncorretos ) {

          $this->log($oInconsistencia->tabela_inconsistente, $sSqlRegistrosIncorretos);

          @db_query("ROLLBACK TO SAVEPOINT $sSavePoint");
          continue;
        }

        /**
         * Nao ocorreu erro, aumenta o contador de registros processados sem erro
         */
        $iTotalProcessados++;
      }

      /**
       * Escreve no arquivo de log quantidade de registros processados
       */
      $this->oDBLog->escreverLog("Registros dependentes processados: {$iTotalProcessados}");
      $this->oDBLog->escreverLog("");

      /**
       * Caso processe uma ou todas as inconsitencias atualiza header das inconsitencias como processado true
       */
      if ( $iTotalProcessados > 0 ) {

        $oDaoDb_registrosinconsistentes->db136_sequencial = $oInconsistencia->codigo_inconsistencia;
        $oDaoDb_registrosinconsistentes->db136_processado = 'true';
        $oDaoDb_registrosinconsistentes->alterar($oInconsistencia->codigo_inconsistencia);
      }
    }
   return !$lErro;
  }

  /**
   * Escreve log com as informacoes de erro
   *
   * @param string $sTabela       - tabela que gerou erro
   * @param string $sSqlExecutado - sql que gerou erro
   * @access public
   * @return void
   */
  public function log($sTabela, $sSqlExecutado) {

    /**
     * remove espacos, tabs e linhas
     */
    $sSqlExecutado = preg_replace('/\s(?=\s)/', '', trim($sSqlExecutado));
    $sErroBanco    = preg_replace('/[\n\r\t]/', ' ', preg_replace('/\s(?=\s)/', '', trim(pg_last_error())));

    $this->oDBLog->escreverLog("Erro ao atualizar tabela '{$sTabela}'", 2);
    $this->oDBLog->escreverLog("Sql executado: {$sSqlExecutado}", 2);
    $this->oDBLog->escreverLog("Erro do banco: {$sErroBanco}", 2);
    $this->oDBLog->escreverLog('');
  }

  /**
   * Retorna um array com todas as inconsistencias nao processadas, o header das inconsistencias
   *
   * @access public
   * @return Array
   */
  public function getInconsistencias() { return $this->aInconsistencias; }

  /**
   * Adicionar Inconsistencia
   *
   * @param InconsistenciaDados $oInconsistencia
   * @access public
   * @return bool
   */
  public function adicionarInconsistencia( InconsistenciaDados $oInconsistencia ) {

    $this->aInconsistencias[] = $oInconsistencia;
    return true;
  }

  /**
   * Percorre um XML onde esta mapeado as excessões encontradas ao processar registros duplos.
   * Monta um array com a seguinte estrutura:
   * $aExcecoesDependencias[<tabela>] = stdClass: nomeTabelaFilha, classeASerExecutada
   *
   * @throws FileException
   */
  private function processaArquivoExcecoesDuplo() {

    $sArquivo = "config/processamentoduplos/mapa_dependencias.xml";
    if (!file_exists($sArquivo)) {
      throw new FileException("Arquivo: mapa_dependencias.xml não encontrado.");
    }

    if (!is_readable($sArquivo)) {
      throw new FileException("Arquivo: mapa_dependencias.xml não tem permissão de leitura.");
    }

    $oArquivo = new DOMDocument();
    /**
     * Carregamos o arquivo xml
     */
    $oArquivo->load($sArquivo);
    /**
     * Pegamos todos os nodes "tabela"
     */
    $aTabelas = $oArquivo->getElementsByTagName('tabela');
    foreach ($aTabelas as $iTabela => $oNodeTabela) {

      /**
       * Bucamos todos nodes "dependencia" filho do node "tabela"
       */
      $aDependencias = $oNodeTabela->getElementsByTagName('dependencia');

      foreach ($aDependencias as $iDependencia => $oNodeDependencia) {

        $oDependencia         = new stdClass();
        $oDependencia->tabela = $oNodeDependencia->getAttribute('nome');
        $oDependencia->classe = $oNodeDependencia->getAttribute('classe');

        $this->aExcecoesDependencias[$oNodeTabela->getAttribute('nome')][] = $oDependencia;
      }
    }
  }

  /**
   * Retorna um array com as Tabelas que possuem excessões ao serem processadas como duplos
   * @return mixed | boolean | string
   */
  private function getExcecoes($sTabela, $sTabelaDepencia) {

    if(count($this->aExcecoesDependencias) == 0) {
      $this->processaArquivoExcecoesDuplo();
    }

    if (array_key_exists($sTabela, $this->aExcecoesDependencias)) {

      foreach ($this->aExcecoesDependencias[$sTabela] as $oTabelaExcecao) {

        if($oTabelaExcecao->tabela == $sTabelaDepencia) {

          return $oTabelaExcecao->classe;
        }
      }
    }

    return false;
  }

  /**
   * Retorna nome do arquivo de log
   * @return string
   */
  public function getNomeArquivoLog() {
    return $this->sArquivoLog;
  }

}