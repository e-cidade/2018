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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$db_opcao = 1;
$codescola = db_getsession("DB_coddepto");
$escola = db_getsession("DB_nomedepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
}
.cabec{
 font-size: 11;
 color: #444444;
 background-color:#999999;
}
.aluno{
 font-size: 10;
}
</style>
</head>
<body bgcolor="#cccccc" leftmargin="15" marginheight="0" marginwidth="3" topmargin="5">
<table width="100%" align="left" valign="top" marginwidth="0" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left">
   <?
   if(isset($ed52_i_codigo)){
    $sql = "SELECT DISTINCT ed29_i_codigo,ed29_c_descr,ed10_c_descr,ed10_c_abrev
            FROM cursoedu
              inner join cursoescola on ed71_i_curso = ed29_i_codigo
              inner join ensino on ed10_i_codigo = ed29_i_ensino
              inner join base on ed31_i_curso = ed29_i_codigo
              inner join turma on ed57_i_base = ed31_i_codigo
              inner join matricula on ed60_i_turma = ed57_i_codigo
             WHERE ed57_i_calendario = $ed52_i_codigo
             AND ed71_i_escola = $codescola
             ORDER BY ed10_c_abrev
            ";
    $titulo = "Cursos no Calend�rio $ed52_c_descr";
    $labels = "C�digo|Curso|Ensino";
    $destino = "edu1_diarioclasse003.php?ed52_c_descr=$ed52_c_descr&calendario=$ed52_i_codigo&ed29_i_codigo=";
   }
   if(isset($ed29_i_codigo)){
    $sql = "SELECT DISTINCT ed218_i_codigo,ed218_c_nome
            FROM regimemat
             inner join base on ed31_i_regimemat = ed218_i_codigo
             inner join turma on ed57_i_base = ed31_i_codigo
             inner join matricula on ed60_i_turma = ed57_i_codigo
             inner join escolabase on ed77_i_base = ed31_i_codigo
            WHERE ed31_i_curso = $ed29_i_codigo
            AND ed57_i_calendario = $calendario
            AND ed77_i_escola = $codescola
            ORDER BY ed218_i_codigo
            ";
    $titulo = "Regimes de Matr�cula do Curso $proximo";
    $labels = "C�digo|Regime Matr�cula";
    $destino = "edu1_diarioclasse003.php?ed52_c_descr=$ed52_c_descr&calendario=$calendario&ed29_i_codigo=$ed29_i_codigo&ed218_i_codigo=";
   }
   if(isset($ed218_i_codigo)){
    $sql = "SELECT DISTINCT ed31_i_codigo,ed31_c_descr,ed218_c_nome
            FROM base
              inner join turma on ed57_i_base = ed31_i_codigo
              inner join matricula on ed60_i_turma = ed57_i_codigo
              inner join escolabase on ed77_i_base = ed31_i_codigo
              inner join regimemat on ed218_i_codigo = ed31_i_regimemat
             WHERE ed31_i_curso = $ed29_i_codigo
             AND ed57_i_calendario = $calendario
             AND ed77_i_escola = $codescola
             AND ed31_i_regimemat = $ed218_i_codigo
             ORDER BY ed31_c_descr desc
            ";
    $titulo = "Bases Curriculares do Regime de Matr�cula $proximo";
    $labels = "C�digo|Base Curricular|Regime Matr�cula";
    $destino = "edu1_diarioclasse003.php?ed52_c_descr=$ed52_c_descr&calendario=$calendario&ed31_i_regimemat=$ed218_i_codigo&ed31_i_codigo=";
   }
   if(isset($ed31_i_codigo)){
    $sql3 = "SELECT si.ed11_i_sequencia as inicial,sf.ed11_i_sequencia as final,si.ed11_i_ensino as ensino
             FROM baseserie
              inner join serie as si on si.ed11_i_codigo = baseserie.ed87_i_serieinicial
              inner join serie as sf on sf.ed11_i_codigo = baseserie.ed87_i_seriefinal
             WHERE ed87_i_codigo = $ed31_i_codigo
            ";
    $query3 = pg_query($sql3);
    $sql = "SELECT DISTINCT ed11_i_codigo,ed11_c_descr,ed11_i_sequencia
            FROM turma
             inner join matricula on ed60_i_turma = ed57_i_codigo
             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
             inner join serie on ed11_i_codigo = ed221_i_serie
             inner join base on ed31_i_codigo = ed57_i_base
            WHERE ed11_i_sequencia >= ".pg_result($query3,0,"inicial")." AND ed11_i_sequencia <= ".pg_result($query3,0,"final")." AND ed11_i_ensino = ".pg_result($query3,0,"ensino")."
            AND ed57_i_calendario = $calendario
            AND ed57_i_escola = $codescola
            AND ed31_i_regimemat = $ed31_i_regimemat
            AND ed221_c_origem = 'S'
            ORDER BY ed11_i_sequencia
            ";
    $titulo = "Etapas da Base Curricular $proximo";
    $labels = "C�digo|Etapa|";
    $destino = "edu1_diarioclasse003.php?ed52_c_descr=$ed52_c_descr&calendario=$calendario&ed31_i_regimemat=$ed31_i_regimemat&ed11_i_codigo=";
   }
   if(isset($ed11_i_codigo)){
    $sql = "SELECT DISTINCT ed57_i_codigo,ed57_c_descr,ed57_i_numvagas,ed57_i_nummatr
            FROM turma
             inner join matricula on ed60_i_turma = ed57_i_codigo
             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
             inner join base on ed31_i_codigo = ed57_i_base
            WHERE ed221_i_serie in ($ed11_i_codigo)
            AND ed57_i_calendario = $calendario
            AND ed57_i_escola = $codescola
            AND ed31_i_regimemat = $ed31_i_regimemat
            AND ed221_c_origem = 'S'
            ORDER BY ed57_c_descr
           ";
    $titulo = "Turmas de $proximo em $ed52_c_descr";
    $labels = "C�digo|Turma|N� Vagas|N� Matr�culas";
    $destino = "edu1_diarioclasse004.php?ed52_c_descr=$ed52_c_descr&codserieregencia=$ed11_i_codigo&turma=";
    $mudar = true;
   }
   $result = pg_query($sql);
   $linhas = pg_num_rows($result);
   $ncampos = pg_num_fields($result);
   ?>
   <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
    <tr>
     <td class='titulo' align="center" colspan="<?=$ncampos?>"><b><?=$titulo?></b></td>
    </tr>
    <tr>
     <?
     $labels = explode("|",$labels);
     for($c=0;$c<$ncampos;$c++){
      ?>
      <td class='cabec' align="center"><b><?=$labels[$c]?></b></td>
     <?}?>
    </tr>
    <?
    if($linhas>0){
     $cor1 = "#f3f3f3";
     $cor2 = "#DBDBDB";
     $cor = "";
     for($c=0;$c<$linhas;$c++){
      if($cor==$cor1){
       $cor = $cor2;
      }else{
       $cor = $cor1;
      }
      $cod = pg_result($result,$c,0);
      $proximo = pg_result($result,$c,1);
      if(isset($mudar) && $mudar==true){?>
       <tr bgcolor="<?=$cor?>" onclick="javascript:location.href='<?=$destino.$cod?>&ed57_c_descr=<?=$proximo?>'" style="Cursor='hand';" onmouseover="bgColor='#DEB887'" onmouseout="bgColor='<?=$cor?>'">
      <?}else{?>
       <tr bgcolor="<?=$cor?>" onclick="javascript:location.href='<?=$destino.$cod?>&proximo=<?=$proximo?>'" style="Cursor='hand';" onmouseover="bgColor='#DEB887'" onmouseout="bgColor='<?=$cor?>'">
      <?}
       for($b=0;$b<$ncampos;$b++){
        if(pg_field_type($result,$b)=="int4"){
         $align = "center";
        }else{
         $align = "";
        }
        ?>
         <td class='aluno' align="<?=$align?>"><?=pg_result($result,$c,$b)?></td>
       <?}?>
      </tr>
      <?
     }
    }?>
   </table>
  </td>
 </tr>
</table>
</body>
</html>
<script>
 parent.document.getElementById("tab_aguarde").style.visibility = "hidden";
</script>