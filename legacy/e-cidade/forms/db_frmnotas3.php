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

//MODULO: educação
$clnotas->rotulo->label();
$clrotulo = new rotulocampo;
//busca dados das avaliações acumuladas
$result = $clavaliacoes->sql_record($clavaliacoes->sql_query($ed13_i_codigo));
db_fieldsmemory($result,0);
?>
<form name="form1" method="post" action="">
<center>
 <table width="600" border="0" cellpadding="0" cellspacing="0">
  <tr height="40">
   <td colspan="4">
    <b>Turma:</b> <?=$ed05_c_nome?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Disciplina:</b> <?=$ed27_c_nome?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Período:</b> <?=$ed23_c_nome?>
   </td>
  </tr>
  <?
  $sql = "select * from turmas
           inner join escolas    on ed02_i_codigo = ed05_i_escola
           inner join matriculas on ed09_i_escola = ed02_i_codigo
           inner join series     on ed03_i_codigo = ed09_i_serie
           inner join alunos     on ed07_i_codigo = ed09_i_aluno
           inner join cgm        on ed07_i_codigo = z01_numcgm
          where ed05_i_codigo = $ed05_i_codigo
         ";
  $result = $clmatriculas->sql_record($sql);
  ?>
  <tr bgcolor="#F9F900">
   <td align="center" width="10%"><b>Matrícula</b></td>
   <td align="center" width="70%"><b>Aluno</b></td>
   <td width="10%" align="center"><b>Média</b>&nbsp;</td>
  </tr>
  <?
  $cor = "#bbbbbb";
  $cor2 = "#dddddd";
  $vermelho = "#ff9999";
  $verde = "#ccffcc";
  for($x=0;$x < $clmatriculas->numrows; $x++){
  db_fieldsmemory($result,$x);
  ?>
  <tr bgcolor="<?=$cor?>">
   <td align="center"><?=str_pad($ed09_i_codigo,7,0,str_pad_left)?></td>
   <td>&nbsp;<?=$z01_nome?></td>
   <td align="center">&nbsp;</td>
  </tr>
  <?
  $sql2 = "select * from notas
           where ed11_i_matriculas = $ed09_i_codigo
          ";
  $result2 = $clnotas->sql_record($sql2);
  ?>
  <tr bgcolor="<?=$cor2?>">
   <td bgcolor="#cccccc">&nbsp;</td>
   <td>
    <table border="1" bordercolor="#dddddd" cellpadding="0" cellspacing="0">
     <tr>
     <?
     $media = 0;
     for($y=0;$y < $clnotas->numrows; $y++){
     @db_fieldsmemory($result2,$y);
     ?>
     <td align="center" width="80" <?if($ed11_c_fechado=="t"){echo "bgcolor='$vermelho'";}else{echo "bgcolor='$verde'";}?>>
      <b><?=substr($ed11_d_data,8,2)?>/<?=substr($ed11_d_data,5,2)?>/<?=substr($ed11_d_data,0,4)?></b><br>
      <?=$ed11_f_media?>
     </td>
     <?
      $media += $ed11_f_media;
     }
     ?>
    </tr></table>
   </td>
   <td align="right"><?db_input('media',5,$media,true,'text',3,"")?></td>
  </tr>
  <?
  }
  ?>
  </table><br>
  <table cellpadding="5" cellspacing="5">
  <tr>
   <td>
    Legenda:
   </td>
   <td>
    <table cellpadding="0" cellspacing="0">
    <tr><td bgcolor="<?=$verde?>" height="20" width="150" align="center">Avaliação em Aberto</td></tr>
    </table>
   </td>
   <td>
    <table cellpadding="0" cellspacing="0">
    <tr><td bgcolor="<?=$vermelho?>" height="20" width="150" align="center">Avaliação Fechada</td></tr>
    </table>
   </td>
  </table>
  </center>
</form>
<script>
 function valida(tudo,documento){
   obj = document.form1;
   count = 0;
   linha='';
   sep = "x";
   for(i=0;i<tudo;i++){
    if(obj.presente[i].checked == true){
     linha += obj.presente[i].value+";S;";
     linha += obj.ed22_c_descr[i].value;
    }else{
     linha += obj.presente[i].value+";N;";
     linha += obj.ed22_c_descr[i].value;
    }
    linha += sep;
    count += 1;
   }
  location="<?=$REQUEST_URI?>&linha="+linha;
 }

</script>