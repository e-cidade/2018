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

//MODULO: laboratorio
//CLASSE DA ENTIDADE lab_fechaconferencia
class cl_lab_fechaconferencia { 
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
   var $la58_i_codigo = 0; 
   var $la58_i_fechamento = 0; 
   var $la58_i_conferencia = 0; 
   var $la58_gerado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 la58_i_codigo = int4 = código 
                 la58_i_fechamento = int4 = Fechamento 
                 la58_i_conferencia = int4 = Conferencia 
                 la58_gerado = bool = Gerado 
                 ";
   //funcao construtor da classe 
   function cl_lab_fechaconferencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_fechaconferencia"); 
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
       $this->la58_i_codigo = ($this->la58_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la58_i_codigo"]:$this->la58_i_codigo);
       $this->la58_i_fechamento = ($this->la58_i_fechamento == ""?@$GLOBALS["HTTP_POST_VARS"]["la58_i_fechamento"]:$this->la58_i_fechamento);
       $this->la58_i_conferencia = ($this->la58_i_conferencia == ""?@$GLOBALS["HTTP_POST_VARS"]["la58_i_conferencia"]:$this->la58_i_conferencia);
       $this->la58_gerado = ($this->la58_gerado == "f"?@$GLOBALS["HTTP_POST_VARS"]["la58_gerado"]:$this->la58_gerado);
     }else{
       $this->la58_i_codigo = ($this->la58_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la58_i_codigo"]:$this->la58_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la58_i_codigo){ 
      $this->atualizacampos();
     if($this->la58_i_fechamento == null ){ 
       $this->erro_sql = " Campo Fechamento nao Informado.";
       $this->erro_campo = "la58_i_fechamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la58_i_conferencia == null ){ 
       $this->erro_sql = " Campo Conferencia nao Informado.";
       $this->erro_campo = "la58_i_conferencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la58_gerado == null ){ 
       $this->erro_sql = " Campo Gerado não informado.";
       $this->erro_campo = "la58_gerado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($la58_i_codigo == "" || $la58_i_codigo == null ){
       $result = db_query("select nextval('lab_fechaconferencia_la58_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_fechaconferencia_la58_i_codigo_seq do campo: la58_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->la58_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from lab_fechaconferencia_la58_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la58_i_codigo)){
         $this->erro_sql = " Campo la58_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la58_i_codigo = $la58_i_codigo; 
       }
     }
     if(($this->la58_i_codigo == null) || ($this->la58_i_codigo == "") ){ 
       $this->erro_sql = " Campo la58_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_fechaconferencia(
                                       la58_i_codigo 
                                      ,la58_i_fechamento 
                                      ,la58_i_conferencia 
                                      ,la58_gerado 
                       )
                values (
                                $this->la58_i_codigo 
                               ,$this->la58_i_fechamento 
                               ,$this->la58_i_conferencia 
                               ,'$this->la58_gerado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Fechamento conferencia ($this->la58_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Fechamento conferencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Fechamento conferencia ($this->la58_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la58_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la58_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18003,'$this->la58_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3182,18003,'','".AddSlashes(pg_result($resaco,0,'la58_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3182,18004,'','".AddSlashes(pg_result($resaco,0,'la58_i_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3182,18005,'','".AddSlashes(pg_result($resaco,0,'la58_i_conferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3182,20670,'','".AddSlashes(pg_result($resaco,0,'la58_gerado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($la58_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update lab_fechaconferencia set ";
     $virgula = "";
     if(trim($this->la58_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la58_i_codigo"])){ 
       $sql  .= $virgula." la58_i_codigo = $this->la58_i_codigo ";
       $virgula = ",";
       if(trim($this->la58_i_codigo) == null ){ 
         $this->erro_sql = " Campo código nao Informado.";
         $this->erro_campo = "la58_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la58_i_fechamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la58_i_fechamento"])){ 
       $sql  .= $virgula." la58_i_fechamento = $this->la58_i_fechamento ";
       $virgula = ",";
       if(trim($this->la58_i_fechamento) == null ){ 
         $this->erro_sql = " Campo Fechamento nao Informado.";
         $this->erro_campo = "la58_i_fechamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la58_i_conferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la58_i_conferencia"])){ 
       $sql  .= $virgula." la58_i_conferencia = $this->la58_i_conferencia ";
       $virgula = ",";
       if(trim($this->la58_i_conferencia) == null ){ 
         $this->erro_sql = " Campo Conferencia nao Informado.";
         $this->erro_campo = "la58_i_conferencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la58_gerado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la58_gerado"])){ 
       $sql  .= $virgula." la58_gerado = '$this->la58_gerado' ";
       $virgula = ",";
       if(trim($this->la58_gerado) == null ){ 
         $this->erro_sql = " Campo Gerado não informado.";
         $this->erro_campo = "la58_gerado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($la58_i_codigo!=null){
       $sql .= " la58_i_codigo = $this->la58_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la58_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18003,'$this->la58_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la58_i_codigo"]) || $this->la58_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3182,18003,'".AddSlashes(pg_result($resaco,$conresaco,'la58_i_codigo'))."','$this->la58_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la58_i_fechamento"]) || $this->la58_i_fechamento != "")
           $resac = db_query("insert into db_acount values($acount,3182,18004,'".AddSlashes(pg_result($resaco,$conresaco,'la58_i_fechamento'))."','$this->la58_i_fechamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la58_i_conferencia"]) || $this->la58_i_conferencia != "")
           $resac = db_query("insert into db_acount values($acount,3182,18005,'".AddSlashes(pg_result($resaco,$conresaco,'la58_i_conferencia'))."','$this->la58_i_conferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la58_gerado"]) || $this->la58_gerado != "")
           $resac = db_query("insert into db_acount values($acount,3182,20670,'".AddSlashes(pg_result($resaco,$conresaco,'la58_gerado'))."','$this->la58_gerado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento conferencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la58_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento conferencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la58_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la58_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($la58_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la58_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18003,'$la58_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3182,18003,'','".AddSlashes(pg_result($resaco,$iresaco,'la58_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3182,18004,'','".AddSlashes(pg_result($resaco,$iresaco,'la58_i_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3182,18005,'','".AddSlashes(pg_result($resaco,$iresaco,'la58_i_conferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3182,20670,'','".AddSlashes(pg_result($resaco,$iresaco,'la58_gerado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_fechaconferencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la58_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la58_i_codigo = $la58_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Fechamento conferencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la58_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Fechamento conferencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la58_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la58_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:lab_fechaconferencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $la58_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_fechaconferencia ";
     $sql .= "      inner join lab_conferencia  on  lab_conferencia.la47_i_codigo = lab_fechaconferencia.la58_i_conferencia";
     $sql .= "      inner join lab_fechamento  on  lab_fechamento.la54_i_codigo = lab_fechaconferencia.la58_i_fechamento";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = lab_conferencia.la47_i_login";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = lab_conferencia.la47_i_procedimento";
     $sql .= "      left  join sau_cid  on  sau_cid.sd70_i_codigo = lab_conferencia.la47_i_cid";
     $sql .= "      inner join lab_requiitem  on  lab_requiitem.la21_i_codigo = lab_conferencia.la47_i_requiitem";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = lab_fechamento.la54_i_login";
     $sql .= "      left  join sau_financiamento  on  sau_financiamento.sd65_i_codigo = lab_fechamento.la54_i_finaciamento";
     $sql2 = "";
     if($dbwhere==""){
       if($la58_i_codigo!=null ){
         $sql2 .= " where lab_fechaconferencia.la58_i_codigo = $la58_i_codigo "; 
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
   function sql_query_file ( $la58_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from lab_fechaconferencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($la58_i_codigo!=null ){
         $sql2 .= " where lab_fechaconferencia.la58_i_codigo = $la58_i_codigo "; 
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