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

/**
 * Class File
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @version $Revision: 1.5 $
 */
class File {

  /**
   * Caminho
   * @var string
   */
  private $sFilePath;

  /**
   * Tamanho
   * @var float
   */
  private $nSize;

  /**
   * Extensão
   * @var string
   */
  private $sExtension;

  /**
   * Diretório
   * @var string
   */
  private $sDiretory;

  /**
   * Nome do arquivo com extensão
   * @var string
   */
  private $sBaseName;

  /**
   * Nome do Arquivo sem extensão
   * @var string
   */
  private $sFileName;


  const TYPE_COMPRESS_ZIP = 'zip';


  /**
   * Carrega as informações do arquivo informado através do parâmetro
   * new File('tmp/arquivo.txt');
   * @param $sFilePath
   * @throws Exception
   */
  public function __construct($sFilePath) {

    $this->sFilePath = $sFilePath;
    if (!is_readable($this->sFilePath)) {
      throw new Exception("Arquivo {$this->sFilePath} não encontrado ou sem permissão de leitura.");
    }

    $this->normalizePath();
  }

  /**
   * Normaliza as informações do arquivo
   * @return void
   */
  private function normalizePath() {

    $aPathInfo        = pathinfo($this->sFilePath);
    $this->nSize      = filesize($this->sFilePath);
    $this->sDiretory  = $aPathInfo['dirname'];
    $this->sBaseName  = $aPathInfo['basename'];
    $this->sExtension = !empty($aPathInfo['extension']) ? $aPathInfo['extension'] : '';
    $this->sFileName  = !empty($aPathInfo['filename']) ? $aPathInfo['filename'] : '';
  }

  /**
   * Altera o nome do arquivo.
   * File->rename('novonome');
   *
   * @param $sNewName
   * @return bool
   * @throws Exception
   */
  public function rename($sNewName) {

    if (!rename($this->sDiretory.'/'.$this->sBaseName, $this->sDiretory.'/'.$sNewName)) {
      throw new Exception("Não foi possível renomear o arquivo.");
    }
    $this->sFilePath = "{$this->sDiretory}/{$this->sBaseName}";
    $this->normalizePath();
    return true;
  }

  /**
   * @return File
   */
  public function compress() {
    return File::compressFiles(array($this), $this->getFileName());
  }

  /**
   * Corta o nome do arquivo sem perder a extensão, caso ultrapasse o tamanho máximo.
   *
   * @param  string  $sNomeArquivo   Nome do arquivo
   * @param  integer $iTamanhoMaximo Tamanho máximo para o nome do arquivo
   * @return string
   */
  public static function cutName($sNomeArquivo, $iTamanhoMaximo) {

    if (strlen($sNomeArquivo) > $iTamanhoMaximo) {

      $oArquivo  = new SplFileInfo($sNomeArquivo);
      $sExtensao = $oArquivo->getExtension();
      $iTamanhoExtensao = strlen($sExtensao) + 1;
      $iTamanhoMaximo  -= $iTamanhoExtensao;

      if ($iTamanhoExtensao > $iTamanhoMaximo) {
        throw new Exception('Nome de arquivo com extensão inválida.');
      }

      $sNomeArquivo = substr($sNomeArquivo, 0, $iTamanhoMaximo) . '.' . $sExtensao;
    }

    return $sNomeArquivo;
  }

  /**
   * @param File[] $aFiles
   * @param string $sName
   * @return File $oFile
   * @throws ParameterException
   */
  public static function compressFiles(array $aFiles, $sName = null) {

    foreach ($aFiles as $oFile) {

      if ( !$oFile instanceof File ) {
        throw new ParameterException("Conteúdo do array não é um objeto do tipo File.");
      }
    }
    $sName = trim($sName);
    if (empty($sName)) {
      $sName = "CompressArchive".date('Y-m-d_h:i');
    }
    $sCreateName = "tmp/{$sName}.".File::TYPE_COMPRESS_ZIP;
    if (class_exists('ZipArchive')) {
      $oFile = File::compressByZipArchive($aFiles, $sCreateName);
    } else {
      $oFile = File::compressByShell($aFiles, $sCreateName);
    }
    return $oFile;
  }

  /**
   * Comprime o arquivo utilizando a classe ZipArchive
   * @param File[] $aFiles
   * @param string $sName
   * @return File
   * @throws ParameterException
   */
  private static function compressByZipArchive(array $aFiles, $sName) {

    $oZipArchive  = new ZipArchive();
    $lOpenArchive = $oZipArchive->open($sName, ZipArchive::OVERWRITE);
    if (!$lOpenArchive) {
      throw new ParameterException("Impossível iniciar o arquivo para compactação.");
    }
    foreach ($aFiles as $oFile) {
      $oZipArchive->addFile($oFile->getFilePath(), $oFile->getBaseName());
    }
    $oZipArchive->close();
    return new File($sName);
  }

  /**
   * Comprime o arquivo utilizando comando shell
   * @param File[] $aFiles
   * @param $sName
   * @return File
   * @throws Exception
   */
  private static function compressByShell(array $aFiles, $sName) {

    $aArquivos = array();
    foreach ($aFiles as $oFile) {
      $aArquivos[] = "'".$oFile->getBaseName()."'";
    }

    $sName = basename($sName);
    exec("zip -q '{$sName}' ".implode(" ", $aArquivos) , $aOutput, $iStatus);
    if ($iStatus > 0) {
      throw new Exception("Impossível compactar o arquivo.");
    }
    return new File("tmp/{$sName}");
  }

  /**
   * @param $iPermission
   * @return bool
   */
  public function setPermission($iPermission) {
    return chmod($this->sFilePath, $iPermission);
  }

  /**
   * @param $sGroup
   * @return bool
   */
  public function setGroup($sGroup) {
    return chgrp($this->sFilePath, $sGroup);
  }

  /**
   * @param $sOwner
   * @return bool
   */
  public function setOwner($sOwner) {
    return chown($this->sFilePath, $sOwner);
  }

  /**
   * @return float
   */
  public function getSize() {
    return $this->nSize;
  }

  /**
   * @return string
   */
  public function getBaseName() {
    return $this->sBaseName;
  }

  /**
   * @return string
   */
  public function getDiretory() {
    return $this->sDiretory;
  }

  /**
   * @return string
   */
  public function getExtension() {
    return $this->sExtension;
  }

  /**
   * @return string
   */
  public function getFileName() {
    return $this->sFileName;
  }

  /**
   * @return string
   */
  public function getFilePath() {
    return $this->sFilePath;
  }

  /**
   * @param string $sExtension
   */
  public function setExtension($sExtension) {
    $this->sExtension = $sExtension;
  }
}
