<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once ('model/configuracao/DBEstrutura.model.php');
/**
 * classe para definição e controle do cadastro de estruturas
 *
 */
class DBEstruturaValor {

  protected $oEstrutura;

  protected $iCodigo;

  protected $sEstrutural;

  protected $nivel;

  protected $oEstruturaPai;

  protected $sDescricao;

  protected $iTipoConta;

  protected $iCodigoEstruturaPai;

  protected $sTipoEstrutural = 'estrutural';

  protected $tipo;

  protected $aEstruturaContaAnalitica = array();

  public function __construct($iCodigoEstrutura) {

    if (!empty($iCodigoEstrutura)) {

      $oDaoEstruturaValor = db_utils::getDao("db_estruturavalor");
      $sSqlDadosEstrutura = $oDaoEstruturaValor->sql_query_file($iCodigoEstrutura);
      $rsDadosEstrutura   = $oDaoEstruturaValor->sql_record($sSqlDadosEstrutura);
      if ($oDaoEstruturaValor->numrows > 0) {

        $oDadosEstrutura  = db_utils::fieldsMemory($rsDadosEstrutura, 0);
        $this->iCodigo    = $iCodigoEstrutura;
        $this->iTipoConta = $oDadosEstrutura->db121_tipoconta;
        $this->nivel      = $oDadosEstrutura->db121_nivel;
        $this->iCodigoEstruturaPai  = $oDadosEstrutura->db121_estruturavalorpai;
        $this->sDescricao = $oDadosEstrutura->db121_descricao;
        $this->setEstrutura(new DBEstrutura($oDadosEstrutura->db121_db_estrutura));
        $this->sEstrutural = $oDadosEstrutura->db121_estrutural;
        unset($oDadosEstrutura);
      }
    }
    $this->tipo = __CLASS__;
  }
  /**
   * retorna o tipo da conta
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o tipo da conta 1- Sintetica 2 - analitica
   *
   * @return integer
   */
  public function getTipoConta() {
    return $this->iTipoConta;
  }

  /**
   * Define o Tipo da Conta, aceitando apenas valores do tipo 1- Sintetica 2 - analitica
   * @param integer $iTipoConta
   * @return DBEstruturaValor
   */
  public function setTipoConta($iTipoConta) {

    if ($iTipoConta != 1 && $iTipoConta != 2) {
      throw new Exception("Erro ao definir tipo da conta: valores Validos 1 - Sintetica 2 - Analítica");
    }
    $this->iTipoConta = $iTipoConta;
    return $this;
  }



  public function setEstruturalPai ($sEstrutural) {
    $this->sEstrutural = $sEstrutural;
  }

  /**
   * retorna o nivel da conta
   * @return integer
   */
  public function getNivel() {
    return $this->nivel;
  }

  public function setNivel($nivel) {
    return $this->nivel = $nivel;
  }

  /**
   * Retorna a definição que a estrutura utiliza
   * @return DBEstrutura
   */
  public function getEstrutura() {
    return $this->oEstrutura;
  }

  /**
   * define a estrutura do grupo
   * @param mixed $mEstrutura codigo da estrutura, ou Instancia da Estrutura
   * @return DBEstruturaValor
   */
  public function setEstrutura($mEstrutura) {

    if (is_int($mEstrutura)) {
      $this->oEstrutura =  new DBEstrutura($mEstrutura);
    } else if ($mEstrutura instanceof DBEstrutura) {
      $this->oEstrutura = $mEstrutura;
    }

    return $this;
  }

  /**
   * Retorna a conta de nivel acima do Estrutural
   * @return DBEstruturaValor
   */
  public function getEstruturaPai() {

    if ($this->oEstruturaPai == '') {

      if (!empty($this->iCodigoEstruturaPai)) {
        $this->oEstruturaPai = $this->getDependenciaEstrutura();
      }
    }
    return $this->oEstruturaPai;
  }

  public function setEstruturaPai ($oEstruturaPai) {
    $this->oEstruturaPai = $oEstruturaPai;
  }

  /**
   * Retorna o Estrutural da conta
   * @return string
   */
  public function getEstrutural() {

    return $this->sEstrutural;
  }

