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

/**
 * Classe para processamento do arquivo e-consig
 * 
 */
class ProcessamentoArquivoEConsig {
    
  const MENSAGEM = 'recursoshumanos.pessoal.ProcessamentoArquivoEConsig.';

  /**
   * Objeto de escrita de Log
   * @var DBLog
   */
  private $oLog;
   /**
   * Objeto de escrita de Log de Modificacoes
   * @var DBLog
   */
  private $oLogModificacoes;
  /**
   * Arquivo com os dados de descontos consignados
   * @var resource
   */
  private $rArquivo;

  /**
   * Competência do Arquivo
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Instituicao do arquivo
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Caminho do arquivo a ser importado
   * @var String $sCaminhoArqwuivo
   */
  private $sCaminhoArquivo;


  /**
   * Objeto do arquivo econsig
   * @var ArquivoEconsig
   */
  private $oArquivoEConsig;

  /**
   * Caminho do arquivo de inconsistências
   * @var String
   */
  private $sCaminhoArquivoInconsistencias;

  /**
   * Construtor da classe
   * 
   * @param String        $sCaminhoArquvo    Arquivo com os dados para importação
   * @param DBCompetencia $oCompetencia Competência a ser importada
   */
  public function __construct($sCaminhoArquivo, DBCompetencia $oCompetencia, Instituicao $oInstituicao) {
    
    $this->oInstituicao    = $oInstituicao;
    $this->sCaminhoArquivo = $sCaminhoArquivo;
    $this->rArquivo        = fopen($sCaminhoArquivo, 'r');
    $this->oCompetencia    = $oCompetencia;
    $this->validarArquivo();
  }

   /**
   * Verifica existência do registro no ultimo arquivo importado.
   * @param  stdClass $oRegistro dados do registro que vai ser pesquisado.
   * @return array - Caso seja encontrado mais de um registro, retorna ambos.
   */
  private function getUltimoRegistro(stdClass $oRegistro) {

    $aRetorno             = array();
    $iMatricula           = (int)$oRegistro->iMatricula;
    $oDaoEConsigMovimento = new cl_econsigmovimento;
    $sSql                 = $oDaoEConsigMovimento->sql_pesquisa_servidor(
                                                                          $iMatricula, 
                                                                          $this->oCompetencia->getAno(), 
                                                                          $this->oCompetencia->getMes(), 
                                                                          $this->oInstituicao->getSequencial()
                                                                        );

    $rsSql                = db_query($sSql);
    $lTemArquivo          = ArquivoEConsigRepository::hasArquivoCompetencia($this->oCompetencia, $this->oInstituicao);

    if(pg_num_rows($rsSql) ==  0) {
      
      if (!$lTemArquivo) {
        return;
      } else {

        try {
          $oServidor = ServidorRepository::getInstanciaByCodigo(
                                                                 $oRegistro->iMatricula, 
                                                                 $this->oCompetencia->getAno(), 
                                                                 $this->oCompetencia->getMes(), 
                                                                 $this->oInstituicao->getSequencial() 
                                                               );

          $this->logModificacoes($oRegistro->iLinha,$iMatricula,$oRegistro->sNome, _M(self::MENSAGEM  . 'servidor_adicionado')); 
          return;
         } catch ( BusinessException $eException ) {
           return;
         }
      }
    }

    if (pg_num_rows($rsSql) > 0) {
      
      for ($iRegistro =0; $iRegistro < pg_num_rows($rsSql) ; $iRegistro++) {

        $oRegistro                  = db_utils::fieldsMemory($rsSql, $iRegistro); 
        $oUltimoRegistro            = new stdClass();
        $oUltimoRegistro->matricula = (int)$iMatricula;
        $oUltimoRegistro->rubrica   = $oRegistro->rubrica;
        $oUltimoRegistro->valor     = $oRegistro->valor;
        $aRetorno[]                 = $oUltimoRegistro;
      }
    }
    return $aRetorno;
  }

