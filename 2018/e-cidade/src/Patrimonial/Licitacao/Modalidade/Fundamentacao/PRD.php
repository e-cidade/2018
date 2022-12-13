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
 * Classe com as fundamentações da modalidade 'Processo de Dispensa'
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
final class PRD implements DeparaInterface
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
      'A24I',
      'A24II',
      'A24IV',
      'A24V',
      'A24VII',
      'A24VIII',
      'A24X',
      'A24XI',
      'A24XII',
      'A24XIII',
      'A24XVI',
      'A24XX',
      'A24XXII',
      'A28I',
      'A28II',
      'A29I',
      'A29II',
      'A29III',
      'A29IV',
      'A29IX',
      'A29V',
      'A29VI',
      'A29VII',
      'A29VIII',
      'A29X',
      'A29XI',
      'A29XII',
      'A29XIII',
      'A29XIV',
      'A29XV',
      'A29XVI',
      'A29XVII',
      'A29XVIII',
      'A30',
      'OUTD'
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