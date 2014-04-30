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

//MODULO: educação
//CLASSE DA ENTIDADE regenciaperiodo
class cl_regenciaperiodo {
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
   var $ed78_i_codigo = 0;
   var $ed78_i_regencia = 0;
   var $ed78_i_procavaliacao = 0;
   var $ed78_i_aulasdadas = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed78_i_codigo = int8 = Código
                 ed78_i_regencia = int8 = Regência
                 ed78_i_procavaliacao = int8 = Período de Avaliação
                 ed78_i_aulasdadas = int4 = N° Aulas Dadas
                 ";
   //funcao construtor da classe
   function cl_regenciaperiodo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("regenciaperiodo");
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
       $this->ed78_i_codigo = ($this->ed78_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed78_i_codigo"]:$this->ed78_i_codigo);
       $this->ed78_i_regencia = ($this->ed78_i_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed78_i_regencia"]:$this->ed78_i_regencia);
       $this->ed78_i_procavaliacao = ($this->ed78_i_procavaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed78_i_procavaliacao"]:$this->ed78_i_procavaliacao);
       $this->ed78_i_aulasdadas = ($this->ed78_i_aulasdadas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed78_i_aulasdadas"]:$this->ed78_i_aulasdadas);
     }else{
       $this->ed78_i_codigo = ($this->ed78_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed78_i_codigo"]:$this->ed78_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed78_i_codigo){
      $this->atualizacampos();
     if($this->ed78_i_regencia == null ){
       $this->erro_sql = " Campo Regência nao Informado.";
       $this->erro_campo = "ed78_i_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed78_i_procavaliacao == null ){
       $this->erro_sql = " Campo Período de Avaliação nao Informado.";
       $this->erro_campo = "ed78_i_procavaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed78_i_aulasdadas == null ){
       $this->ed78_i_aulasdadas = "null";
     }
     if($ed78_i_codigo == "" || $ed78_i_codigo == null ){
       $result = db_query("select nextval('regenciaperiodo_ed78_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: regenciaperiodo_ed78_i_codigo_seq do campo: ed78_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed78_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from regenciaperiodo_ed78_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed78_i_codigo)){
         $this->erro_sql = " Campo ed78_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed78_i_codigo = $ed78_i_codigo;
       }
     }
     if(($this->ed78_i_codigo == null) || ($this->ed78_i_codigo == "") ){
       $this->erro_sql = " Campo ed78_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into regenciaperiodo(
                                       ed78_i_codigo
                                      ,ed78_i_regencia
                                      ,ed78_i_procavaliacao
                                      ,ed78_i_aulasdadas
                       )
                values (
                                $this->ed78_i_codigo
                               ,$this->ed78_i_regencia
                               ,$this->ed78_i_procavaliacao
                               ,$this->ed78_i_aulasdadas
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Aulas dadas no Período de Avaliação ($this->ed78_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Aulas dadas no Período de Avaliação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Aulas dadas no Período de Avaliação ($this->ed78_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed78_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed78_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008507,'$this->ed78_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010085,1008507,'','".AddSlashes(pg_result($resaco,0,'ed78_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010085,1008509,'','".AddSlashes(pg_result($resaco,0,'ed78_i_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010085,1008508,'','".AddSlashes(pg_result($resaco,0,'ed78_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010085,1008510,'','".AddSlashes(pg_result($resaco,0,'ed78_i_aulasdadas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed78_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update regenciaperiodo set ";
     $virgula = "";
     if(trim($this->ed78_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed78_i_codigo"])){
       $sql  .= $virgula." ed78_i_codigo = $this->ed78_i_codigo ";
       $virgula = ",";
       if(trim($this->ed78_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed78_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed78_i_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed78_i_regencia"])){
       $sql  .= $virgula." ed78_i_regencia = $this->ed78_i_regencia ";
       $virgula = ",";
       if(trim($this->ed78_i_regencia) == null ){
         $this->erro_sql = " Campo Regência nao Informado.";
         $this->erro_campo = "ed78_i_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed78_i_procavaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed78_i_procavaliacao"])){
       $sql  .= $virgula." ed78_i_procavaliacao = $this->ed78_i_procavaliacao ";
       $virgula = ",";
       if(trim($this->ed78_i_procavaliacao) == null ){
         $this->erro_sql = " Campo Período de Avaliação nao Informado.";
         $this->erro_campo = "ed78_i_procavaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed78_i_aulasdadas)==null){
         $this->ed78_i_aulasdadas = "null" ;
        }
       $sql  .= $virgula." ed78_i_aulasdadas = $this->ed78_i_aulasdadas ";
       $virgula = ",";

     $sql .= " where ";
     if($ed78_i_codigo!=null){
       $sql .= " ed78_i_codigo = $this->ed78_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed78_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008507,'$this->ed78_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed78_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010085,1008507,'".AddSlashes(pg_result($resaco,$conresaco,'ed78_i_codigo'))."','$this->ed78_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed78_i_regencia"]))
           $resac = db_query("insert into db_acount values($acount,1010085,1008509,'".AddSlashes(pg_result($resaco,$conresaco,'ed78_i_regencia'))."','$this->ed78_i_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed78_i_procavaliacao"]))
           $resac = db_query("insert into db_acount values($acount,1010085,1008508,'".AddSlashes(pg_result($resaco,$conresaco,'ed78_i_procavaliacao'))."','$this->ed78_i_procavaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed78_i_aulasdadas"]))
           $resac = db_query("insert into db_acount values($acount,1010085,1008510,'".AddSlashes(pg_result($resaco,$conresaco,'ed78_i_aulasdadas'))."','$this->ed78_i_aulasdadas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aulas dadas no Período de Avaliação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed78_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aulas dadas no Período de Avaliação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed78_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed78_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed78_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed78_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008507,'$ed78_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010085,1008507,'','".AddSlashes(pg_result($resaco,$iresaco,'ed78_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010085,1008509,'','".AddSlashes(pg_result($resaco,$iresaco,'ed78_i_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010085,1008508,'','".AddSlashes(pg_result($resaco,$iresaco,'ed78_i_procavaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010085,1008510,'','".AddSlashes(pg_result($resaco,$iresaco,'ed78_i_aulasdadas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from regenciaperiodo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed78_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed78_i_codigo = $ed78_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Aulas dadas no Período de Avaliação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed78_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Aulas dadas no Período de Avaliação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed78_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed78_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:regenciaperiodo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed78_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from regenciaperiodo ";
     $sql .= "      inner join procavaliacao  on  procavaliacao.ed41_i_codigo = regenciaperiodo.ed78_i_procavaliacao";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = regenciaperiodo.ed78_i_regencia";
     $sql .= "      inner join periodoavaliacao  on  periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao";
     $sql .= "      inner join formaavaliacao  on  formaavaliacao.ed37_i_codigo = procavaliacao.ed41_i_formaavaliacao";
     $sql .= "      inner join procedimento  on  procedimento.ed40_i_codigo = procavaliacao.ed41_i_procedimento";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
     $sql2 = "";
     if($dbwhere==""){
       if($ed78_i_codigo!=null ){
         $sql2 .= " where regenciaperiodo.ed78_i_codigo = $ed78_i_codigo ";
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
   function sql_query_file ( $ed78_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from regenciaperiodo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed78_i_codigo!=null ){
         $sql2 .= " where regenciaperiodo.ed78_i_codigo = $ed78_i_codigo ";
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

  function sql_query_verifica_periodos ( $ed78_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
    $sql .= " from regencia ";
    $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
    $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
    $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
    $sql .= "      inner join turmaserieregimemat on ed220_i_turma  = turma.ed57_i_codigo";
    $sql .= "      inner join serieregimemat      on ed220_i_serieregimemat       = ed223_i_codigo";
    $sql .= "                                    and ed223_i_serie                = ed59_i_serie";
    $sql .= "      inner join procavaliacao     on ed41_i_procedimento            = ed220_i_procedimento";
    $sql .= "      inner join periodoavaliacao  on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao";
    $sql .= "                                   AND ed09_c_somach = 'S' ";
    $sql .= "      left  join regenciaperiodo   on regenciaperiodo.ed78_i_regencia      = regencia.ed59_i_codigo      ";
    $sql .= "                                  and regenciaperiodo.ed78_i_procavaliacao = procavaliacao.ed41_i_codigo ";
    $sql2 = "";
    if($dbwhere==""){
      if($ed78_i_codigo!=null ){
        $sql2 .= " where regenciaperiodo.ed78_i_codigo = $ed78_i_codigo ";
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


  function sql_query_periodo_avaliacao ( $ed78_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {
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
  	$sql .= " from regenciaperiodo ";
  	$sql .= "      inner join procavaliacao  on  procavaliacao.ed41_i_codigo = regenciaperiodo.ed78_i_procavaliacao";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($ed78_i_codigo!=null ){
  			$sql2 .= " where regenciaperiodo.ed78_i_codigo = $ed78_i_codigo ";
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