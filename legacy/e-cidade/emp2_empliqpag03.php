<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

$cliframe_seleciona = new cl_iframe_seleciona;
$aux = new cl_arquivo_auxiliar;

db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_imprime(){
 vir="";
 listacredor="";
 for(x=0;x<document.form1.credor.length;x++){
  listacredor+=vir+document.form1.credor.options[x].value;
  vir=",";
 }
// document.form1.listacredor.value = listacredor;
  
  var obj=document.form1;
  var query='1=1';
  var data1 = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
  var data2 = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
  if(data1.valueOf() > data2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
  }
 query = parent.iframe_filtro.js_pesquisa(); 
// alert(query);
 jan = window.open('emp2_empliqpag02.php?pag='+document.form1.pag.checked+
                                            '&liq='+document.form1.liq.checked+
					    '&emp='+document.form1.emp.checked+
					    '&ordem='+document.form1.ordem.value+
					    '&com_mov='+document.form1.com_mov.value+
					    '&rp='+document.form1.rp.value+
					    '&mostraritem='+document.form1.mostraritem.value+
					    '&com_ou_sem='+document.form1.com_ou_sem.value+
					    '&listacredor='+listacredor+
					    '&dataini='+document.form1.data1_ano.value+'-'
					               +document.form1.data1_mes.value+'-'
					               +document.form1.data1_dia.value+
					    '&datafin='+document.form1.data2_ano.value+'-'
					               +document.form1.data2_mes.value+'-'
					               +document.form1.data2_dia.value+'&'
						       +query
					    ,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
  
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post">
<table border='0'>
  <tr>
       <table border="0" >
       <tr> 
           <td align="center">
                <strong>Opções:</strong>
                <select name="com_ou_sem">
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
     </tr>
     <table> 
      <tr>
          <td nowrap align="left" colspan=3>
               <b> Período </b>
               <? 
	          $dia="01";
		  $mes="01";
		  $ano= db_getsession("DB_anousu");
		  $dia2="31";
		  $mes2="12";
		  $ano2= db_getsession("DB_anousu");
	          db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");   		          
                  echo " a ";
                  db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
               ?>
          </td>
       </tr>
           <td nowrap align="left">
                    <b> Ordem:</b>
                    <select name="ordem">
                      <option name="ordem" value="e">Empenho </option> 
                      <option name="ordem" value="d">Data Empenho</option>
                      <option name="ordem" value="l">Data Lançamento</option>
                      <option name="ordem" value="t">Tipo</option>
                      <option name="ordem" value="v">Valor</option>
                      <option name="ordem" value="c">Credor</option>
                    </select>
	       <br>
               <b> Mostrar Históricos: </b>
               <select name="com_mov">
                    <option name="com_mov" value="s">Sim</option>
                    <option name="com_mov" value="n">Não</option>
               </select><br>
               <b> Mostrar Itens: </b>
               <select name="mostraritem">
                    <option name="mostraritem" value="m">Sim</option>
                    <option name="mostraritem" value="n">Não</option>
               </select><br>
               <b>  Lista RP: </b>
               <select name="rp">
                    <option name="rp" value="s">Sim</option>
                    <option name="rp" value="n">Não</option>
               </select><br>
           </td> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <td> 
<!--             <b>
	      Totaliza&ccedil;&atilde;o por:</b><br>-->
	      <input type="checkbox" name="emp" checked >Empenhado<br>
	      <input type="checkbox" name="liq" checked >Liquidado<br>
              <input type="checkbox" name="pag" checked >Pago<br>
	   </td>
           <td colspan="3" align="center">
             <input name="Imprimir" type="button" onclick='js_imprime();'  value="Pesquisa">
<!--             <input name="limpa" type="button" onclick='js_limpa();'  value="Limpar campos">-->
           </td>
       </tr> 
    </table>
  </table>
  </form>
</center>
</body>
</html>