<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2015 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Class AcompanhamentoCronograma
 */
class AcompanhamentoCronograma extends cronogramaFinanceiro {

  const MENSAGENS = 'financeiro.orcamento.AcompanhamentoCronograma.';
  /**
   * @var int
   */
  protected $iAcompanhamento;

  /**
   * @var int
   */
  protected $iAno;

  /**
   * @var DBDate
   */
  protected $oDataCadastro;

  /**
   * @var UsuarioSistema
   */
  protected $oUsuario;

  /**
   * @var int
   */
  protected $iMes;

  /**
   * @var string
   */
  protected $sDescricao;

  /**
   * @var cronogramaFinanceiro
   */
  protected $oCronogramaOrigem;

  function __construct($iPerspectiva = null) {

    if ($iPerspectiva != "") {

      $oDaoCronogramaPerspectiva = new cl_cronogramaperspectivaacompanhamento();
      $sWhere                    = "o124_sequencial = {$iPerspectiva}";
      $sSqlDadosCronograma       = $oDaoCronogramaPerspectiva->sql_query_acompanhamento(null, "*", null, $sWhere);
      $rsDadosCronograma         = $oDaoCronogramaPerspectiva->sql_record($sSqlDadosCronograma);

      if ($oDaoCronogramaPerspectiva->numrows == 0) {

        $oStdMensagem              = new stdClass();
        $oStdMensagem->perspectiva = $iPerspectiva;
        throw new BusinessException(_M(self::MENSAGENS . "erro_registro_nao_encontrado", $oStdMensagem));
      }

      $this->iPerspectiva = $iPerspectiva;
      $oDadosCronograma   = db_utils::fieldsMemory($rsDadosCronograma, 0);
      $this->sDescricao   = $oDadosCronograma->o124_descricao;
      $this->iAno         = $oDadosCronograma->o124_ano;
      $this->iPpaVersao   = $oDadosCronograma->o124_ppaversao;
      $this->iMes         = $oDadosCronograma->o151_mes;
      $this->setInstituicoes(array(db_getsession("DB_instit")));

    }
  }

