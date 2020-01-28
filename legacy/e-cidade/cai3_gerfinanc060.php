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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("classes/db_notificacao_classe.php");
include("classes/db_notiusu_classe.php");
include("classes/db_notidebitos_classe.php");
include("classes/db_notimatric_classe.php");
include("classes/db_notiinscr_classe.php");
include("classes/db_notinumcgm_classe.php");
include("classes/db_notitipo_classe.php");
include("classes/db_notiagenda_classe.php");
include("classes/db_notisitu_classe.php");
include("classes/db_noticonf_classe.php");
include("dbforms/db_funcoes.php");
$clnotificacao = new cl_notificacao;
$clnotiusu = new cl_notiusu;
$clnotidebitos = new cl_notidebitos;
$clnotiagenda = new cl_notiagenda;
$clnotiagenda->rotulo->label();
$clnotitipo = new cl_notitipo;
$clnotificacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('k51_descr');
$clnotisitu = new cl_notisitu;
$clnotisitu->rotulo->label();
$clnoticonf = new cl_noticonf;
$clnoticonf->rotulo->label();

$db_opcao = 2;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

db_postmemory($HTTP_POST_VARS);

if(isset($db_datausu)){
  if(!checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))){
     echo "Data para Cálculo Inválida. <br><br>";
     echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
	 exit;
  }
  if(mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) < 
     mktime(0,0,0,date('m',db_getsession("DB_datausu")),date('d',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu"))) ){
     echo "Data não permitida para cálculo. <br><br>";
     echo "Data deverá se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
	 exit;
  }
  $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
}else{
  $DB_DATACALC = db_getsession("DB_datausu");
}
if(isset($notificacao)){
  
  db_inicio_transacao();
  $sql_erro = false;
  $clnotificacao->k50_instit = db_getsession('DB_instit'); 
  $clnotificacao->incluir('');
  if($clnotificacao->erro_status == "0"){
    $sql_erro == true;
    $clnotificacao->erro(true,false);
  }else{
    $clnotiusu->k52_notifica = $clnotificacao->k50_notifica;
    $clnotiusu->k52_id_usuario = db_getsession("DB_id_usuario");
    $clnotiusu->k52_data = date('Y-m-d',db_getsession("DB_datausu"));
    $clnotiusu->k52_hora = date('H:i');
    $clnotiusu->incluir($clnotificacao->k50_notifica);
    if($clnotiusu->erro_status == "0"){
      $sql_erro == true;
      $clnotiusu->erro(true,false);
    }else{
      if($chave1=="matric"){
        $clnotimatric = new cl_notimatric;
	$clnotimatric->notifica = $clnotificacao->k50_notifica;
	$clnotimatric->matric = $matric;
	$clnotimatric->incluir($clnotificacao->k50_notifica,$matric);
        if($clnotimatric->erro_status == "0"){
          $sql_erro == true;
          $clnotimatric->erro(true,false);
	}
      }else if($chave1 == "inscr"){
        $clnotiinscr = new cl_notiinscr;
	$clnotiinscr->notifica = $clnotificacao->k50_notifica;
	$clnotiinscr->inscr = $inscr;
	$clnotiinscr->incluir($clnotificacao->k50_notifica,$inscr);
        if($clnotiinscr->erro_status == "0"){
          $sql_erro == true;
          $clnotimatric->erro(true,false);
	}
      }else if($chave1 == "numcgm"){
        $clnotinumcgm = new cl_notinumcgm;
	$clnotinumcgm->notifica = $clnotificacao->k50_notifica;
	$clnotinumcgm->numcgm = $numcgm;
	$clnotinumcgm->incluir($clnotificacao->k50_notifica,$numcgm);
        if($clnotimatric->erro_status == "0"){
          $sql_erro == true;
          $clnotimatric->erro(true,false);
	}
      }
      if($k58_data_dia!=""){
	$clnotiagenda->k58_notifica = $clnotificacao->k50_notifica;
	if($k58_hora==""){
	   $HTTP_POST_VARS['k58_hora'] = date("H:i",db_getsession("DB_datausu"));
	}
	$clnotiagenda->k58_id_usuario = db_getsession("DB_id_usuario");
	$clnotiagenda->incluir("");
        if($clnotiagenda->erro_status == "0"){
          $sql_erro == true;
          $clnotiagenda->erro(true,false);
	}
      }
      if($k54_codigo!=0){

	 
	$clnoticonf->k54_notifica = $clnotificacao->k50_notifica;
	$clnoticonf->k54_assinante= $k54_assinante;
	$clnoticonf->k54_data     = date("Y-m-d",db_getsession("DB_datausu"));
	$clnoticonf->k54_hora     = date("H:i");
	$clnoticonf->k54_obs      = $k54_obs;
	$clnoticonf->k54_codigo   = $k54_codigo;
	$clnoticonf->incluir($clnotificacao->k50_notifica);
        if($clnoticonf->erro_status == "0"){
          $sql_erro == true;
          $clnoticonf->erro(true,false);
	}
      }

      if($sql_erro != true ){

	    $numpres = "select distinct a.k00_numpre,a.k00_numpar
			from arrecad a 
			inner join arreinstit on arreinstit.k00_numpre = a.k00_numpre 
                      		 and arreinstit.k00_instit = ".db_getsession('DB_instit')." ";
	    if($chave1=="matric"){
	       $numpres .= " inner join arrematric m on m.k00_numpre = a.k00_numpre "; 
	    }else if($chave1 == "inscr"){
	       $numpres .= " inner join arreinscr m on m.k00_numpre = a.k00_numpre "; 
	    }else if($chave1 == "numcgm"){
	       $numpres .= " inner join arrenumcgm m on m.k00_numpre = a.k00_numpre "; 
	    }
	    $numpres .= " where ";
	    if($chave1=="matric"){
	       $numpres .= " m.k00_matric = $matric and "; 
	    }else if($chave1 == "inscr"){
	       $numpres .= " m.k00_inscr = $inscr and "; 
	    }else if($chave1 == "numcgm"){
	       $numpres .= " m.k00_numcgm = $numcgm and "; 
	    }else{
	       $numpres .= " a.k00_numpre = $numpre and "; 
	    }
	    $numpres .= " k00_tipo in (";
	    $num = split(",",$tipo);
	    $numm = "";
	    for($i=0;$i<sizeof($num);$i++){
	      $numpres .= $numm.$num[$i];
	      $numm = ",";
	    }
	    $numpres .= ")";
	    $result = pg_exec($numpres);
	    for($i=0;$i<pg_numrows($result);$i++){
	      db_fieldsmemory($result,$i);
	      $clnotidebitos->k53_notifica = $clnotificacao->k50_notifica;
	      $clnotidebitos->k53_numpre   = $k00_numpre;
	      $clnotidebitos->k53_numpar   = $k00_numpar;
	      $clnotidebitos->incluir($clnotificacao->k50_notifica,$k00_numpre,$k00_numpar);
	      if($clnotidebitos->erro_status == "0"){
		$sql_erro == true;
		$clnotidebitos->erro(true,false);
	      }
	    }
      }
    }
  }
  db_fim_transacao($sql_erro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.borda {
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden'"  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
$erro = false;
if(isset($matric)){
  $chave1 = "matric";
  $chave  = $matric;
}else if(isset($inscr)){
  $chave1 = "inscr";
  $chave = $inscr;
}else if(isset($numcgm)){
  $chave1 = "numcgm";
  $chave = $numcgm;
}else if(isset($numpre)){
  $chave1 = "numpre";
  $chave = $numpre;
}else if(isset($ver_matric) && $ver_matric!='0'){
  $chave1 = "matric";
  $chave = $ver_matric;
}else if(isset($ver_inscr) && $ver_inscr !='0'){
  $chave1 = "inscr";
  $chave = $ver_inscr;
}else if(isset($ver_numcgm) && $ver_numcgm !='0'){
   $chave1 = "numcgm";
   $chave = $ver_numcgm;
}else if(!isset($chave)){
   $chave1 = 0;
   $chave = 0;
}

if(isset($notificacao_tipo)){
  $vt = $HTTP_POST_VARS;
  $virgula = "";
  for($i = 0; $i < sizeof($vt) ;$i++) {
    if(db_indexOf(key($vt),"CHECK") > 0){
      $numpres .= $virgula.$vt[key($vt)];
      $virgula=",";
    }
    next($vt);
  }
}

?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap title="<?=@$Tk52_notifica?>" width="15%">
       <?
       echo $Lk50_notifica;
       ?>
    </td>
    <td width="15%"> 
       <?
       db_input('k50_notifica',8,$Ik50_notifica,true,'text',3,"");
       db_input('tipo',100,$Ik50_notifica,true,'hidden',3);
       db_input('numpres',100,$Ik50_notifica,true,'hidden',3);
       db_input('chave',100,$Ik50_notifica,true,'hidden',3);
       db_input('chave1',100,$Ik50_notifica,true,'hidden',3);
       ?>
    </td>
    <td width="70%" valign="top" rowspan="5">
       <table width="100%" cellspacing="0" cellpadding="0">
       <tr>
       <td>
          <fieldset><b>
           <legend>Agenda Nova Notificação:</legend>   </b><br>
	   <table width="100%" cellspacing="0" cellpadding="0">
	   <tr>
	   <td>
	   <?
	   echo $Lk58_data;
           ?>
	   </td>
	   <td>
	   <?
	   db_inputdata('k58_data','','','',true,'text',2,'');
	   ?>
	   <td>
	   <tr>
	   <td>
	   <?
	   echo $Lk58_hora;
	   ?>
	   </td>
	   <td>
	   <?
           db_input('k58_hora',6,$Ik58_hora,true,'text',2);
           ?>
	   </td>
	   </tr>
	   </table>
	   </fieldset>
       </td>
       </tr>
       <tr>
       <td>
          <fieldset><b>
           <legend>Encerra Notificação:</legend>   </b><br>
	   <table cellspacing="0" cellpadding="0">
	   <tr>
	   <td>
	   <?
	   echo $Lk54_codigo;
           ?>
	   </td>
           <td nowrap>
	   <?
	   $record = $clnotisitu->sql_record($clnotisitu->sql_query());
           db_selectrecord("k54_codigo",$record,true,2,'','','',"0-Aguarda Retorno");
           ?>
	   </td>
	   </tr>
	   <tr>
	   <td>
	   <?
	   echo $Lk54_assinante;
           ?>
	   </td>
           <td>
	   <?
           db_input('k54_assinante',30,$Ik54_assinante,true,'text',2);
           ?>
	   </td>
	   </tr>
	   <tr>
	   <td>
	   <?
	   echo $Lk54_obs;
           ?>
	   </td>
	   <td>
	   <?
           db_textarea("k54_obs",3,30,$Ik54_obs,true,'text',2);
           ?>
	   </td>
	   </tr>
	   </table>
       </td>
       </tr>
       </table>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk52_id_usuario?>">
       <?
       echo $Lk50_procede;
       ?>
    </td>
    <td> 
       <?
       $result = $clnotitipo->sql_record($clnotitipo->sql_query_file('','k51_procede,k51_descr'));
       db_selectrecord('k50_procede',$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk50_dtemite?>">
       <?=$Lk50_dtemite?>
    </td>
    <td> 
      <?
      db_inputdata('k50_dtemite',date('d',db_getsession('DB_datausu')),date('m',db_getsession('DB_datausu')),date('Y',db_getsession('DB_datausu')),true,'text',3)
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk50_obs?>">
       <?=@$Lk50_obs?>
    </td>
    <td> 
<?
db_textarea("k50_obs",3,40,$Ik50_obs,true,'text',$db_opcao);
?>
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">
   <input name="notificacao" value="Inclusão" type="submit">
  <input name="noticonsulta" value="Consulta" type="button" onclick="location.href='cai3_gerfinanc061.php?chave1=<?=$chave1?>&chave=<?=$chave?>'">
    </td>
  </tr>



  </table>

 </center>
</form>

</body>
</html>
<?
if(isset($sql_erro) && $sql_erro==false){
  echo "<script>
        alert('Notificação Incluida.');
	location.href='cai3_gerfinanc061.php?chave1=".$chave1."&chave=".$chave."';
	</script>";
}