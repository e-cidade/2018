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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_transfescolarede_classe.php");
include("classes/db_transfescolafora_classe.php");
include("classes/db_escoladiretor_classe.php");
include("classes/db_obstransferencia_classe.php");
include("libs/db_utils.php");

$oRotulo = new rotulocampo;
$oRotulo->label("ed283_t_mensagem");

$cltransfescolarede = new cl_transfescolarede;
$cltransfescolafora = new cl_transfescolafora;
$clescoladiretor    = new cl_escoladiretor;
$clobstransferencia = new cl_obstransferencia;
$escola             = db_getsession("DB_coddepto");
$resultedu          = eduparametros(db_getsession("DB_coddepto"));

if (isset($incluirobs)) {
  $resultobs = $clobstransferencia->sql_record($clobstransferencia->sql_query("", 
                                                                              "ed283_i_codigo",
                                                                              "",
                                                                              " ed283_i_escola = $escola"
                                                                             )
                                              );
  if ($clobstransferencia->numrows > 0) {
  	
    db_fieldsmemory($resultobs,0);
    db_inicio_transacao();
    $clobstransferencia->ed283_i_escola       = $escola;
    $clobstransferencia->ed283_t_mensagem     = $ed283_t_mensagem;
    $clobstransferencia->ed283_c_bolsafamilia = $ed283_c_bolsafamilia;
    $clobstransferencia->ed283_i_codigo       = $ed283_i_codigo;
    $clobstransferencia->alterar($ed283_i_codigo);
    db_fim_transacao();
    
  } else {
  	 	
    if ($ed283_t_mensagem != "") {
    	  	
      db_inicio_transacao();
      $clobstransferencia->ed283_i_escola       = $escola;
      $clobstransferencia->ed283_c_bolsafamilia = $ed283_c_bolsafamilia;
      $clobstransferencia->ed283_t_mensagem     = $ed283_t_mensagem;
      $clobstransferencia->incluir(null);      
      db_fim_transacao();
      
    }
  }
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<center>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<br>
<fieldset style="width:95%"><legend><b>Guia de Transferência</b></legend>
<table border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">
    </tr>
     <td>
      <b>Ano:</b><br>
      <?
      $sql        = " SELECT DISTINCT extract(year from ed103_d_data) as anotransf ";
      $sql       .= "       FROM transfescolarede ";
      $sql       .= "       WHERE ed103_i_escolaorigem = $escola ";
      $sql       .= "       UNION ";
      $sql       .= "       SELECT DISTINCT extract(year from ed104_d_data) as anotransf ";
      $sql       .= "       FROM transfescolafora ";
      $sql       .= "       WHERE ed104_i_escolaorigem = $escola ";
      $sql       .= "       ORDER BY anotransf DESC"; 
      $sql_result = db_query($sql);
      ?>
      <select name="ano" style="font-size:9px;width:200px;height:18px;" onchange="js_limpaalunos();">
       <option></option>
       <?
       while ($row=pg_fetch_array($sql_result)) {
       	
         $anotransf=$row["anotransf"];
        ?>        
        <option value="<?=$anotransf?>" <?=$anotransf==@$ano?"selected":""?> ><?=$anotransf?></option>
        <?
        
       }
       ?>
      </select>
     </td>
     <td>
      <b>Tipo:</b><br>
      <select name="tipo" style="font-size:9px;width:200px;height:18px;" onchange="js_limpaalunos();">
       <option value=""></option>
       <option value="TR" <?=@$tipo=="TR"?"selected":""?>>TRANSFERÊNCIAS REDE</option>
       <option value="TF" <?=@$tipo=="TF"?"selected":""?>>TRANSFERÊNCIAS FORA</option>
      </select>
     </td>
     <td valign='bottom'>
      <input type="button" name="procurar" value="Procurar" onclick="js_procurar(document.form1.ano.value,document.form1.tipo.value);">
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <?if (isset($ano)) {
 	
  ?>
  <tr>
  <td valign="top">
   <?
     if ($tipo == "TR") {    	   	
       $campos = "ed103_i_codigo as codtransf,ed47_i_codigo,ed47_v_nome,ed103_d_data as datatransf";
       $where  = " ed103_i_escolaorigem = $escola AND extract(year from ed103_d_data) = '$ano'";
       $result = $cltransfescolarede->sql_record($cltransfescolarede->sql_query("",
                                                                                $campos,
                                                                                "to_ascii(ed47_v_nome)",
                                                                                $where
                                                                               )
                                                );
       $linhas = $cltransfescolarede->numrows;
     } elseif($tipo == "TF") {
       $sql    = " SELECT ed104_i_codigo as codtransf,ed47_i_codigo,ed47_v_nome,ed104_d_data as datatransf ";
       $sql   .= "       FROM transfescolafora ";
       $sql   .= "        inner join aluno on aluno.ed47_i_codigo = transfescolafora.ed104_i_aluno ";
       $sql   .= "        inner join matricula on matricula.ed60_i_codigo = transfescolafora.ed104_i_matricula ";
       $sql   .= "        inner join turma on turma.ed57_i_codigo = matricula.ed60_i_turma ";
       $sql   .= "       WHERE ed104_i_escolaorigem = $escola ";
       $sql   .= "       AND extract(year from ed104_d_data) = '$ano' ";            
       $sql   .= "       ORDER BY to_ascii(ed47_v_nome) ";
       $result = db_query($sql);
       $linhas = pg_num_rows($result);
     }
   ?>
   <b>Alunos:</b><br>
   <select name="alunosdiario" id="alunosdiario" size="10" onclick="js_desabinc()" 
           style="font-size:9px;width:430px;height:120px" multiple>
    <?
     for ($i=0; $i<$linhas; $i++) {
     	
       db_fieldsmemory($result,$i);
       echo "<option value='$codtransf'>$ed47_i_codigo - $ed47_v_nome ( Transf. em ".db_formatar($datatransf,'d').")</option>\n";
       
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
   <b>Alunos para gerar guia:</b><br>
   <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()" 
           style="font-size:9px;width:330px;height:120px" multiple>
   </select>
  </td>
 </tr>
 <tr>
  <td colspan="3">
   <b>Emissor:</b>
   <?=Assinatura(db_getsession("DB_coddepto"))?>
  </td>
 </tr>
  <tr>
     <?
   $resultobs = $clobstransferencia->sql_record($clobstransferencia->sql_query("",
                                                                               "ed283_c_bolsafamilia,ed283_t_mensagem",
                                                                               "",
                                                                               " ed283_i_escola = $escola"
                                                                              )
                                               );
   if ($clobstransferencia->numrows>0) {
    
     db_fieldsmemory($resultobs,0);
     $obs = $ed283_t_mensagem;
     
   }
   ?>
  <td colspan="3">
   <b>Bolsa Família:</b>
   <?
     $x = array("1"=>"NÃO","2"=>"SIM");
     db_select('ed283_c_bolsafamilia',$x,true,@$db_opcao,"");
   ?>
  </td>
 </tr>
  <tr>
    <td valign="top" colspan="3">
   <b>Observação Geral:</b><br>
   <?db_textarea('ed283_t_mensagem', 3, 110, $Ied283_t_mensagem, true, 'text', @$db_opcao, "", "", "", 2000)?><br>
  </td>
 </tr>
 <tr>
  <td align="center" colspan="3">
   <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa(document.form1.tipo.value);" disabled>
   <br><br>
   <fieldset style="align:center">
    Para selecionar mais de um aluno<br>mantenha pressionada a tecla CTRL <br>e clique sobre o nome dos alunos.
   </fieldset>
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
          db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_incluir() {
	
  var Tam = document.form1.alunosdiario.length;
  var F = document.form1;
  for (x=0;x<Tam;x++) {
	  
    if (F.alunosdiario.options[x].selected==true) {
        
      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[x].text,F.alunosdiario.options[x].value)
      F.alunosdiario.options[x] = null;
      Tam--;
      x--;
      
    }
  }
  if (document.form1.alunosdiario.length>0) {
    document.form1.alunosdiario.options[0].selected = true;
  } else {
	  
    document.form1.incluirum.disabled = true;
    document.form1.incluirtodos.disabled = true;
    
  }
  document.form1.pesquisar.disabled = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunosdiario.focus();
}

function js_incluirtodos() {
	
  var Tam = document.form1.alunosdiario.length;
  var F = document.form1;
  for (i=0;i<Tam;i++) {
	  
    F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunosdiario.options[0].text,F.alunosdiario.options[0].value)
    F.alunosdiario.options[0] = null;
    
 }
 document.form1.incluirum.disabled = true;
 document.form1.incluirtodos.disabled = true;
 document.form1.excluirtodos.disabled = false;
 document.form1.pesquisar.disabled = false;
 document.form1.alunos.focus();
}

function js_excluir() {
	
  var F = document.getElementById("alunos");
  Tam = F.length;
  for(x=0;x<Tam;x++) {
	  
    if (F.options[x].selected==true) {
        
      document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[x].text,F.options[x].value);
      F.options[x] = null;
      Tam--;
      x--;
      
    }
  }
  if (document.form1.alunos.length>0) {
    document.form1.alunos.options[0].selected = true;
  }
  if (F.length == 0) {
	  
    document.form1.pesquisar.disabled = true;
    document.form1.excluirum.disabled = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
    
  }
  document.form1.alunos.focus();
}

