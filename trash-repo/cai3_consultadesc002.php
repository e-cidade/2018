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

global $HTTP_SESSION_VARS;
$DB_DATACALC = $HTTP_SESSION_VARS["DB_datausu"];
include("fpdf151/scpdf.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$sql = "select  arrecad.k00_numpre,
		arrecad.k00_numpar,
		arrecad.k00_numcgm,
		arrecad.k00_tipo
	  from termo 
	       inner join arrecad on arrecad.k00_numpre = termo.v07_numpre 
	  where v07_parcel = $parcel 
	  group by arrecad.k00_numcgm,arrecad.k00_numpre, arrecad.k00_numpar, arrecad.k00_tipo 
	  ";
$Res = pg_exec($sql);
if(pg_numrows($Res)==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Valores Quitados.');
  exit;
}
$ene="";
$HTTP_POST_VARS["CHECK"] = "";
for($xx=0;$xx<pg_numrows($Res);$xx++){
  $HTTP_POST_VARS["CHECK"] = $HTTP_POST_VARS["CHECK"].$ene.pg_result($Res,$xx,0)."P".pg_result($Res,$xx,1);
  $ene="N";
}
$tipo = pg_result($Res,0,3);
$HTTP_POST_VARS["ver_numcgm"]= pg_result($Res,0,2);


include("fpdf151/impcarne.php");
require("libs/db_barras.php");

$matricularecibo = @$j01_matric;
$inscricaorecibo = @$q02_inscr;
$numcgmrecibo    = @$z01_numcgm;
db_postmemory($HTTP_POST_VARS);
$tipoidentificacao = 0;
if(!isset($emite_recibo_protocolo)){
  pg_exec("BEGIN");
  //gera um nuvo numpre. "numnov"
  //pg_exec("update numpref set k03_numpre = k03_numpre + 1 where k03_anousu = ".db_getsession("DB_anousu"));
  //$result = pg_exec("select k03_numpre as k03_numpre from numpref where k03_anousu = ".db_getsession("DB_anousu"));
  //db_fieldsmemory($result,0);
  $result = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numpre");
  db_fieldsmemory($result,0);

  //pega os numpres da ca3_gerfinanc002.php, separa e insere em db_reciboweb
  $result = pg_exec("select k00_codbco,k00_codage,k00_descr,k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,k00_hist6,k00_hist7,k00_hist8,k03_tipo from arretipo where k00_tipo = $tipo");
    
  if(pg_numrows($result)==0){
    echo "O código do banco não esta cadastrado no arquivo arretipo para este tipo.";
    exit;
  }
  db_fieldsmemory($result,0);

  $k00_descr = $k00_descr;

  $result = pg_exec("select fc_numbco($k00_codbco,'$k00_codage')");
  db_fieldsmemory($result,0);

  $vt = $HTTP_POST_VARS;
  if(!isset($numpre_unica) || $numpre_unica ==""){

    $tam = sizeof($vt);
    reset($vt);
    $numpres = "";
    for($i = 0;$i < $tam;$i++) {
      if(db_indexOf(key($vt) ,"CHECK") > 0){
        $numpres .= "N".$vt[key($vt)];  
      }
      next($vt);
    }
    $numpres = split("N",$numpres);
    for($i = 1;$i < sizeof($numpres);$i++) {
    //  $numpres[$i] = base64_decode($numpres[$i]);
    //  $numpres[$i];

      $valores = split("P",$numpres[$i]);  
      $sql = "insert into db_reciboweb (k99_numpre,k99_numpar,k99_numpre_n,k99_codbco,k99_codage,k99_numbco,k99_desconto,k99_tipo,k99_origem)
                                values (".$valores[0].",".$valores[1].",$k03_numpre,$k00_codbco,'$k00_codage','$fc_numbco',0,1,1)";
      pg_exec($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage()); 
      
    }

  }else{

      $sql = "insert into db_reciboweb (k99_numpre,k99_numpar,k99_numpre_n,k99_codbco,k99_codage,k99_numbco,k99_desconto,k99_tipo,k99_origem)
                                values (".$numpre_unica.",0,$k03_numpre,$k00_codbco,'$k00_codage','$fc_numbco',0,1,1)";
      pg_exec($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage()); 
    
      
  }

  //roda funcao fc_recibo pra gerar o recibo
  $sql = "select fc_recibo($k03_numpre,'".date("Y-m-d",db_getsession("DB_datausu"))."','".db_vencimento()."',".db_getsession("DB_anousu").")";
  $Recibo = pg_exec($sql);
      // to aqui
      $sql = "select sum(k00_valor) from recibopaga where k00_numnov = $k03_numpre";
      $Res = pg_exec($sql) or die("Erro(27) calculo do novo recibo -> ".pg_errormessage()); 

     $calc_p = pg_result($Res,0,0);
     //echo "\n".$valortot."\n";  
      $perc_p =  (($valortot*100)/$calc_p)/100; 

      $sql = "update recibopaga set k00_valor = round(k00_valor * $perc_p,2)::float8 where k00_numnov = $k03_numpre";
      $Res = pg_exec($sql) or die("Erro(27) calculo do novo recibo -> ".pg_errormessage()); 

 
      $sql = "select sum(k00_valor) from recibopaga where k00_numnov = $k03_numpre ";
      $Res = pg_exec($sql) or die("Erro(27) calculo do novo recibo -> ".pg_errormessage()); 
      
      
      $calc_p = pg_result($Res,0,0);

      $sql = "select oid,k00_valor as meucalc from recibopaga where k00_numnov = $k03_numpre limit 1 ";
      $Res = pg_exec($sql) or die("Erro(27) calculo do novo recibo -> ".pg_errormessage()); 
      
 
      $calc_oid = pg_result($Res,0,0);
      $meucalc = pg_result($Res,0,1);
      

      $calc_p = $meucalc - ( $calc_p - $valortot);
      $sql = "update recibopaga set k00_valor = round($calc_p,2)::float8 where oid = $calc_oid";
      $Res = pg_exec($sql) or die("Erro(27) calculo do novo recibo -> ".pg_errormessage()); 
 
      
  
}else{

db_postmemory($HTTP_SERVER_VARS);

if(isset($db_datausu)){
  if(!checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))){
     echo "Data para Cálculo Inválida. <br><br>";
     echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
	 exit;
  }
  if(mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) < 
     mktime(0,0,0,date('m',db_getsession("DB_datausu")),date('d',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu"))) ){
     echo "Data não permitida para cálculo. <br><br>";
     echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
	 exit;
  }
  $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
}else{
  $DB_DATACALC = db_getsession("DB_datausu");
}

$k00_descr = $k00_histtxt;

}
  /*
  if(pg_result($Recibo,0,0) == "2") {
    pg_exec("ROLLBACK");
    echo "<script>opener.document.getElementById(\"enviar\").disabled = true; opener.debitos.location.href='cai3_gerfinanc007.php?erro1=1';window.close();</script>";
    exit;
  } else*/
    pg_exec("COMMIT");
  //seleciona os valores gerado pela funcao fc_recibo

