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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include_once("dbforms/db_classesgenericas.php");

$cliframe_seleciona = new cl_iframe_seleciona;

//db_postmemory($HTTP_POST_VARS,2);//exit;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style type="text/css">
th {
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
}
td {
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
}
</style>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="form1" id="form1" method="post" action="iss2_contribativ002.php" target='alvo'>
<input name="chaves1" id="emite" type="hidden" value="" onClick="">
<table valign="top" width="100%" border="0" cellspacing="1" cellpadding="0">
<tr align="top">
  <td width="100%" height="30" colspan="6" bordercolor="#FFFFCC">
  </td>
</tr>
<tr>
  <td title="" align="center">
    <?
     db_ancora('<b>Inscrição </b>',' js_inscr(true); ',1);
     db_input('q02_inscr',5,0,true,'text',1,"onchange='js_inscr(false)'");
     db_input('z01_nome',50,0,true,'text',3);
     ?>
  </td>
</tr>

 <tr>
    <td nowrap title="" align="center">
       <?
        db_ancora('<b>Rua </b>',"js_pesquisaj14_codigo(true);",$db_opcao);
        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
        db_input('j14_codigo',5,0,true,'text',$db_opcao," onchange='js_pesquisaj14_codigo(false);'");
        db_input('j14_nome',50,0,true,'text',3,'');
       ?>
    <td>
  </tr>

 <tr>
    <td nowrap title="" align="center">
		<strong>Tipo:&nbsp;&nbsp;</strong>
		<?
	  $tipo = array("a"=>"Ativas","t"=>"Todas","b"=>"Baixadas");
	  db_select("tipo",$tipo,true,2);
		?>
    <td>
 </tr>

 <tr>
    <td nowrap title="" align="center">
		<strong>Debitos:&nbsp;&nbsp;</strong>
		<?
	  $debitos = array("d"=>"Em débito","t"=>"Todas","s"=>"Sem débito");
	  db_select("debitos",$debitos,true,2);
		?>
    <td>
 </tr>

<tr>
  <td title="" align="center">

<?   
      $sql = "select q03_ativ,q03_descr from ativid order by q03_ativ"; 
      $cliframe_seleciona->sql           = $sql;
      $cliframe_seleciona->campos        = "q03_ativ,q03_descr";
      $cliframe_seleciona->legenda       = "Atividades";
      $cliframe_seleciona->textocabec    = "darkblue";
      $cliframe_seleciona->textocorpo    = "black";
      $cliframe_seleciona->fundocabec    = "#aacccc";
      $cliframe_seleciona->fundocorpo    = "#ccddcc";
      $cliframe_seleciona->iframe_height = '400px';
      $cliframe_seleciona->iframe_width  = '100%';
      $cliframe_seleciona->iframe_nome   = "ativ";
      $cliframe_seleciona->chaves        = "q03_ativ";
      $cliframe_seleciona->dbscript      = "";
      $cliframe_seleciona->js_marcador   = "";
      $cliframe_seleciona->iframe_seleciona($db_opcao);
?>
  </td>
</tr>
<tr>
  <td title="" align="center">
    <input name="sintetico" id="emite" type="button" value="Processar" onClick="js_relatorio()">
    <input name="atividades" id="" type="hidden" value="">
    <input name="chaves" id="" type="hidden" value="">
  </td>
</tr>


</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

</body>
</html>
<script>
function js_relatorio(){
   js_gera_chaves();
   jan = window.open('','alvo','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   document.form1.submit();
}

function js_mandadados2(){


}
function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    if(inscr!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_inscr','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
    }else{
      document.form1.z01_nome.value="";
      //document.form1.submit();
    }
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nome.value = chave2;
  location.href="iss2_contribativ001.php?q02_inscr="+chave1+"&z01_nome="+chave2;
  //document.form1.submit();
  db_iframe_inscr.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.q02_inscr.focus();
    document.form1.q02_inscr.value = '';
  }else{
    //document.form1.submit();
  }
}

function js_pesquisaj14_codigo(mostra){
  if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|0|1','Pesquisa',true);
  }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.j14_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
  //  db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form1.j14_codigo.value+'&funcao_js=parent.js_mostraruas';
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.j14_codigo.focus();
    document.form1.j14_codigo.value = '';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.j14_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}



</script>