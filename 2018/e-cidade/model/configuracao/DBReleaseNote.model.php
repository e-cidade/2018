<?php

abstract class DBReleaseNote {

  const SORT_ASC = 0;
  const SORT_DESC = 1;

  const TIPO_SISTEMA = 'sistema';
  const TIPO_PLUGIN = 'plugin';
  const TIPO_MODIFICACAO = 'modificacao';
  const TIPO_PREVIA = 'previa';

  protected $iUsuario;
  protected $sNomeArquivo;
  protected $sNomeArquivoAtual;
  protected $sVersao;
  protected $lSomenteNaoLidos = false;

  private $dirBase;

  public function __construct($idUsuario, $sNomeArquivo = null, $sNomeArquivoAtual = null, $sVersao = null) {

    $this->iUsuario  = $idUsuario;
    $this->sNomeArquivo = $sNomeArquivo;
    $this->sNomeArquivoAtual = $sNomeArquivoAtual ?: $sNomeArquivo;
    $this->sVersao = $sVersao;   
  }

  /**
   * Método resposável por marcar um release note como lido para controle de mostrar ou não ao usuario
   * @return void
   */
  abstract public function marcarComoLido($aArquivosLidos);

  /**
   * Método responsável por retornar a versão em que o release note se aplica
   * de forma formatada
   * @return {string}
   */
  abstract public function getVersaoFormatada();

  /**
   * Método responsável por filtar a versão passado por parâmetro,
   * (funciona como um reduce para o método getVersoesPorNomeArquivo)
   * @param  {string} $sVersao
   * @return {boolean}
   */
  abstract public function filtrarVersao($sVersao);

  /**
   * Método responsável por retornar os dados de release notes lidos
   * @param  {Integer} $iSorting ordenacao
   * @return Object[]           Coleção de dados de release notes lidos
   *
   * @example Formato de retorno
   *           array(
   *             stdClass(
   *               "sVersao" => "xxx",
   *               "iIdUsuario" => "99999999",
   *               "iIdMenu" => "xxxxxxxxxx"
   *             ),
   *             stdClass(
   *               "sVersao" => "yyy",
   *               "iIdUsuario" => "8888888888",
   *               "sNomeArquivo" => "yyyyyyy"
   *             )
   *           )
   */
  abstract public function getMudancasLidas($iSorting = self::SORT_ASC);

  /**
   * Verifica se o changelog informado já foi lido pelo usuário.
   * @return boolean True se não foi lido, False se foi lido ou não existe.
   */
  public function check() {

    $sVersao = $this->getVersaoFormatada();

    if ( empty($sVersao) ) {
      return false;
    }

    $aMudancasLidas = $this->getMudancasLidas();

    $iTotalLidos = count($aMudancasLidas);
    $aReleaseNoteNomeArquivo = $this->getVersoesPorNomeArquivo();

    return $iTotalLidos != count($aReleaseNoteNomeArquivo);
  }

  /**
   * Método responsável por retornar o caminho completo do arquivo md que será convertido
   * @param  string $sNomeArquivo - nome do arquivo para buscar caminho do arquivo
   * @return {string}
   */
  public function getCaminhoArquivo($sNomeArquivo = null, $sVersao = null) {

    $sVersao = $sVersao ?: $this->getVersaoFormatada();
    $sNomeArquivo = $sNomeArquivo ?: $this->sNomeArquivoAtual;

    $sCaminhoArquivo = $this->getDirBase() . "/{$sVersao}/$sNomeArquivo.md";

    if (file_exists($sCaminhoArquivo)) {
      return $sCaminhoArquivo;
    }

    $sCaminhoArquivo = $this->getDirBase() . "/{$sVersao}/$sNomeArquivo" . "_01.md";

    if (file_exists($sCaminhoArquivo)) {
      
      if ($this->sNomeArquivo == $this->sNomeArquivoAtual)
        $this->sNomeArquivoAtual = $sNomeArquivo . "_01";

      return $sCaminhoArquivo;
    }

    return false;
  } 

  protected function getVersoesPorNomeArquivo($iSorting = self::SORT_ASC) {

    if (!is_dir($this->getDirBase())) {
      return array();
    }

    $aVersoes = scandir($this->getDirBase(), $iSorting);

    $aRetornoReleaseNotes = array();

    foreach ($aVersoes as $sVersao) {

      if ( is_dir($sVersao) ) {
        continue;
      }

      if ( !$this->filtrarVersao($sVersao) ) {
        continue;
      }

      $sCaminhoArquivo = $this->getCaminhoArquivo($this->sNomeArquivo, $sVersao);

      if (false !== $sCaminhoArquivo) {
        $aRetornoReleaseNotes[] = $sVersao;
      }

    }
    
    return $aRetornoReleaseNotes;
  }

