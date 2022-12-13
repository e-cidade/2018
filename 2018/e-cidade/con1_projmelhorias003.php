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
include("classes/db_projmelhorias_classe.php");
include("classes/db_projmelhoriasresp_classe.php");
include("classes/db_projmelhoriasmatric_classe.php");
include("classes/db_testada_classe.php");
include("dbforms/db_funcoes.php");
$clprojmelhorias = new cl_projmelhorias;
$clprojmelhoriasmatric = new cl_projmelhoriasmatric;
$clprojmelhoriasresp = new cl_projmelhoriasresp;
$db_opcao = 1;
$db_botao = true;
if(isset($confirma)){
  db_postmemory($HTTP_POST_VARS);
  //
  db_inicio_transacao();
  $HTTP_POST_VARS['d40_data_dia'] = date('d');
  $HTTP_POST_VARS['d40_data_mes'] = date('m');
  $HTTP_POST_VARS['d40_data_ano'] = date('Y');
  $HTTP_POST_VARS['d40_login'] = db_getsession('DB_id_usuario');
  $sqlerro = false;
  //perguntar pro paulo disso ae abaixo
  //if($j40_codigo==""){ 
    $result = $clprojmelhorias->incluir(null);
    $d40_codigo = $clprojmelhorias->d40_codigo;
    if($clprojmelhorias->erro_status=='0'){
      $clprojmelhorias->erro(true,false);
      $sqlerro = true;
    } 
    
 // }else{
   // $clprojmelhorias->d40_codigo = $d40_codigo;
 // }
  if($d42_numcgm!=""){
    $clprojmelhoriasresp->d42_codigo=$d40_codigo;
    $clprojmelhoriasresp->d42_numcgm=$d42_numcgm;
    $clprojmelhoriasresp->incluir($d40_codigo);
    if($clprojmelhoriasresp->erro_status=='0'){
      $sqlerro = true;
    } 
  }  
  $tes = split("X",$testada);
  $eixo = split("X",$eixo);
  $obs = split("X",$obs);
  for($ii=0;$ii<sizeof($tes);$ii++){
    $chave = split('-',$tes[$ii]);
    $clprojmelhoriasmatric->d41_codigo = $clprojmelhorias->d40_codigo;
    $clprojmelhoriasmatric->d41_matric = $chave[0];
    $clprojmelhoriasmatric->d41_testada= $chave[1]+0;
    $clprojmelhoriasmatric->d41_eixo   = $eixo[$ii]+0;
    $clprojmelhoriasmatric->d41_obs    = $obs[$ii];
    $clprojmelhoriasmatric->d41_pgtopref = "false";
    $HTTP_POST_VARS['d41_auto'] = true;
    
    //$clprojmelhoriasmatric->erro(true,false);
    $clprojmelhoriasmatric->incluir($clprojmelhorias->d40_codigo,$chave[0]);
    if($clprojmelhoriasmatric->erro_status=='0'){
      $sqlerro = true;
    } 
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_pesquisa_lotes(){
  if(document.form1.d40_codlog.value == ""){
     alert('Selecione o Logradouro.');
  }else{
    document.form1.seleciona.disabled=true; 
    document.getElementById('matriculas').src = "con1_projmelhorias004.php?d40_codlog="+document.form1.d40_codlog.value;
    document.form1.conface.style.visibility='visible';
    }
  }

  function js_confirma(){
    if(document.form1.d40_trecho.value==""){
      alert('Preencha o campo Descrição do trecho');
      return false;
    
    }
    obj = matriculas.document.form1;
    var testada="";
    var xx="";
    var eixo="";
    var obs="";
    var expr = new RegExp("[^0-9\.]+");
    var erro=false;
    for(i=0;i<obj.length;i++){
      if(obj.elements[i].type=='checkbox' && obj.elements[i].checked ){
	matric = obj.elements[i].name.substr(6);
	vlrtes  = obj.elements['d41_testada_'+matric].value;
	vlreixo = obj.elements['d41_eixo_'+matric].value;
	vlrobs  = obj.elements['d41_obs_'+matric].value;
        if(vlrtes.match(expr)) {
	  obj.elements['d41_testada_'+matric].select();
          alert("Este campo deve preenchido somente com números decimais!");
	  erro=true;
	  break;
        }
	if(vlrtes=="" || vlrtes==0){
	  obj.elements['d41_testada_'+matric].select();
          alert("Este campo deve preenchido!");
	  erro=true;
	  break;
	}

        testada += xx+matric+'-'+vlrtes;   
        eixo += xx+vlreixo;   
        obs += xx+vlrobs;   
        xx="X";

      }
      
    }
    document.form1.testada.value=testada;
    document.form1.eixo.value=eixo;
    document.form1.obs.value=obs;

    objconf = document.createElement('input');
    objconf.setAttribute('name','confirma');
    objconf.setAttribute('type','hidden');
    document.form1.appendChild(objconf);
    if(erro==false){
      document.form1.submit();
    }  

  }
  </script>


  <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <table width="790" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>

  <?
  //MODULO: contrib
  $clprojmelhorias->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("nome");
  $clrotulo->label("j14_nome");
  $clrotulo->label("d42_numcgm");
  $clrotulo->label("z01_nome");
  ?>
  <form name="form1" method="post" action="">
  <center>
  <table border="0">
  <tr>
    <td>
    <input name="testada" type="hidden">
    <input name="obs" type="hidden">
    <input name="eixo" type="hidden">
    </td>
  </tr>
    <tr>
      <td nowrap title="<?=@$Td40_codigo?>">
	 <?=@$Ld40_codigo?>
      </td>
      <td> 
  <?
  db_input('d40_codigo',10,$Id40_codigo,true,'text',3,"")
  ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Td40_codlog?>">
	 <?
	 db_ancora(@$Ld40_codlog,"js_pesquisad40_codlog(true);",$db_opcao);
	 ?>
      </td>
      <td> 
  <?
  db_input('d40_codlog',10,$Id40_codlog,true,'text',$db_opcao," onchange='js_pesquisad40_codlog(false);'")
  ?>
	 <?
  db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
	 ?>
      </td>
    </tr>
    <tr>
    <td nowrap title="<?=@$Td42_numcgm?>">
       <?
       db_ancora(@$Ld42_numcgm,"js_pesquisad42_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('d42_numcgm',10,$Id42_numcgm,true,'text',$db_opcao," onchange='js_pesquisad42_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td40_profun?>">
       <?=@$Ld40_profun?>
    </td>
    <td> 
<?
db_input('d40_profun',10,$Id40_profun,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Td40_trecho?>">
       <?=@$Ld40_trecho?>
    </td>
    <td> 
<?
db_input('d40_trecho',54,$Id40_trecho,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  <table>
  <tr>
  <td colspan="2">
  <iframe name="matriculas" id="matriculas" src="" width="750" height="270">
  
  </iframe>
  
  </td>
  </tr>
  
  </table>
  <input name="seleciona" type="button" onclick="js_pesquisa_lotes();" id="SEleciona" value="Seleciona quadras" >
  <input name="confirma" type="button" style='visibility:hidden' onclick='js_confirma()' id="confirma" value="Confirma Lotes" >
  <input name="conface" type="button" style='visibility:hidden' onclick='matriculas.js_conface()' id="conface" value="Confirma Faces" >
  </center>
</form>
<script>
function js_pesquisad42_numcgm(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_nome.php?funcao_js=parent.js_mostracgm1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.d42_numcgm.value+'&funcao_js=parent.js_mostracgm';
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.d42_numcgm.focus(); 
    document.form1.d42_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.d42_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisad40_codlog(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_mostraruas1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.d40_codlog.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.seleciona.disabled=true;
    document.form1.d40_codlog.focus(); 
    document.form1.d40_codlog.value = ''; 
  }else{
    document.form1.seleciona.disabled=false;
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.seleciona.disabled=false;
  document.form1.d40_codlog.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_projmelhorias.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
function js_pesquisaimporta(){
     js_OpenJanelaIframe('top.corpo','db_iframe_importa','func_projmelhorias.php?funcao_js=parent.js_mostraimporta|d40_codigo','Pesquisa',true);
}

</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>


	
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($clprojmelhorias->erro_status=="0"){
  $clprojmelhorias->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clprojmelhorias->erro_campo!=""){
    echo "<script> document.form1.".$clprojmelhorias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clprojmelhorias->erro_campo.".focus();</script>";
  };
}else{
  $clprojmelhorias->erro(true,true);
};
?>