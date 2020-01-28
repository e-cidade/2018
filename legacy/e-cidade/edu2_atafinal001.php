<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_edu_relatmodel_classe.php");
require_once("libs/db_utils.php");
$escola           = db_getsession("DB_coddepto");
$clEduRelatmodel  = new cl_edu_relatmodel();
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
 # Seleciona todos os calendários cadastrados
 $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
 $sql       .= "       FROM calendario ";
 $sql       .= "       inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
 $sql       .= "      WHERE ed38_i_escola = $escola ";
 $sql       .= "      AND ed52_c_passivo = 'N' ";
 $sql       .= "       ORDER BY ed52_i_ano DESC";
 $sql_result = pg_query($sql);
 $num        = pg_num_rows($sql_result);
 $conta      = "";
 while ($row = pg_fetch_array($sql_result)) {
    
   $conta     = $conta+1;
   $cod_curso = $row["ed52_i_codigo"];
   echo "new Array(\n";
   $sub_sql    = " SELECT DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr ";
   $sub_sql   .= "          FROM turma ";
   $sub_sql   .= "           inner join matricula on ed60_i_turma = ed57_i_codigo ";
   $sub_sql   .= "           inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo ";
   $sub_sql   .= "           inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat ";
   $sub_sql   .= "           inner join serie on ed11_i_codigo = ed223_i_serie ";
   $sub_sql   .= "           inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
   $sub_sql   .= "                                     and ed221_i_serie = ed223_i_serie ";
   $sub_sql   .= "           inner join regencia on ed59_i_turma = ed57_i_codigo ";
   $sub_sql   .= "                               and ed59_i_serie = ed223_i_serie ";
   $sub_sql   .= "          WHERE ed57_i_calendario = '$cod_curso' ";
   $sub_sql   .= "          AND ed57_i_escola = $escola ";
   $sub_sql   .= "          AND ed221_c_origem = 'S' ";
   $sub_sql   .= "          AND ed59_c_encerrada = 'S' ";
   $sub_sql   .= "          ORDER BY ed57_c_descr,ed11_c_descr ";
   $sub_result = pg_query($sub_sql);
   $num_sub    = pg_num_rows($sub_result);
   if ($num_sub >= 1){
    
    # Se achar alguma base para o curso, marca a palavra Todas
     $conta_sub = "";
     while ($rowx = pg_fetch_array($sub_result)) {
        
       $codigo_base=$rowx["ed220_i_codigo"];
       $base_nome=$rowx["ed57_c_descr"];
       $serie_nome=$rowx["ed11_c_descr"];
       $conta_sub=$conta_sub+1;
       if ($conta_sub==$num_sub){
        
         echo "new Array(\"$base_nome - $serie_nome\", $codigo_base)\n";
         $conta_sub = "";
         
       } else {
         echo "new Array(\"$base_nome - $serie_nome\", $codigo_base),\n";
       }
     }
   } else {
   #Se nao achar base para o curso selecionado...
     echo "new Array(\"Calendário sem turmas encerradas.\", '')\n";
   }
   if ($num > $conta){
     echo "),\n";
   }
}
echo ")\n";
echo ");\n";
?>
//Inicio da função JS
function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem){
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
 }else{
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
  document.form1.subgrupo.disabled = false;
 }
 document.form1.pesquisar.disabled = true;
 <?if (isset($base)) {?>
 
     qtd = document.form1.alunosdiario.length;
     for (i = 0; i < qtd; i++) {
       document.form1.alunosdiario.options[0] = null;
     }
     
 <?}?>
}
//End -->
</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post">
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td valign="top">
   <br>
   <fieldset style="width:95%"><legend><b>Relatório Ata de Resultados Finais</b></legend>
   <table border="0" align="left">
    <tr>
     <td>
      <b>Selecione o Calendário:</b><br>
      <select name="grupo" 
              onChange="fillSelectFromArray(this.form.subgrupo, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));" 
              style="font-size:9px;width:200px;height:18px;">
       <option></option>
       <?
       #Seleciona todos os grupos para setar os valores no combo
       $sql        = " SELECT ed52_i_codigo,ed52_c_descr ";
       $sql       .= "       FROM calendario ";
       $sql       .= "        inner join calendarioescola on ed38_i_calendario = ed52_i_codigo ";
       $sql       .= "       WHERE ed38_i_escola = $escola ";
       $sql       .= "       AND ed52_c_passivo = 'N' ";
       $sql       .= "       ORDER BY ed52_i_ano DESC";
       $sql_result = pg_query($sql);
       while ($row = pg_fetch_array($sql_result)) {
        
         $cod_curso=$row["ed52_i_codigo"];
         $desc_curso=$row["ed52_c_descr"];
        ?>
         <option value="<?=$cod_curso;?>" <?=$cod_curso==@$curso?"selected":""?>><?=$desc_curso;?></option>
        <?
       }
       #Popula o segundo combo de acordo com a escolha no primeiro
       ?>
      </select>
     </td>
     <td>
     </td>
    </tr>
    <tr> 
     <td>
      <b>Selecione as Turmas:</b><br>
      <select name="subgrupo" id="subgrupo" size="20"  multiple style="font-size:9px;width:400px;" disabled 
              onchange="js_botao(this.value);">
      </select>
     </td>
     <td align="center" valign="top" >
      <br>
      <fieldset style="width:250;align:center">
       Para selecionar mais de uma turma<br>mantenha pressionada a tecla CTRL <br>e clique sobre o nome das turmas.
      </fieldset>
      <fieldset style="align:left">
       <table>
        <tr>
         <td>
          MODELO 1 -> Sem assinatura dos regentes
         </td>
        </tr>
        <tr>
         <td>
          MODELO 2 -> Com assinatura dos regentes
         </td>
        </tr> 
        <tr>
         <td>
          MODELO 3 -> Com Cabeçalho configurado pelo cliente e sem assinatura dos regentes
         </td>
        </tr>  
        <tr>
         <td>
          MODELO 4 -> Com Cabeçalho configurado pelo cliente e com assinatura dos regentes
         </td>
        </tr>       
       </table>
      </fieldset>    
     </td>
    </tr>
    <tr colspan="2">
     <td>
      <input name="transfer" type="checkbox" value=""> <b>Mostrar informações de Troca de Turma nas observações da ata.</b>
     </td>
    </tr>
    <tr>
     <td valign="top" colspan="2">
      <b>Modelo:</b>
      <select name="modelo" id="modelo" style="font-size:9px;" onChange="js_mudautilizacao(this.value);">
      <option value=''></option>
       <option value='1'>MODELO 1</option>
       <option value='2'>MODELO 2</option>
       <option value='3'>MODELO 3</option>
       <option value='4'>MODELO 4</option>
      </select>
     </td>
    </tr>
    <tr>
     <td nowrap>
     <b>Tipo do Modelo:</b>
      <?
      $sSqlDadosEduRelModel = $clEduRelatmodel->sql_query("",
                                                           "ed217_i_codigo,ed217_c_nome",
                                                           "ed217_c_nome",
                                                           " ed217_i_relatorio = 3"
                                                          );
      $rsDadosEduRelModel   = $clEduRelatmodel->sql_record($sSqlDadosEduRelModel); 
           
      ?>
      <select name="ed217_i_codigo" id= "ed217_i_codigo" style="font-size:9px;" >
      <option value=''></option>
      <option value='m1'>Modelo Padrão</option>
       <?if ($clEduRelatmodel->numrows == 0) {?>
           <option value=''>Nenhum modelo cadastrado</option>
       <?} else {?>
           <? 
           for ($x = 0; $x < $clEduRelatmodel->numrows; $x++) {
            
             $oDadosRelatModel  = db_utils::fieldsMemory($rsDadosEduRelModel,$x);
             $iCodigo           = $oDadosRelatModel->ed217_i_codigo;
             $sNome             = $oDadosRelatModel->ed217_c_nome;                   
             echo "<option value='$iCodigo'>$sNome</option>";               
           }
           ?>
       <?}?>
      </select>
     </td>
    </tr>
     <tr>
     <td nowrap>
     <b>Brasão:</b>      
      <select name="brasao" id= "brasao" style="font-size:9px;">
      <option value='b1'>Sim</option>
      <option value='b2'>Não</option>       
      </select>
     </td>
    </tr>
        <tr>
     <td>
      <b>Diretor:</b>
      <?
        
        $oDaoEscolaDiretor = db_utils::getdao('escoladiretor');
        
        $sCamposDiretor    = " 'DIRETOR' as funcao, ";
  	    $sCamposDiretor   .= "          case when ed20_i_tiposervidor = 1 then ";
  	    $sCamposDiretor   .= "                  cgmrh.z01_nome ";
  	    $sCamposDiretor   .= "               else cgmcgm.z01_nome ";
  	    $sCamposDiretor   .= "            end as nome,";
  	    $sCamposDiretor   .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'D' as tipo";
  	    $sWhereDiretor     = " ed254_i_escola = ".$escola." AND ed254_c_tipo = 'A' AND ed01_i_funcaoadmin = 2 ";
  	    $sSqlDiretor       = $oDaoEscolaDiretor->sql_query_resultadofinal("", $sCamposDiretor, "", $sWhereDiretor);
  	    $rsDiretor         = $oDaoEscolaDiretor->sql_record($sSqlDiretor);
  	    $iLinhasDiretor    = $oDaoEscolaDiretor->numrows;
      
      ?>
      <select name="diretor" style="font-size:9px;width:330px;">
      <option value="">Selecione o Diretor(a)</option>
      <?
      for ($iCont = 0; $iCont < $iLinhasDiretor; $iCont++) {
        
        $oDadosDiretor = db_utils::fieldsmemory($rsDiretor, $iCont);
        
        $sValor     = $oDadosDiretor->funcao."|".$oDadosDiretor->nome."|".$oDadosDiretor->descricao;
        $sTexto     = $oDadosDiretor->funcao." - ".$oDadosDiretor->nome;
        $sTexto    .= ($oDadosDiretor->descricao != "" ? " ($oDadosDiretor->descricao)" : "");
        echo "<option value='$sValor'>$sTexto</option>";
        
      }
      ?>
      </select>
     </td>
    </tr>
    <tr>
     <td>
      <b>Secretário:</b>
      <?
      
        $oDaoRechumanoAtiv = db_utils::getdao('rechumanoativ');
        
        $sCamposSec        = " DISTINCT ed01_c_descr as funcao, ";
  	    $sCamposSec       .= "          case when ed20_i_tiposervidor = 1 then ";
  	    $sCamposSec       .= "                  cgmrh.z01_nome ";
  	    $sCamposSec       .= "               else cgmcgm.z01_nome ";
  	    $sCamposSec       .= "            end as nome,";
  	    $sCamposSec       .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'O' as tipo";
  	    $sWhereSec         = " ed75_i_escola = ".$escola." AND ed01_i_funcaoadmin = 3 ";
  	    $sSqlSec           = $oDaoRechumanoAtiv->sql_query_resultadofinal("", $sCamposSec, "", $sWhereSec);
  	    $rsSecretario      = $oDaoRechumanoAtiv->sql_record($sSqlSec);
  	    $iLinhasSecretario = $oDaoRechumanoAtiv->numrows;  
      
      ?>
      <select name="secretario" style="font-size:9px;width:330px;">
      <option value="">Selecione o Secretário(a)</option>
      <?
      for ($iCont = 0; $iCont < $iLinhasSecretario; $iCont++) {
        
        $oDadosSec  = db_utils::fieldsmemory($rsSecretario, $iCont);
        
        $sValor     = $oDadosSec->funcao."|".$oDadosSec->nome."|".$oDadosSec->descricao;
        $sTexto     = $oDadosSec->funcao." - ".$oDadosSec->nome;
        $sTexto    .= ($oDadosSec->descricao != "" ? " ($oDadosSec->descricao)" : "");
        echo "<option value='$sValor'>$sTexto</option>";
        
      }
      ?>
      </select>
     </td>
    </tr>
    <tr>
     <td>
      <input name="pesquisar" type="button" id="pesquisar" value="Processar" 
             onclick="js_pesquisa(document.form1.grupo.value);" disabled>
     </td>
    </tr>
   </table>
   </fieldset>
  </td>
 </tr>
