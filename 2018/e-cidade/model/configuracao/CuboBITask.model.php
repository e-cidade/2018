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

require_once(modification('model/configuracao/Task.model.php'));
require_once(modification('interfaces/iTarefa.interface.php'));
require_once(modification('classes/db_db_relatorio_classe.php'));
require_once(modification("dbagata/classes/core/AgataAPI.class"));
require_once(modification("libs/db_libsys.php"));
require_once(modification("model/dbColunaRelatorio.php"));
require_once(modification("model/dbFiltroRelatorio.php"));
require_once(modification("model/dbVariaveisRelatorio.php"));
require_once(modification("model/dbGeradorRelatorio.model.php"));
require_once(modification("model/dbOrdemRelatorio.model.php"));
require_once(modification("model/dbPropriedadeRelatorio.php"));
require_once(modification("model/dbPropriedadeRelatorio.php"));
require_once(modification("std/DBFtp.model.php"));
require_once(modification("std/DBString.php"));

class CuboBITask extends Task implements iTarefa {

  /**
   * Inicia Execucao da Tarefa
   */
  public function iniciar() {

    parent::iniciar();

    /* Carrega os dados de conexão com o banco de dados */
    require_once(modification('libs/db_conn.php'));
    /* Variaveis de sessão e outras configurações */
    require_once(modification('libs/db_cubo_bi_config.php'));

    $sConnection = "host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA";
    global $conn;
    $conn = pg_connect($sConnection);
    db_inicio_transacao();

    try {

      $aParametros     = $this->getTarefa()->getParametros();
      $iCodRelatorio   = $aParametros['iCubo'];

      /**
       * Busca o nome do relatorio
       */
      $oDao              = new cl_db_relatorio();
      $sSqlNomeRelatorio = $oDao->sql_query_file($iCodRelatorio, "db63_nomerelatorio");
      $rsNomeRelatorio   = db_query($sSqlNomeRelatorio);
      $sNomeRelatorio    = db_utils::fieldsMemory($rsNomeRelatorio, 0)->db63_nomerelatorio;

      $sNomeRelatorio = DBString::removerCaracteresEspeciais(DBString::removerAcentuacao($sNomeRelatorio));
      $sNomeRelatorio = str_replace(" ", "_", $sNomeRelatorio);

      // gera relatorio pelo agata
      $oRelatorio      = new dbGeradorRelatorio($iCodRelatorio);
      $sCaminhoArquivo = $_SESSION['DB_document_root'] . "/" . $oRelatorio->gerarRelatorio($sNomeRelatorio);

      /**
       * As primeira e segunda linha do arquivo esta sendo gerada em branca
       * Deletamos a primeira e terceira linha do arquivo
       */
      $this->limpezaArquivo($sCaminhoArquivo);
      
      $sNomeArquivoNoServidor = "{$sNomeRelatorio}.csv";
      if ( file_exists($sCaminhoArquivo) ) {

        $oFtp            = new DBFtp();
        $oFtp->setFtpServer( $configCuboBi['ftp']['server'] );
        $oFtp->setFtpUsuario( $configCuboBi['ftp']['usuario'] );
        $oFtp->setFtpSenha( $configCuboBi['ftp']['senha'] );
        $oFtp->setNome( $sNomeArquivoNoServidor );
        $oFtp->setPassiveMode( $configCuboBi['ftp']['passive_mode'] );
        $oFtp->setCaminhoArquivo( $sCaminhoArquivo );
        $oFtp->acessarDiretorio( $configCuboBi['ftp']['diretorio'] );
        $oFtp->acessarDiretorio( $sNomeRelatorio );

        if ( !$oFtp->enviarArquivo() ) {
          $this->log("Ocorreu um erro ao transmitir o arquivo {$sNomeRelatorio} para o servidor FTP.");
        }

        $oFtp->desconectar(true);

      } else {
        $this->log("Ocorreu um erro ao gerar o arquivo {$sNomeRelatorio}.");
      }

      db_fim_transacao(false);
    } catch (Exception $oErro ) {

      $this->log($oErro->getMessage());
      db_fim_transacao(true);
    }

    parent::terminar();
  }

  /**
   * Para execução da Tarefa
   */
  public function cancelar(){

  }

  /**
   * Aborta a Execução da Tarefa
   */
  public function abortar(){

  }

  /**
   * Abre o arquivo para leitura e remove as primeira e terceira linha
   */
  private function limpezaArquivo($filepath) {
    
    $lines = array();
    $lineCount = 0;

    // le o conteudo do arquivo antes
    $handler = fopen($filepath, 'r');
    while( ($buffer = fgets($handler)) !== false ){

      if ($lineCount === 0 || $lineCount === 2){
        $lineCount++;
        continue;
      }
      $lines[] = $buffer;
      $lineCount++;
    }
    fclose($handler);

    // escreve o conteudo arquivo sem as linhas desnecessarias
    $handler = fopen($filepath, 'w');
    foreach ($lines as $line) {
      fwrite($handler, $line);
    }
    fclose($handler);
  }
}