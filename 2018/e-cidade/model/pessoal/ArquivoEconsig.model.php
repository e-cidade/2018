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

require_once("fpdf151/pdfnovo.php");

class ArquivoEconsig {

  private $oDbLog;

  private $lErro = false;

  private $iInstit;

  private $sArquivoLog;

  const MENSAGENS = 'recursoshumanos.pessoal.ArquivoEconsig.';

  public function __construct($iInstit) {
    
    $this->iInstit = $iInstit;
    $this->sArquivoLog = "tmp/inconsistencias_econsig_" . db_anofolha() . "_" . db_mesfolha() . $iInstit . ".json";

    require_once("libs/JSON.php");
    require_once("model/configuracao/DBLogJSON.model.php");
    $this->oDbLog = new DBLogJSON($this->sArquivoLog);
  }

  /**
   * Método responsável por importar o arquivo que
   * o e-consig retorna para o ecidade, para que seja descontado 
   * em outra rotina as rubricas do servidor
   * @param  string $sCaminhoArquivo Caminho do arquivo que foi feito upload
   */
  public function importarArquivoMovimento($sCaminhoArquivo) {

    $oDaoEconsigMovimento                = db_utils::getDao("econsigmovimento");
    $oDaoEconsigMovimentoServidor        = db_utils::getDao("econsigmovimentoservidor");
    $oDaoEconsigMovimentoServidorRubrica = db_utils::getDao("econsigmovimentoservidorrubrica");


    $aServidoresCadastrados = array();

    $sExtension = pathinfo($sCaminhoArquivo, PATHINFO_EXTENSION); 

    if (strtolower($sExtension) != "txt") {
      throw new Exception(_M(self::MENSAGENS . "extensao_invalida"));
    }

    $aConteudoArquivo = file($sCaminhoArquivo);

    if ($aConteudoArquivo === false) {
      throw new ParameterException(_M(self::MENSAGENS . "arquivo_invalido"));
    }

    if (empty($aConteudoArquivo)) {
      throw new Exception(_M(self::MENSAGENS . "arquivo_vazio"));
    }

    $oCabecalho = $this->parseMovimentacaoCabecalho($aConteudoArquivo[0]);

    /**
     * Caso o cabeçalho esteja invalido, lança exception
     */
    $this->validaCabecalho($oCabecalho);
    
    /**
     * Apaga os dados das tabelas da econsig pela competência
     */
    $this->apagaTabelaImportacao(new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()));

    /**
     * Salva o arquivo e seus dados no banco
     */
    $oDaoEconsigMovimento->rh133_ano         = $oCabecalho->iAno;
    $oDaoEconsigMovimento->rh133_mes         = $oCabecalho->iMes;
    $oDaoEconsigMovimento->rh133_nomearquivo = end(explode("/", $sCaminhoArquivo));
    $oDaoEconsigMovimento->rh133_instit      = $oCabecalho->iInstit;

    $oDaoEconsigMovimento->incluir(null);
    if ($oDaoEconsigMovimento->erro_status == "0") {
      throw new DBException($oDaoEconsigMovimento->erro_msg);
    }

    /**
     * Pega o id do novo registro para repassar ao filhos
     */
    $iCodigoEconsig = $oDaoEconsigMovimento->rh133_sequencial;

    unset($aConteudoArquivo[0]);

