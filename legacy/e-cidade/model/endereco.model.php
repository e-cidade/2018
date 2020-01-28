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

class endereco  {

  private $db70_sequencial     = null;
  private $db70_descricao      = '';
  private $db71_sequencial     = null;
  private $db71_descricao      = '';
  private $db71_sigla          = '';
  private $db72_sequencial     = null;
  private $db72_descricao      = '';
  private $db73_sequencial     = null;
  private $db73_descricao      = '';
  private $db74_sequencial     = null;
  private $db74_descricao      = '';
  private $db75_sequencial     = null;
  private $db75_numero         = '';
  private $db76_sequencial     = null;
  private $db76_complemento    = '';
  private $db76_loteamento     = '';
  private $db76_condominio     = '';
  private $db76_pontoreferencia = '';
  private $db76_cep            = '';
  private $db85_ruastipo       = null;
  private $db85_cadenderrua    = null;
  private $db85_sequencial     = null;
  private $db86_sequencial     = null;
  private $db86_cep            = '';
  private $db87_sequencial     = null;
  private $db88_sequencial     = null;
  private $db88_cadenderrua    = null;
  private $db88_ruas           = null;
  private $sTipoRua            = null;
  private $db125_codigosistema = '';
  private $db76_caixapostal    = '';

  function __construct($iCodigoEndereco=null) {

    if ($iCodigoEndereco != null) {

      $sWhere       = " db76_sequencial = ".$iCodigoEndereco;
      $oDaoLocal    = new cl_cadenderlocal();
      $sQueryLocal  = $oDaoLocal->sql_query_completo(null, "*", null, $sWhere);
      $rsQueryLocal = $oDaoLocal->sql_record($sQueryLocal);
      if ($rsQueryLocal === false ){

        throw new Exception('Nenhum endereço encontrado para o código informado('.$iCodigoEndereco.').');
      }

      $oDadosEndereco = db_utils::fieldsMemory($rsQueryLocal,0);

      //print_r($oDadosEndereco);
      $this->setCodigoPais($oDadosEndereco->db70_sequencial);
      $this->setDescricaoPais($oDadosEndereco->db70_descricao);

      $this->setCodigoEstado($oDadosEndereco->db71_sequencial);
      $this->setDescricaoEstado($oDadosEndereco->db71_descricao);
      $this->setSiglaEstado($oDadosEndereco->db71_sigla);


      $this->setCodigoMunicipio($oDadosEndereco->db72_sequencial);
      $this->setDescricaoMunicipio($oDadosEndereco->db72_descricao);

      $this->setCodigoBairro($oDadosEndereco->db73_sequencial);
      $this->setDescricaoBairro($oDadosEndereco->db73_descricao);

      $this->setCodigoRua($oDadosEndereco->db74_sequencial);
      $this->setDescricaoRua($oDadosEndereco->db74_descricao);

      $this->setCodigoRuasTipo($oDadosEndereco->db85_sequencial);
      $this->setCadEnderRuaTipo($oDadosEndereco->db85_ruastipo);

      $this->setCodigoLocal($oDadosEndereco->db75_sequencial);
      $this->setNumeroLocal($oDadosEndereco->db75_numero);

      $this->setCodigoEndereco($oDadosEndereco->db76_sequencial);
      $this->setCondominioEndereco($oDadosEndereco->db76_condominio);
      $this->setLoteamentoEndereco($oDadosEndereco->db76_loteamento);
      $this->setComplementoEndereco($oDadosEndereco->db76_complemento);
      $this->setPontoReferenciaEndereco($oDadosEndereco->db76_pontoref);
      $this->setCepEndereco($oDadosEndereco->db76_cep);
      $this->setCep($oDadosEndereco->db86_cep);
      $this->setCodigoCep($oDadosEndereco->db86_sequencial);
      $this->setTipoRua($oDadosEndereco->j88_descricao);

      $this->setCodigoSistemaExterno($oDadosEndereco->db125_codigosistema);
    }
  }
  /**
   * Metodo para setar a propriedade cep do enderco
   * @param string cep
   * @return void
   */
  public function setCep($sCep) {

    $this->db86_cep = $sCep;
  }
  /**
   * Metodo para retornar a propriedade cep do enderco
   * @return string cep
   */
  public function getCep() {

   return $this->db86_cep;
  }
  /**
   * Metodo para setar a propriedade codigo sequencial do cep do enderco
   * @param string cep
   * @return void
   */
  public function setCodigoCep($iCodigoCep) {

    $this->db86_sequencial = $iCodigoCep;
  }

  /**
   * @param null $sTipoRua
   */
  public function setTipoRua($sTipoRua) {
    $this->sTipoRua = $sTipoRua;
  }

  /**
   * @return null
   */
  public function getTipoRua() {
    return $this->sTipoRua;
  }

  /**
   * Metodo para retornar a propriedade codigo do cep do enderco
   * @return integer cep
   */
  public function getCodigoCep() {

   return $this->db86_sequencial;
  }
  /**
   * Metodo para setar a propriedade codigo sequencial da ruatipo
   * @param string codigo
   * @return void
   */
  public function setCodigoRuasTipo($iCodigoRuasTipo) {

    $this->db85_sequencial = $iCodigoRuasTipo;
  }
  /**
   * Metodo para retornar a propriedade cep do enderco
   * @return string cep
   */
  public function getCodigoRuasTipo() {

    return $this->db85_sequencial;
  }
  /**
   * Metodo para setar a propriedade do codigo da rua do enderceo
   * @param integer iCodigoCadEnderRua
   * @return void
   */
  public function setCadEnderRua($iCodigoCadEnderRua) {

    $this->db85_cadenderrua = $iCodigoCadEnderRua;
  }
  /**
   * Metodo para retornar a propriedade codigo do endereco da cadenderruaruastipo
   * @return integer db85_cadenderrua
   */
  public function getCadEnderRua() {

    return $this->db85_cadenderrua;
  }
  /**
   * Metodo para setar a propriedade do codigo do tipo de rua do endereco
   * @param integer iCodigoRuaTipo
   * @return void
   */
  public function setCadEnderRuaTipo($iCodigoRuaTipo) {

    $this->db85_ruastipo = $iCodigoRuaTipo;
  }
  /**
   * Metodo para retornar a propriedade codigo do tipo de rua da cadenderruaruastipo
   * @return string cep
   */
  public function getCadEnderRuaTipo() {

    return $this->db85_ruastipo;
  }

