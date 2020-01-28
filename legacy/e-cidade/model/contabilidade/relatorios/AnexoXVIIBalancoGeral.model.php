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

require_once(modification("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php"));
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use ECidade\Financeiro\Contabilidade\LancamentoContabil\TipoDocumento;

use ECidade\V3\Extension\Registry;

/**
 * classe para controle dos valores do Anexo XVII do balanço Geral
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 *
 */
class AnexoXVIIBalancoGeral extends RelatoriosLegaisBase
{

  /**
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo)
  {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }

  /**
   * retorna os dados da classe em forma de objeto.
   * o objeto de retorno tera a seguinte forma:
   *
   * @return array - Colecao de stdClass
   */
  public function getDados()
  {
     $aLinhas = array();

     /**
      * montamos as datas, e processamos o balancete de verificação
      */
     $oDaoPeriodo      = db_utils::getDao("periodo");
     $sSqlDadosPeriodo = $oDaoPeriodo->sql_query_file($this->iCodigoPeriodo);
     $rsPeriodo        = db_query($sSqlDadosPeriodo);
     $oDadosPerido     = db_utils::fieldsMemory($rsPeriodo, 0);
     $sDataInicial     = "{$this->iAnoUsu}-01-01";
     $iUltimoDiaMes    = cal_days_in_month(CAL_GREGORIAN, $oDadosPerido->o114_mesfinal, $this->iAnoUsu);
     $sDataFinal       = "{$this->iAnoUsu}-{$oDadosPerido->o114_mesfinal}-{$iUltimoDiaMes}";
     $sWherePlano      = " c61_instit in ({$this->getInstituicoes()}) ";
     /**
      * processa o balancete de verificação
      */
     $rsPlano = db_planocontassaldo_matriz($this->iAnoUsu,
                                           $sDataInicial,
                                           $sDataFinal,
                                           false,
                                           $sWherePlano,
                                           '',
                                           'true',
                                           'true');

     $iTotalLinhasPlano = pg_num_rows($rsPlano);
     /**
      * percorremos a slinhas cadastradas no relatorio, e adicionamos os valores cadastrados manualmente.
      */
     $aLinhasRelatorio = $this->oRelatorioLegal->getLinhasCompleto();
     for ($iLinha = 1; $iLinha <= count($aLinhasRelatorio); $iLinha++) {

       $aLinhasRelatorio[$iLinha]->setPeriodo($this->iCodigoPeriodo);
       $aColunasRelatorio  = $aLinhasRelatorio[$iLinha]->getCols($this->iCodigoPeriodo);
       $aColunaslinha      = array();
       $oLinha             = new stdClass();
       $oLinha->totalizar  = $aLinhasRelatorio[$iLinha]->isTotalizador();
       $oLinha->descricao  = $aLinhasRelatorio[$iLinha]->getDescricaoLinha();
       $oLinha->colunas    = $aColunasRelatorio;
       $oLinha->nivellinha = $aLinhasRelatorio[$iLinha]->getNivel();
       foreach ($aColunasRelatorio as $oColuna) {

         $oLinha->{$oColuna->o115_nomecoluna} = 0;
         if ( !$aLinhasRelatorio[$iLinha]->isTotalizador() ) {
           $oColuna->o116_formula = '';
         }
       }

       if (!$aLinhasRelatorio[$iLinha]->isTotalizador()) {

         $aValoresColunasLinhas = $aLinhasRelatorio[$iLinha]->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                                $this->iAnoUsu);

         $aParametros           = $aLinhasRelatorio[$iLinha]->getParametros($this->iAnoUsu, $this->getInstituicoes());
         foreach($aValoresColunasLinhas as $oValor) {
           foreach ($oValor->colunas as $oColuna) {
             $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
           }
         }

         /**
          * verificamos se a a conta cadastrada existe no balancete, e somamos o valor encontrado na linha
          */
         for ($i = 0; $i < $iTotalLinhasPlano; $i++) {

           $oResultado = db_utils::fieldsMemory($rsPlano, $i);


           $oParametro  = $aParametros;

           foreach ($oParametro->contas as $oConta) {

             $oVerificacao    = $aLinhasRelatorio[$iLinha]->match($oConta, $oParametro->orcamento, $oResultado, 3);

             if ($oVerificacao->match) {

              $this->buscarInscricaoEBaixa($oResultado, $iLinha, $sDataInicial, $sDataFinal);

               if ( $oVerificacao->exclusao ) {

                 $oResultado->saldo_anterior         *= -1;
                 $oResultado->saldo_anterior_debito  *= -1;
                 $oResultado->saldo_anterior_credito *= -1;
                 $oResultado->saldo_final            *= -1;
               }

               $oLinha->sd_ex_ant += $oResultado->saldo_anterior;
               $oLinha->inscricao += $oResultado->saldo_anterior_credito;
               $oLinha->baixa     += $oResultado->saldo_anterior_debito;
               $oLinha->sd_ex_seg += $oResultado->saldo_final;
             }
           }
         }
       }
       $aLinhas[$iLinha] = $oLinha;
     }

