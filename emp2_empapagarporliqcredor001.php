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
include("libs/db_usuariosonline.php");
include("libs/db_liborcamento.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empempenho_classe.php");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clempempenho = new cl_empempenho;
$clselorcdotacao = new cl_selorcdotacao;
$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$clempempenho->rotulo->label();

//----
//----
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

if (!isset($testdt)){
  $testdt='sem';
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name="form1" method="post" action="emp2_empapagarporliqcredor002.php">
         <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >
       <table border="0" >
       <tr> 
       <?
       db_input('testdt',10,"",true,"hidden",1);
       db_input('listacredor',10,"",true,"hidden",1);
       db_input('listahist',10,"",true,"hidden",1);
       db_input('listacom',10,"",true,"hidden",1);
       db_input('datacredor',10,"",true,"hidden",1);
       db_input('datacredor1',10,"",true,"hidden",1);
       db_input('dataesp1',10,"",true,"hidden",1);
       db_input('dataesp2',10,"",true,"hidden",1);
       db_input('hist',10,"",true,"hidden",1);
       db_input('mostraritem',10,"",true,"hidden",1);
       db_input('mostrarobs',10,"",true,"hidden",1);
       db_input('mostralan',10,"",true,"hidden",1);
       db_input('agrupar',10,"",true,"hidden",1);
	 
       ?>
           <td align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os credores selecionados</option>
                    <option name="condicao1" value="sem">Sem os credores selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%">
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Credores</strong>";
                 $aux->codigo = "e60_numcgm"; //chave de retorno da func
                 $aux->descr  = "z01_nome";   //chave de retorno
                 $aux->nomeobjeto = 'credor';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_cgm_empenho.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_cgm";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 1;
                 $aux->linhas = 4;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
              ?>    
          </td>
       </tr>
       </table>
      <table border="0" width="48%">
      <tr>
	 <td align="center">
	      <strong>Opções:</strong>
	      <select name="tipo">
		  <option name="tipo1" value="todos">Todos</option>
		  <option name="tipo2" value="doexerc">Do exercício</option>
		  <option name="tipo3" value="rps">RP's</option>
	      </select>
	</td>
      </td>
      <tr>
         <td nowrap> <b>Período das liquidações:</b></td>
	 <td nowrap>
               <? 
//	       $resultmin = pg_exec("select c71_data as e60_emiss from conlancamdoc where c71_coddoc in (3, 23, 33) order by c71_data limit 1");
	       $resultmin = pg_exec("select c71_data as e60_emiss from conlancamdoc where c71_coddoc in (3, 23, 33) order by c71_data limit 1");
	       if ( pg_numrows($resultmin) > 0 ){
           db_fieldsmemory($resultmin,0);

	         $dia=substr($e60_emiss,8,2);
	         $mes=substr($e60_emiss,5,2);
	         $ano=substr($e60_emiss,0,4);
         }else{
           $dia=date("d",db_getsession("DB_datausu"));
	         $mes=date("m",db_getsession("DB_datausu"));
	         $ano= db_getsession("DB_anousu");

         }
	       $dia2=date("d",db_getsession("DB_datausu"));
	       $mes2=date("m",db_getsession("DB_datausu"));
	       $ano2= db_getsession("DB_anousu");
	       db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");
               echo " a ";
               db_inputdata('data11',@$dia2,@$mes2,@$ano2,true,'text',1,"");
              ?>       
          </td>
       </tr>
       <!-- fim linha -->
       <tr>
           <td colspan="2" align="center"><br>
               <input type="button" value="relatorio" onClick="js_emite()"></center>
	       <input  name="orgaos" id="orgaos" type="hidden" value="" >
	       <input  name="vernivel" id="vernivel" type="hidden" value="" >
           </td>
       </tr>

       
   </table>
   </center>
   </form>

    </td>
  </tr>
</table>
<script>

variavel = 1;

function js_emite(){

 vir="";
 listacredor="";
 for(x=0;x<document.form1.credor.length;x++){
  listacredor+=vir+document.form1.credor.options[x].value;
  vir=",";
 }
 document.form1.listacredor.value = listacredor;
 vir="";
 listahist="";

 document.form1.listacom.value = document.form1.listacom.value;
 document.form1.tipo.value = document.form1.tipo.value;
 // carlos não abria sem essa informação, conferir no cvs...

 document.form1.datacredor.value=document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
 document.form1.datacredor1.value=document.form1.data11_ano.value+'-'+document.form1.data11_mes.value+'-'+document.form1.data11_dia.value;

 // pega dados da func_selorcdotacao_aba.php
 document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
 jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 document.form1.target = 'safo' + variavel++;
 setTimeout("document.form1.submit()",1000);
 return true;
}

</script>

  </body>
</html>
