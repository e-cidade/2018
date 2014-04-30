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

$db_opcao = 22;
$db_botao = true;
$anousu = db_getsession("DB_anousu");
//-- tipos de pesquisa
/*
 1- lote
 2- complemento
 3- suplementações
 4- receitas
 5- dotações
 6- empenhos
 7- documento
 8- cgm - nomes
 */
$pesquisa = 7;  // pesquisa por suplementações 1.sup - n.lanc

$tem_dados=false;
$campos="c69_data,c69_codlan,c69_sequen,c69_codhist,c50_descr,
         c69_valor,
	 c69_debito,
	 (select c60_descr 
        from conplano 
	     inner join conplanoreduz on conplanoreduz.c61_codcon=c60_codcon and c61_anousu=c60_anousu
	    where conplanoreduz.c61_reduz=conlancamval.c69_debito and c60_anousu=".db_getsession("DB_anousu").") as  DL_debito_descr,
	 c69_credito,
	 (select c60_descr from conplano 
	     inner join conplanoreduz on conplanoreduz.c61_codcon=c60_codcon and c60_anousu=c61_anousu 
	     where conplanoreduz.c61_reduz=conlancamval.c69_credito and c60_anousu=".db_getsession("DB_anousu").") as  DL_credito_descr
	 ";
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Consultar"){
     $db_opcao=2;
     //-- seleciona todos os lancamentos do lote informado e coloca num recordset
     if ($data_ini_ano !=""){   
        $data_ini = "$data_ini_ano-$data_ini_mes-$data_ini_dia";
        $data_fim = "$data_fim_ano-$data_fim_mes-$data_fim_dia";
     } else {
        $data_ini="";
        $data_fim="";
     }  
     $sql="";
     if (($data_ini!="") and ($data_fim!="")){
        $sql="c69_data >='$data_ini' and c69_data <='$data_fim' and ";     
     }  
     $sql = $sql."c69_codlan in (select c71_codlan from conlancamdoc where c71_coddoc=$codigo order by c71_codlan) and c69_anousu=$anousu";  
     $res_sql= $clconlancamval->sql_query("",$campos,"",$sql);
     //   db_msgbox($res_sql);
   
     $result=$clconlancamval->sql_record($res_sql);
     if ($clconlancamval->numrows > 0 ){
         $tem_dados=true;
     }  
     // db_criatabela($result);   

}else if(isset($chavepesquisa)){
      $db_opcao = 2;
      $codigo=$chavepesquisa;
      $sql = "c69_codlan in (select c71_codlan from conlancamdoc where c71_coddoc=$codigo order by c71_codlan) and c69_anousu=$anousu";  
      $res_sql= $clconlancamval->sql_query("",$campos,"",$sql);
      $result=$clconlancamval->sql_record($res_sql);
      if ($clconlancamval->numrows > 0 ){
          $tem_dados=true;
      }  
}
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
	<?
	include("forms/db_frmconlancam_consulta.php");

        //-- apresenta resultados
	if (isset($res_sql)) {
	   $js_funcao="js_conlancam|c69_codlan|c69_sequen";
            db_lovrot($res_sql,15,"()","","$js_funcao");
	}    
        echo "</form>";
	?>
        <script>
        function js_conlancam(codlan,sequen){
          js_OpenJanelaIframe('top.corpo','db_conlancamval','func_conlancamval002.php?chavepesquisa='+codlan+'&sequen='+sequen,'Pesquisa');
        }  
       </script>

    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
 if($db_opcao==22){
    echo "<script> js_pesquisa();</script>";
 }
?>