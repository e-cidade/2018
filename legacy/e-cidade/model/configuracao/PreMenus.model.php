<?php

class PreMenus {

  private $aTabelasPreMenus = array ( 'db_sysarquivo',
                                      'db_sysarqmod',
                                      'db_sysarqarq',
                                      'db_syscampo',
                                      'db_syscampodef',
                                      'db_syscampodep',
                                      'db_sysarqcamp',
                                      'db_sysprikey',
                                      'db_sysforkey',
                                      'db_sysfuncoes',
                                      'db_sysfuncoesparam',
                                      'db_sysindices',
                                      'db_syscadind',
                                      'db_syssequencia',
                                      'db_itensmenu',
                                      'db_itensfilho',
                                      'db_menu',
                                      'db_modulos',
                                      'atendcadarea',
                                      'atendcadareamod',
                                      'db_arquivos',
                                      'db_processa',
                                      'db_sysmodulo',
                                      'db_layouttxt',
                                      'db_layoutlinha',
                                      'db_layoutcampos',
                                      'avaliacaoperguntaopcaolayoutcampo',
                                      'avaliacaoperguntaopcao',
                                      'avaliacaopergunta',
                                      'atendcadareamod',
                                      'atendcadarea',
                                      'db_documentopadrao',
                                      'db_paragrafopadrao',
                                      'db_docparagpadrao',
                                      'orcparamseqorcparamseqcoluna',
                                      'orcparamseqcoluna',
                                      'avaliacao',
                                      'avaliacaogrupopergunta',
                                      'avaliacaogrupoperguntaresposta',
                                      'avaliacaogruporesposta',
                                      'avaliacaopergunta',
                                      'avaliacaoresposta',
                                      'avaliacaotipo',
                                      'avaliacaotiporesposta',
                                      'avaliacaoquestionariointerno',
                                      'caddocumento',
                                      'caddocumentoatributo',
                                      'db_formulas',
                                      'avaliacaoperguntadb_formulas',
                                      'avaliacaotiporesposta',
                                  );

  private $aOperacoes = array('insert',
                              'update',
                              'delete'
                              );

  private $sArquivo = null;

  private $sDiretorio = ECIDADE_PATH;

  private static $oInstancia;

  private function __construct () {

    $this->sArquivo = $this->sDiretorio . "tmp/pre_menu_".date('Ymd',db_getsession('DB_datausu',false)).".sql";
  }

  /**
   * Singleton para o PreMenus, utiliza mesma instancia do objeto
   *
   * @static
   * @access public
   * @return TraceLog
   */
  public static function getInstance() {

    if ( empty(PreMenus::$oInstancia) ) {
      PreMenus::$oInstancia = new PreMenus();
    }
    return PreMenus::$oInstancia;
  }

  private function write($sSql) {

    $rsFile = fopen($this->sArquivo, 'a');
    fputs($rsFile, $sSql. "\n");
    fclose($rsFile);
    return;
  }

  public function setDiretorio( $sNovoDiretorio = null ) {

    if ( !empty($sNovoDiretorio) ) {
      $this->sDiretorio = $sNovoDiretorio;
    }
  }

  public function setArquivo($sArquivo) {
    $this->sArquivo = $sArquivo;
  }

  public function setTabelas(Array $tables) {
    $this->aTabelasPreMenus = $tables;
  }

  public function verificaInstrucaoSql ( $sSql ) {

    $sInstrucaoSql  =  preg_replace('/\s{2,}/', ' ', trim($sSql));
    $sInstrucaoSql .= ';';

    $aInstrucao     =  explode(' ', $sInstrucaoSql);

    if (in_array($aInstrucao[0], $this->aOperacoes)) {

      switch ($aInstrucao[0]) {

        case 'insert':
        case 'delete':

          $aTabela = explode('(', $aInstrucao[2]);
          $sTabela = $aTabela[0];

          if ( in_array($sTabela, $this->aTabelasPreMenus) ) {
            $this->write($sInstrucaoSql);
          }

          break;

        case 'update':

          if ( in_array($aInstrucao[1], $this->aTabelasPreMenus) ) {
            $this->write($sInstrucaoSql);
          }
          break;
      }
    }
  }

}