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
 * Classe de refatoração dos ajutes dos descontos consignados
 * 
 * @abstract
 * @package Pessoal
 * @subpackage Calculo Financeiro 
 * @author Rafael Serpa Nery <rafal.nery@dbseller.com.br>
 * @author Marcos Andrade <marcos.andrade@dbseller.com.br>
 */
abstract class DescontoConsignado {

  /**
   * Executa o desconto consignado respeitando o valor de 70% do salário do servidor
   * Respeitando a ordem cadastrada.
   *
   * @static
   * @access public
   * @param  $iTipoFolha - Tipo da folha que esta sendo calculada.
   */
  static public function processar( $iTipoFolha, $iMatriculaServidor, $sLotacao ) {

    /**
     * @TODO Remover as globais
     */
    global $tot_prov, $tot_desc, $anousu, $mesusu, $DB_instit;

    LogCalculoFolha::write();
    LogCalculoFolha::write("Processando Ajuste nos Descontos Conforme Margem Consignavel");
    LogCalculoFolha::write();
    LogCalculoFolha::write("Total de Proventos: {$tot_prov}");
    LogCalculoFolha::write("Total de Descontos: {$tot_desc}");

    if( !db_empty($tot_prov) || !db_empty($tot_desc) ) {

      /**
       * Buscar as Rubricas cadastradas como rubricas de consignação.
       */
      $aRubricasConsignadas = DescontoConsignado::getCodigosRubricasConsignadas();
      $oServidor            = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $anousu, $mesusu, $DB_instit); 
      $oCalculoSalario      = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
      $aEventosConsignados  = array();
      
      LogCalculoFolha::write("Rubricas consignadas Encontradas");
      LogCalculoFolha::write(implode(", ", $aRubricasConsignadas));

      /**
       * Percorre as rubricas configuradas, verificando tem valor lançado no cálculo 
       */
      foreach ( $aRubricasConsignadas as $sRubricaConsignada ) {

        $aEventos = $oCalculoSalario->getEventosFinanceiros(null, $sRubricaConsignada);
        
        /**
         * Caso não exista Evento financeiro, não adiciona a rubrica ao cálculo.
         */
        if ( empty($aEventos) ) {
          continue;
        }

        $aEventosConsignados[$sRubricaConsignada] = $aEventos[0];
        LogCalculoFolha::write("Rubrica {$sRubricaConsignada} COM evento");
      }

      if (!empty($aEventosConsignados)) {


        $sBaseAbatimento         = DescontoConsignado::getCodigoBaseAbatimento($anousu, $mesusu, $DB_instit);
        if ( !$sBaseAbatimento ) {
          return false;
        }
        $sFormulaBase            = le_var_bxxx($sBaseAbatimento, "pontofs", "gerfsal","r10","r14",0);
        LogCalculoFolha::write("Formula da Base: {$sFormulaBase}");
        eval("\$nValorBase = $sFormulaBase;" );
        $nValorProventosAbatidos = $tot_prov - $nValorBase;
        $nValorLiquidoFolha      = round($nValorProventosAbatidos - $tot_desc,2);
        $nSaldoMinimo            = round($nValorProventosAbatidos * 0.3, 2);
           
        LogCalculoFolha::write("Total de Proventos..: {$tot_prov}");
        LogCalculoFolha::write("Total de Descontos..: {$tot_desc}");
        LogCalculoFolha::write("Valor da Base.......: {$nValorBase}");
        LogCalculoFolha::write("Valor Liquido Folha.: {$nValorLiquidoFolha}");
        LogCalculoFolha::write("Saldo Minimo........: {$nSaldoMinimo}");

        if( $nValorLiquidoFolha <= $nSaldoMinimo  ) {

          $oDaoFolhaSalario              = new cl_gerfsal();
          $sSqlRubricaInsuficienciaSaldo = $oDaoFolhaSalario->sql_query_file( 
            $anousu, 
            $mesusu,
            $iMatriculaServidor,
            "R928",
            "1"
          );
          $rsRubricaInsuficienciaSaldo  = db_query($sSqlRubricaInsuficienciaSaldo);
           
          if ( !$rsRubricaInsuficienciaSaldo ) {
            throw new DBException("Erro ao pesquisar Rubrica de Insuficiencia de Saldo");
          }

          if ( pg_num_rows($rsRubricaInsuficienciaSaldo ) > 0) {

            LogCalculoFolha::write("Removendo rubrica R928 do salario");
            $oDaoFolhaSalario->excluir(
              $anousu,
              $mesusu,
              $iMatriculaServidor,
              "R928"
            );
          }

          /**
           * A ordem de Descontos das rubricas é inversa (de menor prioridade para maior)
           * pois quando o calculo é efetuado as rubricas são adicionadas a folha
           * de salario do servidor e assim enquanto existe margem para efetuar
           * a dedução, estas são deduzidas do calculo na competencia até que
           * a margem se esgote.
           */
          $nTotalDescontado    = $tot_desc;
          $aEventosConsignados = array_reverse( $aEventosConsignados );

          foreach ($aEventosConsignados as $sRubrica => $oEventoConsignado) {

            if( $oEventoConsignado->getValor() == 0 ) {
              continue;    
            }

            LogCalculoFolha::write();
            LogCalculoFolha::write("Operando a Rubrica({$sRubrica}).");
            
            LogCalculoFolha::write("DescontadoAntes:{$nTotalDescontado}.");
            $nTotalDescontado    -= $oEventoConsignado->getValor();
            $nSaldo               = $nValorProventosAbatidos - $nTotalDescontado;
            
            $sWhere  = " and r14_regist = ".db_sqlformat($iMatriculaServidor);
            $sWhere .= " and r14_rubric = ".db_sqlformat($sRubrica);
            LogCalculoFolha::write("ValorDesconto  :{$oEventoConsignado->getValor()}");
            LogCalculoFolha::write("-----------------------------------");
            LogCalculoFolha::write("DescontadoAtual:{$nTotalDescontado}.");
            LogCalculoFolha::write("Saldo Atual($nValorProventosAbatidos - $nTotalDescontado)   :{$nSaldo}.");
            if ( $nSaldo > $nSaldoMinimo) {

              $tot_desc   -= ($oEventoConsignado->getValor() - ($nSaldo - $nSaldoMinimo));
              $aChaves[1]  = "r14_valor";
              $aValores[1] = ($nSaldo - $nSaldoMinimo);
              db_update( "gerfsal", $aChaves, $aValores, bb_condicaosubpes("r14_").$sWhere );
              LogCalculoFolha::write("Mudou o Valor do Desconto para: {$tot_desc}");
              break;

            } elseif ( $nSaldo <= $nSaldoMinimo || $nSaldo <= 0 ) {
              
              $oDaoFolhaSalario->excluir(
                $anousu,
                $mesusu,
                $iMatriculaServidor,
                $sRubrica
              );
              db_delete("gerfsal",bb_condicaosubpes("r14_").$sWhere);

              $tot_desc -= $oEventoConsignado->getValor();
              LogCalculoFolha::write("Removeu rubrica por falta de saldo");
            }
          }
        }
      }
    }

