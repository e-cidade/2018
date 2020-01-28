<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


require_once('std/DBString.php');
require_once('std/DBNumber.php');
require_once('std/DBDate.php');

db_app::import('exceptions.*');

/**
 * Processamento
 *
 * @package WebServices
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo  <renan@dbseller.com.br>
 * @author Gilton Guma <gilton@dbseller.com.br>
 */
class Processamento {

  /**
   * Caminho do Diret�rio onde s�o Guardados os Arquivos XML com os M�todos
   *
   * @var string
   */
  const PATH_REQUISICOES = 'webservices/processamento_dados/requisicoes/';

  /**
   * Versão
   *
   * @var string
   */
  private $sVersao;
  
  /**
   * Nome do M�todo( tambem nome do XML da pasta requisi��es ).xml
   *
   * @var String
   */
  private $sNomeMetodo;
  
  /**
   * aDadosRecebidos
   *
   * @var array
   * @access private
   */
  private $aDadosRecebidos = array();
  
  /**
   * Dados Referentes a classe a ser executada pelo webservice.
   *
   * @var StdClass
   */
  private $oClasseExecucao;
  
  /**
   * Array de Classes a serem executadas
   *
   * @var ArrayObject
   */
  private $aClassesExecucao;
  
  /**
   * Array de Metodos a serem executados
   *
   * @var ArrayObject
   */
  private $aMetodosExecucao;

  /**
   * Array contento os parametros necess�rios para processar a requisi��o do webService
   *
   * @var Array
   */
  private $aParametrosProcessamento = array();
  
  /**
   * Objeto contendo a Int�ncia do Log
   *
   * @var DBLog
   * @static
   */
  public static $oLog;
  
  public function __construct() {
    
    $this->olog = new DBLog('TXT', '/tmp/teste_log.log');
  }
  
  /**
   * Abre o arquivo a partir do metodo informado
   *
   * @param string $sMetodo
   * @param Array $aParametros
   * @return Processamento
   */
  public function processar($sMetodo, $aParametros) {
    
    $this->log('INICIO PROCESSAMENTO DA CLASSE', 1);
    
    $this->sNomeMetodo     = $sMetodo;
    $this->aDadosRecebidos = $aParametros;
    
    $this->abrirArquivo();
    
    if (!$this->validarParametros()) {
      return false;
    }
    
    // Realizando parser de toda as variaveis, fazendo substituicao das variaveis pelo valor
    $this->definirValoresVariaveis();
    
    return $this->executarClasse();
  }