function js_excluirtodos() {
	
  var Tam = document.form1.alunos.length;
  var F = document.getElementById("alunos");
  for(i=0;i<Tam;i++){
	  
    document.form1.alunosdiario.options[document.form1.alunosdiario.length] = new Option(F.options[0].text,F.options[0].value);
    F.options[0] = null;
    
  }
  if (F.length == 0) {
	  
    document.form1.pesquisar.disabled = true;
    document.form1.excluirum.disabled = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;
    
  }
  document.form1.alunosdiario.focus();
}

function js_desabinc() {
	
  for(i=0;i<document.form1.alunosdiario.length;i++){
	  
    if (document.form1.alunosdiario.length>0 && document.form1.alunosdiario.options[i].selected) {
        
      if (document.form1.alunos.length>0) {
        document.form1.alunos.options[0].selected = false;
      }
      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;
      
    }
  }
}

function js_desabexc() {
	
  for (i=0;i<document.form1.alunos.length;i++) {
	  
    if (document.form1.alunos.length>0 && document.form1.alunos.options[i].selected) {
        
      if (document.form1.alunosdiario.length>0){
        document.form1.alunosdiario.options[0].selected = false;
      }
      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;
      
    }
  }
}

function js_procurar(ano,tipo) {
	
 if(ano!="" && tipo!=""){
     location.href = "edu2_guiatransf001.php?ano="+ano+"&tipo="+tipo;  
 } 
}

