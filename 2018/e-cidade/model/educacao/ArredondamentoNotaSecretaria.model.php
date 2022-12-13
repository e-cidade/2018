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
 * Classe criada para usar na secretaria de educação quando necessário utilizar a configuração da nota criada na secretaria
 *
 * @package Educacao
 * @author  Andrio Costa <andrio.costa@dbseller.com.br
 * @version $Revision: 1.1 $
 */
require_once(modification('model/educacao/IEducacaoArredondamento.interface.php'));
class ArredondamentoNotaSecretaria extends ArredondamentoNota implements IEducacaoArredondamento {

  /**
   * Estatica
   */
  protected static $sInstance;

  /**
   * Regra de Arredondamento
   * @var EducacaoArredondamento
   */
  protected $oEducacaoArredondamento;

  protected function __construct() {

    $this->oEducacaoArredondamento = new EducacaoArredondamento();
    $this->iCasasDecimais          = 0;

    $sCampos = " ed139_arredondamedia, ed139_ano, db77_estrut, ed139_regraarredondamento, ed316_casasdecimaisarredondamento";
    $sWhere  = " ed139_ativo is true ";

    $oDaoConfiguracao = new cl_avaliacaoestruturanotapadrao();
    $sSqlConfiguracao = $oDaoConfiguracao->sql_query(null, $sCampos, "ed139_ano", $sWhere);
    $rsConfiguracao   = db_query($sSqlConfiguracao);

    if (!$rsConfiguracao) {
      throw new Exception("Erro ao buscar configuração da secretaria.");
    }

    $iLinhas = pg_num_rows($rsConfiguracao);

    for ($i = 0; $i < $iLinhas; $i++) {

      $oDados = db_utils::fieldsMemory($rsConfiguracao, $i);

      $oDadosRegra                               = new stdClass();
      $oDadosRegra->sMascara                     = $oDados->db77_estrut;
      $oDadosRegra->lArredondar                  = $oDados->ed139_arredondamedia == 't';
      $oDadosRegra->iCasasDecimais               = 0;
      $oDadosRegra->iCasasDecimaisArredondamento = $oDados->ed316_casasdecimaisarredondamento;
      $oDadosRegra->aRegras                      = array();
      $aPartesMascara                            = explode(".", $oDados->db77_estrut);

      if (isset($aPartesMascara[1])) {
        $oDadosRegra->iCasasDecimais = strlen($aPartesMascara[1]);
      }

      if (count($aPartesMascara) == 2 && $oDados->ed139_regraarredondamento != "") {
        $oDadosRegra->aRegra = $this->retornarFaixasRegraArrendodamento( $oDados->ed139_regraarredondamento );
      }
      $this->oEducacaoArredondamento->adicionarRegras($oDados->ed139_ano, $oDadosRegra);
    }
  }

}