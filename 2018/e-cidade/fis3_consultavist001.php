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
include("classes/db_sanitario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clsanitario = new cl_sanitario;
$clsanitario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clrotulo->label("y76_codvist");
$clrotulo->label("y70_id_usuario");
$clrotulo->label("y70_data");
$clrotulo->label("y70_tipovist");
$clrotulo->label("y77_descricao");
$clrotulo->label("y70_numbloco");
$clrotulo->label("y10_codigo");
$clrotulo->label("y10_codi");
$clrotulo->label("y11_codigo");
$clrotulo->label("y11_codi");
$clrotulo->label("z01_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("q02_inscr");
$db_opcao=1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="form1" method="post" action="">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty76_codvist?>">
       <?
       db_ancora(@$Ly76_codvist,"js_pesquisay76_codvist(true);",1);
       ?>
    </td>
    <td> 
<?
db_input('y76_codvist',10,$Iy76_codvist,true,'text',1," onchange='js_pesquisay76_codvist(false);'")
?>
       <?
db_input('y70_id_usuario',40,$Iy70_id_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
     <tr>   
      <td>
      <?
       db_ancora($Lz01_numcgm,' js_cgm(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
       db_input('z01_nome',30,0,true,'text',3,"","z01_nomecgm");
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
      db_input('z01_nome',30,0,true,'text',3,"","z01_nomematri");
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
       db_input('z01_nome',30,0,true,'text',3,"","z01_nomeinscr");
      ?>
       </td>
     </tr>
    <tr>
      <td nowrap title="<?=@$Ty80_codsani?>">
         <?
         db_ancora(@$Ly80_codsani,"js_sanitario(true);",1);
         ?>
      </td>
      <td> 
        <?
        db_input('y80_codsani',5,$Iy80_codsani,true,'text',1,"onchange='js_sanitario(false)'");
        db_input('z01_nome',30,0,true,'text',3,"","z01_nomesani");
        ?>
      </td>
    </tr>
  <tr>
    <td nowrap title="<?=@$Ty70_data?>">
       <?=@$Ly70_data?>
    </td>
    <td> 
<?
db_inputdata('',@$dia,@$mes,@$ano,true,'text',$db_opcao,"")
?>
&nbsp;&nbsp;&nbsp;À&nbsp;&nbsp;&nbsp;
<?
db_inputdata('a',@$diaa,@$mesa,@$anoa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty70_tipovist?>">
       <?
       db_ancora(@$Ly70_tipovist,"js_pesquisay70_tipovist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y70_tipovist',10,$Iy70_tipovist,true,'text',$db_opcao," onchange='js_pesquisay70_tipovist(false);'");
db_input('y77_descricao',40,$Iy77_descricao,true,'text',3,'');
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty70_numbloco?>">
       <?=$Ly70_numbloco?>
    </td>
    <td> 
<?
db_input('y70_numbloco',10,$Iy70_numbloco,true,'text',$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <fieldset>
      <legend align="center"><strong>Endereço registrado</strong></legend>
      <table>
	<tr>
	  <td nowrap title="<?=@$Ty10_codigo?>" width="100">
	     <?
	     db_ancora(@$Ly10_codigo,"js_ruas1(true);",$db_opcao);
	     ?>
	  </td>
	  <td> 
      <?
      db_input('y10_codigo',10,$Iy10_codigo,true,'text',$db_opcao," onChange='js_ruas1(false)'");
      db_input('j14_nome',50,$Ij14_nome,true,'text',3,"");
      ?>
	  </td>
	</tr>
	<tr> 
	  <td nowrap title="<?=@$Ty10_codi?>"> 
	    <?
	    db_ancora(@$Ly10_codi,"js_bairro1(true);",$db_opcao);
	    ?>
	  </td>
	  <td nowrap> 
	    <?
	      db_input('y10_codi',10,$Iy10_codi,true,'text',$db_opcao," onChange='js_bairro1(false)'");
	      db_input('j13_descr',50,$Ij13_descr,true,'text',3);
	    ?>
	  </td>
	</tr>
      </table>
      </fieldset>
    </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
      <fieldset>
      <legend align="center"><strong>Endereço localizado</strong></legend>
      <table>
	<tr> 
	  <td nowrap title="<?=@$Ty11_codigo?>" width="100"> 
	     <?
	     db_ancora(@$Ly11_codigo,"js_ruas(true);",$db_opcao);
	     ?>
	  </td>
	  <td nowrap> 
	    <?
	      db_input('y11_codigo',10,$Iy11_codigo,true,'text',$db_opcao," onChange='js_ruas(false)'");
	      db_input('j14_nome',50,$Ij14_nome,true,'text',3,"","j14_nome_exec");
	    ?>
	  </td>
	</tr>
	<tr> 
	  <td nowrap title="<?=@$Ty11_codi?>"> 
	    <?
	    db_ancora(@$Ly11_codi,"js_bairro(true);",$db_opcao);
	    ?>
	  </td>
	  <td nowrap> 
	    <?
	      db_input('y11_codi',10,$Iy11_codi,true,'text',$db_opcao," onChange='js_bairro(false)'");
	      db_input('j13_descr',50,$Ij13_descr,true,'text',3,"","j13_descr_exec");
	    ?>
	  </td>
	</tr>
      </table>
    </fieldset>
    </td>
  </tr>
</table>
<input name="consultar" type="button" value="consultar" onClick="js_consultasani();js_limpacampos();" >
  </center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisay70_tipovist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_tipovistorias','func_tipovistoriasdepto.php?funcao_js=parent.js_mostratipovistorias1|y77_codtipo|y77_descricao','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_tipovistorias','func_tipovistoriasdepto.php?pesquisa_chave='+document.form1.y70_tipovist.value+'&funcao_js=parent.js_mostratipovistorias','Pesquisa',false);
  }
}
function js_mostratipovistorias(chave,erro){
  document.form1.y77_descricao.value = chave; 
  if(erro==true){ 
    document.form1.y70_tipovist.focus(); 
    document.form1.y70_tipovist.value = ''; 
  }
}
function js_mostratipovistorias1(chave1,chave2){
  document.form1.y70_tipovist.value = chave1;
  document.form1.y77_descricao.value = chave2;
  db_iframe_tipovistorias.hide();
}
function js_pesquisay76_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_vistorias','func_vistoriasalt.php?funcao_js=parent.js_abreconsulta|y70_codvist','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_vistorias','func_vistoriasalt.php?pesquisa_chave='+document.form1.y76_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
  }
}
function js_mostravistorias(chave,erro){
  document.form1.y70_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y76_codvist.focus(); 
    document.form1.y76_codvist.value = ''; 
  }
}
function js_mostravistorias1(chave1,chave2){
  document.form1.y76_codvist.value = chave1;
  document.form1.y70_id_usuario.value = chave2;
  db_iframe_vistorias.hide();
}
function js_limpacampos(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      document.form1.elements[i].value = '';
    }
  }
}
function js_consultasani(){
  var vazio = 0;
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      if(document.form1.elements[i].value == ""){
        vazio = 1;
      }else{
	vazio = 0;
	break;
      }
    }
  }
  if(vazio == 1){
    alert('Preencha um dos campos para o relatório!');
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_consultavist','fis3_consultavist002.php?y70_codvist='+document.form1.y76_codvist.value+'&cgm='+document.form1.z01_numcgm.value+'&matricula='+document.form1.j01_matric.value+'&inscricao='+document.form1.q02_inscr.value+'&sanitario='+document.form1.y80_codsani.value+'&dataini='+document.form1._ano.value+'-'+document.form1._mes.value+'-'+document.form1._dia.value+'&datafim='+document.form1.a_ano.value+'-'+document.form1.a_mes.value+'-'+document.form1.a_dia.value+'&rua='+document.form1.y10_codigo.value+'&bairro='+document.form1.y10_codi.value+'&tipovist='+document.form1.y70_tipovist.value+'&numbloco='+document.form1.y70_numbloco.value+'&ruae='+document.form1.y11_codigo.value+'&bairroe='+document.form1.y11_codi.value,'Consulta',true);
  }
}
function js_pesquisay80_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.y80_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = erro; 
  if(chave==true){ 
    document.form1.y80_numcgm.focus(); 
    document.form1.y80_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.y80_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_abreconsulta(chave){
  js_OpenJanelaIframe('','db_iframe_consulta','fis3_consultavist002.php?y70_codvist='+chave,'Pesquisa',true,15);
}
function js_bairro(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1&pesquisa_chave='+document.form1.y11_codi.value,'pesquisa',false);
  }
}
function js_preenchebairro(chave,chave1){
  document.form1.y11_codi.value = chave;
  document.form1.j13_descr_exec.value = chave1;
  db_iframe_bairros.hide();
}
function js_preenchebairro1(chave,erro){
  document.form1.j13_descr_exec.value = chave;
  if(erro == true){
    document.form1.y11_codi.focus();
    document.form1.y11_codi.value='';
  }
  db_iframe_bairros.hide();
}
function js_bairro1(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro2|j13_codi|j13_descr','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_bairros','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro22&pesquisa_chave='+document.form1.y10_codi.value,'pesquisa',false);
  }
}
function js_preenchebairro2(chave,chave1){
  document.form1.y10_codi.value = chave;
  document.form1.j13_descr.value = chave1;
  db_iframe_bairros.hide();
}
function js_preenchebairro22(chave,erro){
  document.form1.j13_descr.value = chave;
  if(erro == true){
    document.form1.y10_codi.focus();
    document.form1.y10_codi.value='';
  }
  db_iframe_bairros.hide();
}
function js_ruas(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheruas1&pesquisa_chave='+document.form1.y11_codigo.value+'','Pesquisa',false);
  }
}
function js_preencheruas(chave,chave1){
  document.form1.y11_codigo.value = chave;
  document.form1.j14_nome_exec.value = chave1;
  db_iframe_ruas.hide();
}
function js_preencheruas1(chave,erro){
  document.form1.j14_nome_exec.value = chave;
  if(erro == true){
    document.form1.y11_codigo.focus();
    document.form1.y11_codigo.value='';
  }
  db_iframe_ruas.hide();
}
function js_ruas1(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheender|j14_codigo|j14_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preencheender1&pesquisa_chave='+document.form1.y10_codigo.value+'','Pesquisa',false);
  }
}
function js_preencheender(chave,chave1){
  document.form1.y10_codigo.value = chave;
  document.form1.j14_nome.value = chave1;
  db_iframe_ruas.hide();
}
function js_preencheender1(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){ 
    document.form1.y10_codigo.focus(); 
    document.form1.y10_codigo.value = ''; 
  }
}
function js_sanitario(mostra){
  var sani=document.form1.y80_codsani.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?funcao_js=parent.js_preenchesanitario|y80_codsani|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+sani+'&funcao_js=parent.js_preenchesanitario1','Pesquisa',false);
  }
}
function js_preenchesanitario(chave,chave1){
  document.form1.y80_codsani.value = chave;
  document.form1.z01_nomesani.value = chave1;
  db_iframe_sanitario.hide();
}
function js_preenchesanitario1(chave,chave1,erro){
  document.form1.z01_nomesani.value = chave1;
  if(erro==true){ 
    document.form1.y80_codsani.focus(); 
    document.form1.y80_codsani.value = ''; 
  }
}
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatri|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
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
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.q02_inscr.focus(); 
    document.form1.q02_inscr.value = ''; 
  }
}


function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe2.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
</script>