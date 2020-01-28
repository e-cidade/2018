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
 * Classe para controle das informa��es da Efetividade( recursos humanos dentro de uma compet�ncia )
 * @package educacao
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 *         Andr� Mello   - andre.mello@dbseller.com.br
 * @version $Revision: 1.3 $
 */

class Efetividade {

  const MENSAGENS_EFETIVIDADE = 'educacao.recursohumano.Efetividade.';

  /**
   * C�digo da Efetividade
   * @var null
   */
  private $iCodigo = null;

  /**
   * Inst�ncia contendo o Profissional da Escola
   * @var null
   */
  private $oProfissionalEscola = null;

  /**
   * Inst�ncia contendo a Efetividade RH (compet�ncia)
   * @var EfetividadeRH|null
   */
  private $oEfetividadeRH = null;

  /**
   * Cont�m a observa��o da Efetividade do Profissional
   * @var string
   */
  private $sObservacao = '';

  /**
   * C�digo do rechumano
   * @var null
   */
  private $iRechumano = null;

  /**
   * Cargo do Servidor
   * @var null
   */
  private $oCargo = null;

  /**
   * Fun��o do Servidor
   * @var null
   */
  private $oFuncao = null;

  /**
   * Construtor da Efetividade
   * @param integer $iCodigo
   * @throws DBException
   * @throws ParameterException
   */
  public function __construct( $iCodigo = null ) {

    if ( empty($iCodigo) ) {
      return null;
    }

    $oDaoEfetividade = new cl_efetividade();
    $sSqlEfetividade = $oDaoEfetividade->sql_query_file( $iCodigo );
    $rsEfetividade   = db_query($sSqlEfetividade);

    if ( !$rsEfetividade ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( self::MENSAGENS_EFETIVIDADE . "erro_buscar_efetividade", $oErro ) );
    }

    if ( pg_num_rows($rsEfetividade) == 0 ) {
      throw new ParameterException( _M( self::MENSAGENS_EFETIVIDADE . "nenhuma_efetividade_encontrada" ) );
    }

    $oDadosEfetividade    = db_utils::fieldsMemory($rsEfetividade, 0);
    $this->iCodigo        = $oDadosEfetividade->ed97_i_codigo;
    $this->sObservacao    = $oDadosEfetividade->ed97_t_obs;
    $this->iRechumano     = $oDadosEfetividade->ed97_i_rechumano;
    $this->oEfetividadeRH = new EfetividadeRH( $oDadosEfetividade->ed97_i_efetividaderh );

