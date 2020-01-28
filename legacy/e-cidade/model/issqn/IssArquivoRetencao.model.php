<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
 * Classe para importação do arquivo de Retençoes de ISSQN
 *
 * @author     Roberto Carneiro <roberto@dbseller.com.br>
 * @package    tributario
 * @subpackage issqn
 *
 */
class IssArquivoRetencao{

  /**
   * Sequencial do layout cadastrado no banco de dados
   */
  const CODIGO_LAYOUT = 227;

  /**
   * Constante com as mensagens de Validação
   */
  const MENSAGENS = 'tributario.issqn.IssArquivoRetencao.';

  /**
   * Sequencial do Arquivo de Retençao
   * @var int
   */
  private $iSequencial = null;

  /**
   * Instituiçao
   * @var int
   */
  private $iInstit = null;

  /**
   * Data do Arquivo de Retençao
   * @var date
   */
  private $dData = null;

  /**
   * Numero da Remessa do Arquivo de Retençao
   * @var int
   */
  private $iNumeroRemessa = null;

  /**
   * Versao do Arquivo de Retençao
   * @var int
   */
  private $iVersao = null;

  /**
   * Quantidade de Registros importados no Arquivo de Retençao
   * @var int
   */
  private $iQuantidadeRegistro = null;

  /**
   * Valor somado de todos os Registros no Arquivo de Retençao
   * @var float
   */
  private $iValorTotal = null;

  /**
   * Codigo do banco mandante do Arquivo de Retençao
   * @var int
   */
  private $iCodigoBanco = null;

  /**
   * Codigo do arquivo retencao
   * @var int
   */
  private $iIssArquivoRetencao = null;

  /**
   * Oid do arquivo 'fisico'
   * @var oid
   */
  private $iArquivo = null;

  /**
   * Nome do arquivo importado
   * @var string
   */
  private $sNomeArquivo = null;

  /**
   * Construtor do Model
   * @param integer $iSequencial Codigo sequencial do registro no banco de dados
   */
  public function __construct( $iSequencial = null ){

    $oDaoIssArquivoRetencao = new cl_issarquivoretencao();
    $rsIssArquivoRetencao   = null;

    if ( !is_null($iSequencial) ) {

      $sSql                 = $oDaoIssArquivoRetencao->sql_query($iSequencial);
      $rsIssArquivoRetencao = $oDaoIssArquivoRetencao->sql_record($sSql);

      if ( $oDaoIssArquivoRetencao->numrows > 0 ) {

        $oIssArquivoRetencao = db_utils::fieldsMemory($rsIssArquivoRetencao, 0);

        $this->iSequencial         = $oIssArquivoRetencao->q90_sequencial;
        $this->oInstit             = new Instituicao($oIssArquivoRetencao->q90_instit);
        $this->dData               = $oIssArquivoRetencao->q90_data;
        $this->iNumeroRemessa      = $oIssArquivoRetencao->q90_numeroremessa;
        $this->iVersao             = $oIssArquivoRetencao->q90_versao;
        $this->iQuantidadeRegistro = $oIssArquivoRetencao->q90_quantidaderegistro;
        $this->iValorTotal         = $oIssArquivoRetencao->q90_valortotal;
        $this->iCodigoBanco        = $oIssArquivoRetencao->q90_codigobanco;
        $this->iArquivo            = $oIssArquivoRetencao->q90_oidarquivo;
        $this->sNomeArquivo        = $oIssArquivoRetencao->q90_nomearquivo;
      }
    }

    return true;
  }

