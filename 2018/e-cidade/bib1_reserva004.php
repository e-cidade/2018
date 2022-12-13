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
include("classes/db_reserva_classe.php");
include("classes/db_carteira_classe.php");
include("classes/db_biblioteca_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clreserva = new cl_reserva;
$clcarteira = new cl_carteira;
$clbiblioteca = new cl_biblioteca;
$db_opcao = 1;
$db_botao = true;
$depto = db_getsession("DB_coddepto");
$result = $clbiblioteca->sql_record($clbiblioteca->sql_query("","bi17_codigo",""," bi17_coddepto = $depto"));
if($clbiblioteca->numrows!=0){
 db_fieldsmemory($result,0);
}
function somadata($dias,$ano,$mes,$dia){
 //$dia = date("d");
 //$mes = date("m");
 //$ano = date("Y");
 $i = $dias;
 for($i = 0;$i<$dias;$i++){
  if ($mes == 01 || $mes == 03 || $mes == 05 || $mes == 07 || $mes == 8 || $mes == 10 || $mes == 12){
   if($mes == 12 && $dia == 31){
    $mes = 01;
    $ano++;
    $dia = 00;
   }
   if($dia == 31 && $mes != 12){
    $mes++;
    $dia = 00;
   }
  }//FECHA IF GERAL
  if($mes == 04 || $mes == 06 || $mes == 09 || $mes == 11){
   if($dia == 30){
    $dia =  00;
    $mes++;
   }
  }//FECHA IF GERAL
  if($mes == 02){
   if($ano % 4 == 0){//ANO BISSEXTO
    if($dia == 29){
     $dia = 00;
    }
   }else{
    if($dia == 28){
     $dia = 00;
    }
   }
  }//FECHA IF DO MÊS 2
  $dia++;
 }//FECHA O FOR()
 if($dia==1){$dia="01";}
 if($dia==2){$dia="02";}
 if($dia==3){$dia="03";}
 if($dia==4){$dia="04";}
 if($dia==5){$dia="05";}
 if($dia==6){$dia="06";}
 if($dia==7){$dia="07";}
 if($dia==8){$dia="08";}
 if($dia==9){$dia="09";}
 if($mes==1){$mes="01";}
 if($mes==2){$mes="02";}
 if($mes==3){$mes="03";}
 if($mes==4){$mes="04";}
 if($mes==5){$mes="05";}
 if($mes==6){$mes="06";}
 if($mes==7){$mes="07";}
 if($mes==8){$mes="08";}
 if($mes==9){$mes="09";}
 $data_volta = $ano."-".$mes."-".$dia;
 return $data_volta;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:92%"><legend><b>Acervos devolvidos recentemente que estão reservados:</b></legend>
    <?
    $campos = "case
                when aluno.ed47_i_codigo is not null
                 then aluno.ed47_v_nome
                when cgmrh.z01_numcgm is not null
                 then cgmrh.z01_nome
                when cgmcgm.z01_numcgm is not null
                 then cgmcgm.z01_nome
                else
                 cgmpub.z01_nome
               end as z01_nome,
               bi14_codigo,
               bi14_carteira,
               bi14_datareserva,
               bi14_hora,
               bi14_acervo,
               bi06_titulo,
               bi07_tempo
           ";
    $result = $clreserva->sql_record($clreserva->sql_query("",$campos,""," bi14_acervo in ($codacervos) AND bi14_retirada is null"));
    if($clreserva->numrows>0){
     ?>
     <table width="700" border="1" cellspacing="0" cellpadding="0">
      <tr align="center" bgcolor="#888888">
       <td><b>Código</b></td>
       <td><b>Acervo</b></td>
       <td><b>Leitor</b></td>
       <td><b>Data Reserva</b></td>
       <td><b>Hora</b></td>
       <td></td>
      </tr>
     <?
     for($y=0;$y<$clreserva->numrows;$y++){
      db_fieldsmemory($result,$y);
      $sql1 = "SELECT bi23_codigo
               FROM exemplar
                inner join acervo on acervo.bi06_seq = exemplar.bi23_acervo
               WHERE not exists(select * from emprestimoacervo
                                where emprestimoacervo.bi19_exemplar = exemplar.bi23_codigo
                                and not exists(select *
                                               from devolucaoacervo
                                               where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                              )
                                )
               AND bi23_situacao = 'S'
               AND bi06_biblioteca = $bi17_codigo
               AND bi06_seq = $bi14_acervo
               LIMIT 1
              ";
      $result1 = pg_query($sql1);
      $lista = pg_result($result1,0,'bi23_codigo');
      $codreserva = $bi14_codigo;
      $leitor = $bi14_carteira;
      $nomeleitor = $z01_nome;
      $devolucao = somadata($bi07_tempo,date("Y"),date("m"),date("d"));
      ?>
      <tr bgcolor="#f3f3f3">
       <td align="center" >
        <?=$bi14_codigo?>
       </td>
       <td>
        <?=$bi06_titulo?>
       </td>
       <td align="center">
        <?=$z01_nome?>
       </td>
       <td align="center">
        <?=db_formatar($bi14_datareserva,'d')?>
       </td>
       <td align="center">
        <?=$bi14_hora?>
       </td>
       <td align="center">
        <input type="button" name="emprestimo" value="Empréstimo" onclick="location.href='bib1_emprestimo001.php?leitor=<?=$leitor?>&nomeleitor=<?=$nomeleitor?>&lista=<?=$lista?>&dev=<?=$devolucao?>&reserva=<?=$codreserva?>'">
       </td>
      </tr>
      <?
     }
     ?></table><?
    }else{
     echo "Nenhuma reserva para os acervos devolvidos recentemente.";
    }
    ?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>