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

//MODULO: empenho
$clempautidot->rotulo->label();
$clrotulo = new rotulocampo;
$clorcsuplemval->rotulo->label();
$clorcdotacao->rotulo->label();
$clrotulo->label("o58_orgao");
$clrotulo->label("o46_codlei");
$clrotulo->label("o47_anousu");
$clrotulo->label("c53_descr");
$clrotulo->label("e54_valor");
$clrotulo->label("o56_elemento");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
      <td nowrap title="<?=@$Te56_autori?>"><?=@$Le56_autori?></td>
      <td><? db_input('e56_autori',8,$Ie56_autori,true,'text',3); ?> </td>
      </tr>
  <tr>
      <td nowrap title="<?=@$Te56_anousu?>"><?=$Le56_anousu?></td>
      <td><?  if(empty($e56_anousu)){
               $e56_anousu = db_getsession('DB_anousu');
             }
             db_input('e56_anousu',4,$Ie56_anousu,true,'text',3);
          ?></td>
      </tr>
  <tr>
      <td nowrap title="<?=@$To47_coddot?>"> <? db_ancora(@$Lo47_coddot,"js_pesquisao47_coddot(true);",$db_opcao); ?> </td>
      <td><? db_input('o47_coddot',8,$Io47_coddot,true,'text',$db_opcao,"onchange='js_dot();'"); ?> </td>
      <td> &nbsp;  </td>
      <td> &nbsp;  </td>
      <td> &nbsp;  </td>
      <td> &nbsp;  </td>
      </tr>

     <?    /* busca dados da dotação  */
     if((isset($o47_coddot) && !$o47_coddot== "") && empty($confirmar) && empty($cancelar)){
          $instit=db_getsession('DB_instit');
          $clorcdotacao->sql_record($clorcdotacao->sql_query_file("","","*","","o58_coddot=$o47_coddot and o58_instit=$instit"));
          if($clorcdotacao->numrows >0){
              $result= db_dotacaosaldo(8,2,2,"true","o58_coddot=$o47_coddot" ,db_getsession("DB_anousu")) ;
	      $rnum = pg_numrows($result);
	      if ($rnum > 0 ){
                  db_fieldsmemory($result,0);
	          $atual=number_format($atual,2,",",".");
	          $reservado=number_format($reservado,2,",",".");
                  $atudo=number_format($atual_menos_reservado,2,",",".");
	      }
	   }else{
	     $nops=" Dotação $o47_coddot  não encontrada ";
	   }
      }
      ?>
  <tr>
      <td nowrap title="<?=@$To58_orgao ?>"><?=@$Lo58_orgao ?> </td>
      <td><? db_input('o58_orgao',8,"$Io58_orgao",true,'text',3,"");  ?> </td>
      <td colspan=3><? db_input('o40_descr',50,"",true,'text',3,"");  ?> </td>
      <td> </td>
      </tr>
  <tr>
      <td nowrap title="<?=@$To58_unidade ?>"><?=@$Lo58_unidade ?> </td>
      <td><? db_input('o58_unidade',8,"",true,'text',3,"");  ?> </td>
      <td colspan=3 ><? db_input('o41_descr',50,"",true,'text',3,"");  ?>  </td>
      <td>  </td>
      </tr>
  <tr>
      <td nowrap title="<?=@$To58_funcao ?>"><?=@$Lo58_funcao ?> </td>
      <td> <? db_input('o58_funcao',8,"",true,'text',3,"");  ?> </td>
      <td> <? db_input('o52_descr',50,"",true,'text',3,"");  ?>  </td>
      </tr>
  <tr>
      <td nowrap title="<?=@$To58_subfuncao ?>" ><?=@$Lo58_subfuncao ?> </td>
      <td> <? db_input('o58_subfuncao',8,"",true,'text',3,"");  ?>  </td>
      <td><? db_input('o53_descr',50,"",true,'text',3,"");  ?></td>
      </tr>
  <tr>
      <td nowrap title="<?=@$To58_programa ?>"    ><?=@$Lo58_programa ?> </td>
      <td><? db_input('o58_programa',8,"",true,'text',3,"");  ?> </td>
      <td><? db_input('o54_descr',50,"",true,'text',3,"");  ?>       </td>
      </tr>
  <tr>
      <td nowrap title="<?=@$To58_projativ ?>"><?=@$Lo58_projativ ?> </td>
      <td><? db_input('o58_projativ',8,"",true,'text',3,"");  ?> </td>
      <td><? db_input('o55_descr',50,"",true,'text',3,"");  ?>    </td>
      </tr>
  <tr>
      <td nowrap title="<?=@$To56_elemento ?>" ><?=@$Lo56_elemento ?> </td>
      <td> <? db_input('o58_elemento',8,"",true,'text',3,"");  ?>  </td>
      <td> <? db_input('o56_descr',50,"",true,'text',3,"");  ?>       </td>
      </tr>
  <tr>
      <td nowrap title="<?=@$To58_codigo ?>" ><?=@$Lo58_codigo ?> </td>
      <td> <? db_input('o58_codigo',8,"",true,'text',3,"");  ?> </td>
      <td> <? db_input('o15_descr',50,"",true,'text',3,"");  ?> </td>
      </tr>
  <tr>
      <td>&nbsp;</td>
      <td colspan='2'>
              <table>
	         <tr>
		   <td>Saldo da dotação:</td>
		   <td><? db_input('atual',13,"",true,'text',3,""); ?></td>
		 </tr>
		   <td>Valor reservado:</td>
		   <td>  <? db_input('reservado',13,"",true,'text',3,""); ?></td>
		</tr>
		<tr>
		   <td>Valor disponível: </td>
		   <td><? db_input('atudo',13,"",true,'text',3,"");?></td>
		</tr>
		<tr>
		   <td><?=$RLe54_valor?></td>
		   <?
		     $result = $clempautitem->sql_record($clempautitem->sql_query_file($e56_autori,null,"sum(e55_vltot) as e54_valor"));
		     if ($clempautitem->numrows > 0 ) {
		         db_fieldsmemory($result,0);
		         if(isset($atual_menos_reservado)&&isset($e54_valor)){
 		            $tot= number_format(($atual_menos_reservado- $e54_valor),2,",",".");
		         }
		         if (isset($e54_valor))
		            $e54_valor=number_format($e54_valor,2,",",".");
		     }
		   ?>
	        <td><? db_input('e54_valor',13,"",true,'text',3,""); ?></td>
  	       </tr>
	      </table>
      </td>
      </tr>
  </table>

 </center>
</form>