    foreach ($aConteudoArquivo as $iLinha => $sLinha) {

      $sLinha = trim($sLinha);

      if (empty($sLinha)) {
        throw new DBException( _M(self::MENSAGENS . "conteudo_invalido") );
      }
      
      $oServidor = $this->parseMovimentacaoServidor($sLinha);

      /**
       * Objeto padrao para escrita do log
       */
      $oMensagem = new stdClass();
      $oMensagem->iMatricula = $oServidor->iMatricula;
      $oMensagem->sNome      = utf8_encode($oServidor->sNome);
      $oMensagem->sMotivo    = null;
      $oMensagem->iLinha     = $iLinha;

      try {
        $this->validaServidor($oServidor);
      } catch(Exception $e) {
 
        $oMensagem->sMotivo = utf8_encode($e->getMessage());
        $this->log($oMensagem);
        $this->lErro = true;
        continue;
      }
        
      if ( !isset($aServidoresCadastrados[$oServidor->iMatricula] ) ) {

        /**
         * Insere o servidor na tabela de servidor do econsig
         */
        $oDaoEconsigMovimentoServidor->rh134_sequencial       = null;
        $oDaoEconsigMovimentoServidor->rh134_econsigmovimento = $iCodigoEconsig;
        $oDaoEconsigMovimentoServidor->rh134_regist           = $oServidor->iMatricula;

        $oDaoEconsigMovimentoServidor->incluir(null);
        if ($oDaoEconsigMovimentoServidor->erro_status == "0") {
          throw new DBException(_M(self::MENSAGENS . "erro_banco_inserir_servidor"));
        }

        /**
         * Adiciona do sequencial da tabela no array de acesso rapido
         */
        $aServidoresCadastrados[$oServidor->iMatricula] = $oDaoEconsigMovimentoServidor->rh134_sequencial;
      }

      /**
       * Pega o código do servidor no array de acesso rapido dos servidores.
       */
      $iCodigoEconsigServidor = $aServidoresCadastrados[$oServidor->iMatricula];

      $oRubrica = $this->parseMovimentacaoRubrica($sLinha);

      try {
        $this->validaRubrica($oRubrica, $oCabecalho->iInstit);
      } catch (Exception $e) {

        $oMensagem->sMotivo = utf8_encode($e->getMessage());
        $this->log($oMensagem);
        $this->lErro = true;
        continue;
      }

      if (!is_numeric($oRubrica->fValor)) {

        $oMensagem->sMotivo = utf8_encode( _M(self::MENSAGENS . 'valor_invalido'));
        $this->log($oMensagem);
        $this->lErro = true;
        continue;
      }

      /**
       * Salva as rubricas de desconto na tabela de servidor do econsig
       */
      $oDaoEconsigMovimentoServidorRubrica->rh135_sequencial               = null;
      $oDaoEconsigMovimentoServidorRubrica->rh135_econsigmovimentoservidor = $iCodigoEconsigServidor;
      $oDaoEconsigMovimentoServidorRubrica->rh135_rubrica                  = $oRubrica->sRubrica;
      $oDaoEconsigMovimentoServidorRubrica->rh135_valor                    = $oRubrica->fValor;
      $oDaoEconsigMovimentoServidorRubrica->rh135_instit                   = $oCabecalho->iInstit;

      $oDaoEconsigMovimentoServidorRubrica->incluir(null);

      if ($oDaoEconsigMovimentoServidorRubrica->erro_status == "0") {
        throw new Exception(_M(self::MENSAGENS . "erro_banco_inserir_rubrica"));
      }
    }

