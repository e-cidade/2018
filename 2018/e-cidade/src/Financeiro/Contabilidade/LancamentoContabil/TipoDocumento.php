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
 * Class TipoDocumento
 * @package ECidade\Financeiro\Contabilidade\LancamentoContabil
 */
class TipoDocumento
{

  /**
   * Empenho
   * @var integer
   */
  const EMPENHO = 10;

  /**
   * Estorno de Empenho
   * @var integer
   */
  const ESTORNO_EMPENHO = 11;

  /**
   * Liquidaзгo
   * @var integer
   */
  const LIQUIDACAO = 20;

  /**
   * Estorno de Liquidaзгo
   * @var integer
   */
  const ESTORNO_LIQUIDACAO = 21;

  /**
   * Pagamento
   * @var integer
   */
  const PAGAMENTO = 30;

  /**
   * Estorno
   * @var integer
   */
  const ESTORNO = 31;

  /**
   * Encerramento de Exercнcio
   * @var integer
   */
  const ENCERRAMENTO_EXERCICIO = 1000;


  /**
   * @var integer
   */
  const  RECEBIMENTO_CAUCAO = 150 ;

  /**
   * @var integer
   */
  const  DEVOLUCAO_CAUCAO = 151 ;

  /**
   * @var integer
   */
  const  RECEBIMENTO_CAUCAO_ESTORNO = 152 ;

  /**
   * @var integer
   */
  const  DEVOLUCAO_CAUCAO_ESTORNO = 153 ;

  /**
   * @var integer
   */
  const  DEPOSITOS_DIVERSOS_RECEBIMENTO = 160 ;

  /**
   * @var integer
   */
  const  DEPOSITOS_DIVERSOS_PAGAMENTO = 161 ;

  /**
   * @var integer
   */
  const  DEPOSITOS_DIVERSOS_ESTORNO_RECEBIMENTO = 162 ;

  /**
   * @var integer
   */
  const  DEPOSITOS_DIVERSOS_ESTORNO_PAGAMENTO = 163 ;
}
