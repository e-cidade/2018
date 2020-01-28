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
if(isset($outro)){
 @setcookie("cookie_codigo_cgm");
 header("location:digitaissqn.php");
}
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("classes/db_issbase_classe.php");
include("classes/db_escrito_classe.php");
$clescrito = new cl_escrito;
$clissbase = new cl_issbase;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = pg_exec("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitaissqn.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);

$result = pg_exec("select cgc as cgc_pref from db_config where codigo = " . db_getsession("DB_instit"));
db_fieldsmemory($result,0);


mens_help();
db_mensagem("issqnretencao_cab","issqnretencao_rod");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - Prefeitura On - Line</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_vericampos(){
  jmes=document.form1.mes.value;
  if(jmes=="mes"){
    alert("Favor selecionar o m�s!");
    return false
  }

  inscricaow = document.form1.inscricaow.value;
  cgc        = document.form1.cgc;
  expReg = "/[- /.]/g";
 
  var inscricaow = new Number(inscricaow.replace(expReg,""));
  cgc1           = LimpaCampo(document.form1.cgc.value,10)
  
  if (cgc1 == 0) {
     alert('Dados informados para o CPF/CNPJ s�o inv�lidos');
     return false;
  }

  if (cgc1.length > 11) { 
   //CPNJ
   return js_verificaCGCCPF(cgc,"");
  } else {
   //CPF
   return js_verificaCGCCPF("",cgc);
  }
  
  if(inscricaow=="" && cgc.value==""){
    alert("Favor preencher um dos campos de identifica��o!");
    document.form1.inscricaow.focus();
    return false  
  }
  if(isNaN(inscricaow)){
     alert("Verifique o campo Inscric�o!");
     return false
  }
  
<?
     // conta se o contribuente possui alvar� no mesmo munic�pio que se encontra
	 // caso a consulta retorna um verdadeiro, se retornar zero o contribuente n�o
	 // possui alvar� no munic�pio
	 $sqlCidade =  " select count(db_cgmruas.z01_numcgm) as qtd  			  ";
	 $sqlCidade .= " from db_cgmruas                                          ";
	 $sqlCidade .= " inner join cgm on cgm.z01_numcgm = db_cgmruas.z01_numcgm ";
	 $sqlCidade .= " where cgm.z01_numcgm = $id_usuario 					  ";
	 
	 @$sqlConsultaCidade = pg_query($sqlCidade);
     @$rRetornoContribuinte = pg_fetch_assoc ($sqlConsultaCidade);
	    
  if(@$rRetornoContribuinte['qtd'] == 1) {
?>
  
    if(document.form1.inscricaow.value == "") {
      alert("Selecione uma inscri��o abaixo!");
      return false;
    }
<?		 		 
  }

?>  
  
}

function SelecionaContribuinte(radio,tipo,total){
	var linha = radio;
  var sTipo = tipo;

  inscr = document.form2.inscr[linha].value;
  cnpj  = document.form2.cnpj[linha].value;

  document.form1.inscricaow.value = inscr;
  document.form1.cgc.value        = cnpj;
  document.form1.inscricaow.focus();

}



 function js_criames(obj,cgc_pref){

   for(i=1;i<document.form1.mes.length;i){
     document.form1.mes.options[i] = null;
   }

   var dth = new Date(<?=date("Y")?>,<?=date("m")?>,'1');
    if(document.form1.ano.options[0].value != obj.value ){
     if ( cgc_pref == '87366159000102' && obj.value == 2010 ) {
       iMesFinal = 7;
     } else {
       iMesFinal = 13;
     }
     for(j=1;j<iMesFinal;j++){
       var dt = new Date(<?=date("Y")?>,j,'1');
       document.form1.mes.options[j] = new Option(db_mes(j),dt.getMonth());
       document.form1.mes.options[j].value = j;
     }
    }else{

     if ( cgc_pref == '87366159000102' && obj.value == 2010 ) {
       iMesFinal = 7;
     } else {
       iMesFinal = dth.getMonth()+1;
     }

     for(j=1;j<iMesFinal;j++){
       var dt = new Date(<?=date("Y")?>,j,'1');
       document.form1.mes.options[j] = new Option(db_mes(j),dt.getMonth());
       document.form1.mes.options[j].value = j;
     }
   }
 }
 
 
</script>
<style type="text/css">
<?db_estilosite();?>
</style>
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<center>
<?

//verifica se est� logado
if(@$id_usuario !=""){
  @$result  = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,z01_nome,z01_cgccpf","","q02_numcgm = $id_usuario"));
  @$linhas  = $clissbase->numrows;
  if($linhas!=0){
    db_fieldsmemory($result,0);
    //11 14
    if(strlen($z01_cgccpf)>11){
      //armazena em var
      $var_cnpj = $z01_cgccpf;
    }
  }else{
     
    $sql_z01_cgccpf = pg_query("SELECT cgm.z01_cgccpf FROM cgm WHERE z01_numcgm = $id_usuario");
    $rRetorno = pg_fetch_assoc ($sql_z01_cgccpf);
    $var_cnpj = $rRetorno['z01_cgccpf'];

  }
	 
  ?>
	<form name="form1" method="post" action="" enctype="multipart/form-data">
  <table width="90%" class="texto" >
		<input name="primeiravez" type="hidden" value="true">
		<tr>
			<td align="center">
      	<strong>Inscri��o Alvar�:</strong>
				<input name="inscricaow" type="text" class="digitacgccpf" style="BACKGROUND-COLOR: #eaeaea;" readonly size="8" maxlength="6">
        <strong>CNPJ/CPF:</strong>
				<input name="cgc" value="<?=@$var_cnpj?>" type="text" class="digitacgccpf" id="cgc" style="BACKGROUND-COLOR: #eaeaea;" readonly size="18" maxlength="18" onKeyPress='FormataCPFeCNPJ(this,event); return js_teclas(event);'>
			</td>
		</tr>
		<tr>	
			<td class="green" align="center">
				Selecione o Contribuinte abaixo.
			</td>
		</tr>
		<tr>
			<td align="center">
				<strong>Arquivo de Reten��o ISSQN</strong><br/>
        <input type="file" name="arquivo" id="arquivo">
			</td>
		</tr>
		<tr>
		  <td align="center"><strong>Compet�ncia:</strong>
				<select name="ano" onchange="js_criames(this,<?=$cgc_pref?>)">
	      <?
          $sano = date("Y");
          if(date("m")==12)
            $sano ++;
          for($ci = $sano; $ci >= 2000; $ci--){
            echo "<option value=".$ci." >$ci</option>";
          }
        ?> 
				</select>
				<select class="digitacgccpf" name="mes" id="mes" >
					<option value="mes">M�s</option>
        </select> 
        <script>
					js_criames(document.form1.ano, <?=$cgc_pref?>);
				</script>
				<input name="first" type="hidden">
				  <br /><br />
					<input class="botao" type="submit" name="processar" value="Processar"  onclick="return js_vericampos();">
			</td>
		</tr>
	</table>
	</form>
	
	<form name="form2" method="post" enctype="multipart/form-data">
		<table width="100%" class="texto">
	  <?
      //� escrit�rio?
      $wherebx = " and q10_dtfim is null ";
      if (@$mostrainscricao == 1) {
        // todas
        $wherebx = " and q10_dtfim is null ";
      } else if (@$mostrainscricao == 2) {
        //baixadas
        $wherebx = " and q10_dtfim is null and q02_dtbaix is not null ";
      } if (@$mostrainscricao == 3) {
        // n�o baixadas
				$wherebx = " and q10_dtfim is null and q02_dtbaix is null ";
			}
      $result  = $clescrito->sql_record($clescrito->sql_query("","q02_inscr,cgm.z01_nome as z01_nome,cgm.z01_cgccpf as z01_cgccpf","","q10_numcgm = $id_usuario $wherebx"));
      $escrito = $clescrito->numrows;
      if($escrito!=0) {
        ?>
  			<tr height="20" >
  				<td colspan="3"><b>Mostrar inscri�oes</b> 
  				<select name="mostrainscricao" onchange = "document.form2.submit();">
  	        <? 
  	           echo "<option value = '1'".($mostrainscricao == 1?"selected":"").">Todas</option>";
  				     echo "<option value = '2'".($mostrainscricao == 2?"selected":"").">Somente baixadas</option>";
  				     echo "<option value = '3'".($mostrainscricao == 3?"selected":"").">Somente n�o baixadas</option>";
            ?>
  				</select>
  				</td>
  			</tr>
        <?
                  //busca clientes do escrit�rio
        for ($x = 0; $x < $escrito; $x++) {
          if($x == 0){
            ?>
  				<tr height="20" bgcolor="#eaeaea">
  					<td colspan="3"><b>Inscri��es que tenho acesso</b>
  					</td>
  				</tr>
  				<?
          }
          db_fieldsmemory($result,$x);
          echo "<tr>
                          <td width='5'>
                           <input type='radio' name='radio' value='$x' style='border:0' onclick='SelecionaContribuinte(this.value,1,$escrito)'>
                           <input type='hidden' name='inscr' value='$q02_inscr'>
                           <input type='hidden' name='cnpj' value='$z01_cgccpf'>
                          </td>
                          <td width='50' align='center'>$q02_inscr</td>
                          <td>$z01_nome</td>
                         </tr>";
          echo "<tr height=\"1\" bgcolor=\"#cccccc\"><td colspan=\"3\"></td></tr>";
        }
      }
      //� issbase
      $result2 = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,z01_nome,z01_cgccpf","","q02_numcgm = $id_usuario"));
      $issbase = $clissbase->numrows;
      //busca dados do issbase
      @$x = $x==""?0:$x;
      for($y=$x;$y<$issbase+$x;$y++){
        if($y==$x){
          ?>
				<tr height="20" bgcolor="#eaeaea">
					<td colspan="3"><b>Minhas Inscri��es</b>
					</td>
				</tr>
				<?
        }
        db_fieldsmemory($result2,$y-$x);
        echo "<tr>
                        <td width='5'>
                         <input type='radio' name='radio' value='$y' style='border:0' onclick='SelecionaContribuinte(this.value,2,$issbase)'>
                         <input type='hidden' name='inscr' value='$q02_inscr'>
                         <input type='hidden' name='cnpj' value='$z01_cgccpf'>
                        </td>
                        <td width='50' align='center'>$q02_inscr</td>
                        <td>$z01_nome</td>
                       </tr>";
        echo "<tr height=\"1\" bgcolor=\"#cccccc\"><td colspan=\"3\"></td></tr>";
      }
      ?>
		</table>
		</form>
	  <? 
    } else{
      if(@$_COOKIE["cookie_codigo_cgm"]!=""){
        @$cookie_codigo_cgm = $_COOKIE["cookie_codigo_cgm"];
        @$result  = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,cgm.z01_cgccpf","","cgm.z01_numcgm = $cookie_codigo_cgm"));
        @db_fieldsmemory($result,0);
      }
    ?>
			<form name="form1" method="post" action="iss4_importaplanilharetencao002.php" enctype="multipart/form-data">
			<table width="100%" height="200" border="0" cellspacing="0" cellpadding="0" class="texto">
				<input name="primeiravez" type="hidden" value="true">
				<tr>
          <td colspan="2" align="center">
          	<br> <?=$DB_mens1?>
          </td>
				</tr>
				
				<tr>
					<td align="center">
                 
						<b>CNPJ/CPF</b>:
						<input name="cgc" type="text" class="digitacgccpf" id="cgc" value="<?=@$z01_cgccpf?>" size="18" maxlength="18" onKeyPress="FormataCPFeCNPJ(this,event); return js_teclas(event);" >
						<strong>Inscri��o Alvar�:</strong>
            <!--
            10/05/2006
            Se n�o digitar inscri��o e o cont. possuir inscr, ele encontrar� na pr�xima vez que clicar em iss ret.
            e n�o encontrar� na busca dos valores.
            <input name="inscricaow" type="text" class="digitacgccpf" value="<?=@$q02_inscr?>" size="8" maxlength="6">
            -->
						<input name="inscricaow" type="text" class="digitacgccpf" value="<?=@$q02_inscr?>" size="8" maxlength="6">
						<br>
          </td>
				</tr>
				<tr>
					<td align="center">
						<strong>Arquivo de Reten��o ISSQN</strong><br/>
						<input type="file" name="arquivo" id="arquivo">
					</td>
				</tr>
				<tr>
					<td align="center">
					<strong>Compet�ncia:</strong>
					<select name="ano" onchange="js_criames(this,<?=$cgc_pref?>)">
		      <?
		      if ( $cgc_pref == "87366159000102" ) {
		        $sano = 2010;
		      } else {
		        $sano = date("Y");
		      }
		      
          if(date("m")==12)
            $sano ++;
          
          for($ci = $sano; $ci >= 2000; $ci--){
            echo "<option value=".$ci." >$ci</option>";
          }
          ?>
					</select>
          <select class="digitacgccpf" name="mes" id="mes" >
            <option value="mes">M�s</option>
          </select>
          <script>
					  js_criames(document.form1.ano,<?=$cgc_pref?>);
					</script>
					<input name="first" type="hidden">
					<br /><br />
          <input class="botao" type="submit" name="processar" value="Processar"  onclick="return js_vericampos()">
				</td>
			</tr>
      <tr>
        <td height="60" align="<?=$DB_align2?>">
          <?=$DB_mens2?>
        </td>
      </tr>
		</table>
		</form>
	  <?
    }
    ?>
</center>
</body>
</html>
<?
db_logs("","",0,"Digita Codigo da Inscricao para o issqn retencao.");
if(isset($erroscripts)){
  echo "<script>alert('".$erroscripts."');</script>";
}
?>