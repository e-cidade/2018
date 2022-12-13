<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_liborcamento.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_orcdotacao_classe.php");
require_once("classes/db_pcmater_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_empnota_classe.php");
require_once("classes/db_matordem_classe.php");
require_once("libs/db_app.utils.php");

$clempnota  = new cl_empnota();
$clempnota->rotulo->label();

$clmatordem = new cl_matordem();
$clmatordem->rotulo->label();

$clempempenho = new cl_empempenho;
$clorcdotacao = new cl_orcdotacao;
$clpcmater    = new cl_pcmater;
$clcgm        = new cl_cgm;

$clrotulo = new rotulocampo;
$clrotulo->label("o40_descr");
$clrotulo->label("e53_codord");
$clpcmater->rotulo->label();
$clcgm->rotulo->label();

$clempempenho->rotulo->label();
$clorcdotacao->rotulo->label();

db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/windowAux.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
<script>

function js_consulta(valor1){
  db_iframe_pesquisa.hide();
  var sQuery = '';
  sQuery += 'e69_codnota='+valor1;
  
  js_OpenJanelaIframe('top.corpo','db_iframe_pesquisa',
                      'emp1_empconsultanf003.php?'+sQuery,
                      'Pesquisa',true);  
  
}

function js_abre(){
  
  var sQuery = '';
  sQuery += 'e69_numero='+$F('e69_numero');
  sQuery += '&e60_codemp='+$F('e60_codemp');
  sQuery += '&z01_numcgm='+$F('z01_numcgm');
  sQuery += '&m51_codordem='+$F('m51_codordem');
  sQuery += '&e04_numeroprocesso='+   encodeURIComponent($F('e04_numeroprocesso'));
  
  
  var dtFim = '';
  var dtIni = '';
  
  if($F('dt_inicial') != ''){
    var aDtInicio = $F('dt_inicial').split('/');
    var dtIni = aDtInicio[2]+'-'+aDtInicio[1]+'-'+aDtInicio[0];
  }
  if($F('dt_final')){
    var aDtFim    = $F('dt_final').split('/');
    var dtFim = aDtFim[2]+'-'+aDtFim[1]+'-'+aDtFim[0];
  }
  
  sQuery += '&dtini='+dtIni;
  sQuery += '&dtfim='+dtFim;

  js_OpenJanelaIframe('top.corpo','db_iframe_pesquisa',
                      'emp1_empconsultanf002.php?'+sQuery+'&funcao_js=parent.js_consulta|e69_codnota',
                      'Pesquisa',true);
}
</script>  

<script>
function js_mascara(evt){
  var evt = (evt) ? evt : (window.event) ? window.event : "";
      
  if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:. 
    return true;
  }else{
	  return false;
  }  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post">
<table width="790" style="margin-top: 20px;" align="center">
  <tr>
  <td>
  <fieldset><legend><b>Consulta de Notas Fiscais</b></legend>
    <table>
      <tr>
        <td  align="left" nowrap title="<?=$Te69_numero?>">
        <? db_ancora(@$Le69_numero,"js_pesquisae69_numero(true);",1);  ?>
        </td>
        <td>
        <?db_input("e69_numero",10,$Ie69_numero,true,"text",4,"onchange=''");?> 
        </td>
      </tr>
      <tr> 
		    <td  align="left" nowrap title="<?=$Te60_codemp?>"> <? db_ancora(@$Le60_codemp,"js_pesquisa_empenho(true);",1);?>  </td>
		    <td align="left" nowrap>
	      <?
	       db_input("e60_codemp",10,$Ie60_codemp,true,"text",4,"onchange=''"); 
	       //  db_input("z01_nome1",40,"",true,"text",3);  
	      ?>
	      </td>
      </tr>
      <tr> 
        <td  align="left" nowrap title="<?=$Tz01_numcgm?>"><?db_ancora('<b>Credor:</b>',"js_pesquisa_cgm(true);",1);?></td>
		    <td align="left" nowrap>
		      <? 
		        db_input("z01_numcgm",10,$Iz01_numcgm,true,"text",4,"onchange='js_pesquisa_cgm(false);'");
		        db_input("z01_nome2",40,"",true,"text",3);  
		      ?>
		    </td>
      </tr>
      <tr> 
        <td  align="left" nowrap title="<?=$Tm51_codordem?>"><?db_ancora('<b>Ordem de Compra:</b>',"js_pesquisa_ordemcompra(true);",1);?></td>
        <td align="left" nowrap>
          <? 
            db_input("m51_codordem",10,$Im51_codordem,true,"text",4,"onchange=''");
            //db_input("z01_nome2",40,"",true,"text",3);  
          ?>
        </td>
      </tr>

      <tr>
        <td align='left'><b>Data Inicial:</b>
        
        </td>
        <td align='left'>
        <?
          db_inputdata('dt_inicial',"","","",false,'text',1,"","",""); 
        ?>
        <b>Data Final:</b>
        <?
         db_inputdata('dt_final',"","","",false,'text',1,"","",""); 
        ?>
        </td>
      </tr>
      
      <tr> 
        <td  align="left" nowrap title="Processo administrativo"><strong>Processo Administrativo:</strong></td>
        <td align="left" nowrap>
          <? 
            db_input("e04_numeroprocesso", 10, null,true,"text",4, null, null, null, null,15);
          ?>
        </td>
      </tr>
      
    </table>
  </fieldset>    
  </td>
  </tr>
  <tr>
    <td align="center">
	    <input name="pesquisa" type="button" onclick='js_abre();'  value="Pesquisa">
	    <input name="limpa"    type="button" onclick='js_limpaCampos();' value="Limpar campos">
    </td>
  </tr>
