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


class PlacaBem {

  /**
   * Tipo do parâmetro setado na tabela cfpatripalca
   * @var integer
   */
  protected $iTipoPlaca;
  /**
   * Código do Bem. Tabela: bensplaca.t41_bem
   * @var integer
   */
  protected $iCodigoBem;
  /**
   * Código do Sequencial da Tabela: bensplaca.t41_codigo
   * @var integer
   */
  protected $iCodigoPlaca;
  /**
  * Observação da Tabela: bensplaca.t41_obs
  * @var String
  */
  protected $sObservacao;
  /**
  * Sequencial da Placa. Tabela: bensplaca.t41_placaseq
  * @var String
  */
  protected $sPlacaSeq;
  /**
  * Placa da Tabela: bensplaca.t41_placa
  * @var String
  */
  protected $sPlaca;
  /**
  * Data. Tabela: bensplaca.t41_data
  * @var String
  */
  protected $sData;
  /**
  * Código Usuário. Tabela: bensplaca.t41_usuario
  * @var String
  */
  protected $iCodigoUsuario ;
  /**
   *
   * True se a placa esta impressa
   * @var booleam
   */
  protected $lPlacaImpressa = null;

  /**
   * Instituicao do bem da placa
   * @var Instituicao
   */
  protected $oInstituicao;



  /**
   *
   * Se setado codigo da placa realiza uma busca na tabela bensplaca pelo código e seta os dados de retorno
   * @param integer $iCodigoPlaca
   */
  public function __construct($iCodigoPlaca = null) {

    if (!empty($iCodigoPlaca)) {

      $oPlaca = $this->getPlacaDados($iCodigoPlaca, '*');

      if ($oPlaca != null) {

        $this->iCodigoPlaca   = $iCodigoPlaca;
        $this->iCodigoBem     = $oPlaca->t41_bem;
        $this->sPlaca         = $oPlaca->t41_placa;
        $this->sPlacaSeq      = $oPlaca->t41_placaseq;
        $this->sData          = $oPlaca->t41_data;
        $this->sObservacao    = $oPlaca->t41_obs;
        $this->iCodigoUsuario = $oPlaca->t41_usuario;
        $this->oInstituicao   = new Instituicao($oPlaca->t52_instit);
        unset($oPlaca);
      }
    }
  }

  /**
   *
   * Executa um sql com base no parâmetros recebidos na tabela bensplaca
   * @param Integer $iCodigo
   * @param String $sCampos
   * @param String $sOrder
   * @param String $sWhere
   * @return Object
   */
  protected function getPlacaDados($iCodigo=null, $sCampos="*", $sOrder=null, $sWhere="") {

    $oDaoBensPlaca = db_utils::getDao("bensplaca");
    $sSql          = $oDaoBensPlaca->sql_query_fileLockInLine($iCodigo, $sCampos, $sOrder, $sWhere);
    $rsBensPlaca   = $oDaoBensPlaca->sql_record($sSql);

    if ($oDaoBensPlaca->numrows == 1) {
      return  db_utils::fieldsMemory($rsBensPlaca, 0);
    } else if ($oDaoBensPlaca->numrows == 0 ) {
      return null;
    } else if ($oDaoBensPlaca->numrows > 1) {
      return  db_utils::getCollectionByRecord($rsBensPlaca);
    }
  }

  /**
   * Retorna o código da Placa setada no Construtor
   * @return integer
   */
  public function getPlacaSeq() {
     return $this->sPlacaSeq;
  }
  public function getPlaca() {
    return $this->sPlaca;
  }

  /**
   *
   * Busca o proximo sequencial de acordo com o parâmetro setado na tabela cfpatriplaca.t07_confplaca
   * @param mixed $mParam Este parâmetro pode ser uma classe, um texto
   * @throws Exception
   * @return integer
   */
  public function getProximaPlaca($mParam=null) {

    if (!db_utils::inTransaction()) {
      throw new Exception("No momento não ha uma transação em aberto. \nAguarde um momento e repita a ação.");
    }

    $this->iTipoPlaca = BensParametroPlaca::getCodigoParametro();

    switch ($this->iTipoPlaca) {

      case BensParametroPlaca::PLACA_SEQUENCIAL_AUTOMATICO:  // SEQUENCIAL AUTOMÁTICO

        return BensParametroPlaca::getSequencial();
        break;
      case BensParametroPlaca::PLACA_CLASSIFICACAO_SEQUENCIAL:  //	CLASSIFICAÇÃO + SEQUENCIAL


        return  $this->buscaSequencialMaximoPeloTipo($mParam);
        break;
      case BensParametroPlaca::PLACA_TEXTO_SEQUENCIAL:  // TEXTO + SEQUENCIAL

        return  $this->buscaSequencialMaximoPeloTipo($mParam);
        break;

      case BensParametroPlaca::PLACA_SEQUENCIAL_DIGITADO:  // SEQUENCIAL DIGITADO

        return true;
        break;
    }
  }


