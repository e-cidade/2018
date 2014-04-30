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
include("libs/db_liborcamento.php");
include("classes/db_orcsuplemtipo_classe.php");

$clorcsuplemtipo = new cl_orcsuplemtipo;


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

variavel = 1;
function js_emite(){
  vTipos ='';
  vSp='';
  Nelements = document.form1.length; // pega a quantidade de elemtnos do formulario
  for (x=0;x <Nelements;x++){ 
       obj = document.form1.elements[x];
       if (obj.type=='checkbox'){
           if (obj.checked==true) {
               vTipos += vSp+obj.name;
               vSp=',';
           }
       }
  } 
  document.form1.vTipos.value = vTipos;
  // alert(vTipos);
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  } else {
    document.form1.instituicao.value=document.form1.db_selinstit.value;
  }
  // pega dados da func_selorcdotacao_aba.php
  document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);

  return true;

}
function js_inverte(){
   // inverte as marcações dos checkboxes da tela   
   //
   Nelements = document.form1.length; // pega a quantidade de elemtnos do formulario
   for (x=0;x <Nelements;x++){ 
       obj = document.form1.elements[x];
       if (obj.type=='checkbox'){
           // alert ( obj.name + ' ' + obj.checked);
           if (obj.checked==true) 
               obj.checked=false;
           else 
               obj.checked=true;    
       }
   } 
}

</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <br><br>
    
  <table align="center"  border=0 style="border:1px solid #8D8D8D" cellpadding=2>
  <form name="form1" method="post" action="orc2_suplementacao002.php">
         <input  name="instituicao" type="hidden" value="" >
         <input  name="vernivel" id="vernivel" type="hidden" value="" >
         <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >
         <input  name="vTipos" type="hidden" value="" >
   <tr>
      <td colspan=2 ><h3> Relatório de Suplementações</h3></td>
      <td rowspan=1 ><h4> Tipo de Suplementação</h4></td>
   </tr>
   <tr>
         <td align="center" colspan="2">
         <?
           db_selinstit('',300,150);
         ?>
         </td>
         <td rowspan=7 valign="top"> 
	   <fieldset>
	    <a href="#" onclick="js_inverte();">Inverte Marcação</a><br>
	   <?
	    $res = $clorcsuplemtipo->sql_record($clorcsuplemtipo->sql_query_file(null,"o48_tiposup,o48_descr","o48_tiposup"));
	    if ($clorcsuplemtipo->numrows > 0 ){
	        for($x=0;$x< $clorcsuplemtipo->numrows;$x++){
		        db_fieldsmemory($res,$x); 
		        ?><input type=checkbox name=<?=$o48_tiposup?> checked><?=$o48_tiposup?>  <?=$o48_descr?><br><?
     	    }		
	    }
	   ?>
	   </fieldset>
	 </td>
      </tr>
  <tr>
    <td nowrap><b>  Período inicial  </b></td>
      <td colspan="1">
         <?
	   $data_ini_dia = '01';
	   $data_ini_mes = '01';
           $data_ini_ano = db_getsession("DB_anousu"); 	 
	   db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1);  
	   
	   ?>
      </td>
   </tr>
  <tr>
  <td nowrap><b>  Período final </b></td>
      <td colspan="1">
	  <?
	   $data_fim_dia = date('d',db_getsession("DB_datausu"));
	   $data_fim_mes = date('m',db_getsession("DB_datausu"));
	   $data_fim_ano = db_getsession("DB_anousu");
	   db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',1);  
	    
	   ?>
      </td>
  </tr>
 
 <tr>
      <td><b>Tipo: </b></td>
      <td>
        <?
          $matriz['todos'] ='Todos'; 
          $matriz['decreto'] ='Decreto';          
          $matriz['lei'] ='Lei';
          db_select('tipo',$matriz,"","");
        ?>     
      </td>
   </tr>
 
 
  <tr>
      <td colspan="1"><b>Suplementações: </b></td>
      <td>  
         <select name=processados>
           <option value="1"> Processadas  </option>
           <option value="2"> Não Processadas </option>
           <option value="3"> Todas </option>
        </select>
     </td>
   </tr>

  <tr>
      <td><b>Imprime Filtro: </b></td>
      <td>
        <?
          $matriz = array();
          $matriz['n'] ='Não'; 
          $matriz['s'] ='Sim';          
          db_select('imprime_filtro',$matriz,"","");
        ?>     
      </td>
   </tr>

      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Emitir" onclick="js_emite();" >
          
        </td>
      </tr>

  </form>
</table>
</body>
</html>