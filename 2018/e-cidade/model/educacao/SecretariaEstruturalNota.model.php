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

use \ECidade\Educacao\Secretaria\EstruturalNotaValidacao;

/**
 * Representa a estrura da Configuração da Nota
 * @todo Existe a configuração na Escola e Secretaria, por enquanto só foi implementado SecretariaEstruturalNota
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 *
 */
class SecretariaEstruturalNota extends EstruturalNota {

  public function __construct ( $iCodigo = null ) {

    if ( empty( $iCodigo ) )  {
      return $this;
    }

    $sCampos  = " ed139_sequencial, ed139_db_estrutura, ed139_ativo, ed139_arredondamedia, ed139_regraarredondamento, ";
    $sCampos .= " ed139_observacao, ed139_ano, ed316_descricao";

    $oDao = new cl_avaliacaoestruturanotapadrao();
    $sSql = $oDao->sql_query($iCodigo, $sCampos);
    $rs   = db_query($sSql);
    if ( !$rs ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA ."erro_buscar_configuracao") );
    }

    if ( pg_num_rows($rs) == 1 ) {

      $oDados = db_utils::fieldsMemory($rs, 0);
      $this->iCodigo         = $oDados->ed139_sequencial;
      $this->oDBEstrutura    = new DBEstrutura($oDados->ed139_db_estrutura);
      $this->lAtivo          = $oDados->ed139_ativo          == 't';
      $this->lArredondaMedia = $oDados->ed139_arredondamedia == 't';
      $this->sObservacao     = $oDados->ed139_observacao;
      $this->iAno            = $oDados->ed139_ano;

      if ( !empty($oDados->ed139_regraarredondamento) ) {
       $this->oRegraArredondamento = new RegraArredondamentoVO($oDados->ed139_regraarredondamento, $oDados->ed316_descricao);
      }
    }
  }

  private function validacoes($oParametro) {

    if ( empty($oParametro->iEstrutural) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "informe_estrutural_nota") );
    }

    if ( empty($oParametro->iAno) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "informe_ano") );
    }

    if ( empty($oParametro->iCodigo) && !EstruturalNotaValidacao::permiteInclusaoEstruturaNotaSecretaria($oParametro->iAno) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "ja_existe_configuracao_para_ano", $oParametro) );
    } else if ( !empty($oParametro->iCodigo) && !EstruturalNotaValidacao::permiteAlteracaoEstruturaNotaSecretaria($oParametro->iCodigo, $oParametro->iAno, $oParametro->lAtivo) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "ja_existe_configuracao_ativa_para_ano", $oParametros) );
    }
    return true;
  }

  public function salvar($oParametro) {

    $this->validacoes($oParametro);

    $oDao = new cl_avaliacaoestruturanotapadrao();

    $oDao->ed139_sequencial          = null;
    $oDao->ed139_db_estrutura        = $oParametro->iEstrutural;
    $oDao->ed139_ativo               = $oParametro->lAtivo          ? 't' : 'false';
    $oDao->ed139_arredondamedia      = $oParametro->lArredondaMedia ? 't' : 'false';
    $oDao->ed139_regraarredondamento = empty($oParametro->iRegraArredondamento) ? "null" : $oParametro->iRegraArredondamento;
    $oDao->ed139_observacao          = trim($oParametro->sObservacao);
    $oDao->ed139_ano                 = $oParametro->iAno;

    if (empty($oParametro->iCodigo) ) {
      $oDao->incluir(null);
    } else {

      $oDao->ed139_sequencial = $oParametro->iCodigo;
      $oDao->alterar($oParametro->iCodigo);
    }

    if ( $oDao->erro_status == 0 ) {
      throw new Exception(_M(self::ESTRUTURAL_NOTA . "erro_salvar_configuracao_nota"));
    }

    $this->iCodigo                = $oDao->ed139_sequencial;
    $this->oDBEstrutura           = new DBEstrutura($oParametro->iEstrutural);
    $this->lAtivo                 = $oParametro->lAtivo;
    $this->lArredondaMedia        = $oParametro->lArredondaMedia;
    $this->sObservacao            = $oParametro->sObservacao;
    $this->iAno                   = $oParametro->iAno;

    if ( !empty($oParametro->iRegraArredondamento) ) {
     $this->oRegraArredondamento = new RegraArredondamentoVO($oParametro->iRegraArredondamento, $oParametro->sRegraArredondamento);
    }

    return true;
  }

  public function excluir($iCodigo) {

    if ( empty($iCodigo) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "informe_configuracao") );
    }
    $oDao = new cl_avaliacaoestruturanotapadrao();
    $oDao->excluir($iCodigo);

    if ( $oDao->erro_status == 0 ) {
      throw new Exception(_M(self::ESTRUTURAL_NOTA . "erro_excluir_configuracao_nota"));
    }
  }

}