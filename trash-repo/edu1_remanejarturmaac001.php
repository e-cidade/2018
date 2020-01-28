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
include("classes/db_turmaacativ_classe.php");
include("classes/db_turmaac_classe.php");
include("classes/db_turmalogac_classe.php");
include("classes/db_calendario_classe.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_utils.php"); 
$escola                = db_getsession("DB_coddepto");
$clturmaac             = new cl_turmaac;
$clturmaacativ         = new cl_turmaacativ;
$clturmalogac          = new cl_turmalogac;
$clcalendario          = new cl_calendario;
$usuario               = DB_getsession("DB_id_usuario");
$hoje                  = date("Y-m-d",db_getsession("DB_datausu"));
if (isset($processar)) {

  db_inicio_transacao();  
  $turmaacant = "";
  $sep        = "";
  if (isset($alunos)) {
    for ($p = 0; $p < sizeof($alunos); $p++) {
    
      $turmaacant .= $sep.$alunos[$p];
      $sep      = ","; 

    } 	
  }

  $result    = $clturmaac->sql_record($clturmaac->sql_query("",
                                                            "ed268_i_codigo as codigo,turmaac.*",
                                                            "",
                                                            "ed268_i_codigo in ($turmaacant)"
                                                           )
                                     );
  $linhas    = $clturmaac->numrows;
  $turmanova = "";
  $sepnovo   = "";
  for ($e = 0; $e < $linhas; $e++) {
    
	db_fieldsmemory($result,$e);   
	
    $clturmaac->ed268_i_codigoinep    = "null";
    $clturmaac->ed268_i_escola        = $ed268_i_escola;
    $clturmaac->ed268_i_calendario    = $calendario1;
    $clturmaac->ed268_c_descr         = $ed268_c_descr;
    $clturmaac->ed268_i_turno         = $ed268_i_turno;
    $clturmaac->ed268_i_sala          = $ed268_i_sala;
    $clturmaac->ed268_i_numvagas      = $ed268_i_numvagas;
    $clturmaac->ed268_i_nummatr       = 0;
    $clturmaac->ed268_t_obs           = $ed268_t_obs;
    $clturmaac->ed268_i_tipoatend     = $ed268_i_tipoatend;
    $clturmaac->ed268_i_ativqtd       = $ed268_i_ativqtd;
    $clturmaac->ed268_c_aee           = $ed268_c_aee;
    $clturmaac->incluir(null);   
    $turmanova .= $sepnovo.$clturmaac->ed268_i_codigo;
    $sepnovo    = ","; 
    
   
    $result1   = $clturmaacativ->sql_record($clturmaacativ->sql_query("",
                                                                      "*",
                                                                      "",
                                                                      "ed267_i_turmaac = $codigo"
                                                                     )
                                           );
    $turmanov     = $clturmaac->ed268_i_codigo;
    $linhasturmaacativ = $clturmaacativ->numrows;                                           
    for ($f = 0; $f < $linhasturmaacativ; $f++) {
    	
	  db_fieldsmemory($result1,$f);
      $clturmaacativ->ed267_i_turmaac          = $turmanov;
      $clturmaacativ->ed267_i_censoativcompl   = $ed267_i_censoativcompl;     
      $clturmaacativ->incluir(null);
      
    }     
    

      $clturmalogac->ed288_i_turmaac    = $turmanov;
      $clturmalogac->ed288_i_usuario    = $usuario;
      $clturmalogac->ed288_d_data       = $hoje;
      $clturmalogac->ed288_c_hora       = db_hora();
      $clturmalogac->ed288_i_escola     = $escola;
      $clturmalogac->ed288_i_tipoturma  = 2;
      $clturmalogac->ed288_i_codigoant  = $ed268_i_codigo;       
      $clturmalogac->incluir(null);
       

  }  
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<SCRIPT LANGUAGE="JavaScript">
 team = new Array(
 <?
 # Seleciona todos os calendários
 $sql_result = $clcalendario->sql_record($clcalendario->sql_query_calescola("",
                                                                            "ed52_i_codigo,ed52_c_descr",
                                                                            "ed52_i_ano DESC",
                                                                            "ed38_i_escola = $escola  
                                                                              AND ed52_c_passivo = 'N'"
                                                                           )
                                        );       
 $row        = $clcalendario->numrows;
 $num        = pg_num_rows($sql_result);
 $conta      = "";
 while ($row = pg_fetch_array($sql_result)) {
   $conta     = $conta+1;
   $cod_curso = $row["ed52_i_codigo"];
   echo "new Array(\n";   
   $sub_sql = $clcalendario->sql_record($clcalendario->sql_query_calescola("",
                                                                           "ed52_i_codigo,ed52_c_descr",
                                                                           "ed52_i_ano DESC",
                                                                           "ed38_i_escola = $escola 
                                                                            AND ed52_i_calendant= $cod_curso "
                                                                          )
                                       );       
   $num_sub = $clcalendario->numrows;
   if ($num_sub >= 1) {
     # Se achar alguma base para o curso, marca a palavra Todas
     echo "new Array(\"\", ''),\n";
     $conta_sub = "";
     while ($rowx = pg_fetch_array($sub_sql)) {
       $codigo_base = $rowx["ed52_i_codigo"];
       $base_nome   = $rowx["ed52_c_descr"];
       $conta_sub   = $conta_sub+1;
       if ($conta_sub == $num_sub) {
         echo "new Array(\"$base_nome \", $codigo_base)\n";
         $conta_sub = "";
       } else {
         echo "new Array(\"$base_nome \", $codigo_base),\n";
       }
     }
   } else {
     #Se nao achar base para o curso selecionado...
     echo "new Array(\"Calendário sem turmas cadastradas\", '')\n";
   }
   if ($num > $conta) {
     echo "),\n";
   }
}
echo ")\n";
echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
  var i, j;
  var prompt;
 // empty existing items
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
    // add new items
    for (i = 0; i < itemArray.length; i++){
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null){
        selectCtrl.options[j].value = itemArray[i][1];
      }
      j++;
    }
    selectCtrl.options[0].selected = true;
    document.form1.subgrupo.disabled = false;
  }
  document.form1.procurar.disabled = true;
