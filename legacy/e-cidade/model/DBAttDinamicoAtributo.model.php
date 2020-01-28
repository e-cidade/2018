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

class DBAttDinamicoAtributo {

  const TIPO_TEXT = 1;
  const TIPO_INTEGER = 2;
  const TIPO_DATE = 3;
  const TIPO_NUMERIC = 4;
  const TIPO_BOOLEAN = 5;

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var integer
   */
  private $iGrupoAtributo;

  /**
   * @var string
   */
  private $sNome;

  /**
   * @var string
   */
  private $sDescricao;

  /**
   * @var stdclass
   */
  private $oCampoReferencia;

  /**
   * @var string
   */
  private $sValorDefault;

  /**
   * @var integer
   */
  private $iTipo;

  /**
   * Campo Obrigatorio
   * @var bool
   */
  private $lObrigatorio = false;

  /**
   * @var DBAttDinamicoAtributoOpcao
   */
  private $aOpcoes;

  /**
   * Define se o atributo encontra-se ativo ou não
   * @var bool
   */
  private $lAtivo = true;

  /**
   * DBAttDinamicoAtributo constructor.
   *
   * @param null $iCodigo
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoCadAttDinamicoAtributo = new cl_db_cadattdinamicoatributos();

      $sSqlAtributos    = $oDaoCadAttDinamicoAtributo->sql_query_file($iCodigo);
      $rsDadosAtributos = $oDaoCadAttDinamicoAtributo->sql_record($sSqlAtributos);

      if ($oDaoCadAttDinamicoAtributo->numrows > 0 ) {

        $oAtributo = db_utils::fieldsMemory($rsDadosAtributos,0);

        $this->setCodigo($oAtributo->db109_sequencial);
        $this->setGrupoAtributo($oAtributo->db109_db_cadattdinamico);
        $this->setNome($oAtributo->db109_nome);
        $this->setDescricao($oAtributo->db109_descricao);
        $this->setTipo($oAtributo->db109_tipo);
        $this->setValorDefault($oAtributo->db109_valordefault);
        $this->setCampo($oAtributo->db109_codcam);
        $this->setObrigatorio($oAtributo->db109_obrigatorio == 't');
        $this->setAtivo($oAtributo->db109_ativo == 't');
      }
    }
  }

  /**
   * @param $lAtivo
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }

  /**
   * @return bool
   */
  public function ativo() {
    return $this->lAtivo;
  }

  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  public function getNome() {
    return $this->sNome;
  }

  public function getCodigo() {
    return $this->iCodigo;
  }

  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  public function getDescricao() {
    return $this->sDescricao;
  }

  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  public function getGrupoAtributo() {
    return $this->iGrupoAtributo;
  }

  public function setGrupoAtributo($iGrupoAtributo) {
    $this->iGrupoAtributo = $iGrupoAtributo;
  }

  public function getCampo() {

    if ($this->oCampoReferencia == null) {
      return false;
    } else {
      return $this->oCampoReferencia;
    }
  }

  /**
   * @return bool
   */
  public function isObrigatorio() {
    return $this->lObrigatorio;
  }

  /**
   * @param bool $lObrigatorio
   */
  public function setObrigatorio($lObrigatorio) {
    $this->lObrigatorio = $lObrigatorio;
  }



