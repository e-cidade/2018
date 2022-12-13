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

abstract class ArquivoSiprevEscritor {

  protected $sFile;

  protected $sOutPut;

  protected $aListaArquivos = array();

  /**
   *
   */
  function __construct() {

  }

  /**
   * Retorna o Caminho do arquivo criado
   *
   */
  public function criarArquivo(ArquivoSiprevBase $oArquivo) {


  }

  function adicionarArquivo($sCaminho, $sNomeArquivo) {

    if (is_array($sCaminho) ) {

      foreach ($sCaminho as $indice => $caminho) {

        $iNumeroArquivo = $indice + 1;
        $this->adicionarArquivo($caminho, $sNomeArquivo . ' - Parte ' . $iNumeroArquivo);
      }
      return;
    }


    $oArquivo               = new stdClass();
    $oArquivo->nome         = $sNomeArquivo;
    $oArquivo->caminho      = $sCaminho;
    $this->aListaArquivos[] = $oArquivo;
  }

  public function getListaArquivos() {
    return $this->aListaArquivos;
  }

  public function zip ($sArquivo) {

    $lTemArquivo    = false;
    $aListaArquivos = '';

    foreach ($this->aListaArquivos as $oArquivo) {

      $sArquivo = file_get_contents($oArquivo->caminho);
      if (empty($sArquivo)) {
        continue;
      }

      $lTemArquivo     = true;
      $aListaArquivos .= " " . str_replace("tmp/","", $oArquivo->caminho);
    }

    if(!$lTemArquivo) {
      return '';
    }

    $sArquivo = 'SIPREV';

    $oRetornoSystem1 = system("rm -f tmp/{$sArquivo}.zip");
    $oRetornoSystem2 = system("cd tmp && ../bin/zip -q9 {$sArquivo}.zip {$aListaArquivos}");

    return "{$sArquivo}.zip";
  }
}