</table>
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
function js_mudautilizacao(valor) {
	
  document.form1.ed217_i_codigo.value="";	
  if (valor == 1 || valor == 2) {
	document.form1.brasao.disabled = true;     
    for (i = 0; i < document.form1.ed217_i_codigo.length; i++) {
        	
	  if (i > 1) { 
	    document.form1.ed217_i_codigo[i].disabled = true;	  
	  } else {
        document.form1.ed217_i_codigo[i].disabled = false;
      }
    }
    
  } else if (valor == 3 || valor == 4) {
	document.form1.brasao.disabled = false;
    for (i = 0; i < document.form1.ed217_i_codigo.length; i++) {
        
	  if (i > 1) {
		document.form1.ed217_i_codigo[i].disabled = false;		 
	  } else {
		document.form1.ed217_i_codigo[i].disabled = true;
	  }	
	}
	
  } else {
	document.form1.brasao.disabled = false;
	for (i = 0; i < document.form1.ed217_i_codigo.length; i++) {
      document.form1.ed217_i_codigo[i].disabled = false;
    }
  }
  
}
function js_pesquisa(curso) {
    
  turmas = "";
  sep = "";
  for (i = 0; i < document.form1.subgrupo.length; i++) {
      
    if (document.form1.subgrupo.options[i].selected == true) {
        
      turmas += sep+document.form1.subgrupo.options[i].value;
      sep = ",";
      
    }
  }
  if (document.form1.transfer.checked == true) {
    transfer = "yes";   
  } else {
    transfer = "no";        
  }
  if (document.form1.ed217_i_codigo.value == "") {
      
    alert("Informe o Tipo do Modelo!");
    return false;
    
 }
 if (document.form1.modelo.value == 1) {
      
   jan = window.open('edu2_atafinal002.php?transfer='+transfer+'&turmas='+turmas+
                     '&tipovar='+document.form1.ed217_i_codigo.value+'&brasao='+document.form1.brasao.value+
                     '&diretor='+document.form1.diretor.value+
                     '&secretario='+document.form1.secretario.value,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);  ///sem assinatura do regente
   
 } else if (document.form1.modelo.value == 2) {
     
   jan = window.open('edu2_atafinal003.php?transfer='+transfer+'&turmas='+turmas+
                     '&tipovar='+document.form1.ed217_i_codigo.value+'&brasao='+document.form1.brasao.value+
                     '&diretor='+document.form1.diretor.value+
                     '&secretario='+document.form1.secretario.value,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
   
 } else if (document.form1.modelo.value == 3) {
     
   jan = window.open('edu2_atafinal004.php?transfer='+transfer+'&turmas='+turmas+
                     '&tipovar='+document.form1.ed217_i_codigo.value+'&brasao='+document.form1.brasao.value+
                     '&diretor='+document.form1.diretor.value+
                     '&secretario='+document.form1.secretario.value,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0); ///sem assinatura do regente
   
 } else {
     
   jan = window.open('edu2_atafinal005.php?transfer='+transfer+'&turmas='+turmas+
                     '&tipovar='+document.form1.ed217_i_codigo.value+'&brasao='+document.form1.brasao.value+
                     '&diretor='+document.form1.diretor.value+
                     '&secretario='+document.form1.secretario.value,'',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
   
 }
}

function js_botao(valor) {
    
  if (valor != "") {
    document.form1.pesquisar.disabled = false;
  } else {
    document.form1.pesquisar.disabled = true;
  }
}

<?if (!isset($base) && pg_num_rows($sql_result) > 0) {?>

    fillSelectFromArray(document.form1.subgrupo,team[0]);
    document.form1.grupo.options[1].selected = true;
    
<?}?>
</script>