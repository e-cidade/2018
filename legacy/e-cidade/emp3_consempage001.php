<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empage_classe.php");

$clempage = new cl_empage;

$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e60_numemp");
$clrotulo->label("e81_codmov");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_codemp");
$clrotulo->label("e50_codord");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_consultar(){
    query = '1=1';
    obj = document.form1;
    if(obj.e80_codage.value != "" ){
      query +=  "&e80_codage="+obj.e80_codage.value;
    }
    if(obj.e50_codord.value != ""){
      query += "&e50_codord="+obj.e50_codord.value;
    } 
    if(obj.e60_codemp.value != ''){
      query += "&e60_codemp="+obj.e60_codemp.value;
    }
    if(obj.z01_numcgm.value != ''){
      query += "&z01_numcgm="+obj.z01_numcgm.value;
    }
    if(obj.e81_codmov.value != ''){
      query += "&e81_codmov="+obj.e81_codmov.value;
    }
    if(obj.k17_codigo.value != ''){
      query += "&k17_codigo="+obj.k17_codigo.value;
    }
    if(obj.valor.value != ''){
      query += "&valor="+obj.valor.value;
    }
    if(obj.cheque.value != ''){
      query += "&cheque="+obj.cheque.value;
    }
    if(obj.e60_numemp.value != ''){
      query += "&e60_numemp="+obj.e60_numemp.value;
    }
    if(obj.e80_data_dia.value != "" && obj.e80_data_mes.value !="" && obj.e80_data_ano.value !=""){
      query += "&e80_data="+obj.e80_data_ano.value+"X"+obj.e80_data_mes.value+"X"+obj.e80_data_dia.value;
    }
     js_OpenJanelaIframe('top.corpo','db_iframe_consultar','emp3_consempage002.php?'+query,'Pesquisa',true);
}
</script>
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
      <table>
        <tr>
	  <td>
        <form name="form1" method="post" action="">
	      <br>
         <table>
	    <tr>  
	      <td align='right'>
	       <b> <? db_ancora("SLIP","js_slip(true);",$db_opcao);  ?></b>
	      </td>
	       <td><?=db_input('k17_codigo',8,'',true,'text',1,"onchange='js_slip(false);'")?></td>
	    </tr>
	    <tr>  
	      <td align='right'>
	       <b> Cheque:</b>
	      </td>
	       <td><?=db_input('cheque',8,'',true,'text',1)?></td>
	    </tr>
	    <tr>  
	      <td align='right'>
	       <b> Valor:</b>
	      </td>
	       <td><?=db_input('valor',15,'',true,'text',1)?></td>
	    </tr>
            <tr>
	       <td class='bordas' align='right'>
	              <b> <? db_ancora("Agendas","js_empage(true);",$db_opcao);  ?></b>
	       </td>
	       <td><?=db_input('e80_codage',8,@$e80_codage,true,'text',1,"onchange='js_empage(false);'")?></td>
	   </tr>
		<tr>
		  <td nowrap title="<?=@$Te50_codord?>" align='right'>
		     <? db_ancora(@$Le50_codord,"js_pesquisae50_codord(true);",$db_opcao);  ?>
		  </td>
		  <td> 
		     <? db_input('e50_codord',8,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord(false);'")  ?>
		  </td>
		</tr>
          <tr> 
            <td  align="right" nowrap title="<?=$Te60_codemp?>">
                 <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  ?>
            </td>
	    
            <td  nowrap> 
             
	      <input name="e60_codemp" title='<?=$Te60_codemp?>' size="8" type='text'  onKeyPress="return js_mascara(event);" >
            </td>
          </tr> 
		<tr>
		  <td nowrap title="<?=@$Te60_numemp?>" align='right'>
		     <? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",$db_opcao);  ?>
		  </td>
		  <td> 
		     <? db_input('e60_numemp',8,$Ie60_numemp,true,'text',$db_opcao," onchange='js_pesquisae60_numemp(false);'")  ?>
		  </td>
		</tr>
		<tr>
		  <td nowrap title="<?=@$Te81_codmov?>" align='right'>
		     <? db_ancora(@$Le81_codmov,"js_movs(true);",$db_opcao);  ?>
		  </td>
		  <td> 
		     <? db_input('e81_codmov',8,$Ie81_codmov,true,'text',$db_opcao," onchange='js_movs(false);'")  ?>
		  </td>
		</tr>
  <tr>
    <td nowrap title="<?=@$Tz01_numcgm?>">
    <?
       db_ancora(@$Lz01_nome,"js_pesquisaz01_numcgm(true);",$db_opcao);
     ?>        
    </td>
    <td> 
<?
db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
	   <tr>
	      <td nowrap title="<?=@$Te80_data?>" align='right'>
	      <?=$Le80_data?>
	      </td>	
	      <td>	
	       <?
		 db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',1);
	       ?>
	      </td>
            </tr>
	   <tr>
              <td colspan="2" align="center">
	      <br>
	 	<input name="consultar" type="button" value="Consultar" onclick="js_consultar();"> 
	 	<input name="limpar" type="reset" value="Limpar campos"> 
	      </td>	
            </tr>

	 </table>
       </form>	 
       </td>
     </tr>  
   </table>  
    </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
    function js_mascara(evt){
      var evt = (evt) ? evt : (window.event) ? window.event : "";
      
      if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:. 
	return true;
      }else{
	return false;
      }  
    }
