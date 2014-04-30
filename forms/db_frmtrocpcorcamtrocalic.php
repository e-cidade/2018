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

$clpcorcamtroca->rotulo->label();
$clpcorcamforne->rotulo->label();
$clrotulo->label("z01_nome"); 
$clrotulo->label("pc23_valor"); 
$clrotulo->label("pc23_obs"); 

$sql_troca = "select distinct pc24_orcamitem
              from pcorcamjulg
                   left  join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamjulg.pc24_orcamitem
                   inner join liclicitem     on liclicitem.l21_codigo         = pcorcamitemlic.pc26_liclicitem
                   inner join liclicitemlote on liclicitemlote.l04_liclicitem = liclicitem.l21_codigo
                   left  join pcorcamval     on pcorcamval.pc23_orcamitem     = pcorcamjulg.pc24_orcamitem
                   left  join pcorcamdescla  on pcorcamdescla.pc32_orcamitem  = pcorcamval.pc23_orcamitem and
                                                pcorcamdescla.pc32_orcamforne = pcorcamval.pc23_orcamforne
             where l21_situacao     = 0   and 
                   pc32_orcamitem is null and pc32_orcamforne is null and 
                   l21_codliclicita = $l20_codigo and
                   pc24_orcamforne  = $orcamforne and
                   l04_descricao    = '$lote'";

$result_troca = $clpcorcamforne->sql_record($sql_troca);
$dbwhere      = "";
$dbwhere2     = "";
$dbwhere_lote = "";

if ($clpcorcamforne->numrows > 0){
     if (trim(substr($lote,0,13)) == "LOTE_AUTOITEM"){  // Tipo de julgamento por Item (1)
          db_fieldsmemory($result_troca,0);
          $pc25_orcamitem = $pc24_orcamitem;
          $dbwhere        = " and pc22_orcamitem=$pc25_orcamitem";
          $dbwhere2       = " and pc23_orcamitem=$pc25_orcamitem";
          $tipojulg       = 1;  
     } else if (trim($lote) == "GLOBAL"){               // Tipo de julgamento Global   (2)
          $tipojulg       = 2; 
     } else {                                           // Tipo de julgamento por Lote (3)
          $tipojulg       = 3; 
          $dbwhere_lote   = " and l04_descricao = '".$lote."'";
     }
}
?>
<form name="form1" method="post">
<center><br><br>
<table height="20" border="0">
  <tr>
<?  
    db_input('orcamento',10,0,true,'hidden',3);
    db_input('orcamforne',8,0,true,'hidden',3);
    db_input('l20_codigo',10,0,true,'hidden',3);
    db_input('lote',40,0,true,'hidden',3);

    if ($tipojulg == 1){
?>
    <td nowrap title="<?=@$Tpc25_orcamitem?>">
       <?=@$Lpc25_orcamitem?>
    </td>
    <td> 
    <?
        db_input('pc25_orcamitem',8,$Ipc25_orcamitem,true,'text',3);
    ?>
    </td>
<?
    }
?>
  </tr>  
  <tr>
    <td nowrap>    
	<strong>Fornecedor cotado:</strong>
    </td>
    <td> 
    <?
    $result_forneccotado = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_fornec(null,"pc21_orcamforne as pc21_orcamforne_ant,z01_nome as z01_nome_ant","","pc22_codorc=$orcamento and pc21_orcamforne=$orcamforne $dbwhere"));
    if($clpcorcamforne->numrows>0){
      db_fieldsmemory($result_forneccotado,0);
    }    
    db_input('pc21_orcamforne',8,$Ipc21_orcamforne,true,'text',3,"","pc21_orcamforne_ant");
    db_input('z01_nome',60,$Iz01_nome,true,'text',3,'',"z01_nome_ant");
    ?>
    </td>
  </tr>  
  <tr>
    <td nowrap>    
	<strong>Obs do item:</strong>
    </td>
    <td> 
    <?
    db_input('pc23_obs',51,$Ipc23_obs,true,'text',3);
    ?>
    </td>
  </tr>  
  <tr>
    <td nowrap>    
	<strong>Fornecedor para troca:</strong>
    </td>
    <td> 
    <?
    $result_fornec = $clpcorcamforne->sql_record($clpcorcamforne->sql_query_fornec(null,"pc21_orcamforne, z01_nome, sum(coalesce(pc23_valor, 0)) as pc23_valor, l04_descricao","l04_descricao,z01_nome,pc21_orcamforne","pc22_codorc=$orcamento and pc21_orcamforne<>$orcamforne and pc23_valor > 0 and pc32_orcamitem is null $dbwhere $dbwhere_lote group by pc21_orcamforne, z01_nome, l04_descricao"));
    $numrows       = $clpcorcamforne->numrows;

