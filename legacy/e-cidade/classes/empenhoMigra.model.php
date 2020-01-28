<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("classes/empenho.php");
class empenhoMigra extends empenho {

  private $lMigrar = false;
  function __construct($iNumEmp) {

    parent::__construct($iNumEmp);
    $this->numemp = $iNumEmp;

  }

  function getOrdensPagamento() {

    $oDaoPagOrdem  = db_utils::getDao("pagordem"); 
    $sSqlOrdem     = "select e50_codord,";
    $sSqlOrdem    .= "       e50_data,";
    $sSqlOrdem    .= "       e53_valor,";
    $sSqlOrdem    .= "       e53_vlranu,";
    $sSqlOrdem    .= "       e53_vlrpag,";
    $sSqlOrdem    .= "       z01_nome,";
    $sSqlOrdem    .= "       e69_codnota,";
    $sSqlOrdem    .= "       e69_numero,";
    $sSqlOrdem    .= "       coalesce((select 1 ";
    $sSqlOrdem    .= "          from empnotaitem ";
    $sSqlOrdem    .= "         where e72_codnota = e69_codnota limit 1),0) as notatemitem, " ;
    $sSqlOrdem    .= "       coalesce((select 1 ";
    $sSqlOrdem    .= "          from matordemitem left join matordemanu on m53_codordem = m52_codordem ";
    $sSqlOrdem    .= "                                                 and m53_data is null";
    $sSqlOrdem    .= "               inner join matordem                on m51_codordem = m52_codordem ";
    $sSqlOrdem    .= "         where m52_numemp = e50_numemp  and m51_tipo = 1 limit 1),0) as temordem" ;
    $sSqlOrdem    .= "  from pagordem ";
    $sSqlOrdem    .= "       inner join pagordemele  on e53_codord  = e50_codord  ";
    $sSqlOrdem    .= "       inner join empempenho   on e50_numemp  = e60_numemp  ";
    $sSqlOrdem    .= "       inner join cgm          on e60_numcgm  = z01_numcgm  ";
    $sSqlOrdem    .= "       left  join pagordemnota on e71_codord  = e50_codord  ";
    $sSqlOrdem    .= "                              and e71_anulado is false      ";
    $sSqlOrdem    .= "       left  join empnota      on e71_codnota = e69_codnota ";
    $sSqlOrdem    .= " where e50_numemp = {$this->numemp}";
    $sSqlOrdem    .= " order by  e50_codord";
    $rsOrdensPag  = $oDaoPagOrdem->sql_record($sSqlOrdem);
    if ($oDaoPagOrdem->numrows > 0) {
      for ($iOrdens = 0; $iOrdens < $oDaoPagOrdem->numrows; $iOrdens++) {
        $this->aOrdensPagamento[] = db_utils::fieldsMemory($rsOrdensPag, $iOrdens, false, false,$this->getEncode());   
      }  
      return true;
    } else {
      return false;
    }  
    return true;
  }  

  /**
   * Metodo para criar uma nota de liquidacao para uma unica ordem.
   * @param integer $iOrdem Codigo da ordem de pagamento.
   * @param float   $nValortotal Valor total da nota de liquidacao.
   * @param array   $aItens Array associativo com os itens a serem incluidos na nota
   *                [e69_sequencial, nValor, nQtdeItem]                   
   */

