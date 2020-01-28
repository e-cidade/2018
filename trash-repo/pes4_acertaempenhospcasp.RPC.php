<?
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


   if ($oPost->sSigla == 'r20' && $oPost->iTipo == 1) {
     $xxWhere = " and rh73_seqpes in ( {$oPost->sRescisoes} )";
   }else{
     $xxWhere = " and rh72_seqcompl = ".($sSemestre+0);
   }
    $sql = "select * from rhempenhofolha 
                          inner join rhempenhofolharhemprubrica on rh72_sequencial = rh81_rhempenhofolha 
                          inner join rhempenhofolharubrica on rh73_sequencial = rh81_rhempenhofolharubrica 
                     where rh72_anousu   = {$oPost->iAnoFolha}
                       and rh72_mesusu   = {$oPost->iMesFolha}
                       and rh72_siglaarq = '{$oPost->sSigla}'
                     {$xxWhere}
           ";
// echo $sql;exit;
    $result = db_query($sql) or die($sql);
    
//    db_criatabela($result);

    $quantidade = pg_numrows($result);
    for($yy = 0; $yy < $quantidade ; $yy++){ 
      db_fieldsmemory($result, $yy);

      // verifica tabela de previdencia se oficial ou não
      $sql = "select r33_tipo 
              from rhpessoalmov
                   inner join inssirf on r33_anousu = rh02_anousu 
                                     and r33_mesusu = rh02_mesusu 
                                     and r33_codtab = rh02_tbprev+2 
                                     and r33_instit = rh02_instit 
              where rh02_seqpes = $rh73_seqpes limit 1";
      $result1 = db_query($sql) or die($sql);
      db_fieldsmemory($result1, 0);

      if ( $r33_tipo == "O" ){
 
         // executar a verificação para ver se é necessário trocar o codigo do elemento
         $sSql = $oDaoEmpenhoElementoPCASP->sql_query( null,
                                                       'nov.rh38_codele as elementonovo',
                                                       null,
                                                       "def.rh38_codele = {$rh72_codele}" );
         
         $result1 = db_query($sSql) or die($sSql);
         
         if ( pg_numrows($result1) == 0 ){
           continue;
         }

         db_fieldsmemory($result1, 0);

         // se troca codigo do elemento, verificar se não existe a mesma linha de informação no rhempenhofolha

         $sql = "select rh72_sequencial from rhempenhofolha 
                     where rh72_anousu         = $rh72_anousu         
                       and rh72_mesusu         = $rh72_mesusu
                       and rh72_orgao          = $rh72_orgao          
                       and rh72_unidade        = $rh72_unidade        
                       and rh72_funcao         ".($rh72_funcao==''?' is null':" = ".$rh72_funcao)."         
                       and rh72_subfuncao      ".($rh72_subfuncao==''?' is null':" = ".$rh72_subfuncao)."      
                       and rh72_programa       ".($rh72_programa==''?' is null':" = ".$rh72_programa)."
                       and rh72_recurso        = $rh72_recurso        
                       and rh72_concarpeculiar = '$rh72_concarpeculiar' 
                       and rh72_tabprev        = $rh72_tabprev        
                       and rh72_siglaarq       = '$rh72_siglaarq'
                       and rh72_seqcompl       = $rh72_seqcompl
                       and rh72_codele         = $elementonovo
                       and rh72_coddot         = $rh72_coddot
                       and rh72_projativ       = $rh72_projativ
                       and rh72_tipoempenho    = $rh72_tipoempenho
           ";

         $result1 = db_query($sql) or die($sql);
         $quant = pg_numrows($result1);
         if($quant>0){

           // se existe, pegar codigo do sequencial para troca no rhempenhofolharhemprubrica
           db_fieldsmemory($result1, 0);
         
         }else{


            // se napo existe, criar nova linha e pegar codigo para troca no rhempenhofolharhemprubrica
            $sql = "select nextval('rhempenhofolha_rh72_sequencial_seq') as rh72_sequencial ";
            $result1 = db_query($sql) or die($sql);
            db_fieldsmemory($result1, 0);

            $sql = "insert into rhempenhofolha 
                       (rh72_sequencial,
                        rh72_anousu,
                        rh72_mesusu,         
                        rh72_orgao,                
                        rh72_unidade,              
                        rh72_funcao,               
                        rh72_subfuncao,            
                        rh72_programa,             
                        rh72_recurso,              
                        rh72_concarpeculiar,  
                        rh72_tabprev,             
                        rh72_siglaarq,       
                        rh72_seqcompl,       
                        rh72_codele ,
                        rh72_coddot  ,      
                        rh72_projativ ,    
                        rh72_tipoempenho 

                       )
                       
                       values (
                       
                       $rh72_sequencial,
                       $rh72_anousu,         
                       $rh72_mesusu,
                       $rh72_orgao,          
                       $rh72_unidade,        
                       ".($rh72_funcao==''?'null':$rh72_funcao).",    
                       ".($rh72_subfuncao==''?'null':$rh72_subfuncao).",         
                       ".($rh72_programa==''?'null':$rh72_programa)." ,      
                       $rh72_recurso,
                       
                       '$rh72_concarpeculiar', 
                       $rh72_tabprev,        
                       '$rh72_siglaarq',
                       $rh72_seqcompl,
                       $elementonovo ,
                       $rh72_coddot,
                       $rh72_projativ,
                       $rh72_tipoempenho

                       )
                  ";

            $result1 = db_query($sql) or die($sql);

         }
      
         // trocar no rhempenhofolharhemprubrica
         $sql = " update rhempenhofolharhemprubrica 
                  set rh81_rhempenhofolha = $rh72_sequencial
                  where rh81_sequencial = $rh81_sequencial";
         
         $result1 = db_query($sql) or die($sql);
      
       }
      
    }