</table>
</form>
</center>

<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
//-------------------------------------------------
function js_pesquisa_ordemcompra(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matordem',
                        'func_matordement.php?funcao_js=parent.js_mostram51_codord1|m51_codordem',
                        'Pesquisa',true);
  }else{
     if($F('m51_codordem') != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matordem',
                            'func_matordement.php?pesquisa_chave='+$F('m51_codordem')+'&funcao_js=parent.js_mostram51_codord',
                            'Pesquisa',false);
     }else{
       $('m51_codordem').value = ''; 
     }
  }
}

function js_mostram51_codord(chave,erro){
  if(erro==true){ 
    alert ("usuário:\n\n Código informado não é válido !!!\n\nAdministrador:\n\n");
    $('m51_codordem').value = ''; 
    $('m51_codordem').focus(); 
  }
}

function js_mostram51_codord1(chave1){
   $('m51_codordem').value = chave1;  
   //document.form1.z01_nome2.value = chave2;
   db_iframe_matordem.hide();
}
//------------------------------------------------
function js_pesquisa_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm',
                        'func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                        'Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm',
                            'func_cgm.php?pesquisa_chave='+$F('z01_numcgm')+'&funcao_js=parent.js_mostracgm',
                            'Pesquisa',false);
     }else{
       $('z01_nome2').value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  
  document.form1.z01_nome2.value = chave; 
  if(erro==true){ 
    $('z01_numcgm').value = ''; 
    $('z01_numcgm').focus(); 
  }
}
function js_mostracgm1(chave1,chave2){
  $('z01_numcgm').value = chave1;  
  $('z01_nome2').value  = chave2;
  db_iframe_cgm.hide();
}
//---------------------------------------------------
function js_pesquisa_empenho(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho',
                        'func_empempenho.php?funcao_js=parent.js_mostraempenho1|e60_codemp',
                        'Pesquisa',true);
  }else{
     if($F('e60_codemp') != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho',
                            'func_empempenho.php?pesquisa_chave='+$F('e60_codemp')+'&funcao_js=parent.js_mostraempenho',
                            'Pesquisa',false);
     }else{
        
     }
  }
}
function js_mostraempenho(chave,erro){
  //alert(erro+'-----'+chave) 
  if(erro==true){ 
    alert("usuário:\n\n Código informado não é válido !!!\n\nAdministrador:\n\n");
    $('e60_codemp').value = '';  
    $('e60_codemp').focus(); 
  }
}
function js_mostraempenho1(chave1){
  //alert(chave1)
  $('e60_codemp').value = chave1;
  // document.form1.z01_nome1.value = chave2;
  db_iframe_empempenho.hide();
}
//---------------------------------------------------
function js_pesquisae69_numero(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empnota',
                        'func_empnota.php?funcao_js=parent.js_mostrae69_numero1|e69_numero',
                        'Pesquisa',true);
  }else{
     if($F('e69_numero') != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm',
                            'func_cgm_empenho.php?pesquisa_chave='+$F('e69_numero')+'&funcao_js=parent.js_mostrae69_numero',
                            'Pesquisa',true);
     }else{
       $('e69_numero').value = ''; 
     }
  }
}
function js_mostrae69_numero(chave,erro){
  //$('e69_numero').value = chave; 
  if(erro==true){
    alert("\n\nusuário:\n\n Código informado não é válido !!!\n\nAdministrador:\n\n");
    $('e69_numero').value = '';  
    $('e69_numero').focus(); 
  }
}
function js_mostrae69_numero1(chave1,chave2){
   $('e69_numero').value = chave1;  
   db_iframe_empnota.hide();
}
//---------------------------------------------------
function js_pesquisa_pcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater',
                        'func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater',
                        'Pesquisa',true);
  }else{
     if(document.form1.pc01_codmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcmater',
                            'func_pcmater.php?pesquisa_chave='+$F('pc01_codmater')+'&funcao_js=parent.js_mostrapcmater',
                            'Pesquisa',false);
     }else{
       $('pc01_descrmater').value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  $('pc01_descrmater').value = chave; 
  if(erro==true){ 
    $('pc01_codmater').focus(); 
    $('pc01_codmater').value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
   $('pc01_codmater').value   = chave1;  
   $('pc01_descrmater').value = chave2;
   db_iframe_pcmater.hide();
}

//--------------------------------

function js_buscae53_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordemele',
                        'func_pagordemele.php?funcao_js=parent.js_mostracodord1|e53_codord',
                        'Pesquisa',true);
  }else{
     if($('e53_codord').value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pagordemele',
                            'func_pagordemele.php?pesquisa_chave='+$F('e53_codord')+'&funcao_js=parent.js_mostracodord',
                            'Pesquisa',false);
     }else{
       $('e53_codord').value = ''; 
     }
  }
}

function js_mostracodord(chave,erro){
  if(erro==true){ 
    $('e53_codord').e53_codord.value = ''; 
    $('e53_codord').e53_codord.focus(); 
  }
}

function js_mostracodord1(chave1){
 $('e53_codord').value = chave1;  
   //document.form1.z01_nome2.value = chave2;
   db_iframe_pagordemele.hide();
}

function js_limpaCampos(){
  
  $('e69_numero').value = '';
  $('e60_codemp').value = '';
  $('z01_numcgm').value = '';
  $('z01_nome2').value = '';
  $('m51_codordem').value = '';
  
  $('dt_inicial').value = '';
  $('dt_final').value = '';

}
</script>
</body>
</html>