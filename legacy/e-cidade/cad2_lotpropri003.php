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
           <td   align="right">
                <strong>Opções:</strong>
           </td>
           <td> 
                <select name="ver">
                    <option name="condicao1" value="com">Com os Proprietários selecionados</option>
                    <option name="condicao1" value="sem">Sem os Proprietários selecionados</option>
                </select>
          </td>
       </tr>
       <tr> 
           <td   align="right">
                <strong>Filtrar por:</strong>
           </td>
           <td> 
                <select name="filtro">
                    <option name="filtro1" value="1">Proprietários da Escritura</option>
                    <option name="filtro1" value="2">Proprietários Reais (Promitentes)</option>
                </select>
          </td>
       </tr>
      <tr >
        <td colspan=2 ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Proprietários</strong>";
                 $aux->codigo = "z01_numcgm"; //chave de retorno da func
                 $aux->descr  = "z01_nome";   //chave de retorno
                 $aux->nomeobjeto = 'proprietario';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_nome.php";  //func a executar
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
      
      <tr align="center" >
       <td colspan=2>
         <table>
         <tr>
             <td align="right"  title="Tipo" >
               <strong>Ordenar por:&nbsp;&nbsp;</strong>
               </td>
               <td>
	       <? 
	       $tipo_ordem = array("pr"=>"Proprietário","ps"=>"Possuidor","m"=>"Matrícula","s"=>"Setor/Quadra/Lote");
	       db_select("ordem",$tipo_ordem,true,2); ?>
            </td>
	  </tr>
       <tr>
             <td align="right"  title="Tipo" >
               <strong>Listar:&nbsp;&nbsp;</strong>
               </td>
               <td>
	       <? 
	       $tipo_b = array("t"=>"Todas","b"=>"Baixadas","n"=>"Não Baixadas");
	       db_select("tipo1",$tipo_b,true,2); ?>
            </td>
	  </tr>
      <tr>
             <td align="right"  title="Tipo" >
               <strong>Listar:&nbsp;&nbsp;</strong>
               </td>
               <td>
           <? 
           $tipo_t = array("t"=>"Todas","pr"=>"Prediais","tr"=>"Territoriais");
           db_select("tipo2",$tipo_t,true,2); ?>
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

function js_mostra1(lErro,sNome){
  if(lErro){
    document.form1.z01_numcgm.value = "";
    document.form1.z01_nome.value   = "";
    document.form1.z01_numcgm.focus();
  }else{
    document.form1.z01_nome.value = sNome;
    document.form1.db_lanca.onclick = js_insSelectproprietario;
  }

}

function js_mandadados(){
 
 query="";
 vir="";
 lista="";
 
 for(x=0;x<document.form1.proprietario.length;x++){
  lista+=vir+document.form1.proprietario.options[x].value;
  vir=",";
 }
 setores = "";
 vir = "";   
 for(i=0;i<parent.iframe_g2.setor.document.form1.length;i++){
   if(parent.iframe_g2.setor.document.form1.elements[i].type == "checkbox"){
      if(parent.iframe_g2.setor.document.form1.elements[i].checked == true){
      valor = parent.iframe_g2.setor.document.form1.elements[i].value.split("_");      
      setores += vir + valor[0];
      vir = ",";           
    }
   }
 }
 quadras = "";
 vir = "";
 if (parent.iframe_g3.quadras){
 for(i=0;i<parent.iframe_g3.quadras.document.form1.length;i++){
   if(parent.iframe_g3.quadras.document.form1.elements[i].type == "checkbox"){
      if(parent.iframe_g3.quadras.document.form1.elements[i].checked == true){
      valor = parent.iframe_g3.quadras.document.form1.elements[i].value.split("_");      
      quadras += vir + valor[0];
      vir = ",";           
    }
   }
 }  
 }
 ruas = "";
 vir = "";
 if (parent.iframe_g4.ruas){
 for(i=0;i<parent.iframe_g4.ruas.document.form1.length;i++){
   if(parent.iframe_g4.ruas.document.form1.elements[i].type == "checkbox"){
      if(parent.iframe_g4.ruas.document.form1.elements[i].checked == true){
      valor = parent.iframe_g4.ruas.document.form1.elements[i].value.split("_");      
      ruas += vir + valor[0];
      vir = ",";           
    }
   }
 }  
 }
 
 query+='&lista='+lista+'&ver='+document.form1.ver.value;
 query+='&filtro='+document.form1.filtro.value;
 query+='&tipo1='+document.form1.tipo1.value;
 query+='&tipo2='+document.form1.tipo2.value;
 query+='&ordem='+document.form1.ordem.value;

 
 
 
 jan = window.open('cad2_lotpropri002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
 
}
</script>