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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

define("MSG_LAB_CONFERENCIARPC", "saude.laboratorio.lab_conferenciarpc.");

$oJson               = new services_json();
$oParam              = $oJson->decode( str_replace( "\\", "", $_POST["json"] ) );
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {

  db_inicio_transacao();

  switch ( $oParam->exec ) {

    /**
     * ************************************************************
     * Busca os dados dos exames vinculados a Requisição Laboratorial
     * Retorna a seguinte estrutura:
     * stdClass Object
     * (
     *     [iExame] =>
     *     [sExame] =>
     *     [iCidConferido] =>
     *     [sNomeCidConferido] =>
     *     [sEstruturalCidConferido] =>
     *     [sProcedimentoEstrutural] =>
     *     [sProcedimento] =>
     *     [aCID] => Array
     *         (
     *             [0] => stdClass Object
     *                 (
     *                     [iCodigo] =>
     *                     [sNome] =>
     *                     [lPrincipal] =>
     *                 )
     *         )
     *
     * )
     * @param integer  $oParam->iCodigo
     * ************************************************************
     */
    case 'getExamesRequisicao':

      $oRetorno->aExames = array();
      $oRequisicaoLaboratorial = new RequisicaoLaboratorial($oParam->iCodigo);
      $aRequisicoesExames      = $oRequisicaoLaboratorial->getRequisicoesDeExames();

      if ( is_array($aRequisicoesExames) ) {

        foreach ($aRequisicoesExames as $oRequisicaoExame) {

          if (    $oRequisicaoExame->getSituacao() != RequisicaoExame::LANCADO
               && $oRequisicaoExame->getSituacao() != RequisicaoExame::CONFERIDO
             ) {
            continue;
          }

          $oDadosExame         = new stdClass();
          $oExame              = $oRequisicaoExame->getExame();
          $oDadosExame->iExame = $oRequisicaoExame->getCodigo();
          $oDadosExame->sExame = urlencode( $oExame->getNome() );
          $oDadosExame->aCID   = array();

          $oDadosExame->iProcedimento           = '';
          $oDadosExame->sProcedimentoEstrutural = '';
          $oDadosExame->sProcedimento           = '';
          $oDadosExame->sSituacao               = $oRequisicaoExame->getSituacao();
          $oDadosExame->lConferido              = $oRequisicaoExame->getSituacao() == RequisicaoExame::CONFERIDO;
          $oDadosExame->iCidConferido           = null;
          $oDadosExame->sNomeCidConferido       = '';
          $oDadosExame->sEstruturalCidConferido = '';

          $oCID = $oRequisicaoExame->getCID();

          if ( !empty($oCID) ) {

            $oDadosExame->iCidConferido           = $oCID->getCodigo();
            $oDadosExame->sNomeCidConferido       = urlencode($oCID->getNome());
            $oDadosExame->sEstruturalCidConferido = urlencode($oCID->getCID());
          }

          $oProcedimento = $oExame->getProcedimento();

          if ( !empty($oProcedimento) ) {

            $oDadosExame->iProcedimento           = $oProcedimento->getCodigo();
            $oDadosExame->sProcedimentoEstrutural = $oProcedimento->getEstrutural();
            $oDadosExame->sProcedimento           = urlencode( $oProcedimento->getDescricao() );

            $aCIDProcedimento = $oProcedimento->getCID();

            foreach ($aCIDProcedimento as $oCIDProcedimento) {

              $oCID                = new stdClass();
              $oCID->iCodigo       = $oCIDProcedimento->getCID()->getCodigo();
              $oCID->sCID          = $oCIDProcedimento->getCID()->getCID();
              $oCID->sNome         = urlencode( $oCIDProcedimento->getCID()->getNome() );
              $oCID->lPrincipal    = $oCIDProcedimento->cidPrincipal();
              $oDadosExame->aCID[] = $oCID;
            }
          }
          $oRetorno->aExames[] = $oDadosExame;
        }
      }

    break;

    case 'salvarConferencia':

      /**
       * OBSERVAÇÂO
       * A variável consideração deve pode ser salva em : lab_conferencia ou lab_resultado
       * O local é definido pelo parâmetro : $oParam->lConferido
       * ... false: devemos salvar em lab_conferencia
       * ... true: devemos salvar em lab_resultado
       */

      $oDaoConferencia = new cl_lab_conferencia();

      db_inicio_transacao();

      $oDaoConferencia->la47_d_data  = date('Y-m-d',db_getsession("DB_datausu"));
      $oDaoConferencia->la47_c_hora  = db_hora();
      $oDaoConferencia->la47_i_login = db_getsession("DB_id_usuario");

      foreach ($oParam->aExames as $oExame) {

        $sSituacao = RequisicaoExame::CONFERIDO;
        $oDaoConferencia->la47_i_requiitem    = $oExame->iCodigoRequisicaoExame;
        $oDaoConferencia->la47_i_cid          = $oExame->iCodigoCID;
        $oDaoConferencia->la47_i_resultado    = 1;
        $oDaoConferencia->la47_i_procedimento = $oExame->iProcedimento;

        if ( !$oParam->lConferido ) {

          $sSituacao = RequisicaoExame::COLETADO;
          $oDaoConferencia->la47_i_resultado  = 2;
        }

        $oDaoConferencia->incluir(null);

        if ( $oDaoConferencia->erro_status == "0" ) {
          throw new DBException( _M(MSG_LAB_CONFERENCIARPC . "erro_salvar_conferencia") ."\n {$oDaoConferencia->erro_msg}" );
        }

        $oItemRequisicao = new RequisicaoExame($oExame->iCodigoRequisicaoExame);
        $oItemRequisicao->setSituacao( $sSituacao ) ;
        $oItemRequisicao->salvar();
        $oRetorno->sMensagem = urlencode( _M(MSG_LAB_CONFERENCIARPC . "sucesso_conferencia") );
      }

      db_fim_transacao();

      break;
  }
} catch ( Exception $oErro ) {

  db_fim_transacao( true );
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( str_replace( "\\n", "\n", $oErro->getMessage() ) );
}

echo $oJson->encode( $oRetorno );

