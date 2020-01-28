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
$tipo= $_SESSION["itbitipo"]; 
$cod=@$_SESSION["itbi"];
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_itbi_classe.php");
include("classes/db_itburbano_classe.php");
include("classes/db_itbimatric_classe.php");
include("classes/db_itbipropriold_classe.php");
include("classes/db_propri_classe.php");
include("classes/db_itbidadosimovel_classe.php");
include("classes/db_itbinome_classe.php");
include("classes/db_itbinomecgm_classe.php");


$clitbi            = new cl_itbi;
$clitburbano       = new cl_itburbano;
$clitbimatric      = new cl_itbimatric;
$clitbipropriold   = new cl_itbipropriold;
$clpropri          = new cl_propri;
$clitbidadosimovel = new cl_itbidadosimovel;
$clitbinome        = new cl_itbinome;
$clitbinomecgm     = new cl_itbinomecgm;


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
postmemory($HTTP_POST_VARS);
//echo "tipo = $tipo ";

if($cod!=""){
	echo "
			<script>
           		parent.document.form1.disabilitado.value='nao';
				parent.trocacor('1');
			</script>
		";
}

if (isset($incluir)){
	if($incluir=="Incluir"){
	
	db_inicio_transacao();
// inclui na itbi..................................................
	$sqlerro = false;
	$clitbi->it01_data                = date("Y-m-d");
	$clitbi->it01_hora                = date("H:i");
	$clitbi->it01_tipotransacao       = $it01_tipotransacao;
	$clitbi->it01_areaterreno		  = $it01_areaterreno;
	$clitbi->it01_areaedificada   	  = $it01_areaedificada;
	$clitbi->it01_obs				  = $it01_obs;
	$clitbi->it01_valortransacao      = $it01_valortransacao;
	$clitbi->it01_areatrans   		  = $it01_areatrans;
	$clitbi->it01_mail				  = $it01_mail;
	$clitbi->it01_valortransacaofinanc= $it01_valortransacaofinanc;   
	$clitbi->it01_finalizado          = false;
	$clitbi->incluir(null);	
	
	if ($clitbi->erro_status == 0) {
				$sqlerro = true;
				die($clitbi->erro_sql);
				$erro_msg = $clitbi->erro_msg;
	}
		
	if($sqlerro == false) {

// inclui na itburbano ...........................................   	
	    $clitburbano->it05_guia         = $clitbi->it01_guia;
	    $clitburbano->it05_frente 		= $it05_frente;
	    $clitburbano->it05_fundos   	= $it05_fundos;
	    $clitburbano->it05_direito		= $it05_direito;
	    $clitburbano->it05_esquerdo 	= $it05_esquerdo;    
	    $clitburbano-> it05_itbisituacao= $it05_itbisituacao;
	    $clitburbano->incluir($clitbi->it01_guia);
	    
	    if ($clitburbano->erro_status == 0) {
				$sqlerro = true;
				die($clitburbano->erro_sql);
				$erro_msg = $clitburbano->erro_msg;
		}
	
	    //******************
// inclui na itbimatric ............................................	    	
	    	$clitbimatric->incluir($clitbi->it01_guia,$mat);
	    	if ($clitbimatric->erro_status == 0) {
				$sqlerro = true;
				die($clitbimatric->erro_sql);
				$erro_msg = $clitbimatric->erro_msg;
			}
	
			if($sqlerro == false) {
				$res = $clpropri->sql_record($clpropri->sql_query($mat));
				if($clpropri->numrows > 0){
			    	for($m=0;$m<$clpropri->numrows;$m++){ 
				        db_fieldsmemory($res,$m);
// inclui na itbipropriold ........ proprietarios .......................			        
						$clitbipropriold->it20_numcgm = $j42_numcgm;
						$clitbipropriold->it20_pri = 'f';
						$cgm = $j42_numcgm;
						$clitbipropriold->incluir($clitbi->it01_guia,$cgm);
				      				    
					    if ($clitbipropriold->erro_status == 0) {
							$sqlerro = true;
							die($clitbipropriold->erro_sql);
							$erro_msg = $clitbipropriold->erro_msg;
						}
			    	}
					if($sqlerro == false) {
// inclui na itbipropriold .... proprietario principal .................				
						$clitbipropriold->it20_numcgm = $j01_numcgm;
					    $clitbipropriold->it20_pri = 't';
					    $cgm = $j01_numcgm;
					    $clitbipropriold->incluir($clitbi->it01_guia,$cgm);
						if ($clitbipropriold->erro_status == 0) {
							$sqlerro = true;
							die($clitbipropriold->erro_sql);
							$erro_msg = $clitbipropriold->erro_msg;
						}
						

					}
					
				}
			}
			
	    
  	}
  	
// inclui itbidadosimovel.....dados do imóvel .................
  	
  	$rsdadosimovel =  db_query("select * from proprietario
                   				inner join itbimatric on it06_matric = j01_matric
				                where it06_guia = ".$clitbi->it01_guia);
		$numdados = pg_numrows($rsdadosimovel);
		if($numdados > 0){
			db_fieldsmemory($rsdadosimovel,0);
			$clitbidadosimovel->it22_itbi        = $clitbi->it01_guia;
			$clitbidadosimovel->it22_setor       = $j34_setor;
			$clitbidadosimovel->it22_quadra      = $j34_quadra;
			$clitbidadosimovel->it22_lote        = $j34_lote;
			$clitbidadosimovel->it22_descrlograd = $nomepri;
			$clitbidadosimovel->it22_numero      = $j39_numero;
			$clitbidadosimovel->it22_compl       = $j39_compl;
	        $clitbidadosimovel->incluir($it22_sequencial);
	 	    if($clitbidadosimovel->erro_status == "0"){
	 			$erro = "itbidadosimovel : ".$clitbidadosimovel->erro_msg;
	 			$sqlerro = true;
		    }

// inclui na itbinome............I N C L U I   O   P R O P R I E T A R I O(visao proprietario)   C O M O   T R A N S M I T E N T E   P R I N C I P A L 

			$rsdadosimovel =  db_query("select z01_cxpostal, z01_email from itbimatric
										inner join iptubase on it06_matric = j01_matric
										inner join cgm on z01_numcgm = j01_numcgm
										where it06_guia = ".$clitbi->it01_guia);
			$numdados = pg_numrows($rsdadosimovel);
			if($numdados > 0){
				db_fieldsmemory($rsdadosimovel,0);
			}
       
			$clitbinome->it03_guia     = $clitbi->it01_guia;
			$clitbinome->it03_tipo     = 't';
			$clitbinome->it03_princ    = 'true'; 
			$clitbinome->it03_nome     = $z01_nome;
			$clitbinome->it03_sexo     = 'M';
			$clitbinome->it03_cpfcnpj  = $z01_cgccpf;
			$clitbinome->it03_endereco = $z01_ender;
			$clitbinome->it03_numero   = $z01_numero;
			$clitbinome->it03_compl    = $z01_compl;
			$clitbinome->it03_cxpostal = $z01_cxpostal;
			$clitbinome->it03_bairro   = $z01_bairro;
			$clitbinome->it03_munic    = $z01_munic;
			$clitbinome->it03_uf       = $z01_uf;
			$clitbinome->it03_cep      = $z01_cep;
			$clitbinome->it03_mail     = $z01_email; 

		    $clitbinome->incluir($it03_seq);
		    if($clitbinome->erro_status == '0'){
	 			$erro = "Proprietario itbinome : ".$clitbinome->erro_msg;
	 			$sqlerro = true;
		    }

	        $clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
			$clitbinomecgm->it21_numcgm   = $z01_numcgm;
	        $clitbinomecgm->incluir($it21_sequencial);
			if($clitbinomecgm->erro_status == "0"){
		 		$erro = "Proprietario itbinomecgm : ".$clitbinomecgm->erro_msg;
		 		$sqlerro = true;
			}
   		}// do select  dos dados do imovel

//................ I N C L U I   O U T R O S   P R O P R I E T A R I O S(propri)   C O M O   O U T R O S   T R A N S M I T E N T E S **************************************************************/

		$rsoutros =  db_query("select * from propri
                 			   inner join itbimatric on it06_matric = j42_matric
                               inner join cgm        on z01_numcgm  = j42_numcgm
				               where it06_guia = ".$clitbi->it01_guia);
		$numoutros = pg_numrows($rsoutros);
		if($numdados > 0){
       		for($i=0;$i<$numoutros;$i++){            
				db_fieldsmemory($rsoutros,$i);
//				
				$clitbinome->it03_guia     = $clitbi->it01_guia;
				$clitbinome->it03_tipo     = 't';
				$clitbinome->it03_princ    = 'false'; 
				$clitbinome->it03_nome     = $z01_nome;
				$clitbinome->it03_sexo     = 'M';
				$clitbinome->it03_cpfcnpj  = $z01_cgccpf;
				$clitbinome->it03_endereco = $z01_ender; 
				$clitbinome->it03_numero   = $z01_numero;
				$clitbinome->it03_compl    = $z01_compl;
				$clitbinome->it03_cxpostal = $z01_cxpostal;
				$clitbinome->it03_bairro   = $z01_bairro;
				$clitbinome->it03_munic    = $z01_munic;
				$clitbinome->it03_uf       = $z01_uf;
				$clitbinome->it03_cep      = $z01_cep;
				$clitbinome->it03_mail     = $z01_email; 
     			$clitbinome->incluir($it03_seq);
				if($clitbinome->erro_status == '0'){
					$erro = "Outros proprietarios itbinome : ".$clitbinome->erro_msg;
					$sqlerro = true;
				}
				$clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
				$clitbinomecgm->it21_numcgm   = $z01_numcgm;
				$clitbinomecgm->incluir($it21_sequencial);
				if(isset($clitbinomecgm->erro_status) && $clitbinomecgm->erro_status == 0){
					  $erro = "Outros proprietarios itbinomecgm : ".$clitbinomecgm->erro_msg;
					  $sqlerro = true;
				}
            }
		}
  	
  	
  	
  	
// fim da transação.... se tiver ok mostra a msg e chama a outra tela  	
	db_fim_transacao($sqlerro);	
	if ($sqlerro==false){
		$codigo=$clitbi->it01_guia;
	    msgbox("ITBI $codigo incluida com sucesso");
	    session_register("itbi");
		$_SESSION["itbi"] = $codigo;
		
		 echo "
			<script>
           		parent.document.form1.disabilitado.value='nao';
				location.href = 'itbi_dadosimovel.php';
				parent.trocacor('2');
			</script>
		";
		
	}	
	}
}//fim do incluir

if (isset($incluir)){
	if($incluir=="Alterar"){
		
		$clitbi->it01_guia = $cod;
		$clitbi->it01_tipotransacao       = $it01_tipotransacao;
		$clitbi->it01_areaterreno		  = $it01_areaterreno;
		$clitbi->it01_areaedificada   	  = $it01_areaedificada;
		$clitbi->it01_obs				  = $it01_obs;
		$clitbi->it01_valortransacao      = $it01_valortransacao;
		$clitbi->it01_areatrans   		  = $it01_areatrans;
		$clitbi->it01_mail				  = $it01_mail;
		$clitbi->it01_valortransacaofinanc= $it01_valortransacaofinanc;      
		$clitbi->alterar($cod);	
	}
	
	    $clitburbano->it05_guia         = $cod;
	    $clitburbano->it05_frente 		= $it05_frente;
	    $clitburbano->it05_fundos   	= $it05_fundos;
	    $clitburbano->it05_direito		= $it05_direito;
	    $clitburbano->it05_esquerdo 	= $it05_esquerdo;    
	    $clitburbano-> it05_itbisituacao= $it05_itbisituacao;
	    $clitburbano->alterar($cod);
	
}
?>
<html>
<style type="text/css">
<?db_estilosite(); ?>
</style>
<head>
<title>Cadastro de departamento</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function js_verifica(){
	var obj    = document.form1;
	var ter    = obj.it01_areaterreno.value;
	var edi    = obj.it01_areaedificada.value;
	var vltran = obj.it01_valortransacao.value;
	var vltranf= obj.it01_valortransacaofinanc.value;
	var areatra= obj.it01_areatrans.value;
	var fre    = obj.it05_frente.value;
	var fun    = obj.it05_fundos.value;
	var dir    = obj.it05_direito.value;
	var esq    = obj.it05_esquerdo.value;
	
	if(isNaN(ter)){
	    alert("verifique o valor informado para o campo Área do terreno.");
	    document.form1.it01_areaterreno.value="";
	    document.form1.it01_areaterreno.focus();
	    return false;
	}
	if(isNaN(edi)){
	    alert("verifique o valor informado para o campo Área edificada.");
	    document.form1.it01_areaedificada.value="";
	    document.form1.it01_areaedificada.focus();
	    return false;
	}
	if(isNaN(vltranf)){
	    alert("verifique o valor informado para o campo Valor da transação financiado.");
	    document.form1.it01_valortransacaofinanc.value="";
	    document.form1.it01_valortransacaofinanc.focus();
	    return false;
	}
	if(isNaN(vltran)){
	    alert("verifique o valor informado para o campo Valor da transação à vista.");
	    document.form1.it01_valortransacao.value="";
	    document.form1.it01_valortransacao.focus();
	    return false;
	}
	if(isNaN(areatra)){
	    alert("verifique o valor informado para o campo Área transferida.");
	    document.form1.it01_areatrans.value="";
	    document.form1.it01_areatrans.focus();
	    return false;
	}
	if(isNaN(fre)){
	    alert("verifique o valor informado para o campo frente.");
	    document.form1.it05_frente.value="";
	    document.form1.it05_frente.focus();
	    return false;
	}
	if(isNaN(fun)){
	    alert("verifique o valor informado para o campo fundos.");
	    document.form1.it05_fundos.value="";
	    document.form1.it05_fundos.focus();
	    return false;
	}
	if(isNaN(dir)){
	    alert("verifique o valor informado para o campo Lado direito.");
	    document.form1.it05_direito.value="";
	    document.form1.it05_direito.focus();
	    return false;
	}
	if(isNaN(esq)){
	    alert("verifique o valor informado para o campo Lado esquerdo.");
	    document.form1.it05_esquerdo.value="";
	    document.form1.it05_esquerdo.focus();
	    return false;
	}
	
	var erro = "";
	if (ter=='')    erro = erro+' Área do terreno\n';
	if (edi=='')    erro = erro+' Área edificada\n';
	if (vltran=='') erro = erro+' Valor da transação\n';
	if (areatra=='')erro = erro+' Área transmitida da terreno\n';
	if (fre=='') erro = erro+' Frente\n';
	if (fun=='') erro = erro+' Fundos\n';
	if (dir=='') erro = erro+' Lado Direito\n';
	if (esq=='') erro = erro+' Lado Esquerdo\n';
	
		
	if(erro!=""){
		alert('Preencha os Campos: ' +erro);	
		return false;
	}else{
	 return true;
	}
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" >
<form name="form1" method="post" action="">

<table width="70%" border="0" cellspacing="2" cellpadding="2" align="center">
	<tr class="titulo">
    	<td colspan="2" align="center" >
    	ITBI Urbana
      	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" width="30%">Código da guia itbi:
    	</td>
    	<td align="left" ><?=@$cod?>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Tipo de Transação:
    	</td>
    	<td align="left"" >
    	<select name="it01_tipotransacao"  >
      <?
		  
		  $sqltipo = "select * from itbitransacao";
	      $resulttipo= db_query($sqltipo);
	  	  $linhastipo= pg_num_rows($resulttipo);
	 	  for($i=0;$i<$linhastipo;$i++){
		  db_fieldsmemory($resulttipo,$i);
	  	  echo "<option value='$it04_codigo'>$it04_descr</option>";
	  }
	  ?>
        </select>
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Área do terreno:
    	</td>
    	<td align="left" ><input name="it01_areaterreno" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Área edificada:
    	</td>
    	<td align="left" ><input name="it01_areaedificada" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Observação dadas pelo comprador:
    	</td>
    	<td align="left" ><textarea name="it01_obs" cols="60" rows="3" ></textarea>
    	</td>
  	</tr>
  	<tr class="texto"> 
    	<td align="left" >Valor da transação à vista:
    	</td>
    	<td align="left" ><input name="it01_valortransacao" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Valor da transação financiado:
    	</td>
    	<td align="left" ><input name="it01_valortransacaofinanc" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Área transmitida do terreno:
    	</td>
    	<td align="left" ><input name="it01_areatrans" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Email de contato:
    	</td>
    	<td align="left" ><input name="it01_mail" type="text" >
    	</td>
  	</tr>
  	
  	<tr class="texto">
    	<td align="left" >Frente:
    	</td>
    	<td align="left" ><input name="it05_frente" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Fundos:
    	</td>
    	<td align="left" ><input name="it05_fundos" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Lado direito:
    	</td>
    	<td align="left" ><input name="it05_direito" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Lado esquerdo:
    	</td>
    	<td align="left" ><input name="it05_esquerdo" type="text" >
    	</td>
  	</tr>
  	<tr class="texto">
    	<td align="left" >Situação da ITBI:
    	</td>
    	<td align="left" >
		<select name="it05_itbisituacao"  >
     <?
		  $sqlsit = "select * from itbisituacao";
	      $resultsit= db_query($sqlsit);
	  	  $linhassit= pg_num_rows($resultsit);
	 	  for($i=0;$i<$linhassit;$i++){
		  db_fieldsmemory($resultsit,$i);
	  	  echo "<option value='$it07_codigo'>$it07_descr</option>";
	  }
	  ?>
        </select>
    	
    	</td>
  	</tr>

  	<tr class="texto">
    	<td align="left" >&nbsp;
    	</td>
    	<td align="left" ><input name="incluir" type="submit" value="Incluir" class="botao" onClick="return js_verifica()" >
    	</td>
  	</tr>
  	
</table>
</form>
<html>

<?
if($cod!=""){
	echo"
	<script>
		document.form1.incluir.value='Alterar';
	</script>
	";
	$sql= "select * from itbi where it01_guia=$cod";
	$result = db_query($sql);
	$linhas=pg_num_rows($result);
	if($linhas>0){
		db_fieldsmemory($result,0);
		echo"
		<script>
			document.form1.it01_areaterreno.value          =$it01_areaterreno;
			document.form1.it01_tipotransacao.value        =$it01_tipotransacao  ;
		 	document.form1.it01_areaedificada.value        =$it01_areaedificada ;
			document.form1.it01_obs.value                  ='$it01_obs ';
			document.form1.it01_valortransacaofinanc.value =$it01_valortransacaofinanc;
			document.form1.it01_valortransacao.value       ='$it01_valortransacao'; 
 			document.form1.it01_areatrans.value            ='$it01_areatrans '; 
 			document.form1.it01_mail.value                 ='$it01_mail '; 
		</script>
		";

	}
	$sqlurb= "select * from itburbano where it05_guia=$cod";
	$resulturb = db_query($sqlurb);
	$linhasurb=pg_num_rows($resulturb);
	if($linhasurb>0){
		db_fieldsmemory($resulturb,0);
		echo"
		<script>
			document.form1.it05_frente.value      =$it05_frente;
			document.form1.it05_fundos.value      =$it05_fundos;
		 	document.form1.it05_direito.value     =$it05_direito;
			document.form1.it05_esquerdo.value    =$it05_esquerdo;
			document.form1.it05_itbisituacao.value=$it05_itbisituacao;
		</script>
		";

	}
}
?>