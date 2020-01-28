<?php
/**
 * Classe para processamento Baixa de Imoveis no Recadastro Imobiliario
 * 
 * @uses     RecadastroImobiliarioImoveisInterface
 * @package  Recadastro Imobiliario
 * @author   Alberto Ferri Neto <alberto@dbseller.com.br> 
 * @revision $Author dbalberto $
 * @version  $Revision: 1.12 $
 */
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveis.interface.php");

class RecadastroImobiliarioImoveisExclusao implements RecadastroImobiliarioImoveisInterface {

  public $iMatricula;

  public $dDataBaixa;

  public $sNomeArquivo;

  public $iCodigoRegistro;

  public $sMensagemLog = '';

  public $oRegistroArquivo;

  public $sSQL;

  public function log($sMensagem, $iTipoLog = DBLog::LOG_INFO) {

    $this->sMensagemLog .= $sMensagem;

    RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog($sMensagem, $iTipoLog);

  }

  public function __construct($oRegistroArquivo) {

    if (!is_object($oRegistroArquivo)) {
      throw new Exception('Registro inválido para exclusão.');
    }

    $this->oRegistroArquivo = $oRegistroArquivo;

    $this->iCodigoRegistro  = $oRegistroArquivo->iCodigoRegistro;

    $this->iMatricula       = $oRegistroArquivo->iMatricula;

    $this->dDataBaixa       = $oRegistroArquivo->oDataEnvio->getDate();

    $this->sNomeArquivo     = $oRegistroArquivo->sNomeArquivo; 

    $this->iCodigoLinha     = $oRegistroArquivo->iSequencial; 

    $this->sSQL             = $oRegistroArquivo->sSetorCartograficoNovo  . "/" ;
    $this->sSQL            .= $oRegistroArquivo->sQuadraCartograficaNovo . "/" ;
    $this->sSQL            .= $oRegistroArquivo->sLoteCartograficoNovo;


    $this->log("+------------------------------------------------------------------------------+", DBLog::LOG_INFO);      
    $this->log("| PROCESSANDO INFORMAÇÕES DE EXCLUSÃO DO CÓDIGO SEQUENCIAL [{$this->iCodigoLinha}] DO ARQUIVO |", DBLog::LOG_INFO);     
    $this->log("+------------------------------------------------------------------------------+", DBLog::LOG_INFO); 

  }

  public function processar() {
    
    $dData = date('Y-m-d');
    $sHora = date('H:i');

    if ($this->validaMatricula()) {

      $sInsertIptubaixa  = "insert into iptubaixa                                               ";
      $sInsertIptubaixa .= "       (j02_matric ,                                                ";
      $sInsertIptubaixa .= "       j02_dtbaixa,                                                 ";
      $sInsertIptubaixa .= "       j02_motivo ,                                                 ";
      $sInsertIptubaixa .= "       j02_usuario,                                                 ";
      $sInsertIptubaixa .= "       j02_data   ,                                                 ";
      $sInsertIptubaixa .= "       j02_hora)                                                    ";
      $sInsertIptubaixa .= "values ('{$this->iMatricula}',                                      ";
      $sInsertIptubaixa .= "        '{$this->dDataBaixa}',                                      ";
      $sInsertIptubaixa .= "        'Matrícula baixada conforme recadastramento.',              ";
      $sInsertIptubaixa .= "        1,                                                          ";
      $sInsertIptubaixa .= "        '{$dData}',                                                 ";
      $sInsertIptubaixa .= "        '{$sHora}')                                                 ";

      $sUpdateIptubase   = "update iptubase                                                     ";
      $sUpdateIptubase  .= "   set j01_baixa  = '{$this->dDataBaixa}'                           ";
      $sUpdateIptubase  .= " where j01_matric = '{$this->iMatricula}'                           ";



      if ( !pg_query(Conexao::getInstancia()->getConexao(), $sInsertIptubaixa) ) {

        $this->log( "Erro ao Processar Exclusao dos Registros da Baixa da Matrícula", DBLog::LOG_ERRO );
        $this->log( "Descricao do Erro: ".pg_last_error(), DBLog::LOG_ERRO );
        throw new Exception("Erro ao Processar Exclusao dos Registros da Baixa da Matrícula:".pg_last_error());

      }

      if ( !pg_query(Conexao::getInstancia()->getConexao(), $sUpdateIptubase) ) {

        $this->log( "Erro ao Processar Alteração da Data de Baixa da Matrícula", DBLog::LOG_ERRO );
        $this->log( "Descricao do Erro: ".pg_last_error(), DBLog::LOG_ERRO );
        throw new Exception("Erro ao Processar Alteração da Data de Baixa da Matrícula".pg_last_error());

      }

      $this->log( "Matrícula {$this->iMatricula} baixada com sucesso. Registrando ocorrência", DBLog::LOG_INFO );

      $this->registrarOcorrencia();

      $this->registraLog();

    }
    $this->validaPosicaoFiscal13();

    return true; 

  }

