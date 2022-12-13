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
include("classes/db_lab_horario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllab_horario = new cl_lab_horario;
$db_opcao = 1;
$db_botao = true;
$db_botao1 = false;

if(isset($opcao)){
   if( $opcao == "alterar"){
       $db_opcao = 2;
       $db_botao1 = true;
   }else{
       if( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
           $db_opcao = 3;
           $db_botao1 = true;
       }else{
           if(isset($alterar)){
              $db_opcao = 2;
              $db_botao1 = true;
           }
       }
   }
}

if(isset($incluir)){
   //Monta array dos dias da semana marcados
   $sDia  = isset($chk_seg)?$chk_seg.", ":"";
   $sDia .= isset($chk_ter)?$chk_ter.", ":"";
   $sDia .= isset($chk_qua)?$chk_qua.", ":"";
   $sDia .= isset($chk_qui)?$chk_qui.", ":"";
   $sDia .= isset($chk_sex)?$chk_sex.", ":"";
   $sDia .= isset($chk_sab)?$chk_sab.", ":"";
   $sDia .= isset($chk_dom)?$chk_dom.", ":"";
   $sDia  = substr( $sDia, 0, strlen($sDia)-2 ); // tira o ', ' do final da string
   
   $dValidadeIni = trim($la35_d_valinicio);
   $dValidadeFim = trim($la35_d_valfim);

   if(!empty($dValidadeIni)) {
     
     $aTmp = explode('/',$dValidadeIni);
     $dValidadeIni = $aTmp[2].'-'.$aTmp[1].'-'.$aTmp[0];

   } else {
     
     $dValidadeIni = '1000/01/01';
   
   }

   if(!empty($dValidadeFim)) {
     
     $aTmp = explode('/',$dValidadeFim);
     $dValidadeFim = $aTmp[2].'-'.$aTmp[1].'-'.$aTmp[0];

   } else {
     
     $dValidadeFim = '1000/01/01';
   
   }

  $sWhereInterseccaoHorarios = " la35_i_setorexame = $la35_i_setorexame 
                                  and la35_i_diasemana in ($sDia) 
                                  and (('$la35_c_horaini' between la35_c_horaini and la35_c_horafim)
                                    or ('$la35_c_horafim' between la35_c_horaini and la35_c_horafim)
                                    or (la35_c_horaini between '$la35_c_horaini' and '$la35_c_horafim'))
                                  and (la35_d_valfim is null or la35_d_valinicio is null or '$dValidadeIni' = '1000/01/01' or 
                                       '$dValidadeFim' = '1000/01/01' or '$dValidadeIni' between la35_d_valinicio and la35_d_valfim or
                                       '$dValidadeFim' between la35_d_valinicio and la35_d_valfim or
                                       la35_d_valinicio between '$dValidadeIni' and '$dValidadeFim')";

   $sSql = $cllab_horario->sql_query(null, 'la35_i_diasemana', null, $sWhereInterseccaoHorarios);

   $rsLab_horario = $cllab_horario->sql_record($sSql);

   if($cllab_horario->numrows > 0) {
     $lCfm = false; // tem conflito de horarios
   } else {
     $lCfm = true; // nao tem conflito de horarios
   }

   if($lCfm==true) {
       db_inicio_transacao();
       if($rad_periodo==1){
           $aDia = explode(",", $sDia);
           for( $iCont=0; $iCont<sizeof($aDia); $iCont++){
                $cllab_horario->la35_i_diasemana = $aDia[$iCont];
                $cllab_horario->incluir(null);
           }
       }else{
           
           $aDia = explode(",", $sDia);
           $dias_da_semana=array();
           for( $iCont=0; $iCont<sizeof($aDia); $iCont++){
                $dias_da_semana[$aDia[$iCont]]=0;
           }
           //Verificando se é Quinzenal1(1) ou Mensal(3)
           if($rad_periodo==2){
               $escape=1;
           }else{
               //                 0          1          2          3          4           5         6           7
               $escape=array($semanames,$semanames,$semanames,$semanames,$semanames,$semanames,$semanames,$semanames);
           }
           $vet=explode("/",$la35_d_valinicio);
           $la35_d_valinicio=$vet[2]."-".$vet[1]."-".$vet[0];
           $vet=explode("/",$la35_d_valfim);
           $la35_d_valfim=$vet[2]."-".$vet[1]."-".$vet[0];
           $d2=strtotime($la35_d_valfim);
           //$cllab_horario->incluir(null);
           //For percorre o periodo das datas de validades
           for($d1=strtotime($la35_d_valinicio);$d1 <= $d2;$d1=$d1+86400){
               //echo"<br>for: ".date("d/m/Y",$d1)." { <br> escape=";print_r($escape);echo"<br>dias_da_semana= ";print_r($dias_da_semana);
               foreach ($dias_da_semana as $chave => $valor){
                    if($rad_periodo==2){
                       //escape Quinzenal
                       if(((date("w",$d1)+1)==$chave)&&($dias_da_semana[$chave]==0)){
                          
                          $cllab_horario->la35_i_diasemana = (int)trim($chave);
                          $cllab_horario->la35_d_valinicio = date("Y-m-d",$d1);
                          $cllab_horario->la35_d_valfim = date("Y-m-d",$d1);
                          $cllab_horario->incluir(null);
                          //echo"<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data da agenda:".date("d/m/Y",$d1)." dia da semana $chave";
                          $dias_da_semana[$chave]=$escape;
                       
                       }else{
                          if((date("w",$d1)+1)==$chave){
                             $dias_da_semana[$chave]=$dias_da_semana[$chave]-1;
                          }
                       }
                    }
                    //escape mensal
                    if($rad_periodo==3){
                       //echo"<br>&nbsp;&nbsp;&nbsp;&nbsp;if(((".(date("w",$d1)+1)."==$chave)&&(".date("m",$d1)."!=$dias_da_semana[$chave])){";
                       if(((date("w",$d1)+1)==$chave)&&(date("m",$d1)!=$dias_da_semana[$chave])){
                           if($escape[trim($chave)]==0){ // @ para evitar o erro desconhecido
                               
                               $cllab_horario->la35_i_diasemana = (int)trim($chave);
                               $cllab_horario->la35_d_valinicio = date("Y-m-d",$d1);
                               $cllab_horario->la35_d_valfim = date("Y-m-d",$d1);
                               $cllab_horario->incluir(null);
                               //echo"<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data da agenda:".date("d/m/Y",$d1)." dia da semana $c
                               $dias_da_semana[$chave]=date("m",$d1);
                               $escape[trim($chave)]=$semanames;

                            }else{

                               if(((date("w",$d1)+1)==$chave)&&(date("m",$d1)!=$dias_da_semana[$chave])){
                                  //echo"Escape of $chave  ".$escape[trim($chave)]."-1";
                                  $escape[trim($chave)]=$escape[trim($chave)]-1;
                               }

                            }
                        }
                    }
                }
            }
       
       }
     db_fim_transacao();
   } else {
     echo "<script>alert('Nao foi possivel cadastrar. Conflito com horarios ja existentes.');</script>";
   }
}else if(isset($alterar)) {

   $dValidadeIni = trim($la35_d_valinicio);
   $dValidadeFim = trim($la35_d_valfim);

   if(!empty($dValidadeIni)) {
     
     $aTmp = explode('/',$dValidadeIni);
     $dValidadeIni = $aTmp[2].'-'.$aTmp[1].'-'.$aTmp[0];

   } else {
     
     $dValidadeIni = '1000/01/01';
   
   }

   if(!empty($dValidadeFim)) {
     
     $aTmp = explode('/',$dValidadeFim);
     $dValidadeFim = $aTmp[2].'-'.$aTmp[1].'-'.$aTmp[0];

   } else {
     
     $dValidadeFim = '1000/01/01';
   
   }

   $sWhereInterseccaoHorarios = " la35_i_codigo != $la35_i_codigo
                                  and la35_i_setorexame = $la35_i_setorexame 
                                  and la35_i_diasemana = (select la35_i_diasemana from lab_horario where la35_i_codigo = $la35_i_codigo) 
                                  and (('$la35_c_horaini' between la35_c_horaini and la35_c_horafim)
                                    or ('$la35_c_horafim' between la35_c_horaini and la35_c_horafim)
                                    or (la35_c_horaini between '$la35_c_horaini' and '$la35_c_horafim'))
                                  and (la35_d_valfim is null or la35_d_valinicio is null or '$dValidadeIni' = '1000/01/01' or 
                                       '$dValidadeFim' = '1000/01/01' or '$dValidadeIni' between la35_d_valinicio and la35_d_valfim or
                                       '$dValidadeFim' between la35_d_valinicio and la35_d_valfim or
                                       la35_d_valinicio between '$dValidadeIni' and '$dValidadeFim')";

 $sSql = $cllab_horario->sql_query(null, 'la35_i_diasemana', null, $sWhereInterseccaoHorarios);
   
   $rsLab_horario = $cllab_horario->sql_record($sSql);

   if($cllab_horario->numrows > 0) {
     $lCfm = false; // tem conflito de horarios
   } else {
     $lCfm = true; // nao tem conflito de horarios
   }

  if($lCfm) {

    db_inicio_transacao();
    $cllab_horario->alterar($la35_i_codigo);
    db_fim_transacao();

   } else {
     echo "<script>alert('Nao foi possivel alterar. Conflito com horarios ja existentes.');</script>";
   }

  $db_opcao = 2;
  $db_botao1 = true;

}else if(isset($excluir)){
  db_inicio_transacao();
  $db_opcao = 3;
   $db_botao1 = true;
  $cllab_horario->excluir($la35_i_codigo);
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2; 
   $result = $cllab_horario->sql_record($cllab_horario->sql_query($chavepesquisa));
   if($cllab_horario->numrows>0){
      db_fieldsmemory($result,0);
      $db_botao = true;
   }else{
      $la35_i_laboratorio=$chavepesquisa;
      $db_opcao=1;
   }
}
 
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
</table>
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlab_horario.php");
	?>
    </center>
	</td>
  </tr>
</table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","la35_i_setorexame",true,1,"la35_i_setorexame",true);
</script>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
  if($cllab_horario->erro_status=="0"){
    $cllab_horario->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_horario->erro_campo!=""){
      echo "<script> document.form1.".$cllab_horario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_horario->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_horario->erro(true,false);
    db_redireciona("lab1_lab_horario001.php?la02_i_codigo=$la02_i_codigo&la02_c_descr=$la02_c_descr");
  }
}
?>