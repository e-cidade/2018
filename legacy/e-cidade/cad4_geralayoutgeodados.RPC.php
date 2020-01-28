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

set_time_limit(0);

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("model/cadastro/Lote.model.php"));

$oJson              = new services_json();

$oRetorno           = new stdClass();

$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno->iStatus  = 1;

$oRetorno->sMessage = '';

$aDadosRetorno      = array();

$sTmpNomeArquivo    = "tmp/GEODADOS_{$oParam->iFormato}_" . db_getsession('DB_id_usuario') . "_" . date("dmYHisu") . ".csv";

$sZipTmpNomeArquivo = "GEODADOS_{$oParam->iFormato}_" . db_getsession('DB_id_usuario') . "_" . date("dmYHisu") . "_CPT";

if ($oParam->iFormato == 1) {

  $iCodigoLayout = 185;

} else if ($oParam->iFormato == 2) {

  $iCodigoLayout = 186;

} else {

  $iCodigoLayout = 187;

}

try {

  switch ($oParam->exec) {

    case "geraDados":

      $oDaoCargrup = db_utils::getDao('cargrup');

      $rsCargrup   = $oDaoCargrup->sql_record($oDaoCargrup->sql_query_file(null, "*", "j32_grupo"));
      $aCaracteristicasDisponiveis = db_utils::getCollectionByRecord($rsCargrup);

      $oDaoLote   = db_utils::getDao('lote');
      $rsGeodados = $oDaoLote->sql_record($oDaoLote->sql_queryGeodados());
      $aGeodados  = db_utils::getCollectionByRecord($rsGeodados);
      switch ($oParam->iFormato) {

        case 1:   // GEODADOS

          $oLayoutTxt = new db_layouttxt($iCodigoLayout, $sTmpNomeArquivo);

          layout1($aGeodados, $oLayoutTxt);

          $oRetorno->sNomeArquivo = $sTmpNomeArquivo;

          break;

        case 2:   // VERSAO

          layout2($aGeodados, $aCaracteristicasDisponiveis, $sTmpNomeArquivo, $sZipTmpNomeArquivo, $oParam->sSeparador);

          $oRetorno->sNomeArquivo = "tmp/".$sZipTmpNomeArquivo.".zip";

          break;

        case 3:   // LISTA PONTOS

          $oLayoutTxt = new db_layouttxt($iCodigoLayout, $sTmpNomeArquivo);

          layout3($aGeodados, $oLayoutTxt, $aCaracteristicasDisponiveis);

          $oRetorno->sNomeArquivo = $sTmpNomeArquivo;

          break;

        default:

          throw new ErrorException("Nenhuma Opção Definida");

        break;

      }

      break;

    default:

      throw new ErrorException("Nenhuma Opção Definida");

    break;
  }

  $oRetorno->sMessage = urlencode($oRetorno->sMessage);

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());

}

echo $oJson->encode($oRetorno);

function layout1($aGeodados, $oLayoutTxt) {

  $oGeodadosHeader = new stdClass();

  //cabeçalhos das colunas
  $oGeodadosHeader->cadastro              = "cadastro            ";
  $oGeodadosHeader->codbai                = "codbai              ";
  $oGeodadosHeader->codqua                = "codqua              ";
  $oGeodadosHeader->codlot                = "codlot              ";
  $oGeodadosHeader->unidade               = "unidade             ";
  $oGeodadosHeader->proprietario          = "proprietario        ";
  $oGeodadosHeader->codlog                = "codlog              ";
  $oGeodadosHeader->tipo                  = "tipo                ";
  $oGeodadosHeader->logradouro            = "logradouro          ";
  $oGeodadosHeader->numero                = "numero              ";
  $oGeodadosHeader->complemento           = "complemento         ";
  $oGeodadosHeader->codbaim               = "codbaim             ";
  $oGeodadosHeader->bairro                = "bairro              ";
  $oGeodadosHeader->cep                   = "cep                 ";
  $oGeodadosHeader->prefterreno           = "prefterreno         ";
  $oGeodadosHeader->prefconstruida        = "prefconstruida      ";
  $oGeodadosHeader->preftestada           = "preftestada         ";
  $oGeodadosHeader->vlrvenalterreno       = "vlrvenalterreno     ";
  $oGeodadosHeader->vlrvenalconstrucao    = "vlrvenalconstrucao  ";
  $oGeodadosHeader->vlrimpostoterreno     = "vlrimpostoterreno   ";
  $oGeodadosHeader->vlrimpostoconstrucao  = "vlrimpostoconstrucao";
  $oGeodadosHeader->codisento             = "codisento           ";
  $oGeodadosHeader->descricaoisento       = "descricaoisento     ";
  $oGeodadosHeader->ender_entrega         = "ender_entrega       ";
  $oGeodadosHeader->numero_entrega        = "numero_entrega      ";
  $oGeodadosHeader->compl_entrega         = "compl_entrega       ";
  $oGeodadosHeader->bairro_entrega        = "bairro_entrega      ";
  $oGeodadosHeader->munic_entrega         = "munic_entrega       ";
  $oGeodadosHeader->uf_entrega            = "uf_entrega          ";
  $oGeodadosHeader->cep_entrega           = "cep_entrega         ";
  $oGeodadosHeader->cxpostal_entrega      = "cxpostal_entrega    ";

  if( $oLayoutTxt->setByLineOfDBUtils($oGeodadosHeader, "1") == false ) {

    throw new Exception ("[1] - Erro ao gerar linha do arquivo");

  }

  $oGeodadosLayout1 = new stdClass();

  foreach ($aGeodados as $oGeodados) {

    $oGeodadosLayout1->cadastro              = "";
    $oGeodadosLayout1->codbai                = "";
    $oGeodadosLayout1->codqua                = "";
    $oGeodadosLayout1->codlot                = "";
    $oGeodadosLayout1->unidade               = "";
    $oGeodadosLayout1->proprietario          = "";
    $oGeodadosLayout1->promitente            = "";

    $oGeodadosLayout1->codlog                = "";
    $oGeodadosLayout1->tipo                  = "";
    $oGeodadosLayout1->logradouro            = "";
    $oGeodadosLayout1->numero                = "";
    $oGeodadosLayout1->complemento           = "";
    $oGeodadosLayout1->codbaim               = "";
    $oGeodadosLayout1->bairro                = "";
    $oGeodadosLayout1->cep                   = "";
    $oGeodadosLayout1->prefterreno           = "";
    $oGeodadosLayout1->prefconstruida        = "";
    $oGeodadosLayout1->preftestada           = "";
    $oGeodadosLayout1->vlrvenalterreno       = "";
    $oGeodadosLayout1->vlrvenalconstrucao    = "";
    $oGeodadosLayout1->vlrimpostoterreno     = "";
    $oGeodadosLayout1->vlrimpostoconstrucao  = "";
    $oGeodadosLayout1->codisento             = "";
    $oGeodadosLayout1->descricaoisento       = "";
    $oGeodadosLayout1->ender_entrega         = "";
    $oGeodadosLayout1->numero_entrega        = "";
    $oGeodadosLayout1->compl_entrega         = "";
    $oGeodadosLayout1->bairro_entrega        = "";
    $oGeodadosLayout1->munic_entrega         = "";
    $oGeodadosLayout1->uf_entrega            = "";
    $oGeodadosLayout1->cep_entrega           = "";
    $oGeodadosLayout1->cxpostal_entrega      = "";

    $oLote = new Lote($oGeodados->j01_idbql);

    $oGeodadosLayout1->codbai      = $oLote->getCodigoSetor();
    $oGeodadosLayout1->codqua      = $oLote->getQuadra();
    $oGeodadosLayout1->codlot      = $oLote->getLote();
    $oGeodadosLayout1->codlog      = $oLote->getCodigoLogradouro();
    $oGeodadosLayout1->tipo        = $oLote->getCodigoTipoLogradouro();
    $oGeodadosLayout1->logradouro  = $oLote->getLogradouro();
    $oGeodadosLayout1->codbaim     = $oLote->getCodigoBairro();
    $oGeodadosLayout1->bairro      = $oLote->getBairro();
    $oGeodadosLayout1->cep         = $oLote->getCep();
    $oGeodadosLayout1->prefterreno = $oLote->getAreaLote();
    $oGeodadosLayout1->preftestada = $oLote->getValorTestadaLote();

    $aImoveis         = $oLote->getImoveis();

    foreach ($aImoveis as $oImovel) {

      $oGeodadosLayout1->cadastro     = $oImovel->getMatricula();
      $oGeodadosLayout1->proprietario = $oImovel->getProprietarioPrincipal()->getNome();
      $oGeodadosLayout1->promitente   = $oImovel->getPromitentePrincipal()->getNome();

      $oIsencao = $oImovel->getDadosIsencaoExercicio();
      $oGeodadosLayout1->codisento       = $oIsencao->iTipoIsencao;
      $oGeodadosLayout1->descricaoisento = $oIsencao->sDescricaoIsencao;

      $oCalculo     = $oImovel->getCalculo();

      if($oCalculoIptu = $oCalculo->getCalculoValorIptu()) {

        $oGeodadosLayout1->vlrvenalterreno   = $oCalculoIptu->nValorTerreno;
        $oGeodadosLayout1->vlrimpostoterreno = $oCalculoIptu->nValorTerreno * ($oCalculoIptu->nAliquota / 100);

      }

      $oImovelEndereco = $oImovel->getImovelEndereco();

      $oGeodadosLayout1->ender_entrega    = $oImovelEndereco->getEndereco();
      $oGeodadosLayout1->numero_entrega   = $oImovelEndereco->getNumero();
      $oGeodadosLayout1->compl_entrega    = $oImovelEndereco->getComplemento();
      $oGeodadosLayout1->bairro_entrega   = $oImovelEndereco->getBairro();
      $oGeodadosLayout1->munic_entrega    = $oImovelEndereco->getMunicipio();
      $oGeodadosLayout1->uf_entrega       = $oImovelEndereco->getUf();
      $oGeodadosLayout1->cep_entrega      = $oImovelEndereco->getCep();
      $oGeodadosLayout1->cxpostal_entrega = $oImovelEndereco->getCaixaPostal();

      $aConstrucoes     = $oImovel->getConstrucoes();

      if (count($aConstrucoes) > 0) {

        foreach ($aConstrucoes as $oConstrucao) {

          $oGeodadosLayout1->unidade     = $oConstrucao->getCodigoConstrucao();
          $oGeodadosLayout1->numero      = $oConstrucao->getNumeroEndereco();
          $oGeodadosLayout1->complemento = $oConstrucao->getComplementoEndereco();

          if($oCalculoConstrucao = $oCalculo->getCalculoConstrucao($oConstrucao->getCodigoConstrucao())) {

            $oGeodadosLayout1->prefconstruida       = $oCalculoConstrucao->nAreaConstruida;
            $oGeodadosLayout1->vlrvenalconstrucao   = $oCalculoConstrucao->nValor;
            $oGeodadosLayout1->vlrimpostoconstrucao = $oCalculoConstrucao->nValor *  ($oCalculoIptu->nAliquota / 100);

          }

          if( $oLayoutTxt->setByLineOfDBUtils($oGeodadosLayout1, "3") == false ) {

            throw new Exception ("[ 1 ] - Erro ao gerar linha do arquivo");

          }

        }

      } else {

        if( $oLayoutTxt->setByLineOfDBUtils($oGeodadosLayout1, "3") == false ) {

          throw new Exception ("[ 1 ] - Erro ao gerar linha do arquivo");

        }

      }

    }

  }

}

