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
include("classes/db_rhlota_classe.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$clrhlota   = new cl_rhlota;
$rotulocampo = new rotulocampo;
$rotulocampo->label("rh01_regist");
$rotulocampo->label("z01_nome");


$result_rhlota = $clrhlota->sql_record($clrhlota->sql_query_file(null, "r70_codigo, r70_estrut || ' - ' || r70_descr as r70_descr", "r70_descr"));

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script> 
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table  align="center">
  <form name="form1" method="post" action="" >
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
  <tr>
    <td nowrap title="Período da Efetividade" align="right">
      <b>Período:</b>
    </td>
    <td nowrap>
      <?
      $dataf_dia = 10;
      $dataf_mes = date('m');
      $dataf_ano = date('Y');

      $datai_dia = 11;
      $datai_mes = db_formatar( date('m')-1, 's', '0' , 2, 'e', 0 )  ;
      if($dataf_mes == 1){
        $datai_ano = date('Y')-1;
      }else{
        $datai_ano = date('Y');
      }

      db_inputdata("datai", @$datai_dia, @$datai_mes, @$datai_ano, true, 'text', 1);
      ?>
      <b>&nbsp;a&nbsp;</b>
      <?
      db_inputdata("dataf", @$dataf_dia, @$dataf_mes, @$dataf_ano, true, 'text', 1);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="2">
    <?
    $arr_rhlota_inicial = Array();
    $arr_rhlota_final   = Array();
    if(isset($clrhlota->numrows)){
      for($i=0; $i<$clrhlota->numrows; $i++){
        db_fieldsmemory($result_rhlota, $i);
        if(!isset($objeto2) || (isset($objeto2) && !in_array($r70_codigo, $objeto2))){
          $arr_rhlota_inicial[$r70_codigo] = $r70_descr;
        }else{
          $arr_rhlota_final[$r70_codigo] = $r70_descr;
        }
      }
    }
    db_multiploselect("valor","descr", "", "", $arr_rhlota_inicial, $arr_rhlota_final, 10, 350, "", "", true);
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_emite();" >
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
function js_emite(){
  qry  = "&perinicial=" + document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
  qry += "&perfinal=" + document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value;
  qry += "&tipos=" + js_db_multiploselect_retornaselecionados();
  jan = window.open('pes2_canefetividade002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>