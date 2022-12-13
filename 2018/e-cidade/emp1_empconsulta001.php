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
require_once("libs/db_app.utils.php");
$clempempenho = new cl_empempenho;
$clorcdotacao = new cl_orcdotacao;
$clpcmater  = new cl_pcmater;
$clcgm    = new cl_cgm;

$clrotulo = new rotulocampo;
$clrotulo->label("o40_descr");
$clrotulo->label("e53_codord");
$clpcmater->rotulo->label();
$clcgm->rotulo->label();

$clempempenho->rotulo->label();
$clorcdotacao->rotulo->label();

db_postmemory($HTTP_POST_VARS);
db_app::load("prototype.js");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_abre(){
   // js_OpenJanelaIframe('','db_iframe_orgao','func_saldoorcdotacao.php?coddot='+coddot,'pesquisa',true);
   obj = document.form1;
   if (    (obj.e60_emiss1_dia.value !='') 
        && (obj.e60_emiss2_dia.value !='')
        && (obj.e60_emiss1_mes.value !='')
        && (obj.e60_emiss2_mes.value !='')
        && (obj.e60_emiss1_ano.value !='')
        && (obj.e60_emiss1_ano.value !='')) {
    dt1 = obj.e60_emiss1_ano.value+'-'+obj.e60_emiss1_mes.value+'-'+obj.e60_emiss1_dia.value ;
    dt2 = obj.e60_emiss2_ano.value+'-'+obj.e60_emiss2_mes.value+'-'+obj.e60_emiss2_dia.value ;
   } else {
      dt1='';
      dt2='';
   }

        codemp = document.form1.e60_codemp.value ;  
    var sProcesso = encodeURIComponent($F('e150_numeroprocesso'));  
    js_OpenJanelaIframe('top.corpo','db_iframe_empconsulta002','emp1_empconsulta002.php?e150_numeroprocesso='+sProcesso+'&e60_codemp='+codemp+'&e60_numemp='+document.form1.e60_numemp.value+'&o58_coddot='+document.form1.o58_coddot.value+'&pc01_codmater='+document.form1.pc01_codmater.value+'&z01_numcgm='+document.form1.z01_numcgm.value+'&dt1='+dt1+'&dt2='+dt2+'&e53_codord='+document.form1.e53_codord.value+'&funcao_js=parent.js_consulta002|e60_numemp','Pesquisa',true);
  }
  
function js_consulta002(chave1){
   js_OpenJanelaIframe('top.corpo','db_iframe_empempenho001','func_empempenho001.php?e60_numemp='+chave1,'Pesquisa',true);
//   db_iframe_empconsulta002.hide(); 
   
}

