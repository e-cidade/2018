<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require("libs/db_liborcamento.php");
include("classes/db_empempenho_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_pcmater_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_proctransfer_classe.php");


$clempempenho     = new cl_empempenho;
$clorcdotacao     = new cl_orcdotacao;
$clpcmater        = new cl_pcmater;
$clmatordem       = new cl_matordem;
$clcgm            = new cl_cgm;
$clproctransfer   = new cl_proctransfer; 


$clrotulo = new rotulocampo;

$clrotulo->label("o40_descr");
$clrotulo->label("e53_codord");
$clpcmater->rotulo->label();
$clcgm->rotulo->label();

$clmatordem->rotulo->label();
$clproctransfer->rotulo->label();
$clempempenho->rotulo->label();
$clorcdotacao->rotulo->label();


$clrotulo->label("pc60_numcgm");
$clrotulo->label("z01_nome");


$clrotulo->label("nome");
$clrotulo->label("descrdepto");
$clrotulo->label("proctransfer");
$clrotulo->label("p62_coddeptorecDestino");
$clrotulo->label("m51_codordem");

db_postmemory($HTTP_POST_VARS);


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<center>
<div style="margin-top: 50px; width: 600px;">
<form name="form1" method="post">

  <fieldset style="margin-top: 50px;">
  
    <legend><strong>Posição Ordem de Compra</strong></legend>
    
    <table border='0'>
      
      
      <tr> 
        <td  align="left" nowrap title="<?=$Tm51_codordem?>">
          <strong>
            <?db_ancora("Ordem de Compra:","js_pesquisa_matordem(true);",1);?>
          </strong>
        </td>
        <td align="left" nowrap>
           <?db_input("m51_codordem",10,$Im51_codordem,true,"text",4,"onchange='js_pesquisa_matordem(false);'");?>
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
        <td align="left" nowrap title="<?=$Te60_emiss?>">
          <?db_ancora(@$Le60_emiss,"",3);?>
        </td>
        <td align="left" nowrap>
          <? 
             db_inputdata('e60_emiss1',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',1,"");   		          
             echo " <strong>à</strong> ";
             db_inputdata('e60_emiss2',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',1,"");
          ?>
        </td>
      </tr>      
     
   
     <tr>
       <td nowrap title="<?=@$Tpc60_numcgm?>" align="left">
         <?
           db_ancora(@$Lpc60_numcgm,"js_pesquisa(true);",1);
         ?>
       </td>
       <td>
         <?
           db_input('pc60_numcgm',10,$Ipc60_numcgm,true,'text',1," onchange='js_pesquisa(false);'");
          // echo "&nbsp;";
           db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'');
         ?>        
       </td>
     </tr>     
     
    <tr>
      <td nowrap title="Departamento de Origem" >
         <?
         db_ancora("<strong>Departamento de Origem:</strong>","js_pesquisap62_coddeptorec(true);",1);
         ?>
      </td>
      <td nowrap> 
        <?
          db_input('p62_coddeptorec',10,1,true,'text',1," onchange='js_pesquisap62_coddeptorec(false);'");
          db_input('descrdepto',40,null,true,'text',3);
         ?>
      </td>
    </tr>     
    
    
    <tr>
      <td nowrap title="Departamento de Destino" >
         <?
         db_ancora("<strong>Departamento de Destino:</strong>","js_pesquisap62_coddeptorecDestino(true);",1);
         ?>
      </td>
      <td nowrap> 
        <?
          db_input('p62_coddeptorecDestino',10,1,true,'text',1," onchange='js_pesquisap62_coddeptorecDestino(false);'");
          db_input('descrdeptoDestino',40,null,true,'text',3);
         ?>
      </td>
    </tr>       
    
    
    </table>
  </fieldset>
  
  <div style="margin-top: 10px;">
    <input name="imprimir" type="button" id='imprimir' onclick='js_Emite();'  value="Imprimir">
  </div>
  
  
</form>
</div>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>




<script>

function js_Emite(){


  var sFonte       = "com2_posicaoordemcompra002.php"; 
  
  var iEmpenho     = $F('e60_numemp');
  var sDataInicial = js_formatar($F('e60_emiss1'), 'd');
  var sDataFinal   = js_formatar($F('e60_emiss2'), 'd');
  var iFornecedor  = $F('pc60_numcgm');
  var iOrigem      = $F('p62_coddeptorec');
  var iDestino     = $F('p62_coddeptorecDestino');
  var iOrdem       = $F('m51_codordem');

  
  var sQuery  = "?iEmpenho="     + iEmpenho;
      sQuery += "&dDataInicial=" + sDataInicial;
      sQuery += "&dDataFinal="   + sDataFinal;
      sQuery += "&iFornecedor="  + iFornecedor;
      sQuery += "&iOrigem="      + iOrigem;
      sQuery += "&iDestino="     + iDestino;
      sQuery += "&iOrdem="       + iOrdem;
      
  var jan     = window.open(sFonte+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}



function js_pesquisa_matordem(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordemanulada.php?funcao_js=parent.js_mostramatordem1|m51_codordem|','Pesquisa',true);
  }else{
     if(document.form1.m51_codordem.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordemanulada.php?pesquisa_chave='+document.form1.m51_codordem.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
     }else{
       document.form1.m51_codordem.value = ''; 
     }
  }
}
function js_mostramatordem(chave,erro){
  document.form1.m51_codordem.value = chave1; 
  if(erro==true){ 
    document.form1.m51_codordem.value = ''; 
    document.form1.m51_codordem.focus(); 
  }
}
function js_mostramatordem1(chave1){
   document.form1.m51_codordem.value = chave1;  
   db_iframe_matordem.hide();
}




function js_pesquisap62_coddeptorecDestino(mostra){
  
  var processa = true;
  var form     = document.form1;
  if(mostra == true){
    js_OpenJanelaIframe('top.corpo','db_iframe_Destino','func_db_depart.php?funcao_js=parent.js_mostradb_departDestino1|0|1&todasinstit=1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_Destino','func_db_depart.php?pesquisa_chave='+document.form1.p62_coddeptorecDestino.value+'&funcao_js=parent.js_mostradb_departDestino&todasinstit=1&instituicao=0','Pesquisa',false);
  }
}
function js_mostradb_departDestino(chave,erro){
  
  document.form1.descrdeptoDestino.value = chave; 
  if(erro == true){ 
    
    document.form1.p62_coddeptorecDestino.focus(); 
    document.form1.p62_coddeptorecDestino.value = ''; 
  }
}

function js_mostradb_departDestino1(chave1, chave2){
  
  document.form1.p62_coddeptorecDestino.value = chave1;
  document.form1.descrdeptoDestino.value      = chave2;  
  db_iframe_Destino.hide();
}



function js_pesquisap62_coddeptorec(mostra){
  
  var processa = true;
  var form     = document.form1;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|0|1&todasinstit=1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_tran','func_db_depart.php?pesquisa_chave='+document.form1.p62_coddeptorec.value+'&funcao_js=parent.js_mostradb_depart&todasinstit=1&instituicao=0','Pesquisa',false);
  }
}
function js_mostradb_depart(chave,erro){
  
  document.form1.descrdepto.value = chave; 
  if(erro == true){ 
    
    document.form1.p62_coddeptorec.focus(); 
    document.form1.p62_coddeptorec.value = ''; 
  }
}

