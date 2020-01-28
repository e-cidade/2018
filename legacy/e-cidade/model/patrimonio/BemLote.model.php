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
 * Classe responsável pela manutenção do lote e seus bens
 * @author Matheus Felini
 */
class BemLote {

  /**
   * Código do Lote
   * @var integer
   */
  protected $iCodigoLote;

  /**
   * Descrição do Lote
   * @var string
   */
  protected $sDescricao;

  /**
   * Coleção de objetos do tipo Bem
   * @var Bem
   */
  protected $aBens = array();

  /**
   * Hora de inclusão do lote
   * @var string
   */
  protected $sHora;

  /**
   * Código do Usuário que incluiu o lote
   * @var integer
   */
  protected $iUsuario;

  /**
   * Data de inclusão do lote
   * @var date
   */
  protected $dtData;

  /**
   * Método Construtor
   * Seta valores nas propriedades da entidade caso seja passado o código de cadastro do lote por parâmetro
   * @param integer $iCodigoLote
   */
  public function __construct($iCodigoLote = null) {

    if ($iCodigoLote != null) {

      $oDaoBensCadLote = db_utils::getDao("benscadlote");
      $sSqlBensCadLote = $oDaoBensCadLote->sql_query_file($iCodigoLote);
      $rsBensCadLote   = $oDaoBensCadLote->sql_record($sSqlBensCadLote);

      if ($oDaoBensCadLote->numrows == 1) {

        /*
         *  Seta valores nas propriedades da entidade
         */
        $oDadoLote = db_utils::fieldsMemory($rsBensCadLote, 0);
        $this->setCodigoLote($iCodigoLote);
        $this->setDescricao($oDadoLote->t42_descr);
        $this->setData($oDadoLote->t42_data);
        $this->setHora($oDadoLote->t42_hora);
        $this->setUsuario($oDadoLote->t42_usuario);
        unset($oDadoLote);
      }

      /*
       * Carrega os bens de um lote
       */
      $this->carregarBens();
    }
  }

  /**
   * Método responsável por salvar os dados do lote. Independente se for alteração ou inclusão de novo lote
   * @throws Exception
   * @return boolean
   */
  public function salvar() {

    /*
     * Verifica se existe transação ativa antes de iniciar o processamento
     */
    if (!db_utils::inTransaction()) {
      throw new Exception("ERRO [1]: Não existe transação ativa com o banco de dados.");
    }

    /*
     * Seta as propriedades da entidade benscadlote
     */
    $oDaoBensCadLote              = db_utils::getDao("benscadlote");
    $oDaoBensCadLote->t42_codigo  = $this->getCodigoLote();
    $oDaoBensCadLote->t42_descr   = $this->getDescricao();
    $oDaoBensCadLote->t42_data    = $this->getData();
    $oDaoBensCadLote->t42_hora    = $this->getHora();
    $oDaoBensCadLote->t42_usuario = $this->getUsuario();


    /*
     * Verifica se deve incluir ou alterar um registro já existente
     */
    if (empty($oDaoBensCadLote->t42_codigo)) {

      $oDaoBensCadLote->incluir(null);
      $this->setCodigoLote($oDaoBensCadLote->t42_codigo);
    } else {
      $oDaoBensCadLote->alterar($this->getCodigoLote());
    }

    /*
     * Lança uma exception caso ocorra algum erro no processamento
     */
    if ($oDaoBensCadLote->erro_status == "0") {
      throw new Exception("ERRO [2]: Não foi possível salvar os dados do lote.\n{$oDaoBensCadLote->erro_msg}");
    }

    /*
     * Valida se o lote teve bens alterados individualmente
     */
    $this->validarBensDoLote();

    /*
     * Percorre os bens da propriedade aBens salvando os mesmo utilizando o método
     * 'salvar' do model Bem
     */
    $oDaoBensLote          = new cl_benslote();
    foreach ($this->getBens() as $oBem) {

    	$oBem->salvar();

    	/**
    	 * verifica se o bem já está no lote.
    	 */
    	$sSqlVerificaBemNoLote = $oDaoBensLote->sql_query_file(null,"*", null, "t43_bem = {$oBem->getCodigoBem()}");
    	$rsVerificaBemNoLote   = $oDaoBensLote->sql_record($sSqlVerificaBemNoLote);
    	if ($oDaoBensLote->numrows == 0) {

         $oDaoBensLote->t43_bem     = $oBem->getCodigoBem();
         $oDaoBensLote->t43_codlote = $this->getCodigoLote();
         $oDaoBensLote->incluir(null);
         if ($oDaoBensLote->erro_status == 0) {
         	 throw new Exception("ERRO [3]: Não foi possível salvar os dados do lote.\n{$oDaoBensLote->erro_msg}");
         }
    	}
    }

    return true;
  }

