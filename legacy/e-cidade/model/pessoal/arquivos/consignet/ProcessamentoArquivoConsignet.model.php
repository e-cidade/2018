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
 * Classe para processamento do arquivo consignet
 * 
 */
class ProcessamentoArquivoConsignet {

  const MENSAGEM = 'recursoshumanos.pessoal.ProcessamentoArquivoConsignet.';

  /**
   * Constante com o código do layout do arquivo
   */
  const I_CODIGO_LAYOUT = 224;

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
   * Objeto do arquivo consignet
   * @var ArquivoConsignet
   */
  private $oArquivoConsignet;

  /**
   * Caminho do arquivo de inconsistências
   * @var String
   */
  private $sCaminhoArquivoInconsistencias;

  /**
   * Linha do arquivo para registrar no log
   * @var Integer
   */
  private $iLinha;

  /**
   * Matrícula do servidor para registrar no log
   * @var Integer
   */
  private $iMatriculaRegistro;

  /**
   * Nome do servidor para registrar no log
   * @var String
   */
  private $sNomeRegistro;

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
   * Define a linha do registro do arquivo para o log
   * @param Integer $iLogLinha
   */
  public function setLogLinha($iLogLinha) {
    $this->iLogLinha = $iLogLinha;
  }

  /**
   * Retorna a linha do registro do arquivo para o log
   * @return Integer
   */
  public function getLogLinha() {
    return $this->iLogLinha;
  }

  /**
   * Define a matrícula do servidor para o log
   * @param Integer $iLogMatriculaRegistro
   */
  public function setLogMatriculaRegistro($iLogMatriculaRegistro) {
    $this->iLogMatriculaRegistro = $iLogMatriculaRegistro;
  }

  /**
   * Retorna a matrícula do servidor para o log
   * @return Integer
   */
  public function getLogMatriculaRegistro() {
    return $this->iLogMatriculaRegistro;
  }

  /**
   * Define o nome do servidor para o log
   * @param String $sLogNomeRegistro
   */
  public function setLogNomeRegistro($sLogNomeRegistro) {
    $this->sLogNomeRegistro = $sLogNomeRegistro;
  }

  /**
   * Retorna o nome do servidor para o log
   * @return String
   */
  public function getLogNomeRegistro() {
    return $this->sLogNomeRegistro;
  }

  /**
   * Executa validações gerais sobre o arquivo, como nome
   * @return void
   */
  private function validarArquivo(){

    $this->validarNome();

    return;
  }

