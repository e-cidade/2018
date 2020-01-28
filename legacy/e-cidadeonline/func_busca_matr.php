<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");

$oGet     = db_utils::postmemory($_GET);

if($oGet->chave == 1){

    $sql = " select rh01_regist,
                    z01_cgccpf,
                    z01_nome
               from rhpessoal
                    inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm
              where rh01_regist = {$oGet->numcgm} and z01_cgccpf = z01_cgccpf ";

    $result = db_query($sql);
    $total  = pg_num_rows($result);

    if($total > 0){
      db_fieldsmemory($result,0);
    }
} else if($oGet->chave == 2){

    $sql = " select rh01_regist,
                    z01_cgccpf,
                    z01_nome
               from rhpessoal
                    inner join cgm on cgm.z01_numcgm = rhpessoal.rh01_numcgm
              where z01_nome = '{$oGet->z01_nome}' and z01_cgccpf = z01_cgccpf limit 1 ";

    $result = db_query($sql);
    $total  = pg_num_rows($result);

    if($total > 0){
      db_fieldsmemory($result,0);
    }
}

if($total > 0){
?>
<script type="text/javascript">

   parent.document.form1.matricula.value = '<?php echo $rh01_regist; ?>';
   parent.document.form1.nome.value      = '<?php echo $z01_nome; ?>';
   parent.document.getElementById('msgerro').style.display = 'none';
   parent.document.getElementById('msgerro').innerHTML     = '';
</script>
<?
} else {
?>
<script type="text/javascript">

   str  = "<span><font color='#E9000'> OS DADOS DIGITADOS SÃO INCONSISTENTES! </font></span>";

   var msgerro = str;

   parent.document.form1.matricula.value = '';
   parent.document.form1.nome.value      = '';
   parent.document.getElementById('msgerro').style.display = '';
   parent.document.getElementById('msgerro').innerHTML = msgerro;

</script>
<?
}
?>