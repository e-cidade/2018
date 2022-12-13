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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamforne_classe.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$cl_pcorcamval     = new cl_pcorcamval;
$cl_pcorcamforne   = new cl_pcorcamforne;

$clrotulo = new rotulocampo;
$clrotulo->label("pc23_vlrun");
$clrotulo->label("pc23_valor");
$sqlerro = false;

$sql= "select z01_cgccpf,
              z01_nome,
              pc20_dtate,
              pc20_hrate,
              pc21_prazoent,
              pc21_validadorc  
		     from pcorcamforne 
		    inner join cgm     on pc21_numcgm = z01_numcgm 
		    inner join pcorcam on pc20_codorc = pc21_codorc
		    where pc21_numcgm = $cgm 
		      and pc21_codorc = $orc";
		
$result= db_query($sql);
db_fieldsmemory($result,0);
if($sol==1){ 
$sqlitens="select pc11_codigo,
                  pc11_quant,
                  pc01_descrmater,
                  pc11_resum,
                  pc11_pgto,
                  pc11_prazo,
                  pc11_seq,
                  pc10_numero,
                  m61_usaquant,
                  m61_descr,
		              pc17_codigo,
		              pc17_quant,
		              pc01_servico,
		              pc29_orcamitem,
		              pc23_valor,
		              pc23_obs,
		              pc23_vlrun,
		              pc23_validmin 
			       from pcorcamitemsol 
			      inner join pcorcamitem      on pcorcamitem.pc22_orcamitem      = pcorcamitemsol.pc29_orcamitem
			      inner join pcorcam          on pcorcam.pc20_codorc             = pcorcamitem.pc22_codorc 
			      inner join solicitem        on solicitem.pc11_codigo           = pcorcamitemsol.pc29_solicitem 
			      inner join solicita         on solicita.pc10_numero            = solicitem.pc11_numero 
		        inner join pcorcamforne     on  pcorcam.pc20_codorc            = pc21_codorc
			       left join solicitemunid    on solicitemunid.pc17_codigo       = solicitem.pc11_codigo 
			       left join matunid          on matunid.m61_codmatunid          = solicitemunid.pc17_unid 
			       left join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo 
			       left join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater 
			       left join pcsubgrupo       on pcsubgrupo.pc04_codsubgrupo     = pcmater.pc01_codsubgrupo 
			       left join pctipo           on pctipo.pc05_codtipo             = pcsubgrupo.pc04_codtipo
			       left join pcorcamval       on pcorcamitem.pc22_orcamitem      = pc23_orcamitem and pc23_orcamforne=pc21_orcamforne
			      where pc22_codorc     = $orc 
			        and pc21_orcamforne = $forne
			      order by pc11_seq";
			
$result= db_query($sqlitens);
$linhas= pg_num_rows($result);
}else{ 
	$sol= 2;
	$sqlproc="select pc11_seq,
	                 pc11_resum,
	                 pc11_codigo,
	                 pc11_vlrun,
	                 pc11_quant,
	                 pc01_descrmater,
	                 pc22_orcamitem,
	                 pc31_orcamitem,
				           pc23_valor,
				           pc23_obs,
				           pc23_vlrun,
				           pc23_validmin
				      from pcorcamitem 
				     inner join pcorcam          on pcorcam.pc20_codorc             = pcorcamitem.pc22_codorc
				     inner join pcorcamforne     on pcorcam.pc20_codorc             = pc21_codorc 
				     inner join pcorcamitemproc  on pcorcamitemproc.pc31_orcamitem  = pcorcamitem.pc22_orcamitem 
				     inner join pcprocitem       on pcprocitem.pc81_codprocitem     = pcorcamitemproc.pc31_pcprocitem 
				      left join pcorcamval       on pcorcamitemproc.pc31_orcamitem  = pc23_orcamitem
				                                and pc23_orcamforne = $forne
				     inner join solicitem        on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem 
				      left join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo 
				      left join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater 
				     where pc20_codorc=$orc  
				       and pc21_orcamforne = $forne
				     order by pc22_orcamitem";

$result= db_query($sqlproc);
$linhas= pg_num_rows($result);

}
// ##################### Incluir ################################
if (isset($incluir)) {
	
	db_inicio_transacao();
	
	$prazo  = $p_ano."-".$p_mes."-".$p_dia;
	$valorc = $v_ano."-".$v_mes."-".$v_dia;
	if ($prazo=="--"){
		$prazo = null;
	}
	if ($valorc =="--"){
		$valorc  = null;
	}
	$cl_pcorcamforne->pc21_validadorc = $valorc;
	$cl_pcorcamforne->pc21_prazoent = $prazo;
	$cl_pcorcamforne->pc21_orcamforne=$forne;
	$cl_pcorcamforne->alterar($forne);
	if ($cl_pcorcamforne->erro_status =="0") {
		 $erro_msg = "Erro ao incluir Fornecedor";
		 $sqlerro  = true; 
	} else {
	
  	  for ($i = 0; $i < $linhas; $i ++) {
	   	 db_fieldsmemory($result,$i);
	  	 $q  = "quant".$i;
	  	 $v  = "valortotal".$i;
	  	 $vu = "valor".$i;
	  	 $o  = "obs".$i;
	  	 $val = "o$i";
	  	 $val = $$val;
	  	 
	  	 $val = implode("-",array_reverse(explode("/",$val)));
	  	 if ($val == "--"){
	    	$val= null;
	     }
       
	     $cl_pcorcamval->pc23_orcamforne = $forne;
	     if ($sol==1) {
	       $orcamitem = $pc29_orcamitem;
	     }
	  
	     if ($sol==2) {
	       $orcamitem = $pc31_orcamitem;
	     }
	     $cl_pcorcamval->pc23_orcamitem  = $orcamitem;
	     $cl_pcorcamval->pc23_valor      = $$v; 
	     $cl_pcorcamval->pc23_quant      = $$q;
	     $cl_pcorcamval->pc23_obs        = $$o;  
	     $cl_pcorcamval->pc23_vlrun      = $$vu; 
	     if ($val == "--"){
		    $val= null;
	     }
	     $cl_pcorcamval->pc23_validmin   = $val;
	     $cl_pcorcamval->incluir($forne,$orcamitem);
	     if ($cl_pcorcamval->erro_status == "0") {
	    	$erro_msg = "Erro ao lançar valores do orçamento";
	    	$sqlerro  = true;
	     }
      }
	}
	
	if($sqlerro == false){
		db_msgbox("Operação realizada com sucesso!");
	} else {
    db_msgbox($erro_msg);
    
  }
	db_fim_transacao($sqlerro);
}

