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
include("classes/db_procfiscal_classe.php");
include("classes/db_procfiscalinscr_classe.php");
include("classes/db_procfiscalmatric_classe.php");
include("classes/db_procfiscalsani_classe.php");
include("classes/db_procfiscalcgm_classe.php");
include("classes/db_procfiscalprot_classe.php");
include("classes/db_procfiscalfiscais_classe.php");
$clprocfiscal       = new cl_procfiscal;
$clprocfiscalinscr  = new cl_procfiscalinscr;
$clprocfiscalmatric = new cl_procfiscalmatric;
$clprocfiscalsani   = new cl_procfiscalsani;
$clprocfiscalcgm    = new cl_procfiscalcgm;
$clprocfiscalprot   = new cl_procfiscalprot;
$clprocfiscal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("descrdepto");
$clrotulo->label("nomeinst");
$clrotulo->label("y33_descricao");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("q02_inscr");
$clrotulo->label("y80_codsani");
$clrotulo->label("p58_codproc");
$clrotulo->label("p58_numero");
$clrotulo->label("y100_sequencial");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<br>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" style="margin-top: 25px;" >
<center>
<form name="form1" method="post" action="<?=$db_action?>">
<fieldset style="width: 600px;">
<legend><strong>Consulta Processo Fiscal</strong></legend>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="160" nowrap title="<?=@$Ty100_sequencial?>">
       <?
       db_ancora(@$Ly100_sequencial,"js_pesquisay108_procfiscal(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			db_input('y100_sequencial',10,$Iy100_sequencial,true,'text',$db_opcao," onchange='js_pesquisay108_procfiscal(false);'")
			?>
			<?
			db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
      ?>
    </td>
  </tr>

   <tr>
    <td nowrap title="<?=@$Ty95_numcgm?>">
       <?
       db_ancora(@$Lz01_numcgm,"js_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
			<?
			db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_numcgm(false);'")
			?>
			
    </td>
  </tr> 
<tr> 
    <td nowrap> 
		<?
		 db_ancora($Lj01_matric,'js_mostramatriculas(true);',$db_opcao); 
		?>
		</td>
    <td> 
    <?
 			db_input('j01_matric',10,$Ij01_matric,true,'text',$db_opcao,'onchange="js_mostramatriculas(false);"');  // 4,'onchange="js_mostramatriculas(false);"');
		?>
    </td>
    </tr>
	<tr> 
     <td nowrap> 
		    <?
 			  db_ancora($Lq02_inscr,'js_mostrainscricao(true)',$db_opcao);//4);
			  ?>
     </td>
     <td> 
       <?
 			  db_input('q02_inscr',10,$Iq02_inscr,true,'text',$db_opcao,'onchange="js_mostrainscricao(false);"');   // 4,'onchange="js_mostrainscricao(false);"');
			 ?>
     </td>
  </tr>
	 
  <tr> 
    <td nowrap> 
		  <?
 			db_ancora($Lp58_codproc,' js_mostracodproc(true); ',4);
			 ?>
		   </td>
       <td> 
        <?
 			  db_input('p58_codproc',10,$Ip58_codproc,true,'text',4,'onchange="js_mostracodproc(false);"');
				?>
			 </td>
      </tr>
       <tr>
         <td> <b>Data inicial:</b></td>
			   <td>
			   	 <?
             db_inputdata('dataini',@$dataini_dia,@$dataini_mes,@$dataini_ano,true,'text',$db_opcao,"")
           ?>
           
				 <b>à</b>
			   
			   	 <?
             db_inputdata('datafim',@$datafim_dia,@$datafim_mes,@$datafim_ano,true,'text',$db_opcao,"")
           ?>
				 </td>
			 </tr>
       <tr>
         <td colspan="2">&nbsp</td>
       </tr>
</table>
</fieldset>
<p align="center">
  <input name='pesquisar' id='pesquisar' type='button' value='Pesquisar' onClick='js_pesquisar();'> 
</p>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>

<script>

function js_pesquisar(){
  
  var datainicial  = document.form1.dataini.value;
  var datafinal    = document.form1.datafim.value;
  var sQueryString = 'funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome';
   
  if (datainicial != '') {
    sQueryString += '&datainicial='+datainicial; 
  }
  
  if (datafinal != '') {
    sQueryString += '&datafinal='+datafinal; 
  }
  
  if (datainicial == '' && datafinal == '' ) {
    alert('Preencha pelo menos um filtro para a pesquisa.');
    return false;
  }
    
  js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?'+sQueryString,'Pesquisa',true);

}

function js_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}

function js_mostracgm(erro,chave){
	document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ""; 
		document.form1.q02_inscr.value  = "";
  	document.form1.j01_matric.value = "";
  }else{
		js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome&cgm='+document.form1.z01_numcgm.value,'Pesquisa',true);
	}
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
	document.form1.q02_inscr.value  = "";
 	document.form1.j01_matric.value = "";
  db_iframe_cgm.hide();
  js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome&cgm='+chave1,'Pesquisa',true);
}