  public function setCampo($iCodCampo) {

    if ( trim($iCodCampo) != '') {

      $oDaoDBSysArqCamp = new cl_db_sysarqcamp();
      $sCampos          = " db_syscampo.codcam,    ";
      $sCampos         .= " db_syscampo.nomecam,   ";
      $sCampos         .= " db_syscampo.descricao, ";
      $sCampos         .= " db_sysarquivo.nomearq  ";
      $sWhereCampos     = " db_sysarqcamp.codcam = {$iCodCampo} ";
      $sSqlDadosCampo   = $oDaoDBSysArqCamp->sql_query(null,null,null,$sCampos,null,$sWhereCampos);
      $rsDadosCampo     = $oDaoDBSysArqCamp->sql_record($sSqlDadosCampo);

      if ( $oDaoDBSysArqCamp->numrows > 0 ) {

        $oDadosCampo = db_utils::fieldsMemory($rsDadosCampo,0);

        $oCampoReferencia = new stdClass();
        $oCampoReferencia->iCodigo    = $oDadosCampo->codcam;
        $oCampoReferencia->sNome      = $oDadosCampo->nomecam;
        $oCampoReferencia->sDescricao = $oDadosCampo->descricao;
        $oCampoReferencia->sTabela    = $oDadosCampo->nomearq;

        $this->oCampoReferencia = $oCampoReferencia;

      } else {
        throw new Exception("Campo {$iCodCampo} inválido!");
      }
    }
  }