  /**
   * Verifica se uma placa de bem já existe. Se não existe chama o metodo incluir
   * Antes de Salvar use o método os metodos set
   * @throws Exception
   * @return boolean;
   */
  public function salvar() {

    if (empty($this->iCodigoPlaca)) {

      $this->iTipoPlaca = BensParametroPlaca::getCodigoParametro();

      switch ($this->iTipoPlaca) {

        case BensParametroPlaca::PLACA_SEQUENCIAL_AUTOMATICO : // SEQUENCIAL AUTOMÁTICO

          if ($this->pesquisaSeSequencialExiste()) {
            throw new Exception("Não foi possível incluir pois a placa já existe no sistema.");
          }
          break;
        case BensParametroPlaca::PLACA_CLASSIFICACAO_SEQUENCIAL: // CLASSIFICAÇÃO + SEQUENCIAL

          $sPlaca = " {$this->sPlaca} ";
          if ($this->pesquisaSeSequencialExiste($sPlaca)) {
            throw new Exception("Não foi possível incluir pois a placa já existe no sistema.");
          }
          break;
        case BensParametroPlaca::PLACA_TEXTO_SEQUENCIAL: // TEXTO + SEQUENCIAL

          $sPlaca = " {$this->sPlaca} ";
          if ($this->pesquisaSeSequencialExiste($sPlaca)) {
            throw new Exception("Não foi possível incluir pois a placa já existe no sistema.");
          }
          break;

        case BensParametroPlaca::PLACA_SEQUENCIAL_DIGITADO: // SEQUENCIAL DIGITADO

          if ($this->pesquisaSeSequencialExiste(null)) {
            throw new Exception("Não foi possível incluir pois a placa já existe no sistema.");
          }
          break;
      }
    }
    $this->persistirDados();
  }

  /**
   *
   * Salva ou Inclui dados
   * @throws Exception
   */
  protected function persistirDados() {

    $oDaoBensPlaca = db_utils::getDao("bensplaca");
    $oDaoBensPlaca->t41_bem      = $this->iCodigoBem;
    $oDaoBensPlaca->t41_placa    = $this->sPlaca;
    $oDaoBensPlaca->t41_placaseq = $this->sPlacaSeq;
    $oDaoBensPlaca->t41_obs      = $this->sObservacao;
    $oDaoBensPlaca->t41_data     = $this->sData;
    $oDaoBensPlaca->t41_usuario  = db_getsession("DB_id_usuario");


    if (empty($this->iCodigoPlaca)) {

      $oDaoBensPlaca->incluir(null);
      $this->iCodigoPlaca  = $oDaoBensPlaca->t41_codigo;

      /**
       * Atualizar a tabela CFpatriplaca.
       */
      if ($this->iTipoPlaca == 1) {

        $oDaoPatriPlaca = db_utils::getDao("cfpatriplaca");
        $oDaoPatriPlaca->t07_instit     = db_getsession("DB_instit");
        $oDaoPatriPlaca->t07_sequencial = str_replace(".", "", ($this->sPlacaSeq+1));

        if ($this->iTipoPlaca == 4) {
        	$oDaoPatriPlaca->t07_sequencial = str_replace(".", "", ($this->sPlacaSeq));
        }

        $oDaoPatriPlaca->alterar(db_getsession("DB_instit"));

        if ($oDaoPatriPlaca->erro_status == 0) {
          throw new Exception("Erro ao salvar dados da placa {$oDaoPatriPlaca->erro_msg}");
        }
      }
    } else {

      $oDaoBensPlaca->t41_codigo = $this->iCodigoPlaca;
      $oDaoBensPlaca->alterar($this->iCodigoPlaca);
    }

    if ($oDaoBensPlaca->erro_status == 0) {
      throw new Exception("Erro ao persistir dados: {$oDaoBensPlaca->erro_msg}");
    }

    return true;
  }

