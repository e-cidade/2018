<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
namespace ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository;

use \ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro\Cabecalho as CabecalhoRegistro;

/**
 * Classe responsável pela manutenção aos dados de pontoeletronicoarquivo
 *
 * Class Cabecalho
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Cabecalho {

  /**
   * @var \cl_pontoeletronicoarquivo
   */
  private $oDao;

  /**
   * @var null|integer
   */
  private $iInstituicao;

  /**
   * Cabecalho constructor.
   */
  public function __construct() {

    $this->oDao         = new \cl_pontoeletronicoarquivo();
    $this->iInstituicao = db_getsession("DB_instit");
  }

  /**
   * Persiste os dados da tabela pontoeletronicoarquivo
   * @param CabecalhoRegistro $oCabecalho
   * @param Periodo $oPeriodo
   * @return CabecalhoRegistro
   * @throws \BusinessException
   * @throws \DBException
   */
  public function add(CabecalhoRegistro $oCabecalho, Periodo $oPeriodo) {

    $oLinha       = $oCabecalho->getLayoutLinha();
    $sDataInicial = str_replace('/', '', $oPeriodo->getDataInicio()->getDate(\DBDate::DATA_PTBR));
    $iOid         = null;

    if(!empty($oLinha)) {

      $iOid              = \DBLargeObject::criaOID(true);
      $lSalvaArquivo     = \DBLargeObject::escrita($oCabecalho->getArquivo(), $iOid);

      if(!$lSalvaArquivo) {
        throw new \DBException("Erro ao salvar o arquivo.");
      }

      $oDataInicialArquivo = new \DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_INICIAL));
      $oDataFinalArquivo   = new \DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_FINAL));

      if(!\DBDate::overlaps($oDataInicialArquivo, $oDataFinalArquivo, $oPeriodo->getDataInicio(), $oPeriodo->getDataFim())) {
        return null;
      }

      $sDataInicial = $oLinha->DATA_INICIAL;
    }

    $iSequencial = $this->getRegistroPontoEfetividade($oPeriodo);
    $sQuery      = $iSequencial == null ? "incluir" : "alterar";

    $this->oDao->rh196_efetividade_exercicio   = $oPeriodo->getExercicio();
    $this->oDao->rh196_efetividade_competencia = (string) $oPeriodo->getCompetencia();
    $this->oDao->rh196_instituicao             = $this->iInstituicao;
    $this->oDao->rh196_ano                     = substr($sDataInicial, 4);
    $this->oDao->rh196_mes                     = substr($sDataInicial, 2, 2);
    $this->oDao->rh196_sequencial              = $iSequencial;
    $this->oDao->rh196_arquivo                 = $iOid;

    $this->oDao->{$sQuery}($iSequencial);

    if($this->oDao->erro_status == "0") {
      throw new \BusinessException($this->oDao->erro_msg);
    }

    $oCabecalho->setCodigo($this->oDao->rh196_sequencial);

    return $oCabecalho;
  }

  /**
   * Verifica se já houve anteriormente uma importação para o mesmo exercício/competência
   * @param Periodo $oPeriodo
   * @return null|integer
   * @throws \DBException
   */
  private function getRegistroPontoEfetividade(Periodo $oPeriodo) {

    $sWherePontoEfetividade  = "     rh196_efetividade_exercicio   = {$oPeriodo->getExercicio()}";
    $sWherePontoEfetividade .= " AND rh196_efetividade_competencia = '{$oPeriodo->getCompetencia()}'";

    $sSqlPontoEfetividade = $this->oDao->sql_query_file(
      null,
      'rh196_sequencial',
      null,
      $sWherePontoEfetividade
    );

    $rsPontoEfetividade = db_query($sSqlPontoEfetividade);

    if(!$rsPontoEfetividade) {
      throw new \DBException("Erro ao buscar registro de arquivo importado para efetividade.");
    }

    if(pg_num_rows($rsPontoEfetividade) == 0) {
      return null;
    }

    return \db_utils::fieldsMemory($rsPontoEfetividade, 0)->rh196_sequencial;
  }
}