<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhcadregime_classe.php"));
include(modification("classes/db_tipoasse_classe.php"));
include(modification("dbforms/db_classesgenericas.php"));
db_postmemory($HTTP_POST_VARS);
$cltipoasse   = new cl_tipoasse;
$clrhcadregime = new cl_rhcadregime;
$rotulocampo = new rotulocampo;

$datai_dia = db_subdata(db_getsession("DB_datausu"),"d","t");
$datai_mes = db_subdata(db_getsession("DB_datausu"),"m","t");
$datai_ano = db_subdata(db_getsession("DB_datausu"),"a","t");
  $result_tipoassent = $cltipoasse->sql_record($cltipoasse->sql_query(null, "h12_codigo, h12_assent || ' - ' || h12_descr as h12_descr", "h12_descr"));
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite() {

  if ($F('regime') == 0) {

    alert('Favor informar o Regime.');
    return false;
  }

  if (js_db_multiploselect_retornaselecionados() === ""){

    alert('Favor informar o Tipo de Assentamento.');
    return false;
  };

  qry = "?regime="+ document.form1.regime.value;
  qry += "&ordem="+ document.form1.ordem.value;
  qry += "&datai=" + document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
  qry += "&tipos=" + js_db_multiploselect_retornaselecionados();
  jan = window.open('rec2_afastamentos_abertos002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
           <td align="right" nowrap>
              <strong>Regime:</strong>
            </td>
            <td align="left">
              <?
              $result_regime = $clrhcadregime->sql_record($clrhcadregime->sql_query_file(null,"rh52_regime,rh52_regime||' - '||rh52_descr"));

              db_selectrecord("regime",$result_regime,true,1,"","","","0-Todos...");
              ?>
            </td>

      <tr >
        <td align="Right" nowrap title="Data .:" >
        <strong>Data .:&nbsp;&nbsp;</strong>
        </td>
        <td nowrap>
        <?
        db_inputdata("datai", @$datai_dia, @$datai_mes, @$datai_ano, true, 'text', 1);
        ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Ordem para a emissão do relatório" ><strong>Ordem : </strong>
        </td>
        <td align="left">
          <?
            $xx = array("a"=>"Alfabética","l"=>"Lotação");
            db_select('ordem',$xx,true,4,"");
	  ?>
	</td>
      </tr>
  <tr>
    <td nowrap colspan="2">
    <?
    $arr_tipoassent_inicial = Array();
    $arr_tipoassent_final   = Array();
    if(isset($cltipoasse->numrows)){
      for($i=0; $i<$cltipoasse->numrows; $i++){
        db_fieldsmemory($result_tipoassent, $i);
        if(!isset($objeto2) || (isset($objeto2) && !in_array($h12_codigo, $objeto2))){
          $arr_tipoassent_inicial[$h12_codigo] = $h12_descr;
        }else{
          $arr_tipoassent_final[$h12_codigo] = $h12_descr;
        }
      }
    }
    db_multiploselect("valor","descr", "", "", $arr_tipoassent_inicial, $arr_tipoassent_final, 10, 350, "", "", true);
    ?>
    </td>
  </tr>


      <tr>
	<td colspan="2" align = "center"> 
          <input  name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>