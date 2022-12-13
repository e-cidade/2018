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

namespace ECidade\Tributario\Agua\Coletor\Exportacao\Arquivo;

class Economias extends Arquivo {

  /**
   * @var array Códigos dos contratos
   */
  private $aContratos;

  /**
   * Economias constructor.
   */
  public function __construct() {

    $this->sNomeArquivo  = 'economias';
    $this->iCodigoLayout = 268;
  }

  /**
   * Adiciona um array com códigos de contrato
   * @param array $aContratos Códigos dos contratos
   */
  public function adicionarContratos(array $aContratos) {
    $this->aContratos = $aContratos;
  }

  /**
   * @return array Códigos dos contratos
   */
  public function getContratos() {
    return $this->aContratos;
  }

  /**
   * @return \stdClass[]
   * @throws \DBException
   */
  public function getDados() {

    $oDaoEconomias = new \cl_aguacontratoeconomia;

    $sCampos  = 'x38_aguacontrato, x54_aguabase, x38_aguacategoriaconsumo, count(*) as qtd';
    $sOrdem   = 'x38_aguacontrato, x54_aguabase, x38_aguacategoriaconsumo';
    $sGroupBy = 'x38_aguacontrato, x54_aguabase, x38_aguacategoriaconsumo';
    $aWhere = array(
      'x54_aguabase is not null',
      'x54_condominio is true',
    );
    if ($this->aContratos) {

      $sCodigosContratos = implode(', ', $this->aContratos);
      $aWhere[] = "x38_aguacontrato in({$sCodigosContratos})";
    }

    $sSql    = $oDaoEconomias->sql_query(null, $sCampos, $sOrdem, implode(' and ', $aWhere), $sGroupBy);
    $rsDados = db_query($sSql);

    if (!$rsDados) {
      throw new \DBException('Ocorreu um erro ao buscar as economias.');
    }

    $aEconomias = array();
    $iQtdEconomias = pg_num_rows($rsDados);
    for ($iEconomia = 0; $iEconomia < $iQtdEconomias; $iEconomia++) {

      $oStdEconomia = pg_fetch_object($rsDados, $iEconomia);

      $oEconomia = new \stdClass;
      $oEconomia->codigo_contrato          = $oStdEconomia->x38_aguacontrato;
      $oEconomia->codigo_matricula         = $oStdEconomia->x54_aguabase;
      $oEconomia->codigo_categoria_consumo = $oStdEconomia->x38_aguacategoriaconsumo;
      $oEconomia->quantidade_economias     = $oStdEconomia->qtd;

      $aEconomias[] = $oEconomia;
    }

    return $aEconomias;
  }
}