<?if (isset($calendario1)) {?>
    qtd = document.form1.alunosdiario.length;
    for (i = 0; i < qtd; i++) {
      document.form1.alunosdiario.options[0] = null;
    }
    qtd = document.form1.alunos.length;
    for (i = 0; i < qtd; i++) {
      document.form1.alunos.options[0] = null;
    }
<?}?>
}

function fillSelectFromArray2(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
  var i, j;
  var prompt;
 // empty existing items
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
    // add new items
    for (i = 0; i < itemArray.length; i++){
      selectCtrl.options[j] = new Option(itemArray[i][0]);
      if (itemArray[i][1] != null){
        selectCtrl.options[j].value = itemArray[i][1];
      }
    <?if (isset($calendario1)) {?>
        if (<?=trim($calendario1)?> == itemArray[i][1]) {
          indice = i;
        }
    <?}?>
      j++;
    }
  <?if (isset($calendario1)) {?>
      selectCtrl.options[indice].selected = true;
      document.form1.procurar.disabled = false;
  <?} else {?>
      selectCtrl.options[0].selected = true;
  <?}?>
    document.form1.subgrupo.disabled = false;
  }
}
//End -->
</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<center>
<br>
<fieldset style="width:95%"><legend><b>Remanejamento de Turmas Atividade Complementar/AEE</b></legend>
<table border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    </tr>
     <td>
      <b>Calendário Origem:</b><br>
      <select name="grupo" onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $result = $clcalendario->sql_record($clcalendario->sql_query_calescola("",
                                                                              "ed52_i_codigo,ed52_c_descr",
                                                                              "ed52_i_ano DESC",
                                                                              "ed38_i_escola = $escola  
                                                                                AND ed52_c_passivo = 'N'"
                                                                             )
                                          );       
       $row    = $clcalendario->numrows;
       while($row = pg_fetch_array($result)) {
         $cod_curso=$row["ed52_i_codigo"];
         $desc_curso=$row["ed52_c_descr"];
        ?>
         <option value="<?=$cod_curso;?>" <?=$cod_curso==@$calendario?"selected":""?>><?=$desc_curso;?></option>
        <?
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td>
      <b>Calendário Destino:</b><br>
      <select name="subgrupo" style="font-size:9px;width:200px;height:18px;" disabled onchange="js_botao(this.value);">
       <option value=""></option>
      </select>
     </td>
     <td valign='bottom'>
      <input type="button" name="procurar" value="Procurar" 
             onclick="js_procurar(document.form1.grupo.value,document.form1.subgrupo.value)" disabled>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <?if (isset($calendario1)) {?>
     <script>fillSelectFromArray2(document.form1.subgrupo, ((document.form1.grupo.selectedIndex == -1) ? null : team[document.form1.grupo.selectedIndex-1]));</script>
     <tr>
      <td valign="top">
       <?
       $sql    = "SELECT ed268_i_codigo,ed268_c_descr ";
       $sql .= " from turmaac ";
       $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
       $sql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
       $sql .= "      inner join sala  on  sala.ed16_i_codigo = turmaac.ed268_i_sala";
       $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
       $sql .= "      inner join tiposala  on  tiposala.ed14_i_codigo = sala.ed16_i_tiposala";
       $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
       $sql   .= " where ed268_i_escola = $escola and ed52_i_codigo = $calendario  ";
       $sql   .= " and ed268_i_codigo not in ";
       $sql   .= "(select ed288_i_codigoant from turmalogac ";
       $sql   .= "        inner join turmaac  on  turmaac.ed268_i_codigo = turmalogac.ed288_i_turmaac ";
       $sql   .= "        inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario ";
       $sql   .= "        where ed288_i_tipoturma = 2 and ed268_i_calendario = $calendario1)  order by ed268_c_descr";
        //die($sql);
        $result = pg_query($sql);
        $linhas = pg_num_rows($result);
       ?>
       <b>Turmas:</b><br>
       <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()" 
               style="font-size:9px;width:300px;height:120px" multiple>
       <?
        for ($i = 0; $i < $linhas; $i++) {
          db_fieldsmemory($result,$i);
          echo "<option value='$ed268_i_codigo'>$ed268_c_descr</option>\n";
        }
       ?>
       </select>
      </td>
       <td align="center">
        <br>
         <table border="0">
       <tr>
        <td>
         <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_incluir();" 
                style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                       font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
        </td>
       </tr>
       <tr><td height="1"></td></tr>
       <tr>
        <td>
         <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();" 
                style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                       font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" <?=$linhas==0?"disabled":""?>>
        </td>
       </tr>
       <tr><td height="3"></td></tr>
       <tr>
        <td>
         <hr>
        </td>
       </tr>
       <tr><td height="3"></td></tr>
       <tr>
        <td>
         <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();" 
                style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                       font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
        </td>
       </tr>
       <tr><td height="1"></td></tr>
       <tr>
        <td>
         <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();" 
                style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                       font-size:12px;font-weight:bold;width:30px;height:15px;padding:0px;" disabled>
        </td>
       </tr>
      </table>
       </td>
       <td valign="top">
        <b>Turmas Remanejadas:</b><br>
        <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()" 
                style="font-size:9px;width:300px;height:120px" multiple>        
        </select>
       </td>
       <td valign="top">
        <b>Turmas Incluídas:</b><br>
         <?
          $sql1    = " SELECT ed268_i_codigo as codigo1,ed268_c_descr as descr1";
          $sql1   .= " from turmaac ";
          $sql1   .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";        
          $sql1   .= "      left join turmalogac on turmalogac.ed288_i_turmaac = turmaac.ed268_i_codigo";
          $sql1   .= " where ed52_i_codigo = $calendario1 order by descr1";    
   
          $result1 = pg_query($sql1);
          $linhas1 = pg_num_rows($result1);
         ?>
        <select name="turmasincluidas" id="turmasincluidas" size="10" onclick="js_desabexc()" 
                style="font-size:9px;width:300px;height:120px" multiple>
         <?
          for ($i = 0; $i < $linhas1; $i++) {
            db_fieldsmemory($result1,$i);
            echo "<option disabled value='$codigo1'>$descr1</option>\n";
          }
         ?>
        </select>
       </td>
      </tr>
     </tr>
    <tr>
     <td align="center" colspan="3">
      <input name="processar" type="submit" id="processar" value="Processar" 
             onClick="return js_selecionar();" disabled>
      <br><br>
      <fieldset style="width:250;align:center">
        Para selecionar mais de uma turma<br>mantenha pressionada a tecla CTRL <br>e clique sobre o nome da turma.
      </fieldset>
      <input type="hidden" name="base" value="<?=$base?>">
      <input type="hidden" name="curso" value="<?=$curso?>">
     </td>
    </tr>
 <?}?>
</table>
</fieldset>
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),
  db_getsession("DB_modulo"),
  db_getsession("DB_anousu"),
  db_getsession("DB_instit"));
