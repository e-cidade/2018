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
include("classes/db_lista_classe.php");
include("classes/db_listadoc_classe.php");
include("classes/db_listadeb_classe.php");
include("classes/db_listatipos_classe.php");
include("classes/db_listanotifica_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k00_tipo');
$clrotulo->label('k00_descr');
$clrotulo->label('k31_obs');

$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS);
$cllista         = new cl_lista;
$cllistadoc      = new cl_listadoc;
$cllistadeb      = new cl_listadeb;
$cllistatipos    = new cl_listatipos;
$cllistanotifica = new cl_listanotifica;
$db_opcao = 1;
$db_botao = true;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){

  var val1 = new Number(document.form1.k60_codigo.value);
  if(val1.valueOf() < 1){
     alert('A lista tem que ser selecionada.');
     return false;
  }
  jan = window.open('cai2_emitelista002.php?lista='+document.form1.k60_codigo.value+'&k31_obs='+document.form1.k31_obs.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="<?=@$Tk60_codigo?>" >
          <?
      	   db_ancora('<b>Lista de débitos : </b>',"js_pesquisalista(true);",4)
          ?>
        </td>
        <td align="left">
          <?
      	  db_input('k60_codigo',4,$Ik60_codigo,true,'text',4,"onchange='js_pesquisalista(false);'");
          db_input('k60_descr',40,$Ik60_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td align="right"  ><b>Prescrever Débitos Notificados: </b></td>
        <td  align="left" > 
				<?
				$arr_op = array("n"=>"Não","s"=>"Sim");
				db_select("prescrnoti",$arr_op,true,"text");
				?>&nbsp;</td>
      </tr>
          <tr>
            <td align="right" ><b> <?=@$Lk31_obs?></b></td>
						<td align="left" ><? db_textarea('k31_obs',2,70,$Ik31_obs,'','text',$db_opcao,"") ?></td>
          </tr>
      <tr>
        <td colspan="2" align = "center"> 
         <input name="processar" type="button" id="processar" value="Processar" onclick='js_prescreve();' >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_prescreve(){
// alert('abre tela de prescricao');
   if(document.form1.k60_codigo.value == ''){
      alert('Selecione a lista de debitos!');
   }else if(confirm('Voce realmente deseja prescrever todos os debitos da lista ?')){
      querystring = 'geral=t&processar=processar&lista='+document.form1.k60_codigo.value+"&prescrnoti="+document.form1.prescrnoti.value+'&k31_obs='+document.form1.k31_obs.value;
      js_OpenJanelaIframe('','db_iframe_proc','func_prescreverdivida.php?'+querystring,'Prescrição de Divida',true);
   }
} 
function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_pesquisalista(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista';
     }
}
function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
     document.form1.k60_descr.focus();
     document.form1.k60_descr.value = '';
  }
}
function js_mostralista1(chave1,chave2){
     document.form1.k60_codigo.value = chave1;
     document.form1.k60_descr.value = chave2;
     db_iframe.hide();
}


</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>