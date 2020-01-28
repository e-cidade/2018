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
//CLASSE DA ENTIDADE turmaacativ
class cl_turmaacativ { 
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
   var $ed267_i_codigo = 0; 
   var $ed267_i_turmaac = 0; 
   var $ed267_i_censoativcompl = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed267_i_codigo = int4 = Código 
                 ed267_i_turmaac = int4 = Turma 
                 ed267_i_censoativcompl = int4 = Atividade Complementar 
                 ";
   //funcao construtor da classe 
   function cl_turmaacativ() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turmaacativ"); 
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
       $this->ed267_i_codigo = ($this->ed267_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed267_i_codigo"]:$this->ed267_i_codigo);
       $this->ed267_i_turmaac = ($this->ed267_i_turmaac == ""?@$GLOBALS["HTTP_POST_VARS"]["ed267_i_turmaac"]:$this->ed267_i_turmaac);
       $this->ed267_i_censoativcompl = ($this->ed267_i_censoativcompl == ""?@$GLOBALS["HTTP_POST_VARS"]["ed267_i_censoativcompl"]:$this->ed267_i_censoativcompl);
     }else{
       $this->ed267_i_codigo = ($this->ed267_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed267_i_codigo"]:$this->ed267_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed267_i_codigo){ 
      $this->atualizacampos();
     if($this->ed267_i_turmaac == null ){ 
       $this->erro_sql = " Campo Turma nao Informado.";
       $this->erro_campo = "ed267_i_turmaac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed267_i_censoativcompl == null ){ 
       $this->erro_sql = " Campo Atividade Complementar nao Informado.";
       $this->erro_campo = "ed267_i_censoativcompl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed267_i_codigo == "" || $ed267_i_codigo == null ){
       $result = db_query("select nextval('turmaacativ_ed267_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: turmaacativ_ed267_i_codigo_seq do campo: ed267_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed267_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from turmaacativ_ed267_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed267_i_codigo)){
         $this->erro_sql = " Campo ed267_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed267_i_codigo = $ed267_i_codigo; 
       }
     }
     if(($this->ed267_i_codigo == null) || ($this->ed267_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed267_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     
     if($this->ed267_i_turmaac != null ){
     	
     	/* Censo não validará caso uma turma possua duas atividades complementares com o mesmo código */
     	$result = $this->sql_record("SELECT ed267_i_turmaac 
     																 FROM turmaacativ
     															  WHERE ed267_i_turmaac 			 = {$this->ed267_i_turmaac}
     																	AND ed267_i_censoativcompl = {$this->ed267_i_censoativcompl}");
     	if(($result!=false)||($this->numrows<>0)){
     		$this->erro_sql    = " Turma já possui Atividade Complementar cadastrada com o mesmo código informado.";
     		$this->erro_msg    = "Usuário: \\n\\n ".$this->erro_sql."\\n\\n";
     		$this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     		$this->erro_status = "0";
     		return false;
     	}
     }
     
     $sql = "insert into turmaacativ(
                                       ed267_i_codigo 
                                      ,ed267_i_turmaac 
                                      ,ed267_i_censoativcompl 
                       )
                values (
                                $this->ed267_i_codigo 
                               ,$this->ed267_i_turmaac 
                               ,$this->ed267_i_censoativcompl 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atividade Complementar por Turma ($this->ed267_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atividade Complementar por Turma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atividade Complementar por Turma ($this->ed267_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed267_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed267_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13791,'$this->ed267_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2414,13791,'','".AddSlashes(pg_result($resaco,0,'ed267_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2414,13792,'','".AddSlashes(pg_result($resaco,0,'ed267_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2414,13793,'','".AddSlashes(pg_result($resaco,0,'ed267_i_censoativcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed267_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update turmaacativ set ";
     $virgula = "";
     if(trim($this->ed267_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed267_i_codigo"])){ 
       $sql  .= $virgula." ed267_i_codigo = $this->ed267_i_codigo ";
       $virgula = ",";
       if(trim($this->ed267_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed267_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed267_i_turmaac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed267_i_turmaac"])){ 
       $sql  .= $virgula." ed267_i_turmaac = $this->ed267_i_turmaac ";
       $virgula = ",";
       if(trim($this->ed267_i_turmaac) == null ){ 
         $this->erro_sql = " Campo Turma nao Informado.";
         $this->erro_campo = "ed267_i_turmaac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed267_i_censoativcompl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed267_i_censoativcompl"])){ 
       $sql  .= $virgula." ed267_i_censoativcompl = $this->ed267_i_censoativcompl ";
       $virgula = ",";
       if(trim($this->ed267_i_censoativcompl) == null ){ 
         $this->erro_sql = " Campo Atividade Complementar nao Informado.";
         $this->erro_campo = "ed267_i_censoativcompl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed267_i_codigo!=null){
       $sql .= " ed267_i_codigo = $this->ed267_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed267_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13791,'$this->ed267_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed267_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2414,13791,'".AddSlashes(pg_result($resaco,$conresaco,'ed267_i_codigo'))."','$this->ed267_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed267_i_turmaac"]))
           $resac = db_query("insert into db_acount values($acount,2414,13792,'".AddSlashes(pg_result($resaco,$conresaco,'ed267_i_turmaac'))."','$this->ed267_i_turmaac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed267_i_censoativcompl"]))
           $resac = db_query("insert into db_acount values($acount,2414,13793,'".AddSlashes(pg_result($resaco,$conresaco,'ed267_i_censoativcompl'))."','$this->ed267_i_censoativcompl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividade Complementar por Turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed267_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atividade Complementar por Turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed267_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed267_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed267_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed267_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13791,'$ed267_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2414,13791,'','".AddSlashes(pg_result($resaco,$iresaco,'ed267_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2414,13792,'','".AddSlashes(pg_result($resaco,$iresaco,'ed267_i_turmaac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2414,13793,'','".AddSlashes(pg_result($resaco,$iresaco,'ed267_i_censoativcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from turmaacativ
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed267_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed267_i_codigo = $ed267_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);     
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividade Complementar por Turma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed267_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atividade Complementar por Turma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed267_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed267_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:turmaacativ";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed267_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaacativ ";
     $sql .= "      inner join censoativcompl  on  censoativcompl.ed133_i_codigo = turmaacativ.ed267_i_censoativcompl";
     $sql .= "      inner join turmaac  on  turmaac.ed268_i_codigo = turmaacativ.ed267_i_turmaac";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = turmaac.ed268_i_escola";
     $sql .= "      inner join turno  on  turno.ed15_i_codigo = turmaac.ed268_i_turno";
     $sql .= "      left join sala  on  sala.ed16_i_codigo = turmaac.ed268_i_sala";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = turmaac.ed268_i_calendario";
     $sql2 = "";
     if($dbwhere==""){
       if($ed267_i_codigo!=null ){
         $sql2 .= " where turmaacativ.ed267_i_codigo = $ed267_i_codigo "; 
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
   function sql_query_file ( $ed267_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turmaacativ ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed267_i_codigo!=null ){
         $sql2 .= " where turmaacativ.ed267_i_codigo = $ed267_i_codigo "; 
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