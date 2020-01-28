<?
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/JSON.php");
include("classes/db_rhemissaocheque_classe.php");
include("classes/db_rhemissaochequeitem_classe.php");
include("classes/db_selecao_classe.php");

$oPost = db_utils::postMemory($_POST);
$oJson = new services_json();

$clrhemissaocheque     = new cl_rhemissaocheque();
$clrhemissaochequeitem = new cl_rhemissaochequeitem();
$clselecao             = new cl_selecao();
$clgerasql             = new cl_gera_sql_folha();

if ( isset($oPost->method) && $oPost->method == "gerarCheques")  {
	
	$lSqlErro   = false;
	$sMsgErro   = "";
	$iTamCampo  = strlen($oPost->iNumCheque);
	$iNumCheque = $oPost->iNumCheque;
	
	db_inicio_transacao();
	
	
	if ( $oPost->tipoGera == "p" ) {
		
		$sWhere    = "1=1";
		$sSigla    = "r52";
		$sTabela   = "pensao";
		$sCampoCGM = "r52_numcgm";
		
	  switch ($oPost->tipofol) {
      case "r48":
      	$sCampoValor = "r52_valcom";
      	$iTipoGera   = "8";
      break;
      case "r14":
      	$sCampoValor = "r52_valor";
      	$iTipoGera   = "7";
      break;
      case "r35":
      	$sCampoValor = "r52_val13";
      	$iTipoGera   = "10";
      break;
      case "r52":
      	$sCampoValor = "r52_valfer";
      	$iTipoGera   = "6";
      break;
      case "r20":
      	$sCampoValor = "r52_valres";
      	$iTipoGera   = "9";
      break;                    
    }		
		
	} else {

		switch ($oPost->tipofol) {
			case "r48":
	      $sSigla    = "r48";
	      $sTabela   = "gerfcom";
	      $iTipoGera = "2";		
			break;
	    case "r14":
	      $sSigla    = "r14";
	      $sTabela   = "gerfsal";
	      $iTipoGera = "1";    
	    break;
	    case "r35":
	      $sSigla    = "r35";
	      $sTabela   = "gerfs13";
	      $iTipoGera = "4";    
	    break;
	    case "r22":
	      $sSigla    = "r22";
	      $sTabela   = "gerfadi";
	      $iTipoGera = "5";    
	    break;
	    case "r20":
	      $sSigla    = "r20";
	      $sTabela   = "gerfres";
	      $iTipoGera = "3";    
	    break;                		
		}
		
		$sWhere      = "rh02_fpagto <> 3";
    $sCampoValor = " case 
                       when {$sSigla}_pd = 1 then {$sSigla}_valor 
                       else {$sSigla}_valor *(-1) 
                     end ";
		$sCampoCGM   = "x.rh01_numcgm";
		
	}

	
 	switch ($oPost->tipo) {
 		case "l":
     	$sTipoResumo = "lotac" ;
     	$sCampoWhere = "r70_estrut";
     	$clgerasql->usar_lot = true;
     	$lString     = true;
 		break;
    case "o":
      $sTipoResumo = "orgao";
      $sCampoWhere = "o40_orgao"; 
     	$clgerasql->usar_org = true;
     	$lString     = false;
    break;
    case "m":
      $sTipoResumo = "regis" ;
      $sCampoWhere = "rh01_regist";
      $lString     = false;
    break;
    case "t":
      $sTipoResumo = "local" ;
      $sCampoWhere = "rh55_estrut";
      $clgerasql->usar_tra = true;
      $lString     = true;      
    break;           
 	}
 	
 	if ( isset($oPost->filtro) ) {
 		
	  if ( $oPost->filtro == "s" && isset($oPost->{"f".$sTipoResumo}) && trim($oPost->{"f".$sTipoResumo}) != "" ) {
	    $sWhere .= " and {$sCampoWhere} in ('".str_replace(',',"','",$oPost->{"f".$sTipoResumo})."') ";
	  } else if ( $oPost->filtro == "i" && isset($oPost->{$sTipoResumo."i"}) && trim($oPost->{$sTipoResumo."i"} ) != "" ) {
	  	if ( $lString ) {
	  	  $sWhere .= " and {$sCampoWhere} >= '".$oPost->{$sTipoResumo."i"}."'";
	  	} else {
	  		$sWhere .= " and {$sCampoWhere} >= ".$oPost->{$sTipoResumo."i"};
	  	}
	  }
	
	  if ( $oPost->filtro == "i" && isset($oPost->{$sTipoResumo."f"}) && trim($oPost->{$sTipoResumo."f"} ) != "" ) {
	  	if ( $lString ) {
	  	  $sWhere .= " and {$sCampoWhere} <= '".$oPost->{$sTipoResumo."f"}."'";
	  	} else {
	  		$sWhere .= " and {$sCampoWhere} <= ".$oPost->{$sTipoResumo."f"};
	  	}
	  }
	  
 	}
 	
	if( isset($oPost->selecao) && trim($oPost->selecao) != ""){
	  $rsSelecao = $clselecao->sql_record($clselecao->sql_query_file($oPost->selecao,db_getsession("DB_instit"),"r44_where"));
	  if($clselecao->numrows > 0){
	    $oSelecao = db_utils::fieldsMemory($rsSelecao,0);
	    $sWhere  .= " and ".$oSelecao->r44_where;
	  }
	}
 	
 	
  $clgerasql->usar_pes = true;
  $clgerasql->usar_lot = true;

  $sCamposDados  = "rh01_numcgm, ";
  $sCamposDados .= "rh02_regist, ";
  $sCamposDados .= "rh02_anousu, ";
  $sCamposDados .= "rh02_mesusu, ";
  $sCamposDados .= "rh02_fpagto  ";
     
  $sSqlDados     = $clgerasql->gerador_sql($sSigla,$oPost->anofolha,$oPost->mesfolha,null,null,$sCamposDados,"",$sWhere);
  
  $sSqlConsultaReg  = " select {$sSigla}_regist as regist,                                                      ";
  $sSqlConsultaReg .= "        {$sSigla}_anousu as anousu,                                                      ";
  $sSqlConsultaReg .= "        {$sSigla}_mesusu as mesusu,                                                      ";
  $sSqlConsultaReg .= "        {$sCampoCGM}     as numcgm,                                                      ";
  $sSqlConsultaReg .= "        sum({$sCampoValor}) as liquido                                                   "; 
  $sSqlConsultaReg .= "   from ({$sSqlDados}) as x                                                              ";
  $sSqlConsultaReg .= "        inner join {$sTabela}          on {$sTabela}.{$sSigla}_anousu    = x.rh02_anousu ";
  $sSqlConsultaReg .= "                                      and {$sTabela}.{$sSigla}_mesusu    = x.rh02_mesusu ";
  $sSqlConsultaReg .= "                                      and {$sTabela}.{$sSigla}_regist    = x.rh02_regist ";
  $sSqlConsultaReg .= "        left  join rhemissaochequeitem on rhemissaochequeitem.r18_anousu = x.rh02_anousu ";
  $sSqlConsultaReg .= "                                      and rhemissaochequeitem.r18_mesusu = x.rh02_mesusu ";
  $sSqlConsultaReg .= "                                      and rhemissaochequeitem.r18_regist = x.rh02_regist ";  
  $sSqlConsultaReg .= "                                      and rhemissaochequeitem.r18_tipo   = {$iTipoGera}  ";  
  $sSqlConsultaReg .= "  where {$sSigla}_anousu = {$oPost->anofolha}                                            ";  
  $sSqlConsultaReg .= "    and {$sSigla}_mesusu = {$oPost->mesfolha}                                            ";
  if($sTabela != 'pensao'){
    $sSqlConsultaReg .= "  and {$sSigla}_pd != 3                                                                ";
  }
  $sSqlConsultaReg .= "    and r18_sequencial is null                                                           ";  
  $sSqlConsultaReg .= "  group by {$sSigla}_regist,                                                             ";
  $sSqlConsultaReg .= "           {$sSigla}_anousu,                                                             ";
  $sSqlConsultaReg .= "           {$sSigla}_mesusu,                                                             ";
  $sSqlConsultaReg .= "           {$sCampoCGM}                                                                  ";

  $rsConsultaReg = db_query($sSqlConsultaReg);
  $iLinhasReg    = pg_num_rows($rsConsultaReg);

  if ( $iLinhasReg > 0 ) {
  	
  	$clrhemissaocheque->r15_idusuario = db_getsession('DB_id_usuario');
  	$clrhemissaocheque->r15_descricao = utf8_decode($oPost->descrGera);
  	$clrhemissaocheque->r15_dtgeracao = date('Y-m-d',db_getsession('DB_datausu')); 
  	$clrhemissaocheque->r15_hora      = db_hora();
  	$clrhemissaocheque->incluir(null);
  	
  	if ( $clrhemissaocheque->erro_status == 0 ) {
  		$lSqlErro = true;
  	}
  	
  	$sMsgErro = $clrhemissaocheque->erro_msg;
  	
  	if ( !$lSqlErro ) {
  		
	  	for ( $iInd=0; $iInd < $iLinhasReg; $iInd++ ) {
	  		
	  		$oPes = db_utils::fieldsMemory($rsConsultaReg,$iInd);
	  		
	  		$clrhemissaochequeitem->r18_anousu        = $oPes->anousu;
	  		$clrhemissaochequeitem->r18_mesusu        = $oPes->mesusu;
	  		$clrhemissaochequeitem->r18_regist        = $oPes->regist;
	  		$clrhemissaochequeitem->r18_numcgm        = $oPes->numcgm;
	  		$clrhemissaochequeitem->r18_emissaocheque = $clrhemissaocheque->r15_sequencial;
	  		$clrhemissaochequeitem->r18_tipo          = $iTipoGera;
	  		$clrhemissaochequeitem->r18_valor         = $oPes->liquido;
	  		$clrhemissaochequeitem->r18_numcheque     = str_pad($iNumCheque,$iTamCampo,"0",STR_PAD_LEFT);
	  		$clrhemissaochequeitem->incluir(null);
	  		
		    if ( $clrhemissaochequeitem->erro_status == 0 ) {
		      $lSqlErro = true;
		      $sMsgErro = $clrhemissaochequeitem->erro_msg;
		      break;
		    }
		    
		    $sMsgErro = $clrhemissaochequeitem->erro_msg;
	      $iNumCheque++;
	  	}
  	}
  	
  } else {
  	
  	$sMsgErro = "Nenhum registro encontrado!";
  	$lSqlErro = true;
  }

  
  if ( $lSqlErro ) {
  	
    $aRetorno = array( "msg" =>urlencode($sMsgErro),
                       "erro"=>true);
  } else {
  	
    $aRetorno = array( "matriculas"=>$oPost->fregis,
                       "codgeracao"=>$clrhemissaocheque->r15_sequencial,
                       "msg"       =>urlencode($sMsgErro),
                       "erro"      =>false);
  }

  db_fim_transacao($lSqlErro);
  echo $oJson->encode($aRetorno);
} else if ( isset($oPost->method) && $oPost->method == "regerarCheques") {
	
	
	$lSqlErro   = false;
  $iTamCampo  = strlen($oPost->iNumCheque);
  $iNumCheque = $oPost->iNumCheque;	
	
	$sWhere         = " r18_emissaocheque = {$oPost->iCodGeracao}";
  $sSqlTipo       = $clrhemissaochequeitem->sql_query_file(null,"distinct r18_tipo ",null,$sWhere);
	$rsConsultaTipo = $clrhemissaochequeitem->sql_record($sSqlTipo);
	
	$oTipo = db_utils::fieldsMemory($rsConsultaTipo,0);

	if ( $oTipo->r18_tipo <= 5 ) {

    switch ($oTipo->r18_tipo) {
      case "1":
        $sSigla    = "r14";
        $sTabela   = "gerfsal";
      break;
      case "2":
        $sSigla    = "r48";
        $sTabela   = "gerfcom";
      break;
      case "3":
        $sSigla    = "r20";
        $sTabela   = "gerfres";
      break;                    
      case "4":
        $sSigla    = "r35";
        $sTabela   = "gerfs13";
      break;
      case "5":
        $sSigla    = "r22";
        $sTabela   = "gerfadi";
      break;
    }	
    
    $sCampoValor = " case when {$sSigla}_pd = 1 then {$sSigla}_valor else {$sSigla}_valor *(-1) end ";
    
	} else {
		    
    $sSigla   = "r52";
    $sTabela  = "pensao";
    
		switch ($oTipo->r18_tipo) {
      case "6":
        $sCampoValor = "r52_valfer";
      break;
      case "7":
        $sCampoValor = "r52_valor";      	
      break;                    
      case "8":
        $sCampoValor = "r52_valcom";      	
      break;
      case "9":
        $sCampoValor = "r52_valres";
      break;
      case "10":
        $sCampoValor = "r52_val13";
      break;                  
    }
    
	}
		
	db_inicio_transacao();
	
	$sSqlCheques  = " select r18_regist,                                                                           ";
  $sSqlCheques .= "        r18_anousu,                                                                           ";
  $sSqlCheques .= "        r18_mesusu,                                                                           ";
  $sSqlCheques .= "        r18_numcgm,                                                                           ";
  $sSqlCheques .= "        sum({$sCampoValor}) as liquido                                                        "; 
  $sSqlCheques .= "   from rhemissaochequeitem                                                                   ";
  $sSqlCheques .= "        inner join {$sTabela} on {$sTabela}.{$sSigla}_anousu = rhemissaochequeitem.r18_anousu ";
  $sSqlCheques .= "                             and {$sTabela}.{$sSigla}_mesusu = rhemissaochequeitem.r18_mesusu ";
  $sSqlCheques .= "                             and {$sTabela}.{$sSigla}_regist = rhemissaochequeitem.r18_regist ";
  $sSqlCheques .= "  where r18_emissaocheque = {$oPost->iCodGeracao}                                             ";
  if($sTabela != 'pensao'){
    $sSqlCheques .= "  and {$sSigla}_pd != 3                                                                     ";
  }
  $sSqlCheques .= "  group by r18_regist,                                                                        ";
  $sSqlCheques .= "           r18_anousu,                                                                        ";
  $sSqlCheques .= "           r18_mesusu,                                                                        ";
  $sSqlCheques .= "           r18_numcgm                                                                         ";
	
	$rsConsultaCheques = $clrhemissaochequeitem->sql_record($sSqlCheques);
	$iLinhasCheques    = $clrhemissaochequeitem->numrows;
	
  if ( $iLinhasCheques > 0 ) {
      
    $clrhemissaocheque->r15_sequencial = $oPost->iCodGeracao;
    $clrhemissaocheque->r15_dtgeracao  = date('Y-m-d',db_getsession('DB_datausu'));
    $clrhemissaocheque->r15_hora       = db_hora();
    $clrhemissaocheque->r15_descricao  = utf8_decode($oPost->descrGera);
    $clrhemissaocheque->r15_idusuario  = db_getsession('DB_id_usuario');
    $clrhemissaocheque->alterar($oPost->iCodGeracao);
      
    if ( $clrhemissaocheque->erro_status == 0 ) {
      $lSqlErro = true;
    }
      
    $sMsgErro = $clrhemissaocheque->erro_msg;
  	
    if ( !$lSqlErro ) {
    	
	    $clrhemissaochequeitem->excluir(null,$sWhere);  	
	
	    if ( $clrhemissaochequeitem->erro_status == 0 ) {
	    	$lSqlErro = true;
	    }
	    
	    $sMsgErro = $clrhemissaochequeitem->erro_msg;
	    
    }  	
    
    if ( !$lSqlErro ) {
    	
	  	for ($iInd=0; $iInd < $iLinhasCheques; $iInd++) {
	  		
	  		$oCheques = db_utils::fieldsMemory($rsConsultaCheques,$iInd);
	  		
	  		$clrhemissaochequeitem->r18_regist        = $oCheques->r18_regist;
	  		$clrhemissaochequeitem->r18_anousu        = $oCheques->r18_anousu;
	  		$clrhemissaochequeitem->r18_mesusu        = $oCheques->r18_mesusu;
	  		$clrhemissaochequeitem->r18_numcgm        = $oCheques->r18_numcgm;
	  		$clrhemissaochequeitem->r18_emissaocheque = $oPost->iCodGeracao;
        $clrhemissaochequeitem->r18_numcheque     = str_pad($iNumCheque,$iTamCampo,"0",STR_PAD_LEFT);	  		
	  		$clrhemissaochequeitem->r18_tipo          = $oTipo->r18_tipo;
	  		$clrhemissaochequeitem->r18_valor         = $oCheques->liquido;
	  		$clrhemissaochequeitem->incluir(null);
	  		
	      if ( $clrhemissaochequeitem->erro_status == 0 ) {
	        $lSqlErro = true;
	        $sMsgErro = $clrhemissaochequeitem->erro_msg;
	        break;
	      }
	        
	      $sMsgErro = $clrhemissaochequeitem->erro_msg;  		
	  		$iNumCheque++;
	  	}
    }
  	
  } else {
  	
    $sMsgErro = "Nenhum registro encontrado!";
    $lSqlErro = true;
  }

  db_fim_transacao($lSqlErro);
  
  if ( $lSqlErro ) {
    $aRetorno = array( "msg" =>urlencode($sMsgErro),
                       "erro"=>true);
  } else {
    $aRetorno = array( "codgeracao"=>$oPost->iCodGeracao,
                       "msg"       =>urlencode($sMsgErro),
                       "erro"      =>false);
  }

  
  echo $oJson->encode($aRetorno);  
} else if ( $oPost->method == "imprimir") {

	require_once('classes/db_cfautent_classe.php');
	require_once('classes/db_db_config_classe.php');
  require_once('model/impressaoCheque.model.php');
  
	$clcfautent  = new cl_cfautent();
	$cldb_config = new cl_db_config();
	$lErro       = false;
	
  if (isset($oPost->aMatriculas) && !empty($oPost->aMatriculas)) {
  	
	  $oDaoRhResponsavelRegist     = db_utils::getDao("rhresponsavelregist");
	  $sWhereRhResponsavelRegist   = "rhresponsavelregist.rh108_regist in({$oPost->aMatriculas}) ";
	  $sWhereRhResponsavelRegist  .= "and rhresponsavelregist.rh108_status is true               "; 
	  $sWhereRhResponsavelRegist  .= "limit 1                                                    "; 
	  $sSqlRhResponsavelRegist     = $oDaoRhResponsavelRegist->sql_query(null, "rhresponsavel.rh107_nome", 
	                                                                     null, $sWhereRhResponsavelRegist);                                                          
	  $rsSqlRhResponsavelRegist    = $oDaoRhResponsavelRegist->sql_record($sSqlRhResponsavelRegist);
	  $iNumRowsRhResponsavelRegist = $oDaoRhResponsavelRegist->numrows;
	  if ($iNumRowsRhResponsavelRegist > 0) {
	    
	    $oRhResponsavelRegist = db_utils::fieldsMemory($rsSqlRhResponsavelRegist, 0);
	    $sNomeResponsavel     = $oRhResponsavelRegist->rh107_nome;
	  }
  }
  
	$sWhereDadosImp       = "     k11_ipterm = '".db_getsession('DB_ip')."'";
	$sWhereDadosImp      .= " and k11_instit = ".db_getsession('DB_instit');
	
	$sCamposImpressora    = " k11_tipoimpcheque, ";
	$sCamposImpressora   .= " k11_portaimpcheque,";
	$sCamposImpressora   .= " k11_ipimpcheque    ";
	
	$sSqlDadosImpressora  = $clcfautent->sql_query_file(null,$sCamposImpressora,null,$sWhereDadosImp);
	$rsDadosImpressora    = $clcfautent->sql_record($sSqlDadosImpressora);
	
	if ( $clcfautent->numrows > 0 ) {
	  
		$oDadosImpressora = db_utils::fieldsMemory($rsDadosImpressora,0);
		
    $oImpressaoCheque = new impressaoCheque($oDadosImpressora->k11_tipoimpcheque);
	  if (isset($sNomeResponsavel) && !empty($sNomeResponsavel)) {
      $oImpressaoCheque->setSCredor($sNomeResponsavel);
    }
    
    $oImpressaoCheque->setIp($oDadosImpressora->k11_ipimpcheque);
    $oImpressaoCheque->setPorta($oDadosImpressora->k11_portaimpcheque);
    
    $sWhereGeracao = "1=1";
    
		if ( isset($oPost->iCodGeracao) && trim($oPost->iCodGeracao) != "" ) {
		  $sWhereGeracao .= "and r18_emissaocheque = {$oPost->iCodGeracao}";
		} else if ( isset($oPost->iChequeIni) || isset($oPost->iChequeFin)) {
      
			if ( isset($oPost->iChequeIni) && trim($oPost->iChequeIni) != "" ) {
			  $sWhereGeracao .= " and r18_sequencial >= {$oPost->iChequeIni}";
			}

		  if ( isset($oPost->iChequeFin) && trim($oPost->iChequeFin) != "" ) {
        $sWhereGeracao .= " and r18_sequencial <= {$oPost->iChequeFin}";
      }			
			
		}
		
		$sCampos  = ' distinct r18_sequencial, ';
		$sCampos .= '          r18_regist,     ';
		$sCampos .= '          r18_mesusu,     ';
		$sCampos .= '          r18_anousu,     ';
		$sCampos .= '          r15_dtgeracao,  ';
		$sCampos .= '          r18_valor,      ';
		$sCampos .= '          z01_nome,       ';
		$sCampos .= '          r70_estrut      ';
		
		$sSqlGeracao    = $clrhemissaochequeitem->sql_query_lota(null,$sCampos,'r18_sequencial',$sWhereGeracao);
		$rsDadosGeracao = $clrhemissaochequeitem->sql_record($sSqlGeracao);
    $iLinhasGeracao = $clrhemissaochequeitem->numrows;
		
		if ( $iLinhasGeracao > 0 ) {
			
			$rsInstit = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession('DB_instit'),"munic"));
      $oInstit  = db_utils::fieldsMemory($rsInstit,0);
			
			for ( $iInd=0; $iInd < $iLinhasGeracao; $iInd++ ) {
			sleep(1);
				$oDadosCheque = db_utils::fieldsMemory($rsDadosGeracao,$iInd);
				
				$sFolha                        = substr(db_mes($oDadosCheque->r18_mesusu,1),0,3)."/".$oDadosCheque->r18_anousu;
        $oImpressaoCheque->sMatricula  = $oDadosCheque->r18_regist;     
        $oImpressaoCheque->sFolha      = $sFolha;
        $oImpressaoCheque->sEstrutural = $oDadosCheque->r70_estrut;          
        $oImpressaoCheque->sCont       = "";   
		    $oImpressaoCheque->setdtDataImpressao("{$oDadosCheque->r15_dtgeracao}//");
		    $oImpressaoCheque->setnValor($oDadosCheque->r18_valor);
		    $oImpressaoCheque->setSCredor($oDadosCheque->z01_nome);
		    $oImpressaoCheque->setSMunicipio($oInstit->munic);
		    $oImpressaoCheque->montaImpressao();
		    $oImpressaoCheque->imprimir();
		    
			}
			
			$sMsgErro = "Emissão concluída com sucesso!";
			
		} else {
	    $lErro    = true;       
	    $sMsgErro = "Nenhum registro encontrado!";			
		}
		
	} else {
    $lErro    = true;    		
		$sMsgErro = "Nenhuma autenticadora encontrada!";
	}
	
  if ( $lErro ) {
    $aRetorno = array( "msg" =>urlencode($sMsgErro),
                       "erro"=>true);
  } else {
    $aRetorno = array( "msg" =>urlencode($sMsgErro),
                       "erro"=>false);
  }

  echo $oJson->encode($aRetorno);
}
?>