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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_vtfempr_classe.php");
$gform     = new cl_formulario_rel_pes;
$clvtfempr = new cl_vtfempr;
$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){

  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value ;
    virgula_ssel = ",";
  }

	qry  = '?anoi='+document.form1.DBtxt23.value;
	qry += '&anof='+document.form1.anofinal.value;
	qry += '&imprime_serv='+document.form1.imprime_serv.value;
	qry += '&selecionados='+selecionados;
  jan = window.open('pes2_valetransp_anual002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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

  <table  align="center" >
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td nowrap title="Ano de competência" align="right">
        <strong>Ano :&nbsp;&nbsp;</strong>
        </td>
        <td>
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;&nbsp; a &nbsp;&nbsp;
          <?
           $anofinal = db_anofolha();
           db_input('anofinal',4,'',true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2" >
              <fieldset>
                <Legend>
                  <b>Selecione os Vale Transportes</b>
                </Legend>
                <?
                db_input("valor", 4, 0, true, 'hidden', 3);
                db_input("colunas_sselecionados", 4, 0, true, 'hidden', 3);
                db_input("colunas_nselecionados", 4, 0, true, 'hidden', 3);
                 if(!isset($result_regime)){
                    $result_regime = $clvtfempr->sql_record($clvtfempr->sql_query_file(2013, 1, null, db_getsession('DB_instit'), "r16_codigo, r16_codigo||'-'||r16_descr as r16_descr", "r16_codigo" ));
//                    echo ($clvtfempr->sql_query_file(2013, 1, null, db_getsession('DB_instit'), "r16_codigo, r16_codigo||'-'||r16_descr as r16_descr", "r16_codigo" ));
                    for($x=0; $x<$clvtfempr->numrows; $x++){
                         db_fieldsmemory($result_regime,$x);
                         $arr_colunas[$r16_codigo]= $r16_descr;
                    }
                  }
                  $arr_colunas_final   = Array();
                  $arr_colunas_inicial = Array();
                  if(isset($colunas_sselecionados) && $colunas_sselecionados != ""){
                     $colunas_sselecionados = split(",",$colunas_sselecionados);
                     for($Ic=0;$Ic < count($colunas_sselecionados);$Ic++){
                        $arr_colunas_final[$colunas_sselecionados[$Ic]] = $arr_colunas[$colunas_sselecionados[$Ic]]; 
                     }
                  }
                  if(isset($colunas_nselecionados) && $colunas_nselecionados != ""){
                     $colunas_nselecionados = split(",",$colunas_nselecionados);
                     for($Ic=0;$Ic < count($colunas_nselecionados);$Ic++){
                        $arr_colunas_inicial[$colunas_nselecionados[$Ic]] = $arr_colunas[$colunas_nselecionados[$Ic]]; 
                     }
                  }
                  if(!isset($colunas_sselecionados) || !isset($colunas_sselecionados) || $colunas_sselecionados == ""){
                     $arr_colunas_final  = Array();
                     $arr_colunas_inicial = $arr_colunas;
                  }
                 db_multiploselect("r16_codigo","r16_descr", "nselecionados", "sselecionados", $arr_colunas_inicial, $arr_colunas_final, 6, 250, "", "", true);
                 ?>
              </fieldset>
        </td>
      </tr>
      <tr >
        <td align="right" ><strong>Imprime Servidores :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
            $arr_imprime_serv = array("t"=>"Sim","f"=>"Não");
            db_select('imprime_serv',$arr_imprime_serv,true,4,"");
	        ?>
	      </td>
      </tr>

      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>