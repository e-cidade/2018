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

//MODULO: issqn
//CLASSE DA ENTIDADE areapont
class cl_areapont { 
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
   var $q28_sequencia = 0; 
   var $q28_quantini = 0; 
   var $q28_quantfim = 0; 
   var $q28_pontuacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q28_sequencia = int4 = Sequencia 
                 q28_quantini = float8 = Quantidade final 
                 q28_quantfim = float8 = Quantidade final 
                 q28_pontuacao = float8 = Pontuação 
                 ";
   //funcao construtor da classe 
   function cl_areapont() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("areapont"); 
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
       $this->q28_sequencia = ($this->q28_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["q28_sequencia"]:$this->q28_sequencia);
       $this->q28_quantini = ($this->q28_quantini == ""?@$GLOBALS["HTTP_POST_VARS"]["q28_quantini"]:$this->q28_quantini);
       $this->q28_quantfim = ($this->q28_quantfim == ""?@$GLOBALS["HTTP_POST_VARS"]["q28_quantfim"]:$this->q28_quantfim);
       $this->q28_pontuacao = ($this->q28_pontuacao == ""?@$GLOBALS["HTTP_POST_VARS"]["q28_pontuacao"]:$this->q28_pontuacao);
     }else{
       $this->q28_sequencia = ($this->q28_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["q28_sequencia"]:$this->q28_sequencia);
     }
   }
   // funcao para inclusao
   function incluir ($q28_sequencia){ 
      $this->atualizacampos();
     if($this->q28_quantini == null ){ 
       $this->erro_sql = " Campo Quantidade final nao Informado.";
       $this->erro_campo = "q28_quantini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q28_quantfim == null ){ 
       $this->erro_sql = " Campo Quantidade final nao Informado.";
       $this->erro_campo = "q28_quantfim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q28_pontuacao == null ){ 
       $this->erro_sql = " Campo Pontuação nao Informado.";
       $this->erro_campo = "q28_pontuacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q28_sequencia == "" || $q28_sequencia == null ){
       $result = db_query("select nextval('areapont_q28_sequencia_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: areapont_q28_sequencia_seq do campo: q28_sequencia"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q28_sequencia = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from areapont_q28_sequencia_seq");
       if(($result != false) && (pg_result($result,0,0) < $q28_sequencia)){
         $this->erro_sql = " Campo q28_sequencia maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q28_sequencia = $q28_sequencia; 
       }
     }
     if(($this->q28_sequencia == null) || ($this->q28_sequencia == "") ){ 
       $this->erro_sql = " Campo q28_sequencia nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into areapont(
                                       q28_sequencia 
                                      ,q28_quantini 
                                      ,q28_quantfim 
                                      ,q28_pontuacao 
                       )
                values (
                                $this->q28_sequencia 
                               ,$this->q28_quantini 
                               ,$this->q28_quantfim 
                               ,$this->q28_pontuacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Pontuação da área ($this->q28_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Pontuação da área já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Pontuação da área ($this->q28_sequencia) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q28_sequencia;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q28_sequencia));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8750,'$this->q28_sequencia','I')");
       $resac = db_query("insert into db_acount values($acount,1493,8750,'','".AddSlashes(pg_result($resaco,0,'q28_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1493,8751,'','".AddSlashes(pg_result($resaco,0,'q28_quantini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1493,8752,'','".AddSlashes(pg_result($resaco,0,'q28_quantfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1493,8753,'','".AddSlashes(pg_result($resaco,0,'q28_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q28_sequencia=null) { 
      $this->atualizacampos();
     $sql = " update areapont set ";
     $virgula = "";
     if(trim($this->q28_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q28_sequencia"])){ 
       $sql  .= $virgula." q28_sequencia = $this->q28_sequencia ";
       $virgula = ",";
       if(trim($this->q28_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequencia nao Informado.";
         $this->erro_campo = "q28_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q28_quantini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q28_quantini"])){ 
       $sql  .= $virgula." q28_quantini = $this->q28_quantini ";
       $virgula = ",";
       if(trim($this->q28_quantini) == null ){ 
         $this->erro_sql = " Campo Quantidade final nao Informado.";
         $this->erro_campo = "q28_quantini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q28_quantfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q28_quantfim"])){ 
       $sql  .= $virgula." q28_quantfim = $this->q28_quantfim ";
       $virgula = ",";
       if(trim($this->q28_quantfim) == null ){ 
         $this->erro_sql = " Campo Quantidade final nao Informado.";
         $this->erro_campo = "q28_quantfim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q28_pontuacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q28_pontuacao"])){ 
       $sql  .= $virgula." q28_pontuacao = $this->q28_pontuacao ";
       $virgula = ",";
       if(trim($this->q28_pontuacao) == null ){ 
         $this->erro_sql = " Campo Pontuação nao Informado.";
         $this->erro_campo = "q28_pontuacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q28_sequencia!=null){
       $sql .= " q28_sequencia = $this->q28_sequencia";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q28_sequencia));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8750,'$this->q28_sequencia','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q28_sequencia"]) || $this->q28_sequencia != "")
           $resac = db_query("insert into db_acount values($acount,1493,8750,'".AddSlashes(pg_result($resaco,$conresaco,'q28_sequencia'))."','$this->q28_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q28_quantini"]) || $this->q28_quantini != "")
           $resac = db_query("insert into db_acount values($acount,1493,8751,'".AddSlashes(pg_result($resaco,$conresaco,'q28_quantini'))."','$this->q28_quantini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q28_quantfim"]) || $this->q28_quantfim != "")
           $resac = db_query("insert into db_acount values($acount,1493,8752,'".AddSlashes(pg_result($resaco,$conresaco,'q28_quantfim'))."','$this->q28_quantfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q28_pontuacao"]) || $this->q28_pontuacao != "")
           $resac = db_query("insert into db_acount values($acount,1493,8753,'".AddSlashes(pg_result($resaco,$conresaco,'q28_pontuacao'))."','$this->q28_pontuacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pontuação da área nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q28_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pontuação da área nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q28_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q28_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q28_sequencia=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q28_sequencia));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8750,'$q28_sequencia','E')");
         $resac = db_query("insert into db_acount values($acount,1493,8750,'','".AddSlashes(pg_result($resaco,$iresaco,'q28_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1493,8751,'','".AddSlashes(pg_result($resaco,$iresaco,'q28_quantini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1493,8752,'','".AddSlashes(pg_result($resaco,$iresaco,'q28_quantfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1493,8753,'','".AddSlashes(pg_result($resaco,$iresaco,'q28_pontuacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from areapont
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q28_sequencia != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q28_sequencia = $q28_sequencia ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Pontuação da área nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q28_sequencia;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Pontuação da área nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q28_sequencia;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q28_sequencia;
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
        $this->erro_sql   = "Record Vazio na Tabela:areapont";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q28_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from areapont ";
     $sql2 = "";
     if($dbwhere==""){
       if($q28_sequencia!=null ){
         $sql2 .= " where areapont.q28_sequencia = $q28_sequencia "; 
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
   function sql_query_file ( $q28_sequencia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from areapont ";
     $sql2 = "";
     if($dbwhere==""){
       if($q28_sequencia!=null ){
         $sql2 .= " where areapont.q28_sequencia = $q28_sequencia "; 
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
  
  function sql_query_valida_qtde($q28_sequencia = null, $q28_quantini, $q28_quantfim) {
    
    $sql = "select count(*) as quantidade
              from areapont
             where ({$q28_quantini} between q28_quantini and q28_quantfim or 
                    {$q28_quantfim} between q28_quantini and q28_quantfim) ";
    
    if($q28_sequencia <> null) {
    	$sql .= "and q28_sequencia <> {$q28_sequencia}";
    }
                    
    return $sql;                        
    
  }
}
?>