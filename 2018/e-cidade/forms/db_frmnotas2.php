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
//busca dados da avaliação
$result = $clavaliacoes->sql_record($clavaliacoes->sql_query($ed13_i_codigo));
db_fieldsmemory($result,0);
?>
<br><br>
<form name="form1" method="post" action="">
<center>
<table width="600" border="1" cellpadding="0" cellspacing="0">
  <tr>
   <td colspan="4">
    <b>Avaliação:</b> <?=$ed13_c_descr?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Peso:</b> <?=$ed13_f_valor?>
   </td>
  </tr>
  <tr>
   <td colspan="4">
    <b>Data:</b> <?=substr($ed13_d_data,8,2)?>/<?=substr($ed13_d_data,5,2)?>/<?=substr($ed13_d_data,0,4)?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Turma:</b> <?=$ed05_c_nome?>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <b>Disciplina:</b> <?=$ed27_c_nome?>
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
   <td width="10%" align="center"><b>Nota</b></td>
  </tr>
  <?
  for($x=0;$x < $clmatriculas->numrows; $x++){
  db_fieldsmemory($result,$x);
  $sql2 = "select * from notas
           where ed11_d_data = '$ed13_d_data' and ed11_i_matriculas = $ed09_i_codigo
          ";
  $result2 = $clnotas->sql_record($sql2);
  @db_fieldsmemory($result2,0);
  db_input('ed11_i_codigo',5,$ed11_i_codigo,true,'hidden',3,"");
  if($ed11_c_fechado=="t")
   $db_opcao = 3;
  ?>
  <tr>
   <td align="center"><?db_input('ed09_i_codigo',5,$ed09_i_codigo,true,'text',3,"")?></td>
   <td>&nbsp;<?=$z01_nome?></td>
   <td align="center"><?db_input('ed11_f_media',5,$Ied11_f_media,true,'text',$db_opcao," onchange='digita(this,$ed13_f_valor);'")?></td>
  </tr>
  <?
  }
  ?>
  </table><br>
  Fechadas:
  <?
  $x = array('f'=>'Não','t'=>'Sim');
  db_select('ed11_c_fechado',$x,true,$db_opcao,"");
  ?>
  <input type="button" value="Salvar" name="botao" onclick="valida(<?=$clmatriculas->numrows?>,this,'<?if($ed11_f_media==""){echo 'incluir';}else{echo 'alterar';}?>')" <?=($db_opcao==3?"disabled":"")?>>
  <br><br>
  <?if($ed11_c_fechado=="t"){
   echo "Notas fechadas não podem ser alteradas.";
  }?>
  </center>
</form>
<script>
 function digita(campo,maximo){
  if(campo.value>maximo){
   alert("Nota máxima é "+maximo);
   campo.value = "";
   return false;
  }
 }
 function valida(tudo,documento,opcao){
   obj = document.form1;
   count = 0;
   linha='';
   sep = "x";
   for(i=0;i<tudo;i++){
    if(obj.ed11_f_media[i].value != ""){
     linha += obj.ed09_i_codigo[i].value+";";
     linha += obj.ed11_f_media[i].value+";";
     linha += obj.ed11_i_codigo[i].value;
    }else{
     alert("Informe todas as notas!");
     return false;
    }
    linha += sep;
    count += 1;
   }
  location="<?=$REQUEST_URI?>&linha="+linha+"&data=<?=@$ed13_d_data?>&"+opcao+"&ed11_c_fechado="+obj.ed11_c_fechado.value;
 }
</script>