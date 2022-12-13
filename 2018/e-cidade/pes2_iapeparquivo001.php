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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhpessoalmov_classe.php");

db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');

$cldb_rhpessoalmov = new cl_rhpessoalmov;

$aFolhasSalario = array("gerfsal", "gerfres", "gerfcom");
$aFolhas13o     = array("gerfs13");

$aFolhas          = $aFolhasSalario;
$sRub_Permanencia = "'0021', '2021', '4021'";
$sBase            = "('B018','B020','B021')";
$iTipo_Folha      = '1';
$sSigla           = 'r14_';
$sWhere           = '';
try {

 if($demitidos == 'n') {
     $sWhere = ' and rh05_seqpes is null ';
 }
 
 if ($ponto == 'd') { 
   
   $aFolhas     = $aFolhas13o;
   $sSigla      = 'r35_';
   $sBase       = "('B020')";
   $iTipo_Folha = '2';
 }
 
 if (isset($gera)){
 
   if($exporta == 'I'){
     
   $arq = "/tmp/iapep_".$ano.db_formatar($mes,'s','0',2,'e',0).".txt";
    
    $sSqlCriaTemp = "CREATE TEMP TABLE w_arquivo_iapep (valor                          double precision,     ";    
    $sSqlCriaTemp.= "                                   regist                         integer,              ";     
    $sSqlCriaTemp.= "                                   tipo_registro                  character varying,    ";               
    $sSqlCriaTemp.= "                                   ente                           character varying,    ";               
    $sSqlCriaTemp.= "                                   vinculo                        character varying(1), ";                  
    $sSqlCriaTemp.= "                                   matricula                      character varying,    ";               
    $sSqlCriaTemp.= "                                   is_contribuicao_previdenciaria character varying,    ";               
    $sSqlCriaTemp.= "                                   cpf                            character varying,    ";               
    $sSqlCriaTemp.= "                                   provento_desconto              character varying,    ";               
    $sSqlCriaTemp.= "                                   rubrica character              varying,              ";     
    $sSqlCriaTemp.= "                                   descricao_rubrica              character varying,    ";               
    $sSqlCriaTemp.= "                                   valor_rubrica                  numeric(10,2),        ";           
    $sSqlCriaTemp.= "                                   ano_competencia                character varying,    ";               
    $sSqlCriaTemp.= "                                   mes_competencia                character varying,    ";               
    $sSqlCriaTemp.= "                                   tipo_folha                     character varying,    ";               
    $sSqlCriaTemp.= "                                   possui_abono                   character varying,    ";               
    $sSqlCriaTemp.= "                                   possui_molestia                character varying);   ";                  
    db_query($sSqlCriaTemp);
 
     foreach ($aFolhas as $sFolha ) {
 
       if($sFolha == "gerfres") {
        $sSigla = 'r20_';
       }
       if($sFolha == "gerfcom") {
        $sSigla = 'r48_';
       }
       $sInsert = "insert into w_arquivo_iapep ".$cldb_rhpessoalmov->sql_query_arquivo_iapep($sFolha, $sSigla, $sBase, $ano, $mes, $iTipo_Folha, $sRub_Permanencia, $sWhere);
       db_query($sInsert);
     }
   }
   
   $arquivo = fopen($arq,'w'); 

   $sSql    = "select matricula      as rh01_regist,                                                   \n";
   $sSql   .= "       valor_rubrica  as valor,                                                         \n";
   $sSql   .= "       tipo_registro                                                                    \n";
   $sSql   .= "       || ente                                                                          \n";
   $sSql   .= "       || vinculo                                                                       \n";
   $sSql   .= "       || matricula                                                                     \n";
   $sSql   .= "       || is_contribuicao_previdenciaria                                                \n";
   $sSql   .= "       || cpf                                                                           \n";
   $sSql   .= "       || provento_desconto                                                             \n";
   $sSql   .= "       || rubrica                                                                       \n";
   $sSql   .= "       || descricao_rubrica                                                             \n";
   $sSql   .= "       || lpad(translate(trim(to_char(valor_rubrica,'99999999.99')),'.',''),12,'0')     \n";
   $sSql   .= "       || ano_competencia                                                               \n";
   $sSql   .= "       || mes_competencia                                                               \n";
   $sSql   .= "       || tipo_folha                                                                    \n";
   $sSql   .= "       || possui_abono                                                                  \n";
   $sSql   .= "       || possui_molestia as tipo                                                       \n";
   $sSql   .= "  from ( select tipo_registro,                                                          \n";
   $sSql   .= "                ente,                                                                   \n";
   $sSql   .= "                vinculo,                                                                \n";
   $sSql   .= "                matricula,                                                              \n";
   $sSql   .= "                is_contribuicao_previdenciaria,                                         \n";
   $sSql   .= "                cpf,                                                                    \n";
   $sSql   .= "                provento_desconto,                                                      \n";
   $sSql   .= "                rubrica,                                                                \n";
   $sSql   .= "                descricao_rubrica,                                                      \n";
   $sSql   .= "                sum(valor_rubrica) as valor_rubrica,                                    \n";
   $sSql   .= "                ano_competencia,                                                        \n";
   $sSql   .= "                mes_competencia,                                                        \n";
   $sSql   .= "                tipo_folha,                                                             \n";
   $sSql   .= "                possui_abono,                                                           \n";
   $sSql   .= "                possui_molestia                                                         \n";
   $sSql   .= "           from w_arquivo_iapep                                                         \n";
   $sSql   .= "          group by tipo_registro,                                                       \n";
   $sSql   .= "                   ente,                                                                \n";
   $sSql   .= "                   vinculo,                                                             \n";
   $sSql   .= "                   matricula,                                                           \n";
   $sSql   .= "                   is_contribuicao_previdenciaria,                                      \n";
   $sSql   .= "                   cpf,                                                                 \n";
   $sSql   .= "                   provento_desconto,                                                   \n";
   $sSql   .= "                   rubrica,                                                             \n";
   $sSql   .= "                   descricao_rubrica,                                                   \n";
   $sSql   .= "                   ano_competencia,                                                     \n";
   $sSql   .= "                   mes_competencia,                                                     \n";
   $sSql   .= "                   tipo_folha,                                                          \n";
   $sSql   .= "                   possui_abono,                                                        \n";
   $sSql   .= "                   possui_molestia                                                      \n";
   $sSql   .= "                      ) as x                                                            \n";
   $sSql   .= " order by matricula,                                                                    \n";
   $sSql   .= "          rubrica;                                                                      \n";
   $result  = db_query($sSql);
  
   if (pg_numrows($result) == 0) {
    throw new Exception("Não existem dados para os filtros informados.");
   }

   if($exporta == 'C'){
     fputs($arquivo,"ano;mes;matricula; nome; salario; admissao;rescisao; sexo; nascimento; lotacao; descr_lotacao; funcao; descr_funcao; endereco; numero; complemento; bairro; municipio; uf; cep; telefone; instrucao; estado civil; matr_ipe; titulo; zona; secao; cert_reservista; cat_reserv; ctps_numero; ctps_serie; ctps_digito; ctps_uf; pis;cpf;rg; habilitacao; cat_habilit; validade_habilit; padrao; descr_tipo_vinculo; regime; vinculo; banco; agencia; dig_agencia; conta; dig_conta;estr_local;descr_local;trienio;progressao"."\r\n");
   }
   $total_funcionario = 0;
   $total_registro    = 0;
   $total_valor       = 0;
   $matricula_atual   = '';
   for($x = 0;$x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     fputs($arquivo,$tipo."\r\n");
 
     $total_registro ++;
     $total_valor += $valor;
     if($matricula_atual != $rh01_regist){
       $total_funcionario ++;
       $matricula_atual = $rh01_regist;
     }
 
   }
   fputs($arquivo,
         "2"
        ."5"
        ." "
        .db_formatar($total_funcionario,'s','0',6,'e',0)
        .db_formatar($total_registro,'s','0',8,'e',0)
        .str_repeat(' ',28)
        .str_pad(number_format($total_valor,2,"",""), 12, "0", STR_PAD_LEFT)
        .db_formatar($ano,'s','0',4,'e',0)
        .db_formatar($mes,'s','0',2,'e',0)
        .$iTipo_Folha
        );
   fclose($arquivo);
 
 }
} catch (Exception $eException) {
    unset($gera);
    db_msgbox($eException->getMessage());
}