  /**
   * Abre o arquivo a partir do metodo informado
   *
   * @return Boolean
   */
  private function abrirArquivo() {

    $this->log('');
    $this->log('FAZENDO LEITURA DO XML', 1);
    $this->log('');
        
    $oDadosClasse                        = new stdClass();
    $oDadosClasse->sNome                 = '';
    $oDadosClasse->aParametrosConstrutor = array();
    $oDadosClasse->sCaminhoArquivo       = '';
    $oDadosClasse->aMetodos              = array();
    
    $sCaminhoArquivo                     = DBFileExplorer::getCaminhoArquivo(Processamento::PATH_REQUISICOES,
                                                                             $this->sNomeMetodo.'.xml'
                                                                            );
    
    if (!$sCaminhoArquivo) {
      
      $this->log('Metodo de processamento nao encontrado', DBLog::LOG_ERROR);
      throw new Exception('Metodo de processamento nao encontrado');
    }
    
    $oArquivoXML       = new DOMDocument();
    $oArquivoXML->load($sCaminhoArquivo);
    
    // Vers�o para o tipo de processamento a ser realizado
    $oVersao           = $oArquivoXML->getElementsByTagName('versao')->item(0);
    $oXmlProcessamento = $oArquivoXML->getElementsByTagName('processamento')->item(0);
    $oXmlParametros    = $oArquivoXML->getElementsByTagName('parametros')->item(0);
    $oXmlConfiguracoes = $oArquivoXML->getElementsByTagName('configuracoes')->item(0);
    
    $this->sVersao     = count($oVersao) ? $oVersao->nodeValue : '1.0';
    
    $this->log("Executanto XML vers�o {$this->sVersao}", 2);
    
    // Percorre os parametros
    foreach ($oXmlParametros->getElementsByTagName('parametro') as $oXmlParametro) {
      
      $oAtributos               = $oXmlParametro->attributes;
      
      $oParametro               = new stdClass();
      $oParametro->sNome        = $oAtributos->getNamedItem('nome')->nodeValue;
      $oParametro->lObrigatorio = $oAtributos->getNamedItem('obrigatorio')->nodeValue == 'true';
      $oParametro->sTipo        = $oAtributos->getNamedItem('tipo')->nodeValue;
      
      $this->aParametrosProcessamento[$oParametro->sNome] = $oParametro;
    }
    
    /**
     * Leitura e processamento conforme a versao setada no arquivo XML
     *
     * @tutorial A vers�o deve ser informada no arquivo XML com a tag <versao>
     */
    switch ($this->sVersao) {
      
      case '1.2' :
        
        // Classes que ser�o instanciadas
        foreach ($oXmlConfiguracoes->getElementsByTagName('classe') as $oXmlClasse) {
          
          $oAtributos                          = $oXmlClasse->attributes;
          
          $oDadosClasse                        = new stdClass();
          $oDadosClasse->sNome                 = $oAtributos->getNamedItem('nome')->nodeValue;
          $oDadosClasse->sReferencia           = $oAtributos->getNamedItem('referencia')->nodeValue;
          $oDadosClasse->sCaminhoArquivo       = $oAtributos->getNamedItem('caminho')->nodeValue;
          
          if ($oAtributos->getNamedItem('parametros')->nodeValue) {
            $oDadosClasse->aParametrosConstrutor = explode(',', $oAtributos->getNamedItem('parametros')->nodeValue);
          }
          
          $this->log(print_r($oDadosClasse->aParametrosConstrutor, true));
          
          $this->aClassesExecucao[$oDadosClasse->sReferencia] = $oDadosClasse;
          
          // Verifica se o arquivo informado no XML existe
          if (!file_exists($oDadosClasse->sCaminhoArquivo)) {
            throw new exception("Arquivo da classe ({$oDadosClasse->sCaminhoArquivo}) nao encontrado.");
          }
        }
          
        /**
         * Metodos da Classe
         *  - A ordem do XML deve ser a ordem de Execu��o
         *  - o �ltimo m�todo deve retornar a resposta do webService
         */
        $oXmlExec = $oXmlConfiguracoes->getElementsByTagName('exec')->item(0);
        
        // Percorremos os m�todos e acumulamos em um array
        foreach ($oXmlExec->getElementsByTagName('metodo') as $oXmlMetodo) {
          
          $oAtributos           = $oXmlMetodo->attributes;
          $oXmlAtributo         = $oAtributos->getNamedItem('parametros');
          
          $oMetodo              = new stdClass();
          $oMetodo->sNome       = $oAtributos->getNamedItem('nome')->nodeValue;
          $oMetodo->sReferencia = $oAtributos->getNamedItem('referencia')->nodeValue;
          $oMetodo->aParametros = !is_null($oXmlAtributo) ? explode(',', $oXmlAtributo->nodeValue) : array();
          
          $this->aMetodosExecucao[$oMetodo->sReferencia][] = $oMetodo;
          
          if (!isset($this->aClassesExecucao[$oMetodo->sReferencia])) {
            
            throw new Exception("Classe com refer�ncia: \"{$oMetodo->sReferencia}\" n�o especificada do XML.");
          }
        }
        
        break;
        
      /**
       * Leitura e processamento no modo antigo
       *
       * @tutorial Mantem o modo antigo de leitura e processamento do XML
       */
      default :
        
        // Defini��es sobre a classe que ir�o ser instanciada e processada
        $oXmlClasse                          = $oXmlConfiguracoes->getElementsByTagName('classe')->item(0);
        $oAtributos                          = $oXmlClasse->attributes;
        
        $oDadosClasse->sNome                 = $oAtributos->getNamedItem('nome')->nodeValue;
        $oDadosClasse->aParametrosConstrutor = explode(',', $oAtributos->getNamedItem('parametros')->nodeValue);
        $oDadosClasse->sCaminhoArquivo       = $oAtributos->getNamedItem('caminho')->nodeValue;
         
        // Verifica se o arquivo informado no XML existe
        if (!file_exists($oDadosClasse->sCaminhoArquivo)) {
          throw new exception("Arquivo da classe ({$oDadosClasse->sCaminhoArquivo}) n�o encontrado.");
        }
         
        /**
         * Metodos da Classe
         *  - A ordem do XML deve ser a ordem de Execu��o
         *  - o �ltimo m�todo deve retornar a resposta do webService
         */
        $oXmlMetodos = $oXmlClasse->getElementsByTagName('metodos')->item(0);
         
        // Percorremos os m�todos e acumulamos em um array
        foreach ($oXmlMetodos->getElementsByTagName('metodo') as $oXmlMetodo) {
          
          $oAtributos               = $oXmlMetodo->attributes;
          $oParametros              = $oAtributos->getNamedItem('parametros');
          
          $oMetodo                  = new stdClass();
          $oMetodo->sNome           = $oAtributos->getNamedItem('nome')->nodeValue;
          $oMetodo->aParametros     = !is_null($oParametros) ? explode(',', $oParametros->nodeValue) : array();
          
          $oDadosClasse->aMetodos[] = $oMetodo;
        }
         
        $this->oClasseExecucao      = $oDadosClasse;
    }
     
    return true;
  }
  
