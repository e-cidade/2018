<?php 

class DBReleaseNoteSistema extends DBReleaseNote {

  protected $sVersaoSistema;

  public function __construct($idUsuario, $sNomeArquivo = '', $sNomeArquivoAtual = null, $sVersao = null) {

    parent::__construct($idUsuario, $sNomeArquivo, $sNomeArquivoAtual, $sVersao);
    $this->setDirBase('release_notes');

    require('libs/db_acessa.php');
    $this->sVersaoSistema = 'v2.' . $db_fonte_codversao . '.' . $db_fonte_codrelease;
  }  

  /**
   * @inherited
   */
  public function getVersaoFormatada() {

    if (empty($this->sVersao)) {
      $this->sVersao = $this->getPrimeiraVersaoNaoLida();
    }

    if (empty($this->sVersao)) {
      return null;
    } 

    return 'v2.' . preg_replace('/^v2\./', '', $this->sVersao);
  }

  /**
   * @inherited
   */
  public function filtrarVersao($sVersao) {
    return $sVersao <= $this->sVersaoSistema;
  }

  public function getDadosReleaseNote($iSorting = self::SORT_ASC) {

    $sSql  = "select *                                                         ";
    $sSql .= "  from db_releasenotes                                           ";
    $sSql .= " inner join db_versao on db30_codver = db147_db_versao           ";
    $sSql .= " where db147_nomearquivo ~ '^{$this->sNomeArquivo}(_.*[0-9]{2})?$'           ";
    $sSql .= "  and db147_id_usuario = {$this->iUsuario}                       ";
    $sSql .= " order by db147_db_versao " . ($iSorting == 1 ? "desc" : "asc");

    $rsCheckReleaseNote = db_query($sSql);

    if  (!$rsCheckReleaseNote) {
      throw new DBException("Erro ao buscar as mudanças lidas do usuário.");
    }

    return $rsCheckReleaseNote;
  }

  /**
   * @inherited
   */
  public function getMudancasLidas($iSorting = self::SORT_ASC) {

    $aMudancasLidas = array();

    $rsMudancaLidas = $this->getDadosReleaseNote($iSorting);

    if (!$rsMudancaLidas || pg_num_rows($rsMudancaLidas) == 0 ) {
      return $aMudancasLidas;
    }      

    $aMudancas = db_utils::getCollectionByRecord($rsMudancaLidas);

    foreach ($aMudancas as $oMudanca) {
      
      $oMudancaLida = new stdClass();
      $oMudancaLida->sVersao = "v2.{$oMudanca->db30_codversao}.{$oMudanca->db30_codrelease}";
      $oMudancaLida->iIdUsuario = $oMudanca->db147_id_usuario;
      $oMudancaLida->sNomeArquivo = $oMudanca->db147_nomearquivo;

      $aMudancasLidas[] = $oMudancaLida;

    }

    return $aMudancasLidas;
  }


  /**
   * @inherited
   */
  public function marcarComoLido($aArquivosLidos) {

    if (empty($aArquivosLidos)) {
      return;
    }

    $sSqlDelete = "delete from db_releasenotes where db147_id_usuario = {$this->iUsuario} ";

    $sSqlInsert = "insert into db_releasenotes (db147_sequencial, db147_nomearquivo, db147_db_versao, db147_id_usuario) values ";

    $aArquivosInserir = array();
    $aVersoesDelete = array();
    $aSqlInsert = array();
    
    foreach ($aArquivosLidos as $oArquivoLido) {

      $aVersao = explode(".", $oArquivoLido->sVersao);
      $iCodVersao = $aVersao[1];
      $iCodRelease = $aVersao[2];

      $sSqlBuscaVersoes  = "select db30_codver from db_versao                                   \n";
      $sSqlBuscaVersoes .= " where db30_codversao  = " . $iCodVersao  . "\n";
      $sSqlBuscaVersoes .= "   and db30_codrelease = " . $iCodRelease . "\n";

      $rsBuscaVersoes = db_query($sSqlBuscaVersoes);

      if (!$rsBuscaVersoes || pg_num_rows($rsBuscaVersoes) == 0) {
        throw new DBException("Erro ao buscar as versões do sistema.");
      }

      $oVersao = db_utils::fieldsMemory($rsBuscaVersoes, 0);

      $aSqlInsert[] =  "(nextval('db_releasenotes_db147_sequencial_seq'), '{$oArquivoLido->sNomeArquivo}', {$oVersao->db30_codver}, {$this->iUsuario})";

      $aArquivosInserir[] = "'$oArquivoLido->sNomeArquivo'";
      $aVersoesDelete[]   = $oVersao->db30_codver;
    }

    if (empty($aSqlInsert)) {
      throw new DBException("Não foi possível marcar as mudanças como lido.");
    }

    $aVersoesDelete = array_unique($aVersoesDelete);

    if ( !empty($aArquivosInserir) && !empty($aVersoesDelete) ) {
      $sSqlDelete .= " and db147_nomearquivo in (" . implode(",", $aArquivosInserir) . ") \n";
      $sSqlDelete .= " and db147_db_versao   in (" . implode(",", $aVersoesDelete)   . ");\n";
    } else {
      $sSqlDelete = "";
    }


    $sSqlInsert .= implode(",", $aSqlInsert);
    $rsInsert = db_query($sSqlDelete . $sSqlInsert);

    if (!$rsInsert) {
      throw new DBException("Não foi possível marcar as mudanças como lido.");
    }
  
  }

  public function render() {

    if (empty($this->sNomeArquivo)) {
      return;
    }

    if ($this->check()) {

      $sScriptChangelog  = "<script src=\"scripts/classes/configuracao/DBViewReleaseNote.classe.js\" type=\"text/javascript\"></script>\n";
      $sScriptChangelog .= "<script type=\"text/javascript\">\n";
      $sScriptChangelog .= " DBViewReleaseNote.build(null, true, DBViewReleaseNote.TIPO_SISTEMA); \n";
      $sScriptChangelog .= "</script>";

      return $sScriptChangelog;
    }    

  }

}
