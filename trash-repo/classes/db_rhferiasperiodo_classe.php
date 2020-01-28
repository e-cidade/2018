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

//MODULO: pessoal
//CLASSE DA ENTIDADE rhferiasperiodo
class cl_rhferiasperiodo { 
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
   var $rh110_sequencial = 0; 
   var $rh110_rhferias = 0; 
   var $rh110_dias = 0; 
   var $rh110_datainicial_dia = null; 
   var $rh110_datainicial_mes = null; 
   var $rh110_datainicial_ano = null; 
   var $rh110_datainicial = null; 
   var $rh110_datafinal_dia = null; 
   var $rh110_datafinal_mes = null; 
   var $rh110_datafinal_ano = null; 
   var $rh110_datafinal = null; 
   var $rh110_observacao = null; 
   var $rh110_anopagamento = 0; 
   var $rh110_mespagamento = 0; 
   var $rh110_diasabono = 0; 
   var $rh110_pagaterco = 'f'; 
   var $rh110_tipoponto = null; 
   var $rh110_periodoespecificoinicial_dia = null; 
   var $rh110_periodoespecificoinicial_mes = null; 
   var $rh110_periodoespecificoinicial_ano = null; 
   var $rh110_periodoespecificoinicial = null; 
   var $rh110_periodoespecificofinal_dia = null; 
   var $rh110_periodoespecificofinal_mes = null; 
   var $rh110_periodoespecificofinal_ano = null; 
   var $rh110_periodoespecificofinal = null; 
   var $rh110_situacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh110_sequencial = int4 = Sequencial 
                 rh110_rhferias = int4 = C�digo ferias 
                 rh110_dias = int4 = Dias a gozar 
                 rh110_datainicial = date = Data inicial 
                 rh110_datafinal = date = Data final 
                 rh110_observacao = text = Observa��es 
                 rh110_anopagamento = int4 = Ano de pagamento 
                 rh110_mespagamento = int4 = M�s de pagamento 
                 rh110_diasabono = int4 = Dias de abono 
                 rh110_pagaterco = bool = Paga Ter�o de F�rias 
                 rh110_tipoponto = char(1) = Tipo Ponto 
                 rh110_periodoespecificoinicial = date = Per�odo Espec�fico Inicial 
                 rh110_periodoespecificofinal = date = Per�odo Espec�fico Final 
                 rh110_situacao = int4 = Situa��o 
                 ";
   //funcao construtor da classe 
   function cl_rhferiasperiodo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhferiasperiodo"); 
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
       $this->rh110_sequencial = ($this->rh110_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_sequencial"]:$this->rh110_sequencial);
       $this->rh110_rhferias = ($this->rh110_rhferias == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_rhferias"]:$this->rh110_rhferias);
       $this->rh110_dias = ($this->rh110_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_dias"]:$this->rh110_dias);
       if($this->rh110_datainicial == ""){
         $this->rh110_datainicial_dia = ($this->rh110_datainicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_datainicial_dia"]:$this->rh110_datainicial_dia);
         $this->rh110_datainicial_mes = ($this->rh110_datainicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_datainicial_mes"]:$this->rh110_datainicial_mes);
         $this->rh110_datainicial_ano = ($this->rh110_datainicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_datainicial_ano"]:$this->rh110_datainicial_ano);
         if($this->rh110_datainicial_dia != ""){
            $this->rh110_datainicial = $this->rh110_datainicial_ano."-".$this->rh110_datainicial_mes."-".$this->rh110_datainicial_dia;
         }
       }
       if($this->rh110_datafinal == ""){
         $this->rh110_datafinal_dia = ($this->rh110_datafinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_datafinal_dia"]:$this->rh110_datafinal_dia);
         $this->rh110_datafinal_mes = ($this->rh110_datafinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_datafinal_mes"]:$this->rh110_datafinal_mes);
         $this->rh110_datafinal_ano = ($this->rh110_datafinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_datafinal_ano"]:$this->rh110_datafinal_ano);
         if($this->rh110_datafinal_dia != ""){
            $this->rh110_datafinal = $this->rh110_datafinal_ano."-".$this->rh110_datafinal_mes."-".$this->rh110_datafinal_dia;
         }
       }
       $this->rh110_observacao = ($this->rh110_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_observacao"]:$this->rh110_observacao);
       $this->rh110_anopagamento = ($this->rh110_anopagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_anopagamento"]:$this->rh110_anopagamento);
       $this->rh110_mespagamento = ($this->rh110_mespagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_mespagamento"]:$this->rh110_mespagamento);
       $this->rh110_diasabono = ($this->rh110_diasabono == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_diasabono"]:$this->rh110_diasabono);
       $this->rh110_pagaterco = ($this->rh110_pagaterco == "f"?@$GLOBALS["HTTP_POST_VARS"]["rh110_pagaterco"]:$this->rh110_pagaterco);
       $this->rh110_tipoponto = ($this->rh110_tipoponto == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_tipoponto"]:$this->rh110_tipoponto);
       if($this->rh110_periodoespecificoinicial == ""){
         $this->rh110_periodoespecificoinicial_dia = ($this->rh110_periodoespecificoinicial_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificoinicial_dia"]:$this->rh110_periodoespecificoinicial_dia);
         $this->rh110_periodoespecificoinicial_mes = ($this->rh110_periodoespecificoinicial_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificoinicial_mes"]:$this->rh110_periodoespecificoinicial_mes);
         $this->rh110_periodoespecificoinicial_ano = ($this->rh110_periodoespecificoinicial_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificoinicial_ano"]:$this->rh110_periodoespecificoinicial_ano);
         if($this->rh110_periodoespecificoinicial_dia != ""){
            $this->rh110_periodoespecificoinicial = $this->rh110_periodoespecificoinicial_ano."-".$this->rh110_periodoespecificoinicial_mes."-".$this->rh110_periodoespecificoinicial_dia;
         }
       }
       if($this->rh110_periodoespecificofinal == ""){
         $this->rh110_periodoespecificofinal_dia = ($this->rh110_periodoespecificofinal_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificofinal_dia"]:$this->rh110_periodoespecificofinal_dia);
         $this->rh110_periodoespecificofinal_mes = ($this->rh110_periodoespecificofinal_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificofinal_mes"]:$this->rh110_periodoespecificofinal_mes);
         $this->rh110_periodoespecificofinal_ano = ($this->rh110_periodoespecificofinal_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificofinal_ano"]:$this->rh110_periodoespecificofinal_ano);
         if($this->rh110_periodoespecificofinal_dia != ""){
            $this->rh110_periodoespecificofinal = $this->rh110_periodoespecificofinal_ano."-".$this->rh110_periodoespecificofinal_mes."-".$this->rh110_periodoespecificofinal_dia;
         }
       }
       $this->rh110_situacao = ($this->rh110_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_situacao"]:$this->rh110_situacao);
     }else{
       $this->rh110_sequencial = ($this->rh110_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh110_sequencial"]:$this->rh110_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh110_sequencial){ 
      $this->atualizacampos();
     if($this->rh110_rhferias == null ){ 
       $this->erro_sql = " Campo C�digo ferias nao Informado.";
       $this->erro_campo = "rh110_rhferias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh110_dias == null ){ 
       $this->erro_sql = " Campo Dias a gozar nao Informado.";
       $this->erro_campo = "rh110_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh110_datainicial == null ){ 
       $this->rh110_datainicial = "null";
     }
     if($this->rh110_datafinal == null ){ 
       $this->rh110_datafinal = "null";
     }
     if($this->rh110_anopagamento == null ){ 
       $this->erro_sql = " Campo Ano de pagamento nao Informado.";
       $this->erro_campo = "rh110_anopagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh110_mespagamento == null ){ 
       $this->erro_sql = " Campo M�s de pagamento nao Informado.";
       $this->erro_campo = "rh110_mespagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh110_diasabono == null ){ 
       $this->rh110_diasabono = "0";
     }
     if($this->rh110_pagaterco == null ){ 
       $this->erro_sql = " Campo Paga Ter�o de F�rias nao Informado.";
       $this->erro_campo = "rh110_pagaterco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh110_tipoponto == null ){ 
       $this->erro_sql = " Campo Tipo Ponto nao Informado.";
       $this->erro_campo = "rh110_tipoponto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh110_periodoespecificoinicial == null ){ 
       $this->rh110_periodoespecificoinicial = "null";
     }
     if($this->rh110_periodoespecificofinal == null ){ 
       $this->rh110_periodoespecificofinal = "null";
     }
     if($this->rh110_situacao == null ){ 
       $this->erro_sql = " Campo Situa��o nao Informado.";
       $this->erro_campo = "rh110_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh110_sequencial == "" || $rh110_sequencial == null ){
       $result = db_query("select nextval('rhferiasperiodo_rh110_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhferiasperiodo_rh110_sequencial_seq do campo: rh110_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh110_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhferiasperiodo_rh110_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh110_sequencial)){
         $this->erro_sql = " Campo rh110_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh110_sequencial = $rh110_sequencial; 
       }
     }
     if(($this->rh110_sequencial == null) || ($this->rh110_sequencial == "") ){ 
       $this->erro_sql = " Campo rh110_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhferiasperiodo(
                                       rh110_sequencial 
                                      ,rh110_rhferias 
                                      ,rh110_dias 
                                      ,rh110_datainicial 
                                      ,rh110_datafinal 
                                      ,rh110_observacao 
                                      ,rh110_anopagamento 
                                      ,rh110_mespagamento 
                                      ,rh110_diasabono 
                                      ,rh110_pagaterco 
                                      ,rh110_tipoponto 
                                      ,rh110_periodoespecificoinicial 
                                      ,rh110_periodoespecificofinal 
                                      ,rh110_situacao 
                       )
                values (
                                $this->rh110_sequencial 
                               ,$this->rh110_rhferias 
                               ,$this->rh110_dias 
                               ,".($this->rh110_datainicial == "null" || $this->rh110_datainicial == ""?"null":"'".$this->rh110_datainicial."'")." 
                               ,".($this->rh110_datafinal == "null" || $this->rh110_datafinal == ""?"null":"'".$this->rh110_datafinal."'")." 
                               ,'$this->rh110_observacao' 
                               ,$this->rh110_anopagamento 
                               ,$this->rh110_mespagamento 
                               ,$this->rh110_diasabono 
                               ,'$this->rh110_pagaterco' 
                               ,'$this->rh110_tipoponto' 
                               ,".($this->rh110_periodoespecificoinicial == "null" || $this->rh110_periodoespecificoinicial == ""?"null":"'".$this->rh110_periodoespecificoinicial."'")." 
                               ,".($this->rh110_periodoespecificofinal == "null" || $this->rh110_periodoespecificofinal == ""?"null":"'".$this->rh110_periodoespecificofinal."'")." 
                               ,$this->rh110_situacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Per�odo de ferias ($this->rh110_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Per�odo de ferias j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Per�odo de ferias ($this->rh110_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh110_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh110_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18967,'$this->rh110_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3374,18967,'','".AddSlashes(pg_result($resaco,0,'rh110_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,18968,'','".AddSlashes(pg_result($resaco,0,'rh110_rhferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,18969,'','".AddSlashes(pg_result($resaco,0,'rh110_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,18970,'','".AddSlashes(pg_result($resaco,0,'rh110_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,18971,'','".AddSlashes(pg_result($resaco,0,'rh110_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,18972,'','".AddSlashes(pg_result($resaco,0,'rh110_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,18973,'','".AddSlashes(pg_result($resaco,0,'rh110_anopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,18974,'','".AddSlashes(pg_result($resaco,0,'rh110_mespagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,18975,'','".AddSlashes(pg_result($resaco,0,'rh110_diasabono'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,19064,'','".AddSlashes(pg_result($resaco,0,'rh110_pagaterco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,19065,'','".AddSlashes(pg_result($resaco,0,'rh110_tipoponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,19062,'','".AddSlashes(pg_result($resaco,0,'rh110_periodoespecificoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,19063,'','".AddSlashes(pg_result($resaco,0,'rh110_periodoespecificofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3374,20162,'','".AddSlashes(pg_result($resaco,0,'rh110_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh110_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhferiasperiodo set ";
     $virgula = "";
     if(trim($this->rh110_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_sequencial"])){ 
       $sql  .= $virgula." rh110_sequencial = $this->rh110_sequencial ";
       $virgula = ",";
       if(trim($this->rh110_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh110_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh110_rhferias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_rhferias"])){ 
       $sql  .= $virgula." rh110_rhferias = $this->rh110_rhferias ";
       $virgula = ",";
       if(trim($this->rh110_rhferias) == null ){ 
         $this->erro_sql = " Campo C�digo ferias nao Informado.";
         $this->erro_campo = "rh110_rhferias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh110_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_dias"])){ 
       $sql  .= $virgula." rh110_dias = $this->rh110_dias ";
       $virgula = ",";
       if(trim($this->rh110_dias) == null ){ 
         $this->erro_sql = " Campo Dias a gozar nao Informado.";
         $this->erro_campo = "rh110_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh110_datainicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_datainicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh110_datainicial_dia"] !="") ){ 
       $sql  .= $virgula." rh110_datainicial = '$this->rh110_datainicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_datainicial_dia"])){ 
         $sql  .= $virgula." rh110_datainicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh110_datafinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_datafinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh110_datafinal_dia"] !="") ){ 
       $sql  .= $virgula." rh110_datafinal = '$this->rh110_datafinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_datafinal_dia"])){ 
         $sql  .= $virgula." rh110_datafinal = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh110_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_observacao"])){ 
       $sql  .= $virgula." rh110_observacao = '$this->rh110_observacao' ";
       $virgula = ",";
     }
     if(trim($this->rh110_anopagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_anopagamento"])){ 
       $sql  .= $virgula." rh110_anopagamento = $this->rh110_anopagamento ";
       $virgula = ",";
       if(trim($this->rh110_anopagamento) == null ){ 
         $this->erro_sql = " Campo Ano de pagamento nao Informado.";
         $this->erro_campo = "rh110_anopagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh110_mespagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_mespagamento"])){ 
       $sql  .= $virgula." rh110_mespagamento = $this->rh110_mespagamento ";
       $virgula = ",";
       if(trim($this->rh110_mespagamento) == null ){ 
         $this->erro_sql = " Campo M�s de pagamento nao Informado.";
         $this->erro_campo = "rh110_mespagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh110_diasabono)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_diasabono"])){ 
        if(trim($this->rh110_diasabono)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh110_diasabono"])){ 
           $this->rh110_diasabono = "0" ; 
        } 
       $sql  .= $virgula." rh110_diasabono = $this->rh110_diasabono ";
       $virgula = ",";
     }
     if(trim($this->rh110_pagaterco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_pagaterco"])){ 
       $sql  .= $virgula." rh110_pagaterco = '$this->rh110_pagaterco' ";
       $virgula = ",";
       if(trim($this->rh110_pagaterco) == null ){ 
         $this->erro_sql = " Campo Paga Ter�o de F�rias nao Informado.";
         $this->erro_campo = "rh110_pagaterco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh110_tipoponto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_tipoponto"])){ 
       $sql  .= $virgula." rh110_tipoponto = '$this->rh110_tipoponto' ";
       $virgula = ",";
       if(trim($this->rh110_tipoponto) == null ){ 
         $this->erro_sql = " Campo Tipo Ponto nao Informado.";
         $this->erro_campo = "rh110_tipoponto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh110_periodoespecificoinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificoinicial_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificoinicial_dia"] !="") ){ 
       $sql  .= $virgula." rh110_periodoespecificoinicial = '$this->rh110_periodoespecificoinicial' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificoinicial_dia"])){ 
         $sql  .= $virgula." rh110_periodoespecificoinicial = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh110_periodoespecificofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificofinal_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificofinal_dia"] !="") ){ 
       $sql  .= $virgula." rh110_periodoespecificofinal = '$this->rh110_periodoespecificofinal' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificofinal_dia"])){ 
         $sql  .= $virgula." rh110_periodoespecificofinal = null ";
         $virgula = ",";
       }
     }
     if(trim($this->rh110_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh110_situacao"])){ 
       $sql  .= $virgula." rh110_situacao = $this->rh110_situacao ";
       $virgula = ",";
       if(trim($this->rh110_situacao) == null ){ 
         $this->erro_sql = " Campo Situa��o nao Informado.";
         $this->erro_campo = "rh110_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh110_sequencial!=null){
       $sql .= " rh110_sequencial = $this->rh110_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->rh110_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,18967,'$this->rh110_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_sequencial"]) || $this->rh110_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3374,18967,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_sequencial'))."','$this->rh110_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_rhferias"]) || $this->rh110_rhferias != "")
             $resac = db_query("insert into db_acount values($acount,3374,18968,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_rhferias'))."','$this->rh110_rhferias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_dias"]) || $this->rh110_dias != "")
             $resac = db_query("insert into db_acount values($acount,3374,18969,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_dias'))."','$this->rh110_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_datainicial"]) || $this->rh110_datainicial != "")
             $resac = db_query("insert into db_acount values($acount,3374,18970,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_datainicial'))."','$this->rh110_datainicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_datafinal"]) || $this->rh110_datafinal != "")
             $resac = db_query("insert into db_acount values($acount,3374,18971,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_datafinal'))."','$this->rh110_datafinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_observacao"]) || $this->rh110_observacao != "")
             $resac = db_query("insert into db_acount values($acount,3374,18972,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_observacao'))."','$this->rh110_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_anopagamento"]) || $this->rh110_anopagamento != "")
             $resac = db_query("insert into db_acount values($acount,3374,18973,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_anopagamento'))."','$this->rh110_anopagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_mespagamento"]) || $this->rh110_mespagamento != "")
             $resac = db_query("insert into db_acount values($acount,3374,18974,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_mespagamento'))."','$this->rh110_mespagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_diasabono"]) || $this->rh110_diasabono != "")
             $resac = db_query("insert into db_acount values($acount,3374,18975,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_diasabono'))."','$this->rh110_diasabono',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_pagaterco"]) || $this->rh110_pagaterco != "")
             $resac = db_query("insert into db_acount values($acount,3374,19064,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_pagaterco'))."','$this->rh110_pagaterco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_tipoponto"]) || $this->rh110_tipoponto != "")
             $resac = db_query("insert into db_acount values($acount,3374,19065,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_tipoponto'))."','$this->rh110_tipoponto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificoinicial"]) || $this->rh110_periodoespecificoinicial != "")
             $resac = db_query("insert into db_acount values($acount,3374,19062,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_periodoespecificoinicial'))."','$this->rh110_periodoespecificoinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_periodoespecificofinal"]) || $this->rh110_periodoespecificofinal != "")
             $resac = db_query("insert into db_acount values($acount,3374,19063,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_periodoespecificofinal'))."','$this->rh110_periodoespecificofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["rh110_situacao"]) || $this->rh110_situacao != "")
             $resac = db_query("insert into db_acount values($acount,3374,20162,'".AddSlashes(pg_result($resaco,$conresaco,'rh110_situacao'))."','$this->rh110_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Per�odo de ferias nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh110_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Per�odo de ferias nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh110_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh110_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh110_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($rh110_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,18967,'$rh110_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3374,18967,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,18968,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_rhferias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,18969,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,18970,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_datainicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,18971,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_datafinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,18972,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,18973,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_anopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,18974,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_mespagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,18975,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_diasabono'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,19064,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_pagaterco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,19065,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_tipoponto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,19062,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_periodoespecificoinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,19063,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_periodoespecificofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3374,20162,'','".AddSlashes(pg_result($resaco,$iresaco,'rh110_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rhferiasperiodo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh110_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh110_sequencial = $rh110_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Per�odo de ferias nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh110_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Per�odo de ferias nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh110_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh110_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rhferiasperiodo";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh110_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhferiasperiodo ";
     $sql .= "      inner join rhferias  on  rhferias.rh109_sequencial = rhferiasperiodo.rh110_rhferias";
     $sql .= "      inner join rhpessoal  on  rhpessoal.rh01_regist = rhferias.rh109_regist";
     $sql2 = "";
     if($dbwhere==""){
       if($rh110_sequencial!=null ){
         $sql2 .= " where rhferiasperiodo.rh110_sequencial = $rh110_sequencial "; 
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
   function sql_query_file ( $rh110_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhferiasperiodo ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh110_sequencial!=null ){
         $sql2 .= " where rhferiasperiodo.rh110_sequencial = $rh110_sequencial "; 
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
}
?>