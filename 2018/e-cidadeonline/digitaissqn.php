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
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
                       WHERE m_arquivo = 'digitaissqn.php'
                       ORDER BY m_descricao
                       ");
db_fieldsmemory($result,0);

$result = db_query("select cgc as cgc_pref from db_config where codigo = " . db_getsession("DB_instit"));
db_fieldsmemory($result,0);


mens_help();
db_mensagem("issqnretencao_cab","issqnretencao_rod");
$dblink="index.php";
//$db_verificaip = db_verifica_ip();
//if($db_verificaip == "0"){
  $onsubmit = "onsubmit=\"return js_verificaCGCCPF(document.form1.cgc.value,'');\"";
//}else{
  //$onsubmit = "";
//}


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
    alert("Favor selecionar o mês!");
    return false
  }

  inscricaow = document.form1.inscricaow.value;
  cgc        = document.form1.cgc;
  expReg = "/[- /.]/g";
 
  var inscricaow = new Number(inscricaow.replace(expReg,""));
  cgc1           = LimpaCampo(document.form1.cgc.value,10)
  
  if (cgc1 == 0) {
     alert('Dados informados para o CPF/CNPJ são inválidos');
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
    alert("Favor preencher um dos campos de identificação!");
    document.form1.inscricaow.focus();
    return false  
  }
  if(isNaN(inscricaow)){
     alert("Verifique o campo Inscricão!");
     return false
  }
  
