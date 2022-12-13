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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_termo_classe.php");
require_once("classes/db_certid_classe.php");
require_once("classes/db_certdiv_classe.php");
require_once("classes/db_certter_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arreforo_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("model/cda.model.php");

db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oCda = new cda(null);

$sSqlPardiv    = " select v04_formgeracda "; 
$sSqlPardiv   .= "   from pardiv          ";
$sSqlPardiv   .= "  where v04_instit = ".db_getsession('DB_instit');
$rsPardiv      = db_query($sSqlPardiv);
$iFormaGeracao = db_utils::fieldsMemory($rsPardiv,0)->v04_formgeracda;

if (isset($oPost->H_DATAUSU)) {
  $sDataVenc = date("Y-m-d",$oPost->H_DATAUSU); 
}

$clcgm      = new cl_cgm;
$cltermo    = new cl_termo;
$clcertid   = new cl_certid;
$clcertdiv  = new cl_certdiv;
$clcertter  = new cl_certter;
$clarrecad  = new cl_arrecad;
$clarreforo = new cl_arreforo;

if ( $k03_tipo != "5" && $k03_tipo != "6") {
  db_redireciona('db_erros.php?fechar=true&db_erro=Você não pode gerar certidão para este tipo de débito!');
  exit; 
}
/*
 * Não sera permitido emitir CDA de apenas algumas parcelas de um numpre
 */
$aPost          = $HTTP_POST_VARS;
$aNumpreParcela = array();
   
foreach ($aPost as $sIndice => $sValue ) {
  if ( substr($sIndice,0,5) == 'CHECK' ) {
    $aNumpreParcela[] = $sValue;      
  }
}
  
$aListaNumpres = array();
  
foreach ($aNumpreParcela as $sDadosNumpre) {
  
  $aNumpreParRec = explode("N",$sDadosNumpre);
  
  foreach ($aNumpreParRec as $sNumpreParcelaReceita) {

  	if (trim($sNumpreParcelaReceita) != '') {
  		
	    list($sNumpre,$sNumparReceita) = explode("P",$sNumpreParcelaReceita);
	    list($sNumpar,$sReceita)       = explode("R",$sNumparReceita);
	    
	    if (!isset($aListaNumpres[$sNumpre])) {
		    $aListaNumpres[$sNumpre][] = $sNumpar;  	
	    } else {
	    	
	    	if (!in_array($sNumpar,$aListaNumpres[$sNumpre])) {
	    		$aListaNumpres[$sNumpre][] = $sNumpar;
	    	}
	    }
  	}
	}
}
  
if (count($aListaNumpres) > 0 ) {
    
  foreach ($aListaNumpres as $sNumpre => $aNumpar ) {

    $sSqlTotalNumpre  = " SELECT *                                                           ";
    $sSqlTotalNumpre .= "   FROM arrecad                                                     "; 
    $sSqlTotalNumpre .= "        INNER JOIN arretipo ON arretipo.k00_tipo = arrecad.k00_tipo ";
    $sSqlTotalNumpre .= "  WHERE k00_numpre = {$sNumpre}                                     "; 
    $sSqlTotalNumpre .= "    AND k00_numpar NOT IN (".implode(",",$aNumpar).")               ";
    $sSqlTotalNumpre .= "    AND k03_tipo   = {$k03_tipo} ;                                  "; 

    $rsTotalNumpre    = db_query($sSqlTotalNumpre);
    $aTotalNumpre     = db_utils::getColectionByRecord($rsTotalNumpre);
    
    if ( count($aTotalNumpre) > 0 ) {
    	
    	$sMensagem  = "Não é possível a geração da CDA parcial de um débito! ";
    	$sMensagem .= "Favor selecionar todas parcelas do numpre {$sNumpre}. ";
    	db_redireciona("db_erros.php?fechar=true&db_erro={$sMensagem}");
      exit;
    }     
  }
}


if(isset($ver_matric)){
  $vt = $HTTP_POST_VARS;
  $tam = sizeof($vt);
  $virgula = "";
  $numpar1 = "";
  $numpre1 = "";
  for($i = 0;$i < $tam;$i++) {
  	
    if(db_indexOf(key($vt),"CHECK") > 0){
    	
      $numpres = $vt[key($vt)];
      $mat = split("N",$numpres);
      
      if (isset($oPost->marcarvencidas) && isset($oPost->marcartodas)) {
          
        if ($oPost->marcarvencidas == 'true' && $oPost->marcartodas == 'false') {
            
          for ($iInd = 0; $iInd < count($mat); $iInd++) {
                
            if ($mat[$iInd] == "") {
              continue;   
            }
                
            $numpre = split("P", $mat[$iInd]);
            $numpar = split("P", strstr($mat[$iInd], "P"));
            $numpar = split("R",$numpar[1]);
            $receit = @$numpar[1];
            $numpar = $numpar[0];
            $numpre = $numpre[0];
              
            $sSqlArrecad  = "  select *                               ";
            $sSqlArrecad .= "    from arrecad                         "; 
            $sSqlArrecad .= "   where k00_numpre   = {$numpre}        "; 
            $sSqlArrecad .= "     and k00_numpar   = {$numpar}        ";
            $sSqlArrecad .= "     and k00_dtvenc   > '{$sDataVenc}'   ";
              
            $rsSqlArrecad = db_query($sSqlArrecad);
            $iNumRows     = pg_num_rows($rsSqlArrecad);
            if ($iNumRows == 0) {
                  
              $numpar1 .= $virgula.$numpar;
              $numpre1 .= $virgula.$numpre;
              $virgula = ",";
            }
            
          }
        } else {
            
	        for($j = 0;$j < count($mat);$j++) {
	          
	          if ($mat[$j] == "") {
	            continue;
	          }
	          $numpre = split("P",$mat[$j]);
	          $numpar = split("P",strstr($mat[$j],"P"));
	          $numpar = split("R",$numpar[1]);
	          $numpar = $numpar[0];
	          $numpre = $numpre[0];
	          $numpar1 .= $virgula.$numpar;
	          $numpre1 .= $virgula.$numpre;
	          $virgula = ",";
	        }
        }
      } else {
          
	      for($j = 0;$j < count($mat);$j++) {
	        
	        if ($mat[$j] == "") {
	          continue;
	        }
	        $numpre = split("P",$mat[$j]);
	        $numpar = split("P",strstr($mat[$j],"P"));
	        $numpar = split("R",$numpar[1]);
	        $numpar = $numpar[0];
	        $numpre = $numpre[0];
	        $numpar1 .= $virgula.$numpar;
	        $numpre1 .= $virgula.$numpre;
	        $virgula = ",";
	      }
      }
    }
    next($vt);
  }
}

$lSqlErro = false;
$mat  = split(",",$numpre1);
$mat1 = split(",",$numpar1);
$aListaCertidao = array();

if ($iFormaGeracao == 2 && $k03_tipo == 5) {
	

	$iNroRows     = count($mat);
	$aWhereAgrupa = array();
	
	for ( $iInd=0; $iInd < $iNroRows; $iInd++ ) {
	  
	  $iNumpre = $mat[$iInd]; 
	  $iNumpar = $mat1[$iInd]; 
	
	  $sWhereAgrupa   = "(    v01_numpre = {$iNumpre}  ";
	  $sWhereAgrupa  .= " and v01_numpar = {$iNumpar} )";
	  $aWhereAgrupa[] = $sWhereAgrupa; 
	  
	}
	
	$sWhereAgrupa = implode(" or ",$aWhereAgrupa);
	
	$sSqlDivida = " select distinct
	                       case 
	                          when k00_matric is not null then 'matric'||k00_matric
	                          when k00_inscr  is not null then  'inscr'||k00_inscr
	                          else 'cgm'||k00_numcgm 
	                       end as origem,   
	                       v01_exerc,
	                       v01_proced,
	                       v01_numpre,
	                       v01_numpar
	                  from divida
	                       left join arreinscr  on arreinscr.k00_numpre  = divida.v01_numpre
	                       left join arrematric on arrematric.k00_numpre = divida.v01_numpre
	                       left join arrenumcgm on arrenumcgm.k00_numpre = divida.v01_numpre 
	                 where {$sWhereAgrupa}";
	
	$rsDivida      = db_query($sSqlDivida);
	$aDadosDivida  = db_utils::getColectionByRecord($rsDivida);
	$aAgrupaDivida = array();
	
	foreach ( $aDadosDivida as $oDivida ) {
	
	  $oDadosDebito = new stdClass();
	  $oDadosDebito->iNumpre = $oDivida->v01_numpre;
	  $oDadosDebito->iNumpar = $oDivida->v01_numpar; 
	  $aAgrupaDivida[$oDivida->origem][$oDivida->v01_exerc][$oDivida->v01_proced][] = $oDadosDebito;
	}
	
	db_inicio_transacao();

	try {
	  foreach ($aAgrupaDivida as $iCodOrigem => $aDadosOrigem)  {
	    foreach ($aDadosOrigem as $iExerc     => $aDadosExerc )  {
	      foreach ($aDadosExerc as $iProced    => $aDadosDivida)  {
	        $aListaCertidao[] = $oCda->geraLoteCertidao($k03_tipo,$aDadosDivida,$ver_matric);
	      }
	    }
	  } 
	} catch (Exception $eException) {
	  $lSqlErro = true;
	  $sMsgErro = $eException->getMessage();
	}
	
	
	db_fim_transacao($lSqlErro);	
	
	
} else {

  $iNroRows      = count($mat);
  $aListaDebitos = array();
  
  for ( $iInd=0; $iInd < $iNroRows; $iInd++ ) {
    
    $iNumpre = $mat[$iInd]; 
    $iNumpar = $mat1[$iInd]; 

    $oDadosDebito = new stdClass();
    $oDadosDebito->iNumpre = $iNumpre;
    $oDadosDebito->iNumpar = $iNumpar; 
    
    $aListaDebitos[] = $oDadosDebito;
    
  }  
	
	db_inicio_transacao();
	
	try {
    $aListaCertidao[] = $oCda->geraLoteCertidao($k03_tipo,$aListaDebitos,$ver_matric);
	} catch (Exception $eException) {
    $lSqlErro = true;
    $sMsgErro = $eException->getMessage();		
	}
	
	db_fim_transacao($lSqlErro);
	
} 
  
$sSqlOrdemCDA  = " select max(v04_ordemendcda) as v04_ordemendcda "; 
$sSqlOrdemCDA .= "   from pardiv                                  ";
$rsOrdemCDA    = db_query($sSqlOrdemCDA);
$iOrdemCDA     = db_utils::fieldsMemory($rsOrdemCDA,0)->v04_ordemendcda;
        
if ($iOrdemCDA == 2) {
  $ordemend = "c";
} else {
  $ordemend = "o";       
}

if ( $lSqlErro ) {
  echo "<b>Erro do gerar CDA! Contate suporte! sql: $sMsgErro </b>";
} else {
	
	if ( count($aListaCertidao) > 1 ) {
		echo "<b>CERTIDÕES DE DÍVIDA ".implode(",",$aListaCertidao)." GERADAS COM SUCESSO!</b>";
	} else {
	  echo "<b>CERTIDÃO DE DÍVIDA ".$aListaCertidao[0]." GERADA COM SUCESSO!</b>";
	}
	
	$iCertidInicial = $aListaCertidao[0]; 
	$iCertidFinal   = $aListaCertidao[(count($aListaCertidao)-1)];
	
}

?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>parent.document.getElementById('processando').style.visibility = 'hidden';</script>
<script>
function js_emite(){
  <?
  if($k03_tipo == 5){
    ?>
    window.open('div2_certidaodivida002.php?tipo=2&certid=<?=$iCertidInicial?>&certid1=<?=$iCertidFinal?>&reemissao=t&valormaximo=99999999999&valorminimo=0&datacertidao=&ordenarpor=v14_certid&totexe=t&endaimp=<?=$ordemend?>','','width=790,height=530,scrollbars=1,location=0');
    <?
  }elseif($k03_tipo == 6){
    ?>
    window.open('div2_certidaodivida002.php?tipo=1&certid=<?=$iCertidInicial?>&certid1=<?=$iCertidFinal?>&reemissao=t','','width=790,height=530,scrollbars=1,location=0');
    <?
  } 
  ?>
  parent.document.getElementById('pesquisar').click()
}
</script>
<input type='button' value='OK' onClick="js_emite();">