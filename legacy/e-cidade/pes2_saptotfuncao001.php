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
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("classes/db_rhregime_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
$clrhregime = new cl_rhregime;
$clrhpessoalmov->rotulo->label();
$rotulocampo = new rotulocampo;
$rotulocampo->label("DBtxt23");
$rotulocampo->label("DBtxt25");
$rotulocampo->label('rh30_regime');
$rotulocampo->label('rh30_descr');
$rotulocampo->label('rh30_vinculo');
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite(){
  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  qry  = '?funcion='+document.form1.func.value;
  qry += '&ano='+document.form1.DBtxt23.value;
  qry += '&mes='+document.form1.DBtxt25.value;
  qry += "&selec="+ selecionados;
  qry += "&emitirlei="+document.form1.emitirlei.value;
  jan = window.open('pes2_saptotfuncao002.php' + qry,'',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
<table  align="left">
<form name="form1" method="post" action="">
  <table  align="center" border="0">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="left" title="Digite o Ano / Mes de competência" >
          <b>Ano / Mês :&nbsp;&nbsp;</b>
        </td>
        <td>
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
      <tr>
	     <td align="left" title="Listar funcionários">
	       <b>Listar Funcionários :</b>
	     </td>
	     <td>
         <?
           $x = array("f"=>"Não","t"=>"Sim");
           db_select('func',$x,true,4,"");
         ?>
	     </td>
      </tr>
      <tr>
        <td nowrap align="left" title="Deseja emitir a informacao da lei">
           <b>Emitir Lei:</b>
        </td>
        <td> 
        <?
          $aEmitirLei = array("t"=>"Sim","f"=>"Não");
          db_select('emitirlei',$aEmitirLei,true,1,"");
        ?>
        </td>
      </tr>
</table>
<table  align="center">
    <tr>
      <td align="center" colspan="2">
        <?
          $result_regime = $clrhregime->sql_record($clrhregime->sql_query_file(null, "rh30_codreg,rh30_codreg || ' - ' ||  rh30_descr as rh30_descr", "rh30_codreg"," rh30_instit = ".db_getsession('DB_instit')  ));
          db_multiploselect("rh30_codreg", "rh30_descr", "nselecionados", "sselecionados", $result_regime, array(), 5, 250);
        ?>
      </td>
    </tr>

      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_emite();" >
        </td>
      </tr>
</table>
  </form>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>