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
<center>
<table width="95%" border="1" cellpadding="0" cellspacing="0">
  <tr>
   <td colspan="4">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Turma:</b> <?=$nometurma?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Disciplina:</b> <?=$nomedisciplina?>
   </td>
  </tr>
  <?
  $sql = "select * from turmas
           inner join escolas    on ed02_i_codigo = ed05_i_escola
           inner join matriculas on ed09_i_escola = ed02_i_codigo
           inner join series     on ed03_i_codigo = ed09_i_serie
           inner join alunos     on ed07_i_codigo = ed09_i_aluno
           inner join cgm        on ed07_i_codigo = z01_numcgm
          where ed05_i_codigo = $turma
         ";
  $result = $cl_disciplinas_series->sql_record($sql);
  $linhas = $cl_disciplinas_series->numrows;
  if($linhas==0){
   ?>
   <script>
    alert("A Turma informada não possui a Disciplina em sua grade.");
    parent.document.formaba.a2.disabled=true;
    parent.mo_camada('a1');
   </script>
   <?
   exit;
  }
   $sql = "select * from matriculas
             inner join alunos on ed07_i_codigo = ed09_i_aluno
             inner join cgm    on ed07_i_codigo = z01_numcgm
             inner join series on ed03_i_codigo = ed09_i_serie
             inner join turmas on ed05_i_serie  = ed03_i_codigo
           where ed05_i_codigo = $turma
          ";
  $result = $clmatriculas->sql_record($sql);
  ?>
  <tr bgcolor="#F9F900">
   <td align="center"><b>Matrícula</b></td>
   <td align="center" width="50%"><b>Aluno</b></td>
   <?for($x=1;$x<=15;$x++){?>
   <td width="5%" align="center"><?=$x?></td>
   <?}?>
  </tr>
  <?
  for($x=0;$x < $clmatriculas->numrows; $x++){
  db_fieldsmemory($result,$x);
  ?>
  <tr>
   <td align="center"><?=str_pad($ed09_i_codigo,7,0,str_pad_left)?></td>
   <td>&nbsp;<?=$z01_nome?></td>
   <?for($x=1;$x<=15;$x++){?>
   <td width="5%" align="center"><input type="checkbox" name="presente" value="<?=$ed09_i_codigo?>"></td>
   <?}?>
  </tr>
  <?
  }
  ?>
  </table><br>
   <input type="button" value="Marcar Todos" name="marca" title="Marcar/Desmarcar" onclick="marcar(<?=$clmatriculas->numrows?>, this)">
   <input type="button" value="Salvar" name="incluir" onclick="valida(<?=$clmatriculas->numrows?>,this)">
  </center>
</form>
<script>
 function marcar(tudo,documento){
  for(i=0;i<tudo;i++){
    if(documento.value=="Desmarcar Todos"){
     document.form1.presente[i].checked=false;
    }
    if(documento.value=="Marcar Todos"){
     document.form1.presente[i].checked=true;
    }
  }
  if(document.form1.marca.value == "Desmarcar Todos"){
   document.form1.marca.value="Marcar Todos";
  }else{
   document.form1.marca.value="Desmarcar Todos";
  }
 }
 
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