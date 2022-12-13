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
 * Classe para arredondamento de notas
 * @package Educacao
 * @author iuri@dbseller.com
 * @version $Revision: 1.22 $
 */
require_once(modification('model/educacao/IEducacaoArredondamento.interface.php'));
class ArredondamentoNota implements IEducacaoArredondamento {

  /**
   * Estatica
   */
  protected static $sInstance;

  /**
   * Regra de Arredondamento
   * @var EducacaoArredondamento
   */
  protected $oEducacaoArredondamento;

  /**
   * Metodo construtor
   * verifica qual regra de Arredondamento está sendo utilizada.
   */
  protected function __construct() {

    $this->oEducacaoArredondamento = new EducacaoArredondamento();
    $this->iCasasDecimais          = 0;

    $oDaoAvaliacaoArredondamento   = new cl_avaliacaoestruturanota();
    $sWhere                        = " ed315_escola = ".$_SESSION["DB_coddepto"];
    $sWhere                       .= " and ed315_ativo is true";
    $sCampos                       = "ed315_arredondamedia, ed315_ano, db77_estrut, ed318_regraarredondamento";
    $sCampos                      .= ", ed316_casasdecimaisarredondamento";
    $sSqlRegraArredondamento       = $oDaoAvaliacaoArredondamento->sql_query_configuracao_escola(null,
                                                                                               $sCampos,
                                                                                               null,
                                                                                               $sWhere
                                                                                              );

    $rsRegraArredondamento       = $oDaoAvaliacaoArredondamento->sql_record($sSqlRegraArredondamento);
    if ($oDaoAvaliacaoArredondamento->numrows > 0) {

      $iTotalRegras = $oDaoAvaliacaoArredondamento->numrows;
      for ($iRegra = 0; $iRegra < $iTotalRegras; $iRegra++) {

        $oDadosAvaliacao   = db_utils::fieldsMemory($rsRegraArredondamento, $iRegra);

        $oDadosRegra                               = new stdClass();
        $oDadosRegra->sMascara                     = $oDadosAvaliacao->db77_estrut;
        $oDadosRegra->lArredondar                  = $oDadosAvaliacao->ed315_arredondamedia == 't'?true:false;
        $oDadosRegra->iCasasDecimais               = 0;
        $oDadosRegra->iCasasDecimaisArredondamento = $oDadosAvaliacao->ed316_casasdecimaisarredondamento;
        $oDadosRegra->aRegras                      = array();
        $aPartesMascara                            = explode(".", $oDadosAvaliacao->db77_estrut);

        if (isset($aPartesMascara[1])) {
          $oDadosRegra->iCasasDecimais = strlen($aPartesMascara[1]);
        }

        if (count($aPartesMascara) == 2 && $oDadosAvaliacao->ed318_regraarredondamento != "") {
          $oDadosRegra->aRegra = $this->retornarFaixasRegraArrendodamento( $oDadosAvaliacao->ed318_regraarredondamento );
        }
        $this->oEducacaoArredondamento->adicionarRegras($oDadosAvaliacao->ed315_ano, $oDadosRegra);
      }
    }
  }

  /**
   * Retorna as faixas de uma regra de arredondamento
   * @param  integer $iCodigoRegra código da regra
   * @return array
   */
  protected function retornarFaixasRegraArrendodamento( $iCodigoRegra ) {

    $aRegras    = array();
    $sWhere     = "ed317_regraarredondamento = {$iCodigoRegra}";
    $oDaoRegra  = new cl_regraarredondamentofaixa();
    $sSqlFaixas = $oDaoRegra->sql_query_file(null, "*", null, $sWhere);
    $rsFaixas   = $oDaoRegra->sql_record($sSqlFaixas);

    $aFaixasArredontamento = db_utils::getCollectionByRecord($rsFaixas);
    foreach ($aFaixasArredontamento as $oFaixa) {

      $oRegra                 = new StdClass();
      $oRegra->inicio         = $oFaixa->ed317_inicial;
      $oRegra->fim            = $oFaixa->ed317_final;
      $oRegra->arrendondar    = $oFaixa->ed317_arredondar;
      $aRegras[] = $oRegra;
    }

    return $aRegras;
  }

  /**
   * método para retorna a instancia da classe.
   * @return ArredondamentoNota
   */
  protected function getInstance() {

    if (static::$sInstance == null) {
      static::$sInstance = new static();
    }
    return static::$sInstance;
  }

  /**
   * Retorna a instancia de EducacaoArredondamento
   * @return EducacaoArredondamento
   */
  protected function getArredondamento() {
    return static::getInstance()->oEducacaoArredondamento;
  }

  /**
   * Realiza o arredondamento da nota, conforme as regras ativas.
   * caso nao exista nenhuma regra ativa ou a nota seja um numero inteiro,
   * apenas retorna a nota;
   * @param float $nNota valor da nota
   * @param int   $iAno ano da configuracao
   * @return float
   */
  public static function arredondar($nNota, $iAno) {
    return static::getArredondamento()->arredondar($nNota, $iAno);
  }

  /**
   * Retorna as faixas de arredondamento ativo da escola;
   * @param int   $iAno ano da configuracao
   * @return integer;
   */
  public static function getFaixasDeArredondamento($iAno) {
    return static::getArredondamento()->getFaixasDeArredondamento($iAno);
  }

  /**
   * Retorna as faixas de arredondamento ativo da escola;
   * @return integer;
   */
  public static function getMascara($iAno) {
    return static::getArredondamento()->getMascara($iAno);
  }

  /**
   * Retorna o numero de casas decimais que a regra da nota utiliza.
   * @param  integer $iAno Ano da configuracao
   * @return integer;
   */
  public static function getNumeroCasasDecimais($iAno) {
    return static::getArredondamento()->getNumeroCasasDecimais($iAno);
  }

  /**
   * Verifica se a configuração permite o arredondamento da nota
   * @param int $iAno ano da configuracao
   * @return boolean
   */
  public static function arredondaValor($iAno) {
    return static::getArredondamento()->arredondaValor($iAno);
  }

  /**
   * Formata o numero conforme mascara
   * @param float $nNota nota a ser formata
   * @param integer $iAno ano para buscar as regras de formatacao
   * @return string retorna a nota formatada
   */
  public static function formatar($nNota, $iAno) {
    return static::getArredondamento()->formatar($nNota, $iAno);
  }
  /**
   * Marcamos o clone como privado para evitar de existir
   * dois instancias da classe.
   */
  protected function __clone(){}

  public function destroy() {
    static::$sInstance = null;
  }
}
?>