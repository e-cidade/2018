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

include("fpdf151/pdf.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhdepend_classe.php");
include("libs/db_sql.php");
db_postmemory($HTTP_POST_VARS);
$clrhpessoal = new cl_rhpessoal;
$clrhdepend = new cl_rhdepend;
$clsql  = new cl_gera_sql_folha;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("r70_descr");

if(isset($emite)){
  $siglas = "r14";
  $siglac = "r48";
  $rubbase = "R985";
  $rubdesc = "R901";

  $clsql->inicio_rh = false;
  $clsql->usar_ger  = true;
  $sqlValors = $clsql->gerador_sql($siglas, $ano, $mes, null, null, "coalesce(sum(#s#_valor),0)", "", "#s#_pd != 3 and #s#_pd = 1 and #s#_regist = rh01_regist ");
  $sqlValorc = $clsql->gerador_sql($siglac, $ano, $mes, null, null, "coalesce(sum(#s#_valor),0)", "", "#s#_pd != 3 and #s#_pd = 1 and #s#_regist = rh01_regist ");

  $complSQL = "(coalesce(prev.".$siglas."_valor,0) + coalesce(prevc.".$siglac."_valor,0)) as baseprev,";
  $complSQL.= "(coalesce(descon.".$siglas."_valor,0) + coalesce(desconc.".$siglac."_valor,0)) as descprev,";
  $complSQL.= "(coalesce(desconc.".$siglac."_quant,0)) as quant_descprevc,";
  $complSQL.= "(coalesce(prev.".$siglas."_valor / 100 * ".$percp.",0) + coalesce(prevc.".$siglac."_valor / 100 * ".$percp.",0)) as content,";
  $complSQL.= "(coalesce((".$sqlValors."),0) + coalesce((".$sqlValorc."),0)) as proventos,";

  if($folha == "d"){
    $siglas = "r35";
    $siglac = "";
    $rubbase = "R986";
    $rubdesc = "R902";

    $complSQL = "(coalesce(prev.".$siglas."_valor,0)) as baseprev,";
    $complSQL.= "(coalesce(descon.".$siglas."_valor,0)) as descprev,";
    $complSQL.= "(coalesce(prev.".$siglas."_valor / 100 * ".$percp.",0)) as content,";
    $complSQL.= "(coalesce((".$sqlValors."),0)) as proventos,";
  }

  $complSQL.= "(coalesce(descon.".$siglas."_quant,0)) as quant_descprev";

  $camposSQL = " rh01_regist, ";
  $camposSQL.= " rh01_admiss, ";
  $camposSQL.= " rh01_nasc, ";
  $camposSQL.= " rh02_lota, ";
  $camposSQL.= " rh05_recis, ";
  $camposSQL.= " case rh30_vinculo ";
  $camposSQL.= "      when 'A' then '01' ";
  $camposSQL.= "      when 'I' then '02' ";
  $camposSQL.= "      when 'P' then '03' ";
  $camposSQL.= "      else 'ER' ";
  $camposSQL.= " end as situacao, ";
  $camposSQL.= " z01_nome, ";
  $camposSQL.= " z01_cgccpf, ";
  $camposSQL.= " z01_ender, ";
  $camposSQL.= " z01_bairro, ";
  $camposSQL.= " z01_munic, ";
  $camposSQL.= " z01_uf, ";
  $camposSQL.= " z01_cep, ";
  $camposSQL.= $complSQL;

  $arr_sql = Array(0=>$folha,1=>$rubbase,2=>$rubdesc,3=>$siglas,4=>$siglac);
//echo "<BR> 1  passou aqui !!! __> ".$clrhpessoal->sql_query_relPREVID(null,$camposSQL," rh01_regist limit 5"," rh02_tbprev = 1 and rh05_recis is null ",null,null,$arr_sql);
  $result_dados_imprime = $clrhpessoal->sql_record($clrhpessoal->sql_query_relPREVID(null,$camposSQL," rh01_regist limit 5"," rh02_tbprev = 1 and rh05_recis is null ",null,null,$arr_sql));

  if($clrhpessoal->numrows > 0){

//echo "<BR> 2 passou aqui !!!";
    $result_depend = $clrhdepend->sql_record(
                                             $clrhdepend->sql_query_relPREVID(
                                                                              null,
                                                                              "
                                                                               rh31_regist,
                                                                               rh31_nome,
                                                                               rh31_dtnasc,
                                                                               case rh31_gparen
                                                                                    when 'F' then '01'
                                                                                    when 'C' then '02'
                                                                                    when 'P' then '03'
                                                                                    when 'M' then '03'
                                                                               else '90'
                                                                               end as rh31_gparen
                                                                              ",
                                                                              "",
                                                                              "
                                                                                   rh02_anousu = $ano
                                                                               and rh02_mesusu = $mes
                                                                               and rh02_tbprev = 1
                                                                               and rh05_recis is null
                                                                              "
                                                                             )
                                            );

//echo "<BR> $result_depend";exit;

    if($clrhdepend->numrows > 0){
      for($i=0; $i<$clrhpessoal->numrows; $i++){
        db_fieldsmemory($result_dados_imprime, $i);
      }
    }else{
      $erro_msg = "Não existem lançamentos de dependentes para este ano / mês.";
    }
  }else{
    $erro_msg = "Não existem lançamentos para este ano / mês.";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" border="0">
  <form name="form1" method="post" action="">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <tr>
    <td>
      <strong>Ano / Mês:</strong>
    </td>
    <td>
      <?
      $ano = db_anofolha();
      db_input('ano',4,1,true,'text',1,"");
      ?>
      <b>&nbsp;/&nbsp;</b>
      <?
      $mes = db_mesfolha();
      db_input('mes',2,1,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <strong>Tipo de folha:</strong>
    </td>
    <td>
      <?
      $arr_folha = array("s"=>"Salário","d"=>"Décimo");
      db_select("folha", $arr_folha, true, 1);
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <strong>Percentual patronal:</strong>
    </td>
    <td>
      <?
      db_input('percp',5,4,true,'text',1,"");
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input name="emite" id="emite" type="submit" value="Processar" onclick="return js_verificacampos();">
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
function js_verificacampos(){
  retorno = true;
  if(document.form1.ano.value == ""){
    alert("Informe o ano de competência.");
    document.form1.ano.focus();
    retorno = false;
  }else if(document.form1.mes.value == ""){
    alert("Informe o mês de competência.");
    document.form1.mes.focus();
    retorno = false;
  }else if(document.form1.percp.value == ""){
    alert("Informe o percentual patronal.");
    document.form1.percp.focus();
    retorno = false;
  }
  return retorno;
}
function js_detectaarquivo(arquivo,pdf){
  listagem = arquivo+"#Download arquivo TXT (pagamento eletrônico)|";
  listagem+= pdf+"#Download relatório";
  js_montarlista(listagem,"form1");
}
</script>
<?
if(isset($emite)){
  if(isset($erro_msg)){
/*    db_msgbox($erro_msg);*/
  }else{
    /*
    echo "
          <script>js_detectaarquivo('/tmp/blvreme.txt','/tmp/blvreme.pdf');</script>
         ";
    */
  }
}
?>