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

//MODULO: caixa
//CLASSE DA ENTIDADE arreprescr
class cl_arreprescr { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $k30_sequencial = 0; 
   var $k30_numpre = 0; 
   var $k30_numpar = 0; 
   var $k30_numcgm = 0; 
   var $k30_dtoper_dia = null; 
   var $k30_dtoper_mes = null; 
   var $k30_dtoper_ano = null; 
   var $k30_dtoper = null; 
   var $k30_receit = 0; 
   var $k30_hist = 0; 
   var $k30_valor = 0; 
   var $k30_dtvenc_dia = null; 
   var $k30_dtvenc_mes = null; 
   var $k30_dtvenc_ano = null; 
   var $k30_dtvenc = null; 
   var $k30_numtot = 0; 
   var $k30_numdig = 0; 
   var $k30_tipo = 0; 
   var $k30_tipojm = 0; 
   var $k30_prescricao = 0; 
   var $k30_vlrcorr = 0; 
   var $k30_vlrjuros = 0; 
   var $k30_multa = 0; 
   var $k30_desconto = 0; 
   var $k30_anulado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k30_sequencial = int4 = Sequencial 
                 k30_numpre = int4 = Numpre 
                 k30_numpar = int4 = Parcela 
                 k30_numcgm = int4 = cgm 
                 k30_dtoper = date = Data de operacao 
                 k30_receit = int4 = codigo da receita 
                 k30_hist = int4 = Hist.Calc. 
                 k30_valor = float8 = Valor 
                 k30_dtvenc = date = Data de vencimento 
                 k30_numtot = int4 = Total de parcelas 
                 k30_numdig = int4 = Digito verificador 
                 k30_tipo = int4 = tipo de debito 
                 k30_tipojm = int4 = Tipo de juro e multa 
                 k30_prescricao = int4 = Código da prescricao 
                 k30_vlrcorr = float8 = Valor corrigido 
                 k30_vlrjuros = float8 = Juros 
                 k30_multa = float8 = Multa 
                 k30_desconto = float8 = Desconto 
                 k30_anulado = bool = Anulado 
                 ";
   //funcao construtor da classe 
   function cl_arreprescr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arreprescr"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->k30_sequencial = ($this->k30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_sequencial"]:$this->k30_sequencial);
       $this->k30_numpre = ($this->k30_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_numpre"]:$this->k30_numpre);
       $this->k30_numpar = ($this->k30_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_numpar"]:$this->k30_numpar);
       $this->k30_numcgm = ($this->k30_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_numcgm"]:$this->k30_numcgm);
       if($this->k30_dtoper == ""){
         $this->k30_dtoper_dia = ($this->k30_dtoper_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_dtoper_dia"]:$this->k30_dtoper_dia);
         $this->k30_dtoper_mes = ($this->k30_dtoper_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_dtoper_mes"]:$this->k30_dtoper_mes);
         $this->k30_dtoper_ano = ($this->k30_dtoper_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_dtoper_ano"]:$this->k30_dtoper_ano);
         if($this->k30_dtoper_dia != ""){
            $this->k30_dtoper = $this->k30_dtoper_ano."-".$this->k30_dtoper_mes."-".$this->k30_dtoper_dia;
         }
       }
       $this->k30_receit = ($this->k30_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_receit"]:$this->k30_receit);
       $this->k30_hist = ($this->k30_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_hist"]:$this->k30_hist);
       $this->k30_valor = ($this->k30_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_valor"]:$this->k30_valor);
       if($this->k30_dtvenc == ""){
         $this->k30_dtvenc_dia = ($this->k30_dtvenc_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_dtvenc_dia"]:$this->k30_dtvenc_dia);
         $this->k30_dtvenc_mes = ($this->k30_dtvenc_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_dtvenc_mes"]:$this->k30_dtvenc_mes);
         $this->k30_dtvenc_ano = ($this->k30_dtvenc_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_dtvenc_ano"]:$this->k30_dtvenc_ano);
         if($this->k30_dtvenc_dia != ""){
            $this->k30_dtvenc = $this->k30_dtvenc_ano."-".$this->k30_dtvenc_mes."-".$this->k30_dtvenc_dia;
         }
       }
       $this->k30_numtot = ($this->k30_numtot == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_numtot"]:$this->k30_numtot);
       $this->k30_numdig = ($this->k30_numdig == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_numdig"]:$this->k30_numdig);
       $this->k30_tipo = ($this->k30_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_tipo"]:$this->k30_tipo);
       $this->k30_tipojm = ($this->k30_tipojm == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_tipojm"]:$this->k30_tipojm);
       $this->k30_prescricao = ($this->k30_prescricao == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_prescricao"]:$this->k30_prescricao);
       $this->k30_vlrcorr = ($this->k30_vlrcorr == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_vlrcorr"]:$this->k30_vlrcorr);
       $this->k30_vlrjuros = ($this->k30_vlrjuros == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_vlrjuros"]:$this->k30_vlrjuros);
       $this->k30_multa = ($this->k30_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_multa"]:$this->k30_multa);
       $this->k30_desconto = ($this->k30_desconto == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_desconto"]:$this->k30_desconto);
       $this->k30_anulado = ($this->k30_anulado == "f"?@$GLOBALS["HTTP_POST_VARS"]["k30_anulado"]:$this->k30_anulado);
     }else{
       $this->k30_sequencial = ($this->k30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k30_sequencial"]:$this->k30_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k30_sequencial){ 
      $this->atualizacampos();
     if($this->k30_numpre == null ){ 
       $this->erro_sql = " Campo Numpre nao Informado.";
       $this->erro_campo = "k30_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_numpar == null ){ 
       $this->erro_sql = " Campo Parcela nao Informado.";
       $this->erro_campo = "k30_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_numcgm == null ){ 
       $this->erro_sql = " Campo cgm nao Informado.";
       $this->erro_campo = "k30_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_dtoper == null ){ 
       $this->erro_sql = " Campo Data de operacao nao Informado.";
       $this->erro_campo = "k30_dtoper_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_receit == null ){ 
       $this->erro_sql = " Campo codigo da receita nao Informado.";
       $this->erro_campo = "k30_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_hist == null ){ 
       $this->erro_sql = " Campo Hist.Calc. nao Informado.";
       $this->erro_campo = "k30_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "k30_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_dtvenc == null ){ 
       $this->erro_sql = " Campo Data de vencimento nao Informado.";
       $this->erro_campo = "k30_dtvenc_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_numtot == null ){ 
       $this->erro_sql = " Campo Total de parcelas nao Informado.";
       $this->erro_campo = "k30_numtot";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_numdig == null ){ 
       $this->erro_sql = " Campo Digito verificador nao Informado.";
       $this->erro_campo = "k30_numdig";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_tipo == null ){ 
       $this->erro_sql = " Campo tipo de debito nao Informado.";
       $this->erro_campo = "k30_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_tipojm == null ){ 
       $this->erro_sql = " Campo Tipo de juro e multa nao Informado.";
       $this->erro_campo = "k30_tipojm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_prescricao == null ){ 
       $this->erro_sql = " Campo Código da prescricao nao Informado.";
       $this->erro_campo = "k30_prescricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_vlrcorr == null ){ 
       $this->erro_sql = " Campo Valor corrigido nao Informado.";
       $this->erro_campo = "k30_vlrcorr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_vlrjuros == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "k30_vlrjuros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_multa == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "k30_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_desconto == null ){ 
       $this->erro_sql = " Campo Desconto nao Informado.";
       $this->erro_campo = "k30_desconto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k30_anulado == null ){ 
       $this->erro_sql = " Campo Anulado nao Informado.";
       $this->erro_campo = "k30_anulado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k30_sequencial == "" || $k30_sequencial == null ){
       $result = db_query("select nextval('arreprescr_k30_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arreprescr_k30_sequencial_seq do campo: k30_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k30_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arreprescr_k30_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k30_sequencial)){
         $this->erro_sql = " Campo k30_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k30_sequencial = $k30_sequencial; 
       }
     }
     if(($this->k30_sequencial == null) || ($this->k30_sequencial == "") ){ 
       $this->erro_sql = " Campo k30_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arreprescr(
                                       k30_sequencial 
                                      ,k30_numpre 
                                      ,k30_numpar 
                                      ,k30_numcgm 
                                      ,k30_dtoper 
                                      ,k30_receit 
                                      ,k30_hist 
                                      ,k30_valor 
                                      ,k30_dtvenc 
                                      ,k30_numtot 
                                      ,k30_numdig 
                                      ,k30_tipo 
                                      ,k30_tipojm 
                                      ,k30_prescricao 
                                      ,k30_vlrcorr 
                                      ,k30_vlrjuros 
                                      ,k30_multa 
                                      ,k30_desconto 
                                      ,k30_anulado 
                       )
                values (
                                $this->k30_sequencial 
                               ,$this->k30_numpre 
                               ,$this->k30_numpar 
                               ,$this->k30_numcgm 
                               ,".($this->k30_dtoper == "null" || $this->k30_dtoper == ""?"null":"'".$this->k30_dtoper."'")." 
                               ,$this->k30_receit 
                               ,$this->k30_hist 
                               ,$this->k30_valor 
                               ,".($this->k30_dtvenc == "null" || $this->k30_dtvenc == ""?"null":"'".$this->k30_dtvenc."'")." 
                               ,$this->k30_numtot 
                               ,$this->k30_numdig 
                               ,$this->k30_tipo 
                               ,$this->k30_tipojm 
                               ,$this->k30_prescricao 
                               ,$this->k30_vlrcorr 
                               ,$this->k30_vlrjuros 
                               ,$this->k30_multa 
                               ,$this->k30_desconto 
                               ,'$this->k30_anulado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros de debitos prescritos ($this->k30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros de debitos prescritos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros de debitos prescritos ($this->k30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k30_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k30_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17635,'$this->k30_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1236,17635,'','".AddSlashes(pg_result($resaco,0,'k30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7437,'','".AddSlashes(pg_result($resaco,0,'k30_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7438,'','".AddSlashes(pg_result($resaco,0,'k30_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7439,'','".AddSlashes(pg_result($resaco,0,'k30_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7440,'','".AddSlashes(pg_result($resaco,0,'k30_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7441,'','".AddSlashes(pg_result($resaco,0,'k30_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7442,'','".AddSlashes(pg_result($resaco,0,'k30_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7443,'','".AddSlashes(pg_result($resaco,0,'k30_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7444,'','".AddSlashes(pg_result($resaco,0,'k30_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7445,'','".AddSlashes(pg_result($resaco,0,'k30_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7446,'','".AddSlashes(pg_result($resaco,0,'k30_numdig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7447,'','".AddSlashes(pg_result($resaco,0,'k30_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7448,'','".AddSlashes(pg_result($resaco,0,'k30_tipojm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7453,'','".AddSlashes(pg_result($resaco,0,'k30_prescricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7619,'','".AddSlashes(pg_result($resaco,0,'k30_vlrcorr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7620,'','".AddSlashes(pg_result($resaco,0,'k30_vlrjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7621,'','".AddSlashes(pg_result($resaco,0,'k30_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,7622,'','".AddSlashes(pg_result($resaco,0,'k30_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1236,17634,'','".AddSlashes(pg_result($resaco,0,'k30_anulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k30_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update arreprescr set ";
     $virgula = "";
     if(trim($this->k30_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_sequencial"])){ 
       $sql  .= $virgula." k30_sequencial = $this->k30_sequencial ";
       $virgula = ",";
       if(trim($this->k30_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k30_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_numpre"])){ 
       $sql  .= $virgula." k30_numpre = $this->k30_numpre ";
       $virgula = ",";
       if(trim($this->k30_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre nao Informado.";
         $this->erro_campo = "k30_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_numpar"])){ 
       $sql  .= $virgula." k30_numpar = $this->k30_numpar ";
       $virgula = ",";
       if(trim($this->k30_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela nao Informado.";
         $this->erro_campo = "k30_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_numcgm"])){ 
       $sql  .= $virgula." k30_numcgm = $this->k30_numcgm ";
       $virgula = ",";
       if(trim($this->k30_numcgm) == null ){ 
         $this->erro_sql = " Campo cgm nao Informado.";
         $this->erro_campo = "k30_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_dtoper)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_dtoper_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k30_dtoper_dia"] !="") ){ 
       $sql  .= $virgula." k30_dtoper = '$this->k30_dtoper' ";
       $virgula = ",";
       if(trim($this->k30_dtoper) == null ){ 
         $this->erro_sql = " Campo Data de operacao nao Informado.";
         $this->erro_campo = "k30_dtoper_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k30_dtoper_dia"])){ 
         $sql  .= $virgula." k30_dtoper = null ";
         $virgula = ",";
         if(trim($this->k30_dtoper) == null ){ 
           $this->erro_sql = " Campo Data de operacao nao Informado.";
           $this->erro_campo = "k30_dtoper_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k30_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_receit"])){ 
       $sql  .= $virgula." k30_receit = $this->k30_receit ";
       $virgula = ",";
       if(trim($this->k30_receit) == null ){ 
         $this->erro_sql = " Campo codigo da receita nao Informado.";
         $this->erro_campo = "k30_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_hist"])){ 
       $sql  .= $virgula." k30_hist = $this->k30_hist ";
       $virgula = ",";
       if(trim($this->k30_hist) == null ){ 
         $this->erro_sql = " Campo Hist.Calc. nao Informado.";
         $this->erro_campo = "k30_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_valor"])){ 
       $sql  .= $virgula." k30_valor = $this->k30_valor ";
       $virgula = ",";
       if(trim($this->k30_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "k30_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_dtvenc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_dtvenc_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k30_dtvenc_dia"] !="") ){ 
       $sql  .= $virgula." k30_dtvenc = '$this->k30_dtvenc' ";
       $virgula = ",";
       if(trim($this->k30_dtvenc) == null ){ 
         $this->erro_sql = " Campo Data de vencimento nao Informado.";
         $this->erro_campo = "k30_dtvenc_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k30_dtvenc_dia"])){ 
         $sql  .= $virgula." k30_dtvenc = null ";
         $virgula = ",";
         if(trim($this->k30_dtvenc) == null ){ 
           $this->erro_sql = " Campo Data de vencimento nao Informado.";
           $this->erro_campo = "k30_dtvenc_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k30_numtot)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_numtot"])){ 
       $sql  .= $virgula." k30_numtot = $this->k30_numtot ";
       $virgula = ",";
       if(trim($this->k30_numtot) == null ){ 
         $this->erro_sql = " Campo Total de parcelas nao Informado.";
         $this->erro_campo = "k30_numtot";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_numdig)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_numdig"])){ 
       $sql  .= $virgula." k30_numdig = $this->k30_numdig ";
       $virgula = ",";
       if(trim($this->k30_numdig) == null ){ 
         $this->erro_sql = " Campo Digito verificador nao Informado.";
         $this->erro_campo = "k30_numdig";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_tipo"])){ 
       $sql  .= $virgula." k30_tipo = $this->k30_tipo ";
       $virgula = ",";
       if(trim($this->k30_tipo) == null ){ 
         $this->erro_sql = " Campo tipo de debito nao Informado.";
         $this->erro_campo = "k30_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_tipojm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_tipojm"])){ 
       $sql  .= $virgula." k30_tipojm = $this->k30_tipojm ";
       $virgula = ",";
       if(trim($this->k30_tipojm) == null ){ 
         $this->erro_sql = " Campo Tipo de juro e multa nao Informado.";
         $this->erro_campo = "k30_tipojm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_prescricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_prescricao"])){ 
       $sql  .= $virgula." k30_prescricao = $this->k30_prescricao ";
       $virgula = ",";
       if(trim($this->k30_prescricao) == null ){ 
         $this->erro_sql = " Campo Código da prescricao nao Informado.";
         $this->erro_campo = "k30_prescricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_vlrcorr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_vlrcorr"])){ 
       $sql  .= $virgula." k30_vlrcorr = $this->k30_vlrcorr ";
       $virgula = ",";
       if(trim($this->k30_vlrcorr) == null ){ 
         $this->erro_sql = " Campo Valor corrigido nao Informado.";
         $this->erro_campo = "k30_vlrcorr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_vlrjuros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_vlrjuros"])){ 
       $sql  .= $virgula." k30_vlrjuros = $this->k30_vlrjuros ";
       $virgula = ",";
       if(trim($this->k30_vlrjuros) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "k30_vlrjuros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_multa"])){ 
       $sql  .= $virgula." k30_multa = $this->k30_multa ";
       $virgula = ",";
       if(trim($this->k30_multa) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "k30_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_desconto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_desconto"])){ 
       $sql  .= $virgula." k30_desconto = $this->k30_desconto ";
       $virgula = ",";
       if(trim($this->k30_desconto) == null ){ 
         $this->erro_sql = " Campo Desconto nao Informado.";
         $this->erro_campo = "k30_desconto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k30_anulado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k30_anulado"])){ 
       $sql  .= $virgula." k30_anulado = '$this->k30_anulado' ";
       $virgula = ",";
       if(trim($this->k30_anulado) == null ){ 
         $this->erro_sql = " Campo Anulado nao Informado.";
         $this->erro_campo = "k30_anulado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k30_sequencial!=null){
       $sql .= " k30_sequencial = $this->k30_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k30_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17635,'$this->k30_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_sequencial"]) || $this->k30_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1236,17635,'".AddSlashes(pg_result($resaco,$conresaco,'k30_sequencial'))."','$this->k30_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_numpre"]) || $this->k30_numpre != "")
           $resac = db_query("insert into db_acount values($acount,1236,7437,'".AddSlashes(pg_result($resaco,$conresaco,'k30_numpre'))."','$this->k30_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_numpar"]) || $this->k30_numpar != "")
           $resac = db_query("insert into db_acount values($acount,1236,7438,'".AddSlashes(pg_result($resaco,$conresaco,'k30_numpar'))."','$this->k30_numpar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_numcgm"]) || $this->k30_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,1236,7439,'".AddSlashes(pg_result($resaco,$conresaco,'k30_numcgm'))."','$this->k30_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_dtoper"]) || $this->k30_dtoper != "")
           $resac = db_query("insert into db_acount values($acount,1236,7440,'".AddSlashes(pg_result($resaco,$conresaco,'k30_dtoper'))."','$this->k30_dtoper',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_receit"]) || $this->k30_receit != "")
           $resac = db_query("insert into db_acount values($acount,1236,7441,'".AddSlashes(pg_result($resaco,$conresaco,'k30_receit'))."','$this->k30_receit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_hist"]) || $this->k30_hist != "")
           $resac = db_query("insert into db_acount values($acount,1236,7442,'".AddSlashes(pg_result($resaco,$conresaco,'k30_hist'))."','$this->k30_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_valor"]) || $this->k30_valor != "")
           $resac = db_query("insert into db_acount values($acount,1236,7443,'".AddSlashes(pg_result($resaco,$conresaco,'k30_valor'))."','$this->k30_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_dtvenc"]) || $this->k30_dtvenc != "")
           $resac = db_query("insert into db_acount values($acount,1236,7444,'".AddSlashes(pg_result($resaco,$conresaco,'k30_dtvenc'))."','$this->k30_dtvenc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_numtot"]) || $this->k30_numtot != "")
           $resac = db_query("insert into db_acount values($acount,1236,7445,'".AddSlashes(pg_result($resaco,$conresaco,'k30_numtot'))."','$this->k30_numtot',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_numdig"]) || $this->k30_numdig != "")
           $resac = db_query("insert into db_acount values($acount,1236,7446,'".AddSlashes(pg_result($resaco,$conresaco,'k30_numdig'))."','$this->k30_numdig',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_tipo"]) || $this->k30_tipo != "")
           $resac = db_query("insert into db_acount values($acount,1236,7447,'".AddSlashes(pg_result($resaco,$conresaco,'k30_tipo'))."','$this->k30_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_tipojm"]) || $this->k30_tipojm != "")
           $resac = db_query("insert into db_acount values($acount,1236,7448,'".AddSlashes(pg_result($resaco,$conresaco,'k30_tipojm'))."','$this->k30_tipojm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_prescricao"]) || $this->k30_prescricao != "")
           $resac = db_query("insert into db_acount values($acount,1236,7453,'".AddSlashes(pg_result($resaco,$conresaco,'k30_prescricao'))."','$this->k30_prescricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_vlrcorr"]) || $this->k30_vlrcorr != "")
           $resac = db_query("insert into db_acount values($acount,1236,7619,'".AddSlashes(pg_result($resaco,$conresaco,'k30_vlrcorr'))."','$this->k30_vlrcorr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_vlrjuros"]) || $this->k30_vlrjuros != "")
           $resac = db_query("insert into db_acount values($acount,1236,7620,'".AddSlashes(pg_result($resaco,$conresaco,'k30_vlrjuros'))."','$this->k30_vlrjuros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_multa"]) || $this->k30_multa != "")
           $resac = db_query("insert into db_acount values($acount,1236,7621,'".AddSlashes(pg_result($resaco,$conresaco,'k30_multa'))."','$this->k30_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_desconto"]) || $this->k30_desconto != "")
           $resac = db_query("insert into db_acount values($acount,1236,7622,'".AddSlashes(pg_result($resaco,$conresaco,'k30_desconto'))."','$this->k30_desconto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k30_anulado"]) || $this->k30_anulado != "")
           $resac = db_query("insert into db_acount values($acount,1236,17634,'".AddSlashes(pg_result($resaco,$conresaco,'k30_anulado'))."','$this->k30_anulado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros de debitos prescritos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros de debitos prescritos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k30_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k30_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17635,'$k30_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1236,17635,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7437,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7438,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_numpar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7439,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7440,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_dtoper'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7441,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_receit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7442,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7443,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7444,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_dtvenc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7445,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_numtot'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7446,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_numdig'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7447,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7448,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_tipojm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7453,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_prescricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7619,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_vlrcorr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7620,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_vlrjuros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7621,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,7622,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_desconto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1236,17634,'','".AddSlashes(pg_result($resaco,$iresaco,'k30_anulado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arreprescr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k30_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k30_sequencial = $k30_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros de debitos prescritos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros de debitos prescritos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:arreprescr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from arreprescr ";
     $sql .= "      inner join cgm         on  cgm.z01_numcgm        = arreprescr.k30_numcgm";
     $sql .= "      inner join histcalc    on  histcalc.k01_codigo   = arreprescr.k30_hist";
     $sql .= "      inner join tabrec      on  tabrec.k02_codigo     = arreprescr.k30_receit";
     $sql .= "      inner join prescricao  on  prescricao.k31_codigo = arreprescr.k30_prescricao";
     $sql .= "                            and  prescricao.k31_instit = ".db_getsession("DB_instit");
     $sql .= "      inner join tabrecjm    on  tabrecjm.k02_codjm    = tabrec.k02_codjm";
     $sql .= "      inner join db_usuarios on  db_usuarios.id_usuario = prescricao.k31_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($k30_sequencial!=null ){
         $sql2 .= " where arreprescr.k30_sequencial = $k30_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $k30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from arreprescr ";
     $sql2 = "";
     if($dbwhere==""){
       if($k30_sequencial!=null ){
         $sql2 .= " where arreprescr.k30_sequencial = $k30_sequencial "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }

  /**
   * metodo para retornar as dividas prescritas
   * @param string $sCampos
   * @param string $sOrdem
   * @param string $sWhere
   * @return string $sSql
   */
  function sql_query_divida_prescrita($sCampos = "*", $sOrdem = null, $sWhere = "") {
  
   $sSql = "select ";
  
   if ( $sCampos != "*" ) {
  
    $sCampos_sql = split("#", $sCampos);
    $virgula    = "";
  
    for ($i = 0; $i < sizeof($sCampos_sql); $i++) {
  
     $sSql .= $virgula . $sCampos_sql[$i];
     $virgula = ",";
    }
   } else {
  
    $sSql .= $sCampos. " \n";
   }
    
   $sSql .= "  from arreprescr                                                                                       \n";
   $sSql .= "       inner join prescricao      on k31_codigo            = k30_prescricao                             \n";
   $sSql .= "                                 and k31_instit            = ".db_getsession('DB_instit')."             \n";
   $sSql .= "       inner join arretipo        on k00_tipo              = k30_tipo                                   \n";
   $sSql .= "       inner join divida          on v01_numpre            = arreprescr.k30_numpre                      \n";
   $sSql .= "                                 and v01_numpar            = arreprescr.k30_numpar                      \n";
   $sSql .= "       inner join proced          on v01_proced            = v03_codigo                                 \n";
   $sSql .= "       inner join tabrec          on k02_codigo            = k30_receit                                 \n";
   $sSql .= "       inner join taborc          on taborc.k02_codigo     = tabrec.k02_codigo                          \n";
   $sSql .= "                                 and taborc.k02_anousu     = ".date('Y', db_getsession('DB_datausu'))." \n";
   $sSql .= "       inner join cgm             on z01_numcgm            = k30_numcgm                                 \n";
   $sSql .= "       inner join db_usuarios     on id_usuario            = k31_usuario                                \n";
   $sSql .= "       inner join tipoproced      on v07_sequencial        = v03_tributaria                             \n";
   $sSql .= "       left  join arreinscr       on arreinscr.k00_numpre  = k30_numpre                                 \n";
   $sSql .= "       left  join arrematric      on arrematric.k00_numpre = k30_numpre                                 \n";
  
   $sSql2 = "";
  
   if ( $sWhere != "" ) {
  
    $sSql2 = " where $sWhere \n";
   }
  
   $sSql .= $sSql2;
  
   if ( $sOrdem != null ) {
  
    $sSql       .= " order by ";
    $sCampos_sql = split("#", $sOrdem);
    $virgula    = "";
  
    for ($i = 0; $i < sizeof($sCampos_sql); $i++) {
  
     $sSql    .= $virgula.$sCampos_sql[$i];
     $virgula = ",";
    }
   }
  
   return $sSql;
  }
  
}
?>