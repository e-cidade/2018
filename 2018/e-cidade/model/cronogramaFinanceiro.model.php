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
class cronogramaFinanceiro {

  const TIPO_BASE_CALCULO = 1;
  const TIPO_RECEITA = 2;
  const TIPO_DESPESA = 3;
  const SITUACAO_ABERTO = 1;
  const SITUACAO_HOMOLOGADO = 2;

  const TIPO_CRONOGRAMA     = 1;
  const TIPO_ACOMPANHAMENTO = 2;

  const MENSAGENS = 'financeiro.orcamento.cronogramaFinanceiro.';

  /**
   * Codigo da Perspectiva
   *
   * @var integer
   */
  protected $iPerspectiva;

  /**
   * Ano do Cronrograma
   *
   * @var int
   */
  protected $iAno;

  /**
   * descrição do cronograma
   *
   * @var string
   */
  protected $sDescricao;

  /**
   * instiuições utilizadas
   *
   * @var array
   */
  protected $aInstituicoes = array();

  /**
   * @var array
   */
  protected $aReceitas = array();

  /**
   * @var int
   */
  protected $iPpaVersao;

  /**
   * Tipo da Perspectiva  1 = Cronograma de Desembolso 2 = Acompanhamento
   * @var int
   */
  protected $iTipoPerspectiva;



  /**
   * @param int
   */
  function __construct($iPerspectiva) {

    if ($iPerspectiva != "") {

      $oDaoCronogramaPerspectiva = db_utils::getDao("cronogramaperspectiva");
      $sWhere                    = "o124_sequencial = {$iPerspectiva} and o123_situacao = 1";
      $sSqlDadosCronograma       = $oDaoCronogramaPerspectiva->sql_query_integracao(null,"*", null, $sWhere);
      $rsDadosCronograma         = $oDaoCronogramaPerspectiva->sql_record($sSqlDadosCronograma);

      if ($oDaoCronogramaPerspectiva->numrows > 0) {

        $this->iPerspectiva = $iPerspectiva;
        $oDadosCronograma   = db_utils::fieldsMemory($rsDadosCronograma,0,false,false);
        $this->sDescricao   = $oDadosCronograma->o124_descricao;
        $this->iAno         = $oDadosCronograma->o124_ano;
        $this->iPpaVersao   = $oDadosCronograma->o124_ppaversao;
        $this->iTipoPerspectiva = $oDadosCronograma->o124_tipo;
        $this->setInstituicoes(array(db_getsession("DB_instit")));

      }
    }
  }

  /**
   * @return int
   */
  public function getPpaVersao() {
    return $this->iPpaVersao;
  }

  /**
   * @return array
   */
  public function getInstituicoes() {

    return $this->aInstituicoes;
  }

  /**
   * @param array $aInstituicoes
   */
  public function setInstituicoes($aInstituicoes) {

    $this->aInstituicoes = $aInstituicoes;
  }

  /**
   * @return int
   */
  public function getAno() {

    return $this->iAno;
  }

  /**
   * @return integer
   */
  public function getPerspectiva() {

    return $this->iPerspectiva;
  }

  /**
   * @return string
   */
  public function getDescricao() {

    return $this->sDescricao;
  }

  /**
   * Retornas as receitas cadastradas no ano;
   * @return array
   * @throws Exception
   */
  protected  function getReceitas() {

     /**
      * Buscamos todas as Receitas do ano e das instituições Marcadas
      */
    $aReceitas       = array();
    $sInstituicoes   = implode(",", $this->getInstituicoes());
    $oDaoOrcReceita  = db_utils::getDao("orcreceita");
    $sListaCampos  = "o57_fonte,          ";
    $sListaCampos .= "o57_descr,          " ;
    $sListaCampos .= "o70_codfon,         " ;
    $sListaCampos .= "o70_instit,         " ;
    $sListaCampos .= "o70_anousu,         " ;
    $sListaCampos .= "o70_valor,          " ;
    $sListaCampos .= "c61_reduz,          " ;
    $sListaCampos .= "o70_concarpeculiar, " ;
    $sListaCampos .= "o70_codrec" ;
    $sWhere        = "o70_anousu = {$this->getAno()} ";
    $sWhere       .= " and o70_instit in({$sInstituicoes})";

    $sSqlReceita  = $oDaoOrcReceita->sql_query_plano(null, null, $sListaCampos, "o57_fonte,o70_concarpeculiar",$sWhere);
    $rsReceita    = $oDaoOrcReceita->sql_record($sSqlReceita);
    if ($oDaoOrcReceita->numrows == 0) {

      $oStdMensagem      = new stdClass();
      $oStdMensagem->ano = $this->getAno();
      throw new Exception(_M(self::MENSAGENS . "erro_sem_receitas_ano", $oStdMensagem));
    }

    for ($i = 0; $i < $oDaoOrcReceita->numrows; $i++) {

      $oReceita                 = db_utils::fieldsMemory($rsReceita, $i);
      $oReceita->iPerspectiva   = $this->getPerspectiva();
      $oReceita->iSequencial    = null;

      $sHash                 = $oReceita->o70_codrec.$oReceita->o70_instit.$oReceita->o70_concarpeculiar;
      $aReceitas[$sHash]     = $oReceita;

    }
    return $aReceitas;
  }

