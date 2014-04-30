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
  include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");
  include("classes/db_iptucalc_classe.php");
	include("dbforms/db_funcoes.php");
  $cliptucalc=new cl_iptucalc;
?>
 <html>
  <head>
   <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Expires" CONTENT="0">
   <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
   <script>
    function js_AbreJanelaRelatorio() { 
			
			if (document.form1.mesini.value>document.form1.mesfim.value){
				alert("Mês inicial naum pode ser maior q o mês final!!");
			}else{
				window.open('cad2_iptucalculado_002.php?exercicio='+document.form1.anousu.value+'&vlrvenalini='+document.form1.vlrvenalini.value+'&vlrvenalfim='+document.form1.vlrvenalfim.value+'&considerarisentos='+document.form1.considerarisentos.value+'&emitirpormes='+document.form1.emitirpormes.value+'&mesini='+document.form1.mesini.value+'&mesfim='+document.form1.mesfim.value,'','width=790,height=530,scrollbars=1,location=0'); 
			}
			
    }
   </script>
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
   <table width="790" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr> 
     <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
      <table width="80%" border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td>
         <form name="form1" method="post" action="cad2_iptucalculado_002.php">
          <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
            <td>&nbsp;</td>
           </tr>
           <tr>
            <td align="center">
              <tr align="center"> 
               <td bgcolor="#0099CC"><strong>Relatorio da posição do IPTU calculado</strong></td>
	          </tr>
             <table width="100%" border="1" cellspacing="0" cellpadding="0">
<!--              <tr align="center"> 
               <td bgcolor="#0099CC" ><strong>Relatorio da posição do IPTU calculado</strong></td>
	          </tr>
-->
              <tr align="right">
	           <td><strong>Exercício:</strong> </td><td align="left">
                <select name="anousu" id="anousu">
                 <?
		  $sql = "select j18_anousu as j23_anousu from cfiptu order by j18_anousu desc";
//                 $result = $cliptucalc->sql_record($cliptucalc->sql_query_file("","","distinct j23_anousu"));
                  $result = db_query($sql) or die($sql);
		  
                  for($i = 0;$i < pg_numrows($result);$i++){
                  db_fieldsmemory($result,$i);
                  $sel = "";
                  if($j23_anousu==date("Y")){
                   $sel = "selected";
                  }
                  echo "<option value=\"".($j23_anousu)."\" ".$sel.">".($j23_anousu)."</option>\n";
                  }	     
                 ?>
                </select> 
               </td>
              </tr>
              <tr align="right">
               <td><strong>Valor Venal Inicial:</strong></td><td align="left">
                <input name='vlrvenalini' type='text' value=''>
               </td>
              </tr>
              <tr align="right">
               <td><strong>Valor Venal Final:</strong></td><td align="left">
                <input name='vlrvenalfim' type='text' value=''>
               </td>
              </tr>


      	<tr align="right">
					<td><strong>Considerar isentos:</strong></td><td align="left">
					<select name='considerarisentos'>
					<option value='n'>Não</option>
					<option value='s'>Sim</option>
					</select> 
					</td>
			  </tr>

      	<tr align="right">
					<td><strong>Emitir demonstrativo por mes:</strong></td><td align="left">
					<select name='emitirpormes'>
					<option value='s'>Sim</option>
					<option value='n'>Não</option>
					</select> 
					</td>
			  </tr>




	        <tr>
	           <td align="right"><b>Mês inicial:</b> </td><td>
	             <?
	             $meses = array("01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
	             $mesini = "01" ;
	             db_select("mesini",$meses,true,"text",1);
	             ?>
	           </td>
	        </tr>
	        <tr>
	           <td align="right"><b>Mês final:</b> </td><td>
	             <?
	             $mesfim = date("m");
	             db_select("mesfim",$meses,true,"text",1);
	             ?>
	           </td>
	        </tr>
          <tr align="center"> 
               <td colspan="3"><input name="exibir_relatorio" type="button" id="exibir_relatorio" value="Exibir relat&oacute;rio" onClick="js_AbreJanelaRelatorio()"></td>
          </tr>
          </table>
            </td>
            </tr>
          </table>
         </form>
        </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
   ?>
  </body>
 </html>