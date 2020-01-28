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
 * Controle das configuracoes da progressao parcial da escola
 * Configuracoes da progressao parcial. define a quantidade
 * de disciplinas em que cada aluno pode estar em dependencia, e quais etapas é disponibilizado
 * essa forma de avaliacao.
 * @filesource
 * @author Iuri Guntchnigg - iuri@dbseller.com.br
 * @version $Revision: 1.9 $
 * @package
 */
final class ProgressaoParcialParametro {


  /**
   * Codigo do parametro,
   * Utilizado para persistencia dos dados na base de dados
   * @var integer
   */
  private $iCodigo;

  /**
   * Codigo da escola
   * Escola que possui a configuracao progressao parcial
   * @var integer
   */
  private $iEscola;

  /**
   * Quantidade de disciplinas
   * Quantidade de disciplinas que é permitido o aluno ficar em progressao/dependencia
   * @var integer
   */
  private $iQuantidadeDisciplina = null;

  /**
   * Forma de controle
   * Forma que é controlada a progressao parcial
   * @var integer
   */
  private $iFormaControle = 2;

  /**
   * Forma de controle por base Curricular
   * o Aluno poderá ter no máximo o Quantidade de disciplinas configuradas para toda a base de ensino.
   * @var integer
   */
  const CONTROLE_BASE_CURRICULAR = 2;

  /**
   * Forma de controle por ETAPA
   * o Aluno poderá ter no máximo o Quantidade de disciplinas configuradas para cada etapa
   * @var integer
   */
  CONST CONTROLE_ETAPA = 1;

  /**
   * Progressao habilitada.
   * Verifica se a escola possui a progressao habilitada
   * @var boolean
   */
  private $lHabilitada = false;

  /**
   * Disciplina Aprovada elimina Dependencia
   * Verifica se o aluno aprovou em uma disciplina que ele possui dependencia em um ano posterior, e elimina
   * automaticamente a dependencia nessa disciplina.
   * @var boolean
   */
  private $lDisciplinaAprovadaElimina = false;

  /**
   * Justificativa da eliminacao da dependencia
   * @var string
   */
  private $sJustificativa = "";

  /**
   * Controla frequencia
   * a progressao parcial obriga o controle de frequencia
   * @var boolean
   */
  private $lControleFrequencia = false;


  /**
   * Lista de etapas
   * Lista de etapas que a progressao parcial é permitido
   * @var Etapa[]
   */
  private $aEtapas = array();

  /**
   * Verificacao das  etapas carregadas
   * @var boolean;
   */
  private $lEtapasCarregadas = false;

  /* ATENCAO: PLUGIN ParametroProgressaoParcial - VARIAVEL - INSTALADO AQUI - NAO REMOVER */

  /**
   * Metodo construtor
   * Inicia os dados do parametro para a escola.
   * @param integer $iEscola Codigo da escola
   */
  public function __construct($iEscola) {

    if (!empty($iEscola)) {

      $this->iEscola         = $iEscola;
      $oDaoProgressaoParcial = db_utils::getDao("parametroprogressaoparcial");

      $sWhere                   = "ed112_escola = {$iEscola}";

      /* ATENCAO: PLUGIN ParametroProgressaoParcial - QUERY - INSTALADO AQUI - NAO REMOVER */
      $sSqlProgressaoParcial    = $oDaoProgressaoParcial->sql_query_file(null, "*", null, $sWhere);
      $rsDadosProgressaoParcial = $oDaoProgressaoParcial->sql_record($sSqlProgressaoParcial);
      if ($oDaoProgressaoParcial->numrows == 1) {

        $oDadosProgressaoParcial = db_utils::fieldsMemory($rsDadosProgressaoParcial, 0);
        $lEliminaDisciplina      = $oDadosProgressaoParcial->ed112_disciplinaeliminadependencia == 't' ? true : false;
        $this->setControleFrequencia($oDadosProgressaoParcial->ed112_controlefrequencia == 't' ? true : false);
        $this->setDisciplinaAprovadaEliminaProgressao($lEliminaDisciplina);
        $this->setHabilitada($oDadosProgressaoParcial->ed112_habilitado == 't' ? true : false);
        $this->setFormaControle($oDadosProgressaoParcial->ed112_formacontrole);
        $this->setQuantidadeDisciplina($oDadosProgressaoParcial->ed112_quantidadedisciplinas);
        $this->setJustificativa($oDadosProgressaoParcial->ed112_justificativa);
        $this->iCodigo = $oDadosProgressaoParcial->ed112_sequencial;
        /* ATENCAO: PLUGIN ParametroProgressaoParcial - CONSTRUTOR - INSTALADO AQUI - NAO REMOVER */
        unset($oDadosProgressaoParcial);
      }
    }
  }

