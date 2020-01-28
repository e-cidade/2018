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
$clrotulo->label("q05_ano");
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
                <strong>Op��es:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com as Atividades selecionadas</option>
                    <option name="condicao1" value="sem">Sem as Atividades selecionadas</option>
                </select>
          </td>
       </tr>
      <tr >
        <td colspan=2 ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Atividades</strong>";
                 $aux->codigo = "q03_ativ"; //chave de retorno da func
                 $aux->descr  = "q03_descr";   //chave de retorno
                 $aux->nomeobjeto = 'ativ';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_ativid.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_ativid";
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
      <td>
      <b>De: </b> 
      <?
      $meses = array();
      for($w=1;$w<=12;$w++){
      	$mes=db_mes($w);      	
      	$meses[$w]= $mes;
      }
      db_select("mes_ini",$meses,true,"text",1);
      ?> 
      <b>de</b> 
      <?
      db_input("ano_ini",6,@$Iq05_ano,true,"text",1) ?>
      
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>At�: </b> 
      <?      
      db_select("mes_fim",$meses,true,"text",1);
      echo "<script>document.form1.mes_fim.value=12;</script>";
      ?> 
      <b>de</b> 
      <?
      db_input("ano_fim",6,@$Iq05_ano,true,"text",1) ?>
      
      </td>
      <td> </td>
      </tr>
      <tr>
        <td colspan="2" align = "center">
        <b>Mostrar:</b>
        <?
        $arr_mostra = array("t"=>"Todos","z"=>"Somente com valor zerado","l"=>"Somente com valor lan�ado");
        db_select("mostra",$arr_mostra,true,"text",1);
        ?>
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
 
 query="";
 vir="";
 listaativ="";
 
 for(x=0;x<document.form1.ativ.length;x++){
  listaativ+=vir+document.form1.ativ.options[x].value;
  vir=",";
 }
 
 vir="";
 listaclas="";
 for(x=0;x<parent.iframe_g2.document.form1.classe.length;x++){
  listaclas+=vir+parent.iframe_g2.document.form1.classe.options[x].value;
  vir=",";
 }
 
 query+='&listaativ='+listaativ+'&verativ='+document.form1.ver.value;
 query+='&listaclas='+listaclas+'&verclas='+parent.iframe_g2.document.form1.ver.value;
 query+='&mes_ini='+document.form1.mes_ini.value;
 query+='&mes_fim='+document.form1.mes_fim.value;
 query+='&ano_ini='+document.form1.ano_ini.value;
 query+='&ano_fim='+document.form1.ano_fim.value;
 query+='&mostra='+document.form1.mostra.value;
 
 jan = window.open('iss2_issvaraber002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0); 

}
</script>