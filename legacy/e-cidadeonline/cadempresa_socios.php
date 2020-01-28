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
include("dbforms/db_funcoes.php");
include("classes/db_dbprefcgm_classe.php");
include("classes/db_dbprefcgmrhcbo_classe.php");
include("classes/db_dbprefcgmcnae_classe.php");
include("classes/db_rhcbo_classe.php");
include("classes/db_cnae_classe.php");
include("classes/db_cnaeanalitica_classe.php");
include("classes/db_dbprefempresasocios_classe.php");
postmemory($HTTP_POST_VARS);
postmemory($HTTP_GET_VARS);
$cldbprefcgm       = new cl_dbprefcgm;
$cldbprefcgmcnae   = new cl_dbprefcgmcnae;
$cldbprefcgmrhcbo  = new cl_dbprefcgmrhcbo;
$clrhcbo           = new cl_rhcbo;
$clcnae            = new cl_cnae;
$clcnaeanalitica   = new cl_cnaeanalitica;
$cldbprefempresasocios   = new cl_dbprefempresasocios;

$clcnaeanalitica  -> rotulo->label();
$clcnae  -> rotulo->label();
$clrhcbo -> rotulo->label();
$cgm = $_SESSION["dbprefcgm"];
$dbprefempresa = $_SESSION["dbprefempresa"];
//$jatemcgm = 0;

if(isset($processar)){
  
  if($socio_cpf_cnpj==""){
    db_msgbox("Inclua um CPF/CNPJ.");
    
  }else{
      $cad_socio=1;
  
	  $sqlcpf= "select  *
				from dbprefcgm 
				where z01_cgccpf = '".$socio_cpf_cnpj."'";
	  //echo "1 - $sqlcpf";
	  $resultcpf= db_query($sqlcpf);
	  $linhascpf= pg_num_rows($resultcpf);
	  if($linhascpf>0){
	    $jatemcgm= 1;
	    db_fieldsmemory($resultcpf,0);
	    if($tipo_pessoa=='J'){
	        $q71_descr = $z01_profis ;  
	    }else{
	        $rh70_descr = $z01_profis ;      
	    }
	   	    
	  }else{
	    $sqlcpf= "select  z01_ender,z01_numero,z01_compl,z01_munic,z01_cep
				from dbprefcgm 
				where z01_cgccpf = '".$cpf_cnpj."'";
	    //echo "2 - $sqlcpf";
	    $resultcpf= db_query($sqlcpf);
		  $linhascpf= pg_num_rows($resultcpf);
		  if($linhascpf>0){
		    $jatemcgm= 1;
		    db_fieldsmemory($resultcpf,0);
		  }
	    
	    $jatemcgm= 0;
	  }
  }
 $q66_capital = ""; 
}

