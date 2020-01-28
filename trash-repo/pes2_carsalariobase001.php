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
include("classes/db_rhregime_classe.php");
$clrhregime = new cl_rhregime;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);

$iInstit = db_getsession("DB_instit");
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
<form name="form1" method="post" action="">
<table align="center" border=0>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
 </tr>
      <tr >
        <td align="Right" nowrap title="Digite o Ano / Mes de compet�ncia" >
        <strong>Ano / M�s :</strong>
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
    <td align="right" nowrap title="Ordem para a emiss�o do relat�rio">
      <strong>Ordem:</strong>
    </td>
    <td align="left">
      <?
      $array_ordem = array("n"=>"Num�rica","a"=>"Alfab�tica");
      db_select('ordem',$array_ordem,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="Emite com o Campo Sal�rio na emiss�o do relat�rio">
      <strong>Emite Sal�rio:</strong>
    </td>
    <td align="left">
      <?
      $array_sal = array("t"=>"Sim","f"=>"N�o");
      db_select('salario',$array_sal,true,1);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap >
      <strong>Op��o:</strong>
    </td>
    <td align="left">
      <?
      $xx = array("p"=>"Professores","d"=>"Demais Funcion�rios","t"=>"Todos");
      db_select('prof',$xx,true,1);
      ?>
    </td>
  </tr>
      <tr> 
        <td align="right" nowrap title="<?=@$TDBtxt27?>" >
        <?=$LDBtxt27?>
        </td>
        <td>
          <?
            db_input('DBtxt27',4,$IDBtxt27,true,'text',2,'')
          ?>
	</td>
      </tr>
      <tr> 
        <td align="right" nowrap title="<?=@$TDBtxt28?>" >
        <?=$LDBtxt28?>
        </td>
        <td>
          <?
            db_input('DBtxt28',4,$IDBtxt28,true,'text',2,'')
          ?>
	</td>
      </tr>
    <tr>
      <td align="center" colspan="2">
      <?
        $sWhere        = "rh30_instit = {$iInstit}";
        $sSqlRhRegime  = $clrhregime->sql_query_file(null, "rh30_codreg, rh30_descr", "rh30_descr", $sWhere);
        $rsSqlRhRegime = $clrhregime->sql_record($sSqlRhRegime);
        db_multiploselect("rh30_codreg", "rh30_descr", "nselecionados", "sselecionados", $rsSqlRhRegime, array(), 5, 250);
      ?>
      </td>
    </tr>
    <tr>
  <tr>
    <td colspan="2" align="center"> 
      <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
    </td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_emite(){
  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  qry = "?prof="+document.form1.prof.value;
  qry += "&ordem="+document.form1.ordem.value;
  qry += "&salario="+document.form1.salario.value;
  qry += "&ano="+document.form1.DBtxt23.value;
  qry += "&mes="+document.form1.DBtxt25.value;
  qry += "&selec="+ selecionados;
  qry += "&lotaini="+document.form1.DBtxt27.value;
  qry += "&lotafim="+document.form1.DBtxt28.value;
  jan = window.open('pes2_carsalariobase002.php' + qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>