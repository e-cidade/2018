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

class DBFtp {

  /**
   * [$sFtpServer description]
   * @var string
   */
  private $sFtpServer       = '';

  /**
   * [$sFtpDiretorio description]
   * @var string
   */
  private $sFtpDiretorio    = '';
  
  /**
   * [$sFtpServer description]
   * @var string
   */
  private $sFtpUsuario      = '';
  /**
   * [$sFtpServer description]
   * @var string
   */
  private $sFtpSenha        = '';
  /**
   * [$sFtpServer description]
   * @var string
   */
  private $sFtpMode         = FTP_BINARY;
  /**
   * [$sFtpServer description]
   * @var string
   */
  private $iConexao         = '';
  /**
   * [$sFtpServer description]
   * @var string
   */
  private $sNome            = '';
  /**
   * [$sFtpServer description]
   * @var string
   */
  private $sCaminhoArquivo  = '';

  /**
   * [$lLogin description]
   * @var boolean
   */
  private $lLogin           = false;

  private $lPassiveMode     = false;


  function __construct() {

  }

  /**
   * Seta o servidor de FTP
   * @param String
   */
  public function setFtpServer( $sFtpServer ) {
    $this->sFtpServer = $sFtpServer;
  }

  /**
   * Retorna o servidor FTP
   * @return String
   */
  public function getFtpServer() {
    return $this->sFtpServer;
  }
  
  /**
   * Setter Ftp Diretorio
   * @param string
   */
  public function setFtpDiretorio ($sFtpDiretorio) {
    $this->sFtpDiretorio = $sFtpDiretorio;
  }
  
  /**
   * Getter Ftp Diretorio
   * @return string
   */
  public function getFtpDiretorio () {
    return $this->sFtpDiretorio; 
  }
  
  /**
   * Seta usuario FTP
   * @param String
   */
  public function setFtpUsuario( $sFtpUsuario ) {
    $this->sFtpUsuario = $sFtpUsuario;
  }
  
  /**
   * Retorna usuario FTP
   * @return String
   */
  public function getFtpUsuario() {
    return $this->sFtpUsuario;
  }
  
  /**
   * Seta senha FTP
   * @param String
   */
  public function setFtpSenha( $sFtpSenha ) {
    $this->sFtpSenha = $sFtpSenha;
  }
  
  /**
   * Retorna senha FTP
   * @return String
   */
  public function getFtpSenha() {
    return $this->sFtpSenha;
  }
  
  /**
   * Seta o mode FTP
   * @param String
   */
  public function setFtpMode( $sFtpMode ) {
    $this->sFtpMode = $sFtpMode;
  }
  
  /**
   * Retorna o mode FTP
   * @return String
   */
  public function getFtpMode() {
    return $this->sFtpMode;
  }
   
  /**
   * Seta o nome do Arquivo
   * @param String
   */
  public function setNome( $sNome ) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna o nome do Arquivo
   * @return String
   */
  public function getNome() {
    return $this->sNome;
  }
  
  /**
   * Seta o caminho do arquivo
   * @param String
   */
  public function setCaminhoArquivo( $sCaminhoArquivo ) {
    $this->sCaminhoArquivo = $sCaminhoArquivo;
  }
  
  /**
   * Retorna o caminho do Arquivo
   * @return String
   */
  public function getCaminhoArquivo() {
    return $this->sCaminhoArquivo;
  }

  /**
   * Setter lPassiveMode
   * @param boolean
   */
  public function setPassiveMode ($lPassiveMode) {
    $this->lPassiveMode = $lPassiveMode;
  }
  
  /**
   * Getter lPassiveMode
   * @return boolean
   */
  public function getPassiveMode () {
    return $this->lPassiveMode; 
  }
  

  /**
   * Conecta ao servidor FTP
   * @return void
   */
  public function conectar() {
    $this->iConexao = ftp_connect($this->sFtpServer);
  }

  public function login() {
    $this->lLogin   = ftp_login($this->iConexao, $this->sFtpUsuario, $this->sFtpSenha);
  }
  /**
   * Envia arquivos para o FTP
   * @return boolean
   */
  public function enviarArquivo() {

    if ( empty($this->iConexao) ) {
      $this->conectar();
    }
    if ( !$this->lLogin ) {
      $this->login();
    }

    ftp_pasv($this->iConexao, $this->lPassiveMode);

    return ftp_put($this->iConexao, $this->sNome, $this->sCaminhoArquivo, $this->sFtpMode);
  }


  /**
   * Desconecta do servidor FTP
   * @param  boolean $lRemoverArquivoOrigem - Se true, remove o arquivo do servidor de origem
   * @return boolean
   */
  public function desconectar($lRemoverArquivoOrigem = false) {

    if( $lRemoverArquivoOrigem ) {
      unlink( $this->sCaminhoArquivo );
    }
    ftp_close($this->iConexao);
  }

  /**
   * Acessa um diretório no servidor, se o diretorio não existir cria.
   * @param  string $sDiretorio Nome da pasta
   * @return boolean
   */
  public function acessarDiretorio($sDiretorio) {

    if ( empty($this->iConexao) ) {
      $this->conectar();
    }
    if ( !$this->lLogin ) {
      $this->login();
    }

    if ( @ftp_chdir($this->iConexao, $sDiretorio)) {
      return true;
    } else {

      if ( ftp_mkdir($this->iConexao, $sDiretorio)) {
        $this->acessarDiretorio($sDiretorio);
      }
    }
    return false ;         
  }


}