// ##################### Alterar ################################
if (isset($alterar)) {
	db_inicio_transacao();
	$prazo  = $p_ano."-".$p_mes."-".$p_dia;
	$valorc = $v_ano."-".$v_mes."-".$v_dia;
	if ($prazo=="--"){
		$prazo = null;
	}
	if ($valorc =="--"){
		$valorc  = null;
	}
	$cl_pcorcamforne->pc21_validadorc = $valorc;
	$cl_pcorcamforne->pc21_prazoent = $prazo;
	$cl_pcorcamforne->pc21_orcamforne=$forne;
	$cl_pcorcamforne->alterar($forne);
	
	if ($cl_pcorcamforne->erro_status == 0) {
     $erro_msg = "Erro ao alterar Fornecedor";
     $sqlerro  = true;
  } else {
	  for ($i = 0; $i < $linhas; $i ++) {
	  	db_fieldsmemory($result,$i);
		  $q   = "quant".$i;
  		$v   = "valortotal".$i;
	  	$vu  = "valor".$i;
		  $o   = "obs".$i;
		  $d   = "o".$i;
		
		  $val = substr($$d,6,4)."-".substr($$d,3,2)."-".substr($$d,0,2); 
	 
  	  if ($val == "--"){
  		  $val= null;
  	  }
	
  	  $cl_pcorcamval->pc23_orcamforne = $forne;
    	if ($sol==1) {
      	$orcamitem = $pc29_orcamitem;
	    }
	    if ($sol==2) {
	      $orcamitem = $pc31_orcamitem;
	    }
	  
	    $cl_pcorcamval->pc23_orcamitem  = $orcamitem;
	    $cl_pcorcamval->pc23_valor      = $$v; 
	    $cl_pcorcamval->pc23_quant      = $$q;
	    $cl_pcorcamval->pc23_obs        = $$o;  
	    $cl_pcorcamval->pc23_vlrun      = $$vu; 
	    if ($val == "--"){
  	  	$val= null;
  	  }
  	  $cl_pcorcamval->pc23_validmin   = $val; 
  	  $cl_pcorcamval->alterar($forne,$orcamitem);
	  if ( $cl_pcorcamval->erro_status==0) {
         $erro_msg = $cl_pcorcamval->erro_msg;
         $sqlerro  = true; 
      }
    }
	  
	  if($sqlerro == false){
	    db_msgbox("Operação realizada com sucesso!");
	  } else {
	  	db_msgbox($erro_msg);
	  }
  db_fim_transacao($sqlerro);
  
  }
}
?>
<html>
<head>
<title>Orçamento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script language="JavaScript" src="scripts/scripts.js"></script>
<style type="text/css">
<? db_estilosite(); ?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">

