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
include("classes/db_rhrubricas_classe.php");
$clrhrubricas = new cl_rhrubricas;
$clrotulo = new rotulocampo;
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
  if(document.form1.sselecionados.length == 0 ){
    alert('Você deve selecionar pelo menos 1 item!');
    return;
  }
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  qry  = "?ano="+document.form1.DBtxt23.value;
  qry += "&mes="+document.form1.DBtxt25.value;
  qry += "&perc_extra="+document.form1.perc_extra.value;
  qry += "&salario="+document.form1.salario.value;
  qry += "&selec="+ selecionados;
  if(document.form1.R918 && document.form1.R918.checked){
    qry+= "&R918=true";
  }
  if(document.form1.R919 && document.form1.R919.checked){
    qry+= "&R919=true";
  }
  if(document.form1.R920 && document.form1.R920.checked){
    qry+= "&R920=true";
  }
  jan = window.open('pes2_empenhospatronais002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Ano / Mês :</strong>
        </td>
        <td align="left">
          <?
           $DBtxt23 = db_anofolha();
           db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
           $DBtxt25 = db_mesfolha();
           db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      </tr>
      <tr>
        <td align="right"><b>Arquivo</b</td>
        <td >
         <?
           $xx = array("s"=>"Salário","d"=>"13o. Salário");
           db_select('salario',$xx,true,4,"");
         ?>
	
	</td>
      </tr>
      <tr>
        <td align="right">
           <strong>Coluna Extra (%) :</strong>
        </td>
        <td align="left" >
          <?
          db_input('perc_extra',4,null,true,'text',2,'');
          ?>
       </td>
     </tr>
      <tr >
        <td align="center" colspan="2" >
         <?
	       $sql = "select distinct r33_codtab as r33_codtab,
                       case when r33_codtab = 2 then 'FGTS' else r33_nome end as r33_nome 
                 from inssirf 
                 where r33_anousu = $DBtxt23 
                   and r33_mesusu = $DBtxt25 
                   and r33_codtab > 1 
                   and r33_instit = ".db_getsession('DB_instit') ;
	        $res = pg_query($sql);
        db_multiploselect("r33_codtab", "r33_nome", "nselecionados", "sselecionados", $res, array(), 5, 250);
         ?>
        </td>
      </tr>
  <tr>
    <td colspan="2" align = "center">
      <fieldset>
        <legend><b>Salário família para dedução</b></legend>
          <table>
            <?
            $result_dados_rubricas = $clrhrubricas->sql_record($clrhrubricas->sql_query_file(null,db_getsession('DB_instit'),"rh27_rubric, rh27_descr","rh27_rubric"," rh27_rubric in ('R918','R919','R920') and rh27_instit = ".db_getsession('DB_instit')));
            if($clrhrubricas->numrows > 0){
              for($i=0; $i<$clrhrubricas->numrows; $i++){
                db_fieldsmemory($result_dados_rubricas, $i);
                echo "
                      <tr>
                        <td>
                          <input type='checkbox' name='".$rh27_rubric."' value='".$rh27_rubric."'>".$rh27_rubric." - ".$rh27_descr."
                        </td>
                      </tr>
                     ";
              }
            }
	    ?>
	  </table>
      </fieldset>
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