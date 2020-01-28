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
 * Representa a estrura da Configuração da Nota na Escola
 *
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 */
class EscolaEstruturalNota extends EstruturalNota {

  protected $oEscola = null;

  function __construct( $iCodigo = null ) {

    if ( empty( $iCodigo ) )  {
      return $this;
    }

    $sCampos  = " ed315_sequencial, ed315_escola, ed315_db_estrutura, ed315_ativo, ed315_arredondamedia,";
    $sCampos .= "ed315_observacao, ed315_ano, ed318_regraarredondamento, ed316_descricao";

    $oDao = new \cl_avaliacaoestruturanota();
    $sSql = $oDao->sql_query_configuracao_escola($iCodigo, $sCampos);
    $rs   = db_query($sSql);

    if ( !$rs ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA ."erro_buscar_configuracao") );
    }

    if ( pg_num_rows($rs) == 1 ) {

      $oDados = db_utils::fieldsMemory($rs, 0);
      $this->iCodigo         = $oDados->ed315_sequencial;
      $this->oDBEstrutura    = new DBEstrutura($oDados->ed315_db_estrutura);
      $this->lAtivo          = $oDados->ed315_ativo          == 't';
      $this->lArredondaMedia = $oDados->ed315_arredondamedia == 't';
      $this->sObservacao     = $oDados->ed315_observacao;
      $this->iAno            = $oDados->ed315_ano;
      $this->oEscola         = EscolaRepository::getEscolaByCodigo($oDados->ed315_escola);

      if ( !empty($oDados->ed318_regraarredondamento) ) {
       $this->oRegraArredondamento = new RegraArredondamentoVO($oDados->ed318_regraarredondamento, $oDados->ed316_descricao);
      }
    }
  }

  /**
   * Getter escola
   * @param Escola
   */
  public function getEscola () {
    return $this->oEscola;
  }


  private function validacoes($oParametro) {

    if ( empty($oParametro->iEstrutural) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "informe_estrutural_nota") );
    }

    if ( empty($oParametro->iAno) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "informe_ano") );
    }

    if ( empty($oParametro->iCodigo) && !EstruturalNotaValidacao::permiteInclusaoEstruturaNotaEscola($oParametro->iEscola, $oParametro->iAno) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "ja_existe_configuracao_para_ano", $oParametro) );
    } else if ( !empty($oParametro->iCodigo) && !EstruturalNotaValidacao::permiteAlteracaoEstruturaNotaEscola($oParametro->iCodigo, $oParametro->iAno, $oParametro->lAtivo) ) {
      throw new Exception( _M(self::ESTRUTURAL_NOTA . "ja_existe_configuracao_ativa_para_ano", $oParametros) );
    }

    return true;
  }


  public function salvar($oParametro) {

    $this->validacoes($oParametro);

    $oDaoEstrutura = new cl_avaliacaoestruturanota();
    $oDaoEstrutura->ed315_sequencial     = $oParametro->iCodigo;
    $oDaoEstrutura->ed315_escola         = $oParametro->iEscola;
    $oDaoEstrutura->ed315_db_estrutura   = $oParametro->iEstrutural;
    $oDaoEstrutura->ed315_ativo          = $oParametro->lAtivo          ? 't' : 'false';
    $oDaoEstrutura->ed315_arredondamedia = $oParametro->lArredondaMedia ? 't' : 'false';
    $oDaoEstrutura->ed315_observacao     = trim($oParametro->sObservacao);
    $oDaoEstrutura->ed315_ano            = $oParametro->iAno;

    if (empty($oParametro->iCodigo) ) {
      $oDaoEstrutura->incluir(null);
    } else {

      $oDaoEstrutura->ed139_sequencial = $oParametro->iCodigo;
      $oDaoEstrutura->alterar($oParametro->iCodigo);
    }

    if ( $oDaoEstrutura->erro_status == 0 ) {
      throw new Exception(_M(self::ESTRUTURAL_NOTA . "erro_salvar_configuracao_nota"));
    }

    $oDaoRegra = new cl_avaliacaoestruturaregra();
    $oDaoRegra->excluir(null, " ed318_avaliacaoestruturanota = {$oDaoEstrutura->ed315_sequencial} ");
    if ($oDaoRegra->erro_status == 0) {
      throw new Exception(_M(self::ESTRUTURAL_NOTA . "erro_atualizar_regra"));
    }

    if ( !empty($oParametro->iRegraArredondamento) ) {

      $oDaoRegra->ed318_sequencial             = null;
      $oDaoRegra->ed318_avaliacaoestruturanota = $oDaoEstrutura->ed315_sequencial;
      $oDaoRegra->ed318_regraarredondamento    = $oParametro->iRegraArredondamento;
      $oDaoRegra->incluir(null);
      if ($oDaoRegra->erro_status == 0) {
        throw new Exception(_M(self::ESTRUTURAL_NOTA . "erro_atualizar_regra"));
      }
    }

    $this->iCodigo                = $oDaoEstrutura->ed315_sequencial;
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


}