if(isset($incluir)){
    
  $sqlerro = 'false';

  // inclui em ambos... PJ e PF
  $cldbprefcgm -> z01_cep     = $z01_cep;
  $cldbprefcgm -> z01_ender   = $z01_ender;
  $cldbprefcgm -> z01_numero  = $z01_numero;
  $cldbprefcgm -> z01_compl   = $z01_compl;
  $cldbprefcgm -> z01_munic   = $z01_munic;
  $cldbprefcgm -> z01_bairro  = $z01_bairro;
//  $cldbprefcgm -> z01_telef   = $z01_telef;
 // $cldbprefcgm -> z01_fax     = $z01_fax;
//  $cldbprefcgm -> z01_telcel  = $z01_telcel;
 // $cldbprefcgm -> z01_email   = $z01_email;
 // $cldbprefcgm -> z01_cxpostal= $z01_cxpostal;
  $cldbprefcgm -> z01_situacao= 1;
  
  //########################## INCLUIR FISICA #############################
  if($tipo_pessoa=='F'){
    //Física
    db_query('BEGIN');
 
    $cldbprefcgm -> z01_nome   = "$z01_nome";
    $cldbprefcgm -> z01_cgccpf = $socio_cpf_cnpj1;
    if($jatemcgm == "1"){
      $cldbprefcgm ->z01_sequencial = $z01_sequencial;
      $cldbprefcgm -> alterar($z01_sequencial) ;
     // db_msgbox("alterar cgm");
    }else{
      $cldbprefcgm -> incluir_dbpref(null) ;
      $z01_sequencial = $cldbprefcgm->z01_sequencial;
      //db_msgbox("incluir cgm");
    }
    if ($cldbprefcgm->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefcgm->erro_msg;
    }
    
    //dbprefcgmrhcbo
	$sqlcbo = " select dbprefcgmrhcbo.z01_sequencial as dbprefcgmrhcbo
				from dbprefcgmrhcbo
				where z01_dbprefcgm = $z01_sequencial";
	$resultcbo = db_query($sqlcbo);
	$linhascbo = pg_num_rows($resultcbo);
	if($linhascbo>0){
	  //db_msgbox("alterar cbo");
	  db_fieldsmemory($resultcbo,0);
	  $cldbprefcgmrhcbo -> z01_sequencial = $dbprefcgmrhcbo ;
	  $cldbprefcgmrhcbo ->alterar($dbprefcgmrhcbo) ;
	}else{
	//db_msgbox("incluir cbo");
	$cldbprefcgmrhcbo -> z01_dbprefcgm =  $z01_sequencial;
	$cldbprefcgmrhcbo -> z01_hrcbo     =  $rh70_sequencial;
	$cldbprefcgmrhcbo -> incluir(null) ;
	}
    if ($cldbprefcgmrhcbo->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefcgmrhcbo->erro_msg;
    }
    
   
  }else{
   //########################## INCLUIR JURIDICA #############################
    db_query('BEGIN');
    //$pessoa=1;
    $cldbprefcgm -> z01_cgccpf    = $socio_cpf_cnpj1;
    $cldbprefcgm -> z01_nome      = $z01_nome;
    $cldbprefcgm -> z01_nomefanta = $z01_nomefanta;
 //   $cldbprefcgm -> z01_tipcre    = $z01_tipcre;
  //  $cldbprefcgm -> z01_incest    = $z01_incest;
 //   $cldbprefcgm -> z01_contato   = $z01_contato;
 //   $cldbprefcgm -> z01_profis    = $q71_descr;
    if($jatemcgm == "1"){
      $cldbprefcgm ->z01_sequencial = $z01_sequencial;
      $cldbprefcgm -> alterar($z01_sequencial) ;
    }else{
      $cldbprefcgm -> incluir_dbpref(null) ;
      $z01_sequencial = $cldbprefcgm->z01_sequencial;
    }
    if ($cldbprefcgm->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefcgm->erro_msg;
    }
    
    //dbprefcgmcnae
/*
    $cldbprefcgmcnae -> z01_dbprefcgm    = $cldbprefcgm-> z01_sequencial ;
    $cldbprefcgmcnae -> z01_cnaeanalitica= $q72_sequencial  ;
    $cldbprefcgmcnae -> incluir(null);
    if ($cldbprefcgmcnae->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefcgmcnae->erro_msg;
    }
   */ 
    
  }
  
  //ambos
	$cldbprefempresasocios -> q66_dbprefcgm     = $z01_sequencial ;
	$cldbprefempresasocios -> q66_dbprefempresa = $dbprefempresa ;
	$cldbprefempresasocios -> q66_tipocapital   = $q66_tipocapital ;
	$cldbprefempresasocios -> q66_tipo          = 1 ;
	$cldbprefempresasocios -> q66_capital       = $q66_capital ;
	$cldbprefempresasocios -> incluir(null);
	if ($cldbprefempresasocios->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefempresasocios->erro_msg;
    }
    if($sqlerro=="true"){
      db_query('ROLLBACK');
    }else{
      db_query('COMMIT');
    }
 
}
// ##############################  EXCLUIR ###################################
if(isset($excluir)){
	$cldbprefempresasocios -> q66_sequencial   = $seqsoc ;
	$cldbprefempresasocios -> excluir($seqsoc);
	if ($cldbprefempresasocios->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefempresasocios->erro_msg;
    }
}
// ##############################  quando lica no alterar da tabela para carregar o form ###################################
if(isset($alterar1)){
$cad_socio=1;
$sqlalt="
			select  dbprefcgm.*,
					dbprefempresasocios.* , 
					dbprefcgmrhcbo.z01_sequencial as seq_cbo,
					dbprefcgmcnae.z01_sequencial  as seq_cnae,
					dbprefcgm.z01_sequencial as seq_cgm,
					rhcbo.*
			from dbprefcgm 
			inner join dbprefempresasocios on dbprefcgm.z01_sequencial = q66_dbprefcgm
			left join dbprefcgmrhcbo on dbprefcgm.z01_sequencial =  dbprefcgmrhcbo.z01_dbprefcgm
			left join rhcbo on z01_hrcbo = rh70_sequencial
			left join dbprefcgmcnae  on dbprefcgm.z01_sequencial =  dbprefcgmcnae.z01_dbprefcgm
			where q66_sequencial = $seqsoc";
    //echo"sql".$sqlalt;
	$resultalt = db_query($sqlalt);
	$linhasalt = pg_num_rows($resultalt);
	if($linhasalt > 0){
	   db_fieldsmemory($resultalt,0);
	   $socio_cpf_cnpj = $z01_cgccpf;
	   if(strlen(trim($z01_cgccpf))==14){
	     $tipo_pessoa ='J';	   
	     $q71_descr = $z01_profis ;  
	   }else{
	     $tipo_pessoa ='F';	 
	     $rh70_descr = $z01_profis ;      
	   }
	}
}
// ##############################  ALTERAR #######################
if(isset($alterar)){
$sqlerro = 'false';
  // inclui em ambos... PJ e PF
  $cldbprefcgm -> z01_cep     = $z01_cep;
  $cldbprefcgm -> z01_ender   = $z01_ender;
  $cldbprefcgm -> z01_numero  = $z01_numero;
  $cldbprefcgm -> z01_compl   = $z01_compl;
  $cldbprefcgm -> z01_munic   = $z01_munic;
  $cldbprefcgm -> z01_bairro  = $z01_bairro;

    
  //########################## ALTERAR FISICA #############################
  if($tipo_pessoa=='F'){
    //Física
    db_query('BEGIN');
    
//    $z01_nasc = $z01_nasc_ano."-".$z01_nasc_mes."-".$z01_nasc_dia;
    $cldbprefcgm -> z01_nome   = "$z01_nome";
    $cldbprefcgm -> z01_sequencial = $seq_cgm;
    $cldbprefcgm -> alterar($seq_cgm) ;
    if ($cldbprefcgm->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefcgm->erro_msg;
    }
   
  }else{
   //########################## ALTERAR JURICICA ############################# 
	db_query('BEGIN');
    $cldbprefcgm -> z01_nome      = $z01_nome;
    $cldbprefcgm -> z01_nomefanta = $z01_nomefanta;
    $cldbprefcgm -> alterar($seq_cgm) ;
    if ($cldbprefcgm->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefcgm->erro_msg;
    }
    
  }
  
  //AMBOS
 if($sqlerro == "false"){
	$cldbprefempresasocios -> q66_sequencial    = $seqsoc;
	$cldbprefempresasocios -> q66_tipocapital   = $q66_tipocapital ;
	$cldbprefempresasocios -> q66_tipo          = 1;
	$cldbprefempresasocios -> q66_capital       = $q66_capital ;
	$cldbprefempresasocios -> alterar($seqsoc);
	if ($cldbprefempresasocios->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefempresasocios->erro_msg;
    }
 }
    if($sqlerro=="true"){
      db_query('ROLLBACK');
      //db_msgbox("roll");
    }else{
      db_query('COMMIT');
      // db_msgbox("com");
    }
}
// carregar tabela

    $sqlcarrega="
			select  q66_sequencial as seqsoc,
					z01_sequencial as seq, 
					z01_nome as nome,
					z01_cgccpf as cgccpf,
					q66_capital as capital
			from dbprefcgm 
			inner join dbprefempresasocios on z01_sequencial = q66_dbprefcgm
			where q66_dbprefempresa= $dbprefempresa";
    //die($sqlcarrega);
	$resultcarrega = db_query($sqlcarrega);
	$linhascarrega = pg_num_rows($resultcarrega); 


