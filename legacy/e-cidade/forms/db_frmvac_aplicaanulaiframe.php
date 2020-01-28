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

//MODULO: Vacinas
$oDaoVacAplicaanula->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label('vc07_i_codigo');
$oRotulo->label('z01_v_nome');
$oRotulo->label('z01_v_mae');
$oRotulo->label('z01_d_nasc');
$oRotulo->label('z01_v_sexo');

$oRotulo->label('vc18_i_aplica');
$oRotulo->label('m61_descr');
$oRotulo->label('vc15_i_lote');
$oRotulo->label('m77_dtvalidade');
$oRotulo->label('nome');
$oRotulo->label('login');
$oRotulo->label('vc01_c_nome');
$oRotulo->label('vc17_i_sala');
$oRotulo->label('vc16_i_cgs');
$oRotulo->label('vc16_d_dataaplicada');
$oRotulo->label('vc16_n_quant');
$oRotulo->label('vc16_i_usuario');
$oRotulo->label('vc16_d_data');
$oRotulo->label('vc16_c_hora');
?>
<form name="form1" method="post" action="">

<center>
  <table border="0">
    <tr>
      <td nowrap title="<?=@$Tvc16_i_cgs?>">
        <?=@$Lvc16_i_cgs?>
      </td>
      <td nowrap> 
        <?
        db_input('vc16_i_cgs', 10, $Ivc16_i_cgs, true, 'text',3, "");
        db_input('z01_v_nome', 30, $Iz01_v_nome, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=$Tvc18_i_aplica?>">
        <?=$Lvc18_i_aplica?>
      </td>
      <td nowrap> 
        <?db_input('vc18_i_aplica', 10, $Ivc18_i_aplica, true, 'text', 3, "");
          db_input('vc07_c_nome', 30, $Iz01_v_nome, true, 'text', 3, '');?>
      </td>
    </tr>
    <tr>
      <td><?=$Lvc18_t_obs?></td>
      <td><?db_textarea('vc18_t_obs',2,20,$Ivc18_t_obs,true,'text',$db_opcao,"")?></td>
    </tr>
  </table>
</center>

<input name="anular" type="submit" id="anular" value="Anular Aplicação" onClick="return js_validaEnvio();">
<input type="button" value="Fechar" onclick="parent.js_fechaAplicaanula(2)">
</form>
<script>
</script>