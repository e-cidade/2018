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
//CLASSE DA ENTIDADE turma_matriculas
class cl_turma_matriculas { 
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
   var $ed36_i_turma = 0; 
   var $ed36_i_matricula = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed36_i_turma = int8 = Turma 
                 ed36_i_matricula = int8 = Matrícula 
                 ";
   //funcao construtor da classe 
   function cl_turma_matriculas() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("turma_matriculas"); 
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
       $this->ed36_i_turma = ($this->ed36_i_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed36_i_turma"]:$this->ed36_i_turma);
       $this->ed36_i_matricula = ($this->ed36_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed36_i_matricula"]:$this->ed36_i_matricula);
     }else{
       $this->ed36_i_turma = ($this->ed36_i_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["ed36_i_turma"]:$this->ed36_i_turma);
       $this->ed36_i_matricula = ($this->ed36_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed36_i_matricula"]:$this->ed36_i_matricula);
     }
   }
   // funcao para inclusao
   function incluir ($ed36_i_turma,$ed36_i_matricula){ 
      $this->atualizacampos();
       $this->ed36_i_turma = $ed36_i_turma; 
       $this->ed36_i_matricula = $ed36_i_matricula; 
     if(($this->ed36_i_turma == null) || ($this->ed36_i_turma == "") ){ 
       $this->erro_sql = " Campo ed36_i_turma nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->ed36_i_matricula == null) || ($this->ed36_i_matricula == "") ){ 
       $this->erro_sql = " Campo ed36_i_matricula nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into turma_matriculas(
                                       ed36_i_turma 
                                      ,ed36_i_matricula 
                       )
                values (
                                $this->ed36_i_turma 
                               ,$this->ed36_i_matricula 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Matrículas para Turma ($this->ed36_i_turma."-".$this->ed36_i_matricula) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Matrículas para Turma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Matrículas para Turma ($this->ed36_i_turma."-".$this->ed36_i_matricula) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed36_i_turma."-".$this->ed36_i_matricula;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed36_i_turma,$this->ed36_i_matricula));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006318,'$this->ed36_i_turma','I')");
       $resac = pg_query("insert into db_acountkey values($acount,1006319,'$this->ed36_i_matricula','I')");
       $resac = pg_query("insert into db_acount values($acount,1006050,1006318,'','".AddSlashes(pg_result($resaco,0,'ed36_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006050,1006319,'','".AddSlashes(pg_result($resaco,0,'ed36_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed36_i_turma=null,$ed36_i_matricula=null) { 
      $this->atualizacampos();
     $sql = " update turma_matriculas set ";
     $virgula = "";
     if(trim($this->ed36_i_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed36_i_turma"])){ 
       $sql  .= $virgula." ed36_i_turma = $this->ed36_i_turma ";
       $virgula = ",";
       if(trim($this->ed36_i_turma) == null ){ 
         $this->erro_sql = " Campo Turma nao Informado.";
         $this->erro_campo = "ed36_i_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed36_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed36_i_matricula"])){ 
       $sql  .= $virgula." ed36_i_matricula = $this->ed36_i_matricula ";
       $virgula = ",";
       if(trim($this->ed36_i_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed36_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed36_i_turma!=null){
       $sql .= " ed36_i_turma = $this->ed36_i_turma";
     }
     if($ed36_i_matricula!=null){
       $sql .= " and  ed36_i_matricula = $this->ed36_i_matricula";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed36_i_turma,$this->ed36_i_matricula));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006318,'$this->ed36_i_turma','A')");
         $resac = pg_query("insert into db_acountkey values($acount,1006319,'$this->ed36_i_matricula','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed36_i_turma"]))
           $resac = pg_query("insert into db_acount values($acount,1006050,1006318,'".AddSlashes(pg_result($resaco,$conresaco,'ed36_i_turma'))."','$this->ed36_i_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed36_i_matricula"]))
           $resac = pg_query("insert into db_acount values($acount,1006050,1006319,'".AddSlashes(pg_result($resaco,$conresaco,'ed36_i_matricula'))."','$this->ed36_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrículas para Turma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed36_i_turma."-".$this->ed36_i_matricula;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matrículas para Turma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed36_i_turma."-".$this->ed36_i_matricula;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed36_i_turma."-".$this->ed36_i_matricula;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed36_i_turma=null,$ed36_i_matricula=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed36_i_turma,$ed36_i_matricula));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006318,'$ed36_i_turma','E')");
         $resac = pg_query("insert into db_acountkey values($acount,1006319,'$ed36_i_matricula','E')");
         $resac = pg_query("insert into db_acount values($acount,1006050,1006318,'','".AddSlashes(pg_result($resaco,$iresaco,'ed36_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006050,1006319,'','".AddSlashes(pg_result($resaco,$iresaco,'ed36_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from turma_matriculas
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed36_i_turma != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed36_i_turma = $ed36_i_turma ";
        }
        if($ed36_i_matricula != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed36_i_matricula = $ed36_i_matricula ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Matrículas para Turma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed36_i_turma."-".$ed36_i_matricula;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Matrículas para Turma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed36_i_turma."-".$ed36_i_matricula;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed36_i_turma."-".$ed36_i_matricula;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:turma_matriculas";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed36_i_turma=null,$ed36_i_matricula=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turma_matriculas ";
     $sql .= "      inner join turmas  on  turmas.ed05_i_codigo = turma_matriculas.ed36_i_turma";
     $sql .= "      inner join matriculas  on  matriculas.ed09_i_codigo = turma_matriculas.ed36_i_matricula";
     $sql .= "      inner join escolas  on  escolas.ed02_i_codigo = turmas.ed05_i_escola";
     $sql .= "      inner join series  on  series.ed03_i_codigo = turmas.ed05_i_serie";
     $sql .= "      inner join turnos  on  turnos.ed10_i_codigo = turmas.ed05_i_turno";
     $sql .= "      inner join escolas  as a on   a.ed02_i_codigo = matriculas.ed09_i_escola";
     $sql .= "      inner join series  as b on   b.ed03_i_codigo = matriculas.ed09_i_serie";
     $sql .= "      inner join alunos  on  alunos.ed07_i_codigo = matriculas.ed09_i_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed36_i_turma!=null ){
         $sql2 .= " where turma_matriculas.ed36_i_turma = $ed36_i_turma "; 
       } 
       if($ed36_i_matricula!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " turma_matriculas.ed36_i_matricula = $ed36_i_matricula "; 
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
   function sql_query_file ( $ed36_i_turma=null,$ed36_i_matricula=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from turma_matriculas ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed36_i_turma!=null ){
         $sql2 .= " where turma_matriculas.ed36_i_turma = $ed36_i_turma "; 
       } 
       if($ed36_i_matricula!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " turma_matriculas.ed36_i_matricula = $ed36_i_matricula "; 
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