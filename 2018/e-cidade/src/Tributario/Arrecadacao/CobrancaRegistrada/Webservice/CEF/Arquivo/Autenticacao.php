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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo;

/**
 * Classe responsável pela criação do hash de autenticação para o Webservice da CEF
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class Autenticacao
{
  /**
   * String com os dados prontos para serem encriptografados
   *
   * @var string
   */
  private $sDadosConcatenados;

  /**
   * Contrutor da classe que recebe os dados necessários e os organiza para gerar o hash
   *
   * @param integer $iBeneficiario
   * @param integer $iNossonumero
   * @param string  $sDataVencimento
   * @param numeric $nValor
   * @param string  $sCpfCnpj
   */
  public function __construct($iBeneficiario,
                              $iNossonumero,
                              $sDataVencimento,
                              $nValor,
                              $sCpfCnpj)
  {
    $oDate = new \DBDate($sDataVencimento);

    $iBeneficiario   = str_pad($iBeneficiario, 7, '0', STR_PAD_LEFT);
    $iNossonumero    = str_pad($iNossonumero, 17, '0', STR_PAD_LEFT);
    $sDataVencimento = $oDate->getDate('dmY');
    $nValor          = str_replace('.', '', $nValor);
    $nValor          = str_pad($nValor, 15, '0', STR_PAD_LEFT);
    $sCpfCnpj        = str_replace('-', '', $sCpfCnpj);
    $sCpfCnpj        = str_replace('/', '', $sCpfCnpj);
    $sCpfCnpj        = str_pad($sCpfCnpj, 14, '0', STR_PAD_LEFT);

    $this->sDadosConcatenados  = $iBeneficiario;
    $this->sDadosConcatenados .= $iNossonumero;
    $this->sDadosConcatenados .= $sDataVencimento;
    $this->sDadosConcatenados .= $nValor;
    $this->sDadosConcatenados .= $sCpfCnpj;
  }

  /**
   * funcão que encriptografa os dados para a autenticação do webservice
   *
   * @return string   hash de autenticação
   */
  public function getHash()
  {
    return base64_encode( hash("sha256", $this->sDadosConcatenados, true) );
  }
}