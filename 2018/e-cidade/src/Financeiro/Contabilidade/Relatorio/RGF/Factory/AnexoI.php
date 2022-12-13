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
namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2017\AnexoI  as Anexo2017;
use ECidade\Financeiro\Contabilidade\Relatorio\RGF\Layout\AnexoI as Layout;

/**
 * Class AnexoI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\Factory
 */
class AnexoI {

  /**
   * Retorna a instancia do layout
   * @param  integer       $iAno          ano da sessão
   * @param  \Periodo      $oPeriodo      Periodo dos relatório contabeis
   * @param  \Instituicao[] $aInstituicoes
   * @param  integer       $iModelo       Modelo do layout 1 - Oficial | 2 - Detalhamento Mensal
   *
   * @return Layout
   * @throws \Exception
   */
  public static function getInstance($iAno, \Periodo $oPeriodo, $aInstituicoes, $iModelo) {

    if ( $iAno < 2017) {
      throw new \Exception("Modelo exclusivo para o ano maior ou igual à 2017.");
    }

    $oDadosAnexo = new Anexo2017($iAno, $oPeriodo, $aInstituicoes, $iModelo);

    $oLayout = new Layout();
    $oLayout->setAnexo($oDadosAnexo);
    return $oLayout;
  }
}
