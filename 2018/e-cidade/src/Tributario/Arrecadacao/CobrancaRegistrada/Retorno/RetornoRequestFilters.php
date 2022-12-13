<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Retorno;

use \DBDate;

class RetornoRequestFilters
{
  const CODIGO_OCORRENCIA_TODOS = 0;
  const CODIGO_OCORRENCIA_ACEITO = 1;
  const CODIGO_OCORRENCIA_INCONSISTENTE = 2;

  private $oDataEmissaoInicio;

  private $oDataEmissaoFim;

  private $iCodigoConvenio;

  private $sConvenioDescricao;

  private $iCodigoTipoDebito;

  private $sTipoDebitoDescricao;

  private $iCodigoArrecadacao;

  private $iCodigoOcorrencia;

  private static $aOcorrencia = array(self::CODIGO_OCORRENCIA_TODOS => 'Todos',
                                      self::CODIGO_OCORRENCIA_ACEITO => 'Aceito',
                                      self::CODIGO_OCORRENCIA_INCONSISTENTE => 'Inconsistente');

  public function __construct(DBDate $oDataEmissaoInicio, DBDate $oDataEmissaoFim)
  {
    $this->oDataEmissaoInicio = $oDataEmissaoInicio;
    $this->oDataEmissaoFim = $oDataEmissaoFim;
  }

  public function getDataEmissaoInicio() {
    return $this->oDataEmissaoInicio;
  }

  public function getDataEmissaoFim() {
    return $this->oDataEmissaoFim;
  }

  public function getCodigoConvenio() {
    return $this->iCodigoConvenio;
  }

  public function getConvenioDescricao() {
    return $this->sConvenioDescricao;
  }

  public function getCodigoTipoDebito() {
    return $this->iCodigoTipoDebito;
  }

  public function getTipoDebitoDescricao() {
    return $this->sTipoDebitoDescricao;
  }

  public function getCodigoArrecadacao() {
    return $this->iCodigoArrecadacao;
  }

  public function getCodigoOcorrencia() {
    return $this->iCodigoOcorrencia;
  }

  public static function getOcorrencia() {
    return self::$aOcorrencia;
  }

  public static function getOcorrenciaDescricao($iOcorrencia) {
    return self::$aOcorrencia[$iOcorrencia];
  }

  public function setDataEmissaoInicio(DBDate $oDataEmissaoInicio) {
    $this->oDataEmissaoInicio = $oDataEmissaoInicio;
  }

  public function setDataEmissaoFim(DBDate $oDataEmissaoFim) {
    $this->oDataEmissaoFim = $oDataEmissaoFim;
  }

  public function setCodigoConvenio($iCodigoConvenio){
    $this->iCodigoConvenio = $iCodigoConvenio;
  }

  public function setConvenioDescricao($sConvenioDescricao) {
    $this->sConvenioDescricao = $sConvenioDescricao;
  }

  public function setCodigoTipoDebito($iCodigoTipoDebito){
    $this->iCodigoTipoDebito = $iCodigoTipoDebito;
  }

  public function setTipoDebitoDescricao($sTipoDebitoDescricao) {
    $this->sTipoDebitoDescricao = $sTipoDebitoDescricao;
  }

  public function setCodigoArrecadacao($iCodigoArrecadacao) {
    $this->iCodigoArrecadacao = $iCodigoArrecadacao;
  }

  public function setCodigoOcorrencia($iCodigoOcorrencia) {
    $this->iCodigoOcorrencia = $iCodigoOcorrencia;
  }
}
