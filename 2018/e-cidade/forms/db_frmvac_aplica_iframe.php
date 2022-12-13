<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: Vacinas
$clvac_aplica->rotulo->label();
$clvac_aplicalote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m77_dtvalidade");
$clrotulo->label("m77_lote");
$clrotulo->label("vc01_c_nome");
$clrotulo->label("m61_descr");

$sSql        = $clvac_vacinadoserestricao->sql_query("","*",""," VC08_i_vacinadose=$vc16_i_dosevacina ");
$rsRestricao = $clvac_vacinadoserestricao->sql_record($sSql);
if ($clvac_vacinadoserestricao->numrows > 0) {

  $sStr = "      Restrições \\n ";
  for ($iX = 0; $iX < $clvac_vacinadoserestricao->numrows; $iX++) {

    $oqRestri  = db_utils::fieldsmemory($rsRestricao,$iX);
    $sStr     .= $oqRestri->vc02_c_nome." - ".$oqRestri->vc02_c_descr."  \\n";

  }
  db_msgbox($sStr);

}

$sSql          = $clvac_dependencia->sql_query_alt("",
                                                   "dependencia.vc07_i_codigo,dependencia.vc07_c_nome",
                                                   "",
                                                   "vc09_i_dependente=$vc16_i_dosevacina "
                                                  );
$rsDependencia = $clvac_dependencia->sql_record($sSql);
if ($clvac_dependencia->numrows > 0) {

  $sStr = "      Dependencias      \\n ";
  for ($iX = 0; $iX < $clvac_dependencia->numrows; $iX++) {

    $oDependencia  = db_utils::fieldsmemory($rsDependencia,$iX);
    $sWhere        = " vc16_i_cgs=$vc16_i_cgs and vc16_i_dosevacina=$oDependencia->vc07_i_codigo ";
    $sSql          = $clvac_aplica->sql_query_file("",
                                                   "*",
                                                   "",
                                                   $sWhere
                                                  );
    $rsAplica      = $clvac_aplica->sql_record($sSql);
    if ($clvac_aplica->numrows == 0) {

      $sStr         .= ($iX+1)." - ".$oDependencia->vc07_c_nome."  \\n";
      $sTrava        = " disabled ";

    }

  }
  if ($sTrava != "") {
    db_msgbox($sStr);
  }

}

$sSql   = $clvac_sala->sql_query("","*",""," VC01_i_unidade=$iDepartamento ");
$rsSala = $clvac_sala->sql_record($sSql);
if ($clvac_sala->numrows > 0) {

  db_fieldsmemory($rsSala,0);
  $vc17_i_sala = $vc01_i_codigo;

}

$vc16_d_dataaplicada     = date("d/m/Y",db_getsession("DB_datausu"));
$vc16_d_dataaplicada_dia = date("d",db_getsession("DB_datausu"));
$vc16_d_dataaplicada_mes = date("m",db_getsession("DB_datausu"));
$vc16_d_dataaplicada_ano = date("Y",db_getsession("DB_datausu"));
$vc16_n_quant            = 1;
?>
<form name="form1" method="post" action="">
<center>
<?db_input('vc16_i_codigo',10,$Ivc16_i_codigo,true,'hidden',$db_opcao,'');
  db_input('vc16_i_dosevacina',10,$Ivc16_i_dosevacina,true,'hidden',$db_opcao,'');
  db_input('vc16_i_cgs',10,$Ivc16_i_cgs,true,'hidden',$db_opcao,'');
  db_input('iVacina',10,"",true,'hidden',$db_opcao,'');
  db_input('vc16_c_hora',10,$Ivc16_c_hora,true,'hidden',$db_opcao,'');
  db_input('vc16_d_data',10,$Ivc16_d_data,true,'hidden',$db_opcao,'');
  db_input('vc16_i_usuario',10,$Ivc16_i_usuario,true,'hidden',$db_opcao,'');
  db_input('vc16_i_departamento',10,$Ivc16_i_departamento,true,'hidden',$db_opcao,'');