  /**
   * Metodo para setar a propriedade Ponto de Referncia do endereco
   * @param string sPontoReferenciaEndereco
   * @return void
   */
  public function setPontoReferenciaEndereco($sPontoReferenciaEndereco) {

    $this->db76_pontoreferencia = $sPontoReferenciaEndereco;
  }
  /**
   * Metodo para retornar a propriedade pontodereferencia do endereco
   * @return string  db76_pontoreferencia
   */
  public function getPontoReferenciaEndereco() {

    return $this->db76_pontoreferencia;
  }
  /**
   * Metodo para setar a propriedade cep do enderco
   * @param string sCepEndereco
   * @return void
   */
  public function setCepEndereco($sCepEndereco) {

    $this->db76_cep = $sCepEndereco;
  }
  /**
   * Metodo para retornar a propriedade cep do endereco
   * @return string  db76_cep
   */
  public function getCepEndereco() {

    return $this->db76_cep;
  }
  /**
   * Metodo para setar a propriedade Condominio do endereco
   * @param string $sCondominioEndereco
   * @return void
   */
  public function setCondominioEndereco($sCondominioEndereco) {

    $this->db76_condominio = $sCondominioEndereco;
  }
  /**
   * Metodo para retornar a propriedade condominio do endereco
   * @return string  db76_condominio
   */
  public function getCondominioEndereco() {

    return $this->db76_condominio;
  }
  /**
   * Metodo para setar a propriedade Loteamento do endereco
   * @param string sLoteamentoEndereco
   * @return void
   */
  public function setLoteamentoEndereco($sLoteamentoEndereco) {

    $this->db76_loteamento = $sLoteamentoEndereco;
  }
  /**
   * Metodo para retornar a propriedade loteamento do endereco
   * @return string  db76_loteamento
   */
  public function getLoteamentoEndereco() {

    return $this->db76_loteamento;
  }
  /**
   * Metodo para setar a propriedade Complemento do endereco
   * @param string $sComplementoEndereco
   * @return void
   */
  public function setComplementoEndereco($sComplementoEndereco) {

    $this->db76_complemento = $sComplementoEndereco;
  }
  /**
   * Metodo para retornar a propriedade complemento do endereco
   * @return string  db76_complemento
   */
  public function getComplementoEndereco() {

    return $this->db76_complemento;
  }
  /**
   * Metodo para setar a propriedade codigo do endereco
   * @param integer iCodigoEndereco
   * @return void
   */
  public function setCodigoEndereco($iCodigoEndereco) {

    $this->db76_sequencial = $iCodigoEndereco;
  }
  /**
   * Metodo para retornar a propriedade codigo do endereco
   * @return string  db76_sequencial
   */
  public function getCodigoEndereco() {

    return $this->db76_sequencial;
  }
  /**
   * Metodo para setar a propriedade Número do endereco
   * @param string sNumeroLocal
   * @return void
   */
  public function setNumeroLocal($sNumeroLocal) {

    $this->db75_numero = $sNumeroLocal;
  }
  /**
   * Metodo para retornar a propriedade numero do endereco
   * @return string  db75_numero
   */
  public function getNumeroLocal() {

    return $this->db75_numero;
  }
  /**
   * Metodo para setar a propriedade codigo do local do endereco
   * @param integer iCodigoNumero
   * @return void
   */
  public function setCodigoLocal($iCodigoNumero) {

    $this->db75_sequencial = $iCodigoNumero;
  }
  /**
   * Metodo para retornar a propriedade codigo do local do endereco
   * @return string  db75_sequencial
   */
  public function getCodigoLocal() {

    return $this->db75_sequencial;
  }
  /**
   * Metodo para setar a propriedade descrição ad rua do endereco
   * @param string sDescricaoRua
   * @return void
   */
  public function setDescricaoRua($sDescricaoRua) {

    $this->db74_descricao = $sDescricaoRua;
  }
  /**
   * Metodo para retornar a propriedade descricao da rua do endereco
   * @return string  db74_descricao
   */
  public function getDescricaoRua() {

    return $this->db74_descricao;
  }
  /**
   * Metodo para setar a propriedade codigo da rua do enderco
   * @param integer iCodigoRua
   * @return void
   */
  public function setCodigoRua($iCodigoRua) {

    $this->db74_sequencial = $iCodigoRua;
  }
  /**
   * Metodo para retornar a propriedade codigo da rua do endereco
   * @return string  db74_sequencial
   */
  public function getCodigoRua() {

    return $this->db74_sequencial;
  }
  /**
   * Metodo para setar a propriedade descricao do bairro do endereco
   * @param string sDescricaoBairro
   * @return void
   */
  public function setDescricaoBairro($sDescricaoBairro) {

    $this->db73_descricao = $sDescricaoBairro;
  }
  /**
   * Metodo para retornar a propriedade descricao do bairro do endereco
   * @return string  db73_descricao
   */
  public function getDescricaoBairro() {

    return $this->db73_descricao;
  }
  /**
   * Metodo para setar a propriedade codigo do bairro do endereco
   * @param integer iCodigoBairro
   * @return void
   */
  public function setCodigoBairro($iCodigoBairro) {

    $this->db73_sequencial = $iCodigoBairro;
  }
  /**
   * Metodo para retornar a propriedade codigo do bairro do endereco
   * @return string  db73_sequencial
   */
  public function getCodigoBairro() {

    return $this->db73_sequencial;
  }
  /**
   * Metodo para setar a propriedade descricao do municipio do endereco
   * @param string sDescricaoMunicipio
   * @return void
   */
  public function setDescricaoMunicipio($sDescricaoMunicipio) {

    $this->db72_descricao = $sDescricaoMunicipio;
  }
  /**
   * Metodo para retornar a propriedade descricao do municipio do endereco
   * @return string  db72_descricao
   */
  public function getDescricaoMunicipio() {

    return $this->db72_descricao;
  }
  /**
   * Metodo para setar a propriedade codigo do municipio do enderco
   * @param integer iCodigoMunicipio
   * @return void
   */
  public function setCodigoMunicipio($iCodigoMunicipio) {

    $this->db72_sequencial = $iCodigoMunicipio;
  }
  /**
   * Metodo para retornar a propriedade codigo do municipio do endereco
   * @return string  db72_sequencial
   */
  public function getCodigoMunicipio() {

    return $this->db72_sequencial;
  }
  /**
   * Metodo para setar a propriedade descricao do estado do enderco
   * @param string sDescricaoEstado
   * @return void
   */
  public function setDescricaoEstado($sDescricaoEstado) {

    $this->db71_descricao = $sDescricaoEstado;
  }
  /**
   * Metodo para retornar a propriedade descricao do estado do endereco
   * @return string  db71_descricao
   */
  public function getDescricaoEstado() {

    return $this->db71_descricao;
  }

  /**
   * Seta a sigla do estado
   * @param string $sSiglaEstado
   */
  public function setSiglaEstado( $sSiglaEstado ) {
    $this->db71_sigla = $sSiglaEstado;
  }

  /**
   * Retorna a sigla do estado
   * @return string
   */
  public function getSiglaEstado() {
    return $this->db71_sigla;
  }

  /**
   * Metodo para setar a propriedade codigo do estado do enderco
   * @param string cep
   * @return void
   */
  public function setCodigoEstado($iCodigoEstado) {

    $this->db71_sequencial = $iCodigoEstado;
  }
  /**
   * Metodo para retornar a propriedade codigo do estado do endereco
   * @return string  db71_sequencial
   */
  public function getCodigoEstado() {

    return $this->db71_sequencial;
  }
  /**
   * Metodo para setar a propriedade codigo do pais do endereco
   * @param integer iCodigoPais
   * @return void
   */
  public function setCodigoPais($iCodigoPais) {

    $this->db70_sequencial = $iCodigoPais;
  }
  /**
   * Metodo para retornar a propriedade codigo do pais do endereco
   * @return string  db70_sequencial
   */
  public function getCodigoPais() {

    return $this->db70_sequencial;
  }
  /**
   * Metodo para setar a propriedade cep do enderco
   * @param string cep
   * @return void
   */
  public function setDescricaoPais($sDescricaoPais) {

    $this->db70_descricao = $sDescricaoPais;
  }
  /**
   * Metodo para retornar a propriedade descricao do pais do endereco
   * @return string  db70_descricao
   */
  public function getDescricaoPais() {

    return $this->db70_descricao;
  }
  /**
   * Metodo para setar a propriedade do codigo da ligacao entre endereco e bairro do enderco
   * @param integer iCodigoBairroRua
   * @return void
   */
  public function setCodigoCadEnderBairroRua($iCodigoBairroRua) {

    $this->db87_sequencial = $iCodigoBairroRua;
  }
  /**
   * Metodo para retornar a propriedade codigo do bairro coma rua do endereco
   * @return string  db87_sequencial
   */
  public function getCodigoCadEnderBairroRua() {

    return $this->db87_sequencial;
  }

  public function setCodigoCadEnderRuaRuas($iCodigoRuaRuas) {

    $this->db88_sequencial = $iCodigoRuaRuas;
  }
  public function getCodigoCadEnderRuaRuas() {

    return $this->db88_sequencial;
  }

  public function setRuaCadEnderRuaRuas($iCodigoCadEnderRua) {

    $this->db88_cadenderrua = $iCodigoCadEnderRua;
  }

  public function getRuaCadEnderRuaRuas() {

    return $this->db88_cadenderrua;
  }

