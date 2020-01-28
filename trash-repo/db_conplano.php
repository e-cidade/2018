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

class db_conplano {
	var $reduzido = null;
	var $contrapartida = null;
	var $ano = null;
	var $instit = null;
	var $compara = null;
        var $clconplanoreduz = null;
        var $res = null;
    
	function evento($reduzido = "", $contrapartida = "", $ano = "", $instit = "") {    
	    global $c60_estrut;
		$this->reduzido = $reduzido;
		$this->contrapartida = $contrapartida;
		$this->instit = $instit;
		$this->ano = $ano;
		// 33% - desp corrente
		// 34% - desp capital
		// 4% - receita
	    $this->clconplanoreduz = new cl_conplanoreduz;
		$this->res = $this->clconplanoreduz->sql_record(
		                     $this->clconplanoreduz->sql_query($this->reduzido,null,"c60_estrut",null,"c61_anousu=".$this->ano."   and c61_reduz =".$this->reduzido." and c61_instit = ".$this->instit));
		if ($this->clconplanoreduz->numrows > 0) {
			db_fieldsmemory($this->res, 0);
			if (substr($c60_estrut, 0, 2) == '33') {
				$this->transacao_desp_corrente();
			} else {
			 if (substr($c60_estrut, 0, 2) == '34') {
			  $this->transacao_desp_capital();
				//  }else if (substr($c60_estrut,0,1) =='4'){
			 } else
			  if (substr($c60_estrut, 0, 1) == '4') {
			   $this->transacao_receita();
			  }
		   }
		}
	}
	function transacao_desp_corrente() {
		global $c46_seqtranslan,$achou,$c47_seqtranslr;
		$evento = 3;
		$contranslan = new cl_contranslan;
		$res = $contranslan->sql_record($contranslan->sql_query(null, "c46_seqtranslan", null, "c46_evento=$evento and c45_instit = ".$this->instit." and c45_anousu=".$this->ano."  "));
		if ($contranslan->numrows > 0) {
			db_fieldsmemory($res, 0);
			$clcontranslr = new cl_contranslr;
			$achou = false;			
			$res = $clcontranslr->sql_record($clcontranslr->sql_query_file(null, "c47_seqtranslr", null,"c47_seqtranslan=$c46_seqtranslan  and c47_instit=".$this->instit."  and c47_debito=".$this->reduzido));
			if ($clcontranslr->numrows > 0) {
				$achou = true;
				db_fieldsmemory($res,0);
			}
			$clcontranslr->c47_debito      = $this->reduzido;
			$clcontranslr->c47_credito     = $this->contrapartida;
			$clcontranslr->c47_anousu      = $this->ano;
			$clcontranslr->c47_compara     = 1; // debito
			$clcontranslr->c47_instit      = $this->instit;
			$clcontranslr->c47_tiporesto   = '0';
			$clcontranslr->c47_seqtranslr  = $c47_seqtranslr;
			$clcontranslr->c47_seqtranslan = $c46_seqtranslan;
			if ($achou == false) {
				$clcontranslr->incluir("");
			} else {
				$clcontranslr->alterar($c47_seqtranslr);
			}
		}
		$evento = 4;
		$contranslan = new cl_contranslan;
		$res = $contranslan->sql_record($contranslan->sql_query(null, "c46_seqtranslan", null, "c46_evento=$evento and c45_instit = ".$this->instit." and c45_anousu=".$this->ano."  "));
		if ($contranslan->numrows > 0) {
			db_fieldsmemory($res, 0);
			$clcontranslr = new cl_contranslr;
			$achou = false;
			$res = $clcontranslr->sql_record($clcontranslr->sql_query_file(null, "c47_seqtranslr", null, "c47_seqtranslan=$c46_seqtranslan  and c47_instit=".$this->instit." and c47_credito=".$this->reduzido));
			if ($clcontranslr->numrows > 0) {
				$achou = true;
				db_fieldsmemory($res,0);
			}
			$clcontranslr->c47_debito      = $this->contrapartida;
			$clcontranslr->c47_credito     = $this->reduzido;
			$clcontranslr->c47_anousu      = $this->ano;
			$clcontranslr->c47_compara     = 2; // credito
			$clcontranslr->c47_instit      = $this->instit;
			$clcontranslr->c47_tiporesto   = '0';
		    $clcontranslr->c47_seqtranslr  = $c47_seqtranslr;
			$clcontranslr->c47_seqtranslan = $c46_seqtranslan;
			if ($achou == false) {
				$clcontranslr->incluir("");
			} else {
              if (isset($c47_seqtranslr) && trim($c47_seqtranslr)!=""){
  			 	$clcontranslr->alterar($c47_seqtranslr);
              }
			}
		}
	}
	function transacao_desp_capital() {
		global $c46_seqtranslan, $c47_seqtranslr,$achou;
		$evento = 23;
		$contranslan = new cl_contranslan;
		$res = $contranslan->sql_record(
		            $contranslan->sql_query(null, "c46_seqtranslan", null, "c46_evento=$evento and c45_instit = ".$this->instit." and c45_anousu=".$this->ano." "));
		if ($contranslan->numrows > 0) {
			db_fieldsmemory($res, 0);
			$clcontranslr = new cl_contranslr;
			$achou = false;
			$res = $clcontranslr->sql_record(
			            $clcontranslr->sql_query_file(null, "c47_seqtranslr", null, "c47_seqtranslan=$c46_seqtranslan and c47_instit=".$this->instit." and c47_debito=".$this->reduzido));
			if ($clcontranslr->numrows > 0) {
				$achou = true;
				db_fieldsmemory($res,0);
			}
			$clcontranslr->c47_debito = $this->reduzido;
			$clcontranslr->c47_credito = $this->contrapartida;
			$clcontranslr->c47_anousu = $this->ano;
			$clcontranslr->c47_compara = 1; // debito
			$clcontranslr->c47_instit = $this->instit;
			$clcontranslr->c47_tiporesto = '0';
			$clcontranslr->c47_seqtranslan = $c46_seqtranslan;
			if ($achou == false) {
				$clcontranslr->incluir("");
			} else {
				$clcontranslr->alterar($c47_seqtranslr);
			};
		}
		$evento = 24;
		$contranslan = new cl_contranslan;
		$res = $contranslan->sql_record(
		            $contranslan->sql_query(null, "c46_seqtranslan", null, "c46_evento=$evento and c45_instit = ".$this->instit." and c45_anousu=".$this->ano." "));
		if ($contranslan->numrows > 0) {
			db_fieldsmemory($res, 0);
			$clcontranslr = new cl_contranslr;
			$achou = false;
			$res = $clcontranslr->sql_record($clcontranslr->sql_query_file(null, "c47_seqtranslr", null, "c47_seqtranslan=$c46_seqtranslan and c47_instit=".$this->instit." and c47_credito=".$this->reduzido));
			if ($clcontranslr->numrows > 0) {
				$achou = true;
				db_fieldsmemory($res,0);
			}
			$clcontranslr->c47_debito = $this->contrapartida;
			$clcontranslr->c47_credito = $this->reduzido;
			$clcontranslr->c47_anousu = $this->ano;
			$clcontranslr->c47_compara = 2; // credito
			$clcontranslr->c47_instit = $this->instit;
			$clcontranslr->c47_tiporesto = '0';
			$clcontranslr->c47_seqtranslan = $c46_seqtranslan;
			if ($achou == false) {
				$clcontranslr->incluir("");
			} else {
				$clcontranslr->alterar($c47_seqtranslr);
			};
		}

	}
	function transacao_receita() {
		global $c46_seqtranslan,$c47_seqtranslr,$achou;
		$evento = 100; // arrecadacao de receita
		$contranslan = new cl_contranslan;
		$res = $contranslan->sql_record(
			$contranslan->sql_query(null, "c46_seqtranslan", null, "c46_evento=$evento and c45_instit = ".$this->instit." and c45_anousu=".$this->ano." "));
		if ($contranslan->numrows > 0) {
			db_fieldsmemory($res, 0);
			$clcontranslr = new cl_contranslr;
			$achou = false;
			$res = $clcontranslr->sql_record($clcontranslr->sql_query_file(null, "c47_seqtranslr", null, "c47_seqtranslan=$c46_seqtranslan and c47_instit=".$this->instit." and c47_credito=".$this->reduzido));
			if ($clcontranslr->numrows > 0) {
				 $achou = true;
				 db_fieldsmemory($res,0);
			}
			$clcontranslr->c47_debito = '0';
			$clcontranslr->c47_credito = $this->reduzido;
			$clcontranslr->c47_anousu = $this->ano;
			$clcontranslr->c47_compara = 2; // credita receita
			$clcontranslr->c47_instit = $this->instit;
			$clcontranslr->c47_tiporesto = '0';
			$clcontranslr->c47_seqtranslan = $c46_seqtranslan;
			if ($achou == false) {
				$clcontranslr->incluir("");
			} else {
				$clcontranslr->alterar($c47_seqtranslr);
			}
		}
		$evento = 101; // estorno de receita
		$contranslan = new cl_contranslan;
		$res = $contranslan->sql_record($contranslan->sql_query(null, "c46_seqtranslan", null, "c46_evento=$evento and c45_instit = ".$this->instit." and c45_anousu=".$this->ano." "));
		if ($contranslan->numrows > 0) {
			db_fieldsmemory($res, 0);
			$clcontranslr = new cl_contranslr;
			$achou = false;
			$res = $clcontranslr->sql_record($clcontranslr->sql_query_file(null, "c47_seqtranslr", null, "c47_seqtranslan=$c46_seqtranslan and c47_instit=".$this->instit." and c47_debito=".$this->reduzido));
			if ($clcontranslr->numrows > 0) {
			  $achou = true;
			  db_fieldsmemory($res,0);
			}
			$clcontranslr->c47_debito = $this->reduzido;
			$clcontranslr->c47_credito = '0';
			$clcontranslr->c47_anousu = $this->ano;
			$clcontranslr->c47_compara = 1; // debita receita
			$clcontranslr->c47_instit = $this->instit;
			$clcontranslr->c47_tiporesto = '0';
			$clcontranslr->c47_seqtranslan = $c46_seqtranslan;
			if ($achou == false) {
			  $clcontranslr->incluir("");
			} else {
			  $clcontranslr->alterar($c47_seqtranslr);
			}
		}
	}
}
?>