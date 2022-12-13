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

//MODULO: educação
//CLASSE DA ENTIDADE opcaoquestao
class cl_opcaoquestao { 
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
   var $ed106_i_codigo = 0; 
   var $ed106_c_descr = null; 
   var $ed106_f_pontuacao = 0; 
   var $ed106_i_sequencia = 0; 
   var $ed106_c_ativo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed106_i_codigo = int8 = Código 
                 ed106_c_descr = char(20) = Descrição 
                 ed106_f_pontuacao = float4 = Pontuação 
                 ed106_i_sequencia = int4 = Sequência 
                 ed106_c_ativo = char(1) = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_opcaoquestao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("opcaoquestao"); 
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
       $this->ed106_i_codigo = ($this->ed106_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed106_i_codigo"]:$this->ed106_i_codigo);
       $this->ed106_c_descr = ($this->ed106_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed106_c_descr"]:$this->ed106_c_descr);
       $this->ed106_f_pontuacao = ($this->ed106_f_pontuacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed106_f_pontuacao"]:$this->ed106_f_pontuacao);
       $this->ed106_i_sequencia = ($this->ed106_i_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed106_i_sequencia"]:$this->ed106_i_sequencia);
       $this->ed106_c_ativo = ($this->ed106_c_ativo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed106_c_ativo"]:$this->ed106_c_ativo);
     }else{
       $this->ed106_i_codigo = ($this->ed106_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed106_i_codigo"]:$this->ed106_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed106_i_codigo){ 
      $this->atualizacampos();
     if($this->ed106_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed106_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed106_f_pontuacao == null ){ 
       $this->erro_sql = " Campo Pontuação nao Informado.";
       $this->erro_campo = "ed106_f_pontuacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed106_i_sequencia == null ){ 
       $this->erro_sql = " Campo Sequência nao Informado.";
       $this->erro_campo = "ed106_i_sequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed106_c_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ed106_c_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed106_i_codigo == "" || $ed106_i_codigo == null ){
       $result = db_query("select nextval('opcaoquestao_ed106_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: opcaoquestao_ed106_i_codigo_seq do campo: ed106_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed106_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from opcaoquestao_ed106_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed106_i_codigo)){
         $this->erro_sql = " Campo ed106_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed106_i_codigo = $ed106_i_codigo; 
       }
     }
     if(($this->ed106_i_codigo == null) || ($this->ed106_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed106_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into opcaoquestao(
                                       ed106_i_codigo 
                                      ,ed106_c_descr 
                                      ,ed106_f_pontuacao 
                                      ,ed106_i_sequencia 
                                      ,ed106_c_ativo 
                       )
                values (
                                $this->ed106_i_codigo 
                               ,'$this->ed106_c_descr' 
                               ,$this->ed106_f_pontuacao 
                               ,$this->ed106_i_sequencia 
                               ,'$this->ed106_c_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de opções das questões ($this->ed106_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de opções das questões já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de opções das questões ($this->ed106_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed106_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed106_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009103,'$this->ed106_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010171,1009103,'','".AddSlashes(pg_result($resaco,0,'ed106_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010171,1009104,'','".AddSlashes(pg_result($resaco,0,'ed106_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010171,1009105,'','".AddSlashes(pg_result($resaco,0,'ed106_f_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010171,1009106,'','".AddSlashes(pg_result($resaco,0,'ed106_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010171,1009107,'','".AddSlashes(pg_result($resaco,0,'ed106_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed106_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update opcaoquestao set ";
     $virgula = "";
     if(trim($this->ed106_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed106_i_codigo"])){ 
       $sql  .= $virgula." ed106_i_codigo = $this->ed106_i_codigo ";
       $virgula = ",";
       if(trim($this->ed106_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed106_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed106_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed106_c_descr"])){ 
       $sql  .= $virgula." ed106_c_descr = '$this->ed106_c_descr' ";
       $virgula = ",";
       if(trim($this->ed106_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed106_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed106_f_pontuacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed106_f_pontuacao"])){ 
       $sql  .= $virgula." ed106_f_pontuacao = $this->ed106_f_pontuacao ";
       $virgula = ",";
       if(trim($this->ed106_f_pontuacao) == null ){ 
         $this->erro_sql = " Campo Pontuação nao Informado.";
         $this->erro_campo = "ed106_f_pontuacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed106_i_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed106_i_sequencia"])){ 
       $sql  .= $virgula." ed106_i_sequencia = $this->ed106_i_sequencia ";
       $virgula = ",";
       if(trim($this->ed106_i_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "ed106_i_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed106_c_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed106_c_ativo"])){ 
       $sql  .= $virgula." ed106_c_ativo = '$this->ed106_c_ativo' ";
       $virgula = ",";
       if(trim($this->ed106_c_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ed106_c_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed106_i_codigo!=null){
       $sql .= " ed106_i_codigo = $this->ed106_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed106_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009103,'$this->ed106_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed106_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010171,1009103,'".AddSlashes(pg_result($resaco,$conresaco,'ed106_i_codigo'))."','$this->ed106_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed106_c_descr"]))
           $resac = db_query("insert into db_acount values($acount,1010171,1009104,'".AddSlashes(pg_result($resaco,$conresaco,'ed106_c_descr'))."','$this->ed106_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed106_f_pontuacao"]))
           $resac = db_query("insert into db_acount values($acount,1010171,1009105,'".AddSlashes(pg_result($resaco,$conresaco,'ed106_f_pontuacao'))."','$this->ed106_f_pontuacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed106_i_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1010171,1009106,'".AddSlashes(pg_result($resaco,$conresaco,'ed106_i_sequencia'))."','$this->ed106_i_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed106_c_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1010171,1009107,'".AddSlashes(pg_result($resaco,$conresaco,'ed106_c_ativo'))."','$this->ed106_c_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de opções das questões nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed106_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de opções das questões nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed106_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed106_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed106_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed106_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009103,'$ed106_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010171,1009103,'','".AddSlashes(pg_result($resaco,$iresaco,'ed106_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010171,1009104,'','".AddSlashes(pg_result($resaco,$iresaco,'ed106_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010171,1009105,'','".AddSlashes(pg_result($resaco,$iresaco,'ed106_f_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010171,1009106,'','".AddSlashes(pg_result($resaco,$iresaco,'ed106_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010171,1009107,'','".AddSlashes(pg_result($resaco,$iresaco,'ed106_c_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from opcaoquestao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed106_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed106_i_codigo = $ed106_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de opções das questões nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed106_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de opções das questões nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed106_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed106_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:opcaoquestao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed106_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from opcaoquestao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed106_i_codigo!=null ){
         $sql2 .= " where opcaoquestao.ed106_i_codigo = $ed106_i_codigo "; 
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
   function sql_query_file ( $ed106_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from opcaoquestao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed106_i_codigo!=null ){
         $sql2 .= " where opcaoquestao.ed106_i_codigo = $ed106_i_codigo "; 
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