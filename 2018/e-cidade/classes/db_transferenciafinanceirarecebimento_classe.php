<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE transferenciafinanceirarecebimento
class cl_transferenciafinanceirarecebimento { 
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
   var $k151_sequencial = 0; 
   var $k151_transferenciafinanceira = 0; 
   var $k151_slip = 0; 
   var $k151_db_usuario = 0; 
   var $k151_hora = null; 
   var $k151_data_dia = null; 
   var $k151_data_mes = null; 
   var $k151_data_ano = null; 
   var $k151_data = null; 
   var $k151_estornado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k151_sequencial = int4 = Sequencial 
                 k151_transferenciafinanceira = int4 = Codigo da Transferencia 
                 k151_slip = int4 = Código Slip 
                 k151_db_usuario = int4 = Usuário 
                 k151_hora = varchar(5) = Hora 
                 k151_data = date = Data do recebimento 
                 k151_estornado = bool = Estornado 
                 ";
   //funcao construtor da classe 
   function cl_transferenciafinanceirarecebimento() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("transferenciafinanceirarecebimento"); 
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
       $this->k151_sequencial = ($this->k151_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_sequencial"]:$this->k151_sequencial);
       $this->k151_transferenciafinanceira = ($this->k151_transferenciafinanceira == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_transferenciafinanceira"]:$this->k151_transferenciafinanceira);
       $this->k151_slip = ($this->k151_slip == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_slip"]:$this->k151_slip);
       $this->k151_db_usuario = ($this->k151_db_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_db_usuario"]:$this->k151_db_usuario);
       $this->k151_hora = ($this->k151_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_hora"]:$this->k151_hora);
       if($this->k151_data == ""){
         $this->k151_data_dia = ($this->k151_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_data_dia"]:$this->k151_data_dia);
         $this->k151_data_mes = ($this->k151_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_data_mes"]:$this->k151_data_mes);
         $this->k151_data_ano = ($this->k151_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_data_ano"]:$this->k151_data_ano);
         if($this->k151_data_dia != ""){
            $this->k151_data = $this->k151_data_ano."-".$this->k151_data_mes."-".$this->k151_data_dia;
         }
       }
       $this->k151_estornado = ($this->k151_estornado == "f"?@$GLOBALS["HTTP_POST_VARS"]["k151_estornado"]:$this->k151_estornado);
     }else{
       $this->k151_sequencial = ($this->k151_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k151_sequencial"]:$this->k151_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k151_sequencial){ 
      $this->atualizacampos();
     if($this->k151_transferenciafinanceira == null ){ 
       $this->erro_sql = " Campo Codigo da Transferencia nao Informado.";
       $this->erro_campo = "k151_transferenciafinanceira";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k151_slip == null ){ 
       $this->erro_sql = " Campo Código Slip nao Informado.";
       $this->erro_campo = "k151_slip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k151_db_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "k151_db_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k151_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "k151_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k151_data == null ){ 
       $this->erro_sql = " Campo Data do recebimento nao Informado.";
       $this->erro_campo = "k151_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k151_estornado == null ){ 
       $this->erro_sql = " Campo Estornado nao Informado.";
       $this->erro_campo = "k151_estornado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k151_sequencial == "" || $k151_sequencial == null ){
       $result = db_query("select nextval('transferenciafinanceirarecebimento_k151_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: transferenciafinanceirarecebimento_k151_sequencial_seq do campo: k151_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k151_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from transferenciafinanceirarecebimento_k151_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k151_sequencial)){
         $this->erro_sql = " Campo k151_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k151_sequencial = $k151_sequencial; 
       }
     }
     if(($this->k151_sequencial == null) || ($this->k151_sequencial == "") ){ 
       $this->erro_sql = " Campo k151_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into transferenciafinanceirarecebimento(
                                       k151_sequencial 
                                      ,k151_transferenciafinanceira 
                                      ,k151_slip 
                                      ,k151_db_usuario 
                                      ,k151_hora 
                                      ,k151_data 
                                      ,k151_estornado 
                       )
                values (
                                $this->k151_sequencial 
                               ,$this->k151_transferenciafinanceira 
                               ,$this->k151_slip 
                               ,$this->k151_db_usuario 
                               ,'$this->k151_hora' 
                               ,".($this->k151_data == "null" || $this->k151_data == ""?"null":"'".$this->k151_data."'")." 
                               ,'$this->k151_estornado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "transferenciafinanceirarecebimento ($this->k151_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "transferenciafinanceirarecebimento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "transferenciafinanceirarecebimento ($this->k151_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k151_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k151_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19229,'$this->k151_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3415,19229,'','".AddSlashes(pg_result($resaco,0,'k151_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3415,19241,'','".AddSlashes(pg_result($resaco,0,'k151_transferenciafinanceira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3415,19230,'','".AddSlashes(pg_result($resaco,0,'k151_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3415,19231,'','".AddSlashes(pg_result($resaco,0,'k151_db_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3415,19232,'','".AddSlashes(pg_result($resaco,0,'k151_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3415,19233,'','".AddSlashes(pg_result($resaco,0,'k151_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3415,19234,'','".AddSlashes(pg_result($resaco,0,'k151_estornado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k151_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update transferenciafinanceirarecebimento set ";
     $virgula = "";
     if(trim($this->k151_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k151_sequencial"])){ 
       $sql  .= $virgula." k151_sequencial = $this->k151_sequencial ";
       $virgula = ",";
       if(trim($this->k151_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "k151_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k151_transferenciafinanceira)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k151_transferenciafinanceira"])){ 
       $sql  .= $virgula." k151_transferenciafinanceira = $this->k151_transferenciafinanceira ";
       $virgula = ",";
       if(trim($this->k151_transferenciafinanceira) == null ){ 
         $this->erro_sql = " Campo Codigo da Transferencia nao Informado.";
         $this->erro_campo = "k151_transferenciafinanceira";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k151_slip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k151_slip"])){ 
       $sql  .= $virgula." k151_slip = $this->k151_slip ";
       $virgula = ",";
       if(trim($this->k151_slip) == null ){ 
         $this->erro_sql = " Campo Código Slip nao Informado.";
         $this->erro_campo = "k151_slip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k151_db_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k151_db_usuario"])){ 
       $sql  .= $virgula." k151_db_usuario = $this->k151_db_usuario ";
       $virgula = ",";
       if(trim($this->k151_db_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "k151_db_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k151_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k151_hora"])){ 
       $sql  .= $virgula." k151_hora = '$this->k151_hora' ";
       $virgula = ",";
       if(trim($this->k151_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "k151_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k151_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k151_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k151_data_dia"] !="") ){ 
       $sql  .= $virgula." k151_data = '$this->k151_data' ";
       $virgula = ",";
       if(trim($this->k151_data) == null ){ 
         $this->erro_sql = " Campo Data do recebimento nao Informado.";
         $this->erro_campo = "k151_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k151_data_dia"])){ 
         $sql  .= $virgula." k151_data = null ";
         $virgula = ",";
         if(trim($this->k151_data) == null ){ 
           $this->erro_sql = " Campo Data do recebimento nao Informado.";
           $this->erro_campo = "k151_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k151_estornado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k151_estornado"])){ 
       $sql  .= $virgula." k151_estornado = '$this->k151_estornado' ";
       $virgula = ",";
       if(trim($this->k151_estornado) == null ){ 
         $this->erro_sql = " Campo Estornado nao Informado.";
         $this->erro_campo = "k151_estornado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k151_sequencial!=null){
       $sql .= " k151_sequencial = $this->k151_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k151_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19229,'$this->k151_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k151_sequencial"]) || $this->k151_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3415,19229,'".AddSlashes(pg_result($resaco,$conresaco,'k151_sequencial'))."','$this->k151_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k151_transferenciafinanceira"]) || $this->k151_transferenciafinanceira != "")
           $resac = db_query("insert into db_acount values($acount,3415,19241,'".AddSlashes(pg_result($resaco,$conresaco,'k151_transferenciafinanceira'))."','$this->k151_transferenciafinanceira',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k151_slip"]) || $this->k151_slip != "")
           $resac = db_query("insert into db_acount values($acount,3415,19230,'".AddSlashes(pg_result($resaco,$conresaco,'k151_slip'))."','$this->k151_slip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k151_db_usuario"]) || $this->k151_db_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3415,19231,'".AddSlashes(pg_result($resaco,$conresaco,'k151_db_usuario'))."','$this->k151_db_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k151_hora"]) || $this->k151_hora != "")
           $resac = db_query("insert into db_acount values($acount,3415,19232,'".AddSlashes(pg_result($resaco,$conresaco,'k151_hora'))."','$this->k151_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k151_data"]) || $this->k151_data != "")
           $resac = db_query("insert into db_acount values($acount,3415,19233,'".AddSlashes(pg_result($resaco,$conresaco,'k151_data'))."','$this->k151_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k151_estornado"]) || $this->k151_estornado != "")
           $resac = db_query("insert into db_acount values($acount,3415,19234,'".AddSlashes(pg_result($resaco,$conresaco,'k151_estornado'))."','$this->k151_estornado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "transferenciafinanceirarecebimento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k151_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "transferenciafinanceirarecebimento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k151_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k151_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19229,'$k151_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3415,19229,'','".AddSlashes(pg_result($resaco,$iresaco,'k151_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3415,19241,'','".AddSlashes(pg_result($resaco,$iresaco,'k151_transferenciafinanceira'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3415,19230,'','".AddSlashes(pg_result($resaco,$iresaco,'k151_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3415,19231,'','".AddSlashes(pg_result($resaco,$iresaco,'k151_db_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3415,19232,'','".AddSlashes(pg_result($resaco,$iresaco,'k151_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3415,19233,'','".AddSlashes(pg_result($resaco,$iresaco,'k151_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3415,19234,'','".AddSlashes(pg_result($resaco,$iresaco,'k151_estornado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from transferenciafinanceirarecebimento
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k151_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k151_sequencial = $k151_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "transferenciafinanceirarecebimento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k151_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "transferenciafinanceirarecebimento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k151_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k151_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:transferenciafinanceirarecebimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k151_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from transferenciafinanceirarecebimento ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = transferenciafinanceirarecebimento.k151_db_usuario";
     $sql .= "      inner join slip  on  slip.k17_codigo = transferenciafinanceirarecebimento.k151_slip";
     $sql .= "      inner join transferenciafinanceira  on  transferenciafinanceira.k150_sequencial = transferenciafinanceirarecebimento.k151_transferenciafinanceira";
     $sql .= "      inner join db_config  on  db_config.codigo = slip.k17_instit";
     $sql .= "      inner join db_config  as a on   a.codigo = transferenciafinanceira.k150_instituicao";
     $sql .= "      inner join slip  as b on   b.k17_codigo = transferenciafinanceira.k150_slip";
     $sql2 = "";
     if($dbwhere==""){
       if($k151_sequencial!=null ){
         $sql2 .= " where transferenciafinanceirarecebimento.k151_sequencial = $k151_sequencial "; 
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
   function sql_query_file ( $k151_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from transferenciafinanceirarecebimento ";
     $sql2 = "";
     if($dbwhere==""){
       if($k151_sequencial!=null ){
         $sql2 .= " where transferenciafinanceirarecebimento.k151_sequencial = $k151_sequencial "; 
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