<script>
	function js_calcula(contador,tp){
		var v = eval("document.form1.valor"+contador+".value");
		var t = eval("document.form1.valortotal"+contador+".value");
		var q = eval("document.form1.quant"+contador+".value");
    		
		if (tp == 1) {
		 t = v*q;
		 t = js_round(t,2);
		 eval("document.form1.valortotal"+contador+".value=t");
		} else{
		 v = t/q;
		 t = js_round(t,2);
		 eval("document.form1.valortotal"+contador+".value=t");
		 eval("document.form1.valor"+contador+".value=v");
		}
		
	}
	
	function js_imprime(orc,cgm,origem){
		if(origem=='1'){
			jan = window.open('com2_solorc002.php?cgm='+cgm+'&pc20_codorc='+orc,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		}else{
			jan = window.open('com2_procorc002.php?cgm='+cgm+'&pc20_codorc='+orc,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		}
	}
	
	function js_volta(cgm){
		
		location.href='for_orcamento.php?id_usuario='+cgm;
	}
</script>

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<form name="form1" method="post" action="">

<table width='100%' align='center' border='0' cellpadding='0' cellspacing='0'>

<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr bgcolor="<?=$w01_corfundomenu?>" class="titulo">
	<td colspan="2"  align="center" >Dados do Orçamento</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr class="texto">
	<td colspan="2">Orçamento:<?=$orc?></td>
</tr>

<tr class="texto">
	<td >Data limite: <?echo db_formatar($pc20_dtate,'d'); ?> </td>
	<td >Hora limite: <?=$pc20_hrate?></td>
</tr>

<tr bgcolor="<?=$w01_corfundomenu?>" class="titulo">
	<td colspan="2" align="center">Dados do fornecedor</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr class="texto">
	<td >Nome: <?=$z01_nome?></td>
	<td >CNPJ: <?=$z01_cgccpf?></td>
</tr>

<tr bgcolor="<?=$w01_corfundomenu?>" class="titulo">
	<td colspan="2" align="center">Dados do produto</td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>

<?
if ((isset($alterar)) || (isset($incluir))){ // se clicou no alterar ou incluir... mostra  o resultado
$sql= "select z01_cgccpf,
              z01_nome,
              pc20_dtate,
              pc20_hrate,
              pc21_prazoent,
              pc21_validadorc  
		     from pcorcamforne 
		    inner join cgm     on pc21_numcgm = z01_numcgm 
		    inner join pcorcam on pc20_codorc = pc21_codorc
		    where pc21_numcgm=$cgm 
		      and pc21_codorc = $orc";
		
$result= db_query($sql);
db_fieldsmemory($result,0);
	
if ($sol==1) {// se for orçamento por solicitação
$sol= 1;

$sqlitens="select pc11_codigo,
                  pc11_quant,
                  pc01_descrmater,
                  pc11_resum,
                  pc11_pgto,
                  pc11_prazo,
                  pc11_seq,
                  pc10_numero,
                  m61_usaquant,
                  m61_descr,
		              pc17_codigo,
		              pc17_quant,
		              pc05_servico,
		              pc29_orcamitem,
		              pc23_valor,
		              pc23_obs,
		              pc23_vlrun,
		              pc23_validmin 
			       from pcorcamitemsol 
			      inner join pcorcamitem      on pcorcamitem.pc22_orcamitem      = pcorcamitemsol.pc29_orcamitem
			       left join pcorcamval       on pcorcamitem.pc22_orcamitem      = pc23_orcamitem
			      inner join pcorcam          on pcorcam.pc20_codorc             = pcorcamitem.pc22_codorc 
			      inner join solicitem        on solicitem.pc11_codigo           = pcorcamitemsol.pc29_solicitem 
			      inner join solicita         on solicita.pc10_numero            = solicitem.pc11_numero 
			       left join solicitemunid    on solicitemunid.pc17_codigo       = solicitem.pc11_codigo 
			       left join matunid          on matunid.m61_codmatunid          = solicitemunid.pc17_unid 
			       left join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo 
			       left join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater 
			       left join pcsubgrupo       on pcsubgrupo.pc04_codsubgrupo     = pcmater.pc01_codsubgrupo 
			       left join pctipo           on pctipo.pc05_codtipo             = pcsubgrupo.pc04_codtipo
			      where pc22_codorc     = $orc 
			        and pc23_orcamforne = $forne
			      order by pc11_seq";
			
$result= db_query($sqlitens);
$linhas= pg_num_rows($result);

} else { 
	$sol= 2;	

	$sqlproc="select pc11_seq,
	                 pc11_resum,
	                 pc11_codigo,
	                 pc11_vlrun,
	                 pc11_quant,
	                 pc01_descrmater,
	                 pc22_orcamitem,
	                 pc31_orcamitem,
				           pc23_valor,
				           pc23_obs,
				           pc23_vlrun,
				           pc23_validmin
				      from pcorcamitem 
				     inner join pcorcam          on pcorcam.pc20_codorc             = pcorcamitem.pc22_codorc 
				     inner join pcorcamitemproc  on pcorcamitemproc.pc31_orcamitem  = pcorcamitem.pc22_orcamitem 
				     inner join pcprocitem       on pcprocitem.pc81_codprocitem     = pcorcamitemproc.pc31_pcprocitem 
				      left join pcorcamval       on pcorcamitemproc.pc31_orcamitem  = pc23_orcamitem
				     inner join solicitem        on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem 
				      left join solicitempcmater on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo 
				      left join pcmater          on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater 
				     where pc20_codorc     = $orc 
				       and pc23_orcamforne = $forne
				     order by pc22_orcamitem";
			
  $result= db_query($sqlproc);
  $linhas= pg_num_rows($result);

}	

?>
<tr class="texto">
 	<td>Prazo de entrega:<?if($pc21_prazoent!=""){ echo db_formatar($pc21_prazoent, 'd');}?>
	</td>
	<td>Validade do orçamento:<? if($pc21_validadorc!=""){ echo db_formatar($pc21_validadorc, 'd');}?>
	</td>
	<td></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2">
		<table width='90%' align='center' class="tab" >
		<tr>
			<th>Item</th>
			<th>Quant.</th>
			<th>Material ou serviço</th>
			<th>Obs.</th>
			<th>Validade mínima</th>
			<th>Valor unitário</th>
			<th>Valor total</th>
			
		</tr>
		<?for ($i = 0; $i < $linhas; $i ++) {
			db_fieldsmemory($result,$i); ?>
		<tr>
		
		<? // mostra antes de imprimir
		echo"
			<td>$pc11_seq</td>
			<td>$pc11_quant  <input name='quant$i' type='hidden' value='$pc11_quant' > </td> 
			<td>$pc01_descrmater <br>Resumo: $pc11_resum</td> "; ?>
			<td><? if ($pc23_obs==""){?>&nbsp<?}else{echo $pc23_obs;} ?></td>
			<td><? if($pc23_validmin!=""){ echo db_formatar($pc23_validmin, 'd');}else{?>&nbsp<?}?></td>
			<td> <? echo db_formatar($pc23_vlrun, 'f'); ?> </td>
			<td><?echo db_formatar($pc23_valor, 'f'); ?> </td>	
			
		
		</tr>
		<?}?>
		</table>
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td colspan="2" align="center">
		<input name='imprimir' type='button' value="Imprimir orçamento" class="botao" onclick="js_imprime(<?=$orc?>,<?=$cgm?>,<?=$sol?>)">
		<input name="voltar" type="button" value="Voltar"class="botao" onclick="js_volta(<?=$cgm?>)">
	</td>
</tr>

<?
}else{ // se não clicou no alterar ou incluir
?>

<tr class="texto">
<?

		$dia1[$i]="";
		$mes1[$i]="";
		$ano1[$i]="";
	 	if($pc21_validadorc!= ""){
						$data = split("-",$pc21_validadorc);
						$dia1[$i] = $data[2];
						$mes1[$i] = $data[1];	
						$ano1[$i] = $data[0];
		}
		
		$dia2[$i]="";
		$mes2[$i]="";
		$ano2[$i]="";
	 	if($pc21_prazoent!= ""){
						$data = split("-",$pc21_prazoent);
						$dia2[$i] = $data[2];
						$mes2[$i] = $data[1];	
						$ano2[$i] = $data[0];
		}
		
//db_inputdata($nome, $dia = "", $mes = "", $ano = "", $dbcadastro = true, $dbtype = 'text', $db_opcao = 3		
?>
	<td>Prazo de entrega: <?db_inputdata("p",$dia2[$i],$mes2[$i],$ano2[$i],true,"text",1)?>
	</td>
	<td>Validade do orçamento: <?db_inputdata("v",$dia1[$i],$mes1[$i],$ano1[$i],true,"text",1)?>
	</td>
	<td></td>
</tr>
<tr>
	<td colspan="2">&nbsp;</td>
</tr>
<tr>
	<td colspan="2">
		<table width='90%' align='center' class='tab'>
		<tr>
			<th>Item</th>
			<th>Quant.</th>
			<th>Material ou serviço</th>
			<th>Obs.</td>
			<th>Validade mínima</th>
			<th>Valor unitário</th>
			<th>Valor total</th>
			
		</tr>
		<?
		for ($i = 0; $i < $linhas; $i ++) {
			db_fieldsmemory($result,$i); ?>
		<tr class="texto">
		
		<?
		$dia[$i]="";
		$mes[$i]="";
		$ano[$i]="";
	 	
	 	if($pc23_validmin!= ""){
						$data = split("-",$pc23_validmin);
						$dia[$i] = $data[2];
						$mes[$i] = $data[1];	
						$ano[$i] = $data[0];
						
		}
		     //db_input($nome, $dbsize, $dbvalidatipo, $dbcadastro, $dbhidden = 'text', $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "", $css="") {
		?>
		 <td><?=$pc11_seq?></td>
 		 <td><?=$pc11_quant?>  <input name="quant<?=$i?>" type='hidden' value="<?=$pc11_quant?>" > </td> 
		 <td><?=$pc01_descrmater?> <br>Resumo: <?=$pc11_resum?></td> 
		 <td><input name="obs<?=$i?>" type='text' size='25' value="<?=$pc23_obs?>" >  </td>
		 <td width='135px' align='center'>
		    <?db_inputdata("o$i",$dia[$i],$mes[$i],$ano[$i],true,"text",1)?>
		 </td>
		 <td>
		  <input name="valor<?=$i?>" type='text' style="text-align:right" size='10' value="<?=$pc23_vlrun?>" onBlur="js_calcula(<?=$i?>,1)" onKeyUp="js_ValidaCampos(this,4,'Valor Unitário',false,false,event);"> 
		 </td>
		 <td>
 		  <input name="valortotal<?=$i?>" type='text' style="text-align:right" size='10' value="<?=$pc23_valor?>" onBlur="js_calcula(<?=$i?>,2)">   
		 </td>	
		</tr>
		<?}?>
		</table>
	</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
	<td colspan="2" align="center">
	<?
	if ($pc23_vlrun==""){
		echo "<input name='incluir' type='submit' value='Incluir orçamento' class='botao' >";
	}else{
		echo"<input name='alterar' type='submit' value='Alterar orçamento' class='botao' >";
	}
	?>
		
	<input name="voltar" type="button" value="Voltar" class="botao" onclick="js_volta(<?=$cgm?>)">	
	</td>
</tr>
<?
}

?>
</table>
</form>
</body>
</html>