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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_diarioavaliacao_classe.php");
require_once("classes/db_transfescolarede_classe.php");
require_once("classes/db_transfaprov_classe.php");
require_once("classes/db_alunotransfturma_classe.php");
require_once("classes/db_regencia_classe.php");
require_once("classes/db_conceito_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
db_app::import("educacao.ArredondamentoNota");
db_postmemory($HTTP_POST_VARS);
$resultedu= eduparametros(db_getsession("DB_coddepto"));

$sMascaraInstituicacao = str_replace("0", "9", ArredondamentoNota::getMascara(db_getsession("DB_anousu")));
$oDaoAvaliacaoRegra    = db_utils::getDao("avaliacaoestruturanota");

$escola = db_getsession("DB_coddepto");
$cldiarioavaliacao = new cl_diarioavaliacao;
$cltransfescolarede = new cl_transfescolarede;
$cltransfaprov = new cl_transfaprov;
$clalunotransfturma = new cl_alunotransfturma;
$clregencia = new cl_regencia;
$clconceito = new cl_conceito;
$db_opcao = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
$clrotulo->label("ed72_i_escola");
$result = $clregencia->sql_record($clregencia->sql_query("","ed59_i_turma,ed232_c_descr,ed57_i_calendario,ed52_i_ano,ed59_i_serie","","ed59_i_codigo = $regencia"));
db_fieldsmemory($result,0);
if(isset($alterar)){
 $result = $cldiarioavaliacao->sql_record("UPDATE diarioavaliacao SET
                                            ed72_i_escola = $ed72_i_escola,
                                            ed72_c_tipo = '$ed72_c_tipo'
                                           WHERE ed72_i_codigo = $diarioavaliacao
                                          ");
 db_msgbox("Alteração Efetuada com Sucesso!");
 ?>
 <script>
  parent.parent.iframe_A<?=$ed41_i_codigo?>.location.href = "edu1_diarioavaliacao001.php?regencia=<?=$regencia?>&ed41_i_codigo=<?=$ed41_i_codigo?>";
  parent.db_iframe_tiponota.hide();
 </script>
 <?
 exit;
}
if(isset($retornar)){
 $result = $cldiarioavaliacao->sql_record("UPDATE diarioavaliacao SET
                                            ed72_i_escola = $escola,
                                            ed72_c_tipo = 'M'
                                           WHERE ed72_i_codigo = $diarioavaliacao
                                          ");
 db_msgbox("Alteração Efetuada com Sucesso!");
 ?>
 <script>
  parent.parent.iframe_A<?=$ed41_i_codigo?>.location.href = "edu1_diarioavaliacao001.php?regencia=<?=$regencia?>&ed41_i_codigo=<?=$ed41_i_codigo?>";
  parent.db_iframe_tiponota.hide();
 </script>
 <?
 exit;
}
if(isset($converter)){
 $update = " ed72_c_convertido = 'N'";
 if(isset($tpdestino) && trim($tpdestino)=="NOTA"){
  $aprovperiodo = $aprovperiodo==""?"null":$aprovperiodo;
  $update .= " ,ed72_i_valornota = $aprovperiodo ";
 }elseif(isset($tpdestino) && trim($tpdestino)=="NIVEL"){
  $update .= " ,ed72_c_valorconceito = '$aprovperiodo' ";
 }elseif(isset($tpdestino) && trim($tpdestino)=="PARECER"){
  $update .= " ,ed72_t_parecer = '".strtoupper($aprovperiodo)."' ";
 }
 $sql = "UPDATE diarioavaliacao SET $update WHERE ed72_i_codigo = $diarioavaliacao";
 $result = pg_query($sql);
 db_msgbox("Alteração Efetuada com Sucesso!");
 ?>
 <script>
  parent.parent.iframe_A<?=$ed41_i_codigo?>.location.href = "edu1_diarioavaliacao001.php?regencia=<?=$regencia?>&ed41_i_codigo=<?=$ed41_i_codigo?>";
  parent.db_iframe_tiponota.hide();
 </script>
 <?
 exit;
}
$sql1 = "SELECT ed53_d_inicio,ed53_d_fim,ed95_i_aluno,ed95_i_regencia,ed72_i_escola,ed37_c_tipo as tpdestino,
                ed37_i_variacao as variadestino,ed37_i_maiorvalor as maiordestino,ed37_i_menorvalor as menordestino,ed53_i_periodoavaliacao,ed95_c_encerrado
         FROM diarioavaliacao
          inner join diario on ed95_i_codigo = ed72_i_diario
          inner join regencia on ed59_i_codigo = ed95_i_regencia
          inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao
          inner join formaavaliacao on ed37_i_codigo = ed41_i_formaavaliacao
          inner join calendario on ed52_i_codigo = ed95_i_calendario
          inner join periodocalendario on ed53_i_calendario = ed52_i_codigo
         WHERE ed72_i_codigo = $diarioavaliacao
         AND ed72_i_procavaliacao = $ed41_i_codigo
         AND ed53_i_periodoavaliacao = ed41_i_periodoavaliacao
        ";
$result1 = pg_query($sql1);
db_fieldsmemory($result1,0);
if(trim($tpdestino)=="NOTA"){
 $campoaprov = "ed72_i_valornota";
 $ed72_i_valornota = $aprovperiodo;
}elseif(trim($tpdestino)=="NIVEL"){
 $campoaprov = "ed72_c_valorconceito";
 $ed72_c_valorconceito = $aprovperiodo;
}else{
 $campoaprov = "ed72_t_parecer";
 $ed72_t_parecer = $aprovperiodo;
}
$result2 = $cltransfescolarede->sql_record($cltransfescolarede->sql_query("","ed103_i_codigo,ed102_i_aluno",""," ed103_i_escolaorigem = $ed72_i_escola AND ed102_i_aluno = $ed95_i_aluno"));
if($cltransfescolarede->numrows>0){
 db_fieldsmemory($result2,0);
}
$result3 = $clalunotransfturma->sql_record($clalunotransfturma->sql_query("","ed69_i_codigo,turma.ed57_i_codigo as codturmaorig,turma.ed57_c_descr as trocaturmaorigem ",""," turmadestino.ed57_i_codigo = $ed59_i_turma AND ed60_i_aluno = $ed95_i_aluno"));
if($clalunotransfturma->numrows>0){
 db_fieldsmemory($result3,0);
}
$result4 = $cltransfaprov->sql_record($cltransfaprov->sql_query("","ed251_i_codigo",""," ed251_i_diariodestino = $diarioavaliacao "));
//db_fieldsmemory($result4,0);
if(($cltransfescolarede->numrows>0) || ($clalunotransfturma->numrows>0&&$cltransfescolarede->numrows>0) || $cltransfaprov->numrows>0){
 $sql1 = "SELECT ed37_c_tipo as tporigem,ed37_i_maiorvalor as maiororigem,ed37_i_menorvalor as menororigem,
                 ed72_i_valornota as ntorigem,ed72_t_parecer as prorigem,ed72_c_valorconceito as ctorigem,
                 ed232_c_descr as discorigem,ed41_i_formaavaliacao
          FROM diarioavaliacao
           inner join diario on ed95_i_codigo = ed72_i_diario
           inner join regencia on ed59_i_codigo = ed95_i_regencia
           inner join turma on ed57_i_codigo = ed59_i_turma
           inner join disciplina on ed12_i_codigo = ed59_i_disciplina
           inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
           inner join procavaliacao on ed41_i_codigo = ed72_i_procavaliacao
           inner join formaavaliacao on ed37_i_codigo = ed41_i_formaavaliacao
           left join transfaprov on ed251_i_diarioorigem = ed72_i_codigo
          WHERE ed251_i_diariodestino = $diarioavaliacao
         ";
 $result1 = pg_query($sql1);
 if(pg_numrows($result1)>0){
  db_fieldsmemory($result1,0);
 }else{
  if($clalunotransfturma->numrows>0){
   $sql2 = "SELECT ed37_c_tipo as tporigem,ed37_i_maiorvalor as maiororigem,ed37_i_menorvalor as menororigem,ed41_i_formaavaliacao
            FROM turma
             inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo
             inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
             inner join procedimento on ed40_i_codigo = ed220_i_procedimento
             inner join procavaliacao on ed41_i_procedimento = ed40_i_codigo
             inner join formaavaliacao on ed37_i_codigo = ed41_i_formaavaliacao
            WHERE ed57_i_codigo = $codturmaorig
            AND ed223_i_serie = $ed59_i_serie
            AND ed41_i_periodoavaliacao = $ed53_i_periodoavaliacao
           ";
   $result2 = pg_query($sql2);
   @db_fieldsmemory($result2,0);
   $ntorigem = $aprovperiodo;
   $prorigem = $aprovperiodo;
   $ctorigem = $aprovperiodo;
  }
 }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 9;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
$campos = "case when ed72_c_tipo = 'M'
            then escolaorigem.ed18_i_codigo else escolaproc.ed82_i_codigo end as ed72_i_escola,
           case when ed72_c_tipo = 'M'
            then escolaorigem.ed18_c_nome else escolaproc.ed82_c_nome end as nomeescola,
           case when ed72_c_tipo = 'M'
            then censomunic.ed261_c_nome else ed261_c_nome end as ed72_c_cidade,
           case when ed72_c_tipo = 'M'
             then censouf.ed260_c_sigla else ed260_c_sigla end as ed72_c_estado,
           case when ed72_c_tipo = 'M'
            then 'ESCOLA DA REDE' else 'FORA DA REDE' end as ed72_c_tipodescr,
           ed72_c_tipo,ed95_c_encerrado,ed47_i_codigo,ed47_v_nome,
           ed72_i_valornota,ed72_c_valorconceito,ed72_t_parecer,ed72_c_convertido
          ";
$result = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("",$campos,""," ed72_i_codigo = $diarioavaliacao"));
db_fieldsmemory($result,0);
$lMigrarMascarar = false;
if ($ed72_c_tipo == 'M') {

  /**
   * Pesquisamos os dados da mascara da nota da escola destino.
   * caso as duas mascaras (escola destino, e escola origem sejam diferentes, devemos
   * obrigar o usuário a convertar a nota para o padrao da escola.
   */
  $sWhereMascara         = "ed315_escola = {$ed72_i_escola}";
  $sMascaraOrigem        = '';
  $sSqlMascaraNotaOrigem = $oDaoAvaliacaoRegra->sql_query(null, "db77_estrut", null, $sWhereMascara);
  $rsMascaraOrigem       = $oDaoAvaliacaoRegra->Sql_record($sSqlMascaraNotaOrigem);
  if ($oDaoAvaliacaoRegra->numrows > 0) {

    $sMascaraOrigem  = db_utils::fieldsMemory($rsMascaraOrigem, 0)->db77_estrut;
    $sMascaraOrigem = str_replace("0", "9",$sMascaraOrigem);

  }

  if ($sMascaraOrigem != $sMascaraInstituicacao) {
    $lMigrarMascarar = true;
  }
}
?>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td class='titulo'>&nbsp;&nbsp;
   <?=$ed47_i_codigo?> - <?=$ed47_v_nome?>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   Disciplina <?=@$ed232_c_descr?> - <?=$descrperiodo?>
  </td>
 </tr>
 <tr>
  <td align="center">
   <br>
   <fieldset style="width:95%"><legend><b>Origem Atual - <?=$ed72_i_escola!=$escola||$ed72_c_tipo=="F"?"NOTA EXTERNA":"NOTA INTERNA"?></b></legend>
   <table width="98%" border="0" cellspacing="1" cellpadding="3" align="center">
    <tr>
     <td width="15%">
      <b>Escola:</b>
     </td>
     <td>
      <?db_input('ed72_i_escola',15,$Ied72_i_escola,true,'text',3,"")?>
      <?db_input('nomeescola',50,@$Inomeescola,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Tipo:</b>
     </td>
     <td>
      <?db_input('ed72_c_tipodescr',20,@$Ied72_c_tipodescr,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Município:</b>
     </td>
     <td>
      <?db_input('ed72_c_cidade',40,@$Ied72_c_cidade,true,'text',3,"")?>
      <b>Estado:</b>
      <?db_input('ed72_c_estado',2,@$Ied72_c_estado,true,'text',3,"")?>
     </td>
    </tr>
    <?if($cltransfaprov->numrows && $ed72_i_escola!=$escola){
    $intervaloorigem = isset($menororigem)?$menororigem." a ".$maiororigem:"";
    ?>
    <tr>
     <td>
      <b>Forma de Avaliação:</b>
     </td>
     <td>
     <?db_input("tporigem",10,@$tporigem,true,'text',3,"")?>
     <?if(@$tporigem=="NOTA"){?>
      <?db_input("intervaloorigem",10,@$intervaloorigem,true,'text',3,"")?>
     <?}?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Aproveitamento:</b>
     </td>
     <td>
     <?
     if(trim(@$tporigem)=="NOTA"){
      $aprovorigem = @$ntorigem;
      echo db_input("aprovorigem",10,@$aprovorigem,true,'text',3,"");
     }elseif(trim(@$tporigem)=="NIVEL"){
      $aprovorigem = @$ctorigem;
      $result3 = $clconceito->sql_record($clconceito->sql_query("","ed39_c_conceito","ed39_i_sequencia","ed39_i_formaavaliacao = $ed41_i_formaavaliacao"));
      ?>
      <select name="aprovorigem" style="background:#DEB887;width:50px;height:17px;font-size:10px;text-align:center;padding:0px;" disabled>
       <option value=""></option>
       <?for($z=0;$z<$clconceito->numrows;$z++){
        db_fieldsmemory($result3,$z);?>
        <option value="<?=trim($ed39_c_conceito)?>" <?=trim($ed39_c_conceito)==trim($aprovorigem)?"selected":""?>><?=trim($ed39_c_conceito)?></option>
       <?}?>
      </select>
      <?
     }else{
      $aprovorigem = @$prorigem;
      echo db_textarea('aprovorigem',3,50,$aprovorigem,true,'text',3,"");
     }
     ?>
     </td>
    </tr>
    <?}?>
    <?if(($ed72_c_tipo=="F" || $ed72_i_escola==$escola) || ($ed72_c_tipo=="M" && $ed72_i_escola!=$escola && $cltransfaprov->numrows==0)){
     $db_botao = false;
     ?>
     <tr>
      <td colspan="2">
       <?db_ancora("<b>Modificar Escola de Origem</b>","js_pesquisaed72_i_escolafora(true);",$ed95_c_encerrado=="S"?3:$db_opcao);?>
      </td>
     </tr>
     <tr>
      <td colspan="2">
      <input name='alterar' type='submit' value='Alterar' <?=$ed95_c_encerrado=="S"||$db_botao==false?"disabled":""?> onclick="return js_validar(<?=str_replace('-','',$ed53_d_fim)?>);">
      <?if($ed72_i_escola!=$escola){?>
       <input name='retornar' type='submit' value='Retornar para Nota Interna' <?=$ed95_c_encerrado=="S"?"disabled":""?> onclick="return js_validar(<?=str_replace('-','',$ed53_d_fim)?>);">
      <?}?>
      <input name='fechar' type='button' value='Fechar' onclick='parent.db_iframe_tiponota.hide();'>
     </tr>
    <?}else{
     $db_botao = false;
    }?>
    </table>
   </fieldset>
   <?if($cltransfaprov->numrows && $ed72_c_tipo=="M" && $ed72_i_escola!=$escola){
   $intervalodestino = $menordestino." a ".$maiordestino;
   ?>
   <fieldset style="width:95%"><legend><b>Aproveitamento nesta escola - <?=db_getsession("DB_nomedepto")?></b></legend>
   <table width="98%" border="0" cellspacing="1" cellpadding="3" align="center">
    <tr>
     <td colspan="2">
      <?if((@$tporigem==$tpdestino && $maiordestino!=@$maiororigem)||@$tporigem!=$tpdestino||isset($ed69_i_codigo)){?>
       <?if($ed72_c_convertido=="S"){?>
        <?if(isset($ed69_i_codigo)){?>
         <font color="red"><b>* Forma de avaliação diferente da origem (Turma <?=$trocaturmaorigem?> desta escola) - Aproveitamento precisa ser convertido</b></font>
        <?}else{?>
         <font color="red"><b>* Forma de avaliação diferente da origem - Aproveitamento precisa ser convertido</b></font>
        <?}?>
       <?}else{?>
        <font color="red"><b>* Aproveitamento já convertido</b></font>
       <?}?>
      <?}?>
     </td>
    </tr>
    <tr>
     <td width="15%">
      <b>Forma de Avaliação:</b>
     </td>
     <td>
     <?db_input("tpdestino",10,@$tpdestino,true,'text',3,"")?>
     <?if($tpdestino=="NOTA"){?>
      <?db_input("intervalodestino",10,@$intervalodestino,true,'text',3,"")?>
     <?}?>
     </td>
    </tr>
    <tr>
     <td>
      <b>Aproveitamento:</b>
     </td>
     <td>
     <?
     $disabled = $aprovperiodo==""?"":"disabled";
     $habilitar = (@$tporigem==$tpdestino && $maiordestino!=@$maiororigem)||($lMigrarMascarar)
                   ||@$tporigem!=$tpdestino || $aprovperiodo==""|| isset($ed69_i_codigo)?1:3;
     if(trim($tpdestino) == "NOTA") {
      $aprovperiodo = (@$tporigem==$tpdestino && $maiordestino!=@$maiororigem)||@$tporigem!=$tpdestino||$aprovperiodo==""||isset($ed69_i_codigo)?"":$aprovperiodo;
      echo db_input("aprovperiodo",10,@$aprovperiodo,true,'text',$habilitar,"onchange='js_formatanota(this,$variadestino,$menordestino,$maiordestino)'");
     }elseif(trim($tpdestino)=="NIVEL"){
      $result3 = $clconceito->sql_record($clconceito->sql_query("","ed39_c_conceito","ed39_i_sequencia","ed39_i_formaavaliacao = $ed41_i_formaavaliacao"));
      ?>
      <select name="aprovperiodo" style="background:#DEB887;width:50px;height:17px;font-size:10px;text-align:center;padding:0px;" <?=$disabled?>>
       <option value=""></option>
       <?for($z=0;$z<$clconceito->numrows;$z++){
        db_fieldsmemory($result3,$z);?>
        <option value="<?=trim($ed39_c_conceito)?>" <?=trim($ed39_c_conceito)==trim($aprovperiodo)?"selected":""?>><?=trim($ed39_c_conceito)?></option>
       <?}?>
      </select>
      <?
     }else{
      echo db_textarea('aprovperiodo',3,50,$aprovperiodo,true,'text',$habilitar,"");
     }
     ?>
     </td>
    </tr>
    <tr>
     <td colspan="2"">
       <?if((@$tporigem==$tpdestino && $maiordestino!=@$maiororigem) || @$tporigem!=$tpdestino || $aprovperiodo=="" || isset($ed69_i_codigo) || $lMigrarMascarar){?>
        <input type="submit" value="Alterar" name="converter" onclick="return js_conversao()" <?=$ed95_c_encerrado=="S"?"disabled":""?>>
       <?}?>
       <input name='fechar' type='button' value='Fechar' onclick='parent.db_iframe_tiponota.hide();'>
     <td>
    </tr>
   </table>
   </fieldset>
   <?}?>
   <table>
    <tr>
     <td>
     <?db_input('ed72_c_tipo',2,@$Ied72_c_tipo,true,'hidden',3,"")?>
     <?db_input('validar',20,@$Ivalidar,true,'hidden',3,"")?>
     <?db_input('datamatricula',20,@$datamatricula,true,'hidden',3,"")?>
     <?db_input('maiororigem',20,@$maiororigem,true,'hidden',3,"")?>
     <?db_input('maiordestino',20,@$maiordestino,true,'hidden',3,"")?>
     <?db_input('tporigem',20,@$tporigem,true,'hidden',3,"")?>
     <?db_input('tpdestino',20,@$tpdestino,true,'hidden',3,"")?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<?
if($ed72_i_valornota=="" && $ed72_c_valorconceito=="" && $ed72_t_parecer==""){
 ?><script>document.form1.validar.value="T";</script><?
}
?>
<script>
var sTipoTrans      = '<?=$ed72_c_tipo?>';
var sTipoAvaliacao  = '<?=$tpdestino?>';
function js_pesquisaed72_i_escolafora(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_escolafora','func_escolaforanota.php?funcao_js=parent.js_mostraescolafora1|ed18_i_codigo|ed18_c_nome|ed261_c_nome|ed260_c_sigla|tipoescoladescr|tipoescola','Pesquisa de Escolas Fora da Rede',true,0,0,screen.availWidth-75,screen.availHeight);
 }
}
function js_mostraescolafora1(chave1,chave2,chave3,chave4,chave5,chave6){
 document.form1.ed72_i_escola.value = chave1;
 document.form1.nomeescola.value = chave2;
 document.form1.ed72_c_cidade.value = chave3;
 document.form1.ed72_c_estado.value = chave4;
 document.form1.ed72_c_tipodescr.value = chave5;
 document.form1.ed72_c_tipo.value = chave6;
 document.form1.alterar.disabled = false;
 db_iframe_escolafora.hide();
}
function js_validar(datafinal){
 if(document.form1.validar.value=="T" && (document.form1.ed72_i_escola.value!=<?=db_getsession("DB_coddepto")?> || document.form1.ed72_c_tipo.value=="F")){
  alert("Aproveitamento está em branco.\nPrimeiro informe e salve algum aproveitamento para este aluno neste período.");
  parent.parent.iframe_A<?=$ed41_i_codigo?>.location.href = "edu1_diarioavaliacao001.php?regencia=<?=$regencia?>&ed41_i_codigo=<?=$ed41_i_codigo?>";
  parent.db_iframe_tiponota.hide();
  return false;
 }
 datamatr = document.form1.datamatricula.value;
 datamatr = datamatr.replace("-","");
 datamatr = datamatr.replace("-","");
 if(datamatr<datafinal && (document.form1.ed72_i_escola.value!=<?=db_getsession("DB_coddepto")?> || document.form1.ed72_c_tipo.value=="F")){
  data_matricula = document.form1.datamatricula.value.split('-');
  data_matricula = data_matricula[2]+"/"+data_matricula[1]+"/"+data_matricula[0];
  if(confirm("Aproveitamento deste aluno neste período deve constar como NOTA INTERNA,\npois a data de sua matrícula ("+data_matricula+")\nestá dentro das datas deste período( <?=db_formatar($ed53_d_inicio,'d')?> até <?=db_formatar($ed53_d_fim,'d')?> )\n\nDescartar aviso e confirmar alteração? ")){
   return true;
  }else{
   return false;
  }
 }
 return true;
}
function js_conversao(valor){
 return true;
}

function js_cent(amount) {
 //retorna o valor com 2 casas decimais
 return amount;
}

function js_formatanota(campo,variacao,menor,maior){
 if(campo.value!=""){
  valor = campo.value.replace(",",".");
  var expre = new RegExp("[^0-9\.]+");
  if(!valor.match(expre)){
   if(valor<menor || valor>maior){
    alert("Nota deve ser entre "+menor+" e "+maior+"!");
    campo.value = "";
    campo.focus();
   }else{
    variacaoant = variacao;
    valorant = valor;
    if(variacao<1){
     partevariacao = variacao.toString();
     partevariacao = partevariacao.split(".");
     if(partevariacao[1].length==1){
      variacao = partevariacao[1]+"0";
     }else{
      variacao = partevariacao[1];
     }
     partevalor = valor.toString();
     partevalor = partevalor.split(".");
     if(partevalor[1]!=undefined){
      if(partevalor[1].length==1){
       valor = partevalor[1]+"0";
      }else{
       valor = partevalor[1];
      }
     }else{
      valor = "00";
     }
     valor = parseInt(valor);
     variacao = parseInt(variacao);
    }
    if((valor%variacao)==0){
     variacao = variacaoant;
     valor = valorant;
     valor = parseFloat(valor);
     campo.value = js_cent(valor);
     adiante = js_cent(valor);
    }else{
     variacao = variacaoant;
     alert("Intervalos da Nota devem ser de "+variacao+"");
     campo.value = "";
     campo.focus();
    }
   }
  }else{
   alert("Nota deve ser um número!");
   campo.value = "";
   campo.focus();
  }
 }
}
if (sTipoAvaliacao == 'NOTA') {
  js_observeMascaraNota($('aprovperiodo'), '<?=ArredondamentoNota::getMascara(db_getsession("DB_anousu"))?>');
}
</script>