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

// Para garantir que nao houve erros em outros itens
if($sqlerro == false) {
	
  $iTotPassos = 2;
  db_atutermometro(0, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  // EXERCICIOS LOTACOES
  $sqlorigem = "select * from rhlotaexe where rh26_anousu =  $anoorigem limit 1";
  $resultorigem = db_query($sqlorigem);
  $linhasorigem = pg_num_rows($resultorigem);

  $sqldestino = "select * from rhlotaexe where rh26_anousu =  $anodestino limit 1";
  $resultdestino = db_query($sqldestino);
  $linhasdestino = pg_num_rows($resultdestino);

  if (($linhasorigem > 0) && ($linhasdestino == 0 )) {
    
    // $sqlrhlotaex = "select fc_duplica_exercicio('rhlotaexe', 'rh26_anousu', ".$anoorigem.",".$anodestino.",null);";
    // $resultrhlotaex = db_query($sqlrhlotaex);
    $oDaoRhlotaexe = db_utils::getDao('rhlotaexe');
    $sqlrhlotaex  = " select distinct rh26_codigo,rh26_orgao,rh26_unidade  ";
    $sqlrhlotaex .= "   from rhlotaexe  ";
    $sqlrhlotaex .= "        inner join orcunidade on orcunidade.o41_orgao   = rhlotaexe.rh26_orgao  ";
    $sqlrhlotaex .= "                             and orcunidade.o41_unidade = rhlotaexe.rh26_unidade  ";
    $sqlrhlotaex .= "                             and orcunidade.o41_anousu  = {$anodestino} ";
    $sqlrhlotaex .= " where rhlotaexe.rh26_anousu = {$anoorigem} ";
    $rsLotaExe    = db_query($sqlrhlotaex);
  
    if ($rsLotaExe) {
      $sqlerro = false;      
    } else {
      $sqlerro   = true;
      $erro_msg .= pg_last_error($rsLotaExe); //"Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
    }

    $iNumRows = pg_num_rows($rsLotaExe);
    for ($iLota = 0; $iLota < $iNumRows; $iLota++) {

      $oLotaExe = db_utils::fieldsMemory($rsLotaExe,$iLota);
      $oDaoRhlotaexe->rh26_anousu   = $anodestino;
      $oDaoRhlotaexe->rh26_codigo   = $oLotaExe->rh26_codigo;
      $oDaoRhlotaexe->rh26_orgao    = $oLotaExe->rh26_orgao;
      $oDaoRhlotaexe->rh26_unidade  = $oLotaExe->rh26_unidade;
      $oDaoRhlotaexe->incluir($anodestino,$oLotaExe->rh26_codigo);
      if ($oDaoRhlotaexe->erro_status==0) {
        $sqlerro   = true;
        $erro_msg .= $oDaoRhlotaexe->erro_msg;
      }

    }
    
  } else {
  	
    if ($linhasorigem==0) {
      $cldb_viradaitemlog->c35_log = "Não existem dados de orgaos e unidades da folha cadastrados para o exercicio $anoorigem";
    }
    
    if ($linhasdestino>0) {
      $cldb_viradaitemlog->c35_log = "Ja existem dados de orgaos e unidades da folha cadastrados para ano de destino $anodestino";
    }
    
    $cldb_viradaitemlog->c35_codarq        = 1181;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status==0) {
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }
 
  $sSqlrhElementoEmp  = " select *                          ";
  $sSqlrhElementoEmp .= "   from pessoal.rhelementoemp      "; 
  $sSqlrhElementoEmp .= "  where rh38_anousu = {$anoorigem} ";
  $rsConsultaElemEmp  = db_query($sSqlrhElementoEmp);
  $iLinhasElemEmp     = pg_num_rows($rsConsultaElemEmp);
  
  if ( $iLinhasElemEmp > 0 ) {
  	
	  $oDaoRHElementoEmp = db_utils::getDao('rhelementoemp');
	  
  	for ( $iInd=0; $iInd < $iLinhasElemEmp; $iInd++ ) {
      db_inicio_transacao();
  		$oElemEmp = db_utils::fieldsMemory($rsConsultaElemEmp,$iInd);

		  $oDaoRHElementoEmp->rh38_anousu = $anodestino;
		  $oDaoRHElementoEmp->rh38_codele = $oElemEmp->rh38_codele;
		  $oDaoRHElementoEmp->incluir(null);

      $iElementoEmpAnterior = $oElemEmp->rh38_seq;

		  if ( $oDaoRHElementoEmp->erro_status == 0 ) {
		  	$sqlerro  = true;
		  	$erro_msg = $oDaoRHElementoEmp->erro_msg;
        break;
		  }

      // Busca os dados na rhempenhoelementopcasp trocando o sequencial do ano anterior pelo sequencial do proximo ano.
      $oDaoRhEmpenhoElementoPcasp   = db_utils::getDao('rhempenhoelementopcasp');
      $sWhereRhEmpenhoElementoPcasp = "rh119_rhelementoempdef = {$iElementoEmpAnterior}";
      $sSqlRhEmpenhoElementoPcasp   = $oDaoRhEmpenhoElementoPcasp->sql_query_file(null,'rh119_sequencial',null, $sWhereRhEmpenhoElementoPcasp);
      $rsRhEmpenhoElementoPcasp     = db_query($sSqlRhEmpenhoElementoPcasp);

      for ($iElemento = 0; $iElemento < pg_num_rows($rsRhEmpenhoElementoPcasp); $iElemento++){

        $oElemento = db_utils::fieldsMemory($rsRhEmpenhoElementoPcasp, $iElemento);

        $oDaoRhEmpenhoElementoPcasp->rh119_rhelementoempdef = $oDaoRHElementoEmp->rh38_seq;
        $oDaoRhEmpenhoElementoPcasp->rh119_sequencial       = $oElemento->rh119_sequencial;
        $oDaoRhEmpenhoElementoPcasp->alterar($oElemento->rh119_sequencial);

        if ( $oDaoRhEmpenhoElementoPcasp->erro_status == "0" ) {
          $sqlerro  = true;
          $erro_msg = $oDaoRHElementoEmp->erro_msg;
          break;
        }
      }

      $sWhereRhEmpenhoElementoPcasp = "rh119_rhelementoempnov = {$iElementoEmpAnterior}";
      $sSqlRhEmpenhoElementoPcasp   = $oDaoRhEmpenhoElementoPcasp->sql_query_file(null,'rh119_sequencial',null, $sWhereRhEmpenhoElementoPcasp);
      $rsRhEmpenhoElementoPcasp     = db_query($sSqlRhEmpenhoElementoPcasp);

      for ($iElemento = 0; $iElemento < pg_num_rows($rsRhEmpenhoElementoPcasp); $iElemento++){

        $oElemento = db_utils::fieldsMemory($rsRhEmpenhoElementoPcasp, $iElemento);

        $oDaoRhEmpenhoElementoPcasp->rh119_rhelementoempnov = $oDaoRHElementoEmp->rh38_seq;
        $oDaoRhEmpenhoElementoPcasp->rh119_sequencial       = $oElemento->rh119_sequencial;
        $oDaoRhEmpenhoElementoPcasp->alterar($oElemento->rh119_sequencial);

        if ( $oDaoRhEmpenhoElementoPcasp->erro_status == "0" ) {
           $sqlerro  = true;
           $erro_msg = $oDaoRHElementoEmp->erro_msg;
           break;
        }
      }
  	}
  }
  
  $sSqlElemPCMater  = " select rh36_pcmater,e_destino.rh38_seq                                                            "; 
	$sSqlElemPCMater .= "   from pessoal.rhelementoemppcmater                                                               "; 
	$sSqlElemPCMater .= "        inner join pessoal.rhelementoemp e_origem  on e_origem.rh38_seq     = rh36_rhelementoemp   "; 
	$sSqlElemPCMater .= "                                                  and e_origem.rh38_anousu  = {$anoorigem}         ";
	$sSqlElemPCMater .= "        inner join pessoal.rhelementoemp e_destino on e_destino.rh38_codele = e_origem.rh38_codele "; 
	$sSqlElemPCMater .= "                                                  and e_destino.rh38_anousu = {$anodestino}        ";
  
	$rsConsultaElemPCMater = db_query($sSqlElemPCMater);
  $iLinhasElemPCMater    = pg_num_rows($rsConsultaElemPCMater);
  
  if ( $iLinhasElemPCMater > 0 ) {
  	
  	$oDaoRHElementoEmpPcmater = db_utils::getDao('rhelementoemppcmater');
  	
  	for ( $iInd=0; $iInd < $iLinhasElemPCMater; $iInd++ ) {
  		$oElemEmpPCMater = db_utils::fieldsMemory($rsConsultaElemPCMater,$iInd);

      $oDaoRHElementoEmpPcmater->rh36_pcmater       = $oElemEmpPCMater->rh36_pcmater;
      $oDaoRHElementoEmpPcmater->rh36_rhelementoemp = $oElemEmpPCMater->rh38_seq;
      $oDaoRHElementoEmpPcmater->incluir(null);
      
      if ( $oDaoRHElementoEmpPcmater->erro_status == 0 )  {
      	$sqlerro  = true;
      	$erro_msg = $oDaoRHElementoEmpPcmater->erro_msg;
      	break;
      }
  	}
  }
  
  if ($sqlerro == false) {
  	
    $oDaoRhEmpenhoFolhaExcecaoRubrica     = db_utils::getDao("rhempenhofolhaexcecaorubrica");
    
	  $sWhere                               = "rh74_anousu = {$anoorigem}";
	  $sSqlRhEmpenhoFolhaExcecaoRubrica     = $oDaoRhEmpenhoFolhaExcecaoRubrica->sql_query_file(null, "*", null, $sWhere);
	  $rsSqlRhEmpenhoFolhaExcecaoRubrica    = $oDaoRhEmpenhoFolhaExcecaoRubrica->sql_record($sSqlRhEmpenhoFolhaExcecaoRubrica);
	  $iNumRowsRhEmpenhoFolhaExcecaoRubrica = $oDaoRhEmpenhoFolhaExcecaoRubrica->numrows;
	  if ($iNumRowsRhEmpenhoFolhaExcecaoRubrica > 0) {
	    
	    for ($iIndRubrica = 0; $iIndRubrica < $iNumRowsRhEmpenhoFolhaExcecaoRubrica; $iIndRubrica++) {
	      
	      $oRhEmpenhoFolhaExcecaoRubrica = db_utils::fieldsMemory($rsSqlRhEmpenhoFolhaExcecaoRubrica, $iIndRubrica);
	      
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_rubric                     = $oRhEmpenhoFolhaExcecaoRubrica->rh74_rubric;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_instit                     = $oRhEmpenhoFolhaExcecaoRubrica->rh74_instit;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_unidade                    = $oRhEmpenhoFolhaExcecaoRubrica->rh74_unidade;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_orgao                      = $oRhEmpenhoFolhaExcecaoRubrica->rh74_orgao;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_projativ                   = $oRhEmpenhoFolhaExcecaoRubrica->rh74_projativ;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_anousu                     = $anodestino;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_recurso                    = $oRhEmpenhoFolhaExcecaoRubrica->rh74_recurso;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_concarpeculiar             = $oRhEmpenhoFolhaExcecaoRubrica->rh74_concarpeculiar;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_programa                   = ($oRhEmpenhoFolhaExcecaoRubrica->rh74_programa == null?"null":$oRhEmpenhoFolhaExcecaoRubrica->rh74_programa);
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_subfuncao                  = ($oRhEmpenhoFolhaExcecaoRubrica->rh74_subfuncao == null?"null":$oRhEmpenhoFolhaExcecaoRubrica->rh74_subfuncao);
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_funcao                     = ($oRhEmpenhoFolhaExcecaoRubrica->rh74_funcao == null?"null":$oRhEmpenhoFolhaExcecaoRubrica->rh74_funcao);
        $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_codele                     = $oRhEmpenhoFolhaExcecaoRubrica->rh74_codele;
        $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_tipofolha                  = $oRhEmpenhoFolhaExcecaoRubrica->rh74_tipofolha;
        $oDaoRhEmpenhoFolhaExcecaoRubrica->rh74_rhempenhofolhaexcecaoregra = $oRhEmpenhoFolhaExcecaoRubrica->rh74_rhempenhofolhaexcecaoregra;
	      $oDaoRhEmpenhoFolhaExcecaoRubrica->incluir(null);
	      if ($oDaoRhEmpenhoFolhaExcecaoRubrica->erro_status == 0) {
	        $sqlerro  = true;
	        $erro_msg = $oDaoRhEmpenhoFolhaExcecaoRubrica->erro_msg;
	        break;
	      }
	    }
	  }
  }

  if ($sqlerro == false) {
    $oDaoRhcontasrec = db_utils::getDao('rhcontasrec');

    $sWhere  = " rh41_anousu = {$anoorigem} and not exists( select *                               \n";
    $sWhere .= "                                              from rhcontasrec cr                  \n";
    $sWhere .= "                                             where cr.rh41_conta = rh41_conta      \n";
    $sWhere .= "                                               and cr.rh41_codigo = rh41_codigo    \n";
    $sWhere .= "                                               and cr.rh41_instit = rh41_instit    \n";
    $sWhere .= "                                               and cr.rh41_anousu = {$anodestino}) \n";

    $sSqlRhcontasrec = $oDaoRhcontasrec->sql_query_file(null, null, null, null, "*", null, $sWhere);
    $rsRhcontasrec   = $oDaoRhcontasrec->sql_record( $sSqlRhcontasrec );
    $iNumRows        = $oDaoRhcontasrec->numrows;
    
    if ($iNumRows > 0) {

      for ($iIndice = 0; $iIndice < $iNumRows; $iIndice++) {

        $oRhcontasrec = db_utils::fieldsMemory($rsRhcontasrec, $iIndice);

        $oDaoRhcontasrec->incluir($oRhcontasrec->rh41_conta, $oRhcontasrec->rh41_codigo, $oRhcontasrec->rh41_instit, $anodestino);

        if ($oDaoRhcontasrec->erro_status == 0) {

          $sqlerro = true;
          $erro_msg = $oDaoRhcontasrec->erro_msg;
          break;
        }
      }
    }
  }
  
  db_atutermometro(1, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);
}
?>