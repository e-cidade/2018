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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt22');
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
function js_emite(){
  var data = new Date(document.form1.DBtxt22_ano.value,document.form1.DBtxt22_mes.value,document.form1.DBtxt22_dia.value,0,0,0);
  qry = "?reg="+document.form1.regime.value;
  qry+= "&afastados="+document.form1.afastados.value;
  qry+= "&tipo="+document.form1.tipo.value;
  if(document.form1.selorg){
    if(document.form1.selorg.length > 0){
      qry+="&selorg="+js_campo_recebe_valores();
    }
  }else if(document.form1.orgaoi){
    qry+= "&orgaoi="+document.form1.orgaoi.value;
    qry+= "&orgaof="+document.form1.orgaof.value;
  }
  if(document.form1.sellot){
    if(document.form1.sellot.length > 0){
      qry+="&sellot="+js_campo_recebe_valores();
    }
  }else if(document.form1.lotai){
    qry+= "&lotai="+document.form1.lotai.value;
    qry+= "&lotaf="+document.form1.lotaf.value;
  }
  if(document.form1.selloc){
    if(document.form1.selloc.length > 0){
      qry+="&selloc="+js_campo_recebe_valores();
    }
  }else if(document.form1.locali){
    qry+= "&locali="+document.form1.locali.value;
    qry+= "&localf="+document.form1.localf.value;
  }
  qry+= "&dias="+document.form1.dias.value;
  qry+= "&data="+document.form1.DBtxt22_ano.value+'-' +document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value;
  jan = window.open('pes2_confferias002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
  <?
  if(!isset($tipo)){
    $tipo = "l";
  }
  if(!isset($filtro)){
    $filtro = "i";
  }
  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->usaorga = true;                          // PERMITIR SELEÇÃO DE LOCAL DE TRABALHO
  $geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
  $geraform->usaloca = true;                      // PERMITIR SELEÇÃO DE LOCAL DE TRABALHO

  $geraform->manomes = false;
  $geraform->or1nome = "orgaoi";                  // NOME DO CAMPO DO ÓRGÃO INICIAL
  $geraform->or2nome = "orgaof";                  // NOME DO CAMPO DO ÓRGÃO FINAL
  $geraform->or3nome = "selorg";                  // NOME DO CAMPO DE SELEÇÃO DE ÓRGÃOS 

  $geraform->lo1nome = "lotai";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
  $geraform->lo2nome = "lotaf";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
  $geraform->lo3nome = "sellot";                  // NOME DO CAMPO DE SELEÇÃO DE LOTAÇÕES

  $geraform->tr1nome = "locali";                  // NOME DO CAMPO DO LOCAL INICIAL
  $geraform->tr2nome = "localf";                  // NOME DO CAMPO DO LOCAL FINAL
  $geraform->tr3nome = "selloc";                  // NOME DO CAMPO DE SELEÇÃO DE LOCAIS

  $geraform->trenome = "tipo";                        // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "filtro";                      // NOME DO CAMPO TIPO DE FILTRO

  $geraform->resumopadrao = "l";                      // NOME DO DAS LOTAÇÕES SELECIONADAS
  $geraform->filtropadrao = "i";                      // NOME DO DAS LOTAÇÕES SELECIONADAS

  $geraform->strngtipores = "glot";                     // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                       //                                       l - lotação,
                                                       //                                       o - órgão,
                                                       //                                       m - matrícula,
                                                       //                                       t - local de trabalho

  $geraform->selregime = true;                    // CAMPO PARA ESCOLHA DO REGIME
  $geraform->onchpad = true;                          // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form();
  ?>
      <tr>
	<td nowrap align="right" title="Data de Referência">
	  <strong>Data de Referência :&nbsp;&nbsp;</strong>
	</td>
	<td align="left">
	<?
	$DBtxt22_ano = db_getsession("DB_anousu");
	$DBtxt22_mes = date("m", db_getsession("DB_datausu"));
	$DBtxt22_dia = date("d", db_getsession("DB_datausu"));
	db_inputdata('DBtxt22', $DBtxt22_dia, $DBtxt22_mes, $DBtxt22_ano, true, 'text', 4);
	?>
	</td>
      </tr>
      <tr>
        <td align="right" nowrap title="Número de dias a serem considerados para a comparação." >
        <strong>Dias :&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
           $dias = 730;
           db_input('dias',4,$dias,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap  ><strong>Imprime os Afastados :</strong>
        </td>
        <td align="left">
          <?
          $xvy = array("n"=>"Não","s"=>"Sim");
          db_select('afastados',$xvy,true,4,"");
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