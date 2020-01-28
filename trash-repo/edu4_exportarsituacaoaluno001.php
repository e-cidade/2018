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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_calendarioescola_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_escoladiretor_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;
$clcalendarioescola = new cl_calendarioescola;
$clmatricula = new cl_matricula;
$clescoladiretor = new cl_escoladiretor;
$clrotulo->label("ed52_i_ano");
$clrotulo->label("ed52_d_inicio");
$clrotulo->label("ed52_d_fim");
$escola = db_getsession("DB_coddepto");
$db_opcao = 1;
$db_botao = true;
function RetiraAcento($string){
 set_time_limit(240);
 $acentos = 'áéíóúÁÉÍÓÚàÀÂâÊêôÔüÜïÏöÖñÑãÃõÕçÇäÄ\'';
 $letras  = 'AEIOUAEIOUAAAAEEOOUUIIOONNAAOOCCAA ';
 $new_string = '';
 for($x=0; $x<strlen($string); $x++){
  $let = substr($string, $x, 1);
  for($y=0; $y<strlen($acentos); $y++){
   if($let==substr($acentos, $y, 1)){
    $let=substr($letras, $y, 1);
    break;
   }
  }
  $new_string = $new_string . $let;
 }
 return $new_string;
}
function TiraCaracteres(&$string,$tipo){
 // $string = string a ser retirados os caracteres
 // $tipo = tipo de validação: 1, 2, 3 e 4
 //
 // 1 - Somente Letras e espaço
 // 2 - Somente Números, Letras, espaço, ª, º e traço
 // 3 - Somente Números, Letras, espaço, ª, º , ponto, virgula, barra e traço
 // 4 - Somente Números, Letras, arroba, ponto, sublinha e traço (email)
 $string = str_replace(chr(92),"",$string);// contrabarra -> \
 $string = str_replace(";","",$string);
 $string = str_replace(":","",$string);
 $string = str_replace("?","",$string);
 $string = str_replace("'","",$string);
 $string = str_replace(chr(34),"",$string);// aspas dupla -> "
 $string = str_replace("!","",$string);
 $string = str_replace("#","",$string);
 $string = str_replace("$","",$string);
 $string = str_replace("%","",$string);
 $string = str_replace("&","",$string);
 $string = str_replace("*","",$string);
 $string = str_replace("(","",$string);
 $string = str_replace(")","",$string);
 $string = str_replace("+","",$string);
 $string = str_replace("=","",$string);
 $string = str_replace("{","",$string);
 $string = str_replace("}","",$string);
 $string = str_replace("[","",$string);
 $string = str_replace("]","",$string);
 $string = str_replace("<","",$string);
 $string = str_replace(">","",$string);
 $string = str_replace("|","",$string);
 $string = str_replace("§","",$string);
 $string = str_replace("°","",$string);
 $string = str_replace("¹","",$string);
 $string = str_replace("²","",$string);
 $string = str_replace("³","",$string);
 $string = str_replace("£","",$string);
 $string = str_replace("¢","",$string);
 $string = str_replace("¬","",$string);
 $string = str_replace("~","",$string);
 $string = str_replace("^","",$string);
 $string = str_replace("´","",$string);
 $string = str_replace("`","",$string);
 $string = str_replace("¨","",$string);
 if($tipo==1){
  $string = str_replace("/","",$string);
  $string = str_replace("@","",$string);
  $string = str_replace(".","",$string);
  $string = str_replace(",","",$string);
  $string = str_replace("ª","",$string);
  $string = str_replace("º","",$string);
  $string = str_replace("-","",$string);
  $string = str_replace("_","",$string);
  $string = str_replace("0","",$string);
  $string = str_replace("1","",$string);
  $string = str_replace("2","",$string);
  $string = str_replace("3","",$string);
  $string = str_replace("4","",$string);
  $string = str_replace("5","",$string);
  $string = str_replace("6","",$string);
  $string = str_replace("7","",$string);
  $string = str_replace("8","",$string);
  $string = str_replace("9","",$string);
 }
 if($tipo==2){
  $string = str_replace("/","",$string);
  $string = str_replace("@","",$string);
  $string = str_replace(".","",$string);
  $string = str_replace(",","",$string);
  $string = str_replace("_","",$string);
 }
 if($tipo==3){
  $string = str_replace("@","",$string);
  $string = str_replace("_","",$string);
 }
 if($tipo==4){
  $string = str_replace("/","",$string);
  $string = str_replace(",","",$string);
  $string = str_replace("ª","",$string);
  $string = str_replace("º","",$string);
  $string = str_replace(" ","",$string);
 }
 $string = strtoupper(RetiraAcento($string));
 return $string;
}
function db_criatermometro_edu($dbnametermo='termometro',$dbtexto='Concluído',$dbcor='blue',$dbborda=1,$dbacao='Aguarde Processando...'){
 //#00#//db_criatermometro
 //#10#//Cria uma barra de progresso no ponto do programa que for chamado
 //#15#//db_criatermometro('termometro','Concluído','blue',1);
 //#20#//dbnametermo = Nome do termometro e da funcao js que atualiza o termometro
 //#20#//dbtexto     = Texto mostrado no lado da porcentagem concluida
 //#20#//dbcor       = Cor do termometro
 //#20#//dbborda     = Borda, 1 com borda ou 2 sem borda
 //#20#//dbacao      = Texto para acao executada ex: Aguarde Processando...
 //#99#//Essa função apenas cria o termometro, para atualizar o valor do termometro deve usar a funcao db_atutermometro
 if($dbborda !=1 && $dbborda !=0){
   $dbborda=1;
 }
 echo "<table id='termo' style='visibility:hidden' align='center' marginwidth='0' width='790' border='0' cellspacing='0' cellpadding='0'>";
 echo "<tr><td align='center'><b>$dbacao</b></td></tr>";
 echo "<tr><td align='center'>";
 echo "</td></tr>";
 echo "<tr><td align='center'>
        <table style='border-collapse: collapse; border:1px solid #525252;' cellspacing=0 cellpadding=0>
         <tr><td>";
 echo "   <table border=0 cellspacing=0 cellpadding=0>";
 echo "    <tr><td>";
 echo "     <input name='".$dbnametermo."' style='background: transparent;text-align:center' id='dbtermometro".$dbnametermo."' type='text' value='' size=100 readonly>
           </td></tr>";
 echo "    <tr><td>";
 echo "     <input name='barra".$dbnametermo."' style='background: ".$dbcor.";text-align:center;visibility:hidden' id='dbbarra".$dbnametermo."' type='text' value='' size=0 readonly> ";
 echo "    </td></tr>";
 echo "   </table>
         </td></tr>
        </table>";
 echo "</td></tr>";
 echo "</table>";
 echo "<script>
        function js_termo_".$dbnametermo."(atual,texto){
         dbtexto = (texto==null)?'{$dbtexto}':texto;
         document.getElementById('dbtermometro".$dbnametermo."').value = ' '+atual+'%'+' '+dbtexto;
         document.getElementById('dbbarra".$dbnametermo."').size = atual;
         document.getElementById('dbbarra".$dbnametermo."').style.visibility = 'visible';
        }
       </script>";
}
function db_atutermometro_edu($dblinha,$dbrows,$dbnametermo,$dbquantperc=1,$dbtexto=null){
 //#00#//db_atutermometro
 //#10#//Atualiza o valor do termometro
 //#15#//db_atutermometro($i,$numrows,'termometro',1);
 //#20#//dblinha       = linha que esta atualmente
 //#20#//dbrows        = total de registros
 //#20#//dbnametermo   = nome do termometro q foi criado com o db_criatermometro
 //#20#//dbquantperc   = percentual que a barra sera atualizada
 $percatual = ceil((($dblinha+1) * 100) / $dbrows);
 if(is_null($dbtexto)) {
  echo "<script>js_termo_".$dbnametermo."($percatual);</script>";
 } else {
  echo "<script>js_termo_".$dbnametermo."($percatual,'$dbtexto');</script>";
 }
 @ob_get_contents();
 @ob_flush();
}