    $this->getProfissionalEscola();
    $this->getDadosServidor();
  }

  /**
   * Retorna a observa��o lan�ada na Efetividade
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Retorna o c�digo do Rechumano na qual pertence esta efetividade
   * @return integer
   */
  public function getRechumano() {
    return $this->iRechumano;
  }

  /**
   * Retorna o profissional da escola de acordo com a efetividade, a escola e o c�digo do recurso humano
   * @return null|ProfissionalEscola
   * @throws DBException
   */
  public function getProfissionalEscola() {

    if ( $this->oProfissionalEscola instanceof ProfissionalEscola ) {
      return $this->oProfissionalEscola;
    }

    $oDaoEfetividade     = new cl_efetividade();
    $sCamposEfetividade  = "ed75_i_codigo";
    $sWhereEfetividade   = "     ed97_i_efetividaderh = {$this->oEfetividadeRH->getCodigo()} ";
    $sWhereEfetividade  .= " AND ed75_i_escola = {$this->oEfetividadeRH->getEscola()->getCodigo()}";
    $sWhereEfetividade  .= " AND ed97_i_rechumano = {$this->iRechumano}";
    $sSqlEfetividade     = $oDaoEfetividade->sql_query_rechumanoescola( null, $sCamposEfetividade, 'ed75_i_saidaescola desc', $sWhereEfetividade);
    $rsEfetividade       = db_query($sSqlEfetividade);

    if ( !$rsEfetividade ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( self::MENSAGENS_EFETIVIDADE . "erro_buscar_profissional_escola", $oErro ) );
    }

    $iQuantidadeProfissionais = pg_num_rows($rsEfetividade);

    if ( $iQuantidadeProfissionais > 0 ) {

      $iProfissional             = db_utils::fieldsMemory($rsEfetividade, 0)->ed75_i_codigo;
      $this->oProfissionalEscola = ProfissionalEscolaRepository::getByCodigo( $iProfissional );
    }


    return $this->oProfissionalEscola;
  }

  /**
   * Retorna o Cargo e a Fun��o que o Rechumano possui de acordo com o ano e o m�s da efetividade.
   * Caso n�o haja nenhum Cargo/Fun��o para o ano e m�s especificados, retorna o �ltimo Cargo/Fun��o lan�ada.
   * Este Cargo/Fun��o vem do M�dulo RH > Pessoal
   * No cadastro do Pessoal est� invertido. Cargo � na verdade a Fun��o e a Fun��o � o Cargo.
   *    Cargo  = rhfuncao.rh37_funcao;
   *    Fun��o = rhcargo.rh04_codigo
   * @throws DBException
   */
  private function getDadosServidor() {

    $sWhere   = " ed20_i_codigo = {$this->iRechumano} AND rh02_anousu = {$this->oEfetividadeRH->getDataFim()->getAno()} ";
    $sWhere  .= " AND rh02_mesusu = {$this->oEfetividadeRH->getDataFim()->getMes()}";
    $sOrder   = " rh02_anousu desc, rh02_mesusu desc limit 1";
    $sCampos  = " rh37_funcao, rh37_descr, rh04_codigo, rh04_descr";

    $oDaoRecursoHumano = new cl_rechumano();
    $sSqlDadosServidor = $oDaoRecursoHumano->sql_query_servidor( null, $sCampos, $sOrder, $sWhere );
    $rsDadosServidor   = db_query( $sSqlDadosServidor );

    if( !$rsDadosServidor ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( self::MENSAGENS_EFETIVIDADE . "erro_buscar_dados_servidor", $oErro ) );
    }

    if( pg_num_rows( $rsDadosServidor ) == 0 ) {

      $sWhere   = " ed20_i_codigo = {$this->iRechumano} AND rh02_anousu <= {$this->oEfetividadeRH->getDataFim()->getAno()} ";

      $sSqlDadosServidor = $oDaoRecursoHumano->sql_query_servidor( null, $sCampos, $sOrder, $sWhere );
      $rsDadosServidor   = db_query( $sSqlDadosServidor );

      if( !$rsDadosServidor ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( self::MENSAGENS_EFETIVIDADE . "erro_buscar_dados_servidor", $oErro ) );
      }
    }

    if( pg_num_rows( $rsDadosServidor ) > 0 ) {

      $oDadosServidor = db_utils::fieldsMemory( $rsDadosServidor, 0 );

      if( !empty( $oDadosServidor->rh37_funcao ) ) {

        $this->oCargo             = new stdClass();
        $this->oCargo->iCodigo    = $oDadosServidor->rh37_funcao;
        $this->oCargo->sDescricao = $oDadosServidor->rh37_descr;
      }

      if( !empty( $oDadosServidor->rh04_codigo ) ) {

        $this->oFuncao             = new stdClass();
        $this->oFuncao->iCodigo    = $oDadosServidor->rh04_codigo;
        $this->oFuncao->sDescricao = $oDadosServidor->rh04_descr;
      }
    }
  }

  /**
   * Retorna o c�digo e a descri��o do Cargo
   * @return null|stdClass
   */
  public function getCargo() {
    return $this->oCargo;
  }

  /**
   * Retorna o c�digo e a descri��o da Fun��o
   * @return null|stdClass
   */
  public function getFuncao() {
    return $this->oFuncao;
  }
}