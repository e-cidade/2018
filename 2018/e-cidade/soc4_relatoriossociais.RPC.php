<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("libs/db_app.utils.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");
require_once ("classes/db_avaliacaopergunta_classe.php");

db_app::import("social.*");
db_app::import("social.cadastrounico.*");
db_app::import("Avaliacao");
db_app::import("AvaliacaoPergunta");
db_app::import("exceptions.*");

$oJson              = new Services_JSON();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;


switch ($oParam->exec) {

  case 'buscaBairrosCidadao' :

    /**
     * Foi selecionado com to_ascii pois o postgres configurado com LATIN1 e ptBr nao ordena os acentos de forma
     * correta por default. Existe como configura-lo para ordenar corretamente, mais teria que ser refeito os clusters
     */
    $oDaoCidadao = db_utils::getDao('cidadaocadastrounico');
    $sCampos     = " distinct to_ascii(trim(ov02_bairro)) as bairro";
    $sOrder      = " to_ascii(trim(ov02_bairro)) ";
    $sSqlCidadao = $oDaoCidadao->sql_query(null, $sCampos, $sOrder);
    $rsCidadao   = $oDaoCidadao->sql_record($sSqlCidadao);

    $iRegistros  = $oDaoCidadao->numrows;

    for ($i = 0; $i < $iRegistros; $i++) {
      $oRetorno->dados[] = db_utils::fieldsMemory($rsCidadao, $i)->bairro;
    }

    break;

  /**
   * Buscamos os tipos de familiares
   */
  case 'buscaTipoFamiliar':

    $oRetorno->tipoFamiliar = array();
    $oDaoTipoFamiliar       = db_utils::getDao('tipofamiliar');
    $sOrderTipoFamliar      = "z14_sequencial";
    $sSqlTipoFamiliar       = $oDaoTipoFamiliar->sql_query_file(null, "*", $sOrderTipoFamliar);
    $rsTipoFamiliar         = $oDaoTipoFamiliar->sql_record($sSqlTipoFamiliar);
    $iTotalTipoFamiliar     = $oDaoTipoFamiliar->numrows;

    if ($iTotalTipoFamiliar > 0) {

      for ($iContador = 0; $iContador < $iTotalTipoFamiliar; $iContador++) {

        $oDadosTipoFamilar         = db_utils::fieldsMemory($rsTipoFamiliar, $iContador);
        $oTipoFamiliar             = new stdClass();
        $oTipoFamiliar->iCodigo    = $oDadosTipoFamilar->z14_sequencial;
        $oTipoFamiliar->sDescricao = urlencode($oDadosTipoFamilar->z14_descricao);
        $oRetorno->tipoFamiliar[]  = $oTipoFamiliar;
      }
    }
    break;
  case 'getDeficiencias' :

    $oPergunta            = new AvaliacaoPergunta(3000104);
    $aRespostasDaPergunta = $oPergunta->getRespostas();
    $aRespostas           = array();
    foreach ($aRespostasDaPergunta as $oRespostaPergunta) {

      $oResposta = new stdClass();
      $oResposta->identificador = $oRespostaPergunta->identificador;
      $oResposta->descricao     = $oRespostaPergunta->descricaoresposta;
      $aRespostas[]             = $oResposta;
    }
    $oRetorno->deficiencias = $aRespostas;
    break;

  case 'getEscolaridade' :

    $oPergunta            = new AvaliacaoPergunta(3000113);
    $aRespostasDaPergunta = $oPergunta->getRespostas();
    $aRespostas           = array();
    foreach ($aRespostasDaPergunta as $oRespostaPergunta) {

      if ($oRespostaPergunta->codigoresposta == 3000368 ||
          $oRespostaPergunta->codigoresposta == 3000367 ||
          $oRespostaPergunta->codigoresposta == 3000369 ||
          $oRespostaPergunta->codigoresposta == 3000370) {

        $oResposta = new stdClass();
        $oResposta->identificador = $oRespostaPergunta->identificador;
        $oResposta->descricao     = $oRespostaPergunta->descricaoresposta;
        $aRespostas[] = $oResposta;
      }
    }
    $oRetorno->escolaridades = $aRespostas;
    break;

  case 'getCorRacas' :

    $oPergunta            = new AvaliacaoPergunta(3000073);
    $aRespostasDaPergunta = $oPergunta->getRespostas();
    $aRespostas           = array();
    foreach ($aRespostasDaPergunta as $oRespostaPergunta) {

      $oResposta = new stdClass();
      $oResposta->identificador = $oRespostaPergunta->identificador;
      $oResposta->descricao     = $oRespostaPergunta->descricaoresposta;
      $aRespostas[]             = $oResposta;
    }
    $oRetorno->dados = $aRespostas;
    break;

  case 'getAtividade' :

    $oPergunta            = new AvaliacaoPergunta(3000121);
    $aRespostasDaPergunta = $oPergunta->getRespostas();
    $aRespostas           = array();
    foreach ($aRespostasDaPergunta as $oRespostaPergunta) {

      $oResposta = new stdClass();
      $oResposta->identificador = $oRespostaPergunta->identificador;
      $oResposta->descricao     = $oRespostaPergunta->descricaoresposta;
      $aRespostas[]             = $oResposta;
    }
    $oRetorno->atividade = $aRespostas;
    break;
  case 'getEstabelecimentoDeAssistenciaSaude':

    $oDaoAvaliacao = db_utils::getDao('avaliacaoresposta');
    $sWhere        = " db106_avaliacaoperguntaopcao in (3000261) and db106_resposta <> ''";
    $sCampos       = " distinct db106_resposta ";
    $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file(null, $sCampos, "db106_resposta", $sWhere);
    $rsAvaliacao   = $oDaoAvaliacao->sql_record($sSqlAvaliacao);
    $iRegistros    = $oDaoAvaliacao->numrows;

    $oRetorno->aAes = array();

    if ($iRegistros == 0 ) {

      $oRetorno->status = 2;
      $sMsgErro         = "Não foram encontrados registros de pessoas atendidas pelo EAS ";
      $sMsgErro        .= "(Estabelecimento de Assistência à Saúde)";
      $oRetorno->message = urlencode($sMsgErro);

    } else {

      for ($i = 0; $i < $iRegistros; $i++) {

        $oRetorno->aAes[] = db_utils::fieldsMemory($rsAvaliacao, $i, false, false, true);
      }
    }
    break;

  case 'getCras':

    $oDaoAvaliacao = db_utils::getDao('avaliacaoresposta');
    $sWhere        = " db106_avaliacaoperguntaopcao in(3000263) and db106_resposta <> ''";
    $sCampos       = " distinct db106_resposta ";
    $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file(null, $sCampos, "db106_resposta", $sWhere);
    $rsAvaliacao   = $oDaoAvaliacao->sql_record($sSqlAvaliacao);
    $iRegistros    = $oDaoAvaliacao->numrows;

    $oRetorno->aCras = array();

    if ($iRegistros == 0 ) {

      $oRetorno->status = 2;
      $sMsgErro         = "Não foram encontrados registros de pessoas atendidas pelo EAS ";
      $sMsgErro        .= "(Estabelecimento de Assistência à Saúde)";
      $oRetorno->message = urlencode($sMsgErro);

    } else {

      for ($i = 0; $i < $iRegistros; $i++) {

        $oRetorno->aCras[] = db_utils::fieldsMemory($rsAvaliacao, $i, false, false, true);
      }
    }
    break;

  case 'familiaAtendeCriterioTarifaSocial':

    /**
     * Criterio para validacao da Tarifa Social
     * --> Familia ser inscrita no Cadastro Unico e possuir renda mensal ate tres salarios minimos
     * --> Familia possuir algum membro que recebe o BPC deficiente e/ou idoso.
     * --> Familia ser indigena ou quilombola com renda de ate 1/2 salario minimo
     */

    $oFamilia = new Familia($oParam->iFamilia);

    $lFamiliaComRendaAteTresSalarios = false;

    if ($oFamilia->validaRendaMensalAte(0.5)) {
      $lFamiliaComRendaAteTresSalarios = true;
    }

    $sMsgAviso = "Família sem direito ao benefício!";
    $oRetorno->lAtendeCriterioTarifaSocial = false;

    if ($lFamiliaComRendaAteTresSalarios) {

      $sMsgAviso = "Família atende os requisitos para entrar no programa Tarifa Social";
      $oRetorno->lAtendeCriterioTarifaSocial = true;
    }
    $oRetorno->message = urlencode($sMsgAviso);

    break;

  case 'cidadaoAtendeCriteriosDeclaracaoINSS':

    /**
     * Para ser validado, o cidadao nao pode:
     * --> ter renda no periodo
     * --> a familia ter renda superior a 2 salarios minimos
     */

    $nPossuiRendaPeriodo          = 0;
    $lFamiliaRendaAteDoisSalarios = false;
    $oCidadao = CadastroUnicoRepository::getCadastroUnicoByNis($oParam->iNis);

    $nPossuiRendaPeriodo = $oCidadao->getRendaBrutaNoPeriodo();

    if ($nPossuiRendaPeriodo == 0) {

      if ($oCidadao->getFamilia()->validaRendaMensalAte(2)) {
        $lFamiliaRendaAteDoisSalarios = true;
      }
    }

    $oAvaliacaoCidadao            = $oCidadao->getAvaliacao();

    //8.01) - Na semana passada (nome) trabalhou? tem que estar não
    $lRespostasTrabalhaMesPassado = $oAvaliacaoCidadao->verificaSeRespostaEstaMarcada(3000118, 3000411);

    //.05) - No mês passado (nome) recebeu remuneração de trabalho
    $sRecebeRemuneracaoMesPassado = $oAvaliacaoCidadao->retornaValorRespostaMarcada('RecebeNoMesRemuneracao',3000446);

    //8.06) - (Nome) teve trabalho remunerado nos últimos 12 meses?  item 8.09 tem conter zeros

    $sRecebeNoMesAjudaDoacao               = $oAvaliacaoCidadao->retornaValorRespostaMarcada('RecebeNoMesAjudaDoacao', 3000434);
    $sRecebeNoMesAposentadoriaPensao       = $oAvaliacaoCidadao->retornaValorRespostaMarcada('RecebeNoMesAposentadoriaPensao', 3000435);
    $sRecebeNoMesSeguroDesemprego          = $oAvaliacaoCidadao->retornaValorRespostaMarcada('RecebeNoMesSeguroDesemprego', 3000436);
    $sRecebeNoMesPensaoAlimenticia         = $oAvaliacaoCidadao->retornaValorRespostaMarcada('RecebeNoMesPensaoAlimenticia', 3000437);
    $sRecebeNoMesOutrasFontesRemuneracao   = $oAvaliacaoCidadao->retornaValorRespostaMarcada('RecebeNoMesOutrasFontesRemuneracao', 3000439);

    if ($sRecebeNoMesAjudaDoacao       == 0 && $sRecebeNoMesAposentadoriaPensao     == 0 && $sRecebeNoMesSeguroDesemprego == 0 &&
        $sRecebeNoMesPensaoAlimenticia == 0 && $sRecebeNoMesOutrasFontesRemuneracao == 0 && $lRespostasTrabalhaMesPassado      &&
        $sRecebeRemuneracaoMesPassado  == 0 && $nPossuiRendaPeriodo                 == 0 && $lFamiliaRendaAteDoisSalarios) {

      $sMsgAviso = "Cidadão atende aos critérios para obter o benefício.";
      $oRetorno->lAtendeCriterioDeclaracaoINSS = true;
    } else {

      $sMsgAviso = "Família sem direito ao benefício!";
      $oRetorno->lAtendeCriterioDeclaracaoINSS = false;
    }

    $oRetorno->message =  urlencode($sMsgAviso);

    break;
}
echo $oJson->encode($oRetorno);