  /**
   * [getUltimosRegistrosArquivo description]
   * @return [type] [description]
   */
  private function getUltimosRegistrosArquivo() {

    $aRetorno             = array();
    $oDaoEConsigMovimento = new cl_econsigmovimento;
    $sSql                 = $oDaoEConsigMovimento->sql_pesquisa_servidor(
                                                                          null, 
                                                                          $this->oCompetencia->getAno(), 
                                                                          $this->oCompetencia->getMes(), 
                                                                          $this->oInstituicao->getSequencial()
                                                                        );
    $rsSql                = db_query($sSql);

    if (pg_num_rows($rsSql) > 0) {
      
      for ($iRegistro =0; $iRegistro < pg_num_rows($rsSql) ; $iRegistro++) {

        $oRegistro                       = db_utils::fieldsMemory($rsSql, $iRegistro); 
        $aRetorno[$oRegistro->matricula] = $oRegistro->nome;
      }
    }
    return $aRetorno;
  }

  /**
   * Define Caminho do Arquivo
   * @param String
   */
  public function setCaminhoArquivo ($sCaminhoArquivo) {
    $this->sCaminhoArquivo = $sCaminhoArquivo;
  }
  
  /**
   * Retorna Caminho do Arquivo
   * @return String
   */
  public function getCaminhoArquivo () {
    return $this->sCaminhoArquivo; 
  }

  /**
   * Define Caminho Inconsistencias
   * @param String
   */
  public function setCaminhoArquivoInconsistencias ($sCaminhoArquivoInconsistencias) {
    $this->sCaminhoArquivoInconsistencias = $sCaminhoArquivoInconsistencias;
  }
  
  /**
   * Retorna Caminho Inconsistencias
   * @return String
   */
  public function getCaminhoArquivoInconsistencias () {
    return $this->sCaminhoArquivoInconsistencias; 
  }

  /**
   * Executa validações gerais sobre o arquivo, como nome
   * @return void
   */
  private function validarArquivo(){

    $this->validarNome();
    $this->validarCabecalho($this->montarCabecalho());
   
    return;
  }

  /**
   * Valida  se o nome do arquivo é válido, [anousu][mesusu][instituicao]
   *
   * @param object $oCabecalho
   * @return bool
   * @throws BusinessException
   */
  private function validarCabecalho($oCabecalho) {

    if ($oCabecalho->iAno != $this->oCompetencia->getAno()) {
      throw new BusinessException( _M(self::MENSAGEM . 'ano_arquivo_invalido') );
    }

    if ($oCabecalho->iMes != $this->oCompetencia->getMes()) {
      throw new BusinessException( _M(self::MENSAGEM . 'mes_arquivo_invalido') );
    }

    if ($oCabecalho->iInstituicao != $this->oInstituicao->getSequencial()) {
      throw new BusinessException( _M(self::MENSAGEM . 'instituicao_arquivo_invalido') );
    } 

    return true;
  }

  /**
   * Valida  se o nome do arquivo é válido, econsig_[anousu]_[mesusu]_[instituicao].txt
   *
   * @return bool
   * @throws BusinessException
   * @throws Exception
   */
  private function validarNome(){

    $sNomeArquivo = basename($this->sCaminhoArquivo);

    if (!preg_match('/.*\.txt$/i', $sNomeArquivo)) {
      throw new Exception( _M(self::MENSAGEM . 'extensao_invalida') );
    }

    if (!preg_match('/econsig_(\d{4})_(\d{2})_(\d{3})\.txt/i', $sNomeArquivo)) {
      throw new Exception( _M(self::MENSAGEM . 'nome_invalido') );
    }

    $iAnoArquivo         = substr($sNomeArquivo, 8, 4);
    $iMesArquivo         = substr($sNomeArquivo, 13, 2);
    $iInstituicaoArquivo = substr($sNomeArquivo, 16, 3);

    if ( ($iAnoArquivo != $this->oCompetencia->getAno()) || ($iMesArquivo != $this->oCompetencia->getMes()) ) {
      throw new BusinessException( _M(self::MENSAGEM . 'nome_arquivo_invalido') );
    }

    if ($iInstituicaoArquivo != $this->oInstituicao->getSequencial()) {
      throw new BusinessException( _M(self::MENSAGEM . 'instituicao_nome_arquivo_invalido') );
    }

    return true;
  }