  /**
   * Retorna o codigo do parametro
   * Código do parametro utilizado para a persistencia dos dados
   * @return integer Codigo do parametro
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o codigo da escola
   * @return integer codigo da escola
   */
  public function getEscola() {
    return $this->iEscola;
  }

  /**
   * Retorna o Quantidade de disciplinas
   * Retorna o Quantidade de disciplinas configuradas para a progressao parcial.
   * @return integer Quantidade de disciplinas para progressao parcial
   */
  public function getQuantidadeDisciplina() {
    return $this->iQuantidadeDisciplina;
  }

  /**
   * Define o Quantidade maximo de disciplinas que o aluno pode estar dem progressao parcial
   * @param integer $iQuantidadeDisciplina quantidade de disciplinas
   */
  public function setQuantidadeDisciplina($iQuantidadeDisciplina) {
    $this->iQuantidadeDisciplina = $iQuantidadeDisciplina;
  }

  /**
   * Retorna a forma de controle da progressao parcial
   * As Formas de controle da progressao parcial é pela base curricular, ou por etapa;
   * Caso o controle for por etapa, teremos como retorno <code>ProgressaoParcialParametro::CONTROLE_ETAPA</code>.
   * Quando o retorno for por base curricular teremos como
   * retorno <code>ProgressaoParcialParametro::CONTROLE_BASE_CURRICULAR</code>.
   *
   * @return integer retorna ProgressaoParcialParametro::CONTROLE_ETAPA ou
   *                         ProgressaoParcialParametro::CONTROLE_BASE_CURRICULAR
   */
  public function getFormaControle() {
    return $this->iFormaControle;
  }

  /**
   * Define a forma de controle da progressao parcial
   * Define como a escola quer controlar a forma de progressao parcial.
   * @throws ParameterException
   * @param integer $iFormaControle Aceita as constantes ProgressaoParcialParametro::CONTROLE_ETAPA ou
   *                                                     ProgressaoParcialParametro::CONTROLE_BASE_CURRICULAR
   */
  public function setFormaControle($iFormaControle) {

    $aFormasControle = array(self::CONTROLE_BASE_CURRICULAR,
                             self::CONTROLE_ETAPA
                            );
    if (!in_array($iFormaControle, $aFormasControle)) {

      $sErroParametro  = 'Parametro $iFormaControle deve ser  ProgressaoParcialParametro::CONTROLE_ETAPA';
      $sErroParametro .= 'ou ProgressaoParcialParametro::CONTROLE_BASE_CURRICULAR';
      throw new ParameterException($sErroParametro);
    }

    $this->iFormaControle = $iFormaControle;
  }

  /**
   * Reetorna se a escola possui ou nao progressao ativa.
   * @return boolean retorna true para progressao ativa, false para progressao inativa
   */
  public function isHabilitada() {
    return $this->lHabilitada;
  }

  /**
   * Define se a escola possui controle de progressao parcial
   * Caso o parametro for true, a escola ira ter o controle de progressao ativo.
   *
   * @param boolean $lHabilitada true para progressao ativa, false para progressao inativa
   * @throws ParameterException
   */
  public function setHabilitada($lHabilitada = false) {

    if (!is_bool($lHabilitada)) {
      throw new ParameterException('Parametro $lHabilitada deve ser true ou false');
    }
    $this->lHabilitada = $lHabilitada;
  }

  /**
   * Disciplina aprovada elimina dependencia.
   * Verifica se a escola quer eliminar a dependencia quando o aluno for aprovado na disciplina atraves
   * da turma normal.
   * @return boolean true quando a disciplina aprovada elimina a dependencia
   */
  public function disciplinaAprovadaEliminaProgressao() {
    return $this->lDisciplinaAprovadaElimina;
  }

