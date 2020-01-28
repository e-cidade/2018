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

//MODULO: compras
//CLASSE DA ENTIDADE registroprecocedenciaitem
class cl_registroprecocedenciaitem { 
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
   var $pc36_sequencial = 0; 
   var $pc36_solicitemdestino = 0; 
   var $pc36_solicitemorigem = 0; 
   var $pc36_registroprecocedencia = 0; 
   var $pc36_quantidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc36_sequencial = int8 = Sequencial 
                 pc36_solicitemdestino = int4 = Item destino 
                 pc36_solicitemorigem = int4 = Item origem 
                 pc36_registroprecocedencia = int4 = Registro de preço 
                 pc36_quantidade = numeric(10) = Quantidade 
                 ";
   //funcao construtor da classe 
   function cl_registroprecocedenciaitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("registroprecocedenciaitem"); 
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
       $this->pc36_sequencial = ($this->pc36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc36_sequencial"]:$this->pc36_sequencial);
       $this->pc36_solicitemdestino = ($this->pc36_solicitemdestino == ""?@$GLOBALS["HTTP_POST_VARS"]["pc36_solicitemdestino"]:$this->pc36_solicitemdestino);
       $this->pc36_solicitemorigem = ($this->pc36_solicitemorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc36_solicitemorigem"]:$this->pc36_solicitemorigem);
       $this->pc36_registroprecocedencia = ($this->pc36_registroprecocedencia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc36_registroprecocedencia"]:$this->pc36_registroprecocedencia);
       $this->pc36_quantidade = ($this->pc36_quantidade == ""?@$GLOBALS["HTTP_POST_VARS"]["pc36_quantidade"]:$this->pc36_quantidade);
     }else{
       $this->pc36_sequencial = ($this->pc36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc36_sequencial"]:$this->pc36_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc36_sequencial){ 
      $this->atualizacampos();
     if($this->pc36_solicitemdestino == null ){ 
       $this->erro_sql = " Campo Item destino nao Informado.";
       $this->erro_campo = "pc36_solicitemdestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc36_solicitemorigem == null ){ 
       $this->erro_sql = " Campo Item origem nao Informado.";
       $this->erro_campo = "pc36_solicitemorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc36_registroprecocedencia == null ){ 
       $this->erro_sql = " Campo Registro de preço nao Informado.";
       $this->erro_campo = "pc36_registroprecocedencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc36_quantidade == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "pc36_quantidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc36_sequencial == "" || $pc36_sequencial == null ){
       $result = db_query("select nextval('registroprecocedenciaitem_pc36_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: registroprecocedenciaitem_pc36_sequencial_seq do campo: pc36_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc36_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from registroprecocedenciaitem_pc36_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc36_sequencial)){
         $this->erro_sql = " Campo pc36_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc36_sequencial = $pc36_sequencial; 
       }
     }
     if(($this->pc36_sequencial == null) || ($this->pc36_sequencial == "") ){ 
       $this->erro_sql = " Campo pc36_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into registroprecocedenciaitem(
                                       pc36_sequencial 
                                      ,pc36_solicitemdestino 
                                      ,pc36_solicitemorigem 
                                      ,pc36_registroprecocedencia 
                                      ,pc36_quantidade 
                       )
                values (
                                $this->pc36_sequencial 
                               ,$this->pc36_solicitemdestino 
                               ,$this->pc36_solicitemorigem 
                               ,$this->pc36_registroprecocedencia 
                               ,$this->pc36_quantidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "registroprecocedenciaitem ($this->pc36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "registroprecocedenciaitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "registroprecocedenciaitem ($this->pc36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc36_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc36_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18360,'$this->pc36_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3250,18360,'','".AddSlashes(pg_result($resaco,0,'pc36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3250,18361,'','".AddSlashes(pg_result($resaco,0,'pc36_solicitemdestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3250,18362,'','".AddSlashes(pg_result($resaco,0,'pc36_solicitemorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3250,18363,'','".AddSlashes(pg_result($resaco,0,'pc36_registroprecocedencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3250,18364,'','".AddSlashes(pg_result($resaco,0,'pc36_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc36_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update registroprecocedenciaitem set ";
     $virgula = "";
     if(trim($this->pc36_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc36_sequencial"])){ 
       $sql  .= $virgula." pc36_sequencial = $this->pc36_sequencial ";
       $virgula = ",";
       if(trim($this->pc36_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc36_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc36_solicitemdestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc36_solicitemdestino"])){ 
       $sql  .= $virgula." pc36_solicitemdestino = $this->pc36_solicitemdestino ";
       $virgula = ",";
       if(trim($this->pc36_solicitemdestino) == null ){ 
         $this->erro_sql = " Campo Item destino nao Informado.";
         $this->erro_campo = "pc36_solicitemdestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc36_solicitemorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc36_solicitemorigem"])){ 
       $sql  .= $virgula." pc36_solicitemorigem = $this->pc36_solicitemorigem ";
       $virgula = ",";
       if(trim($this->pc36_solicitemorigem) == null ){ 
         $this->erro_sql = " Campo Item origem nao Informado.";
         $this->erro_campo = "pc36_solicitemorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc36_registroprecocedencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc36_registroprecocedencia"])){ 
       $sql  .= $virgula." pc36_registroprecocedencia = $this->pc36_registroprecocedencia ";
       $virgula = ",";
       if(trim($this->pc36_registroprecocedencia) == null ){ 
         $this->erro_sql = " Campo Registro de preço nao Informado.";
         $this->erro_campo = "pc36_registroprecocedencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc36_quantidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc36_quantidade"])){ 
       $sql  .= $virgula." pc36_quantidade = $this->pc36_quantidade ";
       $virgula = ",";
       if(trim($this->pc36_quantidade) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "pc36_quantidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc36_sequencial!=null){
       $sql .= " pc36_sequencial = $this->pc36_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc36_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18360,'$this->pc36_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc36_sequencial"]) || $this->pc36_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3250,18360,'".AddSlashes(pg_result($resaco,$conresaco,'pc36_sequencial'))."','$this->pc36_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc36_solicitemdestino"]) || $this->pc36_solicitemdestino != "")
           $resac = db_query("insert into db_acount values($acount,3250,18361,'".AddSlashes(pg_result($resaco,$conresaco,'pc36_solicitemdestino'))."','$this->pc36_solicitemdestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc36_solicitemorigem"]) || $this->pc36_solicitemorigem != "")
           $resac = db_query("insert into db_acount values($acount,3250,18362,'".AddSlashes(pg_result($resaco,$conresaco,'pc36_solicitemorigem'))."','$this->pc36_solicitemorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc36_registroprecocedencia"]) || $this->pc36_registroprecocedencia != "")
           $resac = db_query("insert into db_acount values($acount,3250,18363,'".AddSlashes(pg_result($resaco,$conresaco,'pc36_registroprecocedencia'))."','$this->pc36_registroprecocedencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc36_quantidade"]) || $this->pc36_quantidade != "")
           $resac = db_query("insert into db_acount values($acount,3250,18364,'".AddSlashes(pg_result($resaco,$conresaco,'pc36_quantidade'))."','$this->pc36_quantidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "registroprecocedenciaitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "registroprecocedenciaitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc36_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc36_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18360,'$pc36_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3250,18360,'','".AddSlashes(pg_result($resaco,$iresaco,'pc36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3250,18361,'','".AddSlashes(pg_result($resaco,$iresaco,'pc36_solicitemdestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3250,18362,'','".AddSlashes(pg_result($resaco,$iresaco,'pc36_solicitemorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3250,18363,'','".AddSlashes(pg_result($resaco,$iresaco,'pc36_registroprecocedencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3250,18364,'','".AddSlashes(pg_result($resaco,$iresaco,'pc36_quantidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from registroprecocedenciaitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc36_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc36_sequencial = $pc36_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "registroprecocedenciaitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "registroprecocedenciaitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc36_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:registroprecocedenciaitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecocedenciaitem ";
     $sql .= "      inner join solicitem  on  solicitem.pc11_codigo = registroprecocedenciaitem.pc36_solicitemdestino";
     $sql .= "      inner join registroprecocedencia  on  registroprecocedencia.pc37_sequencial = registroprecocedenciaitem.pc36_registroprecocedencia";
     $sql .= "      inner join solicita  on  solicita.pc10_numero = solicitem.pc11_numero";
     $sql2 = "";
     if($dbwhere==""){
       if($pc36_sequencial!=null ){
         $sql2 .= " where registroprecocedenciaitem.pc36_sequencial = $pc36_sequencial "; 
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
   function sql_query_file ( $pc36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecocedenciaitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc36_sequencial!=null ){
         $sql2 .= " where registroprecocedenciaitem.pc36_sequencial = $pc36_sequencial "; 
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
  
function sql_query_cedencia ( $pc36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecocedenciaitem ";
     $sql .= "      inner join registroprecocedencia  on  registroprecocedencia.pc37_sequencial = registroprecocedenciaitem.pc36_registroprecocedencia";
     $sql .= "      inner join solicitem itemdestino on  itemdestino.pc11_codigo = registroprecocedenciaitem.pc36_solicitemdestino";
     $sql .= "      inner join solicita  solicitadestino on  solicitadestino.pc10_numero = itemdestino.pc11_numero";
     $sql .= "      inner join db_depart deptodestino    on  solicitadestino.pc10_depto  = deptodestino.coddepto";
     $sql .= "      inner join solicitem itemorigem on  itemorigem.pc11_codigo = registroprecocedenciaitem.pc36_solicitemorigem";
     $sql .= "      inner join solicita  solicitaorigem on  solicitaorigem.pc10_numero = itemorigem.pc11_numero";
     $sql .= "      inner join db_depart deptoorigem    on  solicitaorigem.pc10_depto  = deptoorigem.coddepto";
     $sql2 = "";
     if($dbwhere==""){
       if($pc36_sequencial!=null ){
         $sql2 .= " where registroprecocedenciaitem.pc36_sequencial = $pc36_sequencial "; 
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