<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE controleacessoalunoregistrovalido
class cl_controleacessoalunoregistrovalido { 
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
   var $ed303_sequencial = 0; 
   var $ed303_controleacessoalunoregistro = 0; 
   var $ed303_aluno = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed303_sequencial = int4 = Código Sequencial 
                 ed303_controleacessoalunoregistro = int4 = Código da Leitura 
                 ed303_aluno = int4 = Código do aluno 
                 ";
   //funcao construtor da classe 
   function cl_controleacessoalunoregistrovalido() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("controleacessoalunoregistrovalido"); 
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
       $this->ed303_sequencial = ($this->ed303_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed303_sequencial"]:$this->ed303_sequencial);
       $this->ed303_controleacessoalunoregistro = ($this->ed303_controleacessoalunoregistro == ""?@$GLOBALS["HTTP_POST_VARS"]["ed303_controleacessoalunoregistro"]:$this->ed303_controleacessoalunoregistro);
       $this->ed303_aluno = ($this->ed303_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed303_aluno"]:$this->ed303_aluno);
     }else{
       $this->ed303_sequencial = ($this->ed303_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed303_sequencial"]:$this->ed303_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed303_sequencial){ 
      $this->atualizacampos();
     if($this->ed303_controleacessoalunoregistro == null ){ 
       $this->erro_sql = " Campo Código da Leitura nao Informado.";
       $this->erro_campo = "ed303_controleacessoalunoregistro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed303_aluno == null ){ 
       $this->erro_sql = " Campo Código do aluno nao Informado.";
       $this->erro_campo = "ed303_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed303_sequencial == "" || $ed303_sequencial == null ){
       $result = db_query("select nextval('controleacessoalunoregistrovalido_ed303_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: controleacessoalunoregistrovalido_ed303_sequencial_seq do campo: ed303_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed303_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from controleacessoalunoregistrovalido_ed303_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed303_sequencial)){
         $this->erro_sql = " Campo ed303_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed303_sequencial = $ed303_sequencial; 
       }
     }
     if(($this->ed303_sequencial == null) || ($this->ed303_sequencial == "") ){ 
       $this->erro_sql = " Campo ed303_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into controleacessoalunoregistrovalido(
                                       ed303_sequencial 
                                      ,ed303_controleacessoalunoregistro 
                                      ,ed303_aluno 
                       )
                values (
                                $this->ed303_sequencial 
                               ,$this->ed303_controleacessoalunoregistro 
                               ,$this->ed303_aluno 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Entradas de alunos realizadas ($this->ed303_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Entradas de alunos realizadas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Entradas de alunos realizadas ($this->ed303_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed303_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed303_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18817,'$this->ed303_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3336,18817,'','".AddSlashes(pg_result($resaco,0,'ed303_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3336,18819,'','".AddSlashes(pg_result($resaco,0,'ed303_controleacessoalunoregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3336,18818,'','".AddSlashes(pg_result($resaco,0,'ed303_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed303_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update controleacessoalunoregistrovalido set ";
     $virgula = "";
     if(trim($this->ed303_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed303_sequencial"])){ 
       $sql  .= $virgula." ed303_sequencial = $this->ed303_sequencial ";
       $virgula = ",";
       if(trim($this->ed303_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "ed303_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed303_controleacessoalunoregistro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed303_controleacessoalunoregistro"])){ 
       $sql  .= $virgula." ed303_controleacessoalunoregistro = $this->ed303_controleacessoalunoregistro ";
       $virgula = ",";
       if(trim($this->ed303_controleacessoalunoregistro) == null ){ 
         $this->erro_sql = " Campo Código da Leitura nao Informado.";
         $this->erro_campo = "ed303_controleacessoalunoregistro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed303_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed303_aluno"])){ 
       $sql  .= $virgula." ed303_aluno = $this->ed303_aluno ";
       $virgula = ",";
       if(trim($this->ed303_aluno) == null ){ 
         $this->erro_sql = " Campo Código do aluno nao Informado.";
         $this->erro_campo = "ed303_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed303_sequencial!=null){
       $sql .= " ed303_sequencial = $this->ed303_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed303_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18817,'$this->ed303_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed303_sequencial"]) || $this->ed303_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3336,18817,'".AddSlashes(pg_result($resaco,$conresaco,'ed303_sequencial'))."','$this->ed303_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed303_controleacessoalunoregistro"]) || $this->ed303_controleacessoalunoregistro != "")
           $resac = db_query("insert into db_acount values($acount,3336,18819,'".AddSlashes(pg_result($resaco,$conresaco,'ed303_controleacessoalunoregistro'))."','$this->ed303_controleacessoalunoregistro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed303_aluno"]) || $this->ed303_aluno != "")
           $resac = db_query("insert into db_acount values($acount,3336,18818,'".AddSlashes(pg_result($resaco,$conresaco,'ed303_aluno'))."','$this->ed303_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Entradas de alunos realizadas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed303_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Entradas de alunos realizadas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed303_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed303_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed303_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed303_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18817,'$ed303_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3336,18817,'','".AddSlashes(pg_result($resaco,$iresaco,'ed303_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3336,18819,'','".AddSlashes(pg_result($resaco,$iresaco,'ed303_controleacessoalunoregistro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3336,18818,'','".AddSlashes(pg_result($resaco,$iresaco,'ed303_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from controleacessoalunoregistrovalido
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed303_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed303_sequencial = $ed303_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Entradas de alunos realizadas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed303_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Entradas de alunos realizadas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed303_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed303_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:controleacessoalunoregistrovalido";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed303_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoalunoregistrovalido ";
     $sql .= "      inner join controleacessoalunoregistro  on  controleacessoalunoregistro.ed101_sequencial = controleacessoalunoregistrovalido.ed303_controleacessoalunoregistro";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = controleacessoalunoregistrovalido.ed303_aluno";
     $sql .= "      inner join controleacessoaluno  on  controleacessoaluno.ed100_sequencial = controleacessoalunoregistro.ed101_controleacessoaluno";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join aluno  as a on   a.ed47_i_codigo = aluno.ed47_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed303_sequencial!=null ){
         $sql2 .= " where controleacessoalunoregistrovalido.ed303_sequencial = $ed303_sequencial "; 
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
   function sql_query_file ( $ed303_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoalunoregistrovalido ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed303_sequencial!=null ){
         $sql2 .= " where controleacessoalunoregistrovalido.ed303_sequencial = $ed303_sequencial "; 
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
  
  function sql_query_controle_acesso ( $ed303_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from controleacessoalunoregistrovalido ";
     $sql .= "      inner join controleacessoalunoregistro  on  controleacessoalunoregistro.ed101_sequencial = controleacessoalunoregistrovalido.ed303_controleacessoalunoregistro";
     $sql .= "      inner join controleacessoaluno  on  controleacessoaluno.ed100_sequencial = controleacessoalunoregistro.ed101_controleacessoaluno";
     $sql .= "      inner join matricula on ed60_i_aluno = ed303_aluno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed303_sequencial!=null ){
         $sql2 .= " where controleacessoalunoregistrovalido.ed303_sequencial = $ed303_sequencial "; 
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