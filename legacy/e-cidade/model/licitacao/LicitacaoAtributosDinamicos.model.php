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
class LicitacaoAtributosDinamicos {

  const NOME_TIPO_OBJETO                       = 'tipoobjeto';
  const NOME_TIPO_LICITACAO                    = 'tipolicitacao';
  const NOME_CARACTERISTICA_OBJETO             = 'caracteristicaobjeto';
  const NOME_REGIME_EXECUCAO                   = 'regimeexecucao';
  const NOME_PERMITE_SUBCONTRATACAO            = 'permitesubcontratacao';
  const NOME_TIPO_BENEFICIO_MEPP               = 'tipobeneficiomicroepp';
  const NOME_TIPO_FORNECIMENTO                 = 'tipofornecimento';
  const NOME_PERCENTUAL_TAXA_RISCO             = 'pctaxarisco';
  const NOME_TIPO_EXECUCAO                     = 'tipoexecucao';
  const NOME_TIPO_DISPUTA                      = 'tipodisputa';
  const NOME_PRE_QUALIFICACAO                  = 'prequalificacao';
  const NOME_INVERSAO_FASES                    = 'inversaofases';
  const NOME_FUNDAMENTACAO                     = 'codigofundamentacao';
  const NOME_NUMERO_ARTIGO                     = 'numeroartigo';
  const NOME_INCISO                            = 'inciso';
  const NOME_LEI                               = 'lei';
  const NOME_DATA_INICIO_INSCRICAO             = 'datainicioinscricaocredenciamento';
  const NOME_DATA_FIM_INSCRICAO                = 'datafiminscricaocredenciamento';
  const NOME_DATA_INICIO_VIGENCIA              = 'datainiciovigenciacredenciamento';
  const NOME_DATA_FIM_VIGENCIA                 = 'datafimvigenciacredenciamento';
  const NOME_RECEBE_INSCRICAO_PERIODO_VIGENCIA = 'recebeinscricaoperiodovigencia';
  const NOME_PERMITE_PARTICIPACAO_CONSORCIO    = 'permiteconsorcio';
  const NOME_TIPO_ORCAMENTO                    = 'tipoorcamento';
  const NOME_CNPJ_ORGAO_GERENCIADOR            = 'cnpjorgaogerenciador';
  const NOME_ORGAO_GERENCIADOR                 = 'nomeorgaogerenciador';
  const NOME_NUMERO_LICITACAO                  = 'numerolicitacao';
  const NOME_ANO_LICITACAO                     = 'anolicitacao';
  const NOME_NUMERO_ATA_REGISTRO_PRECO         = 'numeroataregistropreco';
  const NOME_DATA_ATA                          = 'dataata';
  const NOME_DATA_AUTORIZACAO                  = 'dataautorizacao';
  const NOME_TIPO_ATUACAO                      = 'tipoatuacao';

  /**
   * @var integer Codigo da Licitação
   */
  private $iCodigoLicitacao;

  /**
   * @var integer Codigo do Grupo de Atributo Dinamico
   */
  private $iCodigoGrupo;

  /**
   * @var array Atributos e Valores
   */
  private $aAtributos;

  /**
   * LicitacaoAtributosDinamicos constructor.
   * @param integer $iCodigoLicitacao
   */
  public function __construct($iCodigoLicitacao = null) {
    $this->iCodigoLicitacao = $iCodigoLicitacao;
  }

  /**
   * @param integer $iCodigoLicitacao
   */
  public function setCodigoLicitacao($iCodigoLicitacao) {
    $this->iCodigoLicitacao = (integer) $iCodigoLicitacao;
  }

  /**
   * @param integer $iCodigoGrupo
   */
  public function setCodigoGrupo($iCodigoGrupo) {
    $this->iCodigoGrupo = (integer) $iCodigoGrupo;
  }

  /**
   * @return int
   */
  public function getCodigoGrupo() {
    return (integer)$this->iCodigoGrupo;
  }


  /**
   * Carrega atributos dinamicos usando o codigo da licitação ou codigo do grupo de atributo dinamico
   * @throws ParameterException
   */
  private function carregarAtributos() {

    $this->aAtributos = array();

    if ($this->iCodigoLicitacao && !$this->iCodigoGrupo) {

      $oDaoLicitacaoAtributosDinamico = new cl_liclicitacadattdinamicovalorgrupo;
      $sWhere                         = "l16_liclicita = {$this->iCodigoLicitacao}";
      $sSqlGrupoAtributoDinamico      = $oDaoLicitacaoAtributosDinamico->sql_query_file(null, "l16_cadattdinamicovalorgrupo", null, $sWhere);
      $rsGrupoAtributoDinamico        = db_query($sSqlGrupoAtributoDinamico);

      if ($rsGrupoAtributoDinamico && pg_num_rows($rsGrupoAtributoDinamico) > 0) {
        $this->iCodigoGrupo = db_utils::fieldsMemory($rsGrupoAtributoDinamico, 0)->l16_cadattdinamicovalorgrupo;
      }
    }

    if (!$this->iCodigoGrupo) {
      return;
    }

    $oGrupoAtributoDinamico = new DBAttDinamicoGrupo($this->iCodigoGrupo);
    $this->aAtributos       = $oGrupoAtributoDinamico->getValores();

    foreach ($oGrupoAtributoDinamico->getAtributoDinamico()->getAtributos() as $oAtributo) {

      if (!$this->possuiAtributo($oAtributo->getNome()) || $this->aAtributos[$oAtributo->getNome()] == null) {
        continue;
      }

      switch ($oAtributo->getTipo()) {
        case DBAttDinamicoAtributo::TIPO_BOOLEAN:
          $this->aAtributos[$oAtributo->getNome()] = $this->aAtributos[$oAtributo->getNome()] == 't' ? true : false;
          break;

        case DBAttDinamicoAtributo::TIPO_DATE:
          $this->aAtributos[$oAtributo->getNome()] = new DBDate($this->aAtributos[$oAtributo->getNome()]);
          break;

        case DBAttDinamicoAtributo::TIPO_NUMERIC:
          $this->aAtributos[$oAtributo->getNome()] = (float) $this->aAtributos[$oAtributo->getNome()];
          break;

        case DBAttDinamicoAtributo::TIPO_INTEGER:
          $this->aAtributos[$oAtributo->getNome()] = (integer) $this->aAtributos[$oAtributo->getNome()];
          break;
      }
    }
  }

  /**
   * @param string $sAtributo Nome do Atributo
   * @throws ParameterException
   * @return mixed
   */
  public function getAtributo($sAtributo, $mValorDefault = null) {

    if (!$this->aAtributos) {
      $this->carregarAtributos();
    }

    if (!$this->possuiAtributo($sAtributo)) {
      return $mValorDefault;
    }

    return $this->aAtributos[$sAtributo];
  }

  /**
   * @param string $sAtribuno Nome do Atributo
   * @return boolean
   */
  public function possuiAtributo($sAtributo) {
    return isset($this->aAtributos[$sAtributo]);
  }
}
