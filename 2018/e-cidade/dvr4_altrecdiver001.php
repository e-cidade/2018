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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_arrecad_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$db_opcao=1;
$clarrecad = new cl_arrecad;
$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_nome");
$clrotulo->label("dv05_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("dv05_coddiver");
$clrotulo->label("dv09_procdiver");
$clrotulo->label('k02_codigo');
$clrotulo->label('k02_drecei');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC>
  <form class="container" name="form1" method="post" action="">
   <?
   if (isset($pesquisar)){
   	   db_input('dv05_coddiver',5,$Idv05_coddiver,true,'hidden',1,"");
   	   db_input('dv05_numcgm',5,$Idv05_numcgm,true,'hidden',1,"");
   	   db_input('j01_matric',5,$Ij01_matric,true,'hidden',1,"");
   	   db_input('q02_inscr',5,$Iq02_inscr,true,'hidden',1,"");
   	   db_input('dv09_procdiver',5,$Idv09_procdiver,true,'hidden',1,"");   	 
   ?>   
     <fieldset>
      <legend>Procedimentos - Receitas de Diversos</legend>
       <table class="form-container">
         <tr >
        <td align="right" nowrap title="<?=@$Tk02_codigo?>" >
          <?
             db_ancora(@$Lk02_codigo,"js_pesquisatabrec(true);",4)
          ?>
        </td>
        <td>
          <?
            db_input('k02_codigo',4,$Ik02_codigo,true,'text',4,"onchange='js_pesquisatabrec(false);'")
          ?>
          <?
            db_input('k02_drecei',40,$Ik02_drecei,true,'text',3,'')
          ?>

        </td>
      </tr>
      <tr>
      	<td nowrap>
      	<b>Tipo de Processamento:</b> 
        </td>
          
        <td>
       		<?
       		$tipos = array("t"=>"Todos","v"=>"Débitos Vencidos","n"=>"Débitos Não Vencidos");
       		db_select("tipo",$tipos,true,"text",1);
       		?>
       </td>
      </tr>
      </table>
      </fieldset>
	   		 <input type="submit" name="processar" value="Processar" onclick="return js_processa();">

   <?  
   }else if (isset($processar)){   	
   	db_criatermometro('termometro', 'Concluido...', 'blue', 1);   	
   }else{
   ?>
    <fieldset>
    <legend>Procedimentos - Receitas de Diversos</legend>
    <table class="form-container">
     <tr>   
       <td>
      <?
       db_ancora($Ldv05_coddiver,'js_diver(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('dv05_coddiver',5,$Idv05_coddiver,true,'text',1,"onchange='js_diver(false)'");
       db_input('z01_nome',40,0,true,'text',3,"","z01_nomediver");
      ?>
       </td>
     </tr>
     <tr>   
      <td>
      <?
       db_ancora($Ldv05_numcgm,' js_cgm(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('dv05_numcgm',5,$Idv05_numcgm,true,'text',1,"onchange='js_cgm(false)'","dv05_numcgm");
       db_input('z01_nome',40,0,true,'text',3,"","z01_nomecgm");
      ?>
       </td>
     </tr>
     <tr>   
       <td>
      <?
       db_ancora($Lj01_matric,' js_matri(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('j01_matric',5,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
       db_input('z01_nome',40,0,true,'text',3,"","z01_nomematri");
      ?>
       </td>
     </tr>     
     <tr>   
       <td>
      <?
       db_ancora($Lq02_inscr,' js_inscr(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('q02_inscr',5,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
       db_input('z01_nome',40,0,true,'text',3,"","z01_nomeinscr");
      ?>
       </td>
     </tr>
     <tr>   
       <td>
      <?
       db_ancora($Ldv09_procdiver,'js_proc(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('dv09_procdiver',5,$Idv09_procdiver,true,'text',1,"onchange='js_proc(false)'");
       db_input('z01_nome',40,0,true,'text',3,"","z01_nomeproc");
      ?>
       </td>
      </tr>          
     </table>
     </fieldset>
	   <input type="submit" name="pesquisar" value="Pesquisar" onclick="return js_pesquisa();" >
     <?}?> 

  </form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisa(){
	if (document.form1.dv05_coddiver.value!=""||document.form1.dv05_numcgm.value!=""||document.form1.dv09_procdiver.value!=""||document.form1.q02_inscr.value!=""||document.form1.j01_matric.value!=""){
		return true;
	}else{
		alert(_M("tributario.diversos.dvr4_altrecdiver001.preencha_algum_filtro"));
		return false;
	}
}
function js_processa(){
	if (document.form1.k02_codigo.value!=""){
		return true;
	}else{
		alert(_M("tributario.diversos.dvr4_altrecdiver001.preencha_receita"));
		document.form1.k02_codigo.focus();
		return false;
	}
}
function js_proc(mostra){
  var proc=document.form1.dv09_procdiver.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_procdiver.php?funcao_js=parent.js_mostraproc|dv09_procdiver|dv09_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_procdiver.php?pesquisa_chave='+proc+'&funcao_js=parent.js_mostraproc1','Pesquisa',false);
  }
}
function js_mostraproc(chave1,chave2){
  document.form1.dv09_procdiver.value = chave1;
  document.form1.z01_nomeproc.value = chave2;
  db_iframe.hide();
}
function js_mostraproc1(chave,erro){
  document.form1.z01_nomeproc.value = chave; 
  if(erro==true){ 
    document.form1.dv09_procdiver.focus(); 
    document.form1.dv09_procdiver.value = ''; 
  }
}
function js_diver(mostra){
  var diver=document.form1.dv05_coddiver.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_diversos.php?funcao_js=parent.js_mostradiver|dv05_coddiver|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_diversos.php?pesquisa_chave='+diver+'&funcao_js=parent.js_mostradiver1','Pesquisa',false);
  }
}
function js_mostradiver(chave1,chave2){
  document.form1.dv05_coddiver.value = chave1;
  document.form1.z01_nomediver.value = chave2;
  db_iframe.hide();
}
function js_mostradiver1(chave,erro){
  document.form1.z01_nomediver.value = chave; 
  if(erro==true){ 
    document.form1.dv05_coddiver.focus(); 
    document.form1.dv05_coddiver.value = ''; 
  }
}
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_mostramatri|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe_iptubase.hide();
}
function js_mostramatri1(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
  }
}
function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe_issbase.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.q02_inscr.focus(); 
    document.form1.q02_inscr.value = ''; 
  }
}
function js_cgm(mostra){
  var cgm=document.form1.dv05_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.dv05_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe_cgm.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.dv05_numcgm.focus(); 
    document.form1.dv05_numcgm.value = ''; 
  }
}
function js_pesquisatabrec(mostra){
     if(mostra==true){
     	js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_descr','Pesquisa',true);       
     }else{
     	js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);      
     }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_drecei.value = chave;
  if(erro==true){
     document.form1.k02_codigo.focus();
     document.form1.k02_codigo.value = '';
  }
}
function js_mostratabrec1(chave1,chave2){
     document.form1.k02_codigo.value = chave1;
     document.form1.k02_drecei.value = chave2;
     db_iframe_tabrec.hide();
}
</script>
<?
if (isset($processar)){
	 db_inicio_transacao();
	 $tab = "";
	 $where = "";
	 $inner = "";
	 $sqlerro = false;
	 $erro_msg = "";
	 if (isset($j01_matric)&&$j01_matric != ""){
	 	  $tab = " arrematric ";
	 		$where = " arrematric.k00_matric = $j01_matric ";	 	
	 }	
	 if (isset($q02_inscr)&&$q02_inscr != ""){
	 	  $tab = " arreinscr ";
	 		$where = " arreinscr.k00_inscr = $q02_inscr ";	 	
	 }	
	 if (isset($dv05_numcgm)&&$dv05_numcgm!=""){
	 		$tab = " arrenumcgm ";
	 		$where = " arrenumcgm.k00_numcgm = $dv05_numcgm ";
	 }
	 if (isset($dv09_procdiver)&&$dv09_procdiver!=""){
	 		$where = " diversos.dv05_procdiver = $dv09_procdiver ";
	 }
	 if (isset($dv05_coddiver)&&$dv05_coddiver!=""){
	 		$where = " diversos.dv05_coddiver = $dv05_coddiver ";
	 }	 
	 if ($tab!=""){	 	
	 		$inner = " inner join $tab on $tab.k00_numpre = diversos.dv05_numpre ";
	 }
	 $dt_hoje = date('Y-m-d',db_getsession('DB_datausu'));
	 if (isset($tipo)&&$tipo=="v"){	 	
	 		$where .= " and arrecad.k00_dtvenc<'$dt_hoje'"; 
	 }else if (isset($tipo)&&$tipo=="n"){
	 		$where .= " and arrecad.k00_dtvenc>='$dt_hoje'";	 	
	 }
	 $sql = "select distinct arrecad.k00_numpre as numpre,arrecad.k00_numpar as numpar 
           from diversos 
                inner join arrecad on arrecad.k00_numpre = dv05_numpre
								$inner
           where $where and dv05_instit = ".db_getsession('DB_instit');   
           
   $result = db_query($sql);
   $numrows = pg_numrows($result);   
   if ($numrows==0){
   		db_msgbox(_M("tributario.diversos.dvr4_altrecdiver001.nao_exitem_registro"));
   		echo "<script>location.href='dvr4_altrecdiver001.php';</script>";
   		exit;
   }
   $perc = 0;   
   for($w=0;$w<$numrows;$w++){
   		db_fieldsmemory($result,$w);
   		db_atutermometro($w, $numrows, 'termometro');
   		if ($sqlerro==false){		
   			$clarrecad->k00_receit = $k02_codigo;
   			$clarrecad->k00_numpre = $numpre;
   			$clarrecad->alterar_arrecad("k00_numpre = $numpre and k00_numpar = $numpar");
   			if ($clarrecad->erro_status==0){
   				$sqlerro=true;
   				$erro_msg = $clarrecad->erro_msg;
   				break;   			
   			}
   		}
   }
   db_fim_transacao($sqlerro);
   if ($sqlerro==false){
   		db_msgbox(_M("tributario.diversos.dvr4_altrecdiver001.sucesso_alteracao"));
   		echo "<script>location.href='dvr4_altrecdiver001.php';</script>";
   		exit;
   }else{
   		db_msgbox($erro_msg);
   }
}

?>
<script>

<?php if (!isset($pesquisar)){ ?>
  $("dv05_coddiver").addClassName("field-size2");
  $("z01_nomediver").addClassName("field-size7");
  $("dv05_numcgm").addClassName("field-size2");
  $("z01_nomecgm").addClassName("field-size7");
  $("j01_matric").addClassName("field-size2");
  $("z01_nomematri").addClassName("field-size7");
  $("q02_inscr").addClassName("field-size2");
  $("z01_nomeinscr").addClassName("field-size7");
  $("dv09_procdiver").addClassName("field-size2");
  $("z01_nomeproc").addClassName("field-size7");
<?php }else{ ?>
  $("k02_codigo").addClassName("field-size2");
  $("k02_drecei").addClassName("field-size7");
<?php }?>
  
</script>