  /**
   * a Partir desse set já definos qual o nivel da conta,
   * @param string $sEstrutural  estrutural da Conta
   * @return DBEstruturaValor
   */
  public function setEstrutural($sEstrutural) {

    if ($this->getTipoConta() == "") {
      throw new Exception("tipo da conta nao informada");
    }

    $this->sEstrutural = $sEstrutural;
    $oValidacao        = $this->validarEstrutural();

    if (!$oValidacao->valida) {

      $this->nivel = '';
      $this->sEstrutural = '';
      throw new Exception($oValidacao->errovalidacao);
    }
    $this->nivel   = $this->nivelEstrutura($this->sEstrutural);
    $oEstruturaPai = $this->getDependenciaEstrutura();

    /**
     * verificamos se existe  conta acima cadastrada.
     * a conta acima deverá ser obrigatorio quando:
     * 1 - a conta é analtica.
     * 2 - o nivel é maior que 1,
     */
    $sMensagemInicial = "{$this->sTipoEstrutural} inválido.\n";
    if ($this->getNivel() > 1) {
      if ($oEstruturaPai != "") {

        /**
         * verificamos se  a conta acima é analitica. caso for, não pode existir contas filhas.
         */
        if ($oEstruturaPai->getTipoConta() == 2) {

          $sMensagemInicial .= "O {$this->sTipoEstrutural} acima( {$oEstruturaPai->getEstrutural()}).";
          $sMensagemInicial .=" está cadastrado como analítica.";
          throw new Exception($sMensagemInicial);
        }
      } else {

        $sMensagemInicial .= "{$this->sTipoEstrutural} {$this->getEstrutural()} sem {$this->sTipoEstrutural} sintético.";
        throw new Exception ($sMensagemInicial);
      }
      $this->oEstruturaPai = $oEstruturaPai;
    }
    return $this;
  }
  /**
   * retorna Descricao da estrutura
   * @return string retorna Descricao da estrutura
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * seta a descricao da estrutura
   * @param string $sDescricao descrição da conta
   * @return DBEstruturaValor
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
    return $this;
  }

  /**
   * persite os dados da Estrutura
   *
   * @return DBEstruturaValor
   */
  public function salvar() {

    $oDaoEstruturaValor = db_utils::getDao("db_estruturavalor");
    $oDaoEstruturaValor->db121_db_estrutura = $this->getEstrutura()->getCodigo();
    $oDaoEstruturaValor->db121_estrutural   = $this->getEstrutural();
    $oDaoEstruturaValor->db121_nivel        = $this->getNivel();
    $oDaoEstruturaValor->db121_descricao    = $this->getDescricao();
    $oDaoEstruturaValor->db121_tipoconta    = $this->getTipoConta();

    if ($this->getEstruturaPai() != "") {
      $oDaoEstruturaValor->db121_estruturavalorpai  = $this->getEstruturaPai()->getCodigoEstruturaValor();
    }
    if (!empty($this->iCodigo)) {

      $oDaoEstruturaValor->db121_sequencial = $this->iCodigo;
      $oDaoEstruturaValor->alterar($this->getCodigo());
    } else {

      $oDaoEstruturaValor->incluir(null);
      $this->iCodigo = $oDaoEstruturaValor->db121_sequencial;
    }
    if ($oDaoEstruturaValor->erro_status == 0) {

      $sMsgErro = $oDaoEstruturaValor->erro_msg;
      if (strpos($sMsgErro, "db_estruturavalor_estrutura_estrut_in") !== false) {
        $sMsgErro = "{$this->sTipoEstrutural} {$this->sEstrutural} já cadastradado!.";
      }
      throw new Exception($sMsgErro);
    }
    return $this;
  }


  /**
   * Método Remover
   *
   * Remove da tabela db_estrutravalor os dados correspondidos a uma estrutura
   *
   * @return boolean
   */
  public function remover() {

    $oDaoRemEstruturaValor = db_utils::getDao("db_estruturavalor");

    $iCodigo = $this->getCodigo();
    $oDaoRemEstruturaValor->excluir(null, "db121_sequencial = '{$iCodigo}'");

    if ($oDaoRemEstruturaValor->erro_status == "0") {
      return false;
    } else {
      return true;
    }
  }


  /**
   * Realiza validações no estrutura;
   *
   * @return object
   */
  public function validarEstrutural() {

    $oValidacao = new stdClass();
    $oValidacao->valida        = true;
    $oValidacao->errovalidacao = '';

    $oEstrutura = $this->getEstrutura();

    $aNiveisEstrutura  = $oEstrutura->getNiveis();
    $aNiveisEstrutural = explode(".", $this->getEstrutural());
    /**
     * valida se o estrutural possui o mesmo número de niveis.
     */
    if (count($aNiveisEstrutural) <> count($aNiveisEstrutura)) {

      $oValidacao->valida         = false;
      $oValidacao->errovalidacao  = "Nível do {$this->sTipoEstrutural} Informado não é válido.\n";
      $oValidacao->errovalidacao .= ucfirst($this->sTipoEstrutural)." deve ter ".count($aNiveisEstrutura)." níveis.";
    }

    if ($oValidacao->valida) {

      /**
       * valida se o numero de digitos de cada nivel dpo estrutural está igual ao nivel da mascara
       */
      foreach ($aNiveisEstrutura as $iNivel => $oNivel) {

        if (isset($aNiveisEstrutural[$iNivel])) {

          if ($oNivel->digitos != strlen($aNiveisEstrutural[$iNivel])){

            $oValidacao->valida         = false;
            $oValidacao->errovalidacao  = "Nível {$oNivel->nome} do {$this->sTipoEstrutural} Informado não é válido.\n";
            $oValidacao->errovalidacao .= "Nível {$oNivel->nome} deve ter {$oNivel->digitos} dígitos.";
          }
        }
      }
    }

    return $oValidacao;

  }