  function gerarNotasOrdem($iOrdem, $dtOrdem, $nValortotal, $aItens ) {

    $this->getDados($this->numemp);
    $this->sErroMsg = '';
    if ($iOrdem == '' && $iOrdem == 0 ) {

      throw new exception ("Código da ordem nao informado");
      return false;

    }
    if (!db_utils::inTransaction()) {

      throw new exception ("Nenhuma transação copm o banco foi encontrada.");
      return false;

    }  
    $iAnoUsu           = explode("/", $dtOrdem);
    $oDaoPagOrdem      = db_utils::getDao("pagordem"); 
    $sSqlOrdemComNota  = "select e69_numero,";
    $sSqlOrdemComNota .= "       to_char(e69_dtnota,'dd/mm/YYY') as datanota ";
    $sSqlOrdemComNota .= "  from pagordemnota ";
    $sSqlOrdemComNota .= "       inner join  empnota on e69_codnota = e71_codnota";
    $sSqlOrdemComNota .= " where e71_codord = {$iOrdem} ";
    $sSqlOrdemComNota .= "   and e71_anulado is false";
    $rsOrdemPagamento  = $oDaoPagOrdem->sql_record($sSqlOrdemComNota);
    if ($oDaoPagOrdem->numrows > 0) {

      $oOrdemComNota = db_utils::fieldsMemory($rsOrdemPagamento, 0);
      throw new exception ("Ordem {$iOrdem} já possui a nota {$oOrdemComNota->e69_numero}");
      return false;

    } else {
      
      //Incluimos a nota fiscal na tabela empnota
      $this->gerarOrdemCompra("m{$iOrdem}", $nValortotal, $aItens,false,trim($dtOrdem));
      if ($this->lSqlErro) {
        throw new exception ($this->sMsgErro);
      } else {

        $oDaoPagordemnota              = db_utils::getDao("pagordemnota");
        $oDaoPagordemnota              = new cl_pagordemnota;
        $oDaoPagordemnota->e71_codord  = $iOrdem;
        $oDaoPagordemnota->e71_codnota = $this->iCodNota;
        $oDaoPagordemnota->e71_anulado = 'false';
        $oDaoPagordemnota->incluir($oDaoPagordemnota->e71_codord, $oDaoPagordemnota->e71_codnota);
        if ($oDaoPagordemnota->erro_status == 0) {

          $sErroMsg  = "Pagordemnota:".$oDaoPagordenota->erro_msg;
          throw new exception($sErroMsg);
          return false;

        }
        $sUpdate = "update empnotaele set e70_vlrliq = {$nValortotal} where e70_codnota = {$this->iCodNota}";
        pg_query($sUpdate);
        $sUpdate = "update empnota set e69_anousu = {$iAnoUsu[2]} where e69_codnota = {$this->iCodNota}";
        pg_query($sUpdate);
      }
      if ($this->lMigrar == true) {
        
        $oDaoEmpenhoNL       = db_utils::getDao("empempenhonl");
        $rsEmpempenhoMigrado = $oDaoEmpenhoNL->sql_record($oDaoEmpenhoNL->sql_query_file(null,
                                                          "*",null,
                                                          "e68_numemp = {$this->dadosEmpenho->e60_numemp}")
                                                         );
        if ($oDaoEmpenhoNL->numrows == 0) {

          $oDaoEmpenhoNL->e68_numemp = $this->dadosEmpenho->e60_numemp;
          $oDaoEmpenhoNL->e68_data   = date("Y-m-d",db_getsession("DB_datausu"));
          $oDaoEmpenhoNL->incluir(null);
          if ($oDaoEmpenhoNL->erro_status == 0) {

            $sErroMsg  = "iErro ao marcare empenho como migrado".$oDaoEmpenhoNL->erro_msg;
            throw new exception($sErroMsg);
            return false;
          }
        }
      }
    }

  }  

