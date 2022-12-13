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

namespace ECidade\Tributario\Agua\Coletor\Importacao;

use ECidade\Tributario\Agua\Coletor\Exportacao\Exportacao;

class Importacao {

  const CODIGO_LAYOUT = 276;

  /**
   * @var int
   */
  private $iCodigoExportacao;

  /**
   * @var int
   */
  private $sCaminhoArquivo;

  /**
   * @var int
   */
  private $iCodigoUsuario;

  /**
   * @param int $iCodigoUsuario
   */
  public function setCodigoUsuario($iCodigoUsuario) {
    $this->iCodigoUsuario = $iCodigoUsuario;
  }

  /**
   * @param int $iCodigoExportacao
   */
  public function setCodigoExportacao($iCodigoExportacao) {
    $this->iCodigoExportacao = $iCodigoExportacao;
  }

  /**
   * @param string $sCaminhoArquivo
   */
  public function setCaminhoArquivo($sCaminhoArquivo) {
    $this->sCaminhoArquivo = $sCaminhoArquivo;
  }

  /**
   * @return bool
   * @throws \DBException
   * @throws \ParameterException
   */
  public function processar() {

    if (!$this->iCodigoExportacao) {
      throw new \ParameterException('Código da exportação não informado.');
    }

    if (!$this->iCodigoUsuario) {
      throw new \ParameterException('Código do usuário não informado.');
    }

    if (!\db_utils::inTransaction()) {
      throw new \DBException('Sem transação iniciada.');
    }

    $oDaoColetorExportaDados = new \cl_aguacoletorexportadados;

    $sWhereContagemLinhas = "x50_aguacoletorexporta = {$this->iCodigoExportacao}";
    $sSqlContagemLinhas   = $oDaoColetorExportaDados->sql_query_file(null, 'x50_sequencial', null, $sWhereContagemLinhas);
    $rsContagemLinhas     = db_query($sSqlContagemLinhas);
    if (!$rsContagemLinhas) {
      throw new \DBException('Não foi possível consultar as informações da exportação.');
    }

    $fRegistro = function($aRegistro) {
      return (int) $aRegistro['x50_sequencial'];
    };
    $aLinhasExportacao = array_map($fRegistro, pg_fetch_all($rsContagemLinhas));
    $aLinhasArquivo = array();
    $oLayoutReader = new \DBLayoutReader(self::CODIGO_LAYOUT, $this->sCaminhoArquivo);
    foreach ($oLayoutReader->getLines() as $oLinha) {

      $sSqlExportacaoDado = $oDaoColetorExportaDados->sql_query_file($oLinha->codigo_exportacao);
      $rsExportacaoDado   = db_query($sSqlExportacaoDado);

      if (!$rsExportacaoDado || pg_num_rows($rsExportacaoDado) === 0) {
        throw new \DBException("Não foi possível buscar os dados da exportação.");
      }

      $oColetorExportaDados = pg_fetch_object($rsExportacaoDado, 0, 'cl_aguacoletorexportadados');
      $iCodigoLinhaExportacao = $oColetorExportaDados->x50_sequencial;

      $oColetorExportaDados->x50_sequencial              = null;
      $oColetorExportaDados->x50_aguacoletorexportadados = $iCodigoLinhaExportacao;
      $oColetorExportaDados->x50_contaimpressa           = $oLinha->conta_emitida;
      $oColetorExportaDados->x50_avisoleiturista         = $oLinha->observacao_leiturista;
      $oColetorExportaDados->x50_codigobarras            = $oLinha->codigo_barras;
      $oColetorExportaDados->x50_linhadigitavel          = $oLinha->linha_digitavel;
      $oColetorExportaDados->x50_mediadiaria             = $oLinha->media_consumo_dia;
      $oColetorExportaDados->x50_vencimento              = $oLinha->data_vencimento;
      $oColetorExportaDados->x50_valordesconto           = $oLinha->valor_desconto;
      $oColetorExportaDados->x50_valortotal              = $oLinha->valor_total;
      $oColetorExportaDados->x50_diasleitura             = $oLinha->dias_entre_leituras;
      $oColetorExportaDados->x50_dtleituraanterior       = $oLinha->data_leitura_anterior;
      $oColetorExportaDados->x50_dtleituraatual          = $oLinha->data_leitura_atual;
      $oColetorExportaDados->x50_consumo                 = $oLinha->consumo;
      $oColetorExportaDados->x50_leituracoletada         = $oLinha->leitura_coletada;
      $oColetorExportaDados->incluir(null);

      if ($oColetorExportaDados->erro_status == '0') {
        throw new \DBException('Não foi possível salvar os dados da importação.');
      }

      $aLinhasArquivo[] = (integer) $iCodigoLinhaExportacao;
      $this->atualizarLeitura($oLinha, $oColetorExportaDados);
    }

    $aDiff = array_diff($aLinhasArquivo, $aLinhasExportacao);
    if (count($aLinhasArquivo) !== count($aLinhasExportacao) || !empty($aDiff)) {

      $sMensagem = 'Quantidade ou valores de registros no arquivo não conferem com os registros da exportação.';
      throw new \DBException($sMensagem);
    }

    $this->atualizarExportacao();
    $this->inserirSituacaoExportacao('Importação de dados do coletor.');
  }

