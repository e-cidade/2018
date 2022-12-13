<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_levvalor_classe.php");
require_once("classes/db_levantanotas_classe.php");
require_once("classes/db_levanta_classe.php");
require_once("classes/db_levvalorpgtos_classe.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$cllevvalor      = new cl_levvalor;
$cllevantanotas  = new cl_levantanotas;
$cllevanta       = new cl_levanta;
$cllevvalorpgtos = new cl_levvalorpgtos;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
//  db_msgbox("incluir");
  $sqlerro=false;
  db_inicio_transacao();
  $cllevvalor->incluir(null);
  $erro_msg=$cllevvalor->erro_msg;
  $y63_sequencia= $cllevvalor->y63_sequencia;
  if($cllevvalor->erro_status==0){
    $sqlerro=true;
  }

  if(!$sqlerro && $valores!=''){
    $matriz01=split('HHH',$valores);
    for($i=0; $i<count($matriz01); $i++){
      $matriz=split('-',$matriz01[$i]);
      $result55 = $cllevvalorpgtos->sql_record($cllevvalorpgtos->sql_query_file($y63_sequencia,""," max(y68_seq) +1 as seq"));
      db_fieldsmemory($result55,0);
      $y68_seq = $seq == ""?"1":$seq;
      $cllevvalorpgtos->y68_sequencia=$y63_sequencia;
      $cllevvalorpgtos->y68_seq=$y68_seq;
      $cllevvalorpgtos->y68_valor=$matriz[0];
      $cllevvalorpgtos->y68_pgto = substr($matriz[1],6,4)."-".substr($matriz[1],3,2)."-".substr($matriz[1],0,2);
      $cllevvalorpgtos->incluir($y63_sequencia,$y68_seq);
      $erro_msg=$cllevvalorpgtos->erro_msg;
      if($cllevvalorpgtos->erro_status==0){
	      db_msgbox($erro_msg);
	      $sqlerro=true;
      }
    }
  }
  //rotina para incluir na tabela levantanotas
  if(!$sqlerro && $notas!=''){

    $matriz01=split('HHH',$notas);
    for($i=0; $i<count($matriz01); $i++){

      $matriz=split('_sep_',$matriz01[$i]);
      $data1 = explode("/",$matriz[2]);
			$data  = $data1[2].str_pad($data1[1],2,"0",STR_PAD_LEFT).str_pad($data1[0],2,"0",STR_PAD_LEFT);
      $cllevantanotas->y79_documento = $matriz[0];
      $cllevantanotas->y79_valor     = $matriz[1];
      $cllevantanotas->y79_sequencia = $y63_sequencia;
      $cllevantanotas->y79_data      = $data;
      $cllevantanotas->y79_ordem     = ($i+1);
      $cllevantanotas->incluir(null);
      $erro_msg=$cllevantanotas->erro_msg;
      if($cllevantanotas->erro_status==0){

    	  $sqlerro = true;
    	  break;
      }
    }
  }
  db_fim_transacao($sqlerro);

}else if(isset($alterar)){

  $sqlerro=false;
  db_inicio_transacao();
  //rotina para alterar a tabela levvalor
  $cllevvalor->alterar($y63_sequencia);
  $erro_msg=$cllevvalor->erro_msg;
  if($cllevvalor->erro_status==0){

    $sqlerro=true;
  }
  //rotina para que caso já tenha registro na tabela levvalorespgtos apagar esses registros
  if(!$sqlerro){

    $result55 = $cllevvalorpgtos->sql_record($cllevvalorpgtos->sql_query_file($y63_sequencia,"","y68_seq"));
    if($cllevvalorpgtos->numrows>0){

      $cllevvalorpgtos->excluir($y63_sequencia);
      $erro_msg=$cllevvalorpgtos->erro_msg;
      if($cllevvalorpgtos->erro_status==0){
	      $sqlerro=true;
      }
    }
  }
  //rotina para incluir na tabela levvalorpgtos
  if(!$sqlerro && $valores!=''){

    $matriz01=split('HHH',$valores);
    for($i=0; $i<count($matriz01); $i++){

      $matriz=split('-',$matriz01[$i]);
      $result55 = $cllevvalorpgtos->sql_record($cllevvalorpgtos->sql_query_file($y63_sequencia,""," max(y68_seq) +1 as seq"));
      db_fieldsmemory($result55,0);
      $y68_seq = $seq == ""?"1":$seq;
      $cllevvalorpgtos->y68_sequencia=$y63_sequencia;
      $cllevvalorpgtos->y68_seq=$y68_seq;
      $cllevvalorpgtos->y68_valor=$matriz[0];
      $cllevvalorpgtos->y68_pgto=substr($matriz[1],6,4)."-".substr($matriz[1],3,2)."-".substr($matriz[1],0,2);
      $cllevvalorpgtos->incluir($y63_sequencia,$y68_seq);
      $erro_msg=$cllevvalorpgtos->erro_msg;
      if($cllevvalorpgtos->erro_status==0){
	      $sqlerro=true;
      }
    }
  }

  //rotina para que caso já tenha registro na tabela levvalorespgtos apagar esses registros
  if(!$sqlerro){

    $result = $cllevantanotas->sql_record($cllevantanotas->sql_query_file(null,"*","","y79_sequencia=$y63_sequencia"));
    if($cllevantanotas->numrows>0){

      $cllevantanotas->excluir("","y79_sequencia = $y63_sequencia");
      $erro_msg=$cllevantanotas->erro_msg;
      if($cllevantanotas->erro_status==0){
	      $sqlerro=true;
      }
    }
  }
  //rotina para incluir na tabela levantanotas
  if($sqlerro==false && $notas!=''){

    $matriz01=split('HHH',$notas);
    for($i=0; $i<count($matriz01); $i++){

      $matriz=split('_sep_',$matriz01[$i]);
      $data1 = explode("/",$matriz[2]);
			$data  = $data1[2].str_pad($data1[1],2,"0",STR_PAD_LEFT).str_pad($data1[0],2,"0",STR_PAD_LEFT);
      $cllevantanotas->y79_documento = $matriz[0];
      $cllevantanotas->y79_documento = $matriz[0];
      $cllevantanotas->y79_valor     = $matriz[1];
      $cllevantanotas->y79_sequencia = $y63_sequencia;
      $cllevantanotas->y79_data      = $data;
      $cllevantanotas->y79_ordem     = ($i+1);
      $cllevantanotas->incluir(null);
      $erro_msg=$cllevantanotas->erro_msg;
      if($cllevantanotas->erro_status==0){

    	  $sqlerro=true;
    	  break;
      }
    }
  }
    if($sqlerro){
      $opcao='alterar';
    }
  db_fim_transacao($sqlerro);
}else if(isset($excluir)){

  $sqlerro = false;
  db_inicio_transacao();
  //rotina para que caso já tenha registro na tabela levvalorespgtos apagar esses registros
  if(!$sqlerro){

    $result55 = $cllevvalorpgtos->sql_record($cllevvalorpgtos->sql_query_file($y63_sequencia,"","y68_seq"));
    if($cllevvalorpgtos->numrows>0){

      $cllevvalorpgtos->excluir($y63_sequencia);
      $erro_msg=$cllevvalorpgtos->erro_msg;
      if($cllevvalorpgtos->erro_status==0){
	      $sqlerro=true;
      }
    }
  }

  //rotina para que caso já tenha registro na tabela  apagar esses registros
  if(!$sqlerro){

    $result = $cllevantanotas->sql_record($cllevantanotas->sql_query_file(null,"*","","y79_sequencia=$y63_sequencia"));
    if($cllevantanotas->numrows>0){

      $cllevantanotas->excluir("","y79_sequencia = $y63_sequencia");
      $erro_msg=$cllevantanotas->erro_msg;
      if($cllevantanotas->erro_status==0){
	      $sqlerro=true;
      }
    }
  }

  if(!$sqlerro){

    $cllevvalor->excluir($y63_sequencia);
    $erro_msg=$cllevvalor->erro_msg;
    if($cllevvalor->erro_status==0){
      $sqlerro=true;
    }
  }
  if(!$sqlerro){
    $opcao='excluir';
  }
  db_fim_transacao($sqlerro);
}elseif(isset($opcao)){

  $notas   = '';
  $valores = '';
   //rotina para trazer os campos da tabela
   $result = $cllevvalor->sql_record($cllevvalor->sql_query_file("","levvalor.*","","y63_sequencia=$y63_sequencia and  y63_codlev=$y63_codlev"));
   db_fieldsmemory($result,0);

   $apagar = ($y63_bruto*$y63_aliquota)/100;
   $result = $cllevvalorpgtos->sql_record($cllevvalorpgtos->sql_query_file("$y63_sequencia","","y68_valor,y68_pgto"));
   $numrows=$cllevvalorpgtos->numrows;
   if($numrows>0){

     $valores = '';
     $vir     = '';
     for($r=0; $r<$numrows; $r++){
       db_fieldsmemory($result,$r);
       $valores.=$vir.$y68_valor.'-'.$y68_pgto_dia.'/'.$y68_pgto_mes.'/'.$y68_pgto_ano;
       $vir="HHH";
     }
   }

   //traz os dados da tabela levantanotas
   $result = $cllevantanotas->sql_record($cllevantanotas->sql_query_file(null,"*","y79_ordem","y79_sequencia=$y63_sequencia"));
   $numrows= $cllevantanotas->numrows;
   if($numrows>0){

     $notas='';
     $vir='';
     for($r=0; $r<$numrows; $r++){

       db_fieldsmemory($result,$r);
       $notas .= $vir.$y79_documento.'_sep_'.$y79_valor.'_sep_'.$y79_data_dia.'/'.$y79_data_mes.'/'.$y79_data_ano.'_sep_'.$y79_ordem;
       $vir="HHH";
     }
   }
   $db_botao = true;
}

