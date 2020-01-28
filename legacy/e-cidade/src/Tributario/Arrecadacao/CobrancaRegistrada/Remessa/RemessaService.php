<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2017  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa;

use ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Remessa\Remessa;
use \cl_remessacobrancaregistrada as RemessaCobrancaRegistradaDAO;
use \Exception;

class RemessaService
{
  private $oDao;

  public function __construct(RemessaCobrancaRegistradaDAO $oDao)
  {
    $this->oDao = $oDao;
  }

  public function getDao()
  {
    return $this->oDao;
  }

  public function salvar(Remessa $oRemessa)
  {
    $oDao = $this->oDao;

    $oDao->k147_sequencial = null;
    $oDao->k147_instit = $oRemessa->getInstit();
    $oDao->k147_convenio = $oRemessa->getConvenio();
    $oDao->k147_sequencialremessa = $oRemessa->getSequencialRemessa();
    $oDao->k147_dataemissao = $oRemessa->getDataEmissao()->format("Y-m-d");
    $oDao->k147_horaemissao = $oRemessa->getDataEmissao()->format("H:i");
    $oDao->k147_arquivoremessa = $oRemessa->getOid();
    $oDao->incluir(null);

    if ($oDao->erro_status = "0") {
      throw new Exception("Erro ao salvar remessa.");
    }

    return $oDao->k147_sequencial;
  }
}
