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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conplanoreduzcgm
class cl_conplanoreduzcgm { 
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
   var $c22_sequencial = 0; 
   var $c22_anousu = 0; 
   var $c22_reduz = 0; 
   var $c22_numcgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c22_sequencial = int4 = Código Sequencial 
                 c22_anousu = int4 = Ano da conta 
                 c22_reduz = int4 = Reduzido 
                 c22_numcgm = int4 = Código do CGM 
                 ";
   //funcao construtor da classe 
   function cl_conplanoreduzcgm() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conplanoreduzcgm"); 
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
       $this->c22_sequencial = ($this->c22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c22_sequencial"]:$this->c22_sequencial);
       $this->c22_anousu = ($this->c22_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["c22_anousu"]:$this->c22_anousu);
       $this->c22_reduz = ($this->c22_reduz == ""?@$GLOBALS["HTTP_POST_VARS"]["c22_reduz"]:$this->c22_reduz);
       $this->c22_numcgm = ($this->c22_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["c22_numcgm"]:$this->c22_numcgm);
     }else{
       $this->c22_sequencial = ($this->c22_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c22_sequencial"]:$this->c22_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c22_sequencial){ 
      $this->atualizacampos();
     if($this->c22_anousu == null ){ 
       $this->erro_sql = " Campo Ano da conta nao Informado.";
       $this->erro_campo = "c22_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c22_reduz == null ){ 
       $this->erro_sql = " Campo Reduzido nao Informado.";
       $this->erro_campo = "c22_reduz";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c22_numcgm == null ){ 
       $this->c22_numcgm = "0";
     }
     if($c22_sequencial == "" || $c22_sequencial == null ){
       $result = db_query("select nextval('conplanoreduzcgm_c22_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conplanoreduzcgm_c22_sequencial_seq do campo: c22_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c22_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conplanoreduzcgm_c22_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c22_sequencial)){
         $this->erro_sql = " Campo c22_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c22_sequencial = $c22_sequencial; 
       }
     }
     if(($this->c22_sequencial == null) || ($this->c22_sequencial == "") ){ 
       $this->erro_sql = " Campo c22_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conplanoreduzcgm(
                                       c22_sequencial 
                                      ,c22_anousu 
                                      ,c22_reduz 
                                      ,c22_numcgm 
                       )
                values (
                                $this->c22_sequencial 
                               ,$this->c22_anousu 
                               ,$this->c22_reduz 
                               ,$this->c22_numcgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cgm da conta ($this->c22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cgm da conta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cgm da conta ($this->c22_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c22_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c22_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11817,'$this->c22_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2043,11817,'','".AddSlashes(pg_result($resaco,0,'c22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2043,11818,'','".AddSlashes(pg_result($resaco,0,'c22_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2043,11819,'','".AddSlashes(pg_result($resaco,0,'c22_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2043,11820,'','".AddSlashes(pg_result($resaco,0,'c22_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c22_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conplanoreduzcgm set ";
     $virgula = "";
     if(trim($this->c22_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c22_sequencial"])){ 
       $sql  .= $virgula." c22_sequencial = $this->c22_sequencial ";
       $virgula = ",";
       if(trim($this->c22_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "c22_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c22_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c22_anousu"])){ 
       $sql  .= $virgula." c22_anousu = $this->c22_anousu ";
       $virgula = ",";
       if(trim($this->c22_anousu) == null ){ 
         $this->erro_sql = " Campo Ano da conta nao Informado.";
         $this->erro_campo = "c22_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c22_reduz)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c22_reduz"])){ 
       $sql  .= $virgula." c22_reduz = $this->c22_reduz ";
       $virgula = ",";
       if(trim($this->c22_reduz) == null ){ 
         $this->erro_sql = " Campo Reduzido nao Informado.";
         $this->erro_campo = "c22_reduz";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c22_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c22_numcgm"])){ 
        if(trim($this->c22_numcgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["c22_numcgm"])){ 
           $this->c22_numcgm = "0" ; 
        } 
       $sql  .= $virgula." c22_numcgm = $this->c22_numcgm ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($c22_sequencial!=null){
       $sql .= " c22_sequencial = $this->c22_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c22_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11817,'$this->c22_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c22_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,2043,11817,'".AddSlashes(pg_result($resaco,$conresaco,'c22_sequencial'))."','$this->c22_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c22_anousu"]))
           $resac = db_query("insert into db_acount values($acount,2043,11818,'".AddSlashes(pg_result($resaco,$conresaco,'c22_anousu'))."','$this->c22_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c22_reduz"]))
           $resac = db_query("insert into db_acount values($acount,2043,11819,'".AddSlashes(pg_result($resaco,$conresaco,'c22_reduz'))."','$this->c22_reduz',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c22_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,2043,11820,'".AddSlashes(pg_result($resaco,$conresaco,'c22_numcgm'))."','$this->c22_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cgm da conta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cgm da conta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c22_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c22_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11817,'$c22_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2043,11817,'','".AddSlashes(pg_result($resaco,$iresaco,'c22_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2043,11818,'','".AddSlashes(pg_result($resaco,$iresaco,'c22_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2043,11819,'','".AddSlashes(pg_result($resaco,$iresaco,'c22_reduz'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2043,11820,'','".AddSlashes(pg_result($resaco,$iresaco,'c22_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conplanoreduzcgm
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c22_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c22_sequencial = $c22_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cgm da conta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c22_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cgm da conta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c22_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c22_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conplanoreduzcgm";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conplanoreduzcgm ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = conplanoreduzcgm.c22_numcgm";
     $sql .= "      inner join conplanoreduz  on  conplanoreduz.c61_anousu = conplanoreduzcgm.c22_anousu and  conplanoreduz.c61_reduz = conplanoreduzcgm.c22_reduz";
     $sql .= "      inner join db_config  on  db_config.codigo = conplanoreduz.c61_instit";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = conplanoreduz.c61_codigo";
     $sql .= "      inner join conplano  as a on   a.c60_codcon = conplanoreduz.c61_codcon and   a.c60_anousu = conplanoreduz.c61_anousu";
     $sql .= "      inner join db_config  as b on   b.codigo = conplanoreduz.c61_instit";
     $sql .= "      inner join orctiporec  as c on   c.o15_codigo = conplanoreduz.c61_codigo";
     $sql .= "      inner join conplano  as d on   d.c60_codcon = conplanoreduz.c61_codcon and   d.c60_anousu = conplanoreduz.c61_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($c22_sequencial!=null ){
         $sql2 .= " where conplanoreduzcgm.c22_sequencial = $c22_sequencial "; 
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
   function sql_query_file ( $c22_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conplanoreduzcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($c22_sequencial!=null ){
         $sql2 .= " where conplanoreduzcgm.c22_sequencial = $c22_sequencial "; 
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