function js_limpaalunos() {
	
  if (document.form1.alunosdiario){
	  
    qtd = document.form1.alunosdiario.length;
    for (i = 0; i < qtd; i++) {
      document.form1.alunosdiario.options[0] = null;
    }
    qtd = document.form1.alunos.length;
    for (i = 0; i < qtd; i++) {
      document.form1.alunos.options[0] = null;
    }
    document.form1.pesquisar.disabled = true;
  }
}

function js_pesquisa(tipo) {
	
  if (tipo == "") {
    alert("Informe o tipo da transferência!");
  } else {
	  
    F = document.form1.alunos;
    alunos = "";
    sep = "";
    for(i=0;i<F.length;i++){
        
      alunos += sep+F.options[i].value;
      sep = ",";
      
    }
    jan = window.open('edu2_guiatransf002.php?alunos='+alunos+'&tipo='+tipo+'&diretor='+document.form1.diretor.value+'&obs='+document.form1.ed283_t_mensagem.value+'&bolsafamilia='+document.form1.ed283_c_bolsafamilia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
  location.href = "edu2_guiatransf001.php?diretor="+document.form1.diretor.value+"&ed283_c_bolsafamilia="+document.form1.ed283_c_bolsafamilia.value+"&ed283_t_mensagem="+document.form1.ed283_t_mensagem.value+"&incluirobs";
}
<?if (pg_num_rows($sql_result)>0 && !isset($ano)) {?>
    document.form1.ano[1].selected = true;
<?}?>
</script>