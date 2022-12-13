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
 * Model para retornar os dados da empresa para sistemas webservice
 * @author Everton Catto Heckler <everton.heckler@dbseller.com.br>
 */
class DadosEmpresaWebService extends Empresa {

  /**
   * Foto principal
   * @param string
   */
  public function getFotoPrincipal() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação ativa");
    }

    $sCaminhoArquivo = 'tmp/foto_cgm_'.$this->oCgmEmpresa->getCodigo();
    if (file_exists($sCaminhoArquivo)) {
      unlink($sCaminhoArquivo);
    }

    $iFoto = $this->oCgmEmpresa->getFotoPrincipal();

    /**
     * adicionada validacao caso nao exista Oid
     */
    if(!empty($iFoto)){
      DBLargeObject::leitura($iFoto, $sCaminhoArquivo);
    }

    /**
     * Valida se o arquivo existe
     */
    if (!file_exists($sCaminhoArquivo)) {
      return false;
    }

    return base64_encode(file_get_contents($sCaminhoArquivo));
  }

  /**
   * Adiciona uma nova foto principal ao contribuinte
   * @param string $sDadosArquivo
   * @return bool|string
   */
  public function setFotoPrincipal($sDadosArquivo) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Sem transação ativa");
    }

    try {

      $iFoto = $this->oCgmEmpresa->getIdFotoPrincipal();
      if (!empty($iFoto)) {
        $this->oCgmEmpresa->excluirFoto($iFoto);
      }

      $sNomeArquivo = 'tmp/foto_cgm_ts_'.$this->oCgmEmpresa->getCodigo();

      fopen($sNomeArquivo, "wb+");
      file_put_contents($sNomeArquivo, base64_decode($sDadosArquivo));
      fclose($sNomeArquivo);

      $this->oCgmEmpresa->adicionarFoto($sNomeArquivo);

    } catch (Exception $oErro) {
      return $oErro->getMessage();
    }

    return true;
  }
}