     unset($aLinhasRelatorio);

     /**
      * calcula os totalizadores do relatório, aplicando as formulas.
      */
     foreach ($aLinhas as $oLinha) {

       if ($oLinha->totalizar) {

         foreach ($oLinha->colunas as $iColuna => $oColuna) {

           if (trim($oColuna->o116_formula) != "") {

             $sFormulaOriginal = ($oColuna->o116_formula);
             $sFormula         = $this->oRelatorioLegal->parseFormula('aLinhas', $sFormulaOriginal, $iColuna, $aLinhas);
             $evaluate         = "\$oLinha->{$oColuna->o115_nomecoluna} = {$sFormula};";
             ob_start();
             eval($evaluate);
             $sRetorno = ob_get_contents();
             ob_clean();
             if (strpos(strtolower($sRetorno), "parse error") > 0 || strpos(strtolower($sRetorno), "undefined" > 0)) {
               $sMsg =  "Linha {$iLinha} com erro no cadastro da formula<br>{$oColuna->o116_formula}";
               throw new Exception($sMsg);

             }
           }
         }
       }
     }

     return $aLinhas;
  }

  /**
   * Busca os valores de INSCRIÇÃO e BAIXA para a coluna MOVIMENTAÇÃO NO EXERCÍCIO
   * @param  \stdClass $oResultado
   * @param  integer $iLinha
   * @param  string $sDataInicial
   * @param  string $sDataFinal
   * @return void
   */
  private function buscarInscricaoEBaixa( $oResultado, $iLinha, $sDataInicial, $sDataFinal )
  {
    if ( $iLinha >= 1 && $iLinha < 12 ) {
      $documentos = $this->documentosCalculoDividasAPagar();
    } elseif ( $iLinha == 13 ) {
      $documentos = $this->documentosCalculoDepositos();
    }

    $todosDocumentos = array_merge(
      $documentos->somaInscricao,
      $documentos->subtracaoInscricao,
      $documentos->somaBaixa,
      $documentos->subtracaoBaixa
    );

    $estrutural = new Estrutural( $oResultado->estrutural );

    $query  = 'select ';
    $query .=   $this->campos( $documentos->somaInscricao, $documentos->subtracaoInscricao ) . ' as valor_inscricao, ';
    $query .=   $this->campos( $documentos->somaBaixa, $documentos->subtracaoBaixa ) . ' as valor_baixa ';
    $query .= 'from conplano ';
    $query .= 'inner join conplanoreduz on conplanoreduz.c61_codcon = conplano.c60_codcon ';
    $query .= ' and conplanoreduz.c61_anousu = conplano.c60_anousu ';
    $query .= 'inner join conlancamval on conlancamval.c69_credito = conplanoreduz.c61_reduz ';
    $query .= ' or conlancamval.c69_debito = conplanoreduz.c61_reduz ';
    $query .= 'inner join conlancam on conlancam.c70_codlan = conlancamval.c69_codlan ';
    $query .= 'inner join conlancamdoc on conlancamdoc.c71_codlan = conlancamval.c69_codlan ';
    $query .= 'inner join conhistdoc on conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc ';
    $query .= 'where conplanoreduz.c61_anousu = ' . $this->iAnoUsu;
    $query .= ' and conplano.c60_anousu = ' . $this->iAnoUsu;
    $query .= " and conlancamval.c69_data between '" . $sDataInicial . "' and '" . $sDataFinal . "' ";
    $query .= ' and c61_instit in (' . $this->getInstituicoes() . ') ';
    $query .= " and conplano.c60_estrut ilike '" . $estrutural->getEstruturalAteNivel() . "%'";
    $query .= ' and conhistdoc.c53_tipo in (' . implode( ',', $todosDocumentos ) . ')';

    $rs = db_query( $query );

    if ( !$rs ) {
      throw new Exception( 'Erro ao buscar valores da inscrição e da baixa.' );
    }

    $valores = db_utils::fieldsMemory( $rs, 0 );

    $oResultado->saldo_anterior_credito = $valores->valor_inscricao;
    $oResultado->saldo_anterior_debito = $valores->valor_baixa;

    $logger = Registry::get('app.container')->get('app.logger');
    $logger->debug('Linha: ' . $iLinha . ' --- Query: ' . $query);
    $logger->debug('Linha: ' . $iLinha . ' --- Valores -> Inscrição: ' . $valores->valor_inscricao . ' | Baixa: ' . $valores->valor_baixa);
  }

  /**
   * Retorna classe contendo documentos referentes ao cálculo da movimentação no exercício da inscrição e da baixa
   * para os grupos RESTOS A PAGAR e SERVIÇO DA DÍVIDA A PAGAR
   * @return \stdClass
   */
  private function documentosCalculoDividasAPagar()
  {
    $documentos = new stdClass;
    $documentos->somaInscricao = array( TipoDocumento::LIQUIDACAO );
    $documentos->subtracaoInscricao = array( TipoDocumento::ESTORNO_LIQUIDACAO );
    $documentos->somaBaixa = array( TipoDocumento::PAGAMENTO );
    $documentos->subtracaoBaixa = array( TipoDocumento::ESTORNO );

    return $documentos;
  }

  /**
   * Retorna classe contendo documentos referentes ao cálculo da movimentação no exercício da inscrição e da baixa
   * para o grupo DEPÓSITOS
   * @return \stdClass
   */
  private function documentosCalculoDepositos()
  {
    $documentos = new stdClass;
    $documentos->somaInscricao = array(
      TipoDocumento::RECEBIMENTO_CAUCAO,
      TipoDocumento::DEPOSITOS_DIVERSOS_RECEBIMENTO
    );
    $documentos->subtracaoInscricao = array(
      TipoDocumento::RECEBIMENTO_CAUCAO_ESTORNO,
      TipoDocumento::DEPOSITOS_DIVERSOS_ESTORNO_RECEBIMENTO
    );
    $documentos->somaBaixa = array(
      TipoDocumento::DEVOLUCAO_CAUCAO,
      TipoDocumento::DEPOSITOS_DIVERSOS_PAGAMENTO
    );
    $documentos->subtracaoBaixa = array(
      TipoDocumento::DEVOLUCAO_CAUCAO_ESTORNO,
      TipoDocumento::DEPOSITOS_DIVERSOS_ESTORNO_PAGAMENTO
    );

    return $documentos;
  }

  /**
   * Cria cases para consulta dos valores da dívida
   * @param  array  $soma      Documentos referentes a soma
   * @param  array  $subtracao Documentos referentes a subtração
   * @return string            Query contendo o case
   */
  private function campos( $soma = array(), $subtracao = array() )
  {
    $campos  = 'round(';
    $campos .= '  sum(';
    $campos .= '    case';
    $campos .= '      when c53_tipo in (' . implode( ',', $soma ) . ')';
    $campos .= '        then c70_valor';
    $campos .= '      when c53_tipo in (' . implode( ',', $subtracao ) . ')';
    $campos .= '        then c70_valor * -1 ';
    $campos .= '    end';
    $campos .= '  )';
    $campos .= ', 2)';

    return $campos;
  }
}