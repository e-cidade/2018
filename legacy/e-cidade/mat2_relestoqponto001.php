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
include("dbforms/db_classesgenericas.php");
include("classes/db_db_almox_classe.php");

db_postmemory($HTTP_POST_VARS);

$cliframe_selalmox = new cl_iframe_seleciona;
$cldb_almox        = new cl_db_almox;
?>
  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br><br>
<table align="center" valign="top" marginwidth="0" border="0" cellspacing="0" cellpadding="0">
<form name="form1" action="" method="post">
  <tr> 
     <td colspan="2" align="center">
     <?
         $sql          = $cldb_almox->sql_query(null,"m91_codigo,m91_depto,descrdepto","m91_depto");
         $sql_marca    = $cldb_almox->sql_query(null,"m91_codigo,m91_depto,descrdepto","m91_depto","m91_depto = ".db_getsession("DB_coddepto"));
         $sql_disabled = $cldb_almox->sql_query(null,"m91_codigo,m91_depto,descrdepto","m91_depto","m91_depto = ".db_getsession("DB_coddepto"));
         $campos       = "m91_depto,descrdepto";

         $cliframe_selalmox->campos        = $campos;
         $cliframe_selalmox->legenda       = "";
         $cliframe_selalmox->sql           = $sql;
         $cliframe_selalmox->sql_marca     = $sql_marca;
         $cliframe_selalmox->sql_disabled  = $sql_disabled;
         $cliframe_selalmox->iframe_height = "350";
         $cliframe_selalmox->iframe_width  = "400";
         $cliframe_selalmox->iframe_nome   = "almox";
         $cliframe_selalmox->chaves        = "m91_codigo";
         $cliframe_selalmox->js_marcador   = "";
         $cliframe_selalmox->iframe_seleciona(1);
     ?> 
     </td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr >
    <td align="right" ><strong>Ordem :&nbsp;&nbsp;</strong>
    </td>
    <td align="left">
      <?
        $arr_ordem = array("a"=>"Alfabetica","n"=>"Numerica");
        db_select('ordem',$arr_ordem,true,4,"");
	    ?>
	  </td>
  </tr>
  <tr> 
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr >
    <td align="right" ><strong>Tipo :&nbsp;&nbsp;</strong>
    </td>
    <td align="left">
      <?
        $arr_ponto = array("t"=>"Todos","p"=>"Ponto de Pedido");
        db_select('ponto',$arr_ponto,true,4,"");
	    ?>
	  </td>
  </tr>

  <tr>
     <td colspan="2" height="50" align="center"><input type="submit" value="Emitir" onClick="return js_valida_dados();"></td>
  </tr>
</form>
<script>
function js_valida_dados(){
  var obj      = almox.document.form1;
  var tam      = almox.document.form1.length;
  var codigo   = "";
  var virgula  = "";
  var query    = "";
  var contador = 0;
  
  for(i=0; i < tam; i++){
       if (obj[i].type == "checkbox"){
            if (obj[i].checked == true){
                 contador++;
                 codigo += virgula+obj[i].value;
                 virgula = ",";
            }
       }
  }

  if (contador == 0){
       alert("Selecione algum deposito.");
       return false;
  }

  query  = "coddeposito="+codigo;
  query += "&ordem="+document.form1.ordem.value ; 
  query += "&ponto="+document.form1.ponto.value ; 

  jan = window.open("mat2_relestoqponto002.php?"+query,"","width="+(screen.availWidth-5)+",height="+(screen.availHeight-40)+",scrollbars=1,location=0");
  jan.moveTo(0,0);
}
</script>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>