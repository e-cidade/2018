<?php
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
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");

$data_inicial_dia = "01";
$data_inicial_mes = "01";
$data_inicial_ano = db_getsession("DB_anousu");

$data_final_dia = "31";
$data_final_mes = "12";
$data_final_ano = db_getsession("DB_anousu");

$clrotulo = new rotulocampo;
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_verifica(){
  var codcgm = new Number(document.form1.z01_numcgm.value);	
  var anoi   = new Number(document.form1.data_inicial_ano.value);
  var anof   = new Number(document.form1.data_final_ano.value);

//  if(codcgm.valueOf() == 0) {
//  	alert("Informe o numero de cgm!");
//  	return false;
//  }
  	
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Verifique !');
    return false;
  }
  if(anoi.valueOf() == 0 && anof.valueOf() == 0){
    alert('Intervalo de data invalido. Verifique!');
    return false;
  }
  
  js_emite();
}
function js_emite(){
  var k02_codigo = "";	
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].name == "lista[]"){
      for(x=0;x< document.form1.elements[i].length;x++){
        document.form1.elements[i].options[x].selected = true;
        k02_codigo += document.form1.elements[i].options[x].value+"|";
      }
    }
  }

  k02_codigo = k02_codigo.substr(0,k02_codigo.length-1); 		
  jan = window.open('cai2_relretcgm002.php?z01_numcgm='+document.form1.z01_numcgm.value+'&k02_codigo='+k02_codigo+'&data_inicial='+document.form1.data_inicial_ano.value+'-'+document.form1.data_inicial_mes.value+'-'+document.form1.data_inicial_dia.value+'&data_final='+document.form1.data_final_ano.value+'-'+document.form1.data_final_mes.value+'-'+document.form1.data_final_dia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<table  align="center">
	<form name="form1" method="post" action="" onsubmit="return js_verifica();">
	<tr>
		<td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="left" nowrap title="<?=@$Tz01_numcgm?>" >
          <?
             db_ancora(@$Lz01_numcgm,"js_pesquisa_cgm(true);",4)
          ?>
        </td>
        <td>
          <?
            db_input('z01_numcgm',8,$Iz01_numcgm,true,'text',4,"OnChange='js_pesquisa_cgm(false);'")
          ?>
          <?
            db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
          ?>
        </td>
	</tr>
       <tr>
          <td nowrap>
               <?
                  $receitas = new cl_arquivo_auxiliar;
                  $receitas->cabecalho = "<strong>Receitas</strong>";
                  $receitas->codigo = "k02_codigo"; //chave de retorno da func
                  $receitas->descr  = "k02_descr";   //chave de retorno
                  $receitas->nomeobjeto = 'lista';
                  $receitas->funcao_js = 'js_mostra';
                  $receitas->funcao_js_hide = 'js_mostra1';
                  $receitas->sql_exec  = "";
                  $receitas->func_arquivo = "func_tabrec_todas.php";  //func a executar
                  $receitas->nomeiframe = "db_iframe_receitas";
                  $receitas->localjan = "";
                  $receitas->onclick = "";
                  $receitas->db_opcao = 2;
                  $receitas->tipo = 2;
                  $receitas->top = 0;
                  $receitas->linhas = 5;
                  $receitas->vwhidth = 400;
                  $receitas->funcao_gera_formulario();
              ?>    
          </td>
       </tr>
    <tr>
        <td align="left" nowrap title="Periodo"><b>Periodo:</b></td>
        <td><?=db_inputdata('data_inicial',@$data_inicial_dia,@$data_inicial_mes,@$data_inicial_ano,true,'text',4)?>
            &nbsp;&nbsp;a&nbsp;&nbsp;<?=db_inputdata('data_final',@$data_final_dia,@$data_final_mes,@$data_final_ano,true,'text',4)?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td align="center"><input type="submit" value="Imprime">&nbsp;&nbsp;<input type="reset" value="Limpar"></td>
	</tr>
	</form>
</table>	
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
function js_pesquisa_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostra_cgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostra_cgm','Pesquisa',false);
     }else{
       document.form1.z01_numcgm.value = '';
       document.form1.z01_nome.value   = ''; 
     }
  }
}
function js_mostra_cgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_nome.value = ''; 
    document.form1.z01_nome.focus(); 
  }
}
function js_mostra_cgm1(chave1,chave2){
   document.form1.z01_numcgm.value = chave1;  
   document.form1.z01_nome.value   = chave2;
   db_iframe_cgm.hide();
}
</script>
</body>
</html>