?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<style type="text/css"><?db_estilosite();?></style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0 	bgcolor="<?=$w01_corbody?>">
<center>
<form name="form1" method="post" action="">
<input name="seq_cgm" type= "hidden" value="<?=@$seq_cgm?>" >
<input name="seq_cbo" type= "hidden" value="<?=@$seq_cbo?>" >
<input name="seq_cnae" type= "hidden" value="<?=@$seq_cnae?>" >
<input name="seqsoc" type= "hidden" value="<?=@$seqsoc?>" >
<input name="socio_cpf_cnpj1" type= "hidden" value="<?=@$socio_cpf_cnpj?>" >
<input name="jatemcgm" type= "hidden" value="<?=@$jatemcgm?>" >
<input name="z01_sequencial" type= "hidden" value="<?=@$z01_sequencial?>" >
<table width="700px" border="0" cellspacing="2" cellpadding="2"	class="texto">
	<tr>
		<td width="200px">&nbsp;</td>
		<td width="500px">&nbsp;</td>
	</tr>
	<tr>
		
		<?if($pessoa=="F"){
		  $portefis='t';
		  echo"<td align='center'  colspan='2'><B>Pessoa Física &nbsp;&nbsp;&nbsp; CPF ".db_formatar($cpf_cnpj,"cpf")."</B></td>";
		}else{
		  $portefis='f';
		  echo"<td align='center' colspan='2' ><B>Pessoa Juridica &nbsp;&nbsp;&nbsp; CNPJ: ".db_formatar($cpf_cnpj,"cnpj")."</B></td>";
		}
		?>

	</tr>
	<tr>
		<td colspan="2">
		<fieldset >
		<legend>Sócio</legend>
		<table width="700px" border="0" cellspacing="2" cellpadding="2"	class="texto">
		   <tr>
		<td>Selecione o tipo de sócio</td>
		<td><select name="tipo_pessoa" >
			<?if($tipo_pessoa=='F'){
			  $selectedf = "selected";
			}else{
			 $selectedf = ""; 
			}
			if($tipo_pessoa=='J'){
			 $selectedj = "selected";
			}else{
			 $selectedj = ""; 
			}
			  ?>
			<option value="F" <?=$selectedf?> >Física</option>
			<option value="J" <?=$selectedj?> >Jurídica</option>
		</select></td>
	</tr>
	<tr>
		<td>CPF/CNPJ:</td>
		<td><input name="socio_cpf_cnpj" type="text" value="" size="20" maxlength="14" onchange="js_valida(this);">
			<input class="botao" type="submit" name="processar" value="Processar">
		</td>
	</tr>
		</table>
		</fieldset>
		</td>
	</tr>

	
	
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	
<?
if( @$cad_socio=="1"){ 
 ?> 
  <tr>
		<?if($tipo_pessoa=="F"){
		  $portefis='t';
		  echo"<td ><B>Sócio Pessoa Física</B></td>
				<td width='500px'><B>CPF ".db_formatar($socio_cpf_cnpj,"cpf")."</B></td>";
		}elseif($tipo_pessoa=='J'){
		  $portefis='f';
		  echo" <td ><B>Sócio Pessoa Jurídica</B></td>
				<td width='500px'><B>CNPJ: ".db_formatar($socio_cpf_cnpj,"cnpj")."</B></td>";
		}
		?>

	</tr>
 
	
  
  <?
  
  
	if($tipo_pessoa=='F'){
	 
	  ?>
	 <tr>
		<td>Nome:</td>
		<td><input name="z01_nome" type="text" value="<?=@$z01_nome?>"
			size="60"></td>
	</tr>
		
<?	  
	}elseif($tipo_pessoa=='J'){
	 
	  ?><caption>
	  	<tr>
		<td>Razão social:</td>
		<td><input name="z01_nome" type="text" value="<?=@$z01_nome?>"
			size="60"></td>
	</tr>
	<tr>
		<td>Nome fantasia:</td>
		<td><input name="z01_nomefanta" type="text"
			value="<?=@$z01_nomefanta?>" size="60"></td>
	</tr>
		  
	  <?
	}
	?>
	<tr>
		<td><?db_ancora("CEP","js_pesquisa_cep(true);",1);?></td>
		<td><input name="z01_cep" type="text" value="<?=@$z01_cep?>" size="15"
			onchange='js_pesquisa_cep(false);'></td>

	</tr>
	<tr>
		<td>Logradouro:</td>
		<td><input name="z01_ender" id="z01_ender" ntype="text"	value="<?=@$z01_ender?>" size="50"></td>
	</tr>
	<tr>
		<td>Número do imóvel:</td>
		<td><input name="z01_numero" type="text" value="<?=@$z01_numero?>" size="20"> 
		Complemento do imóvel: <input name="z01_compl" type="text" value="<?=@$z01_compl?>" size="20"></td>
	</tr>
	<tr>
		<td>Município:</td>
		<td><input name="z01_munic" type="text" value="<?=@$z01_munic?>"
			size="60"></td>
	</tr>
	<tr>
		<td>Bairro</td>
		<td><input name="z01_bairro" type="text" value="<?=@$z01_bairro?>"
			size="30"></td>
	</tr>
	<tr>
		<td>Tipo de Capital:</td>
		<td><select name="q66_tipocapital">
			<option value="1" <?=(@$q66_tipocapital=="1")?"selected":""?>>Valor</option>
			<option value="2" <?=(@$q66_tipocapital=="2")?"selected":""?>>Percentual</option>
		</select></td>
	</tr>
	<tr>
		<td>Capital:</td>
		<td><input name="q66_capital" type="text" value="<?=@$q66_capital?>" size="20"></td>
	</tr>
	
	<tr>
		<td colspan="2" align="center"><input class='botao' type='submit' name='incluir' value='Incluir'></td>
	</tr>
	<?
}
?>


