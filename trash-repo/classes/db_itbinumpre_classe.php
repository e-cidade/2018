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

//MODULO: itbi
//CLASSE DA ENTIDADE itbinumpre
class cl_itbinumpre { 
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
   var $it15_guia = 0; 
   var $it15_numpre = 0; 
   var $it15_sequencial = 0; 
   var $it15_ultimaguia = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 it15_guia = int8 = Número da guia de ITBI 
                 it15_numpre = int8 = Numpre da guia de itbi 
                 it15_sequencial = int8 = Sequencial da Tabela 
                 it15_ultimaguia = bool = Ultima Guia Emitida 
                 ";
   //funcao construtor da classe 
   function cl_itbinumpre() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbinumpre"); 
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
       $this->it15_guia = ($this->it15_guia == ""?@$GLOBALS["HTTP_POST_VARS"]["it15_guia"]:$this->it15_guia);
       $this->it15_numpre = ($this->it15_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["it15_numpre"]:$this->it15_numpre);
       $this->it15_sequencial = ($this->it15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it15_sequencial"]:$this->it15_sequencial);
       $this->it15_ultimaguia = ($this->it15_ultimaguia == "" ?@$GLOBALS["HTTP_POST_VARS"]["it15_ultimaguia"]:$this->it15_ultimaguia);
     }else{
       $this->it15_sequencial = ($this->it15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it15_sequencial"]:$this->it15_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($it15_sequencial){ 
      $this->atualizacampos();
     if($this->it15_guia == null ){ 
       $this->erro_sql = " Campo Número da guia de ITBI nao Informado.";
       $this->erro_campo = "it15_guia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it15_numpre == null ){ 
       $this->erro_sql = " Campo Numpre da guia de itbi nao Informado.";
       $this->erro_campo = "it15_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it15_ultimaguia == null ){ 
       $this->erro_sql = " Campo Ultima Guia Emitida nao Informado.";
       $this->erro_campo = "it15_ultimaguia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($it15_sequencial == "" || $it15_sequencial == null ){
       $result = db_query("select nextval('itbinumpre_it15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbinumpre_it15_sequencial_seq do campo: it15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->it15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from itbinumpre_it15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $it15_sequencial)){
         $this->erro_sql = " Campo it15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it15_sequencial = $it15_sequencial; 
       }
     }
     if(($this->it15_sequencial == null) || ($this->it15_sequencial == "") ){ 
       $this->erro_sql = " Campo it15_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbinumpre(
                                       it15_guia 
                                      ,it15_numpre 
                                      ,it15_sequencial 
                                      ,it15_ultimaguia 
                       )
                values (
                                $this->it15_guia 
                               ,$this->it15_numpre 
                               ,$this->it15_sequencial 
                               ,'$this->it15_ultimaguia' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Numpre da guia de itbi ($this->it15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Numpre da guia de itbi já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Numpre da guia de itbi ($this->it15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->it15_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18284,'$this->it15_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,882,5566,'','".AddSlashes(pg_result($resaco,0,'it15_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,882,5567,'','".AddSlashes(pg_result($resaco,0,'it15_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,882,18284,'','".AddSlashes(pg_result($resaco,0,'it15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,882,18285,'','".AddSlashes(pg_result($resaco,0,'it15_ultimaguia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($it15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update itbinumpre set ";
     $virgula = "";
     if(trim($this->it15_guia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it15_guia"])){ 
       $sql  .= $virgula." it15_guia = $this->it15_guia ";
       $virgula = ",";
       if(trim($this->it15_guia) == null ){ 
         $this->erro_sql = " Campo Número da guia de ITBI nao Informado.";
         $this->erro_campo = "it15_guia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it15_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it15_numpre"])){ 
       $sql  .= $virgula." it15_numpre = $this->it15_numpre ";
       $virgula = ",";
       if(trim($this->it15_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre da guia de itbi nao Informado.";
         $this->erro_campo = "it15_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it15_sequencial"])){ 
       $sql  .= $virgula." it15_sequencial = $this->it15_sequencial ";
       $virgula = ",";
       if(trim($this->it15_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial da Tabela nao Informado.";
         $this->erro_campo = "it15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it15_ultimaguia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it15_ultimaguia"])){ 
       $sql  .= $virgula." it15_ultimaguia = '$this->it15_ultimaguia' ";
       $virgula = ",";
       if(trim($this->it15_ultimaguia) == null ){ 
         $this->erro_sql = " Campo Ultima Guia Emitida nao Informado.";
         $this->erro_campo = "it15_ultimaguia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($it15_sequencial!=null){
       $sql .= " it15_sequencial = $this->it15_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->it15_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18284,'$this->it15_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it15_guia"]) || $this->it15_guia != "")
           $resac = db_query("insert into db_acount values($acount,882,5566,'".AddSlashes(pg_result($resaco,$conresaco,'it15_guia'))."','$this->it15_guia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it15_numpre"]) || $this->it15_numpre != "")
           $resac = db_query("insert into db_acount values($acount,882,5567,'".AddSlashes(pg_result($resaco,$conresaco,'it15_numpre'))."','$this->it15_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it15_sequencial"]) || $this->it15_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,882,18284,'".AddSlashes(pg_result($resaco,$conresaco,'it15_sequencial'))."','$this->it15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["it15_ultimaguia"]) || $this->it15_ultimaguia != "")
           $resac = db_query("insert into db_acount values($acount,882,18285,'".AddSlashes(pg_result($resaco,$conresaco,'it15_ultimaguia'))."','$this->it15_ultimaguia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Numpre da guia de itbi nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Numpre da guia de itbi nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($it15_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($it15_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18284,'$it15_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,882,5566,'','".AddSlashes(pg_result($resaco,$iresaco,'it15_guia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,882,5567,'','".AddSlashes(pg_result($resaco,$iresaco,'it15_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,882,18284,'','".AddSlashes(pg_result($resaco,$iresaco,'it15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,882,18285,'','".AddSlashes(pg_result($resaco,$iresaco,'it15_ultimaguia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from itbinumpre
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($it15_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " it15_sequencial = $it15_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Numpre da guia de itbi nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Numpre da guia de itbi nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbinumpre";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $it15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbinumpre ";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbinumpre.it15_guia";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbi.it01_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = itbi.it01_coddepto";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql2 = "";
     if($dbwhere==""){
       if($it15_sequencial!=null ){
         $sql2 .= " where itbinumpre.it15_sequencial = $it15_sequencial "; 
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
   function sql_query_file ( $it15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbinumpre ";
     $sql2 = "";
     if($dbwhere==""){
       if($it15_sequencial!=null ){
         $sql2 .= " where itbinumpre.it15_sequencial = $it15_sequencial "; 
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
   /**
   * Função para busca de recibo vinculados a guia de itbi
   *
   * @param integer  $it15_guia
   * @param string   $campos
   * @param string   $ordem
   * @param string   $dbwhere
   * @return string - Consuta SQL
   */
   function sql_query_recibo($it15_guia=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from itbinumpre                                                                        ";
     $sql .= "      inner join itbi           on  itbi.it01_guia            = itbinumpre.it15_guia    ";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao ";
     $sql .= "      inner join recibo         on  recibo.k00_numpre         = itbinumpre.it15_numpre  ";
     $sql .= "      left  join arrepaga       on  arrepaga.k00_numpre       = itbinumpre.it15_numpre  ";
     $sql .= "      left  join caixa.arreidret on arreidret.k00_numpre = itbinumpre.it15_numpre       ";
     $sql .= "      left  join caixa.disbanco  on disbanco.idret = arreidret.idret                    ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($it15_guia!=null ){
         $sql2 .= " where itbinumpre.it15_guia = $it15_guia "; 
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