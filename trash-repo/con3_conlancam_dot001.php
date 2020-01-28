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
include("classes/db_conlancamval_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamcompl_classe.php");
include("classes/db_conlancamdig_classe.php");
include("classes/db_conplano_classe.php");
include("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clconplano     = new cl_conplano;
$clconlancamval = new cl_conlancamval;
$clconlancamcompl = new cl_conlancamcompl;
$clconlancamdig   = new cl_conlancamdig;
$clconlancam      = new cl_conlancam;
$anousu = db_getsession("DB_anousu");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
 function js_conlancam(codlan,sequen){
      js_OpenJanelaIframe('top.corpo','db_conlancamval','func_conlancamval002.php?chavepesquisa='+codlan+'&sequen='+sequen,'Pesquisa');
 }  
 function js_pesquisa(){
      js_OpenJanelaIframe('top.corpo','db_iframe_conlancamdot','func_conlancamdot.php?funcao_js=parent.js_preenchepesquisa|c73_coddot','Pesquisa',true);

  }
 function js_preenchepesquisa(chave){
     db_iframe_conlancamdot.hide();
    <?  
    echo "location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?codigo='+chave";
    ?>
 }
</script>
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
    <table border="0">
    <tr>
      <td nowrap><? db_ancora("Reduzido da Dotação",'js_pesquisa();',1); ?> </td>
      <td><?  db_input('codigo',12,"",true,'text',1);   ?> </td>
    </tr>
    <tr>
    <td nowrap>  Período   </td>
       <td><? db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1);  ?>
          à
	  <? db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',1);  ?>

      </td>
    </tr>
    </table>
    <input name="db_opcao" type="submit" id="db_opcao" value="Consultar">
    </form>
    <?
        //-- apresenta resultados
        $data1="";  $data2="";
        @$data1="$data_ini_ano-$data_ini_mes-$data_ini_dia"; 
        @$data2="$data_fim_ano-$data_fim_mes-$data_fim_dia"; 
        if ((strlen($data1) < 7) || (strlen($data2) <7 )) {
            $data1=""; $data2="";
        }  
	//
	$txt_where="";
	if (isset($codigo) && ($codigo!="")){
	   $txt_where=" c73_coddot=$codigo  ";
	   if ($data1!="")
              $txt_where.=" and c73_data >= '$data1' and c73_data <='$data2'  ";
	} else {
	   $txt_where ="";
	}  
	if ($txt_where!=""){
          $sql="select c69_data,
                     c69_codlan,
                     c69_sequen,
                     c69_valor,
                     c69_codhist,
	             c50_descr,
                     c69_debito,
                     c1.c60_descr as debito_descr,
                     c69_credito,
                     c2.c60_descr as credito_descr 
              from conlancamdot
                  inner join conlancamval on c69_codlan  = c73_codlan       				   
                  inner join conhist      on c50_codhist = conlancamval.c69_codhist   
                  inner join conplanoreduz r1 on r1.c61_reduz = c69_debito and r1.c61_anousu=".db_getsession("DB_anousu")."
                  inner join conplano c1 on c1.c60_codcon =r1.c61_codcon and c1.c60_anousu =r1.c61_anousu 
                  inner join conplanoreduz r2 on r2.c61_reduz = c69_credito and r2.c61_anousu=".db_getsession("DB_anousu")."
                  inner join conplano c2 on c2.c60_codcon =r2.c61_codcon and c2.c60_anousu=r2.c61_anousu 
              where $txt_where   
	      order by c69_sequen ";
	   } else {
              $sql="";
	   }  
           $js_funcao="js_conlancam|c69_codlan|c69_sequen";
           db_lovrot($sql,15,"()","","$js_funcao");
     ?>

   </center>
  </td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>