<tr>
		<td colspan="2" align="center">
		<table align="center" cellpadding="2" cellspacing="0" class="tab">
			<tr>
				<th align="center">Sequencial</th>
				<th align="center">Nome</th>
				<th align="center">CPF/CNPJ</th>
				<th align="center">Percentual/Valor</th>
				<th align="center">Opção</th>
			</tr>
			<?

			if($linhascarrega>0){
			  for($i=0;$i<$linhascarrega;$i++){
			    db_fieldsmemory($resultcarrega,$i);
			    echo"<tr >";
			    echo"<td  align='center'  class='texto'>$seq</td>";
			    echo"<td  align='center'  class='texto'>$nome</td>";
			    echo"<td  align='center'  class='texto'>$cgccpf</td>";
			    echo"<td  align='center'  class='texto'>$capital</td>";
			    echo"<td  align='center'  width='150px' >
			<input type='submit' name='alterar1' value='Alterar' onClick='document.form1.seqsoc.value=$seqsoc' class='botao' >
			<input type='submit' name='excluir' value='Excluir'  onClick='document.form1.seqsoc.value=$seqsoc' class='botao' >
			</td>
			</tr>";
			  }
			}
			?>
		</table>


		</td>
	</tr>
</table>
</form>
</center>
</body>
</html>
<?
if(isset($incluir)){
		  if($sqlerro == "true"){
		    db_msgbox($erro_msg);
		  }else{
		    db_msgbox("Inclusão efetuada com sucesso.");
		  }
}
if(isset($alterar1)){
echo "
		<script>
		document.form1.incluir.value='Alterar';
		document.form1.incluir.name='alterar';
		</script>
	";
}
if(isset($alterar)){
		  if($sqlerro == "true"){
		    db_msgbox($erro_msg);
		  }else{
		    db_msgbox("Alteração efetuada com sucesso.");
		  }
}
?>
<script>
function js_valida(obj){ 
  
  if (!js_verificaCGCCPF(obj)){
    obj.value = '';
    obj.focus();  
  }
}
function js_pesquisa_cep(mostra){
  if(mostra==true){
  
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',true);
  }else{
     if(document.form1.z01_cep.value != ''){
        js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_cep.value+'&funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|codigo','Pesquisa',false);
     }else{     
       document.form1.z01_cep.value = ''; 
     }
  }
}


