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
require_once("libs/db_utils.php");
include("classes/db_mer_desper_und_classe.php");
include("classes/db_mer_cardapioaluno_classe.php");
include("classes/db_mer_cardapioturma_classe.php");
include("classes/db_mer_cardapiodia_classe.php");
include("classes/db_mer_cardapiodata_classe.php");
include("classes/db_mer_cardapioitem_classe.php");
include("classes/db_mer_subitem_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clmer_desper_und    = new cl_mer_desper_und;
$clmer_cardapioaluno = new cl_mer_cardapioaluno;
$clmer_cardapioturma = new cl_mer_cardapioturma;
$clmer_cardapiodia   = new cl_mer_cardapiodia;
$clmer_cardapiodata  = new cl_mer_cardapiodata;
$clmer_cardapioitem  = new cl_mer_cardapioitem;
$clmer_subitem       = new cl_mer_subitem;
$nutricionista = VerNutricionista(db_getsession("DB_id_usuario"));
$codescola              = db_getsession("DB_coddepto");


if (!isset($fim)) {
	
  $fim_dia    = date("d",db_getsession("DB_datausu"));
  $fim_mes    = date("m",db_getsession("DB_datausu"));
  $fim_ano    = date("Y",db_getsession("DB_datausu"));
  $fim        = $fim_dia."/".$fim_mes."/".$fim_ano;
  $inicio_dia = date("d",db_getsession("DB_datausu"));
  $inicio_mes = date("m",db_getsession("DB_datausu"));
  $inicio_ano = date("Y",db_getsession("DB_datausu"));
  $inicio     = $inicio_dia."/".$inicio_mes."/".$inicio_ano;
  
} else {
	
  $fim_dia     = substr($fim,8,2);
  $fim_mes     = substr($fim,5,2);
  $fim_ano     = substr($fim,0,4);
  $fim         = $fim_dia."/".$fim_mes."/".$fim_ano;
  $inicio_dia  = substr($inicio,8,2);
  $inicio_mes  = substr($inicio,5,2);
  $inicio_ano  = substr($inicio,0,4);
  $inicio      = $inicio_dia."/".$inicio_mes."/".$inicio_ano;
  
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<style>
.cabec{
 font-size: 12;
 color: #DEB887;
 background-color:#dbdbdb;
 font-weight: bold;
}
.descri{
 font-size: 13;
 font-weight: bold;

}
</style>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">   
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Consumo de Refeições</b></legend>
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
     <tr>
      <td>
       <fieldset><legend><b>Automático</b></legend>
        <table border="0" cellpadding="0" cellspacing="0">
         <tr>
          <td>
           <select name="periodo" value="0">
            <option value="0" <?=@$periodo=="0"?"selected":""?>> </option>
            <option value="1" <?=@$periodo=="1"?"selected":""?>>Semana</option>
            <option value="2" <?=@$periodo=="2"?"selected":""?>>Mês</option>
           </select>
          </td>
          <td>
           <input name="consultar" type="button" value="Consultar" onclick="js_consulta1();">
          </td>
         <tr>
        </table>
       </fieldset>
      </td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td>
       <fieldset><legend><b>Por Período</b></legend>
        <table border="0" cellpadding="0" cellspacing="0">
         <tr>
          <td><b>De&nbsp;&nbsp;</b></td>
          <td><?db_inputdata('inicio',@$inicio_dia,@$inicio_mes,@$inicio_ano,true,'text',1,"");?></td>
	      <td><b>&nbsp;&nbsp;até&nbsp;&nbsp;</b></td>
	      <td><?db_inputdata('fim',@$fim_dia,@$fim_mes,@$fim_ano,true,'text',1,"");?></td>
          <td><input name="consultar" type="button" value="Consultar" onclick="js_consulta2()"></td>
         </tr>
        </table>
       </fieldset>
      </td>
     </tr>
    </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?
if (isset($opcao)) {
	
  if ($opcao==1) {
  	
  //Automatico
    if ($periodo==1) {
    	
   //Semana
      $ano    = date("Y",db_getsession("DB_datausu"));
      $mes    = date("m",db_getsession("DB_datausu"));
      $dia    = date("d",db_getsession("DB_datausu"));
      $weeke  = date("w", mktime(0,0,0,$mes,$dia,$ano));
      $inicio = date("Y-m-d",mktime(0, 0, 0, $mes, $dia+(2-($weeke+1)), $ano));
      $fim    = date("Y-m-d",mktime(0, 0, 0, $mes, $dia+(6-($weeke+1)), $ano));
      
    } else {
   //Mês
      $ano    = date("Y",db_getsession("DB_datausu"));
      $mes    = date("m",db_getsession("DB_datausu"));
      $inicio = $ano."-".$mes."-01";
      $fim    = date("Y-m-t", mktime(0, 0, 0, $mes, 1, $ano));
      
    }
  }
  if ($opcao==2) {
  	
    $inicio = substr($inicio,6,4)."-".substr($inicio,3,2)."-".substr($inicio,0,2);
    $fim    = substr($fim,6,4)."-".substr($fim,3,2)."-".substr($fim,0,2);
    
  }
if ($nutricionista != "") {
  
  // Obtenho todas as escolas atendidas pelo usuário nutricionista
  $oDaoMerNutricionistaEscola = db_utils::getdao('mer_nutricionistaescola');
  $sSql                       = $oDaoMerNutricionistaEscola->sql_query_nutricionistausuario(null, 'me31_i_escola', '',
                                                                                            'db_usuarios.id_usuario = '.
                                                                                            $nutricionista
                                                                                           );
  $rs                         = $oDaoMerNutricionistaEscola->sql_record($sSql);
  if ($oDaoMerNutricionistaEscola->numrows > 0) {
    
    $sCodEscolas = '';
    $sSepEscolas = '';
    for ($iCont = 0; $iCont < $oDaoMerNutricionistaEscola->numrows; $iCont++) {
      
      $sCodEscolas .= $sSepEscolas.db_utils::fieldsmemory($rs, $iCont)->me31_i_escola;
      $sSepEscolas  = ', ';

    }
    $condicao = " and me32_i_escola in ($sCodEscolas)";

  } else {
    $condicao = " and me32_i_escola = $codescola";
  }

} else {
  $condicao = " and me32_i_escola = $codescola";	
}
  $campos   = " me12_i_codigo,me03_c_tipo,me01_i_codigo,me01_c_nome,me01_f_versao,me12_d_data, me37_i_codigo, ";
  $campos  .= " ed18_i_codigo, ed18_c_nome, ed18_c_abrev ";
            
  $sSql     = $clmer_cardapiodia->sql_query_cardapiodiaescola('', $campos, "me12_d_data,me03_i_orden,me01_c_nome",
                                                              " me12_d_data between '$inicio' AND '$fim' $condicao"
                                                             );
  $result0  = $clmer_cardapiodia->sql_record($sSql);
  ?>
  <center>
   <br>
  <fieldset style="width:95%"><legend><b>Registros</b></legend>
  <table width="100%" height="380" border="1" cellpadding="0" cellspacing="0">
   <tr>
    <td width="45%" valign="top" bgcolor="#f3f3f3" >
     <table width="100%" border="1" cellpadding="0" cellspacing="0">
     <?
     $primeiro = "";
     if ($clmer_cardapiodia->numrows == 0) {
     	
       ?>
       <tr><td bgcolor="#CCCCCC">Nenhum registro para o período</td></tr>
       <?
       
     }
     for ($x=0;$x<$clmer_cardapiodia->numrows;$x++) {
     	
       db_fieldsmemory($result0,$x);
       if($primeiro!=$me12_d_data){
       	
         ?>
         <tr><td bgcolor="#f3f3f3"><b><?=db_formatar($me12_d_data,'d')?></b></td></tr>
         <?
         $primeiro = $me12_d_data;
         
       }
       $texto_dados  = '<table width="100%" border="1" cellpadding="0" cellspacing="0">';
       $texto_dados .= '<tr><td class="cabec" colspan="5" bgcolor="#444444">';
       $texto_dados .= '<font color="FF6600">Detalhes Refeição '.$me01_c_nome.' - Versão: '.$me01_f_versao.' - ';
       $texto_dados .=  db_formatar($me12_d_data,'d').'</font></td></tr>';
       $texto_dados .= '<tr>';
       $texto_dados .= '<td class="descri" bgcolor="#f3f3f3" colspan="5">ÍTENS</td>';
       $texto_dados .= '</tr>';
       $texto_dados .= '<tr>';
       $texto_dados .= '<td class="descri">Código</td>';
       $texto_dados .= '<td class="descri">Alimento</td>';
       $texto_dados .= '<td class="descri">Quantidade</td>';
       $texto_dados .= '<td class="descri">Unidade</td>';
       $texto_dados .= '<td class="descri">Medida Caseira</td>';
       $texto_dados .= '</tr>';
       $campos = " me35_i_codigo,me35_c_nomealimento,m61_descr,me07_c_medida,me07_f_quantidade ";
       $result1 = $clmer_cardapioitem->sql_record($clmer_cardapioitem->sql_query("",
                                                                                 $campos,
                                                                                 "me35_c_nomealimento",
                                                                                 " me07_i_cardapio = $me01_i_codigo"
                                                                                ));
       for ($y=0;$y<$clmer_cardapioitem->numrows;$y++) {
       	
         db_fieldsmemory($result1,$y);
         $texto_dados .= '<tr>';
         $texto_dados .= '<td>'.$me35_i_codigo.'</td>';
         $texto_dados .= '<td>'.substr($me35_c_nomealimento,0,30).'</td>';
         $texto_dados .= '<td>'.$me07_f_quantidade.'</td>';
         $texto_dados .= '<td>'.$m61_descr.'</td>';
         $texto_dados .= '<td>'.$me07_c_medida.'</td>';
         $texto_dados .= '</tr>';
         
       }
       $campos  = " me29_i_alimentoorig,mer_alimento.me35_c_nomealimento as alimentoorig,alimento.me35_c_nomealimento as alimentonovo, ";
       $campos .= " me29_i_alimentonovo,me29_f_quantidade ";
       $result2 = $clmer_subitem->sql_record($clmer_subitem->sql_query("",
                                                                       $campos,
                                                                       "alimentoorig",
                                                                       " me29_i_refeicao = $me01_i_codigo 
                                                                         AND '$me12_d_data' BETWEEN me29_d_inicio 
                                                                         AND me29_d_fim"
                                                                      ));
       $texto_dados .= '<tr>';
       $texto_dados .= '<td class="descri" bgcolor="#f3f3f3" colspan="5">SUBSTITUIÇÕES</td>';
       $texto_dados .= '</tr>';
       if ($clmer_subitem->numrows>0) {
       	
         $texto_dados .= '<tr>';
         $texto_dados .= '<td class="descri">Código</td>';
         $texto_dados .= '<td class="descri">Substituído</td>';
         $texto_dados .= '<td class="descri">Código</td>';
         $texto_dados .= '<td class="descri">Substituto</td>';
         $texto_dados .= '<td class="descri">Quantidade</td>';
         $texto_dados .= '</tr>';
         for ($y=0;$y<$clmer_subitem->numrows;$y++) {
         	
           db_fieldsmemory($result2,$y);
           $texto_dados .= '<tr>';
           $texto_dados .= '<td>'.$me29_i_alimentoorig.'</td>';
           $texto_dados .= '<td>'.substr($alimentoorig,0,30).'</td>';
           $texto_dados .= '<td>'.$me29_i_alimentonovo.'</td>';
           $texto_dados .= '<td>'.substr($alimentonovo,0,30).'</td>';
           $texto_dados .= '<td>'.$me29_f_quantidade.'</td>';
           $texto_dados .= '</tr>';
           
         }
       } else {
       	
         $texto_dados .= '<tr>';
         $texto_dados .= '<td colspan="5">Nenhum registro.</td>';
         $texto_dados .= '</tr>';
         
       }
       $result3 = $clmer_desper_und->sql_record($clmer_desper_und->sql_query("",
                                                                             "m61_descr,me23_f_quant,me23_t_obs",
                                                                             "",
                                                                             " me22_i_cardapiodiaescola = $me37_i_codigo"
                                                                            )
                                               );
       $texto_dados .= '<tr>';
       $texto_dados .= '<td class="descri" bgcolor="#f3f3f3" colspan="5">DESPERDÍCIO</td>';
       $texto_dados .= '</tr>';
       if ($clmer_desper_und->numrows>0) {
       	
         $texto_dados .= '<tr>';
         $texto_dados .= '<td class="descri">Quantidade</td>';
         $texto_dados .= '<td class="descri">Unidade</td>';
         $texto_dados .= '<td class="descri" colspan="3">Observação</td>';
         $texto_dados .= '</tr>';
         for ($y=0;$y<$clmer_desper_und->numrows;$y++) {
         	
           db_fieldsmemory($result3,$y);
           $me23_t_obs   = empty($me23_t_obs) ? '&nbsp;' : $me23_t_obs;
           $texto_dados .= '<tr>';
           $texto_dados .= '<td>'.$me23_f_quant.'</td>';
           $texto_dados .= '<td>'.$m61_descr.'</td>';
           $texto_dados .= '<td colspan="3">'.$me23_t_obs.'</td>';
           $texto_dados .= '</tr>';
           
         }
       } else {
       	
         $texto_dados .= '<tr>';
         $texto_dados .= '<td colspan="5">Nenhum registro</td>';
         $texto_dados .= '</tr>';
         
       }

       $texto_dados .= '<tr>';
       $texto_dados .= '<td class="descri" bgcolor="#f3f3f3" colspan="5">CONSUMO DE REFEIÇÃO - '.$ed18_c_nome.'</td>';
       $texto_dados .= '</tr>';


       $campos  = " ed47_i_codigo,ed47_v_nome,ed57_c_descr,ed11_c_descr ";
       $order   = " ed11_i_sequencia,ed57_c_descr,ed47_v_nome ";
       $sWhere  = " me11_i_cardapiodia = $me12_i_codigo and ed18_i_codigo = $ed18_i_codigo ";
       $sSql    = $clmer_cardapioaluno->sql_query(null, $campos, $order, $sWhere);
       $result4 = $clmer_cardapioaluno->sql_record($sSql);
       if ($clmer_cardapioaluno->numrows>0) { // Baixa por aluno

         db_fieldsmemory($result4,0);
       	
         $texto_dados .= '<tr>';
         $texto_dados .= '<td class="descri">Código</td>';
         $texto_dados .= '<td class="descri" colspan="3">Nome</td>';
         $texto_dados .= '<td class="descri">Turma / Etapa</td>';
         $texto_dados .= '</tr>';
         for ($y=0;$y<$clmer_cardapioaluno->numrows;$y++) {
         	
           db_fieldsmemory($result4,$y);
           $texto_dados .= '<tr>';
           $texto_dados .= '<td>'.$ed47_i_codigo.'</td>';
           $texto_dados .= '<td colspan="3">'.trim($ed47_v_nome).'</td>';
           $texto_dados .= '<td>'.trim($ed57_c_descr).' / '.trim($ed11_c_descr).'</td>';
           $texto_dados .= '</tr>';
           
         }
       } else {
       	
         $campos  = " ed57_i_codigo,ed57_c_descr,ed11_c_descr,me39_i_quantidade,me39_i_repeticao ";
         $order   = " ed11_i_sequencia,ed57_c_descr ";
         $sWhere  = " me39_i_cardapiodia = $me12_i_codigo and ed18_i_codigo = $ed18_i_codigo ";
         $sSql    = $clmer_cardapioturma->sql_query(null, $campos, $order, $sWhere);
         $result4 = $clmer_cardapioturma->sql_record($sSql);
         if ($clmer_cardapioturma->numrows>0) {

           $texto_dados .= '<tr>';
           $texto_dados .= '<td class="descri">Código</td>';
           $texto_dados .= '<td class="descri">Turma</td>';           
           $texto_dados .= '<td class="descri" colspan="2">Quantidade</td>';
           $texto_dados .= '<td class="descri">Repetição</td>';
           $texto_dados .= '</tr>';
           for ($y=0;$y<$clmer_cardapioturma->numrows;$y++) {
            
             db_fieldsmemory($result4,$y);
             $texto_dados .= '<tr>';
             $texto_dados .= '<td>'.$ed57_i_codigo.'</td>';
             $texto_dados .= '<td>'.trim($ed57_c_descr).' / '.trim($ed11_c_descr).'</td>';
             $texto_dados .= '<td colspan="2">'.$me39_i_quantidade.'</td>';             
             $texto_dados .= '<td>'.$me39_i_repeticao.'</td>';
             $texto_dados .= '</tr>';
           
           }
           
         } else {
                                                  
           $texto_dados .= '<tr>';
           $texto_dados .= '<td colspan="5">Nenhum registro</td>';
           $texto_dados .= '</tr>';
           
         }
         
       }

       $sCampos = "me13_i_codigo, me13_d_data";
       $sWhere  = " me13_i_cardapiodiaescola = $me37_i_codigo";
       $sSql    = $clmer_cardapiodata->sql_query_baixa(null, $sCampos, '', $sWhere);
       $result5 = $clmer_cardapiodata->sql_record($sSql);

       $texto_dados .= '<tr>';
       $texto_dados .= '<td class="descri" bgcolor="#f3f3f3" colspan="5">BAIXA DE ESTOQUE</td>';
       $texto_dados .= '</tr>';
       if ($clmer_cardapiodata->numrows>0) {
       	
         db_fieldsmemory($result5,0);
         $texto_dados .= '<tr>';
         $texto_dados .= '<td class="descri" colspan="2">Código Baixa</td>';
         $texto_dados .= '<td class="descri" colspan="3">Data</td>';
         $texto_dados .= '</tr>';
         $texto_dados .= '<tr>';
         $texto_dados .= '<td colspan="2">'.$me13_i_codigo.'</td>';
         $texto_dados .= '<td colspan="3">'.db_formatar($me13_d_data,'d').'</td>';
         $texto_dados .= '</tr>';
         $baixa = "&nbsp;&nbsp;<font color=red>Baixado</font>";
         
       } else {
       	
         $texto_dados .= '<tr>';
         $texto_dados .= '<td colspan="5">Nenhum registro</td>';
         $texto_dados .= '</tr>';
         $baixa = "";
         
       }
       $texto_dados .= '</table>';
       ?>
       <tr bgcolor="#CCCCCC">
        <td id="R<?=$x?>" onclick="js_texto('R<?=$x?>','<?=urlencode($texto_dados)?>')" >
         <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr style="cursor:pointer">
           <td width="25%">-> <?=trim($me03_c_tipo)?></td>
           <td width="35%"><b><?=trim($me01_c_nome)?> - <?=trim($me01_f_versao)?></b></td>
           <td width="30%"><?=trim($ed18_c_abrev)?></td>
           <td align="right"><b><?=$baixa?></b></td>
          </tr>
         </table>  
        </td>
       </tr>
      <?
     }
    ?>
    </table> 
    </td>
    <td width="1%"><a name="inicio"></a><input type="hidden" id="clicado" value=""></td>
    <td width="54%" id="dados" valign="top" align="center">
     &nbsp;
     <?
      if ($clmer_cardapiodia->numrows>0) {
     ?>
        Clique sobre uma refeição ao lado.
      <?
      }
      ?>
    </td>
   </tr>
  </table>
 </fieldset>
</center>
 <?
}
?>
</form>
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_consulta1() {
	
  opcao = 1;
  periodo = document.form1.periodo.value;
  if (periodo==0) {
    alert('Selecione um periodo!');
  } else {
    location.href = 'mer3_mer_histcardapios001.php?opcao='+opcao+'&periodo='+periodo+'&inicio='
  }
}

function js_consulta2() {
	
  opcao = 2;
  if (document.form1.inicio.value=="" || document.form1.fim.value=="") {
	  
    alert('Informe a data inicial e data final!');
    return false;
    
  }
  inicio = document.form1.inicio.value.substr(6,4)+''+
           document.form1.inicio.value.substr(3,2)+''+
           document.form1.inicio.value.substr(0,2);
  fim    = document.form1.fim.value.substr(6,4)+''+
           document.form1.fim.value.substr(3,2)+''+
           document.form1.fim.value.substr(0,2);
  if (parseInt(inicio)>parseInt(fim)) {
    alert('Data inicial deve ser menor ou igual que a data final!');
  } else{
	  
    inicio = document.form1.inicio.value.substr(6,4)+'-'+
             document.form1.inicio.value.substr(3,2)+'-'+
             document.form1.inicio.value.substr(0,2);
    fim    = document.form1.fim.value.substr(6,4)+'-'+
             document.form1.fim.value.substr(3,2)+'-'+
             document.form1.fim.value.substr(0,2);
    location.href = 'mer3_mer_histcardapios001.php?opcao='+opcao+'&periodo=0&inicio='+inicio+'&fim='+fim;
    
  }
}

function js_texto(id,texto) {
	
  if (document.getElementById("clicado").value!="") {
    document.getElementById(document.getElementById("clicado").value).style.background = "#CCCCCC";
  }
  document.getElementById(id).style.background = "#CCFFCC";
  document.getElementById("dados").innerHTML   = texto.urlDecode();
  document.getElementById("clicado").value     = id;
  location.href = "#inicio";
}
</script>