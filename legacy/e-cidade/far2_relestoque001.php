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
         <td align='right' ></td>
         <td ></td>
      </tr>
       <tr> 
           <td colspan=2  align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os depositos selecionados</option>
                    <option name="condicao1" value="sem">Sem os depositos selecionados</option>
                </select>
          </td>
       </tr>
      <tr >
        <td colspan=2 ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->Labelancora = "Depósito";
                 $aux->cabecalho = "<strong>Depósitos</strong>";
                 $aux->codigo = "coddepto"; //chave de retorno da func
                 $aux->descr  = "descrdepto";   //chave de retorno
                 $aux->nomeobjeto = 'depositos';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_db_almoxdepto.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_db_depart";
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
      <tr align="center" >
       <td colspan=2>
         <table>
         <tr>
             <td align="right"  title="Quebra por depósito" >
               <strong>Quebra por depósito :&nbsp;&nbsp;</strong>
	       <? 
	       $tipo_que = array("N"=>"Não","S"=>"Sim");
	       db_select("quebra",$tipo_que,true,2,"onchange='js_testord(this.value);'"); ?>
            </td>
	  </tr>
	   <tr>
             <td align="right"  title="Ordem por  Codigo/Depósito/Medicamento" >
               <strong>Ordem :&nbsp;&nbsp;</strong>
	       <? 
	       $tipo_ordem = array("c"=>"Alfabética","a"=>"Codigo","b"=>"Depósito");
	       db_select("ordem",$tipo_ordem,true,2); ?>
            </td>
	  </tr>
	  <tr>
             <td align="right"  title="Estoque Zerado" >
               <strong>Listar estoque zerado :&nbsp;&nbsp;</strong>
	       <? 
	       $tipo_est = array("N"=>"Não", "S"=>"Sim");
	       db_select("list_zera",$tipo_est,true,2); ?>
            </td>
	  </tr>
	  <tr>
             <td align="right"  title="Tipo" >
               <strong>Tipo:&nbsp;&nbsp;</strong>
	       <? 
	       $tipo_rel = array("S"=>"Sintético","A"=>"Analítico","C"=>"Conferência");
	       db_select("tipo",$tipo_rel,true,2); ?>
            </td>
	  </tr>
	</table>
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
function js_testord(valor){	
	if (valor=='S'){
		document.form1.ordem.value='b';
		document.form1.ordem.disabled=true;
	}else{
		document.form1.ordem.value='a';
		document.form1.ordem.disabled=false;
	}
}
function js_mandadados(){
 
 query="";
 vir="";
 listadepart="";
 
 for(x=0;x<document.form1.depositos.length;x++){
  listadepart+=vir+document.form1.depositos.options[x].value;
  vir=",";
 }
 
 vir="";
 listamat="";
 for(x=0;x<parent.iframe_g2.document.form1.material.length;x++){
  listamat+=vir+parent.iframe_g2.document.form1.material.options[x].value;
  vir=",";
 }
 
 query+='&listadepart='+listadepart+'&verdepart='+document.form1.ver.value;
 query+='&listamat='+listamat+'&vermat='+parent.iframe_g2.document.form1.ver.value;
 query+='&ordem='+document.form1.ordem.value;
 query+='&tipo='+document.form1.tipo.value;
 query+='&zera='+document.form1.list_zera.value;
 query+='&quebra='+document.form1.quebra.value;
 query+='&opcao_material='+parent.iframe_g2.document.form1.opcao_material.value;
 
 jan = window.open('far2_medestoque002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
 
}
</script>