function layout2($aGeodados, $aCaracteristicasDisponiveis, $sNomeArquivo, $sZipTmpNomeArquivo, $sSeparador) {

  $pArquivo = fopen($sNomeArquivo, 'w');

  $oGeodadosLayout2 = new stdClass();

  $oGeodadosLayout2->matricula                            = "matricula                    ";
  $oGeodadosLayout2->proprietario                         = "proprietario                 ";
  $oGeodadosLayout2->promitente                           = "promitente                   ";
  $oGeodadosLayout2->codigo_setor                         = "codigo_setor                 ";
  $oGeodadosLayout2->codigo_quadra                        = "codigo_quadra                ";
  $oGeodadosLayout2->codigo_lote                          = "codigo_lote                  ";
  $oGeodadosLayout2->codigo_construcao                    = "codigo_construcao            ";
  $oGeodadosLayout2->rua_codigo                           = "rua_codigo                   ";
  $oGeodadosLayout2->rua_nome                             = "rua_nome                     ";
  $oGeodadosLayout2->construcao_numero                    = "construcao_numero            ";
  $oGeodadosLayout2->construcao_complemento               = "construcao_complemento       ";
  $oGeodadosLayout2->bairro_codigo                        = "bairro_codigo                ";
  $oGeodadosLayout2->bairro_descricao                     = "bairro_descricao             ";
  $oGeodadosLayout2->rua_cep                              = "rua_cep                      ";
  $oGeodadosLayout2->lote_codigo_loteamento               = "lote_codigo_loteamento       ";
  $oGeodadosLayout2->lote_descricao_loteamento            = "lote_descricao_loteamento    ";
  $oGeodadosLayout2->lote_area                            = "lote_area                    ";
  $oGeodadosLayout2->construcao_area                      = "construcao_area              ";
  $oGeodadosLayout2->valor_testada_lote                   = "valor_testada_lote           ";
  $oGeodadosLayout2->valor_venal_terreno                  = "valor_venal_terreno          ";
  $oGeodadosLayout2->valor_venal_construcao               = "valor_venal_construcao       ";
  $oGeodadosLayout2->valor_iptu_terreno                   = "valor_iptu_terreno           ";
  $oGeodadosLayout2->valor_iptu_construcao                = "valor_iptu_construcao        ";
  $oGeodadosLayout2->isencao_codigo                       = "isencao_codigo               ";
  $oGeodadosLayout2->isencao_descricao                    = "isencao_descricao            ";
  $oGeodadosLayout2->endereco_entrega                     = "endereco_entrega             ";
  $oGeodadosLayout2->endereco_entrega_numero              = "endereco_entrega_numero      ";
  $oGeodadosLayout2->endereco_entrega_complemento         = "endereco_entrega_complemento ";
  $oGeodadosLayout2->endereco_entrega_bairro              = "endereco_entrega_bairro      ";
  $oGeodadosLayout2->endereco_entrega_municipio           = "endereco_entrega_municipio   ";
  $oGeodadosLayout2->endereco_entrega_uf                  = "endereco_entrega_uf          ";
  $oGeodadosLayout2->endereco_entrega_cep                 = "endereco_entrega_cep         ";
  $oGeodadosLayout2->endereco_entrega_caixapostal         = "endereco_entrega_caixapostal ";
  $oGeodadosLayout2->referencia_anterior                  = "referencia_anterior          ";
  $oGeodadosLayout2->rua_tipo_testada                     = "rua_tipo_testada       			";
  $oGeodadosLayout2->rua_tipo_sigla_testada               = "rua_tipo_sigla_testada 			";

  $i = 1;
  foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

    if ($oCaracteristicaDisponivel->j32_tipo == 'F') {

      $sCaracteristicasFaceTipo    = "caracteristicas_face_tipo_"      . $i;
      $sCaracteristicasFaceIdGrupo = "caracteristicas_face_id_grupo_"  . $i;
      $sCaracteristicasFaceGrupo   = "caracteristicas_face_grupo_"     . $i;
      $sCaracteristicasFaceId      = "caracteristicas_face_id_"        . $i;
      $sCaracteristicasFaceCar     = "caracteristicas_face_descricao_" . $i;
      $sCaracteristicasFacePontos  = "caracteristicas_face_pontos_"    . $i;

      $oGeodadosLayout2->$sCaracteristicasFaceTipo    = "face_tipo_"      . $i;
      $oGeodadosLayout2->$sCaracteristicasFaceIdGrupo = "face_id_grupo_"  . $i;
      $oGeodadosLayout2->$sCaracteristicasFaceGrupo   = "face_grupo_"     . $i;
      $oGeodadosLayout2->$sCaracteristicasFaceId      = "face_id_"        . $i;
      $oGeodadosLayout2->$sCaracteristicasFaceCar     = "face_descricao_" . $i;
      $oGeodadosLayout2->$sCaracteristicasFacePontos  = "face_pontos_"    . $i;

      $i++;
    }

  }
  $iQdteCaracteristicasFace = $i - 1;

  $i = 1;
  foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

    if ($oCaracteristicaDisponivel->j32_tipo == 'L') {

      $sCaracteristicasLoteTipo    = "caracteristicas_lote_tipo_"      . $i;
      $sCaracteristicasLoteIdGrupo = "caracteristicas_lote_id_grupo_"  . $i;
      $sCaracteristicasLoteGrupo   = "caracteristicas_lote_grupo_"     . $i;
      $sCaracteristicasLoteId      = "caracteristicas_lote_id_"        . $i;
      $sCaracteristicasLoteCar     = "caracteristicas_lote_descricao_" . $i;
      $sCaracteristicasLotePontos  = "caracteristicas_lote_pontos_"    . $i;

      $oGeodadosLayout2->$sCaracteristicasLoteTipo    = "lote_tipo_"      . $i;
      $oGeodadosLayout2->$sCaracteristicasLoteIdGrupo = "lote_id_grupo_"  . $i;
      $oGeodadosLayout2->$sCaracteristicasLoteGrupo   = "lote_grupo_"     . $i;
      $oGeodadosLayout2->$sCaracteristicasLoteId      = "lote_id_"        . $i;
      $oGeodadosLayout2->$sCaracteristicasLoteCar     = "lote_descricao_" . $i;
      $oGeodadosLayout2->$sCaracteristicasLotePontos  = "lote_pontos_"    . $i;

      $i++;
    }

  }

  $iQdteCaracteristicasLote = $i - 1;

  $i = 1;
  foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

    if ($oCaracteristicaDisponivel->j32_tipo == 'C') {

      $sCaracteristicasTipo    = "caracteristicas_construcao_tipo_"      . $i;
      $sCaracteristicasIdGrupo = "caracteristicas_construcao_id_grupo_"  . $i;
      $sCaracteristicasGrupo   = "caracteristicas_construcao_grupo_"     . $i;
      $sCaracteristicasId      = "caracteristicas_construcao_id_"        . $i;
      $sCaracteristicasCar     = "caracteristicas_construcao_descricao_" . $i;
      $sCaracteristicasPontos  = "caracteristicas_construcao_pontos_"    . $i;

      $oGeodadosLayout2->$sCaracteristicasTipo    = "construcao_tipo_"      . $i;
      $oGeodadosLayout2->$sCaracteristicasIdGrupo = "construcao_id_grupo_"  . $i;
      $oGeodadosLayout2->$sCaracteristicasGrupo   = "construcao_grupo_"     . $i;
      $oGeodadosLayout2->$sCaracteristicasId      = "construcao_id_"        . $i;
      $oGeodadosLayout2->$sCaracteristicasCar     = "construcao_descricao_" . $i;
      $oGeodadosLayout2->$sCaracteristicasPontos  = "construcao_pontos_"    . $i;

      $i++;
    }

  }

  $iQdteCaracteristicasConstrucao = $i - 1;

  $oGeodadosLayout2->iQdteCaracteristicasFace       = $iQdteCaracteristicasFace      ;
  $oGeodadosLayout2->iQdteCaracteristicasLote       = $iQdteCaracteristicasLote      ;
  $oGeodadosLayout2->iQdteCaracteristicasConstrucao = $iQdteCaracteristicasConstrucao;

  geraLinhaArquivo($pArquivo, $oGeodadosLayout2, $sSeparador, true);

  $oGeodadosLayout2 = new stdClass();

  foreach ($aGeodados as $oGeodados) {

    $oGeodadosLayout2->iQdteCaracteristicasFace       = $iQdteCaracteristicasFace      ;
    $oGeodadosLayout2->iQdteCaracteristicasLote       = $iQdteCaracteristicasLote      ;
    $oGeodadosLayout2->iQdteCaracteristicasConstrucao = $iQdteCaracteristicasConstrucao;

    $oGeodadosLayout2->matricula                      = "";
    $oGeodadosLayout2->proprietario                   = "";
    $oGeodadosLayout2->promitente                     = "";
    $oGeodadosLayout2->codigo_setor                   = "";
    $oGeodadosLayout2->codigo_quadra                  = "";
    $oGeodadosLayout2->codigo_lote                    = "";
    $oGeodadosLayout2->codigo_construcao              = "";
    $oGeodadosLayout2->rua_codigo                     = "";
    $oGeodadosLayout2->rua_nome                       = "";
    $oGeodadosLayout2->construcao_numero              = "";
    $oGeodadosLayout2->construcao_complemento         = "";
    $oGeodadosLayout2->bairro_codigo                  = "";
    $oGeodadosLayout2->bairro_descricao               = "";
    $oGeodadosLayout2->rua_cep                        = "";
    $oGeodadosLayout2->lote_codigo_loteamento         = "";
    $oGeodadosLayout2->lote_descricao_loteamento      = "";
    $oGeodadosLayout2->lote_area                      = "";
    $oGeodadosLayout2->construcao_area                = "";
    $oGeodadosLayout2->valor_testada_lote             = "";
    $oGeodadosLayout2->valor_venal_terreno            = "";
    $oGeodadosLayout2->valor_venal_construcao         = "";
    $oGeodadosLayout2->valor_iptu_terreno             = "";
    $oGeodadosLayout2->valor_iptu_construcao          = "";
    $oGeodadosLayout2->isencao_codigo                 = "";
    $oGeodadosLayout2->isencao_descricao              = "";
    $oGeodadosLayout2->endereco_entrega               = "";
    $oGeodadosLayout2->endereco_entrega_numero        = "";
    $oGeodadosLayout2->endereco_entrega_complemento   = "";
    $oGeodadosLayout2->endereco_entrega_bairro        = "";
    $oGeodadosLayout2->endereco_entrega_municipio     = "";
    $oGeodadosLayout2->endereco_entrega_uf            = "";
    $oGeodadosLayout2->endereco_entrega_cep           = "";
    $oGeodadosLayout2->endereco_entrega_caixapostal   = "";
    $oGeodadosLayout2->referencia_anterior            = "";
    $oGeodadosLayout2->rua_tipo_sigla_testada         = "";
    $oGeodadosLayout2->caracteristicas_face           = "";
    $oGeodadosLayout2->caracteristicas_lote           = "";
    $oGeodadosLayout2->caracteristicas_construcao     = "";

    $oLote            = new Lote($oGeodados->j01_idbql);

    $oGeodadosLayout2->codigo_setor              = $oLote->getCodigoSetor();
    $oGeodadosLayout2->codigo_quadra             = $oLote->getQuadra();
    $oGeodadosLayout2->codigo_lote               = $oLote->getLote();
    $oGeodadosLayout2->rua_codigo                = $oLote->getCodigoLogradouro();
    $oGeodadosLayout2->rua_nome                  = $oLote->getLogradouro();
    $oGeodadosLayout2->bairro_codigo             = $oLote->getCodigoBairro();
    $oGeodadosLayout2->bairro_descricao          = $oLote->getBairro();
    $oGeodadosLayout2->rua_cep                   = $oLote->getCep();
    $oGeodadosLayout2->lote_codigo_loteamento    = $oLote->getCodigoLoteamento();
    $oGeodadosLayout2->lote_descricao_loteamento = $oLote->getDescricaoLoteamento();
    $oGeodadosLayout2->lote_area                 = $oLote->getAreaLote();
    $oGeodadosLayout2->valor_testada_lote        = $oLote->getValorTestadaLote();
    $oGeodadosLayout2->rua_tipo_testada          = $oLote->getCodigoTipoLogradouro();
    $oGeodadosLayout2->rua_tipo_sigla_testada    = $oLote->getSiglaTipoLogradouro();

    $aCaracteristicasFaceLote = array();
    $aCaracteristicasLote     = array();

    foreach ($oLote->getCaracteristicasFace() as $oCaracteristicaFace) {
      $aCaracteristicasFaceLote[$oCaracteristicaFace->iCodigoGrupo] = $oCaracteristicaFace;
    }

    foreach ($oLote->getCaracteristicasLote() as $oCaracteristicaLote) {
      $aCaracteristicasLote[$oCaracteristicaLote->iCodigoGrupo] = $oCaracteristicaLote;
    }

    $iFace = 1;
    $iLote = 1;
    $iConstrucao = 1;
    foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

      if($oCaracteristicaDisponivel->j32_tipo == 'F') {

        $sCaracteristicaFaceTipo    = "caracteristicas_face_tipo_"     . $iFace;
        $iCaracteristicaFaceIdGrupo = "caracteristicas_face_id_grupo_" . $iFace;
        $sCaracteristicaFaceGrupo   = "caracteristicas_face_grupo_"    . $iFace;

        $oGeodadosLayout2->$sCaracteristicaFaceTipo    = $oCaracteristicaDisponivel->j32_tipo ;
        $oGeodadosLayout2->$iCaracteristicaFaceIdGrupo = $oCaracteristicaDisponivel->j32_grupo;
        $oGeodadosLayout2->$sCaracteristicaFaceGrupo   = $oCaracteristicaDisponivel->j32_descr;

        $iCaracteristicaFaceId     = "caracteristicas_face_id_"        . $iFace;
        $sCaracteristicaFaceCar    = "caracteristicas_face_descricao_" . $iFace;
        $iCaracteristicaFacePontos = "caracteristicas_face_pontos_"    . $iFace;
        if (isset($aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo])) {

          $oGeodadosLayout2->$iCaracteristicaFaceId     = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
          $oGeodadosLayout2->$sCaracteristicaFaceCar    = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica      ;
          $oGeodadosLayout2->$iCaracteristicaFacePontos = $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos        ;

        } else {

          $oGeodadosLayout2->$iCaracteristicaFaceId     = "";
          $oGeodadosLayout2->$sCaracteristicaFaceCar    = "";
          $oGeodadosLayout2->$iCaracteristicaFacePontos = "";
        }
        $iFace++;

      } else if ($oCaracteristicaDisponivel->j32_tipo == 'L') {

        $sCaracteristicaLoteTipo    = "caracteristicas_lote_tipo_"     . $iLote;
        $iCaracteristicaLoteIdGrupo = "caracteristicas_lote_id_grupo_" . $iLote;
        $sCaracteristicaLoteGrupo   = "caracteristicas_lote_grupo_"    . $iLote;

        $oGeodadosLayout2->$sCaracteristicaLoteTipo    = $oCaracteristicaDisponivel->j32_tipo ;
        $oGeodadosLayout2->$iCaracteristicaLoteIdGrupo = $oCaracteristicaDisponivel->j32_grupo;
        $oGeodadosLayout2->$sCaracteristicaLoteGrupo   = $oCaracteristicaDisponivel->j32_descr;

        $iCaracteristicaLoteId     = "caracteristicas_lote_id_"        . $iLote;
        $sCaracteristicaLoteCar    = "caracteristicas_lote_descricao_" . $iLote;
        $iCaracteristicaLotePontos = "caracteristicas_lote_pontos_"    . $iLote;

        if (isset($aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo])) {

          $oGeodadosLayout2->$iCaracteristicaLoteId     = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
          $oGeodadosLayout2->$sCaracteristicaLoteCar    = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica      ;
          $oGeodadosLayout2->$iCaracteristicaLotePontos = $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos        ;

        } else {

          $oGeodadosLayout2->$iCaracteristicaLoteId     = '';
          $oGeodadosLayout2->$sCaracteristicaLoteCar    = '';
          $oGeodadosLayout2->$iCaracteristicaLotePontos = '';
        }
        $iLote++;

      } else if($oCaracteristicaDisponivel->j32_tipo == 'C') {

        $sCaracteristicaConstrucaoTipo    = "caracteristicas_construcao_tipo_"      . $iConstrucao;
        $iCaracteristicaConstrucaoIdGrupo = "caracteristicas_construcao_id_grupo_"  . $iConstrucao;
        $sCaracteristicaConstrucaoGrupo   = "caracteristicas_construcao_grupo_"     . $iConstrucao;
        $iCaracteristicaConstrucaoId      = "caracteristicas_construcao_id_"        . $iConstrucao;
        $sCaracteristicaConstrucaoCar     = "caracteristicas_construcao_descricao_" . $iConstrucao;
        $iCaracteristicaConstrucaoPontos  = "caracteristicas_construcao_pontos_"    . $iConstrucao;


        $oGeodadosLayout2->$sCaracteristicaConstrucaoTipo    = '';
        $oGeodadosLayout2->$iCaracteristicaConstrucaoIdGrupo = '';
        $oGeodadosLayout2->$sCaracteristicaConstrucaoGrupo   = '';
        $oGeodadosLayout2->$iCaracteristicaConstrucaoId      = '';
        $oGeodadosLayout2->$sCaracteristicaConstrucaoCar     = '';
        $oGeodadosLayout2->$iCaracteristicaConstrucaoPontos  = '';

        $iConstrucao++;

      }

    }

    $aImoveis = $oLote->getImoveis(2);

    foreach ($aImoveis as $oImovel) {

      $oGeodadosLayout2->matricula    = $oImovel->getMatricula();

      $oGeodadosLayout2->proprietario = '';
      if($oImovel->getProprietarioPrincipal()->getNome()) {
        $oGeodadosLayout2->proprietario = $oImovel->getProprietarioPrincipal()->getNome();
      }

      $oGeodadosLayout2->promitente   = '';
      if ($oImovel->getPromitentePrincipal()) {
        $oGeodadosLayout2->promitente   = $oImovel->getPromitentePrincipal()->getNome();
      }

      $oImovelEndereco = $oImovel->getImovelEndereco();
      $oGeodadosLayout2->endereco_entrega             = $oImovelEndereco->getEndereco();
      $oGeodadosLayout2->endereco_entrega_numero      = $oImovelEndereco->getNumero();
      $oGeodadosLayout2->endereco_entrega_complemento = $oImovelEndereco->getComplemento();
      $oGeodadosLayout2->endereco_entrega_bairro      = $oImovelEndereco->getBairro();
      $oGeodadosLayout2->endereco_entrega_municipio   = $oImovelEndereco->getMunicipio();
      $oGeodadosLayout2->endereco_entrega_uf          = $oImovelEndereco->getUf();
      $oGeodadosLayout2->endereco_entrega_cep         = $oImovelEndereco->getCep();
      $oGeodadosLayout2->endereco_entrega_caixapostal = $oImovelEndereco->getCaixaPostal();
      $oGeodadosLayout2->referencia_anterior          = $oImovel->getReferenciaAnterior();

      $oIsencao = $oImovel->getDadosIsencaoExercicio();
      $oGeodadosLayout2->isencao_codigo    = $oIsencao->iTipoIsencao;
      $oGeodadosLayout2->isencao_descricao = $oIsencao->sDescricaoIsencao;

      $oCalculo = $oImovel->getCalculo();

      if($oCalculoIptu = $oCalculo->getCalculoValorIptu()) {
        $oGeodadosLayout2->valor_venal_terreno = $oCalculoIptu->nValorTerreno;
        $oGeodadosLayout2->valor_iptu_terreno  = $oCalculoIptu->nValorTerreno * ($oCalculoIptu->nAliquota / 100);
      }

      $aConstrucoes     = $oImovel->getConstrucoes(true);

      /**
       * Limpa valores de construção para caso não exista construções não
       * persistir os dados da matricula anterior
       */
      $oGeodadosLayout2->codigo_construcao      = '';
      $oGeodadosLayout2->valor_iptu_construcao  = '';
      $oGeodadosLayout2->construcao_numero      = '';
      $oGeodadosLayout2->construcao_complemento = '';
      $oGeodadosLayout2->construcao_area        = '';
      $oGeodadosLayout2->valor_venal_construcao = '';

      if(count($aConstrucoes) > 0) {

        foreach ($aConstrucoes as $oConstrucao) {

          $oGeodadosLayout2->codigo_construcao      = '';
          $oGeodadosLayout2->construcao_numero      = '';
          $oGeodadosLayout2->construcao_complemento = '';
          $oGeodadosLayout2->construcao_area        = '';
          $oGeodadosLayout2->valor_venal_construcao = '';
          $oGeodadosLayout2->valor_iptu_construcao  = '';

          $oGeodadosLayout2->codigo_construcao      = $oConstrucao->getCodigoConstrucao();
          $oGeodadosLayout2->construcao_numero      = $oConstrucao->getNumeroEndereco();
          $oGeodadosLayout2->construcao_complemento = $oConstrucao->getComplementoEndereco();
          $oGeodadosLayout2->construcao_area        = $oConstrucao->getArea();

          if ($oCalculoConstrucao = $oCalculo->getCalculoConstrucao($oConstrucao->getCodigoConstrucao())) {

            $oGeodadosLayout2->valor_venal_construcao = $oCalculoConstrucao->nValor;
            $oGeodadosLayout2->valor_iptu_construcao  = ($oCalculoConstrucao->nValor *  ($oCalculoIptu->nAliquota / 100));
          }

          $aCaracteristicasConstrucao = array();

          foreach ($oConstrucao->getCaracteristicasConstrucao() as $oCaracteristicaConstrucao) {
            $aCaracteristicasConstrucao[$oCaracteristicaConstrucao->iCodigoGrupo] = $oCaracteristicaConstrucao;
          }

          $iConstrucao = 1;
          foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

            if($oCaracteristicaDisponivel->j32_tipo == 'C') {

              $sCaracteristicaConstrucaoTipo    = "caracteristicas_construcao_tipo_"     . $iConstrucao;
              $iCaracteristicaConstrucaoIdGrupo = "caracteristicas_construcao_id_grupo_" . $iConstrucao;
              $sCaracteristicaConstrucaoGrupo   = "caracteristicas_construcao_grupo_"    . $iConstrucao;

              $oGeodadosLayout2->$sCaracteristicaConstrucaoTipo    = $oCaracteristicaDisponivel->j32_tipo ;
              $oGeodadosLayout2->$iCaracteristicaConstrucaoIdGrupo = $oCaracteristicaDisponivel->j32_grupo;
              $oGeodadosLayout2->$sCaracteristicaConstrucaoGrupo   = $oCaracteristicaDisponivel->j32_descr;

              $iCaracteristicaConstrucaoId     = "caracteristicas_construcao_id_"        . $iConstrucao;
              $sCaracteristicaConstrucaoCar    = "caracteristicas_construcao_descricao_" . $iConstrucao;
              $iCaracteristicaConstrucaoPontos = "caracteristicas_construcao_pontos_"    . $iConstrucao;

              if (isset($aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo])) {

                $oGeodadosLayout2->$iCaracteristicaConstrucaoId     = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica;
                $oGeodadosLayout2->$sCaracteristicaConstrucaoCar    = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica      ;
                $oGeodadosLayout2->$iCaracteristicaConstrucaoPontos = $aCaracteristicasConstrucao[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos        ;

              } else {

                $oGeodadosLayout2->$iCaracteristicaConstrucaoId     = '';
                $oGeodadosLayout2->$sCaracteristicaConstrucaoCar    = '';
                $oGeodadosLayout2->$iCaracteristicaConstrucaoPontos = '';

              }

              $iConstrucao++;

            }

          }

          geraLinhaArquivo($pArquivo, $oGeodadosLayout2, $sSeparador);
        }


      } else {

        geraLinhaArquivo($pArquivo, $oGeodadosLayout2, $sSeparador);
      }

    }

  }

  fclose($pArquivo);

  compactaArquivos(array(str_replace('tmp/', '', $sNomeArquivo)), $sZipTmpNomeArquivo);
}

