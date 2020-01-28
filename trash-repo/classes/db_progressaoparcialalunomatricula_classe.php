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
//CLASSE DA ENTIDADE progressaoparcialalunomatricula
class cl_progressaoparcialalunomatricula { 
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
   var $ed150_sequencial = 0; 
   var $ed150_progressaoparcialaluno = 0; 
   var $ed150_ano = 0; 
   var $ed150_encerrado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed150_sequencial = int4 = Matricula Progress�o 
                 ed150_progressaoparcialaluno = int4 = Codig� da Progress�o 
                 ed150_ano = int4 = Ano 
                 ed150_encerrado = bool = Encerrado 
                 ";
   //funcao construtor da classe 
   function cl_progressaoparcialalunomatricula() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progressaoparcialalunomatricula"); 
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
       $this->ed150_sequencial = ($this->ed150_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed150_sequencial"]:$this->ed150_sequencial);
       $this->ed150_progressaoparcialaluno = ($this->ed150_progressaoparcialaluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed150_progressaoparcialaluno"]:$this->ed150_progressaoparcialaluno);
       $this->ed150_ano = ($this->ed150_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed150_ano"]:$this->ed150_ano);
       $this->ed150_encerrado = ($this->ed150_encerrado == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed150_encerrado"]:$this->ed150_encerrado);
     }else{
       $this->ed150_sequencial = ($this->ed150_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed150_sequencial"]:$this->ed150_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed150_sequencial){ 
      $this->atualizacampos();
     if($this->ed150_progressaoparcialaluno == null ){ 
       $this->erro_sql = " Campo Codig� da Progress�o n�o informado.";
       $this->erro_campo = "ed150_progressaoparcialaluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed150_ano == null ){ 
       $this->erro_sql = " Campo Ano n�o informado.";
       $this->erro_campo = "ed150_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed150_encerrado == null ){ 
       $this->ed150_encerrado = "true";
     }
     if($ed150_sequencial == "" || $ed150_sequencial == null ){
       $result = db_query("select nextval('progressaoparcialalunomatricula_ed150_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progressaoparcialalunomatricula_ed150_sequencial_seq do campo: ed150_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed150_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progressaoparcialalunomatricula_ed150_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed150_sequencial)){
         $this->erro_sql = " Campo ed150_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed150_sequencial = $ed150_sequencial; 
       }
     }
     if(($this->ed150_sequencial == null) || ($this->ed150_sequencial == "") ){ 
       $this->erro_sql = " Campo ed150_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progressaoparcialalunomatricula(
                                       ed150_sequencial 
                                      ,ed150_progressaoparcialaluno 
                                      ,ed150_ano 
                                      ,ed150_encerrado 
                       )
                values (
                                $this->ed150_sequencial 
                               ,$this->ed150_progressaoparcialaluno 
                               ,$this->ed150_ano 
                               ,'$this->ed150_encerrado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matricula da Progress�o Parcial ($this->ed150_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matricula da Progress�o Parcial j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matricula da Progress�o Parcial ($this->ed150_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed150_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed150_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19679,'$this->ed150_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3496,19679,'','".AddSlashes(pg_result($resaco,0,'ed150_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3496,19680,'','".AddSlashes(pg_result($resaco,0,'ed150_progressaoparcialaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3496,19678,'','".AddSlashes(pg_result($resaco,0,'ed150_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3496,19677,'','".AddSlashes(pg_result($resaco,0,'ed150_encerrado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed150_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update progressaoparcialalunomatricula set ";
     $virgula = "";
     if(trim($this->ed150_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed150_sequencial"])){ 
       $sql  .= $virgula." ed150_sequencial = $this->ed150_sequencial ";
       $virgula = ",";
       if(trim($this->ed150_sequencial) == null ){ 
         $this->erro_sql = " Campo Matricula Progress�o n�o informado.";
         $this->erro_campo = "ed150_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed150_progressaoparcialaluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed150_progressaoparcialaluno"])){ 
       $sql  .= $virgula." ed150_progressaoparcialaluno = $this->ed150_progressaoparcialaluno ";
       $virgula = ",";
       if(trim($this->ed150_progressaoparcialaluno) == null ){ 
         $this->erro_sql = " Campo Codig� da Progress�o n�o informado.";
         $this->erro_campo = "ed150_progressaoparcialaluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed150_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed150_ano"])){ 
       $sql  .= $virgula." ed150_ano = $this->ed150_ano ";
       $virgula = ",";
       if(trim($this->ed150_ano) == null ){ 
         $this->erro_sql = " Campo Ano n�o informado.";
         $this->erro_campo = "ed150_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed150_encerrado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed150_encerrado"])){ 
       $sql  .= $virgula." ed150_encerrado = '$this->ed150_encerrado' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed150_sequencial!=null){
       $sql .= " ed150_sequencial = $this->ed150_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed150_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,19679,'$this->ed150_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed150_sequencial"]) || $this->ed150_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3496,19679,'".AddSlashes(pg_result($resaco,$conresaco,'ed150_sequencial'))."','$this->ed150_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed150_progressaoparcialaluno"]) || $this->ed150_progressaoparcialaluno != "")
             $resac = db_query("insert into db_acount values($acount,3496,19680,'".AddSlashes(pg_result($resaco,$conresaco,'ed150_progressaoparcialaluno'))."','$this->ed150_progressaoparcialaluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed150_ano"]) || $this->ed150_ano != "")
             $resac = db_query("insert into db_acount values($acount,3496,19678,'".AddSlashes(pg_result($resaco,$conresaco,'ed150_ano'))."','$this->ed150_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed150_encerrado"]) || $this->ed150_encerrado != "")
             $resac = db_query("insert into db_acount values($acount,3496,19677,'".AddSlashes(pg_result($resaco,$conresaco,'ed150_encerrado'))."','$this->ed150_encerrado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matricula da Progress�o Parcial nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed150_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matricula da Progress�o Parcial nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed150_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed150_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed150_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed150_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,19679,'$ed150_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3496,19679,'','".AddSlashes(pg_result($resaco,$iresaco,'ed150_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3496,19680,'','".AddSlashes(pg_result($resaco,$iresaco,'ed150_progressaoparcialaluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3496,19678,'','".AddSlashes(pg_result($resaco,$iresaco,'ed150_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3496,19677,'','".AddSlashes(pg_result($resaco,$iresaco,'ed150_encerrado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from progressaoparcialalunomatricula
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed150_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed150_sequencial = $ed150_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matricula da Progress�o Parcial nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed150_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matricula da Progress�o Parcial nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed150_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed150_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:progressaoparcialalunomatricula";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed150_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progressaoparcialalunomatricula ";
     $sql .= "      inner join progressaoparcialaluno  on  progressaoparcialaluno.ed114_sequencial = progressaoparcialalunomatricula.ed150_progressaoparcialaluno";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = progressaoparcialaluno.ed114_disciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = progressaoparcialaluno.ed114_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = progressaoparcialaluno.ed114_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed150_sequencial!=null ){
         $sql2 .= " where progressaoparcialalunomatricula.ed150_sequencial = $ed150_sequencial "; 
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
   function sql_query_file ( $ed150_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progressaoparcialalunomatricula ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed150_sequencial!=null ){
         $sql2 .= " where progressaoparcialalunomatricula.ed150_sequencial = $ed150_sequencial "; 
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