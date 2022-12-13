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
class DBAttDinamicoGrupo {

  /**
   *
   * @var array
   */
  private $aCampos;

  /**
   *
   * @var integer
   */
  private $iCodigoGrupo;

  /**
   * Grupo de atributos dinâmicos
   *
   * @var DBAttDinamico
   */
  private $oAtributoDinamico;

  /**
   *
   * @param integet $iCodigoGrupo Código do grupo de valores
   */
  public function __construct($iCodigoGrupo = null) {

    $this->iCodigoGrupo = $iCodigoGrupo;

    if ($this->iCodigoGrupo) {

      $oDaoAtributoGrupo     = new cl_db_cadattdinamicovalorgrupo;
      $sSqlAtributosValor    = $oDaoAtributoGrupo->sql_query($this->iCodigoGrupo);
      $rsDadosAtributosValor = db_query($sSqlAtributosValor);

      if (!$rsDadosAtributosValor || pg_num_rows($rsDadosAtributosValor) === 0) {
        throw new DBException('O grupo de atributos não foi encontrado.');
      }

      $oAtributoDinamicoValor  = DBAttDinamicoValor::getValores($this->iCodigoGrupo);
      $iAtributoDinamico       = $oAtributoDinamicoValor[0]->getAtributo()->getGrupoAtributo();
      $this->oAtributoDinamico = new DBAttDinamico($iAtributoDinamico);
      foreach ($oAtributoDinamicoValor as $oValor) {
        $this->aCampos[$oValor->getAtributo()->getNome()] = $oValor->getValor();
      }
    }
  }

  /**
   *
   * @param DBAttDinamico $oAtributoDinamico Grupo de atributos dinâmicos.
   */
  public function setAtributoDinamico(DBAttDinamico $oAtributoDinamico) {

    $this->oAtributoDinamico = $oAtributoDinamico;

    foreach ($this->oAtributoDinamico->getAtributos() as $oAtributo) {
      $this->aCampos[$oAtributo->getNome()] = '';
    }
  }

  /**
   *
   * @return DBAttDinamico Grupo de atributos dinâmicos.
   */
  public function getAtributoDinamico() {
    return $this->oAtributoDinamico;
  }

  /**
   * Retorna os valores dos atributos
   *
   * @return array
   */
  public function getValores() {
    return $this->aCampos;
  }

  /**
   * Define o valor do atributo
   *
   * @param string $sCampo Nome do atributo
   * @param string $sValor Valor do atributo
   */
  public function setValor($sCampo, $sValor) {

    if (!isset($this->aCampos[$sCampo])) {
      throw new ParameterException('O campo informado não existe no grupo de atributos.');
    }

    $this->aCampos[$sCampo] = $sValor;
  }

  /**
   * Retorna o valor do atributo
   *
   * @param  string $sCampo Nome do atributo
   * @return mixed
   */
  public function getValor($sCampo) {

    if (isset($this->aCampos[$sCampo])) {
      return $this->aCampos[$sCampo];
    }

    return null;
  }

  /**
   * Salva os valores dos atributos
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transação com o banco de dados aberta.");
    }

    $oDaoAtributosValor      = new cl_db_cadattdinamicoatributosvalor();
    $oDaoAtributosValorGrupo = new cl_db_cadattdinamicovalorgrupo();

    /**
     * Caso não exista valor para o agrupador de registros então gera um novo
     */
    if ($this->iCodigoGrupo == null) {

      $oDaoAtributosValorGrupo->incluir(null);
      if ($oDaoAtributosValorGrupo->erro_status == 0) {
        throw new DBException("Não foi possível incluir um novo grupo de valores.");
      }

      $this->iCodigoGrupo = $oDaoAtributosValorGrupo->db120_sequencial;
    } else {

      $oDaoAtributosValor->excluir(null," db110_cadattdinamicovalorgrupo = {$this->iCodigoGrupo} ");
      if ($oDaoAtributosValor->erro_status == 0) {
        throw new DBException($oDaoAtributosValor->erro_msg);
      }
    }

    foreach ($this->oAtributoDinamico->getAtributos() as $oAtributo) {

      $oDaoAtributosValor->db110_cadattdinamicovalorgrupo   = $this->iCodigoGrupo;
      $oDaoAtributosValor->db110_db_cadattdinamicoatributos = $oAtributo->getCodigo();
      $oDaoAtributosValor->db110_valor                      = $this->getValor($oAtributo->getNome());
      $oDaoAtributosValor->incluir(null);

      if ($oDaoAtributosValor->erro_status == 0) {
        throw new DBException($oDaoAtributosValor->erro_msg);
      }
    }

    return $this->iCodigoGrupo;
  }

  /**
   * Apaga o grupo e os respectivos dados
   *
   * @param  integer $iCodigoGrupo
   * @throws DBException
   * @throws ParameterException
   * @return boolean
   */
  public function excluir() {

    if (!$this->iCodigoGrupo) {
      throw new ParameterException("Não existe um grupo carregado.");
    }

    $oDaoAtributosValor = new cl_db_cadattdinamicoatributosvalor();
    $oDaoAtributosValor->excluir(null, "db110_cadattdinamicovalorgrupo = {$this->iCodigoGrupo}");
    if ($oDaoAtributosValor->erro_status == 0) {
      throw new DBException('Não foi possível apagar o grupo de valores.');
    }

    $oDaoAtributoGrupo = new cl_db_cadattdinamicovalorgrupo;
    $oDaoAtributoGrupo->excluir($this->iCodigoGrupo);
    if ($oDaoAtributoGrupo->erro_status == 0) {
      throw new DBException('Não foi possível apagar o grupo de valores.');
    }

    return true;
  }
}