if(isset($db_opcaoal)){

  $db_opcao=33;
  $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){

  $db_opcao = 2;
  $db_botao=true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){

  $db_opcao = 3;
  $db_botao=true;
}else{

  $db_opcao = 1;
  $db_botao=true;
}

if((isset($novo) && $novo=="ok")|| (isset($sqlerro) && $sqlerro==false)){

    $y63_sequencia='';
    $y63_mes++;
    if($y63_dtvenc_mes<12){

      $y63_dtvenc_mes++;
      $y63_dtvenc_mes= (strlen($y63_dtvenc_mes)==1?"0$y63_dtvenc_mes":"$y63_dtvenc_mes");
    }else{

      $y63_dtvenc_mes='01';
      $y63_dtvenc_ano++;
    }

	if($y63_mes > 12 && $db_opcao == 1){
	 $y63_ano++;
	}

  $y63_bruto    = '';
  $y63_aliquota = '';
  $y63_pago     = '';
  $y63_saldo    = '';
  $y63_histor   = '';
  $valores      = '';
  $notas        = '';
  $apagar       = '';
  unset($novo);
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
<body onLoad="document.form1.y63_bruto.focus();" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?
	include("forms/db_frmlevvalor.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?php
if(isset($incluir) || isset($alterar) || isset($excluir)){

  if($cllevvalor->erro_status=="0"){

    db_msgbox($erro_msg);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllevvalor->erro_campo!=""){

      echo "<script> document.form1.".$cllevvalor->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllevvalor->erro_campo.".focus();</script>";
    }
  }
}
?>