//------------SLIP
function js_slip(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?funcao_js=parent.js_mostraslip|k17_codigo','Pesquisa',true);
  }else{
    codigo  =  document.form1.k17_codigo.value;  
    if(codigo != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?pesquisa_chave='+codigo+'&funcao_js=parent.js_mostraslip02','Pesquisa',false);
    }
  }    
}
function js_mostraslip(codage){
  db_iframe_slip.hide();
  document.form1.k17_codigo.value =  codage;  
}

function js_mostraslip02(chave,erro){
  if(erro==true){ 
    document.form1.k17_codigo.focus(); 
    document.form1.k17_codigo.value = ''; 
  }
}
function js_empage(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empage','func_empage.php?funcao_js=parent.js_mostra|e80_codage','Pesquisa',true);
  }else{
    codage =  document.form1.e80_codage.value;  
    if(codage != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_empage','func_empage.php?pesquisa_chave='+codage+'&funcao_js=parent.js_mostra02','Pesquisa',false);
    }
  }    
}
function js_mostra(codage){
  db_iframe_empage.hide();
  document.form1.e80_codage.value =  codage;  
}

function js_mostra02(chave,erro){
  if(erro==true){ 
    document.form1.e80_codage.focus(); 
    document.form1.e80_codage.value = ''; 
  }
}

function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho02.hide();
}

//movimentos-------------------------
function js_movs(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_movs','func_empagemov.php?funcao_js=parent.js_mostramov1|e81_codmov','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_movs','func_empagemov.php?pesquisa_chave='+document.form1.e81_codmov.value+'&funcao_js=parent.js_mostramov','Pesquisa',false);
  }
}
function js_mostramov(chave,erro){
  if(erro==true){ 
    document.form1.e81_codmov.focus(); 
    document.form1.e81_codmov.value = ''; 
  }
}
function js_mostramov1(chave1){
  document.form1.e81_codmov.value = chave1;
  db_iframe_movs.hide();
}
//-----------------------


function js_pesquisae60_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho(chave,erro){
  if(erro==true){ 
    document.form1.e60_numemp.focus(); 
    document.form1.e60_numemp.value = ''; 
  }
}
function js_mostraempempenho1(chave1){
  document.form1.e60_numemp.value = chave1;
  db_iframe_empempenho.hide();
}

function js_pesquisae50_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+document.form1.e50_codord.value+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
  }
}
function js_mostrapagordem(chave,erro){
  if(erro==true){ 
    document.form1.e50_codord.focus(); 
    document.form1.e50_codord.value = ''; 
  }
}
function js_mostrapagordem1(chave1,chave2){
  document.form1.e50_codord.value = chave1;
  db_iframe_pagordem.hide();
}
function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}

</script>