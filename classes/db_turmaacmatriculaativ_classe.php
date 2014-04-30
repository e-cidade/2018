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

//MODULO: Escola
//CLASSE DA ENTIDADE turmaacmatriculaativ
class cl_turmaacmatriculaativ { 
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
   var $ed271_i_codigo = 0; 
   var $ed271_i_turmaacmatricula = 0; 
   var $ed271_i_turmaacativ = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed271_i_codigo = int8 = Código 
                 ed271_i_turmaacmatricula = int8 = Matricula 
                 ed271_i_turmaacativ = int8 = Atividade Complementar 
                 ";
   //funcao construtor da classe 
   function cl_turmaacmatriculaativ() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmaacmatriculaativ"); 
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
       $this->ed271_i_codigo = ($this->ed271_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed271_i_codigo"]:$this->ed271_i_codigo);
       $this->ed271_i_turmaacmatricula = ($this->ed271_i_turmaacmatricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed271_i_turmaacmatricula"]:$this->ed271_i_turmaacmatricula);
       $this->ed271_i_turmaacativ = ($this->ed271_i_turmaacativ == ""?@$GLOBALS["HTTP_POST_VARS"]["ed271_i_turmaacativ"]:$this->ed271_i_turmaacativ);
     }else{
       $this->ed271_i_codigo = ($this->ed271_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed271_i_codigo"]:$this->ed271_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed271_i_codigo){ 
      $this->atualizacampos();
     if($this->ed271_i_turmaacmatricula == null ){ 
       $this->erro_sql = " Campo Matricula nao Informado.";
       $this->erro_campo = "ed271_i_turmaacmatricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed271_i_turmaacativ == null ){ 
       $this->erro_sql = " Campo Atividade Complementar nao Informado.";
       $this->erro_campo = "ed271_i_turmaacativ";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed271_i_codigo == "" || $ed271_i_codigo == null ){
       $result = db_query("select nextval('turmaacmatriculaativ_ed271_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmaacmatriculaativ_ed271_i_codigo_seq do campo: ed271_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed271_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from turmaacmatriculaativ_ed271_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed271_i_codigo)){
         $this->erro_sql = " Campo ed271_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed271_i_codigo = $ed271_i_codigo; 
       }
     }
     if(($this->ed271_i_codigo == null) || ($this->ed271_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed271_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turmaacmatriculaativ(
                                       ed271_i_codigo 
                                      ,ed271_i_turmaacmatricula 
                                      ,ed271_i_turmaacativ 
                       )
                values (
                                $this->ed271_i_codigo 
                               ,$this->ed271_i_turmaacmatricula 
                               ,$this->ed271_i_turmaacativ 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atividades por Matrícula na Turma AC ($this->ed271_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atividades por Matrícula na Turma AC já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atividades por Matrícula na Turma AC ($this->ed271_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed271_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed271_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13845,'$this->ed271_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2419,13845,'','".AddSlashes(pg_result($resaco,0,'ed271_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2419,13846,'','".AddSlashes(pg_result($resaco,0,'ed271_i_turmaacmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2419,13847,'','".AddSlashes(pg_result($resaco,0,'ed271_i_turmaacativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed271_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update turmaacmatriculaativ set ";
     $virgula = "";
     if(trim($this->ed271_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed271_i_codigo"])){ 
       $sql  .= $virgula." ed271_i_codigo = $this->ed271_i_codigo ";
       $virgula = ",";
       if(trim($this->ed271_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed271_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed271_i_turmaacmatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed271_i_turmaacmatricula"])){ 
       $sql  .= $virgula." ed271_i_turmaacmatricula = $this->ed271_i_turmaacmatricula ";
       $virgula = ",";
       if(trim($this->ed271_i_turmaacmatricula) == null ){ 
         $this->erro_sql = " Campo Matricula nao Informado.";
         $this->erro_campo = "ed271_i_turmaacmatricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed271_i_turmaacativ)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed271_i_turmaacativ"])){ 
       $sql  .= $virgula." ed271_i_turmaacativ = $this->ed271_i_turmaacativ ";
       $virgula = ",";
       if(trim($this->ed271_i_turmaacativ) == null ){ 
         $this->erro_sql = " Campo Atividade Complementar nao Informado.";
         $this->erro_campo = "ed271_i_turmaacativ";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed271_i_codigo!=null){
       $sql .= " ed271_i_codigo = $this->ed271_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed271_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13845,'$this->ed271_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed271_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2419,13845,'".AddSlashes(pg_result($resaco,$conresaco,'ed271_i_codigo'))."','$this->ed271_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed271_i_turmaacmatricula"]))
           $resac = db_query("insert into db_acount values($acount,2419,13846,'".AddSlashes(pg_result($resaco,$conresaco,'ed271_i_turmaacmatricula'))."','$this->ed271_i_turmaacmatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed271_i_turmaacativ"]))
           $resac = db_query("insert into db_acount values($acount,2419,13847,'".AddSlashes(pg_result($resaco,$conresaco,'ed271_i_turmaacativ'))."','$this->ed271_i_turmaacativ',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividades por Matrícula na Turma AC nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed271_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atividades por Matrícula na Turma AC nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed271_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed271_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed271_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed271_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13845,'$ed271_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2419,13845,'','".AddSlashes(pg_result($resaco,$iresaco,'ed271_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2419,13846,'','".AddSlashes(pg_result($resaco,$iresaco,'ed271_i_turmaacmatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2419,13847,'','".AddSlashes(pg_result($resaco,$iresaco,'ed271_i_turmaacativ'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from turmaacmatriculaativ
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed271_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed271_i_codigo = $ed271_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividades por Matrícula na Turma AC nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed271_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atividades por Matrícula na Turma AC nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed271_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed271_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaacmatriculaativ";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed271_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaacmatriculaativ ";
     $sql .= "      inner join turmaacativ  on  turmaacativ.ed267_i_codigo = turmaacmatriculaativ.ed271_i_turmaacativ";
     $sql .= "      inner join turmaacmatricula  on  turmaacmatricula.ed269_i_codigo = turmaacmatriculaativ.ed271_i_turmaacmatricula";
     $sql .= "      inner join censoativcompl  on  censoativcompl.ed133_i_codigo = turmaacativ.ed267_i_censoativcompl";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaacativ.ed267_i_turmaac";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaacmatricula.ed269_i_turmaac";
     //$sql .= "      inner join matricula  on  matricula.ed60_i_codigo = turmaacmatricula.ed269_i_matricula";
     $sql2 = "";
     if($dbwhere==""){
       if($ed271_i_codigo!=null ){
         $sql2 .= " where turmaacmatriculaativ.ed271_i_codigo = $ed271_i_codigo "; 
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
   function sql_query_file ( $ed271_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaacmatriculaativ ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed271_i_codigo!=null ){
         $sql2 .= " where turmaacmatriculaativ.ed271_i_codigo = $ed271_i_codigo "; 
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