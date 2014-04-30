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

//MODULO: Farmacia
$oDaoFarFatorRiscoFarmaciaAmbulatorial->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("s105_i_codigo");
$oRotulo->label("fa44_i_codigo");


$sSql                  = $oDaoFarFatorRisco->sql_query_file(null, 'fa44_i_codigo, fa44_c_descr', 'fa44_c_descr');
$rsFatoresFarmacia     = $oDaoFarFatorRisco->sql_record($sSql);

$sSql                  = $oDaoSauFatorDeRisco->sql_query_file(null, 's105_i_codigo, s105_v_descricao', 
                                                              's105_v_descricao'
                                                             );
$rsFatoresAmbulatorial = $oDaoSauFatorDeRisco->sql_record($sSql);


/* Fatores de risco cadastrados no módulo ambulatorial */
$aFatoresAmb = array('-1' => 'Selecione...');
for ($iContAmb = 0; $iContAmb < $oDaoSauFatorDeRisco->numrows; $iContAmb++) {

  $oDados                              = db_utils::fieldsmemory($rsFatoresAmbulatorial, $iContAmb);
  $aFatoresAmb[$oDados->s105_i_codigo] = $oDados->s105_v_descricao; 

}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">

  <?
  for ($iCont = 0; $iCont < $oDaoFarFatorRisco->numrows; $iCont++) {
   
    $oDados    = db_utils::fieldsmemory($rsFatoresFarmacia, $iCont);
    
    /* Verifico se o fator de risco da farmácia já está relacionado a algum fator de risco do ambulatorial */
    $sSql      = $oDaoFarFatorRiscoFarmaciaAmbulatorial2->sql_query_file(null, 'fa45_i_fatorriscoambulatorial', null, 
                                                                         ' fa45_i_fatorriscofarmacia = '.
                                                                         $oDados->fa44_i_codigo
                                                                        );
    $rsLigacao = $oDaoFarFatorRiscoFarmaciaAmbulatorial2->sql_record($sSql);
    if ($oDaoFarFatorRiscoFarmaciaAmbulatorial2->numrows > 0) {

      $oDadoLigacao = db_utils::fieldsmemory($rsLigacao, 0);
      /* seto a global, pois a função db_select analisa o seu conteúdo para selecionar o option */
      $GLOBALS['fatoresAmbulatorial[]'] = $oDadoLigacao->fa45_i_fatorriscoambulatorial;

    } else {
      $GLOBALS['fatoresAmbulatorial[]'] = -1;
    }
  ?>
  <tr>
    <td nowrap title="<?=@$Tfa45_i_codigo?>">
      <input type="hidden" size="2" readonly name="fatoresFarmacia[]" value="<?=$oDados->fa44_i_codigo?>">
      <input type="text" size="50" readonly value="<?=$oDados->fa44_c_descr?>">
    </td>
    <td>
      <?
      db_select('fatoresAmbulatorial[]', $aFatoresAmb, true, 1);
      ?>
    </td>
  </tr>
  <?
  }
  ?>
</table>
</center>
<br>
<input name="confirmar" type="submit" id="db_opcao" value="Gravar">
</form>
<script>
</script>