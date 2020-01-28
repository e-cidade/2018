<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ('model/PadArquivoSigap.model.php');
/**
 * Prove dados para a geração do arquivo das rubricas para o SIGAP
 * @package Pad
 * @author Iuri Guncthnigg
 * @version $Revision: 1.5 $
 */
final class PadArquivoSigapContaDisponibilidade extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "ContaDisponibilidade";
    $this->aDados       = array();
  }
  
  /**
   * Gera os dados para utilizacao posterior. Metodo geralmente usado 
   * em conjuto com a classe PadArquivoEscritorXML
   * @return true;
   */
  public function gerarDados() {
    
    if (empty($this->sDataInicial)) {
      throw new Exception("Data inicial nao informada!");
    }
    
    if (empty($this->sDataFinal)) {
      throw new Exception("Data final não informada!");
    }
    /**
     * Separamos a data do em ano, mes, dia
     */
    
    $sWhereInstit = db_getsession("DB_instit");
    $sWhere       = " c61_instit in ($sWhereInstit)";
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    $this->sDataInicial = "$iAno-$iMes-01";
    $oInstituicao = db_stdClass::getDadosInstit(db_getsession("DB_instit"));
    $rsBalancete  = @db_planocontassaldo_matriz(db_getsession("DB_anousu"),
                                            $this->sDataInicial,
                                            $this->sDataFinal,
                                            false,$sWhere,
                                            '',false,'false');
    $iTotalLinhas = pg_num_rows($rsBalancete);                                            
    for ($i = 0; $i < $iTotalLinhas; $i++) {

     $oBalancete = db_utils::fieldsMemory($rsBalancete, $i);
      if (round($oBalancete->saldo_anterior, 2) + round($oBalancete->saldo_anterior_debito, 2)
           + round($oBalancete->saldo_anterior_credito,2) == 0) {
        continue;
      }
      $sSqlConta  = "select c60_estrut as c60_estrut, "; 
      $sSqlConta .= "       c61_instit, ";
      $sSqlConta .= "       o15_codtri as c61_codigo, ";
      $sSqlConta .= "       case when c63_banco is null then '00' ";
      $sSqlConta .= "            else c63_banco end as c63_banco, ";
      $sSqlConta .= "       case when c63_agencia is null then '000' ";
      $sSqlConta .= "            else c63_agencia end as c63_agencia, ";
      $sSqlConta .= "       case when c63_conta is null then '0000' ";
      $sSqlConta .= "           else c63_conta end as c63_conta, ";
      $sSqlConta .= "       c60_codcla  ";
      $sSqlConta .= "  from conplano  ";
      $sSqlConta .= "       inner join conplanoconta on c63_codcon = c60_codcon and c63_anousu=c60_anousu ";
      $sSqlConta .= "       inner join conplanoreduz on c61_codcon = c60_codcon and c60_anousu=c61_anousu ";  
      $sSqlConta .= "       inner join orctiporec    on c61_codigo = o15_codigo";  
      $sSqlConta .= "  where c61_instit in ($sWhereInstit)";
      $sSqlConta .= "    and c60_codcon = {$oBalancete->c61_codcon}";
      $sSqlConta .= "    and ( c60_estrut like '1111%' or c60_estrut like '115%') "; 
      $sSqlConta .= "    and c60_anousu =".db_getsession("DB_anousu");
      $sSqlConta .= "order by c60_estrut,c61_instit ";  
      $rsContas   = db_query($sSqlConta);
      if (pg_num_rows($rsContas) > 0 ) { 
        
        $sDiaMesAno        =  "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);
        $oConta            = db_utils::fieldsMemory($rsContas, 0);
        $sSqlTipoTribunal  = "select db21_idtribunal";
        $sSqlTipoTribunal .= "  from db_config ";
        $sSqlTipoTribunal .= "       inner join db_tipoinstit on db21_codtipo=db21_tipoinstit "; 
        $sSqlTipoTribunal .= " where codigo = {$oConta->c61_instit} ";
        $rsTipoTribunal    = db_query($sSqlTipoTribunal);      
        if (pg_num_rows($rsTipoTribunal) == 0) {
          throw new Exception("Parametro db21_idtribunal não configurado na tabela db_config->db_tipoinstit");
        }
        $iTipoTribunal  = db_utils::fieldsMemory($rsTipoTribunal, 0)->db21_idtribunal;
        $iClassificacao = 0;
        switch ($iTipoTribunal) {
  
          case 01 :
            
            $iClassificacao = 1;
            break;
          case 02 :
            
            $iClassificacao = 2;
            break;  
          case 05 :
            
            $iClassificacao = 3;
            break;
          case 06: 
  
            $iClassificacao = 3;
            break;
          default:
            
            $iClassificacao = 9;
            break;   
        }
        if ($oConta->c61_codigo == 50) {
          $iClassificacao = 3;
        }
        $oContaRetorno                     = new stdClass();
        $oContaRetorno->disCodigoEntidade  = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
        $oContaRetorno->disMesAnoMovimento = $sDiaMesAno;
        $oContaRetorno->disCodigoContaBalanceteVerificacao = str_pad($oConta->c60_estrut, 20, "0", STR_PAD_RIGHT);
  
        $iTamanhoCampo = strlen($oInstituicao->codtrib);
        if ($iTamanhoCampo != 4) {
          
          $sMsg  = "Identificação do Orgão/Unidade da instituição ({$oInstituicao->codtrib}) está incorreto. \\n ";
          $sMsg .= "Solicitar para o setor responsável pela configuração do sistema a alteração da informação no Menu: \\n \\n "; 
          $sMsg .= "Configuração -> Cadastros -> Instituições -> Alteração. ";
          
          throw new Exception($sMsg);
        }
        
        $sOrgao                                            = substr($oInstituicao->codtrib, 0, 2);
        $sUnidade                                          = substr($oInstituicao->codtrib, 2, 2); 
        $oContaRetorno->disCodigoOrgao                     = str_pad($sOrgao, 2, "0", STR_PAD_LEFT);
        $oContaRetorno->disCodigoUnidadeOrcamentaria       = str_pad($sUnidade, 2, "0", STR_PAD_LEFT);    
        $oContaRetorno->disCodigoRecursoVinculado          = str_pad($oConta->c61_codigo, 6, "0", STR_PAD_LEFT);
        $oContaRetorno->disCodigoBanco                     = str_pad(trim($oConta->c63_banco), 5, "0", STR_PAD_LEFT);
        $oContaRetorno->disCodigoAgenciaBanco              = str_pad(trim($oConta->c63_agencia), 5, "0", STR_PAD_LEFT);
        $oContaRetorno->disNumeroContaCorrente             = str_pad(trim(str_replace('-','',
                                                                     str_replace('.','',trim($oConta->c63_conta)))),
                                                                      5, "0", STR_PAD_LEFT
                                                                     );
        if (substr($oConta->c60_estrut,0,5) == '11111') {
          $iTipoConta =  '1'; // caixa
        } else if (substr($oConta->c60_estrut,0,5) == '11112') {
          $iTipoConta =  '2'; // banco conta movimento
        } else if (substr($oConta->c60_estrut,0,5) == '11113') {
          $iTipoConta =  '3'; // banco conta aplicacao
        } else if (substr($oConta->c60_estrut,0,11) == '11251020001' ||
                  substr($oConta->c60_estrut,0,11) == '11251020002'  ||
                  substr($oConta->c60_estrut,0,11) == '11251020003') {
          $iTipoConta =  '4'; // deposito sentencas judiciais
        } else if (substr($oConta->c60_estrut,0,11) == '11251020004' || 
                   substr($oConta->c60_estrut,0,11) == '11251020005' ||
                   substr($oConta->c60_estrut,0,11) == '11251020006') {
          $iTipoConta =  '5'; // depositos sentencas judiciais rp
        } else {
          $iTipoConta =  '2'; // depositos sentencas judiciais rp
        }
        $oContaRetorno->disTipoConta          = $iTipoConta;
        $oContaRetorno->disClassificacaoConta = $iClassificacao; 
                                                                           
        array_push($this->aDados, $oContaRetorno);
      }
    }
    
    return true;
  }
  
  /**
   * Publica quais elementos/Campos estão disponiveis para 
   * o uso no momento da geração do arquivo
   *
   * @return array com elementos disponibilizados para a geração dos arquivo
   */
  public function getNomeElementos() {
    
    $aElementos = array(
                        "disCodigoEntidade",
                        "disMesAnoMovimento",
                        "disCodigoContaBalanceteVerificacao",
                        "disCodigoRecursoVinculado",
                        "disCodigoOrgao",
                        "disCodigoUnidadeOrcamentaria",
                        "disCodigoBanco",
                        "disCodigoAgenciaBanco",
                        "disNumeroContaCorrente",
                        "disTipoConta",
                        "disClassificacaoConta"
                       );
                       
    return $aElementos;  
  }
}
?>