  /**
   * Valida se o registro do arquivo é válido.
   * 
   * @param stdClass $oRegistro com os dados do registro do arquivo.
   * @return boolean
   */
  private function validarRegistro(stdClass $oRegistro){

    try {

      $oServidor = ServidorRepository::getInstanciaByCodigo( $oRegistro->iMatricula, $this->oCompetencia->getAno(), $this->oCompetencia->getMes(), $this->oInstituicao->getSequencial());

      if (!$oServidor) {

        $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, _M(self::MENSAGEM  . 'servidor_invalido'));
        $oRegistro->iMotivo = ArquivoEConsig::MOTIVO_SERVIDOR_INVALIDO;
      }

      if ($oServidor && trim(DBString::removerAcentuacao($oServidor->getCgm()->getNome())) != trim($oRegistro->sNome))  {
  
        $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, _M(self::MENSAGEM  . 'nome_servidor_invalido'));
        $oRegistro->iMotivo = ArquivoEConsig::MOTIVO_SERVIDOR_INVALIDO;
      }
    } catch( BusinessException $eErro ) {

      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, $eErro->getMessage());
      $oRegistro->iMotivo = ArquivoEConsig::MOTIVO_SERVIDOR_INVALIDO;
    } catch ( Exception $eErro ) {

      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, $eErro->getMessage());
      $oRegistro->iMotivo = ArquivoEConsig::MOTIVO_SERVIDOR_INVALIDO;
    }

    try {
      $oRubrica = RubricaRepository::getInstanciaByCodigo($oRegistro->sRubrica, $this->oInstituicao->getSequencial());
    } catch( BusinessException $eErro ) {

      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, $eErro->getMessage());
      $oRegistro->iMotivo = ArquivoEConsig::MOTIVO_SERVIDOR_INVALIDO;
    } catch ( Exception $eErro ) {
      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, $eErro->getMessage());
    }
    
    if ( !is_numeric($oRegistro->fValor) || strpos($oRegistro->fValor, ".") === false) {
      $oRegistro->iMotivo = ArquivoEConsig::MOTIVO_OUTROS_MOTIVOS;
      $oRegistro->fValor = 0;
      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, _M(self::MENSAGEM  . 'valor_invalido'));
    }
  }

  /**
   * Transforma a linha do cabeçalho em um objeto.
   * 
   * @param  string $sLinha Linha do cabeçalho
   * @return Object
   */
  private function montarCabecalho() {

    $sLinha                   = fgets($this->rArquivo);

    if (strlen($sLinha) > 11) {
      throw new BusinessException( _M(self::MENSAGEM . 'arquivo_inconsistente') );
    }

    $oCabecalho               = new stdClass();
    $oCabecalho->iAno         = substr($sLinha, 0, 4);
    $oCabecalho->iMes         = substr($sLinha, 4, 2);
    $oCabecalho->iInstituicao = substr($sLinha, 6, 3);
    return $oCabecalho;
  }

  /**
   * Monta objeto através da linha do arquivo(apartir da 2ª linha)
   * 
   * @param  String  $sRegistro
   * @param  Integer $iLinha 
   * @return void
   */
  private function montarRegistro( $sRegistro, $iLinha ) {
    
    $oRegistro             = new stdClass(); 
    $oRegistro->iLinha     = $iLinha;
    $oRegistro->iMatricula = (int) substr($sRegistro,  0, 10);
    $oRegistro->sNome      = substr($sRegistro, 10, 40);
    $oRegistro->sRubrica   = substr($sRegistro, 50,  4);
    $oRegistro->fValor     = substr($sRegistro, 54, 10);
    $oRegistro->iMotivo    = 0;

    if (empty($oRegistro->sNome) || empty($oRegistro->sRubrica)) {  
      
      throw new BusinessException(_M(self::MENSAGEM . 'arquivo_linha_branco', $oRegistro));
    }
    
    $this->validarRegistro($oRegistro);

    /**
     * Executa metodo para verificações de mudanças do registro
     * Caso este registro exista no arquivo anterior.
     */
    $aRegistrosAntigos = $this->getUltimoRegistro($oRegistro);
    
    if ($aRegistrosAntigos != "") {
      
      foreach ($aRegistrosAntigos as $oRegistroAntigo ) {
        $this->validarMudanca($oRegistroAntigo, $oRegistro);
      }
    }

    $oRetorno = new RegistroPontoEconsig();

    try {
      $oServidor = ServidorRepository::getInstanciaByCodigo(
                                                              $oRegistro->iMatricula, 
                                                              $this->oCompetencia->getAno(), 
                                                              $this->oCompetencia->getMes(), 
                                                              $this->oInstituicao->getSequencial() 
                                                            );

     } catch ( BusinessException $eException ) {

      $oServidor = new Servidor();
      $oServidor->setMatricula( $oRegistro->iMatricula );
      $oServidor->setCodigoInstituicao($this->oInstituicao->getSequencial());
    }

    try { 
      $oRubrica = RubricaRepository::getInstanciaByCodigo( $oRegistro->sRubrica, $this->oInstituicao->getSequencial());
    } catch ( BusinessException $eException ) {

      $oRubrica = new Rubrica();
      $oRubrica->setCodigo( $oRegistro->sRubrica );
      $oRubrica->setInstituicao( $this->oInstituicao->getSequencial() );
    }

    $oRetorno->setNome($oRegistro->sNome);
    $oRetorno->setServidor($oServidor);
    $oRetorno->setRubrica($oRubrica);
    $oRetorno->setQuantidade(1);
    $oRetorno->setValor($oRegistro->fValor);
    $oRetorno->setMotivo($oRegistro->iMotivo);

    return $oRetorno;
  }

  /**
   * Inicia o processamento da importação
   *
   * @return void
   * @throws DBException
   */
  public function importar() {
 
    $this->oLog              = new DBLog("JSON", "tmp/LogImportacao_e-Consig.log");
    $this->oLogModificacoes  = new DBLog("JSON", "tmp/LogImportacaoModificacoes_e-Consig.log");

    $this->oArquivoEConsig = new ArquivoEConsig();

    $this->oArquivoEConsig->setCompetencia($this->oCompetencia);
    $this->oArquivoEConsig->setInstituicao($this->oInstituicao);
    $this->oArquivoEConsig->setNome(basename($this->sCaminhoArquivo));

    $aArquivoNovo        = array();
    $aArquivoAnterior    = array();
    $aRegistrosRemovidos = array();

    $iLinha   = 2;
    
    while (!feof($this->rArquivo)) {

      $sRegistro = fgets($this->rArquivo);
      
      /**
       * Verifica se a última linha do arquivo é em branco, 
       * caso seja a mesma é ignorada.
       */
      if (feof($this->rArquivo)) {
   
        if (empty($sRegistro)) {
          continue;
        }
      }

      $oRegistro = $this->montarRegistro($sRegistro, $iLinha++);      
      $aArquivoNovo[$oRegistro->getServidor()->getMatricula()] = trim($oRegistro->getNome());
      $this->oArquivoEConsig->adicionarRegistro($oRegistro);
    }

    /*
     * Verifica se existe algum arquivo e-consig existente pela competência
     * para excluir os pontos de salário
     */
    $oArquivoAnterior = ArquivoEConsigRepository::getUltimoArquivo($this->oInstituicao, $this->oCompetencia);
    $oArquivoAnterior->carregarRegistros();

    $aAnterior   = $this->getUltimosRegistrosArquivo();
    $aDiferencas = array_diff($aAnterior, $aArquivoNovo);

    foreach ($aDiferencas as $iMatricula => $sNomeServidor) {
      $this->logModificacoes(null, $iMatricula, $sNomeServidor, _M(self::MENSAGEM  . 'servidor_removido'));
    }
    
    $iCodigoArquivo = $oArquivoAnterior->getCodigo();
    
    if ($oArquivoAnterior && !empty($iCodigoArquivo)) {
      $this->excluirPontoSalario($oArquivoAnterior);
    }
    
    /**
     * Gera o relatório e salva o iOID do mesmo no banco
     */
    $sCaminhoRelatorio = $this->gerarRelatorioImportacao();

    if ( !empty($sCaminhoRelatorio) ) {

      $this->setCaminhoArquivoInconsistencias($sCaminhoRelatorio);
      $this->salvarRelatorio();
    }
    /**
     * Persiste os dados da tabela do e-consig
     */
    ArquivoEConsigRepository::persist($this->oArquivoEConsig);

    /**
     * Lança os dados dos registros no ponto de cada servidor.
     */
    $this->criarPontoSalario(); 
    return;
  }

  /**
   * Escreve log de erros encontrados no processamento
   * 
   * @param  Integer $iLinha
   * @param  Integer $iMatricula
   * @param  String  $sNome
   * @param  String  $sMotivo
   * @return void
   */
  private function log($iLinha, $iMatricula, $sNome, $sMotivo = null) {

    $oMensagem             = new stdClass();
    $oMensagem->iMatricula = (int)$iMatricula;
    $oMensagem->sNome      = utf8_encode($sNome);
    $oMensagem->sMotivo    = utf8_encode($sMotivo);
    $oMensagem->iLinha     = $iLinha;
    $this->oLog->escreverLog($oMensagem,  DBLog::LOG_ERROR);
    return;
  }

   /**
   * Escreve log de modificações encontrados no processamento
   * 
   * @param  String  $sDescricao
   * @return void
   */
  private function logModificacoes($iLinha, $iMatricula, $sNome, $sDescricao) {

    $oMensagemModificacao             = new stdClass();
    $oMensagemModificacao->iMatricula = (int)$iMatricula;
    $oMensagemModificacao->sNome      = utf8_encode($sNome);
    $oMensagemModificacao->sDescricao = utf8_encode($sDescricao);
    $oMensagemModificacao->iLinha     = $iLinha;
    $this->oLogModificacoes->escreverLog($oMensagemModificacao,  DBLog::LOG_ERROR);
    return;
  }  

  /**
   * Valida as mudanças do registro passado por parâmetro, comparando o registro anterior ocm o novo
   * @param  stdClass $oRegistroVelho Registro antigo
   * @param  stdClass $oRegistroNovo  Registro que está sendo validado
   * @return boolean
   */
  private function validarMudanca(stdClass $oRegistroVelho, stdClass $oRegistroNovo) {

    $fValorNovo     = number_format($oRegistroNovo->fValor, 2);
    $fValorVelho    = number_format($oRegistroVelho->valor, 2);
    $iMatricula     = (int)$oRegistroNovo->iMatricula;

    $oVariaveisJson = new stdClass();
    $oVariaveisJson->sRubricaNova  = $oRegistroNovo->sRubrica; 
    $oVariaveisJson->sRubricaVelha = $oRegistroVelho->rubrica; 
    $oVariaveisJson->fValorVelho   = $fValorVelho; 
    $oVariaveisJson->fValorNovo    = $fValorNovo; 

    /**
     * Verifica se existe algum inconsistencia para o motico, 
     * se existe não foi lançado no ponto.
     */
    if ($oRegistroNovo->iMotivo != 0) {
      return false;
    }

    try { 

      $oRubrica = RubricaRepository::getInstanciaByCodigo( $oRegistroNovo->sRubrica, $this->oInstituicao->getSequencial());

      if ($oRegistroVelho->rubrica == '') {
        $this->logModificacoes($oRegistroNovo->iLinha, $oRegistroNovo->iMatricula,$oRegistroNovo->sNome, _M(self::MENSAGEM  . 'rubrica_adicionada', $oVariaveisJson)); 
      }

      if ($oRegistroVelho->rubrica && ($oRegistroVelho->rubrica != $oRegistroNovo->sRubrica)) {
        $this->logModificacoes($oRegistroNovo->iLinha, $oRegistroNovo->iMatricula,$oRegistroNovo->sNome, _M(self::MENSAGEM  . 'rubrica_excluida_adicionada', $oVariaveisJson)); 
      }
    } catch ( BusinessException $eErro ) {

      if ($oRegistroVelho->rubrica == '') {
        return;
      }
      $this->logModificacoes($oRegistroNovo->iLinha,$oRegistroNovo->iMatricula,$oRegistroNovo->sNome, _M(self::MENSAGEM  . 'rubrica_excluida', $oVariaveisJson)); 
    }
    
    
    if ($fValorVelho && ($fValorVelho != $fValorNovo) && ($oRegistroVelho->rubrica == $oRegistroNovo->sRubrica)) {
      $this->logModificacoes($oRegistroNovo->iLinha,$oRegistroNovo->iMatricula,$oRegistroNovo->sNome,_M(self::MENSAGEM  . 'valor_modificado', $oVariaveisJson));
    }

    return true;
  }

  /**
   * Imprime o relatório com as incositências encontradas na importaçõa do arquivo.
   * @return String caminho do PDF.
   */
  public function gerarRelatorioImportacao() {

    $this->oLog->finalizarLog();
    $this->oLogModificacoes->finalizarLog();

    $sLog                   = $this->oLog->getConteudo();
    $sLogModificacoes       = $this->oLogModificacoes->getConteudo();
    
    $oJson                  = new Services_JSON();
    $oMatriculas            = $oJson->decode($sLog);
    $oMatriculasModificadas = $oJson->decode($sLogModificacoes);

    /**
     * Gera o PDF
     */
    require_once("fpdf151/pdfnovo.php");
    $oPdf = new PDFNovo();

    $oPdf->addHeader(' ');
    $oPdf->addHeader(' ');
    $oPdf->addHeader('Relatório de Importação E-Consig.');
    $oPdf->addHeader('Competência: ' . DBPessoal::getAnoFolha() . '/' . DBPessoal::getMesFolha());
    $oPdf->SetFillColor(235);
    $oPdf->Open();
    $oPdf->AliasNbPages();
    
    $lHouveInconsistencias = false;
    $lHouveModificacoes    = false;
    $lBackground           = false;

    if ( isset($oMatriculas->aLogs) && count($oMatriculas->aLogs) > 0 ) {

      $lHouveInconsistencias = true;
      $oPdf->addTableTitle('Inconsistências:',192,4,'L');
      $oPdf->addTableHeader('Matrícula'       , 20, 4, 'C');
      $oPdf->addTableHeader('Nome'            , 75, 4, 'C');
      $oPdf->addTableHeader('Motivo'          , 76, 4, 'C');
      $oPdf->addTableHeader('Linha do Arquivo', 22, 4, 'C');
      
      $oPdf->AddPage("p");

      $lBackground = true;
      $iContador   = 0;

      foreach ($oMatriculas->aLogs as $oMatricula) {
  
        $lBackground = !$lBackground;     
        $oPdf->Cell(20, 4, $oMatricula->iMatricula           , true, 0, 'C', $lBackground);
        $oPdf->Cell(75, 4, $oMatricula->sNome                , true, 0, 'L', $lBackground);
        $oPdf->Cell(76, 4, utf8_decode($oMatricula->sMotivo) , true, 0, 'L', $lBackground);
        $oPdf->Cell(22, 4, $oMatricula->iLinha               , true, 1, 'C', $lBackground);
      }
  
      $oPdf->removeTableHeaders();
    } 

    if ( isset($oMatriculasModificadas->aLogs) && count($oMatriculasModificadas->aLogs) > 0) {

      $lHouveModificacoes = true;
      $oPdf->addTableTitle('Atualizações:',192,4,'L');
      $oPdf->addTableHeader('Matrícula'          , 20, 4, 'C');
      $oPdf->addTableHeader('Nome'               , 75, 4, 'C');
      $oPdf->addTableHeader('Descrição'          , 76, 4, 'C');
      $oPdf->addTableHeader('Linha do Arquivo'   , 22, 4, 'C');

      $oPdf->AddPage("p");
      
      foreach ($oMatriculasModificadas->aLogs as $oMatriculaModificada) {

        $lBackground = !$lBackground;     
        $oPdf->Cell(20, 4, $oMatriculaModificada->iMatricula              , true, 0, 'C', $lBackground);
        $oPdf->Cell(75, 4, $oMatriculaModificada->sNome                   , true, 0, 'L', $lBackground);
        $oPdf->Cell(76, 4, utf8_decode($oMatriculaModificada->sDescricao) , true, 0, 'L', $lBackground);
        $oPdf->Cell(22, 4, $oMatriculaModificada->iLinha                  , true, 1, 'C', $lBackground);
      }
    }

    if ( $lHouveModificacoes || $lHouveInconsistencias )  {

      $sCaminhoInconsistencia = "tmp/Importacao_econsig.pdf";
      $this->setCaminhoArquivoInconsistencias($sCaminhoInconsistencia);
  
      $oPdf->Output($sCaminhoInconsistencia, false, true);
      return $sCaminhoInconsistencia;
    }

    return;
  }

  /**
   * Organiza os registros do ponto.
   *
   * @return Array
   */
  private function organizarRegistroPonto() {

    $aResgistroPonto       = $this->oArquivoEConsig->getRegistros();
    $aRegistrosOrganizados = array();

    for ($iRegistroPonto = 0; $iRegistroPonto < count($aResgistroPonto); $iRegistroPonto++) {

      $oRegistro = $aResgistroPonto[$iRegistroPonto];
      $aRegistrosOrganizados[$oRegistro->getServidor()->getMatricula()][] = $oRegistro;
    }

    return $aRegistrosOrganizados;
  }

  /**
   * Adiciona os registros ao ponto.
   * @return void
   */
  public function criarPontoSalario() {

    $aRegistroOrganizados = $this->organizarRegistroPonto();

    foreach ($aRegistroOrganizados as $iMatricula => $aRegistro) {

      $oPonto = $aRegistro[0]->getServidor()->getPonto(Ponto::SALARIO);

      foreach ($aRegistro as $oRegistro) {
        /**
         * Verifica se o registro é válido para ser adicionado ao Ponto.
         */
        if ($oRegistro->getMotivo() == 0) {
        
          $oPonto->limpar($oRegistro->getRubrica()->getCodigo());
          $oPonto->adicionarRegistro($oRegistro, false);
        }
      }

      $oPonto->salvar();
    }
  }

  /**
   * Exclui todos os pontos de salário do ArquivoEconsig informado
   * 
   * @param ArquivoEconsig $oArquivo
   * @return Boolean
   * @throws Exception
   */
  public function excluirPontoSalario(ArquivoEconsig $oArquivo) {
    
    $aRegistrosPonto = $oArquivo->getRegistros();
    
    foreach ($aRegistrosPonto as $oRegistoPonto) {
      
      $oPonto   = $oRegistoPonto->getServidor()->getPonto(Ponto::SALARIO);
      $sRubrica = $oRegistoPonto->getRubrica()->getCodigo();
      $oPonto->limpar($sRubrica);
    }
    
    return true;
  }

  /**
   * Método responsável por salvar o relatório da importação no banco de dados
   *
   * @throws Exception
   */
  public function salvarRelatorio() {
 
    if (file_exists($this->getCaminhoArquivoInconsistencias())) {

      $iOid   = DBLargeObject::criaOID(true);
      $lGerou = DBLargeObject::escrita($this->getCaminhoArquivoInconsistencias(), $iOid);
      
      if ($lGerou) {
        $this->oArquivoEConsig->setRelatorio($iOid);
      }
    }
  }
}