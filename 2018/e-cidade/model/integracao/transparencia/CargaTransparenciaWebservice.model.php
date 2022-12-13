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

class CargaTransparenciaWebservice {

  const DIRETORIO = "tmp/cargatransparencia/";

  private $sComando;
  private $sDatabase;

  private $sArquivoEstrutura;
  private $sArquivoDados;

  private $aTabelas = array(
      'acordo',
      'acordosituacao',
      'acordogrupo',
      'acordocomissao',
      'acordoposicao',
      'acordoposicaotipo',
      'acordoitem',
      'acordodocumento',
      'empempenhocontrato',
      'empempenho',
      'empelemento',
      'empempaut',
      'empempitem',
      'empautitem',
      'empautitempcprocitem',
      'empautoriza',
      'liclocal',
      'liccomissao',
      'licsituacao',
      'liclicitem',
      'liclicita',
      'cflicita',
      'liclicitaata',
      'liclicitaminuta',
      'liclicitaedital',
      'liclicitaproc',
      'solicitem',
      'solicitempcmater',
      'solicita',
      'solicitemunid',
      'solicitemele',
      'pcmater',
      'pctipocompra',
      'pcprocitem',
      'pcproc',
      'pcorcamitemlic',
      'pcorcamjulg',
      'pcorcamval',
      'pcorcamforne',
      'pcsubgrupo',
      'pctipo',
      'conparametro',
      'conlancamemp',
      'conlancam',
      'conlancamdoc',
      'conhistdoc',
      'conlancamcompl',
      'conplanoorcamento',
      'conplano',
      'conplanogrupo',
      'conlancamrec',
      'conhistdoctipo',
      'rhpessoal',
      'rhpessoalmov',
      'rhfuncao',
      'rhlota',
      'rhregime',
      'rhpesrescisao',
      'rhrubricas',
      'gerfsal',
      'gerfcom',
      'gerfs13',
      'gerfres',
      'gerfadi',
      'assenta',
      'tipoasse',
      'orcelemento',
      'orcorgao',
      'orcprograma',
      'orcprojativ',
      'orcreceita',
      'orcfontes',
      'orctiporec',
      'orcsubfuncao',
      'orcunidade',
      'orcdotacao',
      'orcfuncao',
      'matunid',
      'protprocesso',
      'cgm',
      'bairro',
      'ruas',
      'db_config',
      'db_depart',
      'db_usuarios'
    );

  public function __construct() {

    if (!is_dir(self::DIRETORIO) && !mkdir(self::DIRETORIO, 0775)) {
      throw new Exception("Erro ao iniciar a geração do dump. Erro de permissão ao criar diretóios.");
    }

    $sSenha = trim(db_getsession("DB_senha"));
    $this->sComando  = "pg_dump -U " . db_getsession("DB_user") . " -h " . db_getsession("DB_servidor") . " -p " . db_getsession("DB_porta");
    $this->sComando .= (!empty($sSenha) ? " -W {$sSenha}" : "");
    $this->sDatabase = db_getsession("DB_base");

    $this->sArquivoEstrutura = "estrutura_" . sha1( date("Y-m-d") ) . ".gz";
    $this->sArquivoDados = "dados_" . sha1( date("Y-m-d") ) . ".gz";
  }

  /**
   * Verifica se os arquivos do dump existem
   * @return boolean
   */
  private function existeDump() {

    $sEstrutura = self::DIRETORIO . $this->sArquivoEstrutura;
    $sDados     = self::DIRETORIO . $this->sArquivoDados;

    $lExiste = true;

    if (!file_exists($sEstrutura) || !is_readable($sEstrutura)) {
      $lExiste = false;
    }

    if (!file_exists($sDados) || !is_readable($sDados)) {
      $lExiste = false;
    }

    return $lExiste;
  }

  /**
   * Verifica se o dump iniciado já esta gerado
   * @throws \Exception
   * @return boolean
   */
  private function dumpGerado() {

    $sPidFile = self::DIRETORIO . "processo_dump.txt";

    if (!file_exists($sPidFile)) {

      if ($this->existeDump()) {
        return true;
      } else {
        throw new Exception("Erro ao verificar a situação da geração do dump.");
      }
    }

    exec("ps " . file_get_contents($sPidFile), $aOut);

    if (count($aOut) < 2) {

      unlink($sPidFile);
      return true;
    }

    return false;
  }

