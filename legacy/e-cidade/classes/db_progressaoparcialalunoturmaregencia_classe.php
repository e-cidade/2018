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
//CLASSE DA ENTIDADE progressaoparcialalunoturmaregencia
class cl_progressaoparcialalunoturmaregencia {
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
   var $ed115_sequencial = 0;
   var $ed115_progressaoparcialalunomatricula = 0;
   var $ed115_regencia = 0;
   var $ed115_datavinculo_dia = null;
   var $ed115_datavinculo_mes = null;
   var $ed115_datavinculo_ano = null;
   var $ed115_datavinculo = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed115_sequencial = int4 = Código
                 ed115_progressaoparcialalunomatricula = int4 = Matricula Progressão Parcial
                 ed115_regencia = int4 = Regência
                 ed115_datavinculo = date = Data do Vínculo
                 ";
   //funcao construtor da classe
   function cl_progressaoparcialalunoturmaregencia() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progressaoparcialalunoturmaregencia");
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
       $this->ed115_sequencial = ($this->ed115_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed115_sequencial"]:$this->ed115_sequencial);
       $this->ed115_progressaoparcialalunomatricula = ($this->ed115_progressaoparcialalunomatricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed115_progressaoparcialalunomatricula"]:$this->ed115_progressaoparcialalunomatricula);
       $this->ed115_regencia = ($this->ed115_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed115_regencia"]:$this->ed115_regencia);
       if($this->ed115_datavinculo == ""){
         $this->ed115_datavinculo_dia = ($this->ed115_datavinculo_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed115_datavinculo_dia"]:$this->ed115_datavinculo_dia);
         $this->ed115_datavinculo_mes = ($this->ed115_datavinculo_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ed115_datavinculo_mes"]:$this->ed115_datavinculo_mes);
         $this->ed115_datavinculo_ano = ($this->ed115_datavinculo_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed115_datavinculo_ano"]:$this->ed115_datavinculo_ano);
         if($this->ed115_datavinculo_dia != ""){
            $this->ed115_datavinculo = $this->ed115_datavinculo_ano."-".$this->ed115_datavinculo_mes."-".$this->ed115_datavinculo_dia;
         }
       }
     }else{
       $this->ed115_sequencial = ($this->ed115_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed115_sequencial"]:$this->ed115_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed115_sequencial){
      $this->atualizacampos();
     if($this->ed115_progressaoparcialalunomatricula == null ){
       $this->erro_sql = " Campo Matricula Progressão Parcial nao Informado.";
       $this->erro_campo = "ed115_progressaoparcialalunomatricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed115_regencia == null ){
       $this->erro_sql = " Campo Regência nao Informado.";
       $this->erro_campo = "ed115_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed115_datavinculo == null ){
       $this->erro_sql = " Campo Data do Vínculo nao Informado.";
       $this->erro_campo = "ed115_datavinculo_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed115_sequencial == "" || $ed115_sequencial == null ){
       $result = db_query("select nextval('progressaoparcialalunoturmaregencia_ed115_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progressaoparcialalunoturmaregencia_ed115_sequencial_seq do campo: ed115_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed115_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from progressaoparcialalunoturmaregencia_ed115_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed115_sequencial)){
         $this->erro_sql = " Campo ed115_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed115_sequencial = $ed115_sequencial;
       }
     }
     if(($this->ed115_sequencial == null) || ($this->ed115_sequencial == "") ){
       $this->erro_sql = " Campo ed115_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progressaoparcialalunoturmaregencia(
                                       ed115_sequencial
                                      ,ed115_progressaoparcialalunomatricula
                                      ,ed115_regencia
                                      ,ed115_datavinculo
                       )
                values (
                                $this->ed115_sequencial
                               ,$this->ed115_progressaoparcialalunomatricula
                               ,$this->ed115_regencia
                               ,".($this->ed115_datavinculo == "null" || $this->ed115_datavinculo == ""?"null":"'".$this->ed115_datavinculo."'")."
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Vinculo do aluno com progressão parcial uma turma ($this->ed115_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Vinculo do aluno com progressão parcial uma turma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Vinculo do aluno com progressão parcial uma turma ($this->ed115_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed115_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed115_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19542,'$this->ed115_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3473,19542,'','".AddSlashes(pg_result($resaco,0,'ed115_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3473,19543,'','".AddSlashes(pg_result($resaco,0,'ed115_progressaoparcialalunomatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3473,19544,'','".AddSlashes(pg_result($resaco,0,'ed115_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3473,19545,'','".AddSlashes(pg_result($resaco,0,'ed115_datavinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed115_sequencial=null) {
      $this->atualizacampos();
     $sql = " update progressaoparcialalunoturmaregencia set ";
     $virgula = "";
     if(trim($this->ed115_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed115_sequencial"])){
       $sql  .= $virgula." ed115_sequencial = $this->ed115_sequencial ";
       $virgula = ",";
       if(trim($this->ed115_sequencial) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed115_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed115_progressaoparcialalunomatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed115_progressaoparcialalunomatricula"])){
       $sql  .= $virgula." ed115_progressaoparcialalunomatricula = $this->ed115_progressaoparcialalunomatricula ";
       $virgula = ",";
       if(trim($this->ed115_progressaoparcialalunomatricula) == null ){
         $this->erro_sql = " Campo Matricula Progressão Parcial nao Informado.";
         $this->erro_campo = "ed115_progressaoparcialalunomatricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed115_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed115_regencia"])){
       $sql  .= $virgula." ed115_regencia = $this->ed115_regencia ";
       $virgula = ",";
       if(trim($this->ed115_regencia) == null ){
         $this->erro_sql = " Campo Regência nao Informado.";
         $this->erro_campo = "ed115_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed115_datavinculo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed115_datavinculo_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ed115_datavinculo_dia"] !="") ){
       $sql  .= $virgula." ed115_datavinculo = '$this->ed115_datavinculo' ";
       $virgula = ",";
       if(trim($this->ed115_datavinculo) == null ){
         $this->erro_sql = " Campo Data do Vínculo nao Informado.";
         $this->erro_campo = "ed115_datavinculo_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{
       if(isset($GLOBALS["HTTP_POST_VARS"]["ed115_datavinculo_dia"])){
         $sql  .= $virgula." ed115_datavinculo = null ";
         $virgula = ",";
         if(trim($this->ed115_datavinculo) == null ){
           $this->erro_sql = " Campo Data do Vínculo nao Informado.";
           $this->erro_campo = "ed115_datavinculo_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($ed115_sequencial!=null){
       $sql .= " ed115_sequencial = $this->ed115_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed115_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19542,'$this->ed115_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed115_sequencial"]) || $this->ed115_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3473,19542,'".AddSlashes(pg_result($resaco,$conresaco,'ed115_sequencial'))."','$this->ed115_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed115_progressaoparcialalunomatricula"]) || $this->ed115_progressaoparcialalunomatricula != "")
           $resac = db_query("insert into db_acount values($acount,3473,19543,'".AddSlashes(pg_result($resaco,$conresaco,'ed115_progressaoparcialalunomatricula'))."','$this->ed115_progressaoparcialalunomatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed115_regencia"]) || $this->ed115_regencia != "")
           $resac = db_query("insert into db_acount values($acount,3473,19544,'".AddSlashes(pg_result($resaco,$conresaco,'ed115_regencia'))."','$this->ed115_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed115_datavinculo"]) || $this->ed115_datavinculo != "")
           $resac = db_query("insert into db_acount values($acount,3473,19545,'".AddSlashes(pg_result($resaco,$conresaco,'ed115_datavinculo'))."','$this->ed115_datavinculo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo do aluno com progressão parcial uma turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed115_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo do aluno com progressão parcial uma turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed115_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed115_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed115_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed115_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19542,'$ed115_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3473,19542,'','".AddSlashes(pg_result($resaco,$iresaco,'ed115_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3473,19543,'','".AddSlashes(pg_result($resaco,$iresaco,'ed115_progressaoparcialalunomatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3473,19544,'','".AddSlashes(pg_result($resaco,$iresaco,'ed115_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3473,19545,'','".AddSlashes(pg_result($resaco,$iresaco,'ed115_datavinculo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progressaoparcialalunoturmaregencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed115_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed115_sequencial = $ed115_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Vinculo do aluno com progressão parcial uma turma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed115_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Vinculo do aluno com progressão parcial uma turma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed115_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed115_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:progressaoparcialalunoturmaregencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed115_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from progressaoparcialalunoturmaregencia ";
     $sql .= "      inner join progressaoparcialalunomatricula  on  progressaoparcialalunomatricula.ed150_sequencial = progressaoparcialalunoturmaregencia.ed115_progressaoparcialalunomatricula";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = progressaoparcialalunoturmaregencia.ed115_regencia";
     $sql .= "      inner join progressaoparcialaluno  on  progressaoparcialaluno.ed114_sequencial = progressaoparcialalunomatricula.ed150_progressaoparcialaluno";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = regencia.ed59_i_serie";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
     $sql2 = "";
     if($dbwhere==""){
       if($ed115_sequencial!=null ){
         $sql2 .= " where progressaoparcialalunoturmaregencia.ed115_sequencial = $ed115_sequencial ";
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
   function sql_query_file ( $ed115_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from progressaoparcialalunoturmaregencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed115_sequencial!=null ){
         $sql2 .= " where progressaoparcialalunoturmaregencia.ed115_sequencial = $ed115_sequencial ";
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

  function sql_query_matricula ( $ed115_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from progressaoparcialalunoturmaregencia ";
    $sql .= "      inner join progressaoparcialalunomatricula  on  progressaoparcialalunomatricula.ed150_sequencial = progressaoparcialalunoturmaregencia.ed115_progressaoparcialalunomatricula";
    $sql2 = "";
    if($dbwhere==""){
      if($ed115_sequencial!=null ){
        $sql2 .= " where progressaoparcialalunoturmaregencia.ed115_sequencial = $ed115_sequencial ";
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

  function sql_query_aluno ( $ed115_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from progressaoparcialalunoturmaregencia ";
    $sql .= "      inner join progressaoparcialalunomatricula         on ed150_sequencial             = ed115_progressaoparcialalunomatricula";
    $sql .= "      inner join regencia                                on regencia.ed59_i_codigo       = ed115_regencia";
    $sql .= "      inner join progressaoparcialaluno                  on ed114_sequencial             = ed150_progressaoparcialaluno";
    $sql .= "      inner join disciplina                              on disciplina.ed12_i_codigo     = regencia.ed59_i_disciplina";
    $sql .= "      inner join serie                                   on serie.ed11_i_codigo          = regencia.ed59_i_serie";
    $sql .= "      inner join turma                                   on turma.ed57_i_codigo          = regencia.ed59_i_turma";
    $sql .= "      inner join aluno                                   on aluno.ed47_i_codigo          = ed114_aluno";
    $sql .= "      left  join progressaoparcialalunodiariofinalorigem on ed107_progressaoparcialaluno = ed114_sequencial";
    $sql .= "      left  join diariofinal                             on ed74_i_codigo                = ed107_diariofinal";
    $sql2 = "";
    if($dbwhere==""){
      if($ed115_sequencial!=null ){
        $sql2 .= " where progressaoparcialalunoturmaregencia.ed115_sequencial = $ed115_sequencial ";
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