function js_mostradb_depart1(chave1, chave2){
  
  document.form1.p62_coddeptorec.value = chave1;
  document.form1.descrdepto.value      = chave2;  
  db_iframe_tran.hide();
}


function js_pesquisa(mostra){
	if (mostra==true){
  		js_OpenJanelaIframe('top.corpo','db_iframe_pcforne','func_pcfornealt.php?funcao_js=parent.js_mostra1|pc60_numcgm|z01_nome','Pesquisa',true);
	}else{
		js_OpenJanelaIframe('top.corpo','db_iframe_pcforne','func_pcfornealt.php?pesquisa_chave='+document.form1.pc60_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }
    if(document.form1.pc60_numcgm.value==""){
    	document.form1.z01_nome.value="";
    }
}
function js_mostra(nome,erro){
	document.form1.z01_nome.value=nome;
	if (erro==true){
		document.form1.pc60_numcgm.value="";
		document.form1.pc60_numcgm.focus();
	}	
}
function js_mostra1(numcgm,nome){
	document.form1.pc60_numcgm.value=numcgm;
	document.form1.z01_nome.value=nome;
	db_iframe_pcforne.hide();
}


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

</script>
</body>
</html>

<script>

function js_consulta002(chave1){
   js_OpenJanelaIframe('top.corpo','db_iframe_empempenho001','func_empempenho001.php?e60_numemp='+chave1,'Pesquisa',true);
//   db_iframe_empconsulta002.hide(); 
   
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