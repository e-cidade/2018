<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Prove dados para a geração do arquivo dos Empenhos que possuiram movimentacao no periodo 
 * do municipio para o SIGAP
 * @package Pad
 * @author  Iuri Guncthnigg
 * @version $Revision: 1.8 $
 */
final class PadArquivoSigapEmpenho extends PadArquivoSigap {
  
  /**
   * 
   */
  public function __construct() {
    
    $this->sNomeArquivo = "Empenho";
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
    list($iAno, $iMes, $iDia) = explode("-",$this->sDataFinal);
    $sListaInstit = db_getsession("DB_instit");
    /**
     * Aqui temos a lista de empenhos do exercicio., separado por documento contábil
     *  
     *  1  - ref. a empenho
     *  2  - ref. a anulacao de empenho
     *  31 - ref. a anulacao de RP
     *  32 - ref. a anulacao de RP
     */ 
    $sSqlEmpenho  = " select e60_numemp, ";
    $sSqlEmpenho .= "        e60_anousu, ";
    $sSqlEmpenho .= "        trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlEmpenho .= "        o58_coddot, ";
    $sSqlEmpenho .= "        o58_orgao, ";
    $sSqlEmpenho .= "        o58_unidade, ";
    $sSqlEmpenho .= "        o58_funcao, ";
    $sSqlEmpenho .= "        o58_subfuncao, ";
    $sSqlEmpenho .= "        o58_programa, ";
    $sSqlEmpenho .= "        o58_projativ,  ";
    $sSqlEmpenho .= "        case when o58_anousu >= 2005 then ";
    $sSqlEmpenho .= "             substr(trim(substr(o56_elemento,2,14))||'00000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        else ";
    $sSqlEmpenho .= "            substr(trim(o56_elemento)||'000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        end as rubrica, ";
    $sSqlEmpenho .= "        o15_codtri as recurso, ";
    $sSqlEmpenho .= "        (case when c53_tipo in(11) then c70_data else e60_emiss end) as e60_emiss, ";
    $sSqlEmpenho .= "        c70_valor as valor_empenho, ";
    $sSqlEmpenho .= "        (case when c71_coddoc = 1 then '+' else '-' end)::char(1) as sinal, ";  
    $sSqlEmpenho .= "        z01_cgccpf, ";
    $sSqlEmpenho .= "        ('DOT:['||e60_coddot||'] '|| 'NUMEMP:['||e60_numemp||']'||e60_resumo) as e60_resumo, ";
    $sSqlEmpenho .= "        e60_instit, ";
    $sSqlEmpenho .= "        e60_concarpeculiar, ";
    $sSqlEmpenho .= "        e60_codtipo ";
    $sSqlEmpenho .= "   from empempenho  ";
    $sSqlEmpenho .= "        inner join conlancamemp on c75_numemp = e60_numemp ";
    $sSqlEmpenho .= "        inner join conlancamdoc on c71_codlan = c75_codlan ";
    $sSqlEmpenho .= "        inner join conhistdoc   on c71_coddoc = c53_coddoc ";
    $sSqlEmpenho .= "        inner join conlancam    on c70_codlan = c75_codlan ";
    $sSqlEmpenho .= "        inner join cgm          on z01_numcgm = e60_numcgm ";
    $sSqlEmpenho .= "        inner join orcdotacao   on o58_coddot = e60_coddot ";
    $sSqlEmpenho .= "                               and o58_anousu = e60_anousu and o58_instit = e60_instit ";
    $sSqlEmpenho .= "        inner join orcelemento  on o56_codele = o58_codele and o56_anousu = o58_anousu ";
    $sSqlEmpenho .= "        inner join orctiporec   on o58_codigo = o15_codigo";
    $sSqlEmpenho .= "  where c75_data >= '{$this->sDataInicial}' "; 
    $sSqlEmpenho .= "    and c75_data <='{$this->sDataFinal}' ";  
    $sSqlEmpenho .= "    and e60_emiss <='{$this->sDataFinal}'";
    $sSqlEmpenho .= "    and c71_coddoc in (1,2,31,32) ";
    $sSqlEmpenho .= "    and e60_instit in ({$sListaInstit}) ";
          
   /**
    * Empenhos RP:
    */
    $sSqlEmpenho .= " union all ";
    $sSqlEmpenho .= " select distinct (e91_numemp) , ";
    $sSqlEmpenho .= "        e60_anousu, ";
    $sSqlEmpenho .= "        trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlEmpenho .= "        o58_coddot, ";
    $sSqlEmpenho .= "        o58_orgao, ";
    $sSqlEmpenho .= "        o58_unidade, ";
    $sSqlEmpenho .= "        o58_funcao, ";
    $sSqlEmpenho .= "        o58_subfuncao, ";
    $sSqlEmpenho .= "        o58_programa, ";
    $sSqlEmpenho .= "        o58_projativ, ";
    $sSqlEmpenho .= "        case when o58_anousu >= 2005 then ";
    $sSqlEmpenho .= "             substr(trim(substr(o56_elemento,2,14))||'00000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        else ";
    $sSqlEmpenho .= "             substr(trim(o56_elemento)||'000000000',1,150)::varchar(15) ";
    $sSqlEmpenho .= "        end as rubrica, ";
    $sSqlEmpenho .= "        o15_codtri as recurso, ";
    $sSqlEmpenho .= "        e60_emiss, ";
    //$sSqlEmpenho .= "        round((e91_vlremp-e91_vlranu-e91_vlrpag),2)::float8 as valor_empenho, ";
    $sSqlEmpenho .= "        round(e91_vlremp,2)::float8 as valor_empenho, ";
    $sSqlEmpenho .= "        '+'::char(1) as sinal, ";  
    $sSqlEmpenho .= "        z01_cgccpf, ";
    $sSqlEmpenho .= "        ('DOT:['||e60_coddot||'] '|| 'NUMEMP:['||e60_numemp||']'||e60_resumo) as e60_resumo, ";
    $sSqlEmpenho .= "        e60_instit, ";
    $sSqlEmpenho .= "        e60_concarpeculiar, ";
    $sSqlEmpenho .= "        e60_codtipo ";
    $sSqlEmpenho .= "   from empresto ";
    $sSqlEmpenho .= "        inner join empempenho on e60_numemp = e91_numemp         ";
    $sSqlEmpenho .= "        inner join cgm          on z01_numcgm = e60_numcgm ";
    $sSqlEmpenho .= "        inner join orcdotacao on o58_coddot=e60_coddot and  o58_anousu=e60_anousu and o58_instit = e60_instit ";
    $sSqlEmpenho .= "        inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu ";
    $sSqlEmpenho .= "        inner join orctiporec   on o58_codigo = o15_codigo";
    $sSqlEmpenho .= "  where e91_anousu = ".db_getsession("DB_anousu"); 
    $sSqlEmpenho .= "    and e60_instit in  ({$sListaInstit}) ";
    $sSqlEmpenho .= "    and e91_rpcorreto is false ";
                    

    $sSqlEmpenho .= "  union all ";
    $sSqlEmpenho .= " select e60_numemp, ";
    $sSqlEmpenho .= "        e60_anousu, ";
    $sSqlEmpenho .= "        trim(e60_codemp)::integer as e60_codemp, ";
    $sSqlEmpenho .= "        o58_coddot, ";
    $sSqlEmpenho .= "        o58_orgao, ";
    $sSqlEmpenho .= "        o58_unidade, ";
    $sSqlEmpenho .= "        o58_funcao, ";
    $sSqlEmpenho .= "        o58_subfuncao, ";
    $sSqlEmpenho .= "        o58_programa, ";
    $sSqlEmpenho .= "        o58_projativ,  ";
    $sSqlEmpenho .= "        case when o58_anousu >= 2005 then ";
    $sSqlEmpenho .= "           substr(trim(substr(o56_elemento,2,14))||'00000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        else ";
    $sSqlEmpenho .= "           substr(trim(o56_elemento)||'000000000',1,15)::varchar(15) ";
    $sSqlEmpenho .= "        end as rubrica, ";
    $sSqlEmpenho .= "        o15_codtri as recurso, ";
    $sSqlEmpenho .= "        (case when c53_tipo in(11) then c70_data else e60_emiss end) as e60_emiss, ";
    $sSqlEmpenho .= "        c70_valor as valor_empenho, ";
    $sSqlEmpenho .= "        (case when c71_coddoc = 1 then '+' else '-' end)::char(1) as sinal, ";  
    $sSqlEmpenho .= "        z01_cgccpf, ";
    $sSqlEmpenho .= "        ('DOT:['||e60_coddot||'] '|| 'NUMEMP:['||e60_numemp||']'||e60_resumo) as e60_resumo, ";
    $sSqlEmpenho .= "        e60_instit, ";
    $sSqlEmpenho .= "        e60_concarpeculiar, ";
    $sSqlEmpenho .= "        e60_codtipo ";
    $sSqlEmpenho .= "   from empresto";
    $sSqlEmpenho .= "        inner join empempenho   on e91_numemp = e60_numemp";
    $sSqlEmpenho .= "        inner join conlancamemp on c75_numemp = e60_numemp";
    $sSqlEmpenho .= "        inner join conlancamdoc on c71_codlan = c75_codlan";
    $sSqlEmpenho .= "        inner join conhistdoc   on c71_coddoc = c53_coddoc";
    $sSqlEmpenho .= "        inner join conlancam on c70_codlan = c75_codlan";
    $sSqlEmpenho .= "        inner join cgm          on z01_numcgm = e60_numcgm ";
    $sSqlEmpenho .= "        inner join orcdotacao on o58_coddot=e60_coddot and o58_anousu=e60_anousu and o58_instit = e60_instit";
    $sSqlEmpenho .= "        inner join orcelemento on o56_codele = o58_codele and o56_anousu = o58_anousu";
    $sSqlEmpenho .= "        inner join orctiporec   on o58_codigo = o15_codigo";
    $sSqlEmpenho .= "  where e91_anousu = ".db_getsession("DB_anousu");
    $sSqlEmpenho .= "    and c75_data <= '{$this->sDataFinal}'";
    $sSqlEmpenho .= "    and c71_coddoc in (1,2,32) ";
    $sSqlEmpenho .= "    and e91_rpcorreto is true";
    $sSqlEmpenho .= "    and e60_instit in ({$sListaInstit})";
    $sSqlEmpenho .= "  order by o58_orgao,";
    $sSqlEmpenho .= "        o58_unidade,";
    $sSqlEmpenho .= "        o58_funcao,";
    $sSqlEmpenho .= "        o58_subfuncao,";
    $sSqlEmpenho .= "        o58_programa,";
    $sSqlEmpenho .= "        o58_projativ,"; 
    $sSqlEmpenho .= "        rubrica,";
    $sSqlEmpenho .= "        e60_emiss ";
    $rsEmpenho    = db_query($sSqlEmpenho);
    $iTotalLinhas = pg_num_rows($rsEmpenho);
     
    for ($i = 0; $i < $iTotalLinhas; $i++) {
         	
      $oEmpenho              = db_utils::fieldsMemory($rsEmpenho, $i);
      
      $oEmpenhoRetorno                                = new stdClass();
      //$oEmpenhoRetorno->empCodigoEntidade             = str_pad(db_getsession("DB_instit"), 4, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoEntidade             = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
      $sDiaMesAno                                     = "{$iAno}-".str_pad($iMes, 2, "0", STR_PAD_LEFT)."-".str_pad($iDia, 2, "0", STR_PAD_LEFT);      
      $oEmpenhoRetorno->empMesAnoMovimento            = $sDiaMesAno;
      $oEmpenhoRetorno->empCodigoOrgao                = str_pad($oEmpenho->o58_orgao,    2, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoUnidadeOrcamentaria  = str_pad($oEmpenho->o58_unidade,   2, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoFuncao               = str_pad($oEmpenho->o58_funcao,    2, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoFuncao               = str_pad($oEmpenho->o58_funcao,    3, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoSubFuncao            = str_pad($oEmpenho->o58_subfuncao, 3, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoPrograma             = str_pad($oEmpenho->o58_programa,  4, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoProjetoAtividade     = str_pad($oEmpenho->o58_projativ,  5, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoRubricaDespesa       = str_pad($oEmpenho->rubrica,      15, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empCodigoRecursoVinculado     = str_pad($oEmpenho->recurso,       6, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empContrapartida              = str_pad("0" ,                     4, "0", STR_PAD_LEFT);
      $sNumeroEmpenho                                 = str_pad($oEmpenho->e60_codemp, 5, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empNumero                     = $oEmpenho->e60_anousu.$sNumeroEmpenho;
      $oEmpenhoRetorno->empData                       = $oEmpenho->e60_emiss;
      $oEmpenhoRetorno->empValor                      = number_format($oEmpenho->valor_empenho,2,".","");
      $oEmpenhoRetorno->empSinalValor                 = $oEmpenho->sinal;
      $iTamanhoPad                                    = strlen($oEmpenho->z01_cgccpf); 
      $oEmpenhoRetorno->empCnpjCpf                    = str_pad($oEmpenho->z01_cgccpf, $iTamanhoPad, 0, STR_PAD_LEFT);
      $oEmpenhoRetorno->empHistorico                  = str_replace("\n", "", substr($oEmpenho->e60_resumo, 0, 255));
      $oEmpenhoRetorno->empProcesso                   = $oEmpenho->e60_numemp . "/" . $oEmpenho->e60_anousu;
      
      switch ($oEmpenho->e60_codtipo) {
          
        case '1':
            
          $sCodTipo = '3';
          break;
          
        case '2':
            
          $sCodTipo = '1';
          break;
        
        case '3':
            
          $sCodTipo = '2';
          break;
      }
      
     	$oEmpenhoRetorno->empTipo                      = str_pad($sCodTipo, 2, "0", STR_PAD_LEFT);
      $oEmpenhoRetorno->empNumeroConvenio            = str_pad("0", 15, "0", STR_PAD_LEFT);
     	$oEmpenhoRetorno->empNumeroEdital              = '';
      $oEmpenhoRetorno->empModalLicitacao            = '';  
      $sSqlTipoLicitacao     = " select distinct l20_edital,                                                                                     ";
      $sSqlTipoLicitacao    .= "        l44_codigotribunal                                                                                       ";
      $sSqlTipoLicitacao    .= "   from empautitem                                                                                               ";
      $sSqlTipoLicitacao    .= "        inner join empautitempcprocitem on empautitempcprocitem.e73_sequen = empautitem.e55_sequen               "; 
      $sSqlTipoLicitacao    .= "                                       and empautitempcprocitem.e73_autori = empautitem.e55_autori               ";
      $sSqlTipoLicitacao    .= "        inner join liclicitem           on liclicitem.l21_codpcprocitem    = empautitempcprocitem.e73_pcprocitem "; 
      $sSqlTipoLicitacao    .= "        inner join liclicita            on liclicitem.l21_codliclicita     = liclicita.l20_codigo                ";
      $sSqlTipoLicitacao    .= "        inner join cflicita             on liclicita.l20_codtipocom        = cflicita.l03_codigo                 ";
      $sSqlTipoLicitacao    .= "        inner join pctipocompra         on cflicita.l03_codcom             = pc50_codcom                         ";
      $sSqlTipoLicitacao    .= "        inner join pctipocompratribunal on l03_pctipocompratribunal        = l44_sequencial                      ";
      $sSqlTipoLicitacao    .= "        inner join empautoriza          on empautoriza.e54_autori          = empautitem.e55_autori               "; 
      $sSqlTipoLicitacao    .= "        inner join empempaut            on e61_autori                      = e54_autori                          ";
      $sSqlTipoLicitacao    .= "  where e61_numemp = {$oEmpenho->e60_numemp}                                                                     ";
      $rsSqlTipoLicitacao    = db_query($sSqlTipoLicitacao);
      $iNumRowsTipoLicitacao = pg_num_rows($rsSqlTipoLicitacao);
      if ($iNumRowsTipoLicitacao > 0) {
        
        $oTipoLicitacao                             = db_utils::fieldsMemory($rsSqlTipoLicitacao, 0);
        $oEmpenhoRetorno->empNumeroEdital           = str_pad($oTipoLicitacao->l20_edital, 20, "0", STR_PAD_LEFT);
        $oEmpenhoRetorno->empModalLicitacao         = str_pad($oTipoLicitacao->l44_codigotribunal, 2, "0", STR_PAD_LEFT);
      } else {
        
        $sSqlTipoCompra     = " select distinct l44_codigotribunal                                                   ";
        $sSqlTipoCompra    .= "   from empempenho                                                                    ";
        $sSqlTipoCompra    .= "        inner join pctipocompra         on e60_codcom                = pc50_codcom    "; 
        $sSqlTipoCompra    .= "        inner join pctipocompratribunal on pc50_pctipocompratribunal = l44_sequencial "; 
        $sSqlTipoCompra    .= "  where e60_numemp = {$oEmpenho->e60_numemp}                                          ";
        $rsSqlTipoCompra    = db_query($sSqlTipoCompra);
        $iNumRowsTipoCompra = pg_num_rows($rsSqlTipoCompra);
        if ($iNumRowsTipoCompra > 0) {
        	
        	$oTipoCompra                             = db_utils::fieldsMemory($rsSqlTipoCompra, 0);
          $oEmpenhoRetorno->empNumeroEdital        = str_pad("0", 20, "0", STR_PAD_LEFT);
          $oEmpenhoRetorno->empModalLicitacao      = str_pad($oTipoCompra->l44_codigotribunal, 2, "0", STR_PAD_LEFT);
        }
      }
     	
      array_push($this->aDados, $oEmpenhoRetorno);
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
                         "empCodigoEntidade",
                         "empMesAnoMovimento",
                         "empCodigoOrgao",
                         "empCodigoUnidadeOrcamentaria",
                         "empCodigoFuncao",
                         "empCodigoSubFuncao",
                         "empCodigoPrograma",
                         "empCodigoProjetoAtividade",
                         "empCodigoRubricaDespesa",
                         "empCodigoRecursoVinculado",
                         "empContrapartida",
                         "empNumero",
                         "empData",
                         "empValor",
                         "empSinalValor",
                         "empCnpjCpf",
                         "empHistorico",
                         "empProcesso",
                         "empNumeroEdital",
                         "empModalLicitacao",
                         "empNumeroConvenio",
                         "empTipo"
                       );
                       
    return $aElementos;  
  }
}
?>