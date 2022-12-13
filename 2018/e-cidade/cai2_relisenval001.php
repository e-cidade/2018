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
include("classes/db_iptucalc_classe.php");

$cliptucalc = new cl_iptucalc;
$aux = new cl_arquivo_auxiliar;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
	</table>
	
  <center>	
    <table align="center" border="0">
      <tr>
				<td> 	
					<form name="form1" method="post" action="">
						<table align="center"> 
     	 	 		  <tr>
                <td><b>Exercício :&nbsp;</b>
                </td>
                <td align="left">
                  <select name="anoexe" id="anoexe" style="width:85px">
                    <?
                      $result = $cliptucalc->sql_record($cliptucalc->sql_query_file("","","distinct j23_anousu","j23_anousu desc"));
                      for($i = 0;$i < $cliptucalc->numrows;$i++){
                        db_fieldsmemory($result,$i);
                        echo "<option value=\"".($j23_anousu)."\">".($j23_anousu)."</option>\n";
                      }
                    ?>
                  </select>
                </td>
              </tr>
     	 	 			<tr>
     	 	 			 	<td align="left">
     	 	 			 	  <strong>Tipo : </strong>
                </td>
                <td align="left">
     	 	 			    <?
                     $xx = array("a"=>"Analítico","s"=>"Sintético");
     	 	 			 		  db_select('seltipo',$xx,true,4,"");
                      ?>
                </td>
              </tr>  
     	 	 	  </table>
     	 	 	 
						<table> 
     	 	 		  <tr> 
     	 	 		 	  <td>
     	 	 		 			<?
                    $aux->codigo = "j45_tipo";
                    $aux->descr  = "j45_descr";
                    $aux->nomeobjeto = 'tipoisen';
                    $aux->funcao_js = 'js_funcaotipo';
                    $aux->funcao_js_hide = 'js_funcaotipo1';
                    $aux->sql_exec  = "";
                    $aux->func_arquivo = "func_tipoisen.php";
                    $aux->nomeiframe = "iframe_tipoisen";
                    $aux->localjan = "";
                    $aux->tipo = 2;
                    $aux->db_opcao = 2;
                    $aux->top = 0;
                    $aux->linhas = 10;
                    $aux->vwidth = 400;
                    $aux->funcao_gera_formulario();
                  ?>
     	 	 		    </td>     
     	 	 	    </tr>
     	 	 		  <tr>
                <td colspan="2" align = "center"> 
                  <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
                </td>
              </tr>
            </table>
          </form>
				</td>	
			</tr>   
		</table>
  </center>
	 <?
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	 ?>
</body>
</html>
<script>

  function js_emite(){
 
    tipo = "";
    vir = "";
    for(y=0;y<document.getElementById('tipoisen').length;y++){
       tipo += vir + document.getElementById('tipoisen').options[y].value;
       vir = ",";
    }
    qry  =	'?seltipo='+document.form1.seltipo.value;
    qry +=	'&anoexe='+document.form1.anoexe.value;
    qry +=	'&tipoisen='+tipo;
      
    jan = window.open('cai2_relisenval002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

</script>