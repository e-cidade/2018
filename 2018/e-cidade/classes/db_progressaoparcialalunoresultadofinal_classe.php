<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE progressaoparcialalunoresultadofinal
class cl_progressaoparcialalunoresultadofinal { 
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
   var $ed121_sequencial = 0; 
   var $ed121_progressaoparcialalunomatricula = 0; 
   var $ed121_nota = null; 
   var $ed121_faltas = 0; 
   var $ed121_resultadofinal = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed121_sequencial = int4 = Código Sequencial 
                 ed121_progressaoparcialalunomatricula = int4 = Matricula Progressão Parcial 
                 ed121_nota = varchar(10) = Nota Final da Progressão 
                 ed121_faltas = int4 = Total de Faltas 
                 ed121_resultadofinal = varchar(1) = Resultado Final 
                 ";
   //funcao construtor da classe 
   function cl_progressaoparcialalunoresultadofinal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("progressaoparcialalunoresultadofinal"); 
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
       $this->ed121_sequencial = ($this->ed121_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed121_sequencial"]:$this->ed121_sequencial);
       $this->ed121_progressaoparcialalunomatricula = ($this->ed121_progressaoparcialalunomatricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed121_progressaoparcialalunomatricula"]:$this->ed121_progressaoparcialalunomatricula);
       $this->ed121_nota = ($this->ed121_nota === ""?@$GLOBALS["HTTP_POST_VARS"]["ed121_nota"]:$this->ed121_nota);
       $this->ed121_faltas = ($this->ed121_faltas == ""?@$GLOBALS["HTTP_POST_VARS"]["ed121_faltas"]:$this->ed121_faltas);
       $this->ed121_resultadofinal = ($this->ed121_resultadofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed121_resultadofinal"]:$this->ed121_resultadofinal);
     }else{
       $this->ed121_sequencial = ($this->ed121_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed121_sequencial"]:$this->ed121_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed121_sequencial){ 
      $this->atualizacampos();
     if($this->ed121_progressaoparcialalunomatricula == null ){ 
       $this->erro_sql = " Campo Matricula Progressão Parcial nao Informado.";
       $this->erro_campo = "ed121_progressaoparcialalunomatricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed121_faltas == null ){ 
       $this->ed121_faltas = "0";
     }
     if($ed121_sequencial == "" || $ed121_sequencial == null ){
       $result = db_query("select nextval('progressaoparcialalunoresultadofinal_ed121_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: progressaoparcialalunoresultadofinal_ed121_sequencial_seq do campo: ed121_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed121_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from progressaoparcialalunoresultadofinal_ed121_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed121_sequencial)){
         $this->erro_sql = " Campo ed121_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed121_sequencial = $ed121_sequencial; 
       }
     }
     if(($this->ed121_sequencial == null) || ($this->ed121_sequencial == "") ){ 
       $this->erro_sql = " Campo ed121_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into progressaoparcialalunoresultadofinal(
                                       ed121_sequencial 
                                      ,ed121_progressaoparcialalunomatricula 
                                      ,ed121_nota 
                                      ,ed121_faltas 
                                      ,ed121_resultadofinal 
                       )
                values (
                                $this->ed121_sequencial 
                               ,$this->ed121_progressaoparcialalunomatricula 
                               ,'$this->ed121_nota' 
                               ,$this->ed121_faltas 
                               ,'$this->ed121_resultadofinal' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Resultado final da progressao do aluno ($this->ed121_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Resultado final da progressao do aluno já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Resultado final da progressao do aluno ($this->ed121_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed121_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed121_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19601,'$this->ed121_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3483,19601,'','".AddSlashes(pg_result($resaco,0,'ed121_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3483,19602,'','".AddSlashes(pg_result($resaco,0,'ed121_progressaoparcialalunomatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3483,19603,'','".AddSlashes(pg_result($resaco,0,'ed121_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3483,19604,'','".AddSlashes(pg_result($resaco,0,'ed121_faltas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3483,19605,'','".AddSlashes(pg_result($resaco,0,'ed121_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed121_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update progressaoparcialalunoresultadofinal set ";
     $virgula = "";
     if(trim($this->ed121_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed121_sequencial"])){ 
       $sql  .= $virgula." ed121_sequencial = $this->ed121_sequencial ";
       $virgula = ",";
       if(trim($this->ed121_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ed121_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed121_progressaoparcialalunomatricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed121_progressaoparcialalunomatricula"])){ 
       $sql  .= $virgula." ed121_progressaoparcialalunomatricula = $this->ed121_progressaoparcialalunomatricula ";
       $virgula = ",";
       if(trim($this->ed121_progressaoparcialalunomatricula) == null ){ 
         $this->erro_sql = " Campo Matricula Progressão Parcial nao Informado.";
         $this->erro_campo = "ed121_progressaoparcialalunomatricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed121_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed121_nota"])){ 
       $sql  .= $virgula." ed121_nota = '$this->ed121_nota' ";
       $virgula = ",";
     }
     if(trim($this->ed121_faltas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed121_faltas"])){ 
        if(trim($this->ed121_faltas)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed121_faltas"])){ 
           $this->ed121_faltas = "0" ; 
        } 
       $sql  .= $virgula." ed121_faltas = $this->ed121_faltas ";
       $virgula = ",";
     }
     if(trim($this->ed121_resultadofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed121_resultadofinal"])){ 
       $sql  .= $virgula." ed121_resultadofinal = '$this->ed121_resultadofinal' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed121_sequencial!=null){
       $sql .= " ed121_sequencial = $this->ed121_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed121_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19601,'$this->ed121_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed121_sequencial"]) || $this->ed121_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3483,19601,'".AddSlashes(pg_result($resaco,$conresaco,'ed121_sequencial'))."','$this->ed121_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed121_progressaoparcialalunomatricula"]) || $this->ed121_progressaoparcialalunomatricula != "")
           $resac = db_query("insert into db_acount values($acount,3483,19602,'".AddSlashes(pg_result($resaco,$conresaco,'ed121_progressaoparcialalunomatricula'))."','$this->ed121_progressaoparcialalunomatricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed121_nota"]) || $this->ed121_nota != "")
           $resac = db_query("insert into db_acount values($acount,3483,19603,'".AddSlashes(pg_result($resaco,$conresaco,'ed121_nota'))."','$this->ed121_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed121_faltas"]) || $this->ed121_faltas != "")
           $resac = db_query("insert into db_acount values($acount,3483,19604,'".AddSlashes(pg_result($resaco,$conresaco,'ed121_faltas'))."','$this->ed121_faltas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed121_resultadofinal"]) || $this->ed121_resultadofinal != "")
           $resac = db_query("insert into db_acount values($acount,3483,19605,'".AddSlashes(pg_result($resaco,$conresaco,'ed121_resultadofinal'))."','$this->ed121_resultadofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado final da progressao do aluno nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed121_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultado final da progressao do aluno nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed121_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed121_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19601,'$ed121_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3483,19601,'','".AddSlashes(pg_result($resaco,$iresaco,'ed121_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3483,19602,'','".AddSlashes(pg_result($resaco,$iresaco,'ed121_progressaoparcialalunomatricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3483,19603,'','".AddSlashes(pg_result($resaco,$iresaco,'ed121_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3483,19604,'','".AddSlashes(pg_result($resaco,$iresaco,'ed121_faltas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3483,19605,'','".AddSlashes(pg_result($resaco,$iresaco,'ed121_resultadofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from progressaoparcialalunoresultadofinal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed121_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed121_sequencial = $ed121_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Resultado final da progressao do aluno nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed121_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Resultado final da progressao do aluno nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed121_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed121_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:progressaoparcialalunoresultadofinal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed121_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progressaoparcialalunoresultadofinal ";
     $sql .= "      inner join progressaoparcialalunomatricula  on  progressaoparcialalunomatricula.ed150_sequencial = progressaoparcialalunoresultadofinal.ed121_progressaoparcialalunomatricula";
     $sql .= "      inner join progressaoparcialaluno  on  progressaoparcialaluno.ed114_sequencial = progressaoparcialalunomatricula.ed150_progressaoparcialaluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed121_sequencial!=null ){
         $sql2 .= " where progressaoparcialalunoresultadofinal.ed121_sequencial = $ed121_sequencial "; 
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
   function sql_query_file ( $ed121_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from progressaoparcialalunoresultadofinal ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed121_sequencial!=null ){
         $sql2 .= " where progressaoparcialalunoresultadofinal.ed121_sequencial = $ed121_sequencial "; 
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