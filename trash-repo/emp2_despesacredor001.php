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
$cllote = new cl_lote;
$aux = new cl_arquivo_auxiliar;

$clrotulo = new rotulocampo;

$cliframe_seleciona = new cl_iframe_seleciona;

//--- cria rotulos e labels
$clempempenho->rotulo->label();
$cllote->rotulo->label();
$clrotulo->label("z01_nome");
//----

//----

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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <form name="form1" method="post" action="emp2_despesamaterialdesd002.php">
      <table border="0">
        <tr>         
          <?
          db_input('fornecedores',10,"",true,"hidden",1);
          db_input('mostrafornec',10,"",true,"hidden",1);
          db_input('tipodecompra',10,"",true,"hidden",1);
          db_input('mostracompra',10,"",true,"hidden",1);
          db_input('instit'      ,10,"",true,"hidden",1);
          db_input('orgao'       ,10,"",true,"hidden",1);
          db_input('unidade'     ,10,"",true,"hidden",1);
          db_input('funcao'      ,10,"",true,"hidden",1);
          db_input('subfuncao'   ,10,"",true,"hidden",1);
          db_input('programa'    ,10,"",true,"hidden",1);
          db_input('projativ'    ,10,"",true,"hidden",1);
          db_input('ele'         ,10,"",true,"hidden",1);
          db_input('recurso'     ,10,"",true,"hidden",1);
          db_input('depart'      ,10,"",true,"hidden",1);
          db_input('usuario'     ,10,"",true,"hidden",1);
          ?>
          <td align="center">
            <strong>Opções:</strong>
            <?
            $opcoes = Array("com"=>"Com os credores selecionados","sem"=>"Sem os credores selecionadas");
            db_select("ver",$opcoes,true,1);
            ?>
            <!--
            <select name="ver">             
              <option name="condicao1" value="com">Com os credores selecionados</option>
             <option name="condicao1" value="sem">Sem os credores selecionadas</option>
            </select>
            -->
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
            $aux->linhas = 10;
            $aux->vwhidth = 400;
            $aux->funcao_gera_formulario();
            ?>    
          </td>
        </tr>
      </table>
      <?
      $resultmin = pg_exec("select e54_emiss from empautoriza where e54_anulad is null order by e54_emiss limit 1");
      if (pg_numrows($resultmin) > 0) {
	      db_fieldsmemory($resultmin,0);
      }
      $dia=substr($e54_emiss,8,2);
      $mes=substr($e54_emiss,5,2);
      $ano=substr($e54_emiss,0,4);
      $dia2=date("d",db_getsession("DB_datausu"));
      $mes2=date("m",db_getsession("DB_datausu"));
      $ano2= db_getsession("DB_anousu");
      ?>
      <table border="0" width="48%">
        <tr>
          <td nowrap align="right"><b>Emissão autorização:</b></td>
          <td nowrap align="left">
            <? 
	        db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");
            echo " a ";
            db_inputdata('data11',@$dia2,@$mes2,@$ano2,true,'text',1,"");
            ?>       
          </td>
        </tr>
        <tr>	
	      <td align="right" valign="top" nowrap>
	        <b>Autorizações / Empenhos:</b><br><br>
	        <input type="checkbox" name="mostrafil" value="mostrafil" checked>
	      </td>
	      <td align="left" valign="top">
	        <?
	        $opcoes = Array(
                            "1" => "Todos",
                            "2" => "Não empenhados",
                            "3" => "Somente empenhados",
                            "4" => "Com saldo a pagar geral",
                            "5" => "Com saldo a pagar liquidados",
                            "6" => "Com saldo a pagar não liquidados",
                            "7" => "Com anulação lançada",
                            "8" => "Parcialmente anulados",
                            "9" => "Totalmente anulados",
                            "10"=> "Sem anulação",
                            "11"=> "Parcialmente liquidados",
                            "12"=> "Totalmente liquidados",
                            "13"=> "Sem liquidação",
                            "14"=> "Parcialmente pagos",
                            "15"=> "Totalmente pagos",
                           );
            db_select("filtro",$opcoes,true,1);
            ?>
            <br><br>
            <b>Usar filtro</b>
          </td>
 	      <td align="left" colspan="2" nowrap>
	        <input type="checkbox" name="mdesdobra" value="mdesdobra" onclick="js_marcar(1);">Listar desdobramentos<br>
	        <input type="checkbox" name="mempenhos" value="mempenhos" onclick="js_marcar(2);">Listar empenhos<br>
	        <input type="checkbox" name="mmaterial" value="mmaterial" onclick="js_marcar(3);">Listar materiais / serviços<br>
	        <input type="checkbox" name="soreserva" value="soreserva">Autorizações com saldo reservado
          </td>
        </tr>
        <tr>
          <td colspan="3" align="center">
            <input type="button" value="relatorio" onClick="js_emite()"></center>
          </td>
        <tr>
      </table>
      </center>
      </form>
    </td>
  </tr>
</table>
<script>
function js_marcar(opcao){
	if(opcao == 1){
		if(document.form1.mdesdobra.checked == false){
			document.form1.mmaterial.checked = false;
			document.form1.mempenhos.checked = false;
		}
	}else{
		if(document.form1.mmaterial.checked == true || document.form1.mempenhos.checked == true){
			document.form1.mdesdobra.checked = true;
		}
	}
}
function js_emite(){
  vir="";
  listacredor="";
  for(x=0;x<document.form1.credor.length;x++){
    listacredor+=vir+document.form1.credor.options[x].value;
    vir=",";
  }
  document.form1.fornecedores.value = listacredor;
  
  
  vir="";
  listahist="";
  for(x=0;x<parent.iframe_g2.document.form1.tipocom.length;x++){
    listahist+=vir+parent.iframe_g2.document.form1.tipocom.options[x].value;
    vir=",";
  }

  document.form1.tipodecompra.value = listahist;

  document.form1.mostrafornec.value = document.form1.ver.value;
  document.form1.mostracompra.value = parent.iframe_g2.document.form1.ver.value;
  
  // pega dados da pes2_despesaopcoes001.php
  if(document.form1.mostrafil.checked == true){
    parent.iframe_filtro.js_atualiza_variavel_retorno();
  }
  variavel = 1;

  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=0,location=0 ');
  document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);

  /*
  document.form1.target = 'safo'+variavel;
  jan = window.open('','safo'+variavel+1,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',0,0');
//  jan = window.open('','safo','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',fullscreen=0,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0);
 
  document.form1.submit();
  */
  return true;
}
</script>

  </body>
</html>
