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

//$func_iframe = new janela('func_nome','');
//$func_iframe->posX=1;
//$func_iframe->posY=20;
//$func_iframe->largura=780;
//$func_iframe->altura=430;
//$func_iframe->titulo='Pesquisa';
//$func_iframe->iniciarVisivel = false;
//$func_iframe->mostrar();
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<script>
function js_ValidaOperacao(municipio){

	var lRetorno = false;
	if (municipio) {
		lRetorno = js_ruacorreta();
	}	else {
		lRetorno = js_ruacorreta1();
	}	

	if (lRetorno == true) {
		
		if (document.form1.z01_cpf.value != '') {
			
    	if (document.form1.z01_cpf.value != '00000000000') { 
    		lRetorno = js_verificaCGCCPF(document.form1.z01_cpf);
    	}
    	
		} else if (document.form1.z01_cgc.value != '') {
			
			if (document.form1.z01_cpf.value != '00000000000000') {
		    lRetorno = js_verificaCGCCPF(document.form1.z01_cgc);
		  }
			  
		}	
		
	}	

	return lRetorno;
	
}

function js_ruacorreta(){
	rua = document.form1.j14_codigo.value;
  nome = document.form1.z01_nome.value;
  cep = document.form1.z01_cep.value;
  if(rua == "" || rua == 'undefined'){
    //alert('Atualmente apenas a descri��o do logradouro est� no cadastro deste contribuinte. Clique em OK para escolher o logradouro baseado no cadastro imobili�rio do munic�pio. O nome do logradouro ja vir� preenchido, bastando clicar em pesquisar.');
//    document.form1.z01_ender.value = '';
    document.form1.z01_ender.select();
    document.form1.z01_ender.focus();
    js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_ruas','func_ruas_alt.php?nomerua='+ document.form1.z01_ender.value+'&rural=1&funcao_js=parent.js_preenchepesquisa|j14_codigo|j14_nome|cep','Pesquisa',true);
    return false;
  }else{
    if(nome == "" || nome == 'undefined'){
      alert('Campo Nome/Raz�o Social � obrigat�rio!');
      document.form1.z01_nome.value = '';
      document.form1.z01_nome.focus();
      return false;
    }else{
      if(cep == "" || cep == 'undefined'){
	alert('Campo CEP � obrigat�rio!');
	document.form1.z01_cep.value = '';
	document.form1.z01_cep.focus();
	return false;
      }else{
	return true;
      }
    }
  }
return false;
}
function js_ruacorreta1(){
  document.form1.j14_codigo.value = '';
  rua1= document.form1.z01_ender.value;
  nome = document.form1.z01_nome.value;
  cep = document.form1.z01_cep.value;
  if(rua1 == "" || rua1 == 'undefined'){
    alert('Campo endere�o � obrigat�rio!');
    document.form1.z01_ender.value = '';
    document.form1.z01_ender.focus();
    return false;
  }else{
    if(nome == "" || nome == 'undefined'){
      alert('Campo Nome/Raz�o Social � obrigat�rio!');
      document.form1.z01_nome.value = '';
      document.form1.z01_nome.focus();
      return false;
    }else{
      if(cep == "" || cep == 'undefined'){
	alert('Campo CEP � obrigat�rio!');
	document.form1.z01_cep.value = '';
	document.form1.z01_cep.focus();
	return false;
      }else{
	return true;
      }
    }
  }
return false;
}
function js_preenche(chave){
  func_nome.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_func_nome(){

  js_OpenJanelaIframe("top.corpo",'func_nome','func_nome.php?funcao_js=parent.js_preenche|0','Pesquisa',true);

//  func_nome.jan.location.href = 'func_nome.php?funcao_js=parent.js_preenche|0';
//  func_nome.mostraMsg();
//  func_nome.show();
//  func_nome.focus();
}

</script>
<?
  db_postmemory($HTTP_POST_VARS);
  $clcgm = new cl_cgm;
  $cldb_cgmruas = new cl_db_cgmruas;
  $cldb_cgmbairro = new cl_db_cgmbairro;
  $cldb_cgmcpf = new cl_db_cgmcpf;
  $cldb_cgmcgc = new cl_db_cgmcgc;
  $cl_cepmunic = new cl_db_cepmunic;
  $cl_ruascep = new cl_ruascep;
  $clcgm->rotulo->label();
  $cldb_cgmruas->rotulo->label();
  $cldb_cgmbairro->rotulo->label();
  $cldb_cgmcgc->rotulo->label("z01_cgc");
  $cldb_cgmcpf->rotulo->label("z01_cpf");
  $clrotulo = new rotulocampo;
  $clrotulo->label("DBtxt1");
  $clrotulo->label("DBtxt5");  
  $clrotulo->label("DBtxt29");
  $clrotulo->label("DBtxt32");
  $clrotulo->label("DBtxt33");
  db_fieldsmemory(db_query("select * from db_config where codigo = " . db_getsession("DB_instit")),0);
  if($db_opcao == 2 && (isset($z01_numcgm) || isset($numcgm_cgccpf))){
    if(isset($numcgm_cgccpf))
      $z01_numcgm = $numcgm_cgccpf;
    $result_cgmruas = $cldb_cgmruas->sql_record($cldb_cgmruas->sql_query($z01_numcgm,"*",""," db_cgmruas.z01_numcgm = $z01_numcgm"));
    $numrows_cgmruas=$cldb_cgmruas->numrows;
    if($cldb_cgmruas->numrows > 0 && !isset($municipio)){
      $municipio = "t";
    }elseif(!isset($municipio)){
      if(strtoupper($munic) == strtoupper($z01_munic) && strtoupper($uf) == strtoupper($z01_uf)){
        $municipio = "t";
      }else{
        $municipio = "f";
      }
    $db_botao = true;
    }
  }//echo $cpf;
?> 
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<script>
function js_testanome(obj,cpf,cnpj){
  pesqnome.location.href = 'prot1_comparanomes.php?z01_cpf='+cpf+'&z01_cgc='+cnpj+'&nome='+obj+'&numcgm=<?=@$z01_numcgm?>';  
}
/*function js_tamnome(nome,pessoa){
  tam=nome.split(" ");
  if (tam.length<2){
    alert("Nome Inconsistente!!");
    document.form1.z01_nome.value="";
    document.form1.z01_nome.focus;
  }else{
    for (i=0;i<tam.length;i++){
      if (pessoa=='f'){
	if (tam[i].length<2){
	  alert("Nome Inconsistente!!");
	  document.form1.z01_nome.value="";
	  document.form1.z01_nome.focus;
	  break;
	}
      }
    }
  }
}*/

function js_tamnome(pessoa){
  var nome=document.form1.z01_nome.value;
  var tam=nome.split(" ");
  var passa=true;
  if (tam.length<2){
    alert("Nome inconsistente (regra 1)!");
    document.form1.z01_nome.value="";
    document.form1.z01_nome.focus;
    passa=false;
  }else if (1 == 2){
    for (i=0;i<tam.length;i++){
      if (pessoa=='f'){
	if (tam[0].length<2 || tam[1].length<2){
	  alert("Nome inconsistente (regra 2)!");
	  document.form1.z01_nome.value="";
	  document.form1.z01_nome.focus;
	  passa=false;
	  break;
	}
      }
    }
  }
  if(pessoa == 'j'){
    nomecomple = document.form1.z01_nomecomple.value;
    tamcomple = nomecomple.split(" ");
    if (tamcomple.length<2){
       alert("Nome Completo inconsistente (regra 3)!");
       document.form1.z01_nomecomple.value="";
       document.form1.z01_nomecomple.focus;
       passa=false;
    }
  }
  if (passa==true){
    return true;
  }else{
    return false;
  }
}
</script>
<iframe name="pesqnome" src="prot1_comparanomes.php" width="0" height="0" style="visibility:hidden"></iframe>
<form name="form1" 
      method="post" 
      action="" 
      <?=($db_opcao == 3?"onSubmit=\"return confirm('Deseja excluir este registro permanentemente!')\"":"")?> 
      <?=(@$municipio == 't' && (isset($pessoa) || $db_opcao == 1 || $db_opcao == 2)?'onSubmit="return js_ValidaOperacao(true)"':(isset($pessoa) || @$municipio == 'f'?'onSubmit="return js_ValidaOperacao(false)"':''))?>>
<?
if(isset($pessoa)){
  echo "<input type=\"hidden\" name=\"pessoa\" value=\"$pessoa\">";
}
?>
  <table width="730" border="1" cellspacing="0" cellpadding="0">
    <?
    
    if($db_opcao == 1){
			 if (isset($ov02_sequencial) && trim($ov02_sequencial) != ""){
				
				$sQueryCidadao = "select 	ov02_sequencial, 
																	ov02_seq, 
																	ov02_nome as z01_nome, 
																	ov02_cnpjcpf,
																	ov02_cep as z01_cep,
																	ov02_endereco as z01_ender,
																	ov02_numero as z01_numero,
																	ov02_compl as z01_compl,
																	ov02_munic as z01_munic,
																	ov02_bairro as z01_bairro,
																	ov02_uf as z01_uf,
																	ov02_ident as z01_ident,
																	((case when ov07_ddd = 0 then '' else ov07_ddd end) ||' '||ov07_numero) as z01_telef,	
																	ov08_email as z01_email													
														from cidadao as c 
														left join  cidadaotelefone as ct on c.ov02_sequencial = ct.ov07_cidadao and c.ov02_seq = ct.ov07_seq	and ov07_principal is true
														left join  cidadaoemail as cm on c.ov02_sequencial = cm.ov08_cidadao and c.ov02_seq = cm.ov08_seq	and ov08_principal is true
														where ov02_sequencial = $ov02_sequencial
													and ov02_ativo is true ";
				//die($sQueryCidadao);
				$rsCidadao = $clcidadao->sql_record($sQueryCidadao);
			
				if ($clcidadao->numrows > 0){
					db_fieldsmemory($rsCidadao,0);
			
					if (strlen($ov02_cnpjcpf) == 11){
						$z01_cpf = 	$ov02_cnpjcpf;
					}else if(strlen($ov02_cnpjcpf) == 14){
						$cnpj = $ov02_cnpjcpf;
					}
					
				?>
					<input type="hidden" name="ov02_sequencial" id="ov02_sequencial" value="<?=@$ov02_sequencial?>">
					<input type="hidden" name="ov02_seq" id="ov02_seq" value="<?=@$ov02_seq?>">
				<?
				}
							
			}
        	
      if (isset($cpf) && $cpf != "") {
				$cpf = str_replace(".","",$cpf);
				$cpf = str_replace("/","",$cpf);
				$cpf = str_replace("-","",$cpf);
				
				if(empty($z01_cgccpfi))
	  			$z01_cgccpf = $cpf;
	  			
				include("prot1_pfisica.php");
      }else if (isset($cnpj) && $cnpj != ""){
				$cnpj = str_replace(".","",$cnpj);
				$cnpj = str_replace("/","",$cnpj);
				$cnpj = str_replace("-","",$cnpj); 
				if(empty($z01_cgccpf))
				  $z01_cgccpf = $cnpj;
				include("prot1_pjuridica.php");
      }
    }else if($db_opcao == 2){
      if (isset($autoinfra)&&trim($autoinfra)!=""){
        $db_opcao=3;
      }
      if (isset($autoprot)&&trim($autoprot)!=""){
        $db_opcao=3;
      }
      if(!isset($chavepesquisa) && !isset($numcgm_cgccpf)){
        echo "<script>js_func_nome();</script>";
        echo "</table>";
        echo "</table>";
				if(!isset($testanome)){
			        db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
				}
				exit;
      }else{
	if(isset($numcgm_cgccpf))
	  $chavepesquisa = $numcgm_cgccpf;
    $result = $clcgm->sql_record($clcgm->sql_query($chavepesquisa,"*"));
		db_fieldsmemory($result,0);
	if($z01_cgccpf == "" && !isset($pessoa)){
	?>
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr align="center"> 
              <td nowrap align="center">
	        <strong><?=$z01_nome?> n�o possui CPF/CNPJ cadastrado no CGM</strong><br>
	        <strong>portanto n�o h� como definir se � uma PESSOA F�SICA OU JUR�DICA</strong><br>
	        <strong><?=$DBtxt32?></strong>
              </td>
	    </tr>
	    <tr align="center">
	      <td align="center" id="sel"><br>
	        <select name="pessoa" id="SELECT "TRANSACAO"" >
		  <option value="fisica">Pessoa F�sica</option>
		  <option value="juridica">Pessoa Jur�dica</option>
		</select><br><br>
		<input type="button" value="Confirma" onClick="js_input(document.form1.pessoa.value)">
		<input type="hidden" name="z01_numcgm" value="<?=$z01_numcgm?>">
		<input type="hidden" name="municipio" value="<?=@$municipio?>">
		<script>
		function js_input(obj){
	          var hid = document.createElement("INPUT");
		  hid.setAttribute("type","hidden");
		  hid.setAttribute("name","pessoa");
		  hid.setAttribute("value",obj);
		  document.form1.appendChild(hid);
		  document.form1.submit();
		}
		</script>
	      </td>	      
            </tr>
	  </table>
	</table>
	<?
	if(strcmp(strrev(substr(strrev($z01_nome),0,2)),"ME") == 0 || strcmp(strrev(substr(strrev($z01_nome),0,4)),"LTDA") == 0 || strcmp(strrev(substr(strrev($z01_nome),0,2)),"SA") == 0){
          echo "<script>document.form1.pessoa.options[1].selected = true</script>";
	}
	if(!isset($testanome))
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	exit;
	}elseif(isset($pessoa)){
	  if($pessoa == "fisica"){
	    if(strtoupper($z01_munic) == strtoupper($munic) && !isset($municipio))
	      $municipio = "t";
	    elseif(!isset($municipio))
	      $municipio = "f";
	    include("prot1_pfisica.php");
	  }elseif($pessoa == "juridica"){
	    if(strtoupper($z01_munic) == strtoupper($munic) && !isset($municipio))
	      $municipio = "t";
	    elseif(!isset($municipio))
	      $municipio = "f";
	    include("prot1_pjuridica.php");
	  }
	}elseif($z01_cgccpf != ""){
	  if(strlen($z01_cgccpf) == 14){
	    $result = $clcgm->sql_record($clcgm->sql_query($z01_numcgm,"*"));
	    db_fieldsmemory($result,0);
	    if(strtoupper($z01_munic) == strtoupper($munic) && !isset($municipio))
	      $municipio = "t";
	    elseif(!isset($municipio))
	      $municipio = "f";
	    include("prot1_pjuridica.php");
	  }elseif(strlen($z01_cgccpf) == 11){
	    $result = $clcgm->sql_record($clcgm->sql_query($z01_numcgm,"*"));
	    db_fieldsmemory($result,0);
	    if(strtoupper($z01_munic) == strtoupper($munic) && !isset($municipio))
	      $municipio = "t";
	    elseif(!isset($municipio))
	      $municipio = "f";
	    include("prot1_pfisica.php");
	  }else{
	    ?>
	      <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr align="center"> 
		  <td nowrap align="center">
	            <strong><?=$z01_nome?> n�o possui CPF/CNPJ cadastrado no CGM</strong><br>
	        <strong>portanto n�o h� como definir se � uma PESSOA F�SICA OU JUR�DICA</strong><br>
		    <strong><?=$DBtxt32?></strong>
		  </td>
		</tr>
		<tr align="center">
		  <td align="center"><br>
		    <select name="pessoa">
		      <option value="fisica">Pessoa F�sica</option>
		      <option value="juridica">Pessoa Jur�dica</option>
		    </select><br><br>
		    <input type="button" value="Confirma" onClick="js_input(document.form1.pessoa.value)">
		    <input type="hidden" name="z01_numcgm" value="<?=$z01_numcgm?>">
		    <input type="hidden" name="municipio" value="<?=@$municipio?>">
		<script>
		function js_input(obj){
	          var hid = document.createElement("INPUT");
		  hid.setAttribute("type","hidden");
		  hid.setAttribute("name","pessoa");
		  hid.setAttribute("value",obj);
		  document.form1.appendChild(hid);
		  document.form1.submit();
		}
		</script>
		  </td>	      
		</tr>
	      </table>
	    </table>
	    <?
	    if(!isset($testanome)){
	    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	    }
	    exit;
	  }
	}
      }
    }elseif($db_opcao == 3){
      if(!isset($chavepesquisa)){
        echo "<script>js_func_nome();</script>";
        echo "</table>";
        echo "</table>";
	if(!isset($testanome)){
        db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
        }
	exit;
      }else{
        $result = $clcgm->sql_record($clcgm->sql_query($chavepesquisa,"*"));
	db_fieldsmemory($result,0);
	?>
	  <table border="1" cellspacing="0" cellpadding="0">
	      <tr><strong><?=$DBtxt33?></strong><br><br> 
		<td width="27%" title='<?=$Tz01_numcgm?>' nowrap> 
		  <?=$Lz01_numcgm?>
		</td>
		<td width="73%" nowrap> 
		  <?
		    db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',3);
		  ?>
		</td>
	      </tr>
	      <tr> 
		<td nowrap title=<?=@$Tz01_nome?>> 
		  <?=@$Lz01_nome?>
		</td>
		<td nowrap title="<?=@$Tz01_nome?>"> 
		 <?
		   db_input('z01_nome',40,$Iz01_nome,true,'text',$db_opcao);
		 ?>
		</td>
	      </tr>
	      <tr align="center" valign="middle"> 
		<td height="30" colspan="2" nowrap> <input name="db_opcao" type="submit" id="db_opcao"  value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> > 
		  <?
		  if(!isset($testanome)){
		  ?>
		  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_func_nome();"> 
		  <input name="voltar" type="button" value="Retornar" <?=($db_opcao == 3 ?"onclick=\"location.href = 'prot1_cadcgm003.php';\"":"")?>> 
		  <?
		  	
		  }
		  ?>
		</td>
	      </tr>
	    </table>  
	  </table>  
        <?
      if(!isset($testanome)){ 
        db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      }
      exit;
      }
    }
    if (isset($autoinfra)&&trim($autoinfra)!=""){
        $db_opcao=2;
    }
    if (isset($autoprot)&&trim($autoprot)!=""){
        $db_opcao=2;
    }
    ?>
    <tr>
      <td width="39%" align="center" title="<?=$TDBtxt1?>" valign="middle"> 
        <?=$LDBtxt1?>
      </td>
      <td width="61%" align="center" valign="middle" title="<?=$TDBtxt5?>"> 
        <?=$LDBtxt5?>
      </td>
    </tr>
    <tr align="center" valign="middle"> 
      <td width="39%">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
<script>
function js_cep(abre){
  //if(document.form1.z01_cep.value != "")
    //document.getElementById('teste').style.visibility = 'visible';
  if(abre == true){
    js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_cep','func_cep.php?<?=($municipio == 't'?"municipio=sim&":"")?>pesquisa_chave='+document.form1.z01_cep.value+'&funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|codigo','Pesquisa',false);
  }
}
function js_preenchecep(chave,chave1,chave2,chave3,chave4){
//  setInterval("document.getElementById('teste').style.visibility = 'hidden'",2000);
  document.form1.z01_cep.value = chave;
  document.form1.z01_ender.value = chave1;
  document.form1.z01_munic.value = chave2;
  document.form1.z01_uf.value = chave3;
  document.form1.z01_bairro.value = chave4;
  <?=($municipio == 't'?'document.form1.j14_codigo.value = chave5;':'')?>
  db_iframe_cep.hide();
}
</script>
            <td nowrap title="<?=@$Tz01_cep?>"> 
	       <?
	       db_ancora(@$Lz01_cep,"js_cep(true);",($municipio == "t"?'3':'1'));
	       ?>
            </td>
            <td nowrap> 
              <?
	      /********************AQUI FAZ A M�O DO CEP POR LOGRADOUROS*********************/
	      $query_dbconfig =  "select munic,uf  from db_config where prefeitura is true";
              $result_dbconfig = db_query($query_dbconfig);
              db_fieldsmemory($result_dbconfig,0);
              
	      $muni = strtoupper($munic);
	      $sigla     = strtoupper($uf);
              
	      $query_ceploca = "select cp05_codlocalidades,
	                               cp05_localidades 
				from ceplocalidades 
				where cp05_localidades = '$muni' and cp05_sigla = '$sigla'";
	      $res_ceploca   = db_query($query_ceploca) or die($query_ceploca);
				if (pg_num_rows($res_ceploca) == 0) {
					db_msgbox("Erro ao buscar CEP do municipio!");
					exit;
				};
	      db_fieldsmemory($res_ceploca,0);
              
	      $query_lograd  = "select * 
	                       from ceplogradouros 
	                       where cp06_codlocalidade = $cp05_codlocalidades::bigint";
	      $res_lograd    = db_query($query_lograd) or die($query_lograd);
	      
	      if(pg_num_rows($res_lograd) > 0 ){
		   $temceprua = 't';
                }else{
                   $temceprua = 'f';
                }
                if($municipio == 't' && $temceprua != 't'){
                   $z01_cep = $cep;
                }else{
                   $z01_cep = @$z01_cep;
                }
              
	      db_input('z01_cep',9,$Iz01_cep,true,'text',($municipio=="t"?'3':'1'));
              ?>
              <input type="button" name="buscacep" value="Pesquisar" onClick="js_cep(false)"<?=($municipio == "t"?'disabled':'')?>>
              </td>
              </tr>
              <tr>
              <td nowrap title="<?=@$Tz01_ender?>">
              <?
              if (pg_num_rows($res_lograd) > 0)
                 db_ancora(@$Lz01_ender,"js_logradcep();",($municipio == "t"?'1':'3'));
              else
                 db_ancora(@$Lz01_ender,"js_ruas();",($municipio == "t"?'1':'3'));
              /***************************************************************************************/
              ?>
             </td>
             <td nowrap> 
	      <?
              if($db_opcao == 2 && (isset($z01_numcgm) || isset($numcgm_cgccpf))){
		if ($numrows_cgmruas!=0){
		  db_fieldsmemory($result_cgmruas,0);
		}
	      }
   	      db_input('j14_codigo',5,$Ij14_codigo,true,'hidden',($municipio == "t"?'1':'3'));
	      ?>
                <?
		if($municipio == 'f'){
		?>
		  <script>
		    document.form1.j14_codigo.value = '';
		  </script>
		<?
		}
		?>
	      <?
		if ($db_opcao == 1) {
		  $z01_ender = "";
		}
  	  	db_input('z01_ender',40,$Iz01_ender,true,'text',($municipio == "t"?'3':'1'));

	      ?>
            </td>
          </tr>
          <tr> 
            <td width="29%" nowrap title="<?=@$Tz01_numero?>"> 
              <?=@$Lz01_numero?>
            </td>
            <td width="71%" nowrap  ><a name="AN3"> 
              <?

		  db_input('z01_numero',8,$Iz01_numero,true,'text',$db_opcao);

		  ?>
              &nbsp; 
              <?=@$Lz01_compl?>
              <?

		  db_input('z01_compl',10,$Iz01_compl,true,'text',$db_opcao);

		  ?>
              </a> </td>
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_munic?>"> 
              <?=@$Lz01_munic?>
            </td>
            <td nowrap colspan=4> 
              <?
			  if ($municipio == 't') {
			     $z01_munic = strtoupper($munic);
			  }else{
			     $z01_munic = @strtoupper($z01_munic);
			  }
		  db_input('z01_munic',20,$Iz01_munic,true,'text',3);

		  ?>
              <?=@$Lz01_uf?>
              <?
		  if ($municipio == 't') {
			$z01_uf = $uf;
                  }else{
			$z01_uf = @strtoupper($z01_uf);
		  }
		  db_input('z01_uf',2,$Iz01_uf,true,'text',3);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_bairro?>"> 
	      <?
	      db_ancora(@$Lz01_bairro,"js_bairro();",($municipio == "t"?'1':'3'));
	      ?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_bairro',25,$Iz01_uf,true,'text',($municipio == "t"?'3':'1'));
  ?>
              <?
  		      db_input('j13_codi',6,$Ij13_codi,true,'hidden',1);
		      ?>
            </td>
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_telef?>"> 
              <?=@$Lz01_telef?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_telef',12,$Iz01_telef,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_fax?>"> 
              <?=@$Lz01_fax?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_fax',12,$Iz01_fax,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_telcel?>"> 
              <?=@$Lz01_telcel?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_telcel',12,$Iz01_telcel,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_email?>"> 
              <?=@$Lz01_email?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_email',30,$Iz01_email,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_cxpostal?>"> 
              <?=@$Lz01_cxpostal?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_cxpostal',10,$Iz01_cxpostal,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
        </table>
      </td>
      <td width="61%"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td nowrap title="<?=@$Tz01_cepcon?>"> 
	       <?
	       db_ancora(@$Lz01_cepcon,"js_cepcon(true);",1);
	       ?>
            </td>
            <td nowrap> 
              <?
  		      db_input('z01_cepcon',9,$Iz01_cepcon,true,'text',1,'');
		      ?>
	    <input type="button" name="buscacep" value="Pesquisar" onClick="js_cepcon(false)">	      
	    </td>	      
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_endcon?>"> 
              <?=@$Lz01_endcon?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_endcon',40,$Iz01_endcon,true,'text',$db_opcao);

		  ?>
            </td>
          </tr>
          <tr> 
            <td width="29%" nowrap title="<?=@$Tz01_numcon?>"> 
              <?=@$Lz01_numcon?>
            </td>
            <td width="71%" nowrap > 
              <?

		  db_input('z01_numcon',8,$Iz01_numcon,true,'text',$db_opcao);

		  ?>
              <?=@$Lz01_comcon?>
              <?

		  db_input('z01_comcon',10,$Iz01_comcon,true,'text',$db_opcao);

		  ?>
            </td>
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_muncon?>"> 
              <?=@$Lz01_muncon?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_muncon',20,$Iz01_muncon,true,'text',$db_opcao);

		  ?>
              <?echo "<b>UF:"?>
              <?

		  db_input('z01_ufcon',2,$Iz01_ufcon,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_baicon?>"> 
              <?=@$Lz01_baicon?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_baicon',25,$Iz01_baicon,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_telcon?>"> 
              <?=@$Lz01_telcon?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_telcon',12,$Iz01_telcon,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_celcon?>"> 
              <?=@$Lz01_celcon?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_celcon',12,$Iz01_celcon,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_emailc?>"> 
              <?=@$Lz01_emailc?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_emailc',30,$Iz01_emailc,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_cxposcon?>"> 
              <?=@$Lz01_cxposcon?>
            </td>
            <td nowrap> 
              <?

		  db_input('z01_cxposcon',10,$Iz01_cxposcon,true,'text',$db_opcao);

		  ?>
	    </td>	  
          </tr>
        </table>
      </td>
    </tr>
    <tr align="left" valign="middle">
      <td height="21" colspan="2" nowrap><table width="64%" border="0" cellspacing="0">
       <table border=1>
          <tr nowrap> 
            <td width="18%" nowrap> 
              <?=@$Lz01_cadast?>
            </td>
            <td width="76%" nowrap>
              <?if (isset($z01_cadast)&&$z01_cadast!=""){
	      }
	      if ($db_opcao == 1) {
 	        $z01_cadast_ano = date('Y',db_getsession("DB_datausu"));
	        $z01_cadast_mes = date('m',db_getsession("DB_datausu"));
	        $z01_cadast_dia = date('d',db_getsession("DB_datausu"));
	      }
	      
 	      db_inputdata('z01_cadast',@$z01_cadast_dia,@$z01_cadast_mes,@$z01_cadast_ano,true,'text',3);
	      ?>
            </td>
            <td width="18%" nowrap> 
              <?=@$Lz01_ultalt?>
            </td>
            <td width="76%" nowrap>
              <?
 	        $z01_ultalt_ano = date('Y',db_getsession("DB_datausu"));
	        $z01_ultalt_mes = date('m',db_getsession("DB_datausu"));
	        $z01_ultalt_dia = date('d',db_getsession("DB_datausu"));
 	      db_inputdata('z01_ultalt',@$z01_ultalt_dia,@$z01_ultalt_mes,@$z01_ultalt_ano,true,'text',3);
	      ?>
            </td>
            <td width="6%">
              <?
  $z01_login = db_getsession("DB_id_usuario");
  db_input("z01_login",6,$Iz01_login,true,'hidden',3);
  ?>
            </td>
            <td width="6%">
              <?
  $z01_login = db_getsession("DB_id_usuario");
  db_input("z01_login",6,$Iz01_login,true,'hidden',3);
  ?>
            </td>
          </tr>
        </table>
    </tr>
    <tr align="center" valign="middle"> 
      <td height="30" colspan="2" nowrap> 
        <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_tamnome('<?=$pesa?>');" > 
        <?
	if(!isset($testanome)){
	?>
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_func_nome();"> 
  <input name="voltar" type="button" value="Retornar" onclick="location.href = 'prot1_cadcgm00<?=($db_opcao == 1?'1':'2')?>.php';"> 
	<?
		$lPermissaoMenu = db_permissaomenu(db_getsession("DB_anousu"),604,7901);
		if($db_opcao == 2 && $lPermissaoMenu == true && !isset($ov03_numcgm)){
		?>
			<input name="vincular" type="button" id="vincular" value="Vincular Cidadao ao CGM" onclick="js_vinculaCadastroCidadaoCGM();">
		<?
		}else if ($lPermissaoMenu == true && isset($ov03_numcgm)){
			?>
			<input type="hidden" name="ov02_sequencial" id="ov02_sequencial" value="<?= $ov02_sequencial != 0 ? $ov02_sequencial : 0 ?>">
			<input type="hidden" name="ov02_seq" id="ov02_seq" value="<?= $ov02_seq != 0 ? $ov02_seq : 0 ?>">
			<input name="importar" type="button" id="importar" value="Importar dados do Cidad�o" onclick="js_MICidadao(<?=$ov02_sequencial?>,<?=$ov02_seq?>,<?=$ov03_numcgm?>);">
			<?
		}
	
	}
	?>
    </tr>
  </table>
</table>  
  
<?
if(!isset($testanome)){
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
//include("db_divbalao.php");
?>
</form>
<script>
function js_MICidadao(ov02_sequencial,ov02_seq,ov03_numcgm){
	var ov02_sequencial = ov02_sequencial;
	var ov02_seq				=	ov02_seq;
	var ov03_numcgm			=	ov03_numcgm;
	js_OpenJanelaIframe('top.corpo','db_iframe','ouv4_cidadaocgmdetalhe.php?importa=true&ov02_sequencial='+ov02_sequencial+'&ov02_seq='+ov02_seq+'&ov03_numcgm='+ov03_numcgm,'Pesquisa',true);
}


function js_logradcep(){
  js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_ruas','func_ruas_alt.php?rural=1&funcao_js=parent.js_preenchepesquisa|j14_codigo|j14_nome|cep|','Pesquisa',true);
}

function js_ruas(){
  js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
}

function js_preenchepesquisaruas(chave,chave1){
//  if(document.form1.j14_codigo.value == "")
    <?=($municipio == 't'?'document.form1.j14_codigo.value = chave;':'')?>
  document.form1.z01_ender.value = chave1;
  db_iframe_ruas.hide();
}
function js_preenchepesquisa(chave,chave1,chave2){
  if(document.form1.j14_codigo.value == "")
    <?=($municipio == 't'?'document.form1.j14_codigo.value = chave;':'')?>
  document.form1.z01_ender.value = chave1;
  document.form1.z01_cep.value = chave2;
  db_iframe_ruas.hide();
}
function js_bairro(){
  //if(document.form1.z01_cep.value != "")
    //document.getElementById('teste').style.visibility = 'visible';
  js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
}
function js_preenchebairro(chave,chave1){
//  setInterval("document.getElementById('teste').style.visibility = 'hidden'",2000);
  document.form1.j13_codi.value = chave;
  document.form1.z01_bairro.value = chave1;
  db_iframe_bairro.hide();
}
function js_preenchecep1(chave,chave1,chave2,chave3,chave4,chave5){
//  setInterval("document.getElementById('teste').style.visibility = 'hidden'",2000);
  if(chave=="" && chave1 == "" && chave2 == "" && chave3=="" && chave4==""){
    alert('CEP n�o encontrado!');
    document.form1.z01_cep.focus();
  }
  document.form1.z01_cep.value = chave;
  document.form1.z01_ender.value = chave1;
  document.form1.z01_munic.value = chave2;
  document.form1.z01_uf.value = chave3;
  if(document.form1.z01_bairro.value != ''){
    document.form1.z01_bairro.value = chave4;
     <?=($municipio == 't'?'document.form1.j14_codigo.value = chave5;':'')?>
  }
}
function js_cepcon(abre){
  //if(document.form1.z01_cep.value != "")
    //document.getElementById('teste').style.visibility = 'visible';
  if(abre == true){
    js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_cepcon.value+'&funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',false);
  }
}
function js_preenchecepcon(chave,chave1,chave2,chave3,chave4){
//  setInterval("document.getElementById('teste').style.visibility = 'hidden'",2000);
  document.form1.z01_cepcon.value = chave;
  document.form1.z01_endcon.value = chave1;
  document.form1.z01_muncon.value = chave2;
  document.form1.z01_ufcon.value = chave3;
  document.form1.z01_baicon.value = chave4;
  db_iframe_cep.hide();
}
function js_preenchecepcon1(chave,chave1,chave2,chave3,chave4){
//  setInterval("document.getElementById('teste').style.visibility = 'hidden'",2000);
  if(chave=="" && chave1 == "" && chave2 == "" && chave3=="" && chave4==""){
    alert('CEP n�o encontrado!');
    document.form1.z01_cep.focus();
  }
  document.form1.z01_cepcon.value = chave;
  document.form1.z01_endcon.value = chave1;
  document.form1.z01_muncon.value = chave2;
  document.form1.z01_ufcon.value = chave3;
  document.form1.z01_baicon.value = chave4;
}
<?if($db_opcao == 1){
?>
onLoad = document.form1.z01_nome.focus();
<?
  if($z01_cep != $cep || $municipio == 'f'){
    ?>
    js_cep(false);
    <?
  }
  ?>
<?
}
?>
<?
if($db_opcao == 2){
  if($municipio == 'f' && $cep == $z01_cep){
    ?>
    document.form1.z01_cep.value = '';
    document.form1.z01_numero.value = '';
    document.form1.z01_compl.value = '';
    document.form1.z01_ender.value = '';
    document.form1.z01_munic.value = '';
    document.form1.z01_uf.value = '';
    <?
  }
}
?>

function js_vinculaCadastroCidadaoCGM(){

	js_OpenJanelaIframe('','db_iframe_cidadao','func_cidadaovinculos.php?funcao_js=parent.js_vinculaCidadaoCGM|0|1&liberado=true&ativo=true&vinculocgm=false','Pesquisa',true);
	
}


function js_vinculaCidadaoCGM(ov02_sequencial,ov02_seq){

	
	db_iframe_cidadao.hide();
	location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+document.form1.z01_numcgm.value+'&ov02_sequencial='+ov02_sequencial+'&ov02_seq='+ov02_seq;
/*
	var oVincular = new Object();
	
	oVincular.acao = 'vincular';
	oVincular.ov03_cidadao = ov02_sequencial; 	
	oVincular.ov03_seq		 = ov02_seq;
	oVincular.ov03_numcgm	 = $F('z01_numcgm');
		
	var sDados = Object.toJSON(oVincular);
	var msgDiv = 'Aguarde vinculando Cidad�o ao CGM.....';
	js_divCarregando(msgDiv,'msgBox');
	
	sUrl = 'ouv1_cidadao.RPC.php';
	var sQuery = 'dados='+sDados;
	var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoVincularDados
                                          }
                                  );			
*/	
}

function js_retornoVincularDados(oAjax){
	
	//alert(oAjax.responseText);
	
	js_removeObj("msgBox");
  
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
    
  alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
  
  if ( aRetorno.status == 0){
  	return false;
  }else if ( aRetorno.status == 1) {
  	var z01_numcgm = aRetorno.ov03_numcgm;
  	//$('db_opcao').value = 'vincular';
  	location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+z01_numcgm;
    //location.href = 'prot1_cadcgm002.php?numcgm_cgccpf='+z01_numcgm;
  }  
	
}
</script>
<?
if($temceprua == 't' && $z01_cep != $cep){
//  echo "<script>onLoad = js_cep(false)</script>";
}
?>