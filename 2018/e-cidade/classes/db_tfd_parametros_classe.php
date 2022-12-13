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

//MODULO: tfd
//CLASSE DA ENTIDADE tfd_parametros
class cl_tfd_parametros { 
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
   var $tf11_i_codigo = 0; 
   var $tf11_i_utilizagradehorario = 0; 
   var $tf11_i_campofoco = 0; 
   var $tf11_especmedico = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf11_i_codigo = int4 = Código 
                 tf11_i_utilizagradehorario = int4 = Utiliza Grade de Horário 
                 tf11_i_campofoco = int4 = Foco no Pedido de TFD 
                 tf11_especmedico = int4 = Especmedico 
                 ";
   //funcao construtor da classe 
   function cl_tfd_parametros() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tfd_parametros"); 
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
       $this->tf11_i_codigo = ($this->tf11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf11_i_codigo"]:$this->tf11_i_codigo);
       $this->tf11_i_utilizagradehorario = ($this->tf11_i_utilizagradehorario == ""?@$GLOBALS["HTTP_POST_VARS"]["tf11_i_utilizagradehorario"]:$this->tf11_i_utilizagradehorario);
       $this->tf11_i_campofoco = ($this->tf11_i_campofoco == ""?@$GLOBALS["HTTP_POST_VARS"]["tf11_i_campofoco"]:$this->tf11_i_campofoco);
       $this->tf11_especmedico = ($this->tf11_especmedico == ""?@$GLOBALS["HTTP_POST_VARS"]["tf11_especmedico"]:$this->tf11_especmedico);
     }else{
       $this->tf11_i_codigo = ($this->tf11_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf11_i_codigo"]:$this->tf11_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($tf11_i_codigo){ 
      $this->atualizacampos();
     if($this->tf11_i_utilizagradehorario == null ){ 
       $this->erro_sql = " Campo Utiliza Grade de Horário nao Informado.";
       $this->erro_campo = "tf11_i_utilizagradehorario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf11_i_campofoco == null ){ 
       $this->erro_sql = " Campo Foco no Pedido de TFD nao Informado.";
       $this->erro_campo = "tf11_i_campofoco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf11_especmedico == null ){ 
       $this->tf11_especmedico = "null";
     }
     if($tf11_i_codigo == "" || $tf11_i_codigo == null ){
       $result = db_query("select nextval('tfd_parametros_tf11_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tfd_parametros_tf11_i_codigo_seq do campo: tf11_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf11_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tfd_parametros_tf11_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf11_i_codigo)){
         $this->erro_sql = " Campo tf11_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf11_i_codigo = $tf11_i_codigo; 
       }
     }
     if(($this->tf11_i_codigo == null) || ($this->tf11_i_codigo == "") ){ 
       $this->erro_sql = " Campo tf11_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tfd_parametros(
                                       tf11_i_codigo 
                                      ,tf11_i_utilizagradehorario 
                                      ,tf11_i_campofoco 
                                      ,tf11_especmedico 
                       )
                values (
                                $this->tf11_i_codigo 
                               ,$this->tf11_i_utilizagradehorario 
                               ,$this->tf11_i_campofoco 
                               ,$this->tf11_especmedico 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "tfd_parametros ($this->tf11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "tfd_parametros já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "tfd_parametros ($this->tf11_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf11_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->tf11_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16371,'$this->tf11_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2867,16371,'','".AddSlashes(pg_result($resaco,0,'tf11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2867,16372,'','".AddSlashes(pg_result($resaco,0,'tf11_i_utilizagradehorario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2867,17592,'','".AddSlashes(pg_result($resaco,0,'tf11_i_campofoco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2867,18250,'','".AddSlashes(pg_result($resaco,0,'tf11_especmedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf11_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update tfd_parametros set ";
     $virgula = "";
     if(trim($this->tf11_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf11_i_codigo"])){ 
       $sql  .= $virgula." tf11_i_codigo = $this->tf11_i_codigo ";
       $virgula = ",";
       if(trim($this->tf11_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "tf11_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf11_i_utilizagradehorario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf11_i_utilizagradehorario"])){ 
       $sql  .= $virgula." tf11_i_utilizagradehorario = $this->tf11_i_utilizagradehorario ";
       $virgula = ",";
       if(trim($this->tf11_i_utilizagradehorario) == null ){ 
         $this->erro_sql = " Campo Utiliza Grade de Horário nao Informado.";
         $this->erro_campo = "tf11_i_utilizagradehorario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf11_i_campofoco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf11_i_campofoco"])){ 
       $sql  .= $virgula." tf11_i_campofoco = $this->tf11_i_campofoco ";
       $virgula = ",";
       if(trim($this->tf11_i_campofoco) == null ){ 
         $this->erro_sql = " Campo Foco no Pedido de TFD nao Informado.";
         $this->erro_campo = "tf11_i_campofoco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf11_especmedico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf11_especmedico"])){ 
        if(trim($this->tf11_especmedico)=="" && isset($GLOBALS["HTTP_POST_VARS"]["tf11_especmedico"])){ 
           $this->tf11_especmedico = "null" ; 
        } 
       $sql  .= $virgula." tf11_especmedico = $this->tf11_especmedico ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($tf11_i_codigo!=null){
       $sql .= " tf11_i_codigo = $this->tf11_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->tf11_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16371,'$this->tf11_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf11_i_codigo"]) || $this->tf11_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2867,16371,'".AddSlashes(pg_result($resaco,$conresaco,'tf11_i_codigo'))."','$this->tf11_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf11_i_utilizagradehorario"]) || $this->tf11_i_utilizagradehorario != "")
           $resac = db_query("insert into db_acount values($acount,2867,16372,'".AddSlashes(pg_result($resaco,$conresaco,'tf11_i_utilizagradehorario'))."','$this->tf11_i_utilizagradehorario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf11_i_campofoco"]) || $this->tf11_i_campofoco != "")
           $resac = db_query("insert into db_acount values($acount,2867,17592,'".AddSlashes(pg_result($resaco,$conresaco,'tf11_i_campofoco'))."','$this->tf11_i_campofoco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["tf11_especmedico"]) || $this->tf11_especmedico != "")
           $resac = db_query("insert into db_acount values($acount,2867,18250,'".AddSlashes(pg_result($resaco,$conresaco,'tf11_especmedico'))."','$this->tf11_especmedico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_parametros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_parametros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf11_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($tf11_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16371,'$tf11_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2867,16371,'','".AddSlashes(pg_result($resaco,$iresaco,'tf11_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2867,16372,'','".AddSlashes(pg_result($resaco,$iresaco,'tf11_i_utilizagradehorario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2867,17592,'','".AddSlashes(pg_result($resaco,$iresaco,'tf11_i_campofoco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2867,18250,'','".AddSlashes(pg_result($resaco,$iresaco,'tf11_especmedico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tfd_parametros
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf11_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf11_i_codigo = $tf11_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "tfd_parametros nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf11_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "tfd_parametros nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf11_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf11_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:tfd_parametros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_parametros ";
     $sql .= "      left join especmedico  on  especmedico.sd27_i_codigo = tfd_parametros.tf11_especmedico";
     $sql .= "      left join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      left join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql2 = "";
     if($dbwhere==""){
       if($tf11_i_codigo!=null ){
         $sql2 .= " where tfd_parametros.tf11_i_codigo = $tf11_i_codigo "; 
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
  // funcao do sql query geral
  function sql_query_geral ( $tf11_i_codigo=null, $campos="*", $ordem=null, $dbwhere="") {

    $sql = "select ";
    if ($campos != "*") {

      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";

      }

    } else {
      $sql .= $campos;
    }
    $sql .= " from tfd_parametros ";
    $sql .= "      left join especmedico    on sd27_i_codigo   = tfd_parametros.tf11_especmedico";
    $sql .= "      left join rhcbo          on rh70_sequencial = especmedico.sd27_i_rhcbo";
    $sql .= "      left join unidademedicos on sd04_i_codigo   = especmedico.sd27_i_undmed";
    $sql .= "      left join medicos        on sd03_i_codigo   = unidademedicos.sd04_i_medico";
    $sql .= "      left join cgm            on z01_numcgm      = medicos.sd03_i_cgm";
    $sql .= "      left join unidades       on sd02_i_codigo   = unidademedicos.sd04_i_unidade";
    $sql .= "      left join db_depart      on coddepto        = unidades.sd02_i_codigo";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($tf11_i_codigo != null ) {
        $sql2 .= " where tfd_parametros.tf11_i_codigo = $tf11_i_codigo "; 
      }
    } else if($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {

      $sql       .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {

        $sql    .= $virgula.$campos_sql[$i];
        $virgula = ",";

      }

    }
    return $sql;

  }
   // funcao do sql 
   function sql_query_file ( $tf11_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tfd_parametros ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf11_i_codigo!=null ){
         $sql2 .= " where tfd_parametros.tf11_i_codigo = $tf11_i_codigo "; 
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