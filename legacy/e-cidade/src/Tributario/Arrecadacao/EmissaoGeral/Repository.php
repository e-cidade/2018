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

namespace ECidade\Tributario\Arrecadacao\EmissaoGeral;

use ECidade\Tributario\Arrecadacao\EmissaoGeral\EmissaoGeral;

class Repository
{
  private $oDao;

  public function __construct()
  {
    $this->oDao = new \cl_emissaogeral();
  }

  /**
   * Persiste os dados da emissao
   * @param EmissaoGeral $oEmissao
   * @throws \Exception
   * @return EmissaoGeral
   */
  public function add(EmissaoGeral $oEmissao)
  {
    $oData = $oEmissao->getData();

    $this->oDao->tr01_sequencial  = null;
    $this->oDao->tr01_data        = !empty($oData) ? $oData->getDate() : '';
    $this->oDao->tr01_hora        = $oEmissao->getHora();
    $this->oDao->tr01_usuario     = $oEmissao->getUsuario();
    $this->oDao->tr01_tipoemissao = $oEmissao->getTipo();
    $this->oDao->tr01_instit      = $oEmissao->getInstituicao()->getCodigo();
    $this->oDao->tr01_convenio    = $oEmissao->getConvenio();
    $this->oDao->tr01_parametros  = \JSON::create()->stringify($oEmissao->getParametros());

    $this->oDao->incluir(null);

    if ($this->oDao->erro_status == '2') {
      throw new \Exception($this->oDao->erro_msg);
    }

    $oEmissao->setCodigo($this->oDao->tr01_sequencial);
    return $oEmissao;
  }

  /**
   * Busca a emissao geral
   *
   * @param integer $iCodigo
   * @return EmissaoGeral
   */
  public function getEmissao($iCodigo)
  {
    $sSqlEmissao = $this->oDao->sql_query_file($iCodigo);
    $rsEmissao   = \db_query($sSqlEmissao);

    if (!$rsEmissao || pg_num_rows($rsEmissao) == 0) {
      throw new \Exception("Erro ao buscar os dados da emissão geral.");
    }

    $oDadosEmissao = \db_utils::fieldsMemory($rsEmissao, 0);

    $oEmissaoGeral = new EmissaoGeral();
    $oEmissaoGeral->setCodigo($oDadosEmissao->tr01_sequencial);
    $oEmissaoGeral->setTipo($oDadosEmissao->tr01_tipoemissao);
    $oEmissaoGeral->setData(new \DBDate($oDadosEmissao->tr01_data));
    $oEmissaoGeral->setHora($oDadosEmissao->tr01_hora);
    $oEmissaoGeral->setInstituicao(new \Instituicao($oDadosEmissao->tr01_instit));
    $oEmissaoGeral->setConvenio($oDadosEmissao->tr01_convenio);
    $oEmissaoGeral->setUsuario($oDadosEmissao->tr01_usuario);
    $oEmissaoGeral->setParametros((object) \JSON::create()->parse($oDadosEmissao->tr01_parametros));

    return $oEmissaoGeral;
  }

  /**
   * Função que busca todas as emissões
   *
   * @throws \DBException
   * @param  integer       $iTipoEmissao
   * @return array
   */
  public function getEmissoesPorTipo( $iTipoEmissao )
  {
    $sSqlEmissao = $this->oDao->sql_query_file( null,
                                                "*",
                                                "tr01_sequencial desc",
                                                "tr01_tipoemissao = {$iTipoEmissao}" );
    $rsEmissao   = \db_query($sSqlEmissao);

    if (!$rsEmissao) {
      throw new \DBException("Erro ao buscar os dados das emissões gerais.");
    }

    $aDadosEmissao = \db_utils::getCollectionByRecord($rsEmissao, 0);
    $aEmissaoGeral = array();

    foreach ($aDadosEmissao as $iIndice => $oDadosEmissao) {

      $oEmissaoGeral = new EmissaoGeral();
      $oEmissaoGeral->setCodigo($oDadosEmissao->tr01_sequencial);
      $oEmissaoGeral->setTipo($oDadosEmissao->tr01_tipoemissao);
      $oEmissaoGeral->setData(new \DBDate($oDadosEmissao->tr01_data));
      $oEmissaoGeral->setHora($oDadosEmissao->tr01_hora);
      $oEmissaoGeral->setInstituicao(new \Instituicao($oDadosEmissao->tr01_instit));
      $oEmissaoGeral->setConvenio($oDadosEmissao->tr01_convenio);
      $oEmissaoGeral->setUsuario($oDadosEmissao->tr01_usuario);
      $oEmissaoGeral->setParametros((object) \JSON::create()->parse($oDadosEmissao->tr01_parametros));

      $aEmissaoGeral[] = $oEmissaoGeral;
    }

    return $aEmissaoGeral;
  }

  /**
   * Função que busca as ocorrências da Emissão Geral desejada
   *
   * @param  EmissaoGeral   $oEmissao
   * @throws \DBException
   * @return array
   */
  public function getEmissaoOcorrencias( EmissaoGeral $oEmissao )
  {
    $sCampos        = "k169_codigo as movimentacao, ocorrenciacobrancaregistrada.*";
    $sSqlOcorrencia = $this->oDao->sql_query_ocorrencias_movimentacao($oEmissao->getCodigo(), $sCampos);
    $rsOcorrencia   = \db_query($sSqlOcorrencia);

    if ( !$rsOcorrencia ) {
      throw new \DBException("Erro ao buscar ocorrências da Emissão Geral.");
    }

    $aOcorrencia = \db_utils::getCollectionByRecord($rsOcorrencia);

    return $aOcorrencia;
  }
}