function layout3($aGeodados, $oLayoutTxt, $aCaracteristicasDisponiveis) {

  $oGeodadosHeader = new stdClass();

  $oGeodadosHeader->matricula               = "matricula              ";
  $oGeodadosHeader->setor                   = "setor                  ";
  $oGeodadosHeader->quadra                  = "quadra                 ";
  $oGeodadosHeader->lote                    = "lote                   ";
  $oGeodadosHeader->construcao              = "construcao             ";
  $oGeodadosHeader->nome                    = "nome                   ";
  $oGeodadosHeader->rua_codigo              = "rua_codigo             ";
  $oGeodadosHeader->rua_tipo                = "rua_tipo               ";
  $oGeodadosHeader->rua_nome                = "rua_nome               ";
  $oGeodadosHeader->construcao_numero       = "construcao_numero      ";
  $oGeodadosHeader->construcao_complemento  = "construcao_complemento ";
  $oGeodadosHeader->bairro_codigo           = "bairro_codigo          ";
  $oGeodadosHeader->bairro_descricao        = "bairro_descricao       ";
  $oGeodadosHeader->rua_cep                 = "rua_cep                ";
  $oGeodadosHeader->lote_area               = "lote_area              ";
  $oGeodadosHeader->construcao_area         = "construcao_area        ";
  $oGeodadosHeader->valor_testada_lote      = "valor_testada_lote     ";
  $oGeodadosHeader->valor_venal_terreno     = "valor_venal_terreno    ";
  $oGeodadosHeader->valor_venal_construcao  = "valor_venal_construcao ";
  $oGeodadosHeader->valor_terreno           = "valor_terreno          ";
  $oGeodadosHeader->valor_construcao        = "valor_construcao       ";
  $oGeodadosHeader->isencao_codigo          = "isencao_codigo         ";
  $oGeodadosHeader->isencao_descricao       = "isencao_descricao      ";
  $oGeodadosHeader->endereco                = "endereco               ";
  $oGeodadosHeader->endereco_numero         = "endereco_numero        ";
  $oGeodadosHeader->endereco_complemento    = "endereco_complemento   ";
  $oGeodadosHeader->endereco_bairro         = "endereco_bairro        ";
  $oGeodadosHeader->endereco_municipio      = "endereco_municipio     ";
  $oGeodadosHeader->endereco_uf             = "endereco_uf            ";
  $oGeodadosHeader->endereco_cep            = "endereco_cep           ";
  $oGeodadosHeader->endereco_caixapostal    = "endereco_caixapostal   ";
  $oGeodadosHeader->referencia_anterior     = "referencia_anterior    ";
  $oGeodadosHeader->caracteristicas_lote    = "caracteristicas_lote   ";
  $oGeodadosHeader->caracteristicas_predial = "caracteristicas_predial";
  $oGeodadosHeader->caracteristicas_face    = "caracteristicas_face   ";

  if( $oLayoutTxt->setByLineOfDBUtils($oGeodadosHeader, "1") == false ) {

    throw new Exception ("[ 1 ] - Erro ao gerar linha do arquivo");

  }

  $oGeodadosLayout3 = new stdClass();

  foreach ($aGeodados as $oGeodados) {

    $oGeodadosLayout3->matricula               = "";
    $oGeodadosLayout3->setor                   = "";
    $oGeodadosLayout3->quadra                  = "";
    $oGeodadosLayout3->lote                    = "";
    $oGeodadosLayout3->construcao              = "";
    $oGeodadosLayout3->nome                    = "";
    $oGeodadosLayout3->rua_codigo              = "";
    $oGeodadosLayout3->rua_tipo                = "";
    $oGeodadosLayout3->rua_nome                = "";
    $oGeodadosLayout3->construcao_numero       = "";
    $oGeodadosLayout3->construcao_complemento  = "";
    $oGeodadosLayout3->bairro_codigo           = "";
    $oGeodadosLayout3->bairro_descricao        = "";
    $oGeodadosLayout3->rua_cep                 = "";
    $oGeodadosLayout3->lote_area               = "";
    $oGeodadosLayout3->construcao_area         = "";
    $oGeodadosLayout3->valor_testada_lote      = "";
    $oGeodadosLayout3->valor_venal_terreno     = "";
    $oGeodadosLayout3->valor_venal_construcao  = "";
    $oGeodadosLayout3->valor_terreno           = "";
    $oGeodadosLayout3->valor_construcao        = "";
    $oGeodadosLayout3->isencao_codigo          = "";
    $oGeodadosLayout3->isencao_descricao       = "";
    $oGeodadosLayout3->endereco                = "";
    $oGeodadosLayout3->endereco_numero         = "";
    $oGeodadosLayout3->endereco_complemento    = "";
    $oGeodadosLayout3->endereco_bairro         = "";
    $oGeodadosLayout3->endereco_municipio      = "";
    $oGeodadosLayout3->endereco_uf             = "";
    $oGeodadosLayout3->endereco_cep            = "";
    $oGeodadosLayout3->endereco_caixapostal    = "";
    $oGeodadosLayout3->referencia_anterior     = "";
    $oGeodadosLayout3->caracteristicas_lote    = "";
    $oGeodadosLayout3->caracteristicas_predial = "";
    $oGeodadosLayout3->caracteristicas_face    = "";

    $oLote = new Lote($oGeodados->j01_idbql);

    $oGeodadosLayout3->setor              = $oLote->getCodigoSetor();
    $oGeodadosLayout3->quadra             = $oLote->getQuadra();
    $oGeodadosLayout3->lote               = $oLote->getLote();
    $oGeodadosLayout3->rua_codigo         = $oLote->getCodigoLogradouro();
    $oGeodadosLayout3->rua_tipo           = $oLote->getCodigoTipoLogradouro();
    $oGeodadosLayout3->rua_nome           = $oLote->getLogradouro();
    $oGeodadosLayout3->bairro_codigo      = $oLote->getCodigoBairro();
    $oGeodadosLayout3->bairro_descricao   = $oLote->getBairro();
    $oGeodadosLayout3->rua_cep            = $oLote->getCep();
    $oGeodadosLayout3->lote_area          = $oLote->getAreaLote();
    $oGeodadosLayout3->valor_testada_lote = $oLote->getValorTestadaLote();

    $aCaracteristicasFaceLote   = array();
    $aCaracteristicasLote       = array();

    foreach ($oLote->getCaracteristicasFace() as $oCaracteristicaFace) {
      $aCaracteristicasFaceLote[$oCaracteristicaFace->iCodigoGrupo] = $oCaracteristicaFace;
    }

    foreach ($oLote->getCaracteristicasLote() as $oCaracteristicaLote) {
      $aCaracteristicasLote[$oCaracteristicaLote->iCodigoGrupo] = $oCaracteristicaLote;
    }

    foreach ($aCaracteristicasDisponiveis as $oCaracteristicaDisponivel) {

      if($oCaracteristicaDisponivel->j32_tipo == 'F') {

        $oGeodadosLayout2->caracteristicas_face .= "Caract. Face;";
        $oGeodadosLayout2->caracteristicas_face .= $oCaracteristicaDisponivel->j32_grupo . ";";
        $oGeodadosLayout2->caracteristicas_face .= $oCaracteristicaDisponivel->j32_descr . ";";

        if (isset($aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo])) {

          $oGeodadosLayout2->caracteristicas_face .= $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica . ";";
          $oGeodadosLayout2->caracteristicas_face .= $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica       . ";";
          $oGeodadosLayout2->caracteristicas_face .= $aCaracteristicasFaceLote[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos         . ";";

        }

      } else if ($oCaracteristicaDisponivel->j32_tipo == 'L') {

        $oGeodadosLayout2->caracteristicas_lote .= "Caract. Lote;";
        $oGeodadosLayout2->caracteristicas_lote .= $oCaracteristicaDisponivel->j32_grupo . ";";
        $oGeodadosLayout2->caracteristicas_lote .= $oCaracteristicaDisponivel->j32_descr . ";";

        if (isset($aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo])) {

          $oGeodadosLayout2->caracteristicas_lote .= $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->iCodigoCaracteristica . ";";
          $oGeodadosLayout2->caracteristicas_lote .= $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->sCaracteristica       . ";";
          $oGeodadosLayout2->caracteristicas_lote .= $aCaracteristicasLote[$oCaracteristicaDisponivel->j32_grupo]->iNumeroPontos         . ";";

        }

      }

    }


    $aImoveis         = $oLote->getImoveis();

    foreach ($aImoveis as $oImovel) {

      $oGeodadosLayout3->matricula           = $oImovel->getMatricula();
      $oGeodadosLayout3->nome                = $oImovel->getProprietarioPrincipal()->getNome();
      $oGeodadosLayout3->referencia_anterior = $oImovel->getReferenciaAnterior();

      $oIsencao = $oImovel->getDadosIsencaoExercicio();
      $oGeodadosLayout3->isencao_codigo     = $oIsencao->iTipoIsencao;
      $oGeodadosLayout3->isencao_descricao  = $oIsencao->sDescricaoIsencao;

      $oImovelEndereco = $oImovel->getImovelEndereco();
      $oGeodadosLayout3->endereco             = $oImovelEndereco->getEndereco();
      $oGeodadosLayout3->endereco_numero      = $oImovelEndereco->getNumero();
      $oGeodadosLayout3->endereco_complemento = $oImovelEndereco->getComplemento();
      $oGeodadosLayout3->endereco_bairro      = $oImovelEndereco->getBairro();
      $oGeodadosLayout3->endereco_municipio   = $oImovelEndereco->getMunicipio();
      $oGeodadosLayout3->endereco_uf          = $oImovelEndereco->getUf();
      $oGeodadosLayout3->endereco_cep         = $oImovelEndereco->getCep();
      $oGeodadosLayout3->endereco_caixapostal = $oImovelEndereco->getCaixaPostal();

      $oCalculo     = $oImovel->getCalculo();
      if ($oCalculoIptu = $oCalculo->getCalculoValorIptu()) {

        $oGeodadosLayout3->valor_venal_terreno = $oCalculoIptu->nValorTerreno;
        $oGeodadosLayout3->valor_terreno       = $oCalculoIptu->nValorTerreno * ($oCalculoIptu->nAliquota / 100);

      }

      $aConstrucoes     = $oImovel->getConstrucoes();

      if(count($aConstrucoes) > 0) {

        foreach ($aConstrucoes as $oConstrucao) {

          $oGeodadosLayout3->construcao              = $oConstrucao->getCodigoConstrucao();
          $oGeodadosLayout3->construcao_numero       = $oConstrucao->getNumeroEndereco();
          $oGeodadosLayout3->construcao_complemento  = $oConstrucao->getComplementoEndereco();

          if($oCalculoConstrucao = $oCalculo->getCalculoConstrucao($oConstrucao->getCodigoConstrucao())) {

            $oGeodadosLayout3->construcao_area         = $oCalculoConstrucao->nAreaConstruida;
            $oGeodadosLayout3->valor_venal_construcao  = $oCalculoConstrucao->nValor;
            $oGeodadosLayout3->valor_construcao        = $oCalculoConstrucao->nValor *  ($oCalculoIptu->nAliquota / 100);

          }

          foreach ($oConstrucao->getCaracteristicasConstrucao() as $oCaracteristicaPredial) {

            $oGeodadosLayout2->caracteristicas_predial .= "Caract. Predial,";
            $oGeodadosLayout2->caracteristicas_predial .= $oCaracteristicaPredial->iCodigoGrupo          . ",";
            $oGeodadosLayout2->caracteristicas_predial .= $oCaracteristicaPredial->sDescricaoGrupo       . ",";
            $oGeodadosLayout2->caracteristicas_predial .= $oCaracteristicaPredial->iCodigoCaracteristica . ",";
            $oGeodadosLayout2->caracteristicas_predial .= $oCaracteristicaPredial->sCaracteristica       . ",";
            $oGeodadosLayout2->caracteristicas_predial .= $oCaracteristicaPredial->iNumeroPontos         . ",";

          }

          if( $oLayoutTxt->setByLineOfDBUtils($oGeodadosLayout3, "3") == false ) {

            throw new Exception ("[ 1 ] - Erro ao gerar linha do arquivo");

          }

        }

      } else {

        if( $oLayoutTxt->setByLineOfDBUtils($oGeodadosLayout3, "3") == false ) {

          throw new Exception ("[ 1 ] - Erro ao gerar linha do arquivo");

        }

      }

    }

  }

}

