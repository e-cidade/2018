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
 * Class SolicitacaoTipo
 * Tipos de uma solicitaчуo de compras no e-cidade
 */
final class SolicitacaoTipo {

  /**
   * @tipo integer
   */
  const NORMAL = 1;

  /**
   * @tipo integer
   */
  const PACTO = 2;

  /**
   * @tipo integer
   */
  const ABERTURA_REGISTRO_PRECO = 3;

  /**
   * @tipo integer
   */
  const ESTIMATIVA_REGISTRO_PRECO = 4;

  /**
   * @tipo integer
   */
  const PROCESSAMENTO_REGISTRO_PRECO = 5;

  /**
   * @tipo integer
   */
  const COMPILACAO_REGISTRO_PRECO = 6;

  /**
   * @tipo integer
   */
  const CONTRATO = 7;

  /**
   * Usado em solicitaчуo de compras para licitaчѕes de Concessуo
   * @tipo integer
   */
  const AUTOMATICO = 8;
}