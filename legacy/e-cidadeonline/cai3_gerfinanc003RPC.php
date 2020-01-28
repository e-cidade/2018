<?
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

include("fpdf151/impcarne.php");
require("libs/db_barras.php");
require("libs/db_stdlib.php");
include ("libs/db_utils.php");
include ("classes/db_db_bancos_classe.php");
//require_once ("classes/db_configdbpref_classe.php");
include ("model/regraEmissao.model.php");
include ("model/convenio.model.php");
include("libs/JSON.php");

$cldb_bancos    = new cl_db_bancos;
$clconfigdbpref = new cl_configdbpref();

$oJson             = new services_json();
$oParam            = @$oJson->decode(str_replace("\\","",$_POST));
$oRetorno          = new stdClass;

$inner = "arrematric ";
$campoinner = "k00_matric = $matric";
$instit = db_getsession('DB_instit');

db_postmemory($_POST);
   
$tipo_debito = $tipo;

/**
 *  Verifica se a variável $inicial está setada. Se estiver, busca os numpres
 *  e numpar para as iniciais e faz um for para montar a variável que é utilizada
 *  durante o processo.
 */
if (isset($inicial)) {

  $sSqlNumpresInicial  = " select distinct     ";
  $sSqlNumpresInicial .= "        k00_numpre,  ";
  $sSqlNumpresInicial .= "        k00_numpar   ";
  $sSqlNumpresInicial .= "  from inicialnumpre ";
  $sSqlNumpresInicial .= "       inner join arrecad on arrecad.k00_numpre = inicialnumpre.v59_numpre "; 
  $sSqlNumpresInicial .= " where v59_inicial in (".$num_pres.")                                     ";
  
  $num_pres = "";
  $rsSqlNumpresInicial   = db_query($sSqlNumpresInicial);
  $iLinhasNumpresInicial = pg_num_rows($rsSqlNumpresInicial);
  
  if ($iLinhasNumpresInicial > 0) {
  
    $sVirgula = "";
    for ($iRow = 0; $iRow < $iLinhasNumpresInicial; $iRow++) {
    
      $oDadosNumpre = db_utils::fieldsMemory($rsSqlNumpresInicial, $iRow);
      
      $sNumpre  = "N".$oDadosNumpre->k00_numpre;
      $sNumpar  = "P".$oDadosNumpre->k00_numpar;
      $sReceita = "R0";

      $num_pres .= $sVirgula.$sNumpre.$sNumpar.$sReceita;
      $sVirgula  = ",";
    }
  } 
}

$num_pres		= explode(',',$num_pres);

