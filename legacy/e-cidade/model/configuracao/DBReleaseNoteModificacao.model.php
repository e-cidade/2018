<?php

class DBReleaseNoteModificacao extends DBReleaseNote {
  
  private $aListaArquivos;

  private $iIndiceAtual;

  private $oPluginAtual;

  protected $sVersaoSistema;

  private static $sArquivoMenusPlugins = 'release_notes/plugins/menus.json';

  public function __construct($idUsuario, $sNomeArquivo = null, $sNomeArquivoAtual = null, $sVersao = null) {
    parent::__construct($idUsuario, $sNomeArquivo, $sNomeArquivoAtual, $sVersao);

    require('libs/db_acessa.php');
    $this->sVersaoSistema = 'v2.' . $db_fonte_codversao . '.' . $db_fonte_codrelease;

    $this->carregarListaArquivos();
    $this->setIndiceAtual($this->procurarIndiceAtual());        

    $sNomePlugin = $this->extrairNomePlugin($this->sNomeArquivoAtual);

    if (!empty($sNomePlugin)) {
      $this->setPluginAtual(new Plugin(null, $sNomePlugin));
    } 
  }

  public function check() {

    $lidos = $this->getMudancasLidas();
    $arquivos = $this->getListaArquivos();
    return count($lidos) != count($arquivos);
  }

  public function getCaminhoArquivo($sNomeArquivo = null, $sVersao = null) {
    return $this->sNomeArquivoAtual;
  }

  /**
   * @inherited
   */
  public function getVersaoFormatada() {

    if ($this->oPluginAtual !== null || !isset($this->aListaArquivos[$this->iIndiceAtual])) {
      return $this->sVersaoSistema;
    }
      
    return $this->extrairVersao($this->aListaArquivos[$this->iIndiceAtual]);
  }

  /**
   * Método responsavel por retornar a primeira versão que ainda não foi lida
   */
  public function getPrimeiraVersaoNaoLida() {}

  public function procurarPrimeiroArquivoNaoLido() {

    $aArquivosLidos = $this->getMudancasLidas();

    // nenhum arquivo lido, retorna o primeiro da lista
    if (empty($aArquivosLidos) && !empty($this->aListaArquivos)) {      
      return $this->sNomeArquivoAtual = $this->aListaArquivos[0];
    }

    foreach ($this->aListaArquivos as $sCaminhoReleaseNote) {

      // arquivo ja lido
      if (in_array($sCaminhoReleaseNote, $aArquivosLidos)) {
        continue;
      }

      // primeiro arquivo nao lido
      return $this->sNomeArquivoAtual = $sCaminhoReleaseNote;
    }

    // ultimo arquivo lido
    return $this->sNomeArquivoAtual = end($this->aListaArquivos);
  }

  /**
   * Busca a versão no caminho de um release e retorna a mesma.
   * @param  Caminho de release note $path 
   * @return string       Versão encontrada
   */
  public function extrairVersao($path) {
    preg_match("/^.*release_notes\/(.*)\/{$this->sNomeArquivo}(_.*\d{2})?\.md$/", $path, $match);
    return $match[1];
  }

  public function getArquivosLidosPlugins($iSorting = self::SORT_ASC) {

    $aPlugins = $this->getPlugins();
    $aArquivos = array();

    if (empty($aPlugins)) {
      return array();
    }

    foreach ($aPlugins as $oPlugin) {

      $oDBReleaseNote = new DBReleaseNotePlugin($this->iUsuario, $this->sNomeArquivo, $this->sNomeArquivoAtual, null, $oPlugin);
      $aMudancas = $oDBReleaseNote->getMudancasLidas($iSorting);

      foreach ($aMudancas as $oMudanca) {
        $aArquivos[] = sprintf(
          "plugins/%s/release_notes/%s/%s.md", 
          $oPlugin->getNome(),
          $oMudanca->sVersao, 
          $oMudanca->sNomeArquivo 
        );
      }
    }

    if ($iSorting === self::SORT_DESC) {
      $aArquivos = array_reverse($aArquivos);
    }

    return $aArquivos;
  }

  public function getArquivosLidosSistema($iSorting = self::SORT_ASC) {

    $sSql  = "select *                                                         ";
    $sSql .= "  from db_releasenotes                                           ";
    $sSql .= " inner join db_versao on db30_codver = db147_db_versao           ";
    $sSql .= " where db147_nomearquivo like '{$this->sNomeArquivo}%'           ";
    $sSql .= "  and db147_id_usuario = {$this->iUsuario}                       ";
    $sSql .= " order by db147_db_versao " . ($iSorting == 1 ? "desc" : "asc");

    $rsMudancaLidas = db_query($sSql);

    if  (!$rsMudancaLidas) {
      throw new DBException("Erro ao buscar as mudanças lidas do usuário.");
    }

    if (pg_num_rows($rsMudancaLidas) == 0) {
      return array();
    }

    $aArquivos = array();
    $aMudancas = db_utils::getCollectionByRecord($rsMudancaLidas);

    foreach ($aMudancas as $oMudanca) {
      $aArquivos[] = sprintf(
        "release_notes/v2.%s.%s/%s.md", 
        $oMudanca->db30_codversao, 
        $oMudanca->db30_codrelease, 
        $oMudanca->db147_nomearquivo
      );
    }

    return $aArquivos;
  }