  private function atualizarLeitura($oLinha, $oColetorExportaDados) {

    /**
     * @todo: Reescrever query removendo os joins desnecessários.
     */
    $oDaoAguaLeituraExportacao = new \cl_aguacoletorexportadadosleitura;
    $sCamposLeitura            = 'agualeitura.*';
    $sWhereLeitura             = "x51_aguacoletorexportadados = {$oLinha->codigo_exportacao}";
    $sSqlAguaLeituraExportacao = $oDaoAguaLeituraExportacao->sql_query(null, $sCamposLeitura, null, $sWhereLeitura);
    $rsAguaLeituraExportacao   = db_query($sSqlAguaLeituraExportacao);

    if (!$rsAguaLeituraExportacao || pg_num_rows($rsAguaLeituraExportacao) === 0) {
      throw new \DBException('Não foi possível buscar os dados da leitura');
    }

    $sStatusLeitura = Exportacao::LEITURA_STATUS_ATIVO;
    if ($oLinha->leitura_coletada != '1') {
      $sStatusLeitura = Exportacao::LEITURA_STATUS_CANCELADO;
    }

    $oAguaLeitura = pg_fetch_object($rsAguaLeituraExportacao, 0, 'cl_agualeitura');
    $oAguaLeitura->x21_status        = $sStatusLeitura;
    $oAguaLeitura->x21_situacao      = $oLinha->situacao_leitura;
    $oAguaLeitura->x21_dtleitura     = $oLinha->data_leitura_atual;
    $oAguaLeitura->x21_leitura       = $oLinha->leitura;
    $oAguaLeitura->x21_consumo       = $oLinha->consumo;
    $oAguaLeitura->x21_virou         = $oLinha->hidrometro_virou == '1' ? true : false;
    $oAguaLeitura->x21_tipo          = Exportacao::LEITURA_TIPO_IMPORTADA;
    $oAguaLeitura->alterar($oAguaLeitura->x21_codleitura);

    if ($oAguaLeitura->erro_status == '0') {
      throw new \DBException('Não foi possível alterar a leitura.');
    }

    return true;
  }

  /**
   * @throws \DBException
   * @throws \ParameterException
   * @return bool
   */
  private function atualizarExportacao() {

    $oDaoAguaExportacao = new \cl_aguacoletorexporta;
    $sSqlAguaExportacao = $oDaoAguaExportacao->sql_query_file($this->iCodigoExportacao);
    $rsAguaExportacao   = db_query($sSqlAguaExportacao);

    if (!$rsAguaExportacao || pg_num_rows($rsAguaExportacao) == 0) {
      throw new \DBException('Não foi possível encontrar a exportação.');
    }

    $oAguaExportacao = pg_fetch_object($rsAguaExportacao, 0, 'cl_aguacoletorexporta');
    $oAguaExportacao->x49_situacao = Exportacao::EXPORTACAO_SITUACAO_IMPORTADA;
    $oAguaExportacao->alterar($oAguaExportacao->x49_sequencial);

    if ($oAguaExportacao->erro_status == '0') {
      throw new \DBException('Não foi possível salvar as inforamções de exportação.');
    }

    return true;
  }

  /**
   * @param string $sDescricao
   * @throws \DBException
   * @return bool
   */
  private function inserirSituacaoExportacao($sDescricao) {

    $oDaoAguaSituacaoExportacao = new \cl_aguacoletorexportasituacao;
    $oDataAtual = new \DateTime();

    $oDaoAguaSituacaoExportacao->x48_aguacoletorexporta = $this->iCodigoExportacao;
    $oDaoAguaSituacaoExportacao->x48_usuario  = $this->iCodigoUsuario;
    $oDaoAguaSituacaoExportacao->x48_data     = $oDataAtual->format('Y-m-d');
    $oDaoAguaSituacaoExportacao->x48_hora     = $oDataAtual->format('H:i');
    $oDaoAguaSituacaoExportacao->x48_motivo   = $sDescricao;
    $oDaoAguaSituacaoExportacao->x48_situacao = Exportacao::EXPORTACAO_SITUACAO_IMPORTADA;
    $oDaoAguaSituacaoExportacao->incluir(null);

    if ($oDaoAguaSituacaoExportacao->erro_status == '0') {
      throw new \DBException('Não foi possível salvar as informações de situação de exportação.');
    }

    return true;
  }

}
