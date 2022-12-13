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
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson                  = new services_json();
$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->erro         = false;
$oRetorno->sMensagem    = '';

define("MENSAGENS", "tributario.meioambiente.amb1_empreendimentos.");

$oDaoEmpreendimento                 = db_utils::getDao("empreendimento");
$oDaoAtividadeImpacto               = db_utils::getDao("atividadeimpacto");
$oDaoAtividadeImpactoPorte          = db_utils::getDao("atividadeimpactoporte");
$oDaoEmpreendimentoAtividadeImpacto = db_utils::getDao("empreendimentoatividadeimpacto");

try {

  switch ($oParametros->sExecucao) {

    case "getDadosEmpreendedor":

      if( empty($oParametros->iCgmEmpreendedor) ){
        throw new BusinessException( _M( MENSAGENS . 'cgm_invalido' ) );
      }

      /**
       * Retorna dados do cgm para visualização
       */
      $iCgmEmpreendedor = $oParametros->iCgmEmpreendedor;
      try {

        $oCgmEmpreendedor = CgmFactory::getInstanceByCgm($iCgmEmpreendedor);

        $oDadosEmpreendedor = new StdClass();
        $oDadosEmpreendedor->isFisico      = true;
        $oDadosEmpreendedor->z01_numcgm    = $oCgmEmpreendedor->getCodigo();
        $oDadosEmpreendedor->z01_nome      = utf8_encode($oCgmEmpreendedor->getNome());

        /**
         * Valida se CGM é pessoa física
         */
        if ( !$oCgmEmpreendedor->isFisico() ) {

          $oDadosEmpreendedor->isFisico      = false;
          $oDadosEmpreendedor->z01_nomefanta = utf8_encode($oCgmEmpreendedor->getNomeFantasia());
          $oDadosEmpreendedor->z01_cgccpf    = $oCgmEmpreendedor->getCnpj();
        }else{
          $oDadosEmpreendedor->z01_cgccpf    = $oCgmEmpreendedor->getCpf();
        }

        $oDadosEmpreendedor->z01_ender  = utf8_encode($oCgmEmpreendedor->getLogradouro());
        $oDadosEmpreendedor->z01_cep    = $oCgmEmpreendedor->getCep();
        $oDadosEmpreendedor->z01_munic  = utf8_encode($oCgmEmpreendedor->getMunicipio());

      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      $oRetorno->oDadosEmpreendedor = $oDadosEmpreendedor;
    break;

    case "setEmpreendimento":

      db_inicio_transacao();

      if ( !empty( $oParametros->iCodigoEmpreendimento ) ) {

        $oEmpreendimento                 = new Empreendimento( $oParametros->iCodigoEmpreendimento );
        $oRetorno->sMensagem             = _M( MENSAGENS . 'sucesso_alterar_empreendimento' );
      } else {

        $oEmpreendimento                 = new Empreendimento();
        $oRetorno->sMensagem             = _M( MENSAGENS . 'sucesso_cadastrar_empreendimento' );
      }

      $oEmpreendimento->setNome(db_stdClass::normalizeStringJsonEscapeString($oParametros->sNome));
      $oEmpreendimento->setNomeFantasia(db_stdClass::normalizeStringJsonEscapeString($oParametros->sNomeFanta));
      $oEmpreendimento->setNumero($oParametros->iNumero);
      $oEmpreendimento->setComplemento(db_stdClass::normalizeStringJsonEscapeString($oParametros->sComplemento));
      $oEmpreendimento->setCep($oParametros->iCep);
      $oEmpreendimento->setBairro($oParametros->iCodigoBairro);
      $oEmpreendimento->setRuas($oParametros->iCodigoLogradouro);
      $oEmpreendimento->setCnpj($oParametros->iCnpj);
      $oEmpreendimento->setCgm( CgmFactory::getInstanceByCgm( $oParametros->iNumcgm ) );
      $oEmpreendimento->setAreaTotal($oParametros->nAreaTotal);
      $oEmpreendimento->setProtocolo($oParametros->iProcesso);

      try {

        $oEmpreendimento->processar();
        $oRetorno->iCodigoEmpreendimento = $oEmpreendimento->getSequencial();
        $oRetorno->sMensagem = urlencode( $oRetorno->sMensagem . " \nCódigo: {$oRetorno->iCodigoEmpreendimento}" );
      } catch (Exception $oError) {

        db_fim_transacao(true);
        throw BusinessException( _M( MENSAGENS . $oError->getMessage() ) );
      }

      db_fim_transacao(false);
    break;

    case "getEmpreendimento":

      if( empty( $oParametros->iCodigoEmpreendimento ) ){
        throw new BusinessException( _M( MENSAGENS . 'codigo_empreendimento_obrigatorio' ) );
      }

      $sSqlEmpreendimento = $oDaoEmpreendimento->sql_query( $oParametros->iCodigoEmpreendimento );
      $rsEmpreendimento   = $oDaoEmpreendimento->sql_record( $sSqlEmpreendimento );

      $oEmpreendimento     = db_utils::getCollectionByRecord($rsEmpreendimento, true, false, true);

      $oRetorno->oEmpreendimento = $oEmpreendimento;
    break;

  }

} catch (Exception $eErro){

  $oRetorno->erro      = true;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);