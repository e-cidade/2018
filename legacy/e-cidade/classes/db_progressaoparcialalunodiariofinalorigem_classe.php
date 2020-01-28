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
//CLASSE DA ENTIDADE progressaoparcialalunodiariofinalorigem
class cl_progressaoparcialalunodiariofinalorigem { 
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
   var $ed107_sequencial = 0; 
   var $ed107_progressaoparcialaluno = 0; 
   var $ed107_diariofinal = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed107_sequencial = int4 = Código 
                 ed107_progressaoparcialaluno = int4 = Progressão Parcial 
                 ed107_diariofinal = int4 = Diário Final 
                 ";
   //funcao construtor da classe 
   function cl_progressaoparcialalunodiariofinalorigem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progressaoparcialalunodiariofinalorigem"); 
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
       $this->ed107_sequencial = ($this->ed107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed107_sequencial"]:$this->ed107_sequencial);
       $this->ed107_progressaoparcialaluno = ($this->ed107_progressaoparcialaluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed107_progressaoparcialaluno"]:$this->ed107_progressaoparcialaluno);
       $this->ed107_diariofinal = ($this->ed107_diariofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed107_diariofinal"]:$this->ed107_diariofinal);
     }else{
       $this->ed107_sequencial = ($this->ed107_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed107_sequencial"]:$this->ed107_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed107_sequencial){ 
      $this->atualizacampos();
     if($this->ed107_progressaoparcialaluno == null ){ 
       $this->erro_sql = " Campo Progressão Parcial não informado.";
       $this->erro_campo = "ed107_progressaoparcialaluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed107_diariofinal == null ){ 
       $this->erro_sql = " Campo Diário Final não informado.";
       $this->erro_campo = "ed107_diariofinal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed107_sequencial == "" || $ed107_sequencial == null ){
       $result = db_query("select nextval('progressaoparcialalunodiariofinalorigem_ed107_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progressaoparcialalunodiariofinalorigem_ed107_sequencial_seq do campo: ed107_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed107_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progressaoparcialalunodiariofinalorigem_ed107_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed107_sequencial)){
         $this->erro_sql = " Campo ed107_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed107_sequencial = $ed107_sequencial; 
       }
     }
     if(($this->ed107_sequencial == null) || ($this->ed107_sequencial == "") ){ 
       $this->erro_sql = " Campo ed107_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progressaoparcialalunodiariofinalorigem(
                                       ed107_sequencial 
                                      ,ed107_progressaoparcialaluno 
                                      ,ed107_diariofinal 
                       )
                values (
                                $this->ed107_sequencial 
                               ,$this->ed107_progressaoparcialaluno 
                               ,$this->ed107_diariofinal 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "progressaoparcialalunodiariofinalorigem ($this->ed107_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "progressaoparcialalunodiariofinalorigem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "progressaoparcialalunodiariofinalorigem ($this->ed107_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed107_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed107_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20387,'$this->ed107_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3663,20387,'','".AddSlashes(pg_result($resaco,0,'ed107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3663,20388,'','".AddSlashes(pg_result($resaco,0,'ed107_progressaoparcialaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3663,20389,'','".AddSlashes(pg_result($resaco,0,'ed107_diariofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed107_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update progressaoparcialalunodiariofinalorigem set ";
     $virgula = "";
     if(trim($this->ed107_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed107_sequencial"])){ 
       $sql  .= $virgula." ed107_sequencial = $this->ed107_sequencial ";
       $virgula = ",";
       if(trim($this->ed107_sequencial) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed107_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed107_progressaoparcialaluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed107_progressaoparcialaluno"])){ 
       $sql  .= $virgula." ed107_progressaoparcialaluno = $this->ed107_progressaoparcialaluno ";
       $virgula = ",";
       if(trim($this->ed107_progressaoparcialaluno) == null ){ 
         $this->erro_sql = " Campo Progressão Parcial não informado.";
         $this->erro_campo = "ed107_progressaoparcialaluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed107_diariofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed107_diariofinal"])){ 
       $sql  .= $virgula." ed107_diariofinal = $this->ed107_diariofinal ";
       $virgula = ",";
       if(trim($this->ed107_diariofinal) == null ){ 
         $this->erro_sql = " Campo Diário Final não informado.";
         $this->erro_campo = "ed107_diariofinal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed107_sequencial!=null){
       $sql .= " ed107_sequencial = $this->ed107_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed107_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20387,'$this->ed107_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed107_sequencial"]) || $this->ed107_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3663,20387,'".AddSlashes(pg_result($resaco,$conresaco,'ed107_sequencial'))."','$this->ed107_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed107_progressaoparcialaluno"]) || $this->ed107_progressaoparcialaluno != "")
             $resac = db_query("insert into db_acount values($acount,3663,20388,'".AddSlashes(pg_result($resaco,$conresaco,'ed107_progressaoparcialaluno'))."','$this->ed107_progressaoparcialaluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed107_diariofinal"]) || $this->ed107_diariofinal != "")
             $resac = db_query("insert into db_acount values($acount,3663,20389,'".AddSlashes(pg_result($resaco,$conresaco,'ed107_diariofinal'))."','$this->ed107_diariofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "progressaoparcialalunodiariofinalorigem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed107_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "progressaoparcialalunodiariofinalorigem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed107_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed107_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20387,'$ed107_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3663,20387,'','".AddSlashes(pg_result($resaco,$iresaco,'ed107_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3663,20388,'','".AddSlashes(pg_result($resaco,$iresaco,'ed107_progressaoparcialaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3663,20389,'','".AddSlashes(pg_result($resaco,$iresaco,'ed107_diariofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from progressaoparcialalunodiariofinalorigem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed107_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed107_sequencial = $ed107_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "progressaoparcialalunodiariofinalorigem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed107_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "progressaoparcialalunodiariofinalorigem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed107_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed107_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:progressaoparcialalunodiariofinalorigem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed107_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progressaoparcialalunodiariofinalorigem ";
     $sql .= "      inner join progressaoparcialaluno  on  progressaoparcialaluno.ed114_sequencial = progressaoparcialalunodiariofinalorigem.ed107_progressaoparcialaluno";
     $sql .= "      inner join diariofinal  on  diariofinal.ed74_i_codigo = progressaoparcialalunodiariofinalorigem.ed107_diariofinal";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = progressaoparcialaluno.ed114_disciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = progressaoparcialaluno.ed114_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = progressaoparcialaluno.ed114_aluno";
     $sql .= "      left  join procresultado  on  procresultado.ed43_i_codigo = diariofinal.ed74_i_procresultadoaprov and  procresultado.ed43_i_codigo = diariofinal.ed74_i_procresultadofreq";
     $sql .= "      inner join diario  as a on   a.ed95_i_codigo = diariofinal.ed74_i_diario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed107_sequencial!=null ){
         $sql2 .= " where progressaoparcialalunodiariofinalorigem.ed107_sequencial = $ed107_sequencial "; 
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
   function sql_query_file ( $ed107_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progressaoparcialalunodiariofinalorigem ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed107_sequencial!=null ){
         $sql2 .= " where progressaoparcialalunodiariofinalorigem.ed107_sequencial = $ed107_sequencial "; 
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