  /**
   * Valida se o lote teve algum bem alterado individualmente
   * @throws Exception
   * @return boolean
   */
  public function validarBensDoLote() {

    /*
     * Executa o SQL para verificar se algum bem do lote foi alterado individualmente
     */
    $oDaoBensLote    = db_utils::getDao("benslote");
    $sCampoBensLote  = "distinct t42_codigo,  ";
    $sCampoBensLote .= "         t42_descr,   ";
    $sCampoBensLote .= "         t52_codcla,  ";
    $sCampoBensLote .= "         t64_class,   ";
    $sCampoBensLote .= "         t52_numcgm,  ";
    $sCampoBensLote .= "         z01_nome,    ";
    $sCampoBensLote .= "         t52_valaqu,  ";
    $sCampoBensLote .= "         t52_dtaqu,   ";
    $sCampoBensLote .= "         t52_descr,   ";
    $sCampoBensLote .= "         t52_obs,     ";
    $sCampoBensLote .= "         t52_depart,  ";
    $sCampoBensLote .= "         descrdepto   ";
    $sWhereBensLote  = "t43_codlote = {$this->getCodigoLote()}";

    $sSqlBensLoteAltIndividual = $oDaoBensLote->sql_query(null, $sCampoBensLote, null, $sWhereBensLote);
    $rsBensLoteAltIndividual   = $oDaoBensLote->sql_record($sSqlBensLoteAltIndividual);

    /*
     * Lança exception caso o lote tenha algum bem alterado individualmente
     */
    if ($oDaoBensLote->numrows > 1) {

      $sMsgException  = "ERRO [3]: Alguns bens do lote {$this->getCodigoLote()} foram alterados individualmente. ";
      $sMsgException .= "Procedimento abortado.";
      throw new Exception($sMsgException);
    }
    return true;
  }

  /**
   * Método que adiciona na propriedade aBens os bens de um lote
   * @throws Exception
   * @return boolean
   */
  private function carregarBens() {

    $oDaoBensLote    = db_utils::getDao("benslote");
    $sCampoBensLote  = "t52_bem";
    $sWhereBensLote  = "t43_codlote = {$this->getCodigoLote()}";

    $sSqlBensLoteAltIndividual = $oDaoBensLote->sql_query(null, $sCampoBensLote, null, $sWhereBensLote);
    $rsBensLoteAltIndividual   = $oDaoBensLote->sql_record($sSqlBensLoteAltIndividual);

    if ($oDaoBensLote->numrows == 0) {
      throw new Exception("ERRO [4]: Não foi possível carregar os bens do lote.");
    }

    /*
     * Percorre o result set adicionando bem a propriedade aBens
     */
    for ($iRow = 0; $iRow < $oDaoBensLote->numrows; $iRow++) {

      $oDadoBemLote = db_utils::fieldsMemory($rsBensLoteAltIndividual, $iRow);
      $this->adicionarBem(new Bem($oDadoBemLote->t52_bem));
    }
    return true;
  }


  /**
   * Método criado para adicionar um objeto na propriedade de aBens
   * @param Bem $oBem
   */
  public function adicionarBem(Bem $oBem) {
    $this->aBens[] = $oBem;
  }

  /**
   * Retorna o código do lote
   * @return integer
   */
  public function getCodigoLote() {
    return $this->iCodigoLote;
  }

  /**
   * Retorna descrião do lote
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retornar Collection de Bens
   * @return Bem[]
   */
  public function getBens() {
    return $this->aBens;
  }

  /**
   * Retorna Hora de Inclusão
   * @return string
   */
  public function getHora() {
    return $this->sHora;
  }

  /**
   * Retorna o código do usuário
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }

  /**
   * Retorna a data de inclusão
   * @return date
   */
  public function getData() {
    return $this->dtData;
  }

  /**
   * Seta valor para propriedade iCodigoLote
   * @param integer $iCodigoLote
   */
  public function setCodigoLote($iCodigoLote) {
    $this->iCodigoLote = $iCodigoLote;
  }

  /**
   * Seta valor para propriedade sDescricao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Seta valor para propriedade sHora
   * @param string $sHora
   */
  public function setHora($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * Seta valor para propriedade iUsuario
   * @param integer $iUsuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }

  /**
   * Seta valor para ropriedade dtData
   * @param string $dtData
   */
  public function setData($dtData) {
    $this->dtData = $dtData;
  }
}
?>