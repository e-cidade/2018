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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_calendario_classe.php");
include("classes/db_turma_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcalendario = new cl_calendario;
$clturma      = new cl_turma;
$db_opcao     = 1;
$db_botao     = true;
$nomeescola   = db_getsession("DB_nomedepto");
$escola       = db_getsession("DB_coddepto");
$data         = date('Y');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 13;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 11;
}
</style>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos os calend�rios
 $sql        = " SELECT ed29_i_codigo,ed29_c_descr ";
 $sql       .= "  FROM cursoedu ";
 $sql       .= "   inner join cursoescola on ed71_i_curso = ed29_i_codigo ";
 $sql       .= "  WHERE ed71_i_escola = $escola ";
 $sql       .= "  AND ed71_c_situacao = 'S' ";
 $sql       .= "  ORDER BY ed29_c_descr ASC "; 
 $sql_result = pg_query($sql);
 $num        = pg_num_rows($sql_result);
 $conta      = "";
 while ($row = pg_fetch_array($sql_result)) {
   $conta     = $conta+1;
   $cod_curso = $row["ed29_i_codigo"];
   echo "new Array(\n";
   $sub_sql    = " SELECT DISTINCT ed11_i_codigo,ed11_c_descr,ed11_i_sequencia,ed11_i_ensino ";
   $sub_sql   .= "  FROM basemps "; 
   $sub_sql   .= "   inner join base on ed31_i_codigo = ed34_i_base ";
   $sub_sql   .= "   inner join escolabase on ed77_i_base = ed31_i_codigo ";
   $sub_sql   .= "   inner join serie on ed11_i_codigo = ed34_i_serie ";
   $sub_sql   .= "  WHERE ed31_i_curso = '$cod_curso' ";
   $sub_sql   .= "  AND ed77_i_escola = $escola ";
   $sub_sql   .= "  ORDER BY ed11_i_ensino,ed11_i_sequencia ";
   $sub_result = pg_query($sub_sql);
   $num_sub    = pg_num_rows($sub_result);
   if ($num_sub >= 1) {
     # Se achar alguma base para o curso, marca a palavra Todas
     echo "new Array(\"\", ''),\n";
     echo "new Array(\"TODAS\", 0),\n";
     $conta_sub = "";
     while ($rowx = pg_fetch_array($sub_result)) {
       $codigo_base = $rowx["ed11_i_codigo"];
       $base_nome   = $rowx["ed11_c_descr"];
       $conta_sub   = $conta_sub+1;
       if ($conta_sub == $num_sub) {
         echo "new Array(\"$base_nome\", $codigo_base)\n";
         $conta_sub = "";
       } else {
         echo "new Array(\"$base_nome\", $codigo_base),\n";
       }
     }
   } else {
     #Se nao achar base para o curso selecionado...
     echo "new Array(\"Nenhuma turma cadastrada neste curso\", '')\n";
   }
   if ($num > $conta) {
     echo "),\n";
   }
}
echo ")\n";
echo ");\n";
?>
//Inicio da fun��o JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
  var i, j;
  var prompt;
  for (i = selectCtrl.options.length; i >= 0; i--) {
    selectCtrl.options[i] = null;
  }
  prompt = (itemArray != null) ? goodPrompt : badPrompt;
  if (prompt == null) {
    document.form1.subgrupo.disabled = true;
    j = 0;
  } else {
    selectCtrl.options[0] = new Option(prompt);
    j = 1;
  }
  if (itemArray != null) {
    for (i = 0; i < itemArray.length; i++) {
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null) {
        selectCtrl.options[j].value = itemArray[i][1];
      }
      j++;
    }
    selectCtrl.options[0].selected   = true;
    document.form1.subgrupo.disabled = false;
  }
  document.form1.procurar.disabled = true;
}