  /**
   * retorna o nivel em que a estrutura está digitada
   * @param $sStrutural Estrutural
   * @return integer
   */
  protected function nivelEstrutura($sStrutural) {

    $aNiveis = explode(".", $sStrutural);
    $iNivel  = 1;
    foreach ($aNiveis as $iIndice => $sNivel) {

      $iTamanhoNivel = strlen($sNivel);
      if ($sNivel != str_repeat('0', $iTamanhoNivel)){
         $iNivel  = $iIndice+1;
      }
    }
    return $iNivel;
  }

  protected function getCodigoEstruturalPai() {

    $aNiveis          = explode(".", $this->getEstrutural());
    $iNivel           = $this->getNivel()-1;
    $iTamanho         = strlen($aNiveis[$iNivel]);
    $aNiveis[$iNivel] = str_repeat('0', $iTamanho);
    return implode(".", $aNiveis);
  }

  /**
   * Verifica se a Estrutura possui alguma dependencia, verificando se existe a estrutura cadastrada
   * acima , caso necessário
   *
   * @return DBEstruturaValor
   */
  protected function getDependenciaEstrutura() {

    if (empty($this->tipo )) {
      throw new Exception(__CLASS__.": Erro tipo da classe nao definido. Deve ser definido a propriedade tipo");
    }
    $sEstruturalpai      = $this->getCodigoEstruturalPai();
    $oEstruturaPai       = null;
    if ($sEstruturalpai != $this->getEstrutura()->getMascara()) {

      $oDaoEstruturaValor = db_utils::getDao("db_estruturavalor");
      $sWhere             = " db121_estrutural = '{$sEstruturalpai}' ";
      $sWhere            .= " and db121_db_estrutura = {$this->getEstrutura()->getCodigo()}";
      $sSqlEstruturaPai   = $oDaoEstruturaValor->sql_query_file(null, "db121_sequencial", null, $sWhere);
      $rsEstruturaPai     = $oDaoEstruturaValor->sql_record($sSqlEstruturaPai);
      if ($oDaoEstruturaValor->numrows > 0) {

        $iCodigoPai       = db_utils::fieldsMemory($rsEstruturaPai, 0)->db121_sequencial;

        if (get_class($this) == 'DBEstruturaValor') {
          $iCodigoEstrutura  = $iCodigoPai;
        }
        else {
          $iCodigoEstrutura = call_user_func_array($this->tipo."::getCodigoByEstrutura", array($iCodigoPai));
        }
        $oEstruturaPai    = new $this->tipo($iCodigoEstrutura);
      }
    }
    return $oEstruturaPai;
  }

  public function getCodigoEstruturaValor() {
    return $this->iCodigo;
  }






  public function getFilhosNivel($iNivel) {

    $oDaoEstruturaValor = db_utils::getDao("db_estruturavalor");
    $sWhere             = " db121_estruturavalorpai = '{$this->iCodigo}' and db121_nivel = {$iNivel} ";
    $sSql               = $oDaoEstruturaValor->sql_query_file(null,"*",null,$sWhere);
    $rsEstruturaValorFilhos =  $oDaoEstruturaValor->sql_record($sSql);

    //die($sSql );
    if ($oDaoEstruturaValor->numrows > 0) {

      $aColecaoFilhos = db_utils::getCollectionByRecord($rsEstruturaValorFilhos);
      return $aColecaoFilhos;
    }

    return false;
  }

  /**
   * Método que carrega as contas filhas (analiticas) de um pai (sintetica). Quando o tipo de conta for 2.
   * @param  integer $iCodigoEstruturaPai
   * @return boolean
   */
  public function loadContasAnaliticas($iCodigoEstruturaPai) {

    $oDaoEstruturaValor = db_utils::getDao('db_estruturavalor');
    $sWhereEstruturaPai = "db121_estruturavalorpai = {$iCodigoEstruturaPai}";
    $sSqlBuscaPai       = $oDaoEstruturaValor->sql_query_file(null, "*", null, $sWhereEstruturaPai);
    $rsBuscaPai         = $oDaoEstruturaValor->sql_record($sSqlBuscaPai);
    $iLinhasBuscaPai    = $oDaoEstruturaValor->numrows;
    for ($iRowBuscaPai = 0; $iRowBuscaPai < $iLinhasBuscaPai; $iRowBuscaPai++) {

      $oDadoBuscaPai = db_utils::fieldsMemory($rsBuscaPai, $iRowBuscaPai);
      if ($oDadoBuscaPai->db121_tipoconta == 1) {
      		$this->loadContasAnaliticas($oDadoBuscaPai->db121_sequencial);
      } else {
      		$this->aEstruturaContaAnalitica[] = new DBEstruturaValor($oDadoBuscaPai->db121_sequencial);
      }
    }
    return true;
  }


  /**
   * Retorna um array contendo objetos de DBEstruturaValor com as contas filhas
   * de um determinado pai.
   * @array DBEstruturaValor
   */
  public function getContasAnaliticas() {
    return $this->aEstruturaContaAnalitica;
  }





}
?>