  public function setRuasCadEnderRuaRuas($iCodigoRuas) {

    $this->db88_ruas = $iCodigoRuas;
  }

  public function getRuasCadEnderRuaRuas() {

    return $this->db88_ruas;
  }

  /**
   * Retorna o código do sistema externo do município
   * @return string
   */
  public function getCodigoSistemaExterno() {
    return $this->db125_codigosistema;
  }

  /**
   * Seta o código do sistema externo do município
   * @param string $sCodigoSistemaExterno
   */
  public function setCodigoSistemaExterno( $sCodigoSistemaExterno ) {
    $this->db125_codigosistema = $sCodigoSistemaExterno;
  }

  /**
   * Retorna a caixa postal do endereço
   * @return string
   */
  public function getCaixaPostal() {
    return $this->db76_caixapostal;
  }

  /**
   * Seta a caixa postal referente ao endereço
   * @param string $sCaixaPostal
   */
  public function setCaixaPostal( $sCaixaPostal ) {
    $this->db76_caixapostal = $sCaixaPostal;
  }

  /**
   * Método para realizar a inclusão do município
   * caso ele não esteja cadastrado
   */
  private function cadEnderMunicipio() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não exite transação ativa.');
    }

    if ($this->getCodigoMunicipio() == null) {

      $oDaoMunicipio  = db_utils::getDao('cadendermunicipio');
      $oDaoMunicipio->db72_descricao       = $this->getDescricaoMunicipio();
      $oDaoMunicipio->db72_cadenderestado  = $this->getCodigoEstado();
      $oDaoMunicipio->incluir(null);
      if ($oDaoMunicipio->erro_status == '0') {

         throw new Exception($oDaoMunicipio->erro_msg);
      } else {
        $this->setCodigoMunicipio($oDaoMunicipio->db72_sequencial);
      }
    }
  }
  /**
   * Método para realizar a inclusão do bairro
   * caso ele não esteja cadastrado
   */
  private function cadEnderBairro() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não existe transação ativa.');
    }

    if ($this->getCodigoBairro() == null) {

      $oDaoBairro = db_utils::getDao('cadenderbairro');
      $oDaoBairro->db73_cadendermunicipio = $this->getCodigoMunicipio();
      $oDaoBairro->db73_descricao      = $this->getDescricaoBairro();
      $oDaoBairro->incluir(null);
      if ($oDaoBairro->erro_status == '0') {

        throw new Exception($oDaoBairro->erro_msg);
      } else {
        $this->setCodigoBairro($oDaoBairro->db73_sequencial);
      }
    }
  }
  /**
   * Método para realizar a inclusão da rua
   * caso ele não esteja cadastrado
   */
  private function cadEnderRua() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não existe transação ativa.');
    }

    if ($this->getCodigoRua() == null) {

      $oDaoRua = db_utils::getDao('cadenderrua');
      $oDaoRua->db74_cadendermunicipio = $this->getCodigoMunicipio();
      $oDaoRua->db74_descricao         = $this->getDescricaoRua();
      //$oDaoRua->db74_cep               = $this->getCepEndereco();
      $oDaoRua->incluir(null);
      if ($oDaoRua->erro_status == '0') {

         throw new Exception($oDaoRua->erro_msg);
      } else {
        $this->setCodigoRua($oDaoRua->db74_sequencial);
      }
    }

  }
  /**
   * Método para realizar a inclusão do Cep
   * caso ele não esteja cadastrado
   */
  private function cadEnderRuaCep() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não existe transação ativa.');
    }


    $oDaoCep = db_utils::getDao('cadenderruacep');
    $sWhere = " db86_cep = '".$this->getCep()."' and db86_cadenderrua = ".$this->getCodigoRua();
    $sQueryCep  = $oDaoCep->sql_query_file(null, "*", null, $sWhere);
      //echo $sQueryCep;
    $rsQueryCep = $oDaoCep->sql_record($sQueryCep);
    if ($rsQueryCep === false) {
      if ($this->getCep() != "" ) {
        $oDaoCep->db86_cep         = $this->getCep();
        $oDaoCep->db86_cadenderrua = $this->getCodigoRua();
        $oDaoCep->incluir(null);
        if ($oDaoCep->erro_status == '0') {

          throw new Exception($oDaoCep->erro_msg);
        } else {
          $this->setCodigoCep($oDaoCep->db86_sequencial);
        }
      }
    }
  }

   /**
   * Método para realizar a inclusão da cadenderruaruas com o tipo
   * caso ele não esteja cadastrado
   */
  private function cadEnderRuaRuas() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não existe transação ativa.');
    }

    $oDaoRuaRuas    = db_utils::getDao('cadenderruaruas');

    $sWhere  = " db88_cadenderrua = ".$this->getCodigoRua();
    $sWhere .= " and db88_ruas    = ".$this->getRuasCadEnderRuaRuas();
    $sQueryRuaRuas = $oDaoRuaRuas->sql_query_file(null, "*", null, $sWhere);

    $rsQueryRuaRuas = $oDaoRuaRuas->sql_record($sQueryRuaRuas);
    if ($rsQueryRuaRuas === false) {

        //$oDaoRuaRuas->db88_cadenderrua = $this->getRuaCadEnderRuaRuas();
        $oDaoRuaRuas->db88_cadenderrua = $this->getCodigoRua();
        $oDaoRuaRuas->db88_ruas        = $this->getRuasCadEnderRuaRuas();
        $oDaoRuaRuas->incluir(null);
        if ($oDaoRuaRuas->erro_status == '0') {

          throw new Exception($oDaoRuaRuas->erro_msg);
        }

    }
  }

  /**
   * Método para realizar a inclusão da rua com o tipo
   * caso ele não esteja cadastrado
   */
  private function cadRuaRuasTipo() {

    if (!db_utils::inTransaction()) {
      throw new Exception('Processamento Cancelado não existe transação ativa.');
    }

    $oDaoRuasTipo    = db_utils::getDao('cadenderruaruastipo');
    $sWhere          = " db85_cadenderrua = ".$this->getCodigoRua();
    $sQueryRuaTipo   = $oDaoRuasTipo->sql_query_file(null, "db85_sequencial", null, $sWhere);
    $rsQueryRuaTipo  = $oDaoRuasTipo->sql_record($sQueryRuaTipo);

    if ( $oDaoRuasTipo->numrows == 0 ) {
     // die($sQueryRuaTipo." 1");
      //var_dump($this->getCadEnderRuaTipo());
      $oDaoRuasTipo->db85_cadenderrua = $this->getCodigoRua();
      $oDaoRuasTipo->db85_ruastipo    = $this->getCadEnderRuaTipo();
      $oDaoRuasTipo->incluir(null);

      if ($oDaoRuasTipo->erro_status == '0') {
        throw new Exception($oDaoRuasTipo->erro_msg);
      }

    } else {

      $iSequencialRua                 = db_utils::fieldsMemory($rsQueryRuaTipo, 0)->db85_sequencial;
      //die($sQueryRuaTipo." 2");
      $oDaoRuasTipo->db85_cadenderrua = $this->getCodigoRua();
      $oDaoRuasTipo->db85_ruastipo    = $this->getCadEnderRuaTipo();
      $oDaoRuasTipo->db85_sequencial  = $iSequencialRua;
      $oDaoRuasTipo->alterar($iSequencialRua);

      if ($oDaoRuasTipo->erro_status == '0') {
        throw new Exception($oDaoRuasTipo->erro_msg);
      }
    }
  }
  /**
   * Método para realizar a inclusão da rua com o bairro
   * caso ele não esteja cadastrado
   */
  private function cadEnderBairroCadEnderRua() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não existe transação ativa.');
    }

    $oDaoBairroRua    = db_utils::getDao('cadenderbairrocadenderrua');

    $sWhere = " db87_cadenderrua = ".$this->getCodigoRua()." and db87_cadenderbairro = ".$this->getCodigoBairro();
    $sQueryBairroRua  = $oDaoBairroRua->sql_query_file(null, "*", null, $sWhere);
    $rsQueryBairroRua = $oDaoBairroRua->sql_record($sQueryBairroRua);

    if ($rsQueryBairroRua === false) {
      $oDaoBairroRua->db87_cadenderrua    = $this->getCodigoRua();
      $oDaoBairroRua->db87_cadenderbairro = $this->getCodigoBairro();
      $oDaoBairroRua->incluir(null);

      if ($oDaoBairroRua->erro_status == '0') {

        throw new Exception($oDaoBairroRua->erro_msg);
      }

      $this->setCodigoCadEnderBairroRua($oDaoBairroRua->db87_sequencial);

    } else {

      $oBairroRua = db_utils::fieldsMemory($rsQueryBairroRua,0);
      $this->setCodigoCadEnderBairroRua($oBairroRua->db87_sequencial);
    }
  }
  /**
   * Método para realizar a inclusão do Numero e Local do endereco
   * caso ele não esteja cadastrado
   */
  private function cadEnderLocal() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não existe transação ativa.');
    }

    $oDaoCadenderrua = db_utils::getDao('cadenderrua');
    $sSqlValidacaoNumero = $oDaoCadenderrua->sql_query_file($this->getCodigoRua());
    $rsValidacaoNumero   = $oDaoCadenderrua->sql_record($sSqlValidacaoNumero);

    if (  $oDaoCadenderrua->numrows > 0 ) {

      $oNumero = db_utils::fieldsMemory($rsValidacaoNumero, 0);

      if ( $oNumero->db74_numfinal > 0 ) {

        if ( $this->getNumeroLocal() < $oNumero->db74_numinicial || $oNumero->db74_numfinal < $this->getNumeroLocal() ) {
          throw new Exception("Número não está no intervalo cadastrado\nNúmero Inicial: {$oNumero->db74_numinicial} Final: {$oNumero->db74_numfinal}");
        }
      }

    }

    $oDaoLocal = db_utils::getDao('cadenderlocal');
    $sWhere  = " db75_cadenderbairrocadenderrua = ".$this->getCodigoCadEnderBairroRua();
    //$sWhere .= " and db75_cadenderbairro = ".$this->getCodigoBairro();
    $sWhere .= " and db75_numero         = '".$this->getNumeroLocal()."'";
    $sQueryLocal  = $oDaoLocal->sql_query_file(null,"*",null,$sWhere);
    $rsQueryLocal = $oDaoLocal->sql_record($sQueryLocal);
    if ($rsQueryLocal === false) {
     // echo "codigo = ".$this->getCodigoCadEnderBairroRua();
      $oDaoLocal->db75_cadenderbairrocadenderrua = $this->getCodigoCadEnderBairroRua();
      $oDaoLocal->db75_numero                    = $this->getNumeroLocal();
      $oDaoLocal->incluir(null);
      if ($oDaoLocal->erro_status == '0') {

        throw new Exception($oDaoLocal->erro_msg);
      } else {
        $this->setCodigoLocal($oDaoLocal->db75_sequencial);
      }
    } else {

      $oLocal = db_utils::fieldsMemory($rsQueryLocal,0);
      $this->setCodigoLocal($oLocal->db75_sequencial);
    }
  }
  /**
   * Método para realizar a inclusão do Complemento do Endereco
   * caso ele não esteja cadastrado
   */
  private function cadEndereco() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não existe transação ativa.');
    }
    $oDaoEndereco = db_utils::getDao('endereco');
    $oDaoEndereco->db76_complemento   = $this->getComplementoEndereco();
    $oDaoEndereco->db76_loteamento    = $this->getLoteamentoEndereco();
    $oDaoEndereco->db76_pontoref      = $this->getPontoReferenciaEndereco();
    $oDaoEndereco->db76_condominio    = $this->getCondominioEndereco();
    $oDaoEndereco->db76_cep           = $this->getCepEndereco();

    if ($this->getCodigoEndereco() == null) {


      $oDaoEndereco->db76_cadenderlocal = $this->getCodigoLocal();

      $oDaoEndereco->incluir(null);

    } else {
      //Se o campo sequencial não for vazio será uma alteração
      $oDaoEndereco->db76_sequencial    = $this->getCodigoEndereco();
      $oDaoEndereco->alterar($this->getCodigoEndereco());
    }

    if ($oDaoEndereco->erro_status == '0') {

      throw new Exception($oDaoEndereco->erro_msg);
    }
    $this->setCodigoEndereco($oDaoEndereco->db76_sequencial);
  }
  /**
   * Método para salvar um enderco
   * caso ele não esteja cadastrado
   */
  public function salvaEndereco() {

    if (!db_utils::inTransaction()) {
       throw new Exception('Processamento Cancelado não existe transação ativa.');
    }

    //$this->cadEnderMunicipio();
    $this->cadEnderBairro();
    $this->cadEnderRua();
    //$this->cadEnderRuaCep();
    $this->cadRuaRuasTipo();
    $this->cadEnderBairroCadEnderRua();
    $this->cadEnderLocal();
    $this->cadEndereco();

  }

  /**
   * Método para realizar a pesquisa por um cep informado
   *
   * @param string $sCep
   * @param string $sNomeBairro
   *
   * @return bool|stdClass[]
   * @throws DBException
   */
  static function findCep($sCep, $sNomeBairro = null) {

    if (!empty($sNomeBairro)) {

    }

    $aRetorno = false;

    if (!empty($sCep)) {

      $oCep = new cl_cadenderruacep();

      $sCampos  = " db70_sequencial as ipais, db70_descricao as spais, db71_sequencial as iestado, ";
      $sCampos .= " db72_sequencial as imunicipio, db72_descricao as smunicipio, db73_sequencial as ibairro, ";
      $sCampos .= " db73_descricao as sbairro, db74_sequencial as irua, db74_descricao as srua, ";
      $sCampos .= " db85_ruastipo as iruatipo, db85_sequencial as iruastipo, db71_descricao as sestado, ";
      $sCampos .= " db71_sigla as sestadosigla ";

      $sWhere      = " db86_cep = $1 ";
      $aParametros = array((string)$sCep);

      if ($sNomeBairro != null) {

        $aParametros[] = $sNomeBairro;
        $sWhere .= " and translate(cadenderbairro.db73_descricao,'áéíóúàèìòùãõâêîôôäëïöüçÁÉÍÓÚÀÈÌÒÙÃÕÂÊÎÔÛÄËÏÖÜÇ','aeiouaeiouaoaeiooaeioucAEIOUAEIOUAOAEIOOAEIOUC') = $2";
      }

      $sQueryCep  = $oCep->sql_query_cep(null, $sCampos, 'db70_sequencial ', $sWhere);
      $rsQueryCep = pg_prepare('busca_cep', $sQueryCep);

      if (!$rsQueryCep) {
        throw new DBException("Houve um erro ao realizar a busca pelo CEP {$sCep}.");
      }
      $rsQueryCep = pg_execute('busca_cep', $aParametros);
      if (!$rsQueryCep) {
        throw  new DBException("Houve um erro ao realizar a busca pelo CEP {$sCep}.");
      }
      $aRetorno = db_utils::getCollectionByRecord($rsQueryCep,false,false,true);
    }

    return $aRetorno;
  }
  /**
   * Método para realizar a pesquisa do pais pelo codigo
   */
  static function findPaisByCodigo($iCodigo) {

    $sRetorno = false;

    if (!empty($iCodigo)) {

      $oDaoPais    = db_utils::getDao('cadenderpais');
      $sQueryPais  = $oDaoPais->sql_query($iCodigo);
      $rsQueryPais = $oDaoPais->sql_record($sQueryPais);

      if ($rsQueryPais !== false) {

        $oPais    = db_utils::fieldsMemory($rsQueryPais, 0);
        $sRetorno = $oPais->db70_descricao;
      }
    }

    return $sRetorno;

  }
  /**
   * Método para realizar a pesquisa do pais pela descricao
   */
  static function findPaisByName($sName, $sAlias=false) {

    $aRetorno = false;

    if (!empty($sName)) {

      $oDaoPais    = db_utils::getDao('cadenderpais');
      $sWhere      = " db70_descricao ilike '%".str_replace(' ', '%', trim($sName))."%'";
      $sCampos     = "db70_sequencial, db70_descricao";
      if($sAlias){
        $sCampos     = "db70_sequencial as cod, db70_descricao as label";
      }
      $sQueryPais  = $oDaoPais->sql_query(null, $sCampos, "db70_descricao", $sWhere);
      $rsQueryPais = $oDaoPais->sql_record($sQueryPais);

      if ($rsQueryPais !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryPais, false, false, true);

      }
    }

    return $aRetorno;

  }

  /**
   * Retorna array com todos os paises
   *
   * @static
   * @access public
   * @return Array
   */
  static function getPaises() {

    $aRetorno = array();

    $oDaoPais = db_utils::getDao('cadenderpais');
    $sSqlPais = $oDaoPais->sql_query_file (null, 'db70_sequencial as codigo, db70_descricao as descricao', 'db70_descricao asc', null);
    $rsPais   = $oDaoPais->sql_record($sSqlPais);

    if ( $oDaoPais->erro_status == "0" ) {
      throw new Exception($oDaoPais->erro_msg);
    }

    $aRetorno = db_utils::getCollectionByRecord($rsPais, false, false, true);

    return $aRetorno;
  }

  /**
   * Método para realizar a pesquisa dos estados pelo codigo do pais informado
   */
  static function findEstadoByCodigoPais($iCodigoPais) {

    $aRetorno = false;

    if (!empty($iCodigoPais)) {

      $oDaoEstado    = db_utils::getDao('cadenderestado');
      $sWhere        = " db71_cadenderpais = ".$iCodigoPais;
      $sQueryEstado  = $oDaoEstado->sql_query(null,
                                              "db71_sequencial as codigo, db71_descricao as descricao",
                                              "db71_descricao",
                                              $sWhere
                                              );
      $rsQueryEstado = $oDaoEstado->sql_record($sQueryEstado);

      if ($rsQueryEstado !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryEstado, false, false, true);

      }
    }

    return $aRetorno;

  }
  /**
   * Método para retornar os tipos de rua
   */
  static function findRuasTipo() {

    $aRetorno = false;

    $oDaoRuasTipo    = db_utils::getDao('ruastipo');
    //$sWhere        = " db71_cadenderpais = ".$iCodigoPais;
    $sQueryRuasTipo  = $oDaoRuasTipo->sql_query(null,
                                              "j88_codigo as codigo, j88_sigla as descricao, j88_sigla as sigla",
                                              "sigla",
                                              null
                                              );
    $rsQueryRuasTipo = $oDaoRuasTipo->sql_record($sQueryRuasTipo);

    if ($rsQueryRuasTipo !== false) {

      $aRetorno  = db_utils::getCollectionByRecord($rsQueryRuasTipo, false, false, true);

    }

    return $aRetorno;

  }

  /**
   * Método para realizar a pesquisa do municipio por codigo informado
   */
  static function findMunicipioByCodigo($iCodigoMunicipio, $iCodigoEstado) {

    $oRetorno = false;

    if (!empty($iCodigoMunicipio) && !empty($iCodigoEstado)) {

      $oDaoMunicipio   = db_utils::getDao('cadendermunicipio');
      $sWhere          = " db72_cadenderestado = ".$iCodigoEstado." and db72_sequencial = ".$iCodigoMunicipio;
      $sQueryMunicipio = $oDaoMunicipio->sql_query(
                                                    null,
                                                    "db72_descricao",
                                                    null,
                                                    $sWhere
                                                  );
      $rsQueryMunicipio = $oDaoMunicipio->sql_record($sQueryMunicipio);

      if ($rsQueryMunicipio !== false) {

        $oMunicipio = db_utils::fieldsMemory($rsQueryMunicipio,0);
        $oRetorno  = $oMunicipio->db72_descricao;

      }
    }

    return $oRetorno;

  }

  /**
   * Busca municipios pelo codigo do estado
   *
   * @param integer $iCodigoEstado
   * @static
   * @access public
   * @return Array
   */
  static function findMunicipioByEstado($iCodigoEstado) {

    $aRetorno = array();

    if (!empty($iCodigoEstado)) {

      $oDaoMunicipio = db_utils::getDao('cadendermunicipio');
      $sCampos       = "db72_sequencial as codigo, db72_descricao as descricao";
      $sOrder        = "db72_descricao";
      $sWhere        = "db72_cadenderestado = ".$iCodigoEstado." ";
      $sWhere       .= "and db72_descricao != ''";
      $sSqlMunicipio = $oDaoMunicipio->sql_query(null, $sCampos, $sOrder, $sWhere);
      $rsMunicipio   = $oDaoMunicipio->sql_record($sSqlMunicipio);

      if ( $oDaoMunicipio->erro_status == "0" ) {
        throw new Exception($oDaoMunicipio->erro_msg);
      }

      $aRetorno = db_utils::getCollectionByRecord($rsMunicipio, false, false, true);
    }

    return $aRetorno;
  }

  /**
   * Método para realizar a pesquisa de um municipio por uma descricao informada
   */
  static function findMunicipioByName($sName, $iCodigoEstado, $sAlias=false) {

    $aRetorno = false;

    if (!empty($sName) && !empty($iCodigoEstado)) {

      $oDaoMunicipio  = db_utils::getDao('cadendermunicipio');
      $sWhere         = " db72_descricao ilike '%".str_replace(' ', '%', trim($sName))."%' ";
      $sWhere        .= " and db72_cadenderestado = ".$iCodigoEstado;
      $sCampos        = "db72_sequencial, db72_descricao";
      if($sAlias){
        $sCampos     = "db72_sequencial as cod, db72_descricao as label";
      }
      $sQueryMunicipio  = $oDaoMunicipio->sql_query(null, $sCampos, "db72_descricao", $sWhere);
      $rsQueryMunicipio = $oDaoMunicipio->sql_record($sQueryMunicipio);

      if ($rsQueryMunicipio !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryMunicipio, false, false, true);

      }
    }

    return $aRetorno;

  }
  /**
   * Método para realizar a pesquisa de um bairro por um codigo informado
   */
  static function findBairroByCodigo($iCodigoBairro, $iCodigoMunicipio) {

    $oRetorno = false;

    if (!empty($iCodigoMunicipio) && !empty($iCodigoBairro)) {

      $oDaoBairro   = db_utils::getDao('cadenderbairro');
      $sWhere          = " db73_cadendermunicipio = ".$iCodigoMunicipio." and db73_sequencial = ".$iCodigoBairro;
      $sQueryBairro = $oDaoBairro->sql_query(
                                             null,
                                             "db73_descricao",
                                             null,
                                             $sWhere
                                            );
      $rsQueryBairro = $oDaoBairro->sql_record($sQueryBairro);

      if ($rsQueryBairro !== false) {

        $oBairro = db_utils::fieldsMemory($rsQueryBairro,0);
        $oRetorno  = $oBairro->db73_descricao;

      }
    }

    return $oRetorno;

  }
  /**
   * Método para realizar a pesquisa do complemento dos dados do bairro pelo codigo do bairro
   */
  static function findComplementoBairro($iCodigoBairro) {

    $aRetorno = false;

    if (!empty($iCodigoBairro)) {

      $oDaoBairro = db_utils::getDao('cadenderbairro');
      $sWhere     = " db73_sequencial = ".$iCodigoBairro;
      $sCampos  = " db73_sequencial as icodigobairro, db73_descricao as sdescrbairro, ";
      $sCampos .= " db72_sequencial as icodigomunicipio, db72_descricao as sdescrmunicipio ";

      $sQueryBairro = $oDaoBairro->sql_query(
                                             null,
                                             $sCampos,
                                             null,
                                             $sWhere
                                            );
      $rsQueryBairro = $oDaoBairro->sql_record($sQueryBairro);

      if ($rsQueryBairro !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryBairro, false, false, true);
      }
    }

    return $aRetorno;

  }
  /**
   * Método para realizar a pesquisa de um bairro pela descricao informada
   */
  static function findBairroByName($sName, $iCodigoEstado, $iCodigoMunicipio, $sAlias=false) {

    $aRetorno = false;

    if (!empty($sName)) {

      $oDaoBairro  = db_utils::getDao('cadenderbairro');
      $sWhere         = " db73_descricao ilike '%".str_replace(' ', '%', trim($sName))."%' ";
      $sWhere        .= " and db72_cadenderestado = ".$iCodigoEstado;

      if (trim($iCodigoMunicipio) != '') {

        $sWhere        .= " and db73_cadendermunicipio = ".$iCodigoMunicipio;
      }

      $sCampos        = "db73_sequencial, db73_descricao";
      if($sAlias){
        $sCampos     = "db73_sequencial as cod, db73_descricao||' - '||db72_descricao as label ";
      }
      $sQueryBairro  = $oDaoBairro->sql_query(null, $sCampos, "db73_descricao, db72_descricao", $sWhere);
      $rsQueryBairro = $oDaoBairro->sql_record($sQueryBairro);

      if ($rsQueryBairro !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryBairro, false, false, true);

      }
    }

    return $aRetorno;

  }
  /**
   * Método para realizar a pesquisa de uma rua pelo codigo informado
   */
  static function findRuaByCodigo($iCodigoRua, $iCodigoMunicipio) {

    $aRetorno = false;

    if (!empty($iCodigoMunicipio) && !empty($iCodigoRua)) {

      $oDaoRua   = db_utils::getDao('cadenderrua');
      $sWhere    = " db74_cadendermunicipio = ".$iCodigoMunicipio." and db74_sequencial = ".$iCodigoRua;
      $sWhere   .= " and db85_cadenderrua = ".$iCodigoRua;
      $sQueryRua = $oDaoRua->sql_query_rua_codigo(
                                             null,
                                             " db74_descricao, db85_ruastipo, db73_descricao, db73_sequencial, db85_sequencial ",
                                             null,
                                             $sWhere
                                            );
      $rsQueryRua = $oDaoRua->sql_record($sQueryRua);

      if ($rsQueryRua !== false) {

        //$oRua = db_utils::fieldsMemory($rsQueryRua,0);
        $aRetorno  = db_utils::getCollectionByRecord($rsQueryRua, false, false, true);

      }
    }

    return $aRetorno;

  }
  /**
   * Método para realizar a pesquisa de uma rua pela descricao informada
   */
  static function findRuaByName($sName, $iCodigoEstado, $iCodigoMunicipio, $iCodigoBairro, $sAlias=false) {

    $aRetorno = false;

    if (!empty($sName) && !empty($iCodigoEstado)) {

      $oDaoRua  = db_utils::getDao('cadenderrua');
      $sWhere         = " to_ascii(db74_descricao) ilike to_ascii('%".str_replace(' ', '%', trim($sName))."%') ";
      $sWhere        .= " and db72_cadenderestado = ".$iCodigoEstado;
      if (trim($iCodigoMunicipio) != '') {
        $sWhere        .= " and db73_cadendermunicipio = ".$iCodigoMunicipio;
      }
      if (trim($iCodigoBairro) != '') {
        $sWhere        .= " and db73_sequencial = ".$iCodigoBairro;
      }
      $sCampos        = "db74_sequencial, db74_descricao";
      $sOrder         = null;
      if($sAlias){
        //$sCampos      = "distinct db87_sequencial as cod, db76_cep, db74_descricao||' - '||db72_descricao||' - '||db73_descricao||' - '|| db76_cep as label";
        $sCampos      = "distinct db87_sequencial as cod, db76_cep, db74_descricao as label";
        $sOrder       = " label,cod limit 50 ";
      }
      $sQueryRua  = $oDaoRua->sql_query_endereco_full(null, $sCampos, $sOrder, $sWhere);
      //echo $sQueryRua;
      $rsQueryRua = $oDaoRua->sql_record($sQueryRua);

      if ($rsQueryRua !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryRua, false, false, true);

      }
    }

    return $aRetorno;

  }
  /**
   * Método para retornar o complemento de um endereco pelo codigo da ligagacao da rua com bairro informado
   */
   static function findComplementoRua($iCodigoRua, $iCodigoMunicipio, $iCodigoBairro) {

    $aRetorno = false;

    if (!empty($iCodigoRua)) {

      $oDaoRua  = db_utils::getDao('cadenderrua');
      $sWhere   = " db87_sequencial = ".$iCodigoRua;

      if (trim($iCodigoMunicipio) != "") {

        $sWhere   .= " and db72_sequencial = ".$iCodigoMunicipio;
      }

      if (trim($iCodigoBairro) != "") {

        $sWhere   .= " and db73_sequencial = ".$iCodigoBairro;
      }

      $sCampos  = " distinct db74_descricao as sdescrrua, db74_sequencial as icodigorua, db73_sequencial as icodigobairro,        ";
      $sCampos .= " db73_descricao as sdescrbairro, db72_sequencial as icodigomunicipio,  ";
      $sCampos .= " db72_descricao as sdescrmunicipio , db85_ruastipo as icodigoruatipo, db85_sequencial as icodigoruastipo, ";
      $sCampos .= " db86_cep as scep , db86_sequencial as icep, ";
      $sCampos .= " db76_cep ";
      $sQueryRua  = $oDaoRua->sql_query_left_full(null, $sCampos, "db74_descricao", $sWhere);
      $rsQueryRua = $oDaoRua->sql_record($sQueryRua);

      if ($rsQueryRua !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryRua, false, false, true);

      }

    }

    $oDaoCep  = db_utils::getDao('cadenderruacep');
    $sWhere   = " db86_cadenderrua = ".$aRetorno[0]->icodigorua;
    $sCampos  = " db86_sequencial as icep, db86_cep as scep ";
    $sQueryCep  = $oDaoCep->sql_query_file(null,$sCampos,null,$sWhere);

    $rsQueryCep = $oDaoCep->sql_record($sQueryCep);
    //echo $sQueryCep;
    if ($rsQueryCep !== false) {
        if (pg_num_rows($rsQueryCep) == 1) {

          $oCep = db_utils::fieldsmemory($rsQueryCep,0);
          $aRetorno[0]->icep = $oCep->icep;
          $aRetorno[0]->scep = $oCep->scep;
        } else {

          $aRetorno[0]->icep = '';
          $aRetorno[0]->scep = '';
        }
    }



    return $aRetorno;

  }

  /**
   * Método para realizar a pesquisa dos endereços para o numero informado
   */
  static function findNumeroByNumero($iCodigoNumero, $iCodigoBairro, $iCodigoRua ) {

    $aRetorno = false;

    if (!empty($iCodigoNumero) && trim($iCodigoBairro) != '' && !empty($iCodigoRua)) {

      $oDaoLocal  = db_utils::getDao('cadenderlocal');
      $sWhere     = "     db87_cadenderrua    = ".$iCodigoRua;
      $sWhere    .= " and db87_cadenderbairro = ".$iCodigoBairro;
      $sWhere    .= " and db75_numero         = '".$iCodigoNumero."'";
      $sCampos    = "db75_sequencial, db75_numero, db76_complemento, db85_sequencial, db85_ruastipo,";
      $sCampos   .= "db76_sequencial, db76_caixapostal, db76_loteamento, db76_condominio, db76_cep, db76_pontoref ";
      $sOrder     = "db76_condominio, db76_complemento, db76_loteamento ";
      $sQueryLocal  = $oDaoLocal->sql_query_local(null, $sCampos, $sOrder, $sWhere);
      $rsQueryLocal = $oDaoLocal->sql_record($sQueryLocal);

      if ($rsQueryLocal !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryLocal, false, false, true);

      }
    }

    return $aRetorno;

  }
  /**
   * Método para realizar a pesquisa de um condominio pela descricao informada
   */
  static function findCondominioByName($sName, $iCodigoNumero, $iCodigoBairro, $iCodigoRua, $sAlias=false) {

    $aRetorno = false;

    if (!empty($iCodigoNumero) && !empty($iCodigoBairro) && !empty($iCodigoRua)) {

      $oDaoLocal  = db_utils::getDao('cadenderlocal');
      $sWhere     = " db76_condominio ilike '%".str_replace(' ', '%', trim($sName))."%' ";
      $sWhere    .= " and db75_cadenderrua    = ".$iCodigoRua;
      $sWhere    .= " and db75_cadenderbairro = ".$iCodigoBairro;
      $sWhere    .= " and db75_numero         = ".$iCodigoNumero;
      //$sCampos    = "db75_sequencial, db75_numero, db76_complemento, db76_caixapostal, db76_loteamento, db76_condominio ";
      $sCampos     = "distinct db75_numero, db76_condominio ";
      if($sAlias){
        $sCampos     = "distinct db75_numero as cod, db76_condominio as label";
      }
      $sQueryLocal  = $oDaoLocal->sql_query_local(null, $sCampos, "db76_condominio", $sWhere);
      $rsQueryLocal = $oDaoLocal->sql_record($sQueryLocal);

      if ($rsQueryLocal !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryLocal, false, false, true);

      }
    }

    return $aRetorno;
  }

  /**
   * Método para realizar a pesquisa de um loteamento pela descricao informada
   */
  static function findLoteamentoByName($sName, $iCodigoNumero, $iCodigoBairro, $iCodigoRua, $sAlias=false) {

    $aRetorno = false;

    if (!empty($iCodigoNumero) && !empty($iCodigoBairro) && !empty($iCodigoRua)) {

      $oDaoLocal  = db_utils::getDao('cadenderlocal');
      $sWhere     = " db76_loteamento ilike '%".str_replace(' ', '%', trim($sName))."%' ";
      $sWhere    .= " and db87_cadenderrua    = ".$iCodigoRua;
      $sWhere    .= " and db87_cadenderbairro = ".$iCodigoBairro;
      $sWhere    .= " and db75_numero         = '".$iCodigoNumero."'";
      $sCampos    = "distinct db75_numero, db76_loteamento ";

      if ( $sAlias ) {
        $sCampos = "distinct db75_numero as cod, db76_loteamento as label";
      }

      $sQueryLocal  = $oDaoLocal->sql_query_local(null, $sCampos, "db76_loteamento", $sWhere);
      $rsQueryLocal = $oDaoLocal->sql_record($sQueryLocal);

      if ($rsQueryLocal !== false) {
        $aRetorno  = db_utils::getCollectionByRecord($rsQueryLocal, false, false, true);
      }
    }

    return $aRetorno;
  }

  /**
   * Método para retornar o pais e estado da instituiçao pelo codigo da instituicao
   */
  static function findPaisDbConfig($iCodigoInstit) {

    $aRetorno = false;

    if (!empty($iCodigoInstit)) {

      $oDaoConfig  = db_utils::getDao('db_config');
      $sQueryConfig  = " select db70_sequencial, ";
      $sQueryConfig .= "        db70_descricao,  ";
      $sQueryConfig .= "        db71_sequencial  ";
      $sQueryConfig .= "    from cadenderestado  ";
      $sQueryConfig .= "         inner join cadenderpais on cadenderpais.db70_sequencial = cadenderestado.db71_cadenderpais ";
      $sQueryConfig .= "   where db71_sigla in (select uf from db_config where codigo = ".$iCodigoInstit.") ";
      $rsQueryConfig = $oDaoConfig->sql_record($sQueryConfig);

      if ($rsQueryConfig !== false) {
        $aRetorno  = db_utils::getCollectionByRecord($rsQueryConfig, false, false, true);
      }
    }

    return $aRetorno;
  }

  static function findCepDbConfig($iCodigoInstit) {

    $aRetorno = false;

    if (!empty($iCodigoInstit)) {

      $oDaoConfig  = db_utils::getDao('db_config');
      $sQueryConfig  = " select cep from db_config ";
      $sQueryConfig .= "   where codigo = ".$iCodigoInstit;
      $rsQueryConfig = $oDaoConfig->sql_record($sQueryConfig);

      if ($rsQueryConfig !== false) {
        $aRetorno  = db_utils::getCollectionByRecord($rsQueryConfig, false, false, true);
      }
    }

    return $aRetorno;
  }

   /**
   * Método para retornar o pais e estado da instituiçao pelo codigo da instituicao
   */
  static function findMunicipioDbConfig($iCodigoInstit,$iCodigoEstado) {

    $aRetorno = false;

    if (!empty($iCodigoInstit)) {

      $oDaoConfig  = db_utils::getDao('db_config');
      $sQueryConfig  = " select  db72_sequencial, ";
      $sQueryConfig .= "        db72_descricao  ";
      $sQueryConfig .= "    from cadendermunicipio   ";
      $sQueryConfig .= "         where trim(db72_descricao) in (select trim(munic) from db_config where codigo = ".$iCodigoInstit.") ";
      $sQueryConfig .= "     and db72_cadenderestado = ".$iCodigoEstado;
      $rsQueryConfig = $oDaoConfig->sql_record($sQueryConfig);

      if ($rsQueryConfig !== false) {

        $aRetorno  = db_utils::getCollectionByRecord($rsQueryConfig, false, false, true);

      }
    }

    return $aRetorno;
  }
  /**
   * Método para retornar o pais, estado, municipio do parametros do endereço
   */
  static function findParametrosEndereco($lEncode=true) {

    $aRetorno = false;

    $oDaoParametros   = db_utils::getDao('cadenderparam');
    $sCampos  = "p.db70_sequencial, p.db70_descricao, a.db71_sequencial, a.db71_descricao, db72_sequencial ";
    $sCampos .= ",  db72_cadenderestado , db72_descricao ";

    $sQueryParametros = $oDaoParametros->sql_query_correto(null,$sCampos,null,null);
    $rsQueryParametros = $oDaoParametros->sql_record($sQueryParametros);

    if ($rsQueryParametros !== false) {

      $aRetorno  = db_utils::getCollectionByRecord($rsQueryParametros, false, false, $lEncode);

    }

    return $aRetorno;
  }
  /**
   * Método para retornar o endereco, pela tabela cgmendereco pesquisando pôr numcgm
   * */
  static function findCgmEnderecoByCgm($iNumCgm) {

    $aRetorno = false;

    $oDaoCgmEndereco = new cl_cadenderlocal;
    $sCampos  = " z07_sequencial as icgmendereco, db74_descricao as sRua, db75_numero as sNumero, db73_descricao as sbairro ";
    $sCampos .= " ,db76_sequencial as iendereco , z07_tipo as stipo, db71_sigla as ssigla, db72_descricao as smunicipio ";
    $sCampos .= " ,db76_complemento as scomplemento";

    $sWhere   = " z07_numcgm = ".$iNumCgm;

    $sQueryCgmEndereco  = $oDaoCgmEndereco->sql_query_cgmendereco(null,$sCampos,'z07_tipo',$sWhere);
    $rsQueryCgmEndereco = $oDaoCgmEndereco->sql_record($sQueryCgmEndereco);
    if( $rsQueryCgmEndereco !== false) {
      $aRetorno = db_utils::getCollectionByRecord($rsQueryCgmEndereco, false, false, true);
    }

    return $aRetorno;
  }

  /**
   * Método para retornar o endereco, pela tabela endereco pesquisando pôr codigo do endereco
   * */
  static function findEnderecoByCodigo($iCodigoEndereco, $lEncode=true) {

    $aRetorno = false;

    if (trim($iCodigoEndereco) != "") {

      $oDaoEndereco = db_utils::getDao('cadenderlocal');
      $sCampos  = " distinct db74_descricao as sRua, db75_numero as sNumero, db73_descricao as sbairro, db76_complemento as scomplemento ";
      $sCampos .= " ,db76_sequencial as iendereco , 'P' as stipo, db71_sigla as ssigla, a.db72_descricao as smunicipio, db76_cep as scep ";

      $sWhere   = " db76_sequencial = ".$iCodigoEndereco;

      $sQueryEndereco  = $oDaoEndereco->sql_query_completo(null,$sCampos,null,$sWhere);

      $rsQueryEndereco = $oDaoEndereco->sql_record($sQueryEndereco);
      if( $rsQueryEndereco !== false) {
        $aRetorno = db_utils::getCollectionByRecord($rsQueryEndereco, false, false, $lEncode);
      }
    }
    return $aRetorno;
  }
  /**
   *  Método para retornar a rua e o bairro do cadastro do cidadao
   *
   */
  static function buscaEnderecoCidadao($iOv02_sequencial, $iOv02_seq, $lEncode=true) {

    $aRetorno = false;

    if (trim($iOv02_sequencial) != "" && trim($iOv02_seq) != "") {

      $oDaoCidadao = db_utils::getDao('cidadao');

      $sCampos  = " db71_sequencial, db72_sequencial, db73_sequencial, db74_sequencial, db87_sequencial, ";
      $sCampos .= " ov02_endereco , ov02_numero, ov02_compl, ov02_munic, ov02_bairro, ov02_uf, ov02_cep";
      $sWhere  = " ov02_sequencial = ".$iOv02_sequencial;
      $sWhere .= " and ov02_seq = ".$iOv02_seq ;

      $sQueryEnderecoCidadao  = $oDaoCidadao->sql_query_enderecoCidadao(null, null, $sCampos, null, $sWhere);
      $rsQueryEnderecoCidadao = $oDaoCidadao->sql_record($sQueryEnderecoCidadao);
      if( $rsQueryEnderecoCidadao !== false) {
        $aRetorno = db_utils::getCollectionByRecord($rsQueryEnderecoCidadao, false, false, $lEncode);
      }
    }
    return $aRetorno;
  }

   /**
    *  Método para retornar a rua e o bairro do cadastro imobiliário
    *
    */
  static function buscaBairroRuaMunicipio($iCodigoBairroMunicipio, $iCodigoRuaMunicipio, $iCodigoMunicipio = null, $lEncode=true) {

    $aRetorno = false;
    if (trim($iCodigoBairroMunicipio) != "") {

      $oDaoRuaRuas = new cl_cadenderruaruas;
      $aCampos = array(
        'db74_descricao         as sRua',
        'db73_descricao         as sbairro',
        'db74_sequencial        as irua',
        'db73_sequencial        as ibairro',
        'db85_sequencial        as iruastipo',
        'db85_ruastipo          as iruatipo',
        'db74_cadendermunicipio as imunicipio',
      );

      $aWhere = array(
        "j14_codigo = {$iCodigoRuaMunicipio}",
        "trim(db73_descricao) in (select upper(trim(j13_descr)) from bairro where j13_codi = {$iCodigoBairroMunicipio})",
      );

      if ($iCodigoMunicipio) {
        $aWhere[] = "db74_cadendermunicipio = {$iCodigoMunicipio}";
      }

      $sQueryRuaBairro  = $oDaoRuaRuas->sql_queryBairroRuaMunicipio(null, implode(', ', $aCampos), null, implode(' and ', $aWhere));
      $rsQueryRuaBairro = $oDaoRuaRuas->sql_record($sQueryRuaBairro);
      if($rsQueryRuaBairro !== false) {
        $aRetorno = db_utils::getCollectionByRecord($rsQueryRuaBairro, false, false, $lEncode);
      }
    }
    return $aRetorno;
  }

  static function findBairroRuaMunicipio ($iCodigoBairroMunicipio, $iCodigoRuaMunicipio, $lEncode=true) {

    $oRetorno = new stdClass();
    if (trim($iCodigoBairroMunicipio) != "" && trim($iCodigoRuaMunicipio) != "") {

      //Obtem os dados da Rua do municipio
      $oDaoRuas = db_utils::getDao('ruas');
      $sCampos  = "j14_codigo, j14_nome, j14_tipo ";
      $sWhere   = "j14_codigo = ".$iCodigoRuaMunicipio;

      $sQueryRuas  = $oDaoRuas->sql_query(null, $sCampos, null, $sWhere);
      $rsQueryRuas = $oDaoRuas->sql_record($sQueryRuas);
      if ($rsQueryRuas !== false) {

        $oRuas = db_utils::fieldsmemory($rsQueryRuas,0);
        $oRetorno->descrEndereco = $oRuas->j14_nome;
        $oRetorno->ruaTipo       = $oRuas->j14_tipo;
        $oRetorno->codigoRuas    = $oRuas->j14_codigo;
      }

      //Obtem os dados do Bairro do municipio
      $oDaoBairro = db_utils::getDao('bairro');
      $sCampos  = "j13_codi, j13_descr ";
      $sWhere   = "j13_codi = ".$iCodigoBairroMunicipio;

      $sQueryBairro  = $oDaoBairro->sql_query(null, $sCampos, null, $sWhere);
      $rsQueryBairro = $oDaoBairro->sql_record($sQueryBairro);
      if ($rsQueryBairro !== false) {

        $oBairro = db_utils::fieldsmemory($rsQueryBairro,0);
        $oRetorno->descrBairro = $oBairro->j13_descr;
      }

      $aParam = self::findParametrosEndereco(false);
      //Pesquisar se existe o bairro vinculado ao municipio
      $oDaoBairroMunicipio = db_utils::getDao('cadenderbairro');
      $sCampos = "db73_sequencial ";
      $sWhere  = " db73_descricao = '".trim($oRetorno->descrBairro)."' ";
      $sWhere .= " and db73_cadendermunicipio = ".$aParam[0]->db72_sequencial;

      $oRetorno->codigoMunicipio = $aParam[0]->db72_sequencial;

      $sQueryBairroMunicipio  = $oDaoBairroMunicipio->sql_query_file(null, $sCampos, null, $sWhere);
      $rsQueryBairroMunicipio = $oDaoBairroMunicipio->sql_record($sQueryBairroMunicipio);
      $oRetorno->codigoBairro = "";
      if ($rsQueryBairroMunicipio !== false) {

        $oRetorno->codigoBairro = db_utils::fieldsmemory($rsQueryBairroMunicipio,0)->db73_sequencial;
      }

      $oDaoRuaMunicipio = db_utils::getDao('cadenderrua');
      $sCampos = " db74_sequencial ";
      $sWhere  = " db74_descricao = '".trim($oRetorno->descrEndereco)."' ";
      $sWhere .= " and db74_cadendermunicipio = ".$aParam[0]->db72_sequencial;
      $sQueryRuaMunicipio  = $oDaoRuaMunicipio->sql_query_file(null, $sCampos, null, $sWhere);
      $rsQueryRuaMunicipio = $oDaoRuaMunicipio->sql_record($sQueryRuaMunicipio);
      $oRetorno->codigoEndereco = "";
      if ($rsQueryRuaMunicipio !== false) {

        $oRetorno->codigoEndereco = db_utils::fieldsmemory($rsQueryRuaMunicipio,0)->db74_sequencial;
      }

    }
    return $oRetorno;
  }
   public function cadEnderBairroRuaMunicipio() {
     $this->cadEnderBairro();

     $this->cadEnderRua();

     $this->cadRuaRuasTipo();

     $this->cadEnderBairroCadEnderRua();

     $this->cadEnderRuaRuas();

   }
}