?>
</body>
</html>
<script>
<?if(isset($processar)){?>

	  js_OpenJanelaIframe('','db_iframe_remanejar',
			              'func_remanejarturmaac.php?turma=<?=$turmanova?>&calendario=<?=@$calendario1?>',
			              'Turmas',true);
	  location.href = "#topo";  
	  
<?}?>

function js_incluir() {
	
  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;
  for (x = 0; x < Tam; x++) {
	  
    if (F.alunosdiario.options[x].selected == true) {
        
      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[x].text,F.alunosdiario.options[x].value)
      F.alunosdiario.options[x] = null;
      Tam--;
      x--;
    }
  }
  
  if (document.form1.alunosdiario.length > 0) {
    document.form1.alunosdiario.options[0].selected = true;
  } else {
	  
    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;
    
  }
  
  document.form1.processar.disabled    = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunosdiario.focus();
}

function js_incluirtodos() {
	
  var Tam = document.form1.alunosdiario.length;
  var F   = document.form1;
  for (i = 0; i < Tam; i++) {
	  
    F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,F.alunosdiario.options[0].value);
    F.alunosdiario.options[0] = null;
    
  }
  
  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.processar.disabled    = false;
  document.form1.alunos.focus();
}

function js_excluir() {

  var F = document.getElementById("alunos");
  Tam   = F.length;
  for (x = 0; x < Tam; x++) {
		  
	if (F.options[x].selected == true) {
	        
	  document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
	  F.options[x] = null;
	  Tam--;
	  x--;
	}
	 
  }	
  
  if (F.length == 0) {
	  
    document.form1.processar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
    
  }
  document.form1.alunos.focus();
}

