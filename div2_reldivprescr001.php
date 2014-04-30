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

$clrotulo = new rotulocampo;
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC bgcolor="#cccccc">

<form class="container" name="form1" method="post" action="">
  <fieldset>
	<legend>Prescrição da Dívida</legend>
	<table class="form-container" > 
	  <tr>
		<td nowrap title="<?=@$Tz01_numcgm?>">
		  <?
			db_ancora("CGM :","js_pesquisaz01_numcgm(true);",1);
		  ?>
		</td>
        <td> 
		  <?
			db_input('z01_numcgm',15,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'");
		  ?>

		  <?	
			db_input('z01_nome',30,$Iz01_nome,true,'text',3,'');
          ?>
		</td>
	  </tr>
      <tr>
		<td nowrap title="<?=@$Tj01_matric?>">
		  <?
			db_ancora('Matrícula:',"js_pesquisaj01_matric(true);",1);
		  ?>
        </td>
        <td> 
		  <?
			db_input('j01_matric',15,$Ij01_matric,true,'text',1,"");
          ?>
	    </td>
      </tr>
      <tr>
		<td nowrap title="<?=@$Tq02_inscr?>">
		  <?
			db_ancora('Inscrição :',"js_pesquisaq02_inscr(true);",1);
		  ?>			
        </td>
        <td> 
		  <?
			db_input('q02_inscr',15,$Iq02_inscr,true,'text',1,"");
          ?>
		</td>
      </tr>
	  <?
		$dtd = date("d",db_getsession("DB_datausu"));
		$dtm = date("m",db_getsession("DB_datausu"));
		$dta = date("Y",db_getsession("DB_datausu"));
	  ?>		
	  <tr>
		<td>
		  Data Inicial:
		</td>  
		<td>	
		  <?
			db_inputdata("datai","$dtd","$dtm","$dta","true","text",2);
		  ?>   
		</td>
	  </tr>
      <tr>
        <td>
          Data Final:  
        </td>
        <td>
          <?
            db_inputdata("dataf","$dtd","$dtm","$dta","true","text",2);      
          ?> 
        </td>
      </tr>
      <tr>
        <td>
          Anulada:  
        </td>
        <td>
		  <?
			$aOptions = array("n"=>"Não","s"=>"Sim","todas"=>"Todas");
			db_select("anulada",$aOptions,true,4,"");
		  ?>
        </td>
      </tr>
	</table>
  </fieldset>
								
  <fieldset>
    <legend>Filtros</legend>
	<table class="form-container">
	  <tr>
		<td>
		  Tipo:
        </td>
        <td>
		  <?
            $xx = array("c"=>"Completo","r"=>"Resumido");
			db_select('seltipo',$xx,true,4,"");
          ?>
        </td>
        <td nowrap title="Ordem para a emissão do relatório" >
		  <strong>Ordem:</strong>
		</td>
        <td>
          <?
			$xx = array("d"=>"Data","c"=>"CGM","m"=>"Matrícula","i"=>"Inscrição");
			db_select('selordem',$xx,true,4,"");
          ?>
        </td>
		<td>
		  <strong>Histórico : </strong>
	    </td>
        <td>
		  <?
            $xx = array("s"=>"Sim","n"=>"Não");
			db_select('selhist',$xx,true,4,"");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
</form>

	 <?
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	 ?>
</body>
</html>
<script>

function js_emite(){
 

	qry  =	'?seltipo='+document.form1.seltipo.value;
  qry += 	'&selordem='+document.form1.selordem.value;
  qry += 	'&selhist='+document.form1.selhist.value;
  qry +=	'&datai='+document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
  qry +=  '&dataf='+document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value;
  
	qry +=	'&z01_numcgm='+document.form1.z01_numcgm.value;
	qry +=	'&j01_matric='+document.form1.j01_matric.value;
  qry +=  '&q02_inscr='+document.form1.q02_inscr.value;
  qry +=  '&anulada='+document.form1.anulada.value;
    
	jan = window.open('div2_reldivprescr002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}


function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.kz01_numcgm.value = ''; 
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
  db_iframe_nome.hide();
}


function js_pesquisaj01_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matric','func_iptubase.php?funcao_js=parent.js_mostramatric|j01_matric','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_matric','func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_mostramatric','Pesquisa',false);
  }
}


function js_mostramatric(chave){
		document.form1.j01_matric.value = chave; 
    db_iframe_matric.hide();
}


function js_pesquisaq02_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostrainscr','Pesquisa',false);
  }
}

function js_mostrainscr(chave){
		document.form1.q02_inscr.value = chave; 
    db_iframe_inscr.hide();
}


</script>
<script>

$("z01_numcgm").addClassName("field-size2");
$("j01_matric").addClassName("field-size2");
$("q02_inscr").addClassName("field-size2");
$("z01_nome").addClassName("field-size7");
$("datai").addClassName("field-size2");
$("dataf").addClassName("field-size2");
$("anulada").setAttribute("rel","ignore-css");
$("anulada").addClassName("field-size2");

</script>