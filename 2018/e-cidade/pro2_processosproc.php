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
include("classes/db_protprocesso_classe.php");

$dia1 = date("d");
$mes1 = date("m");
$ano1 = date("Y");

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$cl_protprocesso = new cl_protprocesso;
$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$cl_protprocesso->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<style>
.fieldsetinterno {
  border:0px;
  border-top:2px groove white;
}
fieldset.fieldsetinterno table {

  width: 100%;
  table-layout:auto;
}
fieldset.fieldsetinterno table tr TD:FIRST-CHILD {

  width: 80px;
  white-space: nowrap;
}
select {
 width: 100%;
}  
fieldset.fieldsetinterno table tr TD {
  white-space: nowrap;
}
legend {
  font-weight: bold;
}
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" >
<br /><br />
     <center>
       <form name="form1" method="post" action="">
         <table>
           <tr>
             <td>
               <fieldset>
                  <legend style="font-weight: bold; font-size: 13px;">&nbsp;Filtros Principais&nbsp;</legend>
                  <br />
                  <fieldset class="fieldsetinterno">
                    <legend>&nbsp;Data Emissão&nbsp;</legend>
                      <table border="0">
						            <tr>
						              <td> 
							              <b>De:</b>
							            </td>
							            <td> 
							            <?
						                db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"");
						              ?>
						              &nbsp;&nbsp;&nbsp;&nbsp;
							            <b>Até:</b> 
						              <?
						                db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
						              ?>
										      </td>
						            </tr>
						          </table>
                  </fieldset>
				          <fieldset class="fieldsetinterno">
				            <legend>&nbsp;Outros Filtros&nbsp;</legend>
				              <table border="0">
				                <tr>
				                  <td><b>Tipo:</b> </td>
				                  <td>
				                  <?
				                    $aTipos = array ('n' => 'Somente os em andamento',
				                                     'a' => 'Somente os arquivados',
				                                     't' => 'Todos');
				                    db_select('tipo', $aTipos, true, 1);
				                  ?>
				                  </td>
				                </tr>  
				              </table>      
				          </fieldset>
                  <fieldset class="fieldsetinterno">
					          <legend>&nbsp;Visualização&nbsp;</legend>
					            <table border="0">
					              <tr>
					                <td><b>Ordem:</b></td>
					                <td>
					                <?
					                  $aOrdem = array (1 => 'Processos',
					                                   2 => 'Tipo de Processo',
					                                   3 => 'Data / Hora',
					                                   4 => 'Usuário',
					                                   5 => 'Requerente',
					                                   6 => 'Departamento Inicial',
					                                   7 => 'Departamento Atual');
    					             db_select('ordem', $aOrdem, true, 1);
		    			           ?>
				    	           </td>
					             </tr>
					             <tr>
					               <td><b>Mostrar:</b></td>
					               <td>
					               <?
					                 // Array com valores do select
					                 $aTitulares = array (1 => 'Requerente', 2 => 'Titular');
					                 db_select ('cboTitularProcesso', $aTitulares, true, 1);
					               ?>
					               </td>
					             </tr>
					             <tr>
					                <td><b>Observação:</b></td>
                          <td>
                          <?
                            $aObs = array (1 => 'Sim',
                                           2 => 'Não'
                                          );
                           db_select('observacao', $aObs, true, 1);
                         ?>
                         </td>
					             </tr>          
					           </table>
						      </fieldset>
						      
	           </tr>
	           <tr>
               <td align='center' align="center" nowrap colspan=3>
                 <input type="button" value="Relatório" onclick="js_self()">
               </td>
             </tr>
	         </table>
	       </form>
     </center>
       

<script>
	function js_self(){
	  vir="";
	  listacgm = "";
	  for(x=0;x<parent.iframe_s2.document.form1.cgm.length;x++){
		  listacgm+=vir+parent.iframe_s2.document.form1.cgm.options[x].value;
		  vir=",";
	  }
	  vir="";
	  listadept = "";
    for(x=0;x<parent.iframe_s3.document.form1.dept.length;x++){
      listadept+=vir+parent.iframe_s3.document.form1.dept.options[x].value;
      vir=",";
	  }
	  vir="";
	  listatipo = "";
	  for(x=0;x<parent.iframe_s4.document.form1.tipo.length;x++){
	    listatipo+=vir+parent.iframe_s4.document.form1.tipo.options[x].value;
	    vir=",";
	  }
	  vir="";
	  listaprocand = "";
	  for(x=0;x<parent.iframe_s5.document.form1.procand.length;x++){
	    listaprocand+=vir+parent.iframe_s5.document.form1.procand.options[x].value;
	    vir=",";
	  }
	 
	  /* Definições de Variáveis */
		var Condicao1 = parent.iframe_s2.document.form1.ver.value;
		var Condicao2 = parent.iframe_s3.document.form1.ver.value;
		var Condicao3 = parent.iframe_s4.document.form1.ver.value;
		var Condicao4 = parent.iframe_s5.document.form1.ver.value;
		
		var data1 = document.form1.data1_ano.value+"/"+document.form1.data1_mes.value+"/"+document.form1.data1_dia.value;
		var data2 = document.form1.data2_ano.value+"/"+document.form1.data2_mes.value+"/"+document.form1.data2_dia.value;
		
		var sObservacao      = document.form1.observacao.value;
		var sOrdem           = document.form1.ordem.value;
		var sTipo            = document.form1.tipo.value;
		var iTipoCGMProcesso = document.form1.cboTitularProcesso.value;
		
    /* Monta URL */
		var sUrlOpen  = 'pro2_processos002.php?';
				sUrlOpen += 'listacgm='+listacgm;
				sUrlOpen += '&listadept='+listadept;
				sUrlOpen += '&listatipo='+listatipo;
				sUrlOpen += '&listaprocand='+listaprocand;
				sUrlOpen += '&Condicao1='+Condicao1; 
				sUrlOpen += '&Condicao2='+Condicao2;
				sUrlOpen += '&Condicao3='+Condicao3;
				sUrlOpen += '&Condicao4='+Condicao4;
				sUrlOpen += '&data1='+data1;
				sUrlOpen += '&data2='+data2;
				sUrlOpen += '&Ordem='+sOrdem;
				sUrlOpen += '&Observacao='+sObservacao;
				sUrlOpen += '&tipo='+sTipo;
				sUrlOpen += '&tipoCGMProcesso='+iTipoCGMProcesso;	 
    
    jan = window.open(sUrlOpen,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	
	}
</script>
  </body>
</html>