  public function getTipo() {
    return $this->iTipo;
  }

  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }

  public function getValorDefault() {
    return $this->sValorDefault;
  }

  public function setValorDefault($sValorDefault) {
    $this->sValorDefault = $sValorDefault;
  }

  /**
   * @return DBAttDinamicoAtributoOpcao[]
   */
  public function getOpcoes() {

    if (is_null($this->aOpcoes)) {

      $this->aOpcoes = array();

      if ($this->getCodigo()) {

        $oDaoOpcoes = new cl_db_cadattdinamicoatributosopcoes();
        $sSqlOpcoes = $oDaoOpcoes->sql_query_file(null, "*", null, "db18_cadattdinamicoatributos = {$this->getCodigo()}");
        $rsOpcoes   = $oDaoOpcoes->sql_record($sSqlOpcoes);

        if ($rsOpcoes && $oDaoOpcoes->numrows > 0) {

          $this->aOpcoes = db_utils::makeCollectionFromRecord($rsOpcoes, function($oRegistro) {

            $oOpcao = new DBAttDinamicoAtributoOpcao();
            $oOpcao->setOpcao($oRegistro->db18_opcao);
            $oOpcao->setValor($oRegistro->db18_valor);

            return $oOpcao;
          });
        }
      }
    }

    return $this->aOpcoes;
  }

  /**
   * @param DBAttDinamicoAtributoOpcao[] $aOpcoes
   * @throws Exception
   */
  public function setOpcoes($aOpcoes) {

    foreach ($aOpcoes as $oOpcao) {

      if ( !($oOpcao instanceof DBAttDinamicoAtributoOpcao) ) {
        throw new Exception("Erro ao setar opções do atributo.");
      }
    }

    $this->aOpcoes = $aOpcoes;
  }

  /**
   * @throws Exception
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta!\\n\\nOperação cancelada.");
    }

    $oDaoCadAttDinamicoAtributo = new cl_db_cadattdinamicoatributos();

    $oDaoCadAttDinamicoAtributo->db109_sequencial        = $this->getCodigo() ;
    $oDaoCadAttDinamicoAtributo->db109_db_cadattdinamico = $this->getGrupoAtributo();
    $oDaoCadAttDinamicoAtributo->db109_nome              = $this->getNome();
    $oDaoCadAttDinamicoAtributo->db109_descricao         = $this->getDescricao();
    $oDaoCadAttDinamicoAtributo->db109_tipo              = $this->getTipo();
    $oDaoCadAttDinamicoAtributo->db109_valordefault      = $this->getValorDefault();
    $oDaoCadAttDinamicoAtributo->db109_obrigatorio       = $this->isObrigatorio() ? 'true' : 'false';
    $oDaoCadAttDinamicoAtributo->db109_ativo             = $this->ativo() ? 'true' : 'false';

    $GLOBALS["HTTP_POST_VARS"]["db109_valordefault"]     = $this->getValorDefault();

    if ($this->getCampo()) {
      $oDaoCadAttDinamicoAtributo->db109_codcam          = $this->getCampo()->iCodigo;
    }


    if (trim($this->getCodigo()) == '') {

      $oDaoCadAttDinamicoAtributo->incluir(null);
      $this->setCodigo($oDaoCadAttDinamicoAtributo->db109_sequencial);
    } else {
      $oDaoCadAttDinamicoAtributo->alterar($this->getCodigo());
    }

    if (!is_null($this->aOpcoes)) {

      $oDaoOpcoes = new cl_db_cadattdinamicoatributosopcoes();
      $oDaoOpcoes->excluir(null, "db18_cadattdinamicoatributos = {$this->getCodigo()}");

      foreach ($this->aOpcoes as $oOpcao) {

        $oDaoOpcoes->db18_sequencial = null;
        $oDaoOpcoes->db18_cadattdinamicoatributos = $this->getCodigo();
        $oDaoOpcoes->db18_opcao = $oOpcao->getOpcao();
        $oDaoOpcoes->db18_valor = $oOpcao->getValor();

        $oDaoOpcoes->incluir(null);

        if ($oDaoOpcoes->erro_status == 0) {
          throw new Exception($oDaoOpcoes->erro_msg);
        }
      }
    }

    if ($oDaoCadAttDinamicoAtributo->erro_status == 0) {
      throw new Exception($oDaoCadAttDinamicoAtributo->erro_msg);
    }
  }

  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta!\\n\\nOperação cancelada.");
    }

    if (trim($this->getCodigo()) == '') {
      throw new Exception("Código do atributo não informado!");
    }

    $oDaoCadAttDinamicoAtributoValor = new cl_db_cadattdinamicoatributosvalor();

    $sWhereAtributoValor = "db110_db_cadattdinamicoatributos = {$this->getCodigo()}";
    $sSqlAtributoValor   = $oDaoCadAttDinamicoAtributoValor->sql_query_file(null,"*",null,$sWhereAtributoValor);
    $rsAtributoValor     = $oDaoCadAttDinamicoAtributoValor->sql_record($sSqlAtributoValor);

    if ($oDaoCadAttDinamicoAtributoValor->numrows > 0) {
      throw new Exception("Operação cancelada. Existem valores lançados para o atributo informado.");
    }

    $oDaoOpcoes = new cl_db_cadattdinamicoatributosopcoes();
    $oDaoOpcoes->excluir(null, "db18_cadattdinamicoatributos = {$this->getCodigo()}");

    if ($oDaoOpcoes->erro_status == 0) {
      throw new Exception($oDaoOpcoes->erro_msg);
    }

    $oDaoCadAttDinamicoAtributo = new cl_db_cadattdinamicoatributos();
    $oDaoCadAttDinamicoAtributo->excluir($this->getCodigo());

    if ($oDaoCadAttDinamicoAtributo->erro_status == 0) {
      throw new Exception($oDaoCadAttDinamicoAtributo->erro_msg);
    }

  }

  /**
   * Formatar o valor do atributo
   * @param \DBAttDinamicoAtributo $atributo
   * @param                        $valor
   * @return string
   */
  public static function formatarValor(DBAttDinamicoAtributo $atributo, $valor) {

    if ( $valor == '' && $atributo->getTipo() != \DBAttDinamicoAtributo::TIPO_BOOLEAN ) {
      return $valor;
    }

    switch ($atributo->getTipo()) {

      case \DBAttDinamicoAtributo::TIPO_NUMERIC:

        $valor = trim(db_formatar($valor, 'f'));
        break;

      case \DBAttDinamicoAtributo::TIPO_DATE:

        $data  = new \DBDate($valor);
        $valor = $data->getDate(\DBDate::DATA_PTBR);
        break;

      case \DBAttDinamicoAtributo::TIPO_BOOLEAN:

        $valor = $valor ? 'Sim':'Não';
        break;
    }

    return $valor;
  }

}

