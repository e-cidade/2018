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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_feriadomerenda_ext_classe.php");
$clferiado   = new cl_feriadomerenda_ext;
$escola      = db_getsession("DB_coddepto");
$data_script = $ano ."-". $mes ."-". $dia;
$diasemana   = date("w",mktime (0,0,0,$mes,$dia,$ano));
$strSql      = $clferiado->sql_record($clferiado->sql_query_merenda(null,
                                                                     "*",
                                                                     "",
                                                                    " ed38_i_escola = $escola 
                                                                      and ed54_d_data='$data_script'"
                                                                   ));
if ($clferiado->numrows>0) {
	
  db_fieldsmemory($strSql,0);
  if ($ed54_c_dialetivo=="N") {
  	
   	db_msgbox("Data digitada (".db_formatar($data_script,'d').") não é um dia letivo, escolha outra data!");
   	$data_script="";
   	
  }
} else {
  if ($diasemana == 0 ||  $diasemana == 6 ) {
  	
    db_msgbox("Data digitada (".db_formatar($data_script,'d').") não é um dia letivo, escolha outra data!");
    $data_script="";
    
  }  
}
?>

<script>
<?if (@$data_script!="") {?>
    parent.document.form1.<?=$campo?>_ano.value = "<?=substr($data_script,0,4)?>";
    parent.document.form1.<?=$campo?>_mes.value = "<?=substr($data_script,5,2)?>";
    parent.document.form1.<?=$campo?>_dia.value = "<?=substr($data_script,8,2)?>";
    parent.document.form1.<?=$campo?>.value = "<?=substr($data_script,8,2)?>/
                                               <?=substr($data_script,5,2)?>/
                                               <?=substr($data_script,0,4)?>";
    parent.document.form1.<?=$campo?>.value = "<?=substr($data_script,8,2)?>/
                                               <?=substr($data_script,5,2)?>/
                                               <?=substr($data_script,0,4)?>";
    d1 = "<?=substr($data_script,8,2)?>";
    m1 = "<?=substr($data_script,5,2)?>";
    a1 = "<?=substr($data_script,0,4)?>";
<?}?>	

<?if (@$data_script=="") {?>
    parent.document.form1.<?=$campo?>_ano.value = "";
    parent.document.form1.<?=$campo?>_mes.value = "";
    parent.document.form1.<?=$campo?>_dia.value = "";
    parent.document.form1.<?=$campo?>.value = "";
    d1 = "";
    m1 = "";
    a1 = "";
<?}?>
  var vet    = parent.document.form1.me29_d_inicio.value.split("/");
  var ano    = vet[2];
  var mes    = vet[1]-1;
  var dia    = vet[0];
  var inicio = new Date(ano, mes, dia);
  vet        = parent.document.form1.me29_d_fim.value.split("/");
  ano        = vet[2];
  mes        = vet[1]-1;
  dia        = vet[0];
  var fim    = new Date(ano, mes, dia);
  var dif    = Date.UTC(fim.getYear(),fim.getMonth(),fim.getDate(),0,0,0)
               - Date.UTC(inicio.getYear(),inicio.getMonth(),inicio.getDate(),0,0,0);
  var diferenca = Math.abs((dif / 1000 / 60 / 60 / 24));  
  if (diferenca>0) {
	parent.document.form1.duracao.value=diferenca;
  } else {
	  
	parent.document.form1.me29_d_fim.value='';
	parent.document.form1.duracao.value='';
	parent.document.form1.me29_d_fim.focus();
    
  }
</script>