  public function setCodigoBem($iCodigoBem) {
    $this->iCodigoBem = $iCodigoBem;
  }
  public function setObservacao($sObservacao) {

    $this->sObservacao = '';
    if (!empty($sObservacao)) {
      $this->sObservacao = $sObservacao;
    }
  }
  public function setPlacaSeq($sPlacaSeq) {
    $this->sPlacaSeq = $sPlacaSeq;
  }
  public function setPlaca($sPlaca) {

    $this->sPlaca = '';
    if (!empty($sPlaca)) {
      $this->sPlaca = $sPlaca;
    }
  }

  public function setData($sData) {
    $this->sData = implode('-', array_reverse(explode("/",  $sData)));
  }

  /**
  *
  * Busca o Sequencial Máximo do código da placa se o Parametro for tipo 2 ou 3
  * @param  mixed $mParam Este parâmetro pode ser uma classe, um texto
  * @return mixed
  */
  protected function buscaSequencialMaximoPeloTipo($mParam) {

    if (empty($mParam)) {
      throw new Exception("Você tem que passar a classe por parâmetro.");
    }

    $mParam  = strtoupper($mParam);
    $sCampos = "max(t41_placaseq) as placa";
    $sWhere  = "t41_placa = '{$mParam}' ";

    if (BensParametroPlaca::controlaPlacaPorInstituicao()) {
      $sWhere  .= " and t52_instit = ".db_getsession("DB_instit");
    }

    $oParametro = $this->getPlacaDados(null, $sCampos, null, $sWhere);
    if ($oParametro == null) {
      return 1;
    }

    return ++$oParametro->placa;
  }

  /**
   *
   * Verifica se um sequencial existe.
   * Se o parâmetro cfpatriplaca.t07_confplaca = 2 ou 3, utiliza a variavel $sPlaca recebida por parâmetro como filtro
   *
   * @param String $sPlaca
   * @return boolean
   */
  public function pesquisaSeSequencialExiste($sPlaca = null) {

    $sWHereInstituicao         = null;
    $lControlaPlacaInstituicao = BensParametroPlaca::controlaPlacaPorInstituicao();

    if ($lControlaPlacaInstituicao) {
      $sWHereInstituicao = ' and t52_instit = ' .db_getsession('DB_instit');
    }

    $lExiste = true;
    $sWhere  =  " exists (select 1 from bens where t52_bem = t41_bem $sWHereInstituicao) and t41_placaseq = {$this->sPlacaSeq} ";

    if ($sPlaca != null) {
      $sWhere .= " and t41_placa = '{$sPlaca}'";
    }

    $oParametro = $this->getPlacaDados(null, "*", null, $sWhere);

    if ($oParametro == null) {
      $lExiste = false;
    }
    return $lExiste;
  }

  /**
   * Retorna o numero da placa.
   * a Placa pode ser constituida de duas informações,
   * como a classificação do bem e ou, uma string mais o sequencial dentro desse grupo.
   * ou Apenas o sequencial da placa.
   * @return string
   */
  public function getNumeroPlaca() {
    return $this->sPlaca.$this->sPlacaSeq;
  }

  /**
   * @return boolean
   */
  public function isPlacaImpressa() {

    if (empty($this->lPlacaImpressa) && !empty($this->iCodigoBem)) {

      $this->lPlacaImpressa = false;
      $oDaoPlacaImpressa = db_utils::getDao("bensplacaimpressa");

      $sWhere = "t41_bem = {$this->iCodigoBem}";
      $sSql   = $oDaoPlacaImpressa->sql_query(null, "t73_sequencial", null, $sWhere);

      $rsPlacaImpressa = $oDaoPlacaImpressa->sql_record($sSql);

      if ($oDaoPlacaImpressa->numrows > 0) {

        $this->lPlacaImpressa = true;
      }
    }

    return $this->lPlacaImpressa;
  }

  /**
   * @throws DBException
   * @return void
   */
  public static function bloqueiaManutencao() {

    $oDaoBensPlaca            = new cl_bensplaca();
    $sSqlBloqueiaMovimentacao = $oDaoBensPlaca->sql_query_file(null, "*") . " for update ";
    $rsBloqueiaMovimentacao   = db_query($sSqlBloqueiaMovimentacao);

    if (!$rsBloqueiaMovimentacao) {
      throw new DBException('Não foi possível bloquear a movimentação da tabela bensplaca.');
    }
  }



}