?>
<table border="0">
  <tr>
     <td align="right" nowrap><b>Vacina:</b></td>
     <td>
        <?db_input('sVacinaDose',28,"",true,'text',3,'');?>
     </td>
  <tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tvc16_i_dosevacina?>" >
       <?db_ancora(@$Lvc17_i_sala,"parent.js_pesquisavc17_i_sala(true);",$db_opcao);?>
    </td>
    <td> 
      <?db_input('vc17_i_sala',5,$Ivc17_i_sala,true,'text',$db_opcao," onchange='parent.js_pesquisavc17_i_sala(false);'");
        db_input('vc01_c_nome',20,$Ivc01_c_nome,true,'text',3,'');?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tm61_descr?>">
       <?=@$Lm61_descr?>
    </td>
    <td>
      <?db_input('m61_descr',10,$Im61_descr,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tvc16_n_quant?>">
       <?=@$Lvc16_n_quant?>
    </td>
    <td> 
      <?db_input('vc16_n_quant',10,$Ivc16_n_quant,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tvc16_d_dataaplicada?>">
      <?=@$Lvc16_d_dataaplicada?>
    </td>
    <td> 
      <?db_inputdata('vc16_d_dataaplicada',@$vc16_d_dataaplicada_dia,@$vc16_d_dataaplicada_mes,@$vc16_d_dataaplicada_ano,
                     true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tvc17_i_matetoqueitemlote?>">
       <?db_ancora(@$Lvc17_i_matetoqueitemlote,"parent.js_pesquisavc17_i_vacinalote(true);",$db_opcao);?>
    </td>
    <td>
      <?db_input('vc17_i_matetoqueitemlote',10,$Ivc17_i_matetoqueitemlote,true,'hidden',$db_opcao,'');
        db_input('m77_lote',10,$Im77_lote,true,'text',$db_opcao," onchange='parent.js_pesquisavc17_i_vacinalote(false);'");
        echo"<strong><b>Saldo:</b></strong>";
        db_input('saldo',5,"",true,'text',3,'');?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tm77_dtvalidade?>">
      <?=@$Lm77_dtvalidade?>
    </td>
    <td>
      <?db_inputdata('m77_dtvalidade',@$m77_dtvalidade_dia,@$m77_dtvalidade_mes,@$m77_dtvalidade_ano,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tvc16_t_obs?>">
      <?=@$Lvc16_t_obs?>
    </td>
    <td> 
      <?db_textarea('vc16_t_obs',2,20,$Ivc16_t_obs,true,'text',$db_opcao,"")?>
    </td>
  </tr>
</table>
</center>
<input name = "<?=($db_opcao==1?"incluir":"alterar")?>" type = "submit" id = "db_opcao" 
       value = "<?=($db_opcao==1?"Aplicar":"Alterar")?>" onClick = "return js_valida()" <?=$sTrava?>
       <?=($db_opcao==3?'style="display: none;"':'')?> >
<input name = "anula"  type = "button" id = "anula"  value = "Anular"   
       onClick = "parent.js_CancelaAplica(document.form1.vc16_i_codigo.value,<?=$vc16_i_cgs?>)" 
       <?=($db_opcao==1?"disabled":"")?> <?=$sTrava?> >
<input name = "cancela" type = "button" id = "canela" value = "Cancelar" onClick = "parent.js_fechaAplica(2)" >
</form>
<script>
  function js_valida() {
    
    aVet = document.form1.vc16_d_dataaplicada.value.split('/');
    data = new Date(aVet[2],aVet[1],aVet[0]);
    hoje = new Date(<?=date("Y",db_getsession("DB_datausu"))?>,
           <?=date("m",db_getsession("DB_datausu"))?>,
           <?=date("d",db_getsession("DB_datausu"))?>);
    if (data > hoje) {

      alert('Data da aplicação não pode ser superior a data de hoje!');
      return false;

    }
    
    if (document.form1.vc16_n_quant.value > document.form1.saldo.value) {

      alert('Quantidade aplicada não pode ser maior que o saldo!');
      return false;

    }
    return true;
  }
</script>