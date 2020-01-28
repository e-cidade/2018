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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_calendarioescola_classe.php");
include("classes/db_telefoneescola_classe.php");
include("classes/db_censodistrito_classe.php");
include("classes/db_escoladiretor_classe.php");
include("classes/db_rechumanoescola_classe.php");
include("classes/db_escolaestrutura_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_censoregradisc_classe.php");
include("classes/db_censoetapamodal_classe.php");
include("classes/db_censoetapa_classe.php");
include("classes/db_censoorgreg_classe.php");
include("classes/db_rechumano_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_formacao_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_caddisciplina_classe.php");
include("classes/db_turmaac_classe.php");
include("classes/db_turmaacativ_classe.php");
include("classes/db_turmaacativnova_classe.php");
include("classes/db_alunonecessidade_classe.php");
include("classes/db_turmaacmatricula_classe.php");
include("classes/db_turmaserieregimemat_classe.php");
include("classes/db_turmaachorario_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo              = new rotulocampo;
$clcalendarioescola    = new cl_calendarioescola;
$cltelefoneescola      = new cl_telefoneescola;
$clcensodistrito       = new cl_censodistrito;
$clescoladiretor       = new cl_escoladiretor;
$clrechumanoescola     = new cl_rechumanoescola;
$clescolaestrutura     = new cl_escolaestrutura;
$clmatricula           = new cl_matricula;
$clcensoregradisc      = new cl_censoregradisc;
$clcensoetapamodal     = new cl_censoetapamodal;
$clcensoetapa          = new cl_censoetapa;
$clcensoorgreg         = new cl_censoorgreg;
$clrechumano           = new cl_rechumano;
$clcgm                 = new cl_cgm;
$clformacao            = new cl_formacao;
$clrhpessoal           = new cl_rhpessoal;
$clregenciahorario     = new cl_regenciahorario;
$clturma               = new cl_turma;
$clregencia            = new cl_regencia;
$clcaddisciplina       = new cl_caddisciplina;
$clturmaac             = new cl_turmaac;
$clturmaacativ         = new cl_turmaacativ;
$clturmaacativnova     = new cl_turmaacativnova;
$clalunonecessidade    = new cl_alunonecessidade;
$clturmaacmatricula    = new cl_turmaacmatricula;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$clturmaachorario      = new cl_turmaachorario;
$clrotulo->label("ed52_i_ano");
$clrotulo->label("ed52_d_inicio");
$clrotulo->label("ed52_d_fim");
$escola   = db_getsession("DB_coddepto");
$db_opcao = 1;
$db_botao = true;
function RetiraAcento($string) {

  set_time_limit(240);
  $acentos    = 'áéíóúÁÉÍÓÚàÀÂâÊêôÔüÜïÏöÖñÑãÃõÕçÇäÄ\'';
  $letras     = 'AEIOUAEIOUAAAAEEOOUUIIOONNAAOOCCAA ';
  $new_string = '';

  for ($x = 0; $x < strlen($string); $x++) {

    $let = substr($string, $x, 1);
    for ($y = 0; $y < strlen($acentos); $y++) {

      if ($let == substr($acentos, $y, 1)) {

        $let = substr($letras, $y, 1);
        break;

      }
    }
    $new_string = $new_string . $let;
  }
  return $new_string;
}

function TiraCaracteres(&$string,$tipo) {

  // $string = string a ser retirados os caracteres
  // $tipo = tipo de validação: 1, 2, 3 e 4
  //
  // 1 - Somente Letras e espaço
  // 2 - Somente Números, Letras, espaço, ª, º  e traço
  // 3 - Somente Números, Letras, espaço, ª, º, ponto, virgula, barra e traço
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

  if ($tipo == 1) {

    $string = str_replace("/","",$string);
    $string = str_replace("@","",$string);
    $string = str_replace(".","",$string);
    $string = str_replace(",","",$string);
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
    $string = str_replace("ª","",$string);
    $string = str_replace("º","",$string);
    $string = str_replace("°","",$string);

  }

  if ($tipo == 2) {

    $string = str_replace("/","",$string);
    $string = str_replace("@","",$string);
    $string = str_replace(".","",$string);
    $string = str_replace(",","",$string);
    $string = str_replace("_","",$string);

  }

  if ($tipo == 3) {

    $string = str_replace("@","",$string);
    $string = str_replace("_","",$string);

  }

  if ($tipo == 4) {

    $string = str_replace("/","",$string);
    $string = str_replace(",","",$string);
    $string = str_replace(" ","",$string);
    $string = str_replace("ª","",$string);
    $string = str_replace("º","",$string);
    $string = str_replace("°","",$string);

  }

  $string = strtoupper(RetiraAcento($string));
  return $string;

}

function db_criatermometro_edu($dbnametermo='termometro',$dbtexto='Concluído',$dbcor='blue',
                               $dbborda=1,$dbacao='Aguarde Processando...') {
                               	
 //#00#//db_criatermometro
 //#10#//Cria uma barra de progresso no ponto do programa que for chamado
 //#15#//db_criatermometro('termometro','Concluído','blue',1);
 //#20#//dbnametermo = Nome do termometro e da funcao js que atualiza o termometro
 //#20#//dbtexto     = Texto mostrado no lado da porcentagem concluida
 //#20#//dbcor       = Cor do termometro
 //#20#//dbborda     = Borda, 1 com borda ou 2 sem borda
 //#20#//dbacao      = Texto para acao executada ex: Aguarde Processando...
 //#99#//Essa função apenas cria o termometro, para atualizar o valor do termometro deve usar a funcao db_atutermometro
 if ($dbborda != 1 && $dbborda != 0) {
   $dbborda = 1;
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

function db_atutermometro_edu($dblinha,$dbrows,$dbnametermo,$dbquantperc=1,$dbtexto=null) {
    
  //#00#//db_atutermometro
  //#10#//Atualiza o valor do termometro
  //#15#//db_atutermometro($i,$numrows,'termometro',1);
  //#20#//dblinha       = linha que esta atualmente
  //#20#//dbrows        = total de registros
  //#20#//dbnametermo   = nome do termometro q foi criado com o db_criatermometro
  //#20#//dbquantperc   = percentual que a barra sera atualizada
  $percatual = ceil((($dblinha+1) * 100) / $dbrows);
  if (is_null($dbtexto)) {
    echo "<script>js_termo_".$dbnametermo."($percatual);</script>";
  } else {
    echo "<script>js_termo_".$dbnametermo."($percatual,'$dbtexto');</script>";
  }
  @ob_get_contents();
  @ob_flush();
  
}

if (!isset($ed52_i_ano)) {
    
  $ed52_i_ano = date("Y");
  for ($x = 1; $x <= 31; $x++) {
      
    if (date("w",mktime(0,0,0,5,$x,$ed52_i_ano)) == 3) {
        
      $data_censo_dia = strlen($x) == 1?"0".$x:$x;
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
 <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >  
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
       <fieldset style="width:95%"><legend><b>Gerar Arquivo de Exportação - CENSO ESCOLAR</b></legend>
        <table border="0" align="left">
         <tr>
          <td colspan="2">
           <b>Data do Censo:</b>
            <?db_inputdata('data_censo',@$data_censo_dia,@$data_censo_mes,@$data_censo_ano,true,'text',1,
                           " onchange=\"js_ano();\"","","","parent.js_ano();")?>
            <b>Ano do Censo:</b>
            <?db_input('ed52_i_ano',4,@$Ied52_i_ano,true,'text',3,"");?>
          </td>
         </tr>
         <?
          $verif = false;
          if (isset($ed52_i_ano) && $ed52_i_ano != "" && !isset($gerararquivo)) {
        
            $sOrder                  = "ed52_d_inicio asc,ed52_d_fim desc";
            $sWhere                  = " ed52_i_ano = $ed52_i_ano AND ed38_i_escola = $escola";
            $sSqlCalendarioEscola    = $clcalendarioescola->sql_query("","ed52_d_inicio,ed52_d_fim",$sOrder,$sWhere);
            $sResultCalendarioEscola = $clcalendarioescola->sql_record($sSqlCalendarioEscola);
            if ($clcalendarioescola->numrows > 0) {
              db_fieldsmemory($sResultCalendarioEscola,0);
            } else {
          
              $verif             = true;
              $db_opcao          = 3;
              $ed52_d_inicio     = "";
              $ed52_d_inicio_dia = "";
              $ed52_d_inicio_mes = "";
              $ed52_d_inicio_ano = "";
              $ed52_d_fim        = "";
              $ed52_d_fim_dia    = "";
              $ed52_d_fim_mes    = "";
              $ed52_d_fim_ano    = "";
        
            }
      
          }
         ?>
         <tr>
          <td nowrap title="<?=@$Ted52_d_inicio?>" colspan="2">
         <?if ($verif == true) { 
             echo "<font color='red'><b>*Sem informações para o ano informado.<b></font><br>";
           }?>
           <fieldset ><legend><b>Calendário</b></legend>
            <?=@$Led52_d_inicio?>
            <? db_inputdata('ed52_d_inicio',@$ed52_d_inicio_dia,@$ed52_d_inicio_mes,
                            @$ed52_d_inicio_ano,true,'text',$db_opcao,"");?>
            <?=@$Led52_d_fim?>
            <? db_inputdata('ed52_d_fim',@$ed52_d_fim_dia,@$ed52_d_fim_mes,
                            @$ed52_d_fim_ano,true,'text',$db_opcao,"");?>
           </fieldset>
          </td>
         </tr>
         <tr>
          <td colspan="2">
           <table>
            <tr>
             <td align="center">
            <?if (isset($gerararquivo)) {?>
            
                <script>
                 var sHors  = "00";
                 var sMins  = "00";
                 var sSecs  = "00";
                 function getSecs() {
                     
                   sSecs++;
                   if (sSecs == 60) {
                       
                     sSecs=0;sMins++;
                     if (sMins <= 9)
                       sMins="0"+sMins;
                     
                   }
                   
                   if (sMins == 60) {
                       
                     sMins="0"+0;sHors++;
                     if (sHors <= 9)
                       sHors="0"+sHors;
                   }
                   
                   if (sSecs <= 9)
                     sSecs="0"+sSecs;
                     document.getElementById('clock1').innerHTML=sHors+":"+sMins+":"+sSecs;
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
              if (isset($gerararquivo)) {
                echo "<script>document.getElementById('termo').style.visibility = 'visible';</script>";
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
      <input name="gerararquivo" type="submit" id="arquivo"  <?=$verif==true?"disabled":""?> 
             value="Gerar Arquivo" >     </td>
    </tr>
   </table>
  </form>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
  <script>
   function js_ano() {
    
     datacenso = document.form1.data_censo.value;
     if (datacenso != "" && datacenso.length == 10) {

       datacenso                       = datacenso.split("/");
       document.form1.ed52_i_ano.value = datacenso[2];
       document.form1.submit();

     } else {

       document.form1.ed52_i_ano.value    = "";
       document.form1.ed52_d_inicio.value = "";
       document.form1.ed52_d_fim.value    = "";

     } 
   }

   function js_valida() {
    
     if (document.form1.data_censo.value == "" || document.form1.ed52_i_ano.value == "" 
         || document.form1.ed52_d_inicio.value == "" || document.form1.ed52_d_fim.value == "") {

       alert("Preencha todos os campos do formulário!");
       return false;

     }

     if (document.form1.ed52_i_ano.value != document.form1.ed52_d_inicio_ano.value  
         || document.form1.ed52_i_ano.value != document.form1.ed52_d_fim_ano.value) {
      
       alert("Data Inicial e Final do Calendário deve estar dentro do Ano do Censo!");
       return false;
    
     }
  
     dataini  = document.form1.ed52_d_inicio_ano.value+document.form1.ed52_d_inicio_mes.value;
     dataini += document.form1.ed52_d_inicio_dia.value;
     datafim  = document.form1.ed52_d_fim_ano.value+document.form1.ed52_d_fim_mes.value;
     datafim += document.form1.ed52_d_fim_dia.value;
     if (parseInt(dataini) >= parseInt(datafim)) {
      
       alert("Data Final do Calendário deve ser maior que a Data Inicial!");
       return false;
    
     }
  
     document.form1.gerararquivo.style.visibility = "hidden";
     return true;
  
   } 
  </script>
  <?
   if (isset($gerararquivo)) {
    
     $clescola     = new cl_escola;
     $data_censo   = substr($data_censo,6,4)."-".substr($data_censo,3,2)."-".substr($data_censo,0,2);
     $hoje         = date("Y-m-d");
     $arquivo_txt  = "tmp/censo_".$escola."_".db_getsession("DB_id_usuario")."_".date("dmY")."_".date("His")."_";
     $arquivo_txt .= str_replace("/","",db_formatar($data_censo,'d'))."_".$ed52_i_ano.".txt";
     $logerro_txt  = "tmp/censo_".$escola."_".db_getsession("DB_id_usuario")."_".date("dmY")."_".date("His")."_";
     $logerro_txt .= str_replace("/","",db_formatar($data_censo,'d'))."_".$ed52_i_ano."_logerro.txt";
     $ponteiro     = fopen($arquivo_txt,"w");//arquivo com os dados para exportação
     $ponteiro2    = fopen($logerro_txt,"w");//arquivo com os logs de erros encontrados na validação
  
     ///////////////////////////////////ESCOLA
     $sCamposEscola   = "  ed18_i_codigo, ";
     $sCamposEscola   .= " trim(ed18_c_codigoinep) as ed18_c_codigoinep, ";
     $sCamposEscola   .= " ed18_i_funcionamento, ";
     $sCamposEscola   .= " trim(ed18_c_nome) as ed18_c_nome, ";
     $sCamposEscola   .= " trim(ed18_c_cep) as ed18_c_cep, ";
     $sCamposEscola   .= " trim(j14_nome) as j14_nome, ";
     $sCamposEscola   .= " ed18_i_numero, ";
     $sCamposEscola   .= " trim(ed18_c_compl) as ed18_c_compl, ";
     $sCamposEscola   .= " trim(j13_descr) as j13_descr, ";
     $sCamposEscola   .= " ed18_i_censouf, ";
     $sCamposEscola   .= " ed18_i_censomunic, ";
     $sCamposEscola   .= " ed262_i_coddistrito, ";
     $sCamposEscola   .= " ed18_i_censoorgreg, ";                
     $sCamposEscola   .= " trim(ed18_c_email) as ed18_c_email, ";  
     $sCamposEscola   .= " ed263_i_codigocenso, ";
     $sCamposEscola   .= " trim(ed18_c_mantenedora) as ed18_c_mantenedora, ";
     $sCamposEscola   .= " trim(ed18_c_local) as ed18_c_local, ";
     $sCamposEscola   .= " ed18_i_cnpj, ";
     $sCamposEscola   .= " ed18_i_categprivada, ";
     $sCamposEscola   .= " ed18_i_conveniada, ";
     $sCamposEscola   .= " ed18_i_cnas, ";
     $sCamposEscola   .= " ed18_i_cebas, ";
     $sCamposEscola   .= " trim(ed18_c_mantprivada) as ed18_c_mantprivada, ";
     $sCamposEscola   .= " ed18_i_cnpjprivada, ";
     $sCamposEscola   .= " ed18_i_credenciamento, ";
     $sCamposEscola   .= " ed18_i_locdiferenciada, ";
     $sCamposEscola   .= " ed18_i_educindigena, ";
     $sCamposEscola   .= " ed18_i_tipolinguain, ";
     $sCamposEscola   .= " ed18_i_tipolinguapt, ";
     $sCamposEscola   .= " ed18_i_linguaindigena, ";
     $sCamposEscola   .= " ed18_i_cnpjmantprivada ";
     $sSqlEscola       = $clescola->sql_query("",$sCamposEscola,""," ed18_i_codigo = $escola");              
     $sResultEscola    = $clescola->sql_record($sSqlEscola);
     $num_linha        = 0;
     $lErroEscola      = false; 
     $lErroTurma       = false; 
     $lErroDocente     = false;
     $lErroAluno       = false;
     fwrite($ponteiro2,"Erros encontrados na geração do arquivo de exportação para o Censo Escolar:\n\n");
     
     if ($clescola->numrows > 0) {
         
       for ($a = 0; $a < $clescola->numrows; $a++) {
           
         $oDadosEscola = db_utils::fieldsmemory($sResultEscola, $a);
         db_atutermometro_edu($a, $clescola->numrows , 'termometro',1,'...Processando Escola');
         
         if (strlen($oDadosEscola->ed18_c_codigoinep) < 8) {
             
           $sMsgErro = "ESCOLA: Campo Código INEP deve conter 8 dígitos.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_funcionamento != 1) {
             
           $sMsgErro = "ESCOLA: Campo Situação de Funcionamento deve ser EM ATIVIDADE.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_c_cep == "") {
             
           $sMsgErro = "ESCOLA: Campo CEP deve ser preenchido.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_c_cep != "" && strlen($oDadosEscola->ed18_c_cep) < 8) {
             
           $sMsgErro = "ESCOLA: Campo CEP deve conter 8 dígitos.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_censouf == "") {
             
           $sMsgErro = "ESCOLA: Campo Estado deve ser preenchido.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_censomunic == "") {
             
           $sMsgErro = "ESCOLA: Campo Cidade deve ser preenchido.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed262_i_coddistrito == "") {
             
           $sMsgErro = "ESCOLA: Campo Ditrito deve ser preenchido.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_censouf != "") {
            
           $sWhereOrgreg  = "ed263_i_censouf = $oDadosEscola->ed18_i_censouf";
           $sSqlOrgreg    = $clcensoorgreg->sql_query("","ed260_c_sigla as uforgreg","",$sWhereOrgreg);
           $sResultOrgreg = $clcensoorgreg->sql_record($sSqlOrgreg);
           
           if ($clcensoorgreg->numrows > 0 && trim($oDadosEscola->ed18_i_censoorgreg) == "") {
               
             $uforgreg = db_utils::fieldsmemory($sResultOrgreg, 0)->uforgreg;
             $sMsgErro = "ESCOLA: Campo Órgão de Ensino deve ser preenchido para o estado $uforgreg.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErro_Escola = true;
             
           }              
         } 
         
         if ($oDadosEscola->ed18_c_email != "" && (!strstr($oDadosEscola->ed18_c_email,"@")  || !strstr($oDadosEscola->ed18_c_email,"."))) {
             
           $sMsgErro = "ESCOLA: Campo Email deve conter arroba e ponto.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_cnpj != "" && $oDadosEscola->ed18_c_mantenedora == 4 && $oDadosEscola->ed18_i_categprivada != 4) {
             
           $sMsgErro  = " ESCOLA: Campo CNPJ só poderá ser preenchido quando Dependência Administrativa for FEDERAL ";
           $sMsgErro .= " ou ESTADUAL ou MUNICIPAL ou a Dependência Administrativa for PRIVADA e a Categoria de ";
           $sMsgErro .= " Escola Privada for FILANTRÓPICA.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_categprivada != "" && $oDadosEscola->ed18_c_mantenedora != 4) {
             
           $sMsgErro  = " ESCOLA: Campo Categoria da Escola Privada só poderá ser preenchido quando ";
           $sMsgErro .= " Dependência Administrativa for PRIVADA.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_cnpjmantprivada == "" && $oDadosEscola->ed18_c_mantprivada	 != "0000") {
             
           $sMsgErro  = "ESCOLA: Campo CNPJ Mantenedora Privada deve ser preenchido ";
           $sMsgErro .= " quando Mantenedora da Escola Privada tiver sido informada.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_categprivada == "" && $oDadosEscola->ed18_c_mantenedora == 4) {
             
           $sMsgErro  = "ESCOLA: Campo Categoria da Escola Privada deve ser preenchido ";
           $sMsgErro .= " quando Dependência Administrativa for PRIVADA.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
          
         if ($oDadosEscola->ed18_i_conveniada != "" && $oDadosEscola->ed18_c_mantenedora != 4) {
             
           $sMsgErro  = "ESCOLA: Campo Conveniada Poder Público só poderá ser preenchido ";
           $sMsgErro .= " quando Dependência Administrativa for PRIVADA.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_categprivada == 4 && $oDadosEscola->ed18_c_mantenedora == 4 && $oDadosEscola->ed18_i_cnas == "") {
             
           $sMsgErro  = "ESCOLA: Campo N° Registro no CNAS deve ser preenchido quando Dependência Administrativa ";
           $sMsgErro .= " for PRIVADA e a Categoria de Escola Privada for FILANTRÓPICA.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_categprivada == 4 && $oDadosEscola->ed18_c_mantenedora == 4 && $oDadosEscola->ed18_i_cebas == "") {
             
           $sMsgErro  = "ESCOLA: Campo N° CEBAS deve ser preenchido quando Dependência Administrativa ";
           $sMsgErro .= " for PRIVADA e a Categoria de Escola Privada for FILANTRÓPICA.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
          
         if ($oDadosEscola->ed18_i_cnpjprivada != "" && $oDadosEscola->ed18_c_mantenedora != 4) {
             
           $sMsgErro  = "ESCOLA: Campo CNPJ da Escola Privada deve ser preenchido ";
           $sMsgErro .= " somente quando Dependência Administrativa for PRIVADA.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         if ($oDadosEscola->ed18_i_locdiferenciada == 1 && $oDadosEscola->ed18_c_local == 1) {
             
           $sMsgErro = "ESCOLA: Campo Zona deve ser RURAL quando Localização Diferenciada for ÁREA DE ASSENTAMENTO.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         
         $sWhereTelefone  = " ed26_i_escola = $oDadosEscola->ed18_i_codigo LIMIT 4"; 
         $sSqlTelefone    = $cltelefoneescola->sql_query("","ed26_i_ddd,ed26_i_numero","",$sWhereTelefone); 
         $sResultTelefone = $cltelefoneescola->sql_record($sSqlTelefone);
         
         if ($cltelefoneescola->numrows > 0) {
             
           $telefones = "";
           for ($b = 0; $b < $cltelefoneescola->numrows; $b++) {
               
             $oTelefonesEscola = db_utils::fieldsmemory($sResultTelefone, $b);
             $ddd              = $oTelefonesEscola->ed26_i_ddd;
             $telefones        = $oTelefonesEscola->ed26_i_numero;
             
             if (strlen($oTelefonesEscola->ed26_i_numero) < 7 || strlen($oTelefonesEscola->ed26_i_numero) > 8) {
                 
               $sMsgErro = "ESCOLA: N° do telefone deve possuir 7 ou 8 dígitos.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErro_Escola = true;
               
             }
           }
           
           for ($b = $cltelefoneescola->numrows; $b < 4; $b++) {
             $telefones .= "|";
           }
           
         } else {
             
           $ddd       = "|";
           $telefones = "|";
           
         } 
         $sCamposEscolaDiretor  = " case when ed20_i_tiposervidor = 1 ";
         $sCamposEscolaDiretor .= "      then trim(cgmrh.z01_nome) ";
         $sCamposEscolaDiretor .= " else trim(cgmcgm.z01_nome) end as z01_nome, ";
         $sCamposEscolaDiretor .= " case when ed20_i_tiposervidor = 1 ";
         $sCamposEscolaDiretor .= "      then trim(cgmrh.z01_cgccpf) ";
         $sCamposEscolaDiretor .= " else trim(cgmcgm.z01_cgccpf) end as z01_cgccpf, ";
         $sCamposEscolaDiretor .= " case when ed20_i_tiposervidor = 1 ";
         $sCamposEscolaDiretor .= "      then trim(rhfuncao.rh37_descr) ";
         $sCamposEscolaDiretor .= " else 'DIRETOR' end as rh37_descr,trim(ed254_c_email) as ed254_c_email";
         $sWhereEscolaDiretor   = " ed254_i_escola = $oDadosEscola->ed18_i_codigo AND ed254_c_tipo = 'A' LIMIT 1";
         $sSqlEscolaDiretor     = $clescoladiretor->sql_query("",$sCamposEscolaDiretor,"", $sWhereEscolaDiretor);
         $sResultEscolaDiretor  = $clescoladiretor->sql_record($sSqlEscolaDiretor);
         
         if ($clescoladiretor->numrows > 0) {
             
           $oDadosDiretor = db_utils::fieldsmemory($sResultEscolaDiretor, 0);
           
           if (($oDadosDiretor->z01_cgccpf != "" && strlen($oDadosDiretor->z01_cgccpf) != 11) 
                || $oDadosDiretor->z01_cgccpf == "00000000000" || $oDadosDiretor->z01_cgccpf == "00000000191") {

             $sMsgErro = "ESCOLA: Campo CPF do Diretor inválido.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErro_Escola = true;
             
           }
           
           if ($oDadosDiretor->rh37_descr == "") {
               
             $sMsgErro = "ESCOLA: Campo Cargo do Diretor não informado.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErro_Escola = true;
             
           }
           
           if ($oDadosDiretor->ed254_c_email != "" && (!strstr($oDadosDiretor->ed254_c_email,"@")  || !strstr($oDadosDiretor->ed254_c_email,"."))) {
               
             $sMsgErro = "ESCOLA: Campo Email do diretor deve conter arroba e ponto.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErro_Escola = true;
             
           }
           
         } else  {
             
           $sMsgErro = "ESCOLA: Nenhum diretor com exercício aberto foi informado.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola  = true;
           $oDadosDiretor->z01_nome      = "";
           $oDadosDiretor->z01_cgccpf    = "";
           $oDadosDiretor->rh37_descr    = "";
           $oDadosDiretor->ed254_c_email = "";
           
         }
         
         $sCampoEstrutura        = " trim(ed255_c_localizacao) as ed255_c_localizacao, ";
         $sCampoEstrutura       .= "                     ed255_i_compartilhado, ";
         $sCampoEstrutura       .= "               ed255_i_escolacompartilhada, ";
         $sCampoEstrutura       .= "               ed255_i_aguafiltrada, ";
         $sCampoEstrutura       .= "               trim(ed255_c_abastagua) as ed255_c_abastagua, ";
         $sCampoEstrutura       .= "               trim(ed255_c_abastenergia) as ed255_c_abastenergia, ";
         $sCampoEstrutura       .= "               trim(ed255_c_esgotosanitario) as ed255_c_esgotosanitario, ";
         $sCampoEstrutura       .= "               trim(ed255_c_destinolixo) as ed255_c_destinolixo, ";
         $sCampoEstrutura       .= "               trim(ed255_c_dependencias) as ed255_c_dependencias, ";
         $sCampoEstrutura       .= "               ed255_i_salaexistente,  ";
         $sCampoEstrutura       .= "               ed255_i_salautilizada, ";
         $sCampoEstrutura       .= "               trim(ed255_c_equipamentos) as ed255_c_equipamentos, ";
         $sCampoEstrutura       .= "               ed255_i_computadores, ";
         $sCampoEstrutura       .= "               ed255_i_qtdcomp, ";
         $sCampoEstrutura       .= "               ed255_i_qtdcompadm, ";
         $sCampoEstrutura       .= "               ed255_i_qtdcompalu, ";
         $sCampoEstrutura       .= "               ed255_i_internet, ";
         $sCampoEstrutura       .= "               ed255_i_bandalarga, ";
         $sCampoEstrutura       .= "               ed255_i_alimentacao, ";
         $sCampoEstrutura       .= "               ed255_i_ativcomplementar, ";
         $sCampoEstrutura       .= "               trim(ed255_c_materdidatico) as ed255_c_materdidatico, ";
         $sCampoEstrutura       .= "               ed255_i_aee, ";
         $sCampoEstrutura       .= "               ed255_i_efciclos, ed255_i_formaocupacao";
         $sWhereEstrutura        = " ed255_i_escola = $oDadosEscola->ed18_i_codigo";
         
         $sSqlEscolaEstrutura    =  $clescolaestrutura->sql_query("",$sCampoEstrutura,"",$sWhereEstrutura);         
         $sResultEscolaEstrutura = $clescolaestrutura->sql_record($sSqlEscolaEstrutura);
         
         if ($clescolaestrutura->numrows > 0) {
             
           $oDadosEscolaEstrutura = db_utils::fieldsmemory($sResultEscolaEstrutura, 0);
           if (substr($oDadosEscolaEstrutura->ed255_c_localizacao,0,1) == 1 && $oDadosEscolaEstrutura->ed255_i_salaexistente == "") {
               
             $sMsgErro = " ESCOLA: Campo N° de Sala de Aula Existentes na Escola deve ser informado quando Prédio ";
             $sMsgErro = " Escolar(Local de Funcionamento da Escola) estiver marcado.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErro_Escola = true;
             
           }
           
           if ($oDadosEscolaEstrutura->ed255_i_salautilizada == "") {
               
             $sMsgErro = "ESCOLA: Campo N° de Salas Utilizadas como Sala de Aula não informado.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErro_Escola = true;
             
           }
           
           if (substr($oDadosEscolaEstrutura->ed255_c_localizacao,0,1)==1 && $oDadosEscolaEstrutura->ed255_i_formaocupacao == "") {
               
             $sMsgErro = " ESCOLA: Campo Forma de Ocupação do Prédio deve ser informado quando Prédio ";
             $sMsgErro .= " Escolar(Local de Funcionamento da Escola) estiver marcado.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErro_Escola = true;
             
            }
           
           if ($oDadosEscolaEstrutura->ed255_i_salautilizada == "0" || $oDadosEscolaEstrutura->ed255_i_salaexistente == "0") {
               
             $sMsgErro = " ESCOLA: Campo N° de Salas Utilizadas como Sala de Aula e N° de Sala de Aula Existentes na ";
             $sMsgErro = " Escola devem ser diferentes de zero.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErro_Escola = true;
             
           }
           
         } else {
             
           $sMsgErro = "ESCOLA: Campos referente a infraestrutura da escola não foram informados.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola                = true;
           $oDadosEscolaEstrutura->ed255_c_localizacao         = "";
           $oDadosEscolaEstrutura->ed255_i_compartilhado       = "";
           $oDadosEscolaEstrutura->ed255_i_escolacompartilhada = "";
           $oDadosEscolaEstrutura->ed255_i_aguafiltrada        = "";
           $oDadosEscolaEstrutura->ed255_c_abastagua           = "";
           $oDadosEscolaEstrutura->ed255_c_abastenergia        = "";
           $oDadosEscolaEstrutura->ed255_c_esgotosanitario     = "";
           $oDadosEscolaEstrutura->ed255_c_destinolixo         = "";
           $oDadosEscolaEstrutura->ed255_c_dependencias        = "";
           $oDadosEscolaEstrutura->ed255_i_salaexistente       = "";
           $oDadosEscolaEstrutura->ed255_i_salautilizada       = "";
           $oDadosEscolaEstrutura->ed255_c_equipamentos        = "";
           $oDadosEscolaEstrutura->ed255_i_computadores        = "";
           $oDadosEscolaEstrutura->ed255_i_qtdcomp             = "";
           $oDadosEscolaEstrutura->ed255_i_qtdcompadm          = "";
           $oDadosEscolaEstrutura->ed255_i_qtdcompalu          = "";
           $oDadosEscolaEstrutura->ed255_i_internet            = "";
           $oDadosEscolaEstrutura->ed255_i_bandalarga          = "";
           $oDadosEscolaEstrutura->ed255_i_alimentacao         = "";
           $oDadosEscolaEstrutura->ed255_i_ativcomplementar    = "";
           $oDadosEscolaEstrutura->ed255_c_materdidatico       = "";
           $oDadosEscolaEstrutura->ed255_i_aee                 = "";
           $oDadosEscolaEstrutura->ed255_i_efciclos            = "";
           
         }
         
         $qtdrechumano             = "";
         $sCamposRecHumanoEscola   = "count(*), ";
         $sCamposRecHumanoEscola  .= " case when ed20_i_tiposervidor = 1 ";
         $sCamposRecHumanoEscola  .= "      then cgmrh.z01_numcgm ";
         $sCamposRecHumanoEscola  .= " else cgmcgm.z01_numcgm end as z01_numcgm";
         $sWhereRecHumanoEscola    = " ed75_i_escola = $oDadosEscola->ed18_i_codigo GROUP BY ";
         $sWhereRecHumanoEscola   .= " case when ed20_i_tiposervidor = 1 ";
         $sWhereRecHumanoEscola   .= "      then cgmrh.z01_numcgm ";
         $sWhereRecHumanoEscola   .= " else cgmcgm.z01_numcgm end";
         
         $sSqlRecHumanoEscola      = $clrechumanoescola->sql_query("",$sCamposRecHumanoEscola,"",$sWhereRecHumanoEscola);
         $sResultRecHumanoEscola   = $clrechumanoescola->sql_record($sSqlRecHumanoEscola);
         
         if ($clrechumanoescola->numrows > 0) {
           $qtdrechumano = $clrechumanoescola->numrows;
         } else {
             
           $sMsgErro = "ESCOLA: Nenhum recurso humano foi informado.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErro_Escola = true;
           
         }
         
         $sCamposModalidade = " distinct ed10_i_tipoensino as codtipoensino ";
         $sWhereModalidade  = " turma.ed57_i_escola = $oDadosEscola->ed18_i_codigo AND calendario.ed52_i_ano = $ed52_i_ano ";
         $sWhereModalidade .= " AND ed60_c_situacao = 'MATRICULADO' AND ed60_d_datamatricula <= '$data_censo'";
         $sSqlModalidade    = $clmatricula->sql_query("",$sCamposModalidade,"ed10_i_tipoensino",$sWhereModalidade);
         $sResultModalidade = $clmatricula->sql_record($sSqlModalidade);         
         $modalidades_ens   = "";
         
         for ($b = 0; $b < 3; $b++) {
             
           $modal  = ($b+1);
           $naotem = false;
           
           for ($c = 0; $c < $clmatricula->numrows; $c++) {
                
             $codtipoensino = db_utils::fieldsmemory($sResultModalidade, $c)->codtipoensino;
             if (trim($modal) == trim($codtipoensino)) {
                 
               $modalidades_ens .= "1|";
               $naotem           = true;
               
             }
           }
           
           if ($naotem == false) {
             $modalidades_ens .= "0|";
           }          
         }
         
         $sCamposEtapaModal = "trim(ed273_c_etapa) as ed273_c_etapa,ed273_i_modalidade";
         $sSqlEtapaModal    = $clcensoetapamodal->sql_query("",$sCamposEtapaModal,"ed273_i_sequencia","");
         $sResultEtapaModal = $clcensoetapamodal->sql_record($sSqlEtapaModal);
         $etapa_ens         = "";
         
         for ($b = 0; $b < $clcensoetapamodal->numrows; $b++) {
             
           $oDadosModalidadeEtapa = db_utils::fieldsmemory($sResultEtapaModal, $b);
           $sCamposModalidade = " serie.ed11_i_codcenso as codigoetapa";
           $sWhereModalidade  = " turma.ed57_i_escola = $oDadosEscola->ed18_i_codigo AND calendario.ed52_i_ano = $ed52_i_ano ";
           $sWhereModalidade .= " AND ed60_c_situacao = 'MATRICULADO' AND serie.ed11_i_codcenso in ($oDadosModalidadeEtapa->ed273_c_etapa) ";
           $sWhereModalidade .= " AND ensino.ed10_i_tipoensino = $oDadosModalidadeEtapa->ed273_i_modalidade ";
           $sWhereModalidade .= " AND ed60_d_datamatricula <= '$data_censo'";
           
           $sSqlModalidade    = $clmatricula->sql_query("",$sCamposModalidade,"",$sWhereModalidade);
           $result_modalidade = $clmatricula->sql_record($sSqlModalidade);
           
           if ($clmatricula->numrows > 0) {
             $etapa_ens .= "1|";
           } else {
             $etapa_ens .= "0|";
           }    
         }
         
         if ($oDadosEscolaEstrutura->ed255_i_ativcomplementar == 2) {
             
           $modalidades_ens = "";
           $etapa_ens       = "";
           $oDadosEscolaEstrutura->ed255_i_aee     = "";
           
         }
         
         if ($oDadosEscolaEstrutura->ed255_i_aee == 2) {
             
           $modalidades_ens          = "";
           $etapa_ens                = "";
           $oDadosEscolaEstrutura->ed255_i_ativcomplementar = "";
           
         }
         
         $oDadosEscola->ed18_c_codigoinep           = $oDadosEscola->ed18_c_codigoinep;
         $oDadosEscola->ed18_i_funcionamento        = $oDadosEscola->ed18_i_funcionamento;
         $calinicio                   =  $ed52_d_inicio;//str_replace("/","",$ed52_d_inicio);
         $calfinal                    =  $ed52_d_fim;//str_replace("/","",$ed52_d_fim);
         $oDadosEscola->ed18_c_nome                 = TiraCaracteres($oDadosEscola->ed18_c_nome,2);
         $oDadosEscola->ed18_c_cep                  = $oDadosEscola->ed18_c_cep;
         $oDadosEscola->j14_nome                    = TiraCaracteres($oDadosEscola->j14_nome,3);
         $oDadosEscola->ed18_i_numero               = ($oDadosEscola->ed18_i_numero==0?"":$oDadosEscola->ed18_i_numero);
         $oDadosEscola->ed18_c_compl                = TiraCaracteres($oDadosEscola->ed18_c_compl,3);
         $oDadosEscola->j13_descr                   = TiraCaracteres(substr($oDadosEscola->j13_descr,0,50),3);
         $oDadosEscola->ed18_i_censouf              = $oDadosEscola->ed18_i_censouf;
         $oDadosEscola->ed18_i_censomunic           = $oDadosEscola->ed18_i_censomunic;
         $oDadosEscola->ed262_i_coddistrito         = $oDadosEscola->ed262_i_coddistrito;
         $oDadosEscola->ed18_c_email                = TiraCaracteres($oDadosEscola->ed18_c_email,4);
         $oDadosEscola->ed263_i_codigocenso         = $oDadosEscola->ed263_i_codigocenso;
         $oDadosEscola->ed18_c_mantenedora          = ($oDadosEscola->ed18_c_mantenedora==""?"3":$oDadosEscola->ed18_c_mantenedora);
         $oDadosEscola->ed18_c_local                = ($oDadosEscola->ed18_c_local==""?"1":$oDadosEscola->ed18_c_local);
         $oDadosEscola->ed18_i_cnpj                 = trim($oDadosEscola->ed18_i_cnpj);               
         $oDadosEscola->ed18_i_categprivada         = $oDadosEscola->ed18_i_categprivada;
         $oDadosEscola->ed18_i_conveniada           = $oDadosEscola->ed18_i_conveniada;
         $oDadosEscola->ed18_i_cnas                 = ($oDadosEscola->ed18_i_cnas=="0"?"":$oDadosEscola->ed18_i_cnas);
         $oDadosEscola->ed18_i_cebas                = ($oDadosEscola->ed18_i_cebas=="0"?"":$oDadosEscola->ed18_i_cebas);
         $oDadosEscola->ed18_c_mantprivada          = trim($oDadosEscola->ed18_c_mantprivada);//!=4?"":$oDadosEscola->ed18_c_mantprivada);
         $oDadosEscola->ed18_c_mantprivada          = ($oDadosEscola->ed18_c_mantenedora!=4?"":$oDadosEscola->ed18_c_mantprivada[0]).
                                        "|".($oDadosEscola->ed18_c_mantenedora!=4?"":$oDadosEscola->ed18_c_mantprivada[1]).
                                        "|".($oDadosEscola->ed18_c_mantenedora!=4?"":$oDadosEscola->ed18_c_mantprivada[2]).
                                        "|".($oDadosEscola->ed18_c_mantenedora!=4?"":$oDadosEscola->ed18_c_mantprivada[3]);
         $oDadosEscola->ed18_i_cnpjprivada          = trim($oDadosEscola->ed18_i_cnpjprivada);
         $oDadosEscola->ed18_i_credenciamento       = $oDadosEscola->ed18_i_credenciamento;      
         $oDadosEscola->ed18_i_cnpjmantprivada      = $oDadosEscola->ed18_i_cnpjmantprivada;
         $oDadosDiretor->z01_nome                    = TiraCaracteres($oDadosDiretor->z01_nome,1);
         $oDadosDiretor->z01_cgccpf                  = $oDadosDiretor->z01_cgccpf;
         $oDadosDiretor->rh37_descr                  = TiraCaracteres($oDadosDiretor->rh37_descr,2);
         $oDadosDiretor->ed254_c_email               = TiraCaracteres($oDadosDiretor->ed254_c_email,4);
         $oDadosEscolaEstrutura->ed255_c_localizacao         = trim($oDadosEscolaEstrutura->ed255_c_localizacao);
         $oDadosEscolaEstrutura->ed255_c_localizacao         = $oDadosEscolaEstrutura->ed255_c_localizacao[0].
                                        "|".$oDadosEscolaEstrutura->ed255_c_localizacao[1].
                                        "|".$oDadosEscolaEstrutura->ed255_c_localizacao[2].
                                        "|".$oDadosEscolaEstrutura->ed255_c_localizacao[3].
                                        "|".$oDadosEscolaEstrutura->ed255_c_localizacao[4].
                                        "|".$oDadosEscolaEstrutura->ed255_c_localizacao[5].
                                        "|".$oDadosEscolaEstrutura->ed255_c_localizacao[6].
                                        "|".$oDadosEscolaEstrutura->ed255_c_localizacao[7];
         $oDadosEscolaEstrutura->ed255_i_compartilhado       = $oDadosEscolaEstrutura->ed255_i_compartilhado;
         $oDadosEscolaEstrutura->ed255_i_formaocupacao       = $oDadosEscolaEstrutura->ed255_i_formaocupacao;
         $oDadosEscolaEstrutura->ed255_i_escolacompartilhada = ($oDadosEscolaEstrutura->ed255_i_escolacompartilhada==0?"":$oDadosEscolaEstrutura->ed255_i_escolacompartilhada);
         $restoescolacompartilhada    = "||||";
         $oDadosEscolaEstrutura->ed255_i_aguafiltrada        = $oDadosEscolaEstrutura->ed255_i_aguafiltrada;
         $oDadosEscolaEstrutura->ed255_c_abastagua           = trim($oDadosEscolaEstrutura->ed255_c_abastagua);
         $oDadosEscolaEstrutura->ed255_c_abastagua           = $oDadosEscolaEstrutura->ed255_c_abastagua[0].
                                        "|".$oDadosEscolaEstrutura->ed255_c_abastagua[1].
                                        "|".$oDadosEscolaEstrutura->ed255_c_abastagua[2].
                                        "|".$oDadosEscolaEstrutura->ed255_c_abastagua[3].
                                        "|".$oDadosEscolaEstrutura->ed255_c_abastagua[4]; 
         $oDadosEscolaEstrutura->ed255_c_abastenergia        = trim($oDadosEscolaEstrutura->ed255_c_abastenergia);
         $oDadosEscolaEstrutura->ed255_c_abastenergia         = $oDadosEscolaEstrutura->ed255_c_abastenergia[0].
                                        "|".$oDadosEscolaEstrutura->ed255_c_abastenergia[1].
                                        "|".$oDadosEscolaEstrutura->ed255_c_abastenergia[2].
                                        "|".$oDadosEscolaEstrutura->ed255_c_abastenergia[3];
         $oDadosEscolaEstrutura->ed255_c_esgotosanitario     = trim($oDadosEscolaEstrutura->ed255_c_esgotosanitario);
         $oDadosEscolaEstrutura->ed255_c_esgotosanitario     = $oDadosEscolaEstrutura->ed255_c_esgotosanitario[0].
                                        "|".$oDadosEscolaEstrutura->ed255_c_esgotosanitario[1].
                                        "|".$oDadosEscolaEstrutura->ed255_c_esgotosanitario[2];
         $oDadosEscolaEstrutura->ed255_c_destinolixo         = trim($oDadosEscolaEstrutura->ed255_c_destinolixo);
         $oDadosEscolaEstrutura->ed255_c_destinolixo         = $oDadosEscolaEstrutura->ed255_c_destinolixo[0].
                                        "|".$oDadosEscolaEstrutura->ed255_c_destinolixo[1].
                                        "|".$oDadosEscolaEstrutura->ed255_c_destinolixo[2].
                                        "|".$oDadosEscolaEstrutura->ed255_c_destinolixo[3].
                                        "|".$oDadosEscolaEstrutura->ed255_c_destinolixo[4].
                                        "|".$oDadosEscolaEstrutura->ed255_c_destinolixo[5];
         $oDadosEscolaEstrutura->ed255_c_dependencias        = trim($oDadosEscolaEstrutura->ed255_c_dependencias);
         $oDadosEscolaEstrutura->ed255_c_dependencias        = $oDadosEscolaEstrutura->ed255_c_dependencias[0].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[1].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[2].                                                       
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[3].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[4].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[5].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[6].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[7].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[8].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[9].         
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[10].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[11].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[12].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[13].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[14].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[15].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[16].
                                        "|".$oDadosEscolaEstrutura->ed255_c_dependencias[17];                                        
         $oDadosEscolaEstrutura->ed255_i_salaexistente       = $oDadosEscolaEstrutura->ed255_i_salaexistente;
         $oDadosEscolaEstrutura->ed255_i_salautilizada       = $oDadosEscolaEstrutura->ed255_i_salautilizada;
         $oDadosEscolaEstrutura->ed255_c_equipamentos        = trim($oDadosEscolaEstrutura->ed255_c_equipamentos);
         $oDadosEscolaEstrutura->ed255_c_equipamentos        = $oDadosEscolaEstrutura->ed255_c_equipamentos[0].
                                        "|".$oDadosEscolaEstrutura->ed255_c_equipamentos[1].
                                        "|".$oDadosEscolaEstrutura->ed255_c_equipamentos[2].
                                        "|".$oDadosEscolaEstrutura->ed255_c_equipamentos[3].
                                        "|".$oDadosEscolaEstrutura->ed255_c_equipamentos[4].
                                        "|".$oDadosEscolaEstrutura->ed255_c_equipamentos[5].
                                        "|".$oDadosEscolaEstrutura->ed255_c_equipamentos[6];
         $oDadosEscolaEstrutura->ed255_i_computadores        = $oDadosEscolaEstrutura->ed255_i_computadores;
         $oDadosEscolaEstrutura->ed255_i_qtdcomp             = ($oDadosEscolaEstrutura->ed255_i_computadores==0?"":$oDadosEscolaEstrutura->ed255_i_qtdcomp);
         $oDadosEscolaEstrutura->ed255_i_qtdcompadm          = ($oDadosEscolaEstrutura->ed255_i_computadores==0?"":$oDadosEscolaEstrutura->ed255_i_qtdcompadm);
         $oDadosEscolaEstrutura->ed255_i_qtdcompalu          = ($oDadosEscolaEstrutura->ed255_i_computadores==0?"":$oDadosEscolaEstrutura->ed255_i_qtdcompalu);
         $oDadosEscolaEstrutura->ed255_i_internet            = ($oDadosEscolaEstrutura->ed255_i_computadores==0?"":$oDadosEscolaEstrutura->ed255_i_internet);
         $oDadosEscolaEstrutura->ed255_i_bandalarga          = ($oDadosEscolaEstrutura->ed255_i_computadores==0)?"":($oDadosEscolaEstrutura->ed255_i_internet==0?"":$oDadosEscolaEstrutura->ed255_i_bandalarga);
         $qtdrechumano                = $qtdrechumano;
         $oDadosEscolaEstrutura->ed255_i_alimentacao         = $oDadosEscolaEstrutura->ed255_i_alimentacao;
         $oDadosEscolaEstrutura->ed255_i_aee                 = $oDadosEscolaEstrutura->ed255_i_aee;
         $oDadosEscolaEstrutura->ed255_i_efciclos            = $oDadosEscolaEstrutura->ed255_i_efciclos;
         $oDadosEscolaEstrutura->ed255_i_ativcomplementar    = $oDadosEscolaEstrutura->ed255_i_ativcomplementar;
         $oDadosEscola->ed18_i_locdiferenciada      = $oDadosEscola->ed18_i_locdiferenciada;
         $oDadosEscolaEstrutura->ed255_c_materdidatico       = trim($oDadosEscolaEstrutura->ed255_c_materdidatico);
         $oDadosEscolaEstrutura->ed255_c_materdidatico       = $oDadosEscolaEstrutura->ed255_c_materdidatico[0].
                                        "|".$oDadosEscolaEstrutura->ed255_c_materdidatico[1].
                                        "|".$oDadosEscolaEstrutura->ed255_c_materdidatico[2];
         $oDadosEscola->ed18_i_educindigena         = $oDadosEscola->ed18_i_educindigena;
         $oDadosEscola->ed18_i_tipolinguain         = ($oDadosEscola->ed18_i_educindigena==0?"":$oDadosEscola->ed18_i_tipolinguain);
         $oDadosEscola->ed18_i_tipolinguapt         = ($oDadosEscola->ed18_i_educindigena==0?"":$oDadosEscola->ed18_i_tipolinguapt);
         $oDadosEscola->ed18_i_linguaindigena       = $oDadosEscola->ed18_i_linguaindigena;
         
         
         if ($lErroEscola == false) {
             
         	// registro 00 refere-se ao cadastro da escola
           $num_linha++;
           $write_linha   = "00|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosEscola->ed18_i_funcionamento);
           $write_linha  .= "|".trim($calinicio)."|".trim($calfinal)."|".trim($oDadosEscola->ed18_c_nome);
           $write_linha  .= "|".trim($oDadosEscola->ed18_c_cep)."|".trim($oDadosEscola->j14_nome)."|".trim($oDadosEscola->ed18_i_numero);
           $write_linha  .= "|".trim($oDadosEscola->ed18_c_compl)."|".trim($oDadosEscola->j13_descr);
           $write_linha  .= "|".trim($oDadosEscola->ed18_i_censouf)."|".trim($oDadosEscola->ed18_i_censomunic);
           $write_linha  .= "|".trim($oDadosEscola->ed262_i_coddistrito)."|".trim($ddd)."|".trim($telefones);
           $write_linha  .= "|".trim($oDadosEscola->ed18_c_email)."|".trim($oDadosEscola->ed263_i_codigocenso);
           $write_linha  .= "|".trim($oDadosEscola->ed18_c_mantenedora)."|".trim($oDadosEscola->ed18_c_local);
           $write_linha  .= "|".trim($oDadosEscola->ed18_i_categprivada)."|".trim($oDadosEscola->ed18_i_conveniada);
           $write_linha  .= "|".trim($oDadosEscola->ed18_i_cnas)."|".trim($oDadosEscola->ed18_i_cebas);
           $write_linha  .= "|".$oDadosEscola->ed18_c_mantprivada;
           $write_linha  .= "|".trim($oDadosEscola->ed18_i_cnpjmantprivada)."|".trim($oDadosEscola->ed18_i_cnpjprivada);
           $write_linha  .= "|".trim($oDadosEscola->ed18_i_credenciamento)."|\n";
           fwrite($ponteiro,$write_linha);
           $num_linha++;
           
           //registro 10 refere-se ao cadastro de escola aba estrutura
           $write_linha  = "10|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosDiretor->z01_cgccpf)."|".trim($oDadosDiretor->z01_nome);
           $write_linha .= "|".trim($oDadosDiretor->rh37_descr)."|".trim($oDadosDiretor->ed254_c_email);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_c_localizacao)."|".trim($oDadosEscolaEstrutura->ed255_i_formaocupacao);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_i_compartilhado)."|".trim($oDadosEscolaEstrutura->ed255_i_escolacompartilhada);
           $write_linha .= "|".trim($restoescolacompartilhada)."|".trim($oDadosEscolaEstrutura->ed255_i_aguafiltrada);           
           $write_linha .= "|".$oDadosEscolaEstrutura->ed255_c_abastagua."|".$oDadosEscolaEstrutura->ed255_c_abastenergia;
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_c_esgotosanitario)."|".trim($oDadosEscolaEstrutura->ed255_c_destinolixo);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_c_dependencias)."|".trim($oDadosEscolaEstrutura->ed255_i_salaexistente);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_i_salautilizada)."|".trim($oDadosEscolaEstrutura->ed255_c_equipamentos)."|".trim($oDadosEscolaEstrutura->ed255_i_computadores);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_i_qtdcomp)."|".trim($oDadosEscolaEstrutura->ed255_i_qtdcompadm);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_i_qtdcompalu)."|".trim($oDadosEscolaEstrutura->ed255_i_internet);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_i_bandalarga)."|".trim($qtdrechumano);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_i_alimentacao)."|".trim($oDadosEscolaEstrutura->ed255_i_aee);
           $write_linha .= "|".trim($oDadosEscolaEstrutura->ed255_i_ativcomplementar)."|".trim($modalidades_ens);
           $write_linha .= "".trim($etapa_ens)."".trim($oDadosEscolaEstrutura->ed255_i_efciclos);
           $write_linha .= "|".trim($oDadosEscola->ed18_i_locdiferenciada)."|".trim($oDadosEscolaEstrutura->ed255_c_materdidatico);
           $write_linha .= "|".trim($oDadosEscola->ed18_i_educindigena)."|".trim($oDadosEscola->ed18_i_tipolinguain);
           $write_linha .= "|".trim($oDadosEscola->ed18_i_tipolinguapt)."|".trim($oDadosEscola->ed18_i_linguaindigena)."|\n";
           fwrite($ponteiro,$write_linha);
           
         }
         ///////////////////////////////////TURMA
         $sCamposTurma  = " ed57_i_codigoinep, ";
         $sCamposTurma .= "     ed57_i_codigo, ";
         $sCamposTurma .= "    trim(ed57_c_descr) as ed57_c_descr, ";
         $sCamposTurma .= "    fc_nomeetapaturma(ed57_i_codigo) as ed11_c_descr, ";
         $sCamposTurma .= "    (SELECT min(ed17_h_inicio)||max(ed17_h_fim) ";
         $sCamposTurma .= "     FROM periodoescola ";
         $sCamposTurma .= "     WHERE ed17_i_turno = ed57_i_turno) as horario, ";
         $sCamposTurma .= "    ed57_i_tipoatend, ";
         $sCamposTurma .= "    ed57_i_ativqtd, ";
         $sCamposTurma .= "    ed36_i_codigo, ";
         $sCamposTurma .= "    ed57_i_censocursoprofiss, ";
         $sCamposTurma .= "    ed57_i_censoetapa, ";
         $sCamposTurma .= "    ed57_i_tipoturma ";
         $sWhereTurma   = " ed57_i_escola = $oDadosEscola->ed18_i_codigo AND ed52_i_ano = $ed52_i_ano ";
         $sWhereTurma  .= " AND exists(select * from matricula where ed60_i_turma = ed57_i_codigo ";
         $sWhereTurma  .= "            AND ed60_d_datamatricula <= '$data_censo'  "; 
         $sWhereTurma  .= "            AND ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) ";
         $sWhereTurma  .= "            OR (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '$data_censo')))"; 
         
         $sSqlTurma     = $clturma->sql_query("",$sCamposTurma,"ed57_c_descr",$sWhereTurma); 
         $sResultTurma  = $clturma->sql_record($sSqlTurma);
         
         if ((trim($oDadosEscolaEstrutura->ed255_i_aee) == 2 || trim($oDadosEscolaEstrutura->ed255_i_ativcomplementar) == 2) && $clturma->numrows > 0) {
             
           if (trim($oDadosEscolaEstrutura->ed255_i_aee) == 2) $descr_tipo = "Atendimento Educacional Especial - AEE";
           if (trim($oDadosEscolaEstrutura->ed255_i_ativcomplementar) == 2) $descr_tipo = "Atividade Complementar";
           $sMsgErro  = " TURMA: Escola oferece EXCLUSIVAMENTE $descr_tipo ";
           $sMsgErro .= "(Cadastros -> Dados da Escola -> Aba infra Estrutura). Turmas de ensinos Regular, Eja e";
           $sMsgErro .= " Especial não devem ser informadas. \n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroTurma = true;
           
         }
         
         if ($clturma->numrows == 0) {
             
           $sMsgErro = "TURMA: Nenhuma turma contem matrícula ativa.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroTurma = true;
           
         } else {
             
           for ($b = 0; $b < $clturma->numrows; $b++) {
               
             $oDadosTurma = db_utils::fieldsmemory($sResultTurma, $b);
             db_atutermometro_edu($b, $clturma->numrows , 'termometro',1,'...Processando Turmas');
             $ed11_i_codcenso = $oDadosTurma->ed57_i_censoetapa;
             
             if ($oDadosTurma->ed36_i_codigo == 1) $campo_etapa = "ed266_c_regular";
             if ($oDadosTurma->ed36_i_codigo == 2) $campo_etapa = "ed266_c_especial";
             if ($oDadosTurma->ed36_i_codigo == 3) $campo_etapa = "ed266_c_eja";
             
             $sWhereCensoEtapa  = " ed266_i_codigo = $ed11_i_codcenso AND $campo_etapa = 'S'";
             $sSqlCensoEtapa    = $clcensoetapa->sql_query("", "", "ed266_i_codigo","",$sWhereCensoEtapa);
             $sResultCensoEtapa = $clcensoetapa->sql_record($sSqlCensoEtapa);
             
             if ($clcensoetapa->numrows == 0) {
                 
               $sMsgErro  = " TURMA: Turma ".trim($oDadosTurma->ed57_c_descr)." - ".trim($oDadosTurma->ed11_c_descr)." -> Etapa desta turma não é";
               $sMsgErro .= " compatível com a modalidade de ensino: Etapa - $ed11_i_codcenso";
               $sMsgErro .= " Modalidade - $oDadosTurma->ed36_i_codigo.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroTurma = true;
               
             }
             
             if (($oDadosTurma->ed57_i_tipoatend == 2 || $oDadosTurma->ed57_i_tipoatend == 3) && ($ed11_i_codcenso == 1 || $ed11_i_codcenso == 2 
                  || $ed11_i_codcenso == 3 || $ed11_i_codcenso == 56)) {

               $sMsgErro  = " TURMA: Turma ".trim($oDadosTurma->ed57_c_descr)." - ".trim($oDadosTurma->ed11_c_descr)." -> Etapa desta turma não";
               $sMsgErro .= " é compatível com o Tipo de Atendimento Unidade Prisional e/ou Unidade de Internação:";
               $sMsgErro .= " Etapa - $ed11_i_codcenso.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroTurma = true;

             }

             if ($oDadosTurma->ed57_i_censocursoprofiss == "" && $oDadosTurma->ed36_i_codigo == 1  
                 && ($ed11_i_codcenso == 30 || $ed11_i_codcenso == 31 || $ed11_i_codcenso == 32 
                     || $ed11_i_codcenso == 33  || $ed11_i_codcenso == 34 
                     || $ed11_i_codcenso == 39 || $ed11_i_codcenso == 40)) {
                         
               $sMsgErro  = " TURMA: Turma ".trim($oDadosTurma->ed57_c_descr)." - ".trim($oDadosTurma->ed11_c_descr)." -> Curso";
               $sMsgErro .= " Profissionalizante deve ser informado.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroTurma = true;
               
             }
             
             if ($oDadosTurma->ed57_i_censocursoprofiss == "" && $oDadosTurma->ed36_i_codigo == 2  
                 && ($ed11_i_codcenso == 30 || $ed11_i_codcenso == 31 || $ed11_i_codcenso == 32 
                     || $ed11_i_codcenso == 33  || $ed11_i_codcenso == 34 || $ed11_i_codcenso == 39 
                     || $ed11_i_codcenso == 40 || $ed11_i_codcenso == 62 || $ed11_i_codcenso == 63)) {
                 
               $sMsgErro  = "TURMA: Turma ".trim($oDadosTurma->ed57_c_descr)." - ".trim($oDadosTurma->ed11_c_descr)." -> Curso Profissionalizante";
               $sMsgErro .= " deve ser informado.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroTurma = true;
               
             }
             
             if ($oDadosTurma->ed57_i_censocursoprofiss == "" && $oDadosTurma->ed36_i_codigo == 3  
                && ($ed11_i_codcenso == 62 || $ed11_i_codcenso == 63)) {
                 
               $sMsgErro  = "TURMA: Turma ".trim($oDadosTurma->ed57_c_descr)." - ".trim($oDadosTurma->ed11_c_descr)." -> Curso Profissionalizante";
               $sMsgErro .= " deve ser informado.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroTurma = true;
               
             }
             
             if ($ed11_i_codcenso != 1 && $ed11_i_codcenso != 2 && $ed11_i_codcenso != 3) {
                 
               $array_disciplina = array("1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17",
                                         "20","21","23","25","26","27","99");
               $sCamposRegencia  = "ed59_i_codigo as codregencia,ed232_i_codigo,ed232_i_codcenso";
               $sSqlRegencia     = $clregencia->sql_query("",$sCamposRegencia,""," ed59_i_turma = $oDadosTurma->ed57_i_codigo");
               $sResultRegencia  = $clregencia->sql_record($sSqlRegencia);
               $disciplinas      = "";
               
               for ($y = 0; $y < 24; $y++) {
                   
                 $naotem = false;
                 for ($z = 0; $z < $clregencia->numrows; $z++) {
                     
                   $oDadosRegencia = db_utils::fieldsmemory($sResultRegencia, $z);
                   if ($array_disciplina[$y] == $oDadosRegencia->ed232_i_codcenso) {
                       
                     $sWhereCensoRegraDisc  = " ed272_i_censoetapa = $ed11_i_codcenso ";
                     $sWhereCensoRegraDisc .= " AND ed272_i_censodisciplina = $oDadosRegencia->ed232_i_codcenso";
                     $sSqlCensoRegraDisc    = $clcensoregradisc->sql_query("","ed272_i_codigo","",$sWhereCensoRegraDisc);
                     $sResultCensoRegraDisc = $clcensoregradisc->sql_record($sSqlCensoRegraDisc);
                     
                     if ($clcensoregradisc->numrows == 0) {
                         
                       $sMsgErro  = "TURMA: Turma ".trim($oDadosTurma->ed57_c_descr)." - ".trim($oDadosTurma->ed11_c_descr)." -> Disciplina desta";
                       $sMsgErro .= " turma não compatível com a Etapa: Disciplina - $oDadosRegencia->ed232_i_codcenso";
                       $sMsgErro .= " Etapa - $ed11_i_codcenso.\n";
                       fwrite($ponteiro2,$sMsgErro);
                       $lErroTurma = true;
                       
                     }
                     
                     $sWhereRegHorario       = " ed58_i_regencia = $oDadosRegencia->codregencia and ed58_ativo is true  ";
                     $sSqlRegenciaHorario    = $clregenciahorario->sql_query("","ed58_i_codigo as nada",
                                                                             "",$sWhereRegHorario);
                     $sResultRegenciaHorario = $clregenciahorario->sql_record($sSqlRegenciaHorario);
                     
                     if ($clregenciahorario->numrows == 0) {
                       $disciplinas .= "|2";
                     } else {
                       $disciplinas .= "|1";    
                     }
                     
                     $naotem = true;
                     break;
                     
                   }
                 }
                 
                 if ($naotem == false) {
                   $disciplinas .= "|0";
                 }
               }
               
             } else {                 
               $disciplinas        = "||||||||||||||||||||||||";
             }
             
             $oDadosTurma->ed57_i_codigoinep          = $oDadosTurma->ed57_i_codigoinep;
             $oDadosTurma->ed57_i_codigo              = $oDadosTurma->ed57_i_codigo;
             $oDadosTurma->ed57_c_descr               = TiraCaracteres($oDadosTurma->ed57_c_descr,2);
             $oDadosTurma->horario                    = str_replace(":","",$oDadosTurma->horario);
             $oDadosTurma->horario                    = $oDadosTurma->horario[0].$oDadosTurma->horario[1].
                                        "|".$oDadosTurma->horario[2].$oDadosTurma->horario[3].
                                        "|".$oDadosTurma->horario[4].$oDadosTurma->horario[5].
                                        "|".$oDadosTurma->horario[6].$oDadosTurma->horario[7];
             $oDadosTurma->ed57_i_tipoatend           = $oDadosTurma->ed57_i_tipoatend;
             $oDadosTurma->ed57_i_ativqtd             = "";
             $oDadosTurma->ed36_i_codigo              = $oDadosTurma->ed36_i_codigo;
             $ed11_i_codcenso            = $ed11_i_codcenso;
             $oDadosTurma->ed57_i_censocursoprofiss   = $oDadosTurma->ed57_i_censocursoprofiss;
             $ativcomplementar           = "|||||";
             $turma_aee                  = "||||||||||";
             
             if ($lErroTurma == false) {
                 
             	///registro 20 refere-se ao cadastro de turma
               $num_linha++;
               $write_linha  = "20|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosTurma->ed57_i_codigoinep);
               $write_linha .= "|".trim($oDadosTurma->ed57_i_codigo)."|".trim($oDadosTurma->ed57_c_descr)."|".trim($oDadosTurma->horario);
               $write_linha .= "|".trim($oDadosTurma->ed57_i_tipoatend)."|".trim($oDadosTurma->ed57_i_ativqtd);
               $write_linha .= "|".trim($ativcomplementar)."|".trim($turma_aee)."|".trim($oDadosTurma->ed36_i_codigo);
               $write_linha .= "|".trim($ed11_i_codcenso)."|".trim($oDadosTurma->ed57_i_censocursoprofiss);
               $write_linha .= trim($disciplinas)."|\n";
               fwrite($ponteiro,$write_linha);
               
             }
           }
         }
         ///////////////////////////////////TURMA ATENDIMENTO EDUCACIONAL ESPECIAL
         $sCamposTurmaac  = " ed268_i_codigoinep, ";
         $sCamposTurmaac .= " ed268_i_codigo, ";
         $sCamposTurmaac .= " trim(ed268_c_descr) as ed268_c_descr, ";
         $sCamposTurmaac .= " (SELECT min(ed17_h_inicio)||max(ed17_h_fim) ";
         $sCamposTurmaac .= "         FROM periodoescola ";
         $sCamposTurmaac .= "         WHERE ed17_i_turno = ed268_i_turno) as horario, ";
         $sCamposTurmaac .= " ed268_i_tipoatend, ";
         $sCamposTurmaac .= " ed268_i_ativqtd, ";
         $sCamposTurmaac .= " trim(ed268_c_aee) as ed268_c_aee ";
         $sWhereTurmaAc   = " ed268_i_escola = $oDadosEscola->ed18_i_codigo AND ed52_i_ano = $ed52_i_ano AND ed268_i_tipoatend = 5 ";
         $sWhereTurmaAc  .= " AND exists(select * from turmaacmatricula  ";
         //$sWhereTurmaAc  .= "                          inner join matricula on ed60_i_codigo = ed269_i_matricula  ";
         $sWhereTurmaAc  .= "                          where ed269_i_turmaac = ed268_i_codigo AND ";
         $sWhereTurmaAc  .= "                                ed60_d_datamatricula <= '$data_censo' AND";
         $sWhereTurmaAc  .= "                                ((ed60_c_situacao = 'MATRICULADO' ";
         $sWhereTurmaAc  .= "                                  and ed60_d_datasaida is null) ";
         $sWhereTurmaAc  .= "                                  OR (ed60_c_situacao != 'MATRICULADO' ";
         $sWhereTurmaAc  .= "                                  and ed60_d_datasaida > '$data_censo')))";
         
         $sSqlTurmaAc     = $clturmaac->sql_query("",$sCamposTurmaac,"ed268_c_descr",$sWhereTurmaAc);
         $sResultTurmaAc  = $clturmaac->sql_record($sSqlTurmaAc);
         
         if (trim($oDadosEscolaEstrutura->ed255_i_ativcomplementar) == 2 && $clturmaac->numrows > 0) {
             
           $sMsgErro  = "TURMA AEE: Escola oferece EXCLUSIVAMENTE Atividade Complementar (Cadastros ->";
           $sMsgErro .= " Dados da Escola -> Aba infra Estrutura). Turmas de AEE não devem ser informadas. \n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroTurma = true;
           
         }
         
         if ((trim($oDadosEscolaEstrutura->ed255_i_aee) == 2 || trim($oDadosEscolaEstrutura->ed255_i_aee) == 1) && $clturmaac->numrows == 0) {
             
           $sMsgErro  = "TURMA AEE: Escola oferece Atendimento Educacional Especializado (Cadastros ->";
           $sMsgErro .= " Dados da Escola -> Aba infra Estrutura) e não contém no sistema alunos";
           $sMsgErro .= " vinculados a este tipo de turma.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroTurma = true;
           
         } else if (trim($oDadosEscolaEstrutura->ed255_i_aee) == 0 && $clturmaac->numrows > 0) {
             
           $sMsgErro  = "TURMA AEE: Escola não oferece Atendimento Educacional Especializado (Cadastros ->";
           $sMsgErro .= " Dados da Escola -> Aba infra Estrutura) e contém informadas no sistema turmas deste tipo.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroTurma = true;
           
         } else {
             
           for ($b = 0; $b < $clturmaac->numrows; $b++) {

             $oDadosTurmaAc = db_utils::fieldsmemory($sResultTurmaAc, $b);
             db_atutermometro_edu($b, $clturmaac->numrows , 'termometro',1,'...Processando Turmas AEE');
             $sWhereTurmaacHorario  = " ed270_i_turmaac = $oDadosTurmaAc->ed268_i_codigo";
             $sSqlTurmaacHorario    = $clturmaachorario->sql_query("","ed270_i_codigo","",$sWhereTurmaacHorario);
             $sResultTurmaacHorario = $clturmaachorario->sql_record($sSqlTurmaacHorario);
             
             if ($clturmaachorario->numrows == 0) {
                 
               $sMsgErro = "TURMA AEE: Turma ".trim($oDadosTurmaAc->ed268_c_descr)." -> Sem docentes vinculados.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroTurma = true;
               
             }
             
             $iAtividades                = "|||||";
             $oDadosTurmaAc->ed268_i_codigoinep        = $oDadosTurmaAc->ed268_i_codigoinep;
             $oDadosTurmaAc->ed268_i_codigo            = $oDadosTurmaAc->ed268_i_codigo;
             $oDadosTurmaAc->ed268_c_descr             = TiraCaracteres($oDadosTurmaAc->ed268_c_descr,2);
             $oDadosTurmaAc->horario                   = str_replace(":","",$oDadosTurmaAc->horario);
             $oDadosTurmaAc->horario                   = $oDadosTurmaAc->horario[0].$oDadosTurmaAc->horario[1].
                                          "|".$oDadosTurmaAc->horario[2].$oDadosTurmaAc->horario[3].
                                          "|".$oDadosTurmaAc->horario[4].$oDadosTurmaAc->horario[5].
                                          "|".$oDadosTurmaAc->horario[6].$oDadosTurmaAc->horario[7];
             $oDadosTurmaAc->ed268_i_tipoatend         = $oDadosTurmaAc->ed268_i_tipoatend;
             $oDadosTurmaAc->ed268_i_ativqtd           = $oDadosTurmaAc->ed268_i_ativqtd;
             $oDadosTurmaAc->ed268_c_aee               = trim($oDadosTurmaAc->ed268_c_aee);              
             $oDadosTurmaAc->ed268_c_aee               = $oDadosTurmaAc->ed268_c_aee[0].
                                          "|".$oDadosTurmaAc->ed268_c_aee[1].
                                          "|".$oDadosTurmaAc->ed268_c_aee[3].
                                          "|".$oDadosTurmaAc->ed268_c_aee[4].
                                          "|".$oDadosTurmaAc->ed268_c_aee[5].
                                          "|".$oDadosTurmaAc->ed268_c_aee[6].
                                          "|".$oDadosTurmaAc->ed268_c_aee[7].
                                          "|".$oDadosTurmaAc->ed268_c_aee[8].
                                          "|".$oDadosTurmaAc->ed268_c_aee[9].
                                          "|".$oDadosTurmaAc->ed268_c_aee[10].
                                          "|".$oDadosTurmaAc->ed268_c_aee[11];
             
             $oDadosTurmaAc->ed268_i_censocursoprofiss = "";
             $disciplinas               = "|||||||||||||||||||||||";
             
             if ($lErroTurma == false) {
                 
               $num_linha++;
               $write_linha  = "20|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosTurmaAc->ed268_i_codigoinep);
               $write_linha .= "|".trim($oDadosTurmaAc->ed268_i_codigo)."|".trim($oDadosTurmaAc->ed268_c_descr)."|".trim($oDadosTurmaAc->horario);
               $write_linha .= "|".trim($oDadosTurmaAc->ed268_i_tipoatend)."|".trim($oDadosTurmaAc->ed268_i_ativqtd)."|".trim($iAtividades);
               $write_linha .= "|".trim($oDadosTurmaAc->ed268_c_aee)."||";
               $write_linha .= "|".trim($oDadosTurmaAc->ed268_i_censocursoprofiss)."|".trim($disciplinas)."|\n";
               fwrite($ponteiro,$write_linha);
               
             }
           }
         } 
         ///////////////////////////////////TURMA ATIVIDADE COMPLEMENTAR
         $sCamposTurmaAc  = " ed268_i_codigoinep, "; 
         $sCamposTurmaAc .= " ed268_i_codigo, ";
         $sCamposTurmaAc .= " trim(ed268_c_descr) as ed268_c_descr, ";
         $sCamposTurmaAc .= " (SELECT min(ed17_h_inicio)||max(ed17_h_fim) ";
         $sCamposTurmaAc .= "       FROM periodoescola ";
         $sCamposTurmaAc .= "       WHERE ed17_i_turno = ed268_i_turno) as horario, ";
         $sCamposTurmaAc .= " ed268_i_tipoatend, ";
         $sCamposTurmaAc .= " ed268_i_ativqtd, ";
         $sCamposTurmaAc .= " trim(ed268_c_aee) as ed268_c_aee ";
         $sWhereTurmaac   = " ed268_i_escola = $oDadosEscola->ed18_i_codigo AND ed52_i_ano = $ed52_i_ano AND ed268_i_tipoatend = 4 ";
         $sWhereTurmaac  .= " AND exists(select * from turmaacmatricula inner join matricula ";
         $sWhereTurmaac  .= "                                           on ed60_i_codigo = ed269_i_matricula";
         $sWhereTurmaac  .= "                     where ed269_i_turmaac = ed268_i_codigo ";
         $sWhereTurmaac  .= "                           AND ed60_d_datamatricula <= '$data_censo' ";
         $sWhereTurmaac  .= "                           AND ((ed60_c_situacao = 'MATRICULADO' ";
         $sWhereTurmaac  .= "                                 and ed60_d_datasaida is null) ";
         $sWhereTurmaac  .= "                           OR (ed60_c_situacao != 'MATRICULADO' ";
         $sWhereTurmaac  .= "                               and ed60_d_datasaida > '$data_censo')))";
         
         $sSqlTurmaac     = $clturmaac->sql_query("",$sCamposTurmaAc,"ed268_c_descr",$sWhereTurmaac);
         $sResultTurmaac  = $clturmaac->sql_record($sSqlTurmaac);
         //echo '<br><br>'.$sSqlTurmaac;
         
         if (trim($oDadosEscolaEstrutura->ed255_i_aee) == 2 && $clturmaac->numrows > 0) {
             
           $sMsgErro  = "TURMA ATIVIDADE COMPLEMENTAR: Escola oferece EXCLUSIVAMENTE Atendimento Educacional Especial";
           $sMsgErro .= " - AEE (Cadastros -> Dados da Escola -> Aba infra Estrutura). Turmas de Atividade";
           $sMsgErro .= " Complementar não devem ser informadas. \n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroTurma = true;
           
         }
         
         if ((trim($oDadosEscolaEstrutura->ed255_i_ativcomplementar) == 2 
              || trim($oDadosEscolaEstrutura->ed255_i_ativcomplementar) == 1) && $clturmaac->numrows == 0) {
                  
           $sMsgErro  = "TURMA ATIVIDADE COMPLEMENTAR: Escola oferece Atividade Complementar (Cadastros ->";
           $sMsgErro .= " Dados da Escola -> Aba infra Estrutura) e não contém no sistema alunos vinculados";
           $sMsgErro .= " a este tipo de turma.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroTurma = true;
           
         } else if (trim($oDadosEscolaEstrutura->ed255_i_ativcomplementar) == 0 && $clturmaac->numrows > 0) {
             
           $sMsgErro  = "TURMA ATIVIDADE COMPLEMENTAR: Escola não oferece Atividade Complementar (Cadastros ->";
           $sMsgErro .= " Dados da Escola -> Aba infra Estrutura) e contém informadas no sistema turmas deste tipo.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroTurma = true;
           
         } else {
             
           for ($b = 0; $b < $clturmaac->numrows; $b++) {
               
             
             $oDadosTurmaAc2 = db_utils::fieldsmemory($sResultTurmaac, $b);
             $sProcessando = '...Processando Turmas com Atividade Complementar';
             db_atutermometro_edu($b, $clturmaac->numrows , 'termometro',1,$sProcessando);
             $sWhereTurmaacAtiv  = " ed267_i_turmaac = $oDadosTurmaAc2->ed268_i_codigo LIMIT 6";             
             $sSqlTurmaacAtiv    = $clturmaacativ->sql_query("","ed267_i_censoativcompl","",$sWhereTurmaacAtiv);
             $sResultTurmaacAtiv = $clturmaacativ->sql_record($sSqlTurmaacAtiv);
             
             if ($clturmaacativ->numrows == 0 && $oDadosTurmaAc2->ed268_i_tipoatend == 4) {
                 
               $sMsgErro  = "TURMA ATIVIDADE COMPLEMENTAR: Turma ".trim($oDadosTurmaAc2->ed268_c_descr)." -> Sem atividade";
               $sMsgErro .= " complementar cadastrada.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroTurma = true;
               
             }
             
             $sWhereTurmaacHorario  = " ed270_i_turmaac = $oDadosTurmaAc2->ed268_i_codigo";
             $sSqlTurmaacHorario    = $clturmaachorario->sql_query("","ed270_i_codigo,ed268_i_tipoatend","",$sWhereTurmaacHorario);
             $sResultTurmaacHorario = $clturmaachorario->sql_record($sSqlTurmaacHorario);
             if ($clturmaachorario->numrows == 0) {
                 
               $sMsgErro = "TURMA ATIVIDADE COMPLEMENTAR: Turma ".trim($oDadosTurmaAc2->ed268_c_descr)." -> Sem docentes vinculados.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroTurma = true;
               
             }
             
             $atividades = "";
             for ($z = 0; $z < $clturmaacativ->numrows; $z++) {
                 
               $ed267_i_censoativcompl = db_utils::fieldsmemory($sResultTurmaacAtiv, $z)->ed267_i_censoativcompl;
               $atividades .= $ed267_i_censoativcompl."|";
               
             }
             for ($z = $clturmaacativ->numrows; $z < 6; $z++) {
               $atividades .= "|";///tirei o | ultima modificcacao
             }
             
             $oDadosTurmaAc2->ed268_i_codigoinep        = $oDadosTurmaAc2->ed268_i_codigoinep;
             $oDadosTurmaAc2->ed268_i_codigo            = $oDadosTurmaAc2->ed268_i_codigo;
             $oDadosTurmaAc2->ed268_c_descr             = TiraCaracteres($oDadosTurmaAc2->ed268_c_descr,2);
             $oDadosTurmaAc2->horario                   = str_replace(":","",$oDadosTurmaAc2->horario);
             $oDadosTurmaAc2->horario                   = $oDadosTurmaAc2->horario[0].$oDadosTurmaAc2->horario[1].
                                          "|".$oDadosTurmaAc2->horario[2].$oDadosTurmaAc2->horario[3].
                                          "|".$oDadosTurmaAc2->horario[4].$oDadosTurmaAc2->horario[5].
                                          "|".$oDadosTurmaAc2->horario[6].$oDadosTurmaAc2->horario[7];                                      
             $oDadosTurmaAc2->ed268_i_tipoatend         = $oDadosTurmaAc2->ed268_i_tipoatend;
             $oDadosTurmaAc2->ed268_i_ativqtd           = $oDadosTurmaAc2->ed268_i_ativqtd;
             $oDadosTurmaAc2->ed268_c_aee               = trim($oDadosTurmaAc2->ed268_c_aee);              
             $oDadosTurmaAc2->ed268_c_aee               = "||||||||||||"; 
             $oDadosTurmaAc2->ed268_i_censocursoprofiss = "";
             $disciplinas               = "|||||||||||||||||||||||";
             
             if ($lErroTurma == false) {
                 
               $num_linha++;
               $write_linha  = "20|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosTurmaAc2->ed268_i_codigoinep);
               $write_linha .= "|".trim($oDadosTurmaAc2->ed268_i_codigo)."|".($oDadosTurmaAc2->ed268_c_descr)."|".trim($oDadosTurmaAc2->horario);
               $write_linha .= "|".trim($oDadosTurmaAc2->ed268_i_tipoatend)."|".trim($oDadosTurmaAc2->ed268_i_ativqtd)."|".trim($atividades);
               $write_linha .= trim($oDadosTurmaAc2->ed268_c_aee)."||";
               $write_linha .= trim($oDadosTurmaAc2->ed268_i_censocursoprofiss)."|";
               $write_linha .= trim($disciplinas)."\n";
               fwrite($ponteiro,$write_linha);
               
             }
           }
         } 
         
         ///////////////////////////////////DOCENTE
         $sCamposCgm     = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else ";
         $sCamposCgm    .= "           cgmcgm.z01_numcgm end as z01_numcgm, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_nome) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_nome) end as z01_nome, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_email) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_email) end as z01_email, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then rhpessoal.rh01_nasc else";
         $sCamposCgm    .= "           cgmcgm.z01_nasc end as rh01_nasc, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then rhpessoal.rh01_sexo else";
         $sCamposCgm    .= "           cgmcgm.z01_sexo end as rh01_sexo, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_mae) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_mae) end as z01_mae, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_cgccpf) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_cgccpf) end as z01_cgccpf, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_cep) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_cep) end as z01_cep, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_ender) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_ender) end as z01_ender, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_numero) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_numero) end as z01_numero, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_compl) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_compl) end as z01_compl, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_ident) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_ident) end as z01_ident, ";
         $sCamposCgm    .= " case when ed20_i_tiposervidor = 1 then trim(cgmrh.z01_bairro) else ";
         $sCamposCgm    .= "           trim(cgmcgm.z01_bairro) end as z01_bairro ";
         
         $sWhereCgm      = " ed18_i_codigo = $oDadosEscola->ed18_i_codigo AND ed01_c_regencia = 'S'";                          
         $sSqlDocente    = $clrechumano->sql_query_escola("","DISTINCT ".$sCamposCgm,"z01_nome",$sWhereCgm);         
         $sResultDocente = $clrechumano->sql_record($sSqlDocente);
         $iLinhasDocente = $clrechumano->numrows;
         
         if ($iLinhasDocente == 0) {
             
           $sMsgErro = "DOCENTE: Nenhum docente cadastrado para esta escola ou sem atividades de regência cadastrada.\n";
           fwrite($ponteiro2,$sMsgErro);
           $lErroDocente = true;
           
         } else {
             
           for ($b = 0; $b < $iLinhasDocente; $b++) {
               
             $oDadosDocente = db_utils::fieldsmemory($sResultDocente, $b);
             db_atutermometro_edu($b, $iLinhasDocente , 'termometro',1,'...Processando Docentes');
             $sCamposRechumano  = " ed20_i_codigo, ";
             $sCamposRechumano .= " ed20_i_codigoinep, ";
             $sCamposRechumano .= " trim(ed20_c_nis) as ed20_c_nis, ";
             $sCamposRechumano .= " ed20_i_raca, ";
             $sCamposRechumano .= " ed20_i_nacionalidade, ";
             $sCamposRechumano .= " ed228_i_paisonu, ";
             $sCamposRechumano .= " ed20_i_censoufnat, ";
             $sCamposRechumano .= " ed20_i_censomunicnat, "; 
             $sCamposRechumano .= " ed20_i_censoufender, ";
             $sCamposRechumano .= " ed20_i_censomunicender, ";
             $sCamposRechumano .= " ed20_i_escolaridade, ";
             $sCamposRechumano .= " ed20_c_identcompl, ";
             $sCamposRechumano .= " ed20_i_censoorgemiss, ";
             $sCamposRechumano .= " ed20_i_censoufident, ";
             $sCamposRechumano .= " ed20_d_dataident, ";
             $sCamposRechumano .= " ed20_i_censoorgemiss, ";
             $sCamposRechumano .= " ed20_i_certidaotipo, ";
             $sCamposRechumano .= " ed20_c_certidaonum, ";
             $sCamposRechumano .= " ed20_c_certidaofolha, ";
             $sCamposRechumano .= " ed20_c_certidaolivro, ";
             $sCamposRechumano .= " ed20_c_certidaodata, ";
             $sCamposRechumano .= " ed20_i_censoufcert, ";
             $sCamposRechumano .= " ed20_c_certidaocart,"; 
             $sCamposRechumano .= " ed20_c_passaporte, ";
             $sCamposRechumano .= " trim(ed20_c_posgraduacao) as ed20_c_posgraduacao, ";
             $sCamposRechumano .= " trim(ed20_c_outroscursos) as ed20_c_outroscursos, ";             
             $sCamposRechumano    .= " case when rhregime.rh30_naturezaregime = 1 then 1 ";
             $sCamposRechumano    .= "      when rhregime.rh30_naturezaregime = 2 or rhregime.rh30_naturezaregime = 3 ";
             $sCamposRechumano    .= "           then 2 ";
             $sCamposRechumano    .= "      when rhregime.rh30_naturezaregime = 4";
             $sCamposRechumano    .= "           then 3 ";
             $sCamposRechumano    .= "      else null ";
             $sCamposRechumano    .= "      end as informecenso ";
         
             $sWhereRechumano  = " ed18_i_codigo = $oDadosEscola->ed18_i_codigo AND case when ed20_i_tiposervidor = 1 then";
             $sWhereRechumano .= " cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = $oDadosDocente->z01_numcgm ";
             $sWhereRechumano .= " AND ed01_c_regencia = 'S' LIMIT 1";
             $sSqlRechumano    = $clrechumano->sql_query_censo("",$sCamposRechumano,"",$sWhereRechumano);                         
             $sResultRechumano = $clrechumano->sql_record($sSqlRechumano);             
             $oDadosRecHumano  = db_utils::fieldsmemory($sResultRechumano, 0);
             $formacaorh = "";
             
             if ($oDadosRecHumano->ed20_i_escolaridade == 6) {

               $sCamposFormacao  = " ed20_i_codigo as codrechumano,ed27_i_rechumano,ed27_c_situacao,ed27_i_formacaopedag,";
               $sCamposFormacao .= " ed27_i_cursoformacao,trim(ed94_c_codigocenso) as ed94_c_codigocenso,case when strpos(upper(ed94_c_descr),'BACHARELADO') > 0 then 1 else 0 end as ibacharelado,";
               $sCamposFormacao .= " ed27_i_anoconclusao,ed27_i_anoinicio,ed257_i_tipo,ed27_i_censoinstsuperior,ed27_i_licenciatura ";
               $sSqlFormacao     = " SELECT $sCamposFormacao";
               $sSqlFormacao    .= "      FROM formacao ";
               $sSqlFormacao    .= "           left join censoinstsuperior  on  censoinstsuperior.ed257_i_codigo ";
               $sSqlFormacao    .= "                                          = formacao.ed27_i_censoinstsuperior ";
               $sSqlFormacao    .= "           inner join rechumano  on  rechumano.ed20_i_codigo ";
               $sSqlFormacao    .= "                                                    = formacao.ed27_i_rechumano ";
               $sSqlFormacao    .= "           inner join cursoformacao  on  cursoformacao.ed94_i_codigo ";
               $sSqlFormacao    .= "                                                 = formacao.ed27_i_cursoformacao ";
               $sSqlFormacao    .= "           inner join rechumanoescola  on  rechumanoescola.ed75_i_rechumano ";
               $sSqlFormacao    .= "                                                       = rechumano.ed20_i_codigo ";
               $sSqlFormacao    .= "      WHERE ed75_i_rechumano = $oDadosRecHumano->ed20_i_codigo ";
               $sSqlFormacao    .= "            AND ed75_i_escola = $oDadosEscola->ed18_i_codigo ";
               $sSqlFormacao    .= "      LIMIT 3 ";             
  
               $sResultFormacao  = $clformacao->sql_record($sSqlFormacao);
               
               if ($clformacao->numrows == 0) {
                   
                 $sMsgErro  = "DOCENTE: Docente ".$oDadosFormacao->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Escolaridade SUPERIOR";
                 $sMsgErro .= " COMPLETO deve ter pelo menos um curso de formação cadastrado (Formação).\n";
                 fwrite($ponteiro2,$sMsgErro);
                 $lErroDocente = true;
                 
               }
               unset($cod_cursoformacao);
               for ($c = 0; $c < $clformacao->numrows; $c++) {
                   
                 $oDadosFormacao = db_utils::fieldsmemory($sResultFormacao, $c);
                 $cod_cursoformacao[] = $oDadosFormacao->ed27_i_cursoformacao;
                                  
                 if ($oDadosFormacao->ed27_i_cursoformacao == "") {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosFormacao->codrechumano." ".trim($oDadosDocente->z01_nome)." -> Campo Curso de Formação";
                   $sMsgErro .= " não informado (Formação).\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 }
                 
                 if ($oDadosFormacao->ed27_i_anoconclusao == "" && $oDadosFormacao->ed27_c_situacao=="CON") {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosFormacao->codrechumano." ".trim($oDadosDocente->z01_nome)." -> Campo Ano de Conclusão";
                   $sMsgErro .= " não informado (Formação).\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 }
                 
               if ($oDadosFormacao->ed27_i_licenciatura == 1 && $oDadosFormacao->ed27_i_formacaopedag == 1) {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosFormacao->codrechumano." ".trim($oDadosDocente->z01_nome)." -> Campo Formação pedagógica";
                   $sMsgErro .= " só podera ser informado  quando o curso for Bacharelado (Formação).\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 }
                 
                 if ($oDadosFormacao->ed27_i_anoinicio == "" && $oDadosFormacao->ed27_c_situacao=="CUR") {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosFormacao->codrechumano." ".trim($oDadosDocente->z01_nome)." -> Campo Ano de Início";
                   $sMsgErro .= " não informado (Formação).\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 }
                 
                 if ($oDadosFormacao->ed27_i_censoinstsuperior == "") {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosFormacao->codrechumano." ".trim($oDadosDocente->z01_nome)." -> Campo Instituição de";
                   $sMsgErro .= " Ensino Superior não informado (Formação).\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 }
                 
                 if (count($cod_cursoformacao) > 1) { 
                     
                   if (count($cod_cursoformacao) == 2) {
                       
                     if ($cod_cursoformacao[0] == $cod_cursoformacao[1]) {
                         
                       $sMsgErro  = "DOCENTE: Docente ".$oDadosFormacao->codrechumano." ".trim($oDadosDocente->z01_nome)." -> Mesmo Código do Curso";
                       $sMsgErro .= " Superior informado mais de uma vez (Formação).\n";
                       fwrite($ponteiro2,$sMsgErro);
                       $lErroDocente = true;
                       
                     }
                   }
                   
                   if (count($cod_cursoformacao) == 3) {
                       
                     if ($cod_cursoformacao[0] == $cod_cursoformacao[1] 
                         || $cod_cursoformacao[0] == $cod_cursoformacao[2] 
                         || $cod_cursoformacao[1] == $cod_cursoformacao[2]) {
                         
                       $sMsgErro  = "DOCENTE: Docente ".$oDadosFormacao->codrechumano." ".trim($oDadosDocente->z01_nome)." -> Mesmo Código do Curso";
                       $sMsgErro .= " Superior informado mais de uma vez (Formação).\n";
                       fwrite($ponteiro2,$sMsgErro);
                       $lErroDocente = true;
                       
                     }
                   }
                 }                 
                 
                 $oDadosFormacao->ed27_i_cursoformacao     = $oDadosFormacao->ed94_c_codigocenso;
                 $oDadosFormacao->ed27_i_anoconclusao      = $oDadosFormacao->ed27_i_anoconclusao;
                 $oDadosFormacao->ed27_i_anoinicio         = $oDadosFormacao->ed27_i_anoinicio;
                 $oDadosFormacao->ed257_i_tipo             = $oDadosFormacao->ed257_i_tipo;
                 //echo "<br>$oDadosDocente->z01_nome ($oDadosFormacao->ed27_c_situacao)";
                 if ($oDadosFormacao->ed27_c_situacao == "CON") {
                 	if ($oDadosFormacao->ibacharelado == 0) {
                 	  	$oDadosFormacao->ed27_i_formacaopedag = '';                 		
                 	} 
                   $situacao = 1;                                      
                 } elseif ($oDadosFormacao->ed27_c_situacao == "CUR") {
                   $situacao = 2;	
                   $oDadosFormacao->ed27_i_formacaopedag = "";
                 } else {
                   $situacao = "";
                   $oDadosFormacao->ed27_i_formacaopedag = "";
                 }                 
                 $oDadosFormacao->ed27_i_censoinstsuperior = $oDadosFormacao->ed27_i_censoinstsuperior;
                 $formacaorh              .= $situacao."|".$oDadosFormacao->ed27_i_formacaopedag."|".$oDadosFormacao->ed27_i_cursoformacao."|".$oDadosFormacao->ed27_i_anoinicio."|".$oDadosFormacao->ed27_i_anoconclusao;
                 $formacaorh              .= "|".$oDadosFormacao->ed257_i_tipo."|".$oDadosFormacao->ed27_i_censoinstsuperior;
                 
               }
               
               for ($c = $clformacao->numrows; $c < 3; $c++) {
                 $formacaorh .= "|||||||";
               }
               
             } else {
               $formacaorh .= "||||||||||||||||||||";
             }
             
             $sCamposHorario         = "ed57_i_codigo, ed57_i_codigoinep, ed57_i_censoetapa ";
             $sWhereRegenciaHorario  = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else ";
             $sWhereRegenciaHorario .= "cgmcgm.z01_numcgm end = $oDadosDocente->z01_numcgm AND ed52_i_ano = $ed52_i_ano ";
             $sWhereRegenciaHorario .= " AND ed57_i_escola = $oDadosEscola->ed18_i_codigo and ed58_ativo is true  ";
             $sSqlRegenciaHorario    = $clregenciahorario->sql_query("","DISTINCT ".$sCamposHorario,
                                                                     "ed57_i_codigo",$sWhereRegenciaHorario);
             $sResultHorario         = $clregenciahorario->sql_record($sSqlRegenciaHorario);
             $sCamposHorarioAc       = " ed268_i_codigo,ed268_i_codigoinep ";
             $sWhereHorarioAc        = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else ";
             $sWhereHorarioAc       .= " cgmcgm.z01_numcgm end = $oDadosDocente->z01_numcgm AND ed52_i_ano = $ed52_i_ano ";
             $sWhereHorarioAc       .= " AND ed268_i_escola = $oDadosEscola->ed18_i_codigo";
             $sSqlHorarioAc          = $clturmaachorario->sql_query("","DISTINCT ".$sCamposHorarioAc,
                                                                    "ed268_i_codigo",$sWhereHorarioAc);
             $sResultHorarioAc       = $clturmaachorario->sql_record($sSqlHorarioAc);
             if ($clregenciahorario->numrows == 0 && $clturmaachorario->numrows == 0) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Nenhum vínculo com turmas";
               $sMsgErro .= " informado (Horário da Turma).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_codigoinep != "" && strlen($oDadosRecHumano->ed20_i_codigoinep) < 12) {
                 
               $sMsgErro = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Código INEP inválido.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->z01_email != "" && (!strstr($oDadosDocente->z01_email,"@")  || !strstr($oDadosDocente->z01_email,"."))) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Email deve conter";
               $sMsgErro .= " arroba e ponto.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->rh01_nasc != "") {
                 
               $oDadosDocente->rh01_nasc_dia = substr($oDadosDocente->rh01_nasc,8,2);
               $oDadosDocente->rh01_nasc_mes = substr($oDadosDocente->rh01_nasc,5,2);
               $oDadosDocente->rh01_nasc_ano = substr($oDadosDocente->rh01_nasc,0,4);
               
               if (!checkdate($oDadosDocente->rh01_nasc_mes,$oDadosDocente->rh01_nasc_dia,$oDadosDocente->rh01_nasc_ano)) {
                   
                 $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data de";
                 $sMsgErro .= " nascimento inválido.\n";
                 fwrite($ponteiro2,$sMsgErro);
                 $lErroDocente = true;
                 
               } else {
                   
                 if (str_replace("-","",$oDadosDocente->rh01_nasc) >= str_replace("-","",$hoje)) {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data de";
                   $sMsgErro .= " nascimento deve ser menor que a data corrente.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 }
                 
                 if ($oDadosDocente->rh01_nasc_ano < 1919) {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Ano da Data de nascimento";
                   $sMsgErro .= " deve ser maior ou igual a 1919.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 }
               }
               
             } else {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data de nascimento";
               $sMsgErro .= " não informado.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->rh01_sexo == "") {
                 
               $sMsgErro = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Sexo não informado.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if (($oDadosRecHumano->ed20_i_nacionalidade == 1 || $oDadosRecHumano->ed20_i_nacionalidade == 2) && $oDadosRecHumano->ed228_i_paisonu != 76) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Nacionalidade Brasileira";
               $sMsgErro .= " ou Brasileira no Exterior. Campo pais deve ser BRASIL.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_nacionalidade == 3 && $oDadosRechumano->ed228_i_paisonu == 76) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Nacionalidade Estrangeira.";
               $sMsgErro .= " Campo pais deve ser diferente de BRASIL.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_nacionalidade == 1 && ($oDadosRecHumano->ed20_i_censoufnat == "" || $oDadosRecHumano->ed20_i_censomunicnat == "")) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Nacionalidade Brasileira.";
               $sMsgErro .= " UF de Nascimento e Naturalidade devem ser informados.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_nacionalidade == 3 && ($oDadosDocente->z01_ident != "" || $oDadosRecHumano->ed20_c_identcompl != "" 
                 || $oDadosRecHumano->ed20_i_censoorgemiss != "" || $oDadosRecHumano->ed20_i_censoufident != "" || $oDadosRecHumano->ed20_d_dataident != "")) {
                  
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Nacionalidade Estrangeira.";
               $sMsgErro .= " Campos referente a Identidade NÃO devem ser informados.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->z01_ident == "" && ($oDadosRecHumano->ed20_c_identcompl != "" || $oDadosRecHumano->ed20_i_censoorgemiss != "" 
                 || $oDadosRecHumano->ed20_i_censoufident != "" || $oDadosRecHumano->ed20_d_dataident != "")) {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo N° Identidade deve ser";
               $sMsgErro .= " informado quando um dos campos estiverem informados: (Complemento - UF Identidade -";
               $sMsgErro .= " Órgao Emissor - Data Expedição Identidade).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_censoorgemiss == "" && ($oDadosDocente->z01_ident != "" || $oDadosRecHumano->ed20_i_censoufident != "")) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Órgão Emissor deve";
               $sMsgErro .= " ser informado quando um dos campos estiverem informados:";
               $sMsgErro .= " (N° Identidade - UF Identidade).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_censoufident == "" && ($oDadosDocente->z01_ident != "" || $oDadosRecHumano->ed20_i_censoorgemiss != "")) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo UF Identidade deve";
               $sMsgErro .= " ser informado quando um dos campos estiverem informados:";
               $sMsgErro .= " (N° Identidade - Órgão Emissor).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_c_identcompl != "" && $oDadosDocente->z01_ident == "" && $oDadosRecHumano->ed20_i_censoorgemiss == " " 
                 && $oDadosRecHumano->ed20_i_censoufident == "") {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Complemento só pode";
               $sMsgErro .= " ser informado quando um dos campos estiverem informados: (N° Identidade - Órgão Emissor";
               $sMsgErro .= " - UF Identidade).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_d_dataident != "" && $oDadosDocente->z01_ident == "" && $oDadosRecHumano->ed20_i_censoorgemiss == "" 
                 && $oDadosRecHumano->ed20_i_censoufident == "") {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data Expedição";
               $sMsgErro .= " Identidade só pode ser informado quando um dos campos estiverem informados:";
               $sMsgErro .= " (N° Identidade - Órgão Emissor - UF Identidade).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_d_dataident != "") {
                 
               $oDadosRecHumano->ed20_d_dataident_dia = substr($oDadosRecHumano->ed20_d_dataident,8,2);
               $oDadosRecHumano->ed20_d_dataident_mes = substr($oDadosRecHumano->ed20_d_dataident,5,2);
               $oDadosRecHumano->ed20_d_dataident_ano = substr($oDadosRecHumano->ed20_d_dataident,0,4);
               
               if (!checkdate($oDadosRecHumano->ed20_d_dataident_mes,$oDadosRecHumano->ed20_d_dataident_dia,$oDadosRecHumano->ed20_d_dataident_ano)) {
                   
                 $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Data de expedição da";
                 $sMsgErro .= " identidade inválida.\n";
                 fwrite($ponteiro2,$sMsgErro);
                 $lErroDocente = true;
                 
               } else {
                   
                 if ($oDadosRecHumano->ed20_d_dataident < 1900) {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Ano da Data de expedição";
                   $sMsgErro .= " da identidade não deve ser menor que 1900.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 } else {
                     
                   if (str_replace("-","",$oDadosRecHumano->ed20_d_dataident) >= str_replace("-","",$hoje)) {
                       
                     $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Data de expedição da";
                     $sMsgErro .= " identidade deve ser menor que a data corrente.\n";
                     fwrite($ponteiro2,$sMsgErro);
                     $lErroDocente = true;
                     
                   }
                   
                   if (str_replace("-","",$oDadosRecHumano->ed20_d_dataident) <= str_replace("-","",$oDadosDocente->rh01_nasc)) {
                       
                     $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Data de expedição da";
                     $sMsgErro .= " identidade deve ser maior que a data de nascimento do docente.\n";
                     fwrite($ponteiro2,$sMsgErro);
                     $lErroDocente = true;
                     
                   }
                 }
               }
             }
             
             if ($oDadosRecHumano->ed20_i_nacionalidade == 3 && ($oDadosRecHumano->ed20_i_certidaotipo != "" || $oDadosRecHumano->ed20_c_certidaonum != "" 
                 || $oDadosRecHumano->ed20_c_certidaofolha != "" || $oDadosRecHumano->ed20_c_certidaolivro != "" || $oDadosRecHumano->ed20_c_certidaocart != "" 
                 || $oDadosRecHumano->ed20_c_certidaodata != "" || $oDadosRecHumano->ed20_i_censoufcert != "")) {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Nacionalidade Estrangeira.";
               $sMsgErro .= " Campos referente a Certidão NÃO devem ser informados.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_certidaotipo == "" && ($oDadosRecHumano->ed20_c_certidaonum != "" || $oDadosRecHumano->ed20_c_certidaofolha != "" 
                 || $oDadosRecHumano->ed20_c_certidaolivro != "" || $oDadosRecHumano->ed20_c_certidaodata != "" || $oDadosRecHumano->ed20_i_censoufcert != "" 
                 || $oDadosRecHumano->ed20_c_certidaocart != "")) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Tipo de Certidão deve";
               $sMsgErro .= " ser informado quando um dos campos estiverem informados: (Número do Termo - Folha";
               $sMsgErro .= " - Livro - Data da Emissão - UF Cartório - Cartório).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_c_certidaonum == "" && ($oDadosRecHumano->ed20_i_certidaotipo != "" 
                 || $oDadosRecHumano->ed20_i_censoufcert != "" || $oDadosRecHumano->ed20_c_certidaocart != "")) {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Número do Termo deve";
               $sMsgErro .= " ser informado quando um dos campos estiverem informados:";
               $sMsgErro .= " (Tipo de Certidão - UF Cartório - Cartório).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_c_certidaocart == "" && ($oDadosRecHumano->ed20_i_certidaotipo != "" 
                 || $oDadosRecHumano->ed20_i_censoufcert != "" || $oDadosRecHumano->ed20_c_certidaonum != "")) {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Cartório deve ser";
               $sMsgErro .= " informado quando um dos campos estiverem informados: (Tipo de Certidão";
               $sMsgErro .= " - UF Cartório - Número do Termo).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_censoufcert == "" && ($oDadosRecHumano->ed20_i_certidaotipo != "" 
                 || $oDadosRecHumano->ed20_c_certidaocart != "" || $oDadosRecHumano->ed20_c_certidaonum != "")) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo UF Cartório deve";
               $sMsgErro .= " ser informado quando um dos campos estiverem informados: (Tipo de Certidão - Cartório";
               $sMsgErro .= " - Número do Termo).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
              
             if ($oDadosRecHumano->ed20_c_certidaofolha != "" && $oDadosRecHumano->ed20_i_certidaotipo == "" && $oDadosRecHumano->ed20_c_certidaonum == "" 
                 && $oDadosRecHumano->ed20_i_censoufcert == "" && $oDadosRecHumano->ed20_c_certidaocart == "") {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Folha só pode ser";
               $sMsgErro .= " informado quando um dos campos estiverem informados: (Tipo de Certidão -";
               $sMsgErro .= " Número do Termo - UF Cartório - Cartório).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_c_certidaolivro != "" && $oDadosRecHumano->ed20_i_certidaotipo == "" && $oDadosRecHumano->ed20_c_certidaonum == "" 
                 && $oDadosRecHumano->ed20_i_censoufcert == "" && $oDadosRecHumano->ed20_c_certidaocart == "") {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Livro só pode ser";
               $sMsgErro .= " informado quando um dos campos estiverem informados: (Tipo de Certidão -";
               $sMsgErro .= " Número do Termo - UF Cartório - Cartório).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_c_certidaodata != "" && $oDadosRecHumano->ed20_i_certidaotipo == "" && $oDadosRecHumano->ed20_c_certidaonum == "" 
                 && $oDadosRecHumano->ed20_i_censoufcert == "" && $oDadosRecHumano->ed20_c_certidaocart == "") {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data de Emissão só";
               $sMsgErro .= " pode ser informado quando um dos campos estiverem informados: (Tipo de Certidão";
               $sMsgErro .= " - Número do Termo - UF Cartório - Cartório).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_c_certidaodata != "") {
                 
               $oDadosRecHumano->ed20_c_certidaodata_dia = substr($oDadosRecHumano->ed20_c_certidaodata,8,2);
               $oDadosRecHumano->ed20_c_certidaodata_mes = substr($oDadosRecHumano->ed20_c_certidaodata,5,2);
               $oDadosRecHumano->ed20_c_certidaodata_ano = substr($oDadosRecHumano->ed20_c_certidaodata,0,4);
               
               if (!checkdate($oDadosRecHumano->ed20_c_certidaodata_mes,$oDadosRecHumano->ed20_c_certidaodata_dia,$oDadosRecHumano->ed20_c_certidaodata_ano)) {
                   
                 $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data de emissão";
                 $sMsgErro .= " da certidão inválido.\n";
                 fwrite($ponteiro2,$sMsgErro);
                 $lErroDocente = true;
                 
               } else {
                   
                 if (str_replace("-","",$oDadosRecHumano->ed20_c_certidaodata) >= str_replace("-","",$hoje)) {
                     
                   $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data de emissão";
                   $sMsgErro .= " da certidão deve ser menor que a data corrente.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroDocente = true;
                   
                 }
                 
                 if ($oDadosRecHumano->ed20_i_certidaotipo == 1) {
                     
                   if (str_replace("-","",$oDadosRecHumano->ed20_c_certidaodata) < str_replace("-","",$oDadosDocente->rh01_nasc)) {
                       
                     $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data de Emissão";
                     $sMsgErro .= " da certidão deve ser maior ou igual a data de nascimento do docente.\n";
                     fwrite($ponteiro2,$sMsgErro);
                     $lErroDocente = true;
                     
                   }
                   
                 } else if ($oDadosRecHumano->ed20_i_certidaotipo == 2) {
                     
                   if (str_replace("-","",$oDadosRecHumano->ed20_c_certidaodata) <= str_replace("-","",$oDadosDocente->rh01_nasc)) {
                       
                     $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Data de Emissão";
                     $sMsgErro .= " da certidão deve ser maior que a data de nascimento do docente.\n";
                     fwrite($ponteiro2,$sMsgErro);
                     $lErroDocente = true;
                     
                   }
                 }
               }
             }
             
             if ($oDadosRecHumano->ed20_i_nacionalidade != 3 && $oDadosRecHumano->ed20_c_passaporte != "") {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo N° Passaporte só pode";
               $sMsgErro .= " ser informado quando nacionalidade do aluno for Estrangeira.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if (($oDadosDocente->z01_cgccpf != "" && strlen($oDadosDocente->z01_cgccpf) != 11) 
                  || $oDadosDocente->z01_cgccpf == "00000000000" || $oDadosDocente->z01_cgccpf == "00000000191") {
                      
               $sMsgErro = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo CPF inválido.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->z01_cep == "" && ($oDadosDocente->z01_ender != "" || $oDadosDocente->z01_numero != "" || $oDadosDocente->z01_compl != "" || $oDadosDocente->z01_bairro != "" 
                 || $oDadosRecHumano->ed20_i_censoufender != "" || $oDadosRecHumano->ed20_i_censomunicender != "")) {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo CEP deve ser informado";
               $sMsgErro .= " quando um dos campos estiverem informados: (Endereço - Número - Complemento";
               $sMsgErro .= " - Bairro - UF - Município).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
             }
             
             if ($oDadosDocente->z01_ender == "" && ($oDadosDocente->z01_cep != "" || $oDadosRecHumano->ed20_i_censoufender != "" || $oDadosRecHumano->ed20_i_censomunicender != "")) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Endereço deve ser";
               $sMsgErro .= " informado quando um dos campos estiverem informados: (CEP - UF - Município).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_censoufender == "" && ($oDadosDocente->z01_cep != "" || $oDadosRecHumano->ed20_i_censomunicender != "")) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo UF deve ser informado";
               $sMsgErro .= " quando um dos campos estiverem informados: (CEP - Município).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_censomunicender == "" && ($oDadosDocente->z01_cep != "" || $oDadosRecHumano->ed20_i_censoufender != "")) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Município deve ser";
               $sMsgErro .= " informado quando um dos campos estiverem informados: (CEP - UF).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->z01_numero != "" && $oDadosDocente->z01_cep == "" && $oDadosDocente->z01_ender == "" 
                 && $oDadosRecHumano->ed20_i_censoufender == "" && $oDadosRecHumano->ed20_i_censomunicender == "") {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Número só pode";
               $sMsgErro .= " ser informado quando um dos campos estiverem informados:";
               $sMsgErro .= " (CEP - Endereço - UF - Município).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->z01_compl != "" && $oDadosDocente->z01_cep == "" && $oDadosDocente->z01_ender == "" 
                 && $oDadosRecHumano->ed20_i_censoufender == "" && $oDadosRecHumano->ed20_i_censomunicender == "") {
                     
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Complemento só";
               $sMsgErro .= " pode ser informado quando um dos campos estiverem informados:";
               $sMsgErro .= " (CEP - Endereço - UF - Município).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->z01_bairro != "" && $oDadosDocente->z01_cep == "" && $oDadosDocente->z01_ender == "" && $oDadosRecHumano->ed20_i_censoufender == "" 
                 && $oDadosRecHumano->ed20_i_censomunicender == "") {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Bairro só pode";
               $sMsgErro .= " ser informado quando um dos campos estiverem informados:";
               $sMsgErro .= " (CEP - Endereço - UF - Município).\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosDocente->z01_cep != "" && strlen($oDadosDocente->z01_cep) != 8) {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo CEP deve";
               $sMsgErro .= " conter 8 dígitos.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_c_outroscursos == "" || $oDadosRecHumano->ed20_c_outroscursos == "000000") {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Outros Cursos";
               $sMsgErro .= " não informado.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_escolaridade == 6 && trim($oDadosRecHumano->ed20_c_posgraduacao) == "") {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Pós-Graduação deve";
               $sMsgErro .= " ser informado quando Escolaridade for SUPERIOR COMPLETO.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_escolaridade != 6 && trim($oDadosRecHumano->ed20_c_posgraduacao) != "0000" && trim($oDadosRecHumano->ed20_c_posgraduacao) != "") {
                 
               $sMsgErro  = "DOCENTE: Docente ".$oDadosRecHumano->ed20_i_codigo." ".trim($oDadosDocente->z01_nome)." -> Campo Pós-Graduação somente";
               $sMsgErro .= " deve ser informado quando Escolaridade for SUPERIOR COMPLETO.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroDocente = true;
               
             }
             
             if ($oDadosRecHumano->ed20_i_escolaridade != 6) {
               $oDadosRecHumano->ed20_c_posgraduacao = "";    
             }
             
             $oDadosDocente->z01_numcgm             = $oDadosDocente->z01_numcgm;
             $oDadosRecHumano->ed20_i_codigoinep      = $oDadosRecHumano->ed20_i_codigoinep;
             $oDadosRecHumano->ed20_i_codigo          = $oDadosRecHumano->ed20_i_codigo;
             $oDadosDocente->z01_nome               = TiraCaracteres($oDadosDocente->z01_nome,1);
             $oDadosDocente->z01_email              = TiraCaracteres($oDadosDocente->z01_email,4);
             $oDadosRecHumano->ed20_c_nis             = TiraCaracteres($oDadosRecHumano->ed20_c_nis,0);
             $oDadosDocente->rh01_nasc              = db_formatar($oDadosDocente->rh01_nasc,'d'); //str_replace("/","",db_formatar($oDadosDocente->rh01_nasc,'d'));
             $oDadosDocente->rh01_sexo              = ($oDadosDocente->rh01_sexo=="M"?"1":"2");
             $oDadosRecHumano->ed20_i_raca            = $oDadosRecHumano->ed20_i_raca;
             $oDadosDocente->z01_mae                = TiraCaracteres($oDadosDocente->z01_mae,1);
             $oDadosRecHumano->ed20_i_nacionalidade   = $oDadosRecHumano->ed20_i_nacionalidade;
             $oDadosRechumano->ed228_i_paisonu        = $oDadosRecHumano->ed228_i_paisonu;
             $oDadosRecHumano->ed20_i_censoufnat      = $oDadosRecHumano->ed20_i_censoufnat;
             $oDadosRecHumano->ed20_i_censomunicnat   = $oDadosRecHumano->ed20_i_censomunicnat;     
             $oDadosDocente->z01_cgccpf             = $oDadosDocente->z01_cgccpf;
             $oDadosDocente->z01_cep                = $oDadosDocente->z01_cep;
             $oDadosDocente->z01_ender              = TiraCaracteres($oDadosDocente->z01_ender,3);
             $oDadosDocente->z01_numero             = TiraCaracteres($oDadosDocente->z01_numero,3);
             $oDadosDocente->z01_compl              = TiraCaracteres($oDadosDocente->z01_compl,3);
             $oDadosDocente->z01_bairro             = TiraCaracteres($oDadosDocente->z01_bairro,3);
             $oDadosRecHumano->ed20_i_censoufender    = $oDadosRecHumano->ed20_i_censoufender;
             $oDadosRecHumano->ed20_i_censomunicender = $oDadosRecHumano->ed20_i_censomunicender;     
             $oDadosRecHumano->ed20_i_escolaridade    = $oDadosRecHumano->ed20_i_escolaridade;
             $oDadosRecHumano->ed20_c_posgraduacao    = ($oDadosRecHumano->ed20_i_escolaridade!=6?"":substr($oDadosRecHumano->ed20_c_posgraduacao,0,1)).
                                       "|".($oDadosRecHumano->ed20_i_escolaridade!=6?"":substr($oDadosRecHumano->ed20_c_posgraduacao,1,1)).
                                       "|".($oDadosRecHumano->ed20_i_escolaridade!=6?"":substr($oDadosRecHumano->ed20_c_posgraduacao,2,1)).
                                       "|".($oDadosRecHumano->ed20_i_escolaridade!=6?"":substr($oDadosRecHumano->ed20_c_posgraduacao,3,1));
             $oDadosRecHumano->ed20_c_outroscursos    = trim($oDadosRecHumano->ed20_c_outroscursos)==""?"0":$oDadosRecHumano->ed20_c_outroscursos;            
             $oDadosRecHumano->ed20_c_outroscursos    = substr($oDadosRecHumano->ed20_c_outroscursos,0,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,1,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,2,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,3,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,4,1). 
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,5,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,6,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,7,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,8,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,9,1).
                                       "|".substr($oDadosRecHumano->ed20_c_outroscursos,10,1);
             
             if ($lErroDocente == false) {
                 
               $num_linha++;
               $write_linha  = "30|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosRecHumano->ed20_i_codigoinep);
               $write_linha .= "|".trim($oDadosDocente->z01_numcgm)."|".trim($oDadosDocente->z01_nome)."|".trim($oDadosDocente->z01_email)."|".trim($oDadosRecHumano->ed20_c_nis);
               $write_linha .= "|".trim($oDadosDocente->rh01_nasc)."|".trim($oDadosDocente->rh01_sexo)."|".trim($oDadosRecHumano->ed20_i_raca);
               $write_linha .= "|".trim($oDadosDocente->z01_mae)."|".trim($oDadosRecHumano->ed20_i_nacionalidade)."|".trim($oDadosRecHumano->ed228_i_paisonu);
               $write_linha .= "|".trim($oDadosRecHumano->ed20_i_censoufnat)."|".trim($oDadosRecHumano->ed20_i_censomunicnat)."|\n";               
               fwrite($ponteiro,$write_linha);
               
               $num_linha++;
               $write_linha  = "40|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosRecHumano->ed20_i_codigoinep);
               $write_linha .= "|".trim($oDadosDocente->z01_numcgm)."|".trim($oDadosDocente->z01_cgccpf)."|".trim($oDadosDocente->z01_cep)."|".trim($oDadosDocente->z01_ender);
               $write_linha .= "|".trim($oDadosDocente->z01_numero)."|".trim($oDadosDocente->z01_compl)."|".trim($oDadosDocente->z01_bairro);
               $write_linha .= "|".trim($oDadosRecHumano->ed20_i_censoufender)."|".trim($oDadosRecHumano->ed20_i_censomunicender)."|\n";
               fwrite($ponteiro,$write_linha);
               
               $num_linha++;
               $write_linha  = "50|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosRecHumano->ed20_i_codigoinep);
               $write_linha .= "|".trim($oDadosDocente->z01_numcgm)."|".trim($oDadosRecHumano->ed20_i_escolaridade)."|".trim($formacaorh);
               $write_linha .= "|".trim($oDadosRecHumano->ed20_c_posgraduacao)."|".trim($oDadosRecHumano->ed20_c_outroscursos)."|\n";
               fwrite($ponteiro,$write_linha);
               
               if ($clregenciahorario->numrows > 0) {
                   
                 $linhas_horario = $clregenciahorario->numrows;
                 for ($c = 0; $c < $linhas_horario; $c++) {
                     
                   $oDadosRegenciaHorario = db_utils::fieldsmemory($sResultHorario, $c);
                   $ed11_i_codcenso = $oDadosRegenciaHorario->ed57_i_censoetapa;
                   if ($ed11_i_codcenso != 1 && $ed11_i_codcenso != 2 && $ed11_i_codcenso != 3) {
                     $funcao = '1'; 
                     $sCampos                = "DISTINCT ed232_i_codcenso";
                     $sWhereRegHorario       = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else ";
                     $sWhereRegHorario      .= " cgmcgm.z01_numcgm end = ".trim($oDadosDocente->z01_numcgm)." AND ed57_i_codigo =";
                     $sWhereRegHorario      .= " $oDadosRegenciaHorario->ed57_i_codigo AND ed57_i_escola = $oDadosEscola->ed18_i_codigo";
                     $sSqlRegenciaHorario    = $clregenciahorario->sql_query("",$sCampos,"",$sWhereRegHorario);                     
                     $sResultRegenciaHorario = $clregenciahorario->sql_record($sSqlRegenciaHorario);
                     $disciplinas            = "";
                     
                     for ($z = 0; $z < $clregenciahorario->numrows; $z++) {
                         
                     	 $ed232_i_codcenso = db_utils::fieldsmemory($sResultRegenciaHorario, $z)->ed232_i_codcenso;
                       $disciplinas     .= $ed232_i_codcenso."|";
                       
                     }
                     
                     for ($z = $clregenciahorario->numrows; $z < 13; $z++) {
                       $disciplinas .= "|";
                     }
                     
                   } else {
                   	 $funcao ='2';
                     $disciplinas        = "|||||||||||||";
                   }
                   $oDadosRecHumano->informecenso = ($oDadosRecHumano->informecenso=="0"?"":$oDadosRecHumano->informecenso);
                   $oDadosRegenciaHorario->ed57_i_codigoinep = $oDadosRegenciaHorario->ed57_i_codigoinep;
                   $oDadosRegenciaHorario->ed57_i_codigo     = $oDadosRegenciaHorario->ed57_i_codigo;
                   $funcaorh          = "";
                   $num_linha++;
                   $write_linha  = "51|".$oDadosEscola->ed18_c_codigoinep."|".$oDadosRecHumano->ed20_i_codigoinep;
                   $write_linha .= "|".$oDadosDocente->z01_numcgm."|".$oDadosRegenciaHorario->ed57_i_codigoinep."|".$oDadosRegenciaHorario->ed57_i_codigo;
                   $write_linha .= "|".$funcao."|".$oDadosRecHumano->informecenso."|".$disciplinas."\n";
                   fwrite($ponteiro,$write_linha);
                 }
               } 
               
               if ($clturmaachorario->numrows > 0) {
                   
                 $linhas_horarioac = $clturmaachorario->numrows;
                 for ($c = 0; $c < $linhas_horarioac; $c++) {                   
                   
                   $oDadosTurmaAcHorario = db_utils::fieldsmemory($sResultHorarioAc, $c);
                //   $oDadosRecHumano      = db_utils::fieldsmemory($sResultRechumano, $c);
                   if ($oDadosTurmaAc2->ed268_i_tipoatend == 4 ) {
                     $funcao='3';
                    
                   }else{
                     $funcao ='1';
                   }
                  
                   $disciplinas        = "||||||||||||";
                   $oDadosTurmaAcHorario->ed268_i_codigoinep = $oDadosTurmaAcHorario->ed268_i_codigoinep;
                   $oDadosTurmaAcHorario->ed268_i_codigo     = $oDadosTurmaAcHorario->ed268_i_codigo;                   
                   $num_linha++;                   
                   $write_linha  = "51|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosRecHumano->ed20_i_codigoinep)."|".trim($oDadosDocente->z01_numcgm);
                   $write_linha .= "|".trim($oDadosTurmaAcHorario->ed268_i_codigoinep)."|".trim($oDadosTurmaAcHorario->ed268_i_codigo);
                   $write_linha .= "|".trim($funcao)."|".$oDadosRecHumano->informecenso."|".trim($disciplinas)."|\n";
                   fwrite($ponteiro,$write_linha);
                   
                 }
               } 
             }
           }
         }
//         ///////////////////////////////////ALUNO
         $sCamposAluno     = " trim(aluno.ed47_c_codigoinep) as ed47_c_codigoinep,  ";
         $sCamposAluno    .= " aluno.ed47_i_codigo,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_nome) as ed47_v_nome,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_nis) as ed47_c_nis,  ";
         $sCamposAluno    .= " aluno.ed47_d_nasc,  ";
         $sCamposAluno    .= " aluno.ed47_v_sexo,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_raca) as ed47_c_raca,  ";
         $sCamposAluno    .= " aluno.ed47_i_filiacao,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_mae) as ed47_v_mae,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_pai) as ed47_v_pai,  ";
         $sCamposAluno    .= " aluno.ed47_i_nacion, "; 
         $sCamposAluno    .= " pais.ed228_i_paisonu,  ";
         $sCamposAluno    .= " aluno.ed47_i_censoufnat,  ";
         $sCamposAluno    .= " aluno.ed47_i_censomunicnat,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_ident) as ed47_v_ident,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_identcompl) as ed47_v_identcompl,  ";
         $sCamposAluno    .= " aluno.ed47_i_censoorgemissrg,  ";
         $sCamposAluno    .= " aluno.ed47_i_censoufident, "; 
         $sCamposAluno    .= " aluno.ed47_d_identdtexp,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_certidaotipo) as ed47_c_certidaotipo, "; 
         $sCamposAluno    .= " trim(aluno.ed47_c_certidaonum) as ed47_c_certidaonum,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_certidaofolha) as ed47_c_certidaofolha,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_certidaolivro) as ed47_c_certidaolivro, "; 
         $sCamposAluno    .= " trim(aluno.ed47_c_certidaodata) as ed47_c_certidaodata,  ";
         $sCamposAluno    .= " trim(aluno.ed47_i_censocartorio) as ed47_i_censocartorio,  ";
         $sCamposAluno    .= " aluno.ed47_i_censoufcert,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_cpf) as ed47_v_cpf,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_passaporte) as ed47_c_passaporte,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_cep) as ed47_v_cep,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_ender) as ed47_v_ender,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_numero) as ed47_c_numero,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_compl) as ed47_v_compl,  ";
         $sCamposAluno    .= " trim(aluno.ed47_v_bairro) as ed47_v_bairro,  ";
         $sCamposAluno    .= " aluno.ed47_i_censoufend,  ";
         $sCamposAluno    .= " aluno.ed47_i_censomunicend,  ";
         $sCamposAluno    .= " ed47_i_censomuniccert,";
         $sCamposAluno    .= " trim(aluno.ed47_c_atenddifer) as ed47_c_atenddifer,  ";
         $sCamposAluno    .= " aluno.ed47_i_transpublico,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_transporte) as ed47_c_transporte,  ";
         $sCamposAluno    .= " trim(aluno.ed47_c_zona) as ed47_c_zona,  ";
         $sCamposAluno    .= " matricula.ed60_i_turma,  ";
         $sCamposAluno    .= " turma.ed57_i_codigoinep,  ";
         $sCamposAluno    .= " serie.ed11_i_codcenso as codcensomatricula,  ";
         $sCamposAluno    .= " turma.ed57_i_censoetapa as ed11_i_codcenso,  ";
         $sCamposAluno    .= " ensino.ed10_i_tipoensino,  ";       
         $sCamposAluno    .= " matricula.ed60_i_codigo  ";    
         $sWhereMatricula  = " turma.ed57_i_escola = $oDadosEscola->ed18_i_codigo AND calendario.ed52_i_ano = $ed52_i_ano ";
         $sWhereMatricula .= " AND ed60_d_datamatricula <= '$data_censo' ";
         $sWhereMatricula .= " AND ((ed60_c_situacao = 'MATRICULADO' and ed60_d_datasaida is null) ";
         $sWhereMatricula .= " OR (ed60_c_situacao != 'MATRICULADO' and ed60_d_datasaida > '$data_censo'))";
         $sSqlMatricula    = $clmatricula->sql_query("",$sCamposAluno,"ed47_v_nome",$sWhereMatricula);         
         $sResultMatricula = $clmatricula->sql_record($sSqlMatricula);
         
         for ($x = 0; $x < $clmatricula->numrows; $x++) {
             
           $oDadosAluno  = db_utils::fieldsmemory($sResultMatricula,$x);
           $codigo_aluno = $oDadosAluno->ed47_i_codigo;
           db_atutermometro_edu($x, $clmatricula->numrows , 'termometro',1,'...Processando Alunos');
           
           if (trim($oDadosAluno->ed47_c_codigoinep) != "" && strlen(trim($oDadosAluno->ed47_c_codigoinep)) != 12) {
               
             $sMsgErro = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Código INEP inválido.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           $oDadosAluno->ed47_d_nasc_dia = substr($oDadosAluno->ed47_d_nasc,8,2);
           $oDadosAluno->ed47_d_nasc_mes = substr($oDadosAluno->ed47_d_nasc,5,2);
           $oDadosAluno->ed47_d_nasc_ano = substr($oDadosAluno->ed47_d_nasc,0,4);
           
           if ($oDadosAluno->ed47_d_nasc == "") {
               
             $sMsgErro = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Data de nascimento não informada.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           } else {
               
             if (!checkdate($oDadosAluno->ed47_d_nasc_mes,$oDadosAluno->ed47_d_nasc_dia,$oDadosAluno->ed47_d_nasc_ano)) {
                 
               $sMsgErro = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Data de nascimento inválida.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             } else {
                 
               if ($oDadosAluno->ed47_d_nasc_ano < 1914) {
                   
                 $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Ano de nascimento não deve";
                 $sMsgErro .= " ser menor que 1914.\n";
                 fwrite($ponteiro2,$sMsgErro);
                 $lErroAluno = true;
                 
               } else {
                   
                 if (str_replace("-","",$oDadosAluno->ed47_d_nasc) >= str_replace("-","",$hoje)) {
                     
                   $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Data de nascimento deve";
                   $sMsgErro .= " ser menor que a data corrente.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroAluno = true;
                   
                 }
               }
             }
           }
           
           if ($oDadosAluno->ed47_i_filiacao == 1 && $oDadosAluno->ed47_v_pai == "" && $oDadosAluno->ed47_v_mae == "") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Filiação = (Pai e/ou Mãe).";
             $sMsgErro .= " Pai e/ou Mãe deve ser informados.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_filiacao == 0 && ($oDadosAluno->ed47_v_pai != "" || $oDadosAluno->ed47_v_mae != "")) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Filiação = (Não Declarado/Ignorado).";
             $sMsgErro .= " Pai e Mãe não devem ser informados.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_v_pai != "" && $oDadosAluno->ed47_v_mae != "" && trim($oDadosAluno->ed47_v_pai) == trim($oDadosAluno->ed47_v_mae)) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Pai e Mãe não devem";
             $sMsgErro .= " ter nomes diferentes.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if (($oDadosAluno->ed47_i_nacion == 1 || $oDadosAluno->ed47_i_nacion == 2) && $oDadosAluno->ed228_i_paisonu != 76) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Nacionalidade Brasileira ou";
             $sMsgErro .= " Brasileira no Exterior. Campo pais deve ser BRASIL.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_nacion == 3 && $oDadosAluno->ed228_i_paisonu == 76) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Nacionalidade Estrangeira.";
             $sMsgErro .= " Campo pais deve ser diferente de BRASIL.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_nacion==1 && ($oDadosAluno->ed47_i_censoufnat=="" || $oDadosAluno->ed47_i_censomunicnat=="")){
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Nacionalidade Brasileira.";
             $sMsgErro .= " UF de Nascimento e Naturalidade devem ser informados.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_nacion == 3 && ($oDadosAluno->ed47_v_ident != "" || $oDadosAluno->ed47_v_identcompl != "" 
               || $oDadosAluno->ed47_i_censoorgemissrg != "" || $oDadosAluno->ed47_i_censoufident != "" || $oDadosAluno->ed47_d_identdtexp != "")) {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Nacionalidade Estrangeira.";
             $sMsgErro .= " Campos referente a Identidade NÃO devem ser informados.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_v_ident == "" && ($oDadosAluno->ed47_v_identcompl != "" || $oDadosAluno->ed47_i_censoorgemissrg != "" 
               || $oDadosAluno->ed47_i_censoufident != "" || $oDadosAluno->ed47_d_identdtexp != "")) {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo N° Identidade deve ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (Complemento - UF Identidade";
             $sMsgErro .= " - Órgao Emissor - Data Expedição Identidade).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_censoorgemissrg == "" && ($oDadosAluno->ed47_v_ident != "" || $oDadosAluno->ed47_i_censoufident != "")){
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Órgão Emissor deve ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (N° Identidade - UF Identidade).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_censoufident == "" && ($oDadosAluno->ed47_v_ident != "" || $oDadosAluno->ed47_i_censoorgemissrg != "")) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo UF Identidade deve ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (N° Identidade - Órgão Emissor).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_v_identcompl != "" && $oDadosAluno->ed47_v_ident == "" 
               && $oDadosAluno->ed47_i_censoorgemissrg == " " && $oDadosAluno->ed47_i_censoufident == "") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Complemento só pode ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (N° Identidade";
             $sMsgErro .= " - Órgão Emissor - UF Identidade).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_d_identdtexp != "" && $oDadosAluno->ed47_v_ident == "" 
               && $oDadosAluno->ed47_i_censoorgemissrg == "" && $oDadosAluno->ed47_i_censoufident == "") {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Data Expedição Identidade";
             $sMsgErro .= " só pode ser informado quando um dos campos estiverem informados: (N° Identidade";
             $sMsgErro .= " - Órgão Emissor - UF Identidade).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_d_identdtexp != "") {
               
             $oDadosAluno->ed47_d_identdtexp_dia = substr($oDadosAluno->ed47_d_identdtexp,8,2);
             $oDadosAluno->ed47_d_identdtexp_mes = substr($oDadosAluno->ed47_d_identdtexp,5,2);
             $oDadosAluno->ed47_d_identdtexp_ano = substr($oDadosAluno->ed47_d_identdtexp,0,4);
             
             if (!checkdate($oDadosAluno->ed47_d_identdtexp_mes,$oDadosAluno->ed47_d_identdtexp_dia,$oDadosAluno->ed47_d_identdtexp_ano)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Data de expedição da";
               $sMsgErro .= " identidade inválida.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             } else {
                 
               if ($oDadosAluno->ed47_d_identdtexp_ano < 1904) {
                   
                 $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Ano da Data de expedição da";
                 $sMsgErro .= " identidade não deve ser menor que 1904.\n";
                 fwrite($ponteiro2,$sMsgErro);
                 $lErroAluno = true;
                 
               } else {
                   
                 if (str_replace("-","",$oDadosAluno->ed47_d_identdtexp) >= str_replace("-","",$hoje)) {
                     
                   $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Data de expedição da";
                   $sMsgErro .= " identidade deve ser menor que a data corrente.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroAluno = true;
                   
                 }
                 
                 if (str_replace("-","",$oDadosAluno->ed47_d_identdtexp) <= str_replace("-","",$oDadosAluno->ed47_d_nasc)) {
                     
                   $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Data de expedição da";
                   $sMsgErro .= " identidade deve ser maior que a data de nascimento do aluno.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroAluno = true;
                   
                 }
               }
             }
           }
           
           if ($oDadosAluno->ed47_i_nacion == 3 && ($oDadosAluno->ed47_c_certidaotipo != "" || $oDadosAluno->ed47_c_certidaonum != "" 
               || $oDadosAluno->ed47_c_certidaofolha != "" || $oDadosAluno->ed47_c_certidaolivro != "" || $oDadosAluno->ed47_i_censocartorio != "" 
               || $oDadosAluno->ed47_c_certidaodata != "" || $oDadosAluno->ed47_i_censoufcert != "")) {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Nacionalidade Estrangeira. Campos";
             $sMsgErro .= " referente a Certidão NÃO devem ser informados.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_c_certidaotipo == "" && ($oDadosAluno->ed47_c_certidaonum != "" || $oDadosAluno->ed47_c_certidaofolha != "" 
               || $oDadosAluno->ed47_c_certidaolivro != "" || $oDadosAluno->ed47_c_certidaodata != "" 
               || $oDadosAluno->ed47_i_censoufcert != "" || $oDadosAluno->ed47_i_censocartorio != "" || $oDadosAluno->ed47_i_censomuniccert != "")) {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".$sMsgErro =trim($oDadosAluno->ed47_v_nome)." - Campo Tipo de Certidão";
             $sMsgErro .= " deve ser informado quando um dos campos estiverem informados: (Número do Termo";
             $sMsgErro .= " - Folha - Livro - Data da Emissão - UF Cartório - Cartório - Município Cartório).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_c_certidaonum == "" && ($oDadosAluno->ed47_c_certidaotipo != "" 
               || $oDadosAluno->ed47_i_censoufcert != "" || $oDadosAluno->ed47_i_censocartorio != "" || $oDadosAluno->ed47_i_censomuniccert != "")) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Número do Termo deve ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (Tipo de Certidão";
             $sMsgErro .= " - UF Cartório - Cartório - Município Cartório).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_censocartorio == "" && ($oDadosAluno->ed47_c_certidaotipo != "" 
               || $oDadosAluno->ed47_i_censoufcert != "" || $oDadosAluno->ed47_c_certidaonum != "" || $oDadosAluno->ed47_i_censomuniccert != "")) {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Cartório deve ser informado";
             $sMsgErro .= " quando um dos campos estiverem informados: (Tipo de Certidão";
             $sMsgErro .= " - UF Cartório - Número do Termo - Município Cartório).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_censoufcert == "" && ($oDadosAluno->ed47_c_certidaotipo != "" 
               || $oDadosAluno->ed47_i_censocartorio != "" || $oDadosAluno->ed47_c_certidaonum != "" || $oDadosAluno->ed47_i_censomuniccert != "")) {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo UF Cartório deve ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (Tipo de Certidão";
             $sMsgErro .= " - Cartório - Número do Termo - Município Cartório).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_c_certidaofolha != "" && $oDadosAluno->ed47_c_certidaotipo == "" && $oDadosAluno->ed47_c_certidaonum == "" 
               && $oDadosAluno->ed47_i_censoufcert == "" && $oDadosAluno->ed47_i_censocartorio == "") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Folha só pode ser informado";
             $sMsgErro .= " quando um dos campos estiverem informados: (Tipo de Certidão - Número do Termo";
             $sMsgErro .= " - UF Cartório - Cartório - Município Cartório).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_c_certidaolivro != "" && $oDadosAluno->ed47_c_certidaotipo == "" && $oDadosAluno->ed47_c_certidaonum == "" 
               && $oDadosAluno->ed47_i_censoufcert == "" && $oDadosAluno->ed47_i_censocartorio == "") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Livro só pode ser informado";
             $sMsgErro .= " quando um dos campos estiverem informados: (Tipo de Certidão - Número do Termo";
             $sMsgErro .= " - UF Cartório - Cartório - Município Cartório).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_c_certidaodata != "" && $oDadosAluno->ed47_c_certidaotipo == "" && $oDadosAluno->ed47_c_certidaonum == "" 
               && $oDadosAluno->ed47_i_censoufcert == "" && $oDadosAluno->ed47_i_censocartorio == "") {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Data de Emissão só pode";
             $sMsgErro .= " ser informado quando um dos campos estiverem informados: (Tipo de Certidão";
             $sMsgErro .= " - Número do Termo - UF Cartório - Cartório - Município Cartório).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           if ($oDadosAluno->ed47_c_certidaotipo == "" 
               || $oDadosAluno->ed47_c_certidaonum == "" 
               || $oDadosAluno->ed47_i_censoufcert == "" 
               || $oDadosAluno->ed47_i_censomuniccert == "" 
               || $oDadosAluno->ed47_i_censocartorio == "") {
              $iModeloCertidao = "1";
            } else {
              $iModeloCertidao = "1";
            }
           if ($iModeloCertidao == '1' && ($oDadosAluno->ed47_c_certidaotipo =="" || $oDadosAluno->ed47_c_certidaonum == "" || $oDadosAluno->ed47_i_censoufcert == "" || $oDadosAluno->ed47_i_censomuniccert == "" || $oDadosAluno->ed47_i_censocartorio == "")) {
           	 $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo certidão civil só pode";
             $sMsgErro .= " ser informado quando um dos campos estiverem informados: (Tipo de Certidão";
             $sMsgErro .= " - Número do Termo - UF Cartório - Cartório - Município Cartório).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
           	
           }
           
           if ($oDadosAluno->ed47_c_certidaodata != "") {
               
             $oDadosAluno->ed47_c_certidaodata_dia = substr($oDadosAluno->ed47_c_certidaodata,8,2);
             $oDadosAluno->ed47_c_certidaodata_mes = substr($oDadosAluno->ed47_c_certidaodata,5,2);
             $oDadosAluno->ed47_c_certidaodata_ano = substr($oDadosAluno->ed47_c_certidaodata,0,4);
             
             if (!checkdate($oDadosAluno->ed47_c_certidaodata_mes,$oDadosAluno->ed47_c_certidaodata_dia,$oDadosAluno->ed47_c_certidaodata_ano)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Data de emissão da";
               $sMsgErro .= " certidão inválida.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             } else {
                 
               if (str_replace("-","",$oDadosAluno->ed47_c_certidaodata) >= str_replace("-","",$hoje)) {
                   
                 $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Data de emissão da";
                 $sMsgErro .= " certidão deve ser menor que a data corrente.\n";
                 fwrite($ponteiro2,$sMsgErro);
                 $lErroAluno = true;
                 
               }
               
               if ($oDadosAluno->ed47_c_certidaotipo == "N") {
                   
                 if (str_replace("-","",$oDadosAluno->ed47_c_certidaodata) < str_replace("-","",$oDadosAluno->ed47_d_nasc)) {
                     
                   $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Data de Emissão da";
                   $sMsgErro .= " certidão deve ser maior ou igual a data de nascimento do aluno.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroAluno = true;
                   
                 }
                 
               } else if ($oDadosAluno->ed47_c_certidaotipo == "C") {
                   
                 if (str_replace("-","",$oDadosAluno->ed47_c_certidaodata) <= str_replace("-","",$oDadosAluno->ed47_d_nasc)) {
                     
                   $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Data de Emissão";
                   $sMsgErro .= " da certidão deve ser maior que a data de nascimento do aluno.\n";
                   fwrite($ponteiro2,$sMsgErro);
                   $lErroAluno = true;
                   
                 }
               }
             }
           }
           
