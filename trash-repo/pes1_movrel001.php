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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_movrel_classe.php");
include("classes/db_convenio_classe.php");
include("classes/db_relac_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_pontofs_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmovrel = new cl_movrel;
$clconvenio = new cl_convenio;
$clrelac = new cl_relac;
$clrhpessoal = new cl_rhpessoal;
$clpontofs = new cl_pontofs;
$db_opcao = 1;
$db_botao = true;
$alertar = false;
if(isset($incluir) || isset($confirma)){

  function testavalor($valor=null){
    if(is_numeric($valor) == true){
      return $valor;
    }else{
      return 0;
    }
  }

  $sqlerro = false;
  $dbcontator = 0; 
  $result_dados_posicoes = $clconvenio->sql_record($clconvenio->sql_query_file($r54_codrel,db_getsession('DB_instit')));
  if($clconvenio->numrows > 0){
    db_fieldsmemory($result_dados_posicoes,0);
    db_inicio_transacao();
    $pos_ano01 = ((int)substr($r56_posano,0,3))-1; // 3 caracteres da primeira posição do ano
    $pos_ano02 = ((int)substr($r56_posano,3,3))-1; // 3 caracteres da posição limite do ano
    $cas_ano12 = 0;                                // Quantos caracteres
    // Busca quantidade de caracteres para usar no SUBSTR
    if($pos_ano01 > -1){
      for($i=$pos_ano01; $i<=$pos_ano02; $i++){
        $cas_ano12 ++;
      }
    }

    $pos_mes01 = ((int)substr($r56_posmes,0,3))-1; // 3 caracteres da primeira posição do mes
    $pos_mes02 = ((int)substr($r56_posmes,3,3))-1; // 3 caracteres da posição limite do mes
    $cas_mes12 = 0;                                // Quantos caracteres
    // Busca quantidade de caracteres para usar no SUBSTR
    if($pos_mes01 > -1){
      for($i=$pos_mes01; $i<=$pos_mes02; $i++){
        $cas_mes12 ++;
      }
    }

    $pos_reg01 = ((int)substr($r56_posreg,0,3))-1; // 3 caracteres da primeira posição do registro
    $pos_reg02 = ((int)substr($r56_posreg,3,3))-1; // 3 caracteres da posição limite do registro
    $cas_reg12 = 0;                                // Quantos caracteres
    // Busca quantidade de caracteres para usar no SUBSTR
    if($pos_reg01 > -1){
      for($i=$pos_reg01; $i<=$pos_reg02; $i++){
        $cas_reg12 ++;
      }
    }

    $pos_eve01 = ((int)substr($r56_poseve,0,3))-1; // 3 caracteres da primeira posição do relacionamento
    $pos_eve02 = ((int)substr($r56_poseve,3,3))-1; // 3 caracteres da posição limite do relacionamento
    $cas_eve12 = 0;                                // Quantos caracteres
    // Busca quantidade de caracteres para usar no SUBSTR
    if($pos_eve01 > -1){
      for($i=$pos_eve01; $i<=$pos_eve02; $i++){
        $cas_eve12 ++;
      }
    }

    $pos_q0101 = ((int)substr($r56_posq01,0,3))-1; // 3 caracteres da primeira posição do valor/quantidade 01
    $pos_q0102 = ((int)substr($r56_posq01,3,3))-1; // 3 caracteres da posição limite do valor/quantidade 01
    $cas_q0112 = 0;                                // Quantos caracteres
    // Busca quantidade de caracteres para usar no SUBSTR
    if($pos_q0101 > -1){
      for($i=$pos_q0101; $i<=$pos_q0102; $i++){
        $cas_q0112 ++;
      }
    }

    $pos_q0201 = ((int)substr($r56_posq02,0,3))-1; // 3 caracteres da primeira posição do valor/quantidade 02
    $pos_q0202 = ((int)substr($r56_posq02,3,3))-1; // 3 caracteres da posição limite do valor/quantidade 02
    $cas_q0212 = 0;                                // Quantos caracteres
    // Busca quantidade de caracteres para usar no SUBSTR
    if($pos_q0201 > -1){
      for($i=$pos_q0201; $i<=$pos_q0202; $i++){
        $cas_q0212 ++;
      }
    }

    $pos_q0301 = ((int)substr($r56_posq03,0,3))-1; // 3 caracteres da primeira posição do valor/quantidade 03
    $pos_q0302 = ((int)substr($r56_posq03,3,3))-1; // 3 caracteres da posição limite do valor/quantidade 03
    $cas_q0312 = 0;                                // Quantos caracteres
    // Busca quantidade de caracteres para usar no SUBSTR
    if($pos_q0301 > -1){
      for($i=$pos_q0301; $i<=$pos_q0302; $i++){
        $cas_q0312 ++;
      }
    }


    if(!isset($confirma)){
      // Nome do novo arquivo
      $nomearq = $_FILES["r56_dirarq"]["name"];
  
      // Nome do arquivo temporário gerado no /tmp
      $nometmp = $_FILES["r56_dirarq"]["tmp_name"];
  
      // Seta o nome do arquivo destino do upload
      $arquivoprocessa = "/tmp/ret$nomearq";
  
      // Faz um upload do arquivo para o local especificado
      move_uploaded_file($nometmp,$arquivoprocessa) or $erro_msg = "ERRO: Contate o suporte.";
    }else{
      $arquivoprocessa = $confirma;
    }

    // Abre o arquivo
    $ponteiro = fopen("$arquivoprocessa","r") or $erro_msg = "ERRO: Arquivo não abre.";

    $totalLinhasArq = count(file($arquivoprocessa));
    $totalLinhasArq-= $r56_linhastrailler;
    $contadorLinhas = 0;
    while(!feof($ponteiro)){
      $poslinha = fgets($ponteiro,4096);
      $contadorLinhas ++;
      if($poslinha=="" || $contadorLinhas <= $r56_linhasheader || $contadorLinhas > $totalLinhasArq){
        continue;
      }

      $ano = trim(substr($poslinha,$pos_ano01,$cas_ano12));
      $mes = trim(substr($poslinha,$pos_mes01,$cas_mes12));
      $reg = trim(substr($poslinha,$pos_reg01,$cas_reg12));
      $eve = trim(substr($poslinha,$pos_eve01,$cas_eve12));

      if(trim($eve) != trim($r54_codeve) && trim($eve) != ""){
      	continue;
      }

      $q01 = trim(substr($poslinha,$pos_q0101,$cas_q0112));
      $q02 = trim(substr($poslinha,$pos_q0201,$cas_q0212));
      $q03 = trim(substr($poslinha,$pos_q0301,$cas_q0312));

      if(trim($ano) == ""){
      	$ano = db_anofolha();
      }
      if(trim($mes) == ""){
      	$mes = db_mesfolha();
      }

      $where_exclui = "";
      if(trim($r54_codeve) != ""){
        $where_exclui = " and r54_codeve = '".$r54_codeve."'";
      }

      if(isset($confirma) && !isset($exclusao_confirma)){
      	$clmovrel->excluir(null,"r54_anomes='".$ano.$mes."' and r54_instit = ".db_getsession("DB_instit")." and r54_codrel='".$r54_codrel."'".$where_exclui);
        if($clmovrel->erro_status==0){
          $erro_msg = $clmovrel->erro_msg;
          $sqlerro = true;
          break;
        }
        $exclusao_confirma = true;
      }else if(!isset($confirma) && !isset($nao_alertar)){
        $result_ja_processado = $clmovrel->sql_record($clmovrel->sql_query_file(null,"*","","r54_anomes='".$ano.$mes."' and r54_instit = ".db_getsession("DB_instit")." and r54_codrel='".$r54_codrel."'".$where_exclui));
        if($clmovrel->numrows > 0){
      	  $sqlerro = true;
      	  $alertar = true;
      	  if(isset($incluir)){
      	    unset($incluir);
      	  }
      	  break;
        }
        $nao_alertar = true;
      }

      if(trim($q01) == ""){
        $q01 = 0;
      }else{
        $val = $q01;
        $q01 = substr($val,0,-2).".".substr($val,-2);
      }
      if(trim($q02) == ""){
        $q02 = 0;
      }else{
        $val = $q02;
        $q02 = substr($val,0,-2).".".substr($val,-2);
      }
      if(trim($q03) == ""){
        $q03 = 0;
      }else{
        $val = $q03;
        $q03 = substr($val,0,-2).".".substr($val,-2);
      }

      if($sqlerro == false){
        $clmovrel->r54_anomes = $ano.$mes;
        $clmovrel->r54_codrel = $r54_codrel;
        $reg = testavalor($reg);
        $clmovrel->r54_regist = "$reg";
        $clmovrel->r54_codeve = $eve;
        $q01 = testavalor($q01);
        $clmovrel->r54_quant1 = "$q01";
        $q02 = testavalor($q02);
        $clmovrel->r54_quant2 = "$q02";
        $q03 = testavalor($q03);
        $clmovrel->r54_quant3 = "$q03";
        $clmovrel->r54_lancad = "false";
        $clmovrel->r54_instit = db_getsession("DB_instit");
        $clmovrel->incluir();
        $dbcontator++;
        if($clmovrel->erro_status==0){
          $erro_msg = $clmovrel->erro_msg;
          $sqlerro = true;
          break;
        }
      }
    }
    db_fim_transacao($sqlerro);
  }else{
    $erro_msg = "Convênio não encontrado.";
    $sqlerro = true;
  }
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmovrel.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($incluir) || isset($confirma)){
  if($sqlerro == true){
  	db_msgbox($erro_msg);
  }else{
  	db_msgbox($dbcontator." registros incluídos.");
  };
}else if($alertar == true){
  echo "
        <script>
          if(confirm('Usuário:\\n\\nAlguns movimentos deste arquivo já foram processados.\\nReprocessá-lo excluindo dados anteriores?')){
            obj=document.createElement('input');
            obj.setAttribute('name','confirma');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','$arquivoprocessa');
            document.form1.appendChild(obj);
            document.form1.submit();
          }
        </script>
       ";
}
?>