  /**
   * Valida parametros para poderem ser processados
   *
   * @throws Exception - Caso parametro especificado n�o esta na lista de parametros permitidos
   *                     ou n�o informar um parametro obrigart�rio.
   * @return boolean
   */
  private function validarParametros() {
    
    $this->log('');
    $this->log('INICIO VALIDA��O DE PARAMETROS DECLARADOS NO XML', 1);
    $this->log('');
    
    $aParametrosSeremValidados = array();
    
    // Valida todos os parametros escritos no XML
    foreach ($this->aParametrosProcessamento as $oParametro) {
      
      $this->log("Validando Par�metro: {$oParametro->sNome}", 2);
      
      // Valida se o parametro � obrigat�rio
      if ($oParametro->lObrigatorio) {
        
        // Se for obrigat�rio e n�o estiver setado lan�a exce��o
        if (!isset($this->aDadosRecebidos[$oParametro->sNome])) {
          throw new Exception("Par�metro obrigat�rio({$oParametro->sNome}) n�o foi informado");
        }
        
        if (is_null($this->aDadosRecebidos[$oParametro->sNome]) || $this->aDadosRecebidos[$oParametro->sNome] == '') {
          throw new Exception("Par�metro obrigat�rio({$oParametro->sNome}) n�o pode ser nulo ou vazio");
        }
        
        // Adicionando para valida��o de tipo
        $aParametrosSeremValidados[$oParametro->sNome] = $oParametro;
      } else {
      
        // Caso o parâmetro n�o seja obrigat�rio
        if ( isset($this->aDadosRecebidos[$oParametro->sNome]) ) {
          
          // Adicionando para valida��o de tipo
          $aParametrosSeremValidados[$oParametro->sNome] = $oParametro;
        }
      }
    }
    
    $this->log('');
    $this->log('INICIO VALIDA��O DE PARAMETROS INFORMADOS NO WEBSERVICE', 1);
    $this->log('');
    
    // Percorre os Dados Recebidos vendo se nao houve parametros a mais sendo enviados
    foreach ($this->aDadosRecebidos as $sPropriedadeRecebida => $sValorRecebido) {
      
      $this->log("Validando se o Par�metro: ({$sPropriedadeRecebida} = {$sValorRecebido}) � permitido.", 2);
      
      if (!isset($this->aParametrosProcessamento[$sPropriedadeRecebida])) {
        throw new Exception(
          "O par�metro enviado ({$sPropriedadeRecebida} = {$sValorRecebido}) n�o e valido, verfique documentacao."
        );
      }
    }
    
    $this->log('');
    $this->log('FIM DA VALIDA��O DOS PARAMETROS, INICIANDO A VALIDA��O POR TIPOS.', 1);
    $this->log('');
    
    /**
     * Valida o tipo de cada parametro.
     *
     * @var boolean
     */
    $lValidouTipos = $this->validarTiposParametros($aParametrosSeremValidados);
    
    return true;
  }
  
