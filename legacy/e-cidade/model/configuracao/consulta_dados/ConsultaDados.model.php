<?php

/**
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

require_once(modification("classes/db_db_logsacessa_classe.php" ));
require_once(modification("libs/db_stdlib.php" ));

/**
 * ConsultaDados
 *
 * @package WebServices
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 */
class ConsultaDados {

  const PATH_REQUISICOES = 'webservices/consulta_dados/requisicoes';

  /**
   * Parametros de Saida da Consulta
   *
   * @var array
   * @access private
   */
  private $aResultado   = array();

  /**
   * Parametros de Entrada da Consulta
   *
   * @var array
   * @access private
   */
  private $aParametrosEntrada = array();

  /**
   * rsConn
   *
   * @var resource
   * @access private
   */
  private $rsConn;

  /**
   * Tempo em Minutos referente a query
   *
   * @var float
   * @access private
   */
  private $iTempoLimite       = 1;

  /**
   * Parte Inicial da Consulta de Dados, método chamado no webservice
   *
   * @param string $sMetodo
   * @param array  $aParametrosEntrada
   * @param array  $aResultado
   * @static
   * @access public
   * @return void
   */
  public function consultar( $sMetodo, $aParametrosEntrada , $aResultado ) {

    try {

      global $conn;

      $this->rsConn             = $conn;
      $this->aParametrosEntrada = $aParametrosEntrada;
      $this->aResultado         = $aResultado;

      return $this->getResultadoSQL( $sMetodo );

    } catch ( DBException $oErro ) {

      $oDaoDBLogsAcessa            = new cl_db_logsacessa();
      $oDaoDBLogsAcessa->ip        = $_SERVER['REMOTE_ADDR'];
      $oDaoDBLogsAcessa->data      = date("Y-m-d");
      $oDaoDBLogsAcessa->hora      = date("H:i");
      $oDaoDBLogsAcessa->obs       = pg_escape_string($oErro->getMessage());
      $oDaoDBLogsAcessa->arquivo   = $sMetodo;
      $oDaoDBLogsAcessa->id_usuario= 1;
      $oDaoDBLogsAcessa->id_modulo = $_SESSION['DB_modulo'];
      $oDaoDBLogsAcessa->id_item   = isset($_SESSION['DB_itemmenu_acessado']) ? $_SESSION['DB_itemmenu_acessado'] : 9605;
      $oDaoDBLogsAcessa->coddepto  = 1;
      $oDaoDBLogsAcessa->instit    = $oErro->getCode();
      $oDaoDBLogsAcessa->incluir(null);
      $_SESSION = $oDaoDBLogsAcessa->codsequen;

      if ( $oDaoDBLogsAcessa->erro_status == "0" ) {
        throw new SoapFault("e-Cidade","Erro ao processar registros de Log.\n" . utf8_encode($oDaoDBLogsAcessa->erro_msg));
      }

      throw new SoapFault("e-Cidade", "Erro ao Buscar os Dados do WebService. Contate Suporte. " . pg_last_error());
    } catch ( SoapFault $oErro ) {
      throw new Exception( $oErro->faultstring );
    } catch ( Exception $oErro ) {
      throw $oErro;
    }
  }

