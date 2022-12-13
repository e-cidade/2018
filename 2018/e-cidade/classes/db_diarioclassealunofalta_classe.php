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
//CLASSE DA ENTIDADE diarioclassealunofalta
class cl_diarioclassealunofalta {
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
   var $ed301_sequencial = 0;
   var $ed301_aluno = 0;
   var $ed301_diarioclasseregenciahorario = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed301_sequencial = int4 = Sequencial interno
                 ed301_aluno = int8 = Código do aluno
                 ed301_diarioclasseregenciahorario = int4 = Periodo da Falta
                 ";
   //funcao construtor da classe
   function cl_diarioclassealunofalta() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diarioclassealunofalta");
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
       $this->ed301_sequencial = ($this->ed301_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed301_sequencial"]:$this->ed301_sequencial);
       $this->ed301_aluno = ($this->ed301_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed301_aluno"]:$this->ed301_aluno);
       $this->ed301_diarioclasseregenciahorario = ($this->ed301_diarioclasseregenciahorario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed301_diarioclasseregenciahorario"]:$this->ed301_diarioclasseregenciahorario);
     }else{
       $this->ed301_sequencial = ($this->ed301_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed301_sequencial"]:$this->ed301_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed301_sequencial){
      $this->atualizacampos();
     if($this->ed301_aluno == null ){
       $this->erro_sql = " Campo Código do aluno nao Informado.";
       $this->erro_campo = "ed301_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed301_diarioclasseregenciahorario == null ){
       $this->erro_sql = " Campo Periodo da Falta nao Informado.";
       $this->erro_campo = "ed301_diarioclasseregenciahorario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed301_sequencial == "" || $ed301_sequencial == null ){
       $result = db_query("select nextval('diarioclassealunofalta_ed301_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diarioclassealunofalta_ed301_sequencial_seq do campo: ed301_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed301_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from diarioclassealunofalta_ed301_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed301_sequencial)){
         $this->erro_sql = " Campo ed301_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed301_sequencial = $ed301_sequencial;
       }
     }
     if(($this->ed301_sequencial == null) || ($this->ed301_sequencial == "") ){
       $this->erro_sql = " Campo ed301_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diarioclassealunofalta(
                                       ed301_sequencial
                                      ,ed301_aluno
                                      ,ed301_diarioclasseregenciahorario
                       )
                values (
                                $this->ed301_sequencial
                               ,$this->ed301_aluno
                               ,$this->ed301_diarioclasseregenciahorario
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Faltas dos alunos ($this->ed301_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Faltas dos alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Faltas dos alunos ($this->ed301_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed301_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed301_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18770,'$this->ed301_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3328,18770,'','".AddSlashes(pg_result($resaco,0,'ed301_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3328,18771,'','".AddSlashes(pg_result($resaco,0,'ed301_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3328,18800,'','".AddSlashes(pg_result($resaco,0,'ed301_diarioclasseregenciahorario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed301_sequencial=null) {
      $this->atualizacampos();
     $sql = " update diarioclassealunofalta set ";
     $virgula = "";
     if(trim($this->ed301_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed301_sequencial"])){
       $sql  .= $virgula." ed301_sequencial = $this->ed301_sequencial ";
       $virgula = ",";
       if(trim($this->ed301_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial interno nao Informado.";
         $this->erro_campo = "ed301_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed301_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed301_aluno"])){
       $sql  .= $virgula." ed301_aluno = $this->ed301_aluno ";
       $virgula = ",";
       if(trim($this->ed301_aluno) == null ){
         $this->erro_sql = " Campo Código do aluno nao Informado.";
         $this->erro_campo = "ed301_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed301_diarioclasseregenciahorario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed301_diarioclasseregenciahorario"])){
       $sql  .= $virgula." ed301_diarioclasseregenciahorario = $this->ed301_diarioclasseregenciahorario ";
       $virgula = ",";
       if(trim($this->ed301_diarioclasseregenciahorario) == null ){
         $this->erro_sql = " Campo Periodo da Falta nao Informado.";
         $this->erro_campo = "ed301_diarioclasseregenciahorario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed301_sequencial!=null){
       $sql .= " ed301_sequencial = $this->ed301_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed301_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18770,'$this->ed301_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed301_sequencial"]) || $this->ed301_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3328,18770,'".AddSlashes(pg_result($resaco,$conresaco,'ed301_sequencial'))."','$this->ed301_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed301_aluno"]) || $this->ed301_aluno != "")
           $resac = db_query("insert into db_acount values($acount,3328,18771,'".AddSlashes(pg_result($resaco,$conresaco,'ed301_aluno'))."','$this->ed301_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed301_diarioclasseregenciahorario"]) || $this->ed301_diarioclasseregenciahorario != "")
           $resac = db_query("insert into db_acount values($acount,3328,18800,'".AddSlashes(pg_result($resaco,$conresaco,'ed301_diarioclasseregenciahorario'))."','$this->ed301_diarioclasseregenciahorario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faltas dos alunos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed301_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Faltas dos alunos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed301_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed301_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed301_sequencial=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed301_sequencial));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18770,'$ed301_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3328,18770,'','".AddSlashes(pg_result($resaco,$iresaco,'ed301_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3328,18771,'','".AddSlashes(pg_result($resaco,$iresaco,'ed301_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3328,18800,'','".AddSlashes(pg_result($resaco,$iresaco,'ed301_diarioclasseregenciahorario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from diarioclassealunofalta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed301_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed301_sequencial = $ed301_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Faltas dos alunos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed301_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Faltas dos alunos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed301_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed301_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:diarioclassealunofalta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed301_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diarioclassealunofalta ";
     $sql .= "      inner join diarioclasseregenciahorario  on  diarioclasseregenciahorario.ed302_sequencial = diarioclassealunofalta.ed301_diarioclasseregenciahorario";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diarioclassealunofalta.ed301_aluno";
     $sql .= "      inner join diarioclasse  on  diarioclasse.ed300_sequencial = diarioclasseregenciahorario.ed302_diarioclasse";
     $sql .= "      inner join regenciahorario  on  regenciahorario.ed58_i_codigo = diarioclasseregenciahorario.ed302_regenciahorario";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join aluno  as a on   a.ed47_i_codigo = aluno.ed47_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed301_sequencial!=null ){
         $sql2 .= " where diarioclassealunofalta.ed301_sequencial = $ed301_sequencial ";
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
   function sql_query_file ( $ed301_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diarioclassealunofalta ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed301_sequencial!=null ){
         $sql2 .= " where diarioclassealunofalta.ed301_sequencial = $ed301_sequencial ";
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
  
  function sql_query_aluno_falta ( $ed301_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){

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
     $sql .= " from diarioclassealunofalta ";
     $sql .= "      inner join diarioclasseregenciahorario  on  diarioclasseregenciahorario.ed302_sequencial = diarioclassealunofalta.ed301_diarioclasseregenciahorario";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diarioclassealunofalta.ed301_aluno";
     $sql .= "      inner join diarioclasse  on  diarioclasse.ed300_sequencial = diarioclasseregenciahorario.ed302_diarioclasse";
     $sql .= "      inner join regenciahorario  on  regenciahorario.ed58_i_codigo = diarioclasseregenciahorario.ed302_regenciahorario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed301_sequencial!=null ){
         $sql2 .= " where diarioclassealunofalta.ed301_sequencial = $ed301_sequencial ";
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
  
  function sql_query_falta_regencia ($ed301_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from diarioclassealunofalta ";
     $sql .= "      inner join diarioclasseregenciahorario  on  diarioclasseregenciahorario.ed302_sequencial = diarioclassealunofalta.ed301_diarioclasseregenciahorario";
     $sql .= "      inner join diarioclasse  on  diarioclasse.ed300_sequencial = diarioclasseregenciahorario.ed302_diarioclasse";
     $sql .= "      inner join regenciahorario  on  regenciahorario.ed58_i_codigo = diarioclasseregenciahorario.ed302_regenciahorario";
     $sql .= "      inner join regencia  on  regenciahorario.ed58_i_regencia      = regencia.ed59_i_codigo";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo    = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
     $sql2 = "";
     if($dbwhere==""){
       if($ed301_sequencial!=null ){
         $sql2 .= " where diarioclassealunofalta.ed301_sequencial = $ed301_sequencial ";
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
  
  
  function sql_query_falta_notificada ($ed301_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from diarioclassealunofalta ";
     $sql .= "      inner join diarioclasseregenciahorario  on  diarioclasseregenciahorario.ed302_sequencial = diarioclassealunofalta.ed301_diarioclasseregenciahorario";
     $sql .= "      inner join diarioclasse  on  diarioclasse.ed300_sequencial    = diarioclasseregenciahorario.ed302_diarioclasse";
     $sql .= "      inner join regenciahorario  on  regenciahorario.ed58_i_codigo = diarioclasseregenciahorario.ed302_regenciahorario";
     $sql .= "      inner join regencia  on  regenciahorario.ed58_i_regencia      = regencia.ed59_i_codigo";
     $sql .= "      left join ocorrenciafalta        on ed104_diarioclassealunofalta     = ed301_sequencial";
     $sql .= "      left join ocorrencia             on ed104_ocorrencia                 = ed103_sequencial";
     $sql .= "      left join ocorrencianotificacao  on ed105_ocorrencia                 = ed103_sequencial";
     $sql .= "      left join mensagemnotificacao    on  ed105_mensagemnotificacao       = db134_sequencial ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed301_sequencial!=null ){
         $sql2 .= " where diarioclassealunofalta.ed301_sequencial = $ed301_sequencial ";
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
  
  function sql_query_falta_matricula ($ed301_sequencial=null,$campos="*",$ordem=null,$dbwhere="") {
  
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
    $sql .= " from diarioclassealunofalta ";
    $sql .= "      inner join diarioclasseregenciahorario  on  diarioclasseregenciahorario.ed302_sequencial = diarioclassealunofalta.ed301_diarioclasseregenciahorario";
    $sql .= "      inner join diarioclasse  on  diarioclasse.ed300_sequencial = diarioclasseregenciahorario.ed302_diarioclasse";
    $sql .= "      inner join regenciahorario  on  regenciahorario.ed58_i_codigo = diarioclasseregenciahorario.ed302_regenciahorario";
    $sql .= "      inner join regencia         on  regenciahorario.ed58_i_regencia      = regencia.ed59_i_codigo";
    $sql .= "      inner join turma            on  regencia.ed59_i_turma                = turma.ed57_i_codigo";
    $sql .= "      inner join matricula        on  turma.ed57_i_codigo                  = matricula.ed60_i_turma";
    $sql .= "                                 and  matricula.ed60_i_aluno              = ed301_aluno";
    $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo        = regencia.ed59_i_disciplina";
    $sql .= "      inner join caddisciplina  on  caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
    $sql2 = "";
    if($dbwhere==""){
      if($ed301_sequencial!=null ){
        $sql2 .= " where diarioclassealunofalta.ed301_sequencial = $ed301_sequencial ";
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