  function gerarItensNota($iCodNota, $sTipo='', $aItens, $dtOrdem) {
    
    if (!db_utils::inTransaction()) {

      throw new exception ("Nenhuma transação com o banco foi encontrada.");
      return false;

    }  
    if ($iCodNota == '' or $iCodNota == null) {

      throw new exception ("Código da nota nao pode ser vazio");
      return false;

    }
    $this->getDados($this->numemp);
    if ($this->lMigrar == true) {
        
      $oDaoEmpenhoNL       = db_utils::getDao("empempenhonl");
      $rsEmpempenhoMigrado = $oDaoEmpenhoNL->sql_record($oDaoEmpenhoNL->sql_query_file(null,
                                                       "*",null,
                                                       "e68_numemp = {$this->dadosEmpenho->e60_numemp}")
                                                       );
      if ($oDaoEmpenhoNL->numrows == 0) {

        $oDaoEmpenhoNL->e68_numemp = $this->dadosEmpenho->e60_numemp;
        $oDaoEmpenhoNL->e68_data   = date("Y-m-d",db_getsession("DB_datausu"));
        $oDaoEmpenhoNL->incluir(null);
        if ($oDaoEmpenhoNL->erro_status == 0) {

          $sErroMsg  = "iErro ao marcare empenho como migrado".$oDaoEmpenhoNL->erro_msg;
          throw new exception($sErroMsg);
          return false;
        }
      }
    }
    switch ($sTipo) {

     case "comnotasemitem":
       //a ordem de pagamento possui nota fiscal, mas nao possui ordem de compra, nem itens; 
       $oDaoEmpNotaItem = db_utils::getDao("empnotaitem");
       if (is_array($aItens) && count($aItens) > 0) {

         $iTotItens   = count($aItens);
         $nValortotal = 0;
         //incluimos os itens da nota.
         for ($iInd = 0; $iInd < $iTotItens; $iInd++) {
             
            $oDaoEmpNotaItem->e72_codnota    = $iCodNota;
            $oDaoEmpNotaItem->e72_empempitem = $aItens[$iInd]->e62_sequencial;
            $oDaoEmpNotaItem->e72_qtd        = $aItens[$iInd]->quantidade;
            $oDaoEmpNotaItem->e72_valor      = $aItens[$iInd]->vlrtot;
            $oDaoEmpNotaItem->e72_vlrliq     = $aItens[$iInd]->vlrtot;
            $nValortotal                    += $aItens[$iInd]->vlrtot; 
            $oDaoEmpNotaItem->incluir(null);
            if ($oDaoEmpNotaItem->erro_status == 0) {

               $sErroMsg = "Erro[1] - Não foi possível incluir itens da nota.\nErro técnico:{$oDaoEmpNotaItem->erro_msg}";
               throw new exception($sErroMsg);
               return false;
            }  
         }
         //Incluimos a informação da ordem de compra
         $dtDataOrdem  = implode("-",array_reverse(explode("/",trim($dtOrdem)))); 
         $oDaoMatOrdem = db_utils::getDao("matordem");
         $oDaoMatOrdem->m51_data       = $dtDataOrdem;
         $oDaoMatOrdem->m51_depto      = db_getsession("DB_coddepto");
         $oDaoMatOrdem->m51_numcgm     = $this->dadosEmpenho->e60_numcgm;
         $oDaoMatOrdem->m51_valortotal = $nValortotal;
         $oDaoMatOrdem->m51_tipo       = 2; 
         $oDaoMatOrdem->m51_prazoent   = 3;
         $oDaoMatOrdem->m51_obs        = "Gerada pela migracao de empenho/nota";
         $oDaoMatOrdem->incluir(null);
         if ($oDaoMatOrdem->erro_status == 0) {
 
            $sErroMsg  = "Erro[2] - Não foi possível incluir dados da ordem de compra.";
            $sErroMsg .= "\nErro técnico:{$oDaoMatOrdem->erro_msg}";
            throw new exception($sErroMsg);
            return false;

         }  

         $oDaoMatOrdemitem = db_utils::getDao("matordemitem");
         foreach ($aItens as $aItem) {

           $oDaoMatOrdemitem->m52_codordem = $oDaoMatOrdem->m51_codordem;
           $oDaoMatOrdemitem->m52_numemp   = $this->numemp;
           $oDaoMatOrdemitem->m52_sequen   = $aItem->sequen;
           $oDaoMatOrdemitem->m52_quant    = $aItem->quantidade;
           $oDaoMatOrdemitem->m52_valor    = $aItem->vlrtot;
           $oDaoMatOrdemitem->m52_vlruni   = $aItem->vlruni;
           $oDaoMatOrdemitem->incluir(null);
           if ($oDaoMatOrdemitem->erro_status == 0) {

             $sErroMsg  = "Erro[3] - Não foi possível incluir itens da ordem de compra.";
             $sErroMsg .= "\nErro técnico:{$oDaoMatOrdemitem->erro_msg}";
             throw new exception($sErroMsg);
             return false;

           }
         }

         $oDaoEmpNotaOrd = db_utils::getDao("empnotaord");
         $oDaoEmpNotaOrd->m72_codordem = $oDaoMatOrdem->m51_codordem;
         $oDaoEmpNotaOrd->m72_codnota  = $iCodNota;
         $oDaoEmpNotaOrd->incluir($iCodNota, $oDaoMatOrdem->m51_codordem);
         if ($oDaoEmpNotaOrd->erro_status == 0) {
           
             $sErroMsg  = "Erro[4] - Não foi possível incluir dados da ordem de compra.";
             $sErroMsg .= "\nErro técnico:{$oDaoEmpNotaOrd->erro_msg}";
             throw new exception($sErroMsg);
             return false;

         }  

       } else {

         throw new exception ("Itens não informados corretamentes.");
         return false;

       }
       break;
     case "comordemnota":
       //a ordem de pagamento possui nota fiscal,  possui ordem de compra, mas nao nem itens; 
       $oDaoEmpNotaItem = db_utils::getDao("empnotaitem");
       if (is_array($aItens) && count($aItens) > 0) {

         $iTotItens   = count($aItens);
         $nValortotal = 0;
         //incluimos os itens da nota.
         for ($iInd = 0; $iInd < $iTotItens; $iInd++) {
             
            $oDaoEmpNotaItem->e72_codnota    = $iCodNota;
            $oDaoEmpNotaItem->e72_empempitem = $aItens[$iInd]->e62_sequencial;
            $oDaoEmpNotaItem->e72_qtd        = $aItens[$iInd]->quantidade;
            $oDaoEmpNotaItem->e72_valor      = $aItens[$iInd]->vlrtot;
            $oDaoEmpNotaItem->e72_vlrliq     = $aItens[$iInd]->vlrtot;
            $nValortotal                    += $aItens[$iInd]->vlrtot; 
            $oDaoEmpNotaItem->incluir(null);
            if ($oDaoEmpNotaItem->erro_status == 0) {

               $sErroMsg = "Erro[1] - Não foi possível incluir itens da nota.\nErro técnico:{$oDaoEmpNotaItem->erro_msg}";
               throw new exception($sErroMsg);
               return false;
            }  
         }
       } else {

         throw new exception ("Itens não informados corretamentes.");
         return false;

       }
       break;

    }
    return true;
  }  
  function setMigrado($lMigrado) {
    $this->lMigrar = $lMigrado;
  }
}