function geraLinhaArquivo($pArquivo, $oLinhaArquivo, $sSeparador = ';', $lCabecalho = false) {

  $oLinha->matricula                      =  trim($oLinhaArquivo->matricula                     );
  $oLinha->referencia_anterior            =  trim($oLinhaArquivo->referencia_anterior           );
  $oLinha->codigo_setor                   =  trim($oLinhaArquivo->codigo_setor                  );
  $oLinha->codigo_quadra                  =  trim($oLinhaArquivo->codigo_quadra                 );
  $oLinha->codigo_lote                    =  trim($oLinhaArquivo->codigo_lote                   );
  $oLinha->proprietario                   =  trim($oLinhaArquivo->proprietario                  );
  $oLinha->promitente                     =  trim($oLinhaArquivo->promitente                    );
  $oLinha->rua_tipo_sigla_testada         =  trim($oLinhaArquivo->rua_tipo_sigla_testada        );
  $oLinha->rua_codigo                     =  trim($oLinhaArquivo->rua_codigo                    );
  $oLinha->rua_nome                       =  trim($oLinhaArquivo->rua_nome                      );
  $oLinha->construcao_numero              =  trim($oLinhaArquivo->construcao_numero             );
  $oLinha->construcao_complemento         =  trim($oLinhaArquivo->construcao_complemento        );
  $oLinha->bairro_codigo                  =  trim($oLinhaArquivo->bairro_codigo                 );
  $oLinha->bairro_descricao               =  trim($oLinhaArquivo->bairro_descricao              );
  $oLinha->rua_cep                        =  trim($oLinhaArquivo->rua_cep                       );
  $oLinha->lote_codigo_loteamento         =  trim($oLinhaArquivo->lote_codigo_loteamento        );
  $oLinha->lote_descricao_loteamento      =  trim($oLinhaArquivo->lote_descricao_loteamento     );
  $oLinha->lote_area                      =  trim($oLinhaArquivo->lote_area                     );
  $oLinha->codigo_construcao              =  trim($oLinhaArquivo->codigo_construcao             );
  $oLinha->construcao_area                =  trim($oLinhaArquivo->construcao_area               );
  $oLinha->valor_testada_lote             =  trim($oLinhaArquivo->valor_testada_lote            );
  $oLinha->valor_venal_terreno            =  trim($oLinhaArquivo->valor_venal_terreno           );
  $oLinha->valor_venal_construcao         =  trim($oLinhaArquivo->valor_venal_construcao        );
  $oLinha->valor_iptu_terreno             =  trim($oLinhaArquivo->valor_iptu_terreno            );
  $oLinha->valor_iptu_construcao          =  trim($oLinhaArquivo->valor_iptu_construcao         );
  $oLinha->isencao_codigo                 =  trim($oLinhaArquivo->isencao_codigo                );
  $oLinha->isencao_descricao              =  trim($oLinhaArquivo->isencao_descricao             );
  $oLinha->endereco_entrega               =  trim($oLinhaArquivo->endereco_entrega              );
  $oLinha->endereco_entrega_numero        =  trim($oLinhaArquivo->endereco_entrega_numero       );
  $oLinha->endereco_entrega_complemento   =  trim($oLinhaArquivo->endereco_entrega_complemento  );
  $oLinha->endereco_entrega_bairro        =  trim($oLinhaArquivo->endereco_entrega_bairro       );
  $oLinha->endereco_entrega_municipio     =  trim($oLinhaArquivo->endereco_entrega_municipio    );
  $oLinha->endereco_entrega_uf            =  trim($oLinhaArquivo->endereco_entrega_uf           );
  $oLinha->endereco_entrega_cep           =  trim($oLinhaArquivo->endereco_entrega_cep          );
  $oLinha->endereco_entrega_caixapostal   =  trim($oLinhaArquivo->endereco_entrega_caixapostal  );

  if ($oLinha->valor_testada_lote     and $lCabecalho == false) {
    $oLinha->valor_testada_lote     = round($oLinha->valor_testada_lote, 2);
  }
  if ($oLinha->valor_venal_terreno    and $lCabecalho == false) {
    $oLinha->valor_venal_terreno    = round($oLinha->valor_venal_terreno, 2);
  }
  if ($oLinha->valor_venal_construcao and $lCabecalho == false) {
    $oLinha->valor_venal_construcao = round($oLinha->valor_venal_construcao,2);
  }
  if ($oLinha->valor_iptu_terreno     and $lCabecalho == false) {
    $oLinha->valor_iptu_terreno     = round($oLinha->valor_iptu_terreno,2);
  }
  if ($oLinha->valor_iptu_construcao  and $lCabecalho == false) {
    $oLinha->valor_iptu_construcao  = round($oLinha->valor_iptu_construcao,2);
  }

  if(isset($oLinhaArquivo->iQdteCaracteristicasFace)) {
    for($iCaracteristica = 1; $iCaracteristica <= $oLinhaArquivo->iQdteCaracteristicasFace; $iCaracteristica++) {

      $sCaracteristicasFaceTipo             = "caracteristicas_face_tipo_"      . $iCaracteristica;
      $iCaracteristicasFaceIdGrupo          = "caracteristicas_face_id_grupo_"  . $iCaracteristica;
      $sCaracteristicasFaceGrupo            = "caracteristicas_face_grupo_"     . $iCaracteristica;
      $iCaracteristicasFaceIdCaracteristica = "caracteristicas_face_id_"        . $iCaracteristica;
      $sCaracteristicasFaceDescricao        = "caracteristicas_face_descricao_" . $iCaracteristica;
      $iCaracteristicasFacePontos           = "caracteristicas_face_pontos_"    . $iCaracteristica;

      $oLinha->$sCaracteristicasFaceTipo             = trim($oLinhaArquivo->$sCaracteristicasFaceTipo            );
      $oLinha->$iCaracteristicasFaceIdGrupo          = trim($oLinhaArquivo->$iCaracteristicasFaceIdGrupo         );
      $oLinha->$sCaracteristicasFaceGrupo            = trim($oLinhaArquivo->$sCaracteristicasFaceGrupo           );
      $oLinha->$iCaracteristicasFaceIdCaracteristica = trim($oLinhaArquivo->$iCaracteristicasFaceIdCaracteristica);
      $oLinha->$sCaracteristicasFaceDescricao        = trim($oLinhaArquivo->$sCaracteristicasFaceDescricao       );
      $oLinha->$iCaracteristicasFacePontos           = trim($oLinhaArquivo->$iCaracteristicasFacePontos          );
    }
  }

  if(isset($oLinhaArquivo->iQdteCaracteristicasLote)) {
    for($iCaracteristica = 1; $iCaracteristica <= $oLinhaArquivo->iQdteCaracteristicasLote; $iCaracteristica++) {

      $sCaracteristicasLoteTipo             = "caracteristicas_lote_tipo_"      . $iCaracteristica;
      $iCaracteristicasLoteIdGrupo          = "caracteristicas_lote_id_grupo_"  . $iCaracteristica;
      $sCaracteristicasLoteGrupo            = "caracteristicas_lote_grupo_"     . $iCaracteristica;
      $iCaracteristicasLoteIdCaracteristica = "caracteristicas_lote_id_"        . $iCaracteristica;
      $sCaracteristicasLoteDescricao        = "caracteristicas_lote_descricao_" . $iCaracteristica;
      $iCaracteristicasLotePontos           = "caracteristicas_lote_pontos_"    . $iCaracteristica;

      $oLinha->$sCaracteristicasLoteTipo             = trim($oLinhaArquivo->$sCaracteristicasLoteTipo            );
      $oLinha->$iCaracteristicasLoteIdGrupo          = trim($oLinhaArquivo->$iCaracteristicasLoteIdGrupo         );
      $oLinha->$sCaracteristicasLoteGrupo            = trim($oLinhaArquivo->$sCaracteristicasLoteGrupo           );
      $oLinha->$iCaracteristicasLoteIdCaracteristica = trim($oLinhaArquivo->$iCaracteristicasLoteIdCaracteristica);
      $oLinha->$sCaracteristicasLoteDescricao        = trim($oLinhaArquivo->$sCaracteristicasLoteDescricao       );
      $oLinha->$iCaracteristicasLotePontos           = trim($oLinhaArquivo->$iCaracteristicasLotePontos          );
    }
  }


  if(isset($oLinhaArquivo->iQdteCaracteristicasConstrucao)) {
    for($iCaracteristica = 1; $iCaracteristica <= $oLinhaArquivo->iQdteCaracteristicasConstrucao; $iCaracteristica++) {

      $sCaracteristicasConstrucaoTipo             = "caracteristicas_construcao_tipo_"      . $iCaracteristica;
      $iCaracteristicasConstrucaoIdGrupo          = "caracteristicas_construcao_id_grupo_"  . $iCaracteristica;
      $sCaracteristicasConstrucaoGrupo            = "caracteristicas_construcao_grupo_"     . $iCaracteristica;
      $iCaracteristicasConstrucaoIdCaracteristica = "caracteristicas_construcao_id_"        . $iCaracteristica;
      $sCaracteristicasConstrucaoDescricao        = "caracteristicas_construcao_descricao_" . $iCaracteristica;
      $iCaracteristicasConstrucaoPontos           = "caracteristicas_construcao_pontos_"    . $iCaracteristica;

      $oLinha->$sCaracteristicasConstrucaoTipo             = trim($oLinhaArquivo->$sCaracteristicasConstrucaoTipo            );
      $oLinha->$iCaracteristicasConstrucaoIdGrupo          = trim($oLinhaArquivo->$iCaracteristicasConstrucaoIdGrupo         );
      $oLinha->$sCaracteristicasConstrucaoGrupo            = trim($oLinhaArquivo->$sCaracteristicasConstrucaoGrupo           );
      $oLinha->$iCaracteristicasConstrucaoIdCaracteristica = trim($oLinhaArquivo->$iCaracteristicasConstrucaoIdCaracteristica);
      $oLinha->$sCaracteristicasConstrucaoDescricao        = trim($oLinhaArquivo->$sCaracteristicasConstrucaoDescricao       );
      $oLinha->$iCaracteristicasConstrucaoPontos           = trim($oLinhaArquivo->$iCaracteristicasConstrucaoPontos          );
    }
  }


  fputs($pArquivo, '"'.implode('"' . $sSeparador . '"', (array) $oLinha).'"'. "\n");

}

?>