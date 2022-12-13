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
include("dbforms/db_funcoes.php");
include("classes/db_issbase_classe.php");
include("classes/db_issruas_classe.php");
include("classes/db_termovist_classe.php");
include_once ("dbforms/db_classesgenericas.php");

$clissbase = new cl_issbase;
$issruas   = new cl_issruas;
$cltermovist = new cl_termovist;
$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");

$cliframe_seleciona = new cl_iframe_seleciona;
$db_opcao = 1;
$sql = "select q12_classe,q12_descr from clasativ inner join classe on q82_classe=q12_classe group by q12_classe,q12_descr";
$dbwhere = "";
$vir = "";
//$inscrs = "";
/*
$rsTermotipo = $cltermovist->sql_record($cltermovist->sql_query_file(null,"y91_inscr",null," y91_inscr = $q02_inscr and y91_exerc = '".db_getsession("DB_anousu")."' "));	
$numrowstipo = $cltermovist->numrows;
if ($numrowstipo > 0){
    for ($ir=0;$ir<$numrowstipo;$ir++){
        db_fieldsmemory($rsTermotipo,$ir); 
        $inscrs .= $vir.$y91_inscr; 
	$vir = ',';
     }
}*/
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table  border="0">
     <tr>
       <td height="25" title="<?=$Tq02_inscr?>">
         <?
           db_ancora($Lq02_inscr,'js_pesquisaq02_inscr(true);',4)
         ?>
       </td>
       <td height="25">
         <?
            db_input("q02_inscr",8,$Iq02_inscr,true,'text',4)
         ?>
       </td>
     </tr>
     <tr>
       <td height="25" title="<?=$Tj14_codigo?>">
         <?
           db_ancora($Lj14_codigo,'js_pesquisaj14_codigo(true); ',4)
         ?>
       </td>
       <td height="25">
         <?
           db_input('j14_codigo',8,$Ij14_codigo,true,'text',1,'',"","#E6E4F1");
           db_input('j14_nome',40,$Ij14_nome,true,'text',3);
         ?>
       </td>
     </tr>
     <tr>
        <td><b>Opcoes : </b></td>
        <td>
           <?
			$l = array ("c" => "Com as classes selecionadas", "s" => "Sem as classes selecionadas");
			db_select('tipo', $l, true, 2);
		   ?>
	    </td>
	 </tr>
     <tr>
        <td><b>Reemissão : </b></td>
        <td>
           <?
			$re = array ("n" => "Não", "s" => "Sim");
			db_select('reemis', $re, true, 2);
		   ?>
	    </td>
	 </tr>
	 </table>
	 <table width="900" align="center">
     <tr>
       <td></td>
       <td align="center" valign="top" bgcolor="#CCCCCC">
	       <?
	        $cliframe_seleciona->sql = $sql;
		
	        if(isset($codigos)&&$codigos != ""){		    
	            $dbwhere = " where q12_classe in ($codigos)";
		}
                $sqlmarca = "select q12_classe,q12_descr from clasativ 
                               inner join classe on q82_classe=q12_classe 
			       $dbwhere
			     group by q12_classe,q12_descr";
	        $cliframe_seleciona->sql_marca = $sqlmarca;
//	        echo $sql;
	//        $cliframe_seleciona->checked = true;
		$cliframe_seleciona->campos = "q12_classe,q12_descr";
		$cliframe_seleciona->legenda = "Classes";
		$cliframe_seleciona->textocabec = "darkblue";
		$cliframe_seleciona->textocorpo = "black";
  		$cliframe_seleciona->fundocabec = "#aacccc";
		$cliframe_seleciona->fundocorpo = "#ccddcc";
		$cliframe_seleciona->iframe_height = '200px';
		$cliframe_seleciona->iframe_width = '100%';
		$cliframe_seleciona->iframe_nome = "classe";
		$cliframe_seleciona->chaves = "q12_classe";
		$cliframe_seleciona->marcador = true;
		$cliframe_seleciona->dbscript = "onClick='parent.js_mandadados();'";
                $cliframe_seleciona->js_marcador = 'parent.js_mandadados();';
		$cliframe_seleciona->iframe_seleciona($db_opcao);


//echo($cltermovist->sql_query_file(null,"y91_inscr",null," y91_inscr = $q02_inscr and y91_exerc = '".db_getsession("DB_anousu")."' "));	
	       ?>
       </td>    
     </tr>
     <tr>
       <td colspan='6' align='center' >
	    <input name='start' type='button' value='Gerar' onclick="valida()">
	    <input name='codigos' type='hidden' value='' onclick="">
	    <input name='inscrs'  type='hidden' value='' onclick="">
       </td>
     </tr>
    </table>
    </form>
  </td>
 </tr>