  /**
   * @param boolean $lSomenteNaoLidos
   */
  public function setSomenteNaoLidos($lSomenteNaoLidos) {
    $this->lSomenteNaoLidos = (bool) $lSomenteNaoLidos;
  }

  /**
   * Retorna a versão do proximo release do mesmo menu da instancia
   * @param  boolean $lSomenteNaoLidos
   * @return string
   */
  public function getProximaVersao() {
    return $this->getVersaoOrdenada(static::SORT_ASC, $this->lSomenteNaoLidos);
  }

  public function getVersaoAnterior() {
    return $this->getVersaoOrdenada(static::SORT_DESC, $this->lSomenteNaoLidos);
  }

  public function getArquivoAtual() {
    return $this->sNomeArquivoAtual;
  }

  public function getArquivoAnterior() {
    return $this->getArquivoOrdenado(self::SORT_DESC);
  }

  public function getProximoArquivo() {
    return $this->getArquivoOrdenado(self::SORT_ASC);
  }

  public function getArquivoOrdenado($iSorting) {

    $sVersao = $this->getVersaoFormatada();

    $aArquivos = scandir($this->getDirBase() . "/" . $sVersao, $iSorting);

    $iInicio = -1;
    $lArquivoAtual = false;

    foreach ($aArquivos as $iIndex => $sArquivo) {
      
      $lProximoArquivo = strpos($sArquivo, $this->sNomeArquivo) === 0;

      if ( $lProximoArquivo && $lArquivoAtual) {
        $iInicio = $iIndex;
        break;
      }

      if ( $sArquivo == ($this->sNomeArquivoAtual . ".md") ) {
        $lArquivoAtual = true;
      }

    }

    if ( isset($aArquivos[$iInicio]) ) {
      return trim($aArquivos[$iInicio], '.md');
    }

    return "";
  }

  /**
   * Método responsável por converter o arquivo md e retornar o conteudo html
   * @return {string}
   */
  public function getContent() {

    $sCaminhoArquivo = $this->getCaminhoArquivo();

    if ( false === $sCaminhoArquivo ) {
      return "";
    }

    $sConteudoArquivoMD = file_get_contents($sCaminhoArquivo);

    ini_set('pcre.backtrack_limit', '1000000');

    require_once(modification("ext/php/Michelf/MarkdownExtra.inc.php"));

    $sContent = \Michelf\MarkdownExtra::defaultTransform($sConteudoArquivoMD);

    return $sContent;
  }    

  public function setDirBase($dirBase) {
    $this->dirBase = $dirBase;
  }

  public function getDirBase() {
    return $this->dirBase;
  }

  /**
   * Responsavel por retornar a primeira ocorrencia na lista de versao, de acordo com a ordenacao
   * @return string
   */
  public function getVersaoOrdenada($iSorting, $lSomenteNaoLido = true) {
    
    $aVersoes = $this->getVersoesPorNomeArquivo($iSorting);
    $aVersoesLidas = $this->getMudancasLidas($iSorting);
    
    $iInicio = 0;

    // Procura o indice da primeira ocorrencia dos release notes da versão requerida
    foreach ($aVersoes as $iIndexVersoes => $sVersao) {

      if ( $sVersao == $this->getVersaoFormatada() ) {
        $iInicio = $iIndexVersoes;
        break;
      }
    }


    // Caso seja somente nao lidos, retorna a proxima ocorrencia se existir
    if (!$lSomenteNaoLido) {
      return isset($aVersoes[$iInicio+1]) ? $aVersoes[$iInicio+1] : "";
    }

    $sVersao = "";

    // procura a proxima versao que precisa ser mostrada de acordo com os lidos
    for ($iIndexVersoes = ($iInicio+1); $iIndexVersoes < count($aVersoes); $iIndexVersoes++) {

      $sVersao = $aVersoes[$iIndexVersoes];
      foreach ($aVersoesLidas as $oDadoVersaoLida) {

        if ( $sVersao == $oDadoVersaoLida->sVersao ) {
          $sVersao = "";    
          continue 2;
        }
      }

      break;
    }

    return $sVersao;
  }

