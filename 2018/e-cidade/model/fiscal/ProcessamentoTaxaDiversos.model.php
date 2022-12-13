<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBseller Servicos de Informatica
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
 * Class para processamento de uma taxa de diversos
 */
class ProcessamentoTaxaDiversos
{

  const CALCULO_GERAL      = 'G';
  const CALCULO_INDIVIDUAL = 'I';


  /**
   * Instância da classe
   */
  private static $instance;

  /**
   * Numpre para lançar diversos
   */
  private $numpre;

  /**
   * Construtor da classe
   */
  function __construct() {}

  /**
   * Define o numpre
   * @param Integer
   */
  public function setNumpre ($numpre) {
    $this->numpre = $numpre;
    return $this;
  }

  /**
   * Retorna o numpre
   * @return Integer
   */
  public function getNumpre () {
    return $this->numpre;
  }

  /**
   * Retorna a instância da classe
   * @return \ProcessamentoTaxaDiversos
   */
  public static function getInstance()
  {
    if(empty(self::$instance)) {
      self::$instance = new ProcessamentoTaxaDiversos;
    }

    return self::$instance;
  }

  /**
   * Lança um registro na tabela diversos e na tabela arrecad
   */
  public function lancarDiversos($oDados) {

    $oDiversos = $this->persisteTabelaDiversos($oDados);
    $this->persisteTabelaArrecad();
    $this->persistirTabelaDiversoslancamentos($oDados);

    if(!empty($oDados->inscricao_municipal)) {
      $this->persisteTabelaArreinscr($oDados);
    }

    return $oDiversos;
  }

  protected function persistirTabelaDiversoslancamentos($oDados) {

    $oDaoDiversosLancamentotaxa = new cl_diversoslancamentotaxa;
    $oDaoDiversosLancamentotaxa->dv14_diversos               = $oDados->codigo_diversos;
    $oDaoDiversosLancamentotaxa->dv14_lancamentotaxadiversos = $oDados->codigo_lancamento;
    $oDaoDiversosLancamentotaxa->dv14_data_calculo           = date('Y-m-d', db_getsession('DB_datausu'));

    if(isset($oDados->data_calculo_geral) && !empty($oDados->data_calculo_geral)) {
      $oDaoDiversosLancamentotaxa->dv14_data_calculo = $oDados->data_calculo_geral;
    }

    $oDaoDiversosLancamentotaxa->incluir(null);

    if($oDaoDiversosLancamentotaxa->erro_status == 0) {
      throw new DBException($oDaoDiversosLancamentotaxa->erro_msg);
    }
  }

  /**
   * Lança um registro na tabela diversos e na tabela arrecad
   * @param \StdClass
   * @return Boolean
   * @throws \DBException
   */
  protected function persisteTabelaDiversos($oDados) {

    if(empty($this->numpre)) {
      $this->gerarNumpre();
    }

    $oDaoDiversos = new cl_diversos();

    $oDaoDiversos->dv05_coddiver  = $oDados->codigo_diversos;
    $oDaoDiversos->dv05_numcgm    = $oDados->codigo_cgm;
    $oDaoDiversos->dv05_dtinsc    = $oDados->data_inscricao;
    $oDaoDiversos->dv05_exerc     = $oDados->exercicio;
    $oDaoDiversos->dv05_procdiver = $oDados->codigo_procedencia;
    $oDaoDiversos->dv05_privenc   = $oDados->data_primeiro_vencimento;
    $oDaoDiversos->dv05_vlrhis    = $oDados->valor_historico;
    $oDaoDiversos->dv05_valor     = $oDados->valor_corrigido;
    $oDaoDiversos->dv05_oper      = $oDados->data_operacao;
    $oDaoDiversos->dv05_numtot    = $oDados->total_parcelas;
    $oDaoDiversos->dv05_obs       = $oDados->observacao;
    $oDaoDiversos->dv05_provenc   = $oDados->data_proximo_vencimento;
    $oDaoDiversos->dv05_diaprox   = $oDados->dia_data_proximo_vencimento;
    $oDaoDiversos->dv05_numpre    = $this->numpre;
    $oDaoDiversos->dv05_instit    = $oDados->codigo_instituicao;

    if(empty($oDaoDiversos->dv05_coddiver)) {

      $oDaoDiversos->incluir(null);
      $oDados->codigo_diversos = $oDaoDiversos->dv05_coddiver;

    } else {
      $oDaoDiversos->alterar($oDaoDiversos->dv05_coddiver);
    }

    if($oDaoDiversos->erro_status == '0') {
      throw new DBException($oDaoDiversos->erro_msg);
    }

    return $oDaoDiversos;
  }

  /**
   * Gera um numpre para salvar na tabela diversos e na tabela arrecad
   * @throws \DBException
   * @throws \BusinessException
   */
  protected function gerarNumpre() {

    $rsNumpre = db_query("select nextval('numpref_k03_numpre_seq') as numpre");

    if(!$rsNumpre) {
      throw new DBException("Ocorreu um erro ao gerar numpre.");
    }

    if(pg_num_rows($rsNumpre) == 0) {
      throw new BusinessException("Não foi possível gerar o numpre.");
    }

    $this->numpre = db_utils::fieldsMemory($rsNumpre ,0)->numpre;
  }

  /**
   * Lanca na tabela arrecad um débito
   * @return Boolean
   * @throws \DBException
   * @throws \BusinessException
   */
  protected function persisteTabelaArrecad() {

    $rsArrecad = db_query("select fc_geraarrecad(7,{$this->numpre},true,2) as retorno");

    if(!$rsArrecad) {
      throw new DBException("Ocorreu um erro ao consultar a base de dados.");
    }

    if (pg_num_rows($rsArrecad) == 0 ) {
      throw new BussinessException(_M("tributario.diversos.db_frmdiversosalt.erro_geracao_diverso"));
    }

    $retorno  = db_utils::fieldsMemory($rsArrecad,0)->retorno;
    $iRetorno = substr(trim($retorno),0,1);

    if ($iRetorno != '9') {
      throw new BusinessException($retorno);
    }

    return true;
  }

  /**
   * Salva os dados na tabela arreinscr quando informada Inscrição Municipal
   * @param  stdClass $oDados
   * @throws DBException
   */
  protected function persisteTabelaArreinscr($oDados) {

    $oDaoArreinscr             = new cl_arreinscr();
    $oDaoArreinscr->k00_perc   = 100;
    $oDaoArreinscr->incluir($this->numpre, $oDados->inscricao_municipal);

    if($oDaoArreinscr->erro_status == '0') {
      throw new DBException('Erro ao salvar os dados da inscrição.');
    }
  }
}