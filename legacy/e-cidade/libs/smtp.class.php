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

class Smtp {

  public $conn;
  public $user;
  public $pass;
  public $html;
  public $debug = false;

  /**
   * SMTP reply line ending.
   * @type string
   */
  private $CRLF = "\r\n";

  /**
   * The timeout value for connection, in seconds.
   * Default of 5 minutes (300sec) is from RFC2821 section 4.5.3.2
   * This needs to be quite high to function correctly with hosts using greetdelay as an anti-spam measure.
   * @link http://tools.ietf.org/html/rfc2821#section-4.5.3.2
   * @type integer
   */
  private $timeOut = 300;

  /**
   * How long to wait for commands to complete, in seconds.
   * Default of 5 minutes (300sec) is from RFC2821 section 4.5.3.2
   * @type integer
   */
  private $timeLimit = 300;

  public function __construct() {

    if (!file_exists('libs/config.mail.php')) {
      throw new Exception("Arquivo de configuração de e-mail não encontrado!");
    }

    include(modification('libs/config.mail.php'));

    if (empty($sHost)) {
      throw new Exception("Host servidor de e-mail não informado! \nVerifique arquivo de configuração.");
    }

    if (empty($sPort)) {
      throw new Exception("Porta servidor de e-mail não informado! \nVerifique arquivo de configuração.");
    }

    $this->conn = @fsockopen($sHost, $sPort, $errno, $errstr, 3);
    if (!$this->conn) {
      throw new Exception("Falha ao conectar com o servidor de email! \nVerifique arquivo de configuração.");
    }

    stream_set_timeout($this->conn, $this->timeOut);

    $this->Put("EHLO $sHost");
    $this->user = $sUser;
    $this->pass = $sPass;
  }

  /**
   * @return bool
   */
  public function Auth() {

    $this->Put("AUTH LOGIN");
    $this->Put(base64_encode($this->user));
    $this->Put(base64_encode($this->pass));

    return true;
  }

  /**
   * @param string $to
   * @param string $from
   * @param string $subject
   * @param string $msg
   * @returm bool
   */
  public function Send($to, $from, $subject, $msg) {

    if (!is_resource($this->conn)) {
      return false;
    }

    $this->Auth();
    $this->Put("MAIL FROM:<$from>");
    $this->Put("RCPT TO:<$to>");
    $this->Put("DATA");
    $this->sendHeader($to, $from, $subject);
    $this->Put($msg);
    $this->Put(".");
    $this->Close();

    return true;
  }

  /**
   * @param string $value
   * @param bool $wait - esperar resposta do comando
   * @return mixed
   */
  public function Put($value, $wait = true) {

    /**
     * Resposta do comando
     * @var string
     */
    $data = '';

    /**
     * total de bytes escritos
     * @var mixed
     */
    $bufferSend = fputs($this->conn, $value . $this->CRLF);

    if (!$bufferSend || !$wait) {
      return $bufferSend;
    }

    $timeLimit = time() + $this->timeLimit;

    while (is_resource($this->conn) && !feof($this->conn)) {

      $str = fgets($this->conn, 515);
      $data .= $str;
      if (substr($str, 3, 1) == ' ' || time() > $timeLimit) {
        break;
      }
    }

    return $data;
  }

  /**
   * @param string $to
   * @param string $from
   * @param string $subject
   * @return void
   */
  public function sendHeader($to, $from, $subject) {

    $this->Put("Message-ID: <". date('YmdHis').".". md5(microtime()).".". strtoupper($from) .">", false);
    $this->Put("From: <" . $from . ">", false);
    $this->Put("To: <".$to.">", false);
    $this->Put("Subject: ".$subject, false);
    $this->Put("Date: ". date('D, d M Y H:i:s O'), false);
    if ($this->html) { 
      $this->Put("Content-Type: text/html; charset=iso-8859-1", false);
    }
    $this->Put("X-MSMail-Priority: High", false);
  }

  /**
   * @return bool
   */
  public function Close() {

    $this->Put("QUIT", false);
    return fclose($this->conn);
  }

}
