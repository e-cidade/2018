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
include("libs/db_stdlib.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_aidof_classe.php");
include("classes/db_aidofweb_classe.php");
$cl_aidofweb = new cl_aidofweb ;
$clarreinscr = new cl_arreinscr;
$clissbase   = new cl_issbase;
$claidof     = new cl_aidof;
postmemory($HTTP_POST_VARS);
$ip= $HTTP_SERVER_VARS["REMOTE_ADDR"];

$sqlcgm="select * from issbase inner join cgm on q02_numcgm= z01_numcgm where q02_inscr=$inscricaow";
$resultcgm=db_query($sqlcgm);
$linhascgm=pg_num_rows($resultcgm);
if ($linhascgm>0){
db_fieldsmemory($resultcgm,0);
}

/*
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                   FROM db_menupref
                   WHERE m_arquivo = 'digitaaidof.php'
                   ORDER BY m_descricao
                   ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}

if(@$_COOKIE["cookie_codigo_cgm"]==""){
 // issbase
 if($inscricaow!=""){
  $result  = $clissbase->sql_record($clissbase->sql_query("","cgm.z01_numcgm,cgm.z01_nome","","issbase.q02_inscr = $inscricaow"));
  $linhas1 = $clissbase->numrows;
  db_fieldsmemory($result,0);
  setcookie("cookie_codigo_cgm",$z01_numcgm);
  setcookie("cookie_nome_cgm",$z01_nome);
 }
}
$db_verifica_ip = db_verifica_ip();
//mens_help();
$dblink="digitaaidof.php";
db_logs("","",0,"Digita Aidof.");
postmemory($HTTP_POST_VARS);
$clquery = new cl_query;
$inscricaow!=""?"":$inscricaow = 0 ;
if ( !empty($cgc) ){
  $cgccpf = $cgc;
}else{
  if (!empty($cpf) ){
    $cgccpf = $cpf;
  }else{
    $cgccpf = "";
  }
}
$cgccpf = str_replace(".","",$cgccpf);
$cgccpf = str_replace("/","",$cgccpf);
$cgccpf = str_replace("-","",$cgccpf);  

if($cgccpf != "" ) {
 $result  = $clissbase->sql_record($clissbase->sql_query("","*","","cgm.z01_cgccpf = '$cgccpf' and issbase.q02_inscr  = $inscricaow"));
}else{
 $result  = $clissbase->sql_record($clissbase->sql_query("","*","","issbase.q02_inscr = $inscricaow"));
}

if($clissbase->numrows != 0){
  db_fieldsmemory($result,0);
}else{
  db_redireciona("digitaaidof.php?".base64_encode('erroscripts=Acesso a Rotina Inválido, verifique os dados digitados!'));
}  
if(!isset($DB_LOGADO) && $m_publico !='t'){
  $sql = "select fc_permissaodbpref(".db_getsession("DB_login").",2,$inscricaow)";
  $result = db_query($sql);
  if(pg_numrows($result)==0){
    db_redireciona("digitaaidof.php?".base64_encode('erroscripts=Acesso não Permitido. Contate a Prefeitura.'));
    exit;
  }
  $result = pg_result($result,0,0);
  if($result=="0"){
    db_redireciona("digitaaidof.php?".base64_encode('erroscripts=Acesso não Permitido. Contate a Prefeitura.'));
    exit;
  }
} 
$result  = $clissbase->sql_record($clissbase->sql_query("","*","","issbase.q02_inscr  = $inscricaow"));
if($clissbase->numrows != 0){
   db_fieldsmemory($result,0);
}
*/
if(isset($gravar)){
  

$sql="select * from aidof
      where y08_inscr = $inscricaow and y08_quantlib = '0' and (y08_cancel is false or y08_cancel is null)
      ";
//die($sql."  nota".$tiponota);                                   
$result=db_query($sql);                                
 $linhas = pg_num_rows($result);      
          
if($linhas > 0){
	 for($i = 0;$i < $linhas; $i++){
		 db_fieldsmemory($result,$i);
		 if($y08_nota==$tiponota){
			 db_msgbox("Contribuinte com pedidos em Aberto! Aguarde comunicado da Prefeitura.");
			 db_redireciona("digitaaidof.php");
			 exit;
		 }
	 }
 
}
  // grava os dados da solicitação...$quantnotasrec
  
  db_inicio_transacao();
  $dataat = date("Y-m-d");
  $hora = date("H:i");
  $codigo = db_query("select nextval('aidof_y08_codigo_seq')");
  $codigo = pg_result($codigo,0,0);
  $claidof->y08_nota = $tiponota;    // tipo de nota
  $claidof->y08_inscr = $inscricaow;
  $claidof->y08_dtlanc = $dataat;
  $claidof->y08_notain = $notaini+1;
  $claidof->y08_notafi = $quantnotasrec;
  $claidof->y08_numcgm = $numgrafica; // cgm da grafica
  $claidof->y08_obs = $obs;
  $claidof->y08_login = '1';
  $claidof->y08_codproc = '0';
  $claidof->y08_quantsol = $quantnotas;
  $claidof->y08_quantlib = '0';
  $claidof->incluir($codigo);
  
  if($claidof->erro_status=="0"){
   @$claidof->erro();
  }else{ // abre um arquivo pdf
  	
  	/*
	$mes = date("m");
	$ano = date("Y");
	$dia = date("d");
	$ins= $inscricaow;
	$cnpj ="$z01_cgccpf";
	$nros = $ano . $mes . $dia . $codigo . $ins . $cnpj ;
	$t1 = strrev($nros);
	$cod= md5($t1);
  	*/
  	  	
  	$cl_aidofweb->y07_aidof = $claidof->y08_codigo;
  	$cl_aidofweb->y07_ip    = $ip;
  	$cl_aidofweb->y07_data  = $dataat;
  	$cl_aidofweb->y07_hora  = $hora;
  	//$cl_aidofweb->y07_codautenticidade = $cod;
  	$cl_aidofweb->incluir(null);
  	
  	db_fim_transacao();
   //echo "<script>jan = window.open('aidof.php?".base64_encode('codigo='.$codigo.'&inscricao='.$inscricaow.'&grafica='.$numgrafica.'&nota='.$nomenota)."','','height=500,width=650,scrollbars=1');</script>";
   echo "<script>jan = window.open('aidof.php?codigo=$codigo&inscricao=$inscricaow&grafica=$numgrafica&nota=$nomenota','','height=500,width=650,scrollbars=1');</script>";
   db_redireciona("digitaaidof.php");
  }
}// acaba o gravar
//verifica se contribuinte possui pedido aberto



