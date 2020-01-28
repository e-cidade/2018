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
 * Atividade da escola
 * @package educacao
 * @subpackage recursohumano
 * @author Fabio Esteves - fabio.esteves@dbseller.com.br
 */
class AtividadeEscolar {

  const EFETIVIDADE_PROFESSOR   = 'PROF';
  const EFETIVIDADE_FUNCIONARIO = 'FUNC';
  
  const FUNCAO_NENHUM                          = 0;
  const FUNCAO_DOCENTE                         = 1;
  const FUNCAO_AUXILIAR_ASSISTENTE_EDUCACIONAL = 2;
  const FUNCAO_PROFISSIONAL_MONITOR            = 3; // PROFISSIONAL/MONITOR DE ATIVIDADE COMPLEMENTAR
  const FUNCAO_TRADUTOR_INTERPRETE_LIBRAS      = 4;
  const FUNCAO_DOCENTE_TITULAR                 = 5; // DOCENTE TITULAR - COORDENADOR DE TUTORIA(DE MÓDULO OU DISCIPLINA) - EAD
  const FUNCAO_DOCENTE_TUTOR                   = 6; // DOCENTE TUTOR - (DE MÓDULO OU DISCIPLINA)
    
  /**
   * Código da atividade
   * @var integer
   */
  protected $iCodigo;

  /**
   * Descrição da atividade
   * @var string
   */
  protected $sDescricao;

  /**
   * Identifica se permite lecionar
   * @var boolean
   */
  protected $lPermiteLecionar;

  /**
   * Identifica o tipo da efetividade
   * PROF = professor
   * FUNC = funcionario
   * @var string
   */
  protected $sEfetividade;

  /**
   * Identifica se é docente
   * @var boolean
   */
  protected $lDocente;

  /**
   * Código da funcao ou atividade do profissional
   * @var integer
   */
  protected $iFuncaoAtividade;

  /**
   * Construtor da classe
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoAtividadeRh   = db_utils::getDao("atividaderh");
      $sWhereAtividadeRh = "ed01_i_codigo = {$iCodigo}";
      $sSqlAtividadeRh   = $oDaoAtividadeRh->sql_query(null, "*", null, $sWhereAtividadeRh);
      $rsAtividadeRh     = $oDaoAtividadeRh->sql_record($sSqlAtividadeRh);

      if ($oDaoAtividadeRh->numrows > 0) {

        $oDadosAtividadeRh       = db_utils::fieldsMemory($rsAtividadeRh, 0);
        $this->iCodigo           = $oDadosAtividadeRh->ed01_i_codigo;
        $this->sDescricao        = $oDadosAtividadeRh->ed01_c_descr;
        $this->lPermiteLecionar  = $oDadosAtividadeRh->ed01_c_regencia == "S" ? true : false;
        $this->sEfetividade      = $oDadosAtividadeRh->ed01_c_efetividade;
        $this->lDocente          = $oDadosAtividadeRh->ed01_c_docencia == "S" ? true : false;
        $this->lAtividadeEscolar = $oDadosAtividadeRh->ed01_atividadeescolar == 't';
        $this->iFuncaoAtividade  = $oDadosAtividadeRh->ed01_funcaoatividade;
        unset($oDadosAtividadeRh);
      }
    }
  }

  /**
   * Retorna o codigo da atividade
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o codigo da atividade
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a descricao da atividade
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descricao da atividade
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna se a atividade permite lecionar
   * @return boolean
   */
  public function permiteLecionar() {
    return $this->lPermiteLecionar;
  }


  /**
   * Setter tipo de efetividade
   * @param string
   */
  public function setEfetividade ($sEfetividade) {
    $this->sEfetividade = $sEfetividade;
  }

  /**
   * Getter tipo de efetividade
   * @param string
   */
  public function getEfetividade() {
    return $this->sEfetividade;
  }

  public function isDocente() {
    return $this->lDocente;
  }


  public function setAtividadeEscolar($lAtividadeEscolar) {
    $this->lAtividadeEscolar = $lAtividadeEscolar;
  }

  public function getAtividadeEscolar() {
    return $this->lAtividadeEscolar;
  }

  /**
   * Se atividade permite ao profissional informar relação de trabalho
   * @return boolean
   */
  public function permiteInformarRelacaoTrabalho() {

    return $this->lAtividadeEscolar || $this->lPermiteLecionar;
  }

  /**
   * Retorna o código da Função de trabalho do profissional
   * @return integer
   */
  public function getCodigoFuncaoAtividade() {
    
    return $this->iFuncaoAtividade;
  }

}