  /**
   * Retorna os dados para efetuar o download do dump
   * @return stdclass
   */
  private function getDadosDownload() {

    $sEstrutura = self::DIRETORIO . $this->sArquivoEstrutura;
    $sDados     = self::DIRETORIO . $this->sArquivoDados;

    $oRetorno = new StdClass();
    $oRetorno->dump_gerado = true;
    $oRetorno->estrutura = array(
        'arquivo' => $sEstrutura,
        'md5' => md5_file($sEstrutura)
      );
    $oRetorno->dados = array(
        'arquivo' => $sDados,
        'md5' => md5_file($sDados)
      );

    return $oRetorno;
  }

  /**
   * Retorna os dados para a situação de gerando o dump
   * @return stdclass
   */
  private function getDadosSituacaoGerando() {

    $oRetorno = new StdClass();
    $oRetorno->dump_gerado = false;

    return $oRetorno;
  }

  /**
   * Inicia o processo de geração do dump
   * @throws \Exception
   * @return stdclass
   */
  public function gerarDump($lGerarLargeObjects = false, $lForcarGeracao = false) {

    $sTabelas = " -t " . implode(" -t ", $this->aTabelas);
    $sBlobs   = $lGerarLargeObjects ? " -b " : '';

    if (!$lForcarGeracao) {

      if ($this->existeDump()) {
        return $this->getDadosDownload();
      }
    }

    /**
     * Faz o dump da estrutura
     */
    exec("{$this->sComando} -Fp -s -x -O -Z9 {$sTabelas} {$this->sDatabase} > " . self::DIRETORIO . $this->sArquivoEstrutura, $aOut, $iSaida);

    if ($iSaida != 0) {
      throw new Exception("Erro ao gerar dump da estrutura das tabelas.");
    }

    exec("{$this->sComando} -Fp -s -x -O -Z9 -n public {$this->sDatabase} >> " . self::DIRETORIO . $this->sArquivoEstrutura, $aOut, $iSaida);

    if ($iSaida != 0) {
      throw new Exception("Erro ao gerar dump da estrutura das tabelas.");
    }

    /**
     * Faz o dump dos dados
     *
     * ----
     * 2>&1 means that STDERR is redirected into STDOUT
     * The final & tells the command to execute in the background
     * In a shell the PID of the last process is stored in the variable $!
     */
    exec("{$this->sComando} -Fp -a {$sBlobs} -Z9 {$sTabelas} {$this->sDatabase} > " . self::DIRETORIO . $this->sArquivoDados . " 2>&1 & echo $!", $aOut, $iSaida);

    if ($iSaida != 0) {
      throw new Exception("Erro ao gerar dump dos dados das tabelas.");
    }

    if (!empty($aOut)) {
      file_put_contents(self::DIRETORIO . "processo_dump.txt", trim($aOut[0]));
    } else {
      throw new Exception("Erro ao iniciar dump dos dados.");
    }

    return $this->getDadosSituacaoGerando();
  }

  /**
   * Verifica a situação da geração do dump
   * @throws \Exception
   * @return stdclass
   */
  public function situacaoDump() {

    if (!$this->existeDump()) {
      throw new Exception("Processo de geração do dump não iniciado.");
    }

    if ($this->dumpGerado()) {
      return $this->getDadosDownload();
    }

    return $this->getDadosSituacaoGerando();
  }

  /**
   * Remove o dump gerado
   * @throws \Exception
   * @return boolean
   */
  public function removerDump() {

    if (is_dir(self::DIRETORIO)) {
      $aArquivos = scandir(self::DIRETORIO);

      foreach ($aArquivos as $sArquivo) {

        if ($sArquivo != "index.php" && is_file(self::DIRETORIO . $sArquivo) && is_writable(self::DIRETORIO . $sArquivo)) {
          unlink(self::DIRETORIO . $sArquivo);
        }
      }
    }

    return false;
  }
}
