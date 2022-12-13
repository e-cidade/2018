<?
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

//MODULO: cemiterio
$cltaxaserv->rotulo->label();
$cltaxaservval->rotulo->label();
?>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<form name="form1" method="post" action="">
<table align="center" style="padding-top:15px;" border="0">
  <tr>
    <td>
      <table border="0" align="center">
        <tr>
          <td>
		        <?
		          db_input('cm11_i_codigo',10,$Icm11_i_codigo,true,'text',3,"");
		          db_input('cm35_sequencial',10,$Icm35_sequencial,true,'hidden',3,"");
		        ?>
          </td>
          <td>
            <?
              db_input('cm11_c_descr',40,$Icm11_c_descr,true,'text',3,"");
            ?>
          </td>
        </tr>
		    <tr>
		      <td nowrap title="<?=@$Tcm35_dataini?>">
		        <?=@$Lcm35_dataini?>
		      </td>
		      <td>
		        <?
		          db_inputdata('cm35_dataini',@$cm35_dataini_dia,@$cm35_dataini_mes,@$cm35_dataini_ano,true,'text',$db_opcao,"")
		        ?>
		      </td>
		    </tr>
        <tr>
          <td nowrap title="<?=@$Tcm35_datafin?>">
            <?=@$Lcm35_datafin?>
          </td>
          <td>
            <?
              db_inputdata('cm35_datafin',@$cm35_datafin_dia,@$cm35_datafin_mes,@$cm35_datafin_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tcm35_valor?>">
            <?=@$Lcm35_valor?>
          </td>
          <td>
            <?
              db_input('cm35_valor',10,$Icm35_valor,true,'text',$db_opcao,"onkeypress=\"return mascaraValor(event, this);\"")
            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit"
             id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?>  onclick="return js_verificacampos();">
      <input name="novo" type="submit" id="cancelar" value="Novo" <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  <tr>
    <td>
      <table>
        <tr>
          <td valign="top"  align="center">
            <?

              $aChavePri = array( "cm35_sequencial" => @$cm35_sequencial,
                                  "cm35_taxaserv"   => @$cm35_taxaserv,
                                  "cm35_dataini"    => @$cm35_dataini,
                                  "cm35_datafin"    => @$cm35_datafin,
                                  "cm35_valor"      => @$cm35_valor );

              $sCampo    = "cm35_sequencial,cm35_taxaserv,cm35_dataini,cm35_datafin,cm35_valor";

              $cliframe_alterar_excluir->chavepri      = $aChavePri;
              $cliframe_alterar_excluir->sql           = $cltaxaservval->sql_query(null,"*",null," cm35_taxaserv = {$cm11_i_codigo}");
              $cliframe_alterar_excluir->campos        = $sCampo;
              $cliframe_alterar_excluir->legenda       = "Valores Taxa Serviço";
              $cliframe_alterar_excluir->iframe_height = "160";
              $cliframe_alterar_excluir->iframe_width  = "700";
              $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);

            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
</form>
<script>
function js_verificacampos() {

  var dtInicial   = $('cm35_dataini_ano').value+'-'+$('cm35_dataini_mes').value+'-'+$('cm35_dataini_dia').value;
  var dtFinal     = $('cm35_datafin_ano').value+'-'+$('cm35_datafin_mes').value+'-'+$('cm35_datafin_dia').value;
  var validaDatas = js_diferenca_datas(dtInicial,dtFinal,3);
  if(validaDatas){

    sMsg0 = "Usuario:\n\n";
    sMsg1 = "Data Inicial deve ser menor que a data final!\n\n";
    sMsg2 = "Administrador:\n\n";
    $('cm35_dataini').value = '';
    $('cm35_dataini').focus();
    alert(sMsg0+sMsg1+sMsg2);
    return false;
  }
}
</script>