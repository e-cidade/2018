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
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$aux = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo;
$clrotulo->label('id_usuario');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
 vir="";
 lista="";
 if(document.form1.data1_dia.value == "" || document.form1.data1_dia.value == "" || document.form1.data1_ano.value == "" || document.form1.data2_dia.value == "" || document.form1.data2_mes.value == "" || document.form1.data2_ano.value == ""){
    alert("Preencha a data corretamente");
    document.form1.data1_dia.focus();
    return false;
 }
 data1 = document.form1.data1_ano.value+"-"+document.form1.data1_mes.value+"-"+document.form1.data1_dia.value;
 data2 = document.form1.data2_ano.value+"-"+document.form1.data2_mes.value+"-"+document.form1.data2_dia.value;
 for(x=0;x<document.form1.tipo.length;x++){
  lista=lista+vir+document.form1.tipo.options[x].value;
  vir=",";
 }
 
  jan = window.open('cai2_cancdebitos002.php?usu='+document.form1.id_usuario.value+'&lista='+lista+'&ver='+document.form1.ver.value+'&dat1='+data1+'&dat2='+data2,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
   <td>&nbsp;</td>
  </tr>
</table>

  <table  align="center" border='0'>
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
       <tr>
           <td colspan=2  align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os Tipos selecionadas</option>
                    <option name="condicao1" value="sem">Sem os Tipos selecionados</option>
                </select>
          </td>
       </tr>
      <tr >
        <td colspan='2' ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Tipos de Débitos</strong>";
                 $aux->codigo = "k00_tipo"; //chave de retorno da func
                 $aux->descr  = "k00_descr";   //chave de retorno
                 $aux->nomeobjeto = 'tipo';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_arretipo.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_arretipo";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 10;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
        	?>
       </td>
      </tr>
      <tr>
       <td title="<?=@$id_usuario?>">
        <?db_ancora(@$Lid_usuario,"js_pesquisa_usuario(true);",$db_opcao);?>
       </td>
       <td>
        <?
	  db_input('id_usuario',5,$Iid_usuario,true,'text',$db_opcao," onchange='js_pesquisa_usuario(false);'");
          db_input('nome',50,$Inome,true,'text',3,'')
	?>				   
       </td>
      </tr>
      <tr>
       <td colspan="2">Periodo: <?db_inputdata('data1',"","","",true,'text',4)?> à <?db_inputdata('data2',"","","",true,'text',4)?> </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<script>
 function js_pesquisa_usuario(mostra){
   if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_usuario','func_db_usuarios.php?funcao_js=parent.js_mostrausuario1|id_usuario|nome','Pesquisa',true);
   }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_usuario','func_db_usuarios.php?pesquisa_chave='+document.form1.id_usuario.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);  
   }
 }

function js_mostrausuario(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
   document.form1.id_usuario.focus();
   document.form1.id_usuario.value = '';
  }
 }
 
function js_mostrausuario1(chave1,chave2){
 document.form1.id_usuario.value = chave1;
 document.form1.nome.value = chave2;
 db_iframe_usuario.hide();
}
 
</script>
<?
if(isset($ordem)){
  echo "<script> js_emite(); </script>";
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>