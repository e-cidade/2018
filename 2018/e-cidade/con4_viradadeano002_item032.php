<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
use \ECidade\Educacao\Secretaria\EstruturalNotaValidacao;

require_once(modification("classes/db_avaliacaoestruturanota_classe.php"));
require_once(modification("classes/db_avaliacaoestruturaregra_classe.php"));

if ( !$sqlerro ) {

  db_atutermometro(0, 2, 'termometroitem', 1, $sMensagemTermometroItem);

  try {

    /**
     * PARÂMETROS NA SECRETARIA
     */
    $sWherePadrao         = " ed139_ano = {$iAnoOrigem} and ed139_ativo is true ";
    $oDaoEstruturalPadrao = new cl_avaliacaoestruturanotapadrao();
    $sSqlEstruturalPadrao = $oDaoEstruturalPadrao->sql_query_file(null, "*", null, $sWherePadrao);
    $rsEstruturalPadrao   = db_query($sSqlEstruturalPadrao);

    if ( !$rsEstruturalPadrao ) {
      throw new Exception("Erro ao buscar configuração da nota na secretaria da educação.", 1);
    }

    $iLinhasEstruturalPadrao = pg_num_rows($rsEstruturalPadrao);
    if ( $iLinhasEstruturalPadrao > 0 ) {

      $oDados = db_utils::fieldsMemory($rsEstruturalPadrao, 0);
      if ( !EstruturalNotaValidacao::permiteInclusaoEstruturaNotaSecretaria($iAnoDestino) ) {

        $sMsgErro = "Na Secretaria de Educação, já existe parâmetro configurado para o ano: {$iAnoDestino}";
        throw new Exception( $sMsgErro, 1 );
      }

      $oDaoEstruturalPadrao->ed139_sequencial          = null;
      $oDaoEstruturalPadrao->ed139_db_estrutura        = $oDados->ed139_db_estrutura;
      $oDaoEstruturalPadrao->ed139_ativo               = $oDados->ed139_ativo ? 'true' : 'false';
      $oDaoEstruturalPadrao->ed139_arredondamedia      = $oDados->ed139_arredondamedia ? 'true' : 'false';
      $oDaoEstruturalPadrao->ed139_regraarredondamento = $oDados->ed139_regraarredondamento;
      $oDaoEstruturalPadrao->ed139_observacao          = $oDados->ed139_observacao;
      $oDaoEstruturalPadrao->ed139_ano                 = $iAnoDestino;
      $oDaoEstruturalPadrao->incluir(null);
      if ( $oDaoEstruturalPadrao->erro_status == '0' ) {
        throw new Exception($oDaoEstruturalPadrao->erro_msg, 1);
      }
    }

    /**
     * PARÂMETROS DA ESCOLA
     * Buscamos os dados da tabela avaliacaoestruturanota a serem importados
     */
    $oDaoAvaliacaoEstruturaNota = new cl_avaliacaoestruturanota;
    $sWhereEstruturaNota        = "ed315_ano = {$iAnoOrigem} and ed315_ativo is true ";
    $sSqlAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNota->sql_query_file(null, "*", null, $sWhereEstruturaNota );
    $rsAvaliacaoEstruturaNota   = db_query($sSqlAvaliacaoEstruturaNota);

    if ( !$rsAvaliacaoEstruturaNota ) {
      throw new Exception("Erro ao buscar configuração da nota na Escola.", 1);
    }

    $iLinhasAvaliacaoEstruturaNota = pg_num_rows($rsAvaliacaoEstruturaNota);
    if ($iLinhasAvaliacaoEstruturaNota > 0 )  {

      for ($iContadorNota = 0; $iContadorNota < $iLinhasAvaliacaoEstruturaNota; $iContadorNota++) {

        $oDadosEstruturaNota                = db_utils::fieldsMemory($rsAvaliacaoEstruturaNota, $iContadorNota);
        $oDaoAvaliacaoEstruturaNotaMigracao = new cl_avaliacaoestruturanota;

        $oDaoAvaliacaoEstruturaNotaMigracao->ed315_escola         = $oDadosEstruturaNota->ed315_escola;
        $oDaoAvaliacaoEstruturaNotaMigracao->ed315_db_estrutura   = $oDadosEstruturaNota->ed315_db_estrutura;
        $oDaoAvaliacaoEstruturaNotaMigracao->ed315_ativo          = $oDadosEstruturaNota->ed315_ativo ? 'true':'false';
        $oDaoAvaliacaoEstruturaNotaMigracao->ed315_arredondamedia = $oDadosEstruturaNota->ed315_arredondamedia ? 'true':'false';
        $oDaoAvaliacaoEstruturaNotaMigracao->ed315_observacao     = $oDadosEstruturaNota->ed315_observacao;
        $oDaoAvaliacaoEstruturaNotaMigracao->ed315_ano            = $iAnoDestino;
        $oDaoAvaliacaoEstruturaNotaMigracao->incluir(null);
        $iCodigoAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNotaMigracao->ed315_sequencial;

        if ($oDaoAvaliacaoEstruturaNotaMigracao->erro_status == "0") {
          throw new Exception($oDaoAvaliacaoEstruturaNotaMigracao->erro_msg, 1);
        }

        /**
         * Buscamos os dados da tabela avaliacaoestruturaregra, que tenham alguma configuracao de nota migrada, vinculada
         */
        $oDaoAvaliacaoEstruturaRegra   = new cl_avaliacaoestruturaregra;
        $sWhereAvaliacaoEstruturaRegra = "ed318_avaliacaoestruturanota = {$oDadosEstruturaNota->ed315_sequencial}";
        $sSqlAvaliacaoEstruturaRegra   = $oDaoAvaliacaoEstruturaRegra->sql_query_file( null, "*", null,
                                                                                      $sWhereAvaliacaoEstruturaRegra );

        $rsAvaliacaoEstruturaRegra      = $oDaoAvaliacaoEstruturaRegra->sql_record($sSqlAvaliacaoEstruturaRegra);
        $iLinhasAvaliacaoEstruturaRegra = $oDaoAvaliacaoEstruturaRegra->numrows;

        if ($iLinhasAvaliacaoEstruturaRegra > 0) {

          for ($iContadorRegra = 0; $iContadorRegra < $iLinhasAvaliacaoEstruturaRegra; $iContadorRegra++) {

            $oDadosEstruturaRegra                = db_utils::fieldsMemory($rsAvaliacaoEstruturaRegra, $iContadorRegra);
            $oDaoAvaliacaoEstruturaRegraMigracao = new cl_avaliacaoestruturaregra;

            $oDaoAvaliacaoEstruturaRegraMigracao->ed318_avaliacaoestruturanota = $iCodigoAvaliacaoEstruturaNota;
            $oDaoAvaliacaoEstruturaRegraMigracao->ed318_regraarredondamento    = $oDadosEstruturaRegra->ed318_regraarredondamento;
            $oDaoAvaliacaoEstruturaRegraMigracao->incluir(null);

            if ($oDaoAvaliacaoEstruturaRegraMigracao->erro_status == "0") {
              throw new Exception($oDaoAvaliacaoEstruturaRegraMigracao->erro_msg, 1);
            }
          }
        }
      }
    }

    $sMsgErro = '';
  } catch( Exception $e) {

    $sqlerro   = true;
    $erro_msg .= $e->getMessage();

    if ($e->getCode() == 2) {

      $cldb_viradaitemlog->c35_log = $e->getMessage();
      $cldb_viradaitemlog->c35_codarq        = 3367;
      $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
      $cldb_viradaitemlog->c35_data          = date("Y-m-d");
      $cldb_viradaitemlog->c35_hora          = date("H:i");
      $cldb_viradaitemlog->incluir(null);

      if ($cldb_viradaitemlog->erro_status == 0) {

        $sqlerro   = true;
        $erro_msg .= $cldb_viradaitemlog->erro_msg;
      }
    }
  }

  db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);
}