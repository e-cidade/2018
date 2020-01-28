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
class RegraLicitaconAdesaoPrecoOutroOrgao extends RegraLicitacon {

  protected $sMensagem = "Os campos da Adesão à Ata de Registro de Preço são de preenchimento obrigatório.";

  protected $aModalidades = array('RPO');

  /**
   * @return array
   */
  protected function getModalidades() {
    return $this->aModalidades;
  }

  public function regra() {

    if (in_array($this->oLicitacao->getModalidade()->getSiglaTipoCompraTribunal(), $this->getModalidades())) {

      if(!empty($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_CNPJ_ORGAO_GERENCIADOR])
             && !empty($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_ORGAO_GERENCIADOR])
             && !empty($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_NUMERO_LICITACAO])
             && !empty($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_ANO_LICITACAO])
             && !empty($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_NUMERO_ATA_REGISTRO_PRECO])
             && !empty($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_DATA_ATA])
             && !empty($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_DATA_AUTORIZACAO])
             && !empty($this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_TIPO_ATUACAO])){

        $sCnpj = $this->aAtributosDinamicos[LicitacaoAtributosDinamicos::NOME_CNPJ_ORGAO_GERENCIADOR];
        
        if (!DBString::isCNPJ($sCnpj)) {
          $this->sMensagem = "CNPJ inválido.";
          return false;
        }
      } else {
        return false;
      }
    }
    return true;
  }
}