//    echo($clpcorcamforne->sql_query_fornec(null,"pc21_orcamforne, z01_nome, sum(coalesce(pc23_valor, 0)) as pc23_valor, l04_descricao","l04_descricao,z01_nome,pc21_orcamforne","pc22_codorc=$orcamento and pc21_orcamforne<>$orcamforne and pc23_valor > 0 and pc32_orcamitem is null $dbwhere $dbwhere_lote group by pc21_orcamforne, z01_nome, l04_descricao")."<br>");

    if ($numrows == 0){
         $js_script = "";
    } else {
         $js_script = "js_mostravalor(this.value);";
    }

    if ($numrows > 0){
         db_selectrecord("pc21_orcamforne",$result_fornec,true,1,"","","","",$js_script);
    } else {
         echo "<b>Nao existem fornecedor(es) para troca</b>";
    }
    ?>
    </td>
   </tr> 
<?
    if ($tipojulg == 1) {
?>
   <tr> 
    <td><b>Preco Cotado para troca:</b></td>
    <td>
    <?
    if ($clpcorcamforne->numrows > 0){
         for($i = 0; $i < $clpcorcamforne->numrows; $i++){
              db_fieldsmemory($result_fornec,$i);
              if (trim($pc23_valor) != ""){
                   break;
              }
         }
    }

    if (trim(@$pc23_valor) == ""){
         $pc23_valor = 0;
    }
    $pc23_valor = db_formatar($pc23_valor,"f");
    db_input('pc23_valor',13,$Ipc23_valor,true,'text',3);
    ?>
    </td> 
  </tr>  
<?
  }
?>
  <tr>
    <td nowrap title="<?=@$Tpc25_motivo?>">
       <?=@$Lpc25_motivo?>
    </td>
    <td> 
    <?
    db_textarea('pc25_motivo',3,48,$Ipc25_motivo,true,'text',1);
    db_input("tipojulg",1,0,true,"hidden",3);
    ?>
    </td>
  </tr>  
  <tr>  
    <td align='center' colspan='2'>
      <input name="trocar" type="submit" id="db_opcao" value="Trocar fornecedor" onClick="return js_confirmar();">
      <input name='voltar' type='button' id='voltar' value='Voltar' onClick="js_voltar();">
    </td>
  </tr>  
</table>
</center>
</form>
<?
    if ($numrows == 0){
         db_msgbox("Nao existem fornecedor(es) para troca");
         echo "<script>
                 document.location.href = 'lic1_pcorcamtroca001.php?pc20_codorc=$orcamento&pc21_orcamforne=$orcamforne&l20_codigo=$l20_codigo';
               </script>";
    }
?>
<script>
function js_voltar(){
  qry  = "pc20_codorc="+document.form1.orcamento.value;
  qry += "&pc21_orcamforne="+document.form1.orcamforne.value;
  qry += "&l20_codigo="+document.form1.l20_codigo.value;
  document.location.href = "lic1_pcorcamtroca001.php?"+qry;
}
function js_confirmar(){
  var erro = true;

  if (document.form1.pc25_motivo.value == ""){
       alert("Preencha o motivo da troca.");
       document.form1.pc25_motivo.select();
       document.form1.pc25_motivo.focus();
       erro = false;
  }
  
  return erro;
}
arr_valores = new Array();
arr_obsss = new Array();
<?
$result_valores = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(null,null,"pc23_orcamforne,pc23_valor,pc23_obs","pc23_orcamforne","pc23_valor > 0 $dbwhere2"));
for($i=0;$i<$clpcorcamval->numrows;$i++){
  db_fieldsmemory($result_valores,$i);
?>
  arr_valores[<?=$pc23_orcamforne?>] = '<?=db_formatar($pc23_valor,"f")?>';;
  arr_obsss[<?=$pc23_orcamforne?>]   = '<? echo addslashes($pc23_obs); ?>';
<?  
}
?>
function js_mostravalor(valor){
  document.form1.pc23_valor.value = arr_valores[valor];
  document.form1.pc23_obs.value = arr_obsss[valor];
}
</script>