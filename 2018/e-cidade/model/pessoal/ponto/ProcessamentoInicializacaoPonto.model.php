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
class ProcessamentoInicializacaoPonto {

  private $aProcessamentos = array();
  private $diretory        = 'model/pessoal/ponto/processamento';

  /**
   * Processamenos da inicializacao do ponto que devem ser rodados
   * @return InicializacaoPontoInterface[]
   */
  private function getProcessamentos() {

    $oDirectory = new \DirectoryIterator($this->diretory);

    foreach ($oDirectory as $oFile) {

      if ($oFile->getExtension() != 'php') {
        continue;
      }

      $sNomeClasse = str_replace('.model.php', '', $oFile->getFilename());
      if (!$this->validarProcessamento($sNomeClasse)) {
        continue;
      }
      $this->aProcessamentos[] = new $sNomeClasse;
     }
     return $this->aProcessamentos;
  }

  /**
   * Realiza o processamento dos dados na inicialização do ponto
   */
  public function processar(Servidor $oServidor) {

    $aProcessamentosExecutar = $this->getProcessamentos();
    foreach ($aProcessamentosExecutar as $oProcessamento) {
      $oProcessamento->processar($oServidor);
    }
  }

  private function validarProcessamento($sNomeClasse) {

    if (!class_exists($sNomeClasse, true)) {
      return false;
    }
    $oDadosClasse = new ReflectionClass($sNomeClasse);
    if (!$oDadosClasse->implementsInterface('InicializacaoPontoInterface')) {
      return false;
    }
    return true;
  }
}