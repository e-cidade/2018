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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$aux 							  = new cl_arquivo_auxiliar;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo           = new rotulocampo;

$clrotulo->label("v50_inicial");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >
<form class="container" name="form1" method="post" action="jur2_inicialnum002.php" >
  <fieldset>
    <legend>Relatórios - Iniciais - Inicial/Data</legend>
		<table class="form-container">
			<tr>
		 		<td title="<?=@$Tv50_inicial?>" >
          <?
            db_ancora(@$Lv50_inicial,"js_pesquisainicial(true,false);",4)
          ?>
				</td>		
				<td>		
			    <? db_input("inicial01",15,"",true,"text",4,"onchange='js_pesquisainicial(false,false);'");?>
			    &nbsp;				
          <? db_ancora("<b>até</b>","js_pesquisainicial(true,true);",4);?>
			    &nbsp;				
			    <? db_input("inicial02",16,"",true,"text",4,"onchange='js_pesquisainicial(false,false);'");?>
				</td>
			</tr>      
			<tr>
				<td>
					Período:
        </td>
        <td>
			    <? 
            $dia="01";
            $mes="01";
            $ano= db_getsession("DB_anousu");
            $dia2="31";
            $mes2="12";
            $ano2= db_getsession("DB_anousu");
            db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");   		          
			    ?>	 
			    <b>	&nbsp; até &nbsp;</b> 
          <?  
			      db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
          ?>
				</td>
			</tr>
			<tr>
        <td>
          Tipo:
        </td>
        <td>
          <?
            $aTipo = array("todos"=>"Todos","foro"=>"Processos do Foro","semforo"=>"Processos sem Foro");
            db_select("tipo",$aTipo,true,1,"");
          ?>
        </td>
      </tr>	
	    <tr>
        <td>
          Ativa/Anulada:
        </td>
        <td>
          <?
            $aSituacao = array("0"=>"Todas","1"=>"Ativa","2"=>"Anulada");
            db_select("selSituacao",$aSituacao,true,1,"");
          ?>
        </td>
      </tr>
	 	</table>							
  </fieldset>
  <input type="button" value="relatorio" onClick="js_seleciona()">
</form>
<!---  menu --->
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<!--- --->
<script>
variavel = 1;
function js_seleciona(){
   dt1 = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
   dt2 = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
   if (dt1 > dt2 ){
      alert(_M('tributario.juridico.jur2_inicialnum001.data_inicial_maior_data_final'));
      return false;
   }
  //--
  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);
  return true;
  
}
function js_pesquisainicial(mostra,fim){
if(mostra==true){
       if(fim==true){
         db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostratermofim|0';
       }else{
         db_iframe.jan.location.href = 'func_inicial.php?funcao_js=parent.js_mostratermo1|0';
       }
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
      // db_iframe.jan.location.href = 'func_inicial.php?pesquisa_chave='+document.form1.v50_inicial.value+'&funcao_js=parent.js_mostratermo';
     }
}
function js_mostratermo(chave,erro){
  if(erro==true){
     document.form1.inicial01.focus();
     document.form1.inicial01.value = '';
  }
}
function js_mostratermo1(chave1){
     document.form1.inicial01.value = chave1;
     db_iframe.hide();
}
function js_mostratermofim(chave1){
     document.form1.inicial02.value = chave1;
     db_iframe.hide();
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>

</body>
</html>


<script>
$("inicial01").style.width = "115px";
$("inicial02").style.width = "115px";
$("data1").addClassName("field-size2");
$("data2").addClassName("field-size2");

</script>