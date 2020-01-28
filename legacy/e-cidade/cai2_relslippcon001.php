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
include("classes/db_orctiporec_classe.php");
db_postmemory($HTTP_POST_VARS);

$clorctiporec=new cl_orctiporec;

$clrotulo = new rotulocampo;
$clrotulo->label('c60_codcon');
$clrotulo->label('c60_descr');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
   jan = window.open('cai2_relslippcon002.php?movim='+document.form1.movim.value+
                     '&totalizador='+document.form1.totalizador.value+
 		     '&conta1='+document.form1.contas1.value+
 		     '&tipodata='+document.form1.tipodata.value+
		     '&ordem='+document.form1.ordem.value+
		     '&cod='+document.form1.c60_codcon.value+
                     '&recurso='+document.form1.o15_codigo.value+
		     '&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+
		     '&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value,
		     '',
		     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
}
</script>  
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

  <table  align="center">
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
      <td nowrap title="<?=@$Tc60_codcon?>"><b>
        <?
        db_ancora('Codigo da Conta:',"js_pesquisac60_codcon(true);",1);
        ?></b>
       </td>
       <td> 
        <?
        db_input('c60_codcon',5,$Ic60_codcon,true,'text',1," onchange='js_pesquisac60_codcon(false);'")
          ?>
          <?
           db_input('c60_descr',30,$Ic60_descr,true,'text',3,'')
          ?>
         </td>
         </tr>
	 <tr>
	 
        <td align="left" nowrap title="Contas Todas/Debito/Credito" >
        <strong>Contas:&nbsp;&nbsp;</strong>
        </td>
	<td>
	  <? 
	  $tipo_ordem1 = array("c"=>"Todas","b"=>"Debito","a"=>"Credito");
	  db_select("contas1",$tipo_ordem1,true,2); ?>
        </td>

        <td align="left" nowrap title="Tipo data" >
        <strong>Tipo de data:&nbsp;&nbsp;</strong>
        </td>
	<td>
	  <? 
	  $tipo_data = array("a"=>"Autenticacao","b"=>"Lancamento");
	  db_select("tipodata",$tipo_data,true,2); ?>
        </td>

	 
	
	 </tr>
         <tr>
        <td align="left" nowrap title="Totalizador Nenhum/Debito/Credito" >
        <strong>Totalizador:&nbsp;&nbsp;</strong>
        </td>
	<td>
	  <? 
	  $totalizador = array("n"=>"Nenhum","d"=>"Debito","c"=>"Credito");
	  db_select("totalizador",$totalizador,true,2); ?>
        </td>
         
	 <?
		$dtd = date("d",db_getsession("DB_datausu"));
		$dtm = date("m",db_getsession("DB_datausu"));
		$dta = date("Y",db_getsession("DB_datausu"));
         ?>		
	 <td><b>De:</b><?db_inputdata("data","$dtd","$dtm","$dta","true","text",2)      ?>      </td>
	 <td><b>Ate:</b>  <?db_inputdata("data1","$dtd","$dtm","$dta","true","text",2)      ?> </td>
	 
	 </tr>
	<tr>
        <td align="left" nowrap title="Ordem" >
        <strong>Ordem:&nbsp;&nbsp;</strong>
        </td>
	<td>
	  <? 
	  $ordem = array("1"=>"Autenticacao","2"=>"Slip");
	  db_select("ordem",$ordem,true,2); ?>
        </td>
	
        <td  align="left" nowrap title="Ordem" >
        <strong>Lista Movimento do Caixa:&nbsp;&nbsp;</strong>
        </td>
	<td align="left">
	  <? 
	  $xy = array("n"=>"Não","s"=>"Sim");
	  db_select("movim",$xy,true,2); ?>
        </td>	 
      </tr>
      <tr>
        <td ><strong>Recurso</strong></td>
        <td >
        <?
          $dbwhere = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
	        $rs= $clorctiporec->sql_record($clorctiporec->sql_query(null,"o15_codigo,o15_descr","o15_codigo",$dbwhere));
          db_selectrecord("o15_codigo",$rs,false,1,"","","","0");
	      ?>
	</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>


<?/*--------------------------------------------------*/?>
function js_pesquisac60_codcon(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?funcao_js=parent.js_mostraconplano1|c60_codcon|c60_descr','Pesquisa',true);
  }else{
     if(document.form1.c60_codcon.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?pesquisa_chave='+document.form1.c60_codcon.value+'&funcao_js=parent.js_mostraconplano','Pesquisa',false);
     }else{
       document.form1.c60_descr.value = ''; 
     }
  }
}
function js_mostraconplano(chave,erro){
  document.form1.c60_descr.value = chave; 
  if(erro==true){ 
    document.form1.c60_codcon.focus(); 
    document.form1.c60_codcon = ''; 
  }
}
function js_mostraconplano1(chave1,chave2){
  document.form1.c60_codcon.value = chave1;
  document.form1.c60_descr.value = chave2;
  db_iframe_conplano.hide();
}
<?/*----------------------------------------*/?>


</script>