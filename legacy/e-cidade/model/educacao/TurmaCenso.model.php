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

/**
 * Classe modelo para ligação entre sa turmas e o censo
 * @package   Educacao
 * @author    Andre Mello - andre.mello@dbseller.com.br
 * @version
 */
class TurmaCenso {

  /**
   * Código da TurmaCenso
   * @var integer
   */
  private $iCodigo = null;

  /**
   * Código da CensoEtapa
   * @var integer
   */
  private $iEtapaCenso;

  /**
   * Nome que a turma terá
   * @var string
   */
  private $sNomeTurma;

  /**
   * Ano dos calendarios das turmas
   * @var [type]
   */
  private $iAnoCalendarioTurmas;

  /**
   * Coleção de objetos TurmaCensoTurma contendo as turmas e qual é a principal
   * @var array
   */
  private $aTurmaCensoTurma = array();

  /**
   * Verifica se foi passado o código como parâmetro e retorna os dados conforme o código passado
   * @param integer $iCodigo
   */
  public function __construct( $iCodigo = null ) {

    if ( $iCodigo != null ) {

      $oDaoTurmaCenso = new cl_turmacenso();
      $sSqlTurmaCenso = $oDaoTurmaCenso->sql_query_file( $iCodigo );
      $rsTurmaCenso   = db_query( $sSqlTurmaCenso );

      if ( pg_num_rows($rsTurmaCenso) > 0 ) {

        $oDadosTurmaCenso = db_utils::fieldsMemory($rsTurmaCenso, 0);
        $this->setEtapaCenso( $oDadosTurmaCenso->ed342_censoetapa);
        $this->setNomeTurma( $oDadosTurmaCenso->ed342_nome );
        $this->iCodigo = $oDadosTurmaCenso->ed342_sequencial;
      }
    }
  }

  /**
   * Retorna o código de TurmaCenso
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a EtapaCenso
   * @return integer
   */
  public function getEtapaCenso() {
    return $this->iEtapaCenso;
  }

  /**
   * Define uma EtapaCenso para a TurmaCenso
   * @param integer $iEtapaCenso
   */
  public function setEtapaCenso( $iEtapaCenso ) {
    $this->iEtapaCenso = $iEtapaCenso;
  }

  /**
   * Retorna o nome dado a turma
   * @return string
   */
  public function getNomeTurma() {
    return $this->sNomeTurma;
  }

  /**
   * Define um nome para ser atribuido as turmas
   * @param string $sNomeTurma
   */
  public function setNomeTurma( $sNomeTurma ) {
    $this->sNomeTurma = $sNomeTurma;
  }

  /**
   * Retorna uma coleção contendo as TurmaCensoTurma
   * @return TurmaCensoTurma[]
   */
  public function getTurmaCensoTurma() {

    if( !empty( $this->iCodigo ) ) {

      $oDaoTurmaCensoTurma    = new cl_turmacensoturma();
      $sSqlTurmaCensoTurma    = $oDaoTurmaCensoTurma->sql_query_file('','*','',"ed343_turmacenso = {$this->iCodigo}");
      $rsTurmaCensoTurma      = db_query( $sSqlTurmaCensoTurma );
      $iLinhasTurmaCensoTurma = pg_num_rows($rsTurmaCensoTurma);

      if ( $iLinhasTurmaCensoTurma > 0 ) {

        for ( $iContador=0; $iContador < $iLinhasTurmaCensoTurma; $iContador++ ) {

          $oDadosTurmaCensoTurma = db_utils::fieldsMemory( $rsTurmaCensoTurma, $iContador );
          $oTurmaCensoTurma      = new TurmaCensoTurma();
          $oTurma                = TurmaRepository::getTurmaByCodigo( $oDadosTurmaCensoTurma->ed343_turma);
          $oTurmaCensoTurma->setTurma( $oTurma );
          $oTurmaCensoTurma->setPrincipal( $oDadosTurmaCensoTurma->ed343_principal );

          $this->aTurmaCensoTurma[ $oTurmaCensoTurma->getTurma()->getCodigo() ] = $oTurmaCensoTurma;
        }
      }
    }

    return $this->aTurmaCensoTurma;
  }

  /**
   * Adiciona ao array as TurmaCensoTurma
   * @param  TurmaCensoTurma $oTurmaCensoTurma
   */
  public function adicionarTurmaCensoTurma( TurmaCensoTurma $oTurmaCensoTurma ) {

    if( !array_key_exists( $oTurmaCensoTurma->getTurma()->getCodigo(), $this->aTurmaCensoTurma ) ) {
      $this->aTurmaCensoTurma[ $oTurmaCensoTurma->getTurma()->getCodigo() ] = $oTurmaCensoTurma;
    }
  }