if(!isset($emite_recibo_protocolo)){
  db_query("BEGIN");

  $result = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
  db_fieldsmemory($result,0);
  
 $result = db_query("select k00_codbco,k00_codage,k00_descr,k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,k00_hist6,k00_hist7,k00_hist8,k03_tipo,k00_tipoagrup from arretipo where k00_tipo = $tipo");
    
  if(pg_numrows($result)==0){
    echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo.";
    exit;
  }
  db_fieldsmemory($result,0);
  
  $k00_descr = $k00_descr;
  $historico = $k00_descr;
    
  //$vt = $HTTP_POST_VARS;
  $desconto = 0;
  
  if(isset($inicial)) {
    $tipo_debito=18;
  }
  
  if(!isset($numpre_unica) || $numpre_unica == "") {
    
    $tam         = sizeof($num_pres);
    $numpres     = "";
    $numprestemp = array();
    $meses       = array();
    $arretipos   = array();
    
    for ($i = 0;$i < $tam; $i++) {

      $matnumpres = split('N',$num_pres[$i]);
        
//      echo "<pre>".var_dump($matnumpres). "</pre>";
        //die("aqui");
        for ($contanumpres = 0; $contanumpres < sizeof($matnumpres); $contanumpres++) {
          
        $numprecerto = $matnumpres[$contanumpres];

          if ($matnumpres[$contanumpres] == "") {
            continue;
          }
            
          $resultado = split("P",$numprecerto);
          $numpar    = split("P",$resultado[1]);
          $numpar    = split("R",$numpar[0]);
            
          $sqlagrupa = "  select distinct 
                                 k00_descr as descrarretipo, 
                                 extract (months from k00_dtvenc) as mesagrupa, 
                                 extract (year from k00_dtvenc)   as anoagrupa 
                            from arrecad 
                                 inner join arretipo              on arrecad.k00_tipo = arretipo.k00_tipo 
                                 left  join configdbprefarretipo  on w17_arretipo     = arrecad.k00_tipo
                                                                 and w17_instit       = ".db_getsession("DB_instit")."
                           where k00_numpre = " . $resultado[0] . " 
                             and k00_numpar = " . $numpar[0] ."
                             and case 
                                   when w17_sequencial is null then true
                                   else arrecad.k00_dtvenc between w17_dtini and w17_dtfim
               		               end  ";

          $resultagrupa = db_query($sqlagrupa) or die($sqlagrupa);

          if (pg_numrows($resultagrupa) > 0) {

          	$numprestemp[]  = "N" . $num_pres[$i];
          	$numpres       .= "N" . $num_pres[$i];
            db_fieldsmemory($resultagrupa,0);

            if (!in_array(str_pad($mesagrupa,2,"0") . $anoagrupa, $meses)) {
              $meses[] = str_pad($mesagrupa,2,"0",STR_PAD_LEFT) . $anoagrupa;
            }
            
            if (!in_array($descrarretipo, $arretipos)) {
              $arretipos[] = $descrarretipo;
            }
          }
        }
    }

    if(!empty($ver_matric)) {
      $inner = "arrematric ";
      $campoinner = "k00_matric = $ver_matric";
    } elseif (!empty($ver_inscr)) {
      $inner = "arreinscr ";
      $campoinner = "k00_inscr = $ver_inscr";
    } elseif (!empty($ver_numcgm)) {
      $inner = "arrenumcgm ";
      $campoinner = "k00_numcgm = $ver_numcgm";
    }
    
    $numpre_temp1 = "";
    if ($k00_tipoagrup == 2 || $k00_tipoagrup == 1 ) {
   
       
      for ($mes=0; $mes < sizeof($meses); $mes++) {

        $sqlagrupa = "
          select distinct
                 arrecad.k00_numpre as numpreagrupa,
                 arrecad.k00_numpar as numparagrupa,
                 (case 
                 	when w17_sequencial is null then
                 		's'
                 	when arrecad.k00_dtvenc between w17_dtini and w17_dtfim then
                 		's'
                 	else  
                 	  'n'
                 	end) as imprime
                 	
            from (select {$inner}.*
                    from {$inner}
                         inner join arreinstit    on arreinstit.k00_numpre = {$inner}.k00_numpre
                                                 and arreinstit.k00_instit = ".db_getsession("DB_instit")."
                   where {$inner}.{$campoinner}) as {$inner}

                 inner join arrecad  on arrecad.k00_numpre =  {$inner}.k00_numpre
                                    and arrecad.k00_tipo   <> {$tipo_debito}
                                    and extract (months from arrecad.k00_dtvenc) = " . substr($meses[$mes],0,2) . "
                                    and extract (years  from arrecad.k00_dtvenc) = " . substr($meses[$mes],2,4) . "
                                    
                 left  join configdbprefarretipo  on w17_arretipo = arrecad.k00_tipo
                                                 and w17_instit   = ".db_getsession("DB_instit")."
                                               
           where not exists (select arrenaoagrupa.k00_numpre
                               from arrenaoagrupa
                              where arrenaoagrupa.k00_numpre = {$inner}.k00_numpre) ";

       //die($sqlagrupa);
        $resultagrupa = db_query($sqlagrupa);
       // echo pg_numrows($resultagrupa);
      $numpres_temp = "";
        for ($agrupa=0; $agrupa<pg_numrows($resultagrupa);$agrupa++) {
          db_fieldsmemory($resultagrupa,$agrupa);

          if($imprime == 'n'){

            unset ($numprestemp[$mes]);
          	$numpres_temp = "";
          	break;
          }else{
          	$numpres_temp .= "N" . $numpreagrupa . "P" . $numparagrupa;
          }
          //$numpres .= "N" . $numpreagrupa . "P" . $numparagrupa;
        }
        if($numpres_temp != ""){
        	$numpre_temp1 .= $numpres_temp;
        }
      }
      
      $numpres = "";
    	foreach ($numprestemp as $value){
    		$numpres .= $value;
    	}
    	$numpres .= $numpre_temp1;
    }

   if($numpres == ""){
   	$oRetorno->emissao = 1;
   }else{
   	$oRetorno->emissao = 0;
   }
    
  $rs_agrupadebitos = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file($instit,"w13_agrupadebrecibos"));
  if($clconfigdbpref->numrows > 0){
  	db_fieldsmemory($rs_agrupadebitos,0);
  } else { 
  	$w13_agrupadebrecibos = 'f'; 
  }  
  
  $oRetorno->debitos = 0;
  
  if ($w13_agrupadebrecibos == 't'){
  	 
  		$dDataVenc = $db_datausu;
            
  		$sqltemptable = "create temp table w_agrupa_agua as select {$inner}.*
                    from {$inner}
                         inner join arreinstit    on arreinstit.k00_numpre = {$inner}.k00_numpre
                                                 and arreinstit.k00_instit = ".db_getsession("DB_instit")."
                   where {$inner}.{$campoinner}";
      $resulttemptable = db_query($sqltemptable) or die($sqltemptable);

      $sqlindextemptable = "create index w_agrupa_agua_numpre_in on w_agrupa_agua(k00_numpre)";
  		$resultindextemptable = db_query($sqlindextemptable ) or die($sqlindextemptable );
  		
      $sqlagrupa = "
          select distinct
                 arrecad.k00_numpre as numpreagrupa,
                 arrecad.k00_numpar as numparagrupa
            from (select * from w_agrupa_agua) as {$inner}

                 inner join arrecad  on arrecad.k00_numpre =  {$inner}.k00_numpre
                                    and arrecad.k00_dtvenc < fc_calculavenci('{$dDataVenc}') ";
            
      //die($sqlagrupa);
      $resultagrupa = db_query($sqlagrupa);

	if(pg_numrows($resultagrupa)>0){
      	$oRetorno->debitos = 1;
      }else{
      	$oRetorno->debitos = 0;
      }
      
      db_query("ROLLBACK");

  }
      
  }

}
echo $oJson->encode($oRetorno);
exit();
?>