function js_mostrainscricao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_preencheinscricao|0|z01_nome|db_z01_numcgm','Pesquisa',true,'15');
  
  }else{
    js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_preencheinscricao2','Pesquisa',false);
  }
}

function js_preencheinscricao(chave,chave1,chave2){
  document.form1.j01_matric.value = "";
  document.form1.z01_numcgm.value = chave2;
	document.form1.z01_nome.value = chave1;
  document.form1.q02_inscr.value = chave;
  db_iframe_issbase.hide();
	js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome&inscr='+chave,'Pesquisa',true);
}

function js_preencheinscricao2(chave,erro,chave2,chave3){
	//alert('chave='+chave+' chave1='+chave1+' chave2='+chave2+'chave3= '+chave3);
  document.form1.j01_matric.value = "";
  document.form1.z01_numcgm.value = chave3;
  document.form1.z01_nome.value = chave;
	if(erro==true){
		document.form1.j01_matric.value = "";
		document.form1.q02_inscr.value = "";
		document.form1.z01_numcgm.value = "";
	}else{
		js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome&inscr='+document.form1.q02_inscr.value,'Pesquisa',true);
	}
  db_iframe_issbase.hide();
}


function js_mostramatriculas(mostra){
  if(mostra==true){
     js_OpenJanelaIframe('','db_iframe_iptubase','func_iptubase.php?funcao_js=parent.js_preenchematriculas|j01_matric|z01_nome|db_z01_numcgm','Pesquisa',true,'15');
 
  }else{
     js_OpenJanelaIframe('','db_iframe_iptubase','func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenchematriculas2','Pesquisa',false);
  }
}
function js_preenchematriculas(chave,chave1,chave2){
  document.form1.q02_inscr.value = "";
  document.form1.z01_numcgm.value = chave2;
	document.form1.j01_matric.value = chave;
  document.form1.z01_nome.value = chave1;
  db_iframe_iptubase.hide();
	js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome&matric='+chave,'Pesquisa',true);
}

function js_preenchematriculas2(chave,chave1,chave2){
  document.form1.q02_inscr.value = "";
  document.form1.z01_numcgm.value = chave2;
	document.form1.z01_nome.value = chave;
	if(erro==true){
		document.form1.j01_matric.value = "";
		document.form1.q02_inscr.value = "";
		document.form1.z01_numcgm.value = "";
	}else{
			js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome&matric='+document.form1.j01_matric.value,'Pesquisa',true);
	}
 
}


function js_mostracodproc(mostra){
 if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proc','func_protprocessoalt.php?funcao_js=parent.js_mostraproc1|p58_codproc|p58_numcgm|z01_nome','Pesquisa',true,'15');
  }else{
    if(document.form1.p58_codproc.value != '') {
        js_OpenJanelaIframe('','db_iframe_proc','func_protprocessoalt.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraproc','Pesquisa',false);
    }
  }
}
function js_mostraproc(chave,obs,erro){
  if(erro==true){ 
    document.form1.p58_codproc.focus(); 
    document.form1.p58_codproc.value = ''; 
  }else{
    document.form1.z01_nome.value = obs; 
    js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome&processo='+document.form1.p58_codproc.value,'Pesquisa',true);
  }   
}
function js_mostraproc1(chave1,n,z){  
  // document.form1.z01_numcgm.value = n;
  document.form1.z01_nome.value = z;
  document.form1.p58_codproc.value = chave1;
  db_iframe_proc.hide();
	js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome&processo='+chave1,'Pesquisa',true);
}

function js_pesquisay108_procfiscal(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?funcao_js=parent.js_mostraprocfiscal1|y100_sequencial|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.y100_sequencial.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_procfiscal','func_procfiscal_consulta.php?pesquisa_chave='+document.form1.y100_sequencial.value+'&funcao_js=parent.js_mostraprocfiscal','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.y100_coddepto.value = ''; 
     }
  }
}
function js_mostraprocfiscal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.y100_sequencial.focus(); 
    document.form1.y100_sequencial.value = ''; 
  }else{
		location.href='fis2_conprocfiscal002.php?procfiscal='+document.form1.y100_sequencial.value;
	}
}
function js_mostraprocfiscal1(chave1,chave2){
  document.form1.y100_sequencial.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_procfiscal.hide();
	location.href='fis2_conprocfiscal002.php?procfiscal='+chave1;
}


function js_limpa(tipo,valor){
	db_iframe_procfiscal.hide();
	document.form1.j01_matric.value = "";
	document.form1.q02_inscr.value = "";
	document.form1.z01_numcgm.value = "";
	document.form1.z01_nome.value = "";
	document.form1.p58_codproc.value="";
	alert('Nenhum processo fiscal encontrado para o '+tipo+' '+valor);
	
}

	
</script>