  /**
   * Vincula as turmas da TurmaCensoTurma com a TurmaCenso
   */
  private function vincularTurmaCensoTurma() {

    ksort( $this->aTurmaCensoTurma );
    $lPrimeiraPosicao = true;

    foreach ( $this->aTurmaCensoTurma as $iTurma => $oDadosTurmaCensoTurma ) {

      $sPrincipal = 'false';
      if ( $lPrimeiraPosicao ) {

        $sPrincipal       = 'true';
        $lPrimeiraPosicao = false;
      }

      $oDaoTurmaCensoTurma                   = new cl_turmacensoturma();
      $oDaoTurmaCensoTurma->ed343_sequencial = null;
      $oDaoTurmaCensoTurma->ed343_principal  = $sPrincipal;
      $oDaoTurmaCensoTurma->ed343_turma      = $iTurma;
      $oDaoTurmaCensoTurma->ed343_turmacenso = $this->iCodigo;
      $oDaoTurmaCensoTurma->incluir(null);

      if ( $oDaoTurmaCensoTurma->erro_status == "0" ) {
        throw new DBException("Erro ao criar vínculo com as turmas e o censo.\n {$oDaoTurmaCensoTurma->erro_msg}");
      }
    }
  }

  /**
   * Salva a TurmaCenso e cria o vínculo com a TurmaCensoTurma
   */
  public function salvar() {

    $oDaoTurmaCenso                   = new cl_turmacenso();
    $oDaoTurmaCenso->ed342_nome       = $this->sNomeTurma;
//    $oDaoTurmaCenso->ed342_censoetapa = $this->iEtapaCenso;
    $oDaoTurmaCenso->ed342_sequencial = $this->iCodigo;

    if ( empty( $this->iCodigo ) ) {
      $oDaoTurmaCenso->incluir(null);
    } else {
      $oDaoTurmaCenso->alterar( $this->iCodigo );
    }

    $this->iCodigo = $oDaoTurmaCenso->ed342_sequencial;
    $this->removerTurmas();
    $this->vincularTurmaCensoTurma();
    $this->vincularEtapaCenso();

    if ( $oDaoTurmaCenso->erro_status == "0" ) {
      throw new DBException("Erro ao salvar dados da turma com o censo. \n{$oDaoTurmaCenso->erro_msg}");
    }
  }

  /**
   * Remove todas as turmas que estão vinculadas ao TurmaCenso
   */
  public function removerTurmas() {

    if ( !empty( $this->iCodigo ) ) {

      $oDaoTurmaCensoTurma = new cl_turmacensoturma();
      $oDaoTurmaCensoTurma->excluir( "", "ed343_turmacenso = {$this->iCodigo}");

      if ($oDaoTurmaCensoTurma->erro_status == "0" ) {
          throw new DBException("Erro ao remover vínculo com as turmas. \n{$oDaoTurmaCensoTurma->erro_msg}");
      }
    }
  }

  /**
   * Remove os vinculos existentes com a TurmaCenso e após isso a remove.
   */
  public function remover() {

    if ( $this->iCodigo != null ) {

      $this->removerTurmas();
      $this->removerVinculoEtapaCenso();

      $oDaoTurmaCenso = new cl_turmacenso();
      $oDaoTurmaCenso->excluir( $this->iCodigo );

      if ( $oDaoTurmaCenso->erro_status == "0" ) {
        throw new DBException("Erro ao remover o censo da turma. \n{$oDaoTurmaCenso->erro_msg}");
      }
    }
  }

  /**
   * Define o ano do calendario das turmas
   * @param integer $iAno
   */
  public function setAnoCalendarioTurma( $iAno ) {

    $this->iAnoCalendarioTurmas = $iAno;
  }


  private function removerVinculoEtapaCenso() {

    $oDao   = new cl_censoetapaturmacenso();
    $sWhere =  "ed134_turmacenso = {$this->iCodigo} ";

    $oDao->excluir(null, $sWhere);
    if ( $oDao->erro_status == "0" ) {
      throw new DBException("Erro ao remover vinculo com etapa do censo. \n{$oDao->erro_msg}");
    }

  }

  /**
   * Vincula a etapa do censo com a turmacenso
   * @return void
   */
  private function vincularEtapaCenso() {

    $this->removerVinculoEtapaCenso();

    $oDao = new cl_censoetapaturmacenso();

    $oDao->ed134_codigo     = null;
    $oDao->ed134_turmacenso = $this->iCodigo;
    $oDao->ed134_censoetapa = $this->iEtapaCenso;
    $oDao->ed134_ano        = $this->iAnoCalendarioTurmas;

    $oDao->incluir(null);
    if ( $oDao->erro_status == "0" ) {
      throw new DBException("Erro ao incluir vinculo com etapa do censo. \n{$oDao->erro_msg}");
    }

  }
}