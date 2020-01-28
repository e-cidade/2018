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
 * Classe singleton para  a configuraчуo do serviчo da coleta
 * dos dados de entrada dos alunos na escola
 * @version $Revision: 1.10 $
 * @author dbseller
 * @package Webservice
 *
 */
class ControleAcessoAluno {

  static $sInstance = null;
  private $sUrlWebservice;
  private $lUsaWsdl;
  /**
   * define os dados da classe
   */
  private function __construct() {

    require_once(modification("webservices/config/integracaoControleAcessoAluno.wsdl.php"));
    $this->sUrlWebservice = $sUrlWebService;
    $this->lUsaWsdl       = $lUsaWsdl;
  }

  /**
   * marcamos o mщtodo como private, evitando e termos
   * duas instancias da classe
   */
  private function __clone() {

  }

  /**
   * retorna a instancia da classe
   * @return ControleAcessoAluno
   */
  public static function getInstance() {

    if (self::$sInstance == null) {
      self::$sInstance = new ControleAcessoAluno();
    }
    return self::$sInstance;
  }

  /**
   * Retorna a url de configuracao do servico
   * @return string
   */
  public static function getUrlWebservice() {

    return self::getInstance()->sUrlWebservice;
  }

  /**
   * Webservice usa wsdl
   * @return boolean
   */
  public static function wsdlMode() {
    return self::getInstance()->lUsaWsdl;
  }
  public static function alunoEstaNaEscola($iAluno, $dtDiaAula, $sInicioAula, $sTerminoAula) {

    $lAlunoEmAula = false;
    $oDaoAlunoAcessoRegistro = db_utils::getDao("controleacessoalunoregistro");

    $sWhereEntrada  = " ed303_aluno = {$iAluno}";
    $sWhereEntrada .= " and ed101_dataleitura = '{$dtDiaAula}' ";
    $sWhereEntrada .= " and ed101_horaleitura between cast( '{$sInicioAula}' - '45 min'::interval as varchar) ";
    $sWhereEntrada .= "                          and cast( '{$sInicioAula}' + '45 min'::interval as varchar) ";
    $sSqlEntrada    = $oDaoAlunoAcessoRegistro->sql_query_acesso_aluno(null,
                                                                       "min(ed101_horaleitura) as hora_entrada",
                                                                        null,
                                                                        $sWhereEntrada
                                                                       );
    $rsEntradaAula  = $oDaoAlunoAcessoRegistro->sql_record($sSqlEntrada);
    $sHoraEntrada   = db_Utils::fieldsMemory($rsEntradaAula, 0)->hora_entrada;

    if ($sHoraEntrada != "") {
      $lAlunoEmAula = true;
    }
    $sWhereSaida  = " ed303_aluno = {$iAluno} ";
    $sWhereSaida .= " and ed101_dataleitura = '{$dtDiaAula}' ";
    $sWhereSaida .= " and ed101_horaleitura between cast( '{$sTerminoAula}' - '30 min'::interval as varchar) ";
    $sWhereSaida .= "                          and cast( '{$sTerminoAula}' + '45 min'::interval as varchar) ";
    $sSqlSaida    = $oDaoAlunoAcessoRegistro->sql_query_acesso_aluno(null,
                                                                     "min(ed101_horaleitura) as hora_saida",
                                                                     null,
                                                                     $sWhereSaida
                                                                    );
    $rsSaidaAula  = $oDaoAlunoAcessoRegistro->sql_record($sSqlSaida);
    $sHoraSaida   = db_Utils::fieldsMemory($rsSaidaAula, 0)->hora_saida;
    if ($sHoraSaida != "") {
      $lAlunoEmAula = false;
    }
    return $lAlunoEmAula;
  }

  /**
   * Retorna a URI do Servico
   * @return string
   */
  public static function getURI() {


    $sUrlInversa         = strrev(ControleAcessoAluno::getUrlWebservice());
    $iPosicaoFImPoograma = strpos($sUrlInversa, "/");
    $sUri                = strrev(substr_replace($sUrlInversa, "", 0, $iPosicaoFImPoograma));
    return $sUri;
  }


  /**
   * retorna se o aluno possui alguma leitura no RFID no dia
   * @param DBDate $dtLeitura data da leitura
   * @param Aluno $oAluno aluno
   */
  static function alunosTemLeituraNaData(Aluno $oAluno, DBDate $dtLeitura) {

    $oDaoAlunoAcessoRegistro = db_utils::getDao("controleacessoalunoregistro");

    $sWhereLeituras  = " ed303_aluno = {$oAluno->getCodigoAluno()}";
    $sWhereLeituras .= " and ed101_dataleitura = '".$dtLeitura->convertTo(DBDate::DATA_EN)."}' ";
    $sSqlLeitura     = $oDaoAlunoAcessoRegistro->sql_query_acesso_aluno(null,
                                                                        "count(*) as leituras",
                                                                        null,
                                                                        $sWhereLeituras
                                                                       );
    $rsLeituras   = $oDaoAlunoAcessoRegistro->sql_record($sSqlLeitura);

    return db_utils::fieldsMemory($rsLeituras, 0)->leituras > 0;
  }


  /**
   * Retorna as configuracoes para a notificacao dos alunos que possuem falta/nao estao em sala de aula.
   * @return stdClass
   */
  static function getMensagemNotificacao() {


    $sArquivoConfiguracao = "config/notificacao/servicos/notificacaoaluno.xml";
    if (!file_exists($sArquivoConfiguracao)) {
      throw new FileException('Arquivo de configuraчуo para a notificaчуo dos alunos nуo encontrado.');
    }
    $oDomXML = new DOMDocument();
    $oDomXML->preserveWhiteSpace = false;
    $oDomXML->formatOutput       = true;
    $oDomXML->load($sArquivoConfiguracao);

    $sOperadora   = $oDomXML->getElementsByTagName("operadora")->item(0)->getAttribute("nome");
    $sMSGAmarela  = $oDomXML->getElementsByTagName("mensagemamarela")->item(0)->getAttribute("texto");
    $sMSGVermelha = $oDomXML->getElementsByTagName("mensagemvermelha")->item(0)->getAttribute("texto");

    $oDados                    =  new stdClass();
    $oDados->sOperadora        = $sOperadora;
    $oDados->sMensagemAmarela  = $sMSGAmarela;
    $oDados->sMensagemVermelha = $sMSGVermelha;
    return $oDados;
  }
}

?>