  /**
   * define se aisciplina aprovada elimina dependencia.
   * define se a escola quer eliminar a dependencia quando o aluno for aprovado na disciplina atraves
   * da turma normal.
   *
   * @param boolean $lDisciplinaAprovadaElimina true para eliminar
   * @throws ParameterException
   */
  public function setDisciplinaAprovadaEliminaProgressao($lDisciplinaAprovadaElimina = false) {

    if (!is_bool($lDisciplinaAprovadaElimina)) {
      throw new ParameterException('Parametro $lDisciplinaAprovadaElimina deve ser true ou false');
    }
    $this->lDisciplinaAprovadaElimina = $lDisciplinaAprovadaElimina;
  }

  /**
   * Justificativa para a eliminacao de disciplinas
   * Retorna a justificativa ira para a progressao parcial, quando a mesma for eliminada pelo aluno ter aprovado na
   * disciplina na turma normal.
   * @return string texto da Justificativa
   */
  public function getJustificativa() {
    return $this->sJustificativa;
  }

  /**
   * Define a justificativa para a eliminacao de disciplinas
   * Define qual justificativa ira para a progressao parcial, quando a mesma for eliminada pelo aluno ter aprovado na
   * disciplina na turma normal.
   * @param string $sJustificativa Texto da Justificativa
   */
  public function setJustificativa($sJustificativa) {
    $this->sJustificativa = $sJustificativa;
  }

  /**
   * Progressao tem controle de frequencia
   * Caso o retorno do metodo é true, as turmas de progressao parcial tem o controle de frequencia
   * @return boolean true quando existe controle de frequencia
   */
  public function temControleFrequencia() {
    return $this->lControleFrequencia;
  }

  /**
   * Define se existe o controle de frequencia na etapa
   *
   * @param boolean $lControleFrequencia se passado true, ira controlar a frequencia do aluno
   * @throws ParameterException
   */
  public function setControleFrequencia($lControleFrequencia = false) {

    if (!is_bool($lControleFrequencia)) {
      throw new ParameterException('Parametro $lControleFrequencia deve ser true ou false');
    }
    $this->lControleFrequencia = $lControleFrequencia;
  }

  /**
   * Retorna as etapa
   * Retorna as etapas em que é permitido a progressão parcial do aluno
   * @return Etapa[] Colecao de Etapas
   */
  public function getEtapas() {

    if (count($this->aEtapas) == 0 && !empty($this->iCodigo) && !$this->lEtapasCarregadas) {

      $oDaoParamProgressaoEtapa  = db_utils::getDao("parametroprogressaoparcialetapa");
      $sWereParamProgressaoEtapa = " ed113_parametroprogressaoparcial = {$this->iCodigo}";
      $sSqlParamProgressaoEtapa  = $oDaoParamProgressaoEtapa->sql_query_file(null, "*",
                                                                             null, $sWereParamProgressaoEtapa);

      $rsParamProgressaoEtapa    = $oDaoParamProgressaoEtapa->sql_record($sSqlParamProgressaoEtapa);
      $iRegistro                 = $oDaoParamProgressaoEtapa->numrows;

      for ($i = 0; $i < $iRegistro; $i++) {
        $this->aEtapas[] =  EtapaRepository::getEtapaByCodigo(db_utils::fieldsMemory($rsParamProgressaoEtapa, $i)->ed113_serie);
      }
      $this->lEtapasCarregadas = true;
    }
    return $this->aEtapas;
  }

  /**
   * Adiciona uma etapa ao controle de progressao parcial da escola
   * Define quais etapas poderam ter progressao parcial
   *
   * @param Etapa $oEtapa Etapa para adicionar
   * @return bool
   * @throws BusinessException
   */
  public function adicionarEtapa (Etapa $oEtapa) {

    $this->aEtapas = $this->getEtapas();
    foreach ($this->aEtapas as $oEtapaLancada) {

      if ($oEtapaLancada->getCodigo() == $oEtapa->getCodigo()) {
        return true;
      }
    }
    $this->aEtapas[] = $oEtapa;
  }

  /**
   * Remove uma etapa ou todas as etapas das configuracoes,
   * caso o parametre $oEtapa nao seja informado, todas as etapas vinculadas a progressao parcial serao
   * removidadas.
   *
   * @param Etapa $oEtapa Etapa a ser removida
   * @return bool
   */
  public function removerEtapa (Etapa $oEtapa = null) {

    $this->aEtapas = $this->getEtapas();
    if ($oEtapa == null) {

      $this->aEtapas = array();
      return true;
    }
    foreach ($this->aEtapas as $iIndice => $oEtapaLancada) {

      if ($oEtapaLancada->getCodigo() == $oEtapa->getCodigo()) {

        array_splice($this->aEtapas, $iIndice, 1);
        unset($oEtapaLancada);
        return true;
      }
    }
  }