<?
     // conta se o contribuente possui alvará no mesmo município que se encontra
	 // caso a consulta retorna um verdadeiro, se retornar zero o contribuente não
	 // possui alvará no município
	 //$id_usuario = (!isset($id_usuario)||trim($id_usuario)=='')?'NULL':$id_usuario;
	 if (isset($id_usuario) and trim($id_usuario)<>'') {
	   $sqlCidade =  " select count(db_cgmruas.z01_numcgm) as qtd  			  ";
	   $sqlCidade .= " from db_cgmruas                                          ";
	   $sqlCidade .= " inner join cgm on cgm.z01_numcgm = db_cgmruas.z01_numcgm ";
	   $sqlCidade .= " where cgm.z01_numcgm = $id_usuario 					  ";
	 
	   @$sqlConsultaCidade = db_query($sqlCidade);
	   @$rRetornoContribuinte = pg_fetch_assoc ($sqlConsultaCidade);
	 } else {
	 	$rRetornoContribuinte['qtd'] = 0;
	 }
	    
  if(@$rRetornoContribuinte['qtd'] == 1) {
?>
  
    if(document.form1.inscricaow.value == "") {
      alert("Selecione uma inscrição abaixo!");
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
  //verifica se permite acessar sem login
 //##################eu tirei #######################
 
 
 /* if($w13_liberaissretido=="f" && $id_usuario==""){
   ?>
   <table width="300" align="center" border="0" bordercolor="#cccccc" cellpadding="2" cellspacing="0" class="texto">
    <tr height="220">
     <td align="center">
      <img src="imagens/atencao.gif"><br>
      Para acessar o ISS Retido na Fonte, efetue login.
     </td>
    </tr>
   </table>
   
   <?
   exit;*/
  
  
  
  //verifica se está logado
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
    //db_msgbox("Nenhuma inscrição encontrada!");
    //db_redireciona("centro_pref.php");
     $sql_z01_cgccpf = db_query("SELECT cgm.z01_cgccpf FROM cgm WHERE z01_numcgm = $id_usuario");
     $rRetorno = pg_fetch_assoc ($sql_z01_cgccpf);
	   $var_cnpj = $rRetorno['z01_cgccpf'];
	  }
	 
  ?>
  <table width="90%" border="0" cellpadding="0" cellspacing="0" class="texto">
     
    <tr> 
      <td valign="top"> 
       <table width="100%" height="223" class="texto">
          <tr>
            <td height="148" valign="top"> 
            <form name="form1" method="post" action="opcoesissqn.php">
            <input name="primeiravez" type="hidden" value="true">
              <table width="100%" border="0" cellspacing="0" cellpadding="3" class="texto">
                <tr>
                  <td>
                   Inscrição Alvará:
                   <input name="inscricaow" type="text" class="digitacgccpf" style="BACKGROUND-COLOR: #eaeaea;" readonly size="8" maxlength="6">
                   CNPJ/CPF:
                  <input name="cgc" value="<?=@$var_cnpj?>" type="text" 
                         class="digitacgccpf" id="cgc" style="BACKGROUND-COLOR: #eaeaea;" 
                         readonly size="18" maxlength="18" 
                         onKeyPress='FormataCPFeCNPJ(this,event); return js_teclas(event);'>
                  </td>
                  <td class="green" align="center">
                   Selecione o Contribuinte abaixo.
                  </td>
                </tr>
                <tr>
                  <td>
                   Competência:

		   <select name="ano" onchange="js_criames(this,<?=$cgc_pref?>)">
                    <?
                      $sano = date("Y");
                      if(date("m")==12)
                       $sano ++;
                      //for($ci = $sano; $ci >= ($sano-10); $ci--){
                      for($ci = $sano; $ci >= 2000; $ci--){
                        echo "<option value=".$ci." >$ci</option>";
                      }
                    ?> 
                    </select>
                    <select class="digitacgccpf" name="mes" id="mes" >
                      <option value="mes">Mês</option>
                    </select> 
                    <script>
                    js_criames(document.form1.ano, <?=$cgc_pref?>);
                    </script>
                  <input name="first" type="hidden">
                  <input class="botao" type="submit" name="pesquisa" value="Pesquisa"  onclick="return js_vericampos();">
                </td>
              </tr>
              <tr> 
              </table>
              </form>
              <form name="form2" method="post" >
              <table width="100%" border="1" cellspacing="0" cellpadding="3" class="texto">
               <?
               //é escritório?
			   $wherebx = " and q10_dtfim is null ";
			   if (@$mostrainscricao == 1) {
				 // todas
				 $wherebx = " and q10_dtfim is null ";
			   } else if (@$mostrainscricao == 2) {
			     // baixadas
			     $wherebx = " and q10_dtfim is null and q02_dtbaix is not null ";
			   } if (@$mostrainscricao == 3) {
				 // não baixadas
				 $wherebx = " and q10_dtfim is null and q02_dtbaix is null ";
			   }
               $result  = $clescrito->sql_record($clescrito->sql_query("","q02_inscr,cgm.z01_nome as z01_nome,cgm.z01_cgccpf as z01_cgccpf","","q10_numcgm = $id_usuario $wherebx"));
               //die($clescrito->sql_query("","q02_inscr,cgm.z01_nome as z01_nome,cgm.z01_cgccpf as z01_cgccpf","","q10_numcgm = $id_usuario $wherebx"));
               $escrito = $clescrito->numrows;
               if($escrito!=0){
                ?>
				   <tr height="20" ><td colspan="3"><b>Mostrar inscriçoes</b> 
				   <select name="mostrainscricao" onchange = "document.form2.submit();">
				   <? echo "
				   <option value = '1'".($mostrainscricao == 1?"selected":"").">Todas</option>
				   <option value = '2'".($mostrainscricao == 2?"selected":"").">Somente baixadas</option>
				   <option value = '3'".($mostrainscricao == 3?"selected":"").">Somente não baixadas</option>
				   ";
				   ?>
				   </select>
				   </td></tr>
               
               
               <?
                //busca clientes do escritório
                for ($x = 0; $x < $escrito; $x++) {
                 if($x == 0){
                 ?><tr height="20" bgcolor="#eaeaea"><td colspan="3"><b>Inscrições que tenho acesso</b></td></tr><?
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
               //é issbase
               $result2 = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,z01_nome,z01_cgccpf","","q02_numcgm = $id_usuario"));
               $issbase = $clissbase->numrows;
               //busca dados do issbase
               @$x = $x==""?0:$x;
               for($y=$x;$y<$issbase+$x;$y++){
                if($y==$x){
                ?><tr height="20" bgcolor="#eaeaea"><td colspan="3"><b>Minhas Inscrições</b></td></tr><?
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
             <?}else{
              if(@$_COOKIE["cookie_codigo_cgm"]!=""){
               @$cookie_codigo_cgm = $_COOKIE["cookie_codigo_cgm"];
               @$result  = $clissbase->sql_record($clissbase->sql_query("","issbase.q02_inscr,cgm.z01_cgccpf","","cgm.z01_numcgm = $cookie_codigo_cgm"));
               @db_fieldsmemory($result,0);
              }
              ?>
               <table width="100%" height="200" border="0" cellspacing="0" cellpadding="0" class="texto">
                <form name="form1" method="post" action="opcoesissqn.php">
                <input name="primeiravez" type="hidden" value="true">
                <tr>
                 <td colspan="2" align="center">
                  <br> <?=$DB_mens1?>
                 </td>
                </tr>
                <tr>
                 <td colspan="2" align="center">
                  <a href="digitaissqn.php?outro">:: Pesquisar Outra Inscrição ::</a>
                 </td>
                </tr>
                <tr>
                 <td align="center">
                 	<?/*
                 	$sql ="select q02_inscr,z01_cgccpf,z01_numcgm,z01_nome from cgm inner join issbase on z01_numcgm=q02_numcgm where z01_cgccp=<script>document.form1.cgc.value</script> and q02_dtbaix is null"; 
                 	$result3  = $clissbase->sql_record($sql);
                 	$linha = $clissbase->numrows;
                 	if ($linhas !=0){
                 		db_fieldsmemory($result3,0);
                 		//echo "insc =$q02_inscr"
                 	}*/
                 	
                 	?>
                 
                 
                   <b>CNPJ/CPF</b>:
                   <input name="cgc" type="text" class="digitacgccpf" id="cgc" value="<?=@$z01_cgccpf?>" 
                          size="18" maxlength="18" 
                          onKeyPress="FormataCPFeCNPJ(this,event); return js_teclas(event);" >
                  
                   Inscrição Alvará:
                   <!--
                    10/05/2006
                    Se não digitar inscrição e o cont. possuir inscr, ele encontrará na próxima vez que clicar em iss ret.
                    e não encontrará na busca dos valores.
                    <input name="inscricaow" type="text" class="digitacgccpf" value="<?=@$q02_inscr?>" size="8" maxlength="6">
                   -->
                   <input name="inscricaow" type="text" class="digitacgccpf" value="<?=@$q02_inscr?>" size="8" maxlength="6">
                   <br>
                   <b>Atenção:</b> Campo inscrição só deve ser preenchido
                   para empresas do Município.<br>Empresas de fora do
                   Município devem preencher somente o CNPJ.
                  </td>
                </tr>
                <tr>
                  <td align="center">
                   Competência:
                    <select name="ano" onchange="js_criames(this,<?=$cgc_pref?>)">
		      <?
		      if ( $cgc_pref == "87366159000102" ) {
		        $sano = 2010;
		      } else {
		        $sano = date("Y");
		      }
                      if(date("m")==12)
                       $sano ++;
                      //for($ci = $sano; $ci >= ($sano-10); $ci--){
                      for($ci = $sano; $ci >= 2000; $ci--){
                        echo "<option value=".$ci." >$ci</option>";
                      }
                    ?>
                    </select>
                    <select class="digitacgccpf" name="mes" id="mes" >
                      <option value="mes">Mês</option>
                    </select>
                    <script>
                    js_criames(document.form1.ano,<?=$cgc_pref?>);
                    </script>
                  <input name="first" type="hidden">
                  <input class="botao" type="submit" name="pesquisa" value="Pesquisa"  onclick="return js_vericampos()">
                </td>
               </tr>
               </form>
              </table>
             <?}?>
            </td>
          </tr>
      </td>
    </tr>
    <tr>
      <td height="60" align="<?=$DB_align2?>">
        <?=$DB_mens2?>
      </td>
    </tr>
  </table>
</center>
</body>
</html>
<?
db_logs("","",0,"Digita Codigo da Inscricao para o issqn retencao.");
if(isset($erroscripts)){
  echo "<script>alert('".$erroscripts."');</script>";
}
?>