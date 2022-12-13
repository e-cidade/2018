<?php
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

require_once ('classes/db_orcparamseqfiltroorcamento_classe.php');
require_once ('classes/db_orcparamseqfiltropadrao_classe.php');

$oCl_orcparamseqfiltroorcamento = new cl_orcparamseqfiltroorcamento();
$oCl_orcparamseqfiltropadrao    = new cl_orcparamseqfiltropadrao();


if($sqlerro==false) {
	db_inicio_transacao();
	
	/*
	 * Antes de iniciarmos a inclusão, devemos fazer a exclusão dos itens, 
	 * das tabelas  orcparamseqfiltroorcamento e  orcparamseqfiltropadrao do ano de destino da migração. 
	 */
	//Testa se nao houve erro e exclui da tabela orcparamseqfiltroorcamento
	$oCl_orcparamseqfiltroorcamento->excluir("","o133_anousu = {$anodestino}");
	    if ($oCl_orcparamseqfiltroorcamento->erro_status == 0) {
	    	
	      $sqlerro   = true;
	      $erro_msg .= $oCl_orcparamseqfiltroorcamento->erro_msg;
	    } 
	//Testa se nao houve erro e exclui da tabela orcparamseqfiltropadrao
	$oCl_orcparamseqfiltropadrao->excluir("","o132_anousu = {$anodestino}");
	    if ($oCl_orcparamseqfiltropadrao->erro_status == 0) {
	    	
	      $sqlerro   = true;
	      $erro_msg .= $oCl_orcparamseqfiltropadrao->erro_msg;
	    }

// Para garantir que nao houve erros em outros itens
if($sqlerro==false) {
	
  $iTotPassos = 2;
  
  db_atutermometro(0, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  // CONFIGURAÇÃO DE RELATORIOS
  // orcparamseqfiltroorcamento
  $sqldeporigem = "select * from orcparamseqfiltroorcamento where o133_anousu = $anoorigem limit 1";
  $resultdeporigem = db_query($sqldeporigem);
  $linhasdeporigem = pg_num_rows($resultdeporigem);

  $sqldepdestino  = "select * from orcparamseqfiltroorcamento where o133_anousu =  $anodestino limit 1";
  $resultdepdestino = db_query($sqldepdestino);
  $linhasdepdestino = pg_num_rows($resultdepdestino);

  if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
		/*
		 * Seleciona todos registros da tabela orcparamseqfiltroorcamento onde o exercicio é igual ao ano de origem
		 * e para cada registro, inseri na mesma tabela, os mesmos valores , porem no anousu, vai o novo ano de destino; 
		 */  
		$sSqlorcparamseqfiltroorcamento = $oCl_orcparamseqfiltroorcamento->sql_query_file("","*","","o133_anousu = {$anoorigem}");
		$rsOrcparamseqfiltroorcamento   = $oCl_orcparamseqfiltroorcamento->sql_record($sSqlorcparamseqfiltroorcamento);
		$iTotalLinhaInsertOrcamento     = pg_num_rows($rsOrcparamseqfiltroorcamento);
		for ($iLinhaInsertOrcamento = 0; $iLinhaInsertOrcamento < $iTotalLinhaInsertOrcamento;  $iLinhaInsertOrcamento++ ) {
		  
		  db_fieldsmemory($rsOrcparamseqfiltroorcamento, $iLinhaInsertOrcamento);
		  $o133_sequencial = "";
		  $oCl_orcparamseqfiltroorcamento->o133_orcparamrel = $o133_orcparamrel;
		  $oCl_orcparamseqfiltroorcamento->o133_orcparamseq = $o133_orcparamseq;
		  $oCl_orcparamseqfiltroorcamento->o133_anousu      = $anodestino;
		  $oCl_orcparamseqfiltroorcamento->o133_filtro      = $o133_filtro;
		  $oCl_orcparamseqfiltroorcamento->incluir($o133_sequencial);
			if ($oCl_orcparamseqfiltroorcamento->erro_status == 0) {
	      
	      $sqlerro   = true;
	      $erro_msg .= $oCl_orcparamseqfiltroorcamento->erro_msg;
	    }		     
		}    
    
  } else {
    if ($linhasdeporigem == 0) {
      $cldb_viradaitemlog->c35_log     = "Não existem registros na (orcparamseqfiltroorcamento) para ano de origem $anoorigem";
    } else if ($linhasdepdestino>0) {
      $cldb_viradaitemlog->c35_log     = "Ja existem registros na (orcparamseqfiltroorcamento) para ano de destino $anodestino";
    }
    $cldb_viradaitemlog->c35_codarq        = 2711;  // 2711 tabela: orcparamseqfiltroorcamento
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status==0) {
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
    
  }

  db_atutermometro(1, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);
  
    // orcparamseqfiltropadrao
    $sqldeporigem = "select * from orcparamseqfiltropadrao  where o132_anousu = $anoorigem limit 1";
    $resultdeporigem = db_query($sqldeporigem);
    $linhasdeporigem = pg_num_rows($resultdeporigem);

    $sqldepdestino = "select * from orcparamseqfiltropadrao  where o132_anousu = $anodestino limit 1";
    $resultdepdestino = db_query($sqldepdestino);
    $linhasdepdestino = pg_num_rows($resultdepdestino);

    if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
		/*
		 * Seleciona todos registros da tabela orcparamseqfiltropadrao onde o exercicio é igual ao ano de origem
		 * e para cada registro, inseri na mesma tabela, os mesmos valores , porem no anousu, vai o novo ano de destino; 
		 */  
		$sSqlorcparamseqfiltropadrao = $oCl_orcparamseqfiltropadrao->sql_query_file("","*","","o132_anousu = {$anoorigem}");
		$rsOrcparamseqfiltropadrao   = $oCl_orcparamseqfiltropadrao->sql_record($sSqlorcparamseqfiltropadrao);
		$iTotalLinhaInsertpadrao     = pg_num_rows($rsOrcparamseqfiltropadrao);
		for ($iLinhaInsertpadrao = 0; $iLinhaInsertpadrao < $iTotalLinhaInsertpadrao;  $iLinhaInsertpadrao++ ) {
		  
		  db_fieldsmemory($rsOrcparamseqfiltropadrao, $iLinhaInsertpadrao);
		  $o132_sequencial = "";
		  $oCl_orcparamseqfiltropadrao->o132_orcparamrel = $o132_orcparamrel;
		  $oCl_orcparamseqfiltropadrao->o132_orcparamseq = $o132_orcparamseq;
		  $oCl_orcparamseqfiltropadrao->o132_anousu      = $anodestino;
		  $oCl_orcparamseqfiltropadrao->o132_filtro      = $o132_filtro;
		  $oCl_orcparamseqfiltropadrao->incluir($o132_sequencial);
      if ($oCl_orcparamseqfiltropadrao->erro_status == 0) {
        
        $sqlerro   = true;
        $erro_msg .= $oCl_orcparamseqfiltropadrao->erro_msg;
      } 	  	  		  
		}
      
    } else {
      if ($linhasdeporigem == 0) {
        $cldb_viradaitemlog->c35_log = " Não existem registros na (orcparamseqfiltropadrao) para ano de origem $anoorigem";
      } else if ($linhasdepdestino>0) {
        $cldb_viradaitemlog->c35_log = " Ja existem registros na (orcparamseqfiltropadrao) para ano de destino $anodestino";
      }
      
      $cldb_viradaitemlog->c35_codarq        = 2706; // 2706 : orcparamseqfiltropadrao
      $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
      $cldb_viradaitemlog->c35_data          = date("Y-m-d");
      $cldb_viradaitemlog->c35_hora          = date("H:i");
      $cldb_viradaitemlog->incluir(null);
      if ($cldb_viradaitemlog->erro_status==0) {
        $sqlerro   = true;
        $erro_msg .= $cldb_viradaitemlog->erro_msg;
      }
    }
    

  }
  
db_fim_transacao();  
}

?>