function js_limpa(){
   location.href='emp1_empconsulta001.php'; 
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
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
<div style="margin-top: 20px; width: 450px;">
<form name="form1" method="post">
  <fieldset>
    <legend><strong>Consulta Empenho</strong></legend>
    <table border='0'>
      <tr> 
        <td  align="left" nowrap title="<?=$Te60_codemp?>">
        	<? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",1);  ?>
        </td>
  	    <td  nowrap="nowrap" title='<?=$Te60_codemp?>' > 
          <input name="e60_codemp" size="10" type='text' onKeyPress="return js_mascara(event);" >
        </td>
      </tr> 
      <tr> 
        <td align="left" nowrap title="<?=$Te60_numemp?>"> 
          <? db_ancora(@$Le60_numemp,"js_pesquisa_empenho(true);",1); ?>  
        </td>
        <td align="left" nowrap>
          <? db_input("e60_numemp",10,$Ie60_numemp,true,"text",4); ?>
        </td>
      </tr>
      <tr> 
        <td  align="left" nowrap title="<?=$To58_coddot?>">
          <?db_ancora(@$Lo58_coddot,"js_pesquisa_dotacao(true);",1);?> 
        </td>
        <td align="left" nowrap>
          <? 
            db_input("o58_coddot",10,$Io58_coddot,true,"text",4,"onchange='js_pesquisa_dotacao(false);'"); 
            db_input("o40_descr",30,"",true,"text",3);  
          ?>
        </td>
      </tr>
      <tr> 
        <td  align="left" nowrap title="<?=$Tpc01_codmater ?>">
          <?db_ancora(@$Lpc01_codmater,"js_pesquisa_pcmater(true);",1);?>
        </td>
        <td align="left" nowrap>
          <? 
            db_input("pc01_codmater",10,$Ipc01_codmater,true,"text",4,"onchange='js_pesquisa_pcmater(false);'"); 
            db_input("pc01_descrmater",30,"",true,"text",3);  
          ?>
        </td>
      </tr>
      <tr> 
        <td  align="left" nowrap title="<?=$Tz01_numcgm?>">
          <?db_ancora(@$Lz01_numcgm,"js_pesquisa_cgm(true);",1);?>
        </td>
        <td align="left" nowrap>
          <? 
  	        db_input("z01_numcgm",10,$Iz01_numcgm,true,"text",4,"onchange='js_pesquisa_cgm(false);'");
  	        db_input("z01_nome2",30,"",true,"text",3);  
          ?>
        </td>
      </tr>
      <tr> 
        <td align="left" nowrap title="<?=$Te60_emiss?>">
          <?db_ancora(@$Le60_emiss,"",3);?>
        </td>
        <td align="left" nowrap>
          <? 
             db_inputdata('e60_emiss1',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',1,"");   		          
             echo " a ";
             db_inputdata('e60_emiss2',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',1,"");
          ?>
        </td>
      </tr>
      <tr> 
        <td  align="left" nowrap title="<?=$Te53_codord?>">
          <?db_ancora(@$Le53_codord,"js_buscae53_codord(true)",1);?>
        </td>
        <td align="left" nowrap>
          <? db_input("e53_codord",10,$Ie53_codord,true,"text",4,"onchange='js_buscae53_codord(false);'"); ?>
        </td>
      </tr>
      
      <tr> 
        <td  align="left" nowrap title="Processo Administrativo">
          <strong>Processo Administrativo:</strong>
        </td>
        <td align="left" nowrap>
          <? db_input("e150_numeroprocesso",10,"",true,"text",4,null,null,null,null,15); ?>
        </td>
      </tr>      
      
      
    </table>
  </fieldset>
  <input name="pesquisa" type="button" onclick='js_abre();'  value="Pesquisa">
  <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar campos">
</form>
</div>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
//--------------------------------
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp|e60_anousu','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1, chave2){
  document.form1.e60_codemp.value = chave1 + '/' + chave2;
  db_iframe_empempenho.hide();
}

function js_pesquisa_cgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm_empenho.php?funcao_js=parent.js_mostracgm1|e60_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm_empenho.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome2.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome2.value = chave; 
  if(erro==true){ 
    document.form1.z01_nome2.value = ''; 
    document.form1.z01_numcgm.focus(); 
  }
}
function js_mostracgm1(chave1,chave2){
   document.form1.z01_numcgm.value = chave1;  
   document.form1.z01_nome2.value = chave2;
   db_iframe_cgm.hide();
}
//--------------------------------
function js_pesquisa_pcmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater','Pesquisa',true);
  }else{
     if(document.form1.pc01_codmater.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pcmater','func_pcmater.php?pesquisa_chave='+document.form1.pc01_codmater.value+'&funcao_js=parent.js_mostrapcmater','Pesquisa',false);
     }else{
       document.form1.pc01_descrmater.value = ''; 
     }
  }
}
function js_mostrapcmater(chave,erro){
  document.form1.pc01_descrmater.value = chave; 
  if(erro==true){ 
    document.form1.pc01_codmater.focus(); 
    document.form1.pc01_descrmater.value = ''; 
  }
}
function js_mostrapcmater1(chave1,chave2){
   document.form1.pc01_codmater.value = chave1;  
   document.form1.pc01_descrmater.value = chave2;
   db_iframe_pcmater.hide();
}
//--------------------------------
function js_pesquisa_dotacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?funcao_js=parent.js_mostradotacao1|o58_coddot|o56_descr','Pesquisa',true);
  }else{
     if(document.form1.o58_coddot.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcdotacao','func_orcdotacao.php?pesquisa_chave='+document.form1.o58_coddot.value+'&funcao_js=parent.js_mostradotacao','Pesquisa',false);
     }else{
       document.form1.o40_descr.value = ''; 
     }
  }
}
function js_mostradotacao(chave,erro){
  document.form1.o40_descr.value = chave; 
  if(erro==true){ 
    document.form1.o58_coddot.focus(); 
    document.form1.o58_coddot.value = ''; 
  }
}
function js_mostradotacao1(chave1,chave2){
  document.form1.o58_coddot.value = chave1;  
  document.form1.o40_descr.value = chave2;
  db_iframe_orcdotacao.hide();
}
//--------------------------------
function js_pesquisa_empenho(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempenho1|e60_numemp','Pesquisa',true);
  }else{
     if(document.form1.e60_numemp.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempenho','Pesquisa',false);
     }else{
       document.form1.z01_nome1.value = ''; 
     }
  }
}
function js_mostraempenho(erro,chave){
  document.form1.z01_nome1.value = chave; 
  if(erro==true){ 
    document.form1.e60_numemp.focus(); 
    document.form1.z01_nome1.value = ''; 
  }
}
function js_mostraempenho1(chave1){
  document.form1.e60_numemp.value = chave1;
  // document.form1.z01_nome1.value = chave2;
  db_iframe_empempenho.hide();
}


//--------------------------------


function js_buscae53_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordemele','func_pagordemele.php?funcao_js=parent.js_mostracodord1|e53_codord','Pesquisa',true);
  }else{
     if(document.form1.e53_codord.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pagordemele','func_pagordemele.php?pesquisa_chave='+document.form1.e53_codord.value+'&funcao_js=parent.js_mostracodord','Pesquisa',false);
     }else{
       document.form1.e53_codord.value = ''; 
     }
  }
}

function js_mostracodord(chave,erro){
  if(erro==true){ 
    document.form1.e53_codord.value = ''; 
    document.form1.e53_codord.focus(); 
  }
}

function js_mostracodord1(chave1){
   document.form1.e53_codord.value = chave1;  
   //document.form1.z01_nome2.value = chave2;
   db_iframe_pagordemele.hide();
}

</script>
</body>
</html>