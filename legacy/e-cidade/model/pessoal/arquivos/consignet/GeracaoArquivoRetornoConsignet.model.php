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

/**
 * GeracaoArquivoRetornoConsignet
 *
 * @package    Pessoal
 * @subpackage Arquivos/Consignet
  * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class GeracaoArquivoRetornoConsignet {

  private $oLayoutArquivo;
  private $oCompetencia;
  private $oArquivoMovimentacao;
  private $oInstituicao;
  private $sArquivo;

  const MENSAGEM      = 'recursoshumanos.pessoal.GeracaoArquivoRetornoConsignado.';
  const CODIGO_LAYOUT = 225;

  /**
   * @param Instituicao   $oInstituicao
   * @param DBCompetencia $oCompetencia
   * @throws DBException
   */
  public function __construct( Instituicao $oInstituicao, DBCompetencia $oCompetencia = null) {

    $this->oArquivoMovimentacao = ArquivoConsignetRepository::getUltimoArquivo($oInstituicao, $oCompetencia, true);
    $this->oArquivoMovimentacao->carregarRegistros();

    if ( $this->oArquivoMovimentacao->getCodigo() == null ){
      throw new BusinessException( _M(self::MENSAGEM . 'nenhum_arquivo'));
    }

    $this->oInstituicao         = $oInstituicao;
    $this->oCompetencia         = $oCompetencia;

    $sSufixoArquivo             = '_'. $this->oArquivoMovimentacao->getCompetencia()->getAno();
    $sSufixoArquivo            .= '_'. $this->oArquivoMovimentacao->getCompetencia()->getMes();
    $this->sArquivo             = "tmp/ArquivoRetornoConsignet{$sSufixoArquivo}.txt";
    $this->oLayoutArquivo       = new db_layouttxt(self::CODIGO_LAYOUT, $this->sArquivo );
  }

  /**
   * Processa os dados do consignet para gerar o arquivo de retorno
   */
  public function processar() {

    $aRegistros = $this->getRegistros();

    if (count($aRegistros) == 0) {
      throw new BusinessException( _M(self::MENSAGEM . 'nenhum_registro'));
    }

    foreach ($aRegistros as $oRegistro ) {
      $this->oLayoutArquivo->setByLineOfDBUtils($oRegistro,3);
    }
  }

  /**
   * Retorna os registros da consignet importados
   *
   * @return array
   * @throws BusinessException
   * @throws DBException
   */
  private function getRegistros() {

    $aRegistros = $this->oArquivoMovimentacao->getRegistros();
    $aRetorno   = array();
    $this->oArquivoMovimentacao->limparRegistros();

    foreach ( $aRegistros as $oRegistro ) {

      $oCalculo                    = $oRegistro->getServidor()->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
      $aEventos                    = $oCalculo->getEventosFinanceiros(null, $oRegistro->getRubric());

      /**
       *  Retornando o valor que estava no ponto
       */
      $nValor                      = 0;

      if ( count($aEventos) > 0 ) {
        $nValor = $aEventos[0]->getValor();
      }

      $nValor                      = number_format($nValor, 2, '.', '');

      $oRetorno                    = new stdClass();

      $oRetorno->matricula         = str_pad($oRegistro->getMatricula(), 10, "0", STR_PAD_LEFT);
      $oRetorno->cpf               = $oRegistro->getServidor()->hasCgm() ? $oRegistro->getServidor()->getCgm()->getCpf() : '' ;
      $oRetorno->nome_servidor     = $oRegistro->getNome();
      $oRetorno->estabelecimento   = $oRegistro->getArquivo()->getInstituicao()->getSequencial();
      $oRetorno->orgao             = $oRegistro->getArquivo()->getInstituicao()->getSequencial();
      $oRetorno->rubrica           = str_pad($oRegistro->getRubric(), 4, "0", STR_PAD_LEFT);
      $oRetorno->valor_previsto    = $oRegistro->getValorParcela();

      $oRetorno->valor_descontado  = $nValor;
      $oRetorno->competencia       = $oRegistro->getArquivo()->getCompetencia()->getMes();
      $oRetorno->competencia      .= $oRegistro->getArquivo()->getCompetencia()->getAno();
      $oRetorno->situacao          = $this->getDescricaoSituacao($oRegistro->getMotivo());

      $aRetorno[]                   = $oRetorno;
      $this->oArquivoMovimentacao->adicionarRegistro($oRegistro);
    }

    return $aRetorno;
  }

  /**
   * Retorna a descrição do motivo do consignet a partir do sequencial informado como parâmetro
   *
   * @param integer $iMotivo
   * @return mixed
   * @throws DBException
   */
  private function getDescricaoSituacao($iMotivo){

    if (empty($iMotivo)){
      return '';
    }

    $oDaoConsignadoMotivo = new cl_rhconsignadomotivo();
    $sSqlConsignadoMotivo = $oDaoConsignadoMotivo->sql_query($iMotivo, 'rh154_motivo');
    $rsConsignadoMotivo   = db_query($sSqlConsignadoMotivo);

    if (!$rsConsignadoMotivo) {
      throw new DBException(_M(self::MENSAGEM . 'erro_econsigmotivo'));
    }

    return db_utils::fieldsMemory($rsConsignadoMotivo, 0)->rh154_motivo;
  }

  /**
   * Retorna o caminho do arquivo
   * @return string
   */
  public function getCaminhoArquivo() {
    return $this->sArquivo;
  }
}
