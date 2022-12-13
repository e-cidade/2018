<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: Caixa
//CLASSE DA ENTIDADE concilia
class cl_concilia { 
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
   var $k68_sequencial = 0; 
   var $k68_data_dia = null; 
   var $k68_data_mes = null; 
   var $k68_data_ano = null; 
   var $k68_data = null; 
   var $k68_contabancaria = 0; 
   var $k68_saldoextrato = 0; 
   var $k68_saldocorrente = 0; 
   var $k68_conciliastatus = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k68_sequencial = int4 = Codigo sequencial 
                 k68_data = date = Data da conciliação 
                 k68_contabancaria = int4 = Codigo sequencial da conta bancaria 
                 k68_saldoextrato = float8 = Saldo do extrato 
                 k68_saldocorrente = float8 = Saldo do caixa 
                 k68_conciliastatus = int4 = Codigo 
                 ";
   //funcao construtor da classe 
   function cl_concilia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("concilia"); 
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
       $this->k68_sequencial = ($this->k68_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_sequencial"]:$this->k68_sequencial);
       if($this->k68_data == ""){
         $this->k68_data_dia = ($this->k68_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_data_dia"]:$this->k68_data_dia);
         $this->k68_data_mes = ($this->k68_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_data_mes"]:$this->k68_data_mes);
         $this->k68_data_ano = ($this->k68_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_data_ano"]:$this->k68_data_ano);
         if($this->k68_data_dia != ""){
            $this->k68_data = $this->k68_data_ano."-".$this->k68_data_mes."-".$this->k68_data_dia;
         }
       }
       $this->k68_contabancaria = ($this->k68_contabancaria == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_contabancaria"]:$this->k68_contabancaria);
       $this->k68_saldoextrato = ($this->k68_saldoextrato == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_saldoextrato"]:$this->k68_saldoextrato);
       $this->k68_saldocorrente = ($this->k68_saldocorrente == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_saldocorrente"]:$this->k68_saldocorrente);
       $this->k68_conciliastatus = ($this->k68_conciliastatus == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_conciliastatus"]:$this->k68_conciliastatus);
     }else{
       $this->k68_sequencial = ($this->k68_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k68_sequencial"]:$this->k68_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k68_sequencial){ 
      $this->atualizacampos();
     if($this->k68_data == null ){ 
       $this->erro_sql = " Campo Data da conciliação nao Informado.";
       $this->erro_campo = "k68_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k68_contabancaria == null ){ 
       $this->erro_sql = " Campo Codigo sequencial da conta bancaria nao Informado.";
       $this->erro_campo = "k68_contabancaria";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k68_saldoextrato == null ){ 
       $this->erro_sql = " Campo Saldo do extrato nao Informado.";
       $this->erro_campo = "k68_saldoextrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k68_saldocorrente == null ){ 
       $this->erro_sql = " Campo Saldo do caixa nao Informado.";
       $this->erro_campo = "k68_saldocorrente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k68_conciliastatus == null ){ 
       $this->erro_sql = " Campo Codigo nao Informado.";
       $this->erro_campo = "k68_conciliastatus";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k68_sequencial == "" || $k68_sequencial == null ){
       $result = db_query("select nextval('concilia_k68_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: concilia_k68_sequencial_seq do campo: k68_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k68_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from concilia_k68_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k68_sequencial)){
         $this->erro_sql = " Campo k68_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k68_sequencial = $k68_sequencial; 
       }
     }
     if(($this->k68_sequencial == null) || ($this->k68_sequencial == "") ){ 
       $this->erro_sql = " Campo k68_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into concilia(
                                       k68_sequencial 
                                      ,k68_data 
                                      ,k68_contabancaria 
                                      ,k68_saldoextrato 
                                      ,k68_saldocorrente 
                                      ,k68_conciliastatus 
                       )
                values (
                                $this->k68_sequencial 
                               ,".($this->k68_data == "null" || $this->k68_data == ""?"null":"'".$this->k68_data."'")." 
                               ,$this->k68_contabancaria 
                               ,$this->k68_saldoextrato 
                               ,$this->k68_saldocorrente 
                               ,$this->k68_conciliastatus 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela das conciliações ($this->k68_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela das conciliações já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela das conciliações ($this->k68_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k68_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k68_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10066,'$this->k68_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1730,10066,'','".AddSlashes(pg_result($resaco,0,'k68_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1730,10067,'','".AddSlashes(pg_result($resaco,0,'k68_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1730,15631,'','".AddSlashes(pg_result($resaco,0,'k68_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1730,10068,'','".AddSlashes(pg_result($resaco,0,'k68_saldoextrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1730,10069,'','".AddSlashes(pg_result($resaco,0,'k68_saldocorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1730,10149,'','".AddSlashes(pg_result($resaco,0,'k68_conciliastatus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k68_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update concilia set ";
     $virgula = "";
     if(trim($this->k68_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k68_sequencial"])){ 
       $sql  .= $virgula." k68_sequencial = $this->k68_sequencial ";
       $virgula = ",";
       if(trim($this->k68_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial nao Informado.";
         $this->erro_campo = "k68_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k68_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k68_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k68_data_dia"] !="") ){ 
       $sql  .= $virgula." k68_data = '$this->k68_data' ";
       $virgula = ",";
       if(trim($this->k68_data) == null ){ 
         $this->erro_sql = " Campo Data da conciliação nao Informado.";
         $this->erro_campo = "k68_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k68_data_dia"])){ 
         $sql  .= $virgula." k68_data = null ";
         $virgula = ",";
         if(trim($this->k68_data) == null ){ 
           $this->erro_sql = " Campo Data da conciliação nao Informado.";
           $this->erro_campo = "k68_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k68_contabancaria)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k68_contabancaria"])){ 
       $sql  .= $virgula." k68_contabancaria = $this->k68_contabancaria ";
       $virgula = ",";
       if(trim($this->k68_contabancaria) == null ){ 
         $this->erro_sql = " Campo Codigo sequencial da conta bancaria nao Informado.";
         $this->erro_campo = "k68_contabancaria";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k68_saldoextrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k68_saldoextrato"])){ 
       $sql  .= $virgula." k68_saldoextrato = $this->k68_saldoextrato ";
       $virgula = ",";
       if(trim($this->k68_saldoextrato) == null ){ 
         $this->erro_sql = " Campo Saldo do extrato nao Informado.";
         $this->erro_campo = "k68_saldoextrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k68_saldocorrente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k68_saldocorrente"])){ 
       $sql  .= $virgula." k68_saldocorrente = $this->k68_saldocorrente ";
       $virgula = ",";
       if(trim($this->k68_saldocorrente) == null ){ 
         $this->erro_sql = " Campo Saldo do caixa nao Informado.";
         $this->erro_campo = "k68_saldocorrente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k68_conciliastatus)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k68_conciliastatus"])){ 
       $sql  .= $virgula." k68_conciliastatus = $this->k68_conciliastatus ";
       $virgula = ",";
       if(trim($this->k68_conciliastatus) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "k68_conciliastatus";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k68_sequencial!=null){
       $sql .= " k68_sequencial = $this->k68_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k68_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10066,'$this->k68_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k68_sequencial"]) || $this->k68_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,1730,10066,'".AddSlashes(pg_result($resaco,$conresaco,'k68_sequencial'))."','$this->k68_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k68_data"]) || $this->k68_data != "")
           $resac = db_query("insert into db_acount values($acount,1730,10067,'".AddSlashes(pg_result($resaco,$conresaco,'k68_data'))."','$this->k68_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k68_contabancaria"]) || $this->k68_contabancaria != "")
           $resac = db_query("insert into db_acount values($acount,1730,15631,'".AddSlashes(pg_result($resaco,$conresaco,'k68_contabancaria'))."','$this->k68_contabancaria',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k68_saldoextrato"]) || $this->k68_saldoextrato != "")
           $resac = db_query("insert into db_acount values($acount,1730,10068,'".AddSlashes(pg_result($resaco,$conresaco,'k68_saldoextrato'))."','$this->k68_saldoextrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k68_saldocorrente"]) || $this->k68_saldocorrente != "")
           $resac = db_query("insert into db_acount values($acount,1730,10069,'".AddSlashes(pg_result($resaco,$conresaco,'k68_saldocorrente'))."','$this->k68_saldocorrente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k68_conciliastatus"]) || $this->k68_conciliastatus != "")
           $resac = db_query("insert into db_acount values($acount,1730,10149,'".AddSlashes(pg_result($resaco,$conresaco,'k68_conciliastatus'))."','$this->k68_conciliastatus',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela das conciliações nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k68_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela das conciliações nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k68_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k68_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k68_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k68_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10066,'$k68_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1730,10066,'','".AddSlashes(pg_result($resaco,$iresaco,'k68_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1730,10067,'','".AddSlashes(pg_result($resaco,$iresaco,'k68_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1730,15631,'','".AddSlashes(pg_result($resaco,$iresaco,'k68_contabancaria'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1730,10068,'','".AddSlashes(pg_result($resaco,$iresaco,'k68_saldoextrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1730,10069,'','".AddSlashes(pg_result($resaco,$iresaco,'k68_saldocorrente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1730,10149,'','".AddSlashes(pg_result($resaco,$iresaco,'k68_conciliastatus'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from concilia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k68_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k68_sequencial = $k68_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela das conciliações nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k68_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela das conciliações nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k68_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k68_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:concilia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k68_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from concilia ";
     $sql .= "      inner join conciliastatus  on  conciliastatus.k95_sequencial = concilia.k68_conciliastatus";
     $sql .= "      inner join contabancaria  on  contabancaria.db83_sequencial = concilia.k68_contabancaria";
     $sql .= "      inner join bancoagencia  on  bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia";
     $sql .= "      inner join conplanocontabancaria  on c56_contabancaria            = db83_sequencial";
     $sql .= "      inner join conplano               on c60_codcon                   = c56_codcon ";
     $sql .= "                                       and c60_anousu                   = c56_anousu ";
     $sql .= "      inner join conplanoreduz          on c61_codcon                   = c60_codcon ";
     $sql2 = "";
     if($dbwhere==""){
       if($k68_sequencial!=null ){
         $sql2 .= " where concilia.k68_sequencial = $k68_sequencial "; 
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
   function sql_query_file ( $k68_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from concilia ";
     $sql2 = "";
     if($dbwhere==""){
       if($k68_sequencial!=null ){
         $sql2 .= " where concilia.k68_sequencial = $k68_sequencial "; 
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
   * Retorna uma string sql com a ultima data que possui conciliação feita no mes. O retorno possui um campo
   * para dia, mes, ano; 
   *
   * @param inteiro $conta código da conta bancaria;
   * @return string SQl
   */
  function retornaUltimaDataComConciliacao($conta = null, $iSequencialConcilia = null) {
    
    $sqlData  = " select extract(year  from k68_data) as ano,";
    $sqlData .= "        lpad(extract(month from k68_data), 2, '0') as mes,";
    $sqlData .= "        lpad(max(extract(day  from k68_data)), 2, '0') as dia";  
    $sqlData .= "   from ( ";
    $sqlData .= "          select distinct k68_data ";
    $sqlData .= "            from concilia ";
    $sqlData .= "                 inner join conciliastatus on k95_sequencial = k68_conciliastatus ";
    $sqlData .= "           where k68_contabancaria = {$conta} ";
    $sqlData .= "             and k95_fechada is true";
    if (!empty($iSequencialConcilia)) {
      $sqlData .= "           and k68_sequencial = $iSequencialConcilia ";
    }
    
    $sqlData .= "        ) as x ";
    $sqlData .= "        group by 1, 2";
    $sqlData .= "       order by  1 desc, 2 desc, 3 desc";
    return $sqlData;
  }
  
  /**
   * Retorna uma(uma colecao) string sql com a data que possui conciliação feita no mes. O retorno possui um campo
   * para dia, mes, ano;
   *
   * @param inteiro $conta código da conta bancaria;
   * @return string SQl
   */
  function retornaTodasDatasComConciliacao($conta = null, $iSequencialConcilia = null) {
  
    $sqlData  = " select extract(year  from k68_data) as ano,";
    $sqlData .= "        lpad(extract(month from k68_data), 2, '0') as mes,";
    $sqlData .= "        lpad(extract(day  from k68_data), 2, '0') as dia";
    $sqlData .= "   from ( ";
    $sqlData .= "          select distinct k68_data ";
    $sqlData .= "            from concilia ";
    $sqlData .= "                 inner join conciliastatus on k95_sequencial = k68_conciliastatus ";
    $sqlData .= "           where k68_contabancaria = {$conta} ";
    $sqlData .= "             and k95_fechada is true";
    if (!empty($iSequencialConcilia)) {
      $sqlData .= "           and k68_sequencial = $iSequencialConcilia ";
    }
  
    $sqlData .= "        ) as x ";
    $sqlData .= "       order by 1 desc, 2 desc, 3 desc";
    return $sqlData;
  }
  
  /**
   * Retorna ano e mes qua possui conciliacao para uma conta ou conciliacao 
   * @param integer $conta
   * @param integer $iSequencialConcilia
   */
  function retornaMesAnoComComciliacao($conta = null, $iSequencialConcilia = null) {
  
    
    $sqlData  = " SELECT ano,                                                                     ";
    $sqlData .= "        array_to_string(array_accum(mes), ', ') as mes                           ";
    $sqlData .= "        FROM (SELECT DISTINCT                                                    ";
    $sqlData .= "                     to_char(extract(year  from k68_data), '0000')     as ano,   ";
    $sqlData .= "                     trim(to_char(extract(month from k68_data), '00')) as mes    ";
    $sqlData .= "                     FROM concilia                                               ";
    $sqlData .= "               INNER JOIN conciliastatus ON k95_sequencial = k68_conciliastatus  ";
    $sqlData .= "               WHERE k95_fechada IS TRUE                                         ";
    if (!empty($conta)) {
      $sqlData .= "               AND k68_contabancaria = {$conta}                                ";
    }
    if (!empty($iSequencialConcilia)) {
      $sqlData .= "               AND k68_sequencial = {$iSequencialConcilia}                     ";
    }
    $sqlData .= "              ORDER BY 1, 2 DESC                                                 ";
    $sqlData .= "             ) AS x                                                              ";
    $sqlData .= "  GROUP BY ano                                                                   ";
    $sqlData .= "  ORDER BY 1 desc, 2 desc;                                                       ";
       
    return $sqlData;
  }
}
?>