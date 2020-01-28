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
include("classes/db_conlancamval_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamcompl_classe.php");
include("classes/db_conlancamdig_classe.php");
include("classes/db_conplano_classe.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_orcfontes_classe.php");
include("libs/db_sql.php");
include("libs/db_liborcamento.php");


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clconplano     = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig   = new cl_conlancamdig;
$clconlancam      = new cl_conlancam;
$clorcfontes      = new cl_orcfontes;

$clorcfontes->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("c69_valor");
$clrotulo->label("DBtxt22");
$clrotulo->label("DBtxt21");

$db_opcao = 22;
$db_botao = true;
//-- tipos de pesquisa
$anousu = db_getsession("DB_anousu");
//-- tipos de pesquisa

$tem_dados=false;

if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
	$dataini=$ini;
        $datafim=$fim; 
        $sql = db_receitasaldo(11,1,2,true,'',$anousu,$dataini,$datafim,true);
	
        $sql1 = "select o70_codrec,                  
	   	        sum(saldo_inicial)              as DL_saldo_inicial, 
	 	        sum(saldo_anterior)             as DL_saldo_anterior,
		        sum(saldo_arrecadado)           as DL_saldo_arrecadado,
		        sum(saldo_a_arrecadar)          as DL_saldo_a_arrecadar,
		        sum(saldo_arrecadado_acumulado) as DL_saldo_arrecadado_acumulado
                 from ( $sql ) as x
		 where o70_codrec >0		 
	         ";
        if ($o70_codrec !=""){
	   $sql1 .= " and o70_codrec = $o70_codrec ";
	}     		          
        $sql1 .="group by o70_codrec ";

}
?>
<html>
<head>
<script>
function js_emite(opcao,origem){
  if (opcao == 3){
     document.form1.opcao.value=3;
     var data1 = new Date(document.form1.DBtxt21_ano.value,document.form1.DBtxt21_mes.value,document.form1.DBtxt21_dia.value,0,0,0);
     var data2 = new Date(document.form1.DBtxt22_ano.value,document.form1.DBtxt22_mes.value,document.form1.DBtxt22_dia.value,0,0,0);
     if(data1.valueOf() > data2.valueOf()){
       alert('Data inicial maior que data final. Verifique!');
       return false;
     }
     perini = document.form1.DBtxt21_ano.value+'-'+document.form1.DBtxt21_mes.value+'-'+document.form1.DBtxt21_dia.value;
     perfin = document.form1.DBtxt22_ano.value+'-'+document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value;;

     document.form1.ini.value= perini;
     document.form1.fim.value= perfin; 
  }else if (opcao == 2){
     document.form1.opcao.value=2;
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
     document.form1.ini.value=perini;
     document.form1.fim.value=perfin;
  }else{
     perini = <?=db_getsession("DB_anousu")?>+'-01-01';
     perfin = <?=db_getsession("DB_anousu")?>+'-01-01';
  }
  //rec = document.form1.o70_codrec.value;
  document.form1.submit();

}
</script>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="25">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <form name="form1" method="post" action="">
          <input type=hidden name="db_opcao" value="Incluir">
          <input type=hidden name="opcao"   value="">
          <input type=hidden name="ini"   value="">
          <input type=hidden name="fim"   value="">

          <table border="1">
          <tr>
             <td><? db_ancora("Codigo da Receita",'js_pesquisa();',1); ?>
                 <? db_input('o70_codrec',8,"",true,'text',1);  ?>
	         <? db_input('o57_fonte',20,"",true,'text',3);  ?>
                 <? db_input('o57_descr' ,30,"",true,'text',3);  ?>

  	    </td>
       	  </tr>
	  </tr>
   	     <td><? db_selorcbalanco(true,false); ?> </td>
           </tr>
           </table>
       <? 

	if (isset($sql1)) {
	   $js_funcao="";
	   
	   db_lovrot($sql1,18,"()","","$js_funcao");
	}  
	?>
   </form>
   </center>
   </td>
  </tr>
 </table>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>
<?
 if($db_opcao==22){
    echo "<script>   js_pesquisa();    </script>";
 }
?>
<script>
function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_conlancamrec','func_conlancamrec.php?funcao_js=parent.js_preenchepesquisa|o70_codrec|o57_descr|o57_fonte','Pesquisa',true);
  
}
function js_preenchepesquisa(chave,chave2,chave3){
    db_iframe_conlancamrec.hide();
    document.form1.o70_codrec.value=chave;
    document.form1.o57_descr.value=chave2;
    document.form1.o57_fonte.value=chave3;
 
}
</script>