?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("digitaaidof.php,pesquisagrafica.php,opcoesaidof.php");
function maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_verifica(){
	
  var alerta="";
  notas= new Number(document.form1.quantnotas.value);
  vgrafica=document.form1.numgrafica.value;
  tipo = document.form1.tiponota.value;
  if(isNaN(notas)){
    alert('Este campo deve ser preenchido somente com números');
    document.form1.quantnotas.value = '';
    document.form1.quantnotas.focus();
  }
  if(notas=="" || isNaN(notas)){
    alerta +="Quantidade de Notas\n";
  }
  if(vgrafica==""){
    alerta +="Grafica\n";
  }
  if(tipo=="0"){
    alerta +="Tipo de nota\n";
  }
if(alerta == ""){
  return true;
}  
  if(alerta!=""){
    alert("Verifique os seguintes campos:\n"+alerta);
    return false;
  }else{
    return true;
  }
}
function js_buscagrafica(){
  pesquisagrafica.location.href = 'pesquisagrafica.php?funcao_js=js_mostragrafica|0|1';
  document.getElementById('g').style.visibility = 'visible';
}  
function js_calculanotas(notafinal){
  var notasolicitada = document.form1.quantnotas.value;
  if(isNaN(notasolicitada)){
    alert('Este campo deve ser preenchido somente com números');
    document.form1.quantnotas.value = '';
    document.form1.quantnotas.focus();
  }
  resultado = eval(notafinal+'+'+ notasolicitada);  
  document.form1.quantnotasrec.value = resultado;
}  
function js_tipo(){ 
	document.form1.submit();
} 
</script>
<style type="text/css">
<?
db_estilosite();
?>
td{
  font-size: <?=$w01_tamfontesite?>
  }