  /**
   * Valida os Tipos dos parametros informados
   *
   * @param array $aParametros
   * @throws Exception
   * @return boolean
   */
  private function validarTiposParametros($aParametros) {
    
    // Percorre os parametros validando seus respectivos tipos
    foreach ($aParametros as $oParametro) {
      
      $sValor    = $this->aDadosRecebidos[$oParametro->sNome];
      $sMensagem = "O parametro enviado ({$oParametro->sNome} = {$sValor})";
      
      // Se o Valor informado for vazio n�o valida, caso seja obrigat�rio, � validado antes
      if ($sValor == '') {
        continue;
      }
      
      switch ($oParametro->sTipo) {
        
        // Validando o nome pelas regras 1 e 3 do CGM
        case 'nome' :
          
          if (!DBString::isNomeValido($sValor, DBString::NOME_REGRA_1)) {
            throw new Exception("{$sMensagem} foi invalidado pela regra 1 de Nome");
          }
          
          if (!DBString::isNomeValido($sValor, DBString::NOME_REGRA_3)) {
            throw new Exception("{$sMensagem} foi invalidado pela regra 3 de Nome ");
          }
          break;
        
        case 'float' :
          
          if (!DBNumber::isFloat($sValor)) {
            throw new Exception("{$sMensagem} n�o e um valor v�lido");
          }
          break;
        
        case 'date' :
          
          try {
            $DBDate = new DBDate($sValor);
          }catch (ParameterException $msgErro) {
            throw new Exception("{$sMensagem} n�o e uma data v�lida");
          }
          break;
          
        case 'email' :
          
          if (!DBString::isEmail($sValor)) {
            throw new Exception("{$sMensagem} n�o e um e-mail v�lido");
          }
          break;
          
        case 'cpf' :
          
          if (!DBString::isCPF($sValor)) {
            throw new Exception("{$sMensagem} n�o e um CPF v�lido");
          }
          break;
        
        case 'cnpj' :
        
          if (!DBString::isCNPJ($sValor)) {
            throw new Exception("{$sMensagem} n�o e um CNPJ v�lido");
          }
          break;
        
        case 'integer':
          
          if (!DBNumber::isInteger($sValor)) {
            throw new Exception("{$sMensagem} n�o e um Numero Inteiro");
          }
          break;
        
        case 'somenteletras':
          
          if (!DBString::isSomenteLetras($sValor)) {
            throw new Exception("{$sMensagem} n�o e um valor v�lido");
          }
          break;
      }
    }
    
    return true;
  }

  /**
   * Define os valores do construtor para os valores definidos nas tags parametros do XML
   */
  private function definirValoresVariaveis() {
    
    switch ($this->sVersao) {
      
      case '1.2' :
        
        foreach ($this->aClassesExecucao as $oClasseExecucao) {
          
          $sReferencia           = $oClasseExecucao->sReferencia;
          $aParametrosConstrutor = $oClasseExecucao->aParametrosConstrutor;
          
          // Percorre os parametros do construtor trocando as vari�veis pelos valores
          foreach ($aParametrosConstrutor as $iIndiceParametroConstrutor => $sParametroConstrutor ) {
            
            $sValores = $this->atribuirValoresVariaveis($sParametroConstrutor);
            
            $this->aClassesExecucao[$sReferencia]->aParametrosConstrutor[$iIndiceParametroConstrutor] = $sValores;
          }
        }
        
        // Percorre os parametros dos metodos da classe trocando as vari�veis pelos valores
        foreach ($this->aMetodosExecucao        as $oDadosMetodoExecucao) {
          foreach ($oDadosMetodoExecucao        as $iIndiceDadosMetodo => $oDadosMetodo) {
            foreach ($oDadosMetodo->aParametros as $iIndiceMetodo => $sParametroMetodo) {
              
              $sValores = $this->atribuirValoresVariaveis($sParametroMetodo);
              $this->aMetodosExecucao[$oDadosMetodo->sReferencia][$iIndiceDadosMetodo]->aParametrosValores[$sParametroMetodo] = $sValores;
            }
          }
        }
        
        break;
        
      default :
        
        // Percorre os parametros do construtor trocando as variáveis pelos valores
        $aParametrosConstrutor = $this->oClasseExecucao->aParametrosConstrutor;
        
        foreach ($aParametrosConstrutor as $iIndiceParametroConstrutor => $sParametroConstrutor) {
          
          $sValores = $this->atribuirValoresVariaveis($sParametroConstrutor);
          
          $this->oClasseExecucao->aParametrosConstrutor[$iIndiceParametroConstrutor] = $sValores;
        }
        
        // Percorre os parametros dos metodos da classe trocando as vari�veis pelos valores
        foreach ($this->oClasseExecucao->aMetodos as $oDadosMetodo) {
          foreach ($oDadosMetodo->aParametros     as $iIndiceMetodo => $sParametroMetodo) {
            
            $sValores = $this->atribuirValoresVariaveis($sParametroMetodo);
            
            $oDadosMetodo->aParametros[$iIndiceMetodo] = $sValores;
          }
        }
    }
  }

