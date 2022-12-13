<?php
/**
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

namespace ECidade\Tributario\Integracao\JuntaComercial\Repository;

use ECidade\Tributario\Integracao\JuntaComercial\Model\Protocolo as ProtocoloModel;

class Protocolo
{
  public function __construct(){}

  public static function persist(ProtocoloModel $oProtocolo)
  {
    $oDaoProtocolo = new \cl_juntacomercialprotocolo();
    $oDaoProtocolo->q147_sequencial       = $oProtocolo->getCodigo();
    $oDaoProtocolo->q147_servico          = $oProtocolo->getServico();
    $oDaoProtocolo->q147_funcao           = $oProtocolo->getFuncao();
    $oDaoProtocolo->q147_protocolo        = $oProtocolo->getProtocolo();
    $oDaoProtocolo->q147_xml              = $oProtocolo->getOIDXml();
    $oDaoProtocolo->q147_data             = $oProtocolo->getData()->format("Y-m-d");
    $oDaoProtocolo->q147_cnpjenvia        = $oProtocolo->getCNPJEmissor();
    $oDaoProtocolo->q147_cnpjrecebe       = $oProtocolo->getCNPJReceptor();
    $oDaoProtocolo->q147_cpfcnpjprocesso  = $oProtocolo->getCPFCNPJProcesso();

    if (!empty($oDaoProtocolo->q147_sequencial)) {
      $oDaoProtocolo->alterar();
    }

    if (empty($oDaoProtocolo->q147_sequencial)) {
      $oDaoProtocolo->incluir();
    }

    if ($oDaoProtocolo->erro_status == 0) {
      throw new \Exception($oDaoProtocolo->erro_msg);
    }
    $oProtocolo->setCodigo($oDaoProtocolo->q147_sequencial);

    self::persistEventos($oProtocolo);
  }

  /**
   * Persiste os eventos do protocolo
   * @param ProtocoloModel $protocolo
   * @throws \Exception
   */
  private static function persistEventos(ProtocoloModel $protocolo ) {

    $oDaoEventos = new \cl_juntacomercialprotocoloeventos();
    $oDaoEventos->excluir($protocolo->getCodigo());

    foreach ($protocolo->getEventos() as $evento) {

      $oDaoEventos->q148_sequencial = null;
      $oDaoEventos->q148_protocolo  = $protocolo->getCodigo();
      $oDaoEventos->q148_codevento  = $evento->getCodigo();
      $oDaoEventos->q148_evento     = $evento->getEvento();
      $oDaoEventos->incluir();

      if ($oDaoEventos->erro_status == 0) {
        throw new \Exception("Erro ao salvar eventos do protocolo.");
      }
    }
  }

  public static function getById(){}

  public static function getAll(){}
}
