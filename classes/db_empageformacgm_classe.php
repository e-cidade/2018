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

//MODULO: empenho
//CLASSE DA ENTIDADE empageformacgm
class cl_empageformacgm { 
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
   var $e28_sequencial = 0; 
   var $e28_numcgm = 0; 
   var $e28_empageforma = 0; 
   var $e28_empagetipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e28_sequencial = int4 = Código Sequencial 
                 e28_numcgm = int4 = Código do CGM 
                 e28_empageforma = int4 = Forma de Pagamento 
                 e28_empagetipo = int4 = Código da conta pagadora 
                 ";
   //funcao construtor da classe 
   function cl_empageformacgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empageformacgm"); 
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
       $this->e28_sequencial = ($this->e28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e28_sequencial"]:$this->e28_sequencial);
       $this->e28_numcgm = ($this->e28_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["e28_numcgm"]:$this->e28_numcgm);
       $this->e28_empageforma = ($this->e28_empageforma == ""?@$GLOBALS["HTTP_POST_VARS"]["e28_empageforma"]:$this->e28_empageforma);
       $this->e28_empagetipo = ($this->e28_empagetipo == ""?@$GLOBALS["HTTP_POST_VARS"]["e28_empagetipo"]:$this->e28_empagetipo);
     }else{
       $this->e28_sequencial = ($this->e28_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e28_sequencial"]:$this->e28_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e28_sequencial){ 
      $this->atualizacampos();
     if($this->e28_numcgm == null ){ 
       $this->erro_sql = " Campo Código do CGM nao Informado.";
       $this->erro_campo = "e28_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e28_empageforma == null ){ 
       $this->erro_sql = " Campo Forma de Pagamento nao Informado.";
       $this->erro_campo = "e28_empageforma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e28_empagetipo == null ){ 
       $this->erro_sql = " Campo Código da conta pagadora nao Informado.";
       $this->erro_campo = "e28_empagetipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e28_sequencial == "" || $e28_sequencial == null ){
       $result = db_query("select nextval('empageformacgm_e28_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empageformacgm_e28_sequencial_seq do campo: e28_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e28_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from empageformacgm_e28_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e28_sequencial)){
         $this->erro_sql = " Campo e28_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e28_sequencial = $e28_sequencial; 
       }
     }
     if(($this->e28_sequencial == null) || ($this->e28_sequencial == "") ){ 
       $this->erro_sql = " Campo e28_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empageformacgm(
                                       e28_sequencial 
                                      ,e28_numcgm 
                                      ,e28_empageforma 
                                      ,e28_empagetipo 
                       )
                values (
                                $this->e28_sequencial 
                               ,$this->e28_numcgm 
                               ,$this->e28_empageforma 
                               ,$this->e28_empagetipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ultima forma de pagamento do cgm ($this->e28_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ultima forma de pagamento do cgm já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ultima forma de pagamento do cgm ($this->e28_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e28_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e28_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12288,'$this->e28_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2139,12288,'','".AddSlashes(pg_result($resaco,0,'e28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2139,12289,'','".AddSlashes(pg_result($resaco,0,'e28_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2139,12290,'','".AddSlashes(pg_result($resaco,0,'e28_empageforma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2139,12381,'','".AddSlashes(pg_result($resaco,0,'e28_empagetipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e28_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update empageformacgm set ";
     $virgula = "";
     if(trim($this->e28_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e28_sequencial"])){ 
       $sql  .= $virgula." e28_sequencial = $this->e28_sequencial ";
       $virgula = ",";
       if(trim($this->e28_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e28_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e28_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e28_numcgm"])){ 
       $sql  .= $virgula." e28_numcgm = $this->e28_numcgm ";
       $virgula = ",";
       if(trim($this->e28_numcgm) == null ){ 
         $this->erro_sql = " Campo Código do CGM nao Informado.";
         $this->erro_campo = "e28_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e28_empageforma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e28_empageforma"])){ 
       $sql  .= $virgula." e28_empageforma = $this->e28_empageforma ";
       $virgula = ",";
       if(trim($this->e28_empageforma) == null ){ 
         $this->erro_sql = " Campo Forma de Pagamento nao Informado.";
         $this->erro_campo = "e28_empageforma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e28_empagetipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e28_empagetipo"])){ 
       $sql  .= $virgula." e28_empagetipo = $this->e28_empagetipo ";
       $virgula = ",";
       if(trim($this->e28_empagetipo) == null ){ 
         $this->erro_sql = " Campo Código da conta pagadora nao Informado.";
         $this->erro_campo = "e28_empagetipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e28_sequencial!=null){
       $sql .= " e28_sequencial = $this->e28_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e28_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12288,'$this->e28_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e28_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2139,12288,'".AddSlashes(pg_result($resaco,$conresaco,'e28_sequencial'))."','$this->e28_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e28_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,2139,12289,'".AddSlashes(pg_result($resaco,$conresaco,'e28_numcgm'))."','$this->e28_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e28_empageforma"]))
           $resac = db_query("insert into db_acount values($acount,2139,12290,'".AddSlashes(pg_result($resaco,$conresaco,'e28_empageforma'))."','$this->e28_empageforma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e28_empagetipo"]))
           $resac = db_query("insert into db_acount values($acount,2139,12381,'".AddSlashes(pg_result($resaco,$conresaco,'e28_empagetipo'))."','$this->e28_empagetipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ultima forma de pagamento do cgm nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ultima forma de pagamento do cgm nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e28_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e28_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12288,'$e28_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2139,12288,'','".AddSlashes(pg_result($resaco,$iresaco,'e28_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2139,12289,'','".AddSlashes(pg_result($resaco,$iresaco,'e28_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2139,12290,'','".AddSlashes(pg_result($resaco,$iresaco,'e28_empageforma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2139,12381,'','".AddSlashes(pg_result($resaco,$iresaco,'e28_empagetipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from empageformacgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e28_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e28_sequencial = $e28_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ultima forma de pagamento do cgm nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e28_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ultima forma de pagamento do cgm nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e28_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e28_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empageformacgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e28_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageformacgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empageformacgm.e28_numcgm";
     $sql .= "      inner join empagetipo  on  empagetipo.e83_codtipo = empageformacgm.e28_empagetipo";
     $sql .= "      inner join empageforma  on  empageforma.e96_codigo = empageformacgm.e28_empageforma";
     $sql .= "      inner join saltes  on  saltes.k13_conta = empagetipo.e83_conta";
     $sql .= "      inner join empagemod  on  empagemod.e84_codmod = empagetipo.e83_codmod";
     $sql2 = "";
     if($dbwhere==""){
       if($e28_sequencial!=null ){
         $sql2 .= " where empageformacgm.e28_sequencial = $e28_sequencial "; 
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
   function sql_query_file ( $e28_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from empageformacgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($e28_sequencial!=null ){
         $sql2 .= " where empageformacgm.e28_sequencial = $e28_sequencial "; 
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