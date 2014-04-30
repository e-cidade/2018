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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptucalcpadraoconstr
class cl_iptucalcpadraoconstr { 
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
   var $j11_sequencial = 0; 
   var $j11_iptucalcpadrao = 0; 
   var $j11_matric = 0; 
   var $j11_idcons = 0; 
   var $j11_vlrcons = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j11_sequencial = int8 = Código 
                 j11_iptucalcpadrao = int8 = Calculo padrao 
                 j11_matric = int8 = Matrícula 
                 j11_idcons = int8 = Código da construção 
                 j11_vlrcons = float8 = Valor da construção 
                 ";
   //funcao construtor da classe 
   function cl_iptucalcpadraoconstr() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucalcpadraoconstr"); 
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
       $this->j11_sequencial = ($this->j11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j11_sequencial"]:$this->j11_sequencial);
       $this->j11_iptucalcpadrao = ($this->j11_iptucalcpadrao == ""?@$GLOBALS["HTTP_POST_VARS"]["j11_iptucalcpadrao"]:$this->j11_iptucalcpadrao);
       $this->j11_matric = ($this->j11_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j11_matric"]:$this->j11_matric);
       $this->j11_idcons = ($this->j11_idcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j11_idcons"]:$this->j11_idcons);
       $this->j11_vlrcons = ($this->j11_vlrcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j11_vlrcons"]:$this->j11_vlrcons);
     }else{
       $this->j11_sequencial = ($this->j11_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j11_sequencial"]:$this->j11_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j11_sequencial){ 
      $this->atualizacampos();
     if($this->j11_iptucalcpadrao == null ){ 
       $this->erro_sql = " Campo Calculo padrao nao Informado.";
       $this->erro_campo = "j11_iptucalcpadrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j11_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "j11_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j11_idcons == null ){ 
       $this->erro_sql = " Campo Código da construção nao Informado.";
       $this->erro_campo = "j11_idcons";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j11_vlrcons == null ){ 
       $this->erro_sql = " Campo Valor da construção nao Informado.";
       $this->erro_campo = "j11_vlrcons";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j11_sequencial == "" || $j11_sequencial == null ){
       $result = db_query("select nextval('iptucalcpadraoconstr_j11_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptucalcpadraoconstr_j11_sequencial_seq do campo: j11_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j11_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptucalcpadraoconstr_j11_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j11_sequencial)){
         $this->erro_sql = " Campo j11_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j11_sequencial = $j11_sequencial; 
       }
     }
     if(($this->j11_sequencial == null) || ($this->j11_sequencial == "") ){ 
       $this->erro_sql = " Campo j11_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucalcpadraoconstr(
                                       j11_sequencial 
                                      ,j11_iptucalcpadrao 
                                      ,j11_matric 
                                      ,j11_idcons 
                                      ,j11_vlrcons 
                       )
                values (
                                $this->j11_sequencial 
                               ,$this->j11_iptucalcpadrao 
                               ,$this->j11_matric 
                               ,$this->j11_idcons 
                               ,$this->j11_vlrcons 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Construções ($this->j11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Construções já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Construções ($this->j11_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j11_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j11_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11019,'$this->j11_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1899,11019,'','".AddSlashes(pg_result($resaco,0,'j11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1899,11020,'','".AddSlashes(pg_result($resaco,0,'j11_iptucalcpadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1899,11021,'','".AddSlashes(pg_result($resaco,0,'j11_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1899,11022,'','".AddSlashes(pg_result($resaco,0,'j11_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1899,11023,'','".AddSlashes(pg_result($resaco,0,'j11_vlrcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j11_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptucalcpadraoconstr set ";
     $virgula = "";
     if(trim($this->j11_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j11_sequencial"])){ 
       $sql  .= $virgula." j11_sequencial = $this->j11_sequencial ";
       $virgula = ",";
       if(trim($this->j11_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "j11_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j11_iptucalcpadrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j11_iptucalcpadrao"])){ 
       $sql  .= $virgula." j11_iptucalcpadrao = $this->j11_iptucalcpadrao ";
       $virgula = ",";
       if(trim($this->j11_iptucalcpadrao) == null ){ 
         $this->erro_sql = " Campo Calculo padrao nao Informado.";
         $this->erro_campo = "j11_iptucalcpadrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j11_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j11_matric"])){ 
       $sql  .= $virgula." j11_matric = $this->j11_matric ";
       $virgula = ",";
       if(trim($this->j11_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "j11_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j11_idcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j11_idcons"])){ 
       $sql  .= $virgula." j11_idcons = $this->j11_idcons ";
       $virgula = ",";
       if(trim($this->j11_idcons) == null ){ 
         $this->erro_sql = " Campo Código da construção nao Informado.";
         $this->erro_campo = "j11_idcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j11_vlrcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j11_vlrcons"])){ 
       $sql  .= $virgula." j11_vlrcons = $this->j11_vlrcons ";
       $virgula = ",";
       if(trim($this->j11_vlrcons) == null ){ 
         $this->erro_sql = " Campo Valor da construção nao Informado.";
         $this->erro_campo = "j11_vlrcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j11_sequencial!=null){
       $sql .= " j11_sequencial = $this->j11_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j11_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11019,'$this->j11_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j11_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1899,11019,'".AddSlashes(pg_result($resaco,$conresaco,'j11_sequencial'))."','$this->j11_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j11_iptucalcpadrao"]))
           $resac = db_query("insert into db_acount values($acount,1899,11020,'".AddSlashes(pg_result($resaco,$conresaco,'j11_iptucalcpadrao'))."','$this->j11_iptucalcpadrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j11_matric"]))
           $resac = db_query("insert into db_acount values($acount,1899,11021,'".AddSlashes(pg_result($resaco,$conresaco,'j11_matric'))."','$this->j11_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j11_idcons"]))
           $resac = db_query("insert into db_acount values($acount,1899,11022,'".AddSlashes(pg_result($resaco,$conresaco,'j11_idcons'))."','$this->j11_idcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j11_vlrcons"]))
           $resac = db_query("insert into db_acount values($acount,1899,11023,'".AddSlashes(pg_result($resaco,$conresaco,'j11_vlrcons'))."','$this->j11_vlrcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Construções nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Construções nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j11_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j11_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11019,'$j11_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1899,11019,'','".AddSlashes(pg_result($resaco,$iresaco,'j11_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1899,11020,'','".AddSlashes(pg_result($resaco,$iresaco,'j11_iptucalcpadrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1899,11021,'','".AddSlashes(pg_result($resaco,$iresaco,'j11_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1899,11022,'','".AddSlashes(pg_result($resaco,$iresaco,'j11_idcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1899,11023,'','".AddSlashes(pg_result($resaco,$iresaco,'j11_vlrcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucalcpadraoconstr
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j11_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j11_sequencial = $j11_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Construções nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j11_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Construções nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j11_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j11_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucalcpadraoconstr";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalcpadraoconstr ";
     $sql .= "      inner join iptuconstr  on  iptuconstr.j39_matric = iptucalcpadraoconstr.j11_matric and  iptuconstr.j39_idcons = iptucalcpadraoconstr.j11_idcons";
     $sql .= "      inner join iptucalcpadrao  on  iptucalcpadrao.j10_sequencial = iptucalcpadraoconstr.j11_iptucalcpadrao";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = iptuconstr.j39_matric";
     $sql .= "      inner join ruas  as a on   a.j14_codigo = iptuconstr.j39_codigo";
     $sql .= "      inner join iptubase  as b on   b.j01_matric = iptuconstr.j39_matric";
     $sql .= "      inner join iptubase  as c on   c.j01_matric = iptucalcpadrao.j10_matric";
     $sql2 = "";
     if($dbwhere==""){
       if($j11_sequencial!=null ){
         $sql2 .= " where iptucalcpadraoconstr.j11_sequencial = $j11_sequencial "; 
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
   function sql_query_file ( $j11_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucalcpadraoconstr ";
     $sql2 = "";
     if($dbwhere==""){
       if($j11_sequencial!=null ){
         $sql2 .= " where iptucalcpadraoconstr.j11_sequencial = $j11_sequencial "; 
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