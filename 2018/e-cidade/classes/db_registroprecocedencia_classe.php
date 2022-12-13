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
//CLASSE DA ENTIDADE registroprecocedencia
class cl_registroprecocedencia { 
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
   var $pc37_sequencial = 0; 
   var $pc37_usuario = 0; 
   var $pc37_data_dia = null; 
   var $pc37_data_mes = null; 
   var $pc37_data_ano = null; 
   var $pc37_data = null; 
   var $pc37_resumo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc37_sequencial = int4 = Sequencial 
                 pc37_usuario = int4 = Usuario responsável 
                 pc37_data = date = Data da cedência 
                 pc37_resumo = text = Resumo da cedencia 
                 ";
   //funcao construtor da classe 
   function cl_registroprecocedencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("registroprecocedencia"); 
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
       $this->pc37_sequencial = ($this->pc37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc37_sequencial"]:$this->pc37_sequencial);
       $this->pc37_usuario = ($this->pc37_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc37_usuario"]:$this->pc37_usuario);
       if($this->pc37_data == ""){
         $this->pc37_data_dia = ($this->pc37_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc37_data_dia"]:$this->pc37_data_dia);
         $this->pc37_data_mes = ($this->pc37_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc37_data_mes"]:$this->pc37_data_mes);
         $this->pc37_data_ano = ($this->pc37_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc37_data_ano"]:$this->pc37_data_ano);
         if($this->pc37_data_dia != ""){
            $this->pc37_data = $this->pc37_data_ano."-".$this->pc37_data_mes."-".$this->pc37_data_dia;
         }
       }
       $this->pc37_resumo = ($this->pc37_resumo == ""?@$GLOBALS["HTTP_POST_VARS"]["pc37_resumo"]:$this->pc37_resumo);
     }else{
       $this->pc37_sequencial = ($this->pc37_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc37_sequencial"]:$this->pc37_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc37_sequencial){ 
      $this->atualizacampos();
     if($this->pc37_usuario == null ){ 
       $this->erro_sql = " Campo Usuario responsável nao Informado.";
       $this->erro_campo = "pc37_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc37_data == null ){ 
       $this->erro_sql = " Campo Data da cedência nao Informado.";
       $this->erro_campo = "pc37_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc37_sequencial == "" || $pc37_sequencial == null ){
       $result = db_query("select nextval('registroprecocedencia_pc37_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: registroprecocedencia_pc37_sequencial_seq do campo: pc37_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc37_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from registroprecocedencia_pc37_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc37_sequencial)){
         $this->erro_sql = " Campo pc37_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc37_sequencial = $pc37_sequencial; 
       }
     }
     if(($this->pc37_sequencial == null) || ($this->pc37_sequencial == "") ){ 
       $this->erro_sql = " Campo pc37_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into registroprecocedencia(
                                       pc37_sequencial 
                                      ,pc37_usuario 
                                      ,pc37_data 
                                      ,pc37_resumo 
                       )
                values (
                                $this->pc37_sequencial 
                               ,$this->pc37_usuario 
                               ,".($this->pc37_data == "null" || $this->pc37_data == ""?"null":"'".$this->pc37_data."'")." 
                               ,'$this->pc37_resumo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "registroprecocedencia ($this->pc37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "registroprecocedencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "registroprecocedencia ($this->pc37_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc37_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc37_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18365,'$this->pc37_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3251,18365,'','".AddSlashes(pg_result($resaco,0,'pc37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3251,18366,'','".AddSlashes(pg_result($resaco,0,'pc37_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3251,18367,'','".AddSlashes(pg_result($resaco,0,'pc37_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3251,18368,'','".AddSlashes(pg_result($resaco,0,'pc37_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc37_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update registroprecocedencia set ";
     $virgula = "";
     if(trim($this->pc37_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc37_sequencial"])){ 
       $sql  .= $virgula." pc37_sequencial = $this->pc37_sequencial ";
       $virgula = ",";
       if(trim($this->pc37_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "pc37_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc37_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc37_usuario"])){ 
       $sql  .= $virgula." pc37_usuario = $this->pc37_usuario ";
       $virgula = ",";
       if(trim($this->pc37_usuario) == null ){ 
         $this->erro_sql = " Campo Usuario responsável nao Informado.";
         $this->erro_campo = "pc37_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc37_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc37_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc37_data_dia"] !="") ){ 
       $sql  .= $virgula." pc37_data = '$this->pc37_data' ";
       $virgula = ",";
       if(trim($this->pc37_data) == null ){ 
         $this->erro_sql = " Campo Data da cedência nao Informado.";
         $this->erro_campo = "pc37_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc37_data_dia"])){ 
         $sql  .= $virgula." pc37_data = null ";
         $virgula = ",";
         if(trim($this->pc37_data) == null ){ 
           $this->erro_sql = " Campo Data da cedência nao Informado.";
           $this->erro_campo = "pc37_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc37_resumo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc37_resumo"])){ 
       $sql  .= $virgula." pc37_resumo = '$this->pc37_resumo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($pc37_sequencial!=null){
       $sql .= " pc37_sequencial = $this->pc37_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc37_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18365,'$this->pc37_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc37_sequencial"]) || $this->pc37_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3251,18365,'".AddSlashes(pg_result($resaco,$conresaco,'pc37_sequencial'))."','$this->pc37_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc37_usuario"]) || $this->pc37_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3251,18366,'".AddSlashes(pg_result($resaco,$conresaco,'pc37_usuario'))."','$this->pc37_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc37_data"]) || $this->pc37_data != "")
           $resac = db_query("insert into db_acount values($acount,3251,18367,'".AddSlashes(pg_result($resaco,$conresaco,'pc37_data'))."','$this->pc37_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc37_resumo"]) || $this->pc37_resumo != "")
           $resac = db_query("insert into db_acount values($acount,3251,18368,'".AddSlashes(pg_result($resaco,$conresaco,'pc37_resumo'))."','$this->pc37_resumo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "registroprecocedencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "registroprecocedencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc37_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc37_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18365,'$pc37_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3251,18365,'','".AddSlashes(pg_result($resaco,$iresaco,'pc37_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3251,18366,'','".AddSlashes(pg_result($resaco,$iresaco,'pc37_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3251,18367,'','".AddSlashes(pg_result($resaco,$iresaco,'pc37_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3251,18368,'','".AddSlashes(pg_result($resaco,$iresaco,'pc37_resumo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from registroprecocedencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc37_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc37_sequencial = $pc37_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "registroprecocedencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc37_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "registroprecocedencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc37_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc37_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:registroprecocedencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecocedencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc37_sequencial!=null ){
         $sql2 .= " where registroprecocedencia.pc37_sequencial = $pc37_sequencial "; 
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
   function sql_query_file ( $pc37_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from registroprecocedencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc37_sequencial!=null ){
         $sql2 .= " where registroprecocedencia.pc37_sequencial = $pc37_sequencial "; 
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