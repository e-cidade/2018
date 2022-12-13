<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 * Classe responsável pelo processamento dos dados do preponto
 * para as suas respectivas tabelas de ponto
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package Pessoal
 *
 */
class ProcessamentoPonto{

  /**
   * Intituição
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Competência da Folha
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Arquivo de mensagen para tratamento de exceções
   */
  const MENSAGEM = 'recursoshumanos.pessoal.ProcessamentoPonto.';

  /**
   * Contrutor da classe, reponsavel por instanciar os atributos
   * oInstiuicao e oCompetencia com os dados da sessao.
   */
  public function __construct(){

    $iInstituicao = db_getsession('DB_instit');
    $this->oInstituicao = InstituicaoRepository::getInstituicaoByCodigo($iInstituicao);
    $this->oCompetencia = DBPessoal::getCompetenciaFolha();
  }

  /**
   * Realiza o processamento dos dados para as tabelas de ponto.
   * @return boolean
   */
  public function processaDadosPonto(){

    ProcessamentoPontoConsignados::importarPrePonto();
    $oDadosPonto = $this->getDadosPonto();

    if (!$oDadosPonto) {
      throw new BusinessException(_M(self::MENSAGEM . 'nenhum_dado_encontrado'));
    }

    foreach ($oDadosPonto as $oDadosRegistroPonto) {

      $oPonto      = $this->getPonto($oDadosRegistroPonto->rh149_tipofolha, $oDadosRegistroPonto->rh149_regist);
      $oPonto->carregarRegistros();
      $oPonto->limpar();
      $oRegistroPonto = $this->montaRegistroPonto($oDadosRegistroPonto);
      $oPonto->adicionarRegistro($oRegistroPonto, false);
      $oPonto->salvar();
    }

    $this->limparPrePonto();

    return true;
  }

  /**
   * Retorna os dados para o processamento do ponto.
   * Irá buscar todos os dados da tabela rhpreponto que estejam vinculados a instituição atual.
   * @return Object Objeto com os dados para processamento do ponto,
   */
  private function getDadosPonto(){

    $oDaoRhPrePonto    = new cl_rhpreponto();
    $sWhereRhPrePonto  = "rh149_instit = {$this->oInstituicao->getCodigo()}";
    $sCamposRhPrePonto = "rh149_instit, rh149_regist, rh149_rubric, rh149_valor, rh149_quantidade, rh149_tipofolha";
    $sSqlRhPrePonto    = $oDaoRhPrePonto->sql_query_file(null, "*", null, $sWhereRhPrePonto);
    $rsRhPrePonto      = db_query($sSqlRhPrePonto);

    if (!$rsRhPrePonto) {
      throw new DBException(_M(self::MENSAGEM . 'erro_preponto'));
    }

    if (pg_num_rows($rsRhPrePonto) == 0) {
      throw new BusinessException(_M(self::MENSAGEM . 'nenhum_dado_encontrado'));
    }

    return db_utils::getCollectionbyRecord($rsRhPrePonto);
  }

  /**
   * Retorna o Objeto da folha de pagamento de acordo com o tipo de folha informado por parâmetro
   * @param  integer $iTipoFolha
   * @param  integer $iMatricula
   * @return Folha
   */
  private function getPonto($iTipoFolha, $iMatricula) {

    $oServidor = ServidorRepository::getInstanciaByCodigo($iMatricula, $this->oCompetencia->getAno(), $this->oCompetencia->getMes(), $this->oInstituicao->getSequencial());

    switch ($iTipoFolha) {
      case FolhaPagamento::TIPO_FOLHA_SALARIO:
        return new PontoSalario($oServidor);
      break;

      /**
       * @todo Implementar o restante dos pontos, quando
       * for necessário a utilização dos mesmos.
       *
       * case FolhaPagamento::TIPO_FOLHA_RESCISAO:
       *   return new PontoRescisao($oServidor);
       * break;
       * case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
       *   return new PontoComplementar($oServidor);
       * break;
       * case FolhaPagamento::TIPO_FOLHA_ADIANTAMENTO:
       *   return new PontoAdiantamento($oServidor);
       * break;
       * case FolhaPagamento::TIPO_FOLHA_13o_SALARIO:
       *   return new Ponto13o($oServidor);
       * break;
       */
      default:
        throw new BusinessException(_M(self::MENSAGEM . 'ponto_nao_encontrado'));
      break;
    }
  }

  /**
   * Monta o Objeto Registro de Ponto com os dados passados por parâmetro.
   * @param  Object $oDadosRegistroPonto
   * @return RegistroPonto
   */
  private function montaRegistroPonto($oDadosRegistroPonto) {

    $oServidor      = ServidorRepository::getInstanciaByCodigo($oDadosRegistroPonto->rh149_regist, $this->oCompetencia->getAno(), $this->oCompetencia->getMes(), $this->oInstituicao->getSequencial());
    $oRubrica       = RubricaRepository::getInstanciaByCodigo($oDadosRegistroPonto->rh149_rubric, $this->oInstituicao->getSequencial());
    $oRegistroPonto = new RegistroPonto();

    $oRegistroPonto->setRubrica($oRubrica);
    $oRegistroPonto->setServidor($oServidor);
    $oRegistroPonto->setQuantidade($oDadosRegistroPonto->rh149_quantidade);
    $oRegistroPonto->setValor($oDadosRegistroPonto->rh149_valor);

    return $oRegistroPonto;
  }

  /**
   * Limpa os dados da tabela rhpreponto, esse metodo é chamado logo
   * após o processamneto de todos os dados para o ponto
   * @return Void
   */
  private function limparPrePonto(){

    $oDaoRhPrePonto  = new cl_rhpreponto();
    $sWhere          = "    rh149_instit = {$this->oInstituicao->getSequencial()}                              ";
    $sWhere         .= "and rh149_sequencial not in (select rh156_rhpreponto from rhprepontoloteregistroponto) ";
    $oDaoRhPrePonto->excluir(null, $sWhere);

    if ($oDaoRhPrePonto->erro_status == "0") {
      throw new DBException(_M(self::MENSAGEM . 'erro_excluir_preponto'));
    }

  }
}
