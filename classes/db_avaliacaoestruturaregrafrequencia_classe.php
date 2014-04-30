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

//MODULO: escola
//CLASSE DA ENTIDADE avaliacaoestruturaregrafrequencia
class cl_avaliacaoestruturaregrafrequencia { 
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
   var $ed329_sequencial = 0; 
   var $ed329_avaliacaoestruturafrequencia = 0; 
   var $ed329_regraarredondamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed329_sequencial = int4 = Código Regra Frequência 
                 ed329_avaliacaoestruturafrequencia = int4 = Avaliação Estrutura Frequência 
                 ed329_regraarredondamento = int4 = Regra de Arredondamento 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaoestruturaregrafrequencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaoestruturaregrafrequencia"); 
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
       $this->ed329_sequencial = ($this->ed329_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed329_sequencial"]:$this->ed329_sequencial);
       $this->ed329_avaliacaoestruturafrequencia = ($this->ed329_avaliacaoestruturafrequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed329_avaliacaoestruturafrequencia"]:$this->ed329_avaliacaoestruturafrequencia);
       $this->ed329_regraarredondamento = ($this->ed329_regraarredondamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ed329_regraarredondamento"]:$this->ed329_regraarredondamento);
     }else{
       $this->ed329_sequencial = ($this->ed329_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed329_sequencial"]:$this->ed329_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed329_sequencial){ 
      $this->atualizacampos();
     if($this->ed329_avaliacaoestruturafrequencia == null ){ 
       $this->erro_sql = " Campo Avaliação Estrutura Frequência nao Informado.";
       $this->erro_campo = "ed329_avaliacaoestruturafrequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed329_regraarredondamento == null ){ 
       $this->erro_sql = " Campo Regra de Arredondamento nao Informado.";
       $this->erro_campo = "ed329_regraarredondamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed329_sequencial == "" || $ed329_sequencial == null ){
       $result = db_query("select nextval('avaliacaoestruturaregrafrequencia_ed329_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaoestruturaregrafrequencia_ed329_sequencial_seq do campo: ed329_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed329_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaoestruturaregrafrequencia_ed329_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed329_sequencial)){
         $this->erro_sql = " Campo ed329_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed329_sequencial = $ed329_sequencial; 
       }
     }
     if(($this->ed329_sequencial == null) || ($this->ed329_sequencial == "") ){ 
       $this->erro_sql = " Campo ed329_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaoestruturaregrafrequencia(
                                       ed329_sequencial 
                                      ,ed329_avaliacaoestruturafrequencia 
                                      ,ed329_regraarredondamento 
                       )
                values (
                                $this->ed329_sequencial 
                               ,$this->ed329_avaliacaoestruturafrequencia 
                               ,$this->ed329_regraarredondamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação Estrutura Regra Frequência ($this->ed329_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação Estrutura Regra Frequência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação Estrutura Regra Frequência ($this->ed329_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed329_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed329_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19988,'$this->ed329_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3583,19988,'','".AddSlashes(pg_result($resaco,0,'ed329_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3583,19989,'','".AddSlashes(pg_result($resaco,0,'ed329_avaliacaoestruturafrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3583,19990,'','".AddSlashes(pg_result($resaco,0,'ed329_regraarredondamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed329_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaoestruturaregrafrequencia set ";
     $virgula = "";
     if(trim($this->ed329_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed329_sequencial"])){ 
       $sql  .= $virgula." ed329_sequencial = $this->ed329_sequencial ";
       $virgula = ",";
       if(trim($this->ed329_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Regra Frequência nao Informado.";
         $this->erro_campo = "ed329_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed329_avaliacaoestruturafrequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed329_avaliacaoestruturafrequencia"])){ 
       $sql  .= $virgula." ed329_avaliacaoestruturafrequencia = $this->ed329_avaliacaoestruturafrequencia ";
       $virgula = ",";
       if(trim($this->ed329_avaliacaoestruturafrequencia) == null ){ 
         $this->erro_sql = " Campo Avaliação Estrutura Frequência nao Informado.";
         $this->erro_campo = "ed329_avaliacaoestruturafrequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed329_regraarredondamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed329_regraarredondamento"])){ 
       $sql  .= $virgula." ed329_regraarredondamento = $this->ed329_regraarredondamento ";
       $virgula = ",";
       if(trim($this->ed329_regraarredondamento) == null ){ 
         $this->erro_sql = " Campo Regra de Arredondamento nao Informado.";
         $this->erro_campo = "ed329_regraarredondamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed329_sequencial!=null){
       $sql .= " ed329_sequencial = $this->ed329_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed329_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19988,'$this->ed329_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed329_sequencial"]) || $this->ed329_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3583,19988,'".AddSlashes(pg_result($resaco,$conresaco,'ed329_sequencial'))."','$this->ed329_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed329_avaliacaoestruturafrequencia"]) || $this->ed329_avaliacaoestruturafrequencia != "")
             $resac = db_query("insert into db_acount values($acount,3583,19989,'".AddSlashes(pg_result($resaco,$conresaco,'ed329_avaliacaoestruturafrequencia'))."','$this->ed329_avaliacaoestruturafrequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed329_regraarredondamento"]) || $this->ed329_regraarredondamento != "")
             $resac = db_query("insert into db_acount values($acount,3583,19990,'".AddSlashes(pg_result($resaco,$conresaco,'ed329_regraarredondamento'))."','$this->ed329_regraarredondamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Estrutura Regra Frequência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed329_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Estrutura Regra Frequência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed329_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed329_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed329_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed329_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19988,'$ed329_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3583,19988,'','".AddSlashes(pg_result($resaco,$iresaco,'ed329_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3583,19989,'','".AddSlashes(pg_result($resaco,$iresaco,'ed329_avaliacaoestruturafrequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3583,19990,'','".AddSlashes(pg_result($resaco,$iresaco,'ed329_regraarredondamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaoestruturaregrafrequencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed329_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed329_sequencial = $ed329_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Estrutura Regra Frequência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed329_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Estrutura Regra Frequência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed329_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed329_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaoestruturaregrafrequencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed329_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoestruturaregrafrequencia ";
     $sql .= "      inner join regraarredondamento  on  regraarredondamento.ed316_sequencial = avaliacaoestruturaregrafrequencia.ed329_regraarredondamento";
     $sql .= "      inner join avaliacaoestruturafrequencia  on  avaliacaoestruturafrequencia.ed328_sequencial = avaliacaoestruturaregrafrequencia.ed329_avaliacaoestruturafrequencia";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = avaliacaoestruturafrequencia.ed328_db_estrutura";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = avaliacaoestruturafrequencia.ed328_escola";
     $sql2 = "";
     if($dbwhere==""){
       if($ed329_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturaregrafrequencia.ed329_sequencial = $ed329_sequencial "; 
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
   function sql_query_file ( $ed329_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaoestruturaregrafrequencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed329_sequencial!=null ){
         $sql2 .= " where avaliacaoestruturaregrafrequencia.ed329_sequencial = $ed329_sequencial "; 
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