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


echo "programa desativado";

exit;

// programa desativado

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if(isset($erropdf)){
  $HTTP_POST_VARS["CHECK1"] = $CHECK1;
  $HTTP_POST_VARS["ver_inscr"] = $ver_inscr;
  $tipo = $tipo;
  include("libs/db_conecta.php");
  include("libs/db_stdlib.php");

}

require("libs/db_barras.php");
include("fpdf151/impcarne.php");
include("fpdf151/scpdf.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);
if(!isset($emite_recibo_protocolo)){
  pg_exec("BEGIN");
  $result = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numpre");
  db_fieldsmemory($result,0);
  //pega os numpres da ca3_gerfinanc002.php, separa e insere em db_reciboweb
  $result = pg_exec("select k00_codbco,
                            k00_codage,
			    k00_descr,
			    k00_hist1,
			    k00_hist2,
			    k00_hist3,
			    k00_hist4,
			    k00_hist5,
			    k00_hist6,
			    k00_hist7,
			    k00_hist8 
		      from arretipo 
		      where k00_tipo = $tipo");
  db_fieldsmemory($result,0);
  $k00_descr = $k00_descr." - ".db_getsession("DB_anousu");
  $result = pg_exec("select fc_numbco($k00_codbco,'$k00_codage')");
  db_fieldsmemory($result,0);
  $vt = $HTTP_POST_VARS;
  if(!isset($numpre_unica) || $numpre_unica ==""){
    $tam = sizeof($vt);
    reset($vt);
    $numpres = "";
    for($i = 0;$i < $tam;$i++) {
      if(db_indexOf(key($vt) ,"CHECK") > 0)
        $numpres .= "N".$vt[key($vt)];  
      next($vt);
    }
    $numpres = split("N",$numpres);
    for($i = 1;$i < sizeof($numpres);$i++) {
      $valores = split("P",$numpres[$i]);  
      $sql = "insert into db_reciboweb values(".$valores[0].",".$valores[1].",$k03_numpre,$k00_codbco,'$k00_codage','$fc_numbco')";
      pg_exec($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage()); 
    }
  }else{
      $sql = "insert into db_reciboweb values(".$numpre_unica.",0,$k03_numpre,$k00_codbco,'$k00_codage','$fc_numbco')";
      pg_exec($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage()); 
  }
  //roda funcao fc_recibo pra gerar o recibo
  if(isset($dtpaga)){
    $sql = "select fc_recibo($k03_numpre,'".$dtpaga."','".$dtpaga."',".db_getsession("DB_anousu").")";
  }else{
    $sql = "select fc_recibo($k03_numpre,'".date("Y-m-d",db_getsession("DB_datausu"))."','".db_vencimento()."',".db_getsession("DB_anousu").")";
  }
  $Recibo = pg_exec($sql);

  pg_exec("COMMIT");
}
if(!isset($emite_recibo_protocolo)){
  $sql = "select r.k00_numcgm,r.k00_receit,t.k02_descr,t.k02_drecei,to_char(r.k00_dtoper,'DD-MM-YYYY') as k00_dtoper,to_char(r.k00_dtvenc,'DD-MM-YYYY') as k00_dtvenc,sum(r.k00_valor) as valor
                   from recibopaga r
                   inner join tabrec t on t.k02_codigo = r.k00_receit 
                   inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
		   where r.k00_numnov = ".$k03_numpre."
                   group by r.k00_dtoper,r.k00_dtvenc,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_numcgm";
}else{
  $sql = "select r.k00_numcgm,r.k00_receit,t.k02_descr,t.k02_drecei,to_char(r.k00_dtoper,'DD-MM-YYYY') as k00_dtoper,to_char(r.k00_dtvenc,'DD-MM-YYYY') as k00_dtvenc,sum(r.k00_valor) as valor
                   from recibo r
                   inner join tabrec t on t.k02_codigo = r.k00_receit 
                   inner join tabrecjm on tabrecjm.k02_codjm = t.k02_codjm
		   where r.k00_numpre = ".$k03_numpre."
                   group by r.k00_dtoper,r.k00_dtvenc,r.k00_receit,t.k02_descr,t.k02_drecei,r.k00_numcgm";
}
$DadosPagamento = pg_exec($sql);
//faz um somatorio do valor
if (pg_numrows($DadosPagamento) == 1) {
  $datavencimento = pg_result($DadosPagamento,0,"k00_dtvenc");
} else {
  $datavencimento = pg_result($DadosPagamento,0,"k00_dtoper");
}

if ($datavencimento <= date('d/m/Y',db_getsession("DB_datausu"))) {
  $datavencimento = date('d/m/Y',db_getsession("DB_datausu"));
}
  
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
$valor_parm = $total_recibo; 
//seleciona dados de identificacao. Verifica se é inscr ou matric e da o respectivo select
//essa variavel vem do cai3_gerfinanc002.php, pelo window open, criada por parse_str
$tipo_chave = ""; 
if(!empty($HTTP_POST_VARS["ver_matric"])) {
  $numero = $HTTP_POST_VARS["ver_matric"];
  $Identificacao = pg_exec("select z01_nome,z01_ender,z01_munic,z01_uf,z01_cep,nomepri,j39_compl,j39_numero,j13_descr,j34_setor||'.'||j34_quadra||'.'||j34_lote as sql
                            from proprietario
			    where j01_matric = ".$HTTP_POST_VARS["ver_matric"]." limit 1");
  db_fieldsmemory($Identificacao,0);
  $tipo_chave = "Matrícula";
} else if(!empty($HTTP_POST_VARS["ver_inscr"])) {
  $numero = $HTTP_POST_VARS["ver_inscr"];
  $Identificacao = pg_exec("select z01_nome,
                                   z01_ender,
			           z01_munic,
				   z01_uf,
				   z01_cep,
				   z01_ender as nomepri,
				   z01_compl as j39_compl,
				   z01_numero as j39_numero,
				   z01_bairro as j13_descr, 
				   '' as sql  
                            from empresa
			    where q02_inscr = ".$HTTP_POST_VARS["ver_inscr"]);
  db_fieldsmemory($Identificacao,0);
  $tipo_chave = "Inscrição";
}else if(!empty($HTTP_POST_VARS["ver_numcgm"])) {
  $numero = $HTTP_POST_VARS["ver_numcgm"];
  $Identificacao = pg_exec("select z01_nome,z01_ender,z01_munic,z01_uf,z01_cep,''::bpchar as nomepri,''::bpchar as j39_compl,''::bpchar as j39_numero,z01_bairro as j13_descr, '' as sql 
                            from cgm
			    where z01_numcgm = ".$HTTP_POST_VARS["ver_numcgm"]);
  db_fieldsmemory($Identificacao,0);
  $tipo_chave = "Numcgm";
} else {
  if(isset($emite_recibo_protocolo)){
    $Identificacao = pg_exec("
            select cgm.z01_nome,cgm.z01_ender,cgm.z01_munic,cgm.z01_uf,cgm.z01_cep,' ' as nomepri,' ' as j39_compl, ' ' as j39_numero, ' ' as j13_descr, '' as sql
            from recibo r
                 inner join cgm c on c.z01_numcgm = r.k00_numcgm
   		    where r.k00_numpre = ".$k03_numpre."
            limit 1");
    db_fieldsmemory($Identificacao,0);
  }
}
//select pras observacoes
$Observacoes = pg_exec($conn,"select mens,alinhamento from db_confmensagem where cod in('obsboleto1','obsboleto2','obsboleto3','obsboleto4')");
$db_vlrbar = db_formatar(str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT)),'s','0',11,'e');
/*$db_vlrbar = "0".str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT));
if($total_recibo>999)
   $db_vlrbar = "0".$db_vlrbar;
if($total_recibo>9999)
   $db_vlrbar = "0".$db_vlrbar;
if($total_recibo>99999)
   $db_vlrbar = "0".$db_vlrbar;
if($total_recibo>999999)
   $db_vlrbar = "0".$db_vlrbar;*/
//$db_numpre = db_formatar($k03_numpre,'s',8,'e').'000'; 
$db_numpre = db_numpre($k03_numpre).'000';
$db_dtvenc = substr($datavencimento,6,4).substr($datavencimento,3,2).substr($datavencimento,0,2);

////$resultcod = pg_exec("select fc_febraban('816'||'$db_vlrbar'||'".$db_numbanco."'||$db_dtvenc||'000000'||'$db_numpre')");
db_fieldsmemory($resultcod,0);
$codigo_barra = split(",",$fc_febraban);
$codigobarras = $codigo_barra[0];
$linhadigitavel = $codigo_barra[1];
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
  $idenpar .= "&dt_hoje=".date('d/m/Y',db_getsession("DB_datausu"));
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
//numpre formatado
$numpre = db_sqlformatar($k03_numpre,8,'0').'000999';
$numpre = $numpre . db_CalculaDV($numpre,11);
//concatena todos os parametros
$str = base64_encode($idenpar."##".$codigobarras."##".$valor_parm."##".$datavencimento);
if( 1==2 && !isset($itbi) && !isset($emite_recibo_protocolo)){
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
    $sql = "select fc_calcula('$k00_numpre','$k00_numpar','$k00_receit','".date("Y-m-d",db_getsession("DB_datausu"))."','".db_vencimento()."',".db_getsession("DB_anousu").")";
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
  }
}


if(isset($tipo)){

  $resulttipo = pg_exec("select k03_tipo from arretipo where k00_tipo = $tipo");
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





global $pdf;
$pdf = new scpdf();
$pdf->Open();
$pdf1 = new db_impcarne($pdf,'22');
//$pdf1->modelo		= 22;
$pdf1->logo 		= $src;
$pdf1->prefeitura 	= $db_nomeinst;
$pdf1->enderpref	= $db_ender;
$pdf1->municpref	= $db_munic;
$pdf1->telefpref	= $db_telef;
$pdf1->emailpref	= @$db_email;
$pdf1->nome		= $z01_nome;
$pdf1->ender		= $z01_ender." ".$j39_numero." ".$j39_compl;
$pdf1->munic		= $z01_munic;
$pdf1->cep		= $z01_cep;
$pdf1->tipoinscr	= $tipo_chave;
$pdf1->nrinscr		= $numero;
$pdf1->ip		= $_SERVER['REMOTE_ADDR'];
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
$pdf1->quaisbancos	= "LOCAIS DE PAGAMENTO ATÉ O VENCIMENTO:\nAgências do BANCO DO BRASIL, BANRISUL, CAIXA ECONÔMICA FEDERAL e Rede Nacional de LOTÉRICAS.\nEM CASO DE PAGAMENTO VIA HOME-BANKING ESCOLHER A OPÇÃO ARRECADAÇÃO";
$pdf1->numpre		= $numpre;
$pdf1->valtotal		= db_formatar(@$valor_parm,'f');
$pdf1->linhadigitavel	= $linhadigitavel;
$pdf1->codigobarras	= $codigobarras;
$pdf1->texto     = '';
$pdf1->imprime();




//$arq = tempnam("tmp","pdf").".pdf";
//$pdf1->objpdf->output($arq);
$pdf1->objpdf->output();
//echo "<script>alert('$arq')</script>";


?>