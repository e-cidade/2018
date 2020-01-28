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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_db_virada_classe.php"));
require_once(modification("classes/db_db_viradaitem_classe.php"));
require_once(modification("classes/db_db_viradaitemlog_classe.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cldb_virada        = new cl_db_virada();
$cldb_viradaitem    = new cl_db_viradaitem();
$cldb_viradaitemlog = new cl_db_viradaitemlog();

$sqlerro = false;

$aListaItens = explode("_",$lista);
array_shift($aListaItens);
$sListaItens = implode(",",$aListaItens);

$iAnoOrigem  = db_getsession('DB_anousu');
$iAnoDestino = ($iAnoOrigem + 1);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<script>	
function js_imprime(virada){
 
  jan = window.open('con4_viradadeano003.php?&virada='+virada,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  //js_OpenJanelaIframe('','db_iframe_relatorio','con4_viradadeano003.php?&virada='+virada,'Pesquisa',true);
}
</script>	
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0">
<table width="750" border="1" align="center" cellspacing="0"
	bgcolor="#CCCCCC">
	<tr>
		<td><br>
    <?php
    db_criatermometro('termometro','Concluido...','blue',1);
    echo "<br><br>";
    db_criatermometro('termometroitem','Concluido...','blue',1);

    db_inicio_transacao();
    
    // Consulta todas instituições
	    
    $sSqlConsultaInstit = " select codigo,nomeinst from db_config where db21_tipoinstit in (1,2,5)"; 
    $rsConsultaInstit   = db_query($sSqlConsultaInstit);
    $iLinhasInstit      = pg_num_rows($rsConsultaInstit);

    $aInstit = array();
    for ( $iInd=0; $iInd < $iLinhasInstit; $iInd++ ) {
    	$oInstit = db_utils::fieldsMemory($rsConsultaInstit,$iInd);
    	$aInstit[$oInstit->codigo] = $oInstit->nomeinst;
    }
    $sListaInstit = implode(",",$aInstit);
	    
	    
    // Verifica se existe virada já processada para os itens selecionados
    // Caso exista então valida se o processamento é do exercício anterior
	    
    $sSqlVirada  = " select distinct c31_db_viradacaditem,                                                         ";
    $sSqlVirada .= "        case                                                                                   ";
    $sSqlVirada .= "          when exists( select *                                                                ";
    $sSqlVirada .= "                         from db_virada a                                                      ";
    $sSqlVirada .= "                              inner join db_viradaitem b on b.c31_db_virada = a.c30_sequencial ";
    $sSqlVirada .= "                        where b.c31_db_viradacaditem = db_viradaitem.c31_db_viradacaditem      ";
    $sSqlVirada .= "                          and a.c30_anodestino = {$iAnoOrigem}) then false                     ";
    $sSqlVirada .= "          else true                                                                            ";
    $sSqlVirada .= "        end as erro                                                                            ";
    $sSqlVirada .= "   from db_virada                                                                              ";
    $sSqlVirada .= "        inner join db_viradaitem on c31_db_virada = c30_sequencial                             ";
    $sSqlVirada .= "  where c31_db_viradacaditem in ({$sListaItens}) and c31_db_viradacaditem not in(23,24)        ";
    
    $rsConsultaVirada = db_query($sSqlVirada);
    $iLinhasVirada    = pg_num_rows($rsConsultaVirada);
	    
    if ( $iLinhasVirada > 0 ) {
    	$aListaItemErro = array();
    	for ( $iInd=0; $iInd < $iLinhasVirada; $iInd++ ) {
    		$oItemVirada = db_utils::fieldsMemory($rsConsultaVirada,$iInd);
    		if ( $oItemVirada->erro == 't') {
          $aListaItemErro[] = $oItemVirada->c31_db_viradacaditem;
          $sqlerro = true;    				
    	  }
    	}
   	  if ( $sqlerro ) {
        $erro_msg  = "Processamento Interrompido!";
        $erro_msg .= "\\nItem de virada nº ".implode(",",$aListaItemErro)." sem processamento no exercício anterior";    	  	    	  
  	  }
    }

    // Verifica apartir dos itens informados se deve ser feito a validação do orçamento  
    
    $lValida = false;
    foreach ( $aListaItens as $iInd => $iItem ) {
      if ( in_array($iItem,array(1,2,3,4,8,11,12,15))) {
        $lValida = true;
      }
    }    
    
    if ( $lValida ) {
    	
	    if ( !$sqlerro ) {
	    	
		    // Verifica se existe uma versão do PPA homologada
		    $sSqlPPAVersao    = " select *                        "; 
		    $sSqlPPAVersao   .= "   from ppaversao                ";
		    $sSqlPPAVersao   .= "  where o119_versaofinal is true ";
		    $rsPPAVersao      = db_query($sSqlPPAVersao);
		    $iLinhasPPAVersao = pg_num_rows($rsPPAVersao);
		    
		  	if ( $iLinhasPPAVersao > 0 ) {
		  		
		  		$aListaInstitErro = array();
		  		
			    foreach ( $aInstit as $iInstit => $sDescrInstit ) {
		        
			    	// Verifica se existe vinculação do PPA com orçamento
			    	$sSqlPPAIntegracao  = " select *                              ";
			    	$sSqlPPAIntegracao .= "   from ppaintegracao                  ";
			    	$sSqlPPAIntegracao .= "  where o123_ano      = {$iAnoDestino} ";
			    	$sSqlPPAIntegracao .= "    and o123_instit   = {$iInstit}     ";
			    	$sSqlPPAIntegracao .= "    and o123_situacao = 1              ";
			    	$sSqlPPAIntegracao .= "    and o123_tipointegracao = 1         ";
		
			      $rsPPAIntegracao    = db_query($sSqlPPAIntegracao);
		        
		        if ( pg_num_rows($rsPPAIntegracao) == 0 ) {
		        	$aListaInstitErro[] = $sDescrInstit;
		          $sqlerro  = true;
		        }	    	
		      }
		      
		      if ( $sqlerro ) {
		      	if ( count($aListaInstitErro) > 1 ) {
		          $erro_msg  = "Exportação do PPA para Orçamento não encontrado nas instituições:\\n";        		
		          $erro_msg .= implode("\\n",$aListaInstitErro);
		       	} else {
			        $erro_msg  = "Exportação do PPA para Orçamento não encontrado na instituiçao {$aListaInstitErro[0]}!";
		      	}
		      }
			    
		    } else {
		    	
		    	$sErroMsg = '';
		    	
		    	foreach ( $aInstit as $iInstit => $sDescrInstit ) {
		      	
		      	// Verifica se existe cadastro de contas para o exercício de destino
		        $sSqlConPlano  = " select *                                                ";
		        $sSqlConPlano .= "  from conplanoreduz                                     "; 
		        $sSqlConPlano .= "       inner join conplanoexe on c62_anousu = c61_anousu "; 
		        $sSqlConPlano .= "                             and c62_reduz  = c61_reduz  ";
		        $sSqlConPlano .= " where c61_instit = {$iInstit}                           ";
		        $sSqlConPlano .= "   and c61_anousu = {$iAnoDestino} limit 1;              ";
		
		        $rsConPlano    = db_query($sSqlConPlano);
		        
		        if ( pg_num_rows($rsConPlano) == 0 ) {
		          $sErroMsg = "Cadastro de contas para o exercício {$iAnoDestino} não encontrado na instituição {$sDescrInstit}!\\n";
		        	$sqlerro  = true;
		        }
		
		        // Verifica se existe alguma receita configurada para o exercício de destino
		        $sSqlOrcReceita  = " select *                                    ";
		        $sSqlOrcReceita .= "   from orcreceita                           ";
		        $sSqlOrcReceita .= "  where o70_instit = {$iInstit}              ";
		        $sSqlOrcReceita .= "    and o70_anousu = {$iAnoDestino} limit 1; ";
		        
		        $rsOrcReceita    = db_query($sSqlOrcReceita); 
		        
		        if ( pg_num_rows($rsOrcReceita) == 0 ) {
		          $sErroMsg = "Nenhuma receita encontrada para o orçamento de {$iAnoDestino} na instituição {$sDescrInstit}\\n";
		          $sqlerro  = true;
		        }        
		        
		        // Verifica se existe alguma conta de despesa cadastrada para o exercício de destino        
		        $sSqlOrcDotacao  = " select *                                    ";
		        $sSqlOrcDotacao .= "   from orcdotacao                           ";
		        $sSqlOrcDotacao .= "  where o58_instit = {$iInstit}              ";
		        $sSqlOrcDotacao .= "    and o58_anousu = {$iAnoDestino} limit 1; ";
		        
		        $rsOrcDotacao    = db_query($sSqlOrcDotacao); 
		        
		        if ( pg_num_rows($rsOrcDotacao) == 0 ) {
		          $sErroMsg = "Nenhuma conta de despesa cadastrada para o exercício {$iAnoDestino} na instituição {$sDescrInstit}\\n";
		          $sqlerro  = true;
		        }               
		      }
		
		      if ( $sqlerro ) {
		        $erro_msg  = "Processamento Interrompido!\\n";
		        $erro_msg .= $sErroMsg;      	
		      }
		    }
	    }
    }
    
    if ( !$sqlerro ) {
    
	    // inclui na db_virada
	    $cldb_virada->c30_anoorigem  = $anoorigem;
	    $cldb_virada->c30_anodestino = $anodestino;
	    $cldb_virada->c30_usuario    = db_getsession("DB_id_usuario");
	    $cldb_virada->c30_data       = date("Y-m-d");
	    $cldb_virada->c30_hora       = date("H:i");
	    $cldb_virada->c30_situacao   = 1;
	    $cldb_virada->incluir(null);
	    if ($cldb_virada->erro_status==0) {
	      $sqlerro = true;
	      $erro_msg = $cldb_virada->erro_msg;
	    }
	
	    //echo "<pre>".print_r($lista)."</pre>";
	
	    if($sqlerro == false) {
	      $aItem  = split("_",$lista);
	      $iCountItem = sizeof($aItem);
	      for ($iItem=0; $iItem<$iCountItem; $iItem++) {
	        if (($aItem[$iItem] != "") && ($sqlerro == false)) {
	          
	          $sqlcaditem = "select * from db_viradacaditem where c33_sequencial=".$aItem[$iItem];
	          $resultcaditem = db_query($sqlcaditem);
	          db_fieldsmemory($resultcaditem, 0);
	
	          // inclui na db_viradaitem
	          $cldb_viradaitem->c31_db_virada        = $cldb_virada->c30_sequencial;
	          $cldb_viradaitem->c31_db_viradacaditem = $aItem[$iItem];
	          $cldb_viradaitem->c31_situacao         = 1;
	          $cldb_viradaitem->incluir(null);
	          if ($cldb_viradaitem->erro_status==0) {
	            $sqlerro = true;
	            $erro_msg = $cldb_viradaitem->erro_msg;
	          }
	
	          if($sqlerro == false) {
	            $sArquivoItemVirada = "con4_viradadeano002_item".str_pad("{$aItem[$iItem]}", 3, "0", STR_PAD_LEFT).".php";
	            //echo "<br>$sArquivoItemVirada";
	            if(file_exists($sArquivoItemVirada)) {
	              // Seta Variavel Para Erro, caso houver
	              $erro_msg = "Erro ao processar item {$c33_sequencial}-{$c33_descricao}!\\n";
	
	              $sMensagemTermometroItem = "Processando Item {$c33_sequencial}-{$c33_descricao}...";
	
	              // Inclui programa adequado para ser processado
	              require_once(modification($sArquivoItemVirada));
	
	              if($sqlerro==true) {
	                break;
	              }
	            } else {
	              $sqlerro  = true;
	              $erro_msg = "Item de Virada {$aItem[$iItem]} não disponível! Processamento Interrompido!";
	              break;
	            }
	          }
	        }
	        db_atutermometro($iItem, $iCountItem, 'termometro', 1, "Processando Virada de Ano do Exercicio {$anoorigem} para {$anodestino}");
	      }
	    }
    }  

    db_fim_transacao($sqlerro);
        
		?>
  </td>
	</tr>
	<tr>
	  <td align="center">
    <?php

    if($sqlerro == false){
      $virada = $cldb_virada->c30_sequencial;
      echo "
            <script>
              var confirmar = confirm('O procedimento de virada foi realizado com sucesso. Visualizar o relatório de logs das inconsistências desta operação.'); 
              if(confirmar==true){
                js_imprime($virada);
              }else{
                alert('Procedimento de virada concluido.');
              }
              parent.document.form1.submit();

            </script>
          ";
    } else {
      db_msgbox($erro_msg);
      echo "<script>parent.document.form1.submit();</script>";
    }
    ?>
	  </td>
	</tr>
</table>
</body>
</html>