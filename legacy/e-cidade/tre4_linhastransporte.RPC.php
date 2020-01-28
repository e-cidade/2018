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
define( "MENSAGENS_TRE4_LINHATRANSPORTE_RPC", 'educacao.transporteescolar.tre4_linhastransporte.' );

require_once(modification("std/db_stdClass.php"));
require_once(modification("std/DBNumber.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {
  db_inicio_transacao();
  switch ($oParam->sExecucao) {

    /**
     * Salva uma linha de transporte. Caso iCodigo esteja vazio (novo cadastro), incluimos tambem os registros em
     * LinhaItinerario com tipo 1 e 2 para a linha
     * @param integer $oParam->iCodigo      - Sequencial de linha transporte
     * @param string  $oParam->sNome        - Descricao da linha
     * @param string  $oParam->sAbreviatura - Abreviatura da linha
     *
     * @return integer $oRetorno->iCodigo - Sequencial da linha de transporte salvo
     */
    case 'salvarLinha':

      if (isset($oParam->iCodigo) && isset($oParam->sNome)) {

        $oLinhaTransporte = new LinhaTransporte($oParam->iCodigo);
        $oLinhaTransporte->setNome(db_stdClass::normalizeStringJsonEscapeString($oParam->sNome));
        $oLinhaTransporte->setAbreviatura(db_stdClass::normalizeStringJsonEscapeString($oParam->sAbreviatura));
        $oLinhaTransporte->salvar();

        if (empty($oParam->iCodigo)) {

          for ($iContador = 1; $iContador <= 2; $iContador++) {

            $oLinhaItinerario = new LinhaItinerario();
            $oLinhaItinerario->setLinhaTransporte($oLinhaTransporte);
            $oLinhaItinerario->setTipo($iContador);
            $oLinhaItinerario->salvar();
          }
        }

        $oRetorno->iCodigo   = $oLinhaTransporte->getCodigo();
        $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC .'linha_salva') );
      }
      break;

    /**
     * Salva um logradouro vinculado a um itinerario.
     * 1º Instancia LinhaTransporte referente a linha em que esta sendo feito o vinculo
     * 2º Instancia LinhaItinerario, seta os dados e salva as informacoes (caso ainda nao exista - validacao feita no
     * model)
     * 3º Instancia LinhaItinerarioLogradouro, seta os dados e salva as informacoes
     *
     * @param integer $oParam->iCodigoLinha            - Codigo de LinhaTransporte
     * @param integer $oParam->iCodigoBairroLogradouro - Codigo do vinculo Bairro-Logradouro (cadenderbairrocadenderrua)
     * @param integer $oParam->iTipo                   - Tipo do vinculo (1 - Ida / 2 - Retorno)
     *
     * @return integer $oRetorno->iCodigoItinerario
     */
    case 'adicionarItinerarioLogradouro':

      if (isset($oParam->iCodigoLinha) && isset($oParam->iCodigoBairroLogradouro) && isset($oParam->iTipo)) {

        $oLinhaTransporte = new LinhaTransporte($oParam->iCodigoLinha);
        $oLinhaItinerario = new LinhaItinerario();
        $oLinhaItinerario->setLinhaTransporte($oLinhaTransporte);
        $oLinhaItinerario->setTipo($oParam->iTipo);
        $oLinhaItinerario->salvar();

        $oLinhaItinerarioLogradouro = new LinhaItinerarioLogradouro();
        $oLinhaItinerarioLogradouro->setLogradouroBairro(new LogradouroBairro($oParam->iCodigoBairroLogradouro));
        $oLinhaItinerarioLogradouro->setLinhaItinerario($oLinhaItinerario);

        if (isset($oParam->iOrdem) && !empty($oParam->iOrdem)) {
          $oLinhaItinerarioLogradouro->setOrdem($oParam->iOrdem);
        }

        $oLinhaItinerarioLogradouro->salvar();

        $oRetorno->iCodigoItinerario = $oLinhaItinerario->getCodigo();
        $oRetorno->sMensagem         = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'itinerario_logradouro_salvo' ) );
      }
      break;

    /**
     * Salva um horario do itinerario
     * 1º Instancia LinhaTransporte referente a linha em que esta sendo feito o vinculo
     * 2º Instancia LinhaItinerarioHorario, seta os dados e salva as informacoes
     *
     * @param integer $oParam->iItinerario      - Tipo de itinerario que o horario sera vinculado
     * @param string  $oParam->sHoraPartida     - Hora de partida do itinerario
     * @param string  $oParam->sHoraChegada     - Hora de chegada do itinerario
     * @param integer $oParam->iLinhaTransporte - Codigo de LinhaTransporte
     */
    case 'adicionarItinerarioHorario':

      if (isset($oParam->sHoraPartida) && isset($oParam->sHoraChegada) && isset($oParam->iLinhaTransporte)) {

        $oLinhaTransporte        = new LinhaTransporte($oParam->iLinhaTransporte);
        foreach ($oLinhaTransporte->getItinerarios() as $oLinhaItinerario) {

          if ($oParam->iItinerario == $oLinhaItinerario->getTipo()) {

            $oLinhaItinerarioHorario = new LinhaItinerarioHorario();
            $oLinhaItinerarioHorario->setHoraSaida($oParam->sHoraPartida);
            $oLinhaItinerarioHorario->setHoraChegada($oParam->sHoraChegada);
            $oLinhaItinerarioHorario->setLinhaItinerario($oLinhaItinerario);
            $oLinhaItinerarioHorario->salvar();
          }
        }

        $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'itinerario_horario_salvo' ) );
      }
      break;

    /**
     * Retorna os logradouros vinculados ao itinerario da linha de transporte
     * @param integer $oParam->iCodigoLinha - Codigo de LinhaTransporte
     *
     * @return array stdClass $oRetorno->aLogradouros
     *                        integer iCodigoLinhaLogradouro  - Codigo de LinhaItinerarioLogradouro
     *                        integer iBairroLogradouro       - Codigo de LogradouroBairro
     *                        string  sNomeLogradouro         - Descricao do logradouro
     *                        string  sBairro                 - Descricao do bairro
     *                        integer iTipo                   - Tipo do itinerario (1 - Ida / 2 - Retorno)
     *                        integer iOrdem                  - Ordem do logradouro no itinerario
     */
    case 'getLogradouros':

      if (isset($oParam->iCodigoLinha)) {

        $oRetorno->aLogradouros = array();
        $oLinhaTransporte       = new LinhaTransporte($oParam->iCodigoLinha);
        foreach ($oLinhaTransporte->getItinerarios() as $oLinhaItinerario) {

          if (isset($oParam->iItinerario) && $oParam->iItinerario != $oLinhaItinerario->getTipo()) {
            continue;
          }

          foreach ($oLinhaItinerario->getLogradouros() as $oLinhaItinerarioLogradouro) {

            $oDadosLogradouro = new stdClass();
            $oDadosLogradouro->iCodigoLinhaLogradouro  = $oLinhaItinerarioLogradouro->getCodigo();
            $oDadosLogradouro->iBairroLogradouro       = $oLinhaItinerarioLogradouro->getLogradouroBairro()
                                                                                    ->getCodigo();
            $oDadosLogradouro->sNomeLogradouro         = urlencode($oLinhaItinerarioLogradouro->getLogradouroBairro()
                                                                                              ->getLogradouro()
                                                                                              ->getDescricao());
            $oDadosLogradouro->sBairro                 = urlencode($oLinhaItinerarioLogradouro->getLogradouroBairro()
                                                                                              ->getBairro()
                                                                                              ->getDescricao());
            $oDadosLogradouro->iTipo                   = $oLinhaItinerario->getTipo();
            $oDadosLogradouro->iOrdem                  = $oLinhaItinerarioLogradouro->getOrdem();
            $oRetorno->aLogradouros[]                  = $oDadosLogradouro;
          }
        }
      }
      break;

    /**
     * Retorna os horarios vinculados a linha de transporte
     * @param integer $oParam->iCodigoLinha - Codigo de LinhaTransporte
     *
     * @return array stdClass $oRetorno->aHorariosItinerario
     *                        integer iCodigoHorario - Codigo de LinhaItinerarioHorario
     *                        string  sHoraPartida   - Hora de partida da linha
     *                        string  sHoraRetorno   - Hora de retorno da linha
     */
    case 'getHorariosItinerarios':

      if (isset($oParam->iCodigoLinha)) {

        $oRetorno->aHorariosItinerario = array();
        $oLinhaTransporte              = new LinhaTransporte($oParam->iCodigoLinha);
        foreach ($oLinhaTransporte->getItinerarios() as $oLinhaItinerario) {

          foreach ($oLinhaItinerario->getHorarios() as $oLinhaItinerarioHorario) {

            $oDadosHorario                   = new stdClass();
            $oDadosHorario->iCodigoHorario   = $oLinhaItinerarioHorario->getCodigo();
            $oDadosHorario->sHoraPartida     = urlencode($oLinhaItinerarioHorario->getHoraSaida());
            $oDadosHorario->sHoraRetorno     = urlencode($oLinhaItinerarioHorario->getHoraChegada());
            $oDadosHorario->iItinerario      = $oLinhaItinerarioHorario->getTipoItinerario();
            $oRetorno->aHorariosItinerario[] = $oDadosHorario;
          }
        }
      }
      break;

    /**
     * Remove um horario vinculado a linha de transporte
     * @param integer $oParam->iCodigoHorario - Codigo de LinhaItinerarioHorario
     */
    case 'removerHorario':

      if (isset($oParam->iCodigoHorario)) {

        $oLinhaItinerarioHorario = new LinhaItinerarioHorario($oParam->iCodigoHorario);
        $oLinhaItinerarioHorario->remover();

        $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'itinerario_horario_removido' ) );
      }
      break;

    /**
     * Remove um logradouro vinculado ao itinerario
     * @param integer $oParam->iCodigoLinhaLogradouro - Codigo de LinhaItinerarioLogradouro
     */
    case 'removerLogradouro':

      if (isset($oParam->iCodigoLinhaLogradouro)) {

        $oLinhaItinerarioLogradouro = new LinhaItinerarioLogradouro($oParam->iCodigoLinhaLogradouro);
        $oLinhaItinerarioLogradouro->remover();

        $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'itinerario_logradouro_removido' ) );
      }
      break;

    /**
     * Vincula um ponto de parada ao itinerário
     *
     * @param string $oParam->iItinerarioLogradouro
     * @param string $oParam->iPontoParada
     */
    case 'vincularPontoParada':

      if (isset($oParam->iItinerarioLogradouro) && isset($oParam->iPontoParada)) {

        $oLinhaItinerarioLogradouro = new LinhaItinerarioLogradouro($oParam->iItinerarioLogradouro);

        if ($oLinhaItinerarioLogradouro->adicionarPontoDeParada(new PontoParada($oParam->iPontoParada))) {
          $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'vinculopontoparada_salvo' ) );
        }
      }
      break;

    /**
     * Remove o ponto de parada do itinerário
     *
     * @param string $oParam->iItinerarioLogradouro
     * @param string $oParam->iPontoParada
     */
    case 'removerPontoParada':

      if (isset($oParam->iPontoParada)) {

        //Validação para verificar se não existe aluno vinculado ao ponto.
        $oLinhaTransportePontoParadaAluno    = new cl_linhatransportepontoparadaaluno();
        $sWhere                              = "tre12_linhatransportepontoparada = {$oParam->iPontoParada}";
        $sSqlLinhaTransportePontoParadaAluno = $oLinhaTransportePontoParadaAluno->sql_query_file(null,
                                                                                                 'tre12_sequencial',
                                                                                                 'tre12_sequencial',
                                                                                                 $sWhere);

        $rsLinhaTransportePontoParadaAluno = db_query( $sSqlLinhaTransportePontoParadaAluno );

        if( !$rsLinhaTransportePontoParadaAluno ) {

          $oErro        = new stdClass();
          $oErro->sErro = pg_last_error();

          throw new DBException( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'erro_buscar_vinculo_pontoparada', $oErro ) );
        }

        if( pg_num_rows( $rsLinhaTransportePontoParadaAluno ) > 0 ) {
          throw new BusinessException( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'aluno_vinculado' ) );
        }

        $oItinerarioPontoParada = new ItinerarioPontoParada($oParam->iPontoParada);
        $oItinerarioPontoParada->remover();

        $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'vinculopontoparada_excluido' ) );
      }
      break;

    /**
     * Busca paradas por itinerário
     *
     * @param string $oParam->iLinhaTransporte
     * @return array PontoParada
     */
    case 'getPontoParadaPorItinerario':

      if (isset($oParam->iLinhaTransporte)) {

        $oRetorno->aPontosParada = array();
        $oLinhaTransporte        = new LinhaTransporte($oParam->iLinhaTransporte);
        foreach ($oLinhaTransporte->getItinerarios() as $oLinhaItinerario) {

          foreach ($oLinhaItinerario->getLogradouros() as $oLinhaItinerarioLogradouro) {

            foreach ($oLinhaItinerarioLogradouro->getPontosDeParada() as $oPontoParada) {

              $oDadosPontoParada = new stdClass();
              $oDadosPontoParada->iCodigo      = $oPontoParada->getCodigo();
              $oDadosPontoParada->sLogradouro  = urlencode($oLinhaItinerarioLogradouro->getLogradouroBairro()
                                                                                      ->getLogradouro()
                                                                                      ->getDescricao());
              $oDadosPontoParada->sBairro      = urlencode($oLinhaItinerarioLogradouro->getLogradouroBairro()
                                                                                      ->getBairro()->getDescricao());
              $oDadosPontoParada->sPontoParada = urlencode($oPontoParada->getPontoParada()->getNome());
              $oDadosPontoParada->sItinerario  = urlencode($oLinhaItinerario->getTipo() == 1 ? "Ida" : "Retorno");
              $oRetorno->aPontosParada[]       = $oDadosPontoParada;
            }
          }
        }
      }
      break;

    /**
     * Busca paradas por logradouro
     *
     * @param string $oParam->iItinerarioLogradouro
     * @return array PontoParada
     */
    case 'getPontoParadaPorLogradouro':

      $oRetorno->aPontosParada = array();
      if (isset($oParam->iItinerarioLogradouro)) {

        $oLinhaItinerarioLogradouro = new LinhaItinerarioLogradouro($oParam->iItinerarioLogradouro);
        $oRetorno->aPontosParada    = $oLinhaItinerarioLogradouro->getPontosParadaPorLogradouro();
      }
      break;

    /**
     * Remove uma linha de transporte
     * @param integer $oParam->iCodigoLinha - Codigo da linha a ser removida
     */
    case 'removerLinha':

      if (isset($oParam->iCodigoLinha)) {

        $oLinhaTransporte = new LinhaTransporte($oParam->iCodigoLinha);
        $oLinhaTransporte->remover();

        $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'linha_transporte_removida' ) );
      }
      break;

    case 'salvarReordenacaoItinerario':


      if (is_array($oParam->aNovoItinerario)) {

        foreach ($oParam->aNovoItinerario as $oItinerarioOrdenado) {

          $oItinerario = new LinhaItinerarioLogradouro($oItinerarioOrdenado->iCodigo);
          $oItinerario->setOrdem($oItinerarioOrdenado->iOrdem);
          $oItinerario->salvar();
        }

      }

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'itinerario_reordenado' ) );
      break;

    case 'gerarRetorno':

      $oLinhaTransporte = new LinhaTransporte($oParam->iCodigoLinha);
      $oLinhaTransporte->gerarRetorno();

      $oRetorno->sMensagem = urlencode( _M( MENSAGENS_TRE4_LINHATRANSPORTE_RPC . 'retorno_gerado')  );

      break;
  }
  db_fim_transacao();
} catch (Exception $eException) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eException->getMessage());
  db_fim_transacao(true);
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo $oJson->encode($oRetorno);