////           if ($oDadosAluno->ed47_i_censocartorio != "" && strlen($oDadosAluno->ed47_i_censocartorio) < 4) {
////               
////             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Cártorio, quando informado,";
////             $sMsgErro .= " deve possuir no mínimo 4 dígitos.\n";
////             fwrite($ponteiro2,$sMsgErro);
////             $lErroAluno = true;
////             
////           }
          
           if ($oDadosAluno->ed47_i_nacion != 3 && $oDadosAluno->ed47_c_passaporte != "") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo N° Passaporte só pode ser";
             $sMsgErro .= " informado quando nacionalidade do aluno for Estrangeira.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if (($oDadosAluno->ed47_v_cpf != "" && strlen($oDadosAluno->ed47_v_cpf) != 11) 
                || $oDadosAluno->ed47_v_cpf == "00000000000" || $oDadosAluno->ed47_v_cpf == "00000000191") {
                    
             $sMsgErro = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo CPF inválido.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_v_cep == "" && ($oDadosAluno->ed47_v_ender != "" || $oDadosAluno->ed47_c_numero != "" || $oDadosAluno->ed47_v_compl != "" 
               || $oDadosAluno->ed47_v_bairro != "" || $oDadosAluno->ed47_i_censoufend != "" || $oDadosAluno->ed47_i_censomunicend != "")){
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo CEP deve ser informado";
             $sMsgErro .= " quando um dos campos estiverem informados: (Endereço - Número - Complemento";
             $sMsgErro .= " - Bairro - UF - Município).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_v_ender == "" && ($oDadosAluno->ed47_v_cep != "" || $oDadosAluno->ed47_i_censoufend != "" || $oDadosAluno->ed47_i_censomunicend != "")) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Endereço deve ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (CEP - UF - Município).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_censoufend == "" && ($oDadosAluno->ed47_v_cep != "" || $oDadosAluno->ed47_i_censomunicend != "")) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo UF deve ser informado";
             $sMsgErro .= " quando um dos campos estiverem informados: (CEP - Município).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_censomunicend == "" && ($oDadosAluno->ed47_v_cep != "" || $oDadosAluno->ed47_i_censoufend != "")) {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Município deve ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (CEP - UF).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_c_numero != "" && $oDadosAluno->ed47_v_cep == "" && $oDadosAluno->ed47_v_ender == "" 
               && $oDadosAluno->ed47_i_censoufend == "" && $oDadosAluno->ed47_i_censomunicend == "") {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Número só pode ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (CEP - Endereço - UF - Município).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_v_compl != "" && $oDadosAluno->ed47_v_cep == "" && $oDadosAluno->ed47_v_ender == "" 
               && $oDadosAluno->ed47_i_censoufend == "" && $oDadosAluno->ed47_i_censomunicend == "") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Complemento só pode";
             $sMsgErro .= " ser informado quando um dos campos estiverem informados:";
             $sMsgErro .= " (CEP - Endereço - UF - Município).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_v_bairro != "" && $oDadosAluno->ed47_v_cep == "" && $oDadosAluno->ed47_v_ender == "" 
               && $oDadosAluno->ed47_i_censoufend == "" && $oDadosAluno->ed47_i_censomunicend == "") {
                   
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Bairro só pode ser";
             $sMsgErro .= " informado quando um dos campos estiverem informados: (CEP - Endereço - UF - Município).\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_v_cep != "" && strlen($oDadosAluno->ed47_v_cep) != 8) {
               
             $sMsgErro = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo CEP deve conter 8 dígitos.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_transpublico == 0 && $oDadosAluno->ed47_c_transporte != "") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Poder Publico Responsável";
             $sMsgErro .= " só pode ser informado quando campo Transporte Escolar Público for igual a Utiliza.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if ($oDadosAluno->ed47_i_transpublico == 1 && $oDadosAluno->ed47_c_transporte == "") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Campo Poder Publico Responsável";
             $sMsgErro .= " deve ser informado quando campo Transporte Escolar Público for igual a Utiliza.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           $sWhereNecessidade  = " ed214_i_aluno = $oDadosAluno->ed47_i_codigo";
           $sSqlNecessidade    = $clalunonecessidade->sql_query("","ed214_i_necessidade","",$sWhereNecessidade);
           $sResultNecessidade = $clalunonecessidade->sql_record($sSqlNecessidade);
           
           if ($clalunonecessidade->numrows > 0 || $oDadosAluno->ed10_i_tipoensino == 2) {
               
             $necessidades     = "1|";
             $tiponecessidades = "";
             $aCodNec          = array();
             
             for ($d = 101; $d <= 113; $d++) {
                  
               $naotem = false;
               for ($q = 0; $q < $clalunonecessidade->numrows; $q++) {
                   
                 $ed214_i_necessidade = db_utils::fieldsmemory($sResultNecessidade, $q)->ed214_i_necessidade;
                 if ($d == $ed214_i_necessidade) {
                     
                   $tiponecessidades .= "1|";
                   $naotem            = true;
                   $aCodNec[$d]       = $d;
                   break;
                   
                 }
               }
               
               if ($naotem == false) {
                   
                 $tiponecessidades .= "0|";
                 $aCodNec[$d]       = "0|";
                 
               }
             }
             
             if (in_array(101,$aCodNec) && in_array(102,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências CEGUEIRA e BAIXA";
               $sMsgErro .= " VISAO não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(101,$aCodNec) && in_array(103,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências CEGUEIRA e SURDEZ";
               $sMsgErro .= " não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(101,$aCodNec) && in_array(104,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências CEGUEIRA e";
               $sMsgErro .= " DEFICIENCIA AUDITIVA não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(101,$aCodNec) && in_array(105,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências CEGUEIRA e";
               $sMsgErro .= " SURDOCEGUEIRA não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(102,$aCodNec) && in_array(103,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências BAIXA VISAO";
               $sMsgErro .= " e SURDEZ não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(102,$aCodNec) && in_array(104,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências BAIXA VISAO";
               $sMsgErro .= " e DEFICIENCIA AUDITIVA não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(102,$aCodNec) && in_array(105,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências BAIXA VISAO";
               $sMsgErro .= " e SURDOCEGUEIRA não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(103,$aCodNec) && in_array(104,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências SURDEZ e DEFICIENCIA";
               $sMsgErro .= " AUDITIVA não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(103,$aCodNec) && in_array(105,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências SURDEZ";
               $sMsgErro .= " e SURDOCEGUEIRA não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(104,$aCodNec) && in_array(105,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências DEFICIENCIA";
               $sMsgErro .= " AUDITIVA e SURDOCEGUEIRA não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             }
             
             if (in_array(107,$aCodNec) && in_array(113,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências DEFICIENCIA MENTAL";
               $sMsgErro .= " e ALTAS HABILIDADES/SUPERDOTACAO não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             } 
             
             if (in_array(108,$aCodNec) && in_array(114,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências DEFICIENCIA MÚLTIPLA";
               $sMsgErro .= " e DEFICIÊNCIA INTELECTUAL não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             } 
             
             if (in_array(108,$aCodNec) && in_array(115,$aCodNec)) {
                 
               $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiências DEFICIENCIA MÚLTIPLA";
               $sMsgErro .= " e AUTISMO INFANTIL não podem ser informadas simultaneamente.\n";
               fwrite($ponteiro2,$sMsgErro);
               $lErroAluno = true;
               
             } 
             
             if (in_array(108,$aCodNec)) {
                 
               if((in_array(101,$aCodNec) && in_array(106,$aCodNec)) 
                   OR (in_array(101,$aCodNec) && in_array(107,$aCodNec)) 
                   OR (in_array(102,$aCodNec) && in_array(106,$aCodNec))
                   OR (in_array(102,$aCodNec) && in_array(107,$aCodNec)) 
                   OR (in_array(103,$aCodNec) && in_array(106,$aCodNec)) 
                   OR (in_array(103,$aCodNec) && in_array(107,$aCodNec))
                   OR (in_array(105,$aCodNec) && in_array(106,$aCodNec)) 
                   OR (in_array(105,$aCodNec) && in_array(107,$aCodNec))) {
                       
               } else {
                   
                 $sMsgErro = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Deficiência DEFICIENCIA";
                 $sMsgErro .= " MULTIPLA somente poderá ser informada se existir a informação nas";
                 $sMsgErro .= " deficiências simultaneamente: ";
                 $sMsgErro .= "Cegueira e Deficiência Física OU "; 
                 $sMsgErro .= "Cegueira e Deficiência Mental OU ";
                 $sMsgErro .= "Baixa visão e Deficiência Física OU ";
                 $sMsgErro .= "Baixa visão e Deficiência Mental OU ";
                 $sMsgErro .= "Surdez e Deficiência Física OU ";
                 $sMsgErro .= "Surdez e Deficiência Mental OU ";
                 $sMsgErro .= "Surdocegueira e Deficiência Física OU ";
                 $sMsgErro .= "Surdocegueira e Deficiência Mental\n";
                 fwrite($ponteiro2,$sMsgErro);
                 $lErroAluno = true;
                 
               }
             }   
              
             unset($aCodNec);
           } else {
               
             $necessidades = "0|";
             $tiponecessidades = "|||||||||||||";
           }
           
           if ($oDadosAluno->ed10_i_tipoensino == 2 && $tiponecessidades == "0000000000000") {
               
             $sMsgErro  = "ALUNO: Aluno ".$oDadosAluno->ed47_i_codigo." ".trim($oDadosAluno->ed47_v_nome)." - Aluno está vinculado a uma";
             $sMsgErro .= " turma de Educação Especial mas não contém Necessidades Especiais cadastradas no sistema.\n";
             fwrite($ponteiro2,$sMsgErro);
             $lErroAluno = true;
             
           }
           
           if (trim($oDadosAluno->ed47_c_raca) == "BRANCA") {
             $oDadosAluno->ed47_c_raca = "1";
           } else if (trim($oDadosAluno->ed47_c_raca) == "PRETA") {
             $oDadosAluno->ed47_c_raca = "2";
           } else if (trim($oDadosAluno->ed47_c_raca) == "PARDA") {
             $oDadosAluno->ed47_c_raca = "3";
           } else if (trim($oDadosAluno->ed47_c_raca) == "AMARELA") {
             $oDadosAluno->ed47_c_raca = "4";
           } else if (trim($oDadosAluno->ed47_c_raca) == "INDÍGENA") {
             $oDadosAluno->ed47_c_raca = "5";
           } else {
             $oDadosAluno->ed47_c_raca = "0";
           }
           
           if (trim($oDadosAluno->ed47_c_certidaotipo) == "N") {
             $oDadosAluno->ed47_c_certidaotipo = "1";
           } else if (trim($oDadosAluno->ed47_c_certidaotipo) == "C") {
             $oDadosAluno->ed47_c_certidaotipo = "2";
           } else {
             $oDadosAluno->ed47_c_certidaotipo = "";
           }
           
           $oDadosAluno->ed47_c_codigoinep          = $oDadosAluno->ed47_c_codigoinep;
           $oDadosAluno->ed47_i_codigo              = $oDadosAluno->ed47_i_codigo;
           $oDadosAluno->ed47_v_nome                = TiraCaracteres($oDadosAluno->ed47_v_nome,1);
           $oDadosAluno->ed47_c_nis                 = TiraCaracteres($oDadosAluno->ed47_c_nis,0);
           $oDadosAluno->ed47_d_nasc                = db_formatar($oDadosAluno->ed47_d_nasc,'d'); //str_replace("/","",db_formatar($oDadosAluno->ed47_d_nasc,'d'));
           $oDadosAluno->ed47_v_sexo                = ($oDadosAluno->ed47_v_sexo=="M"?"1":"2");
           $oDadosAluno->ed47_c_raca                = $oDadosAluno->ed47_c_raca;
           $oDadosAluno->ed47_i_filiacao            = $oDadosAluno->ed47_i_filiacao;
           $oDadosAluno->ed47_v_mae                 = TiraCaracteres($oDadosAluno->ed47_v_mae,1);
           $oDadosAluno->ed47_v_pai                 = TiraCaracteres($oDadosAluno->ed47_v_pai,1);
           $oDadosAluno->ed47_i_nacion              = $oDadosAluno->ed47_i_nacion;
           $oDadosAluno->ed228_i_paisonu             = $oDadosAluno->ed228_i_paisonu;
           $oDadosAluno->ed47_i_censoufnat          = $oDadosAluno->ed47_i_censoufnat;
           $oDadosAluno->ed47_i_censomunicnat       = $oDadosAluno->ed47_i_censomunicnat;    
           $oDadosAluno->ed47_v_ident               = TiraCaracteres($oDadosAluno->ed47_v_ident,0);
           $oDadosAluno->ed47_v_identcompl          = TiraCaracteres($oDadosAluno->ed47_v_identcompl,0);
           $oDadosAluno->ed47_i_censoorgemissrg     = $oDadosAluno->ed47_i_censoorgemissrg;
           $oDadosAluno->ed47_i_censoufident        = $oDadosAluno->ed47_i_censoufident;
           $oDadosAluno->ed47_d_identdtexp          = db_formatar($oDadosAluno->ed47_d_identdtexp,'d'); //str_replace("/","",db_formatar($oDadosAluno->ed47_d_identdtexp,'d'));
           $oDadosAluno->ed47_c_certidaotipo        = $oDadosAluno->ed47_c_certidaotipo;
           $oDadosAluno->ed47_c_certidaonum         = TiraCaracteres($oDadosAluno->ed47_c_certidaonum,0);
           $oDadosAluno->ed47_c_certidaofolha       = TiraCaracteres($oDadosAluno->ed47_c_certidaofolha,4);
           $oDadosAluno->ed47_c_certidaolivro       = TiraCaracteres($oDadosAluno->ed47_c_certidaolivro,4);
           $oDadosAluno->ed47_c_certidaodata        = db_formatar($oDadosAluno->ed47_c_certidaodata,'d'); //str_replace("/","",db_formatar($oDadosAluno->ed47_c_certidaodata,'d'));
           $oDadosAluno->ed47_i_censocartorio       = $oDadosAluno->ed47_i_censocartorio;
           $oDadosAluno->ed47_i_censoufcert         = $oDadosAluno->ed47_i_censoufcert;
           $oDadosAluno->ed47_v_cpf                 = $oDadosAluno->ed47_v_cpf;
           $oDadosAluno->ed47_c_passaporte          = TiraCaracteres($oDadosAluno->ed47_c_passaporte,0);
           $oDadosAluno->ed47_c_zona                = (($oDadosAluno->ed47_c_zona=="URBANA"||$oDadosAluno->ed47_c_zona=="")?"1":"2");
           $oDadosAluno->ed47_v_cep                 = $oDadosAluno->ed47_v_cep;
           $oDadosAluno->ed47_v_ender               = TiraCaracteres($oDadosAluno->ed47_v_ender,3);
           $oDadosAluno->ed47_c_numero              = TiraCaracteres($oDadosAluno->ed47_c_numero,3);
           $oDadosAluno->ed47_v_compl               = TiraCaracteres($oDadosAluno->ed47_v_compl,3);
           $oDadosAluno->ed47_v_bairro              = TiraCaracteres($oDadosAluno->ed47_v_bairro,3);
           $oDadosAluno->ed47_i_censoufend          = $oDadosAluno->ed47_i_censoufend;
           $oDadosAluno->ed47_i_censomunicend       = $oDadosAluno->ed47_i_censomunicend;
           $oDadosAluno->ed57_i_codigoinep          = $oDadosAluno->ed57_i_codigoinep;
           $oDadosAluno->ed60_i_turma               = $oDadosAluno->ed60_i_turma;
           $oDadosAluno->ed47_c_atenddifer          = ($oDadosAluno->ed47_c_atenddifer==""?"3":$oDadosAluno->ed47_c_atenddifer);
           $oDadosAluno->ed47_i_transpublico        = ($oDadosAluno->ed47_i_transpublico==""?"0":$oDadosAluno->ed47_i_transpublico);
           $oDadosAluno->ed47_c_transporte          = $oDadosAluno->ed47_c_transporte;
           $matriculacertidao           = "";
           
           if ($oDadosAluno->ed11_i_codcenso == 3) {
             $turmaunificada = "1|";
           } else {
             $turmaunificada = "";
           }
           
           if (($oDadosAluno->ed10_i_tipoensino == 1 || $oDadosAluno->ed10_i_tipoensino == 2) 
                && ($oDadosAluno->ed11_i_codcenso == 12 || $oDadosAluno->ed11_i_codcenso == 13)) {
                    
             $turmamultietapa = $codcensomatricula;
             
           } else if (($oDadosAluno->ed10_i_tipoensino == 1 || $oDadosAluno->ed10_i_tipoensino == 2) 
                       && ($oDadosAluno->ed11_i_codcenso == 22 || $oDadosAluno->ed11_i_codcenso == 23)) {
                           
             $turmamultietapa = $codcensomatricula;
             
           } else if (($oDadosAluno->ed10_i_tipoensino == 1 || $oDadosAluno->ed10_i_tipoensino == 2) && $oDadosAluno->ed11_i_codcenso == 24) {
             $turmamultietapa = $codcensomatricula;
           } else if (($oDadosAluno->ed10_i_tipoensino == 2 || $oDadosAluno->ed10_i_tipoensino == 3) && $oDadosAluno->ed11_i_codcenso == 51) {
             $turmamultietapa = $codcensomatricula;
           } else if (($oDadosAluno->ed10_i_tipoensino == 1 || $oDadosAluno->ed10_i_tipoensino == 2) && $oDadosAluno->ed11_i_codcenso == 56) {
             $turmamultietapa = $codcensomatricula;
           } else if (($oDadosAluno->ed10_i_tipoensino == 2 || $oDadosAluno->ed10_i_tipoensino == 3) && $oDadosAluno->ed11_i_codcenso == 58) {
             $turmamultietapa = $codcensomatricula;        
           } else if (($oDadosAluno->ed10_i_tipoensino == 1 || $oDadosAluno->ed10_i_tipoensino == 2) && $oDadosAluno->ed11_i_codcenso == 64) {        
             $turmamultietapa = $codcensomatricula;
           } else {
             $turmamultietapa = "";
           } 
           
           if ($lErroAluno == false) {
               
             $num_linha++;
             $write_linha  = "60|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosAluno->ed47_c_codigoinep);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_codigo)."|".trim($oDadosAluno->ed47_v_nome)."|".trim($oDadosAluno->ed47_c_nis);
             $write_linha .= "|".trim($oDadosAluno->ed47_d_nasc)."|".trim($oDadosAluno->ed47_v_sexo)."|".trim($oDadosAluno->ed47_c_raca);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_filiacao)."|".trim($oDadosAluno->ed47_v_mae)."|".trim($oDadosAluno->ed47_v_pai);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_nacion)."|".trim($oDadosAluno->ed228_i_paisonu)."|".trim($oDadosAluno->ed47_i_censoufnat);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_censomunicnat)."|".trim($necessidades)."".trim($tiponecessidades)."\n";
             fwrite($ponteiro,$write_linha);
             $num_linha++;
             
             $write_linha  = "70|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosAluno->ed47_c_codigoinep);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_codigo)."|".trim($oDadosAluno->ed47_v_ident);
             $write_linha .= "|".trim($oDadosAluno->ed47_v_identcompl);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_censoorgemissrg);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_censoufident)."|".trim($oDadosAluno->ed47_d_identdtexp)."|".$iModeloCertidao;
             $write_linha .= "|".trim($oDadosAluno->ed47_c_certidaotipo)."|".trim($oDadosAluno->ed47_c_certidaonum);
             $write_linha .= "|".trim($oDadosAluno->ed47_c_certidaofolha)."|".trim($oDadosAluno->ed47_c_certidaolivro);
             $write_linha .= "|".trim($oDadosAluno->ed47_c_certidaodata)."|".trim($oDadosAluno->ed47_i_censoufcert);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_censomuniccert)."|".trim($oDadosAluno->ed47_i_censocartorio);
             $write_linha .= "|".$matriculacertidao."|".trim($oDadosAluno->ed47_v_cpf)."|".trim($oDadosAluno->ed47_c_passaporte);
             $write_linha .= "|".trim($oDadosAluno->ed47_c_zona)."|".trim($oDadosAluno->ed47_v_cep);
             $write_linha .= "|".trim($oDadosAluno->ed47_v_ender)."|".trim($oDadosAluno->ed47_c_numero)."|".trim($oDadosAluno->ed47_v_compl);
             $write_linha .= "|".trim($oDadosAluno->ed47_v_bairro)."|".trim($oDadosAluno->ed47_i_censoufend);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_censomunicend)."|\n";
             fwrite($ponteiro,$write_linha);
             $num_linha++;
             
             $write_linha = "80|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosAluno->ed47_c_codigoinep);
             $write_linha .= "|".trim($oDadosAluno->ed47_i_codigo)."|".trim($oDadosAluno->ed57_i_codigoinep);
             $write_linha .= "|".trim($oDadosAluno->ed60_i_turma)."|";
             $write_linha .= "|".trim($turmaunificada)."|".trim($turmamultietapa);
             $write_linha .= "|".trim($oDadosAluno->ed47_c_atenddifer)."|".trim($oDadosAluno->ed47_i_transpublico);
             $write_linha .= "|".trim($oDadosAluno->ed47_c_transporte)."|\n";
             fwrite($ponteiro,$write_linha);
             
             $sCamposTurmaAcMat       = "ed268_i_codigo,ed268_i_codigoinep";
             $sWhereTurmaAcMatricula  = " ed60_i_aluno = $codigo_aluno AND turma.ed57_i_escola = $oDadosEscola->ed18_i_codigo";
             $sWhereTurmaAcMatricula .= " AND calendario.ed52_i_ano = $ed52_i_ano AND ed60_c_situacao = 'MATRICULADO'";
             $sSqlTurmaAcMatricula    = $clturmaacmatricula->sql_query("",$sCamposTurmaAcMat,"",$sWhereTurmaAcMatricula);
             $sResultTurmaAcMat       = $clturmaacmatricula->sql_record($sSqlTurmaAcMatricula);
             
             if ($clturmaacmatricula->numrows > 0) {
                 
               for ($q = 0; $q < $clturmaacmatricula->numrows; $q++) {
                   
                 $oDadosTurmaAcInep  = db_utils::fieldsmemory($sResultTurmaAcMat, $q);
                 $turmaunificada     = "";
                 $turmamultietapa    = "";
                 $num_linha++;
                 $write_linha  = "80|".trim($oDadosEscola->ed18_c_codigoinep)."|".trim($oDadosAluno->ed47_c_codigoinep);
                 $write_linha .= "|".trim($oDadosAluno->ed47_i_codigo)."|".trim($oDadosTurmaAcInep->ed268_i_codigoinep);
                 $write_linha .= "|".trim($oDadosTurmaAcInep->ed268_i_codigo)."||".trim($turmaunificada)."|".trim($turmamultietapa);
                 $write_linha .= "|".trim($oDadosAluno->ed47_c_atenddifer)."|".trim($oDadosAluno->ed47_i_transpublico)."|".trim($oDadosAluno->ed47_c_transporte)."|\n";
                 fwrite($ponteiro,$write_linha);
                 
               }
             }
           }
         }
         
         db_atutermometro_edu(99, 100, 'termometro',1,'Processo Concluído');
         
       }
     }
     
     fclose($ponteiro);
     fclose($ponteiro2);
     if ($lErroEscola == true || $lErroTurma == true || $lErroDocente == true || $lErroAluno == true) {
         
       ?>
  <script>
   jan = window.open('edu4_exportarcenso002.php?arquivo_erro=<?=$logerro_txt?>','Erros Geração de Arquivo Censo escolar','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
  </script>
  <?
  unlink($arquivo_txt);
  db_redireciona("edu4_exportarcenso001.php");
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
  db_redireciona("edu4_exportarcenso001.php");
       
     }
   }
  ?>
 </body>
</html>