  /**
   * Verificamos se algum arquivo com o mesmo numero de remessa ja fora importado
   * @return boolean
   */
  public function validarRemessa() {

    if (is_null($this->iNumeroRemessa)) {
      throw new Exception( _M (self::MENSAGENS . 'numeroremessa_nao_informado') );
    }

    $oDaoIssArquivoRetencao = db_utils::getDao('issarquivoretencao');
    $sSql                   = $oDaoIssArquivoRetencao->sql_query(null, "*", null, "q90_numeroremessa = {$this->iNumeroRemessa}");
    $rsIssArquivoRetencao   = $oDaoIssArquivoRetencao->sql_record($sSql);

    if ($oDaoIssArquivoRetencao->numrows > 0) {
      return false;
    }

    return true;
  }

  /**
   * Carregamos os dados do arquivo no model
   *
   * @param  array  $aLinhas
   * @param  oid    $iOid
   * @param  string $sNomeArquivo
   */
  public function carregarDados($aLinhas, $iOid, $sNomeArquivo) {

    $this->iArquivo            = $iOid;
    $this->sNomeArquivo        = $sNomeArquivo;
    $this->iCodigoBanco        = 1;
    $this->iInstit             = (int) db_getsession("DB_instit");
    $this->dData               = date( 'Y-m-d', db_getsession("DB_datausu"));

    foreach ($aLinhas as $iIndice => $oLinha) {

      if ($iIndice == 0) {

        $this->iNumeroRemessa      = (int) $oLinha->numero_remessa;
        $this->iVersao             = (int) $oLinha->numero_versao;

      } else {

        $this->iQuantidadeRegistro = (int) $oLinha->total_registros_gravados;
        $this->iValorTotal         = db_formatar($oLinha->valor_total_recebido, 'p', '', 2);
      }
    }
  }

  /**
   * Incluimos os dados do arquivo no banco
   */
  public function incluir() {

    $oDaoIssArquivoRetencao = new cl_issarquivoretencao;

    $oDaoIssArquivoRetencao->q90_instit             = $this->iInstit;
    $oDaoIssArquivoRetencao->q90_data               = $this->dData;
    $oDaoIssArquivoRetencao->q90_numeroremessa      = $this->iNumeroRemessa;
    $oDaoIssArquivoRetencao->q90_versao             = $this->iVersao;
    $oDaoIssArquivoRetencao->q90_quantidaderegistro = $this->iQuantidadeRegistro;
    $oDaoIssArquivoRetencao->q90_valortotal         = db_formatar($this->iValorTotal, 'p', '', 2) / 100;
    $oDaoIssArquivoRetencao->q90_codigobanco        = $this->iCodigoBanco;
    $oDaoIssArquivoRetencao->q90_oidarquivo         = $this->iArquivo;
    $oDaoIssArquivoRetencao->q90_nomearquivo        = $this->sNomeArquivo;

    $oDaoIssArquivoRetencao->incluir(null);

    if ($oDaoIssArquivoRetencao->erro_status == 0) {
      throw new Exception( _M (self::MENSAGENS . 'erro_arquivo_invalido') );
    }

    $this->setSequencial($oDaoIssArquivoRetencao->q90_sequencial);
  }

