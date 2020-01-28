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
include("libs/db_usuariosonline.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empempenho_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempempenho = new cl_empempenho;
$aux = new cl_arquivo_auxiliar;
$clempempenho->rotulo->label();
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

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
<center>
<table align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
       <form name="form1" method="post" action="pro2_procdepart002.php">
       <fieldset>
       <legend><strong>Relatório Processos/Departamento</strong></legend>
       
       <table border="0" >
       <tr> 
           <td align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao" value="com">Com os Departamentos selecionados</option>
                    <option name="condicao" value="sem">Sem os Departamentos selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%">
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Departamentos</strong>";
                 $aux->codigo = "coddepto"; //chave de retorno da func
                 $aux->descr  = "descrdepto";   //chave de retorno
                 $aux->nomeobjeto = 'lista';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_db_depart.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_depart";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 5;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
              ?>    
          </td>
       </tr>
       </table>
      <table border="0" width="50%" cellspacing="3" cellpadding="3">
      <tr>
          <td nowrap colspan=2>
               <b> Período </b>
               <? 
		  $ano= db_getsession("DB_anousu");
		  $dia2="31";
		  $mes2="12";
		  $ano2= db_getsession("DB_anousu");
    	          list($mes,$dia)= split("-",date("m-d")); 
	          db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");   		          
                  echo " a ";
                  db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
               ?>
          </td>
       </tr>
       <tr>
           <td nowrap >
               <b> ORDEM : </b>
               <select name="ordem">
                    <option name="ordem" value="p58_codproc">PROCESSO </option>
                    <option name="ordem" value="p58_numcgm">CGM </option>
               </select>
           </td>     
	   <td >
	     <? 
	     //	 $matriz=array("com"=>"Com Andamentos","sem"=>"Sem Andamentos");    
	     //  db_select('andamento',$matriz,"",1); 
	     ?>
           </td>
       </tr> 
       <tr>
         <td nowrap colspan="2">
	  <b>Seleção</b>
	     <? 
	      $matriz=array("1"=>"Processos iniciados no departamento","2"=>"Processos que estao no departamento(Ultimo andamento)","3"=>"Processos sem tramite inicial");
	      db_select('tipo',$matriz,"",1); 
	     ?>
      </td>
      </tr>
      </table>
      </fieldset>
      <p align="center">
        <input type="button" value="Relatório" onClick="js_seleciona()">
      </p>
      </form>

    </td>
  </tr>
</table>
</center>
<!---  menu --->
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<!--- --->
<script>
variavel = 1;
function js_seleciona(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].name == "lista[]"){
      for(x=0;x< document.form1.elements[i].length;x++){
        document.form1.elements[i].options[x].selected = true;
      }
    }
  }
  //--
   dt1 = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
   dt2 = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
   if (dt1 > dt2 ){
      alert('Data inicial não pode ser maior que a Data final ! ');
      return false;
   }
  //--
  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);
  return true;
  
}
</script>

  </body>
</html>