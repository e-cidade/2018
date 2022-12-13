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
include("libs/db_liborcamento.php");
include("libs/db_libcontabilidade.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancamval_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamcompl_classe.php");
include("classes/db_conplano_classe.php");
include("classes/db_orcfontes_classe.php");
include("libs/db_sql.php");
include("classes/db_conplanoreduz_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clconplano     = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancam      = new cl_conlancam;
$clorcfontes      = new cl_orcfontes;
$clconplanoreduz  = new cl_conplanoreduz;

$clorcfontes->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("c61_codcon");
$clrotulo->label("DBtxt22");
$clrotulo->label("DBtxt21");
$db_opcao = 1;
$db_botao = true;
$anousu = db_getsession("DB_anousu");


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
  if(document.form1.c61_codcon.value==''){
    alert('Selecione uma conta para consultar.');
    return false;
  }
  if(document.form1.c61_reduz.value==''){
    alert('Selecione uma conta para consultar.');
    return false;
  }


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
    <td width="360" height="18">&nbsp;</td>
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

          <table border="0">
          <tr>
             <td><? db_ancora("$Lc61_codcon",'js_pesquisa();',1); ?>
                 <? db_input('c61_codcon',8,"",true,'text',1);  ?>
	         <? db_input('c61_reduz',8,"",true,'text',3);  ?>
                 <? db_input('c60_descr' ,50,"",true,'text',3);  ?>

  	    </td>
       	  </tr>
	  </tr>
   	     <td><? db_selorcbalanco(true,false); ?> </td>
           </tr>
           </table>
           <? 
 
           if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
               $perini=$ini;
               $perfin=$fim; 
               if (isset($c61_reduz) && ($c61_reduz!="")) {
                 echo "<script>  
                        function lancamentos(chave1){
                             chave2='$perini';
                             chave3='$perfin';
                             js_OpenJanelaIframe('top.corpo','db_iframe_conlancamval','func_conlancamval003.php?codrec='+chave1+'&perini='+chave2+'&perfin='+chave3+'&funcao_js=parent.js_pesquisa_lancam|c69_sequen|c69_codlan','Pesquisa',true);
                      }
                    </script>";
		 
		 echo "<script> lancamentos($c61_reduz); </script>";
            }else{
       	         $sql01 = db_planocontassaldo(db_getsession("DB_anousu"),$perini,$perfin,true);
  	           $sql= "select c61_reduz,
		                 saldo_anterior_debito as DL_Saldo_a_Debito,
		                 saldo_anterior_credito as DL_Saldo_a_Credito,
		                 saldo_final as DL_Saldo
	                  from ($sql01) as X
 	                  where c61_reduz >0 ";
		       
   	           $js_funcao="lancamentos|c61_reduz";
                   db_lovrot($sql,18,"()","","$js_funcao");  
	     
             }
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
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conplanoreduz','func_conplanoreduz.php?funcao_js=parent.js_preenchepesquisa|c61_codcon|c61_reduz|c60_descr','Pesquisa',true);
}
function js_preenchepesquisa(chave1,chave2,chave3){
    db_iframe_conplanoreduz.hide();
    document.form1.c61_codcon.value=chave1;
    document.form1.c61_reduz.value=chave2;
    document.form1.c60_descr.value=chave3;
}
  
function js_pesquisa_lancam(sequen,codlan){
   
  //  db_iframe_conlancamval.hide();
  // chave1 = c69_sequen
  // chave2 = c69_codlan 
  js_OpenJanelaIframe('top.corpo','db_conlancamval','func_conlancamval002.php?chavepesquisa='+codlan+'&sequen='+sequen,'Pesquisa');
}  
</script>