  public function getMudancasLidas($iSorting = self::SORT_ASC) {

    $aArquivosSistema = $this->getArquivosLidosSistema($iSorting);
    $aArquivosPlugins = $this->getArquivosLidosPlugins($iSorting);

    return array_merge($aArquivosSistema, $aArquivosPlugins);
  }

  /**
   * @inherited
   */
  public function filtrarVersao($sVersao) {}

  /**
   * @param stdClass[]
   * @return boolean
   */
  public function marcarComoLido($aArquivosLidos) {

    $aArquivosSistema = array();
    $aArquivosPlugins = array();

    foreach ($aArquivosLidos as $oDadoArquivo) {

      $sNomePlugin = $this->extrairNomePlugin($oDadoArquivo->sNomeArquivo);
      preg_match('/release_notes\/.*\/(.*)\.md$/', $oDadoArquivo->sNomeArquivo, $match);
      $oDadoArquivo->sNomeArquivo = $match[1];

      if (empty($sNomePlugin)) {
        $aArquivosSistema[] = $oDadoArquivo;
      } else {
        $aArquivosPlugins[] = $oDadoArquivo;
      }
    }

    if (!empty($aArquivosSistema)) {
      $oDBReleaseNoteSistema = new DBReleaseNoteSistema($this->iUsuario, $this->sNomeArquivo, null, null);
      $oDBReleaseNoteSistema->marcarComoLido($aArquivosSistema);
    }

    if (!empty($aArquivosPlugins)) {

      foreach ($this->getPlugins() as $oPlugin) {
        $oDBReleaseNotePlugin = new DBReleaseNotePlugin($this->iUsuario, $this->sNomeArquivo, $this->sNomeArquivoAtual, null, $oPlugin);
        $oDBReleaseNotePlugin->marcarComoLido($aArquivosPlugins);
      }
    }

    return true;
  }

  // paginacao das versoes
  public function getProximaVersao() {}
  public function getVersaoAnterior() {}

  public function getVersaoSistema() {
    return $this->sVersaoSistema;
  }

  // paginação interna das versoes
  public function getArquivoAnterior() {

    if (!$this->lSomenteNaoLidos && isset($this->aListaArquivos[$this->iIndiceAtual-1])) {
      return $this->aListaArquivos[$this->iIndiceAtual-1];
    }

    $aArquivosLidos = $this->getMudancasLidas();
    $aProximos = array_reverse(array_slice($this->aListaArquivos, 0, $this->iIndiceAtual));

    foreach ($aProximos as $sArquivo) {
        
      if (!in_array($sArquivo, $aArquivosLidos)) {
        return $sArquivo;
      }
    }

    return "";
  }

  public function getProximoArquivo() {

    if (!$this->lSomenteNaoLidos &&  isset($this->aListaArquivos[$this->iIndiceAtual+1])) {
      return $this->aListaArquivos[$this->iIndiceAtual+1];
    } 

    $aArquivosLidos = $this->getMudancasLidas();
    $aProximos = array_slice($this->aListaArquivos, $this->iIndiceAtual+1);

    foreach ($aProximos as $sArquivo) {
        
      if (!in_array($sArquivo, $aArquivosLidos)) {
        return $sArquivo;
      }
    }

    return "";
  }

  //metodos proprios
  
  public function setIndiceAtual($iIndiceAtual) {
    $this->iIndiceAtual = $iIndiceAtual;
  }
  
  public function setPluginAtual(Plugin $oPlugin) {
    $this->oPluginAtual = $oPlugin;
  }

  public function procurarIndiceAtual() {

    if (empty($this->sNomeArquivoAtual) || $this->sNomeArquivoAtual == $this->sNomeArquivo) {
      $this->procurarPrimeiroArquivoNaoLido();
    }

    return array_search($this->sNomeArquivoAtual, $this->getListaArquivos()) ?: 0;
  }
  
  public function carregarListaArquivos() {
    
    $listaSistema = $this->getListaArquivosSistema();
    $listaPlugins = $this->getListaArquivosPlugins();

    $this->aListaArquivos = array_merge($listaSistema, $listaPlugins);
  }

  public function getListaArquivos() {
    return $this->aListaArquivos;    
  }

  /**
   * Retorna a lista de arquivos de release notes do sistema
   * @return array
   */
  private function getListaArquivosSistema() {
    $lista = $this->getArquivosReleaseNotes('release_notes');    

    $_this = $this;
    $lista = array_filter($lista, function($arquivo) use ($_this) {
      return $_this->extrairVersao($arquivo) <= $_this->getVersaoSistema();
    });

    return $lista;
  }

