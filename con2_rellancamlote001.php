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
include("dbforms/db_funcoes.php");
include("classes/db_conlancamdig_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clconlancamdig = new cl_conlancamdig;
$clconlancamdig->rotulo->label("c78_chave");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_imprimir() {
  var sel_instit = document.form1.db_selinstit.value;
  var lote       = document.form1.c78_chave.value;
  var data_ini   = document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
  var data_fim   = document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value;
  var data1      = new Date(document.form1.data1_ano.value,document.form1.data1_mes.value,document.form1.data1_dia.value,0,0,0);
  var data2      = new Date(document.form1.data2_ano.value,document.form1.data2_mes.value,document.form1.data2_dia.value,0,0,0);
  
  if(data1.valueOf() > data2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
  }
  
  if(sel_instit==0){
      alert('Você não escolheu nenhuma Instituição. Verifique!');
      return false;
  }
  else {
      jan = window.open('con2_rellancamlote002.php?db_selinstit='+sel_instit+'&c78_chave='+lote+'&data_ini='+data_ini+'&data_fim='+data_fim,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
      jan.moveTo(0,0);
      return true;
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conlancamdig','func_conlancamdig.php?funcao_js=parent.js_preenchepesquisa|c78_chave','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conlancamdig.hide();
  document.form1.c78_chave.value=chave;
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
       <form name="form1" method="post" action="">
      <table border="0" width="48%">
      <tr>
        <td align="center" colspan="3">
	<?
	db_selinstit('',300,100);
	?>
	</td>
      </tr>
      <tr><td colspan="2">&nbsp;</td></tr>
      <tr>
         <tr>
           <td nowrap align="left"><? db_ancora("$Lc78_chave",'js_pesquisa();',1); ?> </td>
           <td><?  db_input("c78_chave",15,"",true,'text',1); ?></td>
        </tr>
        <tr>
          <td nowrap align=right><b>Período</b> </td>
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