  /**
   * @param int $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
  }

  /**
   * @param DBDate $oDataCadastro
   */
  public function setDataCadastro(DBDate $oDataCadastro) {
    $this->oDataCadastro = $oDataCadastro;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @param int $iMes
   */
  public function setMes($iMes) {
    $this->iMes = $iMes;
  }

  /**
   * @param UsuarioSistema $oUsuario
   */
  public function setUsuario(UsuarioSistema $oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * Incluimos os dados da Abertura do acompanhamento
   * @param cronogramaFinanceiro $oCronograma
   * @throws BusinessException
   * @throws Exception
   */
  public function incluirAbertura(cronogramaFinanceiro $oCronograma) {

    $this->oCronogramaOrigem = $oCronograma;
    $this->setInstituicoes($this->oCronogramaOrigem->getInstituicoes());

    $this->salvar();
    $this->salvarAcompanhamento();

    $this->processarAberturaReceita();
    $this->processarAberturaDespesa();

  }

  /**
   * Persiste os dados do Acompanhamento
   * @return bool
   * @throws BusinessException
   * @throws ParameterException
   */
  public function salvar() {

    if (empty($this->iPerspectiva)) {

      $oDaoConogramaPerspectiva                   = new cl_cronogramaperspectiva();
      $oDaoConogramaPerspectiva->o124_descricao   = $this->sDescricao;
      $oDaoConogramaPerspectiva->o124_ppaversao   = $this->oCronogramaOrigem->getPpaVersao();
      $oDaoConogramaPerspectiva->o124_idusuario   = $this->oUsuario->getCodigo();
      $oDaoConogramaPerspectiva->o124_situacao    = 2;//Não está sendo usado, manter padrão 2 - Homologado.
      $oDaoConogramaPerspectiva->o124_tipo        = self::TIPO_ACOMPANHAMENTO;
      $oDaoConogramaPerspectiva->o124_ano         = $this->iAno;
      $oDaoConogramaPerspectiva->o124_datacriacao = $this->oDataCadastro->convertTo(DBDate::DATA_EN);

      $lResultado         = $oDaoConogramaPerspectiva->incluir(null);
      $this->iPerspectiva = $oDaoConogramaPerspectiva->o124_sequencial;
      if (!$lResultado) {

        $oStdMensagem      = new stdClass();
        $oStdMensagem->msg = $oDaoConogramaPerspectiva->erro_msg;
        throw new BusinessException(_M(self::MENSAGENS . "erro_inclusao_acompanhamento", $oStdMensagem));
      }
      return $lResultado;
    }
    return true;
  }

  /**
   * @throws BusinessException
   */
  private function processarAberturaDespesa() {

    $oDaoOrcDotacao = new cl_orcdotacao;
    $sSqlDotacoes   = $oDaoOrcDotacao->sql_query_file(null, null, "*", null,
      "o58_anousu = {$this->oCronogramaOrigem->getAno()}"
    );

    $oDaoCronogramaDespesa = new cl_cronogramaperspectivadespesa();
    $oDaoCronogramaMetaDespesa = new cl_cronogramametadespesa();
    $rsDotacoes = db_query($sSqlDotacoes);
    if (!$rsDotacoes) {
      throw new BusinessException(_M(self::MENSAGENS . "erro_busca_despesa_cronograma"));
    }

    $iTotalDespesas = pg_num_rows($rsDotacoes);
    for ($iDespesa = 0; $iDespesa < $iTotalDespesas; $iDespesa++) {

      $oDadosDespesa = db_utils::fieldsMemory($rsDotacoes, $iDespesa);
      $oDaoCronogramaDespesa->o130_anousu                = $oDadosDespesa->o58_anousu;
      $oDaoCronogramaDespesa->o130_coddot                = $oDadosDespesa->o58_coddot;
      $oDaoCronogramaDespesa->o130_cronogramaperspectiva = $this->getPerspectiva();
      if (!$oDaoCronogramaDespesa->incluir(null)) {
        throw new BusinessException(_M(self::MENSAGENS . "erro_inclusao_despesa_cronograma"));
      }

      $sWhere  = "o130_coddot = {$oDadosDespesa->o58_coddot} and o130_anousu = {$oDadosDespesa->o58_anousu} ";
      $sWhere .= " and o130_cronogramaperspectiva = {$this->oCronogramaOrigem->getPerspectiva()} and o131_mes is not null";
      $sCampos = "o131_mes, o131_percentual, o131_valor";
      $sOrder  = "o131_mes";
      $sSqlMetasDaDespesa = $oDaoCronogramaDespesa->sql_query_metas_despesa($sCampos, $sWhere, $sOrder);
      $rsMetasDaDespesa   = db_query($sSqlMetasDaDespesa);
      $iTotalMetasDespesa = pg_num_rows($rsMetasDaDespesa);
      for ($iMetaDespesa = 0; $iMetaDespesa < $iTotalMetasDespesa; $iMetaDespesa++) {

        $oDadosMetaDespesa                                            = db_utils::fieldsMemory($rsMetasDaDespesa, $iMetaDespesa);
        $oDaoCronogramaMetaDespesa->o131_mes                          = $oDadosMetaDespesa->o131_mes;
        $oDaoCronogramaMetaDespesa->o131_cronogramaperspectivadespesa = $oDaoCronogramaDespesa->o130_sequencial;
        $oDaoCronogramaMetaDespesa->o131_percentual                   = $oDadosMetaDespesa->o131_percentual;
        $oDaoCronogramaMetaDespesa->o131_valor                        = $oDadosMetaDespesa->o131_valor;
        if (!$oDaoCronogramaMetaDespesa->incluir(null)) {
          throw new BusinessException(_M(self::MENSAGENS . "erro_inclusao_meta_despesa_cronograma"));
        }
      }
    }
  }

  /**
   * Processa a abertura dos dados da receita.
   * @throws BusinessException
   * @throws Exception
   */
  private function processarAberturaReceita() {

    $this->aReceitas           = $this->oCronogramaOrigem->getReceitas();
    $oDaoCronogramaReceita     = new cl_cronogramaperspectivareceita();
    $oDaoCronogramaMetaReceita = new cl_cronogramametareceita();
    foreach ($this->aReceitas as $oReceita) {

      $oDaoCronogramaReceita->o126_codrec = $oReceita->o70_codrec;
      $oDaoCronogramaReceita->o126_anousu = $oReceita->o70_anousu;
      $oDaoCronogramaReceita->o126_cronogramaperspectiva = $this->getPerspectiva();
      if (!$oDaoCronogramaReceita->incluir(null)) {

        $oStdMensagem         = new stdClass();
        $oStdMensagem->codrec = $oReceita->o70_codrec;
        $oStdMensagem->fonte  = $oReceita->o57_fonte;
        throw new BusinessException(_M(self::MENSAGENS . "erro_inclusao_receita_cronograma", $oStdMensagem));
      }

      $sWhere  = "o126_codrec = {$oReceita->o70_codrec} and o126_anousu = {$oReceita->o70_anousu} ";
      $sWhere .= " and o126_cronogramaperspectiva = {$this->oCronogramaOrigem->getPerspectiva()} and o127_mes is not null";
      $sCampos = "o127_mes, o127_percentual, o127_valor";
      $sOrder  = "o127_mes";
      $sSqlBuscaMetasDaReceita = $oDaoCronogramaReceita->sql_query_metas_receita($sCampos, $sWhere, $sOrder);
      $rsBuscaMetasDaReceita   = db_query($sSqlBuscaMetasDaReceita);
      if (!$rsBuscaMetasDaReceita) {

        $oStdMensagem         = new stdClass();
        $oStdMensagem->codrec = $oReceita->o70_codrec;
        $oStdMensagem->fonte  = $oReceita->o57_fonte;
        throw new BusinessException(_M(self::MENSAGENS . "erro_busca_meta_receita", $oStdMensagem));
      }
      $iTotalMetas = pg_num_rows($rsBuscaMetasDaReceita);
      for ($iReceita = 0; $iReceita < $iTotalMetas; $iReceita++) {

        $oDadosMeta = db_utils::fieldsMemory($rsBuscaMetasDaReceita, $iReceita);

        $oDaoCronogramaMetaReceita->o127_mes                          = $oDadosMeta->o127_mes;
        $oDaoCronogramaMetaReceita->o127_valor                        = "{$oDadosMeta->o127_valor}";
        $oDaoCronogramaMetaReceita->o127_percentual                   = "{$oDadosMeta->o127_percentual}";
        $oDaoCronogramaMetaReceita->o127_cronogramaperspectivareceita = $oDaoCronogramaReceita->o126_sequencial;
        if (!$oDaoCronogramaMetaReceita->incluir(null)) {

          $oStdMensagem         = new stdClass();
          $oStdMensagem->codrec = $oReceita->o70_codrec;
          $oStdMensagem->fonte  = $oReceita->o57_fonte;
          throw new BusinessException(_M(self::MENSAGENS . "erro_inclusao_meta_receita", $oStdMensagem));
        }
      }
    }
  }

  /**
   * Salva os dados do Acompanhamento
   * @return bool
   * @throws BusinessException
   */
  protected function salvarAcompanhamento() {

    $oDaoAcompanhamento                                   = new cl_cronogramaperspectivaacompanhamento();
    $oDaoAcompanhamento->o151_cronogramaperspectivaorigem = $this->oCronogramaOrigem->getPerspectiva();
    $oDaoAcompanhamento->o151_cronogramaperspectiva       = $this->iPerspectiva;
    $oDaoAcompanhamento->o151_mes                         = $this->iMes;

    $lResultado = $oDaoAcompanhamento->incluir(null);
    if (!$lResultado) {

      $oStdMensagem      = new stdClass();
      $oStdMensagem->msg = $oDaoAcompanhamento->erro_msg;
      throw new BusinessException(_M(self::MENSAGENS . "erro_inclusao_acompanhamento", $oStdMensagem));
    }
    return $lResultado;
  }
}