if(!isset($ed52_i_ano)){
 $ed52_i_ano = date("Y")-1;
 for($x=1;$x<=31;$x++){
  if(date("w",mktime(0,0,0,5,$x,$ed52_i_ano)) == 3){
   $data_censo_dia = strlen($x)==1?"0".$x:$x;
   $data_censo_mes = "05";
   $data_censo_ano = $ed52_i_ano;
  }
 }
 $data_censo = $data_censo_dia."/".$data_censo_mes."/".$data_censo_ano;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Gerar Arquivo de Exportação - CENSO ESCOLAR - SITUAÇÃO DO ALUNO</b></legend>
   <table border="0" align="left">
    <tr>
     <td colspan="2">
      <b>Data do Censo:</b>
      <?db_inputdata('data_censo',@$data_censo_dia,@$data_censo_mes,@$data_censo_ano,true,'text',1," onchange=\"js_ano();\"","","","parent.js_ano();")?>
      <b>Ano do Censo:</b>
      <?db_input('ed52_i_ano',4,@$Ied52_i_ano,true,'text',3,"");?>
     </td>
    </tr>
    <?
    $verif = false;
    if(isset($ed52_i_ano) && $ed52_i_ano!="" && !isset($gerararquivo)){
     $result1 = $clcalendarioescola->sql_record($clcalendarioescola->sql_query("","ed52_d_inicio,ed52_d_fim","ed52_d_inicio asc,ed52_d_fim desc"," ed52_i_ano = $ed52_i_ano AND ed38_i_escola = $escola"));
     if($clcalendarioescola->numrows>0){
      db_fieldsmemory($result1,0);
     }else{
      $verif = true;
      $db_opcao = 3;
      $ed52_d_inicio = "";
      $ed52_d_inicio_dia = "";
      $ed52_d_inicio_mes = "";
      $ed52_d_inicio_ano = "";
      $ed52_d_fim = "";
      $ed52_d_fim_dia = "";
      $ed52_d_fim_mes = "";
      $ed52_d_fim_ano = "";
     }
    }
    ?>
    <tr>
     <td nowrap title="<?=@$Ted52_d_inicio?>" colspan="2">
      <?if($verif==true){
       echo "<font color='red'><b>*Sem informações para o ano informado.<b></font><br>";
      }?>
      <fieldset ><legend><b>Calendário</b></legend>
      <?=@$Led52_d_inicio?>
      <? db_inputdata('ed52_d_inicio',@$ed52_d_inicio_dia,@$ed52_d_inicio_mes,@$ed52_d_inicio_ano,true,'text',$db_opcao,"");?>
      <?=@$Led52_d_fim?>
      <? db_inputdata('ed52_d_fim',@$ed52_d_fim_dia,@$ed52_d_fim_mes,@$ed52_d_fim_ano,true,'text',$db_opcao,"");?>
      </fieldset>
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <table>
       <tr>
        <td align="center">
         <?if(isset($gerararquivo)){?>
         <script>
          var sHors  = "00";
          var sMins  = "00";
          var sSecs  = "00";
          function getSecs(){
           //verifica o relógio
           sSecs++;
           if(sSecs==60){
            sSecs=0;sMins++;
            if(sMins<=9)
             sMins="0"+sMins;
           }
           if(sMins==60){
            sMins="0"+0;sHors++;
            if(sHors<=9)
             sHors="0"+sHors;
           }
           if(sSecs<=9)
           sSecs="0"+sSecs;
           //monta o relógio
           document.getElementById('clock1').innerHTML=sHors+":"+sMins+":"+sSecs;
           //mostra o relógio
           setTimeout('getSecs()',1000);
          }
         </script>
         <b>
          Tempo de execução:<br>
          <span id="clock1"><?=date("H:i:s")?></span><script>setTimeout('getSecs()',1000);</script>
         </b>
         <?}?>
        </td>
        <td>
         <?=db_criatermometro_edu('termometro', 'Concluido...', 'blue', 1);?>
         <?
         if(isset($gerararquivo)){
          ?><script>document.getElementById("termo").style.visibility = "visible";</script><?
         }
         ?>
        </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
   </fieldset>
   </center>
  </td>
 </tr>
 <tr>
  <td align="center">
   <input name="gerararquivo" type="submit" id="arquivo"  <?=$verif==true?"disabled":""?> value="Gerar Arquivo" onclick="return js_valida();" <?=isset($gerararquivo)?"style='visibility:hidden'":""?>>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_ano(){
 datacenso = document.form1.data_censo.value;
 if(datacenso!="" && datacenso.length==10){
  datacenso = datacenso.split("/");
  document.form1.ed52_i_ano.value = datacenso[2];
  document.form1.submit();
 }else{
  document.form1.ed52_i_ano.value = "";
  document.form1.ed52_d_inicio.value = "";
  document.form1.ed52_d_fim.value = "";
 }
}
function js_valida(){
 if(document.form1.data_censo.value=="" || document.form1.ed52_i_ano.value=="" || document.form1.ed52_d_inicio.value=="" || document.form1.ed52_d_fim.value==""){
  alert("Preencha todos os campos do formulário!");
  return false;
 }
 if(document.form1.ed52_i_ano.value!=document.form1.ed52_d_inicio_ano.value || document.form1.ed52_i_ano.value!=document.form1.ed52_d_fim_ano.value){
  alert("Data Inicial e Final do Calendário deve estar dentro do Ano do Censo!");
  return false;
 }
 dataini = document.form1.ed52_d_inicio_ano.value+document.form1.ed52_d_inicio_mes.value+document.form1.ed52_d_inicio_dia.value;
 datafim = document.form1.ed52_d_fim_ano.value+document.form1.ed52_d_fim_mes.value+document.form1.ed52_d_fim_dia.value;
 if(parseInt(dataini)>=parseInt(datafim)){
  alert("Data Final do Calendário deve ser maior que a Data Inicial!");
  return false;
 }
 document.form1.gerararquivo.style.visibility = "hidden";
 return true;
}
</script>
<?
if(isset($gerararquivo)){
 echo str_pad("<br>",1100," ",STR_PAD_RIGHT)."\n";
 $clescola = new cl_escola;
 $data_censo = substr($data_censo,6,4)."-".substr($data_censo,3,2)."-".substr($data_censo,0,2);
 $hoje = date("Y-m-d");
 $arquivo_txt = "tmp/censo_sitAluno_".$escola."_".db_getsession("DB_id_usuario")."_".date("dmY")."_".date("His")."_".str_replace("/","",db_formatar($data_censo,'d'))."_".$ed52_i_ano.".txt";
 $logerro_txt = "tmp/censo_sitAluno_".$escola."_".db_getsession("DB_id_usuario")."_".date("dmY")."_".date("His")."_".str_replace("/","",db_formatar($data_censo,'d'))."_".$ed52_i_ano."_logerro.txt";
 $ponteiro = fopen($arquivo_txt,"w");//arquivo com os dados para exportação
 $ponteiro2= fopen($logerro_txt,"w");//arquivo com os logs de erros encontrados na validação
 ///////////////////////////////////REGISTRO 89
 $erro_diretor = false;
 $result_diretor = $clescoladiretor->sql_record($clescoladiretor->sql_query("","ed18_c_codigoinep,case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_nome) else trim(cgmcgm.z01_nome) end as z01_nome,case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_cgccpf) else trim(cgmcgm.z01_cgccpf) end as z01_cgccpf,case when ed20_i_tiposervidor = 1 then trim(rhfuncao.rh37_descr) else 'DIRETOR' end as rh37_descr,trim(ed254_c_email) as ed254_c_email",""," ed254_i_escola = $escola AND ed254_c_tipo = 'A' LIMIT 1"));
 if($clescoladiretor->numrows>0){
  db_fieldsmemory($result_diretor,0);
  if(($z01_cgccpf!="" && strlen($z01_cgccpf)!=11) || $z01_cgccpf=="00000000000" || $z01_cgccpf=="00000000191"){
   $var_erro = "ESCOLA: Campo CPF do Diretor inválido.\n";
   fwrite($ponteiro2,$var_erro);
   $erro_diretor = true;
  }
  if($rh37_descr==""){
   $var_erro = "ESCOLA: Campo Cargo do Diretor não informado.\n";
   fwrite($ponteiro2,$var_erro);
   $erro_diretor = true;
  }
  if($ed254_c_email!="" && (!strstr($ed254_c_email,"@")  || !strstr($ed254_c_email,"."))){
   $var_erro = "ESCOLA: Campo Email do diretor deve conter arroba e ponto.\n";
   fwrite($ponteiro2,$var_erro);
   $erro_diretor = true;
  }
 }else{
  $var_erro = "ESCOLA: Nenhum diretor com exercício aberto foi informado.\n";
  fwrite($ponteiro2,$var_erro);
  $erro_diretor = true;
 }
 if($erro_diretor==false){
  $write_linha = "89|".$ed18_c_codigoinep."|".$z01_cgccpf."|".$z01_nome."|".$rh37_descr."|".$ed254_c_email."\n";
  fwrite($ponteiro,$write_linha);
 }
 
 ///////////////////////////////////REGISTRO 90
 $campos = "escola.ed18_c_codigoinep,
            calendario.ed52_i_ano,
            turma.ed57_i_codigo,
            turma.ed57_i_codigoinep,
            turma.ed57_c_descr,
            aluno.ed47_c_codigoinep,
            aluno.ed47_i_codigo,
            aluno.ed47_v_nome,
            aluno.ed47_d_nasc,
            aluno.ed47_v_mae,
            ensino.ed10_i_tipoensino,
            matricula.ed60_i_codigo,
            matriculaserie.ed221_i_serie,
            matricula.ed60_c_situacao,
            matricula.ed60_d_datamatricula,
            matricula.ed60_d_datasaida,
            case when matricula.ed60_d_datamatricula > '$data_censo'
             then 1 else 0 end as admitidoapos,
            fc_edurfatual(ed60_i_codigo) as rendimentobanco
           ";
 $sql = "SELECT $campos
         FROM matricula
          inner join turma on ed57_i_codigo = ed60_i_turma
          inner join aluno on ed47_i_codigo = ed60_i_aluno
          inner join escola on ed18_i_codigo = ed57_i_escola
          inner join calendario on ed52_i_codigo = ed57_i_calendario
          inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
          inner join serie on ed11_i_codigo = ed221_i_serie
          inner join ensino on ed10_i_codigo = ed11_i_ensino
         WHERE ed221_c_origem = 'S'
         AND ed57_i_escola = $escola
         AND ed52_i_ano = $ed52_i_ano
         AND (
              (ed60_d_datamatricula <= '$data_censo' AND (ed60_d_datasaida is null OR ed60_d_datasaida > '$data_censo'))
               OR
              (ed60_d_datamatricula > '$data_censo')               
             )
         ORDER BY ed57_c_descr,ed57_i_codigoinep,to_ascii(ed47_v_nome)
        ";
 $result_matricula = $clmatricula->sql_record($sql);
 $erro_escola = false;
 $erro_turma = false;
 $erro_aluno = false;
 $array_turma = array();
 if($clmatricula->numrows>0){
  fwrite($ponteiro2,"Erros encontrados na geração do arquivo de exportação para o Censo Escolar:\n\n");
  $var_erro = "* Campo Código INEP da turma deve ser informado quando esta tiver alunos com data da matrícula inferior ou igual a data de referência do censo(".db_formatar($data_censo,'d')."):\n";
  fwrite($ponteiro2,$var_erro);
  for($a=0;$a<$clmatricula->numrows;$a++){
   db_fieldsmemory($result_matricula,$a);
   $comparadatasaida = str_replace("-","",$ed60_d_datasaida);
   $comparadatacenso = str_replace("-","",$data_censo);   
   db_atutermometro_edu($a, $clmatricula->numrows , 'termometro',1,'...Processando Alunos');
   $ed18_c_codigoinep = trim($ed18_c_codigoinep)!=""?trim($ed18_c_codigoinep):"";
   $ed52_i_ano        = trim($ed52_i_ano)!=""?trim($ed52_i_ano):"";
   $ed57_i_codigo     = trim($ed57_i_codigo)!=""?trim($ed57_i_codigo):"";
   $ed57_i_codigoinep = trim($ed57_i_codigoinep)!=""?trim($ed57_i_codigoinep):"";
   $ed57_c_descr      = trim($ed57_c_descr)!=""?trim($ed57_c_descr):"";
   $ed47_c_codigoinep = trim($ed47_c_codigoinep)!=""?trim($ed47_c_codigoinep):"";
   $ed47_i_codigo     = trim($ed47_i_codigo)!=""?trim($ed47_i_codigo):"";
   $ed47_v_nome       = trim($ed47_v_nome)!=""?trim(TiraCaracteres($ed47_v_nome,3)):"";
   $ed47_d_nasc       = trim($ed47_d_nasc)!=""?db_formatar($ed47_d_nasc,'d'):"";
   $ed47_v_mae        = trim($ed47_v_mae)!=""?trim(TiraCaracteres($ed47_v_mae,3)):"";
   $ed60_i_codigo     = trim($ed60_i_codigo)!=""?trim($ed60_i_codigo):"";
   $ed10_i_tipoensino = trim($ed10_i_tipoensino)!=""?trim($ed10_i_tipoensino):"";
   $ed221_i_serie     = trim($ed221_i_serie)!=""?trim($ed221_i_serie):"";
   $ed60_c_situacao   = trim($ed60_c_situacao)!=""?trim($ed60_c_situacao):"";
   $sql_etapa = "SELECT ed11_i_codcenso FROM serie WHERE ed11_i_codigo = $ed221_i_serie";
   $result_etapa = pg_query($sql_etapa);
   if(pg_num_rows($result_etapa)>0){
    $ed221_i_serie = pg_result($result_etapa,0,0);
   }else{
    db_msgbox("Matrícula $ed60_i_codigo do aluno $ed47_v_nome não tem vínculo com etapas!");
    exit;
   }
   $array_etapas = array("4","5","6","7","8","9","10","11","14","15","16","17","18","19","20","21","25","26","27","28","29","30","31","32","33","34","35","36","37","38","39","40","41");
   $etapa_permitida = false;
   for($rr=0;$rr<count($array_etapas);$rr++){
    if($array_etapas[$rr]==$ed221_i_serie){
     $etapa_permitida = true;
     break;
    }
   }
   if($ed18_c_codigoinep=="" && $erro_escola==false){
    $var_erro = "Campo Código INEP da escola não informado no sistema.\n";
    fwrite($ponteiro2,$var_erro);
    $erro_escola = true;
    break;
   }
   if($ed57_i_codigoinep=="" && $admitidoapos==0){
    if(!array_key_exists($ed57_i_codigo,$array_turma)){
     $array_turma[$ed57_i_codigo] = $ed57_i_codigo;
     $var_erro = "* TURMA: $ed57_i_codigo - $ed57_c_descr\n";
     fwrite($ponteiro2,$var_erro);
    }
    $erro_turma = true;
   }
   if($admitidoapos==0 && trim($ed60_d_datasaida)==""){
    $imprime = "1";
   }elseif($admitidoapos==0 && trim($ed60_d_datasaida)!="" && trim($comparadatasaida)>trim($comparadatacenso)){
    $imprime = "2";
   }else{
    $imprime = "4";
   }   
   if($ed47_c_codigoinep=="" && $imprime!="4"){
    $var_erro = "ALUNO: $ed47_i_codigo - $ed47_v_nome - Código INEP não informado.\n";
    fwrite($ponteiro2,$var_erro);
    $erro_aluno = true;
   }
   if($ed57_i_codigoinep!=""){
    $sql_matcenso = "SELECT ed280_i_matcenso
                     FROM alunomatcenso
                     WHERE ed280_i_aluno = $ed47_i_codigo
                     AND ed280_i_ano = $ed52_i_ano
                     AND ed280_i_turmacenso = $ed57_i_codigoinep 
                    ";
    $result_matcenso = pg_query($sql_matcenso);   
    if(pg_num_rows($result_matcenso)==0 && $admitidoapos==0){
     $var_erro = "ALUNO: $ed47_i_codigo - $ed47_v_nome - Matrícula INEP não informada para este aluno.\n";
     fwrite($ponteiro2,$var_erro);
     $erro_aluno = true;
    }elseif(pg_num_rows($result_matcenso)==0 && $admitidoapos==1){
     $matricula_censo = " ";    	
    }elseif(pg_num_rows($result_matcenso)>0){
     $matricula_censo = pg_result($result_matcenso,0,0);    	
    }
   }else{
    $matricula_censo = " ";   	
   }
   
   if($ed60_c_situacao=="TRANSFERIDO FORA" || $ed60_c_situacao=="TRANSFERIDO REDE" || $ed60_c_situacao=="TROCA DE MODALIDADE"){
    $movimento = "1";
   }elseif($ed60_c_situacao=="CANCELADO" || $ed60_c_situacao=="EVADIDO"){
    $movimento = "2";
   }elseif($ed60_c_situacao=="FALECIDO"){
    $movimento = "3";
   }else{
    $movimento = " ";
   }
   if($imprime!="4"){
    if($movimento!=" "){
     $rendimento = " ";
     $concluinte = " ";
     $seminformacao = " ";
    }else{
     if($ed221_i_serie==1 || $ed221_i_serie==2){
      $rendimento = " ";
      $concluinte = " ";
      $seminformacao = "1";
     }elseif($ed10_i_tipoensino==1 && ($ed221_i_serie==30 || $ed221_i_serie==31 || $ed221_i_serie==32 || $ed221_i_serie==33 || $ed221_i_serie==34 || $ed221_i_serie==35 || $ed221_i_serie==36 || $ed221_i_serie==37 || $ed221_i_serie==38)){
      $rendimento = " ";
      $concluinte = " ";
      $seminformacao = "1";
     }elseif($ed10_i_tipoensino==2 && ($ed221_i_serie==30 || $ed221_i_serie==31 || $ed221_i_serie==32 || $ed221_i_serie==33 || $ed221_i_serie==34 || $ed221_i_serie==35 || $ed221_i_serie==36 || $ed221_i_serie==37 || $ed221_i_serie==38 || $ed221_i_serie==57 || $ed221_i_serie==59)){
      $rendimento = " ";
      $concluinte = " ";
      $seminformacao = "1";
     }else{
      if($ed60_c_situacao=="CLASSIFICADO" || $ed60_c_situacao=="AVANÇADO"){
       $rendimento = "1";
       $seminformacao = " ";
       if($ed10_i_tipoensino==1 && ($ed221_i_serie==11 || $ed221_i_serie==41 || $ed221_i_serie==27 || $ed221_i_serie==28 || $ed221_i_serie==29 || $ed221_i_serie==39 || $ed221_i_serie==40)){
        $concluinte = "1";
       }elseif($ed10_i_tipoensino==2 && ($ed221_i_serie==11 || $ed221_i_serie==41 || $ed221_i_serie==27 || $ed221_i_serie==28 || $ed221_i_serie==29 || $ed221_i_serie==39 || $ed221_i_serie==40 || $ed221_i_serie==44 || $ed221_i_serie==45 || $ed221_i_serie==47 || $ed221_i_serie==48 || $ed221_i_serie==51 || $ed221_i_serie==58)){
        $concluinte = "1";
       }elseif($ed10_i_tipoensino==3 && ($ed221_i_serie==50 || $ed221_i_serie==51 || $ed221_i_serie==52 || $ed221_i_serie==54 || $ed221_i_serie==55 || $ed221_i_serie==58 || $ed221_i_serie==57 || $ed221_i_serie==59)){
        $concluinte = "1";
       }else{
        $concluinte = " ";
       }
      }else{
       if($rendimentobanco=="A"){
        $rendimento = "1";
        $seminformacao = " ";
        if($ed10_i_tipoensino==1 && ($ed221_i_serie==11 || $ed221_i_serie==41 || $ed221_i_serie==27 || $ed221_i_serie==28 || $ed221_i_serie==29 || $ed221_i_serie==39 || $ed221_i_serie==40)){
         $concluinte = "1";
        }elseif($ed10_i_tipoensino==2 && ($ed221_i_serie==11 || $ed221_i_serie==41 || $ed221_i_serie==27 || $ed221_i_serie==28 || $ed221_i_serie==29 || $ed221_i_serie==39 || $ed221_i_serie==40 || $ed221_i_serie==44 || $ed221_i_serie==45 || $ed221_i_serie==47 || $ed221_i_serie==48 || $ed221_i_serie==51 || $ed221_i_serie==58)){
         $concluinte = "1";
        }elseif($ed10_i_tipoensino==3 && ($ed221_i_serie==50 || $ed221_i_serie==51 || $ed221_i_serie==52 || $ed221_i_serie==54 || $ed221_i_serie==55 || $ed221_i_serie==58 || $ed221_i_serie==57 || $ed221_i_serie==59)){
         $concluinte = "1";
        }else{
         $concluinte = " ";
        }
       }elseif($rendimentobanco=="R"){
        $rendimento = "0";
        $concluinte = " ";
        $seminformacao = " ";
       }else{
        $rendimento = " ";
        $concluinte = " ";
        $seminformacao = "1";
       }
      }
     }
    }
    $write_linha = "90|".$ed18_c_codigoinep."|".$ed52_i_ano."|".$ed57_i_codigo."|".$ed57_i_codigoinep."|".$ed57_c_descr."|".$ed47_c_codigoinep."|".$ed47_i_codigo."|".$ed47_v_nome."|".$ed47_d_nasc."|".$ed47_v_mae."|".$matricula_censo."|".$ed10_i_tipoensino."|".$ed221_i_serie."|".$admitidoapos."|".$movimento."|".$rendimento."|".$concluinte."|".$seminformacao."\n";
    fwrite($ponteiro,$write_linha);
   }
  }
  db_atutermometro_edu(99,100, 'termometro',1,'...Processo Concluído');
 }
 fclose($ponteiro);
 fclose($ponteiro2);
 if($erro_diretor==true || $erro_escola==true || $erro_turma==true || $erro_aluno==true){
  ?>
  <script>
   jan = window.open('edu4_exportarsituacaoaluno002.php?arquivo_erro=<?=$logerro_txt?>','Erros Geração de Arquivo Censo escolar','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
  </script>
  <?
  unlink($arquivo_txt);
  db_redireciona("edu4_exportarsituacaoaluno001.php");
 }else{
  ?>
  <script>
  function js_detectaarquivo(ponteiro,pdf){
   listagem = ponteiro+'#<br>Arquivo de Exportação do Censo Escolar<br><br>Clique neste link para salvar o arquivo de exportação do censo escolar gerado pelo sistema, para posterior envio deste ao site do INEP|';
   js_montarlista(listagem,'form1');
  }
  js_detectaarquivo("<?=$arquivo_txt?>");
  </script>
  <?
  unlink($logerro_txt);
  sleep(3);
  db_redireciona("edu4_exportarsituacaoaluno001.php");
 }
}
?>
</body>
</html>