  /**
   * Incluimos os registro vinculados ao arquivo de retenção
   * @param std DBLayoutLinha
   * @return boolean
   */
  public function incluirRegistros ( $oLinha ){

    foreach ($oLinha as $iIndice => $oArquivo) {

      /**
       * Detalhe
       */
      if( $oArquivo->codigo_registro == 2) {

        $oDaoIssArquivoRetencaoRegistro = new cl_issarquivoretencaoregistro;

        $oDaoIssArquivoRetencaoRegistro->q91_issarquivoretencao      = $this->getSequencial();
        $oDaoIssArquivoRetencaoRegistro->q91_sequencialregistro      = (integer) $oArquivo->sequencial_registro;
        $oDaoIssArquivoRetencaoRegistro->q91_dataemissaodocumento    = $oArquivo->data_emissao_documento;
          $oDaoIssArquivoRetencaoRegistro->q91_dataemissaodocumento_dia = substr($oArquivo->data_emissao_documento, 0, 4);
          $oDaoIssArquivoRetencaoRegistro->q91_dataemissaodocumento_mes = substr($oArquivo->data_emissao_documento, 4, 2);
          $oDaoIssArquivoRetencaoRegistro->q91_dataemissaodocumento_ano = substr($oArquivo->data_emissao_documento, 6, 2);

        $oDaoIssArquivoRetencaoRegistro->q91_datavencimento          = $oArquivo->data_vencimento_documento;
          $oDaoIssArquivoRetencaoRegistro->q91_datavencimento_dia    = substr($oArquivo->data_vencimento_documento, 0, 4);
          $oDaoIssArquivoRetencaoRegistro->q91_datavencimento_mes    = substr($oArquivo->data_vencimento_documento, 4, 2);
          $oDaoIssArquivoRetencaoRegistro->q91_datavencimento_ano    = substr($oArquivo->data_vencimento_documento, 6, 2);

        $oDaoIssArquivoRetencaoRegistro->q91_numerodocumento         = $oArquivo->numero_ducumento;
        $oDaoIssArquivoRetencaoRegistro->q91_cnpjtomador             = $oArquivo->cnpj_unidade_tomadora;
        $oDaoIssArquivoRetencaoRegistro->q91_codigomunicipiotomador  = $oArquivo->codigo_municipio_tomadora;
        $oDaoIssArquivoRetencaoRegistro->q91_cpfcnpjprestador        = $oArquivo->cnpj_cpf_substituto;
        $oDaoIssArquivoRetencaoRegistro->q91_codigomunicipionota     = $oArquivo->codigo_municipio_nota_fiscal;
        $oDaoIssArquivoRetencaoRegistro->q91_esferareceita           = $oArquivo->esfera_receita;

        $oDaoIssArquivoRetencaoRegistro->q91_anousu                  = substr($oArquivo->competencia, 0, 4);
        $oDaoIssArquivoRetencaoRegistro->q91_mesusu                  = substr($oArquivo->competencia, 4, 2);

        $oDaoIssArquivoRetencaoRegistro->q91_valorprincipal          = db_formatar($oArquivo->valor_principal, 'p', '', 2) / 100;
        $oDaoIssArquivoRetencaoRegistro->q91_valormulta              = db_formatar($oArquivo->valor_multa, 'p', '', 2) / 100;
        $oDaoIssArquivoRetencaoRegistro->q91_valorjuros              = db_formatar($oArquivo->valor_juros, 'p', '', 2) / 100;
        $oDaoIssArquivoRetencaoRegistro->q91_numeronotafiscal        = (integer) $oArquivo->numero_nota_fiscal_recibo;
        $oDaoIssArquivoRetencaoRegistro->q91_serienotafiscal         = $oArquivo->serie_nota_fiscal;
        $oDaoIssArquivoRetencaoRegistro->q91_subserienotafiscal      = $oArquivo->subserie_nota_fiscal;

        $oDaoIssArquivoRetencaoRegistro->q91_dataemissaonotafiscal   = $oArquivo->data_emissao_nota_fiscal_recibo;
          $oDaoIssArquivoRetencaoRegistro->q91_dataemissaonotafiscal_dia = substr($oArquivo->data_emissao_nota_fiscal_recibo, 0, 4);
          $oDaoIssArquivoRetencaoRegistro->q91_dataemissaonotafiscal_mes = substr($oArquivo->data_emissao_nota_fiscal_recibo, 4, 2);
          $oDaoIssArquivoRetencaoRegistro->q91_dataemissaonotafiscal_ano = substr($oArquivo->data_emissao_nota_fiscal_recibo, 6, 2);

        $nAliquota = $oArquivo->aliquota;
        if( (float)$oArquivo->aliquota > 1000){
          $nAliquota = (float)$oArquivo->aliquota / 1000;
        }

        $oDaoIssArquivoRetencaoRegistro->q91_valornotafiscal           = db_formatar($oArquivo->valor_nota_fiscal_recibo, 'p', '', 2) / 100;
        $oDaoIssArquivoRetencaoRegistro->q91_aliquota                  = db_formatar($nAliquota, 'p', '', 3);
        $oDaoIssArquivoRetencaoRegistro->q91_valorbasecalculo          = db_formatar($oArquivo->valor_base_calculo, 'p', '', 2) / 100;
        $oDaoIssArquivoRetencaoRegistro->q91_observacao                = $oArquivo->observacao;
        $oDaoIssArquivoRetencaoRegistro->q91_codigomunicipiofavorecido = $oArquivo->codigo_municipio_favorecido;

        $oDaoIssArquivoRetencaoRegistro->incluir(null);

        if ($oDaoIssArquivoRetencaoRegistro->erro_status == 0) {
          throw new Exception( _M (self::MENSAGENS . 'erro_arquivo_invalido') );
        }

        unset($oDaoIssArquivoRetencaoRegistro);
      }
    }
  }