  /* ATENCAO: PLUGIN ParametroProgressaoParcial - GETTER E SETTER - INSTALADO AQUI - NAO REMOVER */

  /**
   * Persiste os dados da configuracao da progressao parcial;
   * @throws BusinessException
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
     throw new DBException("Não existe transação com o banco de dados ativa.");
    }
    
    $oDaoParametroProgressaoParcial = db_utils::getDao("parametroprogressaoparcial");

    $lDisciplinaElimina = $this->disciplinaAprovadaEliminaProgressao() === true ? "true" : "false";

    $oDaoParametroProgressaoParcial->ed112_habilitado         = $this->isHabilitada() === true? "true" : "false";
    $oDaoParametroProgressaoParcial->ed112_controlefrequencia = $this->temControleFrequencia() === true ? "true" :
                                                                                                          "false";

    $oDaoParametroProgressaoParcial->ed112_disciplinaeliminadependencia = $lDisciplinaElimina;
    $oDaoParametroProgressaoParcial->ed112_escola                       = $this->iEscola;
    $oDaoParametroProgressaoParcial->ed112_formacontrole                = $this->getFormaControle();
    $oDaoParametroProgressaoParcial->ed112_quantidadedisciplinas        = "{$this->getQuantidadeDisciplina()}";

    $oDaoParametroProgressaoParcial->ed112_justificativa = $this->getJustificativa();

    /* ATENCAO: PLUGIN ParametroProgressaoParcial - Definindo Parametro Incluir - INSTALADO AQUI - NAO REMOVER */

    if (empty($this->iCodigo)) {

      $oDaoParametroProgressaoParcial->incluir(null);
      $this->iCodigo = $oDaoParametroProgressaoParcial->ed112_sequencial;
      /* ATENCAO: PLUGIN ParametroProgressaoParcial - Setando Parametro Incluir - INSTALADO AQUI - NAO REMOVER */
    } else {

      $oDaoParametroProgressaoParcial->ed112_sequencial = $this->iCodigo;
      $oDaoParametroProgressaoParcial->alterar($this->iCodigo);
    }

    if ($oDaoParametroProgressaoParcial->erro_status == 0) {

      $sErroMensagem = "Erro ao salvar dados da progressao parcial";
      $sErroMensagem .= "Erro Técnico: {$oDaoParametroProgressaoParcial->erro_msg}";
      throw new BusinessException($sErroMensagem);
    }

    /* ATENCAO: PLUGIN ParametroProgressaoParcial - INCLUINDO E ALTERANDO - INSTALADO AQUI - NAO REMOVER */

    /**
     * Persistimos os dados das etapas;
     * Antes de incluirmos, deletamos as etapas vinculadas, apos incluimos as que estao configuradas
     */
    $oDaoParametroProgressaoParcialEtapa = db_utils::getDao("parametroprogressaoparcialetapa");
    $oDaoParametroProgressaoParcialEtapa->excluir(null, "ed113_parametroprogressaoparcial = {$this->getCodigo()}");
    if ($oDaoParametroProgressaoParcialEtapa->erro_status == 0) {
      throw new BusinessException("Erro ao verificar etapas vinculadas da progressao parcial. ");
    }

    foreach ($this->getEtapas() as $oEtapa) {

      $oDaoParametroProgressaoParcialEtapa->ed113_serie = $oEtapa->getCodigo();
      $oDaoParametroProgressaoParcialEtapa->ed113_parametroprogressaoparcial = $this->getCodigo();
      $oDaoParametroProgressaoParcialEtapa->incluir(null);
      if ($oDaoParametroProgressaoParcialEtapa->erro_status == 0) {

        $sMensagem  = "Erro ao vincular etapas vinculadas da progressao parcial. ";
        $sMensagem .= "Erro Técnico:".str_replace("\\n", "\n", $oDaoParametroProgressaoParcialEtapa->erro_msg);
        throw new BusinessException($sMensagem);
      }
    }
  }
}