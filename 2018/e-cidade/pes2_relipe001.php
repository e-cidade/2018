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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhlota_classe.php");
$clrhlota = new cl_rhlota;
?>

<html>
<head>
 <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
 <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
 <meta http-equiv="Expires" CONTENT="0">
 <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
 <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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

<br><br>

<div align="center">
 <fieldset style="width: 850px;">
 <legend><b>Relatório do IPERGS</b></legend>
  <table align="center" cellspacing="0" cellpadding="1">
   <form name="form1" method="post" action="" onsubmit="return js_verifica();">
   
    <tr>
      <td width="15%" title="Digite o Ano / Mes de competência"><b>Ano / Mês :</b></td>
      <td>
        <?
         $ano = db_anofolha();
         db_input('ano',4,1,true,'text',2,'')
        ?>
        &nbsp;/&nbsp;
        <?
         $mes = db_mesfolha();
         db_input('mes',2,1,true,'text',2,'')
        ?>
      </td>
    </tr>
    
    <tr>
      <td nowrap title="Tipo de relatório" ><b>Tipo :</b></td>
      <td>
        <?
         $aTipoRelatorio = array("m"=>"Manutenção", "i"=>"Inclusão", "t"=>"Todos","c"=>"Cadastro");
         db_select('tipo',$aTipoRelatorio,true,1,"style='width: 108px;'");
        ?>
      </td>
    </tr>
    
    <tr>
      <td><b>Unifica I.P.E.:</b></td>
      <td>
       <?
        $aUnica  = Array('f'=>'Não', 't'=>'Sim');
        db_select("unifica_ipe",$aUnica,true,1, "style='width: 108px;'");
       ?>
      </td>
    </tr>
    
    <tr>
      <td colspan="2">
      <fieldset>
       <legend><b>Lotações</b></legend>
       <?
         $sSqlLotacaoes = $clrhlota->sql_query_file(null, "r70_estrut, r70_estrut || ' - ' || r70_descr as r70_descr", "r70_estrut", " r70_instit = ".db_getsession('DB_instit'));
         $rsLotacoes    = $clrhlota->sql_record($sSqlLotacaoes);
         db_multiploselect("r70_estrut","r70_descr", "", "", $rsLotacoes, array(), 16, 380);
       ?>
       </fieldset>
      </td>
    </tr>
    
  </form>
  </table>
 </fieldset>
 <br>
 <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
</div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
function js_emite() {

  if ($F("ano") == "" || $F("mes") == "") {
    alert('Informe o Ano e Mes');
    return false;
  }  
  
  var sParametros  = "";
      sParametros += "iAno="+$F("ano");
      sParametros += "&iMes="+$F("mes");
      sParametros += "&sTipo="+$F("tipo");
      sParametros += "&lUnificado="+($F("unifica_ipe")=='f'?false:true);
      sParametros += '&sListaLotacoes='+js_db_multiploselect_retornaselecionados();
  jan = window.open("pes2_relipe002.php?"+sParametros,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>