if(!isset($emite_recibo_protocolo)){
  $sql = "select r.k00_numcgm,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_dtoper as k00_dtoper,sum(r.k00_valor) as valor
                   from recibopaga r
                   inner join tabrec t on t.k02_codigo = r.k00_receit 
                   inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
		   where r.k00_numnov = ".$k03_numpre."
                   group by r.k00_dtoper,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_numcgm";
}else{
  $sql = "select r.k00_numcgm,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_dtoper as k00_dtoper,sum(r.k00_valor) as valor
              from recibo r
                   inner join tabrec t on t.k02_codigo = r.k00_receit 
                   inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
		      where r.k00_numpre = ".$k03_numpre."
              group by r.k00_dtoper,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_numcgm";
}

$DadosPagamento = pg_exec($sql);

//faz um somatorio do valor
$datavencimento = pg_result($DadosPagamento,0,"k00_dtoper");
$total_recibo = 0;
for($i = 0;$i < pg_numrows($DadosPagamento);$i++) {
  $total_recibo += pg_result($DadosPagamento,$i,"valor");
}

//seleciona da tabela db_config, o numero do banco e a taxa bancaria e concatena em variavel
$DadosInstit = pg_exec("select nomeinst,ender,munic,email,telef,uf,logo,to_char(tx_banc,'9.99') as tx_banc,numbanco from db_config where codigo = ".db_getsession("DB_instit"));
//cria codigo de barras e linha digitável
$NumBanco = pg_result($DadosInstit,0,"numbanco");
$taxabancaria = pg_result($DadosInstit,0,"tx_banc");
$src = pg_result($DadosInstit,0,'logo');
$db_nomeinst = pg_result($DadosInstit,0,'nomeinst');
$db_ender    = pg_result($DadosInstit,0,'ender');
$db_munic    = pg_result($DadosInstit,0,'munic');
$db_uf       = pg_result($DadosInstit,0,'uf');
$db_telef    = pg_result($DadosInstit,0,'telef');
$db_email    = pg_result($DadosInstit,0,'email');