  public function validaPosicaoFiscal13 () {

    $this->log( "Buscando Construção pela REF ANTERIOR: ". $this->getCodigoAnteriorConstrucao() ." e caracteristica da construção 713" );
    $sSqlPosicaoFiscal13  = "select distinct j39_matric, j39_idcons                              ";
    $sSqlPosicaoFiscal13 .= "  from iptuconstr                                                   ";
    $sSqlPosicaoFiscal13 .= " inner join carconstr on carconstr.j48_matric = j39_matric          ";
    $sSqlPosicaoFiscal13 .= "                     and carconstr.j48_idcons = j39_idcons          ";
    $sSqlPosicaoFiscal13 .= " where iptuconstr.j39_obs ~ '{$this->getCodigoAnteriorConstrucao()}'";
    $sSqlPosicaoFiscal13 .= "   and carconstr.j48_caract = 713                                   ";
    $rsPosicaoFiscal13    = pg_query(Conexao::getInstancia()->getConexao(), $sSqlPosicaoFiscal13);

    if ( !$rsPosicaoFiscal13 || pg_num_rows($rsPosicaoFiscal13) == 0 ) {
    
      $this->log( "Nao Encontradas Construcao com PosicaoFiscal 13");
      return false;
     }

    if ( pg_num_rows($rsPosicaoFiscal13) > 1 ) {

      $this->log( "Encontrada 2 ou mais matriculas com referencia anterior {$this->getCodigoAnteriorConstrucao()} e característica 'POSIÇÃO FISCAL 13'", DBLog::LOG_NOTICE );
      $this->log( "Ignorando Registro...", DBLog::LOG_NOTICE );
      return false;
    }


    $oIptuConstr          = db_utils::fieldsMemory($rsPosicaoFiscal13, 0);
    $iMatriculaConstrucao = $oIptuConstr->j39_matric;
    $iCodigoConstrucao    = $oIptuConstr->j39_idcons;

    $this->log( "Encontrou 1 Construção: Matricula: {$iMatriculaConstrucao} | ID Construcao: {$iCodigoConstrucao}.", DBLog::LOG_NOTICE );
    $sUpdate              = "update iptuconstr                            ";
    $sUpdate             .= "   set j39_dtdemo = '{$this->dDataBaixa}',    ";
    $sUpdate             .= "       j39_obs    = j39_obs || '\\nConstrução Demolida Pelo Recadastramento(arquivo - {$this->oRegistroArquivo->sNomeArquivo})'   ";
    $sUpdate             .= " where j39_matric = '{$iMatriculaConstrucao}'";
    $sUpdate             .= "   and j39_idcons = '{$iCodigoConstrucao}'   ";

    if ( !pg_query($sUpdate) ) {

      $sMensagem = "Demolindo construção {$iCodigoConstrucao} da matrícula {$iMatriculaConstrucao} com tipo 'POSIÇÃO FISCAL 13'";
      $this->log( $sMensagem, DBLog::LOG_NOTICE );
    }
    return true;
  }

  /**
   * Registra Log da Operação e Passa Registro para Processado
   * @return boolean
   */
  public function registraLog() {

    $this->sMensagemLog = pg_escape_string(Conexao::getInstancia()->getConexao(), $this->sMensagemLog);

    $sUpdateRecadastroImobiliarioImoveis  = "update recadastroimobiliarioimoveis                 ";
    $sUpdateRecadastroImobiliarioImoveis .= "   set ie28_processado  = 't',                      ";
    $sUpdateRecadastroImobiliarioImoveis .= "       ie28_observacoes = '{$this->sMensagemLog}'   ";
    $sUpdateRecadastroImobiliarioImoveis .= " where ie28_sequencial  =  {$this->iCodigoRegistro} ";

    if (!pg_query(Conexao::getInstancia()->getConexao(), $sUpdateRecadastroImobiliarioImoveis)) {

      $sMensagem = "Erro ao salvar log das operações do setor/quadra/lote: {$this->sSQL}.";

      $this->log($sMensagem, DBLog::LOG_ERROR);

      throw new Exception($sMensagem);

    }

    $this->log("Log do registro das operações executadas para o setor/quadra/lote {$this->sSQL} salvo nas observações do registro {$this->iCodigoRegistro}.", DBLog::LOG_INFO);
    return true;
  }

