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
db_postmemory($HTTP_POST_VARS);
$escola = db_getsession("DB_coddepto");
function db_criatermometro_edu($dbnametermo='termometro',$dbtexto='Conclu�do',$dbcor='blue',$dbborda=1,$dbacao='Aguarde Processando...'){
 //#00#//db_criatermometro
 //#10#//Cria uma barra de progresso no ponto do programa que for chamado
 //#15#//db_criatermometro('termometro','Conclu�do','blue',1);
 //#20#//dbnametermo = Nome do termometro e da funcao js que atualiza o termometro
 //#20#//dbtexto     = Texto mostrado no lado da porcentagem concluida
 //#20#//dbcor       = Cor do termometro
 //#20#//dbborda     = Borda, 1 com borda ou 2 sem borda
 //#20#//dbacao      = Texto para acao executada ex: Aguarde Processando...
 //#99#//Essa fun��o apenas cria o termometro, para atualizar o valor do termometro deve usar a funcao db_atutermometro
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
if(isset($ano_opcao)){
 $titulofieldset = "<font color='red'> -> C�DIGO INEP</font>";
}else{
 $titulofieldset = "";
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
<form name="form1" method="post" action="" enctype="multipart/form-data">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Importa��o de informa��es do CENSO ESCOLAR <?=$titulofieldset?></b></legend>
    <?
    $result = pg_query("SELECT ed18_c_codigoinep FROM escola WHERE ed18_i_codigo = $escola");
    $codigoinep_banco = pg_result($result,0,0);
    ?>
    <table border="0" align="left">
     <tr>
      <td>
       <b>Ano das informa��es do arquivo:</b>
       <select name="ano_opcao" onchange="js_anoopcao(this.value);">
        <option value="2011" <?=@$ano_opcao=="2011"?"selected":""?>>2011</option>
        <option value="2010" <?=@$ano_opcao=="2010"?"selected":""?>>2010</option>
        <option value="2010" <?=@$ano_opcao=="2009"?"selected":""?>>2009</option>
       </select><br>       
       <b>C�digo INEP da Escola:</b> <input type="text" name="codigoinep_banco" value="<?=$codigoinep_banco?>" size="8" readonly style="background:#deb887">
      </td>
     </tr>
     <tr>
      <td>
       <b>Tipo de arquivo:</b>
       <select name="tipo_opcao" onchange="js_tipoopcao(this.value);">
        <option value="0" <?=@$tipo_opcao=="0"?"selected":""?> selected >>> Selecione <<</option>
        <option value="1" <?=@$tipo_opcao=="1"?"selected":""?>>Arquivos de Abertura</option>        
        <option value="2" <?=@$tipo_opcao=="2"?"selected":""?>>Arquivos Situa��o do Aluno</option>                
       </select><br>       
      </td>
     </tr>
     <tr>
      <td>
       <input type="checkbox" name="escolacenso" value="escolacenso" <?=!isset($escolacenso)?"":"checked"?>> <b>Escola</b>
       <input type="checkbox" name="turma" value="turma" <?=!isset($turma)?"":"checked"?>> <b>Turmas</b>
       <input type="checkbox" name="docente" value="docente" <?=!isset($docente)?"":"checked"?>> <b>Docentes</b>
       <input type="checkbox" name="aluno" value="aluno" <?=!isset($aluno)?"":"checked"?>> <b>Alunos</b>
      </td>
     </tr>
     <tr>
      <td>
       <b>Arquivo de importa��o do Censo:</b>
       <?db_input('arquivo_censo',50,@$Iarquivo_censo,true,'file',3,"");?>
       <?db_input('caminho_arquivo',100,@$Icaminho_arquivo,true,'hidden',3,"");?>
      </td>
     </tr>
     <tr>
      <td>
       <table id="table_termo" style="visibility:hidden;">
        <tr>
         <td align="center">
          <?if(isset($processar)){?>
          <script>
           var sHors  = "00";
           var sMins  = "00";
           var sSecs  = "00";
           function getSecs(){
            //verifica o rel�gio
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
            //monta o rel�gio
            document.getElementById('clock1').innerHTML=sHors+":"+sMins+":"+sSecs;
            //mostra o rel�gio
            varTempo = setTimeout('getSecs()',1000);
           }
          </script>
          <b>
           Tempo de execu��o:<br>
           <span id="clock1">00:00:00</span><script>varTempo = setTimeout('getSecs()',1000);</script>
          </b>
          <?}?>
         </td>
         <td>
          <?=db_criatermometro_edu('termometro', 'Concluido...', 'blue', 1);?>
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
   <?
   if(trim($codigoinep_banco)==""){
    echo "<font color=red><b>* C�digo INEP desta escola n�o informado no sistema. Opera��o N�o Permitida.</b></font>
          &nbsp;&nbsp;<a href='edu1_escolaabas002.php'>Informar C�digo INEP</a>
          <br>";
   }
   ?>
   <input name="processar" type="submit" id="processar" value="Processar" onclick="return js_valida()" <?=$codigoinep_banco==""||isset($processar)?"disabled":""?>>
   <input name="recomecar" type="submit" id="recomecar" value="Recome�ar" onclick="location.href='edu4_importarcodigoinep001.php'" style="visibility:hidden;">
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_valida(){
 if(document.form1.tipo_opcao.value=="0"){
  alert("Informe o tipo de arquivo para realizar a importa��o!");
  return false;
 }
 if(document.form1.escolacenso.checked==false && document.form1.turma.checked==false && document.form1.aluno.checked==false && document.form1.docente.checked==false){
  alert("Escolha no m�nimo uma das op��es para realizar a importa��o.\n(Escola, Turmas, Alunos ou Docentes)!");
  return false;
 }
 if(document.form1.arquivo_censo.value==""){
  alert("Informe o arquivo para realizar a importa��o!");
  return false;
 }
 document.form1.caminho_arquivo.value = document.form1.arquivo_censo.value;
 document.getElementById("processar").style.visibility = "hidden";
 document.getElementById("recomecar").style.visibility = "hidden";  
 return true;
}
function js_tipoopcao(valor){
 if(valor==1){
  if(document.form1.ano_opcao.value=="2011" || document.form1.ano_opcao.value=="2010"){
   location.href = "edu4_importarcodigoinep001.php?ano_opcao="+document.form1.ano_opcao.value+"&tipo_opcao="+valor;
  }else{
   location.href = "edu4_importarcodigoinep_2009_001.php?ano_opcao="+document.form1.ano_opcao.value+"&tipo_opcao="+valor;
  }
 }else if (valor==2){
  if(document.form1.ano_opcao.value=="2011" || document.form1.ano_opcao.value=="2010"){
   location.href = "edu4_importarmatriculainep001.php?ano_opcao="+document.form1.ano_opcao.value+"&tipo_opcao="+valor;
  }else{
   location.href = "edu4_importarmatriculainep_2009_001.php?ano_opcao="+document.form1.ano_opcao.value+"&tipo_opcao="+valor;
  }    
 }
}
function js_anoopcao(valor){
 if(document.form1.tipo_opcao.value=="1"){
  if(valor=="2011" || valor=="2010"){
   location.href = "edu4_importarcodigoinep001.php?ano_opcao="+valor+"&tipo_opcao="+document.form1.tipo_opcao.value;  
  }else{
   location.href = "edu4_importarcodigoinep_2009_001.php?ano_opcao="+valor+"&tipo_opcao="+document.form1.tipo_opcao.value;  
  }
 }else{
  if(valor=="2011" || valor=="2010"){
   location.href = "edu4_importarmatriculainep001.php?ano_opcao="+valor+"&tipo_opcao="+document.form1.tipo_opcao.value;
  }else{
   location.href = "edu4_importarmatriculainep_2009_001.php?ano_opcao="+valor+"&tipo_opcao="+document.form1.tipo_opcao.value;
  }    
 }
}
</script>
<?
if(isset($processar)){
  $tmp_name = $_FILES["arquivo_censo"]["tmp_name"];
  $name     = $_FILES["arquivo_censo"]["name"];
  $type     = $_FILES["arquivo_censo"]["type"];
  $size     = $_FILES["arquivo_censo"]["size"];
  if(!@copy($tmp_name,"tmp/".$name)){
   db_msgbox("N�o foi poss�vel efetuar upload. Verifique permiss�o do Diret�rio");
   db_redireciona("edu4_importarcodigoinep001.php?ano_opcao=2011");
   exit;
  }
  $caminho_arquivo = "tmp/".$name;
  $ponteiro3 = fopen($caminho_arquivo,"r");
  $valida_arquivo1 = false;
  $valida_arquivo2 = false;
  $valida_arquivo3 = false;  
  $contador_escola = 0;
  $contador_turma = 0;
  $contador_docente = 0;
  $contador_aluno = 0;
  $contador_geral = 0;
  while(!feof($ponteiro3)){
   $linhaponteiro = fgets($ponteiro3,500);
   if($contador_geral==0 && substr($linhaponteiro,0,2)!="00"){
    $valida_arquivo1 = true;
    break;
   }
   if($contador_geral==0 && substr($linhaponteiro,12,8)!=$codigoinep_banco){
    $valida_arquivo2 = true;
    break;
   }
   if($contador_geral==0 && substr($linhaponteiro,25,4)!=$ano_opcao){
    $valida_arquivo3 = true;
    break;
   }
   if(substr($linhaponteiro,0,2)=="00"){
    $contador_escola++;
   }
   if(substr($linhaponteiro,0,2)=="20"){
    $contador_turma++;
   }
   if(substr($linhaponteiro,0,2)=="30"){
    $contador_docente++;
   }
   if(substr($linhaponteiro,0,2)=="60"){
    $contador_aluno++;
   }
   if(substr($linhaponteiro,0,2)!=""){
    $contador_geral++;
   }
  }
  fclose($ponteiro3);
  if($valida_arquivo2==true){
   db_msgbox("[2] Arquivo informado n�o pertence a esta escola !");
   ?><script>document.form1.processar.disabled = false;</script><?   
   ?><script>document.form1.recomecar.style.visibility = "visible";</script><?   
  }elseif($valida_arquivo3==true){
   db_msgbox("[3] Arquivo informado n�o pertence ao ano de $ano_opcao!");
   ?><script>document.form1.processar.disabled = false;</script><?   
   ?><script>document.form1.recomecar.style.visibility = "visible";</script><?   
  }elseif($valida_arquivo1==true){  	
   db_msgbox("[1] Arquivo informado n�o � um arquivo de exporta��o geral gerado pelo Educacenso!");
   ?><script>document.form1.processar.disabled = false;</script><?   
   ?><script>document.form1.recomecar.style.visibility = "visible";</script><?   
  }else{
   ?>
   <script>document.getElementById("termo").style.visibility = "visible";</script>
   <script>document.getElementById("table_termo").style.visibility = "visible";</script>
   <?
   set_time_limit(0);
   $arquivo_logerro = "tmp/censo_impGeral_".$escola."_".db_getsession("DB_id_usuario")."_".date("dmY")."_".date("His")."_log.txt";
   $ponteiro_log = fopen($arquivo_logerro,"w");
   fwrite($ponteiro_log,"Registros n�o atualizados na importa��o do Censo Escolar:\n\n");
   pg_exec("begin");
   $ponteiro4 = fopen($caminho_arquivo,"r");
   $erro_naoencontrado = false;
   $cont_escola_while = 0;
   $cont_turma_while = 0;
   $cont_docente_while = 0;
   $cont_aluno_while = 0;
   $primeiro_turma = false;
   $primeiro_docente = false;
   $array_docente = array();
   while(!feof($ponteiro4)){
    $linha = str_replace(chr(39)," ",fgets($ponteiro4,500));
    if(trim($linha)==""){
     continue;
    }
    $linha = " ".$linha;
    $tiporegistro = trim(substr($linha,1,2));
    if($tiporegistro==00){
     $datainicial_anoletivo = trim(substr($linha,22,8));
     $anoletivo = substr($datainicial_anoletivo,4,4);
    }
    /////////////////ESCOLA
    if(isset($escolacenso)){
     if($tiporegistro==00){
      db_atutermometro_edu($cont_escola_while, $contador_escola , 'termometro',1,'...Processando Escola');
      $cont_escola_while++;
      $codigoescola = "";
      $codigoinep_escola = trim(substr($linha,13,8));
      $sql44 = "SELECT * FROM escola WHERE ed18_c_codigoinep = '$codigoinep_escola'";
      $result44 = pg_query($sql44);
      if(!$result44){
       die("ERRO ESCOLA: ".$sql44."<br><br>");
      }
      $linhas44 = pg_num_rows($result44);
      $codigoescola = pg_result($result44,0,'ed18_i_codigo');

      $sqlupdateescola = " UPDATE escola SET ed18_i_codigo = $codigoescola ";

      if($codigoinep_escola!="" && $codigoinep_escola!=trim(pg_result($result44,0,'ed18_c_codigoinep'))){
       $sqlupdateescola  .= " ,ed18_c_codigoinep = $codigoinep_escola ";
      }
      $sqlupdateescola .= " WHERE ed18_i_codigo = $codigoescola";
      $resultupdateescola = pg_query($sqlupdateescola);
      if(!$resultupdateescola){
       die("ERRO ESCOLA[1]: ".$sqlupdateescola."<br><br>");
      }
     }
    }
    /////////////////TURMA
    if(isset($turma)){
     if($tiporegistro==20){
      db_atutermometro_edu($cont_turma_while, $contador_turma , 'termometro',1,'...Processando Turmas');
      $cont_turma_while++;
      $codigoinep_turmacenso = trim(substr($linha,21,10));
      $nome_turmacenso = trim(substr($linha,51,80));
      $nome_turmacenso2 = str_replace("�","",$nome_turmacenso);
      $nome_turmacenso2 = str_replace("�","",$nome_turmacenso2);
      $nome_turmacenso2 = str_replace("�","",$nome_turmacenso2);      
      $tipoatend_turmacenso = trim(substr($linha,139,1));
      $modalidade_turmacenso = trim(substr($linha,182,1));
      $etapa_turmacenso = trim(substr($linha,183,2));
      if($tipoatend_turmacenso==0 || $tipoatend_turmacenso==1 || $tipoatend_turmacenso==2 || $tipoatend_turmacenso==3){
       $sqlturma33 = "SELECT DISTINCT ed57_i_codigo
                      FROM turma
                       inner join calendario on ed52_i_codigo = ed57_i_calendario
                       inner join escola on ed18_i_codigo = ed57_i_escola
                       inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo
                       inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                       inner join serie on ed11_i_codigo = ed223_i_serie
                       inner join ensino on ed10_i_codigo = ed11_i_ensino
                      WHERE translate(to_ascii(ed57_c_descr),' a','') = '".str_replace(" ","",$nome_turmacenso2)."'
                      AND ed57_i_tipoatend = $tipoatend_turmacenso
                      AND ed52_i_ano = $anoletivo
                      AND ed10_i_tipoensino = $modalidade_turmacenso
                      AND ed18_c_codigoinep = '$codigoinep_banco'
                     ";
       $resultturma33 = pg_query($sqlturma33);
       if(!$resultturma33){
        die("ERRO TURMA[1]: ".$sqlturma33."<br><br>");
       }
       $linhasturma33 = pg_num_rows($resultturma33);
       if($linhasturma33==0){
        if($primeiro_turma==false){
         fwrite($ponteiro_log,"Turma(s) abaixo relacionada(s), informada(s) no censo $anoletivo, n�o foram encontrada(s) no sistema.(N�o existe ou o nome da turma informado no censo n�o coincide com o nome informado no sistema)\n");
         $primeiro_turma = true;
        }
        fwrite($ponteiro_log,"TURMA: [$codigoinep_turmacenso] $nome_turmacenso\n");
        $erro_naoencontrado = true;
       }else{
        $codigoturma = pg_result($resultturma33,0,0);
        if(trim($codigoinep_turmacenso)!=""){
         $sqlupdate_turma = "UPDATE turma SET
                              ed57_i_codigoinep = $codigoinep_turmacenso
                             WHERE ed57_i_codigo = $codigoturma ";
         $resulupdate_turma = pg_query($sqlupdate_turma);
         if(!$resulupdate_turma){
          die("ERRO TURMA[2]: ".$sqlupdate_turma."<br><br>");
         }        	
        }
       }
      }elseif($tipoatend_turmacenso==4 || $tipoatend_turmacenso==5){
       $sqlturma33 = "SELECT *
                      FROM turmaac
                       inner join calendario on ed52_i_codigo = ed268_i_calendario
                       inner join escola on ed18_i_codigo = ed268_i_escola
                      WHERE translate(to_ascii(ed268_c_descr, 'LATIN1'),' a','') = '".str_replace(" ","",$nome_turmacenso2)."'
                      AND ed268_i_tipoatend = $tipoatend_turmacenso
                      AND ed52_i_ano = $anoletivo
                      AND ed18_c_codigoinep = $codigoinep_banco
                     ";
       $resultturma33 = pg_query($sqlturma33);
       if(!$resultturma33){
        die("ERRO TURMA[3]: ".$sqlturma33."<br><br>");
       }
       $linhasturma33 = pg_num_rows($resultturma33);
       if($linhasturma33==0){
        if($primeiro_turma==false){
         fwrite($ponteiro_log,"Turma(s) abaixo relacionada(s), informada(s) no censo $anoletivo, n�o foram encontrada(s) no sistema.(N�o existe ou o nome da turma informado no censo n�o coincide com o nome informado no sistema)\n");
         $primeiro_turma = true;
        }
        fwrite($ponteiro_log,"TURMA: [$codigoinep_turmacenso] $nome_turmacenso\n");
        $erro_naoencontrado = true;
       }else{
        $codigoturma = pg_result($resultturma33,0,'ed268_i_codigo');
        if(trim($codigoinep_turmacenso)!=""){
          $sqlupdate_turma = "UPDATE turmaac SET ed268_i_codigoinep = $codigoinep_turmacenso";
          $ativqtd_turma = trim(substr($linha,415,1));
          if($ativqtd_turma!="" && $ativqtd_turma!=trim(pg_result($resultturma33,0,'ed268_i_ativqtd'))){
           $sqlupdate_turma .= " ,ed268_i_ativqtd = $ativqtd_turma ";
          }
          $aee_turma = trim(substr($linha,171,11));
          if($aee_turma!="" && $aee_turma!=trim(pg_result($resultturma33,0,'ed268_c_aee'))){
           $sqlupdate_turma .= " ,ed268_c_aee = '$aee_turma' ";
          }
          $sqlupdate_turma .= " WHERE ed268_i_codigo = $codigoturma ";
          $resulupdate_turma = pg_query($sqlupdate_turma);
          if(!$resulupdate_turma){
           die("ERRO TURMA[4]: ".$sqlupdate_turma."<br><br>");
          }
        }
       }
      }
     }
    }
    /////////////////DOCENTE
    if(isset($docente)){
     if($tiporegistro==30){
      db_atutermometro_edu($cont_docente_while, $contador_docente , 'termometro',1,'...Processando Docentes');
      $cont_docente_while++;
      $codigodocente = "";
      $nome_docentecenso = trim(substr($linha,53,100));
      $nome_docentecenso2 = str_replace("�","",$nome_docentecenso);
      $nome_docentecenso2 = str_replace("�","",$nome_docentecenso2);
      $nasc_docentecenso = trim(substr($linha,264,8));
      $nasc_docentecenso = substr($nasc_docentecenso,4,4)."-".substr($nasc_docentecenso,2,2)."-".substr($nasc_docentecenso,0,2);
      $mae_docentecenso =  trim(substr($linha,274,100));
      $mae_docentecenso = str_replace("�","",$mae_docentecenso);
      $mae_docentecenso = str_replace("�","",$mae_docentecenso);
      $sql22 = "SELECT *
                FROM rechumano
                 left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
                 left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal
                 left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm
                 left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo
                 left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm
                 inner join rechumanoescola on ed75_i_rechumano = ed20_i_codigo
                 inner join escola on ed18_i_codigo = ed75_i_escola
                WHERE ed18_c_codigoinep = '$codigoinep_banco'
                AND (
                     ( (to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end, 'LATIN1') = '$nome_docentecenso2'  OR to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nomecomple else cgmcgm.z01_nomecomple end, 'LATIN1') = '$nome_docentecenso2') AND case when ed20_i_tiposervidor = 1 then cgmrh.z01_nasc else cgmcgm.z01_nasc end = '$nasc_docentecenso')
                      OR
                     ( (to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end, 'LATIN1') = '$nome_docentecenso2' OR to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nomecomple else cgmcgm.z01_nomecomple end, 'LATIN1') = '$nome_docentecenso2') AND to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_mae else cgmcgm.z01_mae end, 'LATIN1') = '$mae_docentecenso')
                      OR
                     ( (to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end, 'LATIN1') = '$nome_docentecenso2' OR to_ascii(case when ed20_i_tiposervidor = 1 then cgmrh.z01_nomecomple else cgmcgm.z01_nomecomple end, 'LATIN1') = '$nome_docentecenso2'))
                    )
                ";
      $result22 = pg_query($sql22);
      if(!$result22){
       die("ERRO DOCENTE[1]: ".$sql22."<br><br>");
      }
      $linhas22 = pg_num_rows($result22);
      if($linhas22==0){
       if($primeiro_docente==false){
        fwrite($ponteiro_log,"\nDocente(s) abaixo relacionado(s), informado(s) no censo $anoletivo, n�o foram atualizado(s) no sistema.\n (N�o existe no sistema ou o nome do docente informado no censo n�o coincide com o nome informado no sistema ou o docente n�o est� mais vinculado a esta escola)\n");
        $primeiro_docente = true;
       }
       if(!array_key_exists($nome_docentecenso,$array_docente)){
        $array_docente[$nome_docentecenso] = $nome_docentecenso;
        fwrite($ponteiro_log,"DOCENTE: $nome_docentecenso\n");
       } 
       $erro_naoencontrado = true;
      }else{
       for($tt=0;$tt<$linhas22;$tt++){
        $codigodocente = pg_result($result22,$tt,0);

        $sqlupdatedocente = " update rechumano set ed20_i_codigo = $codigodocente ";

        $ed20_i_codigoinep = trim(substr($linha,21,12));
        if($ed20_i_codigoinep!="" && $ed20_i_codigoinep!=trim(pg_result($result22,0,'ed20_i_codigoinep'))){
         $sqlupdatedocente .= " ,ed20_i_codigoinep = $ed20_i_codigoinep ";
        }
        $sqlupdatedocente .= " WHERE ed20_i_codigo = $codigodocente ";        
        $resultupdatedocente = pg_query($sqlupdatedocente);
        if(!$resultupdatedocente){
         die("ERRO DOCENTE[2]: ".$sqlupdatedocente."<br><br>");
        }
       }
      }
     }
    }
    /////////////////ALUNO
    if(isset($aluno)){
     if($tiporegistro==60){
      db_atutermometro_edu($cont_aluno_while, $contador_aluno , 'termometro',1,'...Processando Alunos');
      $cont_aluno_while++;
      $codigoaluno = "";
      $ed47_c_codigoinep = trim(substr($linha,21,12));      
      $nome_censo  = trim(substr($linha,53,100));
      $nome_censo2 = str_replace("�","",$nome_censo);
      $nome_censo2 = str_replace("�","",$nome_censo2);
      $sql11 = "SELECT aluno.*,escola.ed18_c_codigoinep as vinculo_escola
                FROM aluno
                 inner join alunocurso on ed56_i_aluno = ed47_i_codigo
                 inner join escola on ed18_i_codigo = ed56_i_escola
                WHERE to_ascii(translate(ed47_v_nome,'�`',''),'LATIN1') = '$nome_censo2'
                ";
      $result11 = pg_query($sql11);
      $linhas11 = pg_num_rows($result11);
      if($linhas11==0){
       fwrite($ponteiro_log,"\nAluno [$ed47_c_codigoinep] $nome_censo2: Nome cadastrado no censo n�o existe no sistema.");
       $erro_naoencontrado = true;
      }else{
       $tem_vinculo = false;
       for($rr=0;$rr<$linhas11;$rr++){
        if(pg_result($result11,$rr,'vinculo_escola')==trim($codigoinep_banco)){
         $tem_vinculo = true;
         $vinculo_escola = pg_result($result11,$rr,'vinculo_escola');
         break;  
        }
       }
       if($tem_vinculo==false){
        fwrite($ponteiro_log,"\nAluno [$ed47_c_codigoinep] $nome_censo2: aluno n�o est� mais vinculado a esta escola.");
        $erro_naoencontrado = true;
       }else{
        $codigoaluno = pg_result($result11,0,'ed47_i_codigo');

        $sqlupdate11 = " UPDATE aluno SET ed47_i_codigo = $codigoaluno ";

        if($ed47_c_codigoinep!="" && $ed47_c_codigoinep!=trim(pg_result($result11,0,'ed47_c_codigoinep'))){
         $sqlupdate11 .= " ,ed47_c_codigoinep = '$ed47_c_codigoinep' ";
        }
        $sqlupdate11 .= " WHERE ed47_i_codigo = $codigoaluno";
        $resultupdate11 = pg_query($sqlupdate11);
        if(!$resultupdate11){
         die("ERRO ALUNO[2]: ".$sqlupdate11."<br><br>");
        }
       }
      }
     }
    }
   }
   fclose($ponteiro4);   
   if($erro_naoencontrado==true){
    ?>
    <script>
     jan = window.open('edu4_importarcodigoinep002.php?arquivo_erro=<?=$arquivo_logerro?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
     jan.moveTo(0,0);
    </script>
    <?
   }
   db_atutermometro_edu(99, 100, 'termometro',1,'...Processo Conclu�do');
   ?>
   <script>
    clearTimeout(varTempo);
    document.form1.recomecar.style.visibility = "visible";
   </script><?
   pg_exec("commit");
   unlink($caminho_arquivo);
   db_msgbox("Importa��o realizada com sucesso!");
  }
}
?>
</body>
</html>