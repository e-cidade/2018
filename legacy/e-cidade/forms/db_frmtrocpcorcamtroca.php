<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
$clpcorcamjulg->rotulo->label();

$clrotulo->label("z01_nome");
$clrotulo->label("pc23_valor");
$clrotulo->label("pc23_obs");
?>
<form name="form1">
  <fieldset>
    <legend>Troca de Fornecedores</legend>

    <table height="20" border="0">
      <tr>
        <td nowrap title="<?=@$Tpc25_orcamitem?>">
           <?=@$Lpc25_orcamitem?>
        </td>
        <td>
        <?
        db_input('pc25_orcamitem',8,$Ipc25_orcamitem,true,'text',3);
        db_input('sol',10,0,true,'hidden',3);
        db_input('orcamento',10,0,true,'hidden',3);
        ?>
        </td>
      </tr>
      <tr>
        <td nowrap>
    	<strong>Fornecedor cotado:</strong>
        </td>
        <td>
        <?
        $result_forneccotado = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,null,"pc24_orcamforne as pc24_orcamforne_ant,z01_nome as z01_nome_ant","","pc24_orcamitem=$pc25_orcamitem and pc24_pontuacao=1"));
        if($clpcorcamjulg->numrows>0){
          db_fieldsmemory($result_forneccotado,0);
        }
        db_input('pc24_orcamforne',8,$Ipc24_orcamforne,true,'text',3,"","pc24_orcamforne_ant");
        db_input('z01_nome',40,$Iz01_nome,true,'text',3,'',"z01_nome_ant");
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
        $result_fornec = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query(null,null,"pc24_orcamforne,z01_nome","pc24_orcamforne","pc24_orcamitem=$pc25_orcamitem and pc24_pontuacao<>1"));
        db_selectrecord("pc24_orcamforne",$result_fornec,true,1,"","","","","js_mostravalor(this.value);");
        db_input('pc23_valor',13,$Ipc23_valor,true,'text',3);
        ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tpc25_motivo?>">
           <?=@$Lpc25_motivo?>
        </td>
        <td>
        <?
        db_textarea('pc25_motivo',3,48,$Ipc25_motivo,true,'text',1);
        ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <input name="trocar" type="submit" id="db_opcao" value="Trocar fornecedor">
  <input name='voltar' type='button' id='voltar' value='Voltar' onclick= 'js_voltar();'>
</form>
<script>
arr_valores = new Array();
arr_obsss = new Array();
function js_voltar(){
  qry = "pc20_codorc="+document.form1.orcamento.value;
  qry+= "&sol="+document.form1.sol.value;
  document.location.href = "com1_pcorcamtroca001.php?"+qry;
}
<?
$result_valores = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(null,$pc25_orcamitem,"pc23_orcamforne,pc23_valor,pc23_obs","pc23_orcamforne"));
for($i=0;$i<$clpcorcamval->numrows;$i++){
  db_fieldsmemory($result_valores,$i);
  echo "arr_valores[$pc23_orcamforne] = '";echo db_formatar($pc23_valor,"f");echo "';";
  echo "arr_obsss[$pc23_orcamforne] = '$pc23_obs';";
}
?>
function js_mostravalor(valor){
  document.form1.pc23_valor.value = arr_valores[valor];
  document.form1.pc23_obs.value = arr_obsss[valor];
}
js_mostravalor(document.form1.pc24_orcamforne.value);
</script>