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
 * Model para retornar configura��es do m�dulo CAIXA
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package caixa
 * @version $Revision: 1.5 $
 */
final class ParametroCaixa {

  const TIPO_TRANSMISSAO_CNAB240 = 1;
  const TIPO_TRANSMISSAO_OBN     = 2;

  const NOME_CONFIGURACAO_TIPO_TRANSMISSAO = 'tipo_transmissao';
  const NOME_CONFIGURACAO_CONVENIO_BANCO   = 'codigo_convenio_banco';

  const CAMINHO_ARQUIVO = 'config/financeiro/agenda_pagamento.ini';

  private $aConfiguracoes = array();

  /**
   * Retorna o c�digo do recurso configurado para FUNDEB configurado para a institui��o informada na sess�o. Caso a
   * institui��o n�o possua recurso configurado, ir� retornar 0 (zero)
   *
   * @param $iInstituicao
   *
   * @return int
   */
  public static function getCodigoRecursoFUNDEB($iInstituicao) {

    $oDaoCaiParametro   = db_utils::getDao('caiparametro');
    $sSqlBuscaParametro = $oDaoCaiParametro->sql_query_file($iInstituicao, "k29_orctiporecfundeb");
    $rsBuscaParametro   = $oDaoCaiParametro->sql_record($sSqlBuscaParametro);

    $iCodigoRecurso = 0;
    if ($oDaoCaiParametro->numrows == 1) {

      $iCodigoRecurso = db_utils::fieldsMemory($rsBuscaParametro, 0)->k29_orctiporecfundeb;
      if (empty($iCodigoRecurso)) {
        $iCodigoRecurso = 0;
      }
    }
    return $iCodigoRecurso;
  }

  /**
   * @return int
   */
  public function getTipoTransmissaoPadrao() {

    $iTipoTransmissao = self::getConfiguracao(self::NOME_CONFIGURACAO_TIPO_TRANSMISSAO);
    if (empty($iTipoTransmissao)) {
      $iTipoTransmissao = self::TIPO_TRANSMISSAO_CNAB240;
    }
    return (int) $iTipoTransmissao;
  }

  /**
   * @return string
   */
  public function getConvenioBanco() {
    return self::getConfiguracao(self::NOME_CONFIGURACAO_CONVENIO_BANCO);
  }

  /**
   * @param integer $iTipoTransmissao
   */
  public function setTipoTramissaoPadrao($iTipoTransmissao = self::TIPO_TRANSMISSAO_CNAB240) {
    $this->setConfiguracao(self::NOME_CONFIGURACAO_TIPO_TRANSMISSAO, $iTipoTransmissao);
  }

  /**
   * @param string $sConvenioBanco
   */
  public function setConvenioBanco($sConvenioBanco) {
    $this->setConfiguracao(self::NOME_CONFIGURACAO_CONVENIO_BANCO, $sConvenioBanco);
  }

  /**
   * Seta uma configura��o.
   * @param string $sCampo Nome da configura��o.
   * @param string $sValor Valor da Configura��o.
   */
  private function setConfiguracao($sCampo, $sValor) {

    $aConfiguracoes = $this->getConfiguracoes();
    $aConfiguracoes[$sCampo] = $sValor;
    $this->aConfiguracoes = $aConfiguracoes;
  }

  /**
   * Busca o valor de uma configura��o.
   * @param string $sNomeCampo
   *
   * @return array
   */
  private function getConfiguracao($sNomeCampo) {

    $aConfiguracoes = $this->getConfiguracoes();
    if (isset($aConfiguracoes[$sNomeCampo])) {
      return $aConfiguracoes[$sNomeCampo];
    }
    return null;
  }

  /**
   * Busca todas as configura��es.
   * @return array
   */
  private function getConfiguracoes() {

    if (empty($this->aConfiguracoes)) {

      if (file_exists(self::CAMINHO_ARQUIVO) && is_readable(self::CAMINHO_ARQUIVO)) {
        $this->aConfiguracoes = parse_ini_file(self::CAMINHO_ARQUIVO);
      }
    }
    return $this->aConfiguracoes;
  }

  /**
   * Salva as configura��es.
   * @return bool
   */
  public function salvar() {

    $sConteudo = '';
    foreach ($this->aConfiguracoes as $sNome => $sValor) {
      $sConteudo .= "{$sNome}={$sValor}\n";
    }
    $lAlterouArquivo = file_put_contents(self::CAMINHO_ARQUIVO, $sConteudo);
    if (!$lAlterouArquivo) {
      return false;
    }
    return true;
  }

}