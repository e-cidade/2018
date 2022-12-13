<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_relrub_classe.php");
include("classes/db_relrubmov_classe.php");
include("classes/db_selecao_classe.php");
$clrelrub = new cl_relrub;
$clrelrubmov = new cl_relrubmov;
$clselecao = new cl_selecao;
$clrotulo = new rotulocampo;
$clrotulo->label("rh45_codigo");
$clrotulo->label("rh45_descr");
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
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
  <tr><td>&nbsp;</td></tr>
  <tr><td>&nbsp;</td></tr>
  <form name="form1" method="post">
  <tr>
    <td colspan="2">
    <?
      $arr_pontosgerfs_inicial = Array(
                                   "00" =>"Salário",
                                   "01" =>"Adiantamento",
                                   "02" =>"Férias",
                                   "03" =>"Rescisão",
                                   "04" =>"Saldo do 13o",
                                   "05" =>"Complementar",
                                   "06" =>"Ponto Fixo",
                                   "07" =>"Ponto Salário",
                                   "08" =>"Ponto Complementar",
                                   "09" =>"Ponto Rescisão",
                                   "10"=>"Ponto 13o"
                                  );
      $arr_pontosgerfs_final   = Array();
      db_multiploselect("valor","descr", "", "", $arr_pontosgerfs_inicial, $arr_pontosgerfs_final, 11);
    ?>
    </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td align="right" width="30%" nowrap title="<?=@$Trh45_codigo?>">
		<?
		db_ancora(@$Lrh45_codigo,"js_pesquisarh45_codigo(true);",$db_opcao);
		?>
    </td>
    <td align="left"> 
		<?
		db_input('rh45_codigo',8,$Irh45_codigo,true,'text',$db_opcao,"onchange='js_pesquisarh45_codigo(false);'");
		db_input('rh45_descr',40,$Irh45_descr,true,'text',3,"");
		?>
    </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td colspan="2" align="center">
      <table width="100%">
		<?
		include("dbforms/db_classesgenericas.php");
		$geraform = new cl_formulario_rel_pes;

                $geraform->manomes = true;

		$geraform->tipores = true;
		$geraform->mostord = true;
		$geraform->qbrapag = true;
		$geraform->atinpen = true;
		$geraform->tipoarq = true;

		$geraform->trenome = "resumo";
		$geraform->mornome = "ordem";
		$geraform->qbrnome = "quebra";
		$geraform->aignome = "ativos";
		
		$geraform->arr_tipoarq = Array('pdf'  => 'PDF',
																	 'csv'  => 'CSV');
		
		$geraform->arr_tipores = Array(
		                               "g"=>"Geral",
		                               "o"=>"Órgão",
		                               "l"=>"Unidade",
		                               "lc"=>"Unidade Completa",
		                               "m"=>"Matrícula",
					       "t"=>"Local de Trabalho"
		                              );
		$geraform->arr_mostord = Array(
		                               "a"=>"Alfabético",
		                               "n"=>"Numérico"
		                              );
		$geraform->gera_form(null,null);
		?>
      </table>
    </td>
  </tr>
  <tr><td colspan="2">&nbsp;</td></tr>
  <tr>
    <td colspan='2' align='center'>
      <input type="button" name="processar" value="Processar" onclick="js_retornacampos();">
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

function js_pesquisarh45_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_relrub','func_relrub.php?funcao_js=top.corpo.js_mostracodigo1|rh45_codigo|rh45_descr','Pesquisa',true,20);
  }else{
     if(document.form1.rh45_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_relrub','func_relrub.php?pesquisa_chave='+document.form1.rh45_codigo.value+'&funcao_js=top.corpo.js_mostracodigo','Pesquisa',false,'0');
     }else{
       document.form1.rh45_descr.value = '';
     }
  }
}
function js_mostracodigo(chave,erro){
  document.form1.rh45_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh45_codigo.focus(); 
    document.form1.rh45_codigo.value = ''; 
  }
}
function js_mostracodigo1(chave1,chave2){
  document.form1.rh45_codigo.value = chave1;
  document.form1.rh45_descr.value = chave2;
  db_iframe_relrub.hide();
}
function js_retornacampos(){

	var sUrl;
	
  obj1 = document.form1.objeto1;
  obj2 = document.form1.objeto2;
  
  txt22 = "";
  vir22 = "";
  
  txt11 = "";
  vir11 = "";

  for(i=0;i<obj1.length;i++){
    txt22 += vir22+obj1.options[i].value;
    vir22 = ",";
  }

  for(i=0;i<obj2.length;i++){
    txt11 += vir11+obj2.options[i].value;
    vir11 = ",";
  }

  if(txt11 == ""){
    alert("Selecione algum item para gerar relatório.");
  }else if(document.form1.rh45_codigo.value == ""){
    alert("Informe o código do relatório.");
  }else{
	  
    qry = "";
    if(confirm("Emitir somente totais?")){
      qry = "&sototais=true";
    }
    
    qry+= "&ano="+document.form1.anofolha.value;
    qry+= "&mes="+document.form1.mesfolha.value;

    sUrl = "pes2_relrub002.php?tipoarq="+document.form1.tipoarquivo.value+
           "&codigo="+document.form1.rh45_codigo.value+
           "&resumo="+document.form1.resumo.value+
           "&ordem="+document.form1.ordem.value+
           "&quebra="+document.form1.quebra.value+
           "&ativos="+document.form1.ativos.value+
           "&selecionados="+txt11+qry;
    
    if(document.form1.tipoarquivo.value == 'pdf') {
    	jan = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    	jan.moveTo(0,0);
    } else {
    	js_OpenJanelaIframe('top.corpo','db_iframe_emissao', sUrl,'Processando arquivo',true,'20', '0');	        
    } 
  }
}
function js_fechaiframe(){

	db_iframe_emissao.hide();
	
}
js_trocacordeselect();
</script>
</html>