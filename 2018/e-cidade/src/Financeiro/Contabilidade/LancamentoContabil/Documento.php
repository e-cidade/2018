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
namespace ECidade\Financeiro\Contabilidade\LancamentoContabil;

/**
 * Class Documento
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil
 */
class Documento
{
  /**
   * Estorno de Empenho de Restos a Pagar Nуo Processados (Nуo Liquidados)
   * @var integer
   */
  const ESTORNO_RP_NAO_PROCESSADO = 32;

  /**
   * Liquiaчуo de RP
   * @var integer
   */
  const LIQUIDACAO_RP = 33;

  /**
   * Estorno de Liquiaчуo de RP
   * @var integer
   */
  const ESTORNO_LIQUIDACAO_RP = 34;

  /**
   * Liquidaчуo de RP para Estoque e Patrimonio
   * @var integer
   */
  const LIQUIDACAO_RP_ESTOQUE_PATRIMONIO = 39;

  /**
   * Estorno de Liquidaчуo de RP para Estoque e Patrimonio
   * @var integer
   */
  const ESTORNO_LIQUIDACAO_RP_ESTOQUE_PATRIMONIO = 39;

  /**
   * Inscriчуo de Restos a Pagar Nуo Processados (Nуo Liquidados)
   * @var integer
   */
  const INSCRICAO_RP_NAO_PROCESSADO = 1007;
}
