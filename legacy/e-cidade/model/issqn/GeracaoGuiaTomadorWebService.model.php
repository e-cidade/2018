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

require_once(modification("model/issqn/PlanilhaRetencaoWebService.model.php"));
require_once(modification("model/arrecadacao/boletos/EmissaoBoletoWebService.model.php"));

/**
 * Classe para gerar planilha para tomador
 *
 * @author Renan Melo <renan@dbseller.com.br>
 * @package webservices
 */
class GeracaoGuiaTomadorWebService {

  private $oPlanilha;

  public function __construct( $iCodigoPlanilha ) {
    $this->oPlanilha = new PlanilhaRetencaoWebService( $iCodigoPlanilha );
  }

  public function retornarDados() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação ativa");
    }

    try {

      $oRetorno = $this->gerar();
      return $oRetorno;
    } catch ( Exception $eErro ) {
      throw new DBException($eErro->getMessage());
    }
  }

  public function gerar() {

      $this->oPlanilha->gerarDebito("Debito Gerado por Integração Via WebService.");

      $iNumpre    = $this->oPlanilha->getNumpre();
      $iNumpar    = $this->oPlanilha->getMesCompetencia();
      $iInscricao = $this->oPlanilha->getInscricao();
      $iCgm       = $this->oPlanilha->getNumeroCgm();

      $oEmissaoBoleto = new EmissaoBoletoWebservice();
      $oEmissaoBoleto->adicionarDebito($iNumpre, 1);
      $oEmissaoBoleto->setInscricao($iInscricao);
      $oEmissaoBoleto->setCodigoCgm($iCgm);
      $oEmissaoBoleto->setDataVencimento( $this->oDataVencimento );
      $oEmissaoBoleto->setModeloImpressao(2);
      $oEmissaoBoleto->gerarRecibo();
      $oEmissaoBoleto->imprimir();

      return $oEmissaoBoleto->getDadosBoleto();

  }

  public function setDataVencimento( $sDataVencimento ) {

    $this->oDataVencimento = new DBDate($sDataVencimento);
    return true;

  }
}