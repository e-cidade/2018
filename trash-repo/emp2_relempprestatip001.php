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
$aux = new cl_arquivo_auxiliar;
$rotulocampo = new rotulocampo;

$dtoper = date('Y-m-d',db_getsession("DB_datausu"));
$dtoper_dia = date('d',db_getsession("DB_datausu"));
$dtoper_mes = date('m',db_getsession("DB_datausu"));
$dtoper_ano = date('Y',db_getsession("DB_datausu"));

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_emite(){
  obj=document.form1;
  var datai = obj.datai_ano.value+'-'+obj.datai_mes.value+'-'+obj.datai_dia.value;
  var dataf = obj.dataf_ano.value+'-'+obj.dataf_mes.value+'-'+obj.dataf_dia.value;
  vir="";
  dados="";
  query="";
  if (document.form1.lista){
    for(x=0;x<document.form1.lista.length;x++){
      dados+=vir+document.form1.lista.options[x].value;
      vir=",";
    }
  }
  if (dados!=""){
    query+='&dados='+dados;
  }
  
  jan = window.open('emp2_relempprestatip002.php?dataini='+datai+'&datafin='+dataf+'&tipoemp='+obj.tipoemp.value+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
////  jan = window.open('pes2_contra_cheque.php?opcao='+document.form1.folha.value+'&ano='+document.form1.DBtxt23.value+'&mes='+document.form1.DBtxt25.value+'&filtro='+document.form1.filtro.value+'&msg='+document.form1.mensagem.value+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}




</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">
	    <tr>
               <td width="25">&nbsp;</td>
               <td width="140">&nbsp;</td>
	    </tr>
            <tr> 
              <td align="right" nowrap><strong>Data inicial:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;&nbsp; 
                <?=db_data("datai",'01',$dtoper_mes,$dtoper_ano)?>
              </td>
            </tr>


            <tr> 
              <td align="right" nowrap><strong>Data final:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;&nbsp; 
                <?=db_data("dataf",$dtoper_dia,$dtoper_mes,$dtoper_ano)?>
              </td>
            </tr>
      <tr >
        <td colspan=2 ><?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Tipo de Empenho</strong>";
                 $aux->codigo = "e40_codhist"; //chave de retorno da func
                 $aux->descr  = "e40_descr";   //chave de retorno
                 $aux->nomeobjeto = 'lista';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_emphist.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_lista";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 5;
                 $aux->vwhidth = 200;
                 $aux->funcao_gera_formulario();
        	?>
       </td>
      </tr>
      <tr >
	<td align="right"> <strong>Opção : </strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
            $xx = array("l"=>"Liquidado","e"=>"Empenhado","p"=>"Pago");
            db_select('tipoemp',$xx,true,4,"");
	  ?>
	</td>
      </tr>
	    <tr>
               <td width="25">&nbsp;</td>
               <td width="140">&nbsp;</td>
	    </tr>

            <tr> 
              <td colspan = "2" align="center" > 
	          <input name="emite" type="button" id="emite" onClick="js_emite()" value="Emite"> &nbsp; &nbsp;&nbsp; &nbsp;
	      </td>
            </tr>
          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>