  /**
   * Valida  se o nome do arquivo é válido, consignet_[anousu]_[mesusu]_[instituicao].txt
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

    if (!preg_match('/consignet_(\d{4})_(\d{2})_(\d{3})\.txt/i', $sNomeArquivo)) {
      throw new Exception( _M(self::MENSAGEM . 'nome_invalido') );
    }

    $iAnoArquivo         = substr($sNomeArquivo, 10, 4);
    $iMesArquivo         = substr($sNomeArquivo, 15, 2);
    $iInstituicaoArquivo = substr($sNomeArquivo, 18, 3);

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
        $oRegistro->iMotivo = ArquivoConsignet::MOTIVO_SERVIDOR_INVALIDO;
      }

      if ($oServidor && trim(DBString::removerAcentuacao($oServidor->getCgm()->getNome())) != trim($oRegistro->sNome))  {

        $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, _M(self::MENSAGEM  . 'nome_servidor_invalido'));
        $oRegistro->iMotivo = ArquivoConsignet::MOTIVO_SERVIDOR_INVALIDO;
      }
    } catch( BusinessException $eErro ) {

      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, $eErro->getMessage());
      $oRegistro->iMotivo = ArquivoConsignet::MOTIVO_SERVIDOR_INVALIDO;
    } catch ( Exception $eErro ) {

      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, $eErro->getMessage());
      $oRegistro->iMotivo = ArquivoConsignet::MOTIVO_SERVIDOR_INVALIDO;
    }

    try {
      $oRubrica = RubricaRepository::getInstanciaByCodigo($oRegistro->sRubrica, $this->oInstituicao->getSequencial());
    } catch( BusinessException $eErro ) {

      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, $eErro->getMessage());
      $oRegistro->iMotivo = ArquivoConsignet::MOTIVO_OUTROS_MOTIVOS;
    } catch ( Exception $eErro ) {
      $this->log($oRegistro->iLinha, $oRegistro->iMatricula, $oRegistro->sNome, $eErro->getMessage());
    }

    if ( !is_numeric($oRegistro->fValorParcela) || strpos($oRegistro->fValorParcela, ".") === false) {
      $oRegistro->iMotivo = ArquivoConsignet::MOTIVO_OUTROS_MOTIVOS;
    }
  }

  /**
   * Monta objeto através da linha do arquivo(apartir da 2ª linha)
   * 
   * @param  String  $sRegistro
   * @param  Integer $iLinha 
   * @return void
   */
  private function montarRegistro( $oLinha, $iLinha ) {

    /**
     * São as informações do log.
     */
    $this->setLogLinha($iLinha);
    $this->setLogMatriculaRegistro($oLinha->matricula);
    $this->setLogNomeRegistro($oLinha->nome);

    /**
     * Monta o registro do arquivo
     */

    $oRegistro                 = new stdClass(); 
    $oRegistro->iLinha         = $iLinha;

    $oRegistro->iMatricula     = $this->validarCampo(2, $oLinha->matricula, 10, _M(self::MENSAGEM . 'matricula_invalida'));
    $oRegistro->sNome          = $this->validarCampo(1, $oLinha->nome, 40, _M(self::MENSAGEM . 'nome_servidor_formato_invalido'));
    $oRegistro->sRubrica       = $this->validarCampo(5, $oLinha->rubrica, 4, _M(self::MENSAGEM . 'rubrica_invalida'));
    $oRegistro->fValorParcela  = $this->validarCampo(6, $oLinha->valor_parcela, 10, _M(self::MENSAGEM . 'valor_invalido'));
    $oRegistro->iParcela       = $this->validarCampo(2, $oLinha->parcela, 3, _M(self::MENSAGEM . 'numero_parcela_invalida'));
    $oRegistro->iTotalParcelas = $this->validarCampo(2, $oLinha->total_parcelas, 3, _M(self::MENSAGEM . 'numero_total_parcelas_invalida'));
    $oRegistro->iMotivo        = null;

    if (empty($oRegistro->sNome) || empty($oRegistro->sRubrica)) {  

      throw new BusinessException(_M(self::MENSAGEM . 'arquivo_linha_branco', $oRegistro));
    }

    $this->validarRegistro($oRegistro);

    $oItemRegistro = new RegistroArquivoImportacaoConsignet();
    $oItemRegistro->setLinha ($oRegistro->iLinha);
    $oItemRegistro->setMatricula ($oRegistro->iMatricula);
    $oItemRegistro->setNome ($oRegistro->sNome);
    $oItemRegistro->setRubric ($oRegistro->sRubrica);
    $oItemRegistro->setValorParcela ($oRegistro->fValorParcela);
    $oItemRegistro->setParcela ($oRegistro->iParcela);
    $oItemRegistro->setTotalParcelas ($oRegistro->iTotalParcelas);
    $oItemRegistro->setMotivo ($oRegistro->iMotivo);

    try {
      $oServidor = ServidorRepository::getInstanciaByCodigo($oRegistro->iMatricula, 
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

    $oItemRegistro->setServidor ($oServidor);
    $oItemRegistro->setRubrica ($oRubrica);

    return $oItemRegistro;
  }

  /**
   * Inicia o processamento da importação
   *
   * @return void
   * @throws DBException
   */
  public function importar() {

    $this->oLog              = new DBLog("JSON", "tmp/LogImportacao_Consignet.log");
    $this->oLogModificacoes  = new DBLog("JSON", "tmp/LogImportacaoModificacoes_Consignet.log");

    $this->oArquivoConsignet = new ArquivoConsignet();

    $this->oArquivoConsignet->setCompetencia($this->oCompetencia);
    $this->oArquivoConsignet->setInstituicao($this->oInstituicao);
    $this->oArquivoConsignet->setNome(basename($this->sCaminhoArquivo));

    /**
     * Layout do arquivo de importação
     * @var DBLayoutReader
     */
    $oLayoutArquivo      = new DBLayoutReader(self::I_CODIGO_LAYOUT, $this->sCaminhoArquivo);

    $aArquivoNovo        = array();
    $aArquivoAnterior    = array();
    $aRegistrosRemovidos = array();
    $iTamanhoLinha       = null;

    while (!feof($this->rArquivo)) {

      $sRegistro = fgets($this->rArquivo);

      /**
       * Verifica se a última linha do arquivo é em branco, 
       * caso seja a mesma é ignorada.
       */
        if (empty($sRegistro)) {
          continue;
        }
      /**
       * Verifica se há diferença no tamanho das linhas, se houver layout está inválido 
       */
      if ( ($iTamanhoLinha != null && $iTamanhoLinha != strlen($sRegistro) && !feof($this->rArquivo)) || strlen($sRegistro) == 0 ){
        throw new BusinessException(_M(self::MENSAGEM . 'arquivo_layout_invalido'));
      }

      $iTamanhoLinha = strlen($sRegistro);

    }

    if ( count($oLayoutArquivo->getLines()) > 0 ) {

      foreach ($oLayoutArquivo->getLines() as $iLinha => $oLinha) {

        $iLinha++;
        $oRegistro = $this->montarRegistro($oLinha, $iLinha);

        if (ServidorRepository::isMatriculaValida($oRegistro->getMatricula(),
          $this->oArquivoConsignet->getCompetencia()->getAno(),
          $this->oArquivoConsignet->getCompetencia()->getMes() , 
          $this->oArquivoConsignet->getInstituicao()->getCodigo() 
        )) {        
          /**
           * Valida se o Servidor possuí algum afastamento, se possuí afastamento 
           * sem remuneração não pode ser realizado o desconto previsto
           */
          $this->validarAfastamento($oRegistro);

          /**
           * Valida se o servidor esta rescindido, se estiver insere na tabela rhconsignadomovimentoservidor 
           * com o campo movtivo preenchido com o código.
           */
          $this->validarRescisao($oRegistro);

          /**
           * Valida se o servidor esta rescindido e o tipo de rescisao é falescimento, 
           * caso positivo cadastra o motivo do tipo 'Falecimento'.
           */
          $this->validaServidorFalecido($oRegistro);
        }

        $this->oArquivoConsignet->adicionarRegistro($oRegistro);

      }

    } else {
      throw new BusinessException(_M(self::MENSAGEM . 'arquivo_vazio'));
    }

    /**
     * Gera o relatório e salva o iOID do mesmo no banco
     */
    $sCaminhoRelatorio = $this->gerarRelatorioImportacao();
    if ( !empty($sCaminhoRelatorio) ) {

      $this->setCaminhoArquivoInconsistencias($sCaminhoRelatorio);
      $this->salvarRelatorio();
    } else {
      $this->oArquivoConsignet->setRelatorio('null');
    }

    /**
     * Persiste os dados da tabela do consignet
     */
    ArquivoConsignetRepository::persist($this->oArquivoConsignet);

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
    require_once(modification("fpdf151/pdfnovo.php"));
    $oPdf = new PDFNovo();

    $oPdf->addHeader(' ');
    $oPdf->addHeader(' ');
    $oPdf->addHeader('Relatório de Importação Consignet.');
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

      $sCaminhoInconsistencia = "tmp/Importacao_consignet_". DBPessoal::getAnoFolha() ."_". DBPessoal::getMesFolha() .".pdf";
      $this->setCaminhoArquivoInconsistencias($sCaminhoInconsistencia);

      $oPdf->Output($sCaminhoInconsistencia, false, true);
      return $sCaminhoInconsistencia;
    }

    return;
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
        $this->oArquivoConsignet->setRelatorio($iOid);
      }
    }
  }

  /**                                                                                                                                                                         
   * Válida e formata o campo.
   * 
   * @access private
   * @param Integer $iTipoCampo
   * @param String $sCampo
   * @param Integer $iTamanhoMaximo
   * @param String $sMensagem
   * @return String
   */
  public function validarCampo($iTipoCampo, $sCampo, $iTamanhoMaximo, $sMensagem) {

    $sCampoFormatado = ProcessamentoArquivoConsignet::formatarCampo($iTipoCampo, $sCampo, $iTamanhoMaximo);

    if(!$sCampoFormatado) {

      $this->log($this->getLogLinha(), $this->getLogMatriculaRegistro(), $this->getLogNomeRegistro(), $sMensagem); 
      return $sCampo;
    }

    return $sCampoFormatado;
  }

  /**
   * Método responsável por válidar e formatar os campos.
   * Estes são os tipos do campo:
   * 1- String
   * 2- Número
   * 3- Unitário
   * 4- Data - o formato da data {AAAAMMDD}  
   * 5- Rubricas - Especial para rúbricas com zeros a esquerda
   * 6- Unitário com ponto decimal
   * 
   * @param Integer $iTipo
   * @param String $sString
   * @param Integer $iTamanhoMaximo
   * @return String|Boolean
   */
  public static function formatarCampo($iTipo , $sString, $iTamanhoMaximo) {

    $sStringFormatada = $sString;

    /**
     * Válida se o campo é vázio ou nulo.
     */
    if (empty($sStringFormatada)) {
      return false;
    }

    /**
     * Válida se o tamanho do campo é superior ao tamanho máximo.
     */
    if (!DBString::validarTamanhoMaximo($sStringFormatada, $iTamanhoMaximo)) {
      return false;
    }

    /**
     * Formata o campo se for diferente de zero.
     * Ex.: "000000ABC" => "ABC" 
     */
    if($sStringFormatada != "0"){
      $sStringFormatada = trim(ltrim($sStringFormatada, "0"));
    }

    switch ($iTipo) {

    case 1:

      if (!DBString::isSomenteAlfanumerico($sStringFormatada, true)) {
        return false;
      }
      break;

    case 2:

      if (!DBString::isSomenteNumero($sStringFormatada)) {    
        return false;
      }
      break;

      /**
       * Ex.: 10000 => 100.00 
       */  
    case 3:

      if (!DBString::isSomenteNumero(str_replace(".", "", $sStringFormatada))) {
        return false;
      }
      $sStringFormatada = number_format(str_replace(".", "", $sStringFormatada)/100, 2, ".", "");
      break;

      /**
       * Ex.: 20150101 => 01/01/2015 
       */  
    case 4:

      if (!DBString::isSomenteNumero($sStringFormatada)) {
        return false;
      }

      $sDataMascarada   = ProcessamentoArquivoConsignet::mascararString($sStringFormatada, "####/##/##"); 
      $oData            = DateTime::createFromFormat('Y/m/d', $sDataMascarada);
      $sStringFormatada = $oData->format('d/m/Y');
      break;

      /**
       * Especial para tratar com as rúbricas, os zeros a esquerda não podem ser removidos
       */
    case 5:

      $sStringFormatada = $sString;
      if (!DBString::isSomenteAlfanumerico($sString, true)) {
        return false;
      }
      break;

      /**
       * Ex.: 100.11  => 100.11 
       * Ex.: 100.1   => 100.10 
       * Ex.: 100     => 100.00
       */  
    case 6:

      if (!DBString::isSomenteNumero(str_replace(".", "", $sStringFormatada))) {
        return false;
      }

      if (strpos($sStringFormatada, ".") == false){

        if (strlen($sStringFormatada) <= ($iTamanhoMaximo-3)) {
          $sStringFormatada .= ".00";
        } else {
          return false;
        }
      } elseif (strlen(str_replace(".", "", substr($sStringFormatada, strpos($sStringFormatada, ".")))) == 1 ){
        if (strlen($sStringFormatada) <= ($iTamanhoMaximo-2)){
          $sStringFormatada .= ".0";
        } else {
          return false;
        }
      } elseif (strlen(str_replace(".", "", substr($sStringFormatada, strpos($sStringFormatada, ".")))) == 2 ){
        if (strlen($sStringFormatada) > $iTamanhoMaximo){
          return false;
        }
      } elseif (strlen(str_replace(".", "", substr($sStringFormatada, strpos($sStringFormatada, ".")))) > 2 ){
        if (strlen($sStringFormatada) > $iTamanhoMaximo){
          return false;
        } else {
          $sStringFormatada = str_replace(substr($sStringFormatada, strpos($sStringFormatada, ".")), 
          substr($sStringFormatada, strpos($sStringFormatada, "."), 3), 
          $sStringFormatada);
        }
      }

      $sStringFormatada = number_format(str_replace(".", "", $sStringFormatada)/100, 2, ".", "");
      break;
    }

    return $sStringFormatada;
  }

  /**                                                                                                                                                                         
   * @todo Migrar e refarorar este método para função "db_formatar()".
   * 
   * Faz uma máscara na string.
   * 
   * @param String $sString
   * @param String $sMascara
   * @return String
   */
  public static function mascararString($sString, $sMascara){

    $sStringMask = '';
    $i           = 0;

    for ($iIndice = 0; $iIndice <= strlen($sMascara)-1; $iIndice++) {

      if ($sMascara[$iIndice] == '#') {

        if (isset($sString[$i])) {
          $sStringMask .= $sString[$i++];
        }
      } else {     

        if(isset($sMascara[$iIndice])) {
          $sStringMask .= $sMascara[$iIndice];
        }
      }
    }
    return $sStringMask;
  }

  /**
   * Válida se o registro esta afastado
   *
   * @param RegistroPontoConsignet $oRegistro
   * @return RegistroPontoConsignet
   * @throws BusinessException
   */
  private function validarAfastamento(RegistroArquivoImportacao $oRegistro) {

    $oDaoMovimentoServidor = new cl_rhconsignadomovimentoservidor();
    $mAfastamento = $oRegistro->getServidor()->isAfastado();

    if ($mAfastamento) {

      $this->log($oRegistro->getLinha(), $oRegistro->getMatricula(), $oRegistro->getNome(), _M(self::MENSAGEM  . 'servidor_afastado'));
      $oRegistro->setMotivo(ArquivoConsignet::MOTIVO_SERVIDOR_AFASTADO);
    } 
      
    return $oRegistro;
  }

  private function validarRescisao($oRegistro) {

    $oServidor = $oRegistro->getServidor();

    if ($oServidor->isRescindido()) {
      $this->log($oRegistro->getLinha(), $oRegistro->getMatricula(), $oRegistro->getNome(), _M(self::MENSAGEM  . 'servidor_rescindido'));
      $oRegistro->setMotivo(ArquivoConsignet::MOTIVO_SERVIDOR_DESLIGADO);
    }
  }

  /**
   * Valida se o servidor informado, esta falecido, se estiver retorna true senão retorna false.
   *
   * @param integer $iMatricula
   * @return bool
   * @throws DBException
   */
  private function validaServidorFalecido($oRegistro) {

    $oDaoRhPesRescisao = new cl_rhpesrescisao();

    /**
     * Valida se o servidor possui uma das causas(60,62,64) 
     * se posusir é porque o mesmo esta falecido.
     */
    $sWherePesRescisao = "rh02_regist = {$oRegistro->getServidor()->getMatricula()} and r59_causa in (60, 62, 64)";
    $sSqlRhPesRescisao = $oDaoRhPesRescisao->sql_query_rescisao(null, '*', null, $sWherePesRescisao);
    $rsRhPesRescisao   = db_query($sSqlRhPesRescisao);

    if (!$rsRhPesRescisao) {
      throw new DBException( _M(self::MENSAGEM . 'erro_consultar_recisao') . $sSqlRhPesRescisao );
    }

    if (pg_num_rows($rsRhPesRescisao)) {
      $this->log($oRegistro->getLinha(), $oRegistro->getMatricula(), $oRegistro->getNome(), _M(self::MENSAGEM  . 'servidor_falecido'));
      $oRegistro->setMotivo(ArquivoConsignet::MOTIVO_FALECIMENTO);
    }
  }
}
