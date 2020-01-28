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
$clchamadas->rotulo->label();
$clrotulo = new rotulocampo;
?>
<form name="form1" method="post" action="">
<center><br>
<table width="700" border="1" cellpadding="0" cellspacing="0">
  <tr bgcolor="#f3f3f3">
   <td colspan="4">
    <b>Data:</b> <?=substr($data,8,2)?>/<?=substr($data,5,2)?>/<?=substr($data,0,4)?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Turma:</b> <?=$nometurma?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Disciplina:</b> <?=$nomedisciplina?>
   </td>
  </tr>
  <?
  $result = $clchamadas->sql_record($clchamadas->sql_query("","*",""," ed22_i_disciplina = $disciplina "));
  $linhas = $clchamadas->numrows;
  if($linhas==0){
   ?>
   <script>
    alert("A Turma informada não possui chamada cadastrada.");
    parent.document.formaba.a2.disabled=true;
    parent.mo_camada('a1');
   </script>
   <?
   exit;
  }
  $sql = "select * from chamadas
             inner join matriculas on ed22_i_matricula = ed09_i_codigo
             inner join series on ed03_i_codigo = ed09_i_serie
             inner join alunos on ed07_i_codigo = ed09_i_aluno
             inner join cgm    on ed07_i_codigo = z01_numcgm
         where ed22_i_disciplina = $disciplina and ed22_d_data = '$data'
         ";
  $result = $clmatriculas->sql_record($sql);
  $clmatriculas->numrows;
  ?>
  <tr bgcolor="#aaccff">
   <td align="center" width="10%"><b>Matrícula</b></td>
   <td align="center" width="45%"><b>Aluno</b></td>
   <td width="5%" align="center"><b>Presente</b></td>
   <td align="center" width="40%"><b>Observações</b></td>
  </tr>
  <?
  for($x=0;$x < $clmatriculas->numrows; $x++){
  db_fieldsmemory($result,$x);
  ?>
  <tr>
   <td align="center" bgcolor="#eaeaea"><?=str_pad($ed09_i_codigo,7,0,str_pad_left)?></td>
   <td>&nbsp;<?=$z01_nome?></td>
   <td align="center" bgcolor="#eaeaea"><?if($ed22_c_presenca=="S"){echo "SIM";}else{echo "NÃO";}?></td>
   <td align="center">&nbsp;<?=$ed22_c_descr?></td>
  </tr>
  <?
  }
  ?>
  </table><br>
  </center>
</form>