  /**
   * getResultadoSQL
   *
   * @param mixed $sMetodo
   * @access private
   * @return void
   */
  private function getResultadoSQL( $sMetodo ) {

    $sSQLStatusSistema  = 'select codigo, db21_ativo from db_config where prefeitura is true;';
    $rsSQLStatusSistema = pg_query($sSQLStatusSistema);

    if ( !$rsSQLStatusSistema ) {
      throw new SoapFault('e-Cidade', 'Erro ao Validar Status do Sistema');
    }

    $oDados = pg_fetch_object($rsSQLStatusSistema, 0);

    if ( $oDados->db21_ativo <> 1 ) {
      throw new SoapFault('e-Cidade', 'Sistema nao esta ativo. Tente mais tarde.');
    }

    $sCaminhoMetodo = DBFileExplorer::getCaminhoArquivo(ConsultaDados::PATH_REQUISICOES, $sMetodo.'.xml');

    if ( !$sCaminhoMetodo ) {
      throw new SoapFault("e-Cidade", "Metodo nao Encontrado. Contate Suporte.");
    }

    $sSQL                = ConsultaDados::carregarXMLPesquisa( $sCaminhoMetodo );
    $iMomentoInicioQuery = time();
    $lEnviouQuery        = pg_send_query( $this->rsConn, $sSQL);

    if (!$lEnviouQuery) {
       throw new SoapFault('e-Cidade', 'Não Foi possivel enviar a Query.'.pg_last_error().'...');
    }

    $iTempoExecucaoQuery = 0;

    while ( true ) {

      $lConexaoOcupada   = pg_connection_busy( $this->rsConn );
      $iTempoAtual       = time();
      $iMinutosDiferenca = ( $iTempoAtual - $iMomentoInicioQuery) / 60;

      if ( $lConexaoOcupada ) {

        if ( $iMinutosDiferenca > $this->iTempoLimite ) {

          pg_cancel_query($this->rsConn);
          throw new SoapFault("e-Cidade", "Tempo limite de Execucao da Consulta foi Atingido.");
        }
        continue;
      }
      $iTempoExecucaoQuery = $iTempoAtual - $iMomentoInicioQuery;
      break;
    }

    $rsSQL = pg_get_result( $this->rsConn );
    $sErro = pg_last_error();

    if ( !empty($sErro) ) {

      $sLog                        = "--------------------------------------------------------------------\n";
      $sLog                       .= "CHAMADA PARA O MÉTODO: \n{$sMetodo}                                 \n";
      $sLog                       .= "--------------------------------------------------------------------\n";
      $sLog                       .= "FILTROS PARA CONSULTA:\n".print_r($this->aParametrosEntrada, 1)."   \n";
      $sLog                       .= "--------------------------------------------------------------------\n";
      $sLog                       .= "CAMPOS INFORMADOS PARA RETORNO:\n ". print_r($this->aResultado, 1)."\n";
      $sLog                       .= "--------------------------------------------------------------------\n";
      $sLog                       .= "QUERY EXECUTADA:\n". $sSQL;
      $sLog                       .= "--------------------------------------------------------------------\n";
      $sLog                       .= "ERRO OCORRIDO:\n". pg_last_error() . "\n";
      $sLog                       .= "--------------------------------------------------------------------\n";
      $sLog                       .= "TEMPO DE EXECUCAO: \n".$iTempoExecucaoQuery . "seg\n";

      throw new DBException($sLog, $oDados->codigo);
    }

    $iMemoriaLimite = preg_replace('/[a-zA-Z]/',"",ini_get("memory_limit")) * 1024 * 1024;
    $aRetorno       = array();

    while ( $oDados = pg_fetch_object( $rsSQL ) ) {

      $iMemoriaUtilizada = memory_get_usage();
      $aRetorno[]        = $oDados;

      foreach ( $oDados as $sCampo =>$sValor ) {
        $oDados->$sCampo = urlencode($sValor);
      }

      if ( $iMemoriaUtilizada >= $iMemoriaLimite * 0.90 ) { // Deixa 10% da memória para responder
        throw new SoapFault("e-Cidade","Quantidade de Dados Retornada nao Suportada Pelo Servidor. Registros Encontrados: " . pg_num_rows($rsSQL));
      }
    }

    return $aRetorno;
  }