function fillSelectFromArray2(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
  var i, j;
  var prompt;
  for (i = selectCtrl.options.length; i >= 0; i--) {
    selectCtrl.options[i] = null;
  }
  prompt = (itemArray != null) ? goodPrompt : badPrompt;
  if (prompt == null) {
    document.form1.subgrupo.disabled = true;
    j = 0;
  } else {
    selectCtrl.options[0] = new Option(prompt);
    j = 1;
  }
  if (itemArray != null) {
    for (i = 0; i < itemArray.length; i++) {
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null){
        selectCtrl.options[j].value = itemArray[i][1];
      }
    <?if (isset($serieescolhida)) {?>
        if (<?=trim($serieescolhida)?> == itemArray[i][1]) {
          indice = i;
        }
    <?}?>
      j++;
    }
  <?if (isset($serieescolhida)) {?>
      selectCtrl.options[indice].selected = true;
      document.form1.procurar.disabled    = false;
  <?} else {?>
      selectCtrl.options[0].selected = true;
  <?}?>
    document.form1.subgrupo.disabled = false;
  }
}
//End -->
</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<a name="topo"></a>
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="center" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b>Consulta Lista de Candidatos</b></legend>
    <table border="0">
     <tr>
      <td colspan="2">
       Escola:<?db_input('escola',20,@$escola,true,'text',3)?>
       <?db_input('nomeescola',50,@$nomeescola,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td align="left">
       <b>Calendario:</b><br>
       <select name="calenda" style="font-size:9px;width:150px;height:18px;">
       <option value="0">TODOS</option>
        <?
        $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
        $sql       .= "  FROM calendario ";
        $sql       .= "   inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
        $sql       .= "  WHERE ed38_i_escola = $escola AND ed52_c_passivo = 'N'";
        //$sql       .= "  AND ed52_i_ano >= ".date("Y",db_getsession("DB_datausu"));
        $sql       .= "  ORDER BY ed52_c_descr ASC ";
        echo ($sql);
        $sql_result = pg_query($sql);
        while ($row = pg_fetch_array($sql_result)) {
                  	
         $cod_cal  = $row["ed52_i_codigo"];
         $desc_cal = $row["ed52_c_descr"];
         ?>
         
         <option value="<?=$cod_cal?>" <?=$cod_cal==@$calendario?"selected":""?>><?=$desc_cal;?></option>
         <?
        }
        ?>
       </select>
      </td>
      <td align="left">
       <b>Selecione o Curso:</b><br>
       <select name="grupo" 
               onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" 
               style="font-size:9px;width:250px;height:18px;">
        <option></option>
        <?
        #Seleciona todos os grupos para setar os valores no combo
        $sql        = " SELECT ed29_i_codigo,ed29_c_descr ";
        $sql       .= "  FROM cursoedu ";
        $sql       .= "   inner join cursoescola on ed71_i_curso = ed29_i_codigo ";
        $sql       .= "  WHERE ed71_i_escola = $escola ";
        $sql       .= "  AND ed71_c_situacao = 'S' ";
        $sql       .= "  ORDER BY ed29_c_descr ASC ";     
        $sql_result = pg_query($sql);
        while($row = pg_fetch_array($sql_result)) {
          $cod_curso  = $row["ed29_i_codigo"];
          $desc_curso = $row["ed29_c_descr"];
         ?>
         <option value="<?=$cod_curso;?>" <?=$cod_curso==@$curso?"selected":""?>><?=$desc_curso;?></option>
         <?
        }
        #Popula o segundo combo de acordo com a escolha no primeiro
        ?>
       </select>
      </td>
      <td>
       <b>Selecione a Etapa:</b><br>
       <select name="subgrupo" style="font-size:9px;width:100px;height:18px;" disabled onchange="js_botao(this.value);">
        <option value=""></option>
       </select>
      </td>
      <td valign='bottom'>
       <input type="button" name="procurar" value="Procurar" 
              onclick="js_procurar(document.form1.calenda.value,document.form1.grupo.value,document.form1.subgrupo.value)" 
              disabled>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <?if (isset($curso)) {?>
 <tr>
  <td align="center">
   <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
   <table border="0" cellspacing="2px" width="98%" cellpadding="1px" bgcolor="#cccccc">
    <tr>
     <td align="center" valign="top">
      <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
      <tr class='cabec'>
       <td>C�digo Aluno</td>
       <td>Nome</td>
       <td>Endere�o</td>
       <td>Bairro</td>
       <td>Situa��o</td>
       <td>Etapa</td>
       <td>Calend�rio</td>
      </tr>
      <?
       
       if ($calendario !=0) {
         $result = $clcalendario->sql_record($clcalendario->sql_query("",
                                                                    "ed52_i_codigo,ed52_i_calendant",
                                                                    "",
                                                                    "ed52_i_codigo = $calendario" 
                                                                   )
                                           );
       db_fieldsmemory($result,0);
         $ed52_i_calendant = $ed52_i_calendant == 0 || $ed52_i_calendant == ""?0:$ed52_i_calendant;
       } else {
       	$ed52_i_calendant ="";
       	 
       }
       
       if ($serieescolhida != 0) {
         $where  = " AND alunopossib.ed79_i_serie= $serieescolhida";
         $where1 = " AND ed223_i_serie = $serieescolhida";
       } else {
        $where  = "";
        $where1 = "";
       }        
       if ($calendario !=0) {
       	$where4 = "alunocurso.ed56_i_calendario = $ed52_i_codigo AND";
         $where5 = "alunocurso.ed56_i_calendario = $ed52_i_calendant AND";
         
       } else {
       	 $where4 = "";
       	 $where5 = "";
       	 
       }
       $sCampos  = " ed47_i_codigo,ed47_v_nome,ed47_v_ender,ed47_c_numero,ed47_v_bairro,";
       $sCampos .= " ed56_c_situacao,ed11_c_descr,ed52_c_descr ";
       $sql      = " SELECT $sCampos ";
       $sql     .= "  FROM  alunocurso ";
       $sql     .= "   inner join escola  on  escola.ed18_i_codigo = alunocurso.ed56_i_escola ";
       $sql     .= "   inner join aluno on aluno.ed47_i_codigo = alunocurso.ed56_i_aluno ";
       $sql     .= "   inner join calendario on calendario.ed52_i_codigo = alunocurso.ed56_i_calendario ";
       $sql     .= "   inner join base on base.ed31_i_codigo = alunocurso.ed56_i_base ";
       $sql     .= "   inner join alunopossib on alunopossib.ed79_i_alunocurso= alunocurso.ed56_i_codigo ";
       $sql     .= "   inner join cursoedu on cursoedu.ed29_i_codigo = base.ed31_i_curso ";
       $sql     .= "   inner join serie on serie.ed11_i_codigo = alunopossib.ed79_i_serie ";
       $sql     .= "  WHERE alunocurso.ed56_i_escola= $escola ";
       $sql     .= "  AND base.ed31_i_curso = $curso ";
       $sql     .= "  $where ";
       $sql     .= "  AND (($where4 alunocurso.ed56_c_situacao='CANDIDATO') ";
       $sql     .= "  OR ($where5( alunocurso.ed56_c_situacao='APROVADO' ";
       $sql     .= "  OR alunocurso.ed56_c_situacao='REPETENTE'))) ";
       $sql     .= "  ORDER BY ed52_i_codigo,ed56_c_situacao,ed11_i_sequencia ";       
       $result   = pg_query($sql);
       $linhas   = pg_numrows($result);
       if ($linhas > 0) {
         $cor1 = "#f3f3f3";
         $cor2 = "#dbdbdb";
         $cor = "";
         $cont = 0;
         for ($x = 0; $x < $linhas; $x++) {
           db_fieldsmemory($result,$x);
           if ($cor == $cor1) {
             $cor = $cor2;
           } else {
             $cor = $cor1;
           }
           $cont++;
         ?>
         <tr bgcolor="<?=$cor?>">
          <td class="aluno"><?=$ed47_i_codigo?></td>
          <td class="aluno"><?=$ed47_v_nome?></td>
          <td class="aluno"><?=$ed47_v_ender." ".$ed47_c_numero?></td>
          <td class="aluno"><?=$ed47_v_bairro?></td>
          <td class="aluno"><?=$ed56_c_situacao?></td>
          <td class="aluno"><?=$ed11_c_descr?></td>
          <td class="aluno"><?=$ed52_c_descr?></td>
         </tr>
        <?
        }    
        $result_codigocalendario = $clcalendario->sql_record($clcalendario->sql_query("",
                                                                       "ed52_i_codigo as codigocalendario,ed52_i_ano",
                                                                       "ed52_i_codigo,ed52_i_ano desc",
                                                                       "ed52_i_ano =$data"
                                                                      )
                                            );
        if ($clcalendario->numrows > 0) {                                            
          db_fieldsmemory($result_codigocalendario,0);
        }                                                                       
        $sWhere  = " ed57_i_calendario =$codigocalendario  and ed31_i_curso = $curso ".$where1;          
        $result1 = $clturma->sql_record($clturma->sql_query_turmaserie("",
                                                                       "sum(ed57_i_numvagas-ed57_i_nummatr) as vagas",
                                                                       "",
                                                                       $sWhere
                                                                      )
                                                                      
                                       );
        if ($clturma->numrows > 0) {                                       
          db_fieldsmemory($result1,0);
        }
        ?>
        <tr bgcolor="#EAEAEA">
         <td class='aluno' colspan="7">
         <b>Etapa:</b> <?=$serieescolhida!=0?$ed11_c_descr:"TODAS"?><br>
         <b>Total de candidato(s):</b> <?=$cont?><br>
         <b>Total de vagas dispon�veis no calend�rio atual:</b> <?=$vagas==""||$vagas==0?0:$vagas?><br>
         <input type="button" name="imprimir" value="Imprimir Consulta" onclick= "js_imprimir();">
         <?db_input('cont',10,@$cont,true,'hidden',3,'')?>
         <?db_input('vagas',50,@$vagas,true,'hidden',3,'')?>
         </td>
         <td></td>
        </tr>
        <?
       }else{
        ?>
        <tr bgcolor="#EAEAEA">
         <td align="center" class='aluno' colspan="7">Nenhum candidato para as op��es escolhidas.</td>
        </tr>
        <?
       }
      }
      ?>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
  db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_procurar(calendario,curso,turma) {
  if (calendario != "" && curso != "" && turma != "") {
    location.href = "edu3_listacandidatos001.php?calendario="+calendario+"&curso="+curso+"&serieescolhida="+turma;
  }
}

function js_imprimir(calendario,grupo,subgrupo) {
	
  jan = window.open("edu2_listacandidatos002.php?calendario="+document.form1.calenda.value+
		            "&curso="+document.form1.grupo.value+
		            "&serieescolhida="+document.form1.subgrupo.value+"&cont="+document.form1.cont.value+
		            "&vagas="+document.form1.vagas.value,"",
			        "width="+(screen.availWidth-5)+",height="+(screen.availHeight-40)+",scrollbars=1,location=0 "
			       );
	
}

function js_matriculas(turma,descrturma,calendario) {
	
  js_OpenJanelaIframe('top.corpo','db_iframe_matriculas','edu3_alunomatriculado002.php?turma='+turma,
		              'Alunos Matriculados na Turma '+descrturma,true);
  location.href = "#topo";
  
}

function js_botao(valor) {
  if (valor != "") {
    document.form1.procurar.disabled = false;
  } else {
    document.form1.procurar.disabled = true;
  }
}

<?if(!isset($serieescolhida) && pg_num_rows($sql_result)>0){?>
 fillSelectFromArray2(document.form1.subgrupo,team[0]);
 document.form1.grupo.options[1].selected = true;
<?}?>


</script>