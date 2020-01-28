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
 * 
 * classe para controle da movimentação de reativação
 * 
 * @author rafael.lopes rafael.lopes@dbseller.com.br
 *
 */  
class AcordoMovimentacaoReativacao extends AcordoMovimentacao {
    
  
  /**
   * constante com o caminho de mensagens JSON
   * @var unknown
   */
  const CAMIONHO_MENSAGEM = 'patrimonial.contratos.AcordoMovimentacaoReativacao.';
  
  /**
   * Tipo da movimentacao na inclusão da Situacao
   * @var int
   */
  protected $iTipo = 18;
  
  /**
   * Tipo da movimentacao no cancelamento da movimentação
   * @var int
   */
  protected $iCodigoCancelamento = 19;
  
  
  /**
   * Método construtor
   * Instancia os dados da movimentção passada com parâmetro
   *
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    parent::__construct($iCodigo);
  }
  
  
  /**
   * Persiste os dados da Acordo Movimentacao na base de dados
   *
   * @throws Exception
   * @return AcordoMovimentacaoParalisacao
   */
  public function save() {
  
    parent::save();
    $oDaoAcordoMovimentacao = new cl_acordomovimentacao;
    $oDaoAcordo             = new cl_acordo;
 
    /**
     * Acerta movimentacao corrente para alterar um movimento anterior
     */
    $sCampos                    = "ac10_sequencial, ac10_acordomovimentacaotipo, ";
    $sCampos                   .= "ac10_acordo, ac09_acordosituacao              ";
    $sWhere                     = "ac10_sequencial = {$this->iCodigo}            ";
    $sOrderBy                   = "ac10_sequencial desc limit 1                  ";
    $sSqlAcordoMovimentacao     = $oDaoAcordoMovimentacao->sql_query_acertaracordo(null, $sCampos, $sOrderBy, $sWhere);
    
    $rsSqlAcordoMovimentacao    = $oDaoAcordoMovimentacao->sql_record($sSqlAcordoMovimentacao);
    $iNumRowsAcordoMovimentacao = $oDaoAcordoMovimentacao->numrows;
    if ($iNumRowsAcordoMovimentacao > 0) {
    
      /**
       * Altera situacao do movimento
       */
      $oAcordoMovimentacao             = db_utils::fieldsMemory($rsSqlAcordoMovimentacao, 0);
      $oDaoAcordo->ac16_sequencial     = $oAcordoMovimentacao->ac10_acordo;
      $oDaoAcordo->ac16_acordosituacao = $oAcordoMovimentacao->ac09_acordosituacao;
      $oDaoAcordo->alterar($oDaoAcordo->ac16_sequencial);
      if ($oDaoAcordo->erro_status == 0) {
        throw new Exception($oDaoAcordo->erro_msg);
      }
    }
    return $this;
  }
  
  
  
  /**
   * Cancela o movimento
   *
   * @return AcordoMovimentacaoParalisacao
   */
  public function cancelar() {
    parent::cancelar();
    return $this;
  }

  
 
}



