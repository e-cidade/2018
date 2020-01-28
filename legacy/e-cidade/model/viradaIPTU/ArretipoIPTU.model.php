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

/*
 * Implementa interface iViradaIPTU.interface.php
 * @method public vira()
 */
require_once('interfaces/iViradaIPTU.interface.php');

class ArretipoIPTU implements iViradaIPTU {

  /**
   * Anual atual do exercicío
   * @var integer
   */
  private $iAnoAtual      = 0;

  /**
   * Ano novo do exercicío
   * @var integer
   */
  private $iAnoNovo       = 0;

  /**
   * Tipo de débito
   * @var integer
   */
  private $iTipo          = 0;

  /**
   * Código da tabela da virada anual
   * @var integer
   */
  private $iCodigoTabela  = 0;

  /**
   * Percentual aplicado para os valores
   * @var numeric
   */
  private $nPercentual    = 0;

  /**
   * Campos chave configurados na tabela iptutabelasconfigcampochave
   * @var array
   */
  private $aCampoChave    = array();

  /**
   * Campos correcao configurados na tabela iptutabelasconfigcampocorrecao
   * @var array
   */
  private $aCampoCorrecao = array();

  /**
   * @return $this->iAnoAtual
   */
  private function getAnoAtual() {

    return $this->iAnoAtual;
  }

  /**
   * @param integer type $iAnoAtual
   */
  private function setAnoAtual($iAnoAtual) {

    $this->iAnoAtual = $iAnoAtual;
  }

  /**
   * @return $this->iAnoNovo
   */
  private function getAnoNovo() {

    return $this->iAnoNovo;
  }

  /**
   * @param integer type $iAnoNovo
   */
  private function setAnoNovo($iAnoNovo) {

    $this->iAnoNovo = $iAnoNovo;
  }

  /**
   * @return integer
   */
  private function getTipo() {

    return $this->iTipo;
  }

  /**
   * @param integer $iTipo
   */
  private function setTipo($iTipo) {

    $this->iTipo = $iTipo;
  }

  /**
   * @return integer
   */
  private function getCodigoTabela() {

    return $this->iCodigoTabela;
  }

  /**
   * @param integer $iCodigoTabela
   */
  private function setCodigoTabela($iCodigoTabela) {

    $this->iCodigoTabela = $iCodigoTabela;
  }

  /**
   * @return $this->nPercentual
   */
  private function getPercentual() {

    return $this->nPercentual;
  }

  /**
   * @param numeric type $nPercentual
   */
  private function setPercentual($nPercentual) {

    $this->nPercentual = $nPercentual;
  }

  /**
   * @return $this->aCampoChave
   */
  private function getCampoChave() {

    return $this->aCampoChave;
  }

  /**
   * @param string  type $sCampoChave
   */
  private function setCampoChave($sCampoChave) {

    $this->aCampoChave[] = $sCampoChave;
    return $this;
  }

  /**
   * @return $this->aCampoCorrecao
   */
  private function getCampoCorrecao() {

    return $this->aCampoCorrecao;
  }

  /**
   * @param string type $sCampoCorrecao
   */
  private function setCampoCorrecao($sCampoCorrecao) {

    $this->aCampoCorrecao[] = $sCampoCorrecao;
    return $this;
  }