function js_excluirtodos() {
	
  var Tam = document.form1.alunos.length;
  var F   = document.getElementById("alunos");  

  for (i = 0; i < Tam; i++) {

	document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,F.options[0].value);
	F.options[0] = null;

  }
  
  if (F.length == 0) {
	   
    document.form1.processar.disabled    = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
    
  }
  document.form1.alunosdiario.focus();
}

function js_selecionar() {
	
  var F = document.getElementById("alunos").options;  	 
  for (var i = 0;i < F.length; i++) {
	F[i].selected = true;    
  } 
  return true;
}

function js_desabinc() {
	
  for (i = 0; i < document.form1.alunosdiario.length; i++) {
	  
    if (document.form1.alunosdiario.length > 0 && document.form1.alunosdiario.options[i].selected) {
        
      if (document.form1.alunos.length > 0) {
        document.form1.alunos.options[0].selected = false;
      }
      
      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
    }
  }
}

function js_desabexc() {

  for (i = 0; i < document.form1.alunos.length; i++) {
    if (document.form1.alunos.length > 0 && document.form1.alunos.options[i].selected) {
      if (document.form1.alunosdiario.length > 0) {
        document.form1.alunosdiario.options[0].selected = false;
      }
      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;
    }
  }
}

function js_botao(valor){
	
  if (valor != "") {
    document.form1.procurar.disabled = false;
  } else {
    document.form1.procurar.disabled = true;
  }
<?if (isset($calendario1)) {?>

    qtd = document.form1.alunosdiario.length;
    for (i = 0; i < qtd; i++) {
      document.form1.alunosdiario.options[0] = null;
    }
    
    qtd = document.form1.alunos.length;
    for (i = 0; i < qtd; i++) {
      document.form1.alunos.options[0] = null;
    }
<?}?>
}

function js_procurar(calendario,calendario1) {
  location.href = "edu1_remanejarturmaac001.php?calendario="+calendario+"&calendario1="+calendario1;
}

function js_pesquisa(turma) {
  F           = document.form1.alunos;
  disciplinas = "";
  sep         = "";
  for (i = 0; i < F.length; i++) {
    disciplinas += sep+F.options[i].value;
    sep          = ",";
  } 
}



<?if (!isset($calendario1) && pg_num_rows($sql_result) > 0) {?>
    fillSelectFromArray2(document.form1.subgrupo,team[0]);
    document.form1.grupo.options[1].selected = true;
<?}?>

</script>