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

//MODULO: Caixa
//CLASSE DA ENTIDADE slipcorrente
class cl_slipcorrente { 
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
   var $k112_sequencial = 0; 
   var $k112_id = 0; 
   var $k112_data_dia = null; 
   var $k112_data_mes = null; 
   var $k112_data_ano = null; 
   var $k112_data = null; 
   var $k112_autent = 0; 
   var $k112_slip = 0; 
   var $k112_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k112_sequencial = int4 = Código Sequencial 
                 k112_id = int4 = Caixa 
                 k112_data = date = Data Autenticacao 
                 k112_autent = int4 = Seq. Autenticação 
                 k112_slip = int4 = Slip 
                 k112_ativo = bool = Vinculação Ativa 
                 ";
   //funcao construtor da classe 
   function cl_slipcorrente() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("slipcorrente"); 
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
       $this->k112_sequencial = ($this->k112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k112_sequencial"]:$this->k112_sequencial);
       $this->k112_id = ($this->k112_id == ""?@$GLOBALS["HTTP_POST_VARS"]["k112_id"]:$this->k112_id);
       if($this->k112_data == ""){
         $this->k112_data_dia = ($this->k112_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["k112_data_dia"]:$this->k112_data_dia);
         $this->k112_data_mes = ($this->k112_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["k112_data_mes"]:$this->k112_data_mes);
         $this->k112_data_ano = ($this->k112_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["k112_data_ano"]:$this->k112_data_ano);
         if($this->k112_data_dia != ""){
            $this->k112_data = $this->k112_data_ano."-".$this->k112_data_mes."-".$this->k112_data_dia;
         }
       }
       $this->k112_autent = ($this->k112_autent == ""?@$GLOBALS["HTTP_POST_VARS"]["k112_autent"]:$this->k112_autent);
       $this->k112_slip = ($this->k112_slip == ""?@$GLOBALS["HTTP_POST_VARS"]["k112_slip"]:$this->k112_slip);
       $this->k112_ativo = ($this->k112_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["k112_ativo"]:$this->k112_ativo);
     }else{
       $this->k112_sequencial = ($this->k112_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k112_sequencial"]:$this->k112_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k112_sequencial){ 
      $this->atualizacampos();
     if($this->k112_id == null ){ 
       $this->erro_sql = " Campo Caixa nao Informado.";
       $this->erro_campo = "k112_id";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k112_data == null ){ 
       $this->erro_sql = " Campo Data Autenticacao nao Informado.";
       $this->erro_campo = "k112_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k112_autent == null ){ 
       $this->erro_sql = " Campo Seq. Autenticação nao Informado.";
       $this->erro_campo = "k112_autent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k112_slip == null ){ 
       $this->erro_sql = " Campo Slip nao Informado.";
       $this->erro_campo = "k112_slip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k112_ativo == null ){ 
       $this->erro_sql = " Campo Vinculação Ativa nao Informado.";
       $this->erro_campo = "k112_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k112_sequencial == "" || $k112_sequencial == null ){
       $result = db_query("select nextval('slipcorrente_k112_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: slipcorrente_k112_sequencial_seq do campo: k112_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k112_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from slipcorrente_k112_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k112_sequencial)){
         $this->erro_sql = " Campo k112_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k112_sequencial = $k112_sequencial; 
       }
     }
     if(($this->k112_sequencial == null) || ($this->k112_sequencial == "") ){ 
       $this->erro_sql = " Campo k112_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into slipcorrente(
                                       k112_sequencial 
                                      ,k112_id 
                                      ,k112_data 
                                      ,k112_autent 
                                      ,k112_slip 
                                      ,k112_ativo 
                       )
                values (
                                $this->k112_sequencial 
                               ,$this->k112_id 
                               ,".($this->k112_data == "null" || $this->k112_data == ""?"null":"'".$this->k112_data."'")." 
                               ,$this->k112_autent 
                               ,$this->k112_slip 
                               ,'$this->k112_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "LIgacao do slip com as arrecadocoes realizadas ($this->k112_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "LIgacao do slip com as arrecadocoes realizadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "LIgacao do slip com as arrecadocoes realizadas ($this->k112_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k112_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k112_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14533,'$this->k112_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2560,14533,'','".AddSlashes(pg_result($resaco,0,'k112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2560,14534,'','".AddSlashes(pg_result($resaco,0,'k112_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2560,14535,'','".AddSlashes(pg_result($resaco,0,'k112_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2560,14537,'','".AddSlashes(pg_result($resaco,0,'k112_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2560,14538,'','".AddSlashes(pg_result($resaco,0,'k112_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2560,14539,'','".AddSlashes(pg_result($resaco,0,'k112_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k112_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update slipcorrente set ";
     $virgula = "";
     if(trim($this->k112_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k112_sequencial"])){ 
       $sql  .= $virgula." k112_sequencial = $this->k112_sequencial ";
       $virgula = ",";
       if(trim($this->k112_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "k112_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k112_id)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k112_id"])){ 
       $sql  .= $virgula." k112_id = $this->k112_id ";
       $virgula = ",";
       if(trim($this->k112_id) == null ){ 
         $this->erro_sql = " Campo Caixa nao Informado.";
         $this->erro_campo = "k112_id";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k112_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k112_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["k112_data_dia"] !="") ){ 
       $sql  .= $virgula." k112_data = '$this->k112_data' ";
       $virgula = ",";
       if(trim($this->k112_data) == null ){ 
         $this->erro_sql = " Campo Data Autenticacao nao Informado.";
         $this->erro_campo = "k112_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["k112_data_dia"])){ 
         $sql  .= $virgula." k112_data = null ";
         $virgula = ",";
         if(trim($this->k112_data) == null ){ 
           $this->erro_sql = " Campo Data Autenticacao nao Informado.";
           $this->erro_campo = "k112_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->k112_autent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k112_autent"])){ 
       $sql  .= $virgula." k112_autent = $this->k112_autent ";
       $virgula = ",";
       if(trim($this->k112_autent) == null ){ 
         $this->erro_sql = " Campo Seq. Autenticação nao Informado.";
         $this->erro_campo = "k112_autent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k112_slip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k112_slip"])){ 
       $sql  .= $virgula." k112_slip = $this->k112_slip ";
       $virgula = ",";
       if(trim($this->k112_slip) == null ){ 
         $this->erro_sql = " Campo Slip nao Informado.";
         $this->erro_campo = "k112_slip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k112_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k112_ativo"])){ 
       $sql  .= $virgula." k112_ativo = '$this->k112_ativo' ";
       $virgula = ",";
       if(trim($this->k112_ativo) == null ){ 
         $this->erro_sql = " Campo Vinculação Ativa nao Informado.";
         $this->erro_campo = "k112_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k112_sequencial!=null){
       $sql .= " k112_sequencial = $this->k112_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k112_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14533,'$this->k112_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k112_sequencial"]) || $this->k112_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2560,14533,'".AddSlashes(pg_result($resaco,$conresaco,'k112_sequencial'))."','$this->k112_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k112_id"]) || $this->k112_id != "")
           $resac = db_query("insert into db_acount values($acount,2560,14534,'".AddSlashes(pg_result($resaco,$conresaco,'k112_id'))."','$this->k112_id',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k112_data"]) || $this->k112_data != "")
           $resac = db_query("insert into db_acount values($acount,2560,14535,'".AddSlashes(pg_result($resaco,$conresaco,'k112_data'))."','$this->k112_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k112_autent"]) || $this->k112_autent != "")
           $resac = db_query("insert into db_acount values($acount,2560,14537,'".AddSlashes(pg_result($resaco,$conresaco,'k112_autent'))."','$this->k112_autent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k112_slip"]) || $this->k112_slip != "")
           $resac = db_query("insert into db_acount values($acount,2560,14538,'".AddSlashes(pg_result($resaco,$conresaco,'k112_slip'))."','$this->k112_slip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k112_ativo"]) || $this->k112_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2560,14539,'".AddSlashes(pg_result($resaco,$conresaco,'k112_ativo'))."','$this->k112_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "LIgacao do slip com as arrecadocoes realizadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k112_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "LIgacao do slip com as arrecadocoes realizadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k112_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k112_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14533,'$k112_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2560,14533,'','".AddSlashes(pg_result($resaco,$iresaco,'k112_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2560,14534,'','".AddSlashes(pg_result($resaco,$iresaco,'k112_id'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2560,14535,'','".AddSlashes(pg_result($resaco,$iresaco,'k112_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2560,14537,'','".AddSlashes(pg_result($resaco,$iresaco,'k112_autent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2560,14538,'','".AddSlashes(pg_result($resaco,$iresaco,'k112_slip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2560,14539,'','".AddSlashes(pg_result($resaco,$iresaco,'k112_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from slipcorrente
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k112_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k112_sequencial = $k112_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "LIgacao do slip com as arrecadocoes realizadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k112_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "LIgacao do slip com as arrecadocoes realizadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k112_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k112_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:slipcorrente";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k112_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from slipcorrente ";
     $sql .= "      inner join slip  on  slip.k17_codigo = slipcorrente.k112_slip";
     $sql .= "      inner join corrente  on  corrente.k12_id = slipcorrente.k112_id and  corrente.k12_data = slipcorrente.k112_data and  corrente.k12_autent = slipcorrente.k112_autent";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = slip.k17_instit";
     $sql .= "      inner join db_depart  as a on   a.coddepto = corrente.k12_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($k112_sequencial!=null ){
         $sql2 .= " where slipcorrente.k112_sequencial = $k112_sequencial "; 
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
   function sql_query_file ( $k112_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from slipcorrente ";
     $sql2 = "";
     if($dbwhere==""){
       if($k112_sequencial!=null ){
         $sql2 .= " where slipcorrente.k112_sequencial = $k112_sequencial "; 
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