  public function validaMatricula() {

    /**
     * Verifica se matrícula não está baixada 
     */
    if (!empty($this->iMatricula)) {

      $sSqlIptubaixa = "select * from iptubaixa where j02_matric = {$this->iMatricula}";

      $rsIptubaixa   = pg_query(Conexao::getInstancia()->getConexao(), $sSqlIptubaixa);

      if (pg_num_rows($rsIptubaixa) == 0) {
        return true;
      }  

      return false;

    }

    return false;  

  }

  public function registrarOcorrencia () {

    $aConfiguracoes         = (object)parse_ini_file(PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);
    $iInstituicao           = $aConfiguracoes->sistema['instituicao_prefeitura'];

    $sInsertHistocorrencia  = "insert into histocorrencia                                                              "; 
    $sInsertHistocorrencia .= "       (ar23_sequencial  ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_id_usuario  ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_instit      ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_modulo      ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_id_itensmenu,                                                              ";
    $sInsertHistocorrencia .= "        ar23_data        ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_hora        ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_tipo        ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_descricao   ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_ocorrencia)                                                                ";
    $sInsertHistocorrencia .= " values (nextval('histocorrencia_ar23_sequencial_seq'),                                 ";
    $sInsertHistocorrencia .= "         1,                                                                             ";
    $sInsertHistocorrencia .= "         {$iInstituicao},                                                               ";
    $sInsertHistocorrencia .= "         578,                                                                           ";
    $sInsertHistocorrencia .= "         1722,                                                                          ";
    $sInsertHistocorrencia .= "         '{$this->dDataBaixa}',                                                         ";
    $sInsertHistocorrencia .= "         '00:00',                                                                       ";
    $sInsertHistocorrencia .= "         2,                                                                             ";
    $sInsertHistocorrencia .= "         'Imóvel baixado pelo recadastramento. Nome do arquivo: {$this->sNomeArquivo}.',";
    $sInsertHistocorrencia .= "         'Imóvel baixado pelo recadastramento. Nome do arquivo: {$this->sNomeArquivo}.')";

    if (pg_query (Conexao::getInstancia()->getConexao(), $sInsertHistocorrencia)) {

      $sInsertHistocorrenciaMatric  = "insert into histocorrenciamatric                            ";
      $sInsertHistocorrenciaMatric .= "       (ar25_sequencial   ,                                 ";
      $sInsertHistocorrenciaMatric .= "        ar25_matric       ,                                 ";
      $sInsertHistocorrenciaMatric .= "        ar25_histocorrencia)                                ";
      $sInsertHistocorrenciaMatric .= "values (nextval('histocorrenciamatric_ar25_sequencial_seq'),";
      $sInsertHistocorrenciaMatric .= "        {$this->iMatricula},                                ";
      $sInsertHistocorrenciaMatric .= "        currval('histocorrencia_ar23_sequencial_seq'))      ";

      if (pg_query(Conexao::getInstancia()->getConexao(), $sInsertHistocorrenciaMatric)) {

        $this->log( "Incluindo histórico de ocorrência para a matrícula {$this->iMatricula}.", DBLog::LOG_INFO );

        return true;

      }              

    }

    $this->log( "Erro ao incluir histórico de ocorrência para a matrícula {$this->iMatricula}. Continuando...'", DBLog::LOG_ERRO );

    return false;

  }

  private function getCodigoAnteriorConstrucao() {

    /**
     * Código de Referencia Anterior do Imovel/Construcao
     *
     *       ??904234450603001
     *       |||  ||  ||  || |
     *       |||  ||  ||  |+-+---> Unidade Imobiliaria Novo
     *       |||  ||  |+--+------> Lote Cartografico Novo
     *       |||  |+--+----------> Quadra Cartografica Novo
     *       ||+--+--------------> Setor Cartografico Novo
     *       |+------------------> Fixo "2"
     *       +-------------------> Distrito Novo do Imóvel
     */
    $sCodigoReferenciaAnterior  = str_pad( trim($this->oRegistroArquivo->sSetorCartograficoAnterior ), 4,"0", STR_PAD_LEFT );  
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sQuadraCartograficaAnterior), 4,"0", STR_PAD_LEFT );
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sLoteCartograficoAnterior  ), 4,"0", STR_PAD_LEFT );
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sUnidadeImobiliariaAnterior), 3,"0", STR_PAD_LEFT );
    return $sCodigoReferenciaAnterior;
  }

  /**
   * Retorna o Log 
   * 
   * @access public
   * @return void
   */
  public function getLog() {
    return $this->sMensagemLog;
  }
}