function js_preenchecep(chave,chave1,chave2,chave3,chave4){

  document.form1.z01_cep.value = chave;
  document.form1.z01_ender.value = chave1;
  document.form1.z01_munic.value = chave2;
  document.form1.z01_bairro.value = chave4;
  if(chave1!=""){
  	document.form1.z01_ender.readOnly = true;
  	document.form1.z01_ender.style.backgroundColor = '#CCCCCC';
  }
  if(chave2!=""){
  	document.form1.z01_munic.readOnly = true;
  	document.form1.z01_munic.style.backgroundColor = '#CCCCCC';
  }
  if(chave3!=""){
  	document.form1.z01_bairro.readOnly = true;
  	document.form1.z01_bairro.style.backgroundColor = '#CCCCCC';
  }
  db_iframe_cep.hide();
}
function js_pesquisa_cbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cbo','func_rhcbo.php?funcao_js=parent.js_mostracbo|rh70_estrutural|rh70_descr|rh70_sequencial','Pesquisa',true);
  }else{
     if(document.form1.rh70_estrutural.value != ''){
     //alert(); 
        js_OpenJanelaIframe('','db_iframe_cbo','func_rhcbo.php?pesquisa_chave='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostracbo2','Pesquisa',false);
     }else{     
       document.form1.rh70_estrutural.value = ''; 
     }
  }
}
function js_mostracbo(estrutural,descr,sequencial){
    db_iframe_cbo.hide();
	document.form1.rh70_estrutural.value=estrutural;
	document.form1.rh70_descr.value=descr;
	document.form1.rh70_sequencial.value=sequencial;
 
}
function js_mostracbo2(estrutural,descr,sequencial,erro){
//alert(chave+' -- '+erro);
    document.form1.rh70_estrutural.value=estrutural;
	document.form1.rh70_descr.value=descr;
	document.form1.rh70_sequencial.value=sequencial;
 
 if(erro== true){
   document.form1.rh70_estrutural.value = '';
   document.form1.rh70_sequencial.value='';
  }
  //document.form1.submit();
  
}
function js_pesquisa_cnae(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cnae','func_cnae.php?funcao_js=parent.js_mostracnae|q71_estrutural|q71_descr|q72_sequencial','Pesquisa',true);
  }else{
     if(document.form1.q71_estrutural.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cnae','func_cnae.php?pesquisa_chave='+document.form1.q71_estrutural.value+'&funcao_js=parent.js_mostracnae2','Pesquisa',false);
     }else{
       document.form1.q71_estrutural.value = ''; 
     }
  }
}
function js_mostracnae(estrutural,descr,sequencial){
  if(sequencial == ''){
  	  alert('Selecione uma atividade do tipo "Analitico".');
  }else{
	  db_iframe_cnae.hide();
	  document.form1.q71_estrutural.value=estrutural;
	  document.form1.q71_descr.value=descr;
	  document.form1.q72_sequencial.value=sequencial;
  }

}
function js_mostracnae2(estrutural,descr,sequencial,erro){
 if(erro == false){
  if(sequencial == ''){
  	  alert('Selecione uma atividade do tipo "Analitico".');
  }else{
	  document.form1.q71_estrutural.value=estrutural;
	  document.form1.q71_descr.value=descr;
	  document.form1.q72_sequencial.value=sequencial;
  }
 }else{
      document.form1.q71_estrutural.value='';
	  document.form1.q71_descr.value = descr;
	  document.form1.q72_sequencial.value='';
 }
}
</script>