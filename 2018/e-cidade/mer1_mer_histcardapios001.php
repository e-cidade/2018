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
include("classes/db_mer_desperdicio_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_desperdicio = new cl_mer_desperdicio;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <br><br>
    <center>
    <fieldset style="width:55%"><legend><b>Histórico de Cardápios</b></legend>
    <center>
	<table border="0">
	  <tr>    
	    <td> 
	        <fieldset><legend><b> Automatico </b></legend>
	        <table border="0">
	          <tr>  
	             <td> 
	                <select name="periodo" value="0">
	                  <option value="0"> </option>
	                  <option value="1">Semana</option>
	                  <option value="2">Mês</option>
	                </select>
	             </td>
	          </tr>
	          <tr>   
	             <td> 
	                <input name="consultar" type="button" value="consultar" onclick="js_consulta1();">
	             </td>
	          <tr>
	        </table>      
	        </fieldset>
	    </td>
	    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	    <td>
	       <fieldset><legend><b> Por Periodo </b></legend>
	       <table border="0">
	          <tr>
	            <td>Inicio</td>
	            <td><?db_inputdata('dataini',@$diai,@$mesi,@$anoi,true,'text',1,"");?></td>
	          </tr>
	          <tr>
	            <td>Fim</td>   
	            <td><?db_inputdata('datafim',@$diaf,@$mesf,@$anof,true,'text',1,"");?></td>
              </tr>
              <tr>  
               <td colspan="2"><input name="consultar" type="button" value="consultar" onclick="js_consulta2()"></td>	      
              </tr>
           </table>   
	       </fieldset>
	    </td>
	  </tr>    
    </table>
    </center>
	</fieldset>
	</center>
	<br><br>
	<?if (isset($opcao)) {
		
		if ($opcao==1){
			
		  if ($periodo==1) {
		  	
		    $weeke  = date("w", mktime(0,0,0,date("m"),date("d"),date("Y"))); 
            $inicio = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+(2-($weeke+1)), date("y")));
		    $fim    = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+(6-($weeke+1)), date("y")));
		    
		  } else {
		  	
		    $ano    = date("Y");
		    $mes    = date("m");
		    $inicio = $ano."-".$mes."-01";
		    $fim    = date("Y/m/t", mktime(0, 0, 0, $mes, 1, $ano));
		    
		  }
		}
	    ?>
	    <center>
	    <table border="0">
	       <tr>
	          <td align="center">
	              <?
	               $sql  = " select me01_i_codigo,me01_c_nome,me13_d_data from mer_cardapiodata ";
	               $sql .= "        inner join mer_cardapio on me13_i_cardapio=me01_i_codigo ";
                   $sql .= "    where me13_d_data between '".$inicio."' and '".$fim."'";
                  ?><fieldset><legend>Lista de Cardapios</legend> <?
	              db_lovrot($sql,"10","()","","");
	              ?></fieldset>
	              </
		      </td>
		   </tr>
		   <tr>   
		      <td align="center">
		          <br><br>
	              <?	
		          $sql  = " select me07_i_codmater,m60_descr,(sum(me07_f_quantidade)) as total from mer_cardapioitem ";
                  $sql .= "             inner join mer_cardapiodata on me07_i_cardapio=me13_i_cardapio ";
                  $sql .= "             inner join matmater on m60_codmater=me07_i_codmater ";
                  $sql .= "      where me13_d_data between '".$inicio."' and '".$fim."'  ";
                  $sql .= "      group by me07_i_codmater,m60_descr";
                  ?><fieldset><legend>Lista de Itens</legend> <?
		          db_lovrot($sql,"10","()","","");
                  ?>
	          </td>
	        </tr>
	    </table>
    <?
	  }
	?>
	</td>
  </tr>
</table>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
       );
?>
</body>
</html>
<script>
function js_consulta1() {
	
  opcao   = 1;
  periodo = document.form1.periodo.value;
  if (periodo == 0) {
    alert('Selecione um periodo');
  } else {
    location.href = 'mer1_mer_histcardapios001.php?opcao='+opcao+'&periodo='+periodo; 
  }
}

function js_consulta2() {
	
  opcao  = 2;
  dat    = document.form1.dataini.value;
  inicio = dat.substr(6,4)+'-'+dat.substr(3,2)+'-'+dat.substr(0,2);
  dat    = document.form1.datafim.value;
  fim    = dat.substr(6,4)+'-'+dat.substr(3,2)+'-'+dat.substr(0,2);
  if (inicio == '') {
    alert('Entre com a data inicial!');
  } else {
	       
    if (fim == '') {
      alert('Entre com a data final!');
    } else {
      location.href = 'mer1_mer_histcardapios001.php?opcao='+opcao+'&inicio='+inicio+'&fim='+fim;      
    }
  } 
}
</script>