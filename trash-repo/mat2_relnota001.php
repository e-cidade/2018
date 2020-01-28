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
                    <option name="condicao1" value="com">Com os Fornecedores selecionados</option>
                    <option name="condicao1" value="sem">Sem os Fornecedores selecionados</option>
                </select>
          </td>
       </tr>
      <tr >
        <td colspan=2 ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Fornecedores</strong>";
                 $aux->codigo = "e60_numcgm"; //chave de retorno da func
                 $aux->descr  = "z01_nome";   //chave de retorno
                 $aux->nomeobjeto = 'fornecedores';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_cgm_empenho.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_cgm";
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
        <td align='center'  colspan=2 >
               <b> Período do recebimento: </b>
               <? 
	          db_inputdata('data3','','','',true,'text',1,"");   		          
                  echo "<b> a</b> ";
                  db_inputdata('data4','','','',true,'text',1,"");
               ?>&nbsp;
	</td>
      </tr>
      <tr>
               <td colspan=2  align="left"  title="Opções  Todas/Liquidadas/Não liquidadas" >
               <strong>Opção de impressão :&nbsp;&nbsp;</strong>
	       <? 
	       $tipo_opcao = array("a"=>"Todas","b"=>"LIquidadas","c"=>"Não liquidadas");
	       db_select("opcao",$tipo_opcao,true,2); ?>
            </td>
	    </tr>
      <tr align="center" >
       <td colspan=2>
	</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
        </td>
      </tr>

  </form>
    </table>
</body>
</html>
<script>
function js_mandadados(){
 vir="";
 listafor="";
 for(x=0;x<document.form1.fornecedores.length;x++){
  listafor+=vir+document.form1.fornecedores.options[x].value;
  vir=",";
 }
 vir="";
 listaele="";
 for(x=0;x<parent.iframe_g2.document.form1.elementos.length;x++){
  listaele+=vir+parent.iframe_g2.document.form1.elementos.options[x].value;
  vir=",";
 }
 vir="";
 listausu="";
 for(x=0;x<parent.iframe_g3.document.form1.usuario.length;x++){
  listausu+=vir+parent.iframe_g3.document.form1.usuario.options[x].value;
  vir=",";
 }
 jan = window.open('mat2_empnota002.php?&listafor='+listafor+'&listaele='+listaele+'&listausu='+listausu+'&data='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&data1='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value+'&data2='+document.form1.data3_ano.value+'-'+document.form1.data3_mes.value+'-'+document.form1.data3_dia.value+'&data3='+document.form1.data4_ano.value+'-'+document.form1.data4_mes.value+'-'+document.form1.data4_dia.value+'&verfor='+document.form1.ver.value+'&verele='+parent.iframe_g2.document.form1.ver.value+'&verusu='+parent.iframe_g3.document.form1.ver.value+'&opcao='+document.form1.opcao.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>