  /**
   * Retorna a lista de arquivos de release notes dos plugins que modification o menu atual
   * @return array
   */
  private function getListaArquivosPlugins() {

    $lista = array();

    $aPlugins = $this->getPlugins();

    foreach ($aPlugins as $oPlugin) {      
      $lista = array_merge($lista, $this->getArquivosReleaseNotes("plugins/{$oPlugin->getNome()}/release_notes"));
    }

    return $lista;
  }

  /**
   * Método responsavel por procurar release notes de um menu em diretório especificado por parametro
   * @param  string $dir Caminho no qual será procurado os release notes
   * @return array      Array de caminhos dos arquivos
   */
  private function getArquivosReleaseNotes($dir) {
    $arquivos =  glob("$dir/*/{$this->sNomeArquivo}*.md");
    return array_values(preg_grep("/^.*{$this->sNomeArquivo}(_.*\d{2})?\.md$/", $arquivos));
  }

  /**
   * Retorna um array do instancia de plugins de modificam/interferam no funcionamento do menu atual
   * @return array Array de instancias de plugins
   */
  private function getPlugins() {

    $aMenus = static::buscarMenusPlugins();
    $idMenu = $this->sNomeArquivo;
    $aPlugins = array();

    if (empty($aMenus[$idMenu])) {
      return $aPlugins;
    }

    foreach ($aMenus[$idMenu] as $sNomePlugin) {

      $oPlugin = new Plugin(null, $sNomePlugin); 

      if ($oPlugin->isAtivo()) {
        $aPlugins[] = $oPlugin;
      }
    }

    return $aPlugins;
  }

  public function extrairNomePlugin($sCaminhoArquivo) {
    
    $aMatches = array();
    $result = preg_match('/^plugins\/(.*)\/release_notes.*$/', $sCaminhoArquivo, $aMatches);
    return $result ? $aMatches[1] : false;
  }

  public function buildData() {
  
    $oRetorno = parent::buildData();
    $oRetorno->sNomePlugin = $this->oPluginAtual ? $this->oPluginAtual->getNome() : null;
    $oRetorno->sVersaoAtual = $this->extrairVersao($this->aListaArquivos[$this->iIndiceAtual]);
    return $oRetorno;
  }

  public function render() { 

    if (empty($this->sNomeArquivo)) {
      return;
    }

    if ($this->check()) {

      $sScriptChangelog  = "<script src=\"scripts/classes/configuracao/DBViewReleaseNote.classe.js\" type=\"text/javascript\"></script>\n";
      $sScriptChangelog .= "<script type=\"text/javascript\">\n";
      $sScriptChangelog .= " DBViewReleaseNote.build(null, true, DBViewReleaseNote.TIPO_MODIFICACAO); \n";
      $sScriptChangelog .= "</script>";

      return $sScriptChangelog;
    }    

  }

  /**
   * @return array
   */
  public static function buscarMenusPlugins() {

    if (!is_readable(static::$sArquivoMenusPlugins)) {
      return array();
    }
    
    $aMenus = json_decode(file_get_contents(static::$sArquivoMenusPlugins), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception('Erro ao ler arquivo json: ' . static::$sArquivoMenusPlugins);
    }

    return $aMenus;
  }

  /**
   * @param array $aMenus [id-menu][id-plugin]
   * @return boolean
   */
  public static function salvarMenusPlugins(array $aMenus) {

    $sDiretorio = dirname(static::$sArquivoMenusPlugins); 
    if (!is_dir($sDiretorio) && !mkdir($sDiretorio, 0775, true)) {
      throw new BusinessException('Erro ao criar diretório: ' . $sDiretorio);
    }

    if (!file_put_contents(static::$sArquivoMenusPlugins, json_encode($aMenus))) {
      throw new BusinessException('Erro ao salvar arquivo: ' . static::$sArquivoMenusPlugins);
    }

    return true;
  } 

  public static function removerPluginMenusPlugins(Plugin $oPlugin) {

    $aMenusPlugins = static::buscarMenusPlugins();
    $lSalvar = false;

    foreach($aMenusPlugins as $idMenu => & $aPlugins) {

      $iKey = array_search($oPlugin->getNome(), $aPlugins);

      if ($iKey !== false) {
        array_splice($aPlugins, $iKey, 1);
        $lSalvar = true;
      }

      if (empty($aPlugins)) {
        unset($aMenusPlugins[$idMenu]);
      }
    }

    if ($lSalvar) {
      static::salvarMenusPlugins($aMenusPlugins);
    }

    return true;
  }

  public static function getPluginsMenu($idMenu) {

    $aMenus = static::buscarMenusPlugins();
    return isset($aMenus[$idMenu]) ? $aMenus[$idMenu] : array();
  }

}
