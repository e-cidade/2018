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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_arrecad_classe.php");
include("classes/db_prescricao_classe.php");
include("classes/db_arreprescr_classe.php");
include("libs/db_sql.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<DIV id=textInLoad style="position:absolute;top:50%;left:40%"></div>
<script language=javascript>
textInLoad.innerHTML="<DIV id='msg_proc' style='border:1px solid #eaeaea;background-color:#f3f3f3;padding:5px;position:absolute;top:10%;left:10%;width:200;height:20;' class='pequeno3' align='center'>Carregando...</div>"
</script>
<div id='int_perc1' align="left" style="position:absolute;top:60%;left:35%; float:left; width:300; background-color:#ECEDF2; padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
  <div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
   <div id='int_perc2' style="width:0%; background-color:green;">&nbsp;</div>
   </div>
  </div>
</div>
<?
$clprescricao = new cl_prescricao;
$clarrecad    = new cl_arrecad;
$clarreprescr = new cl_arreprescr;

db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
//db_msgbox("dfhaskd hflkashd lfkhasdl flash k");
//pega nome do usuário
$sql = "select db_usuarios.nome as db_usunome
          from db_usuarios 
	       where db_usuarios.id_usuario = ".db_getsession("DB_id_usuario");
$result = pg_query($sql);
db_fieldsmemory($result,0);
?>
<table>
 <tr><td height="15"></td></tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<?
//MODULO: caixa
$clprescricao->rotulo->label();
$clarreprescr->rotulo->label();

$func_nome = new janela('func_nome','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=780;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
  </tr>
  <tr id="numcgm">
    <td><?db_ancora("<strong>Numcgm</strong>","js_pesquisa_numcgm(true);",1);?></td>
    <td> 
        <? db_input('k31_numcgm',10,@$k31_numcgm,true,'text',$db_opcao,"onblur=js_pesquisa_numcgm(false);") ?>
        <? db_input('k31_nome',50,@$k31_nome,true,'text',3) ?>
    </td>    
  </tr>
  <tr id="matricula">
     <td><?db_ancora("<strong>Matrícula</strong>","js_mostramatricula(true);",1);?></td>
     <td> 
        <? db_input('k31_matric',10,@$k31_matric,true,'text',$db_opcao,"onblur=js_mostramatricula(false);") ?>
     </td>    
   </tr>
   <tr id="inscr">
    <td><?db_ancora("<strong>Inscrição</strong>","js_mostrainscricao(true);",1);?></td>
    <td> 
        <? db_input('k31_inscr',10,@$k31_inscr,true,'text',$db_opcao,"onblur=js_mostrainscricao(false);") ?>
    </td>    
  </tr>
  
   <tr> 
            <td width="" align="left" nowrap title="">
               <b>Prescrever Débitos Notificados: </b>
            </td>
            <td width="" align="left" nowrap title="">
             <?
				$arr_op = array("n"=>"Não","s"=>"Sim");
				db_select("prescrnoti",$arr_op,true,"text");
				?>
            </td>
          </tr>
 </table>
</center>
<input name="processar" type="button" value="Pesquisar" onclick="return js_verifica()">
</form>
<script>
function js_limpacampos(){
    document.form1.k31_numcgm.value = '';
    document.form1.k31_matric.value = '';
    document.form1.k31_inscr.value  = '';
}

function js_verifica(){
  numcgm = '';
  matric = '';
  inscr  = '';
  if(document.form1.k31_numcgm.value != ''){
     numcgm = document.form1.k31_numcgm.value;    
  }
  if(document.form1.k31_matric.value != ''){
     matric = document.form1.k31_matric.value;    
  }
  if(document.form1.k31_inscr.value != ''){
     inscr = document.form1.k31_inscr.value;
  }
  if( document.form1.k31_numcgm.value == "" && document.form1.k31_matric.value == "" && document.form1.k31_inscr.value == "" && document.form1.k31_obs.value == "" ){
    alert('Favor preencher pelo menos uma das informações de origem da pesquisa(CGM, Matricula ou Inscrição).');
    return false;
  }
  if(numcgm != '' && matric != ''){
    alert('Preencha somente uma das informações de origem da pesquisa(CGM, Matricula ou Inscrição).');
    return false;
  }else if(matric != '' && inscr != ''){
    alert('Preencha somente uma das informações de origem da pesquisa(CGM, Matricula ou Inscrição).');
    return false;
  }else if(numcgm != '' && inscr != ''){
    alert('Preencha somente uma das informações de origem da pesquisa(CGM, Matricula ou Inscrição).');
    return false;
  }

  if( confirm('Confirma Prescrição dos Débitos' ) == false ){
    return false;
  }
  querystring = 'numcgm='+numcgm+'&matric='+matric+'&inscr='+inscr+'&prescrnoti='+document.form1.prescrnoti.value;
  js_OpenJanelaIframe('','db_iframe_proc','func_prescreverdivida.php?'+querystring,'Prescrição de Divida',true);
}  
function js_prescreve_gp( opcao ){
  if( opcao == 1 ){
    document.getElementById('numcgm').style.visibility="visible";
    document.getElementById('matricula').style.visibility="visible";;
    document.getElementById('inscr').style.visibility="visible";;
  }
  else {
    document.getElementById('numcgm').style.visibility='hidden';
    document.getElementById('matricula').style.visibility='hidden';
    document.getElementById('inscr').style.visibility='hidden';
  }
}

//Procura CGM
function js_pesquisa_numcgm(mostra){
 if(mostra == true){
   func_nome.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome';
   func_nome.mostraMsg();
   func_nome.show();
   func_nome.focus();
//  js_OpenJanelaIframe('','db_iframe_proc','func_cgm.php?funcao_js=parent.js_mostra1|z01_numcgm|z01_nome','Pesquisa',true);
 } else {
 	if (document.form1.k31_numcgm.value != '') {
	  func_nome.jan.location.href = 'func_nome.php?pesquisa_chave=' + document.form1.k31_numcgm.value + '&funcao_js=parent.js_mostra';
	} else{
      if (document.form1.k31_inscr.value == '' && document.form1.k31_matric.value == '' && document.form1.k31_numcgm.value == '') {
	  	document.form1.k31_nome.value = "";
	  }
	}
  //js_OpenJanelaIframe('','db_iframe_proc','func_cgm.php?pesquisa_chave='+document.form1.k31_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
 }
}

function js_mostracgm(chave1,chave2){
 document.form1.k31_matric.value = '';
 document.form1.k31_inscr.value = ''; 
 document.form1.k31_numcgm.value = chave1;
 document.form1.k31_nome.value = chave2;
 func_nome.hide();
 }


function js_mostra(erro, chave){
  document.form1.k31_nome.value = chave;
  if(erro==true){
   document.form1.k31_numcgm.focus();
   document.form1.k31_numcgm.value = '';
  }
 }

// Procura matricula
function js_mostramatricula(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenchematricula|0|1|2';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
  	if (document.form1.k31_matric.value != '') {
      func_nome.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.k31_matric.value+'&funcao_js=parent.js_preenchematricula1';
	} else{
      if (document.form1.k31_inscr.value == '' && document.form1.k31_matric.value == '' && document.form1.k31_numcgm.value == '') {
	  	document.form1.k31_nome.value = "";
	  }
	}
  }
}
 function js_preenchematricula(chave,chave1,chave2){
   document.form1.k31_numcgm.value = '';
   document.form1.k31_inscr.value = '';
   document.form1.k31_matric.value = chave;
   document.form1.k31_nome.value = chave2;
   func_nome.hide();
 }
 function js_preenchematricula1(chave,erro){
   document.form1.k31_nome.value = chave;
   if( erro == true ){
     document.form1.k31_matric.focus();
     document.form1.k31_matric.value = '';
   }
 }


//Procura ISSQN
function js_mostrainscricao(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_issbase.php?funcao_js=parent.js_preencheinscricao|0|1|2';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
  	if (document.form1.k31_inscr.value != '') {
      func_nome.jan.location.href = 'func_issbase.php?pesquisa_chave='+document.form1.k31_inscr.value+'&funcao_js=parent.js_preencheinscricao1';
	} else {
	  if (document.form1.k31_inscr.value == '' && document.form1.k31_matric.value == '' && document.form1.k31_numcgm.value == '') {
	  	document.form1.k31_nome.value = "";
	  }
	}
  }
}
function js_preencheinscricao(chave,chave1,chave2){
   document.form1.k31_numcgm.value = ''; 	
   document.form1.k31_matric.value = ''; 	
   document.form1.k31_inscr.value = chave;
   document.form1.k31_nome.value = chave2;
   func_nome.hide();
 }
function js_preencheinscricao1(chave,erro){
   document.form1.k31_nome.value = chave;
   if( erro == true ){
     document.form1.k31_inscr.focus();
     document.form1.k31_inscr.value = '';
   }
}
 
</script>
    </center>
	</td>
  </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
  textInLoad.style.display="none";
  document.getElementById('int_perc1').style.visibility='hidden';
</script>

</body>
</html>