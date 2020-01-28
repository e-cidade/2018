<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
namespace ECidade\Patrimonial\Licitacao\Modalidade\Fundamentacao;

/**
 * Classe com as fundamentações da modalidade 'Processo de Inexigibilidade'
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
final class PRI implements DeparaInterface
{
  /**
   * Fundamentações da modalidade
   *
   * @var array
   */
  private $aFundamentacoes;

  /**
   * Método construtor
   */
  public function __construct()
  {
    $this->aFundamentacoes = array(
      'A25CAPT',
      'A25I',
      'A25II',
      'A25III',
      'A30I',
      'A30IIA',
      'A30IIB',
      'A30IIC',
      'A30IID',
      'A30IIE',
      'A30IIF',
      'A30IIG',
      'A31',
      'OUTI'
    );
  }

  /**
   * Retornamos as fundamenações que a modalidade permite
   *
   * @return array
   */
  public function getFundamentacoes()
  {
    return $this->aFundamentacoes;
  }
}