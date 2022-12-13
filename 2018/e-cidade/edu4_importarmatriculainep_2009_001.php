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
if(isset($ano_opcao)){
 $titulofieldset = "<font color='red'> -> SITUAÇÃO DO ALUNO - MATRÍCULA INEP</font>";
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
   <fieldset style="width:95%"><legend><b>Importação de informações do CENSO ESCOLAR <?=$titulofieldset?></b></legend>
    <?
    $result = pg_query("SELECT ed18_c_codigoinep FROM escola WHERE ed18_i_codigo = $escola");
    $codigoinep_banco = pg_result($result,0,0);
    ?>
    <table border="0" align="left">
     <tr>
      <td>
       <b>Ano das informações do arquivo:</b>
       <select name="ano_opcao" onchange="js_anoopcao(this.value);">
        <option value="2011" <?=@$ano_opcao=="2011"?"selected":""?>>2011</option>
        <option value="2010" <?=@$ano_opcao=="2010"?"selected":""?>>2010</option>
        <option value="2009" <?=@$ano_opcao=="2009"?"selected":""?>>2009</option>
       </select><br>       
       <b>Código INEP da Escola:</b> <input type="text" name="codigoinep_banco" value="<?=$codigoinep_banco?>" size="8" readonly style="background:#deb887">
      </td>
     </tr>
     <tr>
      <td>
       <b>Tipo de arquivo:</b>
       <select name="tipo_opcao" onchange="js_tipoopcao(this.value);">
        <option value="0" <?=@$tipo_opcao=="0"?"selected":""?> selected >>> Selecione <<</option>
        <option value="1" <?=@$tipo_opcao=="1"?"selected":""?>>Arquivos de Abertura</option>        
        <option value="2" <?=@$tipo_opcao=="2"?"selected":""?>>Arquivos Situação do Aluno</option>                
       </select><br>       
      </td>
     </tr>
     <tr>
      <td>
       <b>Arquivo de importação do Censo:</b>
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
            varTempo = setTimeout('getSecs()',1000);
           }
          </script>
          <b>
           Tempo de execução:<br>
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
    echo "<font color=red><b>* Código INEP desta escola não informado no sistema. Operação Não Permitida.</b></font>
          &nbsp;&nbsp;<a href='edu1_escolaabas002.php'>Informar Código INEP</a>
          <br>";
   }
   ?>
   <input name="processar" type="submit" id="processar" value="Processar" onclick="return js_valida()" <?=$codigoinep_banco==""||isset($processar)?"disabled":""?>>
   <input name="recomecar" type="submit" id="recomecar" value="Recomeçar" onclick="location.href='edu4_importarmatriculainep_2009_001.php'" style="visibility:hidden;">
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_valida(){
 if(document.form1.tipo_opcao.value=="0"){
  alert("Informe o tipo de arquivo para realizar a importação!");
  return false;
 }
 if(document.form1.arquivo_censo.value==""){
  alert("Informe o arquivo para realizar a importação!");
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
 }else if(document.form1.tipo_opcao.value=="2"){
  if(valor=="2011" || valor=="2010"){  
   location.href = "edu4_importarmatriculainep001.php?ano_opcao="+valor+"&tipo_opcao="+document.form1.tipo_opcao.value;
  }else{
   location.href = "edu4_importarmatriculainep_2009_001.php?ano_opcao="+valor+"&tipo_opcao="+document.form1.tipo_opcao.value;
  }     
 }
}
</script>
<?
$sql_nomes = "SELECT ed47_i_codigo as cod1,trim(ed47_v_nome) as nome1 FROM aluno WHERE trim(ed47_v_nome) like '%  %'";
$result_nomes = pg_query($sql_nomes);
$linhas_nomes = pg_num_rows($result_nomes);
for($t=0;$t<$linhas_nomes;$t++){
 db_fieldsmemory($result_nomes,$t);
 $nome_partes = explode(" ",$nome1);
 $novo_nome = "";
 $espaco = "";
 for($e=0;$e<count($nome_partes);$e++){
  if(trim($nome_partes[$e])!=""){
   $novo_nome .= $espaco.trim($nome_partes[$e]);
   $espaco = " ";
  }
 }
 $update_nome = "UPDATE ALUNO SET ed47_v_nome = '$novo_nome' WHERE ed47_i_codigo = $cod1";
 $result_nome = pg_query($update_nome);
}
if(isset($processar)){
  $tmp_name = $_FILES["arquivo_censo"]["tmp_name"];
  $name     = $_FILES["arquivo_censo"]["name"];
  $type     = $_FILES["arquivo_censo"]["type"];
  $size     = $_FILES["arquivo_censo"]["size"];
  if(!@copy($tmp_name,"tmp/".$name)){
   db_msgbox("Não foi possível efetuar upload. Verifique permissão do Diretório");
   db_redireciona("edu4_importarmatriculainep_2009_001.php?ano_opcao=2011");
   exit;
  }
  $caminho_arquivo = "tmp/".$name;
  $ponteiro3 = fopen($caminho_arquivo,"r");
  $valida_arquivo1 = false;
  $valida_arquivo2 = false;
  $valida_arquivo3 = false;  
  $cont_aluno_while = 0;  
  $contador_geral = 0;
  while(!feof($ponteiro3)){
   $linhaponteiro = fgets($ponteiro3,500);
   $explode_linha = explode("|",$linhaponteiro);
   if($contador_geral==0 && trim($explode_linha[0])!="90"){
    $valida_arquivo1 = true;
    break;
   }
   if($contador_geral==0 && trim($explode_linha[1])!=$codigoinep_banco){
    $valida_arquivo2 = true;
    break;
   }
   if($contador_geral==0 && trim($explode_linha[2])!=$ano_opcao){
    $valida_arquivo3 = true;
    break;
   }
   if(trim($explode_linha[0])=="90"){
    $contador_geral++;
   }
  }
  fclose($ponteiro3);
  if($valida_arquivo2==true){
   db_msgbox("[2] Arquivo informado não pertence a esta escola !");
   ?><script>document.form1.processar.disabled = false;</script><?   
   ?><script>document.form1.recomecar.style.visibility = "visible";</script><?   
  }elseif($valida_arquivo3==true){
   db_msgbox("[3] Arquivo informado não pertence ao ano de $ano_opcao!");
   ?><script>document.form1.processar.disabled = false;</script><?   
   ?><script>document.form1.recomecar.style.visibility = "visible";</script><?   
  }elseif($valida_arquivo1==true){      
   db_msgbox("[1] Arquivo informado não é um arquivo de exportação de Situação do Aluno gerado pelo Educacenso!");
   ?><script>document.form1.processar.disabled = false;</script><?   
   ?><script>document.form1.recomecar.style.visibility = "visible";</script><?   
  }else{
   ?>
   <script>document.getElementById("termo").style.visibility = "visible";</script>
   <script>document.getElementById("table_termo").style.visibility = "visible";</script>
   <?
   set_time_limit(0);
   $arquivo_logerro = "tmp/censo_impMatInep_".$escola."_".db_getsession("DB_id_usuario")."_".date("dmY")."_".date("His")."_log.txt";
   $ponteiro_log = fopen($arquivo_logerro,"w");
   fwrite($ponteiro_log,"Registros não atualizados na importação do Censo Escolar:\n\n");
   pg_exec("begin");
   $ponteiro4 = fopen($caminho_arquivo,"r");
   $erro_naoencontrado = false;
   while(!feof($ponteiro4)){
    $linha = str_replace(chr(39)," ",fgets($ponteiro4,500));
    if(trim($linha)==""){
     continue;
    }
    $exp_linha = explode("|",$linha);
    db_atutermometro_edu($cont_aluno_while, $contador_geral , 'termometro',1,'...Processando Alunos');
    $cont_aluno_while++;
    $codigoaluno = "";
    $codigoinepaluno = trim($exp_linha[6]);
    $nome_censo  = trim($exp_linha[8]);
    $matcenso = trim($exp_linha[11]);
    $turmacenso = trim($exp_linha[4]);
    $anocenso = trim($exp_linha[2]);
    $nome_censo2 = str_replace("ª","",$nome_censo);
    $nome_censo2 = str_replace("º","",$nome_censo2);
    $sql11 = "SELECT aluno.*,escola.ed18_c_codigoinep as vinculo_escola
              FROM aluno
               inner join alunocurso on ed56_i_aluno = ed47_i_codigo
               inner join escola on ed18_i_codigo = ed56_i_escola
              WHERE to_ascii(translate(ed47_v_nome,'´`',''),'LATIN1') = '$nome_censo2'
              ORDER BY vinculo_escola DESC
              ";
    $result11 = pg_query($sql11);
    $linhas11 = pg_num_rows($result11);
    if($linhas11==0){
      fwrite($ponteiro_log,"\n[Matr.INEP: $matcenso Turma INEP: $turmacenso Ano: $anocenso] $nome_censo2 : Nome cadastrado no censo não existe no sistema.");
      $erro_naoencontrado = true;
    }else{
      $codigoaluno = pg_result($result11,0,'ed47_i_codigo');
      $sql21 = "UPDATE aluno SET
                 ed47_c_codigoinep = '$codigoinepaluno' 
                WHERE ed47_i_codigo = $codigoaluno 
               ";
      $result21 = pg_query($sql21);
      if($matcenso!=""){
       $linhas21 = pg_num_rows($result21);
        
       $sql22 = "SELECT ed280_i_codigo
                 FROM alunomatcenso
                 WHERE ed280_i_matcenso = $matcenso
                 AND ed280_i_ano = $anocenso
                 AND ed280_i_aluno = $codigoaluno 
                ";
       $result22 = pg_query($sql22);
       $linhas22 = pg_num_rows($result22);
       if($linhas22==0){
        $sql4 = "INSERT INTO alunomatcenso (ed280_i_codigo,
                                            ed280_i_aluno,
                                            ed280_i_turmacenso,
                                            ed280_i_ano,
                                            ed280_i_matcenso
                                           )
                                           VALUES
                                           (nextval('alunomatcenso_ed280_i_codigo_seq'),
                                            $codigoaluno,
                                            $turmacenso,
                                            $anocenso,
                                            $matcenso
                                           )";
        $result4 = pg_query($sql4);
        if(!$result4){
         die("ERRO ALUNOMATCENSO[1]: ".$sql4."<br><br>");
        }           
       }    
        
      }
     }
   }   
   fclose($ponteiro4);
   if($erro_naoencontrado==true){
    ?>
    <script>
     jan = window.open('edu4_importarmatriculainep_2009_002.php?arquivo_erro=<?=$arquivo_logerro?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
     jan.moveTo(0,0);
    </script>
    <?
   }
   db_atutermometro_edu(99, 100, 'termometro',1,'...Processo Concluído');
   ?>
   <script>
    clearTimeout(varTempo);
    document.form1.recomecar.style.visibility = "visible";
   </script><?
   pg_exec("commit");
   unlink($caminho_arquivo);
   db_msgbox("Importação realizada com sucesso!");
  }
}
?>
</body>
</html>