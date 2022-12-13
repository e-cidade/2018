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
         <td align='right' ><b>  Ordem de compra &nbsp;</b></td>
         <td ><b>Inicial:</b><?db_input('m51_codordem','5','',true,'text',1,'','m51_codordemINI')?><b>Final:</b><?db_input('m51_codordem','5','',true,'text',1,'','m51_codordemFIN')?>&nbsp;</td>
      </tr>
       <tr> 
           <td colspan=2  align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os departamentos selecionados</option>
                    <option name="condicao1" value="sem">Sem os departamentos selecionados</option>
                </select>
          </td>
       </tr>
      <tr >
        <td colspan=2 ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Departamentos</strong>";
                 $aux->codigo = "coddepto"; //chave de retorno da func
                 $aux->descr  = "descrdepto";   //chave de retorno
                 $aux->nomeobjeto = 'departamentos';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_db_depart.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_db_depart";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 7;
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
      <tr align="center" >
       <td colspan=2>
         <table>
	   <tr>
             <td align="right"  title="Ordem por  Ordem de compra/Departamento/Cgm/Data" >
               <strong>Ordem :&nbsp;&nbsp;</strong>
	       <? 
	       $tipo_ordem = array("a"=>"Ordem de Compra","b"=>"Departamento","c"=>"Cgm","d"=>"Data");
	       db_select("ordem",$tipo_ordem,true,2); ?>
        
               <td align="left"  title="Opções  Não Anuladas/Todas/Anuladas" >
               <strong>Opção de impressão :&nbsp;&nbsp;</strong>
	       <? 
	       $tipo_opcao = array("b"=>"Não Anuladas","a"=>"Todas","c"=>"Anuladas");
	       db_select("opcao",$tipo_opcao,true,2); ?>
            </td>
	  </tr>
	</table>
	</td>
      </tr>
      <table   align = "center">
      <tr>
        <td  align = "left"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
        </td>
           <td>
             <b>
	      Totaliza&ccedil;&atilde;o por:</b><br>
	      <input type="checkbox" name="depart" value="d" checked >Departamentos<br>
	      <input type="checkbox" name="forn" value="f"    checked >Fornecedor<br>
              <input type="checkbox" name="itens" value="i"  checked >Itens<br>
	   </td>
      </tr>
      </table>

  </form>
    </table>
</body>
</html>
<script>
function js_mandadados(){
 vir="";
 listadepart="";
 for(x=0;x<document.form1.departamentos.length;x++){
  listadepart+=vir+document.form1.departamentos.options[x].value;
  vir=",";
 }
 vir="";
 listacgm="";
 for(x=0;x<parent.iframe_g2.document.form1.cgm.length;x++){
  listacgm+=vir+parent.iframe_g2.document.form1.cgm.options[x].value;
  vir=",";
 }
 vir="";
 listaitens="";
 for(x=0;x<parent.iframe_g3.document.form1.itens.length;x++){
  listaitens+=vir+parent.iframe_g3.document.form1.itens.options[x].value;
  vir=",";
 }
 jan = window.open('emp2_relordemcompra002.php?&listadepart='+listadepart+'&listacgm='+listacgm+'&listaitens='+listaitens+'&data='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&data1='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value+'&verdepart='+document.form1.ver.value+'&vercgm='+parent.iframe_g2.document.form1.ver.value+'&veritens='+parent.iframe_g3.document.form1.ver.value+'&ordem='+document.form1.ordem.value+'&opcao='+document.form1.opcao.value+'&m51_codordemINI='+document.form1.m51_codordemINI.value+'&m51_codordemFIN='+document.form1.m51_codordemFIN.value+'&depart='+document.form1.depart.checked+'&forn='+document.form1.forn.checked+'&itens='+document.form1.itens.checked,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>