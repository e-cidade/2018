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

$func_iframe = new janela('func_nome','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<script>
function js_ruacorreta(){
  rua = document.form1.j14_codigo.value;
  nome = document.form1.z01_nome.value;
  cep = document.form1.z01_cep.value;
  if(rua == "" || rua == 'undefined'){
    alert('Atualmente apenas a descrição do logradouro está no cadastro deste contribuinte. Clique em OK para escolher o logradouro baseado no cadastro imobiliário do município. O nome do logradouro ja virá preenchido, bastando clicar em pesquisar.');
//    document.form1.z01_ender.value = '';
    document.form1.z01_ender.select();
    document.form1.z01_ender.focus();
    js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_ruas','func_ruas.php?nomerua='+ document.form1.z01_ender.value+'&rural=1&funcao_js=parent.js_preenchepesquisa|j14_codigo|j14_nome|j29_cep','Pesquisa',true);
    return false;
  }else{
    if(nome == "" || nome == 'undefined'){
      alert('Campo Nome/Razão Social é obrigatório!');
      document.form1.z01_nome.value = '';
      document.form1.z01_nome.focus();
      return false;
    }else{
      if(cep == "" || cep == 'undefined'){
	alert('Campo CEP é obrigatório!');
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
    alert('Campo endereço é obrigatório!');
    document.form1.z01_ender.value = '';
    document.form1.z01_ender.focus();
    return false;
  }else{
    if(nome == "" || nome == 'undefined'){
      alert('Campo Nome/Razão Social é obrigatório!');
      document.form1.z01_nome.value = '';
      document.form1.z01_nome.focus();
      return false;
    }else{
      if(cep == "" || cep == 'undefined'){
	alert('Campo CEP é obrigatório!');
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
  func_nome.jan.location.href = 'func_nome.php?funcao_js=parent.js_preenche|0';
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
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
  db_fieldsmemory(pg_exec("select * from db_config where codigo = " . db_getsession("DB_instit")),0);
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
  }
?> 
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<script>// onMousemove="js_mover(document.getElementById('teste'),event)"</script>
<?
/*
<div id="teste" style="position:absolute;left:300px;top:250px;visibility:hidden;z-index:100">
      <small>AGUARDE<br><blink>&nbsp;&nbsp;processando...</blink></small>
    </div>
*/
?>
<script>
function js_testanome(obj,cpf,cnpj){
  pesqnome.location.href = 'prot1_comparanomes.php?z01_cpf='+cpf+'&z01_cgc='+cnpj+'&nome='+obj+'&numcgm=<?=@$z01_numcgm?>';  
}
function js_tamnome(nome,pessoa){
  tam=nome.split(" ");
  if (tam.length<2){
    alert("Nome Inconsistente!");
    document.form1.z01_nome.value="";
    document.form1.z01_nome.focus;
  }else{
    for (i=0;i<tam.length;i++){
      if (pessoa=='f'){
	if (tam[i].length<2){
	  alert("Nome Inconsistente!");
	  document.form1.z01_nome.value="";
	  document.form1.z01_nome.focus;
	  break;
	}
      }
    }
  }
}
</script>
<iframe name="pesqnome" src="prot1_comparanomes.php" width="0" height="0" style="visibility:hidden"></iframe>
<form name="form1" method="post" action="" <?=($db_opcao == 3?"onSubmit=\"return confirm('Deseja excluir este registro permanentemente!')\"":"")?> <?=(@$municipio == 't' && (isset($pessoa) || $db_opcao == 1 || $db_opcao == 2)?'onSubmit="return js_ruacorreta()"':(isset($pessoa) || @$municipio == 'f'?'onSubmit="return js_ruacorreta1()"':''))?>>
<?
if(isset($pessoa)){
  echo "<input type=\"hidden\" name=\"pessoa\" value=\"$pessoa\">";
}
?>
  <table width="730" border="1" cellspacing="0" cellpadding="0">
    <?
    if($db_opcao == 1){
      if(isset($cpf) && $cpf != ""){
	$cpf = str_replace(".","",$cpf);
	$cpf = str_replace("/","",$cpf);
	$cpf = str_replace("-","",$cpf); 
	if(empty($z01_cgccpfi))
	  $z01_cgccpf = $cpf;
	include("prot1_pfisica.php");
      }elseif(isset($cnpj) && $cnpj != ""){
	$cnpj = str_replace(".","",$cnpj);
	$cnpj = str_replace("/","",$cnpj);
	$cnpj = str_replace("-","",$cnpj); 
	if(empty($z01_cgccpf))
	  $z01_cgccpf = $cnpj;
	include("prot1_pjuridica.php");
      }
    }elseif($db_opcao == 2){
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
	        <strong><?=$z01_nome?> não possui CPF/CNPJ cadastrado no CGM</strong><br>
	        <strong>portanto não há como definir se é uma PESSOA FÍSICA OU JURÍDICA</strong><br>
	        <strong><?=$DBtxt32?></strong>
              </td>
	    </tr>
	    <tr align="center">
	      <td align="center" id="sel"><br>
	        <select name="pessoa" id="SELECT "TRANSACAO"" >
		  <option value="fisica">Pessoa Física</option>
		  <option value="juridica">Pessoa Jurídica</option>
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
	            <strong><?=$z01_nome?> não possui CPF/CNPJ cadastrado no CGM</strong><br>
	        <strong>portanto não há como definir se é uma PESSOA FÍSICA OU JURÍDICA</strong><br>
		    <strong><?=$DBtxt32?></strong>
		  </td>
		</tr>
		<tr align="center">
		  <td align="center"><br>
		    <select name="pessoa">
		      <option value="fisica">Pessoa Física</option>
		      <option value="juridica">Pessoa Jurídica</option>
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
	       <td><?
	      db_ancora(@$Lz01_cep,"js_dbceplog();",($ceplog == "t"?'1':'3'));
	      db_input('z01_cep',10,$Iz01_cep,true,'text',3);
	      ?>
	       </td>
	      <?
	      db_ancora(@$Lz01_cep,"js_dbceplog();",($ceplog == "t"?'1':'3'));
	      db_input('z01_cep',10,$Iz01_cep,true,'text',3);
	      ?>
	       <td>
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
            <td nowrap> 
              <?
			  if ($municipio == 't') {
			     $z01_munic = strtoupper($munic);
			  }else{
			     $z01_munic = @strtoupper($z01_munic);
			  }
		  db_input('z01_munic',20,$Iz01_munic,true,'text',3);

		  ?>
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=@$Tz01_uf?>"> 
              <?=@$Lz01_uf?>
            </td>
            <td nowrap> 
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
	    <input type="button" value="Pesquisar" onClick="js_cepcon(false)">
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
	    </td>	  
          </tr>
          <tr> 
            <td nowrap title="<?=$Tz01_ufcon?>"> 
              <?=@$Lz01_ufcon?>
            </td>
            <td nowrap> 
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
      <td height="30" colspan="2" nowrap> <input name="db_opcao" type="submit" id="db_opcao"  value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> > 
        <?
	if(!isset($testanome)){
	?>
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_func_nome();"> 
        <input name="voltar" type="button" value="Retornar" onclick="location.href = 'prot1_cadcgm00<?=($db_opcao == 1?'1':'2')?>.php';"> 
	<?
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
function js_ruas(){
  js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisa|j14_codigo|j14_nome|j29_cep','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  if(document.form1.j14_codigo.value == "")
    <?=($municipio == 't'?'document.form1.j14_codigo.value = chave;':'')?>
  document.form1.z01_ender.value = chave1;
  if(document.form1.z01_cep.value == "" )
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
    alert('CEP não encontrado!');
    document.form1.z01_cep.focus();
  }
  document.form1.z01_cep.value = chave;
  document.form1.z01_ender.value = chave1;
  document.form1.z01_munic.value = chave2;
  document.form1.z01_uf.value = chave3;
  if(document.form1.z01_bairro.value == "")
    document.form1.z01_bairro.value = chave4;
  <?=($municipio == 't'?'document.form1.j14_codigo.value = chave5;':'')?>
}
function js_cepcon(abre){
  //if(document.form1.z01_cep.value != "")
    //document.getElementById('teste').style.visibility = 'visible';
  if(abre == true){
    js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecepcon|cep|endereco|municipio|estado|bairro','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('<?=(!isset($testanome)?"top.corpo":"")?>','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_cepcon.value+'&funcao_js=parent.js_preenchecepcon1|cep|endereco|municipio|estado|bairro','Pesquisa',false);
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
    alert('CEP não encontrado!');
    document.form1.z01_cep.focus();
  }
  document.form1.z01_cepcon.value = chave;
  document.form1.z01_endcon.value = chave1;
  document.form1.z01_muncon.value = chave2;
  document.form1.z01_ufcon.value = chave3;
  document.form1.z01_baicon.value = chave4;
}
</script>
<script>
/*
function js_mover(obj,evt) {
  evt = (evt) ? evt : (window.event) ? window.event : "";
  if (evt.pageX) {
    obj.style.left = evt.pageX + 20 + "px";
    obj.style.top = evt.pageY  + "px";
  } else {
    obj.style.left = evt.clientX - 50 + "px";
    obj.style.top = evt.clientY -50 + "px";
  }
  return false;
}
*/
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
</script>
<?
if($temceprua == 't' && $z01_cep != $cep){
  echo "<script>onLoad = js_cep(false)</script>";
}
?>