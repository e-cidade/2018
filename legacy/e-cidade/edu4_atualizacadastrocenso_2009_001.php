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
 ob_get_contents();
 //ob_flush();
}
$ano_atual = date("Y",db_getsession("DB_datausu"));
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
   <fieldset style="width:95%"><legend><b>Importação de informações do CENSO ESCOLAR -> ESCOLA / TURMA / DOCENTE / ALUNO</b></legend>
    <?
    $result = pg_query("SELECT ed18_c_codigoinep FROM escola WHERE ed18_i_codigo = $escola");
    $codigoinep_banco = pg_result($result,0,0);
    ?>
    <table border="0" align="left">
     <tr>
      <td>
       <b>Ano das informações do arquivo:</b>
       <select name="ano_opcao" onchange="js_trocaano(this.value)">
        <option value="<?=$ano_atual?>" <?=@$ano_opcao==$ano_atual?"selected":""?>><?=$ano_atual?></option>
        <option value="<?=$ano_atual-1?>" <?=@$ano_opcao==$ano_atual-1?"selected":""?>><?=$ano_atual-1?></option>        
       </select><br>       
       <b>Código INEP:</b> <input type="text" name="codigoinep_banco" value="<?=$codigoinep_banco?>" size="8" readonly style="background:#deb887">
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
   <input name="recomecar" type="submit" id="recomecar" value="Recomeçar" onclick="location.href='edu4_atualizacadastrocenso_2009_001.php?ano_opcao=<?=$ano_atual?>>'" style="visibility:hidden;">
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_valida(){
 if(document.form1.arquivo_censo.value==""){
  alert("Informe o arquivo para realizar a importação!");
  return false;
 }
 if(document.form1.escolacenso.checked==false && document.form1.turma.checked==false && document.form1.aluno.checked==false && document.form1.docente.checked==false){
  alert("Escolha no mínimo uma das opções para realizar a importação.\n(Escola, Turmas, Alunos ou Docentes)!");
  return false;
 }
 document.form1.caminho_arquivo.value = document.form1.arquivo_censo.value;
 document.getElementById("processar").style.visibility = "hidden";
 document.getElementById("recomecar").style.visibility = "hidden"; 
 return true;
}
function js_trocaano(ano){
 if(ano=="<?=$ano_atual?>"){
  location.href = "edu4_atualizacadastrocenso001.php?ano_opcao="+ano;
 }else{
  location.href = "edu4_atualizacadastrocenso_2009_001.php?ano_opcao="+ano;	 
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
   db_redireciona("edu4_atualizacadastrocenso_2009_001.php?ano_opcao=$ano_atual");
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
      if(substr($linhaponteiro,0,2)=="00" || substr($linhaponteiro,0,2)=="10"){
    $contador_escola++;
   }
   if(substr($linhaponteiro,0,2)=="20"){
    $contador_turma++;
   }
   if(substr($linhaponteiro,0,2)=="30" || substr($linhaponteiro,0,2)=="40" || substr($linhaponteiro,0,2)=="50"){
    $contador_docente++;
   }
   if(substr($linhaponteiro,0,2)=="60" || substr($linhaponteiro,0,2)=="70" || substr($linhaponteiro,0,2)=="80"){
    $contador_aluno++;
   }
   if(substr($linhaponteiro,0,2)!=""){
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
   db_msgbox("[1] Arquivo informado não é um arquivo de exportação geral gerado pelo Educacenso!");
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
   fwrite($ponteiro_log,"Registros não atualizados na importação do Censo Escolar:\n\n");
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
    $tiporegistro = trim(substr($linha,0,2));
    if($tiporegistro==00){
     $datainicial_anoletivo = trim(substr($linha,21,8));
     $anoletivo = substr($datainicial_anoletivo,4,4);
    }
    /////////////////ESCOLA
    if(isset($escolacenso)){
     if($tiporegistro==00){
      db_atutermometro_edu($cont_escola_while, $contador_escola , 'termometro',1,'...Processando Escola');
      $cont_escola_while++;
      $codigoescola = "";
      $codigoinep_escola = trim(substr($linha,12,8));
      $sql44 = "SELECT * FROM escola WHERE ed18_c_codigoinep = '$codigoinep_escola'";
      $result44 = pg_query($sql44);
      if(!$result44){
       die("ERRO ESCOLA: ".$sql44."<br><br>");
      }
      $linhas44 = pg_num_rows($result44);
      $codigoescola = pg_result($result44,0,'ed18_i_codigo');

      $sqlupdateescola = " UPDATE escola SET ed18_i_codigo = $codigoescola ";

      $funcionamento_escola = trim(substr($linha,20,1));
      if($funcionamento_escola!="" && $funcionamento_escola!=trim(pg_result($result44,0,'ed18_i_funcionamento'))){
       $sqlupdateescola  .= " ,ed18_i_funcionamento = $funcionamento_escola ";
      }
      $cep_escola = trim(substr($linha,137,8));
      if($cep_escola!="" && $cep_escola!=trim(pg_result($result44,0,'ed18_c_cep'))){
       $sqlupdateescola  .= " ,ed18_c_cep = '$cep_escola' ";
      }
      $numero_escola = (int) substr($linha,245,10);
      if($numero_escola!="" && $numero_escola!=trim(pg_result($result44,0,'ed18_i_numero'))){
       $sqlupdateescola  .= " ,ed18_i_numero = $numero_escola ";
      }
      $compl_escola = trim(substr($linha,255,20));
      if($compl_escola!="" && $compl_escola!=trim(pg_result($result44,0,'ed18_c_compl'))){
       $sqlupdateescola  .= " ,ed18_c_compl  = '$compl_escola' ";
      }
      $email_escola = trim(substr($linha,370,50));
      if($email_escola!="" && $email_escola!=trim(pg_result($result44,0,'ed18_c_email'))){
       $sqlupdateescola  .= " ,ed18_c_email = '$email_escola' ";
      }
      $censouf_escola = trim(substr($linha,325,2));
      if($censouf_escola!="" && $censouf_escola!=trim(pg_result($result44,0,'ed18_i_censouf'))){
       $sqlupdateescola  .= " ,ed18_i_censouf  = $censouf_escola ";
      }
      $censomunic_escola = trim(substr($linha,327,7));
      if($censomunic_escola!="" && $censomunic_escola!=trim(pg_result($result44,0,'ed18_i_censomunic'))){
       $sqlupdateescola  .= " ,ed18_i_censomunic = $censomunic_escola ";
      }
      $censodistrito_escola  = trim(substr($linha,334,2));
      if($censodistrito_escola!="" && $censodistrito_escola!=trim(pg_result($result44,0,'ed18_i_censodistrito'))){
       $sql_distrito = "SELECT ed262_i_codigo FROM censodistrito
                        WHERE ed262_i_censomunic = $censomunic_escola
                        AND ed262_i_coddistrito = $censodistrito_escola";
       $res_distrito = pg_query($sql_distrito);
       if(pg_num_rows($res_distrito)>0){
        $censodistrito_escola = pg_result($res_distrito,0,0);
       }else{
        $censodistrito_escola = "null";
       }
       $sqlupdateescola  .= " ,ed18_i_censodistrito = $censodistrito_escola ";
      }
      $censoorgreg_escola = trim(substr($linha,420,5));
      if($censoorgreg_escola!="" && $censoorgreg_escola!=trim(pg_result($result44,0,'ed18_i_censoorgreg'))){
       $sql_orgreg = "SELECT ed263_i_codigo
                      FROM censoorgreg
                      WHERE ed263_i_censouf = $censouf_escola
                      AND ed263_i_codigocenso = $censoorgreg_escola";
       $res_orgreg = pg_query($sql_orgreg);
       if(pg_num_rows($res_orgreg)>0){
        $censoorgreg_escola = pg_result($res_orgreg,0,0);
       }else{
        $censoorgreg_escola = "null";
       }
       $sqlupdateescola  .= " ,ed18_i_censoorgreg = $censoorgreg_escola ";
      }
      $local_escola = trim(substr($linha,426,1));
      if($local_escola!="" && $local_escola!=trim(pg_result($result44,0,'ed18_c_local'))){
       $sqlupdateescola  .= " ,ed18_c_local = '$local_escola' ";
      }
      $categprivada_escola = trim(substr($linha,441,1));
      if($categprivada_escola!="" && $categprivada_escola!=trim(pg_result($result44,0,'ed18_i_categprivada'))){
       $sqlupdateescola  .= " ,ed18_i_categprivada = '$categprivada_escola' ";
      }
      $conveniada_escola = trim(substr($linha,442,1));
      if($conveniada_escola!="" && $conveniada_escola!=trim(pg_result($result44,0,'ed18_i_conveniada'))){
       $sqlupdateescola  .= " ,ed18_i_conveniada = $conveniada_escola ";
      }
      $cnas_escola = trim(substr($linha,443,15));
      if($cnas_escola!="" && $cnas_escola!=trim(pg_result($result44,0,'ed18_i_cnas'))){
       $sqlupdateescola  .= " ,ed18_i_cnas = $cnas_escola ";
      }
      $cebas_escola = trim(substr($linha,458,15));
      if($cebas_escola!="" && $cebas_escola!=trim(pg_result($result44,0,'ed18_i_cebas'))){
       $sqlupdateescola  .= " ,ed18_i_cebas = $cebas_escola ";
      }
      $mantenedora_escola = trim(substr($linha,425,1));
      if($mantenedora_escola!="" && $mantenedora_escola!=trim(pg_result($result44,0,'ed18_c_mantenedora'))){
       $sqlupdateescola  .= " ,ed18_c_mantenedora = '$mantenedora_escola'";
      }
      $mantprivada_escola = trim(substr($linha,473,4));
      if($mantprivada_escola!="" && $mantprivada_escola!=trim(pg_result($result44,0,'ed18_c_mantprivada'))){
       $sqlupdateescola  .= " ,ed18_c_mantprivada = '$mantprivada_escola' ";
      }
      $cnpjprivada_escola = trim(substr($linha,477,14));
      if($cnpjprivada_escola!="" && $cnpjprivada_escola!=trim(pg_result($result44,0,'ed18_i_cnpjprivada'))){
       $sqlupdateescola  .= " ,ed18_i_cnpjprivada = $cnpjprivada_escola ";
      }
      $cnpj_escola = trim(substr($linha,427,14));
      if($cnpj_escola!="" && $cnpj_escola!=trim(pg_result($result44,0,'ed18_i_cnpj'))){
       $sqlupdateescola  .= " ,ed18_i_cnpj = $cnpj_escola ";
      }
      $credenciamento_escola = trim(substr($linha,491,1));
      if($credenciamento_escola!="" && $credenciamento_escola!=trim(pg_result($result44,0,'ed18_i_credenciamento'))){
       $sqlupdateescola  .= " ,ed18_i_credenciamento = $credenciamento_escola ";
      }
      $bairro_escola = trim(substr($linha,275,50));
      if($bairro_escola!=""){
       $sql_bairro = "SELECT j13_codi FROM bairro WHERE to_ascii(j13_descr,'LATIN1') = '$bairro_escola'";
       $res_bairro = pg_query($sql_bairro);
       $linhas_bairro = pg_num_rows($res_bairro);
       if($linhas_bairro>0){
        $codbairro = pg_result($res_bairro,0,0);
        $sqlupdateescola  .= " ,ed18_i_bairro = $codbairro ";
       }
      }
      $endereco_escola = trim(substr($linha,145,100));
      if($endereco_escola!=""){
       $sql_endereco = "SELECT j14_codigo FROM ruas WHERE to_ascii(j14_nome,'LATIN1') = '$endereco_escola'";
       $res_endereco = pg_query($sql_endereco);
       $linhas_endereco = pg_num_rows($res_endereco);
       if($linhas_endereco>0){
        $codendereco = pg_result($res_endereco,0,0);
        $sqlupdateescola  .= " ,ed18_i_rua = $codendereco ";
       }
      }
      if($credenciamento_escola!="" && $credenciamento_escola!=trim(pg_result($result44,0,'ed18_i_credenciamento'))){
       $sqlupdateescola  .= " ,ed18_i_credenciamento = $credenciamento_escola ";
      }
      $sqlupdateescola .= " WHERE ed18_i_codigo = $codigoescola";
      $resultupdateescola = pg_query($sqlupdateescola);
      if(!$resultupdateescola){
       die("ERRO ESCOLA[1]: ".$sqlupdateescola."<br><br>");
      }
     }
     if($tiporegistro==10){
      db_atutermometro_edu($cont_escola_while, $contador_escola , 'termometro',1,'...Processando Escola');
      $cont_escola_while++;
      $sqlescola = " UPDATE escola SET ed18_i_codigo = $codigoescola ";
      $locdiferenciada_escola = trim(substr($linha,415,1));
      if($locdiferenciada_escola!="" && $locdiferenciada_escola!=trim(pg_result($result44,0,'ed18_i_locdiferenciada'))){
       $sqlescola  .= " ,ed18_i_locdiferenciada = $locdiferenciada_escola ";
      }
      $educindigena_escola = trim(substr($linha,419,1));
      if($educindigena_escola!="" && $educindigena_escola!=trim(pg_result($result44,0,'ed18_i_educindigena'))){
       $sqlescola  .= " ,ed18_i_educindigena = $educindigena_escola ";
      }
      $tipolinguain_escola = trim(substr($linha,420,1));
      if($tipolinguain_escola!="" && $tipolinguain_escola!=trim(pg_result($result44,0,'ed18_i_tipolinguain'))){
       $sqlescola  .= " ,ed18_i_tipolinguain = $tipolinguain_escola ";
      }
      $tipolinguapt_escola = trim(substr($linha,421,2));
      if($tipolinguapt_escola!="" && $tipolinguapt_escola!=trim(pg_result($result44,0,'ed18_i_tipolinguapt'))){
       $sqlescola  .= " ,ed18_i_tipolinguapt = $tipolinguapt_escola ";
      }
      $linguaindigena_escola = trim(substr($linha,422,5));
      if($linguaindigena_escola!="" && $linguaindigena_escola!=trim(pg_result($result44,0,'ed18_i_linguaindigena'))){
       $sqlescola  .= " ,ed18_i_linguaindigena = $linguaindigena_escola ";
      }
      $sqlescola .= " WHERE ed18_i_codigo = $codigoescola ";
      $resultescola = pg_query($sqlescola);
      if(!$resultescola){
       die("ERRO ESCOLA[2]: ".$sqlescola."<br><br>");
      }
      $sql45 = "SELECT * FROM escolaestrutura WHERE ed255_i_escola = $codigoescola";
      $result45 = pg_query($sql45);
      if(pg_num_rows($result45)>0){
       $codigoescolaestrutura = pg_result($result45,0,'ed255_i_codigo');

       $sqlescola = " UPDATE escolaestrutura SET ed255_i_codigo = $codigoescolaestrutura ";

       $compartilhado_escola = trim(substr($linha,269,1));
       if($compartilhado_escola!="" && $compartilhado_escola!=trim(pg_result($result45,0,'ed255_i_compartilhado'))){
        $sqlescola  .= " ,ed255_i_compartilhado = $compartilhado_escola ";
       }
       $escolacompartilhada_escola = trim(substr($linha,270,8));
       if($escolacompartilhada_escola!="" && $escolacompartilhada_escola!=trim(pg_result($result45,0,'ed255_i_escolacompartilhada'))){
        $sqlescola  .= " ,ed255_i_escolacompartilhada = $escolacompartilhada_escola ";
       }
       $salaexite_escola = trim(substr($linha,354,4));
       if($salaexite_escola!="" && $salaexite_escola!=trim(pg_result($result45,0,'ed255_i_salaexistente'))){
        $sqlescola  .= " ,ed255_i_salaexistente = $salaexite_escola ";
       }
       $salautil_escola = trim(substr($linha,358,4));
       if($salautil_escola!="" && $salautil_escola!=trim(pg_result($result45,0,'ed255_i_salautilizada'))){
        $sqlescola  .= " ,ed255_i_salautilizada = $salautil_escola ";
       }
       $abastagua_escola = trim(substr($linha,319,5));
       if($abastagua_escola!="" && $abastagua_escola!=trim(pg_result($result45,0,'ed255_c_abastagua'))){
        $sqlescola  .= " ,ed255_c_abastagua = '$abastagua_escola' ";
       }
       $abastenergia_escola = trim(substr($linha,324,4));
       if($abastenergia_escola!="" && $abastenergia_escola!=trim(pg_result($result45,0,'ed255_c_abastenergia'))){
        $sqlescola  .= " ,ed255_c_abastenergia = '$abastenergia_escola' ";
       }
       $aguafiltrada_escola = trim(substr($linha,318,1));
       if($aguafiltrada_escola!="" && $aguafiltrada_escola!=trim(pg_result($result45,0,'ed255_i_aguafiltrada'))){
        $sqlescola  .= " ,ed255_i_aguafiltrada = $aguafiltrada_escola ";
       }
       $esgotosanitario_escola = trim(substr($linha,328,3));
       if($esgotosanitario_escola!="" && $esgotosanitario_escola!=trim(pg_result($result45,0,'ed255_c_esgotosanitario'))){
        $sqlescola  .= " ,ed255_c_esgotosanitario = '$esgotosanitario_escola' ";
       }
       $destinolixo_escola = trim(substr($linha,331,6));
       if($destinolixo_escola!="" && $destinolixo_escola!=trim(pg_result($result45,0,'ed255_c_destinolixo'))){
        $sqlescola  .= " ,ed255_c_destinolixo = '$destinolixo_escola' ";
       }
       $localizacao_escola = trim(substr($linha,261,8));
       if($localizacao_escola!="" && $localizacao_escola!=trim(pg_result($result45,0,'ed255_c_localizacao'))){
        $sqlescola  .= " ,ed255_c_localizacao = '$localizacao_escola' ";
       }
       $dependencias_escola = trim(substr($linha,337,17));
       if($dependencias_escola!="" && $dependencias_escola!=trim(pg_result($result45,0,'ed255_c_dependencias'))){
        $sqlescola  .= " ,ed255_c_dependencias = '$dependencias_escola' ";
       }
       $equipamentos_escola = trim(substr($linha,362,7));
       if($equipamentos_escola!="" && $equipamentos_escola!=trim(pg_result($result45,0,'ed255_c_equipamentos'))){
        $sqlescola  .= " ,ed255_c_equipamentos = '$equipamentos_escola' ";
       }
       $computadores_escola = trim(substr($linha,369,1));
       if($computadores_escola!="" && $computadores_escola!=trim(pg_result($result45,0,'ed255_i_computadores'))){
        $sqlescola  .= " ,ed255_i_computadores = $computadores_escola ";
       }
       $qtdcomp_escola = trim(substr($linha,370,4));
       if($qtdcomp_escola!="" && $qtdcomp_escola!=trim(pg_result($result45,0,'ed255_i_qtdcomp'))){
        $sqlescola  .= " ,ed255_i_qtdcomp = $qtdcomp_escola ";
       }
       $qtdcompadm_escola = trim(substr($linha,374,4));
       if($qtdcompadm_escola!="" && $qtdcompadm_escola!=trim(pg_result($result45,0,'ed255_i_qtdcompadm'))){
        $sqlescola  .= " ,ed255_i_qtdcompadm = $qtdcompadm_escola ";
       }
       $qtdcompalu_escola = trim(substr($linha,378,4));
       if($qtdcompalu_escola!="" && $qtdcompalu_escola!=trim(pg_result($result45,0,'ed255_i_qtdcompalu'))){
        $sqlescola  .= " ,ed255_i_qtdcompalu = $qtdcompalu_escola ";
       }
       $internet_escola = trim(substr($linha,382,1));
       if($internet_escola!="" && $internet_escola!=trim(pg_result($result45,0,'ed255_i_internet'))){
        $sqlescola  .= " ,ed255_i_internet = $internet_escola ";
       }
       $bandalarga_escola = trim(substr($linha,383,1));
       if($bandalarga_escola!="" && $bandalarga_escola!=trim(pg_result($result45,0,'ed255_i_bandalarga'))){
        $sqlescola  .= " ,ed255_i_bandalarga = $bandalarga_escola ";
       }
       $alimentacao_escola = trim(substr($linha,388,1));
       if($alimentacao_escola!="" && $alimentacao_escola!=trim(pg_result($result45,0,'ed255_i_alimentacao'))){
        $sqlescola  .= " ,ed255_i_alimentacao = $alimentacao_escola ";
       }
       $ativcomplementar_escola = trim(substr($linha,390,1));
       if($ativcomplementar_escola!="" && $ativcomplementar_escola!=trim(pg_result($result45,0,'ed255_i_ativcomplementar'))){
        $sqlescola  .= " ,ed255_i_ativcomplementar = $ativcomplementar_escola ";
       }
       $aee_escola = trim(substr($linha,389,1));
       if($aee_escola!="" && $aee_escola!=trim(pg_result($result45,0,'ed255_i_aee'))){
        $sqlescola  .= " ,ed255_i_aee = $aee_escola ";
       }
       $efciclos_escola = trim(substr($linha,414,1));
       if($efciclos_escola!="" && $efciclos_escola!=trim(pg_result($result45,0,'ed255_i_efciclos'))){
        $sqlescola  .= " ,ed255_i_efciclos = $efciclos_escola ";
       }
       $materdidatico_escola = trim(substr($linha,416,3));
       if($materdidatico_escola!="" && $materdidatico_escola!=trim(pg_result($result45,0,'ed255_c_materdidatico'))){
        $sqlescola  .= " ,ed255_c_materdidatico = $materdidatico_escola ";
       }
       $sqlescola .= " WHERE ed255_i_codigo = $codigoescolaestrutura ";
       $resultescola = pg_query($sqlescola);
       if(!$resultescola){
        die("ERRO ESCOLA[3]: ".$sqlescola."<br><br>");
       }
      }
      if(!isset($turma) && !isset($docente) && !isset($aluno)){
       break;
      }
     }
    }
    /////////////////TURMA
    if(isset($turma)){
     if($tiporegistro==20){
      db_atutermometro_edu($cont_turma_while, $contador_turma , 'termometro',1,'...Processando Turmas');
      $cont_turma_while++;
      $codigoinep_turmacenso = trim(substr($linha,20,10));
      $nome_turmacenso = trim(substr($linha,50,80));
      $nome_turmacenso2 = str_replace("ª","",$nome_turmacenso);
      $nome_turmacenso2 = str_replace("º","",$nome_turmacenso2);
      $tipoatend_turmacenso = trim(substr($linha,138,1));
      $modalidade_turmacenso = trim(substr($linha,181,1));
      $etapa_turmacenso = trim(substr($linha,182,2));
      if($tipoatend_turmacenso==0 || $tipoatend_turmacenso==1 || $tipoatend_turmacenso==2 || $tipoatend_turmacenso==3){
       $sqlturma33 = "SELECT DISTINCT ed57_i_codigo
                      FROM turma
                       inner join calendario on ed52_i_codigo = ed57_i_calendario
                       inner join escola on ed18_i_codigo = ed57_i_escola
                       inner join turmaserieregimemat on ed220_i_turma = ed57_i_codigo
                       inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                       inner join serie on ed11_i_codigo = ed223_i_serie
                       inner join ensino on ed10_i_codigo = ed11_i_ensino
                      WHERE translate(to_ascii(ed57_c_descr, 'LATIN1'),' ','') = '".str_replace(" ","",$nome_turmacenso2)."'
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
         fwrite($ponteiro_log,"Turma(s) abaixo relacionada(s), informada(s) no censo $anoletivo, não foram encontrada(s) no sistema.(Não existe ou o nome da turma informado no censo não coincide com o nome informado no sistema)\n");
         $primeiro_turma = true;
        }
        fwrite($ponteiro_log,"TURMA: $nome_turmacenso\n");
        $erro_naoencontrado = true;
       }else{
        $codigoturma = pg_result($resultturma33,0,0);
        $sqlupdate_turma = "UPDATE turma SET
                             ed57_i_codigoinep = $codigoinep_turmacenso
                            WHERE ed57_i_codigo = $codigoturma ";
        $resulupdate_turma = pg_query($sqlupdate_turma);
        if(!$resulupdate_turma){
         die("ERRO TURMA[2]: ".$sqlupdate_turma."<br><br>");
        }
       }
      }elseif($tipoatend_turmacenso==4 || $tipoatend_turmacenso==5){
       $sqlturma33 = "SELECT *
                      FROM turmaac
                       inner join calendario on ed52_i_codigo = ed268_i_calendario
                       inner join escola on ed18_i_codigo = ed268_i_escola
                      WHERE translate(to_ascii(ed268_c_descr, 'LATIN1'),' ','') = '".str_replace(" ","",$nome_turmacenso2)."'
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
         fwrite($ponteiro_log,"Turma(s) abaixo relacionada(s), informada(s) no censo $anoletivo, não foram encontrada(s) no sistema.(Não existe ou o nome da turma informado no censo não coincide com o nome informado no sistema)\n");
         $primeiro_turma = true;
        }
        fwrite($ponteiro_log,"TURMA: $nome_turmacenso\n");
        $erro_naoencontrado = true;
       }else{
        $codigoturma = pg_result($resultturma33,0,'ed268_i_codigo');
        $sqlupdate_turma = "UPDATE turmaac SET ed268_i_codigoinep = $codigoinep_turmacenso";
        $ativqtd_turma = trim(substr($linha,414,1));
        if($ativqtd_turma!="" && $ativqtd_turma!=trim(pg_result($resultturma33,0,'ed268_i_ativqtd'))){
         $sqlupdate_turma .= " ,ed268_i_ativqtd = $ativqtd_turma ";
        }
        $aee_turma = trim(substr($linha,170,11));
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
    /////////////////DOCENTE
    if(isset($docente)){
     if($tiporegistro==30){
      db_atutermometro_edu($cont_docente_while, $contador_docente , 'termometro',1,'...Processando Docentes');
      $cont_docente_while++;
      $codigodocente = "";
      $nome_docentecenso = trim(substr($linha,52,100));
      $nome_docentecenso2 = str_replace("ª","",$nome_docentecenso);
      $nome_docentecenso2 = str_replace("º","",$nome_docentecenso2);
      $nasc_docentecenso = trim(substr($linha,263,8));
      $nasc_docentecenso = substr($nasc_docentecenso,4,4)."-".substr($nasc_docentecenso,2,2)."-".substr($nasc_docentecenso,0,2);
      $mae_docentecenso =  trim(substr($linha,273,100));
      $mae_docentecenso = str_replace("ª","",$mae_docentecenso);
      $mae_docentecenso = str_replace("º","",$mae_docentecenso);
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
        fwrite($ponteiro_log,"\nDocente(s) abaixo relacionado(s), informado(s) no censo $anoletivo, não foram atualizado(s) no sistema.\n (Não existe no sistema ou o nome do docente informado no censo não coincide com o nome informado no sistema ou o docente não está mais vinculado a esta escola)\n");
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

        $ed20_i_codigoinep = trim(substr($linha,20,12));
        if($ed20_i_codigoinep!="" && $ed20_i_codigoinep!=trim(pg_result($result22,0,'ed20_i_codigoinep'))){
         $sqlupdatedocente .= " ,ed20_i_codigoinep = $ed20_i_codigoinep ";
        }
        $ed20_c_nis = trim(substr($linha,252,11));
        if($ed20_c_nis!="" && $ed20_c_nis!=trim(pg_result($result22,0,'ed20_c_nis'))){
         $sqlupdatedocente .= " ,ed20_c_nis = '$ed20_c_nis' ";
        }
        $ed20_i_raca = trim(substr($linha,272,1));
        if($ed20_i_raca!="" && $ed20_i_raca!=trim(pg_result($result22,0,'ed20_i_raca'))){
         $sqlupdatedocente .= " ,ed20_i_raca = $ed20_i_raca ";
        }
        $ed20_i_nacionalidade = trim(substr($linha,373,1));
        if($ed20_i_nacionalidade!="" && $ed20_i_nacionalidade!=trim(pg_result($result22,0,'ed20_i_nacionalidade'))){
         $sqlupdatedocente .= " ,ed20_i_nacionalidade = $ed20_i_nacionalidade ";
        }
        $ed20_i_pais = trim(substr($linha,374,3));
        if($ed20_i_pais!="" && $ed20_i_pais!=trim(pg_result($result22,0,'ed20_i_pais'))){
         $sqlupdatedocente .= " ,ed20_i_pais = $ed20_i_pais ";
        }
        $ed20_i_censoufnat = trim(substr($linha,377,2));
        if($ed20_i_censoufnat!="" && $ed20_i_censoufnat!=trim(pg_result($result22,0,'ed20_i_censoufnat'))){
         $sqlupdatedocente .= " ,ed20_i_censoufnat = $ed20_i_censoufnat ";
        }
        $ed20_i_censomunicnat = trim(substr($linha,379,7));
        if($ed20_i_censomunicnat!="" && $ed20_i_censomunicnat!=trim(pg_result($result22,0,'ed20_i_censomunicnat'))){
         $sqlupdatedocente .= " ,ed20_i_censomunicnat = $ed20_i_censomunicnat ";
        }
        $sqlupdatedocente .= " WHERE ed20_i_codigo = $codigodocente ";        
        $resultupdatedocente = pg_query($sqlupdatedocente);
        if(!$resultupdatedocente){
         die("ERRO DOCENTE[2]: ".$sqlupdatedocente."<br><br>");
        }
       }
      }
     }
     if($tiporegistro==40){
      db_atutermometro_edu($cont_docente_while, $contador_docente , 'termometro',1,'...Processando Docentes');
      $cont_docente_while++;
      if($codigodocente!=""){
       for($tt=0;$tt<$linhas22;$tt++){
        $codigodocente = pg_result($result22,$tt,0);

        $sqldocente = " update rechumano set ed20_i_codigo=$codigodocente ";

        $ed20_c_identcompl = trim(substr($linha,72,4));
        if($ed20_c_identcompl !="" && $ed20_c_identcompl!= trim(pg_result($result22,0,'ed20_c_identcompl'))){
         $sqldocente  .= " ,ed20_c_identcompl      = '$ed20_c_identcompl' ";
        }
        $ed20_i_censoorgemiss   = trim(substr($linha,76,2));
        if($ed20_i_censoorgemiss !="" && $ed20_i_censoorgemiss!= trim(pg_result($result22,0,'ed20_i_censoorgemiss'))){
         $sqldocente  .= " ,ed20_i_censoorgemiss   = $ed20_i_censoorgemiss ";
        }
        $ed20_i_censoufident = trim(substr($linha,78,2));
        if($ed20_i_censoufident !="" && $ed20_i_censoufident!= trim(pg_result($result22,0,'ed20_i_censoufident'))){
         $sqldocente  .= " ,ed20_i_censoufident    = $ed20_i_censoufident ";
        }
        $ed20_d_dataident = trim(substr($linha,80,8));
        if($ed20_d_dataident !="" && $ed20_d_dataident!= trim(pg_result($result22,0,'ed20_d_dataident'))){
         $ed20_d_dataident = "'".substr($ed20_d_dataident,4,4)."-".substr($ed20_d_dataident,2,2)."-".substr($ed20_d_dataident,0,2)."'";       	 
         $sqldocente  .= " ,ed20_d_dataident    = $ed20_d_dataident ";
        }
        $ed20_i_certidaotipo = trim(substr($linha,88,1));
        if($ed20_i_certidaotipo !="" && $ed20_i_certidaotipo!= trim(pg_result($result22,0,'ed20_i_certidaotipo'))){
         $sqldocente  .= " ,ed20_i_certidaotipo    = $ed20_i_certidaotipo ";
        }
        $ed20_c_certidaonum     = trim(substr($linha,89,8));
        if($ed20_c_certidaonum !="" && $ed20_c_certidaonum!= trim(pg_result($result22,0,'ed20_c_certidaonum'))){
         $sqldocente  .= " ,ed20_c_certidaonum     = '$ed20_c_certidaonum' ";
        }
        $ed20_c_certidaofolha   = trim(substr($linha,97,4));
        if($ed20_c_certidaofolha !="" && $ed20_c_certidaofolha!= trim(pg_result($result22,0,'ed20_c_certidaofolha'))){
         $sqldocente  .= " ,ed20_c_certidaofolha   = '$ed20_c_certidaofolha' ";
        }
        $ed20_c_certidaolivro   = trim(substr($linha,101,8));
        if($ed20_c_certidaolivro !="" && $ed20_c_certidaolivro!= trim(pg_result($result22,0,'ed20_c_certidaolivro'))){
         $sqldocente  .= " ,ed20_c_certidaolivro   = '$ed20_c_certidaolivro' ";
        }
        $ed20_c_certidaodata = trim(substr($linha,109,8));
        if($ed20_c_certidaodata !="" && $ed20_c_certidaodata!= trim(pg_result($result22,0,'ed20_c_certidaodata'))){
       	 $ed20_c_certidaodata = "'".substr($ed20_c_certidaodata,4,4)."-".substr($ed20_c_certidaodata,2,2)."-".substr($ed20_c_certidaodata,0,2)."'";
         $sqldocente  .= " ,ed20_c_certidaodata    = $ed20_c_certidaodata ";
        }        
        $ed20_c_certidaocart = trim(substr($linha,117,100));
        if($ed20_c_certidaocart !="" && $ed20_c_certidaocart!= trim(pg_result($result22,0,'ed20_c_certidaocart'))){
         $sqldocente  .= " ,ed20_c_certidaocart    = '$ed20_c_certidaocart' ";
        }
        $ed20_i_censoufcert = trim(substr($linha,217,2));
        if($ed20_i_censoufcert !="" && $ed20_i_censoufcert!= trim(pg_result($result22,0,'ed20_i_censoufcert'))){
         $sqldocente  .= " ,ed20_i_censoufcert     = $ed20_i_censoufcert ";
        }
        $ed20_c_passaporte = trim(substr($linha,230,20));
        if($ed20_c_passaporte !="" && $ed20_c_passaporte!= trim(pg_result($result22,0,'ed20_c_passaporte'))){
         $sqldocente  .= " ,ed20_c_passaporte      = '$ed20_c_passaporte' ";
        }
        $ed20_i_censoufender = trim(substr($linha,438,2));
        if($ed20_i_censoufender !="" && $ed20_i_censoufender!= trim(pg_result($result22,0,'ed20_i_censoufender'))){
         $sqldocente  .= " ,ed20_i_censoufender    = $ed20_i_censoufender ";
        }
        $ed20_i_censomunicender = trim(substr($linha,440,7));
        if($ed20_i_censomunicender !="" && $ed20_i_censomunicender!= trim(pg_result($result22,0,'ed20_i_censomunicender'))){
         $sqldocente  .= " ,ed20_i_censomunicender = $ed20_i_censomunicender ";
        }
        $sqldocente .= " WHERE ed20_i_codigo = $codigodocente ";        
        $resultdocente = pg_query($sqldocente);
        if(!$resultdocente){
         die("ERRO DOCENTE[3]: ".$sqldocente."<br><br>");
        }
       }
      }
     }
     if($tiporegistro==50){
      db_atutermometro_edu($cont_docente_while, $contador_docente , 'termometro',1,'...Processando Docentes');
      $cont_docente_while++;
      if($codigodocente!=""){
       for($tt=0;$tt<$linhas22;$tt++){
        $codigodocente = pg_result($result22,$tt,0);

        $update = " update rechumano set ed20_i_codigo=$codigodocente ";

        $ed20_i_escolaridade = trim(substr($linha,52,1));
        if($ed20_i_escolaridade !="" && $ed20_i_escolaridade!= trim(pg_result($result22,0,'ed20_i_escolaridade'))){
         $update  .= " ,ed20_i_escolaridade = $ed20_i_escolaridade ";
        }
        $ed20_c_posgraduacao = trim(substr($linha,110,4));
        if($ed20_c_posgraduacao !="" && $ed20_c_posgraduacao!= trim(pg_result($result22,0,'ed20_c_posgraduacao'))){
         $update  .= " ,ed20_c_posgraduacao = '$ed20_c_posgraduacao' ";
        }
        $ed20_c_outroscursos = trim(substr($linha,114,6));
        if($ed20_c_outroscursos !="" && $ed20_c_outroscursos!= trim(pg_result($result22,0,'ed20_c_outroscursos'))){
         $update  .= " ,ed20_c_outroscursos = '$ed20_c_outroscursos' ";
        }        
        $update .= " WHERE ed20_i_codigo = $codigodocente ";
        $resultupdaterech = pg_query($update);
        if(!$resultupdaterech){
         die("ERRO DOCENTE[4]: ".$update."<br><br>");
        }
       }
       $array_formacao[0][0] = trim(substr($linha,53,1));
       $array_formacao[0][1] = trim(substr($linha,54,6));
       $array_formacao[0][2] = trim(substr($linha,60,4));
       $array_formacao[0][3] = trim(substr($linha,64,1));
       $array_formacao[0][4] = trim(substr($linha,65,7));

       $array_formacao[1][0] = trim(substr($linha,72,1));
       $array_formacao[1][1] = trim(substr($linha,73,6));
       $array_formacao[1][2] = trim(substr($linha,79,4));
       $array_formacao[1][3] = trim(substr($linha,83,1));
       $array_formacao[1][4] = trim(substr($linha,84,7));

       $array_formacao[2][0] = trim(substr($linha,91,1));
       $array_formacao[2][1] = trim(substr($linha,92,6));
       $array_formacao[2][2] = trim(substr($linha,98,4));
       $array_formacao[2][3] = trim(substr($linha,102,1));
       $array_formacao[2][4] = trim(substr($linha,103,7));

       for($rr=0;$rr<count($array_formacao);$rr++){
        if(trim($array_formacao[$rr][1])!=""){
         $sql_del = "DELETE FROM formacao WHERE ed27_i_rechumano = $codigodocente";
         $result_del = pg_query($sql_del);
         $sql_cursoformacao = "SELECT ed94_i_codigo
                               FROM cursoformacao
                               WHERE ed94_c_codigocenso = '".$array_formacao[$rr][1]."'
                              ";
         $result_cursoformacao = pg_query($sql_cursoformacao);
         if(pg_num_rows($result_cursoformacao)>0){
          $codigo_cursoformacao = pg_result($result_cursoformacao,0,0);
          $insert_formacao = "INSERT INTO formacao
                               (ed27_i_codigo
                               ,ed27_i_rechumano
                               ,ed27_i_cursoformacao
                               ,ed27_c_situacao
                               ,ed27_i_licenciatura
                               ,ed27_i_anoconclusao
                               ,ed27_i_censoinstsuperior)
                              VALUES
                               (nextval('formacao_ed27_i_codigo_seq')
                               ,$codigodocente
                               ,$codigo_cursoformacao
                               ,'CON'
                               ,".trim($array_formacao[$rr][0])."
                               ,".trim($array_formacao[$rr][2])."
                               ,".trim($array_formacao[$rr][4]).")
                             ";
          $result_formacao = pg_query($insert_formacao);
          if(!$result_formacao){
           die("ERRO DOCENTE[5]: ".$insert_formacao."<br><br>");
          }
         }  
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
      $nome_censo  = trim(substr($linha,52,100));
      $nome_censo2 = str_replace("ª","",$nome_censo);
      $nome_censo2 = str_replace("º","",$nome_censo2);
      $sql11 = "SELECT aluno.*,escola.ed18_c_codigoinep as vinculo_escola
                FROM aluno
                 inner join alunocurso on ed56_i_aluno = ed47_i_codigo
                 inner join escola on ed18_i_codigo = ed56_i_escola
                WHERE to_ascii(ed47_v_nome,'LATIN1') = '$nome_censo2'
                ";
      $result11 = pg_query($sql11);
      $linhas11 = pg_num_rows($result11);
      if($linhas11==0){
       fwrite($ponteiro_log,"\nAluno $nome_censo2: Nome cadastrado no censo não existe no sistema.");
       $erro_naoencontrado = true;
      }else{
       $vinculo_escola = pg_result($result11,0,'vinculo_escola');
       if(trim($vinculo_escola)!=trim($codigoinep_banco)){
        fwrite($ponteiro_log,"\nAluno $nome_censo2: aluno não está mais vinculado a esta escola.");
        $erro_naoencontrado = true;
       }else{
        $codigoaluno = pg_result($result11,0,'ed47_i_codigo');

        $sqlupdate11 = " UPDATE aluno SET ed47_i_codigo = $codigoaluno ";

        $nasc_censo = trim(substr($linha,163,8));
        if($nasc_censo!=""){
         $nasc_censo = substr($nasc_censo,4,4)."-".substr($nasc_censo,2,2)."-".substr($nasc_censo,0,2);
         $sqlupdate11 .= " ,ed47_d_nasc = '$nasc_censo' ";
        }
        $ed47_c_codigoinep = trim(substr($linha,20,12));
        if($ed47_c_codigoinep!="" && $ed47_c_codigoinep!=trim(pg_result($result11,0,'ed47_c_codigoinep'))){
         $sqlupdate11 .= " ,ed47_c_codigoinep = '$ed47_c_codigoinep' ";
        }
        $ed47_c_nis = trim(substr($linha,152,11));
        if($ed47_c_nis!="" && $ed47_c_nis!=trim(pg_result($result11,0,'ed47_c_nis'))){
         $sqlupdate11 .= " ,ed47_c_nis = '$ed47_c_nis' ";
        }
        $ed47_v_sexo = trim(substr($linha,171,1));
        if($ed47_v_sexo!="" && $ed47_v_sexo!=trim(pg_result($result11,0,'ed47_v_sexo'))){
         if($ed47_v_sexo==1){
          $ed47_v_sexo = 'M';
         }else{
          $ed47_v_sexo = 'F';
         }
         $sqlupdate11 .= " ,ed47_v_sexo = '$ed47_v_sexo' ";
        }
        $ed47_c_raca = trim(substr($linha,172,1));
        if($ed47_c_raca!="" && $ed47_c_raca!=trim(pg_result($result11,0,'ed47_c_raca'))){
         if($ed47_c_raca==0){
          $ed47_c_raca = 'NÃO DECLARADA';
         }elseif($ed47_c_raca==1){
          $ed47_c_raca = 'BRANCA';
         }elseif($ed47_c_raca==2){
          $ed47_c_raca = 'PRETA';
         }elseif($ed47_c_raca==3){
          $ed47_c_raca = 'PARDA';
         }elseif($ed47_c_raca==4){
          $ed47_c_raca = 'AMARELA';
         }else{
          $ed47_c_raca = 'INDÍGENA';
         }
         $sqlupdate11 .= " ,ed47_c_raca = '$ed47_c_raca' ";
        }
        $ed47_i_filiacao = trim(substr($linha,173,1));
        if($ed47_i_filiacao!="" && $ed47_i_filiacao!=trim(pg_result($result11,0,'ed47_i_filiacao'))){
          $sqlupdate11 .= " ,ed47_i_filiacao = $ed47_i_filiacao ";
        }
        $ed47_i_nacion = trim(substr($linha,374,1));
        if($ed47_i_nacion!="" && $ed47_i_nacion!=trim(pg_result($result11,0,'ed47_i_nacion'))){
          $sqlupdate11 .= " ,ed47_i_nacion = $ed47_i_nacion ";
        }
        $ed47_i_pais = trim(substr($linha,375,3));
        if($ed47_i_pais!="" && $ed47_i_pais!=trim(pg_result($result11,0,'ed47_i_pais'))){
          $sqlupdate11 .= " ,ed47_i_pais = $ed47_i_pais ";
        }
        $ed47_i_censoufnat = trim(substr($linha,378,2));
        if($ed47_i_censoufnat!="" && $ed47_i_censoufnat!=trim(pg_result($result11,0,'ed47_i_censoufnat'))){
          $sqlupdate11 .= " ,ed47_i_censoufnat = $ed47_i_censoufnat ";
        }
        $ed47_i_censomunicnat = trim(substr($linha,380,7));
        if($ed47_i_censomunicnat!="" && $ed47_i_censomunicnat!=trim(pg_result($result11,0,'ed47_i_censomunicnat'))){
          $sqlupdate11 .= " ,ed47_i_censomunicnat = $ed47_i_censomunicnat ";
        }
        $sqlupdate11 .= " WHERE ed47_i_codigo = $codigoaluno";
        $resultupdate11 = pg_query($sqlupdate11);
        if(!$resultupdate11){
         die("ERRO ALUNO[2]: ".$sqlupdate11."<br><br>");
        }
       }
      }
     }
     if($tiporegistro==70){
      db_atutermometro_edu($cont_aluno_while, $contador_aluno , 'termometro',1,'...Processando Alunos');
      $cont_aluno_while++;
      if($codigoaluno!=""){
       $sqlupdate70 = " update aluno set ed47_i_codigo = $codigoaluno ";
       $ed47_v_ident = trim(substr($linha,52,20));
       if($ed47_v_ident !="" && $ed47_v_ident!= trim(pg_result($result11,0,'ed47_v_ident'))){
         $sqlupdate70  .= " ,ed47_v_ident = '$ed47_v_ident' ";
       }
       $ed47_v_identcompl = trim(substr($linha,72,4));
       if($ed47_v_identcompl !="" && $ed47_v_identcompl!= trim(pg_result($result11,0,'ed47_v_identcompl'))){
         $sqlupdate70  .= " ,ed47_v_identcompl = '$ed47_v_identcompl'  ";
       }
       $ed47_i_censoorgemissrg =trim(substr($linha,76,2));
       if($ed47_i_censoorgemissrg !="" && $ed47_i_censoorgemissrg!= trim(pg_result($result11,0,'ed47_i_censoorgemissrg'))){
         $sqlupdate70  .= " ,ed47_i_censoorgemissrg = $ed47_i_censoorgemissrg ";
       }
       $ed47_i_censoufident = trim(substr($linha,78,2));
       if($ed47_i_censoufident !="" && $ed47_i_censoufident!= trim(pg_result($result11,0,'ed47_i_censoufident'))){
         $sqlupdate70  .= " ,ed47_i_censoufident = $ed47_i_censoufident ";
       }
       $ed47_d_identdtexp = trim(substr($linha,80,8));
       if($ed47_d_identdtexp !="" && $ed47_d_identdtexp!= trim(pg_result($result11,0,'ed47_d_identdtexp'))){
      	$ed47_d_identdtexp = "'".substr($ed47_d_identdtexp,4,4)."-".substr($ed47_d_identdtexp,2,2)."-".substr($ed47_d_identdtexp,0,2)."'";
        $sqlupdate70  .= " ,ed47_d_identdtexp = $ed47_d_identdtexp ";
       }                     
       $ed47_c_certidaotipo = trim(substr($linha,88,1));
       if($ed47_c_certidaotipo !="" && $ed47_c_certidaotipo!= trim(pg_result($result11,0,'ed47_c_certidaotipo'))){
        if($ed47_c_certidaotipo==1){
         $ed47_c_certidaotipo = 'N';
        }elseif($ed47_c_certidaotipo==2){
         $ed47_c_certidaotipo = 'C';
        }else{
         $ed47_c_certidaotipo = '';
        }
        $sqlupdate70  .= " ,ed47_c_certidaotipo = '$ed47_c_certidaotipo' ";
       }
       $ed47_c_certidaonum = trim(substr($linha,89,8));
       if($ed47_c_certidaonum !="" && $ed47_c_certidaonum!= trim(pg_result($result11,0,'ed47_c_certidaonum'))){
         $sqlupdate70  .= " ,ed47_c_certidaonum = '$ed47_c_certidaonum' ";
       }
       $ed47_c_certidaofolha = trim(substr($linha,97,4));
       if($ed47_c_certidaofolha !="" && $ed47_c_certidaofolha!= trim(pg_result($result11,0,'ed47_c_certidaofolha'))){
         $sqlupdate70  .= " ,ed47_c_certidaofolha = '$ed47_c_certidaofolha' ";
       }
       $ed47_c_certidaolivro = trim(substr($linha,101,8));
       if($ed47_c_certidaolivro !="" && $ed47_c_certidaolivro!= trim(pg_result($result11,0,'ed47_c_certidaolivro'))){
         $sqlupdate70  .= " ,ed47_c_certidaolivro = '$ed47_c_certidaolivro' ";
       }
       $ed47_c_certidaodata = trim(substr($linha,109,8));
       if($ed47_c_certidaodata !="" && $ed47_c_certidaodata!= trim(pg_result($result11,0,'ed47_c_certidaodata'))){        
         $ed47_c_certidaodata = "'".substr($ed47_c_certidaodata,4,4)."-".substr($ed47_c_certidaodata,2,2)."-".substr($ed47_c_certidaodata,0,2)."'";
         $sqlupdate70  .= " ,ed47_c_certidaodata = $ed47_c_certidaodata ";
       }
       $ed47_c_certidaocart = trim(substr($linha,117,100));
       if($ed47_c_certidaocart !="" && $ed47_c_certidaocart!= trim(pg_result($result11,0,'ed47_c_certidaocart'))){
         $sqlupdate70  .= " ,ed47_c_certidaocart = '$ed47_c_certidaocart' ";
       }
       $ed47_i_censoufcert = trim(substr($linha,217,2));
       if($ed47_i_censoufcert !="" && $ed47_i_censoufcert!= trim(pg_result($result11,0,'ed47_i_censoufcert'))){
         $sqlupdate70  .= " ,ed47_i_censoufcert = $ed47_i_censoufcert ";
       }
       $ed47_v_cpf = trim(substr($linha,219,11));
       if($ed47_v_cpf !="" && $ed47_v_cpf!= trim(pg_result($result11,0,'ed47_v_cpf'))){
         $sqlupdate70  .= " ,ed47_v_cpf = '$ed47_v_cpf' ";
       }
       $ed47_c_passaporte = trim(substr($linha,230,20));
       if($ed47_c_passaporte !="" && $ed47_c_passaporte!= trim(pg_result($result11,0,'ed47_c_passaporte'))){
         $sqlupdate70  .= " ,ed47_c_passaporte = '$ed47_c_passaporte' ";
       }
       $ed47_v_cep = trim(substr($linha,250,8));
       if($ed47_v_cep !="" && $ed47_v_cep!= trim(pg_result($result11,0,'ed47_v_cep'))){
         $sqlupdate70  .= " ,ed47_v_cep = '$ed47_v_cep' ";
         $virgula = ",";
       }
       $ed47_v_ender = trim(substr($linha,258,100));
       if($ed47_v_ender !="" && $ed47_v_ender!= trim(pg_result($result11,0,'ed47_v_ender'))){
         $sqlupdate70  .= " ,ed47_v_ender = '$ed47_v_ender' ";
       }
       $ed47_c_numero = trim(substr($linha,358,10));
       if($ed47_c_numero !="" && $ed47_c_numero!= trim(pg_result($result11,0,'ed47_c_numero'))){
         $sqlupdate70  .= " ,ed47_c_numero = '$ed47_c_numero' ";
       }
       $ed47_v_compl = trim(substr($linha,368,20));
       if($ed47_v_compl !="" && $ed47_v_compl!= trim(pg_result($result11,0,'ed47_v_compl'))){
         $sqlupdate70  .= " ,ed47_v_compl = '$ed47_v_compl' ";
       }
       $ed47_v_bairro = trim(substr($linha,388,50));
       if($ed47_v_bairro !="" && $ed47_v_bairro!= trim(pg_result($result11,0,'ed47_v_bairro'))){
         $sqlupdate70  .= " ,ed47_v_bairro = '".substr($ed47_v_bairro,0,40)."' ";
       }
       $ed47_i_censoufend = trim(substr($linha,438,2));
       if($ed47_i_censoufend !="" && $ed47_i_censoufend!= trim(pg_result($result11,0,'ed47_i_censoufend'))){
         $sqlupdate70  .= " ,ed47_i_censoufend = $ed47_i_censoufend ";
       }
       $ed47_i_censomunicend = trim(substr($linha,440,7));
       if($ed47_i_censomunicend !="" && $ed47_i_censomunicend!= trim(pg_result($result11,0,'ed47_i_censomunicend'))){
         $sqlupdate70  .= " ,ed47_i_censomunicend = $ed47_i_censomunicend ";
       }
       $sqlupdate70 .= " WHERE ed47_i_codigo = $codigoaluno";
       $resultupdate70 = pg_query($sqlupdate70);
       if(!$resultupdate70){
        die("ERRO ALUNO[3]: ".$sqlupdate70."<br><br>");
       }else{
        if($ed47_v_bairro!=""){
         $sql_bairro = "SELECT j13_codi FROM bairro WHERE to_ascii(j13_descr,'LATIN1') = '$ed47_v_bairro'";
         $res_bairro = pg_query($sql_bairro);
         $linhas_bairro = pg_num_rows($res_bairro);
         if($linhas_bairro>0){
          $codbairro = pg_result($res_bairro,0,0);
          $deletebairro = pg_query("DELETE FROM alunobairro WHERE ed225_i_aluno = $codigoaluno");
	  $sqlinsertbairro = "INSERT INTO alunobairro VALUES(nextval('alunobairro_ed225_i_codigo_seq'),$codigoaluno,$codbairro)";
	  $insertbairro = pg_query($sqlinsertbairro);
	  if(!$insertbairro){
	   die("ERRO ALUNO[33]: ".$sqlinsertbairro."<br><br>");
	  }
         }
        }
       }
      }
     }
     if($tiporegistro==80){
      db_atutermometro_edu($cont_aluno_while, $contador_aluno , 'termometro',1,'...Processando Alunos');
      $cont_aluno_while++;
      if($codigoaluno!=""){
       $sqlupdate800 = " update aluno set ed47_i_codigo = $codigoaluno ";
       $ed47_c_atenddifer = trim(substr($linha,85,1));
       if($ed47_c_atenddifer !="" && $ed47_c_atenddifer!= trim(pg_result($result11,0,'ed47_c_atenddifer'))){
         $sqlupdate800  .= " ,ed47_c_atenddifer = '$ed47_c_atenddifer'	 ";
       } 
       $ed47_i_transpublico = trim(substr($linha,86,1));
       if($ed47_i_transpublico !="" && $ed47_i_transpublico!= trim(pg_result($result11,0,'ed47_i_transpublico'))){
         $sqlupdate800  .= " ,ed47_i_transpublico = $ed47_i_transpublico";
       } 
       $ed47_c_transporte = trim(substr($linha,87,1));
       if($ed47_c_transporte!= trim(pg_result($result11,0,'ed47_c_transporte'))){
         $sqlupdate800  .= " ,ed47_c_transporte = '$ed47_c_transporte' ";
       }        
       $ed47_c_zona = trim(substr($linha,88,1));
       if($ed47_c_zona !="" && $ed47_c_zona!= trim(pg_result($result11,0,'ed47_c_zona'))){
        if($ed47_c_zona==1){
         $ed47_c_zona = 'URBANA';
        }elseif($ed47_c_zona==2){
         $ed47_c_zona = 'RURAL';
        }
        $sqlupdate800  .= " ,ed47_c_zona = '$ed47_c_zona' ";
       }
       $ed47_i_atendespec = trim(substr($linha,89,1));
       if($ed47_i_atendespec !="" && $ed47_i_atendespec!= trim(pg_result($result11,0,'ed47_i_atendespec'))){
         $sqlupdate800  .= " ,ed47_i_atendespec = $ed47_i_atendespec ";
       }
       $sqlupdate800 .= " WHERE ed47_i_codigo = $codigoaluno";
       $resultupdate800 = pg_query($sqlupdate800);
       if(!$resultupdate800){
        die("ERRO ALUNO[4]: ".$sqlupdate800."<br><br>");
       }
       if($ed47_i_atendespec==1){
        $deletenecessidade = pg_query("DELETE FROM alunonecessidade WHERE ed214_i_aluno = $codigoaluno");
        $ed214_i_necessidade[] = trim(substr($linha,90,1))==1?101:0;
        $ed214_i_necessidade[] = trim(substr($linha,91,1))==1?102:0;
        $ed214_i_necessidade[] = trim(substr($linha,92,1))==1?103:0;
        $ed214_i_necessidade[] = trim(substr($linha,93,1))==1?104:0;
        $ed214_i_necessidade[] = trim(substr($linha,94,1))==1?105:0;
        $ed214_i_necessidade[] = trim(substr($linha,95,1))==1?106:0;
        $ed214_i_necessidade[] = trim(substr($linha,96,1))==1?107:0;
        $ed214_i_necessidade[] = trim(substr($linha,97,1))==1?108:0;
        $ed214_i_necessidade[] = trim(substr($linha,98,1))==1?109:0;
        $ed214_i_necessidade[] = trim(substr($linha,99,1))==1?110:0;
        $ed214_i_necessidade[] = trim(substr($linha,100,1))==1?111:0;
        $ed214_i_necessidade[] = trim(substr($linha,101,1))==1?112:0;
        $ed214_i_necessidade[] = trim(substr($linha,102,1))==1?113:0;
        for($w=0;$w<13;$w++){
         if($ed214_i_necessidade[$w]>0){
          $ed214_i_necessidade = $ed214_i_necessidade[$w];
          $ed214_c_principal   = 'NAO';
          $ed214_i_apoio       = 1;
          $ed214_d_data        = 'null';
          $ed214_i_tipo        = 1;
          $ed214_i_escola      = 'null';
          $sql4 = "INSERT INTO alunonecessidade (ed214_i_codigo,
                                                 ed214_i_aluno,
                                                 ed214_i_necessidade,
                                                 ed214_c_principal,
                                                 ed214_i_apoio,
                                                 ed214_d_data,
                                                 ed214_i_tipo,
                                                 ed214_i_escola
                                                )
                                                VALUES
                                                (nextval('alunonecessidade_ed214_i_codigo_seq'),
                                                  $codigoaluno,
                                                  $ed214_i_necessidade,
                                                 '$ed214_c_principal',
                                                  $ed214_i_apoio,
                                                  $ed214_d_data,
                                                  $ed214_i_tipo,
                                                  $ed214_i_escola
                                                )";
          $result4 = pg_query($sql4);
          if(!$result4){
           die("ERRO ALUNONECESSIDADE[1]: ".$sql4."<br><br>");
          }
         }
        }
       }
       unset($ed214_i_necessidade);
      }
     }
    }
   }
   if($erro_naoencontrado==true){
    ?>
    <script>
     jan = window.open('edu4_atualizacadastrocenso_2009_002.php?arquivo_erro=<?=$arquivo_logerro?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
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