  /**
   * Atribui o valor para as variaveis contidas na string passada por parametro.
   *
   * @param string $sString
   */
  private function atribuirValoresVariaveis($sString) {
  
    if (empty($sString)) {
      return '';
    }
    
    $sChaveParametro = str_replace('$', '', $sString);
    $sValor          = '';
    if (isset($this->aDadosRecebidos[$sChaveParametro])) {
      $sValor = $this->aDadosRecebidos[$sChaveParametro];
    }

    return $sValor;
  }
  
  /**
   * Executa Classes
   *
   * @return mixed
   */
  private function executarClasse() {
    
    $this->log('');
    $this->log('INICIO PROCESSAMENTO DAS CLASSE(S)', 1);
    $this->log('');
    
    switch ($this->sVersao) {
      
      case '1.2' :
        $iTotalMetodos = count($this->aMetodosExecucao);
        $iPosicaoAtual = 0;
        $mResposta     = null;
        
        foreach ($this->aClassesExecucao as $oClasseExecucao) {
          
          require_once ($oClasseExecucao->sCaminhoArquivo);
          
          // Instancia a classe
          $oReflection = new ReflectionClass($oClasseExecucao->sNome);
          
          if (is_array($oClasseExecucao->aParametrosConstrutor)) {
            $oClasse[$oClasseExecucao->sReferencia] = $oReflection->newInstanceArgs($oClasseExecucao->aParametrosConstrutor);
          } else {
            $oClasse[$oClasseExecucao->sReferencia] = $oReflection->newInstance();
          }
        }
        
        // Executa seus M�todos, quando for o ultimo responde ao webservice
        foreach ($this->aMetodosExecucao as $oDadosMetodoExecucao) {
          foreach ($oDadosMetodoExecucao as $oDadosMetodo) {
            
            $sNomeClasse = $this->aClassesExecucao[$oDadosMetodo->sReferencia]->sNome;
            $oMetodo     = new ReflectionMethod($sNomeClasse, $oDadosMetodo->sNome);
            $mResposta   = $oMetodo->invokeArgs($oClasse[$oDadosMetodo->sReferencia], $oDadosMetodo->aParametrosValores);
            $iPosicaoAtual++;
          }
        }
        
        return $mResposta;
        
        break;
        
      default :
        // Carrega o arquivo da classe informada no XML.
        require_once($this->oClasseExecucao->sCaminhoArquivo);
        
        // Instancia a classe
        $oReflection     = new ReflectionClass($this->oClasseExecucao->sNome);
        $oClasse         = $oReflection->newInstanceArgs($this->oClasseExecucao->aParametrosConstrutor);
        $iTotalMetodos   = count($this->oClasseExecucao->aMetodos);
        
        // Executa seus M�todos, quando for o ultimo responde ao webservice
        foreach ($this->oClasseExecucao->aMetodos as $iIndiceMetodo => $oDadosMetodo ) {
          
          $oMetodo       = new ReflectionMethod( $this->oClasseExecucao->sNome, $oDadosMetodo->sNome);
          $mResposta     = $oMetodo->invokeArgs($oClasse, $oDadosMetodo->aParametros);
          $iPosicaoAtual = $iIndiceMetodo + 1;
          
          if ($iPosicaoAtual == $iTotalMetodos) {
            return $mResposta;
          }
        }
    }
    
    $this->log('');
    $this->log('FIM PROCESSAMENTO DAS CLASSE(S)', 1);
    $this->log('');
  }
  
  /**
   * Escreve Log do Processamento
   *
   * @param String  $sMensagem - Mensagem de Log
   * @param Integer $iTipoLog  - Tipo de Log a Ser Utilizado
   * @access public
   * @static
   */
  public function log($sMensagem, $iNivel = 1, $iTipoLog = DBLog::LOG_INFO){
    
    $oLog = $this->olog;
    return $oLog->escreverLog(str_repeat('    ', $iNivel - 1) . $sMensagem, $iTipoLog);
  }
}