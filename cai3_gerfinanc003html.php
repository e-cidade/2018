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


exit;

require("libs/db_barras.php");
db_postmemory($HTTP_SERVER_VARS);
$matricularecibo = @$j01_matric;
$inscricaorecibo = @$q02_inscr;
$numcgmrecibo    = @$z01_numcgm;
db_postmemory($HTTP_POST_VARS);
$tipoidentificacao = 0;
?>
<html>
<head>
<title>Recibo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
.parcelas {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	border: 1px solid #666666;
}
td {
  font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="window.print()">
<center>
<?
if(!isset($emite_recibo_protocolo)){
  pg_exec("BEGIN");
  //gera um nuvo numpre. "numnov"
  //pg_exec("update numpref set k03_numpre = k03_numpre + 1 where k03_anousu = ".db_getsession("DB_anousu"));
  //$result = pg_exec("select k03_numpre as k03_numpre from numpref where k03_anousu = ".db_getsession("DB_anousu"));
  //db_fieldsmemory($result,0);
  $result = pg_exec("select nextval('numpref_k03_numpre_seq') as k03_numpre");
  db_fieldsmemory($result,0);

  //pega os numpres da ca3_gerfinanc002.php, separa e insere em db_reciboweb
  $result = pg_exec("select k00_codbco,k00_codage,k00_descr,k00_hist1,k00_hist2,k00_hist3,k00_hist4,k00_hist5,k00_hist6,k00_hist7,k00_hist8 from arretipo where k00_tipo = $tipo");
    
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
        echo "<script>window.opener.top.corpo.limpaparcela('".key($vt)."');</script>";
        $numpres .= "N".$vt[key($vt)];  
      }
      next($vt);
    }
    $numpres = split("N",$numpres);
    for($i = 1;$i < sizeof($numpres);$i++) {
    //  $numpres[$i] = base64_decode($numpres[$i]);
    //  $numpres[$i];

      $valores = split("P",$numpres[$i]);  
      $sql = "insert into db_reciboweb values(".$valores[0].",".$valores[1].",$k03_numpre,$k00_codbco,'$k00_codage','$fc_numbco')";
      pg_exec($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage()); 
    }

  }else{

      $sql = "insert into db_reciboweb values(".$numpre_unica.",0,$k03_numpre,$k00_codbco,'$k00_codage','$fc_numbco')";
      pg_exec($sql) or die("Erro(26) inserindo em db_reciboweb: ".pg_errormessage()); 
    
      
  }

  //roda funcao fc_recibo pra gerar o recibo
  $sql = "select fc_recibo($k03_numpre,'".date("Y-m-d",$DB_DATACALC)."','".db_vencimento()."',".db_getsession("DB_anousu").")";
  $Recibo = pg_exec($sql);
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
  $Identificacao = pg_exec("select z01_nome,z01_ender,z01_munic,z01_uf,z01_cep,nomepri,j39_compl,j39_numero,j13_descr,j34_setor||'.'||j34_quadra||'.'||j34_lote as sql
                     from proprietario
					 where j01_matric = $numero limit 1");
  db_fieldsmemory($Identificacao,0);

} else if(!empty($HTTP_POST_VARS["ver_inscr"]) || $inscricaorecibo > 0 ) {
  $numero = @$HTTP_POST_VARS["ver_inscr"] + $inscricaorecibo;
  $tipoidentificacao = "Inscricao :";
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
  $Identificacao = pg_exec("select z01_nome,z01_ender,z01_munic,z01_uf,z01_cep,''::bpchar as nomepri,''::bpchar as j39_compl,''::bpchar as j39_numero,z01_bairro as j13_descr, '' as sql 
                     from cgm
					 where z01_numcgm = $numero ");
  db_fieldsmemory($Identificacao,0);
} else {
  if(isset($emite_recibo_protocolo)){
   $Identificacao = pg_exec("
            select c.z01_nome,c.z01_ender,c.z01_munic,c.z01_uf,c.z01_cep,' ' as nomepri,' ' as j39_compl, ' ' as j39_numero, ' ' as j13_descr, '' as sql
            from recibo r
                 inner join cgm c on c.z01_numcgm = r.k00_numcgm
   		    where r.k00_numpre = ".$k03_numpre."
            limit 1");
    db_fieldsmemory($Identificacao,0);
  }
}


if(isset($tipo_debito)){

  if($tipo==5 || $tipo==17){
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
  }else if($tipo_debito == 3 || $tipo_debito == 2){
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
  }else if($tipo_debito == 6 || $tipo_debito == 1){
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

$db_vlrbar = "0".str_replace('.','',str_pad(number_format($total_recibo,2,"","."),11,"0",STR_PAD_LEFT));
//$db_numbanco = "4268" ;// deve ser tirado do db_config
$resultnumbco = pg_exec("select numbanco from db_config where codigo = " . db_getsession("DB_instit"));
$db_numbanco = pg_result($resultnumbco,0) ;// deve ser tirado do db_config
$db_numpre = db_numpre($k03_numpre).'000'; //db_formatar(0,'s',3,'e');
$db_dtvenc = str_replace("-","",$datavencimento);
//$resultcod = pg_exec("select fc_febraban('816'||'$db_vlrbar'||'".$db_numbanco."'||$db_dtvenc||'000000'||'$db_numpre')");
db_fieldsmemory($resultcod,0);
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

///////$str = $idenpar."##".$codigobarras."##".$valor_parm."##".$datavencimento;
?>
<table height="1030" border="0" cellpadding="0" cellspacing="0">
<tr>
<td height="652">
<!------------------------------- RECIBO ------------------------------------------------------------->
<table width="670" height="633" border="0" cellpadding="15" cellspacing="0">
  <tr>
    <td height="633" valign="top" style="border: 1px solid #000000"><table width="100%" height="550" border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="37%" height="130" align="right" valign="middle"><img src="imagens/logo_boleto.jpg" width="115" height="125" onclick="window.print();"> 
                  </td>
                  <td width="63%" align="center" valign="middle"><table width="100%" border="0" cellspacing="0">
                      <tr>
                        <td><font size="4" face="Arial, Helvetica, sans-serif"><strong><?=$db_nomeinst?></strong></font>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><?=$db_ender?>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><?=$db_munic?>&nbsp;<?=$db_uf?></td>
                      </tr>
                      <tr>
                        <td><?$db_telef?>&nbsp;</td>
                      </tr>
                      <tr>
                        <td><?=$db_email?>&nbsp;</td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="100" colspan="2" align="left" valign="top"><table width="100%" height="99%" border="0" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td width="70%" height="100" align="left" valign="top" style="border: 1px solid #000000"> 
                          <font style="font-size:10px;font-weight: bold">&nbsp;Iddentifica&ccedil;&atilde;o:<br>
                          <br>
                          </font> <table border="0" cellpadding="0" cellspacing="0"  style="font-size:11px">
                            <?				
				  echo "<tr><Td nowrap>&nbsp;Nome:</td><td nowrap>".trim(pg_result($Identificacao,0,"z01_nome"))."</td></tr>\n";
				  echo "<tr><Td nowrap>&nbsp;Endereço:</td><td nowrap>".trim(pg_result($Identificacao,0,"z01_ender"))."</td></tr>\n";
				  echo "<tr><Td nowrap>&nbsp;Município:&nbsp;</td><td nowrap>".trim(pg_result($Identificacao,0,"z01_munic"))." - ".trim(pg_result($Identificacao,0,"z01_uf"))."</td></tr>\n";
				  echo "<tr><Td nowrap>&nbsp;CEP:</td><td nowrap>".substr(trim(pg_result($Identificacao,0,"z01_cep")),0,2).".".substr(trim(pg_result($Identificacao,0,"z01_cep")),2,3)."-".substr(trim(pg_result($Identificacao,0,"z01_cep")),3,2)."</td></tr>\n";
                  		  echo "<tr><td nowrap>&nbsp;Data:</td><td nowrap>".date("d/m/Y")." Hora: ".date("H:i:s")."</td></tr>\n";
                  		  echo "<tr><td nowrap>&nbsp;".$tipoidentificacao."</td><td nowrap>".$numero."</td></tr>\n";
                  		  echo "<tr><td nowrap>&nbsp;IP:</td><td nowrap>".db_getsession("DB_ip")."</td></tr>\n";
				  /*echo "<tr><Td>Nome:</td><td>".trim(pg_result($Identificacao,0,"nomepri"))."</td></tr>\n";
				  echo "<tr><Td>Nome:</td><td>".trim(pg_result($Identificacao,0,"v11_compl"))."</td></tr>\n";
				  echo "<tr><Td>Nome:</td><td>".trim(pg_result($Identificacao,0,"v11_numero"))."</td></tr>\n";
				  echo "<tr><Td>Nome:</td><td>".trim(pg_result($Identificacao,0,"j13_descr"))."</td></tr>\n";*/
				?>
                          </table></td>
                        <td width="3%" align="left" valign="top">&nbsp;</td>
                        <td width="27%" align="left" valign="top"> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr> 
                              <td height="34" valign="top" style="border: 1px solid #000000;border-bottom-style: none"> 
                                <?
								if(!empty($HTTP_POST_VARS["ver_matric"])){
								  ?>
                                <font style="font-size:10px;font-weight: bold">&nbsp;Matr&iacute;cula:<br>
                                &nbsp; 
                                <?=@$numero."  S/Q/L:".@$sql?>
                                </font> 
                                <?

								} else if(!empty($HTTP_POST_VARS["ver_inscr"])){
								  ?>
                                <font style="font-size:10px;font-weight: bold">&nbsp;Inscri&ccedil;&atilde;o:<br>
                                &nbsp; 
                                <?=@$numero?>
                                </font> 
                                <?
								}
								?>
                              </td>
                            </tr>
                            <tr> 
                              <td height="33" valign="top" style="border: 1px solid #000000;border-bottom-style: none"><font style="font-size:10px;font-weight: bold">&nbsp;Logradouro:<br>
                                &nbsp; 
                                <?=@$nomepri?>
                                </font></td>
                            </tr>
                            <tr> 
                              <td height="33" valign="top" style="border: 1px solid #000000;border-bottom-style: none"> 
                                <font style="font-size:10px;font-weight: bold">&nbsp;N&uacute;mero/Complemento<br>
                                &nbsp; 
                                <?=@$j39_numero.  "      ".@$j39_compl?>
                                </font> </td>
                            </tr>
                            <tr> 
                              <td height="32" valign="top" style="border: 1px solid #000000"> 
                                <font style="font-weight: bold;font-size:10px">&nbsp;Bairro:<br>
                                </font><font style="font-size:10px;font-weight: bold"> 
                                &nbsp; 
                                <?=@$j13_descr?>
                                </font><font style="font-weight: bold;font-size:10px">&nbsp; 
                                </font> </td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="37" colspan="2" align="left" valign="middle"> <table width="100%" border="0" cellpadding="0" cellspacing="0">
                      <tr> 
                        <td height="27" align="center" style="font-size:12px;font-family: Arial, Helvetica, sans-serif;font-weight: bold;border: 1px solid #000000">
						RECIBO VÁLIDO ATÉ: <font size='4' color='red'><?=date('d-m-Y',$DB_DATACALC)?></font></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="200" colspan="2" align="left" valign="top" style="border: 1px solid #000000"> 
                    <table border="0" cellpadding="3" cellspacing="0">
                      <tr> 
                        <td> <table border="0" cellpadding="0" cellspacing="0" style="font-size:11px">
                            <tr> 
                              <Td>&nbsp;</Td>
                              <?
			     if($taxabancaria!=0){
				   ?>
                              <td>&nbsp;&nbsp;Taxa Bancária</td>
                              <?
				 }else{
				   ?>
                              <td>&nbsp;&nbsp;</td>
                              <?
				 }
				 ?>
                              <td>&nbsp;</td>
                              <?
			     if($taxabancaria!=0){
				   ?>
                              <td align="right">
                                <?=number_format($taxabancaria,2,".",",")?>
                                &nbsp;&nbsp;</td>
                              <?
				 }else{
				   ?>
                              <td>&nbsp;&nbsp;</td>
                              <?
				 }
				 ?>
                            </tr>
                            <?
				  $numrows = pg_numrows($DadosPagamento);
				  for($i = 0;$i < $numrows;$i++) {
				    echo "<tr><Td nowrap>&nbsp;&nbsp;".trim(pg_result($DadosPagamento,$i,"k00_receit"))."&nbsp;&nbsp;</td><td nowrap>&nbsp;&nbsp;".trim(pg_result($DadosPagamento,$i,"k02_descr"))."&nbsp;&nbsp;</td><td nowrap>&nbsp;&nbsp;".trim(pg_result($DadosPagamento,$i,"k02_drecei"))."&nbsp;&nbsp;</td><td align=\"right\" nowrap>".number_format(pg_result($DadosPagamento,$i,"valor"),2,".",",")."&nbsp;&nbsp;</td></tr>\n";
				  }
				?>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
                <tr> 
                  <td height="81" colspan="2" valign="top">Hist&oacute;rico:&nbsp;&nbsp;&nbsp;<font size="2">
                    <?=@$k00_descr?>
                    <br>
                    </font> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td><font size="2">
                          <?=@$histparcela?>
                          </font></td>
                      </tr>
                    </table>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                  </td>
                </tr>
              </table></td>
  </tr>
</table>
</td>
</tr>
<tr>
<td ><?
//if(trim($k15_codbco) == "" && trim($k15_codage) == "") {
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

/*select valores.k00_numpre as identificador,substr(valores.k00_numpre,9,2) as parcela,k01_descr as descricao,valor,acrescimo,desconto,(valor+acrescimo-desconto) as total , to_char(k00_dtvenc,'DD/MM/YYYY') as vencimento,k01_descr
from (
      select
      distinct on (k00_numpre||k00_numpar) k00_numpre,k00_numpar,k00_hist,
      from recibopaga
           left outer join histcalc on k01_codigo = k00_hist,   
           ( select distinct k00_numpre as numero, k00_numpar as parcela from recibopaga where k00_numnov = $k03_numpre) as rec
      where recibopaga.k00_numpre = rec.numero and recibopaga.k00_numpar = rec.parcela and recibopaga.k00_numnov = $k03_numpre
      group by k00_numpre,k00_numpar,k00_hist
      ) as valores
	  left outer join histcalc on valores.k00_hist = histcalc.k01_codigo
  where venc.k00_numpre = valores.k00_numpre and venc.k00_numpar = valores.k00_numpar");

select valores.k00_numpre as identificador,
       substr(valores.k00_numpre,9,2) as parcela,
	   k01_descr as descricao,
	   valor,
	   acrescimo,
	   desconto,
	   (valor+acrescimo-desconto) as total , 
	   to_char(k00_dtvenc,'DD/MM/YYYY') as vencimento,
	   k01_descr
*/


$sql = "select arrecad.k00_numpre,arrecad.k00_numpar,arrecad.k00_receit 
from arrecad,recibopaga
where recibopaga.k00_numnov = $k03_numpre
and arrecad.k00_numpre = recibopaga.k00_numpre
and arrecad.k00_numpar = recibopaga.k00_numpar
group by arrecad.k00_numpre,arrecad.k00_numpar,arrecad.k00_receit  ";
echo "parou";exit;
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
  <?
  }
  ?>
</center>
</body>
</html>