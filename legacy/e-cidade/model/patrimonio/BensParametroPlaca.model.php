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
 * Class BensParametroPlaca
 */
class BensParametroPlaca {

  const CONTROLE_GOBAL_PLACAS          = false;
  const CONTROLE_INSTITUCIONAL_PLACAS  = true;
  const PLACA_SEQUENCIAL_AUTOMATICO    = 1;
  const PLACA_CLASSIFICACAO_SEQUENCIAL = 2;
  const PLACA_TEXTO_SEQUENCIAL         = 3;
  const PLACA_SEQUENCIAL_DIGITADO      = 4;

  static $oInstance;

  /**
   * Código do parâmetro. Tabela cfpatriplaca.t07_confplaca
   * @var integer
   */
  protected $iCodigoParamentro;
  /**
   * Código do sequencial. Tabela cfpatriplaca.t07_sequencial
   * Só utilixado quando o código do parâmetro igual a 1
   * @var integer
   */
  protected $sSequencialCfPatriPlaca;

  /**
   * Parâmetro que verifica se controle de placas é por instituição
   * @var boolean
   */
  protected $lControlaPlacasInstituicao;

  /**
   * Tipo de controle para numeracao de placa
   * @var integer
   */
  protected $iTipoConfiguracaoPlaca;

  /**
   * Construtor da classe
   *
   * @throws Exception
   */
  protected function __construct() {

    /**
     * Busca as configurações para a instituição em questão
     */
    $oDaoCfPatriPlaca = db_utils::getDao("cfpatriplaca");
    $iInstituicao     = db_getsession("DB_instit");
    $sOrder           = 't07_sequencial desc limit 1';

    /**
     * Verifica se o controle das placas é feito por instituição
     */
    $this->lControlaPlacasInstituicao = self::controlaPlacaPorInstituicao();

    $sSQLSequencialPlaca = $oDaoCfPatriPlaca->sql_query_fileLockInLine($iInstituicao, '*', $sOrder);
    $rsCfPatriPlaca      = $oDaoCfPatriPlaca->sql_record($sSQLSequencialPlaca);

    if ($oDaoCfPatriPlaca->numrows == 1) {

      $oCfPatriPlaca = db_utils::fieldsMemory($rsCfPatriPlaca, 0);
      $this->iCodigoParamentro = $oCfPatriPlaca->t07_confplaca;

      /**
       * Se o controle das placas não é feito por instituição, então será buscado o maior sequencial global
       */
      if (!($this->lControlaPlacasInstituicao)) {
        $iInstituicao = null;
      }
      $iCodigoSequencialPlaca = self::buscaSequencial($iInstituicao);

      $this->iTipoConfiguracaoPlaca = $oCfPatriPlaca->t07_confplaca;

      /**
       * Se for uma placa do tipo sequencial deve ser usado o sequencial encontrado pela função buscaSequencial
       */
      if ( !empty($oCfPatriPlaca->t07_sequencial) && $this->iTipoConfiguracaoPlaca == self::PLACA_SEQUENCIAL_AUTOMATICO) {
        $this->sSequencialCfPatriPlaca = $iCodigoSequencialPlaca;
      }


    } else if ($oDaoCfPatriPlaca->numrows > 1) {

      $sMsg  = "Contate o suporte, há mais de um registro para a instituição ";
      $sMsg .= db_getsession("DB_instit");
      $sMsg .= " na tabela: cfpatriplaca. ";
      throw new Exception($sMsg);

    } else {
      throw new Exception("Verifique se foi cadastrado os Parâmetros da Placa");
    }
  }

  /**
   * Retorna o Códido do parâmetro setado
   * @return Integer
   */
  public static function getCodigoParametro() {
    return self::getInstance()->iCodigoParamentro;
  }

  /**
   * Retorna o próximo sequencial se Parâmetro for do tipo sequencial
   * @return Integer
   */
  public static function getSequencial() {
    return self::getInstance()->sSequencialCfPatriPlaca++;
  }

  /**
  * Retorna a instancia da classe
  * @return BensParametroPlaca
  */
  public static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new BensParametroPlaca();
    }
    return self::$oInstance;
  }

  /**
   * Retorna o tipo de configuração da placa
   */
  public function getTipoConfiguracaoPlaca() {
    return $this->iTipoConfiguracaoPlaca;
  }

  /**
   * Verifica se controle de placas é por instituição
   * @return boolean
   */
  public static function controlaPlacaPorInstituicao() {

    $oDaoCfPatri               = db_utils::getDao("cfpatri");
    $sSqlCfPatri               = $oDaoCfPatri->sql_query_file(null, "*", null, null);
    $rsCfPatri                 = $oDaoCfPatri->sql_record($sSqlCfPatri);
    $lControlaPlacaInstituicao = db_utils::fieldsMemory($rsCfPatri, 0)->t06_controlaplacainstituicao;

    if ($lControlaPlacaInstituicao == 't') {
      return true;
    }
    return false;
  }

  /**
   * Função que busca o sequencial seguinte para uma instituição.
   * Caso instituição não seja informada, será buscado o próximo sequencial global
   * @param string $iInstituicao
   * @return integer próximo sequencial automatico
   */
  public static function buscaSequencial($iInstituicao = null) {

    $oDaoCfPatriPlaca    = db_utils::getDao("cfpatriplaca");
    $sOrder              = 't07_sequencial desc limit 1';
    $sSQLSequencialPlaca = $oDaoCfPatriPlaca->sql_query_fileLockInLine($iInstituicao, '*', $sOrder);
    $rsCfPatriPlaca      = $oDaoCfPatriPlaca->sql_record($sSQLSequencialPlaca);

    $oCfPatriPlaca = null;
    if ($oDaoCfPatriPlaca->numrows == 1) {
      $oCfPatriPlaca = db_utils::fieldsMemory($rsCfPatriPlaca, 0)->t07_sequencial;
    }
    return $oCfPatriPlaca;
  }

  /**
   * @return int|null
   */
  public static function getPlacaDisponivel($iInstituicao) {

    $oParametro = BensParametroPlaca::getInstance();
    $iNovaPlaca = null;
    switch ($oParametro->iTipoConfiguracaoPlaca) {

      case BensParametroPlaca::PLACA_SEQUENCIAL_AUTOMATICO:

        $oDaoBensPlaca  = new cl_bensplaca();
        $sSqlBuscaPlaca = $oDaoBensPlaca->sql_query_placa_bem(null, 'max(t41_placaseq)+1 as nova_placa', null, "t52_instit = {$iInstituicao}");
        $iNovaPlaca     = db_utils::fieldsMemory(db_query($sSqlBuscaPlaca), 0)->nova_placa;

        break;
    }
    return $iNovaPlaca;
  }
}