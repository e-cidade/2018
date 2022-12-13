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
require("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_empempenho_classe.php");
include("classes/db_cgm_classe.php");

$clempempenho = new cl_empempenho;
$clcgm    = new cl_cgm;

$clempempenho->rotulo->label();

$clrotulo = new rotulocampo;
$clcgm->rotulo->label();
$clrotulo->label("z01_nome");

db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
  if (document.form1.e60_numcgm.value!=''){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e60_numcgm.value+'&funcao_js=parent.js_mostracgmtesta','Pesquisa',false);
  }else if(document.form1.e60_codemp.value!=''){
    codemp = document.form1.e60_codemp.value ;    
    js_OpenJanelaIframe('top.corpo','db_iframe_empconsulta002','emp1_empconsulta002.php?e60_codemp='+codemp+'&e60_numemp='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostracodemp|e60_numemp','Pesquisa',true);
  }else if(document.form1.e60_numemp.value!=''){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenhotesta','Pesquisa',false);
  }
}
function js_mostracgmtesta(erro,chave){
  if(erro==true){ 
    document.form1.e60_numcgm.focus(); 
    document.form1.e60_numcgm.value =''; 
  }else{
    <? if (isset($vLiquida) && ($vLiquida==true))
         echo "location.href='emp1_ordemcompra_liquida002.php?vLiquida=true&e60_numcgm='+document.form1.e60_numcgm.value;";
       else 
         echo "location.href='emp1_ordemcompra002.php?e60_numcgm='+document.form1.e60_numcgm.value;";
    ?>
  }
}
function js_mostracodemp(chave){
   <? if (isset($vLiquida) && ($vLiquida==true))
        echo "location.href='emp1_ordemcompra_liquida002.php?vLiquida=true&e60_numemp='+chave;";
      else
        echo "location.href='emp1_ordemcompra002.php?e60_numemp='+chave;";
   ?>	
}
function js_mostraempempenhotesta(chave,erro){
  if(erro==true){ 
    alert("Nº do empenho não existe!!");
    document.form1.e60_numemp.value = ''; 
    document.form1.e60_numemp.focus(); 
  }else{
    <? if (isset($vLiquida) && ($vLiquida==true))
         echo "location.href='emp1_ordemcompra_liquida002.php?vLiquida=true&e60_numemp='+document.form1.e60_numemp.value;";   
       else	
         echo "location.href='emp1_ordemcompra002.php?e60_numemp='+document.form1.e60_numemp.value;";   
    ?>	 
  }
}

function js_limpa(){
   <? if (isset($vLiquida) && ($vLiquida==true))
        echo "location.href='con4_ordemcompra001.php';";
      else
        echo "location.href='emp1_ordemcompra001.php';";
   ?>	
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
<center>
<form name="form1" method="post" target="" action="">

<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>

 <? if (isset($vLiquida) && $vLiquida==true){ ?>

  <tr> 
    <td align="left" nowrap colspan=2><b><font size=+1>Ordem de Compra/Liquidação</font></b></td>
  </tr>



 <?  }  ?>
  <tr> 
    <td  align="left" nowrap title="<?=$Te60_numcgm?>"><?db_ancora(@$Le60_numcgm,"js_pesquisae60_numcgm(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("e60_numcgm",6,$Ie60_numcgm,true,"text",4,"onchange='js_pesquisae60_numcgm(false);'");
         db_input("z01_nome",40,"$Iz01_nome",true,"text",3);  
        ?></td>
  </tr>

  <tr>
	<td nowrap title="<?=@$Te60_codemp?>">
	   <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",1); ?>
	</td>
	<td> 
	   <? db_input('e60_codemp',15,$Ie60_codemp,true,'text',1,"onchange=js_pesquisae60_codemp(false);")  ?>
	</td>
      </tr>

      <tr>
	<td nowrap title="<?=@$Te60_numemp?>">
	   <? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",1); ?>
	</td>
	<td> 
	   <? db_input('e60_numemp',15,$Ie60_numemp,true,'text',1," onchange='js_pesquisae60_numemp(false);'")  ?>
	</td>
      </tr>




  <tr height="20px">
  <td ></td>
  <td ></td>
  </tr>
  <tr>
  <td colspan="2" align="center">
    <input name="processar" type="button"   value="Processar" onclick='js_emite();'>
    <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar">
  </td>
  </tr>
  </table>
  </form>
 

</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
//---------------------------------------------------------------
function js_pesquisae60_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.e60_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e60_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.e60_numcgm.focus(); 
    document.form1.e60_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.e60_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
//----------------------------------------------------------------------
function js_pesquisae60_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_codemp|e60_numemp','Pesquisa',true);  }else{
     if(document.form1.e60_numemp.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
     }else{
       document.form1.e60_numemp.value = ''; 
     }
  }
}
function js_mostraempempenho(chave,erro){
  if(erro==true){ 
    document.form1.e60_numemp.value = ''; 
    document.form1.e60_numemp.focus(); 
  }
}
function js_mostraempempenho1(chave1,x){
  document.form1.e60_numemp.value = x;
  db_iframe_empempenho.hide();
}
//-----------------------------------------------------
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?anul=false&funcao_js=parent.js_mostraempempenho1|e60_codemp|e60_numemp','Pesquisa',true);
  }else{
     if(document.form1.e60_codemp.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?anul=false&pesquisa_chave='+document.form1.e60_codemp.value+'&funcao_js=parent.js_mostraempempenho3','Pesquisa',false);
     }else{
       document.form1.e60_codemp.value = ''; 
     }
  }
}
function js_mostraempempenho3(chave,erro){
  if(erro==true){ 
    document.form1.e60_codemp.value = ''; 
    document.form1.e60_codemp.focus(); 
  }
}
//--------------------------------
</script>
</body>
</html>