  /**
   * Calcula as /bases/Metas de arrecadacao da receita e metas de custo da despesa
   *
   * @param integer $iTipo Tipo do processamento 1 = BASE  2 = METAS 3 -  Despesa
   * @throws Exception
   */
  public function CalcularBases($iTipo) {

    if ($iTipo == 1 || $iTipo == 2) {

      if ($iTipo == 1) {

         db_inicio_transacao();

      }
      $aReceitas = $this->getReceitas();
      $iNumRows  = count($aReceitas);
      $oDaoCronogramaReceita = db_utils::getDao("cronogramaperspectivareceita");
      $i = 0;
      foreach ($aReceitas as $oReceita) {

        db_fim_transacao(false);
        db_inicio_transacao();

        /**
         * Verificamos se nao existe a receita na perspectiva
         * Caso exista, apenas
         */
        $sHash   = $oReceita->o70_codrec.$oReceita->o70_instit.$oReceita->o70_concarpeculiar;
        $sWhere  = "o126_cronogramaperspectiva = {$this->iPerspectiva}";
        $sWhere .= " and o126_codrec = {$oReceita->o70_codrec}";
        $sSqlVerificaReceita = $oDaoCronogramaReceita->sql_query_file(null,"*", null, $sWhere);
        $rsVerificaReceita   = $oDaoCronogramaReceita->sql_record($sSqlVerificaReceita);

        if ($oDaoCronogramaReceita->numrows > 0) {
          $aReceitas[$sHash]->iSequencial = db_utils::fieldsMemory($rsVerificaReceita, 0)->o126_sequencial;
        } else {

          $oDaoCronogramaReceita->o126_anousu                = $oReceita->o70_anousu;
          $oDaoCronogramaReceita->o126_codrec                = $oReceita->o70_codrec;
          $oDaoCronogramaReceita->o126_cronogramaperspectiva = $oReceita->iPerspectiva;
          $oDaoCronogramaReceita->incluir(null);
          if ($oDaoCronogramaReceita->erro_status == 0) {

            $oStdMensagem      = new stdClass();
            $oStdMensagem->msg = $oDaoCronogramaReceita->erro_msg;
            throw new Exception(_M(self::MENSAGENS . "erro_inclusao_receita", $oStdMensagem));
          }
          $aReceitas[$sHash]->iSequencial = $oDaoCronogramaReceita->o126_sequencial;
        }
        if ($iTipo == 1) {

          require_once("model/cronogramaBaseReceita.model.php");
          $aReceitas[$sHash]->aBases      = new cronogramaBaseReceita($aReceitas[$sHash]);
          $aReceitas[$sHash]->aBases->calcularBases();

        } else if ($iTipo == 2) {

          require_once("model/cronogramaMetaReceita.model.php");
          $aReceitas[$sHash]->aMetas      = new cronogramaMetaReceita($aReceitas[$sHash]);
          $aReceitas[$sHash]->aMetas->calcularMetas();

        }
        $i++;
      }
    } else if ($iTipo == 3) {

      /**
       * Verificamos se nao existe nenhuma receita com valores negativos.
       */
      $aReceitasNegativas = $this->getReceitasComValorNegativa('', '', true);
      if (count($aReceitasNegativas) > 0) {

        $sMsg  = "Existem receitas com valores em alguns meses inconsistentes.\n";
        $sMsg .= 'Para corrigir esse problema, você poderá acessar a rotina ';
        $sMsg .= 'Orcamento > Procedimentos > Prog. Financeira e Cronog. de Desembolso > Metas da Receita > ';
        $sMsg .= 'Configuração das Metas.';
        throw new Exception($sMsg);
      }
      $aDespesas = $this->getDespesas();
      $iNumRows  = count($aDespesas);
      $oDaoCronogramaDespesa = db_utils::getDao("cronogramaperspectivadespesa");
      $i = 0;
      foreach ($aDespesas as $oDespesa) {

        /**
         * Verificamos se nao existe a despesa na perspectiva
         */
        $sWhere  = "o130_cronogramaperspectiva = {$this->iPerspectiva}";
        $sWhere .= " and o130_coddot = {$oDespesa->dotacao}";
        $sWhere .= " and o130_anousu = {$this->getAno()}";

        $sSqlVerificaDespesa = $oDaoCronogramaDespesa->sql_query_file(null,"*", null, $sWhere);
        $rsVerificaDespesa   = $oDaoCronogramaDespesa->sql_record($sSqlVerificaDespesa);
        if ($oDaoCronogramaDespesa->numrows > 0) {
          $aDespesas[$oDespesa->dotacao]->iSequencial = db_utils::fieldsMemory($rsVerificaDespesa, 0)->o130_sequencial;
        } else {

          $oDaoCronogramaDespesa->o130_anousu                = $this->getAno();
          $oDaoCronogramaDespesa->o130_coddot                = $oDespesa->dotacao;
          $oDaoCronogramaDespesa->o130_cronogramaperspectiva = $oDespesa->iPerspectiva;
          $oDaoCronogramaDespesa->incluir(null);
          if ($oDaoCronogramaDespesa->erro_status == 0) {

            $oStdMensagem      = new stdClass();
            $oStdMensagem->msg = $oDaoCronogramaDespesa->erro_msg;
            throw new Exception(_M(self::MENSAGENS . "erro_inclusao_despesa", $oStdMensagem));
          }

          $aDespesas[$oDespesa->dotacao]->iSequencial = $oDaoCronogramaDespesa->o130_sequencial;

        }

        require_once("model/cronogramaMetaDespesa.model.php");
        $aDespesas[$oDespesa->dotacao]->aMetas      = new cronogramaMetaDespesa($aDespesas[$oDespesa->dotacao], null, $this);
        $aDespesas[$oDespesa->dotacao]->aMetas->calcularMetas();
      }
    }
  }