?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
</table>
<center>
<br/><br/>
  <form name="form1" method="post" action="">
    <fieldset style="width: 220px; margin: auto;">
      <legend style="font-weight: bold;">Geração Arquivo do IAPEP</legend>
    <table  align="center">
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de competência" >
        <strong>Competência:&nbsp;&nbsp;</strong>
        </td>
        <td align="left">
          <?
           $ano = db_anofolha();
           db_input('ano',4,$IDBtxt23,true,'text',2,"onkeyup='js_ValidaCampos(this,1,\"Ano\",\"\",\"\",event);'")
          ?>
          &nbsp;/&nbsp;
          <?
           $mes = db_mesfolha();
           db_input('mes',2,$IDBtxt25,true,'text',2,'')
          ?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Tipo de Arquivo:&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr = array('I'=>'Iapep');
	  db_select("exporta",$arr,true,1);
	?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Emite Demitidos:&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr_1 = array('n'=>'Não','s'=>'Sim');
	  db_select("demitidos",$arr_1,true,1);
	?>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Tipo de Folha:&nbsp;&nbsp;<b></td>
	<td align="left">
	<?
	  $arr_2 = array('s'=>'Salário', 'd'=>'13o Salário');
	  db_select("ponto",$arr_2,true,1);
	?>
        </td>
      </tr>
      </table>
      </fieldset>
          <br/>
          <input  name="gera" id="gera" type="submit" value="Processar" onclick="return js_validaDados();" >
     </center>
    </form>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
$('mes').maxLength = 2;
$('ano').maxLength = 4;
function js_validaDados() {
  if ($F('ano') == '') {
    alert('Campo Ano é de preenchimento obrigatório.');
    $('ano').focus();
    return false
  }
  if ($F('mes') == '') {
    alert('Campo Mês é de preenchimento obrigatório.');
    $('mes').focus();
    return false
  }
  if ($F('ano').length < 4) {
    alert('Ano informado inválido.');
    $('ano').value = '';
    $('ano').focus();
    return false;
  }
  if ($F('mes').length < 2) {
    alert('Mês informado inválido.');
    $('mes').value = '';
    $('mes').focus();
    return false;
  }
  if ($F('mes') > 12 || $F('mes') < 1) {
    alert('Mês informado inválido.');
    $('mes').value = '';
    $('mes').focus();
    return false;
  }
}
  <?
  if(isset($gera)){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>
</script>