  /**
   * Metodo contrutor da classe
   *
   * @param string type $sNomeTabela
   */
  function __construct() {

    $this->setAnoAtual(db_getsession('DB_anousu'));
    $this->setAnoNovo((db_getsession('DB_anousu') + 1));

    /**
     * Pesquisa se já foi feito a virada anual
     */
    $oDaoIptuTabelasConfig  = db_utils::getDao('iptutabelasconfig');
    $sSqlIptuTabelasConfig  = $oDaoIptuTabelasConfig->sql_query(null, "iptutabelasconfig.j122_sequencial",
                                                                null, "db_sysarquivo.nomearq = 'arretipo'");
    $rsSqlIptuTabelasConfig = $oDaoIptuTabelasConfig->sql_record($sSqlIptuTabelasConfig);
    if ($oDaoIptuTabelasConfig->numrows > 0) {

      $oDadosIptuTabelasConfig      = db_utils::fieldsMemory($rsSqlIptuTabelasConfig, 0);
      $oDaoIptuTabelasConfigVirada  = db_utils::getDao('iptutabelasconfigvirada');

      $sWhere                       = "j129_iptutabelasconfig = {$oDadosIptuTabelasConfig->j122_sequencial}";
      $sWhere                      .= " and j129_anousu = {$this->getAnoNovo()}";
      $sSqlIptuTabelasConfigVirada  = $oDaoIptuTabelasConfigVirada->sql_query_file(null, "*", null, $sWhere);
      $rsSqlIptuTabelasConfigVirada = $oDaoIptuTabelasConfigVirada->sql_record($sSqlIptuTabelasConfigVirada);
      if ($oDaoIptuTabelasConfigVirada->numrows > 0) {

        $sMensagem = "ERRO: Tabela arretipo já foi feito virada anual para exercicío {$this->getAnoNovo()}!";
        throw new Exception($sMensagem);
      }

      $this->setCodigoTabela($oDadosIptuTabelasConfig->j122_sequencial);
    }

    /**
     * Pesquisa tipo de debito
     */
    $sSqlCfIptuTipoDebito     = " select q92_tipo                                          ";
    $sSqlCfIptuTipoDebito    .= "   from cfiptu                                            ";
    $sSqlCfIptuTipoDebito    .= "        inner join cadvencdesc on j18_vencim = q92_codigo ";
    $sSqlCfIptuTipoDebito    .= "  where j18_anousu = {$this->getAnoNovo()}                ";
    $rsSqlCfIptuTipoDebito    = db_query($sSqlCfIptuTipoDebito);
    $iNumRowsCfIptuTipoDebito = pg_num_rows($rsSqlCfIptuTipoDebito);
    if ($iNumRowsCfIptuTipoDebito == 0) {

      $sMensagem = "ERRO: Nenhum registro encontrado na cfiptu exercicío {$this->getAnoNovo()}!";
      throw new Exception($sMensagem);
    }

    $oCfIptuTipoDebito = db_utils::fieldsMemory($rsSqlCfIptuTipoDebito, 0);
    $this->setTipo($oCfIptuTipoDebito->q92_tipo);

      /**
     * Pesquisa percentual padrao
     */
    $oDaoCfIptuCorrecao  = db_utils::getDao('cfiptu');
    $sSqlCfIptuCorrecao  = $oDaoCfIptuCorrecao->sql_query_file($this->getAnoAtual(), "cfiptu.j18_perccorrepadrao", null, '');
    $rsSqlCfIptuCorrecao = $oDaoCfIptuCorrecao->sql_record($sSqlCfIptuCorrecao);
    if ($oDaoCfIptuCorrecao->numrows == 0) {

      $sMensagem = "ERRO: Nenhum registro encontrado na cfiptu exercicío {$this->getAnoAtual()}!";
      throw new Exception($sMensagem);
    }

    $oCfIptuCorrecao = db_utils::fieldsMemory($rsSqlCfIptuCorrecao, 0);
    $this->setPercentual($oCfIptuCorrecao->j18_perccorrepadrao);

    /**
     * Pesquisa campos chave
     */
    $oDaoIptuTabelasConfigCampoChave  = db_utils::getDao('iptutabelasconfigcampochave');
    $sWhere                           = "db_sysarquivo.nomearq = 'arretipo'";
    $sSqlIptuTabelasConfigCampoChave  = $oDaoIptuTabelasConfigCampoChave->sql_query(null, "db_syscampo.nomecam",
                                                                                    null, $sWhere);
    $rsSqlIptuTabelasConfigCampoChave = $oDaoIptuTabelasConfigCampoChave->sql_record($sSqlIptuTabelasConfigCampoChave);

    for ($iInd = 0; $iInd < $oDaoIptuTabelasConfigCampoChave->numrows; $iInd++) {

      $oIptuTabelasConfigCampoChave = db_utils::fieldsMemory($rsSqlIptuTabelasConfigCampoChave, $iInd);
      $this->setCampoChave($oIptuTabelasConfigCampoChave->nomecam);
    }

    /**
     * Pesquisa campo para correcao de percentual
     */
    $oDaoIptuTabelasConfigCorrecao  = db_utils::getDao('iptutabelasconfigcampocorrecao');
    $sWhere                         = "db_sysarquivo.nomearq = 'arretipo'";
    $sSqlIptuTabelasConfigCorrecao  = $oDaoIptuTabelasConfigCorrecao->sql_query(null, "*", null, $sWhere);
    $rsSqlIptuTabelasConfigCorrecao = $oDaoIptuTabelasConfigCorrecao->sql_record($sSqlIptuTabelasConfigCorrecao);
    if ($oDaoIptuTabelasConfigCorrecao->numrows > 0) {

      for ($iInd = 0; $iInd < $oDaoIptuTabelasConfigCorrecao->numrows; $iInd++) {

        $oIptuTabelasConfigCorrecao = db_utils::fieldsMemory($rsSqlIptuTabelasConfigCorrecao, $iInd);
        $this->setCampoCorrecao($oIptuTabelasConfigCorrecao->nomecam);
      }
    }
  }

