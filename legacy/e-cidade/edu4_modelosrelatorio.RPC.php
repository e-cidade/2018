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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

define( "MENSAGENS_MODELOS_RELATORIO_RPC", "educacao.escola.edu4_modelosrelatorio_RPC." );

$oJson                 = new services_json();
$oParam                = $oJson->decode( str_replace( "\\", "", $_POST["json"] ) );
$oRetorno              = new stdClass();
$oRetorno->iStatus     = 1;
$oRetorno->sMensagem   = '';
$oPropriedadesMensagem = new stdClass();

try {

  switch ( $oParam->sExecucao ) {

    /**
     * Busca os modelos configurados de acordo com o tipo de relatório passado
     * @param  integer $oParam->iRelatorio - Tipo de relatório
     * @return array
     *         ..... stdClass
     *         .............. integer iCodigo - Código do modelo
     *         .............. string  sNome   - Nome do modelo
     */
    case 'tipoModelo':

      if( !isset( $oParam->iRelatorio ) || empty( $oParam->iRelatorio ) ) {
        throw new ParameterException( _M( MENSAGENS_MODELOS_RELATORIO_RPC . 'tipo_modelo_nao_informado' ) );
      }

      $oDaoEduRelatModel    = new cl_edu_relatmodel();
      $sCamposEduRelatModel = "ed217_i_codigo, ed217_c_nome";
      $sWhereEduRelatModel  = "ed217_i_relatorio = {$oParam->iRelatorio}";
      $sSqlDadosEduRelModel = $oDaoEduRelatModel->sql_query( null, $sCamposEduRelatModel,null, $sWhereEduRelatModel );
      $rsDadosEduRelModel   = $oDaoEduRelatModel->sql_record( $sSqlDadosEduRelModel );

      if( !$rsDadosEduRelModel ) {

        $oPropriedadesMensagem->sErro = pg_last_error( $rsDadosEduRelModel );
        throw new DBException( _M( MENSAGENS_MODELOS_RELATORIO_RPC . 'erro_busca_modelo', $oPropriedadesMensagem ) );
      }

      $oRetorno->aModelos = array();

      if( $rsDadosEduRelModel && pg_num_rows( $rsDadosEduRelModel ) ) {

        $iTotalLinhas = pg_num_rows( $rsDadosEduRelModel );
        for( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

          $oDadosModelo         = db_utils::fieldsMemory( $rsDadosEduRelModel, $iContador );
          $oModelo              = new stdClass();
          $oModelo->iCodigo     = $oDadosModelo->ed217_i_codigo;
          $oModelo->sNome       = urlencode( $oDadosModelo->ed217_c_nome );
          $oRetorno->aModelos[] = $oModelo;
        }
      }

      break;
  }
} catch ( Exception $oErro ) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( str_replace( "\\n", "\n", $oErro->getMessage() ) );
}

echo $oJson->encode( $oRetorno );