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

//MODULO: empenho
//CLASSE DA ENTIDADE empagenotasordem
class cl_empagenotasordem { 
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
   var $e43_sequencial = 0; 
   var $e43_ordempagamento = 0; 
   var $e43_empagemov = 0; 
   var $e43_autorizado = 'f'; 
   var $e43_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e43_sequencial = int4 = Código  Sequencial 
                 e43_ordempagamento = int4 = Código ordem pagamento 
                 e43_empagemov = int4 = Código do movimento da agenda 
                 e43_autorizado = bool = Pagamento autorizado 
                 e43_valor = float8 = Valor Liberado 
                 ";
   //funcao construtor da classe 
   function cl_empagenotasordem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empagenotasordem"); 
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
       $this->e43_sequencial = ($this->e43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e43_sequencial"]:$this->e43_sequencial);
       $this->e43_ordempagamento = ($this->e43_ordempagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["e43_ordempagamento"]:$this->e43_ordempagamento);
       $this->e43_empagemov = ($this->e43_empagemov == ""?@$GLOBALS["HTTP_POST_VARS"]["e43_empagemov"]:$this->e43_empagemov);
       $this->e43_autorizado = ($this->e43_autorizado == "f"?@$GLOBALS["HTTP_POST_VARS"]["e43_autorizado"]:$this->e43_autorizado);
       $this->e43_valor = ($this->e43_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["e43_valor"]:$this->e43_valor);
     }else{
       $this->e43_sequencial = ($this->e43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e43_sequencial"]:$this->e43_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e43_sequencial){ 
      $this->atualizacampos();
     if($this->e43_ordempagamento == null ){ 
       $this->erro_sql = " Campo Código ordem pagamento nao Informado.";
       $this->erro_campo = "e43_ordempagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e43_empagemov == null ){ 
       $this->erro_sql = " Campo Código do movimento da agenda nao Informado.";
       $this->erro_campo = "e43_empagemov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e43_autorizado == null ){ 
       $this->erro_sql = " Campo Pagamento autorizado nao Informado.";
       $this->erro_campo = "e43_autorizado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e43_valor == null ){ 
       $this->e43_valor = "0";
     }
     if($e43_sequencial == "" || $e43_sequencial == null ){
       $result = db_query("select nextval('empagenotasordem_e43_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empagenotasordem_e43_sequencial_seq do campo: e43_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e43_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empagenotasordem_e43_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e43_sequencial)){
         $this->erro_sql = " Campo e43_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e43_sequencial = $e43_sequencial; 
       }
     }
     if(($this->e43_sequencial == null) || ($this->e43_sequencial == "") ){ 
       $this->erro_sql = " Campo e43_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empagenotasordem(
                                       e43_sequencial 
                                      ,e43_ordempagamento 
                                      ,e43_empagemov 
                                      ,e43_autorizado 
                                      ,e43_valor 
                       )
                values (
                                $this->e43_sequencial 
                               ,$this->e43_ordempagamento 
                               ,$this->e43_empagemov 
                               ,'$this->e43_autorizado' 
                               ,$this->e43_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "notas de ordem do pagamento ($this->e43_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "notas de ordem do pagamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "notas de ordem do pagamento ($this->e43_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e43_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e43_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12323,'$this->e43_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2150,12323,'','".AddSlashes(pg_result($resaco,0,'e43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2150,12324,'','".AddSlashes(pg_result($resaco,0,'e43_ordempagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2150,12325,'','".AddSlashes(pg_result($resaco,0,'e43_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2150,12326,'','".AddSlashes(pg_result($resaco,0,'e43_autorizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2150,12330,'','".AddSlashes(pg_result($resaco,0,'e43_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e43_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empagenotasordem set ";
     $virgula = "";
     if(trim($this->e43_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e43_sequencial"])){ 
       $sql  .= $virgula." e43_sequencial = $this->e43_sequencial ";
       $virgula = ",";
       if(trim($this->e43_sequencial) == null ){ 
         $this->erro_sql = " Campo Código  Sequencial nao Informado.";
         $this->erro_campo = "e43_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e43_ordempagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e43_ordempagamento"])){ 
       $sql  .= $virgula." e43_ordempagamento = $this->e43_ordempagamento ";
       $virgula = ",";
       if(trim($this->e43_ordempagamento) == null ){ 
         $this->erro_sql = " Campo Código ordem pagamento nao Informado.";
         $this->erro_campo = "e43_ordempagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e43_empagemov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e43_empagemov"])){ 
       $sql  .= $virgula." e43_empagemov = $this->e43_empagemov ";
       $virgula = ",";
       if(trim($this->e43_empagemov) == null ){ 
         $this->erro_sql = " Campo Código do movimento da agenda nao Informado.";
         $this->erro_campo = "e43_empagemov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e43_autorizado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e43_autorizado"])){ 
       $sql  .= $virgula." e43_autorizado = '$this->e43_autorizado' ";
       $virgula = ",";
       if(trim($this->e43_autorizado) == null ){ 
         $this->erro_sql = " Campo Pagamento autorizado nao Informado.";
         $this->erro_campo = "e43_autorizado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e43_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e43_valor"])){ 
        if(trim($this->e43_valor)=="" && isset($GLOBALS["HTTP_POST_VARS"]["e43_valor"])){ 
           $this->e43_valor = "0" ; 
        } 
       $sql  .= $virgula." e43_valor = $this->e43_valor ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($e43_sequencial!=null){
       $sql .= " e43_sequencial = $this->e43_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e43_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12323,'$this->e43_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e43_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2150,12323,'".AddSlashes(pg_result($resaco,$conresaco,'e43_sequencial'))."','$this->e43_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e43_ordempagamento"]))
           $resac = db_query("insert into db_acount values($acount,2150,12324,'".AddSlashes(pg_result($resaco,$conresaco,'e43_ordempagamento'))."','$this->e43_ordempagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e43_empagemov"]))
           $resac = db_query("insert into db_acount values($acount,2150,12325,'".AddSlashes(pg_result($resaco,$conresaco,'e43_empagemov'))."','$this->e43_empagemov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e43_autorizado"]))
           $resac = db_query("insert into db_acount values($acount,2150,12326,'".AddSlashes(pg_result($resaco,$conresaco,'e43_autorizado'))."','$this->e43_autorizado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e43_valor"]))
           $resac = db_query("insert into db_acount values($acount,2150,12330,'".AddSlashes(pg_result($resaco,$conresaco,'e43_valor'))."','$this->e43_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notas de ordem do pagamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notas de ordem do pagamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e43_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e43_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12323,'$e43_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2150,12323,'','".AddSlashes(pg_result($resaco,$iresaco,'e43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2150,12324,'','".AddSlashes(pg_result($resaco,$iresaco,'e43_ordempagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2150,12325,'','".AddSlashes(pg_result($resaco,$iresaco,'e43_empagemov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2150,12326,'','".AddSlashes(pg_result($resaco,$iresaco,'e43_autorizado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2150,12330,'','".AddSlashes(pg_result($resaco,$iresaco,'e43_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empagenotasordem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e43_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e43_sequencial = $e43_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notas de ordem do pagamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notas de ordem do pagamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e43_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empagenotasordem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagenotasordem ";
     $sql .= "      inner join empagemov  on  empagemov.e81_codmov = empagenotasordem.e43_empagemov";
     $sql .= "      inner join empageordem  on  empageordem.e42_sequencial = empagenotasordem.e43_ordempagamento";
     $sql .= "      inner join empage  on  empage.e80_codage = empagemov.e81_codage";
     $sql2 = "";
     if($dbwhere==""){
       if($e43_sequencial!=null ){
         $sql2 .= " where empagenotasordem.e43_sequencial = $e43_sequencial "; 
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
   function sql_query_file ( $e43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empagenotasordem ";
     $sql2 = "";
     if($dbwhere==""){
       if($e43_sequencial!=null ){
         $sql2 .= " where empagenotasordem.e43_sequencial = $e43_sequencial "; 
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
  
  function sql_query_empenho( $e43_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
    
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
     $sql .= " from empagenotasordem ";
     $sql .= "      inner join empagemov      on  empagemov.e81_codmov = empagenotasordem.e43_empagemov";
     $sql .= "      inner join empageordem    on  empageordem.e42_sequencial = empagenotasordem.e43_ordempagamento";
     $sql .= "      inner join empage         on  empage.e80_codage = empagemov.e81_codage";
     $sql .= "      left  join empord         on  empagemov.e81_codmov = e82_codmov";
     $sql .= "      left  join pagordem       on  e82_codord = e50_codord";
     $sql .= "      left  join pagordemnota   on  e50_codord  = e71_codord";
     $sql .= "      left  join empnota        on  e71_codnota = e69_codnota";
     $sql .= "      left  join pagordemele    on  e53_codord  = e50_codord";
     $sql .= "      left  join empempenho     on  e50_numemp  = e60_numemp";
     $sql .= "      left  join cgm cgmemp     on  e60_numcgm  = cgmemp.z01_numcgm";
     $sql .= "      left  join empagepag      on  e85_codmov  = e81_codmov";
     $sql .= "      left  join empagemovforma on  e97_codmov  = e81_codmov";
     $sql .= "      left  join empageforma    on  e97_codforma = e96_codigo";
     $sql .= "      left  join empagetipo     on  e83_codtipo = e85_codtipo";
     $sql .= "      left  join pagordemconta  on  e49_codord  = e50_codord";
     $sql .= "      left  join cgm cgmordem   on  e49_numcgm  = cgmordem.z01_numcgm";
     $sql .= "      left  join empageslip     on  e81_codmov  = e89_codmov";
     $sql .= "      left  join slip           on  e89_codigo  = slip.k17_codigo";
     $sql .= "      left  join slipnum        on  slip.k17_codigo  = slipnum.k17_codigo";
     $sql .= "      left  join cgm cgmslip    on  slipnum.k17_numcgm  = cgmslip.z01_numcgm";
     $sql .= "      left  join orcelemento    on  e53_codele  = o56_codele ";
     $sql .= "                                and e50_anousu  = o56_anousu ";
     $sql2 = "";
     if($dbwhere==""){
       if($e43_sequencial!=null ){
         $sql2 .= " where empagenotasordem.e43_sequencial = $e43_sequencial "; 
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