    LogCalculoFolha::write("Recalculando insuficiência de Saldo(R928).");
    calcula_r928($iMatriculaServidor, $sLotacao, $iTipoFolha);
  }

  /**
   * Retorna os código das Rubricas 
   * 
   * @static
   * @access private
   * @return array
   */
  private static  function getCodigosRubricasConsignadas(){

    /**
     * Rubricas a serem devolvidas
     */
    $aRubricasConsignadas    = array();

    $oDaoRubricasConsignadas = new cl_rubricadescontoconsignado();
    $sSqlRubricasConsignadas = $oDaoRubricasConsignadas->sql_query_file(null, "rh140_rubric", 'rh140_ordem', "rh140_instit = " . db_getsession('DB_instit'));
    $rsRubricasConsignadas   = db_query($sSqlRubricasConsignadas);

    if (!$rsRubricasConsignadas){
      throw new DBException("Erro ao buscar as rubricas.");
    }

    for ( $iRubrica = 0; $iRubrica < pg_num_rows($rsRubricasConsignadas); $iRubrica++){
      $aRubricasConsignadas[] = db_utils::fieldsMemory($rsRubricasConsignadas, $iRubrica)->rh140_rubric;
    }

    return $aRubricasConsignadas;
  }

  /**
   * Retorna o Código da Base de Abatimento do Total de Proventos      
   *
   * @param  integer $iAno
   * @param  integer $iMes
   * @param  integer $iInstituicao
   * @static
   * @access private
   * @return String
   */
  private static function getCodigoBaseAbatimento($iAno, $iMes, $iInstituicao) {


    $oDaoCfPess = new cl_cfpess();
    $sSqlCfPess = $oDaoCfPess->sql_query_parametro($iAno, $iMes, $iInstituicao, "r11_baseconsignada");
     
    $rsCfPess   = db_query($sSqlCfPess);


    if ( !$rsCfPess ) {
      throw new DBException("Erro ao buscar as configurações do Sistema");
    }

    if ( pg_num_rows($rsCfPess) == 0 ) {
      return false;
    }

    $sCodigo = db_utils::fieldsMemory($rsCfPess, 0);

    return $sCodigo->r11_baseconsignada;  
  }

}
