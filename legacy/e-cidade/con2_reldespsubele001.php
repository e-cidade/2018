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

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');



?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_abre(opcao){

 sel_instit  = new Number(document.form1.db_selinstit.value);
 if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
 }
 if (document.form1.vernivel.value != '' && document.form1.vernivel.value != document.form1.nivel.value){
    if(confirm('Você já escolheu anteriormente dados do nível '+document.form1.vernivel.value+' , deseja altera-los?')==false) 
      return false
    else
      js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
 }else if(top.corpo.db_iframe_orgao != undefined){
//   alert('entrou');
   
   if(document.form1.nivel.value == document.form1.vernivel.value){
     db_iframe_orgao.show();
   }else{
     js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
   }
 }else{
   js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
 }
 
 
}


variavel = 1;
function js_emite(opcao,origem){
  if (opcao == 3){
     var data1 = new Date(document.form1.DBtxt21_ano.value,document.form1.DBtxt21_mes.value,document.form1.DBtxt21_dia.value,0,0,0);
     var data2 = new Date(document.form1.DBtxt22_ano.value,document.form1.DBtxt22_mes.value,document.form1.DBtxt22_dia.value,0,0,0);
     if(data1.valueOf() > data2.valueOf()){
       alert('Data inicial maior que data final. Verifique!');
       return false;
     }
     perini = document.form1.DBtxt21_ano.value+'-'+document.form1.DBtxt21_mes.value+'-'+document.form1.DBtxt21_dia.value;
     perfin = document.form1.DBtxt22_ano.value+'-'+document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value;;
  }else if (opcao == 2){
     if(document.form1.mesfin.value == 0){
       mesfinal = 12;
     }else if(document.form1.mesfin.value < 10){
       mesfinal = '0'+document.form1.mesfin.value;
     }else if(document.form1.mesfin.value == 'mes'){
       alert('Mês final do intervalo invalido.Verifique!');
       return false
     }else{
       mesfinal = document.form1.mesfin.value;
     }

     if(document.form1.mesini.value == 0){
       mesinicial = 12;
     }else if(document.form1.mesini.value < 10){
       mesinicial = '0'+document.form1.mesini.value;
     }else{
       mesinicial = document.form1.mesini.value;
     }
    
     perini = <?=db_getsession("DB_anousu")?>+'-'+mesinicial+'-01';
     perfin = <?=db_getsession("DB_anousu")?>+'-'+mesfinal+'-01';
  }else{
     perini = <?=db_getsession("DB_anousu")?>+'-01-01';
     perfin = <?=db_getsession("DB_anousu")?>+'-01-01';
  }
  valor_nivel = new Number(document.form1.orgaos.value);
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(valor_nivel == 0){
    alert('Você não escolheu nenhum nível a ser listado. Verifique!');
    return false;
  }else if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
 }else{
    jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    document.form1.target = 'safo' + variavel++;
    setTimeout("document.form1.submit()",1000);
    return true;
 }
}
function js_limpa(){
  if(document.form1.orgaos.value != ''){
    alert('Os dados selecionados serão excluídos. Você deverá selecionar novamente.');
    document.form1.vernivel.value = '';
    document.form1.orgaos.value = '';
    document.form1.seleciona.click();
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="con2_reldespsubele002.php">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><? db_selinstit('parent.js_limpa'); ?></td>
      </tr>

      <tr>
        <td align="right" ><strong>Nível :</strong></td>
        <td>
	  <?
	     $xy = array('1A'=>'Órgão Até o Nível','1B'=>'Órgão só o Nível','2A'=>'Unidade Até o Nível','2B'=>'Unidade só o Nível','3A'=>'Função Até o Nível','3B'=>'Função só o Nível','4A'=>'Subfunção Até o Nível','4B'=>'Subfunção só o Nível','5A'=>'Programa Até o Nível','5B'=>'Programa só o Nível','6A'=>'Proj/Ativ Até o Nível','6B'=>'Proj/Ativ só o Nível','7A'=>'Elemento Até o Nível','7B'=>'Elemento só o Nível','8A'=>'Recurso Até o Nível','8B'=>'Recurso só o Nível');
	     db_select('nivel',$xy,true,2,"");
	     
	   ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td ><input  name="seleciona" id="seleciona" type="button" value="Selecionar" onclick="js_abre();"> &nbsp;</td>
      </tr>
      <tr>
        <td align="right"><strong>Troca de Página por Órgão:</strong> 
	</td>
	
        <td >
	<?
	$x = array('S'=>'SIM','N'=>'NÃO');
	db_select('quebra_orgao',$x,true,2,"");
	?>
	</td>
      </tr>
      <tr>
        <td align="right"><strong>Troca de Página por Unidade:</strong> 
	</td>
	
        <td >
	<?
	$xx = array('S'=>'SIM','N'=>'NÃO');
	db_select('quebra_unidade',$xx,true,2,"");
	?>
	</td>
      </tr>
      <tr>
        <?
        $sql = "select o50_subelem from orcparametro where o50_anousu = ".db_getsession("DB_anousu");
        $result1 = pg_exec($sql);
        $o50_subelem = pg_result($result1,0,0);
        if($o50_subelem=='f'){

          ?>
      
          <td align="right"><strong>Listar Sub-elementos:</strong> 
  	  </td>
          <td >
	  <?
	  $xx = array('S'=>'SIM','N'=>'NÃO');
	  db_select('lista_subeleme',$xx,true,2,"");
	  ?>
	  </td>
        <?
 	}else{
	?>
          <td align="right"> 
  	  </td>
          <td>
	  <?
	  global $lista_subeleme;
	  $lista_subeleme = 'N';
	  db_input("lista_subeleme",15,0,true,'hidden',3);
	  ?>
          </td>
	<?
	}
	?>
      </tr>
      <?
      db_selorcbalanco(); 
      ?>

      <tr>
        <td colspan="2" align = "center"> 
<!--          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >-->
          <input  name="orgaos" id="orgaos" type="hidden" value="" >
          <input  name="vernivel" id="vernivel" type="hidden" value="" >
        </td>
      </tr>

  </form>
    </table>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>