$total_recibo += $taxabancaria;
if ( $total_recibo == 0 ){
   db_redireciona('db_erros.php?fechar=true&db_erro=O Recibo Com Valor Zerado.');
}
$valor_parm = $total_recibo; 
//$dtvenc = str_replace('-','',db_vencimento());
/*
db_barras($k00_codbco,9,$total_recibo,"$fc_numbco",'8200','0461','00600000037',$datavencimento);
*/
//seleciona dados de identificacao. Verifica se é inscr ou matric e da o respectivo select
//essa variavel vem do cai3_gerfinanc002.php, pelo window open, criada por parse_str
if(!empty($HTTP_POST_VARS["ver_matric"]) || $matricularecibo > 0 ) {
  $numero = @$HTTP_POST_VARS["ver_matric"] + $matricularecibo;
  $tipoidentificacao = "Matricula :";
  $Identificacao = pg_exec("select z01_nome,z01_ender,z01_numero,z01_compl,z01_munic,z01_uf,z01_cep,nomepri,j39_compl,j39_numero,j13_descr,j34_setor||'.'||j34_quadra||'.'||j34_lote as sql
                     from proprietario
					 where j01_matric = $numero limit 1");
  db_fieldsmemory($Identificacao,0);

} else if(!empty($HTTP_POST_VARS["ver_inscr"]) || $inscricaorecibo > 0 ) {
  $numero = @$HTTP_POST_VARS["ver_inscr"] + $inscricaorecibo;
  $tipoidentificacao = "Inscricao :";
  $Identificacao = pg_exec("select z01_nome,
                                   z01_ender,
				   z01_numero,
				   z01_compl,
				   z01_munic,
				   z01_uf,
				   z01_cep,
				   z01_ender as nomepri,
				   z01_compl as j39_compl,
				   z01_numero as j39_numero,
				   z01_bairro as j13_descr, 
				   '' as sql  
                            from empresa
							where q02_inscr = $numero");

/*

                     select cgm.z01_nome,cgm.z01_ender,cgm.z01_munic,cgm.z01_uf,cgm.z01_cep,c.j14_nome as nomepri,i.q02_compl as j39_compl,i.q02_numero as j39_numero,j13_descr, '' as sql  
                     from cgm
					 inner join issbase i 
					     on i.q02_numcgm = cgm.z01_numcgm
					 left outer join ruas c 
					     on c.j14_codigo = i.q02_lograd 
					 left outer join bairro b 
					    on b.j13_codi = i.q02_bairro 
					 where i.q02_inscr = ".$HTTP_POST_VARS["ver_inscr"]);
*/
  db_fieldsmemory($Identificacao,0);
}else if(!empty($HTTP_POST_VARS["ver_numcgm"]) || $numcgmrecibo > 0 ) {
  $numero = @$HTTP_POST_VARS["ver_numcgm"] + $numcgmrecibo ;
  $tipoidentificacao = "Numcgm :";
  $Identificacao = pg_exec("select z01_nome,z01_ender,z01_numero,z01_compl,z01_munic,z01_uf,z01_cep,''::bpchar as nomepri,''::bpchar as j39_compl,''::bpchar as j39_numero,z01_bairro as j13_descr, '' as sql 
                     from cgm
					 where z01_numcgm = $numero ");
  db_fieldsmemory($Identificacao,0);
} else {
  if(isset($emite_recibo_protocolo)){
   $Identificacao = pg_exec("
            select c.z01_nome,c.z01_ender,c.z01_numero,c.z01_compl,c.z01_munic,c.z01_uf,c.z01_cep,' ' as nomepri,' ' as j39_compl, ' ' as j39_numero, ' ' as j13_descr, '' as sql
            from recibo r
                 inner join cgm c on c.z01_numcgm = r.k00_numcgm
   		    where r.k00_numpre = ".$k03_numpre."
            limit 1");
    db_fieldsmemory($Identificacao,0);
  }
}


if(isset($tipo_debito)){

  $resulttipo = pg_exec("select k03_tipo from arretipo where k00_tipo = $tipo_debito");
  db_fieldsmemory($resulttipo,0);

  if($k03_tipo==5 ){
    $histparcela = "Divida: ";
    $sqlhist = "select distinct v01_exerc,v01_numpar
	        from db_reciboweb
			     left outer join divida on v01_numpre = k99_numpre and v01_numpar = k99_numpar
	        where k99_numpre_n = $k03_numpre 
			group by v01_exerc,v01_numpar
			order by v01_exerc,v01_numpar";
    $result = pg_query($sqlhist);
	if(pg_numrows($result)!=false){
	  $exercv = "0000";
  	  for($xy=0;$xy<pg_numrows($result);$xy++){
	    if( $exercv != pg_result($result,$xy,0)){
	       $exercv = pg_result($result,$xy,0);
           $histparcela .= pg_result($result,$xy,0).":";
		}
        $histparcela .= pg_result($result,$xy,1)."-";
	  }	
    }
  }else if($k03_tipo == 3 || $k03_tipo == 2){
    $histparcela = "Exercicio: ";
    $sqlhist = "select distinct q05_ano,q05_numpar
	        from db_reciboweb
			     left outer join issvar on q05_numpre = k99_numpre and q05_numpar = k99_numpar
	        where k99_numpre_n = $k03_numpre 
			group by q05_ano,q05_numpar
			order by q05_ano,q05_numpar";
    $result = pg_exec($sqlhist);
	if(pg_numrows($result)!=false){
	  $exercv = "0000";
  	  for($xy=0;$xy<pg_numrows($result);$xy++){
	    if( $exercv != pg_result($result,$xy,0)){
	       $exercv = pg_result($result,$xy,0);
           $histparcela .= "  ".pg_result($result,$xy,0).": Parc:";
		}
        $histparcela .= "-".pg_result($result,$xy,1);
	  }	
    }
  }else if($k03_tipo==6 ){
    $histparcela = '';
    $parcelamento = '';
    $sqlhist = "select v07_parcel,k99_numpar
	        from db_reciboweb
			     left outer join termo on v07_numpre = k99_numpre
	        where k99_numpre_n = $k03_numpre 
			order by v07_parcel,k99_numpar";
    $result = pg_query($sqlhist);
    if(pg_numrows($result)!=false){
 //         $histparcela = "Parcelamento: ".pg_result($result,0,0)." Parc:";
  	for($xy=0;$xy<pg_numrows($result);$xy++){
	    if (pg_result($result,$xy,0) != $parcelamento){
	       $histparcela .= ' Parc : '.pg_result($result,$xy,0)." - ";
	    }
            $histparcela .= pg_result($result,$xy,1)." ";
	    $parcelamento = pg_result($result,$xy,0);
	}	
    }
  }else{
    $histparcela = "PARCELAS: ";
    $sqlhist = "select k99_numpar
	        from db_reciboweb
	        where k99_numpre_n = $k03_numpre order by k99_numpar";
    $result = pg_query($sqlhist);
	for($xy=0;$xy<pg_numrows($result);$xy++){
       $histparcela .= pg_result($result,$xy,0)." ";	   
	}	
  }
}


//select pras observacoes
$Observacoes = pg_exec($conn,"select mens,alinhamento from db_confmensagem where cod in('obsboleto1','obsboleto2','obsboleto3','obsboleto4')");
/*
$parametros .= "&obs1=".pg_result($result,0,0);
$parametros .= "&".pg_result($result,0,1);
$parametros .= "&obs2=".pg_result($result,1,0);
$parametros .= "&".pg_result($result,1,1);
$parametros .= "&obs3=".pg_result($result,2,0);
$parametros .= "&".pg_result($result,2,1);
$parametros .= "&obs4=".pg_result($result,3,0);
$parametros .= "&".pg_result($result,3,1);
*/
$db_vlrbar = db_formatar(str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
//$db_vlrbar = "0".str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT));
//if($total_recibo>999)
// $db_vlrbar = "0".$db_vlrbar;
//if($total_recibo>9999)
// $db_vlrbar = "0".$db_vlrbar;
//if($total_recibo>99999)
// $db_vlrbar = "0".$db_vlrbar;
//if($total_recibo>999999)
// $db_vlrbar = "0".$db_vlrbar;

//$db_numbanco = "4268" ;// deve ser tirado do db_config
$resultnumbco = pg_exec("select numbanco, segmento, formvencfebraban from db_config where codigo = " . db_getsession("DB_instit"));
db_fieldsmemory($resultnumbco,0) ;// deve ser tirado do db_config

$db_numpre = db_numpre($k03_numpre).'000'; //db_formatar(0,'s',3,'e');

if ($formvencfebraban == 1) {
  $db_dtvenc = str_replace("-","",$datavencimento);
  $vencbar = $db_dtvenc . '000000';
} elseif ($formvencfebraban == 2) {
  $db_dtvenc = str_replace("-","",$datavencimento);
  $db_dtvenc = substr($db_dtvenc,6,2) . substr($db_dtvenc,4,2) . substr($db_dtvenc,2,2);
  $vencbar = $db_dtvenc . '00000000';
}

$inibar="8" . $segmento . "6";
$resultcod = pg_exec("select fc_febraban('$inibar'||'$db_vlrbar'||'".$numbanco."'||'".$vencbar."'||'$db_numpre')");
db_fieldsmemory($resultcod,0);

  if ($fc_febraban == "") {
    db_msgbox("Erro ao gerar codigo de barras (3)!");
    exit;
  }


$codigo_barra = split(",",$fc_febraban);
$codigobarras = $codigo_barra[0];
$linhadigitavel = $codigo_barra[1];

$datavencimento = db_formatar($datavencimento,"d");

$result = pg_exec("select k15_local,k15_aceite,k15_carte,k15_espec,k15_ageced
				   from cadban
                   where k15_codbco = $k00_codbco and
				   k15_codage = '$k00_codage'");
$idenpar = "";
if(pg_numrows($result) > 0) {				   
  $idenpar  = "k15_local= ".pg_result($result,0,0);
  $idenpar .= "&k15_aceite=".pg_result($result,0,1);
  $idenpar .= "&k15_carte=".pg_result($result,0,2);
  $idenpar .= "&k15_espec=".pg_result($result,0,3);
  $idenpar .= "&k15_ageced=".pg_result($result,0,4);
  $idenpar .= "&fc_numbco=".$fc_numbco;
  $idenpar .= "&dt_hoje=".date('d/m/Y',$DB_DATACALC);
  $idenpar .= "&k00_hist1= ".$k00_hist1;
  $idenpar .= "&k00_hist2= ".$k00_hist2;
  $idenpar .= "&k00_hist3= ".$k00_hist3;
  $idenpar .= "&k00_hist4= ".$k00_hist4;
  $idenpar .= "&k00_hist5= ".$k00_hist5;
  $idenpar .= "&k00_hist6= ".$k00_hist6;
  $idenpar .= "&k00_hist7= ".$k00_hist7;
  $idenpar .= "&k00_hist8= ".$k00_hist8;
  $idenpar .= "&k00_codbco=".$k00_codbco;
  $idenpar .= "&linhadigitavel=".$linhadigitavel;
}
//$result = pg_exec("select nomeinst as nome_ced
//				   from db_config
//                   where codigo = ".db_getsession("DB_instit"));
//$idenpar .= "&nome_ced=".pg_result($result,0,0);
//numpre formatado
$numpre = db_sqlformatar($k03_numpre,8,'0').'000999';
$numpre = $numpre . db_CalculaDV($numpre,11);
//concatena todos os parametros
//$str = base64_encode($identificacao."##".$dados."##".$taxabancaria."##".$numero."##".$codigobarras."##".$datavencimento."##".$numpre."##".$valor_parm."##".$parametros."##".$idenpar);


$str = base64_encode($idenpar."##".$codigobarras."##".$valor_parm."##".$datavencimento);

$pdf = new scpdf();
$pdf->Open();
global $pdf;
$pdf1 = new db_impcarne($pdf,'2');
//$pdf1->modelo		= 2;
$pdf1->logo 		= $src;
$pdf1->prefeitura 	= $db_nomeinst;
$pdf1->enderpref	= $db_ender;
$pdf1->municpref	= $db_munic;
$pdf1->telefpref	= $db_telef;
$pdf1->emailpref	= @$db_email;
$pdf1->nome		= trim(pg_result($Identificacao,0,"z01_nome"));
$pdf1->ender		= trim(pg_result($Identificacao,0,"z01_ender")).', '.pg_result($Identificacao,0,"z01_numero").' '.trim(pg_result($Identificacao,0,"z01_compl"));
$pdf1->munic		= trim(pg_result($Identificacao,0,"z01_munic"));
$pdf1->cep		= trim(pg_result($Identificacao,0,"z01_cep"));
$pdf1->tipoinscr	= $tipoidentificacao;
$pdf1->nrinscr		= $numero;
$pdf1->ip		= db_getsession("DB_ip");
$pdf1->nomepri		= $nomepri;
$pdf1->nrpri		= $j39_numero;
$pdf1->complpri		= $j39_compl;
$pdf1->bairropri	= $j13_descr;
$pdf1->datacalc		= $datavencimento;
$pdf1->taxabanc		= db_formatar($taxabancaria,'f');
$pdf1->recorddadospagto = $DadosPagamento;
$pdf1->linhasdadospagto = pg_numrows($DadosPagamento);
$pdf1->receita		= 'k00_receit';
$pdf1->dreceita 	= 'k02_descr';
$pdf1->ddreceita	= 'k02_drecei';
$pdf1->valor 		= 'valor';
$pdf1->historico	= $k00_descr;
$pdf1->histparcel	= @$histparcela;
$pdf1->dtvenc		= $datavencimento;
$pdf1->numpre		= $numpre;
$pdf1->valtotal		= db_formatar(@$valor_parm,'f');
$pdf1->linhadigitavel	= $linhadigitavel;
$pdf1->codigobarras	= $codigobarras;
$pdf1->texto     = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();

$pdf1->imprime();

//$arq = tempnam("tmp","pdf").".pdf";
//$pdf->output($arq);
$pdf1->objpdf->output();
//echo "<script>location.href='$arq'</script>";





/*
///////$str = $idenpar."##".$codigobarras."##".$valor_parm."##".$datavencimento;
$pdf = new scpdf();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Setfont('Arial','B',12);
$pdf->setfillcolor(245);
$pdf->roundedrect(05,05,200,288,2,'DF','1234');
$pdf->setfillcolor(255,255,255);
$pdf->roundedrect(10,07,190,183,2,'DF','1234');
$pdf->Image('imagens/files/logo_boleto.png',45,9,20);
$pdf->text(70,15,$db_nomeinst);
$pdf->Setfont('Arial','',12);
$pdf->text(70,20,$db_ender);
$pdf->text(70,25,$db_munic);
$pdf->text(70,30,$db_telef);
$pdf->text(70,35,$db_email);

$pdf->setfillcolor(245);
$pdf->Roundedrect(15,45,110,35,2,'DF','1234');
$pdf->Setfont('Arial','',6);
$pdf->text(16,47,'Identificação:');
$pdf->Setfont('Arial','',8);
$pdf->text(16,51,'Nome :');
$pdf->text(32,51,trim(pg_result($Identificacao,0,"z01_nome")));
$pdf->text(16,56,'Endereço :');
$pdf->text(32,56,trim(pg_result($Identificacao,0,"z01_ender")));
$pdf->text(16,60,'Município :');
$pdf->text(32,60,trim(pg_result($Identificacao,0,"z01_munic")));
$pdf->text(16,64,'CEP :');
$pdf->text(32,64,trim(pg_result($Identificacao,0,"z01_cep")));
$pdf->text(16,68,'Data :');
$pdf->text(32,68,date('d/m/Y'));
$pdf->text(50,68,'Hora: '.date("H:i:s"));
$pdf->text(16,72,$tipoidentificacao);
$pdf->text(32,72,$numero);
$pdf->text(16,76,'IP :');
$pdf->text(32,76,db_getsession("DB_ip"));
$pdf->Setfont('Arial','',6);

$pdf->Roundedrect(130,45,65,35,2,'DF','1234');
if(!empty($HTTP_POST_VARS["ver_matric"])){
   $pdf->text(132,47,'Matrícula :');
   $pdf->Setfont('Arial','',8);
   $pdf->text(132,50,@$numero);
} else if(!empty($HTTP_POST_VARS["ver_inscr"])){
   $pdf->text(132,47,'Inscrição :');
   $pdf->Setfont('Arial','',8);
   $pdf->text(132,50,@$numero);
}

$pdf->Setfont('Arial','',6);
$pdf->text(132,55,'Logradouro :');
$pdf->Setfont('Arial','',8);
$pdf->text(132,58,@$nomepri);
$pdf->Setfont('Arial','',6);
$pdf->text(132,63,'Número/Complemento :');
$pdf->Setfont('Arial','',8);
$pdf->text(132,66,@$j39_numero.  "      ".@$j39_compl);
$pdf->Setfont('Arial','',6);
$pdf->text(132,71,'Bairro :');
$pdf->Setfont('Arial','',8);
$pdf->text(132,74,$j13_descr);

$pdf->Setfont('Arial','B',11);
$pdf->text(70,87,'RECIBO VÁLIDO ATÉ: '.date('d-m-Y',$DB_DATACALC));

$pdf->setfillcolor(245);
$pdf->Roundedrect(15,90,180,65,2,'DF','1234');
$pdf->Setfont('Arial','',8);

$pdf->SetXY(17,96);
if($taxabancaria!=0){
  $pdf->Cell(20,4,'Taxa Bancária',0,0,"L",0);
  $pdf->Cell(20,4,db_formatar($taxabancaria,'f'),0,1,"R",0);
}

$numrows = pg_numrows($DadosPagamento);
for($i = 0;$i < $numrows;$i++) {
   $pdf->setx(17);
   $pdf->cell(5,4,trim(pg_result($DadosPagamento,$i,"k00_receit")),0,0,"C",0);
   $pdf->cell(30,4,trim(pg_result($DadosPagamento,$i,"k02_descr")),0,0,"L",0);
   $pdf->cell(60,4,trim(pg_result($DadosPagamento,$i,"k02_drecei")),0,0,"L",0);
   $pdf->cell(15,4,db_formatar(pg_result($DadosPagamento,$i,"valor"),'f'),0,1,"R",0);
}
$pdf->SetXY(15,158);
$pdf->multicell(0,4,'HISTÓRICO :   '.@$k00_descr);
$pdf->setx(15);
$pdf->multicell(0,4,@$histparcela);
$pdf->setfillcolor(255,255,255);
$pdf->Roundedrect(10,195,190,46,2,'DF','1234');

$pdf->setfont('Arial','',6);
$pdf->setfillcolor(245);
$pdf->Roundedrect(40,200,48,10,2,'DF','1234');
$pdf->Roundedrect(93,200,48,10,2,'DF','1234');
$pdf->Roundedrect(146,200,48,10,2,'DF','1234');
$pdf->text(42,202,'Vencimento');
$pdf->text(95,202,'Código de Arrecadação');
$pdf->text(148,202,'Valor a Pagar');
$pdf->setfont('Arial','',10);
$pdf->text(48,207,$datavencimento);
$pdf->text(101,207,$numpre);
$pdf->text(153,207,db_formatar(@$valor_parm,'f'));

$pdf->SetDash(0.8,0.8);
$pdf->line(5,242.5,205,242.5);
$pdf->SetDash();
$pdf->setfillcolor(255,255,255);
$pdf->Roundedrect(10,244,190,46,2,'DF','1234');
$pdf->setfont('Arial','',12);
$pdf->setfillcolor(0,0,0);
$pdf->Image('imagens/files/logo_boleto.png',12,200,25);
$pdf->text(60,218,$linhadigitavel);
$pdf->int25(60,220,$codigobarras,15,0.341);
$pdf->setfillcolor(245);
$pdf->Roundedrect(40,250,48,10,2,'DF','1234');
$pdf->Roundedrect(93,250,48,10,2,'DF','1234');
$pdf->Roundedrect(146,250,48,10,2,'DF','1234');
$pdf->setfont('Arial','',6);
$pdf->text(42,252,'Vencimento');
$pdf->text(95,252,'Código de Arrecadação');
$pdf->text(148,252,'Valor a Pagar');
$pdf->setfont('Arial','',10);
$pdf->text(48,257,$datavencimento);
$pdf->text(101,257,$numpre);
$pdf->text(153,257,db_formatar(@$valor_parm,'f'));
$pdf->Image('imagens/files/logo_boleto.png',12,250,25);
$pdf->setfillcolor(0,0,0);
$pdf->setfont('Arial','',12);
$pdf->text(60,268,$linhadigitavel);
$pdf->int25(60,270,$codigobarras,15,0.341);

$pdf->output();

*/
/*
if($fc_numbco == '0' || $fc_numbco == "") {
//$logo = pg_exec("select logo from db_config where codigo = ".db_getsession("DB_instit"));
?>
<table width="669" height="318" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="669" valign="top" style="border: 1px solid #000000"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#E6E6E6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="20%" height="88" align="center" valign="middle">
				  <img src="imagens/logo_boleto.jpg" width="85" height="85" border="0">
				</td>
                <td width="80%" height="88" valign="bottom"><table width="100%" border="0" cellspacing="10" cellpadding="0">
                    <tr align="left" valign="top" bgcolor="#FFFFFF">
					 <td colspan="3" align="center">
					 <font size="2">
                    <?=$linhadigitavel?>
                    </font></td>
                    </tr>
                    <tr align="left" valign="top" bgcolor="#FFFFFF">
                      <td width="33%" height="40" align="right"> <font style="font-size:10px"><strong>&nbsp;Vencimento:</strong> 
                        </font> <br>
						<?=$datavencimento?>
                      </td>
                      <td width="33%" height="40" align="right"><font style="font-size:10px"><strong>&nbsp;C&oacute;digo 
                        de Arrecada&ccedil;&atilde;o: </strong></font><br>
						<?=$numpre?>
						</td>
                      <td width="34%" height="40" align="right"><font style="font-size:10px">&nbsp;<strong>Valor 
                        a pagar:</strong></font><br>
						<?=db_formatar(@$valor_parm,'f')?>
                      </td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
                  <td height="70" align="center" valign="middle">
                    <img src="boleto/int25.php?text=<?=$codigobarras?>"> </td>
        </tr>
        <tr>
          <td height="88" valign="top" bgcolor="#E6E6E6"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="20%" height="88" align="center" valign="middle"> <img src="imagens/logo_boleto.jpg" width="85" height="85" border="0"> 
                </td>
                <td width="80%" height="88" valign="bottom">
				<table width="100%" border="0" cellspacing="10" cellpadding="0">
                    <tr align="left" valign="top" bgcolor="#FFFFFF">
					 <td colspan="3" align="center">
					 <font size="2">
                    <?=$linhadigitavel?>
                    </font></td>
                    </tr>
					<tr align="left" valign="top" bgcolor="#FFFFFF"> 
                      <td width="33%" height="40" align="right"> <font style="font-size:10px"><strong>&nbsp;Vencimento:</strong> 
                        </font> <br>
						<?=$datavencimento?>
						 </td>
                      <td width="33%" height="40" align="right"><font style="font-size:10px"><strong>&nbsp;C&oacute;digo 
                        de Arrecada&ccedil;&atilde;o: </strong></font><br>
						<?=$numpre?>						
						</td>
                      <td width="34%" height="40" align="right"><font style="font-size:10px">&nbsp;<strong>Valor 
                        a pagar:</strong></font><br>
						<?=db_formatar(@$valor_parm,'f')?>						
						 </td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
        <tr>
                  <td height="70" align="center" valign="middle">
		  		  <img src="boleto/int25.php?text=<?=$codigobarras?>">
		  </td>
        </tr>
      </table></td>
  </tr>
</table>
<?
} else {
?>
<img src="boleto/imgboleto.php?<?=$str?>"> 
<?
}
?>
</td>
</tr>
</table>
<?
//if(!isset($itbi) && sizeof($numpres) > 2) {  
// comentado
if( 1==2 && !isset($itbi) && !isset($emite_recibo_protocolo)){
  ?>
    <br>
    <br>
    <br>
    <br>
    <!--br>
    <br>
    <br>
    <Br style="page-break-after: always"-->
  <table width="90%" class="parcelas" border="1" cellspacing="0" cellpadding="0">
  <tr align="center"> 
      <td colspan="10" class="parcelas"><img src="imagens/logo_boleto.jpg" width="102" height="99"></td>
  </tr>
  <tr> 
    <th class="parcelas">Identificador</th>
    <th class="parcelas">Parc</th>
    <th class="parcelas">Descrição</th>
    <th class="parcelas">Dt Venc</th>
    <th class="parcelas">Valor</th>
    <th class="parcelas">Val Corr</th>	
    <th class="parcelas">Juros</th>	
    <th class="parcelas">Multa</th>	
    <th class="parcelas">Desco</th>	
    <th class="parcelas">Tot Parc</th>		
  </tr>
<?


$sql = "select arrecad.k00_numpre,arrecad.k00_numpar,arrecad.k00_receit 
from arrecad,recibopaga
where recibopaga.k00_numnov = $k03_numpre
and arrecad.k00_numpre = recibopaga.k00_numpre
and arrecad.k00_numpar = recibopaga.k00_numpar
group by arrecad.k00_numpre,arrecad.k00_numpar,arrecad.k00_receit  ";
$result = pg_exec($sql);
$numrows = pg_numrows($result);
  for($i = 0;$i < $numrows;$i++) {
    db_fieldsmemory($result,$i);
    $sql = "select fc_calcula('$k00_numpre','$k00_numpar','$k00_receit','".date("Y-m-d",$DB_DATACALC)."','".db_vencimento()."',".db_getsession("DB_anousu").")";
    $result_calculo = pg_exec($sql);
	$fc_calcula = preg_split("/\s+/",pg_result($result_calculo,0,0));
	$Desco = preg_split("/[0-9][0-9][0-9][0-9]-[0-9][0-9]-[0-9][0-9]/",$fc_calcula[5]);
    $sql = "select distinct k00_numpre as identificador,
                   k00_numpar as parcela,
	               k01_descr as descricao,
	               to_char(k00_dtvenc,'DD/MM/YYYY') as vencimento
            from arrecad
			left outer join histcalc
			on histcalc.k01_codigo = arrecad.k00_hist
            where k00_numpre = $k00_numpre and k00_numpar = $k00_numpar and k00_receit = $k00_receit";
    $result_receita = pg_exec($sql);
    echo "<tr>
    <td class=\"parcelas\">&nbsp;".pg_result($result_receita,0,"identificador")."</td>
    <td class=\"parcelas\">&nbsp;".pg_result($result_receita,0,"parcela")."</td>
    <td class=\"parcelas\" nowrap>&nbsp;".pg_result($result_receita,0,"descricao")."</td>
    <td align=\"parcelas\" class=\"parcelas\">&nbsp;".pg_result($result_receita,0,"vencimento")."</td>
    <td align=\"right\" class=\"parcelas\">".number_format($fc_calcula[1],2,".",",")."&nbsp;</td>
    <td align=\"right\" class=\"parcelas\">".number_format($fc_calcula[2],2,".",",")."&nbsp;</td>	
    <td align=\"right\" class=\"parcelas\">".number_format($fc_calcula[3],2,".",",")."&nbsp;</td>	
    <td align=\"right\" class=\"parcelas\">".number_format($fc_calcula[4],2,".",",")."&nbsp;</td>	
    <td align=\"right\" class=\"parcelas\">".number_format($Desco[0],2,".",",")."&nbsp;</td>
    <td align=\"right\" class=\"parcelas\">".number_format((($fc_calcula[2]+$fc_calcula[3]+$fc_calcula[4])-$Desco[0]),2,".",",")."&nbsp;</td>	
  </tr>\n";
  }
  ?>
   <tr>  
    <td colspan="9" align="right" class="parcelas"><strong>Total:</strong>&nbsp;</td>
    <td align="right" class="parcelas"><?=number_format(($valor_parm - $taxabancaria),2,".",",")?>&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="10" class="parcelas">
	<strong>&nbsp;Código Recibo:</strong> <?=$numpre?><Br>
	<strong>&nbsp;Data:</strong> <?=date("m/d/Y")?><br>
	<strong>&nbsp;Hora:</strong> <?=date("H:i:s")?><Br>
	<strong>&nbsp;IP:</strong> <?=$_SERVER['REMOTE_ADDR']?><Br>
	<strong>&nbsp;Linha Digitável:</strong> <?=$linhadigitavel?>	
	</td>
  </tr>
</table>
</center>
</body>
</html>
*/
?>





?>