  /**
   * Retorna as bases calculadas da Receita
   *
   * @param  string $sStrutural
   * @param integer $iRecurso
   * @return array
   * @throws Exception
   */
  public function getBaseReceitas($sStrutural= '', $iRecurso = '') {

    require_once("model/cronogramaBaseReceita.model.php");

    $sWhere  = "  o57_anousu = {$this->getAno()} ";
    if (trim($sStrutural) != "") {
      $sWhere .= " and o57_fonte ilike '{$sStrutural}%'";
    }

    if (!empty($iRecurso)) {
      $sWhere .= " and o70_codigo = {$iRecurso}";
    }

    $aReceitas       = array();
    $sInstituicoes   = implode(",", $this->getInstituicoes());
    $oDaoOrcReceita  = db_utils::getDao("cronogramaperspectivareceita");
    $sListaCampos  = "o57_fonte,          ";
    $sListaCampos .= "o57_descr,          " ;
    $sListaCampos .= "o57_codfon,        " ;
    $sListaCampos .= "o70_instit,         " ;
    $sListaCampos .= "o70_anousu,         " ;
    $sListaCampos .= "o70_valor,         " ;
    $sListaCampos .= "o70_concarpeculiar, " ;
    $sListaCampos .= "o126_sequencial, " ;
    $sListaCampos .= "o70_codrec,";
    $sListaCampos .= "fc_conplano_grupo(o57_anousu,substring(o57_fonte,1,1)||'%','9000') as deducao";
    $sSqlReceita  = $oDaoOrcReceita->sql_query_receita(null,$sListaCampos, "o57_fonte,o70_concarpeculiar",$sWhere);
    $rsReceita    = $oDaoOrcReceita->sql_record($sSqlReceita);
    if ($oDaoOrcReceita->numrows == 0) {

      $oStdMensagem      = new stdClass();
      $oStdMensagem->ano = $this->getAno();
      throw new Exception(_M(self::MENSAGENS . "erro_sem_receitas_ano", $oStdMensagem));
    }
    $aEstruturaisPai    = array();
    $aReceitasDesdobrar = array();
    for ($i = 0; $i < $oDaoOrcReceita->numrows; $i++) {

      $oReceita                 = db_utils::fieldsMemory($rsReceita, $i,false,false,true);
      /**
       * Verificamos se a conta possui desobramento no ano
       */
      $oReceita->analitica       = true;
      $oReceita->iPerspectiva    = $this->getPerspectiva();
      $oReceita->iSequencial     = null;
      $oReceita->desdobra        = false;
      $oReceita->aDesdobramentos = array();
      $oReceita->aReceitas       = array();
      $oReceita->aIndices        = array();
      if ($oReceita->o70_codrec == "") {

        $oReceita->o70_valor  = 0;
        $oReceita->analitica  = false;
        $oReceita->valormedia = 0;
        $oReceita->aBases     = new stdClass();
        for ($iMes = 1; $iMes <= 12; $iMes++) {

          $oDaoMes                   = new stdClass();
          $oDaoMes->sequencial       = null;
          $oDaoMes->percentual       = 0;
          $oDaoMes->valor            = 0;
          $oDaoMes->valormedia       = 0;
          $oDaoMes->mes              = $iMes;
          $oReceita->aBases->dados[] = $oDaoMes;
          $aReceitas[$i]             = $oReceita;

        }

        $aEstruturaisPai[$oReceita->o57_fonte] = $i;
      } else if ($oReceita->o126_sequencial != "") {

        $aReceitas[$i]                = $oReceita;
        $aReceitas[$i]->iSequencial   = $oReceita->o126_sequencial;
        $aReceitas[$i]->aBases        = new stdClass();
        $aReceitas[$i]->valormedia    = 0;
        $aReceitas[$i]->aBases->dados = array();
        $aReceitas[$i]->aBases        = new cronogramaBaseReceita($aReceitas[$i]);
        $aReceitas[$i]->valormedia    = $aReceitas[$i]->aBases->getValorMedia();
        $aReceitas[$i]->aBases->dados = $aReceitas[$i]->aBases->getDadosBase();
        if ($aReceitas[$i]->desdobra) {

         if (isset($aReceitas[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]])) {

            $aReceitas[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]]->aDesdobramentos[] = $oReceita->o126_sequencial;
            $aReceitas[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]]->aReceitas[]       = $oReceita->o70_codrec;
          }
          $aReceitas[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]];
          $aReceitas[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]]->valormedia += $aReceitas[$i]->valormedia;

          $iIndiceReceita   =  $aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)];
          for ($iMes = 0; $iMes < count($aReceitas[$i]->aBases->dados); $iMes++) {
             $aReceitas[$iIndiceReceita]->aBases->dados[$iMes]->valor     += $aReceitas[$i]->aBases->dados[$iMes]->valor;
             $aReceitas[$iIndiceReceita]->aBases->dados[$iMes]->percentual = $aReceitas[$i]->aBases->dados[$iMes]->percentual;
          }
        }
      }
    }
    $iIndice          = 0;
    $aReceitasRetorno = array();
    $aEstruturaisPai  = array();
    foreach ($aReceitas as $oReceita) {

      if ($oReceita->o70_codrec == "" && count($oReceita->aDesdobramentos) == 0){
        continue;
      } else {

        $aReceitasRetorno[$iIndice] = clone $oReceita;
        /*
         * A receita possui desdobramentos. indicamos ela como uma conta sintetica (incluimos no array aEstruturalPai),
         * e vinculamos seu indice no array das receitas, para poder indicar quais sao os desbrametnos que essa receita
         * possui.
         */
        if (count($oReceita->aDesdobramentos) > 0) {
          $aEstruturaisPai[$oReceita->o57_fonte] = $iIndice;
        }
        if (isset($aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)])) {
          if (isset($aReceitasRetorno[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]])) {
            $aReceitasRetorno[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]]->aIndices[] = $iIndice;
          }
        }
        $iIndice++;
      }
    }

    unset($aReceitas);
    return $aReceitasRetorno;
  }

  /**
   * Retorna as metas de receita Cadastradas
   *
   * @param string $sStrutural codigo estrutural da receita
   * @param mixed $mRecurso array ou inteiro com o codigo da receita
   * @return array com metas cadastradas
   */
  function getMetasReceita($sStrutural= '', $mRecurso = '') {

    require_once("model/cronogramaMetaReceita.model.php");

    $sInstituicoes   = implode(",", $this->getInstituicoes());

    $sWhere  = "o57_anousu = {$this->getAno()} ";
    if ($sInstituicoes != "") {

      $sWhere .= " and (o70_instit in({$sInstituicoes}) or o70_instit is null)";
    }
    if (trim($sStrutural) != "") {
      $sWhere .= " and o57_fonte ilike '{$sStrutural}%'";
    }

    if (!empty($mRecurso)) {

      if (is_array($mRecurso) && count($mRecurso) > 0) {
        $sWhere .= " and o70_codigo in (".implode(",", $mRecurso).")";
      } else if (is_integer($mRecurso)) {
        $sWhere .= " and o70_codigo = {$mRecurso}";
      }
    }

    $aReceitas       = array();
    $sInstituicoes   = implode(",", $this->getInstituicoes());
    $oDaoOrcReceita  = db_utils::getDao("cronogramaperspectivareceita");
    $sListaCampos  = "o57_fonte,          ";
    $sListaCampos .= "o57_descr,          " ;
    $sListaCampos .= "o70_instit,         " ;
    $sListaCampos .= "o70_anousu,         " ;
    $sListaCampos .= "o70_valor,          " ;
    $sListaCampos .= "o60_codfon,         " ;
    $sListaCampos .= "o57_codfon,         " ;
    $sListaCampos .= "coalesce(o60_perc, 0) as o60_perc, " ;
    $sListaCampos .= "o70_concarpeculiar, " ;
    $sListaCampos .= "o70_codigo, " ;
    $sListaCampos .= "o126_sequencial,    " ;
    $sListaCampos .= "o15_descr,    " ;
    $sListaCampos .= "fc_conplano_grupo(o57_anousu,substring(o57_fonte,1,1)||'%','9000') as deducao,";
    $sListaCampos .= "o70_codrec" ;
    $sSqlReceita  = $oDaoOrcReceita->sql_query_receitaplano(null,$sListaCampos, "o57_fonte,o70_concarpeculiar",$sWhere,
                                                            $this->getPerspectiva());
    $rsReceita    = $oDaoOrcReceita->sql_record($sSqlReceita);
    $aEstruturaisPai    = array();
    $aReceitasDesdobrar = array();
    if ( $oDaoOrcReceita->numrows > 0) {

      for ($i = 0; $i < $oDaoOrcReceita->numrows; $i++) {

        $oReceita                 = db_utils::fieldsMemory($rsReceita, $i,false,false,true);
        $oReceita->analitica       = true;
        $oReceita->iPerspectiva    = $this->getPerspectiva();
        $oReceita->iSequencial     = null;
        $oReceita->desdobra        = $oReceita->o60_codfon==''?false:true;
        $oReceita->aDesdobramentos = array();
        $oReceita->aReceitas       = array();
        $oReceita->aIndices        = array();
        if ($oReceita->o70_codrec == "") {

          $oReceita->o70_valor  = 0;
          $oReceita->analitica  = false;
          $oReceita->aBases     = new stdClass();
          for ($iMes = 1; $iMes <= 12; $iMes++) {

            $oDaoMes                   = new stdClass();
            $oDaoMes->sequencial       = null;
            $oDaoMes->percentual       = 0;
            $oDaoMes->valor            = 0;
            $oDaoMes->valormedia       = 0;
            $oDaoMes->mes              = $iMes;
            $oReceita->aMetas->dados[] = $oDaoMes;
            $aReceitas[$i]             = $oReceita;

          }
          $aEstruturaisPai[$oReceita->o57_fonte] = $i;
        } else if ($oReceita->o126_sequencial != "") {

          $oReceita->iPerspectiva   = $this->getPerspectiva();
          $oReceita->iSequencial     = null;
          $aReceitas[$i]                = $oReceita;
          $aReceitas[$i]->iSequencial   = $oReceita->o126_sequencial;
          $aReceitas[$i]->aMetas        = new cronogramaMetaReceita($aReceitas[$i]);
          $aReceitas[$i]->aMetas->dados = $aReceitas[$i]->aMetas->getMetas();
          if ($aReceitas[$i]->desdobra) {

            if (isset($aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)])) {
              if (isset($aReceitas[@$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]])) {

                $aReceitas[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]]->aDesdobramentos[] = $oReceita->o126_sequencial;
                $aReceitas[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]]->aReceitas[]       = $oReceita->o70_codrec;
              }
              $aReceitas[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]]->o70_valor += $aReceitas[$i]->o70_valor;

              $iIndiceReceita   =  $aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)];
              for ($iMes = 0; $iMes < count($aReceitas[$i]->aMetas->dados); $iMes++) {

                 $aReceitas[$iIndiceReceita]->aMetas->dados[$iMes]->valor      += $aReceitas[$i]->aMetas->dados[$iMes]->valor;
                 $aReceitas[$iIndiceReceita]->aMetas->dados[$iMes]->valormedia += $aReceitas[$i]->aMetas->dados[$iMes]->valormedia;
                 $aReceitas[$iIndiceReceita]->aMetas->dados[$iMes]->percentual  = $aReceitas[$i]->aMetas->dados[$iMes]->percentual;
              }
            }
          }
        }
      }
    }
    $iIndice          = 0;
    $aReceitasRetorno = array();
    $aEstruturaisPai  = array();
    foreach ($aReceitas as $oReceita) {

      if ($oReceita->o70_codrec == "" && count($oReceita->aDesdobramentos) == 0){
        continue;
      } else {

        $aReceitasRetorno[$iIndice] = clone $oReceita;
        /*
         * A receita possui desdobramentos. indicamos ela como uma conta sintetica (incluimos no array aEstruturalPai),
         * e vinculamos seu indice no array das receitas, para poder indicar quais sao os desbrametnos que essa receita
         * possui.
         */
        if (count($oReceita->aDesdobramentos) > 0) {
          $aEstruturaisPai[$oReceita->o57_fonte] = $iIndice;
        }
        if (isset($aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)])) {
          if (isset($aReceitasRetorno[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]])) {
            $aReceitasRetorno[$aEstruturaisPai[db_le_mae_rec($oReceita->o57_fonte)]]->aIndices[] = $iIndice;
          }
        }
        $iIndice++;
      }
    }

    unset($aReceitas);
    return $aReceitasRetorno;
  }

  private function getDespesas($sElemento='', $mRecurso = '') {

    $aDespesas       = array();
    $sInstituicoes   = implode(",", $this->getInstituicoes());
    $oDaoOrcDotacao  = db_utils::getDao("orcdotacao");
    $sWhere  = "o58_anousu = {$this->getAno()} ";
    $sWhere .= " and o58_instit in({$sInstituicoes})";
    if (trim($sElemento) != "") {
      $sWhere .= " and o56_elemento ilike '{$sElemento}%'";
    }
    if (!empty($mRecurso)) {

      if (is_array($mRecurso) && count($mRecurso) > 0) {
        $sWhere .= " and o58_codigo in (".implode(",", $mRecurso).")";
      } else if (is_integer($mRecurso)) {
        $sWhere .= " and o58_codigo = {$mRecurso}";
      }
    }

    $sListaCampos   = "o58_coddot as dotacao,";
    $sListaCampos  .= "o58_anousu as ano,";
    $sListaCampos  .= "o58_orgao as orgao,";
    $sListaCampos  .= "orcorgao.o40_descr as orgaodescr,";
    $sListaCampos  .= "o58_unidade as unidade,";
    $sListaCampos  .= "o41_descr as unidadedescr,";
    $sListaCampos  .= "o58_funcao as funcao,";
    $sListaCampos  .= "o52_descr as funcaodescr,";
    $sListaCampos  .= "o58_subfuncao as subfuncao,";
    $sListaCampos  .= "o53_descr as subfuncaodescr,";
    $sListaCampos  .= "o58_programa as programa,";
    $sListaCampos  .= "o54_descr as programadescr,";
    $sListaCampos  .= "o58_projativ as projativ,";
    $sListaCampos  .= "o55_descr  as projativdescr,";
    $sListaCampos  .= "o58_codele as elemento,";
    $sListaCampos  .= "o56_descr as elementodescr,";
    $sListaCampos  .= "o58_codigo as recurso,";
    $sListaCampos  .= "o15_descr as recursodescr,";
    $sListaCampos  .= "o58_valor as valororcado,";
    $sListaCampos  .= "o58_localizadorgastos as localizadorgastos,";
    $sListaCampos  .= "o58_coddot as lista_dotacoes_grupo";

    $sOrderBy   = "o58_anousu,";
    $sOrderBy  .= "o58_orgao,";
    $sOrderBy  .= "o58_unidade,";
    $sOrderBy  .= "o58_funcao ,";
    $sOrderBy  .= "o58_subfuncao,";
    $sOrderBy  .= "o58_programa,";
    $sOrderBy  .= "o58_projativ,";
    $sOrderBy  .= "o58_codele,";
    $sOrderBy  .= "o58_codigo,";
    $sOrderBy  .= "o58_localizadorgastos";

    $sSqldotacao = $oDaoOrcDotacao->sql_query(null,null, $sListaCampos, $sOrderBy ." ", $sWhere);
    $rsDotacao   = $oDaoOrcDotacao->sql_record($sSqldotacao);
    if ($oDaoOrcDotacao->numrows == 0) {

      $oStdMensagem      = new stdClass();
      $oStdMensagem->ano = $this->getAno();
      throw new Exception(_M(self::MENSAGENS . "erro_sem_dotacoes_ano", $oStdMensagem));
    }

    for ($i = 0; $i < $oDaoOrcDotacao->numrows; $i++) {

      $oDotacao                 = db_utils::fieldsMemory($rsDotacao, $i,false,false,true);
      $oDotacao->iPerspectiva   = $this->getPerspectiva();
      $oDotacao->iSequencial    = null;

      $aDespesas[$oDotacao->dotacao] = $oDotacao;

    }
    return $aDespesas;
  }

  function getMetasDespesa($iAgrupar, $sFiltros = '') {

    $oFields = new stdClass();
    $oFields->sCampos = "";
    $oFields->sOrder  = "";

    switch ($iAgrupar) {

    case 1:

        $oFields->sCampos = "o58_orgao as codigo, trim(orcorgao.o40_descr) as descricao";
        $oFields->sOrder  = "1, 2";
        break;

      case 2:

        $oFields->sCampos = "o58_orgao,o58_unidade as codigo, trim(o41_descr) as descricao";
        $oFields->sOrder  = "1,2,3";
        break;

      case 3:

        $oFields->sCampos = "o58_funcao as codigo,trim(o52_descr) as descricao";
        $oFields->sOrder  = "1, 2";
        break;

      case 4:

        $oFields->sCampos = "o58_subfuncao as codigo, trim(o53_descr) as descricao";
        $oFields->sOrder = "1, 2";
        break;

      case 5:

        $oFields->sCampos = "o58_programa as codigo, trim(o54_descr) as descricao";
        $oFields->sOrder  = "1, 2";
        break;

      case 6:

        $oFields->sCampos = "o58_projativ as codigo, trim(o55_descr)  as descricao";
        $oFields->sOrder  = "1, 2";
        break;

      case 7:

        $oFields->sCampos = "o58_codele as codigo, o56_descr as descricao";
        $oFields->sOrder = "1, 2";
        break;

      case 8:

        $oFields->sCampos = "o58_codigo as codigo, o15_descr as descricao";
        $oFields->sOrder  = "1,2";
        break;

      case 9:

        $oFields->sCampos = "null as codigo, o58_orgao, o58_unidade, o58_codigo, o58_localizadorgastos, o15_descr, o11_descricao ";
        $oFields->sOrder  = "1,2,3,4, 5,6, 7";
        break;

      case 99:

        $oFields->sCampos  = "o58_orgao, o58_unidade, o58_funcao, o58_subfuncao, o58_programa, o58_projativ,";
        $oFields->sCampos .= "o58_codele, o58_codigo";
        $oFields->sOrder  = $oFields->sCampos;
        break;
     default:

       $oStdMensagem          = new stdClass();
       $oStdMensagem->agrupar = $iAgrupar;
       throw new Exception(_M(self::MENSAGENS . "erro_nivel_nao_definido", $oStdMensagem));
    }

    $aDespesas       = array();
    $sInstituicoes   = implode(",", $this->getInstituicoes());
    $oDaoOrcDotacao  = db_utils::getDao("orcdotacao");
    require_once("libs/db_liborcamento.php");
    $oSelDotacao = new cl_selorcdotacao();
    $sWhere      = "o58_anousu = {$this->getAno()} ";
    $oSelDotacao->setDados($sFiltros); // passa os parametros vindos da func_selorcdotacao_abas.php
    $sWhere     .= " and ".$oSelDotacao->getDados(false);
    $sWhere     .= " and o58_instit in({$sInstituicoes})";

    if ($this->getTipo() == cronogramaFinanceiro::TIPO_ACOMPANHAMENTO) {
      $sWhere .= " and o58_valor > 0";
    }

    $sCampos     = "{$oFields->sCampos}, sum(o58_valor) as valororcado, array_to_string(array_accum(distinct o58_coddot), ',') as lista_dotacoes_grupo";
    $sSqldotacao = $oDaoOrcDotacao->sql_query(null,null,
                                               $sCampos,
                                               $oFields->sOrder ."",
                                               $sWhere." group by {$oFields->sOrder}",
                                               $this->iPerspectiva
                                             );


    $rsDotacao   = $oDaoOrcDotacao->sql_record($sSqldotacao);
    if ($oDaoOrcDotacao->numrows > 0) {

       for ($i = 0; $i < $oDaoOrcDotacao->numrows; $i++) {

          $aDespesas[$i] = db_utils::fieldsMemory($rsDotacao, $i, false, false, true);
          $aDespesas[$i]->iPerspectiva   = $this->getPerspectiva();
          $aDespesas[$i]->aMetas         = new cronogramaMetaDespesa($aDespesas[$i], $iAgrupar, $this);
          $aDespesas[$i]->aMetas->setInstituicoes($this->getInstituicoes());
          $aDespesas[$i]->aMetas->dados  = $aDespesas[$i]->aMetas->getMetas($sFiltros);

       }
    }
    return $aDespesas;
  }

  public function getReceitasComValorNegativa($iRecurso = '', $iGrupo = '9', $lReceitasNegativas = false) {

    $sWhere         = "";
    if (!empty($iRecurso)) {
      $sWhere .= " and o70_codigo = {$iRecurso} ";
    }

    if (!empty($iGrupo)) {
      $sWhere .= " and o57_fonte iLike '{$iGrupo}%'";
    }
    if ($lReceitasNegativas) {

      $sWhere .= " and  ( fc_conplano_grupo(o57_anousu,substring(o57_fonte,1,1)||'%','9000') is false and o127_valor < 0 ";
      $sWhere .= " or    fc_conplano_grupo(o57_anousu,substring(o57_fonte,1,1)||'%','9000') is true and o127_valor > 0 )";
    }
    $sSqlReceitas   = "SELECT distinct orcreceita.*,o57_fonte ,o57_descr, o126_sequencial";
    $sSqlReceitas  .= "  from orcreceita inner join orcfontes on o57_codfon = o70_codfon ";
    $sSqlReceitas  .= "                                      and o57_anousu = o70_anousu ";
    $sSqlReceitas  .= "       inner join cronogramaperspectivareceita on o70_codrec = o126_codrec ";
    $sSqlReceitas  .= "                                              and o70_anousu = o126_anousu ";
    $sSqlReceitas  .= "       inner join cronogramametareceita on o126_sequencial = o127_cronogramaperspectivareceita ";
    $sSqlReceitas  .= " where o126_cronogramaperspectiva = {$this->getPerspectiva()}";
    $sSqlReceitas  .= $sWhere;
    $rsReceitas     = db_query(analiseQueryPlanoOrcamento($sSqlReceitas));
    $aReceitas      = array();
    $iTotalReceitas = pg_num_rows($rsReceitas);
    for ($i = 0; $i < $iTotalReceitas; $i++) {

      $oReceita                     = db_utils::fieldsMemory($rsReceitas, $i, false, false, true);
      $oReceita->valormes           = round($oReceita->o70_valor/12);
      $oReceita->valordezembro      = $oReceita->valormes;
      $oReceita->percentual         = round($oReceita->valormes*100/$oReceita->o70_valor, 2);
      $oReceita->percentualdezembro = $oReceita->percentual;
      $nValorTotal                  = 0;
      $nValorPercentual             = 0;
      for ($iMes = 1; $iMes <= 12; $iMes++) {

        $nValorTotal      += $oReceita->valormes;
        $nValorPercentual += $oReceita->percentual;
      }
      $nDiferenca            = $oReceita->o70_valor - $nValorTotal;
      $nDiferencalPercentual = 100  - $nValorPercentual;

      $oReceita->valordezembro      += $nDiferenca;
      $oReceita->percentualdezembro += $nDiferencalPercentual;

      $aReceitas[] = $oReceita;

    }
    return $aReceitas;
  }

  function criaContaMae($string) {

    $string = db_formatar($string,"sistema");
    $iNivel = cronogramaFinanceiro::estruturalNivel($string);
    $stringnova = "";
    $aNiveis = explode(".", $string);
    for ($i = 0;  $i < $iNivel; $i++) {

      $stringnova .=  $aNiveis[$i];
    }
    return $stringnova;
  }

  public function estruturalNivel($sEstrutural) {

    $iNiveis = array();
    $iAux    = 1;
    $iNiveis = explode(".", $sEstrutural);
    $iLaco   = count($iNiveis);

    for ($i = 1; $i < $iLaco; $i++) {

      if ($iNiveis[$i] != 0 ) {
        $iAux = $i+1;
      }
    }
    return $iAux;
  }

  public function corrigeReceita ($iReceita, $nValorNormal, $nPercentual, $nValorAjuste, $nPercentualAjuste) {

    $sInstituicoes   = implode(",", $this->getInstituicoes());
    $oDaoOrcReceita  = db_utils::getDao("cronogramaperspectivareceita");
    $sListaCampos  = "o57_fonte,          ";
    $sListaCampos .= "o57_descr,          " ;
    $sListaCampos .= "o70_instit,         " ;
    $sListaCampos .= "o70_anousu,         " ;
    $sListaCampos .= "o70_valor,          " ;
    $sListaCampos .= "o60_codfon,         " ;
    $sListaCampos .= "o57_codfon,         " ;
    $sListaCampos .= "coalesce(o60_perc, 0) as o60_perc, " ;
    $sListaCampos .= "o70_concarpeculiar, " ;
    $sListaCampos .= "o70_codigo, " ;
    $sListaCampos .= "o126_sequencial,    " ;
    $sListaCampos .= "o15_descr,    " ;
    $sListaCampos .= "fc_conplano_grupo(o57_anousu,substring(o57_fonte,1,1)||'%','9000') as deducao,";
    $sListaCampos .= "o70_codrec" ;
    $sSqlReceita  = $oDaoOrcReceita->sql_query_receitaplano(null,
                                                            $sListaCampos,
                                                            null,
                                                            "o70_codrec = {$iReceita}
                                                            and o70_anousu = {$this->getAno()}",
                                                            $this->getPerspectiva()
                                                           );

    $rsReceita = db_query($sSqlReceita);
    if (pg_num_rows($rsReceita) != 1) {

      $oStdMensagem          = new stdClass();
      $oStdMensagem->receita = $iReceita;
      throw new Exception(_M(self::MENSAGENS . "erro_ajuste_receita", $oStdMensagem));
    }
    $oReceita = db_utils::fieldsMemory($rsReceita, 0);
    $oReceita->iSequencial    = $oReceita->o126_sequencial;
    $oReceita->iPerspectiva   = $this->getPerspectiva();
    $oReceita->aMetas         = new cronogramaMetaReceita($oReceita);
    $aMetas  =  $oReceita->aMetas->getMetas();
    foreach ($aMetas as $oMeta) {

      if ($oMeta->mes != 12) {
        $oReceita->aMetas->setValorMes($oMeta->mes, $nValorNormal);
        $aMetas[$oMeta->mes -1]->percentual  = $nPercentual;
      } else {
        $oReceita->aMetas->setValorMes($oMeta->mes, $nValorAjuste);
        $aMetas[$oMeta->mes-1]->percentual  = $nPercentualAjuste;
      }
    }
    $oReceita->aMetas->save();
  }

  /**
   * Retorna o tipo da perspectiva
   * @return int
   */
  public function getTipo() {
    return $this->iTipoPerspectiva;
  }

  /**
   * Verifica se a perspectiva possui acompanhamento
   * @return bool
   */
  public function temAcompanhamento() {

    $oDaoCronogramaPerspectivaAcompanhamento = new cl_cronogramaperspectivaacompanhamento();

    $sWhere                = "o151_cronogramaperspectivaorigem = {$this->iPerspectiva}";
    $sSqlTemAcompanhamento = $oDaoCronogramaPerspectivaAcompanhamento->sql_query_acompanhamento(null, '1', null, $sWhere);
    $oDaoCronogramaPerspectivaAcompanhamento->sql_record($sSqlTemAcompanhamento);
    return $oDaoCronogramaPerspectivaAcompanhamento->numrows > 0;
  }
}