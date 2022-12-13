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

//MODULO: pessoal
$clpessoal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("r13_descr");
$clrotulo->label("r37_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tr01_anousu?>">
       <?=@$Lr01_anousu?>
    </td>
    <td> 
<?
db_input('r01_anousu',4,$Ir01_anousu,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_mesusu?>">
       <?=@$Lr01_mesusu?>
    </td>
    <td> 
<?
db_input('r01_mesusu',2,$Ir01_mesusu,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_numcgm?>">
       <?=@$Lr01_numcgm?>
    </td>
    <td> 
<?
db_input('r01_numcgm',6,$Ir01_numcgm,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_regist?>">
       <?=@$Lr01_regist?>
    </td>
    <td> 
<?
db_input('r01_regist',6,$Ir01_regist,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_admiss?>">
       <?=@$Lr01_admiss?>
    </td>
    <td> 
<?
db_inputdata('r01_admiss',@$r01_admiss_dia,@$r01_admiss_mes,@$r01_admiss_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_regime?>">
       <?=@$Lr01_regime?>
    </td>
    <td> 
<?
db_input('r01_regime',1,$Ir01_regime,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_lotac?>">
       <?
       db_ancora(@$Lr01_lotac,"js_pesquisar01_lotac(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('r01_lotac',4,$Ir01_lotac,true,'text',$db_opcao," onchange='js_pesquisar01_lotac(false);'")
?>
       <?
db_input('r13_descr',40,$Ir13_descr,true,'text',3,'')
       ?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_vincul?>">
       <?=@$Lr01_vincul?>
    </td>
    <td> 
<?
db_input('r01_vincul',2,$Ir01_vincul,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_cbo?>">
       <?=@$Lr01_cbo?>
    </td>
    <td> 
<?
db_input('r01_cbo',5,$Ir01_cbo,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_padrao?>">
       <?=@$Lr01_padrao?>
    </td>
    <td> 
<?
db_input('r01_padrao',10,$Ir01_padrao,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_salari?>">
       <?=@$Lr01_salari?>
    </td>
    <td> 
<?
db_input('r01_salari',12,$Ir01_salari,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_tipsal?>">
       <?=@$Lr01_tipsal?>
    </td>
    <td> 
<?
db_input('r01_tipsal',1,$Ir01_tipsal,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_folha?>">
       <?=@$Lr01_folha?>
    </td>
    <td> 
<?
db_input('r01_folha',1,$Ir01_folha,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_fpagto?>">
       <?=@$Lr01_fpagto?>
    </td>
    <td> 
<?
db_input('r01_fpagto',1,$Ir01_fpagto,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_banco?>">
       <?=@$Lr01_banco?>
    </td>
    <td> 
<?
db_input('r01_banco',3,$Ir01_banco,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_agenc?>">
       <?=@$Lr01_agenc?>
    </td>
    <td> 
<?
db_input('r01_agenc',5,$Ir01_agenc,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_contac?>">
       <?=@$Lr01_contac?>
    </td>
    <td> 
<?
db_input('r01_contac',15,$Ir01_contac,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_ctps?>">
       <?=@$Lr01_ctps?>
    </td>
    <td> 
<?
db_input('r01_ctps',12,$Ir01_ctps,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_pis?>">
       <?=@$Lr01_pis?>
    </td>
    <td> 
<?
db_input('r01_pis',11,$Ir01_pis,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_fgts?>">
       <?=@$Lr01_fgts?>
    </td>
    <td> 
<?
db_inputdata('r01_fgts',@$r01_fgts_dia,@$r01_fgts_mes,@$r01_fgts_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_bcofgt?>">
       <?=@$Lr01_bcofgt?>
    </td>
    <td> 
<?
db_input('r01_bcofgt',3,$Ir01_bcofgt,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_agfgts?>">
       <?=@$Lr01_agfgts?>
    </td>
    <td> 
<?
db_input('r01_agfgts',5,$Ir01_agfgts,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_ccfgts?>">
       <?=@$Lr01_ccfgts?>
    </td>
    <td> 
<?
db_input('r01_ccfgts',11,$Ir01_ccfgts,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_hrssem?>">
       <?=@$Lr01_hrssem?>
    </td>
    <td> 
<?
db_input('r01_hrssem',2,$Ir01_hrssem,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_situac?>">
       <?=@$Lr01_situac?>
    </td>
    <td> 
<?
db_input('r01_situac',1,$Ir01_situac,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_nasc?>">
       <?=@$Lr01_nasc?>
    </td>
    <td> 
<?
db_inputdata('r01_nasc',@$r01_nasc_dia,@$r01_nasc_mes,@$r01_nasc_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_nacion?>">
       <?=@$Lr01_nacion?>
    </td>
    <td> 
<?
db_input('r01_nacion',2,$Ir01_nacion,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_anoche?>">
       <?=@$Lr01_anoche?>
    </td>
    <td> 
<?
db_input('r01_anoche',2,$Ir01_anoche,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_instru?>">
       <?=@$Lr01_instru?>
    </td>
    <td> 
<?
db_input('r01_instru',1,$Ir01_instru,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_sexo?>">
       <?=@$Lr01_sexo?>
    </td>
    <td> 
<?
db_input('r01_sexo',1,$Ir01_sexo,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_recis?>">
       <?=@$Lr01_recis?>
    </td>
    <td> 
<?
db_inputdata('r01_recis',@$r01_recis_dia,@$r01_recis_mes,@$r01_recis_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_causa?>">
       <?=@$Lr01_causa?>
    </td>
    <td> 
<?
db_input('r01_causa',2,$Ir01_causa,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_ponto?>">
       <?=@$Lr01_ponto?>
    </td>
    <td> 
<?
db_input('r01_ponto',6,$Ir01_ponto,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_alim?>">
       <?=@$Lr01_alim?>
    </td>
    <td> 
<?
db_input('r01_alim',5,$Ir01_alim,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_digito?>">
       <?=@$Lr01_digito?>
    </td>
    <td> 
<?
db_input('r01_digito',1,$Ir01_digito,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_tpvinc?>">
       <?=@$Lr01_tpvinc?>
    </td>
    <td> 
<?
db_input('r01_tpvinc',1,$Ir01_tpvinc,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_arredn?>">
       <?=@$Lr01_arredn?>
    </td>
    <td> 
<?
db_input('r01_arredn',12,$Ir01_arredn,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_progr?>">
       <?=@$Lr01_progr?>
    </td>
    <td> 
<?
db_input('r01_progr',1,$Ir01_progr,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_carth?>">
       <?=@$Lr01_carth?>
    </td>
    <td> 
<?
db_input('r01_carth',11,$Ir01_carth,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_rubric?>">
       <?=@$Lr01_rubric?>
    </td>
    <td> 
<?
db_input('r01_rubric',4,$Ir01_rubric,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_tbprev?>">
       <?=@$Lr01_tbprev?>
    </td>
    <td> 
<?
db_input('r01_tbprev',1,$Ir01_tbprev,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_adia13?>">
       <?=@$Lr01_adia13?>
    </td>
    <td> 
<?
db_input('r01_adia13',12,$Ir01_adia13,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_anter?>">
       <?=@$Lr01_anter?>
    </td>
    <td> 
<?
db_inputdata('r01_anter',@$r01_anter_dia,@$r01_anter_mes,@$r01_anter_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_dtafas?>">
       <?=@$Lr01_dtafas?>
    </td>
    <td> 
<?
db_inputdata('r01_dtafas',@$r01_dtafas_dia,@$r01_dtafas_mes,@$r01_dtafas_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_ctpsuf?>">
       <?=@$Lr01_ctpsuf?>
    </td>
    <td> 
<?
db_input('r01_ctpsuf',2,$Ir01_ctpsuf,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_dadi13?>">
       <?=@$Lr01_dadi13?>
    </td>
    <td> 
<?
db_inputdata('r01_dadi13',@$r01_dadi13_dia,@$r01_dadi13_mes,@$r01_dadi13_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_estciv?>">
       <?=@$Lr01_estciv?>
    </td>
    <td> 
<?
db_input('r01_estciv',1,$Ir01_estciv,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_funcao?>">
       <?
       db_ancora(@$Lr01_funcao,"js_pesquisar01_funcao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('r01_funcao',5,$Ir01_funcao,true,'text',$db_opcao," onchange='js_pesquisar01_funcao(false);'")
?>
       <?
db_input('r37_descr',30,$Ir37_descr,true,'text',3,'')
       ?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_trien?>">
       <?=@$Lr01_trien?>
    </td>
    <td> 
<?
db_inputdata('r01_trien',@$r01_trien_dia,@$r01_trien_mes,@$r01_trien_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_tipadm?>">
       <?=@$Lr01_tipadm?>
    </td>
    <td> 
<?
db_input('r01_tipadm',1,$Ir01_tipadm,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_caub?>">
       <?=@$Lr01_caub?>
    </td>
    <td> 
<?
db_input('r01_caub',2,$Ir01_caub,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_aviso?>">
       <?=@$Lr01_aviso?>
    </td>
    <td> 
<?
db_inputdata('r01_aviso',@$r01_aviso_dia,@$r01_aviso_mes,@$r01_aviso_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_hrsmen?>">
       <?=@$Lr01_hrsmen?>
    </td>
    <td> 
<?
db_input('r01_hrsmen',6,$Ir01_hrsmen,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_rfi1?>">
       <?=@$Lr01_rfi1?>
    </td>
    <td> 
<?
db_inputdata('r01_rfi1',@$r01_rfi1_dia,@$r01_rfi1_mes,@$r01_rfi1_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_rfi2?>">
       <?=@$Lr01_rfi2?>
    </td>
    <td> 
<?
db_inputdata('r01_rfi2',@$r01_rfi2_dia,@$r01_rfi2_mes,@$r01_rfi2_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_rff1?>">
       <?=@$Lr01_rff1?>
    </td>
    <td> 
<?
db_inputdata('r01_rff1',@$r01_rff1_dia,@$r01_rff1_mes,@$r01_rff1_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_rff2?>">
       <?=@$Lr01_rff2?>
    </td>
    <td> 
<?
db_inputdata('r01_rff2',@$r01_rff2_dia,@$r01_rff2_mes,@$r01_rff2_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_rnd1?>">
       <?=@$Lr01_rnd1?>
    </td>
    <td> 
<?
db_input('r01_rnd1',6,$Ir01_rnd1,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_rnd2?>">
       <?=@$Lr01_rnd2?>
    </td>
    <td> 
<?
db_input('r01_rnd2',6,$Ir01_rnd2,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_r13i?>">
       <?=@$Lr01_r13i?>
    </td>
    <td> 
<?
db_inputdata('r01_r13i',@$r01_r13i_dia,@$r01_r13i_mes,@$r01_r13i_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_r13f?>">
       <?=@$Lr01_r13f?>
    </td>
    <td> 
<?
db_inputdata('r01_r13f',@$r01_r13f_dia,@$r01_r13f_mes,@$r01_r13f_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_rnd3?>">
       <?=@$Lr01_rnd3?>
    </td>
    <td> 
<?
db_input('r01_rnd3',6,$Ir01_rnd3,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_ndres?>">
       <?=@$Lr01_ndres?>
    </td>
    <td> 
<?
db_input('r01_ndres',6,$Ir01_ndres,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_ndsal?>">
       <?=@$Lr01_ndsal?>
    </td>
    <td> 
<?
db_input('r01_ndsal',6,$Ir01_ndsal,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_prores?>">
       <?=@$Lr01_prores?>
    </td>
    <td> 
<?
db_input('r01_prores',7,$Ir01_prores,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_matipe?>">
       <?=@$Lr01_matipe?>
    </td>
    <td> 
<?
db_input('r01_matipe',13,$Ir01_matipe,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_dtvinc?>">
       <?=@$Lr01_dtvinc?>
    </td>
    <td> 
<?
db_inputdata('r01_dtvinc',@$r01_dtvinc_dia,@$r01_dtvinc_mes,@$r01_dtvinc_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_estado?>">
       <?=@$Lr01_estado?>
    </td>
    <td> 
<?
db_input('r01_estado',2,$Ir01_estado,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_dtalt?>">
       <?=@$Lr01_dtalt?>
    </td>
    <td> 
<?
db_inputdata('r01_dtalt',@$r01_dtalt_dia,@$r01_dtalt_mes,@$r01_dtalt_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_natura?>">
       <?=@$Lr01_natura?>
    </td>
    <td> 
<?
db_input('r01_natura',25,$Ir01_natura,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_tpcont?>">
       <?=@$Lr01_tpcont?>
    </td>
    <td> 
<?
db_input('r01_tpcont',2,$Ir01_tpcont,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_titele?>">
       <?=@$Lr01_titele?>
    </td>
    <td> 
<?
db_input('r01_titele',11,$Ir01_titele,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_zonael?>">
       <?=@$Lr01_zonael?>
    </td>
    <td> 
<?
db_input('r01_zonael',3,$Ir01_zonael,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_secaoe?>">
       <?=@$Lr01_secaoe?>
    </td>
    <td> 
<?
db_input('r01_secaoe',4,$Ir01_secaoe,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_taviso?>">
       <?=@$Lr01_taviso?>
    </td>
    <td> 
<?
db_input('r01_taviso',1,$Ir01_taviso,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_cc?>">
       <?=@$Lr01_cc?>
    </td>
    <td> 
<?
db_input('r01_cc',1,$Ir01_cc,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_ocorre?>">
       <?=@$Lr01_ocorre?>
    </td>
    <td> 
<?
db_input('r01_ocorre',2,$Ir01_ocorre,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_basefo?>">
       <?=@$Lr01_basefo?>
    </td>
    <td> 
<?
db_input('r01_basefo',10,$Ir01_basefo,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_descfo?>">
       <?=@$Lr01_descfo?>
    </td>
    <td> 
<?
db_input('r01_descfo',10,$Ir01_descfo,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_b13fo?>">
       <?=@$Lr01_b13fo?>
    </td>
    <td> 
<?
db_input('r01_b13fo',10,$Ir01_b13fo,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_d13fo?>">
       <?=@$Lr01_d13fo?>
    </td>
    <td> 
<?
db_input('r01_d13fo',10,$Ir01_d13fo,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_equip?>">
       <?=@$Lr01_equip?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('r01_equip',$x,true,$db_opcao,"");
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_raca?>">
       <?=@$Lr01_raca?>
    </td>
    <td> 
<?
db_input('r01_raca',1,$Ir01_raca,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_mremun?>">
       <?=@$Lr01_mremun?>
    </td>
    <td> 
<?
db_input('r01_mremun',10,$Ir01_mremun,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_reserv?>">
       <?=@$Lr01_reserv?>
    </td>
    <td> 
<?
db_input('r01_reserv',15,$Ir01_reserv,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_catres?>">
       <?=@$Lr01_catres?>
    </td>
    <td> 
<?
db_input('r01_catres',4,$Ir01_catres,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_propi?>">
       <?=@$Lr01_propi?>
    </td>
    <td> 
<?
db_input('r01_propi',8,$Ir01_propi,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_cargo?>">
       <?=@$Lr01_cargo?>
    </td>
    <td> 
<?
db_input('r01_cargo',5,$Ir01_cargo,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_clas1?>">
       <?=@$Lr01_clas1?>
    </td>
    <td> 
<?
db_input('r01_clas1',5,$Ir01_clas1,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_origp?>">
       <?=@$Lr01_origp?>
    </td>
    <td> 
<?
db_input('r01_origp',6,$Ir01_origp,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  <tr>
    <td nowrap title="<?=@$Tr01_clas2?>">
       <?=@$Lr01_clas2?>
    </td>
    <td> 
<?
db_inputdata('r01_clas2',@$r01_clas2_dia,@$r01_clas2_mes,@$r01_clas2_ano,true,'text',$db_opcao,"")
?>
    <td>
  <tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisar01_lotac(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_lotacao.php?funcao_js=parent.js_mostralotacao1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_lotacao.php?pesquisa_chave='+document.form1.r01_lotac.value+'&funcao_js=parent.js_mostralotacao';
  }
}
function js_mostralotacao(chave,erro){
  document.form1.r13_descr.value = chave; 
  if(erro==true){ 
    document.form1.r01_lotac.focus(); 
    document.form1.r01_lotac.value = ''; 
  }
}
function js_mostralotacao1(chave1,chave2){
  document.form1.r01_lotac.value = chave1;
  document.form1.r13_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisar01_funcao(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_funcao.php?funcao_js=parent.js_mostrafuncao1|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_funcao.php?pesquisa_chave='+document.form1.r01_funcao.value+'&funcao_js=parent.js_mostrafuncao';
  }
}
function js_mostrafuncao(chave,erro){
  document.form1.r37_descr.value = chave; 
  if(erro==true){ 
    document.form1.r01_funcao.focus(); 
    document.form1.r01_funcao.value = ''; 
  }
}
function js_mostrafuncao1(chave1,chave2){
  document.form1.r01_funcao.value = chave1;
  document.form1.r37_descr.value = chave2;
  db_iframe.hide();
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_pessoal.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>