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
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_empempenho_classe.php");
include("classes/db_conhistdoc_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clempempenho = new cl_empempenho;
$aux = new cl_arquivo_auxiliar;
$clconhistdoc = new cl_conhistdoc;

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
<script>
variavel = 1;
function js_imprimir() {
  var data1 = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
  var data2 = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
  if(data1.valueOf() > data2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
  }
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].name == "lista[]"){
      for(x=0;x< document.form1.elements[i].length;x++){
        document.form1.elements[i].options[x].selected = true;
      }
    }
  }
  if( document.form1.lista.length == 0 && document.form1.c60_descr != ''){
    js_insSelectlista();
  }
  retorno = true;
  if ( document.form1.lista.length == 0 ){
    retorno = confirm('Você não selecionou contas, gerar todas?');
  }
  
  if( retorno == true ){
      jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
      document.form1.target = 'safo' + variavel++;
      setTimeout("document.form1.submit()",1000);
      return true;
  }
}

</script>

</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>  
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
       <form name="form1" method="post" action="con2_razaocontas002.php" >
			 <br>
      <table>
			<tr>
			 <td>
			  <fieldset>
					 <table>
       <tr>
        <td nowrap align=right>
           <b>Período:</b>
	     </td>
	     <td nowrap align=left>
          <? 
	         $dia=  date("d",db_getsession("DB_datausu"));
		       $mes=  date("m",db_getsession("DB_datausu"));
		       $ano=  date("Y",db_getsession("DB_datausu"));
		       $dia2= date("d",db_getsession("DB_datausu"));
		       $mes2= date("m",db_getsession("DB_datausu"));
		       $ano2= date("Y",db_getsession("DB_datausu"));
	         db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");   		          
           echo " a ";
           db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
          ?>
       </td>
     </tr>
     <tr>
       <td align=right><b>Tipo de documento:</b></td>
	     <td align=left nowrap>   
	     <?    
		     $res = $clconhistdoc->sql_record($clconhistdoc->sql_query_file(null,"c53_coddoc,c53_descr","c53_coddoc"));
		     db_selectrecord("c53_coddoc",$res,'true',1,"","","","0");
		   ?>  
	     </td>
	  </tr>
     <tr>
        <td nowrap align=right>
            <b>Tipo:</b>
	    </td>
	     <td align=left>    
            <select name="relatorio">
                <option name="relatorio" value="a">Analitico </option>
                <option name="relatorio" value="s">Sintético </option>                    
            </select>
         </td>
      </tr>
	    <tr>
       <td nowrap align=right>
	       <b>Imprime Contrapartida:</b>
	     </td>
	      <td align=left>
	        <input type=checkbox name=contrapartida checked>	      
        </td>
			</tr>	
      <tr>          
            <td nowrap align=right>
 	         <b>Imprime saldo por dia:</b>
	      </td>
	       <td align=left>
	           <input type=checkbox name=saldopordia >
          </td>
       </tr>       
     
     <tr>
         <td nowrap align=right>
            <b>Imprime conta sem movimento:</b>
         </td>
         <td align=left>
         <input type=checkbox name=contasemmov >
        </td>
    </tr>
     
     <tr>
         <td align = "right"><strong> Estrutural: </strong></td><td>
         <input type=text name=estrut_inicial size=15 maxlength=10> 
         </td>
       </tr>
     <tr>
        <td nowrap align=right>
            <b>Quebrar página por conta:</b>
	    </td>
	     <td align=left>    
            <select name="quebrapaginaporconta">
                <option  value="s">Sim </option>
                <option  value="n">Não </option>                    
            </select>
         </td>
      </tr>
		  </table>
			</fieldset>
			</tr>
       <tr>
          <td width='100%'>
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Contas</strong>";
                 $aux->codigo = "c62_reduz"; //chave de retorno da func
                 $aux->descr  = "c60_descr";   //chave de retorno
                 $aux->nomeobjeto = 'lista';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
	               $aux->func_arquivo = "func_conplanoexe.php";
                 $aux->nomeiframe = "db_iframe_conplanoreduz";
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
       </table>
			</td></tr>
           <tr>
              <td colspan=2 align=center>
               <input type="button" id="emite" value="Emite" onClick="js_imprimir()">
              </td>
	   </tr>

       </tr> 
       </table>
       </center>
       </form>

    </td>
  </tr>
</table>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>

  </body>
</html>