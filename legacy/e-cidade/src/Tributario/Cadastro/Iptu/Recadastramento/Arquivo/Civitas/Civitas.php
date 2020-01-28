<?php
namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento\Arquivo\Civitas;
use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Lote;
use ECidade\Tributario\Cadastro\Iptu\Recadastramento\Construcao;
use ECidade\Tributario\Integracao\Civitas\Model\Situacao;

class Civitas {

  private $arquivoLote;
  private $arquivoConstrucao;
  private $nomeImportacao;
  private $aMatriculasImportadas = array();
  private $importacaoManual;

  public function __construct($nomeImportacao, $importacaoManual = true) {
    $this->nomeImportacao   = $nomeImportacao;
    $this->importacaoManual = $importacaoManual;
  }


  public function setArquivoConstrucao($arquivo) {
    $this->arquivoConstrucao = $arquivo;
  }

  public function setArquivoLote($arquivo) {
    $this->arquivoLote = $arquivo;
  }

  public function processar() {

    db_query("set search_path=public,{$this->nomeImportacao}");

    if(!empty($this->arquivoLote)) {
      $this->processarLote();
    }

    if(!empty($this->arquivoConstrucao)) {
      $this->processarConstrucao();
    }

    db_query("select fc_set_pg_search_path();");
  }

  private function processarLote() {

    $oArquivoLote = new \SplFileObject($this->arquivoLote);
    $oArquivoLote->setFlags(\SplFileObject::READ_CSV);
    $oArquivoLote->setCsvControl('|');

    //Busca todos os setores e os adiciona em um array
    $oDaoSetor = new \cl_setor();
    $sSql      = $oDaoSetor->sql_query_file(null,'j30_codi' );
    $rsSetor   = db_query($sSql);

    if( !$rsSetor || pg_num_rows($rsSetor) == 0){
      throw new \DBException('Erro ao buscar os setores cadastrados no sistema.');
    }
    $aSetores = \db_utils::makeCollectionFromRecord($rsSetor, function($oSetor) {
       return $oSetor->j30_codi;
    });

    $aLinhasArquivoLote = new \LimitIterator($oArquivoLote, 1);

    foreach ($aLinhasArquivoLote as $aLinha) {

      if (empty($aLinha[1])) {
        continue;
      }

      if ( !in_array($aLinha[3], $aSetores) ) {

        $sSetor = str_pad($aLinha[3], 4, "0", STR_PAD_LEFT);

        $excecao = new \BusinessException("Erro ao importar arquivo de Lote. \nSetor {$sSetor} informado no arquivo, não existe no sistema.");
        Situacao::lancarExcecao($excecao, $this->importacaoManual);
        continue;
      }

      $oMatricula = new \stdClass();
      $oMatricula->iMatricula = $aLinha[1];
      $oMatricula->iStatus    = 0;

      $this->aMatriculasImportadas[$oMatricula->iMatricula] = $oMatricula;

      $aCaracteristicas = array($aLinha[14], $aLinha[16], $aLinha[18], $aLinha[20], $aLinha[22], $aLinha[24], $aLinha[26],
                                $aLinha[28], $aLinha[30]);

      $aCaracteristicas = array_filter($aCaracteristicas);

      $oLote = new Lote();
      $oLote->setMatricula($aLinha[1]);
      $oLote->setIdbql($aLinha[2]);
      $oLote->setSetor($aLinha[3]);
      $oLote->setLoteArea($aLinha[9]);
      $oLote->setValorTestada($aLinha[10]);
      $oLote->setCaracteristicasLote($aCaracteristicas);

      try{
        $oLote->atualizar();
      }catch(Exception $excecao) {
        Situacao::lancarExcecao($excecao, $this->importacaoManual);
        continue;
      }
    }
  }

   private function processarConstrucao() {

    $oArquivoConstrucao = new \SplFileObject($this->arquivoConstrucao);
    $oArquivoConstrucao->setFlags(\SplFileObject::READ_CSV);
    $oArquivoConstrucao->setCsvControl('|');

    $aLinhasArquivoConstrucao = new \LimitIterator($oArquivoConstrucao, 1);

    foreach ($aLinhasArquivoConstrucao as $aLinha) {

      if (empty($aLinha[2]) || empty($aLinha[9]) || empty($aLinha[6]) ) {
        continue;
      }

      $aCaracteristicas = array($aLinha[18], $aLinha[20], $aLinha[22], $aLinha[24], $aLinha[26], $aLinha[28], $aLinha[30],
                                $aLinha[32], $aLinha[34], $aLinha[36], $aLinha[38], $aLinha[40], $aLinha[42], $aLinha[44],
                                $aLinha[46], $aLinha[48], $aLinha[50]);

      $aCaracteristicas = array_filter($aCaracteristicas);

      $oConstrucao = new Construcao();
      $oConstrucao->setMatricula( $aLinha[1] );
      $oConstrucao->setAreaConstrucao( $aLinha[10] );
      $oConstrucao->setIdConstrucao( $aLinha[9] );
      $oConstrucao->setCaracteristicas($aCaracteristicas);
      $oConstrucao->setIdbql( $aLinha[2] );
      $oConstrucao->setRua( $aLinha[6] );

      $iNumero = !empty($aLinha[7]) ? $aLinha[7] : 0;

      $oConstrucao->setNumero( $iNumero );

      if ( !empty($aLinha[14]) ) {

        $aDataDemolicao = explode(' ', $aLinha[14]);
        $oConstrucao->setDataDemolicao(new \DBDate($aDataDemolicao[0]));
      }

      try{
        $oConstrucao->salvar();
      }catch(\Exception $excecao) {
        Situacao::lancarExcecao($excecao, $this->importacaoManual);
        continue;
      }

      $oMatricula = new \stdClass();
      $oMatricula->iMatricula = $oConstrucao->getMatricula();
      $oMatricula->iStatus    = empty($aLinha[1]) ? 1 : 0;

      $this->aMatriculasImportadas[$oMatricula->iMatricula] = $oMatricula;
    }
   }

   public function getMatriculasImportadas() {
      return $this->aMatriculasImportadas;
   }
}