</style>

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<form name="form1" method="post" action="opcoesaidof.php" >
<center>
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr align="center">
     <td>
       <table width="89%" border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td>
           </td>
         </tr>
         <tr>
             <td width="89%" colspan="5">
               <table border="0">
                <tr>
                   <input type="hidden" name="z01_numcgm" value="<?=@$z01_numcgm?>" size="5" maxlength="5" onChange="js_calculanotas('<?=@$y08_notafi?>')">
                   <input type="hidden" name="inscricaow" value="<?=@$inscricaow?>" size="5" maxlength="5" onChange="js_calculanotas('<?=@$y08_notafi?>')">
                   <td width="19%" colspan="3" nowrap><b>Nome
                     ou Raz&atilde;o Social:</b>
                     <font color="<?=$w01_corfontesite?>"> <?=$z01_nome?></font>
                   </td>
                </tr>
                <tr>
                   <td width="19%" nowrap><b>Endereco:</b>
                     <font color="<?=$w01_corfontesite?>">  <?=$z01_ender?>
                     </font>
                   </td>
                   <td width="19%" nowrap><b>Número:</b>
                     <font color="<?=$w01_corfontesite?>">  <?=$z01_numero?>
                     </font>
                   </td>
                   <td width="19%" nowrap><b>Bairro:</b>
                     <font color="<?=$w01_corfontesite?>">  <?=$z01_bairro?>
                     </font>
                   </td>
                 </tr>
               </table>
             </td>
           </tr>
           <tr height="30">
             <td colspan="2">
             <b>Tipo de Nota:&nbsp;&nbsp;&nbsp;&nbsp;</b>
             <?
             $result = db_query("select distinct q14_nota,q09_descr
                                  from issbase
                                        inner join tabativ on q07_inscr = q02_inscr
                                        inner join atinota on q14_ativ = q07_ativ
                                        inner join notasiss on q09_codigo = q14_nota
                                ");
             if(pg_numrows($result) == 1){
               db_fieldsmemory($result,0);
               echo $q09_descr;
               echo "<input type=\"hidden\" value=\"$q14_nota\" name=\"nota\">";
               echo "<input type=\"hidden\" value=\"$q09_descr\" name=\"nomenota\">";
             }elseif(pg_numrows($result) > 1){
               echo "<select name=\"tiponota\" onchange=\"js_tipo()\">";
                     echo "<option value=\"0\">Selecione</option>";
               for($i = 0;$i < pg_numrows($result); $i++){
                 db_fieldsmemory($result,$i);
                 //echo "<option value = \"$m\"".($m==$mesdai?" selected":"").">".db_mes($m)." </option>";
                 echo "<option value=\"$q14_nota\"".($q14_nota==$tiponota?" selected":"")." >$q09_descr</option>";
               }
               echo "</select>";
               echo "<input type=\"hidden\" value=\"$q14_nota\" name=\"nota\">";
               echo "<input type=\"hidden\" value=\"$q09_descr\" name=\"nomenota\">";
             }/*elseif(pg_numrows($result) == 0){
               echo "<script>alert('A inscrição não possui atividade cadastrada, entre em contato com a Prefeitura Municipal.')</script>";
               db_redireciona("digitaaidof.php");
             }*/
             ?>
             </td>
           </tr>
           <tr height="30">
             <td colspan="2">
             <? if(isset($tiponota)){?>
             <fieldset style="border: 1px solid <?=$w01_corfontesite?>">
               <legend> <b>Última Solicitação</b> </legend>
               <table cellpadding="0" cellspacing="0" border="0" width="100%">
               <? 
               //die($tiponota);
               $result = db_query("select * from aidof where y08_inscr = $inscricaow and y08_nota=$tiponota and (y08_cancel is false or y08_cancel is null) order by  y08_codigo desc limit 1");
               if(pg_numrows($result) != 0){
                 db_fieldsmemory($result,0);
                 echo "<tr class=\"bold\" bgcolor=\"#eaeaea\">
                         <td align=\"center\">
                           Data:
                         </td>
                         <td align=\"center\">
                           Quantidade de Notas :
                         </td>
                         <td align=\"center\">
                           Número das notas:
                         </td>
                       </tr>  ";
                 echo "<tr>
                         <td align=\"center\">
                           ".db_formatar($y08_dtlanc,'d')."
                         </td>
                         <td align=\"center\">
                           ".(($y08_notafi - $y08_notain) + 1)."
                         </td>
                         <td align=\"center\">
                           ".$y08_notain." até ".$y08_notafi."
                         </td>
                       </tr>  ";
                 echo "<tr>
                         <td colspan=\"3\" align=\"center\">
                           <input type=\"hidden\" value=\"$y08_notafi\" name=\"notaini\"><br>
                           <input type=\"hidden\" value=\"$y08_codigo\" name=\"codant\"><br>
                           		";
                           //&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"reemite\" type=\"button\" class=\"botao\" value=\"Reemite última solicitação\" onClick=\"js_abre()\">
                         echo"
                         </td>
                       </tr>
                           ";

               }else{
                 $y08_notafi = "0";
                 echo "<tr>
                         <td align=\"center\">
                           Não há solicitações anteriores.
                         </td>
                       </tr>  ";
               }
                 echo "<tr>
                         <td colspan=\"3\" align=\"center\">
                           <input type=\"hidden\" value=\"$y08_notafi\" name=\"notaini\">
                         </td>
                       </tr>
                           ";
               ?>
               </table>
             </fieldset>
             <?}?>
             </td>
           </tr>
           <tr nowrap height="30">
             <td width="200">
               Quantidade de Notas :&nbsp;&nbsp;&nbsp;
               <input  type="text" name="quantnotas" value="" size="5" maxlength="5" onChange="js_calculanotas('<?=$y08_notafi?>')">
             </td>
           </tr>
           <tr height="30">
             <td>
             <? if(isset($tiponota) && $tiponota!=0){?>
               Notas a serem impressas :&nbsp;&nbsp;&nbsp;
               de&nbsp;<?=($y08_notafi + 1)?>&nbsp;até
               <input type="text" name="quantnotasrec" value="" size="5" maxlength="5" style="border: none; border-color: #transparent; background-color: <?=$w01_corbody?>; color: <?=$w01_corfontesite?>; font-size:<?=$w01_tamfontesite?>">
               <?}?>
             </td>
           </tr>
           <tr>
             <td colspan="5"> <table width="457" border="0">
                 <tr height="30" valign="center">
                   <td align="left" nowrap>Gráfica:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <?
                   $sqlgraf= "select z01_nome,z01_numcgm from cgm inner join graficas on y20_grafica = z01_numcgm order by z01_nome";
                   $resultgraf=db_query($sqlgraf);
                   $linhasgraf=pg_num_rows($resultgraf);
                   echo"<select name='numgrafica' >";
                   for($i = 0;$i < $linhasgraf; $i++){
                 		db_fieldsmemory($resultgraf,$i);
                 		echo "<option value='$z01_numcgm'>$z01_nome</option>";
               	   }
               	   echo "</select>";
                   ?>
                   
                 <!--   <input name="grafica" type="text" readonly size="40" maxlength="40" value="">
                    <input name="numgrafica" type="hidden" size="40" maxlength="40" value="">
                 -->   
                   </td>
                   <td align="left" colspan="4">
                    <!--<input name="pesquisa" class="botao" type="button"  value="Pesquisar" onclick="js_buscagrafica() "><font size="1" face="arial"></font>-->
                   </td>
                 </tr>
               </table>
             </td>
           </tr>
           <tr>
             <td>
               Observações :
             </td>
           </tr>
           <tr>
             <td>
               <textarea name="obs" rows="2" cols="60"></textarea>
             </td>
           </tr>
           <tr>
             <td colspan="5" align="center" >
             <br>
             <input name="gravar" type="submit" class="botao" value="Solicitar AIDOF" onclick="return js_verifica();" >
             </td>
           </tr>
         </table>
     </td>
   </tr>
 </table>
</center>
</form>
</body>
</html>