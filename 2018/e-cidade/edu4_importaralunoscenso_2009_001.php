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
require ("libs/db_utils.php");
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
   <fieldset style="width:95%"><legend><b>Importação de informações do CENSO ESCOLAR -> ALUNO</b></legend>
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
   <input name="recomecar" type="submit" id="recomecar" value="Recomeçar" onclick="location.href='edu4_importaralunoscenso_2009_001.php?ano_opcao=<?=$ano_atual?>'" style="visibility:hidden;">
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
 document.form1.caminho_arquivo.value = document.form1.arquivo_censo.value;
 document.getElementById("processar").style.visibility = "hidden";
 document.getElementById("recomecar").style.visibility = "hidden"; 
 return true;
}
function js_trocaano(ano){
 if(ano=="<?=$ano_atual?>"){
  location.href = "edu4_importaralunoscenso001.php?ano_opcao="+ano;
 }else{
  location.href = "edu4_importaralunoscenso_2009_001.php?ano_opcao="+ano;	 
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
   db_msgbox("Não foi possível efetuar upload. Verifique permissão do Diretório");
   db_redireciona("edu4_importaralunoscenso_2009_001.php?ano_opcao=$ano_opcao");
   exit;
  }
  $caminho_arquivo = "tmp/".$name;
  $ponteiro3 = fopen($caminho_arquivo,"r");
  $valida_arquivo1 = false;
  $valida_arquivo2 = false;
  $valida_arquivo3 = false;  
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
   pg_exec("begin");
   $ponteiro4 = fopen($caminho_arquivo,"r");
   $cont_aluno_while = 0;
   while(!feof($ponteiro4)){
    $linha = str_replace(chr(39)," ",fgets($ponteiro4,500));
    if(trim($linha)==""){
     continue;
    }
    $tiporegistro = trim(substr($linha,0,2));
    if($tiporegistro==60){
     db_atutermometro_edu($cont_aluno_while, $contador_aluno , 'termometro',1,'...Processando Alunos');
     $cont_aluno_while++;
     $codigoaluno = "";
     $nome_censo  = trim(substr($linha,52,100));
     $nome_censo2 = str_replace("ª","",$nome_censo);
     $nome_censo2 = str_replace("º","",$nome_censo2);
     $nasc_censo  = trim(substr($linha,163,8));
     $nasc_censo = substr($nasc_censo,4,4)."-".substr($nasc_censo,2,2)."-".substr($nasc_censo,0,2);
     $mae_censo   =  trim(substr($linha,174,100));
     $mae_censo = str_replace("ª","",$mae_censo);
     $mae_censo = str_replace("º","",$mae_censo);
     $sql11 = "SELECT DISTINCT ed47_i_codigo
               FROM aluno
               WHERE (
                      (to_ascii(ed47_v_nome, 'LATIN1') = '$nome_censo2' AND ed47_d_nasc = '$nasc_censo')
                       OR
                      (to_ascii(ed47_v_nome, 'LATIN1') = '$nome_censo2' AND to_ascii(ed47_v_mae, 'LATIN1') = '$mae_censo')
                     )
              ";
     $result11 = pg_query($sql11);
     if(!$result11){
      die("ERRO ALUNO[1]: ".$sql11."<br><br>");
     }
     $ed47_c_codigoinep = trim(substr($linha,20,12));
     $ed47_c_nis = trim(substr($linha,152,11));
     $ed47_v_sexo = trim(substr($linha,171,1));
     if($ed47_v_sexo==1){
      $ed47_v_sexo = 'M';
     }else{
      $ed47_v_sexo = 'F';
     }
     $ed47_c_raca = trim(substr($linha,172,1));
     if($ed47_c_raca==0){
      $ed47_c_raca='NÃO DECLARADA';
     }elseif($ed47_c_raca==1){
      $ed47_c_raca='BRANCA';
     }elseif($ed47_c_raca==2){
      $ed47_c_raca='PRETA';
     }elseif($ed47_c_raca==3){
      $ed47_c_raca='PARDA';
     }elseif($ed47_c_raca==4){
      $ed47_c_raca='AMARELA';
     }else{
      $ed47_c_raca='INDÍGENA';
     }
     $ed47_i_filiacao     = trim(substr($linha,173,1));
     $ed47_v_pai          = trim(substr($linha,274,100));
     $ed47_i_nacion       = trim(substr($linha,374,1));
     $ed47_i_pais         = trim(substr($linha,375,3));
     $ed47_i_censoufnat   = trim(substr($linha,378,2));
     if($ed47_i_censoufnat==""){
      $ed47_i_censoufnat = 'null';
     }
     $ed47_i_censomunicnat= trim(substr($linha,380,7));
     if($ed47_i_censomunicnat==""){
      $ed47_i_censomunicnat = 'null';
     }
     $linhas11 = pg_num_rows($result11);
     if($linhas11==0){
      $sqlinsert11 = "INSERT INTO aluno (
                       ed47_i_codigo,
                       ed47_c_codigoinep,
	               ed47_v_nome,
	 	       ed47_c_nis,
	 	       ed47_d_nasc,
	 	       ed47_v_sexo,
	  	       ed47_c_raca,
	    	       ed47_i_filiacao,
	    	       ed47_v_mae,
     		       ed47_v_pai,
	    	       ed47_i_nacion,
	    	       ed47_i_pais,
	    	       ed47_i_censoufnat,
	    	       ed47_i_censomunicnat)
	     	      VALUES
                       (nextval('aluno_ed47_i_codigo_seq'),
                       '$ed47_c_codigoinep',
	               '$nome_censo',
	 	       '$ed47_c_nis',
	 	       '$nasc_censo',
	 	       '$ed47_v_sexo',
	  	       '$ed47_c_raca',
	    	       $ed47_i_filiacao,
	    	       '$mae_censo',
     		       '$ed47_v_pai',
	    	       $ed47_i_nacion,
	    	       $ed47_i_pais,
	    	       $ed47_i_censoufnat,
	    	       $ed47_i_censomunicnat)
                      ";
      $resultinsert11 = pg_query($sqlinsert11);
      if(!$resultinsert11){
       die("ERRO ALUNO[2]: ".$sqlinsert11."<br><br>");
      }else{
       $res_ultimo = pg_query("SELECT last_value from aluno_ed47_i_codigo_seq");
       $codigoaluno = pg_result($res_ultimo,0,0);
      }
     }else{
      $codigoaluno = pg_result($result11,0,0);
      $sqlupdate11 = "UPDATE aluno SET
                       ed47_c_codigoinep   = '$ed47_c_codigoinep',
	               ed47_v_nome         = '$nome_censo',
	 	       ed47_c_nis          = '$ed47_c_nis',
	 	       ed47_d_nasc         = '$nasc_censo',
	 	       ed47_v_sexo         = '$ed47_v_sexo',
	  	       ed47_c_raca         = '$ed47_c_raca',
	    	       ed47_i_filiacao     = $ed47_i_filiacao,
	    	       ed47_v_mae          = '$mae_censo',
     		       ed47_v_pai          = '$ed47_v_pai',
	    	       ed47_i_nacion       = $ed47_i_nacion,
	    	       ed47_i_pais         = $ed47_i_pais,
	    	       ed47_i_censoufnat   = $ed47_i_censoufnat,
	    	       ed47_i_censomunicnat= $ed47_i_censomunicnat
	     	      WHERE ed47_i_codigo = $codigoaluno";
      $resultupdate11 = pg_query($sqlupdate11);
      if(!$resultupdate11){
       die("ERRO ALUNO[3]: ".$sqlupdate11."<br><br>");
      }
     }
    }
    if($tiporegistro==70){
     db_atutermometro_edu($cont_aluno_while, $contador_aluno , 'termometro',1,'...Processando Alunos');
     $cont_aluno_while++;
     if($codigoaluno!=""){
      $ed47_v_ident = trim(substr($linha,52,20));
      $ed47_v_identcompl = trim(substr($linha,72,4));
      $ed47_i_censoorgemissrg =trim(substr($linha,76,2));
      if($ed47_i_censoorgemissrg==""){
       $ed47_i_censoorgemissrg = 'null';
      }
      $ed47_i_censoufident = trim(substr($linha,78,2));
      if($ed47_i_censoufident==""){
       $ed47_i_censoufident = 'null';
      }
      $ed47_d_identdtexp = trim(substr($linha,80,8));
      if($ed47_d_identdtexp==""){
       $ed47_d_identdtexp = 'null';
      }else{
       $ed47_d_identdtexp = "'".substr($ed47_d_identdtexp,4,4)."-".substr($ed47_d_identdtexp,2,2)."-".substr($ed47_d_identdtexp,0,2)."'";
      }
      $ed47_c_certidaotipo = trim(substr($linha,88,1));
      if($ed47_c_certidaotipo==1){
       $ed47_c_certidaotipo = 'N';
      }elseif($ed47_c_certidaotipo==2){
       $ed47_c_certidaotipo = 'C';
      }
      $ed47_c_certidaonum = trim(substr($linha,89,8));
      $ed47_c_certidaofolha = trim(substr($linha,97,4));
      $ed47_c_certidaolivro = trim(substr($linha,101,8));
      $ed47_c_certidaodata = trim(substr($linha,109,8));
      if($ed47_c_certidaodata!=""){
       $ed47_c_certidaodata = "'".substr($ed47_c_certidaodata,4,4)."-".substr($ed47_c_certidaodata,2,2)."-".substr($ed47_c_certidaodata,0,2)."'";
      }else{
       $ed47_c_certidaodata = 'null';
      }
      $ed47_c_certidaocart = trim(substr($linha,117,100));
      $ed47_i_censoufcert = trim(substr($linha,217,2));
      if($ed47_i_censoufcert==""){
       $ed47_i_censoufcert = 'null';
      }
      $ed47_v_cpf = trim(substr($linha,219,11));
      $ed47_c_passaporte = trim(substr($linha,230,20));
      $ed47_v_cep = trim(substr($linha,250,8));
      $ed47_v_ender = trim(substr($linha,258,100));
      $ed47_c_numero = trim(substr($linha,358,10));
      $ed47_v_compl = trim(substr($linha,368,20));
      $ed47_v_bairro = trim(substr($linha,388,50));
      $ed47_i_censoufend = trim(substr($linha,438,2));
      if($ed47_i_censoufend==""){
       $ed47_i_censoufend = 'null';
      }
      $ed47_i_censomunicend   =trim(substr($linha,440,7));
      if($ed47_i_censomunicend==""){
       $ed47_i_censomunicend = 'null';
      }
      $sqlupdate70 = "UPDATE aluno SET
                       ed47_v_ident           = '$ed47_v_ident',
                       ed47_v_identcompl      = '$ed47_v_identcompl',
                       ed47_i_censoorgemissrg = $ed47_i_censoorgemissrg,
                       ed47_i_censoufident    = $ed47_i_censoufident,
                       ed47_d_identdtexp      = $ed47_d_identdtexp,
                       ed47_c_certidaotipo    = '$ed47_c_certidaotipo',
                       ed47_c_certidaonum     = '$ed47_c_certidaonum',
                       ed47_c_certidaofolha   = '$ed47_c_certidaofolha',
                       ed47_c_certidaolivro   = '$ed47_c_certidaolivro',
                       ed47_c_certidaodata    = $ed47_c_certidaodata,
                       ed47_c_certidaocart    = '$ed47_c_certidaocart',
                       ed47_i_censoufcert     = $ed47_i_censoufcert,
                       ed47_v_cpf             = '$ed47_v_cpf',
                       ed47_c_passaporte      = '$ed47_c_passaporte',
                       ed47_v_cep             = '$ed47_v_cep',
                       ed47_v_ender           = '$ed47_v_ender',
                       ed47_c_numero          = '$ed47_c_numero',
                       ed47_v_compl           = '$ed47_v_compl',
                       ed47_v_bairro          = '$ed47_v_bairro',
                       ed47_i_censoufend      = $ed47_i_censoufend,
                       ed47_i_censomunicend   = $ed47_i_censomunicend
                      WHERE ed47_i_codigo = $codigoaluno";
      $resultupdate70 = pg_query($sqlupdate70);
      if(!$resultupdate70){
       die("ERRO ALUNO[4]: ".$sqlupdate70."<br><br>");
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
	  die("ERRO ALUNO[5]: ".$sqlinsertbairro."<br><br>");
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
      $ed47_c_atenddifer = trim(substr($linha,85,1));
      $ed47_i_transpublico = trim(substr($linha,86,1));
      if($ed47_i_transpublico==""){
       $ed47_i_transpublico = 'null';
      }
      $ed47_c_transporte = trim(substr($linha,87,1));
      $ed47_i_atendespec = trim(substr($linha,89,1));
      if($ed47_i_atendespec==""){
       $ed47_i_atendespec = 'null';
      }
      $ed47_c_zona         = trim(substr($linha,88,1));
      if($ed47_c_zona==1){
       $ed47_c_zona = 'URBANA';
      }elseif($ed47_c_zona==2){
       $ed47_c_zona = 'RURAL';
      }
      $sqlupdate800 = "UPDATE aluno SET
                        ed47_c_atenddifer   = '$ed47_c_atenddifer',
	                ed47_i_transpublico = $ed47_i_transpublico,
			ed47_c_transporte   = '$ed47_c_transporte',
	 		ed47_c_zona         = '$ed47_c_zona',
	 		ed47_i_atendespec   = $ed47_i_atendespec
	     	       WHERE ed47_i_codigo= $codigoaluno";
      $resultupdate800 = pg_query($sqlupdate800);
      if(!$resultupdate800){
       die("ERRO ALUNO[5]: ".$sqlupdate800."<br><br>");
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
          die("ERRO ALUNONECESSIDADE[2]: ".$sql4."<br><br>");
         }
        }
       }
      }
      unset($ed214_i_necessidade);
     }
    }
   }
   fclose($ponteiro4);
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