  public function getPrimeiraVersaoNaoLida() {

    $aVersoes = $this->getVersoesPorNomeArquivo();

    if (empty($aVersoes)) {
      return false;
    }

    $aArquivosLidas = $this->getMudancasLidas();

    if (empty($aArquivosLidas)) {
      return $aVersoes[0];
    }

    $aVersoesLidas = array();

    foreach ($aArquivosLidas as $aVersao) {

      if (!in_array($aVersao->sVersao, $aVersoesLidas)) {
        $aVersoesLidas[] = $aVersao->sVersao;
      }

    }

    /**
     * Procura um release note que nao foi lido ainda.
     */
    foreach ($aVersoes as $iIndex => $sVersao) {

      if (isset ($aVersoesLidas[$iIndex]) ) {

        $sVersaoMudanca = $aVersoesLidas[$iIndex];

        if ( $sVersao != $sVersaoMudanca ) {
          return $sVersao;
        }

      } else {
        return $sVersao;
      }

    }

    return end($aVersoes);
  }
  
  /**
   * @param stdClass $oParam
   * @return stdClass
   */
  public function buildData() {

    $oRetorno = new stdClass();

    $oRetorno->sArquivoAnterior = $this->getArquivoAnterior();
    $oRetorno->sArquivoAtual = $this->getArquivoAtual();
    $oRetorno->sProximoArquivo = $this->getProximoArquivo();

    $oRetorno->sProximaVersao = $this->getProximaVersao();
    $oRetorno->sVersaoAtual = $this->getVersaoFormatada();
    $oRetorno->sVersaoTela = $oRetorno->sVersaoAtual;
    $oRetorno->sVersaoAnterior = $this->getVersaoAnterior();

    $oUsuario = new UsuarioSistema($this->iUsuario);
    $oRetorno->sNomeUsuario = urlencode($oUsuario->getNome());

    $oRetorno->sContent = $this->getContent();

    return $oRetorno;
  }


  public static function createInstance($sTipo, $idUsuario, $sNomeArquivo = null, $sNomeArquivoAtual = null, $sVersao = null) {

    switch($sTipo) {

      case self::TIPO_SISTEMA :
        return new DBReleaseNoteSistema($idUsuario, $sNomeArquivo, $sNomeArquivoAtual, $sVersao);
      break;

      case self::TIPO_PLUGIN :
        return new DBReleaseNotePlugin($idUsuario, $sNomeArquivo, $sNomeArquivoAtual, $sVersao);
      break;

      case self::TIPO_MODIFICACAO :
        return new DBReleaseNoteModificacao($idUsuario, $sNomeArquivo, $sNomeArquivoAtual, $sVersao);
      break;

      case self::TIPO_PREVIA :
        return new DBReleaseNotePrevia($idUsuario, $sNomeArquivo, $sNomeArquivoAtual, $sVersao);
      break;
    }

    throw new BusinessException('Não foi possivel criar instancia de DBReleaseNote');
  }

  /**
   * @param stdClass $oParam
   * @return stdClass
   */
  public static function buildFromParams(stdClass $oParam) {

    $aPadrao = array(
      'sTipo' => null, 'idUsuario' => null, 'sNomeArquivo' => null, 
      'sArquivoAtual' => null, 'sVersao' => null, 'lSomenteNaoLidos' => false,
    );
    $oParam = (object) array_merge($aPadrao, (array) $oParam);

    $oDBReleaseNote = DBReleaseNote::createInstance(
      $oParam->sTipo, $oParam->idUsuario, $oParam->sNomeArquivo, $oParam->sArquivoAtual, $oParam->sVersao
    );

    if (isset($oParam->lSomenteNaoLidos)) {
      $oDBReleaseNote->setSomenteNaoLidos($oParam->lSomenteNaoLidos);
    }

    return $oDBReleaseNote;
  }

  /**
   * @param integer $idMenu
   * @return string
   */
  public static function buscarTipo($idMenu) {

    if (PluginService::getPluginAtual($idMenu) !== null) {
      return DBReleaseNote::TIPO_PLUGIN;
    }

    if (count(DBReleaseNoteModificacao::getPluginsMenu($idMenu)) > 0) {
      return DBReleaseNote::TIPO_MODIFICACAO;
    }    

    return static::TIPO_SISTEMA;
  }

}
