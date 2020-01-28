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
$clconlancamdig->rotulo->label("c78_chave");
$clconlancamval->rotulo->label("c69_valor");
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
      js_OpenJanelaIframe('top.corpo','db_iframe_conlancamdig','func_conlancamdig.php?funcao_js=parent.js_preenchepesquisa|c78_chave','Pesquisa',true);
 }
 function js_preenchepesquisa(chave){
    db_iframe_conlancamdig.hide();
    document.form1.c78_chave.value=chave;
    <?  
    // echo "location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?c78_chave='+chave";
    ?>
 }
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name="form1" method="post" action="">
    <table border="0">
    <tr>
      <td nowrap><? db_ancora("$Lc78_chave",'js_pesquisa();',1); ?> </td>
      <td><?  db_input("c78_chave",15,"",true,'text',1);   ?> </td>
      <td colspan=3> &nbsp;  </td>
   </tr>
   <tr>
     <td nowrap>  Período   </td>
       <td><? db_inputdata('data_ini',@$data_ini_dia,@$data_ini_mes,@$data_ini_ano,true,'text',1);  ?>
          à
	  <? db_inputdata('data_fim',@$data_fim_dia,@$data_fim_mes,@$data_fim_ano,true,'text',1);  ?>
       </td>
     <td colspan=2 width="170px" align="center"><input name="db_opcao" type="submit" id="db_opcao" value="Consultar"></td>   
     <td nowrap> Total &nbsp;
           <input type=text name=vtotal size=20  align=right readonly style="background-color:#DEB887;">

     </td>
    </tr>
    </table>
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
	if (isset($c78_chave) && ($c78_chave!="")){
	   $txt_where=" c78_chave='$c78_chave'  ";
	   if ($data1!="")
               $txt_where.=" and c78_data >= '$data1' and c78_data <='$data2'  ";
	} else {
	   if ($data1!="")
               $txt_where.="c78_data >= '$data1' and c78_data <='$data2'  ";
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
              from conlancamdig
                  inner join conlancamval on c69_codlan  = c78_codlan       				   
                  left outer join conhist      on c50_codhist = conlancamval.c69_codhist   
                  inner join conplanoreduz r1 on r1.c61_reduz = c69_debito and r1.c61_anousu= $anousu and r1.c61_instit = ".db_getsession("DB_instit")."
                  inner join conplano c1 on c1.c60_codcon =r1.c61_codcon and c1.c60_anousu=r1.c61_anousu
                  inner join conplanoreduz r2 on r2.c61_reduz = c69_credito and r2.c61_anousu= $anousu and  r2.c61_instit = ".db_getsession("DB_instit")."
                  inner join conplano c2 on c2.c60_codcon =r2.c61_codcon and c2.c60_anousu=r2.c61_anousu 
              where $txt_where   
	      order by c69_sequen ";
	      // não indicado
	      // faz a soma e atualiza no campo acima
              $sql_soma="select sum(c69_valor) as vtotal
                         from conlancamdig
                            inner join conlancamval on c69_codlan  = c78_codlan       				   
                            left outer join conhist      on c50_codhist = conlancamval.c69_codhist  

                            inner join conplanoreduz r1 on r1.c61_reduz = c69_debito and r1.c61_anousu= $anousu and r1.c61_instit = ".db_getsession("DB_instit")."
                            inner join conplano c1 on c1.c60_codcon =r1.c61_codcon and c1.c60_anousu=r1.c61_anousu
                            inner join conplanoreduz r2 on r2.c61_reduz = c69_credito and r2.c61_anousu= $anousu  and r2.c61_instit = ".db_getsession("DB_instit")."
                            inner join conplano c2 on c2.c60_codcon =r2.c61_codcon and c2.c60_anousu=r2.c61_anousu 

                         where $txt_where   ";
	      $rr = pg_exec($sql_soma);
	      if (pg_numrows($rr) > 0 ){
                   db_fieldsmemory($rr,0);
		   $vtotal=db_formatar($vtotal,'f');
		   echo "<script>
		            document.form1.vtotal.value='$vtotal'; 
		         </script>";
              }
	      
	  } else {
	     $sql="";
	  }
          $js_funcao="js_conlancam|c69_codlan|c69_sequen";
          db_lovrot($sql,15,"()","","$js_funcao");
     ?>
    <!--  </form> -->

   </center>
  </td>
</tr>
</table>
</body>
</html>