  /**
   * carregarXMLPesquisa
   *
   * @param mixed $sCaminhoXML
   * @access private
   * @return void
   */
  private function carregarXMLPesquisa( $sCaminhoXML ) {

    try {

      $aLabelsInformados  = $this->aResultado;
      $aFiltrosInformados = $this->aParametrosEntrada;

      $oArquivoXML        = new DOMDocument();
      $oArquivoXML->load( $sCaminhoXML );

      /**
       * Nodes básicos do XML
       */
      $oNodeConsulta    = $oArquivoXML->getElementsByTagName('consulta')->item(0);
      $oNodeCampos      = $oNodeConsulta->getElementsByTagName('campos')->item(0);
      $oNodeFrom        = $oNodeConsulta->getElementsByTagName('from')->item(0);
      $oNodeJuncoes     = $oNodeConsulta->getElementsByTagName('juncoes')->item(0);
      $oNodeFiltros     = $oNodeConsulta->getElementsByTagName('filtros')->item(0);
      $oNodeAgrupamento = $oNodeConsulta->getElementsByTagName('agrupamento')->item(0);
      $oNodeOrdenacao   = $oNodeConsulta->getElementsByTagName('ordenacao')->item(0);


      /**
       * Estruturando Variáveis para criar a String SQL
       */

      $lDistinct        = $oNodeConsulta->getAttribute("distinct") == "true";

      /**
       * Montando Campos para criar a query
       */
      $aCampos          = array();
      $aCamposXML       = array();
      foreach ( $oNodeCampos->getElementsByTagName("campo") as $iIndiceNode => $oNodeCampo ) {

        $sNomeCampo               = $oNodeCampo->getAttribute("campo");
        $sLabelCampo              = $oNodeCampo->getAttribute("label");
        $aCamposXML[$sLabelCampo] = "{$sNomeCampo} as {$sLabelCampo}";
      }

      foreach ( $aLabelsInformados as $sLabelInformado ) {

        if ( !array_key_exists($sLabelInformado, $aCamposXML ) ) {
          throw new Exception("O Campo $sLabelInformado nao foi encontrado na Lista de campos permitidos");
        }

        $aCampos[$sLabelInformado] = $aCamposXML[$sLabelInformado];
      }

      $sTabela          = $oNodeFrom->getAttribute("tabela");

      /**
       * Montando juncoes com outras tabelas
       */
      $aJuncoes         = array();
      foreach ( $oNodeJuncoes->getElementsByTagName('join') as $oNodeJoin ) {

        $sTipoJoin     = $oNodeJoin->getAttribute("tipo");
        $sTabelajoin   = $oNodeJoin->getAttribute("tabela");
        $sCondicaoJoin = $oNodeJoin->getAttribute("condicao");
        $aJuncoes[]    = "{$sTipoJoin} join {$sTabelajoin} on {$sCondicaoJoin}";
      }

      /**
       * Montando Filtros
       */
      $aFiltros = array();
      foreach ( $oNodeFiltros->getElementsByTagName('filtro') as $oNodeFiltro ) {

        $sLabelFiltro         = $oNodeFiltro->getAttribute("label");
        $sCondicao            = $oNodeFiltro->getAttribute("condicao");
        $lCondicaoObrigatorio = $oNodeFiltro->getAttribute("obrigatorio") == "true";


        if ( !empty($sLabelFiltro)  && !array_key_exists($sLabelFiltro, $aFiltrosInformados) && $lCondicaoObrigatorio ) {
          throw new Exception("O Campo $sLabelFiltro deve ser passado por parametro");
        } else if ( !empty($sLabelFiltro)  && !array_key_exists($sLabelFiltro, $aFiltrosInformados) && !$lCondicaoObrigatorio ) {
          continue;
        }

        if ( array_key_exists($sLabelFiltro, $aFiltrosInformados) ) {
          $sCondicao = str_replace("\${$sLabelFiltro}", $aFiltrosInformados[$sLabelFiltro], $sCondicao);

          $sTabela = str_replace("\${$sLabelFiltro}", $aFiltrosInformados[$sLabelFiltro], $sTabela);

        }
        $aFiltros[] = $sCondicao;
      }

      $sGroupBy         = $oNodeAgrupamento->getAttribute("campos");
      $sOrderBy         = $oNodeOrdenacao->getAttribute("campos");

      /**
       * Criando Strings base para o SQL
       */

      $sDistinct        = $lDistinct           ? "distinct" : "";

      if ( count($aCampos) == 0 ) {
        throw new Exception("Nenhum Campo Selecionado para a consulta");
      }

      $sCampos          = implode(",\n       ", $aCampos);
      $sJuncoes         = count($aJuncoes)== 0 ? ""    : implode("\n       " , $aJuncoes);
      $sGroupBy         = empty($sGroupBy)     ? ""    : "group by {$sGroupBy}";
      $sOrderBy         = empty($sOrderBy)     ? ""    : "order by {$sOrderBy}";
      $sWhere           = count($aFiltros)== 0 ? ""    : "where " . implode("\n   and " , $aFiltros);

      /**
       * Montado SQL Final
       */
      $sSQL = "select {$sDistinct}\n";
      $sSQL.= "       $sCampos    \n";
      $sSQL.= "  from {$sTabela}  \n";
      $sSQL.= "       {$sJuncoes} \n";
      $sSQL.= " {$sWhere}         \n";
      $sSQL.= " {$sGroupBy}       \n";
      $sSQL.= " {$sOrderBy}       \n";

      return $sSQL;
    } catch ( Exception $eErro ) {

      throw new SoapFault("e-Cidade", "Erro ao Processar Requisicao " . $eErro->getMessage());
    }
  }
}
