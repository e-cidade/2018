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
include("classes/db_marca_classe.php");
db_postmemory($HTTP_POST_VARS);
$aux = new cl_arquivo_auxiliar;
$clmarca = new cl_marca;
$clmarca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
 var qtd=0;
 for(i=0;i<document.form1.length;i++){
  if(document.form1.elements[i].name == "proprietarios[]"){
   vir="";
   lista="";
   for(x=0;x< document.form1.elements[i].length;x++){
    qtd = qtd+1;
    document.form1.elements[i].options[x].selected = true;
    lista+=vir+document.form1.proprietarios.options[x].value;
    vir=",";
   }
  }
 }
 //-- ve se tem lista
 if (qtd == 0){
  alert('Lista de CGM não pode ser vazia ! ');
  document.form1.z01_numcgm.style.backgroundColor="#99A9AE";
  document.form1.z01_numcgm.focus();
  return false;
 }
 jan = window.open('mar2_marcaprop002.php?imagem='+document.form1.imagem.value+'&tipo='+document.form1.tipo.value+'&lista='+lista,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
function verimagem(valor){
 if(valor=="com"){
  document.getElementById('foto').style.visibility = "visible";
 }else{
  document.getElementById('foto').style.visibility = "hidden";
 }
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
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td colspan=2 ><?
                 $aux->cabecalho = "<strong>Proprietários</strong>";
                 $aux->codigo = "z01_numcgm"; //chave de retorno da func
                 $aux->descr  = "z01_nome";   //chave de retorno
                 $aux->nomeobjeto = 'proprietarios';
                 $aux->funcao_js = 'js_mostracgmnome';
                 $aux->funcao_js_hide = 'js_mostracgmnome1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_consmarca.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_marca";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 10;
                 $aux->vwhidth = 400;
                 $aux->executa_script_lost_focus_campo = "document.form1.db_lanca.click()";
                 $aux->funcao_gera_formulario();
                ?>
       </td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
         <b>Propriedades:</b>
         <select name="tipo" onchange="verimagem(this.value)">
             <option value="res">Resumido</option>
             <option value="com">Completo</option>
         </select>
         <span name="foto" id="foto" style="visibility:hidden">
         <select name="imagem">
             <option value="s">Sem imagem</option>
             <option value="c">Com imagem</option>
         </select>
         </span>
        </td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
          <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>