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

class CategoriasConsumo extends Arquivo {

  const CODIGO_LAYOUT = 266;

  const SERVICO_BASICO_AGUA   = 4;
  const SERVICO_BASICO_ESGOTO = 5;
  const SERVICO_AGUA          = 6;
  const SERVICO_ESGOTO        = 7;

  public function __construct() {

    $this->iCodigoLayout = self::CODIGO_LAYOUT;
    $this->sNomeArquivo  = 'categorias_consumo';
  }

  /**
   * @return array
   */
  public function getDados() {

    $oDaoEstruturaTarifaria = new \cl_aguaestruturatarifaria;
    $sCampos = '*';
    $sOrder  = 'x13_sequencial, x37_tipoestrutura, x37_valorinicial';
    $sWhere  = null;
    $sSql    = $oDaoEstruturaTarifaria->sql_query(null, $sCampos, $sOrder, $sWhere);
    $rsCategorias = db_query($sSql);

    $aCategorias = array();
    while ($oCategoria = pg_fetch_object($rsCategorias)) {

      $oCategoriaRetorno = new \stdClass;
      $oCategoriaRetorno->codigo              = $oCategoria->x13_sequencial;
      $oCategoriaRetorno->descricao           = $oCategoria->x13_descricao;
      $oCategoriaRetorno->exercicio           = $oCategoria->x13_exercicio;
      $oCategoriaRetorno->faixa_inicial       = null;
      $oCategoriaRetorno->faixa_final         = null;
      $oCategoriaRetorno->valor               = null;
      $oCategoriaRetorno->valor_tarifa_agua   = null;
      $oCategoriaRetorno->valor_tarifa_esgoto = null;
      $oCategoriaRetorno->percentual_esgoto   = null;

      if ($oCategoria->x37_aguaconsumotipo == self::SERVICO_BASICO_AGUA) {
        $oCategoriaRetorno->valor_tarifa_agua = $oCategoria->x37_valor;
      }

      if ($oCategoria->x37_aguaconsumotipo == self::SERVICO_BASICO_ESGOTO) {
        $oCategoriaRetorno->valor_tarifa_esgoto = $oCategoria->x37_valor;
      }

      if ($oCategoria->x37_aguaconsumotipo == self::SERVICO_AGUA) {

        $oCategoriaRetorno->faixa_inicial = $oCategoria->x37_valorinicial;
        $oCategoriaRetorno->faixa_final   = $oCategoria->x37_valorfinal;
        $oCategoriaRetorno->valor         = $oCategoria->x37_valor;
      }

      if ($oCategoria->x37_aguaconsumotipo == self::SERVICO_ESGOTO) {
        $oCategoriaRetorno->percentual_esgoto = $oCategoria->x37_percentual;
      }

      $aCategorias[] = $oCategoriaRetorno;
    }

    return $aCategorias;
  }
}
