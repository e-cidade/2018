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
db_postmemory($HTTP_POST_VARS);
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
<center>
<table width="60%" border="0" cellspacing="4" cellpadding="0">
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <form name="form1" method="post" action="pes1_aturubricas002.php">
  <tr>
    <td colspan="2" align='center'>
    <?
      $arr_pontosgerfs_inicial = Array(
		                                   "fa" =>"Ponto Adiantamento",
		                                   "fc" =>"Ponto Complementar",
		                                   "f3" =>"Ponto 13o",
		                                   /*"fe" =>"Ponto Férias",*/
		                                   "fx" =>"Ponto Fixo",
		                                   /*"fr" =>"Ponto Rescisão",*/
		                                   "fs" =>"Ponto Salário"
		                                  );
      $arr_pontosgerfs_final   = Array();
      db_multiploselect("valor","descr", "", "", $arr_pontosgerfs_inicial, $arr_pontosgerfs_final, 5, 180, "Pontos a selecionar", "Pontos selecionados");
    ?>
    </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td colspan="2" align="center">
		<?
		include("dbforms/db_classesgenericas.php");
		$geraform = new cl_formulario_rel_pes;
    $geraform->manomes = false;
		$geraform->jsconsr = "
                          retorno = js_retornacampos();
                          if(retorno == false){
                            return false;
                          }else{
                            qryret = retorno;
                          }
                          document.form1.valores_campos_rel.value = qryret;
                          document.form1.submit();
                         ";
		$geraform->usarubr = true;
		$geraform->unirubr = true;
		$geraform->selecao = true;
		$geraform->ru1nome = "rubrica";
		$geraform->gera_form(null,null);
		?>
    </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td colspan="2" align='center'>
      <table>
			  <tr>
			    <td align='center' width='75%'>
	          <fieldset>
	            <Legend align="left">
	              <b>Opções<b>
	            </Legend>
              <table>
						  	<tr>
						    	<td align='center'>
							      <input type="radio" name="iae" value='i'><b>Inclusão</b><?=str_repeat("&nbsp;",7)?>
					  			  <input type="radio" name="iae" value='a' checked><b>Alteração</b><?=str_repeat("&nbsp;",7)?>
					    			<input type="radio" name="iae" value='e'><b>Exclusão</b>
						    	</td>
						  	</tr>
			      	</table>
    				</fieldset>
			    </td>
			  </tr>
      </table>
    </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td colspan='2' align='center'>
      <input type="button" name="processar" value="Processar" onclick="js_gerar_consrel();" onblur='js_tabulacaoforms("form1","rubrica",true,1,"rubrica",true);'>
    </td>
  </tr>
  </form>
</table>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_retornacampos(){
  obj2 = document.form1.objeto2;
  
  txt11 = "";
  vir11 = "";

  for(i=0;i<obj2.length;i++){
    txt11 += vir11+obj2.options[i].value;
    vir11 = ",";
  }

  if(txt11 == ""){
    alert("Selecione algum item para prosseguir.");
    return false;
  }else if(document.form1.rubrica.value == ""){
    alert("Informe o código da rubrica a alterar.");
    document.form1.rubrica.focus();
    return false;
  }else{
    qryon = txt11;
    return qryon;
  }
}
js_trocacordeselect();
js_tabulacaoforms("form1","rubrica",true,1,"rubrica",true);
</script>
</html>