</table>
     
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>

function js_pesquisaq02_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_q02_inscr','func_issbase.php?funcao_js=parent.js_mostraq02_inscr1|q02_inscr','Pesquisa',true);
  }else{
     if(document.form1.q02_inscr.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_q02_inscr','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostraq02_inscr','Pesquisa',false);
     }else{
       document.form1.q02_inscr.value = '';
     }
  }
}

function js_mostraq02_inscr(chave,erro){
  document.form1.j14_codigo.value = '';
  document.form1.j14_codigo.value = '';
  document.form1.q02_inscr.value = chave;
  if(erro==true){
    document.form1.q02_inscr.focus();
    document.form1.q02_inscr.value = erro;
  }
}
function js_mostraq02_inscr1(chave1){
  document.form1.j14_codigo.value = '';
  document.form1.j14_codigo.value = '';
  document.form1.q02_inscr.value = chave1;
  db_iframe_q02_inscr.hide();
}

function js_pesquisaj14_codigo(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe','func_ruas.php?rural=1&funcao_js=parent.js_mostraruas1|0|1','Pesquisa',true,20);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe','func_ruas.php?rural=1&pesquisa_chave='+document.form1.j14_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false,0);
    }
  }

//se n?o digitou nada
function js_mostraruas1(chave1, chave2){
  document.form1.q02_inscr.value = '';
  document.form1.j14_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe.hide();
}


function js_pesquisa_cids(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cids','func_cids.php?funcao_js=parent.js_mostracids1|sd22_c_codigo|sd22_c_descr','Pesquisa',true);
  }else{
     if(document.form1.cids.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_cids','func_cids.php?pesquisa_chave='+document.form1.cids.value+'&funcao_js=parent.js_mostracids','Pesquisa',false);
     }else{
       document.form1.descr.value = '';
     }
  }
}
function js_mostracids(chave,erro){
  document.form1.descr.value = chave;
  if(erro==true){
    document.form1.cids.focus();
    document.form1.cids.value = '';
  }
}
function js_mostracids1(chave1,chave2){
  document.form1.cids.value = chave1;
  document.form1.descr.value = chave2;
  db_iframe_cids.hide();
}


function js_mandadados(){
   dados = '';
   virgula = '';
   for(i = 0;i < classe.document.form1.elements.length;i++){
      if(classe.document.form1.elements[i].type == "checkbox" &&  classe.document.form1.elements[i].checked){
        dados += virgula+classe.document.form1.elements[i].value;
         virgula = ',';
      }
    }
  document.form1.codigos.value = dados;
//  document.form1.submit();

}

function valida(){
   var confirma;
   obj = document.form1;
   inscricao = obj.q02_inscr.value;
   logradouro = obj.j14_codigo.value;
   js_mandadados();
   if( ( inscricao == '' ) && ( logradouro == '' ) ){
      alert("Informe uma Inscrição ou um Logradouro!");
      obj.q02_inscr.focus();
   }else{
	   if(document.form1.reemis.value == 'n'){
		   confirma = confirm("Deseja imprimir relatório dos termo(s) que não foram gerado(s)?");
		   if (confirma){
		   	jan2 = window.open('fis2_termofiscal003.php?inscricao='+inscricao+'&logradouro='+logradouro+'&tipo='+document.form1.tipo.value+'&classes='+document.form1.codigos.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
				jan2.moveTo(0,0);
		   	jan = window.open('fis2_termofiscal002.php?reemis='+document.form1.reemis.value+'&inscricao='+inscricao+'&logradouro='+logradouro+'&tipo='+document.form1.tipo.value+'&classes='+document.form1.codigos.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
				jan.moveTo(0,0);

		   }else{
				jan = window.open('fis2_termofiscal002.php?reemis='+document.form1.reemis.value+'&inscricao='+inscricao+'&logradouro='+logradouro+'&tipo='+document.form1.tipo.value+'&classes='+document.form1.codigos.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
				jan.moveTo(0,0);
		   } 
     }else{
       jan = window.open('fis2_termofiscal002.php?reemis='+document.form1.reemis.value+'&inscricao='+inscricao+'&logradouro='+logradouro+'&tipo='+document.form1.tipo.value+'&classes='+document.form1.codigos.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		   jan.moveTo(0,0);
     }
   }
}
</script>