  /**
   * Processa virada anual
   *
   * @return $this
   */
  public function vira() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta.  \\n\\nProcessamento cancelado.");
    }

    $iAnoAtual = $this->getAnoAtual();
    if ($iAnoAtual == 0) {

      $sMensagem = "ERRO: Ano exercício atual nao definido!";
      throw new Exception($sMensagem);
    }

    $iAnoNovo = $this->getAnoNovo();
    if ($iAnoNovo == 0) {

      $sMensagem = "ERRO: Ano próximo exercício nao definido!";
      throw new Exception($sMensagem);
    }

    $iTipo = $this->getTipo();
    if ($iTipo == 0) {

      $sMensagem = "ERRO: Tipo atual nao definido para exercicío {$this->getAnoAtual()}!";
      throw new Exception($sMensagem);
    }

    $oDaoArretipo  = db_utils::getDao('arretipo');
    $sSqlArretipo  = $oDaoArretipo->sql_query_file($this->getTipo(), "arretipo.*", null, "");
    $rsSqlArretipo = $oDaoArretipo->sql_record($sSqlArretipo);
    $iNumRows = $oDaoArretipo->numrows;
    if ($iNumRows > 0) {

	    for ( $iInd=0; $iInd < $iNumRows; $iInd++ ) {

	      $oDadosArretipo  = db_utils::fieldsMemory($rsSqlArretipo,$iInd);
	      $aCamposArretipo = get_object_vars($oDadosArretipo);

	      foreach ($aCamposArretipo as $sNomeCampoArretipo => $sValorCampoArretipo ) {

	        if (in_array($sNomeCampoArretipo, $this->getCampoCorrecao())) {

	          $nPercentual     = $this->getPercentual();
	          $nSomaPercentual = ($oDadosArretipo->$sNomeCampoArretipo + ($oDadosArretipo->$sNomeCampoArretipo * ($nPercentual / 100)));
	          $oDaoArretipo->$sNomeCampoArretipo = "{$nSomaPercentual}";
	        } else {

		        if (trim($sNomeCampoArretipo) == 'k00_descr') {
	            $oDaoArretipo->$sNomeCampoArretipo = "IPTU {$this->getAnoNovo()}";
            } elseif( trim($sNomeCampoArretipo) == 'k00_agnum' || trim($sNomeCampoArretipo) == 'k00_agpar' ||
                      trim($sNomeCampoArretipo) == 'k00_emrec' || trim($sNomeCampoArretipo) == 'k00_impval' ) {

              $oDaoArretipo->$sNomeCampoArretipo = "false";
              if( $oDadosArretipo->$sNomeCampoArretipo == 't' ){
                $oDaoArretipo->$sNomeCampoArretipo = "true";
              }

            } else {
              $oDaoArretipo->$sNomeCampoArretipo = $oDadosArretipo->$sNomeCampoArretipo;
	          }
	        }
	      }

        $oDaoArretipo->incluir(null);
        if ($oDaoArretipo->erro_status == 0) {
          throw new Exception($oDaoArretipo->erro_msg);
        }
      }
    }

    /**
     * Adiciona registro para verificação se tabela já fez virada anual
     */
    $oDaoIptuTabelasConfigVirada = db_utils::getDao('iptutabelasconfigvirada');
    $oDaoIptuTabelasConfigVirada->j129_iptutabelasconfig = $this->getCodigoTabela();
    $oDaoIptuTabelasConfigVirada->j129_anousu            = $this->getAnoNovo();
    $oDaoIptuTabelasConfigVirada->incluir(null);
    if ($oDaoIptuTabelasConfigVirada->erro_status == 0) {
      throw new Exception($oDaoIptuTabelasConfigVirada->erro_msg);
    }

    return $this;
  }
}
?>