  /**
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * @param integer
   */
  public function setSequencial($iSequencial) {
    $this->iSequencial = $iSequencial;
  }

  /**
   * Altera a Instituiçao
   * @param Instituiçao
   */
  public function setInstit ($oInstit) {
    $this->oInstit = $oInstit;
  }

  /**
   * Busca o Instituiçao
   * @return Instituiçao
   */
  public function getInstit () {
    return $this->oInstit;
  }

  /**
   * Altera a data
   * @param date
   */
  public function setData ($dData) {
    $this->dData = $dData;
  }

  /**
   * Busca a data
   * @return date
   */
  public function getData () {
    return $this->dData;
  }

  /**
   * Altera o Numero da Remessa
   * @param integer
   */
  public function setNumeroRemessa ($iNumeroRemessa) {
    $this->iNumeroRemessa = $iNumeroRemessa;
  }

  /**
   * Busca o Numero da Remessa
   * @return $iNumeroRemessa
   */
  public function getNumeroRemessa () {
    return $this->iNumeroRemessa;
  }

  /**
   * Altera a versao
   * @param int
   */
  public function setVersao ($iVersao) {
    $this->iVersao = $iVersao;
  }

  /**
   * Busca a versao
   * @return int
   */
  public function getVersao () {
    return $this->iVersao;
  }

  /**
   * Altera a quantidade de registros
   * @param int
   */
  public function setQuantidadeRegistro ($iQuantidadeRegistro) {
    $this->iQuantidadeRegistro = $iQuantidadeRegistro;
  }

  /**
   * Busca a quantidade de registro
   * @return int
   */
  public function getQuantidadeRegistro () {
    return $this->iQuantidadeRegistro;
  }

  /**
   * Altera o valor total
   * @param float
   */
  public function setValorTotal ($iValorTotal) {
    $this->iValorTotal = $iValorTotal;
  }

  /**
   * Busca o valor total
   * @return float
   */
  public function getValorTotal () {
    return $this->iValorTotal;
  }

  /**
   * Altera o codigo do banco
   * @param int
   */
  public function setCodigoBanco ($iCodigoBanco) {
    $this->iCodigoBanco = $iCodigoBanco;
  }

  /**
   * Busca o codigo do banco
   * @return int
   */
  public function getCodigoBanco () {
    return $this->iCodigoBanco;
  }

  /**
   * Altera o arquivo
   * @param oid
   */
  public function setArquivo ($iArquivo) {
    $this->iArquivo = $iArquivo;
  }

  /**
   * Busca o arquivo
   * @return oid
   */
  public function getArquivo () {
    return $this->iArquivo;
  }

  /**
   * Altera o nome do arquivo
   * @param string
   */
  public function setNomeArquivo ($sNomeArquivo) {
    $this->sNomeArquivo = $sNomeArquivo;
  }

  /**
   * Busca o nome do arquivo
   * @return string
   */
  public function getNomeArquivo() {
    return $this->sNomeArquivo;
  }

}