    return !$this->lErro;
  }

  /**
   * Transforma a linha do cabeçalho em um objeto.
   * @param  string $sLinha Linha do cabeçalho
   * @return Object
   */
  private function parseMovimentacaoCabecalho($sLinha) {

    $oCabecalho = new stdClass();
    $oCabecalho->iAno    = substr($sLinha, 0, 4);
    $oCabecalho->iMes    = substr($sLinha, 4, 2);
    $oCabecalho->iInstit = substr($sLinha, 6, 3);

    return $oCabecalho;
  }

  /**
   * Transforma a linha do registro em um objeto com os dados do servidor.
   * @param  string $sLinha Linha do registro
   * @return Object
   */
  private function parseMovimentacaoServidor($sLinha) {

    $oServidor = new stdClass();

    $oServidor->iMatricula = substr($sLinha, 0, 10);
    $oServidor->sNome      = substr($sLinha, 10, 40);

    return $oServidor;
  }

  /**
   * Valida o cabeçalho do arquivo.
   *   - Valida se a competência do arquivo é a mesma da competência atual da folha
   *   - Valida se a instituição é a mesma instituição onde o arquivo está sendo importado.
   * @param  object $oCabecalho
   * @return boolean 
   */
  private function validaCabecalho($oCabecalho) {
    
    $iAnoFolha    = DBPessoal::getAnoFolha();
    $iMesFolha    = DBPessoal::getMesFolha();
    $iInstituicao = str_pad(db_getsession("DB_instit"), 3, 0, STR_PAD_LEFT);

    if ($oCabecalho->iAno != $iAnoFolha || $oCabecalho->iMes != $iMesFolha) {
      throw new BusinessException(_M(self::MENSAGENS . 'competencia_invalida'));
    }

    if ($oCabecalho->iInstit != $iInstituicao){
      throw new BusinessException(_M(self::MENSAGENS . 'instituicao_invalida'));
    }

    return true;
  }

  /**
   * Valida se o Servidor é válido.
   * - Valida se é possível instânciar a classe Servidor com a matrícula informada.
   * - Valida se o nome é compativel com a matricula.
   * @param  object $oDadosServidor
   * @return boolean
   */
  private function validaServidor($oDadosServidor) {
    
    $oServidor = new Servidor((int) $oDadosServidor->iMatricula);

    if ($oServidor->getCgm()->getNome() != trim($oDadosServidor->sNome)) {
      throw new BusinessException(_M(self::MENSAGENS . 'nome_invalido'));
    }

    return true;
  }

  /**
   * Valida a Rubrica.
   *    - Valida se a rubrica existe no e-cidade
   *
   * @param Object  $oRubrica
   * @param Integer $iInstituicao
   */
  private function validaRubrica($oRubrica, $iInstituicao) {

    $oDaoRubrica = db_utils::getDao("rhrubricas");

    $sSql = $oDaoRubrica->sql_query_file($oRubrica->sRubrica, $iInstituicao, "*");
    $oDaoRubrica->sql_record( $sSql );

    if (!$oDaoRubrica->numrows) {
      throw new BusinessException( _M(self::MENSAGENS . "rubrica_invalida", $oRubrica) );
    }

    return false;
  }


  /**
   * Transforma a linha do registro em um objeto com os dados da rubrica do servidor.
   * @param  string $sLinha Linha do registro
   * @return Object
   */
  private function parseMovimentacaoRubrica($sLinha) {

    $oRubrica = new stdClass();
    $oRubrica->sRubrica = substr($sLinha, 50, 4);
    $oRubrica->fValor = substr($sLinha, 54, 10);

    return $oRubrica;
  }

  /**
   * Escreve o log no arquivo definido no construtor
   * @param  Object $oMensagem Objeto padrao para a escrita do log.
   */
  private function log($oMensagem) {
    $this->lErro = true;
    $this->oDbLog->log($oMensagem, DBLog::LOG_ERROR);
  }

  /**
   * Apagar toda a tabela do econsig e suas dependencias
   * 
   * @param DBCompetencia $oCompetencia
   */
  private function apagaTabelaImportacao(DBCompetencia $oCompetencia = null) {

    if (is_null($oCompetencia)) {
      $oCompetencia = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
    }

    $sSql = "
      DELETE
        FROM econsigmovimentoservidorrubrica
       WHERE rh135_econsigmovimentoservidor IN (
         SELECT DISTINCT rh135_econsigmovimentoservidor
           FROM econsigmovimento
                INNER JOIN econsigmovimentoservidor ON rh133_sequencial = rh134_econsigmovimento
                INNER JOIN econsigmovimentoservidorrubrica ON rh134_sequencial = rh135_econsigmovimentoservidor
          WHERE rh133_ano    = {$oCompetencia->getAno()}
            AND rh133_mes    = {$oCompetencia->getMes()}
            AND rh133_instit = {$this->iInstit}
       )
    ";

    $rsResult = db_query($sSql);
    if (!is_resource($rsResult)) {
      throw new DBException("Problema ao deletar as rubricas.");
    }     

    $sSql = "
      DELETE
        FROM econsigmovimentoservidor
       WHERE rh134_econsigmovimento IN (
         SELECT DISTINCT rh134_econsigmovimento
           FROM econsigmovimento
                INNER JOIN econsigmovimentoservidor ON rh133_sequencial = rh134_econsigmovimento
          WHERE rh133_ano    = {$oCompetencia->getAno()}
            AND rh133_mes    = {$oCompetencia->getMes()}
            AND rh133_instit = {$this->iInstit}
       )
    ";

    $rsResult = db_query($sSql);
    if (!is_resource($rsResult)) {
      throw new DBException("Problema ao deletar os dados do servidor.");
    } 

    $sSql = "
      DELETE
        FROM econsigmovimento
       WHERE rh133_ano    = {$oCompetencia->getAno()}
         AND rh133_mes    = {$oCompetencia->getMes()}
         AND rh133_instit = {$this->iInstit}
    ";

    $rsResult = db_query($sSql);
    if (!is_resource($rsResult)) {
      throw new DBException("Problema ao deletar um movimento para a competência atual.");
    }
  }
  
  /**
   * Imprime o relatório com as incositências encontradas na importaçõa do arquivo.
   * @return String caminho do PDF.
   */
  public function imprimeRelatorio() {
    
    $this->oDbLog->finalizarLog();

    $sLog = $this->oDbLog->getConteudo($this->sArquivoLog);
    
    $oJson       = new Services_JSON();
    $oMatriculas = $oJson->decode($sLog);
    
    /**
     * Gera o PDF
     */
    $oPdf = new PDFNovo();

    $oPdf->addHeader(' ');
    $oPdf->addHeader(' ');
    $oPdf->addHeader('Relatório de Inconsistências E-Consig.');
    $oPdf->addHeader('Competência: ' . DBPessoal::getAnoFolha() . '/' . DBPessoal::getMesFolha());


    $oPdf->addTableHeader('Matrícula'       , 20, 4, 'C');
    $oPdf->addTableHeader('Nome'            , 75, 4, 'C');
    $oPdf->addTableHeader('Motivo'          , 75, 4, 'C');
    $oPdf->addTableHeader('Linha do Arquivo', 22, 4, 'C');
    
    $oPdf->Open();
    $oPdf->AliasNbPages();
    $oPdf->AddPage("p");
    $oPdf->SetFillColor(235);
    $lBackground = true;
    $iContador   = 0;

    foreach ($oMatriculas->aLogs as $oMatricula) {

      $lBackground = ($iContador % 2 == 0);
      
      $oPdf->Cell(20, 4, $oMatricula->iMatricula          , true, 0, 'C', $lBackground);
      $oPdf->Cell(75, 4, utf8_decode($oMatricula->sNome)  , true, 0, 'C', $lBackground);
      $oPdf->Cell(75, 4, utf8_decode($oMatricula->sMotivo), true, 0, 'C', $lBackground);
      $oPdf->Cell(22, 4, $oMatricula->iLinha              , true, 1, 'C', $lBackground);
      $iContador++;
    }

    $sCaminho = "tmp/Inconsistencias_econsig.pdf";
    $oPdf->Output($sCaminho, false, true);

    return $sCaminho;
  }
}