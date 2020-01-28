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
include("classes/db_sanitario_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$sanitario = new cl_sanitario;
$aux = new cl_arquivo_auxiliar; 
//$clsanitario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q03_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$clrotulo->label("y76_codvist");
$clrotulo->label("y70_id_usuario");
$clrotulo->label("y70_data");
$clrotulo->label("y70_tipovist");
$clrotulo->label("y77_descricao");
$clrotulo->label("y70_numbloco");
$clrotulo->label("y10_codigo");
$clrotulo->label("y10_codi");
$clrotulo->label("y11_codigo");
$clrotulo->label("y11_codi");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
  </tr>
</table >
  <table    align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td ></td>
         <td ></td>
      </tr>
       <tr> 
           <td colspan=2  align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com as vistorias selecionadas</option>
                    <option name="condicao1" value="sem">Sem as vistorias selecionadas</option>
                </select>
          </td>
       </tr>
      <tr>
        <td colspan=2 >
	      <?
                $aux->cabecalho = "<strong>Tipo de vistorias</strong>";
                $aux->codigo = "y77_codtipo";
                $aux->descr  = "y77_descricao";
                $aux->nomeobjeto = 'tipo';
                $aux->funcao_js = 'js_mostra';
				$aux->funcao_js_hide = 'js_mostra1';
				$aux->sql_exec  = "";
				$aux->func_arquivo = "func_tipovistorias.php";
				$aux->nomeiframe = "db_iframe_tipovist";
				$aux->localjan = "";
				$aux->onclick = "";
				$aux->db_opcao = 2;
				$aux->tipo = 2;
				$aux->top = 0;
				$aux->linhas = 10;
				$aux->vwhidth = 400;
				$aux->btn_lanca = "lancatipo";
				$aux->funcao_gera_formulario();
              ?>
       </td>
      </tr>
      <!--
      <tr>
         <td nowrap title="<?=@$Ty76_codvist?>">
	    <?
	     db_ancora(@$Ly76_codvist,"js_pesquisay76_codvist(true);",1);
	    ?>
	 </td>
	 <td>
	    <?
	     db_input('y76_codvist',10,$Iy76_codvist,true,'text',1," onchange='js_pesquisay76_codvist(false);'");
	    ?>
	    <?
	     db_input('y70_id_usuario',40,$Iy70_id_usuario,true,'text',3,'');
	    ?>
	 </td>
	 </tr>
	 -->
	 <tr>
	 
         <td align = 'right'> 
	    <b>Ordenar por:</b>
	 </td>
	 <td>
	    <?
	    $tipo_ordem = array("v"=>"Vistoria","r"=>"Rua","b"=>"Bairro","n"=>"Nome","t"=>"Tipo");
	    db_select("ordem",$tipo_ordem,true,"text",4); 
	    ?>
	    
	 </td>
      </tr>
      </tr>
      <tr>
        <td align='center'  colspan=2 >
               <b> Período </b>
               <? 
	          db_inputdata('data1','','','',true,'text',1,"");   		          
                  echo "<b> a</b> ";
                  db_inputdata('data2','','','',true,'text',1,"");
               ?>&nbsp;
	</td>
      </tr>
      <tr>
      </tr>
      <tr>
      </tr>
      <tr align="center" >
       <td colspan=2>
	</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
           <strong>Tipo : </strong>
           <select name="tiporel">
             <option name="analitico" value="ana">Analítico</option>
             <option name="sintetico" value="sin">Sintético</option>
           </select>
          <input name="consultar" type="submit" value="Processar" onclick="js_mandadados();" >
        </td>
      </tr>
  </form>
    </table>
</body>
</html>

<script>
function js_pesquisay76_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_abreconsulta|y70_codvist','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_vistorias','func_vistorias.php?pesquisa_chave='+document.form1.y76_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
  }
}
function js_mostravistorias(chave,erro){
  document.form1.y70_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y76_codvist.focus(); 
    document.form1.y76_codvist.value = ''; 
  }
}
function js_mostravistorias1(chave1,chave2){
  document.form1.y76_codvist.value = chave1;
  document.form1.y70_id_usuario.value = chave2;
  db_iframe_vistorias.hide();
}
function js_limpacampos(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      document.form1.elements[i].value = '';
    }
  }
}
function js_consultasani(){
  var vazio = 0;
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      if(document.form1.elements[i].value == ""){
        vazio = 1;
      }else{
	vazio = 0;
	break;
      }
    }
  }
  if(vazio == 1){
    alert('Preencha um dos campos para o relatório!');
    return false;
  }else{
    jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
}
function js_abreconsulta(chave){
  js_OpenJanelaIframe('','db_iframe_consulta','fis3_consultavist002.php?y70_codvist='+chave,'Pesquisa',true,15);
}

function js_mandadados(){
 vir="";
 listatipo="";
 for(x=0;x<document.form1.tipo.length;x++){
  listatipo+=vir+document.form1.tipo.options[x].value;
  vir=",";
 }
 vir="";
 listarua="";
 for(x=0;x<parent.iframe_g2.document.form1.rua.length;x++){
  listarua+=vir+parent.iframe_g2.document.form1.rua.options[x].value;
  vir=",";
 }
 vir="";
 listabairro="";
 for(x=0;x<parent.iframe_g3.document.form1.bairro.length;x++){
  listabairro+=vir+parent.iframe_g3.document.form1.bairro.options[x].value;
  vir=",";
 }
 jan = window.open('fis2_relatoriovist002.php?listatipo='+listatipo+'&listarua='+listarua+'&listabairro='+listabairro+'&datai='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&dataf='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value+'&vertipo='+document.form1.ver.value+'&verrua='+parent.iframe_g2.document.form1.ver.value+'&verbairro='+parent.iframe_g3.document.form1.ver.value+'&tiporel='+parent.iframe_g1.document.form1.tiporel.value+'&ordem='+document.form1.ordem.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>