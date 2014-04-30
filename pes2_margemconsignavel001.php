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
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');

$clrotulo->label('r08_descr');
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
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  qry = 'base1='+document.form1.base01.value+
	'&base2='+document.form1.base02.value+
	'&base3='+document.form1.base03.value+
	'&perc='+document.form1.perc.value+
	'&tipo_margem='+document.form1.tipo_margem.value+
	'&ordem='+document.form1.ordem.value+
	'&ano='+document.form1.DBtxt23.value+
	'&mes='+document.form1.DBtxt25.value;
  jan = window.open('pes2_margemconsignavel002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
<center>
<form name="form1" method="post" action="" >

<fieldset style="width: 30%"><legend><b>Margem Consignável</b></legend>
  <table  align="center">
      <tr>
      </tr>
      <tr >
        <td align="left" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
            $DBtxt23 = db_anofolha();
            db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
            $DBtxt25 = db_mesfolha();
            db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
          <tr>
            <td nowrap align="left" title="" width="30%"><b>
              <?
               db_ancora('Remuneração',"js_pesquisabase01(true)",@$db_opcao);
              ?>
	      </b>&nbsp;&nbsp;
            </td>
            <td nowrap> 
              <?
               db_input('base01',4,@$base01,true,'text',@$db_opcao,"onchange='js_pesquisabase01(false)'");
               db_input("r08_descr",30,@$Ir08_descr,true,"text",3,"","descr_base01");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="" width="30%"><b>
              <?
               db_ancora('Desc. Obrigatórios',"js_pesquisabase02(true)",@$db_opcao);
              ?>
	      </b>&nbsp;&nbsp;
            </td>
            <td nowrap> 
              <?
               db_input('base02',4,@$base02,true,'text',@$db_opcao,"onchange='js_pesquisabase02(false)'");
               db_input("r08_descr",30,@$Ir08_descr,true,"text",3,"","descr_base02");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="left" title="" width="30%"><b>
              <?
               db_ancora('Comprometido',"js_pesquisabase03(true)",@$db_opcao);
              ?>
	      </b>&nbsp;&nbsp;
            </td>
            <td nowrap> 
              <?
               db_input('base03',4,@$base03,true,'text',@$db_opcao,"onchange='js_pesquisabase03(false)'");
               db_input("r08_descr",30,@$Ir08_descr,true,"text",3,"","descr_base03");
              ?>
            </td>
          </tr>
      <tr>
        <td align="left" nowrap title="Percentual da margem consignável" >
        <strong>Perc. Consignável :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
	         @$perc=0;
           db_input('perc',3,$perc,true,'text',2,'')
          ?>
	</td>
      </tr>
      <tr>
	      <td align="left" nowrap><strong>Apresentar Servidores :</strong>&nbsp;&nbsp;
        </td>
        <td>
         <?
           $xx = array("t"=>"Todos","s"=>"Sem Margem","c"=>"Com Margem");
           db_select('tipo_margem',$xx,true,4,"");
         ?>
	      </td>
      </tr>
      <tr>
        <td align="left" nowrap><strong>Ordem :</strong>&nbsp;&nbsp;</td>
        <td>
         <?
           $xy = array("a"=>"Nome","n"=>"Matricula");
           db_select('ordem',$xy,true,4,"");
         ?>
        </td>
      </tr>
</table>
</fieldset>
	<table>
    <tr>
      <td colspan="2"align = "center"> 
        <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
      </td>
    </tr>
  </table>
</form>
</center>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisabase01(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bases','func_bases.php?funcao_js=parent.js_mostrabase011|r08_codigo|r08_descr','Pesquisa',true);
  }else{
    if(document.form1.base01.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_base01','func_bases.php?pesquisa_chave='+document.form1.base01.value+'&funcao_js=parent.js_mostrabase01','Pesquisa',false);
    }else{
      document.form1.descr_base01.value = ''; 
    }
  }
}
function js_mostrabase01(chave,erro){
  document.form1.descr_base01.value = chave; 
  if(erro==true){ 
    document.form1.base01.focus(); 
    document.form1.base01.value = ''; 
  }
}
function js_mostrabase011(chave1,chave2){
  document.form1.base01.value = chave1;
  document.form1.descr_base01.value = chave2;
  db_iframe_bases.hide();
}



function js_pesquisabase02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bases','func_bases.php?funcao_js=parent.js_mostrabase021|r08_codigo|r08_descr','Pesquisa',true);
  }else{
    if(document.form1.base02.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_base02','func_bases.php?pesquisa_chave='+document.form1.base02.value+'&funcao_js=parent.js_mostrabase02','Pesquisa',false);
    }else{
      document.form1.descr_base02.value = ''; 
    }
  }
}
function js_mostrabase02(chave,erro){
  document.form1.descr_base02.value = chave; 
  if(erro==true){ 
    document.form1.base02.focus(); 
    document.form1.base02.value = ''; 
  }
}
function js_mostrabase021(chave1,chave2){
  document.form1.base02.value = chave1;
  document.form1.descr_base02.value = chave2;
  db_iframe_bases.hide();
}


function js_pesquisabase03(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bases','func_bases.php?funcao_js=parent.js_mostrabase031|r08_codigo|r08_descr','Pesquisa',true);
  }else{
    if(document.form1.base03.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_base03','func_bases.php?pesquisa_chave='+document.form1.base03.value+'&funcao_js=parent.js_mostrabase03','Pesquisa',false);
    }else{
      document.form1.descr_base03.value = ''; 
    }
  }
}
function js_mostrabase03(chave,erro){
  document.form1.descr_base03.value = chave; 
  if(erro==true){ 
    document.form1.base03.focus(); 
    document.form1.base03.value = ''; 
  }
}
function js_mostrabase031(chave1,chave2){
  document.form1.base03.value = chave1;
  document.form1.descr_base03.value = chave2;
  db_iframe_bases.hide();
}













</script>