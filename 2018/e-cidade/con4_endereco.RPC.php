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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("model/endereco.model.php"));

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$iInstit  = db_getsession("DB_instit");
$oRetorno = $aRetorno = new stdClass();
$oRetorno->status  = "1";
$oRetorno->erro    = false;
$oRetorno->message = "";

switch ($oParam->exec) {

  case 'findCep':

    $aRetorno->endereco = endereco::findCep($oParam->codigoCep, $oParam->sNomeBairro);
    //print_r($aRetorno);die(' <<<');
    if ($aRetorno->endereco !== false) {
      $aRetorno->estados  = endereco::findEstadoByCodigoPais($aRetorno->endereco[0]->ipais);
    }

    echo $oJson->encode($aRetorno);

  break;

  case 'findEnderecoByCep':

    $oRetorno->endereco = endereco::findCep($oParam->codigoCep, $oParam->sNomeBairro);

    echo $oJson->encode($oRetorno);

  break;

  case 'findPaisByCodigo':

    $oRetorno->sNomePais = endereco::findPaisByCodigo($oParam->iCodigoPais);
    echo $oJson->encode($oRetorno);
  break;

  case 'findPaisByName':

    $aRetorno = endereco::findPaisByName($_POST["string"], true);
    echo $oJson->encode($aRetorno);
  break;

  case 'findEstadoByCodigoPais':

    $oRetorno->itens = endereco::findEstadoByCodigoPais($oParam->iCodigoPais);
    $oRetorno->pais  = endereco::findPaisDbConfig($iInstit);
    echo $oJson->encode($oRetorno);

  break;

  case 'findMunicipioByCodigo':

    $oRetorno->sNomeMunicipio   = endereco::findMunicipioByCodigo($oParam->iCodigoMunicipio, $oParam->iCodigoEstado);
    echo $oJson->encode($oRetorno);

  break;

  case 'findMunicipioByEstado':

    $oRetorno->aMunicipios = endereco::findMunicipioByEstado($oParam->iCodigoEstado);
    $aParametrosEndereco   = endereco::findParametrosEndereco(true);

    $oRetorno->iEstadoPadrao    = $aParametrosEndereco[0]->db72_cadenderestado;
    $oRetorno->iMunicipioPadrao = $aParametrosEndereco[0]->db72_sequencial;

    echo $oJson->encode($oRetorno);

  break;

  case 'findMunicipioByName':

    $aRetorno = endereco::findMunicipioByName(db_stdClass::normalizeStringJsonEscapeString($oParam->sQuery),$oParam->iCodigoEstado,true);
    echo $oJson->encode($aRetorno);
  break;

  case 'findBairroByCodigo':

    $oRetorno->sNomeBairro  = endereco::findBairroByCodigo($oParam->iCodigoBairro, $oParam->iCodigoMunicipio);
    echo $oJson->encode($oRetorno);
  break;

  case 'findBairroByName':

    $aRetorno = endereco::findBairroByName(db_stdClass::normalizeStringJsonEscapeString($oParam->sQuery),
                                           $oParam->iCodigoEstado,
                                           $oParam->iCodigoMunicipio,
                                           true);
    echo $oJson->encode($aRetorno);
  break;

  case 'findRuaByCodigo':

    $oRetorno->dados  = endereco::findRuaByCodigo($oParam->iCodigoRua, $oParam->iCodigoMunicipio);
    echo $oJson->encode($oRetorno);
  break;

  case 'findRuaByName':

    //$aRetorno = endereco::findRuaByName($oParam->sQuery,$oParam->iCodigoMunicipio,true);
    $aRetorno = endereco::findRuaByName(db_stdClass::normalizeStringJsonEscapeString($oParam->sQuery),
                                        $oParam->iCodigoEstado,
                                        $oParam->iCodigoMunicipio,
                                        $oParam->iCodigoBairro,
                                        true);



    echo $oJson->encode($aRetorno);
  break;

  case 'findNumeroByNumero':

    $oRetorno->dados  = endereco::findNumeroByNumero($oParam->iCodigoNumero,$oParam->iCodigoBairro,$oParam->iCodigoRua);
    //ByCodigo($oParam->iCodigoRua, $oParam->iCodigoMunicipio);
    echo $oJson->encode($oRetorno);
  break;

  case 'findCondominioByName':

    $aRetorno = endereco::findCondominioByName(db_stdClass::normalizeStringJsonEscapeString($oParam->sQuery), $oParam->iCodigoNumero,
                                               $oParam->iCodigoBairro, $oParam->iCodigoRua, true);
    echo $oJson->encode($aRetorno);

  break;

  case 'findLoteamentoByName':

    $aRetorno = endereco::findLoteamentoByName(db_stdClass::normalizeStringJsonEscapeString($oParam->sQuery),        $oParam->iCodigoNumero,
                                               $oParam->iCodigoBairro, $oParam->iCodigoRua, true);
    echo $oJson->encode($aRetorno);

  break;

  case 'findComplementoRua':

    $aRetorno = endereco::findComplementoRua($oParam->iCodigoRua, $oParam->iCodigoMunicipio, $oParam->iCodigoBairro);
    echo $oJson->encode($aRetorno);
  break;

  case 'findComplementoBairro':

    $aRetorno = endereco::findComplementoBairro($oParam->iCodigoBairro);
    echo $oJson->encode($aRetorno);
  break;

  case 'salvarEndereco':

    db_inicio_transacao();
    try {

      $oEndereco = new endereco(null);

      $oEndereco->setCodigoEstado($oParam->endereco->codigoEstado);
      $oEndereco->setCodigoMunicipio($oParam->endereco->codigoMunicipio);
      $oEndereco->setCodigoBairro($oParam->endereco->codigoBairro);
      $oEndereco->setDescricaoBairro(db_stdClass::normalizeStringJsonEscapeString(($oParam->endereco->descricaoBairro)));
      $oEndereco->setCodigoRua($oParam->endereco->codigoRua);
      $oEndereco->setDescricaoRua(db_stdClass::normalizeStringJsonEscapeString($oParam->endereco->descricaoRua));
      $oEndereco->setCodigoLocal($oParam->endereco->codigoLocal);
      $oEndereco->setCodigoEndereco($oParam->endereco->codigoEndereco);
      $oEndereco->setNumeroLocal($oParam->endereco->numeroLocal);
      $oEndereco->setComplementoEndereco(db_stdClass::normalizeStringJsonEscapeString($oParam->endereco->descricaoComplemento));
      $oEndereco->setLoteamentoEndereco(db_stdClass::normalizeStringJsonEscapeString($oParam->endereco->descricaoLoteamento));
      $oEndereco->setCondominioEndereco(db_stdClass::normalizeStringJsonEscapeString($oParam->endereco->descricaoCondominio));
      $oEndereco->setPontoReferenciaEndereco(db_stdClass::normalizeStringJsonEscapeString($oParam->endereco->descricaoPontoReferencia));
      $oEndereco->setCepEndereco($oParam->endereco->cepEndereco);
      $oEndereco->setCep($oParam->endereco->cepEndereco);
      $oEndereco->setCadEnderRuaTipo($oParam->endereco->codigoRuaTipo);
      $oEndereco->setCodigoRuasTipo($oParam->endereco->codigoRuasTipo);
      $oEndereco->setCodigoCep($oParam->endereco->codigoCepRua);
      $oEndereco->salvaEndereco();

      db_fim_transacao(false);

      $aRetorno->icodigoEndereco  = $oEndereco->getCodigoEndereco();
      $aRetorno->icodigoMunicipio = $oEndereco->getCodigoMunicipio();
      $aRetorno->icodigoBairro    = $oEndereco->getCodigoBairro();
      $aRetorno->icodigoRua       = $oEndereco->getCodigoRua();
      $aRetorno->message = urlencode("Endereco ($aRetorno->icodigoEndereco) incluído com sucesso!") ;
      $aRetorno->status   = 1;

    } catch (Exception $erro) {

      db_fim_transacao(true);
      $aRetorno->message  = urlencode($erro->getMessage());
      $aRetorno->status   = 2;
    }

    echo $oJson->encode($aRetorno);

  break;

  case 'buscaValoresPadrao':

    try {

      $oRetorno->cepmunic      = endereco::findCepDbConfig($iInstit);
      $oRetorno->tiposRua      = endereco::findRuasTipo();
      $oRetorno->valoresPadrao = endereco::findParametrosEndereco();

      if ($oRetorno->valoresPadrao === false) {
        throw new Exception("usuário: \n\nParâmetros do endereço não configurados!\n\nContate o Administrador.\n\n");
      }

      $aEstados = array();

      /**
       * estados pelo pais padrao
       */
      if ( !empty($oRetorno->valoresPadrao[0]->db70_sequencial) ) {
        $aEstados = endereco::findEstadoByCodigoPais($oRetorno->valoresPadrao[0]->db70_sequencial);
      }
      $oRetorno->estados = $aEstados;
      $oRetorno->aPaises = endereco::getPaises();

    } catch(Exception $oErro) {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode($oErro->getMessage());
    }

    echo $oJson->encode($oRetorno);

  break;

  case 'buscaBairroRuaMunicipio':

    $oRetorno->status        = 1;
    $oRetorno->tiposRua      = endereco::findRuasTipo();
    $oRetorno->valoresPadrao = endereco::findParametrosEndereco();
    $oRetorno->cepmunic      = endereco::findCepDbConfig($iInstit);

    if ($oRetorno->valoresPadrao !== false) {
      $oRetorno->estados = endereco::findEstadoByCodigoPais($oRetorno->valoresPadrao[0]->db70_sequencial);
    } else {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode("Parâmetros do endereço não configurados!\n\n Contate o Administrador.\n\n");
    }

    $oParam->lCgmMunicipio = isset($oParam->lCgmMunicipio) ? $oParam->lCgmMunicipio : false;
    $iCodigoMunicipio      = $oParam->lCgmMunicipio ? $oRetorno->valoresPadrao[0]->db72_sequencial : null;
    $oRetorno->bairroRuaMunicipio = endereco::buscaBairroRuaMunicipio((int) $oParam->icodigobairromunicipio,
                                                                      (int) $oParam->icodigoruamunicipio,
                                                                      $iCodigoMunicipio,
                                                                      true);

    if ($oRetorno->bairroRuaMunicipio == false) {

      db_inicio_transacao();
      try {

        $oDados = endereco::findBairroRuaMunicipio($oParam->icodigobairromunicipio,
                                        $oParam->icodigoruamunicipio);

        $oEndereco = new endereco(null);
        $oEndereco->setCodigoMunicipio($oDados->codigoMunicipio);
        $oEndereco->setCodigoBairro($oDados->codigoBairro);
        $oEndereco->setDescricaoBairro($oDados->descrBairro);
        $oEndereco->setCodigoRua($oDados->codigoEndereco);
        $oEndereco->setDescricaoRua($oDados->descrEndereco);
        $oEndereco->setCadEnderRuaTipo($oDados->ruaTipo);
        $oEndereco->setRuaCadEnderRuaRuas($oDados->codigoEndereco);
        $oEndereco->setRuasCadEnderRuaRuas($oDados->codigoRuas);
        $oEndereco->cadEnderBairroRuaMunicipio();

        db_fim_transacao(false);

      } catch (Exception $erro) {

        db_fim_transacao(true);
        $oRetorno->message  = urlencode($erro->getMessage());
        $oRetorno->status   = 2;
      }
    }

    if ($oRetorno->status == 1) {
      $oRetorno->bairroRuaMunicipio = endereco::buscaBairroRuaMunicipio((int) $oParam->icodigobairromunicipio,
                                                                        (int) $oParam->icodigoruamunicipio,
                                                                        $iCodigoMunicipio,
                                                                        true);
    }

    echo $oJson->encode($oRetorno);

  break;

  case 'buscaEndereco':

    try{

     $oEndereco = new endereco($oParam->icodigoendereco);

     $oRetorno->endereco = new stdClass();

     $oRetorno->endereco->iPais             = $oEndereco->getCodigoPais();
     $oRetorno->endereco->sPais             = urlencode($oEndereco->getDescricaoPais());

     $oRetorno->endereco->iEstado           = $oEndereco->getCodigoEstado();
     $oRetorno->endereco->sEstado           = urlencode($oEndereco->getDescricaoEstado());

     $oRetorno->endereco->iMunicipio        = $oEndereco->getCodigoMunicipio();
     $oRetorno->endereco->sMunicipio        = urlencode($oEndereco->getDescricaoMunicipio());

     $oRetorno->endereco->iBairro           = $oEndereco->getCodigoBairro();
     $oRetorno->endereco->sBairro           = urlencode($oEndereco->getDescricaoBairro());

     $oRetorno->endereco->iRua              = $oEndereco->getCodigoRua();
     $oRetorno->endereco->sRua              = urlencode($oEndereco->getDescricaoRua());

     $oRetorno->endereco->iRuaTipo          = $oEndereco->getCadEnderRuaTipo();
     $oRetorno->endereco->iRuasTipo         = $oEndereco->getCodigoRuasTipo();

     $oRetorno->endereco->iLocal            = $oEndereco->getCodigoLocal();
     $oRetorno->endereco->sNumeroLocal      = urlencode($oEndereco->getNumeroLocal());

     $oRetorno->endereco->iEndereco         = $oEndereco->getCodigoEndereco();
     $oRetorno->endereco->sCondominio       = urlencode($oEndereco->getCondominioEndereco());
     $oRetorno->endereco->sLoteamento       = urlencode($oEndereco->getLoteamentoEndereco());
     $oRetorno->endereco->sComplemento      = urlencode($oEndereco->getComplementoEndereco());
     $oRetorno->endereco->sPontoReferencia  = urlencode($oEndereco->getPontoReferenciaEndereco());
     $oRetorno->endereco->sCep              = urlencode($oEndereco->getCepEndereco());
     $oRetorno->endereco->iCep              = $oEndereco->getCodigoCep();

     $oRetorno->aPaises          = endereco::getPaises();
     $oRetorno->estados          = endereco::findEstadoByCodigoPais($oRetorno->endereco->iPais);
     $oRetorno->iCodigoMunicipio = $oEndereco->getCodigoMunicipio();
     $oRetorno->tiposRua         = endereco::findRuasTipo();

    } catch (Exception $erro) {

      $oRetorno->status   = 2;
      $oRetorno->message  = urlencode($erro->getMessage());
    }

    echo $oJson->encode($oRetorno);

    break;
  case 'buscaEnderecoCidadao':
    /*
    $oRetorno->pais      = endereco::findPaisDbConfig($iInstit);
    $oRetorno->municipio = endereco::findMunicipioDbConfig($iInstit, $oRetorno->pais[0]->db71_sequencial);
    $oRetorno->tiposRua  = endereco::findRuasTipo();
    $oRetorno->estados   = endereco::findEstadoByCodigoPais($oRetorno->pais[0]->db70_sequencial);
    */
    $oRetorno->tiposRua      = endereco::findRuasTipo();
    $oRetorno->valoresPadrao = endereco::findParametrosEndereco();

    if ($oRetorno->valoresPadrao !== false) {
      $oRetorno->estados   = endereco::findEstadoByCodigoPais($oRetorno->valoresPadrao[0]->db70_sequencial);
    } else {
      $oRetorno->status = 2;
      $oRetorno->message = urlencode("\n\nusuário: \n\nParâmetros do endereço não configurados!\n\n Contate o Administrador.\n\n");
    }

    $oRetorno->enderecocidadao = endereco::buscaEnderecoCidadao($oParam->ov02_sequencial,$oParam->ov02_seq);

    echo $oJson->encode($oRetorno);

  break;
}
?>